<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Connection
$conn = new mysqli("localhost", "root", "", "social_app_db");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Query to fetch posts with likes count, comments, images, and videos
$sql = "SELECT 
            posts.id, 
            posts.post_text, 
            posts.created_at, 
            users.id AS user_id, 
            users.name AS user_name,
            user_profiles.profile_picture AS user_profile,
            (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS like_count,
            (SELECT GROUP_CONCAT(images.image_url) FROM images WHERE images.post_id = posts.id) AS image_urls,
            (SELECT GROUP_CONCAT(videos.video_url) FROM videos WHERE videos.post_id = posts.id) AS video_urls
        FROM posts 
        LEFT JOIN users ON posts.user_id = users.id
        LEFT JOIN user_profiles ON users.id=user_profiles.user_id
        ORDER BY posts.created_at DESC";

$result = $conn->query($sql);

$posts = [];
while ($row = $result->fetch_assoc()) {
    $postId = $row['id'];

    // Initialize post entry if it does not exist
    if (!isset($posts[$postId])) {
        $posts[$postId] = [
            "id" => $postId,
            "post_text" => $row['post_text'],
            "created_at" => $row['created_at'],
            "user_id" => $row['user_id'],
            "user_name" => $row['user_name'],
            "profile_image"=>$row['user_profile'],
            "like_count" => $row['like_count'],
            "images" => [],
            "comments" => [],
            "videos" => []
        ];
    }
    
    // Add images to the post (if any)
    if (!empty($row['image_urls'])) {
        $posts[$postId]["images"] = explode(",", $row['image_urls']);
    }

    // Add videos to the post (if any)
    if (!empty($row['video_urls'])) {
        $posts[$postId]["videos"] = explode(",", $row['video_urls']);
    }

    // Fetch comments for the current post
    $commentSql = "SELECT 
                    comments.id AS comment_id,
                    comments.comment_text,
                    comments.created_at AS comment_time,
                    comment_users.id AS comment_user_id,
                    comment_users.name AS comment_user_name
                   FROM comments
                   LEFT JOIN users AS comment_users ON comments.user_id = comment_users.id
                   WHERE comments.post_id = $postId";

    $commentResult = $conn->query($commentSql);
    $comments = [];
    while ($comment = $commentResult->fetch_assoc()) {
        $comments[] = [
            "comment_id" => $comment['comment_id'],
            "comment_text" => $comment['comment_text'],
            "comment_time" => $comment['comment_time'],
            "comment_user_id" => $comment['comment_user_id'],
            "comment_user_name" => $comment['comment_user_name']
        ];
    }

    // Merge comments into the post
    $posts[$postId]["comments"] = $comments;
}

// Close the connection
$conn->close();

// Output the final JSON response
echo json_encode(array_values($posts), JSON_PRETTY_PRINT);
?>
