<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: user_login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

 .dashboard-container {
    width: 80%;
    margin: 50px auto;
    padding: 20px;
    background-color: #ffffff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}

h1 {
    text-align: center;
    color: #333333;
}

 nav {
    text-align: center;
            margin-bottom: 20px;
}



nav ul {
    list-style-type: none;
    padding: 0;
}

nav ul li {
    display: inline;
            margin: 0 10px;
}

nav ul li a {
    text-decoration: none;
            color: #007bff;
            padding: 10px 20px;
            border: 1px solid #007bff;
            border-radius: 5px;
}

nav ul li a:hover {
    background-color: #007bff;
            color: #ffffff;
}
   
</style>


</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <nav>
            <ul>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="book_room.php">Book a Room</a></li>
                <li><a href="view_bookings.php">View Bookings</a></li>
                <li><a href="user_logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>