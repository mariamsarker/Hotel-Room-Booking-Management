<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];
    $availability = isset($_POST['availability']) ? 1 : 0;

    $sql = "INSERT INTO Room (room_number, room_type, price, availability) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdi', $room_number, $room_type, $price, $availability);

    if ($stmt->execute()) {
        echo "Room added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Room</title>
    <style>
         <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333333;
        }

        form {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        label {
            margin-bottom: 5px;
            color: #333333;
        }

        input[type="text"],
        select,
        input[type="checkbox"] {
            width: calc(100% - 22px); /* Adjusted width to account for checkbox */
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
    </style>
</head>
<body>
    <h1>Add New Room</h1>
    <form action="add_room.php" method="POST">
        <label for="room_number">Room Number:</label><br>
        <input type="text" id="room_number" name="room_number" required><br>
        <label for="room_type">Room Type:</label><br>
        <select id="room_type" name="room_type" required>
            <option value="Single">Single</option>
            <option value="Double">Double</option>
            <option value="Suite">Suite</option>
            <option value="Deluxe">Deluxe</option>
        </select><br>
        
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" required><br>
        <label for="availability">Availability:</label>
        <input type="checkbox" id="availability" name="availability"><br><br>
        <input type="submit" value="Add Room">
    </form>
</body>
</html>
