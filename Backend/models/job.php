<?php

require_once '../controllers/Database.php';

class Job
{
    private $pdo;
    public $company_id;
    public $job_category_id;
    public $title;
    public $description;
    public $location;
    public $salary;
    public $job_type;
    public $application_deadline;
    public $status;
    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    /**
     * Create a new job posting
     *
     * @param array $data
     * @return bool
     */
    public function createJob($data)
    {
        $sql = "INSERT INTO jobs (company_id, job_category_id, title, description, location, salary, job_type,requirements,application_deadline,status)
                VALUES (:company_id, :job_category_id, :title, :description, :location, :salary, :job_type,:requirements,  :application_deadline, 'pending'";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':company_id' => $data['company_id'],
            ':job_category_id' => $data['job_category_id'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':location' => $data['location'],
            ':salary' => $data['salary'],
            ':job_type' => $data['job_type'],
            ':requirements' => $data['requirements'],
            ':application_deadline' => $data['application_deadline'],
            ':status' => $data['status']
        ]);
    }
    public function createJobs($company_id, $job_category_id, $title, $description, $salary, $location, $job_type,  $application_deadline)
    {
        $db = Database::getConnection();


        $stmt = $db->prepare("INSERT INTO jobs (company_id,job_category_id, title, description,  salary, location,job_type, application_deadline) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");


        return $stmt->execute([$company_id, $job_category_id, $title, $description,  $salary, $location, $job_type, $application_deadline]);
    }

