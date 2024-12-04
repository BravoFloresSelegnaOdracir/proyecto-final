const logoutButton = document.getElementById("logoutButton");
const logoutModal = document.getElementById("logoutModal");
const confirmLogout = document.getElementById("confirmLogout");
const cancelLogout = document.getElementById("cancelLogout");

logoutButton.addEventListener("click", (event) => {
    event.preventDefault(); 
    logoutModal.style.display = "block";
});

confirmLogout.addEventListener("click", () => {
    window.location.href = "app/logout.php";
});

cancelLogout.addEventListener("click", () => {
    logoutModal.style.display = "none";
});

window.addEventListener("click", (event) => {
    if (event.target === logoutModal) {
        logoutModal.style.display = "none";
    }
});