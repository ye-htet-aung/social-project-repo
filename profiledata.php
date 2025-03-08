<?php
include "database/config.php";

session_start();
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    
    echo "Please log in first.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $birthday = $_POST["birthday"];
    $current_location = $_POST["current_location"];
    $hometown = $_POST["hometown"];
    $educatione = $_POST["educatione"];
    $bio = $_POST["bio"];

    // Profile Picture Upload
    $profile_picture = "";
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";  // Correct the folder name if needed
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            // Validate the file type and size
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
                $profile_picture = $target_file; // Store file path
            } else {
                echo "Error uploading file.";
                exit;
            }
        } else {
            echo "File is not an image.";
            exit;
        }
    }

    }

    $sql = "INSERT INTO user_profiles (user_id, birthday, current_location, hometown, educatione, bio, profile_picture) 
            VALUES ('$user_id', '$birthday', '$current_location', '$hometown', '$educatione', '$bio', '$profile_picture')";

    if ($con->query($sql) === TRUE) {
        header("Location: Screen/Home.php");
        echo "Registration Successful!";
    } else {
        echo "Error: " . $con->error;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
        <label for="">Birthday</label>
        <input type="date" name="birthday" required><br>
        <label for="">Current Location</label>
        <input type="text" name="current_location" required>
        <label for="">HomeTown</label>
        <input type="text" name="hometown" required>
        <label for="">Education</label>
        <input type="text" name="educatione" required>
        <label for="">Bio</label>
        <input type="text" name="bio" >
        <input type="file" name="profile_picture" accept="image/*" required>
       <button type="submit">Login</button>
    </form>
</body>
</body>
</html>