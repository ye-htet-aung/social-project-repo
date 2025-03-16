<?php
session_start(); 
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "social_app_db");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';
$current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
// Initialize response array
$response = [
    'users' => [],
    'posts' => []
];

// Prepared statement to fetch matching users
$userSql = "SELECT 
                users.id AS user_id, 
                users.name AS user_name,
                COALESCE(user_profiles.profile_picture, 'default.png') AS user_profile
            FROM users 
            LEFT JOIN user_profiles ON users.id = user_profiles.user_id
            WHERE LOWER(users.name) LIKE LOWER(?)";
$userStmt = $conn->prepare($userSql);
$likeQuery = "%" . $query . "%";
$userStmt->bind_param("s", $likeQuery);
$userStmt->execute();
$userResult = $userStmt->get_result();
$users = [];

while ($row = $userResult->fetch_assoc()) {
    $users[] = [
        'user_id' => $row['user_id'],
        'user_name' => $row['user_name'],
        'user_profile' => $row['user_profile']
    ];
}

// Add users to response
$response['users'] = $users;

// Prepared statement to fetch matching posts
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
        WHERE LOWER(posts.post_text) LIKE LOWER(?)
        ORDER BY posts.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $likeQuery);
$stmt->execute();
$result = $stmt->get_result();
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
            "profile_image" => $row['user_profile'],
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
                   WHERE comments.post_id = ?";
    $commentStmt = $conn->prepare($commentSql);
    $commentStmt->bind_param("i", $postId);
    $commentStmt->execute();
    $commentResult = $commentStmt->get_result();
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

// Add posts to response
$response['posts'] = array_values($posts);

// Close the connection
$conn->close();

// Output the final JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>
