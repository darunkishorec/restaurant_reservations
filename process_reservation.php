<?php
header('Content-Type: application/json');

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "restaurant_reservations";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get form data
$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$guests = mysqli_real_escape_string($conn, $_POST['guests']);
$date = mysqli_real_escape_string($conn, $_POST['date']);
$time = mysqli_real_escape_string($conn, $_POST['time']);
$message = mysqli_real_escape_string($conn, $_POST['message']);

// Basic validation
if (empty($name) || empty($email) || empty($phone) || empty($guests) || empty($date) || empty($time)) {
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
    exit;
}

// Check if date is in the future
if ($date <= date('Y-m-d')) {
    echo json_encode(['success' => false, 'message' => 'Please select a future date']);
    exit;
}

// Generate reservation ID
$reservation_id = 'RES' . date('Ymd') . rand(100, 999);

// Insert into database
$sql = "INSERT INTO reservations (reservation_id, guest_name, email, phone, guest_count, reservation_date, reservation_time, special_requests, status, created_at) 
        VALUES ('$reservation_id', '$name', '$email', '$phone', '$guests', '$date', '$time', '$message', 'confirmed', NOW())";

if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true, 'message' => 'Reservation booked successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?> 