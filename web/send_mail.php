<?php
// Import PHP mailer class 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Check if the contact form was submitted
if (isset($_POST['send_contact_message'])) {

    // Define user inputs 
    $name    = htmlspecialchars($_POST['name']);
    $email   = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.'); window.location.href='sjz.php#contact';</script>";
        exit;
    }

    // Create a new PHP Mailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';                   // SMTP Protocol
        $mail->SMTPAuth   = true;                               // Gmail SMTP server
        $mail->Username   = 'jzheng0512@gmail.com';             // Your Gmail address
        $mail->Password   = 'ifmj okzn swaa dlya';              // Gmail app password (not regular password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS Encryption
        $mail->Port       = 587;                                // TCP port

        // Recipients
        $mail->setFrom('your_email@gmail.com', 'Portfolio Contact');
        $mail->addAddress('jzheng0512@gmail.com');         // Your destination email
        $mail->addReplyTo($email, $name);                  // Reply to sender

        // Content
        $mail->isHTML(false);
        $mail->Subject = "New Message from Contact Form";
        $mail->Body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";

        // Send the email
        $mail->send();

        // Show a success message and redirect to contact section
        echo "<script>alert('Message sent successfully!'); window.location.href='sjz.php#contact';</script>";
    } catch (Exception $e) {
        // Show error 
        echo "<script>alert('Mailer Error: " . $mail->ErrorInfo . "'); window.location.href='sjz.php#contact';</script>";
    }
} else {
    // Handle invalid message.
    echo "<script>alert('Invalid form submission.'); window.location.href='sjz.php#contact';</script>";
}
?>
