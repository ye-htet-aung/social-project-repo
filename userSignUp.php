<?php
session_start();
include 'database/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $name = mysqli_real_escape_string($con, $name);
    $email = mysqli_real_escape_string($con, $email);
    $password = mysqli_real_escape_string($con, $password);

    $hashed_Password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_Password')";
    if ($con->query($sql) === True) {
        $user_id = $con->insert_id;
        $_SESSION['user_id'] = $user_id;
        header("Location: profiledata.php");
        exit();
    } else {
        echo "Error: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/usersignup.css">
</head>
<body >
    <div class="container">
        <h2 id="signup-page-title">Sign Up</h2>
        <form action="" method="post">
            <label for="name-label">Name:</label>
            <input type="text" name="name" placeholder="Name" required>
            <label for="email-label">Email:</label>
            <input type="email" name="email" placeholder="Email" required>
            <label for="password-label">Password:</label>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" id="signup-button">Sign Up</button>
        </form>
    </div>
    <!-- Language Selector -->
    <label for="language-select">Change Language:</label>
    <select id="language-select">
        <option value="en">English</option>
        <option value="my">မြန်မာ</option>
        <option value="fr">Français</option>
        <option value="ja">日本語</option>
    </select>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const languageSelect = document.getElementById('language-select');
            const signupPageTitle = document.getElementById('signup-page-title');
            const nameLabel = document.querySelector('label[for="name-label"]');
            const emailLabel = document.querySelector('label[for="email-label"]');
            const passwordLabel = document.querySelector('label[for="password-label"]');
            const signupButton = document.getElementById('signup-button');

            // Load the selected language
            function loadLanguage(lang) {
                fetch('javascript/languages.json')
                    .then(response => response.json())
                    .then(data => {
                        const translations = data[lang];
                        if (translations) {
                            signupPageTitle.textContent = translations.signup_page_title;
                            nameLabel.textContent = translations.name_label;
                            emailLabel.textContent = translations.email_label;
                            passwordLabel.textContent = translations.password_label;
                            signupButton.textContent = translations.signup_button;
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
<script src="../javascript/setting.js"></script>
    
</body>
</html>