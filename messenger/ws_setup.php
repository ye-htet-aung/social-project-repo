<?php
use Workerman\Worker;
require_once __DIR__ . '/vendor/autoload.php';

// ✅ Database connection
$db_name = "social_app_db";
$mysqli = new mysqli("localhost", "root", "", $db_name, 3306);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// ✅ WebSocket Server
$ws_server = new Worker("websocket://0.0.0.0:9090");

// ✅ Store active connections with user IDs
$clients = [];

$ws_server->onConnect = function($connection) {
    echo "New connection\n";
};



// ✅ Handle incoming messages
$ws_server->onMessage = function($connection, $data) use ($ws_server, $mysqli, &$clients) {
    $messageData = json_decode($data, true);

    if (isset($messageData['sender_id']) && isset($messageData['receiver_id'])) {
        // ✅ Store user connection
        $clients[$messageData['sender_id']] = $connection;

        // ✅ Store message in the database
        $stmt = $mysqli->prepare("INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $messageData['sender_id'], $messageData['receiver_id'], $messageData['message']);
        $stmt->execute();
        $stmt->close();

        // ✅ Broadcast to receiver if they are online
        if (isset($clients[$messageData['receiver_id']])) {
            $clients[$messageData['receiver_id']]->send(json_encode([
                "sender_id" => $messageData['sender_id'],
                "message" => $messageData['message'],
                "type" => "incoming"
            ]));
        }

        // ✅ Send the message back to sender for confirmation
        $connection->send(json_encode([
            "sender_id" => $messageData['sender_id'],
            "message" => $messageData['message'],
            "type" => "outgoing"
        ]));
    }
};

// ✅ Handle disconnect
$ws_server->onClose = function($connection) use (&$clients) {
    foreach ($clients as $user_id => $client) {
        if ($client === $connection) {
            unset($clients[$user_id]);
            break;
        }
    }
    echo "Connection closed\n";
};

// ✅ Run the server
Worker::runAll();
?>