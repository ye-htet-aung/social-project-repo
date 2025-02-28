<?php
use Workerman\Worker;
require_once __DIR__ . '/vendor/autoload.php';

$ws_server = new Worker("websocket://0.0.0.0:8080");

$ws_server->onConnect = function($connection) {
    echo "New connection\n";
};

$ws_server->onMessage = function($connection, $data) use ($ws_server) {
    $messageData = json_decode($data, true);
    if (!empty($messageData['message'])) {
        foreach ($ws_server->connections as $client) {
            $client->send(json_encode([
                "message" => $messageData['message'],
                "type" => $connection->id === $client->id ? "outgoing" : "incoming"
            ]));
        }
    }
};

$ws_server->onClose = function($connection) {
    echo "Connection closed\n";
};

Worker::runAll();
