<?php
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'workease';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die('Connection failed: ' . $conn->connect_error);

// Error reporting (optional for debugging)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Assuming user's ID is already stored in session
$UserID = $_SESSION['UserID'] ?? null;

// Handle appointment booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_appointment'])) {
    $serviceType = $_POST['service_type'];
    $date = $_POST['date'];
    $timeFrom = $_POST['time_from'];
    $timeTo = $_POST['time_to'];
    $address = $_POST['address'];
    $status = 'Pending';
    $createdAt = date('Y-m-d H:i:s');

    // Find available provider
    $stmt = $conn->prepare("
        SELECT ProviderID FROM serviceproviders 
        WHERE ServiceType = ?
        LIMIT 1
    ");
    $stmt->bind_param("s", $serviceType);
    $stmt->execute();
    $stmt->bind_result($providerID);
    $stmt->fetch();
    $stmt->close();

    if ($providerID) {
        // Insert into appointment table
        $insert = $conn->prepare("
            INSERT INTO appointments (UserID, ProviderID, ServiceType, DateFrom, DateTo, TimeFrom, TimeTo, Address, Status, CreatedAt)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insert->bind_param(
            "iissssssss",
            $UserID, $providerID, $serviceType,
            $date, $date, $timeFrom, $timeTo, $address,
            $status, $createdAt
        );

        if ($insert->execute()) {
            echo "<script>alert('Appointment scheduled successfully!'); window.location.href='userapp.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error scheduling appointment.');</script>";
        }
        $insert->close();
    } else {
        echo "<script>alert('No providers available for the selected service.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule Appointment</title>
    <style>
        body {
            background: linear-gradient(135deg, #ffb0c8, #6ed5e4);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            background: #fff;
            margin: 50px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #6ed5e4;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"], input[type="date"], input[type="time"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #ffb0c8;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }

        textarea {
            resize: none;
            height: 80px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #6ed5e4;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #51c2d4;
        }

        .note {
            text-align: center;
            margin-top: 10px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Schedule Your Appointment</h2>
    <form method="POST" action="">
        <label for="service_type">Select Service:</label>
        <?php
$preselectedService = $_GET['service'] ?? '';
?>

<?php if ($preselectedService): ?>
    <input type="hidden" name="service_type" value="<?= htmlspecialchars($preselectedService) ?>">
    <p><strong>Service:</strong> <?= htmlspecialchars($preselectedService) ?></p>
<?php else: ?>
    <label for="service_type">Select Service:</label>
    <select name="service_type" id="service_type" required>
        <option value="">-- Select Service --</option>
        <option value="Plumber">Plumber</option>
        <option value="Electrician">Electrician</option>
        <option value="Cook">Cooky</option>
        <option value="Maid">Maid</option>
        <option value="Babysitter">Babysitter</option>
        <option value="Sweeper">Sweeper</option>
    </select>
<?php endif; ?>


        <label for="date">Select Date:</label>
        <input type="date" name="date" id="date" required>

        <label for="time_from">Time From:</label>
        <input type="time" name="time_from" id="time_from" required>

        <label for="time_to">Time To:</label>
        <input type="time" name="time_to" id="time_to" required>

        <label for="address">Your Address:</label>
        <textarea name="address" id="address" required></textarea>

        <button type="submit" name="book_appointment">Book Appointment</button>
    </form>

    <div class="note">
        You'll be notified once the provider confirms the appointment.
    </div>
</div>

</body>
</html>
