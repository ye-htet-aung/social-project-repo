<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = [];
$upload_dir = "uploads/";
$allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];

// Create upload directory if it doesn't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Connect to Database
$conn = new mysqli("localhost", "root", "", "social_app_db");

// Check Connection
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

$conn->query($createPostTable);
$conn->query($createImageTable);

// Retrieve Post Data
$post_text = $_POST['post_text'] ?? '';
$user_id = 1; // This should come from session/authentication
$post_status = "active";

// Insert Post into Database
$stmt = $conn->prepare("INSERT INTO posts (user_id, post_text, post_status) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $post_text, $post_status);
$stmt->execute();
$post_id = $stmt->insert_id;
$stmt->close();

$uploaded_files = [];

// Handle Image Uploads
if (!empty($_FILES['photos']['name'][0])) {
    foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['photos']['error'][$key] === 0) {
            $file_name = basename($_FILES['photos']['name'][$key]);
            $file_type = $_FILES['photos']['type'][$key];
            $file_path = $upload_dir . time() . "_" . $file_name;

            if (in_array($file_type, $allowed_types)) {
                if (move_uploaded_file($tmp_name, $file_path)) {
                    // Insert Image Path into Database
                    $stmt = $conn->prepare("INSERT INTO images (post_id, image_url) VALUES (?, ?)");
                    $stmt->bind_param("is", $post_id, $file_path);
                    $stmt->execute();
                    $stmt->close();

                    $uploaded_files[] = $file_path;
                }
            }
        }
    }
}

$conn->close();
echo json_encode(["success" => true, "message" => "Post uploaded!", "images" => $uploaded_files]);
?>
