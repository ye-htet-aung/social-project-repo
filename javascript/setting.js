document.addEventListener("DOMContentLoaded", () => {
    const darkModeToggle = document.getElementById('dark-mode-toggle');

    // Function to enable dark mode
    const enableDarkMode = () => {
        document.body.classList.add('darkmode');
        localStorage.setItem('darkmode', 'active');
        darkModeToggle.innerHTML = '<i class="fa-solid fa-sun"></i> Light Mode';
    };

    // Function to disable dark mode
    const disableDarkMode = () => {
        document.body.classList.remove('darkmode');
        localStorage.setItem('darkmode', 'disabled');
        darkModeToggle.innerHTML = '<i class="fa-solid fa-moon"></i> Dark Mode';
    };

    // Apply dark mode on page load based on localStorage
    if (localStorage.getItem('darkmode') === "active") {
        enableDarkMode();
    } else {
        disableDarkMode();
    }

    // Toggle dark mode on button click
    darkModeToggle.addEventListener("click", () => {
        if (document.body.classList.contains('darkmode')) {
            disableDarkMode();
        } else {
            enableDarkMode();
        }
    });

    // Listen for changes to localStorage (for other pages to update theme without reload)
    window.addEventListener('storage', () => {
        if (localStorage.getItem('darkmode') === "active") {
            enableDarkMode();
        } else {
            disableDarkMode();
        }
    });

    // Logout Functionality (Redirect to userLogin.php)
    document.getElementById("logout-btn").addEventListener("click", () => {
        console.log("Logout button clicked"); // Debugging line
        sessionStorage.clear(); // Clear session storage
        window.location.href = "../userLogin.php"; // Redirect to userLogin.php
    });
});