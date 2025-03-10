document.addEventListener("DOMContentLoaded", function () {
    const languageSelect = document.getElementById('language-select');
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const backButton = document.getElementById("back-button");
    const logoutButton = document.getElementById("logout-btn");
    const languageLabel = document.querySelector('label[for="language-select"]');
    
    // Load the selected language
    function loadLanguage(lang) {
        fetch(`lang/${lang}.json`)
            .then(response => response.json())
            .then(data => {
                // Update content based on the selected language
                backButton.textContent = data.back_button;
                darkModeToggle.textContent = data.dark_mode;
                logoutButton.textContent = data.logout;
                languageLabel.textContent = data.change_language;
            })
            .catch(error => console.error('Error loading language:', error));
    }

    // Event listener for language change
    languageSelect.addEventListener("change", function (event) {
        let selectedLanguage = event.target.value;
        loadLanguage(selectedLanguage); // Call function to load the selected language
    });

    // Load default language
    loadLanguage('en');

    // Dark Mode Toggle
    let darkmode = localStorage.getItem('darkmode');
    
    const enableDarkMode = () => {
        document.body.classList.add('dark-mode');
        localStorage.setItem('darkmode', 'active');
        darkModeToggle.innerHTML = '<i class="fa-solid fa-sun"></i> Light Mode';
    };

    const disableDarkMode = () => {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('darkmode', 'disabled');
        darkModeToggle.innerHTML = '<i class="fa-solid fa-moon"></i> Dark Mode';
    };

    if (darkmode === "active") {
        enableDarkMode();
    } else {
        disableDarkMode();
    }

    darkModeToggle.addEventListener("click", () => {
        darkmode = localStorage.getItem('darkmode');
        darkmode !== "active" ? enableDarkMode() : disableDarkMode();
    });
});
