<?php
require 'db_connect.php';
session_start();

if (!isset($_SESSION['ticket'])) {
    // no ticket in session, redirect to login
    header("Location: login.php");
    exit();
}

$ticket = $_SESSION['ticket'];

// validate the ticket
$stmt = $conn->prepare("SELECT user_id, expiry FROM user_tickets WHERE ticket = ?");
$stmt->bind_param("s", $ticket);
$stmt->execute();
$stmt->bind_result($userId, $expiry);
$stmt->fetch();

if (!$userId || time() > $expiry) {
    // invalid or expired ticket, redirect to login
    echo "Session expired. Please log in again.";
    session_destroy();
    header("Location: login.php");
    exit();
}

echo "Welcome, User ID: $userId";

// refresh the ticket expiry time
$newExpiry = time() + 3600;
$stmt = $conn->prepare("UPDATE user_tickets SET expiry = ? WHERE ticket = ?");
$stmt->bind_param("is", $newExpiry, $ticket);
$stmt->execute();

$stmt->close();
$conn->close();
?>

