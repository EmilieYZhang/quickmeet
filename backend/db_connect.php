<?php

//require_once '../config/config.php';

// // create a database connection
//$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// check the connection
//if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
//}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mysql";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

