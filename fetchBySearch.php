<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "social_app_db");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

// Fetch matching users
$userSql = "SELECT 
                users.id AS user_id, 
                users.name AS user_name,
                COALESCE(user_profiles.profile_picture, 'default.png') AS user_profile
            FROM users 
            LEFT JOIN user_profiles ON users.id = user_profiles.user_id
            WHERE users.name LIKE '%$query%'";

$userResult = $conn->query($userSql);
$users = [];

while ($row = $userResult->fetch_assoc()) {
    $users[] = $row;
}

// Fetch matching posts
$postSql = "SELECT 
                posts.id AS post_id,
                posts.post_text, 
                posts.created_at, 
                users.name AS user_name, 
                COALESCE(user_profiles.profile_picture, 'default.png') AS user_profile,
                (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS like_count,
                (SELECT GROUP_CONCAT(images.image_url) FROM images WHERE images.post_id = posts.id) AS image_urls,
                (SELECT GROUP_CONCAT(videos.video_url) FROM videos WHERE videos.post_id = posts.id) AS video_urls
            FROM posts
            LEFT JOIN users ON posts.user_id = users.id
            LEFT JOIN user_profiles ON users.id = user_profiles.user_id
            WHERE posts.post_text LIKE '%$query%' OR users.name LIKE '%$query%'
            ORDER BY posts.created_at DESC";

$postResult = $conn->query($postSql);
$posts = [];

while ($row = $postResult->fetch_assoc()) {
    // Convert images & videos to array
    $row['image_urls'] = isset($row['image_urls']) ? explode(',', $row['image_urls']) : [];
    $row['video_urls'] = isset($row['video_urls']) ? explode(',', $row['video_urls']) : [];
    $posts[] = $row;
}

// Send the JSON response
$response = ['users' => $users, 'posts' => $posts];
if (empty($users) && empty($posts)) {
    $response['message'] = "No results found for '$query'";
}

echo json_encode($response, JSON_PRETTY_PRINT);
$conn->close();
?>
