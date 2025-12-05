<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

require 'database.php';

$room_id = $_GET['room_id'];

$sql = "DELETE FROM Room WHERE room_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $room_id);

if ($stmt->execute()) {
    echo "Room deleted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: manage_rooms.php");
exit();
?>
