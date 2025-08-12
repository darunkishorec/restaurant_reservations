<?php
header('Content-Type: application/json');

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "restaurant_reservations";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}

// Get form data (basic sanitization; prepared statements used for DB)
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$guests = isset($_POST['guests']) ? (int)$_POST['guests'] : 0;
$date = isset($_POST['date']) ? trim($_POST['date']) : '';
$time = isset($_POST['time']) ? trim($_POST['time']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Basic validation
if (empty($name) || empty($email) || empty($phone) || empty($guests) || empty($date) || empty($time)) {
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
    exit;
}

// Check if date is today or in the future
if ($date < date('Y-m-d')) {
    echo json_encode(['success' => false, 'message' => 'Please select today or a future date']);
    exit;
}

// Generate reservation ID
$reservation_id = 'RES' . date('Ymd') . rand(100, 999);

// Insert into database using prepared statement
$sql = "INSERT INTO reservations (reservation_id, guest_name, email, phone, guest_count, reservation_date, reservation_time, special_requests, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', NOW())";

$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'sssiisss', $reservation_id, $name, $email, $phone, $guests, $date, $time, $message);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($ok) {
        echo json_encode(['success' => true, 'message' => 'Reservation booked successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving reservation']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
}

mysqli_close($conn);
?> 