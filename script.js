// home page

document.querySelector('.hero button').addEventListener('click', () => {
    window.location.href = '#services';
});

// login page
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
        e.preventDefault();
        alert("Login form submitted!");
    });
});


//    login page toggle (to hide one form)

function showForm(formType) {
    const signupForm = document.getElementById("signup-form");
    const loginForm = document.getElementById("login-form");

    if (formType === 'signup') {
        signupForm.classList.remove("hidden");
        loginForm.classList.add("hidden");
    } else {
        signupForm.classList.add("hidden");
        loginForm.classList.remove("hidden");
    }
}