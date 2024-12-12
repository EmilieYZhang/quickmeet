<?php

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $fname = htmlspecialchars(trim($_POST['fname']));
    $lname = htmlspecialchars(trim($_POST['lname']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Validate required fields
    if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
        $error_message = "Please fill out all required fields.";
    }

    // Validate McGill email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@(?:mcgill\.ca|mail\.mcgill\.ca)$/', $email)) {
        $error_message = "Invalid McGill email address.";
    }

    // Default username to first name if not provided
    if (empty($username)) {
        $username = $fname;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (fname, lname, username, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fname, $lname, $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Redirect to the login page after successful registration
        header("Location: ../FrontEndCode/Login.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

