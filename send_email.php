<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Composer's autoloader
require 'vendor/autoload.php'; // Ensure the path is correct

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name    = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email   = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '';
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

    // Initialize an array to hold error messages
    $errors = [];

    // Validate required fields
    $requiredFields = [
        'Name'    => $name,
        'Email'   => $email,
        'Subject' => $subject,
        'Message' => $message
    ];

    foreach ($requiredFields as $fieldName => $fieldValue) {
        if (empty($fieldValue)) {
            $errors[] = "The field '{$fieldName}' is required.";
        }
    }

    // Additional specific validations
    if (!$email) {
        $errors[] = "Invalid email address.";
    }

    // If there are errors, redirect back with error flags
    if (!empty($errors)) {
        // Optionally, you can pass specific error messages via session or other methods
        // For simplicity, redirecting with a generic error flag
        header("Location: contact-us.html?error=1");
        exit;
    }

    // Construct the email body
    $body = "
        <html>
        <head>
            <title>Contact Form Submission</title>
        </head>
        <body>
            <h2>Contact Form Submission</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Subject:</strong> {$subject}</p>
            <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
        </body>
        </html>
    ";

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Enable verbose debug output (disable in production)
        $mail->SMTPDebug = 0; // 0 = off (for production), 2 = verbose
        $mail->Debugoutput = 'html';

        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;             // Enable SMTP authentication
        $mail->Username   = 'Samiasidd877@gmail.com'; // SMTP username
        $mail->Password   = 'uxvlhkjehpjmnzuy';    // SMTP password (App Password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; PHPMailer::ENCRYPTION_SMTPS encouraged
        $mail->Port       = 587;              // TCP port to connect to

        // Recipients
        $mail->setFrom('Samiasidd877@gmail.com', 'Samia Sidd'); // Sender's email and name
        $mail->addAddress('samiasidd877@gmail.com', 'Recipient Name'); // Add a recipient
        $mail->addReplyTo($email, $name); // Add a reply-to address

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Optional: Add plain text version for non-HTML email clients
        $mail->AltBody = "Contact Form Submission\n\nName: {$name}\nEmail: {$email}\nSubject: {$subject}\nMessage:\n{$message}";

        // Send the email
        $mail->send();
        // Redirect back with success flag
        header("Location: contact-us.html?success=1");
        exit;
    } catch (Exception $e) {
        // Log the error message (optional)
        // error_log("Mailer Error: {$mail->ErrorInfo}");

        // Redirect back with error flag
        header("Location: contact-us.html?error=1");
        exit;
    }
}
?>
