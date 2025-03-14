<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_job_portal";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jobId = $_POST['job_id'];
    $action = strtolower($_POST['action']); 

    // Validate input
    if (!in_array($action, ['open', 'reject'])) {
        echo "Invalid action";
        exit;
    }

    // Update status in database
    $stmt = $conn->prepare("UPDATE jobs SET status=? WHERE id=?");
    $stmt->bind_param("si", $action, $jobId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        session_start();
        if($action=='open'){

            $_SESSION['message'] = "Job Approved Successfully!";
            echo "Job status updated successfully";
        } else{
            $_SESSION['message'] = "sorry!! Job Rejected";

            echo "Job is rejected";
        }
        
    } else {
        echo "Error updating job status";
        //echo "error";
    }

    $stmt->close();
}

$conn->close();
?>
