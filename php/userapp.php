<?php
session_start();
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'workease';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die('Connection failed: ' . $conn->connect_error);

$UserID = $_SESSION['UserID'] ?? null;
if (!$UserID) {
    echo "<script>alert('Please log in first.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch appointments
$stmt = $conn->prepare("
    SELECT AppointmentID, ServiceType, DateFrom, TimeFrom, TimeTo, Address, Status, CreatedAt 
    FROM appointments
    WHERE UserID = ? 
    ORDER BY CreatedAt DESC
");
$stmt->bind_param("i", $UserID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ffb0c8, #6ed5e4);
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #6ed5e4;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <h2>Your Appointments</h2>
    <table>
        <tr>
            <th>Service</th>
            <th>Date</th>
            <th>From</th>
            <th>To</th>
            <th>Address</th>
            <th>Status</th>
            <th>Booked On</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['ServiceType']) ?></td>
            <td><?= $row['DateFrom'] ?></td>
            <td><?= $row['TimeFrom'] ?></td>
            <td><?= $row['TimeTo'] ?></td>
            <td><?= htmlspecialchars($row['Address']) ?></td>
            <td><?= htmlspecialchars($row['Status']) ?></td>
            <td><?= $row['CreatedAt'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
