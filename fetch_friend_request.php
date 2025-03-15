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
    echo json_encode(['error' => 'You must log in first.']);
    exit;
}
$user_id = $_SESSION['user_id'];

// Fetch pending friend requests where the logged in user is the receiver
$sql = "SELECT fr.id as r_id,u.id, u.name, p.profile_picture
        FROM friends fr
        LEFT JOIN users u ON fr.user_id = u.id
        LEFT JOIN user_profiles p ON u.id = p.user_id
        WHERE fr.friend_id = ? AND fr.status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$friend_requests = [];
while($row = $result->fetch_assoc()) {
    $friend_requests[] = $row;
}
$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($friend_requests);
?>
