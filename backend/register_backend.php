<?php

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = htmlspecialchars(trim($_POST['fname']));
    $lname = htmlspecialchars(trim($_POST['lname']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // validate required fields
    if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
        $error_message = "Please fill out all required fields.";
    }

    // validate McGill email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@(?:mcgill\.ca|mail\.mcgill\.ca)$/', $email)) {
        $error_message = "Invalid McGill email address.";
    }

    // check if email is already registered
    if (!isset($error_message)) { 
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // email already exists
            $error_message = "Email address is already registered. Please go to Login page.";
        }
        
        $stmt->close();
    }

    if (!isset($error_message)) {
        if (empty($username)) {
            $username = $fname;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (fname, lname, username, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fname, $lname, $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "
		<script>
		    alert('Registration successful! You will now be redirected to the login page.');
		    window.location.href = '../FrontEndCode/Login.html';
		</script>
	    ";
	    exit();

        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<script>alert('$error_message'); window.location.href = '../FrontEndCode/Register.html';</script>";
    }

    $conn->close();
}
?>
