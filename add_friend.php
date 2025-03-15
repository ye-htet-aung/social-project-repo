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
$friend_id = $_POST['friend_id'];

// Ensure that the friend ID is valid and not the same as the logged-in user
if ($friend_id == $user_id) {
    echo json_encode(['error' => 'You cannot add yourself as a friend.']);
    exit;
}

// Check if the friend request already exists (either pending from the current user or the reverse from the other user)
$sql_check = "SELECT * FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode(['error' => 'Friend request already sent or pending.']);
    exit;
}

// Insert the friend request into the database
$sql = "INSERT INTO friends(user_id, friend_id, status) VALUES (?, ?, 'pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $user_id, $friend_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Error sending friend request.']);
}
?>
