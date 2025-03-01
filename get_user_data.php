<?php
session_start();
header("Content-Type: application/json");

// ✅ Secure Database Connection
$mysqli = new mysqli("localhost", "root", "", "social_app_db", 3307);

// ✅ Check for connection errors
if ($mysqli->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $mysqli->connect_error]));
}

// ✅ Assume the current logged-in user is stored in the session
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["error" => "User not logged in"]));
}

$sender_id = $_SESSION['user_id']; // Logged-in user

// ✅ Get the last chatted user (receiver)
$sql = "SELECT receiver_id FROM chat_messages WHERE sender_id = ? 
        UNION 
        SELECT sender_id FROM chat_messages WHERE receiver_id = ? 
        ORDER BY timestamp DESC LIMIT 1";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $sender_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$receiver_id = $row ? $row['receiver_id'] : null;

// ✅ Close database connection
$stmt->close();
$mysqli->close();

// ✅ Return JSON Response
echo json_encode([
    "sender_id" => $sender_id,
    "receiver_id" => $receiver_id
]);
