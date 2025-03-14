<?php
require_once '../controllers/Database.php';
//require_once '../controllers/user.php';
//require_once '../controllers/AuthController.php';

// $auth = new AuthController();
// $auth->loginAction();
session_start();
header('Content-Type: application/json');

// Debugging: Uncomment to inspect session data
//echo json_encode(['debug' => $_SESSION]);

if (isset($_SESSION['current_user_key']) && isset($_SESSION[$_SESSION['current_user_key']])) {
    // Retrieve session key and user details
    $currentSessionKey = $_SESSION['current_user_key'];
    $currentUser = $_SESSION[$currentSessionKey];

    $role = $currentUser['role'];
    $userId = $currentUser['id'];

    // Initialize database connection
    $db = new Database();
    $conn = $db->getConnection(); // Assuming Database::getConnection() returns a PDO instance

    try {
        if ($role === 'jobseeker') {
            // Fetch jobseeker information
            $query = "SELECT 
                        j.*, 
                        jc.category_name 
                    FROM 
                        jobseeker j 
                    LEFT JOIN 
                        job_category jc 
                    ON 
                        j.job_category_id = jc.id 
                    WHERE 
                        j.user_id = :user_id
                    ";

            $stmt = $conn->prepare($query);
            $stmt->execute([':user_id' => $userId]);

            $jobseekerInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($jobseekerInfo) {
                echo json_encode([
                    'success' => true,
                    'role' => 'jobseeker',
                    'data' => $jobseekerInfo
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Jobseeker information not found.']);
            }
        } elseif ($role === 'company') {
            // Fetch company information
            $query = "SELECT * FROM companyRegistration WHERE user_id = :user_id";
            $stmt = $conn->prepare($query);
            $stmt->execute([':user_id' => $userId]);

            $companyInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($companyInfo) {
                echo json_encode([
                    'success' => true,
                    'role' => 'company',
                    'data' => $companyInfo
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Company information not found.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid role.']);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Session not set or invalid.']);
}
