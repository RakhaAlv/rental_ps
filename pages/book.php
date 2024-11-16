<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Harga per jam untuk setiap jenis PlayStation
$prices = [
    "PS3" => 6000,
    "PS4" => 12000,
    "PS4 Pro" => 15000,
    "PS5" => 25000,
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $playstation_type = $_POST['playstation_type'];
    $booking_time = $_POST['booking_time'];
    $duration = $_POST['duration'];
    $user_id = $_SESSION['user_id'];

    // Hitung total biaya
    $price_per_hour = $prices[$playstation_type];
    $total_amount = $price_per_hour * $duration;

    // Insert booking ke database
    $insert_booking_query = "INSERT INTO bookings (user_id, playstation_type, booking_time, duration, total_amount) VALUES ('$user_id', '$playstation_type', '$booking_time', '$duration', '$total_amount')";
    
    if ($conn->query($insert_booking_query) === TRUE) {
        $booking_id = $conn->insert_id; // Ambil ID booking yang baru saja ditambahkan
        echo "<p class='success-message'>Booking successful! Total amount: Rp " . number_format($total_amount, 0, ',', '.') . ". Please proceed to payment.</p>";
        echo "<a href='payment.php?booking_id=$booking_id' class='futuristic-button'>Proceed to Payment</a>";
    } else {
        echo "<p class='error-message'>Error: " . $insert_booking_query . "<br>" . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book a PlayStation</title>
    <link rel="stylesheet" type="text/css" href="../Design/style.css"> <!-- Link ke CSS -->
</head>
<body>
    <div class="booking-container">
        <h2>Book a PlayStation</h2>
        <form method="POST" action="">
            <select name="playstation_type" required>
                <option value="PS3">PS3</option>
                <option value="PS4">PS4</option>
                <option value="PS4 Pro">PS4 Pro</option>
                <option value="PS5">PS5</option>
            </select><br>
            <input type="datetime-local" name="booking_time" required><br>
            <input type="number" name="duration" placeholder="Duration (hours)" required><br>
            <button type="submit">Book</button>
        </form>
    </div>
</body>
</html>