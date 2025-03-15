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
    $gender=trim($_POST["gender"]);

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
        // BackgroundPicture Upload
        $background_picture = "";
        if (isset($_FILES['background_picture']) && $_FILES['background_picture']['error'] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["background_picture"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            $check = getimagesize($_FILES["background_picture"]["tmp_name"]);
            if ($check !== false) {
                $allowed_types = ['jpg', 'jpeg', 'png'];
                $max_size = 2 * 1024 * 1024; // 2MB limit
    
                if (!in_array($imageFileType, $allowed_types)) {
                    echo "Only JPG, JPEG, and PNG files are allowed.";
                    exit;
                }
    
                if ($_FILES["background_picture"]["size"] > $max_size) {
                    echo "File size exceeds the 2MB limit.";
                    exit;
                }
    
                if (move_uploaded_file($_FILES["background_picture"]["tmp_name"], $target_file)) {
                    $background_picture = $target_file;
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
    $stmt = $con->prepare("INSERT INTO user_profiles (user_id, birthday,gender,current_location, hometown, educatione, bio, profile_picture,background) 
                           VALUES (?, ?, ?,?, ?, ?, ?, ?,?)");
    $stmt->bind_param("issssssss", $user_id, $birthday,$gender,$current_location, $hometown, $education, $bio, $profile_picture,$background_picture);

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
    <link rel="stylesheet" href="../css/profiledata.css">
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <h2>Enter your informations</h2>

        <label id="birthday-label">Birthday</label>
        <input type="date" name="birthday" required><br>

        <fieldset>
        <legend id="gender-label">Gender</legend>
        <label id="male" >Male</label>
        <input type="radio" name="gender" value="male" >
        <label id="female">Female</label>
        <input type="radio" name="gender" value="female" >
        </fieldset>

        <label id="current-location-label">Current Location</label>
        <input type="text" name="current_location" required><br>
        
        <label id="hometown-label">HomeTown</label>
        <input type="text" name="hometown" required><br>
        
        <label id="education-label">Education</label>
        <input type="text" name="education" required><br>
        
        <label id="bio-label">Write something to describe you</label>
        <input type="text" name="bio"><br>
        
        <label id="profile-picture-label">Profile Picture</label>
        <input type="file" name="profile_picture" accept="image/*" required><br>

        <label id="background-picture-label">Background Picture</label>
        <input type="file" name="background_picture" accept="image/*" required><br>

        <input type="submit" id="register-button" value="Register">
    </form>

    <!-- Language Selector
    <label for="language-select">Change Language:</label>
    <select id="language-select">
        <option value="en">English</option>
        <option value="my">မြန်မာ</option>
        <option value="fr">Français</option>
        <option value="ja">日本語</option>
    </select> -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const languageSelect = document.getElementById('language-select');
            const profileRegistrationTitle = document.querySelector('title');
            const birthdayLabel = document.getElementById('birthday-label');
            const genderlable = document.getElementById('gender-label');
            const gender1 = document.getElementById('male');
            const gender2 = document.getElementById('female');
            const currentLocationLabel = document.getElementById('current-location-label');
            const hometownLabel = document.getElementById('hometown-label');
            const educationLabel = document.getElementById('education-label');
            const bioLabel = document.getElementById('bio-label');
            const profilePictureLabel = document.getElementById('profile-picture-label');
            const registerButton = document.getElementById('register-button');

            // Load the selected language
            function loadLanguage(lang) {
                fetch('javascript/languages.json')
                    .then(response => response.json())
                    .then(data => {
                        const translations = data[lang];
                        if (translations) {
                            profileRegistrationTitle.textContent = translations.profile_registration_title;
                            birthdayLabel.textContent = translations.birthday_label;
                            genderlable.textContent = translations.gender_label;
                            gender1.textContent = translations.gender_1;
                            gender2.textContent = translations.gender_2;
                            currentLocationLabel.textContent = translations.current_location_label;
                            hometownLabel.textContent = translations.hometown_label;
                            educationLabel.textContent = translations.education_label;
                            bioLabel.textContent = translations.bio_label;
                            profilePictureLabel.textContent = translations.profile_picture_label;
                            registerButton.value = translations.register_button;
                        }
                    })
                    .catch(error => console.error('Error loading language:', error));
            }

            // Set default language to English or load from localStorage
            const defaultLanguage = localStorage.getItem('language') || 'en';
            loadLanguage(defaultLanguage);
            languageSelect.value = defaultLanguage;

            // Event listener for language change
            languageSelect.addEventListener("change", function (event) {
                const selectedLanguage = event.target.value;
                localStorage.setItem('language', selectedLanguage);
                loadLanguage(selectedLanguage);
            });
        });
    </script>
</body>
</html>