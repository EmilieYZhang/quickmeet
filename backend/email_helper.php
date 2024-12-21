<?php

// @author: Japmann Sarin
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer and configuration
require __DIR__ . '/../vendor/autoload.php'; // Composer autoloader
require __DIR__ . '/../config/config.php';   // Email settings from config.php

/**
 * Sends an email using PHPMailer.
 *
 * @param string $toEmail Recipient's email address
 * @param string $subject Subject of the email
 * @param string $body HTML content for the email body
 * @return bool Returns true if email was sent successfully, false otherwise
 */
function sendEmail($toEmail, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;         // SMTP host (from config.php)
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;     // SMTP username
        $mail->Password   = MAIL_PASSWORD;     // SMTP password (App Password)
        $mail->SMTPSecure = 'tls';
        $mail->Port       = MAIL_PORT;

        // Sender and Recipient
        $mail->setFrom(MAIL_USERNAME, 'Quickmeet Team'); // Sender email
        $mail->addAddress($toEmail);                    // Recipient's email

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}"); // Log the error
        echo "Email Error: {$mail->ErrorInfo}"; // Output the error for debugging
        return false;
    }
    }

?>
