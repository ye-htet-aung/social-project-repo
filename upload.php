<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = [];
$upload_dir = "uploads/";
$allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'video/mp4', 'video/avi', 'video/mkv'];

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    die(json_encode(["error" => "User not authenticated"]));
}
$user_id = $_SESSION["user_id"];

// Database connection
$conn = new mysqli("localhost", "root", "", "social_app_db");
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}  
// Create Tables if Not Exists
$createPostTable = "CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    post_status VARCHAR(10) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);";

$createImageTable = "CREATE TABLE IF NOT EXISTS images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);";
$createVideoTable="CREATE TABLE IF NOT EXISTS videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    video_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id)
);";

$postLikeTableCreate = "CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);";

$postCommentTableCreate = "CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    comment_text TEXT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);";
$NotificationTableCreate="CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    notification_text TEXT NOT NULL,
    post_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (post_id) REFERENCES posts(id)
);";

$conn->query($createPostTable);
$conn->query($createImageTable);
$conn->query($postLikeTableCreate);
$conn->query($postCommentTableCreate);
$conn->query($createVideoTable);
$conn->query($NotificationTableCreate);


// Handle Post Data (Post Creation)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Post creation action
    if ($action === 'create_post') {
        $post_text = $_POST['post_text'] ?? '';
        $post_status = "active";

        // Insert the post into the database
        $stmt = $conn->prepare("INSERT INTO posts (user_id, post_text, post_status) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $post_text, $post_status);
        if (!$stmt->execute()) {
            die(json_encode(["error" => "Post insert failed: " . $stmt->error]));
        }
        $post_id = $stmt->insert_id;
        $stmt->close();

        // Handle Image Uploads only for the post
        $uploaded_files = [];
        if (!empty($_FILES['photos']['name'][0])) {
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['photos']['error'][$key] === 0) {
                    $file_name = time() . "_" . basename($_FILES['photos']['name'][$key]);
                    $file_type = $_FILES['photos']['type'][$key];
                    $file_path = $upload_dir.$file_name;

                    if (in_array($file_type, $allowed_types)) {
                        if (move_uploaded_file($tmp_name, $file_path)) {
                            // Store only the filename in the database
                            $stmt = $conn->prepare("INSERT INTO images (post_id, image_url) VALUES (?, ?)");
                            $stmt->bind_param("is", $post_id, $file_name);
                            if ($stmt->execute()) {
                                $uploaded_files[] = $file_name;
                            }
                            $stmt->close();
                        }
                    }
                }
            }
        }
        // Handle Video Uploads only for the post
        $uploaded_videos = [];
        if (!empty($_FILES['videos']['name'][0])) {
            foreach ($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['videos']['error'][$key] === 0) {
                    $file_name = time() . "_" . basename($_FILES['videos']['name'][$key]);
                    $file_type = $_FILES['videos']['type'][$key];
                    $file_path = $upload_dir.$file_name;

                    // Check if the file is a valid video type
                    if (in_array($file_type, ['video/mp4', 'video/avi', 'video/mkv'])) {
                        if (move_uploaded_file($tmp_name, $file_path)) {
                            // Store video filename in the database
                            $stmt = $conn->prepare("INSERT INTO videos (post_id, video_url) VALUES (?, ?)");
                            $stmt->bind_param("is", $post_id, $file_name);
                            if ($stmt->execute()) {
                                $uploaded_videos[] = $file_name;
                            }
                            $stmt->close();
                        }
                    }
                }
            }
        }

        // Return JSON response
        echo json_encode(["success" => true, "message" => "Post uploaded!", "images" => $uploaded_files]);
    }

    // Handle Comment Action (Do not upload images here)
    elseif ($action === 'comment') {
        $post_id = $_POST['post_id'] ?? '';
        $comment_text = $_POST['comment_text'] ?? '';

        if (empty($post_id) || empty($comment_text)) {
            die(json_encode(["error" => "Post ID and Comment text are required"]));
        }

        // Insert Comment
        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $post_id, $user_id, $comment_text);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Comment added successfully!"]);
        } else {
            echo json_encode(["error" => "Failed to add comment"]);
        }
        $stmt->close();
    }

    // Handle Like Action
// Handle Like Action
elseif ($action === 'like') {
    $post_id = $_POST['post_id'] ?? '';
    if (empty($post_id)) {
        die(json_encode(["error" => "Post ID is required"]));
    }

    // Check if user already liked the post
    $stmt = $conn->prepare("SELECT id FROM likes WHERE post_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User has already liked the post, so remove the like (unlike)
        $stmt = $conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $post_id, $user_id);
        if ($stmt->execute()) {
            // Return updated like count and liked status
            $like_count = getLikeCount($post_id);
            echo json_encode(["success" => true, "message" => "Post unliked successfully!", "is_liked" => false, "like_count" => $like_count]);
        } else {
            echo json_encode(["error" => "Failed to remove like"]);
        }
    } else {
        // User has not liked the post, so add the like
        $stmt = $conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $post_id, $user_id);
        if ($stmt->execute()) {
            // Return updated like count and liked status
            $like_count = getLikeCount($post_id);
            echo json_encode(["success" => true, "message" => "Post liked successfully!", "is_liked" => true, "like_count" => $like_count]);
        } else {
            echo json_encode(["error" => "Failed to like post"]);
        }
    }
    $stmt->close();
}
    // Handle Share Action
    elseif ($action === 'share') {
        $post_id = $_POST['post_id'] ?? '';
        if (empty($post_id)) {
            die(json_encode(["error" => "Post ID is required"]));
        }

        // Insert Share Record (You can add specific share logic here)
        $stmt = $conn->prepare("INSERT INTO shares (post_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $post_id, $user_id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Post shared successfully!"]);
        } else {
            echo json_encode(["error" => "Failed to share post"]);
        }
        $stmt->close();
    }

    // Handle Message Action (e.g., send a message)
    elseif ($action === 'message') {
        $receiver_id = $_POST['receiver_id'] ?? '';
        $message_text = $_POST['message_text'] ?? '';

        if (empty($receiver_id) || empty($message_text)) {
            die(json_encode(["error" => "Receiver ID and Message text are required"]));
        }

        // Insert Message
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $receiver_id, $message_text);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Message sent successfully!"]);
        } else {
            echo json_encode(["error" => "Failed to send message"]);
        }
        $stmt->close();
    }
    // Function to get the like count for a post
function getLikeCount($postId) {
    global $conn;
    $query = "SELECT COUNT(*) FROM likes WHERE post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    return $count;
}
}

$conn->close();
?>
