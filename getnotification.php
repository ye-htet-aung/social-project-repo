<?php
session_start();
header('Content-Type: application/json');

// Database Connection
$conn = new mysqli("localhost", "root", "", "social_app_db");

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit; // Stop further execution
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;  // Ensure no further code is executed
}

$user_id = $_SESSION['user_id'];

$query = "SELECT n.id as id, n.notification_text as notification_text, n.created_at as created_at, n.is_read as is_read, 
          x.profile_picture as profile_picture, u.name as username
FROM notifications n
LEFT JOIN posts p ON n.post_id = p.id
LEFT JOIN users u ON n.user_id = u.id
LEFT JOIN user_profiles x ON n.user_id = x.user_id
WHERE n.user_id !=$user_id
ORDER BY n.created_at DESC;";

// Prepare and execute the query
if ($stmt = $conn->prepare($query)) {
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            'id' => $row['id'],
            'notification_text' => $row['notification_text'],
            'created_at' => $row['created_at'],
            'is_read' => $row['is_read'],
            'profile_image_url' => $row['profile_picture'],
            'username' => $row['username'],
        ];
    }

    // Return the notifications as JSON
    // echo json_encode(['notifications' => $notifications]);
    // echo json_encode($notifications, JSON_PRETTY_PRINT);
    echo json_encode(["notifications" => $notifications], JSON_PRETTY_PRINT);



    $stmt->close();
} else {
    echo json_encode(["error" => "Failed to prepare the query"]);
}

$conn->close();
?>
