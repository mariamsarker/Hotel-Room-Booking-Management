<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];
    $availability = isset($_POST['availability']) ? 1 : 0;

    $sql = "UPDATE Room SET room_number = ?, room_type = ?, price = ?, availability = ? WHERE room_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdii', $room_number, $room_type, $price, $availability, $room_id);

    if ($stmt->execute()) {
        echo "Room updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $room_id = $_GET['room_id'];
    $sql = "SELECT room_number, room_type, price, availability FROM Room WHERE room_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $room_id);
    $stmt->execute();
    $stmt->bind_result($room_number, $room_type, $price, $availability);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Room</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <h1>Edit Room</h1>
    <form action="edit_room.php" method="POST">
        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
        <label for="room_number">Room Number:</label><br>
        <input type="text" id="room_number" name="room_number" value="<?php echo htmlspecialchars($room_number); ?>" required><br>
        <label for="room_type">Room Type:</label><br>
        <input type="text" id="room_type" name="room_type" value="<?php echo htmlspecialchars($room_type); ?>" required><br>
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" required><br>
        <label for="availability">Availability:</label>
        <input type="checkbox" id="availability" name="availability" <?php echo $availability ? 'checked' : ''; ?>><br><br>
        <input type="submit" value="Update Room">
    </form>
</body>
</html>
