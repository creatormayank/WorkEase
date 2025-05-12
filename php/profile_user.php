<?php
session_start();

// If user not logged in, redirect to login
if (!isset($_SESSION['email'])) {
    header("Location: ../index.html"); // your home
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WorkEase - User Profile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f0f2f5;
    }
    
    .header {
      background: linear-gradient(to right, #f8a5c2, #4ecdc4);
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .logo {
      font-size: 24px;
      font-weight: bold;
      color: white;
      text-decoration: none;
    }
    
    .nav {
      display: flex;
      gap: 20px;
    }
    
    .nav a {
      color: white;
      text-decoration: none;
      padding: 8px 15px;
      border-radius: 20px;
      transition: all 0.3s ease;
    }
    
    .nav a:hover {
      background-color: rgba(255,255,255,0.2);
    }
    
    .profile-banner {
      height: 180px;
      background: linear-gradient(to right, #f8a5c2, #4ecdc4);
      position: relative;
      overflow: hidden;
    }
    
    .banner-pattern {
      position: absolute;
      width: 100%;
      height: 100%;
      background-image: radial-gradient(circle, rgba(255,255,255,0.2) 10%, transparent 10.5%);
      background-size: 20px 20px;
    }
    
    .profile-circle {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      background: linear-gradient(135deg, #f8a5c2, #4ecdc4);
      border: 5px solid white;
      position: absolute;
      top: 100px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 48px;
      color: white;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .profile-info {
      margin-top: 85px;
      text-align: center;
      padding: 0 20px;
      background-color: white;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
      border-radius: 10px;
      box-shadow: 0 2px 15px rgba(0,0,0,0.05);
      padding: 20px;
    }
    
    .user-name {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 5px;
      background: linear-gradient(to right, #f8a5c2, #4ecdc4);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }
    
    .user-type {
      font-size: 18px;
      color: #666;
      margin-bottom: 20px;
      position: relative;
      display: inline-block;
    }
    
    .user-type:after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 3px;
      background: linear-gradient(to right, #f8a5c2, #4ecdc4);
      border-radius: 3px;
    }
    
    .user-contact {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 30px 0;
      flex-wrap: wrap;
    }
    
    .contact-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 15px;
      background-color: #f9f9f9;
      border-radius: 25px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      transition: transform 0.3s ease;
    }
    
    .contact-item:hover {
      transform: translateY(-2px);
    }
    
    .action-buttons {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }
    
    .action-btn {
      padding: 12px 24px;
      border: none;
      border-radius: 25px;
      background: linear-gradient(to right, #ff6b6b, #ff8e8e);
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255,107,107,0.3);
      font-weight: 600;
    }
    
    .action-btn:hover {
      box-shadow: 0 6px 20px rgba(255,107,107,0.4);
      transform: translateY(-2px);
    }
    
    .booking-history {
      background-color: white;
      border-radius: 10px;
      max-width: 800px;
      margin: 20px auto;
      box-shadow: 0 2px 15px rgba(0,0,0,0.05);
      overflow: hidden;
    }
    
    .booking-header {
      padding: 15px 25px;
      background: linear-gradient(to right, #f8a5c2, #4ecdc4);
      color: white;
      font-weight: bold;
      text-align: center;
    }
    
    .booking-content {
      padding: 30px;
      min-height: 200px;
    }
  </style>
</head>
<body>
  <div class="header">
    <a href="#" class="logo">WorkEase</a>
    <div class="nav">
      <a href="userdash.php">Dashboard</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>
  
  <div class="profile-banner">
    <div class="banner-pattern"></div>
  </div>
  
  <div class="profile-circle">initials</div>
  
  <div class="profile-info">
    <div class="user-name">customer name</div>
    <div class="user-type">Mathew</div>
    
    <div class="user-contact">
      <div class="contact-item">
        <span>📞</span>
        <span>+91-0987654321</span>
      </div>
      <div class="contact-item">
        <span>✉</span>
        <span>customer@gmail.com</span>
      </div>
      <div class="contact-item">
        <span>📍</span>
        <span>Indore , MP</span>
      </div>
    </div>
    
    <div class="action-buttons">
      <button class="action-btn">Edit Profile</button>
      <button class="action-btn">Change Picture</button>
      <button class="action-btn"><a href="userdash.php">View Services</a></button>
    </div>
  </div>
    </div>
  </div>
</body>
</html>
