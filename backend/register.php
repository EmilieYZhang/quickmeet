<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = htmlspecialchars(trim($_POST['fname']));
    $lname = htmlspecialchars(trim($_POST['lname']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    //validate required fields
    if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
        die("Please fill out all required fields.");
    }

    // validate McGill email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@(?:mcgill\.ca|mail\.mcgill\.ca)$/', $email)) {
        die("Invalid McGill email address.");
    }

    if (empty($username)) {
        $username = $fname;
    }

    // hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // insert into the database
    $stmt = $conn->prepare("INSERT INTO users (fname, lname, username, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fname, $lname, $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "Registration successful! <a href='../frontend/login_form.php'>Login now</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
