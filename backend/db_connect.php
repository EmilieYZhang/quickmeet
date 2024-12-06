<?php
define('DB_HOST', 'mysql.cs.mcgill.ca');
define('DB_USER', 'comp307-hkacma');
define('DB_PASS', '772MnV0gk6UqmrK');
define('DB_NAME', 'fall2024-comp307-hkacma');

// Create a database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully!";
}
?>

