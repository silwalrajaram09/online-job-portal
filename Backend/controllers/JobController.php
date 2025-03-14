<?php

require_once '../models/Job.php';
require_once 'AuthController.php';

class JobController
{

    private $jobModel;

    public function __construct()
    {
        $this->jobModel = new Job();
    }

    /**
     * Handle a request to create a new job
     */

    // Ensure the user is logged in and has the "company" role
    // if (!isset($_SESSION['current_user_key']) || !isset($_SESSION[$_SESSION['current_user_key']])) {
    //     echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    //     exit;
    // }

    // $currentSessionData = $_SESSION[$_SESSION['current_user_key']];
    // if ($currentSessionData['role'] !== 'company') {
    //     echo json_encode(['success' => false, 'message' => 'Only companies can post jobs.']);
    //     exit;
    // }

    // $userId = $currentSessionData['id'];

    // // Get company_id based on user_id
    // $companyId = $this->jobModel->getCompanyIdByUserId($userId);
    // if (!$companyId) {
    //     echo json_encode(['success' => false, 'message' => 'Company not found.']);
    //     exit;
    // }

    public function createJob()
    {
        //header ('Content-Type: application/json');
        session_start();
        $currentSessionData = $_SESSION[$_SESSION['current_user_key']];
        $userId = $currentSessionData['id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $companyId = $this->jobModel->getCompanyIdByUserId($userId);
            $title = trim($_POST['title'] ?? '');
            $description = htmlspecialchars(trim($_POST['description'] ?? ''));
            $location = htmlspecialchars(trim($_POST['location'] ?? ''));
            $jobType = htmlspecialchars(trim($_POST['job_type'] ?? ''));
            $salary = htmlspecialchars(trim($_POST['salary'] ?? ''));
            $applicationDeadline = htmlspecialchars(trim($_POST['application_deadline'] ?? ''));
            $job_Category_Id = htmlspecialchars(trim($_POST['job_category'] ?? ''));

            // $errors = $this->validateJobField($title, $description,$location , $job_Category_Id, $jobType,$salary,$applicationDeadline);
            //validate the fields
            if (empty($title) || empty($description) || empty($location) || empty($job_Category_Id) || empty($jobType) || empty($salary) || empty($applicationDeadline)) {
                echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
                exit;
            }

            // 1. Title validation
            if (empty($title)) {
                echo json_encode(['success' => false, 'message' => 'Title is required']);
                exit;
            } elseif (strlen($title) < 3 || strlen($title) > 100) {
                echo json_encode(['success' => false, 'message' => 'Job title must be between 3 and 100 characters']);
                exit;
            } elseif (!preg_match('/^[a-zA-Z0-9\s.,\'"-]+$/', $title)) {
                echo json_encode(['success' => false, 'message' => 'Job title contains invalid characters']);
                exit;
            }

            // 2. Description validation
            if (empty($description)) {
                echo json_encode(['success' => false, 'message' => 'Description is required']);
                exit;
            } elseif (strlen($description) < 10 || strlen($description) > 500) {
                echo json_encode(['success' => false, 'message' => 'Job description must be between 10 and 500 characters']);
                exit;
            } elseif (!preg_match('/^[a-zA-Z0-9\s.,\'"-]+$/', $description)) {
                echo json_encode(['success' => false, 'message' => 'Job description contains invalid characters']);
                exit;
            }

            // 3. Location validation
            if (empty($location)) {
                echo json_encode(['success' => false, 'message' => 'Location is required']);
                exit;
            } elseif (strlen($location) < 3 || strlen($location) > 100) {
                echo json_encode(['success' => false, 'message' => 'Location must be between 3 and 100 characters']);
                exit;
            } elseif (!preg_match('/^[a-zA-Z0-9\s.,\'"-]+$/', $location)) {
                echo json_encode(['success' => false, 'message' => 'Location contains invalid characters']);
                exit;
            }

            // 4. Job Type validation
            // if (empty($jobType)) {
            //     echo json_encode(['success' => false, 'message' => 'Job type is required']);
            //     exit;
            // } elseif (strlen($jobType) < 3 || strlen($jobType) > 100) {
            //     echo json_encode(['success' => false, 'message' => 'Job type must be between 3 and 100 characters']);
            //     exit;
            // } elseif (!preg_match('/^[a-zA-Z\s]+$/', $jobType)) { // Allow only letters and spaces for job type
            //     echo json_encode(['success' => false, 'message' => 'Job type contains invalid characters']);
            //     exit;
            // }

            // 5. Salary validation
            if (empty($salary)) {
                echo json_encode(['success' => false, 'message' => 'Salary is required']);
                exit;
            } elseif (!is_numeric($salary) || $salary <= 0) {
                echo json_encode(['success' => false, 'message' => 'Salary must be a positive number']);
                exit;
            }

            // 6. Application Deadline validation
            if (empty($applicationDeadline)) {
                echo json_encode(['success' => false, 'message' => 'Application deadline is required']);
                exit;
            } else {
                $currentDateTime = date('Y-m-d H:i:s'); // Get current date and time
                if (strtotime($applicationDeadline) <= strtotime($currentDateTime)) {
                    echo json_encode(['success' => false, 'message' => 'Application deadline must be greater than the current date and time']);
                    exit;
                }
            }

            $result = $this->jobModel->createJobs(
                $companyId,
                $job_Category_Id,
                $title,
                $description,
                $salary,
                $location,
                $jobType,
                $applicationDeadline,
                
            );
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Job created successfully']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create job']);
                exit;
            }

        }
    }

    /**
     * Handle a request to update a job
     */
    public function updateJob($id)
    {

        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $data = [
               
        //         'job_category_id' => $_POST['job_category'],
        //         'title' => trim($_POST['title']),
        //         'description' => trim($_POST['description']),
        //         'location' => trim($_POST['location']),
        //         'salary' => $_POST['salary'],
        //         'job_type' => $_POST['job_type'],
                
        //         'application_deadline' => $_POST['application_deadline']
        //     ];

        //     // Validate inputs
        //     $errors = $this->validateJobData($data);

        //     if (empty($errors)) {
        //         if ($this->jobModel->updateJob($id, $data)) {
        //             echo json_encode(['success' => true, 'message' => 'Job updated successfully!']);
        //         } else {
        //             echo json_encode(['success' => false, 'message' => 'Failed to update job.']);
        //         }
        //     } else {
        //         echo json_encode(['success' => false, 'errors' => 'hello']);
        //     }
        // } else {
        //     echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        // }
        session_start();
        $currentSessionData = $_SESSION[$_SESSION['current_user_key']];
        $userId = $currentSessionData['id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $companyId = $this->jobModel->getCompanyIdByUserId($userId);
            $title = trim($_POST['title'] ?? '');
            $description = htmlspecialchars(trim($_POST['description'] ?? ''));
            $location = htmlspecialchars(trim($_POST['location'] ?? ''));
            $jobType = htmlspecialchars(trim($_POST['job_type'] ?? ''));
            $requirements = htmlspecialchars(trim($_POST['requirements'] ?? ''));
            $salary = htmlspecialchars(trim($_POST['salary'] ?? ''));
            $applicationDeadline = htmlspecialchars(trim($_POST['application_deadline'] ?? ''));
            $job_Category_Id = htmlspecialchars(trim($_POST['job_category'] ?? ''));

            // $errors = $this->validateJobField($title, $description,$location , $job_Category_Id, $jobType,$salary,$applicationDeadline);
            //validate the fields
            if (empty($title) || empty($description) || empty($location) || empty($job_Category_Id) || empty($jobType) || empty($salary) || empty($applicationDeadline)) {
                echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
                exit;
            }

            // 1. Title validation
            if (empty($title)) {
                echo json_encode(['success' => false, 'message' => 'Title is required']);
                exit;
            } elseif (strlen($title) < 3 || strlen($title) > 100) {
                echo json_encode(['success' => false, 'message' => 'Job title must be between 3 and 100 characters']);
                exit;
            } elseif (!preg_match('/^[a-zA-Z0-9\s.,\'"-]+$/', $title)) {
                echo json_encode(['success' => false, 'message' => 'Job title contains invalid characters']);
                exit;
            }

            // 2. Description validation
            if (empty($description)) {
                echo json_encode(['success' => false, 'message' => 'Description is required']);
                exit;
            } elseif (strlen($description) < 10 || strlen($description) > 500) {
                echo json_encode(['success' => false, 'message' => 'Job description must be between 10 and 500 characters']);
                exit;
            } elseif (!preg_match('/^[a-zA-Z0-9\s.,\'"-]+$/', $description)) {
                echo json_encode(['success' => false, 'message' => 'Job description contains invalid characters']);
                exit;
            }

            // 3. Location validation
            if (empty($location)) {
                echo json_encode(['success' => false, 'message' => 'Location is required']);
                exit;
            } elseif (strlen($location) < 3 || strlen($location) > 100) {
                echo json_encode(['success' => false, 'message' => 'Location must be between 3 and 100 characters']);
                exit;
            } elseif (!preg_match('/^[a-zA-Z0-9\s.,\'"-]+$/', $location)) {
                echo json_encode(['success' => false, 'message' => 'Location contains invalid characters']);
                exit;
            }

            // 4. Job Type validation
            // if (empty($jobType)) {
            //     echo json_encode(['success' => false, 'message' => 'Job type is required']);
            //     exit;
            // } elseif (strlen($jobType) < 3 || strlen($jobType) > 100) {
            //     echo json_encode(['success' => false, 'message' => 'Job type must be between 3 and 100 characters']);
            //     exit;
            // } elseif (!preg_match('/^[a-zA-Z\s]+$/', $jobType)) { // Allow only letters and spaces for job type
            //     echo json_encode(['success' => false, 'message' => 'Job type contains invalid characters']);
            //     exit;
            // }

            // 5. Salary validation
            if (empty($salary)) {
                echo json_encode(['success' => false, 'message' => 'Salary is required']);
                exit;
            } elseif (!is_numeric($salary) || $salary <= 0) {
                echo json_encode(['success' => false, 'message' => 'Salary must be a positive number']);
                exit;
            }

            // 6. Application Deadline validation
            if (empty($applicationDeadline)) {
                echo json_encode(['success' => false, 'message' => 'Application deadline is required']);
                exit;
            } else {
                $currentDateTime = date('Y-m-d H:i:s'); // Get current date and time
                if (strtotime($applicationDeadline) <= strtotime($currentDateTime)) {
                    echo json_encode(['success' => false, 'message' => 'Application deadline must be greater than the current date and time']);
                    exit;
                }
            }

            $result = $this->jobModel->updateJobs(
                $id,
                $companyId,
                $job_Category_Id,
                $title,
                $description,
                $salary,
                $location,
                $jobType,
                $applicationDeadline,
                
                
            );
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Job update successfully']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update job']);
                exit;
            }

        }
    }
   
    
    //delete job 
    public function deleteJob($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_GET['id']; // Get the job ID from the AJAX request

            // Check if the ID is valid
            if (!is_numeric($id)) {
                echo json_encode(['success' => false, 'message' => 'Invalid job ID.']);
                return;
            }

            // Call the model's deleteJob function
            if ($this->jobModel->deleteJob($id)) {
                echo json_encode(['success' => true, 'message' => 'Job deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete job.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }


    /**
     * Approve a job post
     */
    public function approveJob($id)
    {
        if ($this->jobModel->approveJob($id)) {
            echo json_encode(['success' => true, 'message' => 'Job approved successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to approve job.']);
        }
    }

    /**
     * Reject a job post
     */
    public function rejectJob($id)
    {
        if ($this->jobModel->rejectJob($id)) {
            echo json_encode(['success' => true, 'message' => 'Job rejected successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reject job.']);
        }
    }

    /**
     * Fetch all jobs
     */
    public function getAllJobs()
    {
        $jobs = $this->jobModel->getAllJobs();

        if (!empty($jobs)) {
            echo json_encode(['success' => true, 'jobs' => $jobs]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No jobs found.']);
        }
    }
    public function getAllJob()
    {
        $jobs = $this->jobModel->getAllJob();

        if (!empty($jobs)) {
            echo json_encode(['success' => true, 'jobs' => $jobs]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No jobs found.']);
        }
    }
    // public function searchJob()
    // {
    //     $jobtitle = $_REQUEST['jobTitle'] ?? ''; // Accept both GET & POST
    //     $location = $_REQUEST['location'] ?? '';
    
    //     try {
    //         $jobs = $this->jobModel->searchJobs($jobtitle, $location);
    //         echo json_encode(['success' => true, 'jobs' => $jobs]);
    //     } catch (Exception $e) {
    //         echo json_encode(['success' => false, 'message' => 'Error fetching jobs: ' . $e->getMessage()]);
    //     }
    // }
    
    /**
     * Validate job data
     */
    private function validateJobData($data)
    {

        $errors = [];
        if (
            empty($data['title']) && empty($data['description']) && empty($data['location']) &&
            empty($data['salary']) && empty($data['job_type']) && empty($data['application_deadline'])
        ) {
            $errors[] = 'Please fill in all required fields. ';
        } else {
            if (empty($data['title'])) {
                $errors['title'] = 'Job title is required.';
            }

            if (empty($data['description'])) {
                $errors['description'] = 'Job description is required.';
            }

            if (empty($data['location'])) {
                $errors['location'] = 'Job location is required.';
            }

            if (!is_numeric($data['salary']) || $data['salary'] <= 0) {
                $errors['salary'] = 'Salary must be a positive number.';
            }

            if (empty($data['job_type'])) {
                $errors['job_type'] = 'Job type is required.';
            }

            if (empty($data['application_deadline'])) {
                $errors['application_deadline'] = 'Application deadline is required.';
            } else {
                $currentDateTime = date('Y-m-d H:i:s'); // Get current date and time
                $deadline = $data['application_deadline'];

                // Convert both dates to timestamps for comparison
                if (strtotime($deadline) <= strtotime($currentDateTime)) {
                    $errors['application_deadline'] = 'Application deadline must be greater than the current date and time.';
                }
            }


            return json_encode($errors);
        }
    }
    private function validateJobField($title, $description, $salary, $jobcategory, $jobtype, $location, $applicationdate)
    {
        $errors = [];
        //use the empty functin for all fields

        if (empty($title) && empty($description) && empty($salary) && empty($jobcategory) && empty($jobtype) && empty($applicationdate)) {
            $errors[''] = 'all fields are required';
        }
        return $errors;
    }
    public function getCompanyJobs()
    {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['current_user_key']) || !isset($_SESSION[$_SESSION['current_user_key']])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            return;
        }
        $currentSessionData = $_SESSION[$_SESSION['current_user_key']];
        if ($currentSessionData['role'] !== 'company') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            return;
        }

        $userId = $currentSessionData['id'];

        // Get company_id based on user_id
        //$companyId = $this->getCompanyId($userId);
        $companyId = $this->jobModel->getCompanyIdByUserId($userId);
        //$_SESSION['company_id']=$companyId;
        if (!$companyId) {
            echo json_encode(['success' => false, 'message' => 'Company not found.']);
            return;
        }

        // Fetch jobs for the company
        $jobs = $this->jobModel->getJobsByCompanyId($companyId);

        if (!empty($jobs)) {
            echo json_encode(['success' => true, 'jobs' => $jobs]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No jobs found for this company.']);
        }
    }
    public function getJobDetails()
    {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['current_user_key']) || !isset($_SESSION[$_SESSION['current_user_key']])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            return;
        }
        $currentSessionData = $_SESSION[$_SESSION['current_user_key']];
        if ($currentSessionData['role'] !== 'company') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            return;
        }
        $jobId = $_GET['id'];
        $jobDetails = $this->jobModel->getJobDetails($jobId);
        if ($jobDetails) {
            echo json_encode(['success' => true, 'job_details' => $jobDetails]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Job not found.']);
        }
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
            case 'create':
                $this->createJob();
                break;

            case 'update':
                if (!isset($_POST['id'])) {
                    echo json_encode(['success' => false, 'message' => 'Job ID not provided']);
                    break;
                }
                $this->updateJob($_POST['id']);
                break;
            case 'delete':
                if (!isset($_GET['id'])) {
                    echo json_encode(['success' => false, 'message' => 'Job ID not provided']);
                    break;
                }
                $this->deleteJob($_GET['id']);
                break;
            case 'approve':
                if (!isset($_GET['id'])) {
                    echo json_encode(['success' => false, 'message' => 'Job ID not provided']);
                    break;
                }
                $this->approveJob($_GET['id']);
                break;

            case 'getAll':
                $this->getAllJob();
                break;
            case 'getAllJobs':
                $this->getAllJobs();
                break;
            // case 'searchJob':
            //         $this->searchJob();
            //         break;
            case 'getCompanyJobs':
                $this->getCompanyJobs();
                break;
            case 'getJobDetails':
                if (!isset($_GET['id'])) {
                    echo json_encode(['success' => false, 'message' => 'Job ID not provided']);
                    break;
                }
                $this->getJobDetails();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
    }
}

// Create an instance of JobController and handle the request

$controller = new JobController();
$controller->handleRequest();
