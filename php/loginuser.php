<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Database connection details
$host = 'localhost'; 
$user = 'root';      
$pass = '';          
$db = 'workease';    

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT PasswordHash,UserID FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword,$UserID);
        $stmt->fetch();

        // Verify the entered password with the hashed password
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['email'] = $email;
            $_SESSION['UserID'] = $UserID;
            header('Location: userdash.php');
            exit();
        } else {
            echo "<script>alert('Invalid username or password');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
