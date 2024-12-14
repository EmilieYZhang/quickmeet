<?php
require 'db_connect.php';
session_start();

if (isset($_SESSION['ticket'])) {
    $stmt = $conn->prepare("DELETE FROM user_tickets WHERE ticket = ?");
    $stmt->bind_param("s", $_SESSION['ticket']);
    $stmt->execute();
    $stmt->close();
}

// destroy the session
session_destroy();

// redirect to login
header("Location: login.php");
exit();
?>

