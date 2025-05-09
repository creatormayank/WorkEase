document.addEventListener("DOMContentLoaded", () => {
    const signupForm = document.getElementById("signup-form");
    const loginForm = document.getElementById("login-form");

    // Toggle active forms
    signupForm.classList.add("active");

    signupForm.addEventListener("submit", (e) => {
        e.preventDefault();
        alert("Signup form submitted!");
    });

    loginForm.addEventListener("submit", (e) => {
        // Remove e.preventDefault(); so the form can submit
        // e.preventDefault();
        alert("Login form submitted!");
    });
});
