<?php 
    
    require realpath(dirname(__DIR__).'/vendor/autoload.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
        $host = 'localhost';
        $dbname = 'project_task_management_system';
        $db_username = 'root';
        $db_password = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
            exit;
        }

    function sendMail($name, $username, $password, $email){
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host ='sandbox.smtp.mailtrap.io'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = '6fc36dfac60d8b'; // SMTP username
            $mail->Password = 'e3b9e7d2a88423'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@example.com', 'Your Company');
            $mail->addAddress($email); // User's email address

            $mail->isHTML(true);
            $mail->Subject = 'Your Account Details';
            $mail->Body    = "
                <h3>Welcome, $name!</h3>
                <p>Your manager account has been created successfully.</p>
                <p><b>Username:</b> $username</p>
                <p><b>Password:</b> $password</p>
                <p>Best regards,<br>Your Team</p>
            ";

            $mail->send();
            echo " Email sent to $email.";
            
        } catch (Exception $e) {
            echo "Data inserted, but email could not be sent. Error: {$mail->ErrorInfo}";
        }
    }
?>