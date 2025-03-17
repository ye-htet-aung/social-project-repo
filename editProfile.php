<?php
include "database/config.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the current profile data (if available)
$stmt = $con->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc();
} else {
    // If no profile exists, initialize with empty values.
    $profile = [
        "birthday"         => "",
        "gender"           => "",
        "current_location" => "",
        "hometown"         => "",
        "educatione"       => "",
        "bio"              => "",
        "profile_picture"  => "",
        "background"       => ""
    ];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $birthday = trim($_POST["birthday"]);
    $current_location = trim($_POST["current_location"]);
    $hometown = trim($_POST["hometown"]);
    $education = trim($_POST["education"]);  // Note: your DB column is named 'educatione'
    $bio = trim($_POST["bio"]);
    $gender = trim($_POST["gender"]);

    // Process profile picture update if a new file was uploaded
    $profile_picture = $profile['profile_picture']; // retain existing if no new file
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            $allowed_types = ['jpg', 'jpeg', 'png'];
            $max_size = 2 * 1024 * 1024; // 2MB limit

            if (!in_array($imageFileType, $allowed_types)) {
                echo "Only JPG, JPEG, and PNG files are allowed for profile picture.";
                exit;
            }
            if ($_FILES["profile_picture"]["size"] > $max_size) {
                echo "Profile picture file size exceeds the 2MB limit.";
                exit;
            }
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = $target_file;
            } else {
                echo "Error uploading profile picture.";
                exit;
            }
        } else {
            echo "Profile picture file is not a valid image.";
            exit;
        }
    }

    // Process background picture update if a new file was uploaded
    $background_picture = $profile['background']; // retain existing if no new file
    if (isset($_FILES['background_picture']) && $_FILES['background_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["background_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["background_picture"]["tmp_name"]);
        if ($check !== false) {
            $allowed_types = ['jpg', 'jpeg', 'png'];
            $max_size = 2 * 1024 * 1024; // 2MB limit

            if (!in_array($imageFileType, $allowed_types)) {
                echo "Only JPG, JPEG, and PNG files are allowed for background picture.";
                exit;
            }
            if ($_FILES["background_picture"]["size"] > $max_size) {
                echo "Background picture file size exceeds the 2MB limit.";
                exit;
            }
            if (move_uploaded_file($_FILES["background_picture"]["tmp_name"], $target_file)) {
                $background_picture = $target_file;
            } else {
                echo "Error uploading background picture.";
                exit;
            }
        } else {
            echo "Background picture file is not a valid image.";
            exit;
        }
    }

    // Update the profile data in the database
    $stmt = $con->prepare("UPDATE user_profiles 
                           SET birthday = ?, gender = ?, current_location = ?, hometown = ?, educatione = ?, bio = ?, profile_picture = ?, background = ? 
                           WHERE user_id = ?");
    $stmt->bind_param("ssssssssi", $birthday, $gender, $current_location, $hometown, $education, $bio, $profile_picture, $background_picture, $user_id);

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
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/profiledata.css">
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <h2>Edit Your Profile</h2>

        <label for="birthday">Birthday</label>
        <input type="date" name="birthday" id="birthday" value="<?php echo htmlspecialchars($profile['birthday']); ?>" required><br>

        <fieldset>
            <legend>Gender</legend>
            <label for="male">Male</label>
            <input type="radio" name="gender" id="male" value="male" <?php if ($profile['gender'] == 'male') echo 'checked'; ?>>
            <label for="female">Female</label>
            <input type="radio" name="gender" id="female" value="female" <?php if ($profile['gender'] == 'female') echo 'checked'; ?>>
        </fieldset>

        <label for="current_location">Current Location</label>
        <input type="text" name="current_location" id="current_location" value="<?php echo htmlspecialchars($profile['current_location']); ?>" required><br>
        
        <label for="hometown">Hometown</label>
        <input type="text" name="hometown" id="hometown" value="<?php echo htmlspecialchars($profile['hometown']); ?>" required><br>
        
        <label for="education">Education</label>
        <input type="text" name="education" id="education" value="<?php echo htmlspecialchars($profile['educatione']); ?>" required><br>
        
        <label for="bio">Write something to describe you</label>
        <input type="text" name="bio" id="bio" value="<?php echo htmlspecialchars($profile['bio']); ?>"><br>
        
        <label for="profile_picture">Profile Picture</label>
        <input type="file" name="profile_picture" id="profile_picture" accept="image/*"><br>
        <?php if (!empty($profile['profile_picture'])): ?>
            <img src="<?php echo htmlspecialchars($profile['profile_picture']); ?>" alt="Current Profile Picture" width="100"><br>
        <?php endif; ?>
        
        <label for="background_picture">Background Picture</label>
        <input type="file" name="background_picture" id="background_picture" accept="image/*"><br>
        <?php if (!empty($profile['background'])): ?>
            <img src="<?php echo htmlspecialchars($profile['background']); ?>" alt="Current Background Picture" width="100"><br>
        <?php endif; ?>
        
        <input type="submit" value="Save Changes">
    </form>

    <script>
        // Optionally, you can include your language selector functionality here.
        document.addEventListener("DOMContentLoaded", function () {
            const languageSelect = document.getElementById('language-select');
            if(languageSelect){
                // Add language selector logic if needed.
            }
        });
    </script>
    <script src="./javascript/setting.js"></script>
</body>
</html>
