<?php
require 'db_connect.php';
session_start();

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Fetch user details
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    if ($stmt === false) {
        die("Database query failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId, $hashedPassword);
    $stmt->fetch();
    $stmt->close(); // Close the SELECT statement

    if ($userId && password_verify($password, $hashedPassword)) {
        // Generate a secure ticket
        $ticket = bin2hex(random_bytes(32));
        $expiry = time() + 3600; // 1-hour expiry

        // Store the ticket in the database
        $stmt = $conn->prepare("INSERT INTO user_tickets (user_id, ticket, expiry) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Database query failed: " . $conn->error);
        }

        $stmt->bind_param("isi", $userId, $ticket, $expiry);
        $stmt->execute();
        $stmt->close(); // Close the INSERT statement

        // Save the ticket in the session
        $_SESSION['ticket'] = $ticket;

        // Redirect to Dashboard.html
        header("Location: ../FrontEndCode/Dashboard.html");
        exit(); // Ensure no further code is executed
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid email or password.'); window.location.href='../FrontEndCode/Login.html';</script>";
        exit();
    }
}

$conn->close();
?>

