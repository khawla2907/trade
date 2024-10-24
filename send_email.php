<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php'; 

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'samiasidd877@gmail.com;  // Your email
        $mail->Password   = 'lxqe unkl uosf cchi';     // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        //Recipients
        $mail->setFrom($email, $name);  // Sender's email and name
        $mail->addAddress('samiasidd877@gmail.com');  // Your own email to receive the form data
        
        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "<h2>Contact Form Submission</h2>
                          <p><strong>Name:</strong> {$name}</p>
                          <p><strong>Email:</strong> {$email}</p>
                          <p><strong>Subject:</strong> {$subject}</p>
                          <p><strong>Message:</strong><br>{$message}</p>";
        
        $mail->send();
        
        // Redirect back to the contact page with a success flag
        header("Location: contact%20us.html?success=1");
        exit;
    } catch (Exception $e) {
        // Redirect back to the contact page with an error flag
        header("Location: contact%20us.html?error=1");
        exit;
    }
}
?>
