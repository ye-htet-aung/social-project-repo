<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Connection
$conn = new mysqli("localhost", "root", "", "social_app_db");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Fetch Posts and Images
$sql = "SELECT posts.id, posts.post_text, posts.created_at, images.image_url 
        FROM posts 
        LEFT JOIN images ON posts.id = images.post_id 
        ORDER BY posts.created_at DESC";

$result = $conn->query($sql);

$posts = [];
while ($row = $result->fetch_assoc()) {
    $postId = $row['id'];

    if (!isset($posts[$postId])) {
        $posts[$postId] = [
            "id" => $postId,
            "post_text" => $row['post_text'],
            "created_at" => $row['created_at'],
            "images" => [],
        ];
    }

    if ($row['image_url']) {
        $posts[$postId]["images"][] = $row['image_url'];
    }
}

$conn->close();

echo json_encode(array_values($posts));
?>
