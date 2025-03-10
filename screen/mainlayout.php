<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Layout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../css/layout.css">
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <div id="nav-left">
        
        <h2>facebook</h2>
        <div id="na-leftdiv">
            <a href="SearchedPage.php">
                <i class="fa-solid fa-magnifying-glass" style="color: #005eff;"></i>
            </a>
            <i class="fa-brands fa-facebook-messenger" style="color: #005eff;"></i>
        </div>
    </div>
    
    <div id="nav-center">
        <a href="/screen/Home.php">
            <i class="fa-solid fa-house fa-lg" style="color: #005eff;"></i>
        </a>
        <a href="/screen/Video.php">
            <i class="fa-solid fa-film fa-lg" style="color: #005eff;"></i>
        </a>
        <a href="/screen/Profile.php">
            <i class="fa-solid fa-circle-user fa-lg" style="color: #005eff;"></i>
        </a>
        <a href="/screen/Friend.php">
            <i class="fa-solid fa-user-group fa-lg" style="color: #005eff;"></i>
        </a>
        <a href="/screen/Notification.php">
            <i class="fa-solid fa-bell fa-lg" style="color: #005eff;"></i>
        </a>
        <a href="/screen/settings.php" id="settings-icon">
            <i class="fa-solid fa-cog fa-lg" style="color: #005eff;"></i>
        </a>
    </div>
</nav>

<!-- Dark Mode Toggle Button -->


    <script>
        const searchIcon = document.getElementById("search-icon");
        const navLeft = document.getElementById("nav-left");
        const searchForm = document.getElementById("search-form");
        const searchInput = document.getElementById("search-input");

        // Toggle search bar on clicking the search icon
        searchIcon.addEventListener("click", () => {
            navLeft.classList.toggle("active");
            searchInput.focus(); // Focus on the input field when activated
        });

        // Prevent empty search submission
        searchForm.addEventListener("submit", (event) => {
            if (searchInput.value.trim() === "") {
                event.preventDefault(); // Stop form submission if input is empty
            }
        });
    </script>

<!-- JavaScript -->
<script src="../js/setting.js"></script>

</body>
</html>
