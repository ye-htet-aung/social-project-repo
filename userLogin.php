<?php
session_start();
include 'database/config.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $email=$_POST['email'];
    $password=$_POST['password'];

    $email=mysqli_real_escape_string($con,$email);
    $password=mysqli_real_escape_string($con,$password);

    $sql="SELECT * FROM users where email='$email' LIMIT 1";
    $data=$con->query($sql);
    
    if($data->num_rows>0){
        $user=$data->fetch_assoc();
        
        if(password_verify($password,$user['password'])){
            $_SESSION['user_id']=$user['id'];
            $_SESSION['user_name']=$user['name'];
            header("Location: screen/Home.php");
            exit();
        }else{
            $error="Invalid Password.";
        }
    }else{
        $error="User not Found";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/userlogin.css">
    <title>Document</title>
</head>
<body>
    <h2 id="login-page-title">Login page</h2>
    <div class="container">
        <form action="" method="post">
            <label for="email-label">Email:</label>
            <input type="email" name="email" required><br>
            <label for="password-label">Password:</label>
            <input type="password" name="password" required>
            <button type="submit" id="login-button">Login</button>
            <?php if (!empty($error)): ?>
                <p id="error-message" style="color: red;"><?php echo $error; ?></p>
                <button type="button" id="signup-button" onclick="window.location.href='usersignup.php'">Go to Sign Up</button>
            <?php endif; ?>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const languageSelect = document.getElementById('language-select');
            const loginPageTitle = document.getElementById('login-page-title');
            const emailLabel = document.querySelector('label[for="email-label"]');
            const passwordLabel = document.querySelector('label[for="password-label"]');
            const loginButton = document.getElementById('login-button');
            const signupButton = document.getElementById('signup-button');
            const errorMessage = document.getElementById('error-message');

            // Load the selected language
            function loadLanguage(lang) {
                fetch('javascript/languages.json')
                    .then(response => response.json())
                    .then(data => {
                        const translations = data[lang];
                        if (translations) {
                            loginPageTitle.textContent = translations.login_page_title;
                            emailLabel.textContent = translations.email_label;
                            passwordLabel.textContent = translations.password_label;
                            loginButton.textContent = translations.login_button;
                            signupButton.textContent = translations.signup_button;
                            if (errorMessage) {
                                errorMessage.textContent = translations[errorMessage.textContent];
                            }
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