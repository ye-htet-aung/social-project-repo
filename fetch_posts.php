<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Connection
$conn = new mysqli("localhost", "root", "", "social_app_db");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$sql = "SELECT 
            posts.id, 
            posts.post_text, 
            posts.created_at, 
            users.id AS user_id, 
            users.name AS user_name, 
            images.image_url,
            (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS like_count,
            comments.id AS comment_id,
            comments.comment_text,
            comments.created_at AS comment_time,
            comment_users.id AS comment_user_id,
            comment_users.name AS comment_user_name
        FROM posts 
        LEFT JOIN users ON posts.user_id = users.id
        LEFT JOIN images ON posts.id = images.post_id
        LEFT JOIN likes ON posts.id = likes.post_id
        LEFT JOIN comments ON posts.id = comments.post_id
        LEFT JOIN users AS comment_users ON comments.user_id = comment_users.id
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
            "user_id" => $row['user_id'],
            "user_name" => $row['user_name'],
            "like_count" => $row['like_count'],  // Add likes count
            "comments" => [] // Prepare comments array
        ];
    }

    // Append images
    if (!empty($row['image_url'])) {
        $posts[$postId]["images"][] = $row['image_url'];
    }

    // Append comments
    if (!empty($row['comment_id'])) {
        $posts[$postId]["comments"][] = [
            "comment_id" => $row['comment_id'],
            "comment_text" => $row['comment_text'],
            "comment_time" => $row['comment_time'],
            "comment_user_id" => $row['comment_user_id'],
            "comment_user_name" => $row['comment_user_name']
        ];
    }
}

$conn->close();

// Output JSON
echo json_encode(array_values($posts), JSON_PRETTY_PRINT);
?>
