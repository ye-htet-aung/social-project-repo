<?php
session_start();
// Database Connection
$conn = new mysqli("localhost", "root", "", "social_app_db");

// Check if the database connection is working
if ($conn->connect_errno) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'You must be logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$request_id = $_POST['request_id'];

// Update the status of the friend request to 'accepted'
$sql = "UPDATE friends SET status ='accepted' WHERE id = ? AND (user_id = ? OR friend_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iii', $request_id, $user_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Error confirming friend request.']);
}
?>
