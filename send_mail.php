<?php
// Load Composer's autoloader or include PHPMailer classes manually if not using Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Get form data
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';

// Validate
if (!$name || !$email || !$message || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'All fields are required.';
    exit;
}

if (!getenv('SMTP_PASS')) {
    http_response_code(500);
    error_log('SMTP_PASS environment variable is not set');
    echo 'Message could not be sent.';
    exit;
}

$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Set your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'prossienabbona20@gmail.com'; // Your Gmail address
    $mail->Password   = getenv('SMTP_PASS'); // Set SMTP_PASS in Render > Environment, never in code
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    //Recipients
    // Gmail only sends as the authenticated account; put the visitor in Reply-To
    $mail->setFrom('prossienabbona20@gmail.com', 'Portfolio contact form');
    $mail->addReplyTo($email, $name);
    $mail->addAddress('prossienabbona20@gmail.com', 'Nabbona Prossy'); // Your receiving email

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Portfolio message from ' . $name;
    $safeName  = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $mail->Body    = "<b>Name:</b> $safeName<br><b>Email:</b> $safeEmail<br><b>Message:</b><br>" . nl2br(htmlspecialchars($message));

    $mail->send();
    echo 'Message sent successfully!';
} catch (Exception $e) {
    http_response_code(500);
    error_log('Mailer Error: ' . $mail->ErrorInfo);
    echo 'Message could not be sent.';
}
