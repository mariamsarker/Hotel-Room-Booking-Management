<?php
session_start();
require 'database.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.html");
    exit();
}

// Get the booking ID from the GET request
$booking_id = $_GET['booking_id'];

// Fetch booking details
$sql = "SELECT b.booking_id, b.check_in, b.check_out, b.status, b.created_at, 
               r.room_number, r.room_type, r.price 
        FROM Booking b
        JOIN Room r ON b.room_id = r.room_id
        WHERE b.booking_id = ? AND b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $booking_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No booking found or you don't have permission to view this invoice.";
    exit();
}

$booking = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice for Booking ID: <?php echo $booking['booking_id']; ?></title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .invoice-container {
            background-color: #ffffff;
            padding: 20px;
            margin: auto;
            width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-details th, .invoice-details td {
            padding: 10px;
            border: 1px solid #cccccc;
            text-align: left;
        }
        .invoice-footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Invoice</h1>
            <p>Booking ID: <?php echo $booking['booking_id']; ?></p>
        </div>
        <div class="invoice-details">
            <table>
                <tr>
                    <th>Room Number</th>
                    <td><?php echo $booking['room_number']; ?></td>
                </tr>
                <tr>
                    <th>Room Type</th>
                    <td><?php echo $booking['room_type']; ?></td>
                </tr>
                <tr>
                    <th>Check-in Date</th>
                    <td><?php echo $booking['check_in']; ?></td>
                </tr>
                <tr>
                    <th>Check-out Date</th>
                    <td><?php echo $booking['check_out']; ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?php echo $booking['status']; ?></td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>$<?php echo number_format($booking['price'], 2); ?></td>
                </tr>
                <tr>
                    <th>Booking Date</th>
                    <td><?php echo $booking['created_at']; ?></td>
                </tr>
            </table>
        </div>
        <div class="invoice-footer">
            <p>Thank you for booking with us!</p>
        </div>
    </div>
</body>
</html>
