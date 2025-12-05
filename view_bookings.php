<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT booking_id, room_id, check_in, check_out, status FROM Booking WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>My Bookings</h1>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Room ID</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
            <th>Invoice</th>
            <th>Edit</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['booking_id']; ?></td>
                <td><?php echo $row['room_id']; ?></td>
                <td><?php echo $row['check_in']; ?></td>
                <td><?php echo $row['check_out']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><a href="invoice.php?booking_id=<?php echo $row['booking_id']; ?>">View Invoice</a></td>
                <td><a href="edit_booking.php?booking_id=<?php echo $row['booking_id']; ?>">Edit</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