    /**
     * Get a job posting by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getJobById($id)
    {
        $sql = "SELECT jobs.*, job_category.category_name AS category_name, companyregistration.company_name AS company_name 
                FROM jobs 
                LEFT JOIN job_category ON jobs.job_category_id = job_category.id
                LEFT JOIN companyregistration ON jobs.company_id = companyregistration.id
                WHERE jobs.id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getJobDetails($jobId)
    {
        try {
            // Prepare the SQL query to fetch job details
            $query = "SELECT jobs.*, job_category.category_name  AS category_name 
                        FROM jobs 
                        LEFT JOIN job_category ON jobs.job_category_id = job_category.id 
                        WHERE jobs.id = :jobId
                        ";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':jobId', $jobId, PDO::PARAM_INT);
            $stmt->execute();

            // Fetch the job details as an associative array
            $jobDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($jobDetails) {
                return [
                    'success' => true,
                    'job' => $jobDetails,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Job not found.',
                ];
            }
        } catch (Exception $e) {
            // Handle any errors
            return [
                'success' => false,
                'message' => 'Error fetching job details: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Update an existing job posting
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateJob($id, $data)
    {
        $sql = "UPDATE jobs SET company_id = :company_id, job_category_id = :job_category_id, title = :title, 
                description = :description, location = :location, salary = :salary, job_type = :job_type, 
                requirements = :requirements, application_deadline = :application_deadline, updated_at = NOW() 
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':company_id' => $data['company_id'],
            ':job_category_id' => $data['job_category_id'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':location' => $data['location'],
            ':salary' => $data['salary'],
            ':job_type' => $data['job_type'],
            ':requirements' => $data['requirements'],
            ':application_deadline' => $data['application_deadline'],
            ':id' => $id
        ]);
    }
    public function updateJobs($id, $company_id, $job_category_id, $title, $description, $salary, $location, $job_type,  $application_deadline)
    {
        $sql = "UPDATE jobs SET company_id= :company_id, job_category_id = :job_category_id, title = :title,
        description = :description, salary = :salary, location = :location, job_type = :job_type, application_deadline = :application_deadline WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([

            ':company_id' => $company_id,
            ':job_category_id' => $job_category_id,
            ':title' => $title,
            ':description' => $description,
            ':salary' => $salary,
            ':location' => $location,
            ':job_type' => $job_type,
            ':application_deadline' => $application_deadline,
            ':id' => $id



        ]);
    }
    /**
     * Delete a job posting by ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteJob($id)
    {
        $sql = "DELETE FROM jobs WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Get all job postings
     *
     * @return array
     */
    public function getAllJobs()
    {
        session_start();
        if (!isset($_SESSION['current_user_key'])) {
            $response["message"] = "User not logged in.";
            echo json_encode($response);
            exit;
        }
        
        $currentSessionKey = $_SESSION['current_user_key'];
        $currentUser = $_SESSION[$currentSessionKey];
        $role = $currentUser['role'];
        $userId = $currentUser['id'];

        $updateSql = "UPDATE jobs SET status = 'closed' WHERE application_deadline < CURDATE() AND status = 'open'";
        $this->pdo->query($updateSql);

        $sql = "SELECT 
            jobs.*, 
            job_category.category_name AS category_name, 
            companyregistration.company_name AS company_name, 
            jobseeker.id AS jobseeker_id, 
            jobseeker.first_name, 
            jobseeker.last_name, 
            users.email, 
            jobseeker.phone, 
            jobseeker.skills
          FROM jobs
          LEFT JOIN job_category ON jobs.job_category_id = job_category.id
          LEFT JOIN companyregistration ON jobs.company_id = companyregistration.id
          LEFT JOIN jobseeker ON jobseeker.user_id =  $userId
          LEFT JOIN users ON users.id =  $userId
          WHERE jobs.status IN ('open', 'close')
          ORDER BY jobs.created_at DESC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllJob()
    {
       

        $updateSql = "UPDATE jobs SET status = 'closed' WHERE application_deadline < CURDATE() AND status = 'open'";
        $this->pdo->query($updateSql);

        $sql = "SELECT 
        jobs.*, 
        job_category.category_name AS category_name, 
        companyregistration.company_name AS company_name
      FROM jobs
      LEFT JOIN job_category ON jobs.job_category_id = job_category.id
      LEFT JOIN companyregistration ON jobs.company_id = companyregistration.id
      WHERE jobs.status IN ('open', 'closed')
      ORDER BY jobs.created_at DESC";

         $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search jobs by keywords and location
     *
     * @param string $keywords
     * @param string $location
     * @return array
     */
//     public function searchJobs($keywords, $location)
//     {
//         $sql = "SELECT jobs.*, 
//                        job_category.category_name AS category_name, 
//                        companyregistration.company_name AS company_name 
//                 FROM jobs 
//                 LEFT JOIN job_category ON jobs.job_category_id = job_category.id
//                 LEFT JOIN companyregistration ON jobs.company_id = companyregistration.id
//                WHERE jobs.status = 'open'
// AND (jobs.title LIKE :keywords OR jobs.location LIKE :location)

                
//                 ORDER BY created_at DESC";

//         $stmt = $this->pdo->prepare($sql);
//         $stmt->execute([
//             ':keywords' => '%' . $keywords . '%',
//             ':location' => '%' . $location . '%'
//         ]);

//         return $stmt->fetchAll(PDO::FETCH_ASSOC);
//     }


    /**
     * Approve a job by ID
     *
     * @param int $id
     * @return bool
     */
    public function approveJob($id)
    {
        $sql = "UPDATE jobs SET status = 'open' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Reject a job by ID
     *
     * @param int $id
     * @return bool
     */
    public function rejectJob($id)
    {
        $sql = "UPDATE jobs SET status = 'closed' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    public function getCompanyIdByUserId($userId)
    {
        $sql = "SELECT id FROM companyregistration WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['id'];
        }
        return null;
    }
    public function getJobseekerIdByUserId($userId)
    {
        $sql = "SELECT id FROM jobseeker WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['id'];
        }
        return null;
    }
    public function getJobIdByCompanyId($companyID)
    {
        $sql = "SELECT id FROM jobs WHERE company_id = :company_id AND status = '
    open'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':company_id', $companyID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['id'];
        }
        return null;
    }


    public function getJobsByCompanyId($companyId)
    {
        // $sql = "SELECT id, title, description, salary, location, job_type, status, application_deadline, created_at 
        //         FROM jobs WHERE company_id = :company_id ORDER BY created_at DESC";
        $sql = "
            SELECT 
        jobs.*, 
        companyregistration.company_name AS company_name, 
        job_category.category_name AS category_name 
    FROM 
        jobs
    LEFT JOIN 
        companyregistration ON jobs.company_id = companyregistration.id
    LEFT JOIN 
        job_category ON jobs.job_category_id = job_category.id
    WHERE 
        jobs.company_id = :company_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);

        $stmt->execute();

        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $jobs;
    }
}
