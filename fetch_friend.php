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
// $user_id = $_SESSION['user_id'];
$user_id = $_SESSION['primary_id'];
$sql = "SELECT fr.id as r_id, u.id, u.name, p.profile_picture
        FROM friends fr
        LEFT JOIN users u ON (fr.user_id = u.id OR fr.friend_id = u.id)
        LEFT JOIN user_profiles p ON u.id = p.user_id
        WHERE (fr.friend_id = ? OR fr.user_id = ?) AND fr.status = 'accepted' 
        AND u.id != ?"; // Exclude self

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$friends = [];
while($row = $result->fetch_assoc()) {
    $friends[] = $row;
}
$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($friends);
?>
