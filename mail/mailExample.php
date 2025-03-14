<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
$errors = [];
$errorMessage = '';
$successMessage = '';
$siteKey = ''; // reCAPTCHA site key
$secret = ''; // reCAPTCHA secret key
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $message = sanitizeInput($_POST['message']);
    if (empty($name)) {
        $errors[] = 'Name is empty';
    }
    if (empty($email)) {
        $errors[] = 'Email is empty';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }
    if (empty($message)) {
        $errors[] = 'Message is empty';
    }
    if (!empty($errors)) {
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
    } else {
        //  $toEmail = 'chapagainbasanta@gmail.com';
        $toEmail = $email;
        $emailSubject = 'New email from your contaсt form';
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        try {
            // Configure the PHPMailer instance
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '01c87ee6acc466';
            $mail->Password = 'f1bb86e1ffb7dc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            // Set the sender, recipient, subject, and body of the message
            $mail->setFrom($email);
            $mail->addAddress($toEmail);
            $mail->Subject = $emailSubject;
            $mail->isHTML(true);
            $mail->Body = "<p>Name: {$name}</p><p>Email: {$email}</p><p>Message: 
        {$message}</p>";
            // Send the message
            $mail->send();
            $successMessage = "<p style='color: green;'>Thank you for contacting us :)</p>";
        } catch (Exception $e) {
            $errorMessage = "<p style='color: red;'>Oops, something went wrong. Please try again 
         later</p>";
        }
    }
}
function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}
?>
<html>

<body>

    <form action="" method="post" id="contact-form">
        <h2>Contact us</h2>
        <?php echo ((!empty($errorMessage)) ? $errorMessage : '') ?>
        <?php echo ((!empty($successMessage)) ? $successMessage : '') ?>
        <p>
            <label>First Name:</label>
            <input name="name" type="text" required />
        </p>
        <p>
            <label>Email Address:</label>
            <input style="cursor: pointer;" name="email" type="email" required />
        </p>
        <p>
            <label>Message:</label>
            <textarea name="message" required></textarea>
        </p>
        <p>
            <button type="submit">
                Submit
            </button>
        </p>
    </form>
</body>

</html>