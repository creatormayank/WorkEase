<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    // If not logged in, redirect to the login page
    header("Location: loginuser.html");
    exit();
}
?>