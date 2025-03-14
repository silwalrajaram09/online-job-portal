<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_job_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// Handle approve or reject actions via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['job_id'])) {
    $id = $_POST['job_id'];
    $action = strtolower($_POST['action']);

    if ($action == 'approve') {
        $updateSql = "UPDATE job_application SET status='approve' WHERE id=?";
    } elseif ($action == 'reject') {
        $updateSql = "UPDATE job_application SET status='reject' WHERE id=?";
    }

    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating record: ' . $conn->error]);
    }
    $stmt->close();
    $conn->close();
    exit; // Stop further execution after AJAX response
}

// SQL query to fetch all job applications
$sql = "SELECT
            ja.id,
            ja.jobseeker_id,
            ja.jobs_id,
            ja.created_at,
            ja.status,
            j.title AS job_title,
            js.first_name AS jobseeker_name,
            c.company_name AS company_name
        FROM job_application ja
        INNER JOIN jobs j ON ja.jobs_id = j.id
        INNER JOIN jobseeker js ON ja.jobseeker_id = js.id
        INNER JOIN companyregistration c ON j.company_id = c.id";

$result = $conn->query($sql);

$applications = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}

$conn->close();

echo json_encode(['success' => true, 'applications' => $applications]);

?>