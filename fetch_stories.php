<?php
header('Content-Type: application/json');
include 'database/config.php';

// Database Connection
$conn = new mysqli("localhost", "root", "", "social_app_db");

// Check if the database connection is working
if ($conn->connect_errno) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Create stories table if it doesn't exist
$sql_create = "CREATE TABLE IF NOT EXISTS stories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    video_url VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_stories_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if (!$conn->query($sql_create)) {
    echo json_encode(["error" => "Error creating stories table: " . $conn->error]);
    exit;
}

// Fetch stories
$sql = "SELECT s.id, s.video_url, s.image_url, s.created_at, u.name as user_name,p.profile_picture as profile_pic
        FROM stories s
        LEFT JOIN users u ON s.user_id = u.id
        LEFT JOIN user_profiles p ON s.user_id=p.user_id
        ORDER BY s.created_at DESC";

$result = $conn->query($sql);

// Check if query failed
if (!$result) {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit;
}

$stories = [];
while ($row = $result->fetch_assoc()) {
    $stories[] = $row;
}

// Check if the response is empty
if (empty($stories)) {
    echo json_encode(["message" => "No stories found."]);
} else {
    echo json_encode($stories, JSON_PRETTY_PRINT);
}

$conn->close();
?>
