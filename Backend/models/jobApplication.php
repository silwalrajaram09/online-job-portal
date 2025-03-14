<?php
require_once '../controllers/Database.php'; // Include the database configuration

class JobApplication{

    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    // Function to insert a new job application
    public function createApplication($jobId, $jobseekerId, $name, $email, $phone)
    {

        $sql = "INSERT INTO job_application (jobs_id, jobseeker_id, name, email, phone, status, created_at) 
                    VALUES (:job_id, :jobseeker_id, :name, :email, :phone, :status, :created_at)";
        $stmt = $this->pdo->prepare($sql);

        return  $stmt->execute([
            ':job_id' => $jobId,
            ':jobseeker_id' => $jobseekerId,
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':status' => 'pending',
            ':created_at' => date('Y-m-d H:i:s')
            // Default status

        ]);
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


    // Function to validate job_id
    public function getJobIdForJobseeker($jobseekerId)
    {
        try {
            $sql = "SELECT j.id AS job_id
                FROM jobs j
                JOIN jobseeker js ON j.job_category_id = js.job_category_id
                WHERE js.id = :jobseeker_id AND j.status = 'open'";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':jobseeker_id', $jobseekerId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['job_id']; // Return the matching job ID
            }
            return null; // Return null if no match is found
        } catch (PDOException $e) {
            error_log("Error fetching job ID for jobseeker: " . $e->getMessage());
            return false;
        }
    }



    // Function to validate jobseeker_id
    public function isValidJobseekerId($jobseekerId)
    {
        try {
            $sql = "SELECT id FROM jobseeker WHERE id = :jobseeker_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':jobseeker_id' => $jobseekerId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error validating Jobseeker ID: " . $e->getMessage());
            return false;
        }
    }
    //check the job id and jobseeker id exist in the jobapplication tablle (job seeker cannnot apply for the same job)
    public function checkJobApplication($jobId, $jobseekerId)
    {
        try {
            $sql = "SELECT COUNT(*) AS count 
                FROM job_application 
                WHERE jobs_id = :job_id AND jobseeker_id = :jobseeker_id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':job_id', $jobId, PDO::PARAM_INT);
            $stmt->bindParam(':jobseeker_id', $jobseekerId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0; // Returns true if a record exists
        } catch (PDOException $e) {
            error_log("Error checking job application: " . $e->getMessage());
            return false; // Returns false in case of an error
        }
    }
    //get all application also join operation to get the job title and jobseeker name 
    public function getAllApplication()
    {
        try {
            $sql = "SELECT
                
            ja.jobseeker_id,
            ja.jobs_id,
            ja.created_at,
            ja.c_status,
            ja.rejection_reason,
            j.title AS job_title,
            
            ja.name AS jobseeker_name,
            c.company_name AS company_name
        FROM job_application ja
        INNER JOIN jobs j ON ja.jobs_id = j.id
        INNER JOIN jobseeker js ON ja.jobseeker_id = js.id
        INNER JOIN companyregistration c ON j.company_id = c.id 
        WHERE ja.status = 'approve' " ;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
           // var_dump($result);
            return $result;
        } catch (PDOException $e) {
            error_log("Error checking job application: " . $e->getMessage());
            return false;
        }
    }
    public function getJobApplicationByJobseekerId($jobseeker_id)
    {
        try {
            $sql = "SELECT
                ja.jobseeker_id,
                ja.jobs_id,
                ja.created_at,
                ja.status,
                ja.c_status,
                ja.name AS jobseeker_name,
                ja.rejection_reason,
                j.title AS job_title,
                c.company_name AS company_name
            FROM job_application ja
            INNER JOIN jobs j ON ja.jobs_id = j.id
            INNER JOIN companyregistration c ON j.company_id = c.id 
            WHERE ja.jobseeker_id = :jobseeker_id";
    
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':jobseeker_id', $jobseeker_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error fetching job applications by jobseeker ID: " . $e->getMessage());
            return false;
        }
    }
    
    //only fetch the job application whre company id is equal to the logged in user company id
    public function getJobApplicationByCompanyId($companyId) {
        //try {

            $sql = "SELECT
                        ja.id,
                        ja.jobseeker_id,
                        ja.jobs_id,
                        ja.created_at,
                        ja.email,
                        ja.phone,
                        ja.status,
                        j.title AS job_title,
                        ja.name AS jobseeker_name,
                        js.skills as skills,
                        c.company_name AS company_name
                    FROM job_application ja
                    INNER JOIN jobs j ON ja.jobs_id = j.id
                    INNER JOIN jobseeker js ON ja.jobseeker_id = js.id
                    INNER JOIN companyregistration c ON j.company_id = c.id
                    WHERE c.id = :company_id AND ja.status = 'approve' AND ja.c_status = 'pending' " ;
    
            // Prepare the statement
            $stmt = $this->pdo->prepare($sql);
    
          
            $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
       
    }
     public function getselectedJobApplicationByCompanyId($companyId) {
        //try {

            $sql = "SELECT
                        ja.id,
                        ja.jobseeker_id,
                        ja.jobs_id,
                        ja.created_at,
                        ja.email,
                        ja.phone,
                        ja.status,
                        j.title AS job_title,
                        ja.name AS jobseeker_name,
                        js.skills as skills,
                        c.company_name AS company_name
                    FROM job_application ja
                    INNER JOIN jobs j ON ja.jobs_id = j.id
                    INNER JOIN jobseeker js ON ja.jobseeker_id = js.id
                    INNER JOIN companyregistration c ON j.company_id = c.id
                    WHERE c.id = :company_id AND ja.status = 'approve' AND c_status='accept'";
    
            // Prepare the statement
            $stmt = $this->pdo->prepare($sql);
    
          
            $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
       
    }
    
    public function updateApplicationStatus($id, $status, $reason)
{
    try {
        $sql = "UPDATE job_application SET c_status = ?, rejection_reason = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        // Bind parameters for all placeholders in the SQL query
        $stmt->bindParam(1, $status, PDO::PARAM_STR);
        $stmt->bindParam(2, $reason, PDO::PARAM_STR);
        $stmt->bindParam(3, $id, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        die(json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]));
    }
}
public function cancelJobApplication($jobId, $jobseekerId)
{
    try {
        $sql = "DELETE FROM job_application WHERE jobs_id = :job_id AND jobseeker_id = :jobseeker_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':job_id', $jobId, PDO::PARAM_INT);
        $stmt->bindParam(':jobseeker_id', $jobseekerId, PDO::PARAM_INT);

        $stmt->execute();
           
    } catch (PDOException $e) {
        return ['success' => false, 'message' => "Error: " . $e->getMessage()];
    }
}


}
// $job = new JobApplication();
// $job->getJobApplicationByCompanyId(26);
