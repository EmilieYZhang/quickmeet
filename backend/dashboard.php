<?php
require 'db_connect.php';
session_start();

if (!isset($_SESSION['ticket'])) {
    // No ticket in session, redirect to login
    header("Location: login.php");
    exit();
}

$ticket = $_SESSION['ticket'];

// Validate the ticket
$stmt = $conn->prepare("SELECT user_id, expiry FROM user_tickets WHERE ticket = ?");
if ($stmt === false) {
    // If the statement preparation fails, show an error
    echo "Database query failed.";
    exit();
}

$stmt->bind_param("s", $ticket);
$stmt->execute();
$stmt->bind_result($userId, $expiry);
$stmt->fetch();

// Check if the ticket is valid and not expired
if (!$userId || time() > $expiry) {
    // Invalid or expired ticket, redirect to login
    session_destroy();
    header("Location: login.php");
    exit();
}

// Welcome message
echo "Welcome, User ID: $userId";

// Refresh the ticket expiry time by extending it (1 hour from now)
$newExpiry = time() + 3600;  // 3600 seconds = 1 hour
$updateStmt = $conn->prepare("UPDATE user_tickets SET expiry = ? WHERE ticket = ?");
if ($updateStmt === false) {
    // If the update query fails, handle it
    echo "Database update failed.";
    exit();
}

$updateStmt->bind_param("is", $newExpiry, $ticket);
$updateStmt->execute();

// Close the prepared statements and database connection
$updateStmt->close();
$stmt->close();
$conn->close();
?>

