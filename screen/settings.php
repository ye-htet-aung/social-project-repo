<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../css/layout.css">
    <style>
        /* Back Button Styling */
        #back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 10px 15px;
            background-color: #005eff;
            color: #fff;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        #back-button i {
            margin-right: 5px;
        }

        #back-button:hover {
            background-color: #0040cc;
        }

        /* Center settings container */
        #settings-container {
            text-align: center;
            padding: 30px;
            margin-top: 50px;
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

<!-- Back Button -->
<button id="back-button" onclick="goBack()">
    <i class="fa-solid fa-arrow-left"></i> Back
</button>

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
        <option value="es">Español</option>
        <option value="fr">Français</option>
        <option value="de">Deutsch</option>
        <option value="zh">中文</option>
    </select>

    <!-- Logout Button -->
    <button id="logout-btn" class="setting-btn logout-btn">
        <i class="fa-solid fa-sign-out-alt"></i> Logout
    </button>
</div>

<script>
    function goBack() {
        window.history.back();
    }

    // Dark Mode Toggle
    document.addEventListener("DOMContentLoaded", () => {
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        let darkmode = localStorage.getItem('darkmode');

        // Function to enable dark mode
        const enableDarkMode = () => {
            document.body.classList.add('dark-mode');
            localStorage.setItem('darkmode', 'active');
            darkModeToggle.innerHTML = '<i class="fa-solid fa-sun"></i> Light Mode';
            // Notify other tabs
            localStorage.setItem('darkmode_changed', Date.now());
        };

        // Function to disable dark mode
        const disableDarkMode = () => {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkmode', 'disabled');
            darkModeToggle.innerHTML = '<i class="fa-solid fa-moon"></i> Dark Mode';
            // Notify other tabs
            localStorage.setItem('darkmode_changed', Date.now());
        };

        // Apply dark mode based on localStorage
        if (darkmode === "active") {
            enableDarkMode();
        } else {
            disableDarkMode();
        }

        // Toggle dark mode on button click
        darkModeToggle.addEventListener("click", () => {
            darkmode = localStorage.getItem('darkmode');
            darkmode !== "active" ? enableDarkMode() : disableDarkMode();
        });

        // Listen for changes to localStorage (for other pages to update theme without reload)
        window.addEventListener('storage', () => {
            if (localStorage.getItem('darkmode') === "active") {
                enableDarkMode();
            } else {
                disableDarkMode();
            }
        });
    });
</script>

</body>
</html>
