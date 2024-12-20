<?php
require 'db_connect.php'; // Include database connection
session_start();

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Validate required fields
    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and password are required!'); window.location.href='../FrontEndCode/Login.html';</script>";
        exit();
    }

    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT id, fname, password FROM users WHERE email = ?");
    if ($stmt === false) {
        die("Database query failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId, $firstName, $hashedPassword);
    $stmt->fetch();
    $stmt->close();

    // Check if user exists and password is correct
    if ($userId && password_verify($password, $hashedPassword)) {
        // Generate a secure session ticket
        $ticket = bin2hex(random_bytes(32)); // Generate a 64-character ticket
        $expiry = time() + 3600; // 1-hour expiry

        // Store the ticket in the database
        $stmt = $conn->prepare("INSERT INTO user_tickets (user_id, ticket, expiry) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Database query failed: " . $conn->error);
        }

        $stmt->bind_param("isi", $userId, $ticket, $expiry);
        $stmt->execute();
        $stmt->close();

        // Save session variables
        $_SESSION['ticket'] = $ticket;
        $_SESSION['userId'] = $userId;
        $_SESSION['firstName'] = $firstName;

        // Redirect to the dashboard
        header("Location: ../backend/dashboard.php");
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid email or password!'); window.location.href='../FrontEndCode/Login.html';</script>";
        exit();
    }
}

// Close database connection
$conn->close();
?>

