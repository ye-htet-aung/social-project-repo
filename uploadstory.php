<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Database connection
$conn = new mysqli("localhost", "root", "", "social_app_db");
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

$uploadDir = 'uploads/stories/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Create the stories table if it doesn't exist
$createStoriesTable = "
    CREATE TABLE IF NOT EXISTS stories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        image_url VARCHAR(255) DEFAULT NULL,
        video_url VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );
";
$conn->query($createStoriesTable);

// Handle Post Data (Post Creation)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && filter_input(INPUT_POST, 'action') === 'create_post') {
    $image_url = null; 
    $video_url = null;

    // Handle Video Upload
    if (!empty($_FILES['videos']['name'][0])) {
        $videoFile = $_FILES['videos'];
        $videoTmpName = $videoFile['tmp_name'][0];
        $videoFileType = strtolower(pathinfo($videoFile['name'][0], PATHINFO_EXTENSION));

        $allowedVideoTypes = ['mp4', 'mov', 'avi', 'mkv'];
        $maxVideoSize = 10 * 1024 * 1024  *1024;

        if (!in_array($videoFileType, $allowedVideoTypes) || $videoFile['size'][0] > $maxVideoSize) {
            echo json_encode(['success' => false, 'error' => 'Invalid video format or size too large.']);
            exit;
        }

        $newVideoName = uniqid('story_vid_', true) . '.' . $videoFileType;
        $destination = realpath($uploadDir) . DIRECTORY_SEPARATOR . $newVideoName;

        if (!move_uploaded_file($videoTmpName, $destination)) {
            echo json_encode(['success' => false, 'error' => 'Error uploading video.']);
            exit;
        }

        $video_url = 'uploads/stories/' . $newVideoName;
    }

    // Handle Image Upload (Optional, can be added later if needed)
    if (!empty($_FILES['images']['name'][0])) {
        $imageFile = $_FILES['images'];
        $imageTmpName = $imageFile['tmp_name'][0];
        $imageFileType = strtolower(pathinfo($imageFile['name'][0], PATHINFO_EXTENSION));

        $allowedImageTypes = ['jpeg', 'png', 'jpg'];
        $maxImageSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($imageFileType, $allowedImageTypes) || $imageFile['size'][0] > $maxImageSize) {
            echo json_encode(['success' => false, 'error' => 'Invalid image format or size too large.']);
            exit;
        }

        $newImageName = uniqid('story_img_', true) . '.' . $imageFileType;
        $destination = realpath($uploadDir) . DIRECTORY_SEPARATOR . $newImageName;

        if (!move_uploaded_file($imageTmpName, $destination)) {
            echo json_encode(['success' => false, 'error' => 'Error uploading image.']);
            exit;
        }

        $image_url = 'uploads/stories/' . $newImageName;
    }

    // Insert story into the database
    $stmt = $conn->prepare("INSERT INTO stories (user_id, image_url, video_url) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $image_url, $video_url);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Story uploaded successfully!']);
    } else {
        error_log("MySQL Error: " . $stmt->error);
        echo json_encode(['success' => false, 'error' => 'Database error occurred.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method or action']);
}

$conn->close();
?>
