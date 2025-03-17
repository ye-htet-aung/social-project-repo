<?php
header("Content-Type: application/json");
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(["status" => "error", "error" => "User not logged in."]));
}

$sender_id = $_POST['sender_id'] ?? 0;
$receiver_id = $_POST['receiver_id'] ?? 0;

if ($sender_id == 0 || $receiver_id == 0) {
    die(json_encode(["status" => "error", "error" => "Invalid sender or receiver."]));
}

if (!isset($_FILES['image'])) {
    die(json_encode(["status" => "error", "error" => "No file uploaded."]));
}

$targetDir = "uploads/chat_images/";
if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

$filename = time() . "_" . basename($_FILES['image']['name']);
$targetFilePath = $targetDir . $filename;

$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$fileType = mime_content_type($_FILES['image']['tmp_name']);

if (!in_array($fileType, $allowedTypes)) {
    die(json_encode(["status" => "error", "error" => "Invalid file type."]));
}

if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
    $stmt = $mysqli->prepare("INSERT INTO chat_messages (sender_id, receiver_id, image, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $sender_id, $receiver_id, $targetFilePath);
    $stmt->execute();
    $stmt->close();
    echo json_encode(["status" => "success", "image_url" => $targetFilePath]);
} else {
    echo json_encode(["status" => "error", "error" => "Upload failed."]);
}

$mysqli->close();
?>
