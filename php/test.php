<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    // If not logged in, redirect to the login page
    header("Location: ../loginuser.html");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkEase</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f8f8f8;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #ffb0c8;
            color: white;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }
        .logout-link {
            text-decoration: none;
            color: white;
        }
        .main-section {
            background: linear-gradient(to right, #ffb0c8, #6ed5e4);
            padding: 40px 20px;
            text-align: center;
        }
        .search-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            position: relative;
        }
        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.8);
            color: #333;
            padding-right: 40px;
        }
        .search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            background-color: transparent;
            pointer-events: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .search-icon svg {
            stroke: #000;
        }
        .recommendations {
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            padding: 10px;
            color: white;
        }
        .recommendation {
            padding: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .page-section {
            padding: 20px;
        }
        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .image-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .image-box {
            border: 1px solid rgba(255, 255, 255, 0.3);
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to bottom right, #ffb0c8, #6ed5e4);
            border-radius: 5px;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">WorkEase</div>
        <a href="#" class="logout-link">Logout</a>
    </header>
    
    <div class="main-section">
        <div class="search-container">
            <div class="search-wrapper">
                <input type="text" placeholder="Search bar...">
                <div class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="recommendations">
            <div class="recommendation">Recommendation 1</div>
            <div class="recommendation">Recommendation 2</div>
            <div class="recommendation">Recommendation 3</div>
            <div class="recommendation">Recommendation 4</div>
        </div>
    </div>
    
    <div class="page-section">
        <h2 class="section-title">Our Services</h2>
        <div class="image-grid">
            <div class="image-box">img 1</div>
            <div class="image-box">img 2</div>
            <div class="image-box">img 3</div>
            <div class="image-box">img 4</div>
            <div class="image-box">img 5</div>
            <div class="image-box">img 6</div>
        </div>
    </div>
</body>
</html>