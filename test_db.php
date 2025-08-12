<?php
// Database connection test
$host = "localhost";
$username = "root";
$password = "";
$database = "restaurant_reservations";

echo "<h2>Testing Database Connection</h2>";

// Test connection
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    echo "<p style='color: red;'>❌ Connection failed: " . mysqli_connect_error() . "</p>";
} else {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test if database exists
    $result = mysqli_query($conn, "SHOW DATABASES LIKE '$database'");
    if (mysqli_num_rows($result) > 0) {
        echo "<p style='color: green;'>✅ Database '$database' exists</p>";
        
        // Test if table exists
        $result = mysqli_query($conn, "SHOW TABLES LIKE 'reservations'");
        if (mysqli_num_rows($result) > 0) {
            echo "<p style='color: green;'>✅ Table 'reservations' exists</p>";
            
            // Count records
            $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations");
            $row = mysqli_fetch_assoc($result);
            echo "<p style='color: blue;'>📊 Total reservations: " . $row['count'] . "</p>";
        } else {
            echo "<p style='color: red;'>❌ Table 'reservations' does not exist</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Database '$database' does not exist</p>";
    }
    
    mysqli_close($conn);
}

echo "<hr>";
echo "<h3>XAMPP Status Check:</h3>";
echo "<p>Make sure both Apache and MySQL are running in XAMPP Control Panel</p>";
echo "<p>MySQL should be on port 3306 (default)</p>";
echo "<p>Check if you can access: <a href='http://localhost/phpmyadmin' target='_blank'>phpMyAdmin</a></p>";
?>
