const passwordInput = document.querySelector("#pwd-input");
const passwordToggle = document.querySelector("#pwd-toggle");

passwordToggle.addEventListener("click", function() {
    if (passwordInput.getAttribute("type") == 'password') {
        passwordInput.setAttribute("type", 'text');
        passwordToggle.querySelector('i').className = 'fas fa-eye-slash';
    } else {
        passwordInput.setAttribute("type", 'password');
        passwordToggle.querySelector('i').className = 'fas fa-eye';
    }
})