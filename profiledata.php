<?php
include "database/config.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $birthday = trim($_POST["birthday"]);
    $current_location = trim($_POST["current_location"]);
    $hometown = trim($_POST["hometown"]);
    $education = trim($_POST["education"]);  // Fixed typo
    $bio = trim($_POST["bio"]);

    // Profile Picture Upload
    $profile_picture = "";
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            $allowed_types = ['jpg', 'jpeg', 'png'];
            $max_size = 2 * 1024 * 1024; // 2MB limit

            if (!in_array($imageFileType, $allowed_types)) {
                echo "Only JPG, JPEG, and PNG files are allowed.";
                exit;
            }

            if ($_FILES["profile_picture"]["size"] > $max_size) {
                echo "File size exceeds the 2MB limit.";
                exit;
            }

            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = $target_file;
            } else {
                echo "Error uploading file.";
                exit;
            }
        } else {
            echo "File is not an image.";
            exit;
        }
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO user_profiles (user_id, birthday, current_location, hometown, educatione, bio, profile_picture) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $user_id, $birthday, $current_location, $hometown, $education, $bio, $profile_picture);

    if ($stmt->execute()) {
        header("Location: Screen/Home.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Registration</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Birthday</label>
        <input type="date" name="birthday" required><br>
        
        <label>Current Location</label>
        <input type="text" name="current_location" required><br>
        
        <label>HomeTown</label>
        <input type="text" name="hometown" required><br>
        
        <label>Education</label>
        <input type="text" name="education" required><br>
        
        <label>Bio</label>
        <input type="text" name="bio"><br>
        
        <label>Profile Picture</label>
        <input type="file" name="profile_picture" accept="image/*" required><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>
