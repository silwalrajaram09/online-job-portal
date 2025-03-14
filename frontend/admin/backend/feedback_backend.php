<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../mail/vendor/autoload.php'; 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_job_portal";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
            case 'get_feedback':
                $stmt = $conn->prepare("SELECT * FROM feedback");
                $stmt->execute();
                $feedbackList = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'feedback' => $feedbackList]);
                break;

            case 'update_status':
                $feedbackId = $_POST['feedback_id'];
                $status = $_POST['status'];

                $stmt = $conn->prepare("UPDATE feedback SET status = :status WHERE id = :feedback_id");
                $stmt->bindParam(':feedback_id', $feedbackId);
                $stmt->bindParam(':status', $status);

                if ($stmt->execute() && $status === 'reviewed') {
                    $email = 'silwalrajaram2@gmail.com'; // Static email (replace with dynamic)

                    // PHPMailer setup
                    $mail = new PHPMailer(true);
                    try {
                        // SMTP configuration (replace with your settings)
                        $mail->isSMTP();
                        $mail->Host = 'sandbox.smtp.mailtrap.io'; // Example Mailtrap
                        $mail->SMTPAuth = true;
                        $mail->Username = '01c87ee6acc466'; // Example Mailtrap
                        $mail->Password = 'f1bb86e1ffb7dc'; // Example Mailtrap
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Email content
                        $mail->setFrom('admin@gmail.com', 'Admin'); // Replace with your domain
                        $mail->addAddress($email);
                        $mail->Subject = "Feedback Reviewed";
                        $mail->Body = "Thank you for your feedback!";

                        $mail->send();
                        echo json_encode(['success' => true, 'message' => 'Status updated and email sent.']);
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'message' => 'Status updated, but email failed: ' . $mail->ErrorInfo]);
                    }
                } elseif ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update status']);
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Action not specified']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn = null;
?>