<?php
use Workerman\Worker;
require_once __DIR__ . '/vendor/autoload.php';

// ✅ Database connection for storing messages
$mysqli = new mysqli("localhost", "root", "", "social_app_db", 3307);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$ws_server = new Worker("websocket://0.0.0.0:8080");

$ws_server->onConnect = function($connection) {
    echo "New connection\n";
};

$ws_server->onMessage = function($connection, $data) use ($ws_server, $mysqli) {
    $messageData = json_decode($data, true);
    
    if (!empty($messageData['message'])) {
        // ✅ Store message in the database
        $stmt = $mysqli->prepare("INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $messageData['sender_id'], $messageData['receiver_id'], $messageData['message']);
        $stmt->execute();
        $stmt->close();

        // ✅ Broadcast message to all connected clients
        foreach ($ws_server->connections as $client) {
            $client->send(json_encode([
                "sender_id" => $messageData['sender_id'],
                "message" => $messageData['message'],
                "type" => ($connection->id === $client->id) ? "outgoing" : "incoming"
            ]));
        }
    }
};

$ws_server->onClose = function($connection) {
    echo "Connection closed\n";
};

Worker::runAll();
?>
