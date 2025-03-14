<?php
header ('Content-Type: application/json');
require realpath(dirname(__DIR__) . '../../mail/vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($name) || empty($email) || empty($message)) {
        $response['message'] = "All fields are required.";
        echo json_encode($response);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '972f28c2f619db';
        $mail->Password = '4ef7c4e4b4b8aa';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email to Admin (You)
        $mail->setFrom($email, 'JobLink Contact Form');
        $mail->addAddress('rajaramsilwal819@gmail.com'); // Your email

        // Email Content for Admin
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body = "<h3>New Contact Form Message</h3>
                        <p><strong>Name:</strong> {$name}</p>
                        <p><strong>Email:</strong> {$email}</p>
                        <p><strong>Message:</strong> {$message}</p>";

        // Send Email to Admin
        if ($mail->send()) {
            // Now send an auto-reply to the user
            $mail->clearAddresses();
            $mail->addAddress($email); // User's email

            $mail->Subject = 'Thank you for contacting JobLink';
            $mail->Body = "<h3>Hello, {$name}</h3>
                            <p>Thank you for reaching out to us. We have received your message and will get back to you as soon as possible.</p>
                            <p>Best regards,<br>JobLink Support Team</p>";

            if ($mail->send()) {
                $response['success'] = true;
                $response['message'] = "Your message has been sent successfully!.";
            } else {
                $response['message'] = "Message sent, but failed to send confirmation email.";
            }
        } else {
            $response['message'] = "Failed to send email.";
        }
    } catch (Exception $e) {
        $response['message'] = "Mailer Error: " . $mail->ErrorInfo;
    }
}

echo json_encode($response);
?>
