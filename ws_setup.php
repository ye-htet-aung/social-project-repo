<?php
use Workerman\Worker;
require_once __DIR__ . '/vendor/autoload.php';

// ✅ Database connection for storing messages
$db_name = "social_app_db";
$mysqli = new mysqli("localhost", "root", "", $db_name, 3307);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$ws_server = new Worker("websocket://0.0.0.0:8080");

$ws_server->onConnect = function($connection) {
    echo "New connection\n";
};

// Ensure the `chat_messages` table exists
$mysqli->select_db($db_name);
$table_sql = "CREATE TABLE IF NOT EXISTS chat_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT,
    receiver_id INT,
    message TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($mysqli->query($table_sql) === TRUE) {
    echo "Chat messages table checked and created successfully.";
} else {
    die("Error creating table: " . $mysqli->error);
}

// Store and broadcast messages to the correct receiver
$ws_server->onMessage = function($connection, $data) use ($ws_server, $mysqli) {
    $messageData = json_decode($data, true);
    
    if (!empty($messageData['message'])) {
        // Store message in the database
        $stmt = $mysqli->prepare("INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $messageData['sender_id'], $messageData['receiver_id'], $messageData['message']);
        $stmt->execute();
        $stmt->close();

        // Broadcast message to the intended receiver only
        foreach ($ws_server->connections as $client) {
            if ($client->id == $messageData['receiver_id']) {
                $client->send(json_encode([
                    "sender_id" => $messageData['sender_id'],
                    "message" => $messageData['message'],
                    "type" => "incoming"
                ]));
            }
        }
    }
};

$ws_server->onClose = function($connection) {
    echo "Connection closed\n";
};

Worker::runAll();
?>
