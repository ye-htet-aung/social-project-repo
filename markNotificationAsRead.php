<?php
session_start();
header('Content-Type: application/json');

// Database Connection
$conn = new mysqli("localhost", "root", "", "social_app_db");

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

if (isset($_GET['id'])) {
    $notificationId = $_GET['id'];

    // Update notification as read in the database
    $query = "UPDATE notifications SET is_read = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $notificationId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Notification not found or already read']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid notification ID']);
}

$conn->close();
?>
