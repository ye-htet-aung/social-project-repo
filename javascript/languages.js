document.addEventListener("DOMContentLoaded", function () {
    const languageSelect = document.getElementById('language-select');
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const backButton = document.getElementById("back-button");
    const logoutButton = document.getElementById("logout-btn");
    const languageLabel = document.querySelector('label[for="language-select"]');
    
    // Load the selected language
    function loadLanguage(lang) {
        fetch('../javascript/languages.json')
            .then(response => response.json())
            .then(data => {
                const translations = data[lang];
                if (translations) {
                    backButton.innerHTML = '<i class="fa-solid fa-arrow-left"></i> ' + translations.back_button;
                    darkModeToggle.innerHTML = '<i class="fa-solid fa-moon"></i> ' + translations.dark_mode;
                    logoutButton.innerHTML = '<i class="fa-solid fa-sign-out-alt"></i> ' + translations.logout;
                    languageLabel.textContent = translations.change_language;
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