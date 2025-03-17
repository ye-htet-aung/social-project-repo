<?php
    include_once '../screen/mainlayout.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet" href="../css/home.css">
    <style>
        /* Center settings container */
        #settings-container {
            text-align: center;
            padding: 30px 0px;
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        /* Buttons Styling */
        .setting-btn {
            background-color: #005eff;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 10px auto;
            width: 200px;
        }

        .setting-btn i {
            font-size: 20px;
        }

        .setting-btn:hover {
            background-color: #0040cc;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #121212;
            color: #fff;
        }

        body.dark-mode .setting-btn {
            background-color: #ccc;
            color: #000;
        }

        body.dark-mode .setting-btn:hover {
            background-color: #aaa;
        }

        /* Logout Button */
        .logout-btn {
            background-color: #e53935;
            color: white;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }

        /* Language Selector */
        #language-select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Dark mode for select */
        body.dark-mode #language-select {
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
        }
    </style>
</head>
<body>
    
    <div id="main">
        <div id="media">
        
            <!-- Settings Container -->
            <div id="settings-container">
                <!-- Dark Mode Button -->
                <button id="dark-mode-toggle" class="setting-btn">
                    <i class="fa-solid fa-moon"></i> Dark Mode
                </button>

                <!-- Language Selector -->
                <label for="language-select">Change Language:</label>
                <select id="language-select">
                    <option value="en">English</option>
                    <option value="my">မြန်မာ</option>
                    <option value="fr">Français</option>
                    <option value="ja">日本語</option>
                </select>

                <!-- Logout Button -->
                <button id="logout-btn" class="setting-btn logout-btn">
                    <i class="fa-solid fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </div>
    </div>
<script src="../javascript/languages.js"></script>

</body>
</html>