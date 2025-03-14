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

// Fetch job postings
$sql = "SELECT 
            j.id AS id,
            j.title AS title,
            j.location AS location,
            j.created_at AS created_at,
            j.status AS status,
            c.company_name AS company_name
        FROM 
            jobs j
        INNER JOIN 
            companyregistration c 
        ON 
            j.company_id = c.id
        ORDER BY 
            j.created_at DESC"; 

$result = $conn->query($sql);
$rows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .status-approved { color: green; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        button { padding: 5px 10px; margin: 2px; cursor: pointer; }
        .approve-btn { background: green; color: white; border: none; }
        .reject-btn { background: red; color: white; border: none; }
    </style>
</head>
<body>

    <h2>Job Listings</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Job Title</th>
            <th>Company</th>
            <th>Location</th>
            <th>Date Posted</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($rows as $row) { ?>
            <tr id="job-<?php echo $row['id']; ?>">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['company_name']; ?></td>
                <td><?php echo $row['location']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td class="status <?php echo 'status-' . strtolower($row['status']); ?>">
                    <?php echo ucfirst($row['status']); ?>
                </td>
                <td class="actions">
                    <?php if ($row['status'] == 'pending') { ?>
                        <button class="approve-btn" onclick="updateJobStatus(<?php echo $row['id']; ?>, 'open')">Approve</button>
                        <button class="reject-btn" onclick="updateJobStatus(<?php echo $row['id']; ?>, 'reject')">Reject</button>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <script>
        
        function updateJobStatus(jobId, action) {
            $.ajax({
                url: "backend/update_job.php",
                type: "POST",
                data: { job_id: jobId, action: action },
                success: function(response) {
                    if (response.trim() === "success") {
                        let row = $("#job-" + jobId);
                        row.find(".status").text(action.charAt(0).toUpperCase() + action.slice(1))
                                           .removeClass()
                                           .addClass("status status-" + action);
                        row.find(".actions").html(""); 
                        
                    } else {
                        alert(" " + response);
                    }
                },
                error: function() {
                    alert("AJAX request failed.");
                }
            });
        }

    </script>

</body>
</html>
