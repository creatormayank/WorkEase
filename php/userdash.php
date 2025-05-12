<?php
session_start();

// If user not logged in, redirect to login
if (!isset($_SESSION['email'])) {
    header("Location: ../index.html"); // your home
    exit();
}

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

// Fetch User Data
$email = $_SESSION['email'];
$UserID = $_SESSION['UserID'];
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
} else {
    // User not found or error
    echo "Error: User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WorkEase</title>
  <style>
    /* --- Your entire CSS as you posted --- */
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
    .header-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .profile-btn {
      background-color: rgba(255, 255, 255, 0.3);
      border: none;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .profile-btn:hover {
      background-color: rgba(255, 255, 255, 0.5);
      transform: translateY(-1px);
    }
    .profile-btn:active {
      transform: translateY(0);
    }
    .profile-btn img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
    }
    .logout-btn {
      background-color: rgba(255, 255, 255, 0.3);
      border: none;
      border-radius: 20px;
      padding: 8px 15px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.2s;
    }
    .logout-btn:hover {
      background-color: rgba(255, 255, 255, 0.5);
      transform: translateY(-1px);
    }
    .profile-menu {
      position: absolute;
      top: 60px;
      left: 20px;
      background: white;
      border-radius: 5px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 10px 0;
      min-width: 150px;
      z-index: 100;
      display: none;
    }
    .profile-menu.active {
      display: block;
    }
    .menu-item {
      padding: 10px 20px;
      color: #333;
      cursor: pointer;
    }
    .menu-item:hover {
      background-color: #f5f5f5;
    }
    .logo {
      font-size: 24px;
      font-weight: bold;
    }
    .main-section {
      background: linear-gradient(to right, #ffb0c8, #6ed5e4);
      padding: 40px 20px;
      text-align: center;
    }
    .address-box {
      background: rgba(255, 255, 255, 0.2);
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: left;
      color: white;
    }
    .search-container {
      background: rgba(255, 255, 255, 0.2);
      padding: 20px;
      border-radius: 5px;
      position: relative;
    }
    .search-wrapper {
      display: flex;
      align-items: center;
      position: relative;
    }
    input[type="text"] {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 5px 0 0 5px;
      background: rgba(255, 255, 255, 0.8);
    }
    .search-btn {
      background: linear-gradient(to right, #ffb0c8, #6ed5e4);
      border: none;
      border-radius: 0 5px 5px 0;
      padding: 12px 15px;
      cursor: pointer;
    }
    .search-btn:hover {
      background: linear-gradient(to right, #ff9cb9, #5cc6d5);
    }
    .search-btn svg {
      stroke: white;
    }
    .recommendations {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 0 0 5px 5px;
      max-height: 200px;
      overflow-y: auto;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: none;
    }
    .recommendation {
      padding: 10px 15px;
      cursor: pointer;
      text-align: left;
      color: #333;
    }
    .recommendation:hover {
      background-color: #eee;
    }
    

h2 {
  margin: 20px
}

    .service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    padding: 20px;
    margin-bottom: 5px;
    }

.service {
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease-in-out;
}

.service img {
    width: 100%;
    height: 300px;
    object-fit: cover;
}

.service:hover {
    transform: scale(1.05);
}

  </style>
</head>

<body>

<header>
  <div class="header-left">
    <button class="profile-btn" id="profile-button">
      <?php if (!empty($user['profile_pic'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile">
      <?php else: ?>
        <img src="../user/def_user.jpg" alt="Profile">
      <?php endif; ?>
    </button>
    <div class="profile-menu" id="profile-menu">
      <div class="menu-item"><a href="profile_user.php">My Profile</a></div>
      <div class="menu-item"><a href="userapp.php">Appointments</a></div>
      <div class="menu-item">Help</div>
      <div class="menu-item" onclick="location.href='logout.php'">Logout</div>
    </div>
    <div class="logo">WorkEase</div>
  </div>
  <button class="logout-btn" id="logout-button">Logout</button>
</header>

<div class="main-section">
  <div class="address-box">
    <h4>Hello! <?php echo htmlspecialchars($user['FullName']); ?></h4>
    <h3>Your City</h3>
    <p><?php echo htmlspecialchars($user['City']); ?></p>
  </div>

  <div class="search-container">
    <div class="search-wrapper">
      <input type="text" id="search-input" placeholder="Search...">
      <button class="search-btn" id="search-button">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
      </button>
    </div>
    <div class="recommendations" id="recommendations"></div>
  </div>
</div>

<section id="services">
            <h2>Our Services</h2>
            <div class="service-grid">
                <div class="service">
                    <a href="schedule_appointment.php?service=Babysitter"><img src="../includes\images\babysitter.jpg" alt="Babysitter"></a>
                    <h3>Babysitting</h3>
                </div>
                <div class="service">
                    <a href="schedule_appointment.php?service=Plumber"><img src="../includes\images/plumber.jpg" alt="Plumber"></a>
                    <h3>Plumber</h3>
                </div>
                <div class="service">
                    <a href="schedule_appointment.php?service=Electrician"><img src="../includes\images/electrician.jpg" alt="Electrician"></a>
                    <h3>Electrician</h3>
                </div>
           </div>
           <div class="service-grid">
                <div class="service">
                    <a href="schedule_appointment.php?service=Maid"><img src="../includes\images/maid.jpg" alt="Maid"></a>
                    <h3>Maid</h3>
                </div>
                <div class="service">
                    <a href="schedule_appointment.php?service=Sweeper"><img src="../includes\images/sweeper.jpg" alt="Sweeper"></a>
                    <h3>Sweeper</h3>
                </div>
                <div class="service">
                    <a href="schedule_appointment.php?service=Cook"><img src="../includes\images/cook.jpg" alt="Cook"></a>
                    <h3>Cook</h3>
                </div>
            </div>
        </section>

<script>
  const searchInput = document.getElementById('search-input');
  const recommendationsBox = document.getElementById('recommendations');
  const profileButton = document.getElementById('profile-button');
  const profileMenu = document.getElementById('profile-menu');
  const logoutButton = document.getElementById('logout-button');

  const recommendationsList = [
    'Plumber',
    'Electrician',
    'Carpenter',
    'Painter',
    'Maid Service',
    'Cook',
    'Gardener',
    'Driver',
    'House Cleaner',
    'Technician',
    'Security Guard'
  ];

  searchInput.addEventListener('input', function () {
    const inputValue = searchInput.value.toLowerCase();
    recommendationsBox.innerHTML = '';

    if (inputValue.trim() === '') {
      recommendationsBox.style.display = 'none';
      return;
    }

    const filtered = recommendationsList.filter(item =>
      item.toLowerCase().includes(inputValue)
    );

    if (filtered.length > 0) {
      filtered.forEach(item => {
        const div = document.createElement('div');
        div.className = 'recommendation';
        div.textContent = item;
        div.addEventListener('click', function () {
          searchInput.value = item;
          recommendationsBox.style.display = 'none';
        });
        recommendationsBox.appendChild(div);
      });
      recommendationsBox.style.display = 'block';
    } else {
      recommendationsBox.style.display = 'none';
    }
  });

  profileButton.addEventListener('click', function (event) {
    event.stopPropagation();
    profileMenu.classList.toggle('active');
  });

  logoutButton.addEventListener('click', function () {
    window.location.href = 'logout.php';
  });

  document.addEventListener('click', function (event) {
    if (!profileButton.contains(event.target) && !profileMenu.contains(event.target)) {
      profileMenu.classList.remove('active');
    }
    if (!searchInput.contains(event.target)) {
      recommendationsBox.style.display = 'none';
    }
  });
</script>

</body>
</html>
