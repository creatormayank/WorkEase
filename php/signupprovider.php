<?php
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $fullName = $_POST['FullName'];
    $dob = $_POST['DOB'];
    $gender = $_POST['Gender'];
    $address = $_POST['Address'];
    $city = $_POST['City'];
    $state = $_POST['State'];
    $pinCode = $_POST['PinCode'];
    $mobileNo = $_POST['MobileNo'];
    $email = $_POST['Email'];
    $serviceType = $_POST['ServiceType'];
    $aadhaarNo = $_POST['AadhaarNo'];
    $passwordHash = password_hash($_POST['Password'], PASSWORD_DEFAULT);

    // Insert basic details first to get ProviderID
    $stmt = $conn->prepare("INSERT INTO serviceproviders (FullName, DOB, Gender, Address, City, State, PinCode, MobileNo, Email, ServiceType, AadhaarNo, PasswordHash, CreatedAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssssssssss", $fullName, $dob, $gender, $address, $city, $state, $pinCode, $mobileNo, $email, $serviceType, $aadhaarNo, $passwordHash);
    $stmt->execute();

    // Get the newly inserted ProviderID
    $providerId = $stmt->insert_id;

    // Define upload directory
    $uploadDir = '../uploads/';

    // Rename and save ID1
    $id1File = $_FILES['ID1'];
    $id1Path = $uploadDir . $providerId . 'One.' . pathinfo($id1File['name'], PATHINFO_EXTENSION);
    move_uploaded_file($id1File['tmp_name'], $id1Path);

    // Rename and save ID2
    $id2File = $_FILES['ID2'];
    $id2Path = $uploadDir . $providerId . 'Two.' . pathinfo($id2File['name'], PATHINFO_EXTENSION);
    move_uploaded_file($id2File['tmp_name'], $id2Path);

    // Update file paths in the database
    $updateStmt = $conn->prepare("UPDATE serviceproviders SET ID1 = ?, ID2 = ? WHERE ProviderID = ?");
    $updateStmt->bind_param("ssi", $id1Path, $id2Path, $providerId);
    $updateStmt->execute();

    echo "Service provider registered successfully!";
}
?>
