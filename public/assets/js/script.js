let profileBtn = document.getElementById("profileBtn");
let profileMenu = document.getElementById("profileMenu");

profileBtn.onclick = () => {
    profileMenu.style.display =
        profileMenu.style.display === "block" ? "none" : "block";
};

document.addEventListener("click", function (e) {
    if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
        profileMenu.style.display = "none";
    }
});

function togglePassword(el) {
    const passwordInput = document.getElementById("password");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        el.textContent = "🙈"; // mata tertutup
    } else {
        passwordInput.type = "password";
        el.textContent = "🙉"; // mata terbuka
    }
}
