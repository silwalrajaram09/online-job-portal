<?php
require_once 'dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications</title>
    <style>
        #jobApplications {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        #jobApplications th,
        #jobApplications td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #jobApplications th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <h2>Job Applications</h2>
    <div id="message"></div> <!-- Error/Success Message -->
    <table id="jobApplications">
        <thead>
            <tr>

                <th>Job Title</th>
                <th>Jobseeker Name</th>
                <th>Applied date</th>
                <th>Company Name </th>
                <th>message </th>
                <th>Action </th>
            </tr>
        </thead>
        <tbody id="applicationData">
            <!-- Job application rows will appear here -->
        </tbody>
    </table>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            fetchJobApplications();

            function fetchJobApplications() {
                $.ajax({
                    url: '../Backend/controllers/jobApplicationController.php?action=getApplications',
                    type: 'POST',
                    //data: { action: 'getApplications' }, 
                    dataType: 'json', // Expect JSON response
                    success: function(response) {
                        console.log(response); // Debugging
                        if (response.success) {
                            displayApplications(response.jobapplication);
                        } else {
                            $('#message').html(`<p style="color: red;">${response.message}</p>`);
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.error("AJAX Error:", error);
                        $('#message').html('<p style="color: red;">Failed to fetch job applications.</p>');
                    }
                });
            }

            function displayApplications(applications) {
                const tableBody = $('#applicationData');
                tableBody.empty(); // Clear existing rows

                applications.forEach(app => {
                    let row = `<tr>
            <td>${app.job_title}</td>
            <td>${app.jobseeker_name}</td>
            <td>${app.created_at}</td>
            <td>${app.company_name}</td>`;

                    if (app.c_status === 'accept') {
                        row += `<td><span style="color: green;">Accepted by the company</span></td>
                    <td>-</td>`; // No action needed
                    } else if (app.c_status === 'reject') {
                        row += `<td><span style="color: red;">Rejected</span> - ${app.rejection_reason}</td>
                    <td>-</td>`; // No action needed
                    } else {
                        // Show Cancel button for pending applications
                        row += `<td><span style="color: blue;">Pending</span></td>
                    <td><button class="cancel-btn" data-id="${app.jobs_id}" style="color: red; cursor: pointer;">Cancel</button></td>`;
                    }

                    row += `</tr>`;
                    $('#applicationData').append(row);
                });

                // Add event listener for cancel buttons
                $('.cancel-btn').click(function() {
                    let jobId = $(this).data('id');
                    console.log(jobId);
                    cancelApplication(jobId);
                });
            }
        });

        function cancelApplication(jobId) {
            if (confirm("Are you sure you want to cancel this application?")) {
                $.ajax({
                    url: '../Backend/controllers/jobApplicationController.php?action=cancelApplication',
                    type: 'POST',
                    data: {
                        jobs_id: jobId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert("Application cancelled successfully!");
                            fetchJobApplications(); // Refresh the list
                        } else {
                            alert("Failed to cancel application: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Error cancelling application: " + error);
                    }
                });
            }
        }
    </script>
</body>

</html>