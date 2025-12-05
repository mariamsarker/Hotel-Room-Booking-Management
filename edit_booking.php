<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.html");
    exit();
}

if (!isset($_GET['booking_id'])) {
    echo "No booking ID provided.";
    exit();
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // Check if the room is available for the new dates
    $sql = "SELECT * FROM Booking WHERE room_id = (SELECT room_id FROM Booking WHERE booking_id = ?) AND booking_id != ? AND (check_in < ? AND check_out > ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiss', $booking_id, $booking_id, $check_out, $check_in);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Room is available, proceed with updating booking
        $sql = "UPDATE Booking SET check_in = ?, check_out = ? WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $check_in, $check_out, $booking_id);

        if ($stmt->execute()) {
            echo "Booking updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        // Room is not available
        echo "The room is not available for the selected dates. Please choose different dates.";
    }

    $stmt->close();
    $conn->close();
} else {
    // Fetch the current booking details
    $sql = "SELECT check_in, check_out FROM Booking WHERE booking_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $booking_id, $user_id);
    $stmt->execute();
    $stmt->bind_result($check_in, $check_out);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .edit-booking-container {
            width: 300px;
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

        label {
            margin-bottom: 5px;
            color: #333333;
        }

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
    </style>
</head>
<body>
    <div class="edit-booking-container">
        <h1>Edit Booking</h1>
        <form action="edit_booking.php?booking_id=<?php echo $booking_id; ?>" method="POST">
            <label for="check_in">Check-in Date:</label><br>
            <input type="date" id="check_in" name="check_in" value="<?php echo htmlspecialchars($check_in); ?>" required><br>
            <label for="check_out">Check-out Date:</label><br>
            <input type="date" id="check_out" name="check_out" value="<?php echo htmlspecialchars($check_out); ?>" required><br><br>
            <input type="submit" value="Update Booking">
        </form>
    </div>
</body>
</html>
