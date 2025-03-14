<?php
require_once '../controllers/Database.php';
session_start();

class ProfileController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function updateProfile() {
        header("Content-Type: application/json");
        $response = ["success" => false];

        // Check if user is logged in
        if (!isset($_SESSION['current_user_key'])) {
            $response["message"] = "User not logged in.";
            echo json_encode($response);
            exit;
        }

        $currentSessionKey = $_SESSION['current_user_key'];
        $currentUser = $_SESSION[$currentSessionKey];
        $role = $currentUser['role'];
        $userId = $currentUser['id'];

        // Retrieve and decode JSON input
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            $response["message"] = "No data received.";
            echo json_encode($response);
            exit;
        }

        // Initialize query and parameters
        $query = "";
        $params = [];

        // Validate Jobseeker Profile
        if ($role === "jobseeker") {
            $first_name = trim($data['fname'] ?? "");
            $last_name = trim($data['lname'] ?? "");
            $phone = trim($data['phone'] ?? "");
            $skills = trim($data['skills'] ?? "");

            if (empty($first_name)) {
                $response["message"] = "First name is required.";
                echo json_encode($response);
                exit;
            }
            if (empty($last_name)) {
                $response["message"] = "Last name is required.";
                echo json_encode($response);
                exit;
            }
            if (empty($phone)) {
                $response["message"] = "Phone number is required.";
                echo json_encode($response);
                exit;
            }
            if (!preg_match('/^98[0-9]{8}$/', $phone)) {
                $response["message"] = "Phone number must start with 98 and be 10 digits long.";
                echo json_encode($response);
                exit;
            }
            if (empty($skills)) {
                $response["message"] = "Skills are required.";
                echo json_encode($response);
                exit;
            }

            $query = "UPDATE jobseeker SET first_name=?, last_name=?, phone=?, skills=? WHERE user_id=?";
            $params = [$first_name, $last_name, $phone, $skills, $userId];
        } 

        // Validate Company Profile
        elseif ($role === "company") {
            $company_name = trim($data['company_name'] ?? "");
            $industry = trim($data['industry'] ?? "");
            $location = trim($data['location'] ?? "");

            if (empty($company_name)) {
                $response["message"] = "Company name is required.";
                echo json_encode($response);
                exit;
            }
            if (empty($industry)) {
                $response["message"] = "Industry is required.";
                echo json_encode($response);
                exit;
            }
            if (empty($location)) {
                $response["message"] = "Location is required.";
                echo json_encode($response);
                exit;
            }

            $query = "UPDATE companyregistration SET company_name=?, industry=?, location=? WHERE user_id=?";
            $params = [$company_name, $industry, $location, $userId];
        } else {
            $response["message"] = "Invalid user role.";
            echo json_encode($response);
            exit;
        }

        // Execute the update query
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute($params)) {
            $response["success"] = true;
            $response["message"] = "Profile updated successfully.";
        } else {
            $response["message"] = "Error updating profile.";
        }

        echo json_encode($response);
    }
}

// Handle request
$profileController = new ProfileController();
$profileController->updateProfile();
?>
