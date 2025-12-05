<?php
// Include your database connection
require 'database.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Assuming the user is logged in and session is set
    } else {
        echo "You need to be logged in to book a room.";
        exit();
    }

    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // Check if the room is available for the given dates
    $sql = "SELECT * FROM Booking WHERE room_id = ? AND (check_in < ? AND check_out > ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $room_id, $check_out, $check_in);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Room is available, proceed with booking
        $sql = "INSERT INTO Booking (user_id, room_id, check_in, check_out) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiss', $user_id, $room_id, $check_in, $check_out);

        if ($stmt->execute()) {
            echo "Room booked successfully!";
            
            // The email sending code has been removed
        } else {
            echo "Error: " . $stmt->error;
        }
        
    } else {
        // Room is not available
        echo "The room is not available for the selected dates. Please choose different dates or a different room.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Room Booking</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
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
        input[type="date"] {
            width: 100%;
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
        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .rooms-container {
            width: 80%;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .room {
            background-color: #ffffff;
            padding: 15px;
            margin: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 200px;
            text-align: center;
        }

        .room img {
            max-width: 100%;
            border-radius: 5px;
        }

        .room h3 {
            color: #333333;
        }

        .room p {
            color: #666666;
        }
    </style>
</head>
<body>
    <h1>Book a Room</h1>
    <form action="book_room.php" method="POST">
        <label for="room_id">Room ID:</label><br>
        <input type="text" id="room_id" name="room_id" required><br>
        <label for="check_in">Check-in Date:</label><br>
        <input type="date" id="check_in" name="check_in" required><br>
        <label for="check_out">Check-out Date:</label><br>
        <input type="date" id="check_out" name="check_out" required><br><br>
        <input type="submit" value="Book">
        </form>
        <div class="rooms-container">
        <div class="room">
            <img src="pic1.png" alt="Single Room">
            <h3>Single Room</h3>
            <p>Room ID: 1,2,3</p>
            <p>Price: $50.00 per night</p>
        </div>
        <div class="room">
            <img src="pic2.png" alt="Double Room">
            <h3>Double Room</h3>
            <p>Room ID: 4,5,6</p>
            <p>Price: $80.00 per night</p>
        </div>
        <div class="room">
            <img src="pic3.png" alt="Suite">
            <h3>Suite</h3>
            <p>Room ID: 7,8,9</p>
            <p>Price: $150.00 per night</p>
        </div>
        <div class="room">
            <img src="pic4.png" alt="Deluxe Room">
            <h3>Deluxe Room</h3>
            <p>Room ID: 10,11,12</p>
            <p>Price: $200.00 per night</p>
        </div>
    </div>
        
    
</body>
</html>