<?php
require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;

// Store connected clients
$clients = [];

$ws_server = new Worker("websocket://0.0.0.0:8080");

$ws_server->onConnect = function ($connection) use (&$clients) {
    echo "New client connected: {$connection->id}\n";
    $clients[$connection->id] = $connection; // Save connection
};

$ws_server->onMessage = function ($connection, $data) use (&$clients) {
    // Assume message format: "username:message"
    list($username, $message) = explode(":", $data, 2);
    
    // Private messaging - example format: "username: Hello"
    foreach ($clients as $client) {
        if ($client->username === $username) {
            $client->send("Private message: $message");
            break;
        }
    }

    echo "Received message for $username: $message\n";
};


$ws_server->onClose = function ($connection) use (&$clients) {
    echo "Client disconnected: {$connection->id}\n";
    unset($clients[$connection->id]); // Remove connection
};

// Run the WebSocket server
Worker::runAll();
