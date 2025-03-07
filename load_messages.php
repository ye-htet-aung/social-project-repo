<?php
header("Content-Type: application/json");

// ✅ Secure Database Connection
$mysqli = new mysqli("localhost", "root", "", "social_app_db", 3307);

// ✅ Proper Connection Error Handling
if ($mysqli->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $mysqli->connect_error]));
}

// ✅ Validate and Sanitize User Input
$sender_id = isset($_GET['sender_id']) ? (int) $_GET['sender_id'] : 0;
$receiver_id = isset($_GET['receiver_id']) ? (int) $_GET['receiver_id'] : 0;

// ✅ Ensure IDs Are Valid
if ($sender_id === 0 || $receiver_id === 0) {
    die(json_encode(["error" => "Invalid sender or receiver ID."]));
}

// ✅ Use Prepared Statement to Prevent SQL Injection
$sql = "SELECT id, sender_id, receiver_id, message, timestamp FROM chat_messages 
        WHERE (sender_id = ? AND receiver_id = ?) 
        OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY timestamp ASC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

// ✅ Close Database Connection
$stmt->close();
$mysqli->close();

// ✅ Return JSON Response
echo json_encode($messages);
?>
