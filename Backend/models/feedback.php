<?php
require_once '../controllers/Database.php';
class Feedback {
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }
    public function submitFeedback($userType, $userId, $userEmail, $feedbackText, $rating = null) {
        try {
            $query = "INSERT INTO feedback (  email,user_type,user_id,feedback_text, rating) VALUES (:email,:user_type, :user_id, :feedback_text, :rating)";
            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(":user_type", $userType);
            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":email", $userEmail);
          
            $stmt->bindParam(":feedback_text", $feedbackText);
            $stmt->bindParam(":rating", $rating);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Feedback submission error: " . $e->getMessage());
            return false;
        }
    }

    // public function getAllFeedback() {
    //     try {
    //         $query = "SELECT * FROM feedback";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->execute();
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (PDOException $e) {
    //         error_log("Feedback retrieval error: " . $e->getMessage());
    //         return false;
    //     }
    // }

    // public function updateFeedbackStatus($feedbackId, $status) {
    //     try {
    //         $query = "UPDATE feedback SET status = :status WHERE id = :feedback_id";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bindParam(":status", $status);
    //         $stmt->bindParam(":feedback_id", $feedbackId);

    //         if ($stmt->execute()) {
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     } catch (PDOException $e) {
    //         error_log("Feedback status update error: " . $e->getMessage());
    //         return false;
    //     }
    // }

    // Add more methods as needed (e.g., getFeedbackById, deleteFeedback, etc.)
}
?>