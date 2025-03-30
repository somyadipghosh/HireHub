<?php
session_start();
error_reporting(0);
require('../config/db.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ensure PHPMailer is included properly
require('../config/db.php');

if (isset($_POST['new-email'])) {
    $email = $_POST['new-email'];
    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_email'] = $email;

    // PHPMailer setup
    $mail = new PHPMailer(true);
    
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com'; // Use your mail provider's SMTP
        $mail->SMTPAuth = true;
        $mail->Username = '88a560001@smtp-brevo.com'; // Your email
        $mail->Password = 'JWdmswArvkxBPH9a'; // Your email password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email details
        $mail->setFrom('hirehubbusiness@gmail.com', 'HireHub');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP for verification is: $otp.";

        if ($mail->send()) {
            echo "<script>alert('OTP sent successfully!'); window.location.href='../views/registration.php';</script>";
        } else {
            echo "<script>alert('OTP sending failed! Please try again.'); window.location.href='../views/registration.php';</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Mail Error: " . $mail->ErrorInfo . "'); window.location.href='../views/registration.php';</script>";
    }
} else {
    echo "<script>alert('Invalid Request'); window.location.href='../views/registration.php';</script>";
}
?>