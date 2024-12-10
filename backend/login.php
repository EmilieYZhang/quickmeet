<?php
require 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // fetch user details
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    if ($userID && password_verify($password, $hashedPassword)) {
        // generate a secure ticket
        $ticket = bin2hex(random_bytes(32));
        $expiry = time() + 3600;

        // store the ticket in the database
        $stmt = $conn->prepare("INSERT INTO user_tickets (user_id, ticket, expiry) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $userId, $ticket, $expiry);
        $stmt->execute();

        // save the ticket in the session
        $_SESSION['ticket'] = $ticket;

        // redirect to the dashboard
        header("Location: dashboard.php");
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
}
$conn->close();
?>
