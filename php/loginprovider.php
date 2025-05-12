<?php
// Start session
session_start();

// Include database connection file
// Database Connection
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

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email and password from the login form
    $email = trim($_POST['Email']);
    $password = trim($_POST['Password']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        echo "<script>alert('Both fields are required!'); window.location.href='loginprovider.html';</script>";
        exit;
    }

    // Prepare SQL to fetch provider details by email
    $stmt = $conn->prepare("SELECT ProviderID, PasswordHash FROM serviceproviders WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if email exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($providerId, $passwordHash);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $passwordHash)) {
            // Store session data
            $_SESSION['ProviderID'] = $providerId;
            $_SESSION['Email'] = $email;

            // Redirect to service provider dashboard
            header("Location: providerdash.php");
            exit;
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='loginprovider.html';</script>";
            exit;
        }
    } else {
        echo "<script>alert('No account found with this email!'); window.location.href='loginprovider.html';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='loginprovider.html';</script>";
    exit;
}
?>
