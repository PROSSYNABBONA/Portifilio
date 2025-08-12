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
if (!$name || !$email || !$message) {
    http_response_code(400);
    echo 'All fields are required.';
    exit;
}

$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Set your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'prossienabbona20@gmail.com'; // Your Gmail address
    $mail->Password   = 'yjmzxbusvlbikqgb'; // Your Gmail app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom($email, $name);
    $mail->addAddress('prossienabbona20@gmail.com', 'Your Name'); // Your receiving email

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Message from Portifilio ' . $name;
    $mail->Body    = "<b>Name:</b> $name<br><b>Email:</b> $email<br><b>Message:</b><br>" . nl2br(htmlspecialchars($message));

    $mail->send();
    echo 'Message sent successfully!';
} catch (Exception $e) {
    http_response_code(500);
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
