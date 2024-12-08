<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // validate McGill email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@(?:mcgill\.ca|mail\.mcgill\.ca)$/', $email)) {
        die("Invalid McGill email address.");
    }

    // hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // insert into the database
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

