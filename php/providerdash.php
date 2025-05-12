<?php
session_start();

// Redirect if provider not logged in
if (!isset($_SESSION['ProviderID'])) {
    header("Location: provider_login.php");
    exit();
}

$providerID = $_SESSION['ProviderID'];

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'workease';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle accept/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['appointment_id'])) {
    $action = $_POST['action'];
    $appointmentID = intval($_POST['appointment_id']);

    if ($action === 'accept' || $action === 'reject') {
        $status = ($action === 'accept') ? 'Accepted' : 'Rejected';
        $stmt = $conn->prepare("UPDATE appointments SET Status = ? WHERE AppointmentID = ? AND ProviderID = ?");
        $stmt->bind_param("sii", $status, $appointmentID, $providerID);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch electrician appointments
$stmt = $conn->prepare("SELECT AppointmentID, UserID, DateFrom, DateTo, TimeFrom, TimeTo, Address, Status, ArrivalTime, Cost 
                        FROM appointments 
                        WHERE ProviderID = ? AND ServiceType = 'Electrician'");
$stmt->bind_param("i", $providerID);
$stmt->execute();
$result = $stmt->get_result();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkEase - Service Provider Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #ffb0c8, #6ed5e4);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            background-color: #ffb0c8;
            border-radius: 8px;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .profile-logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            overflow: hidden;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .profile-logo svg {
            width: 24px;
            height: 24px;
            fill: white;
        }
        
        .brand-name {
            font-size: 28px;
            font-weight: bold;
            color: white;
            letter-spacing: 0.5px;
        }
        
        .logout-link {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .logout-link:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        
        .main-heading {
            text-align: center;
            margin-bottom: 40px;
            color: white;
            font-size: 32px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .card {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .card h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            border-bottom: 2px solid rgba(255, 176, 200, 0.3);
            padding-bottom: 10px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 15px;
        }
        
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        .service-list li {
            margin-bottom: 12px;
            padding-left: 20px;
            position: relative;
            color: #333;
        }
        
        .service-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #ffb0c8;
            font-size: 20px;
            line-height: 20px;
        }
        
        .action-link {
            background-color: #ffb0c8;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%;
            display: block;
            text-align: center;
            margin-top: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .action-link:hover {
            background-color: #ff9fb9;
        }
        
        .progress-bar {
            height: 12px;
            background-color: #e6e6e6;
            border-radius: 6px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .progress {
            height: 100%;
            background: linear-gradient(90deg, #ffb0c8, #6ed5e4);
            border-radius: 6px;
            width: 80%;
        }
        
        .schedule-card {
            grid-column: 1 / -1;
        }
        
        .get-started-link {
            background-color: #ff9fb9;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%;
            display: block;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .get-started-link:hover {
            background-color: #ff8eab;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <a href="#" class="logo-container">
                <div class="profile-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                </div>
                <div class="brand-name">WorkEase</div>
            </a>
            <a href="logout.php" class="logout-link">Logout</a>
        </header>
        
        <h1 class="main-heading">Rajesh ! Manage your profile and services here.</h1>
        
        <div class="dashboard-grid">
            <div class="card">
                <h2>Profile Overview</h2>
                <div class="info-row">
                    <div class="info-label">Service Category</div>
                    <div class="info-value">Electrician</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Service Area</div>
                    <div class="info-value">Indore, MP</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">0123456789</div>
                </div>
                <a href="#" class="action-link">Edit Profile</a>
            </div>
            
            <div class="card">
                <h2>Services Offered</h2>
                <ul class="service-list">
                    <li>Appliences Installation</li>
                    <li>Appliences Management</li>
                    <li>Repair</li>
                </ul>
                <a href="#" class="action-link">Add/Edit Services</a>
            </div>
            
            <div class="card">
                <h2>Profile Status</h2>
                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">Active</div>
                </div>
                <div class="progress-bar">
                    <div class="progress"></div>
                </div>
                <div class="info-value" style="text-align: right; margin-top: 5px;">80% complete</div>
                <a href="#" class="action-link">Complete Now</a>
            </div>
            
            <div class="card schedule-card">
                <h2>Service Schedule</h2>
                <div class="info-row">
                 
                    <?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Date</th>
            <th>Time</th>
            <th>Address</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['AppointmentID'] ?></td>
                <td><?= $row['UserID'] ?></td>
                <td><?= $row['DateFrom'] ?> to <?= $row['DateTo'] ?></td>
                <td><?= $row['TimeFrom'] ?> - <?= $row['TimeTo'] ?></td>
                <td><?= $row['Address'] ?></td>
                <td><?= $row['Status'] ?></td>
                <td>
                    <?php if ($row['Status'] === 'Pending'): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="appointment_id" value="<?= $row['AppointmentID'] ?>">
                            <button type="submit" name="action" value="accept" class="btn accept">Accept</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="appointment_id" value="<?= $row['AppointmentID'] ?>">
                            <button type="submit" name="action" value="reject" class="btn reject">Reject</button>
                        </form>
                    <?php else: ?>
                        <?= $row['Status'] ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No electrician appointments found.</p>
<?php endif; ?>

                </div>
                
            </div>
        </div>
    </div>
</body>
</html>