<?php
require_once '../models/JobApplication.php';
require_once 'AuthController.php';
require_once '../models/Job.php';
// ob_start(); 
// header('Content-Type: application/json'); 
class JobApplicationController
{
    private $jobApplication;
    private $jobModel;
    public function __construct()
    {
        $this->jobApplication = new JobApplication();
        $this->jobModel = new Job();
    }


    public function createJobApplication()
    {
        session_start();
        $currentSessionData = $_SESSION[$_SESSION['current_user_key']];
        $userId = $currentSessionData['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = htmlspecialchars(trim($_POST['name'] ?? ''));
            $email = htmlspecialchars(trim($_POST['email'] ?? ''));
            $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
            $jobseekerId = $this->jobApplication->getJobseekerIdByUserId($userId);;
            // $jobId = $this->jobApplication->getJobIdForJobseeker($jobseekerId);
            $jobId = $_POST['job_id'];
            // Ensure all fields are provided
            if (empty($name) || empty($email) || empty($phone)) {
                echo json_encode(['success' => false, 'message' => 'All fields are required.']);
                return;
            }



            //check the job and jobseeker duplicate in application table
            $ifExists = $this->jobApplication->checkJobApplication($jobId, $jobseekerId);
            if ($ifExists) {
                echo json_encode(['success' => false, 'message' => 'You have already applied for
                this job.']);
            } else {

                // Create the job application
                $result = $this->jobApplication->createApplication($jobId, $jobseekerId, $name, $email, $phone);

                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Job application submitted successfully.']);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to submit the job application.']);
                    exit;
                }
            }
        }
    }
    public function getJobApplications()
    {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['current_user_key']) || !isset($_SESSION[$_SESSION['current_user_key']])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            exit;
        }
        $currentSessionData = $_SESSION[$_SESSION['current_user_key']];
        if ($currentSessionData['role'] !== 'jobseeker') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            exit;
        }
        $userId = $currentSessionData['id'];
        $jobseekerId = $this->jobModel-> getJobseekerIdByUserId($userId);
        $jobapplication = $this->jobApplication->getJobApplicationByJobseekerId( $jobseekerId);
        if (!empty($jobapplication)) {
            echo json_encode(['success' => true, 'jobapplication' => $jobapplication]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'No application found.']);
            exit;
        }
    }
    //get all the application function for the company
    public function getJobApplicationOfCompany()
    {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['current_user_key']) || !isset($_SESSION[$_SESSION['current_user_key']])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            exit;
        }
        $currentSessionData = $_SESSION[$_SESSION['current_user_key']];
        if ($currentSessionData['role'] !== 'company') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            exit;
        }
        $userId = $currentSessionData['id'];
        $companyId = $this->jobModel->getCompanyIdByUserId($userId);
        // if ($companyId) {
        //     echo json_encode(['success' => true, 'message' => $companyId]);
        //     return;
        // }

        $jobapplication = $this->jobApplication->getJobApplicationByCompanyId($companyId);
        if (!empty($jobapplication)) {
            echo json_encode(['success' => true, 'jobapplication' => $jobapplication]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'No application found.']);
            exit;
        }
    }
    public function getSelectedApplications() {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['current_user_key']) || !isset($_SESSION[$_SESSION['current_user_key']])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            exit;
        }
        $currentSessionData = $_SESSION[$_SESSION['current_user_key']];
        if ($currentSessionData['role'] !== 'company') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            exit;
        }
        $userId = $currentSessionData['id'];
        $companyId = $this->jobModel->getCompanyIdByUserId($userId);

        $jobapplication = $this->jobApplication->getSelectedJobApplicationByCompanyId($companyId);
        if (!empty($jobapplication)) {
            echo json_encode(['success' => true, 'jobapplication' => $jobapplication]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'No selecetd application found.']);
            exit;
        }
    }

    
    public function updateApplicationStatus()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $applicationId = $_POST['application_id'] ?? null;
            $status = $_POST['status'] ?? null;
            $rejectionReason = $_POST['rejection_reason'] ?? '';

            // Validate inputs
            if (!$applicationId || !in_array($status, ['accept', 'reject'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid application ID or status.']);
                exit;
            }

            // If rejected, ensure a rejection reason is provided
            if ($status === 'reject' && empty($rejectionReason)) {
                echo json_encode(['success' => false, 'message' => 'Rejection reason is required.']);
                exit;
            }

            // Update application status
            $result = $this->jobApplication->updateApplicationStatus($applicationId, $status, $rejectionReason);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Application status updated.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update application status.']);
            }
        }
    }
    public function cancelApplication() {
        $jobId = $_POST['jobs_id'] ?? null;
        session_start();
        if (!isset($_SESSION['current_user_key']) || !isset($_SESSION[$_SESSION['current_user_key']])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            exit;
        }
        $currentSessionData = $_SESSION[$_SESSION['current_user_key']];
        if ($currentSessionData['role'] !== 'jobseeker') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            exit;
        }
        $userId = $currentSessionData['id'];
        $jobseekerId = $this->jobModel-> getJobseekerIdByUserId($userId);
        if ($jobId && $jobseekerId) {
            $response = $this->jobApplication->cancelJobApplication($jobId, $jobseekerId);
        } else {
            $response = ['success' => false, 'message' => 'Invalid request'];
        }

        echo json_encode($response);
        exit;
    }
    public function handleRequest()
    {
        header('Content-Type: application/json');

        // Determine the action
        $action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : null);

        // Handle undefined action
        if ($action === null) {
            echo json_encode(['success' => false, 'message' => 'Action not specified']);
            exit;
        }

        // Route the action to the appropriate method
        switch ($action) {
            case 'apply':
                $this->createJobApplication();
                break;

            case 'getApplications':
                $this->getJobApplications();
                break;

            case 'getCompanyApplications':
                $this->getJobApplicationOfCompany();
                break;
            case 'getSelectedApplications':
                $this->getSelectedApplications();
                break;
            case 'updateApplicationStatus':
                $this->updateApplicationStatus();
                break;
            case 'cancelApplication':
                $this->cancelApplication();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
    }
}

// Handle the request
$controller = new JobApplicationController();
$controller->handleRequest();
//$controller->getJobApplicationOfCompany();
//ob_end_clean();
