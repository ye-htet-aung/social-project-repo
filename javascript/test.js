const enableDarkmode = () => {
    document.body.classList.add('darkmode');
    localStorage.setItem('darkmode', 'active');
    themeSwitch.textContent = "Change to Light Mode";
    console.log('Dark mode enabled');
};

const disableDarkmode = () => {
    document.body.classList.remove('darkmode');
    localStorage.setItem('darkmode', 'disabled');
    themeSwitch.textContent = "Change to Dark Mode";
    console.log('Dark mode disabled');
};

if (darkmode === "active") {
    enableDarkmode();
    console.log('Dark mode is active');
} else {
    disableDarkmode();
    console.log('Dark mode is disabled');
}

themeSwitch.addEventListener("click", () => {
    darkmode = localStorage.getItem('darkmode');
    darkmode !== "active" ? enableDarkmode() : disableDarkmode();
    console.log('Dark mode toggled');
});