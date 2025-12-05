<?php
session_start();
require 'database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "SELECT r.room_type, COUNT(b.booking_id) as bookings, SUM(r.price) as revenue 
        FROM Booking b 
        JOIN Room r ON b.room_id = r.room_id 
        GROUP BY r.room_type";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Report</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Booking Report</h1>
    <table>
        <tr>
            <th>Room Type</th>
            <th>Number of Bookings</th>
            <th>Total Revenue</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                <td><?php echo htmlspecialchars($row['bookings']); ?></td>
                <td><?php echo htmlspecialchars($row['revenue']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>