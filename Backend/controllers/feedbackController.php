<?php

require_once '../models/feedback.php'; // Include the Feedback model

class FeedbackController {
    private $feedbackModel;
    private $db; // Add a database connection property

    public function __construct() {
         // Store the database connection
        $this->feedbackModel = new Feedback(); // Pass the connection to the model
    }

    public function submitFeedback() {
        $userType = $_POST['user_type'];
        $userId = $_POST['user_id'];
        $userEmail = $_POST['user_email'];
        $feedbackText = $_POST['feedback_text'];
        $rating = $_POST['rating'] ?? null;

        if ($this->feedbackModel->submitFeedback($userType, $userId,  $userEmail,$feedbackText, $rating)) {
            $this->sendJsonResponse(['success' => true, 'message' => 'Feedback submitted successfully!']);
        } else {
            $this->sendJsonResponse(['success' => false, 'message' => 'Error submitting feedback. Please try again later.']);
        }
    }

    private function sendJsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

// Assuming $db is your database connection object
// Include the database connection setup here (e.g., PDO connection)
// Example:
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "your_dbname";
// try {
//     $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
//     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch(PDOException $e) {
//     echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
//     exit;
// }

$feedbackController = new FeedbackController();

// Check if the request is for submitting feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'submit_feedback') {
    $feedbackController->submitFeedback();
} else {
    // Handle other requests or send an error response
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>