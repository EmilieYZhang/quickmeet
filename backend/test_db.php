<?php
require 'db_connect.php';

// test the connection
$query = "SHOW TABLES";
$result = $conn->query($query);

if ($result) {
    echo "Tables in the database:<br>";
    while ($row = $result->fetch_array()) {
        echo $row[0] . "<br>";
    }
} else {
    echo "Query failed: " . $conn->error;
}

$conn->close();
?>

