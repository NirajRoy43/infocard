<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

// Get the visitor's IP address
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

// Get location details from ipinfo.io
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

// Extract details
$location = $details->city . ', ' . $details->region . ', ' . $details->country;
$isp = $details->org;

// PHPMailer instance
$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';  // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com'; // Replace with your SMTP username
    $mail->Password = 'your_email_password';   // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('your_site@example.com', 'Your Site');
    $mail->addAddress('your_email@example.com', 'Your Name');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'New Visitor Logged';
    $mail->Body    = "IP: $ip<br>Location: $location<br>ISP: $isp";
    $mail->AltBody = "IP: $ip\nLocation: $location\nISP: $isp";

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
