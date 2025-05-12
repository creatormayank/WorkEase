<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "workease";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$fullname = $_POST['fullname'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$city = $_POST['city'];
$state = $_POST['state'];
$pincode = $_POST['pincode'];
$mobile = $_POST['mobile'];
$email = $_POST['email'];

// $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Insert into database
$sql = "INSERT INTO users (FullName, DOB, Gender, Address, City, State, PinCode, MobileNo, Email, PasswordHash)
        VALUES ('$fullname', '$dob', '$gender', '$address', '$city', '$state', '$pincode', '$mobile', '$email', '$hashedPassword')";

if ($conn->query($sql) === TRUE) {
    echo "Signup successful!";
    header("Location: ");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>