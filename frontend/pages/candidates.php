<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications</title>
    <style>
        #jobApplications,
        #selectedApplications {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        #jobApplications th,
        #selectedApplications th,
        #jobApplications td,
        #selectedApplications td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #jobApplications th,
        #selectedApplications th {
            background-color: #f4f4f4;
        }

        .action-btn {
            margin-right: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .accept-btn {
            background-color: green;
            color: white;
            border: none;
        }

        .reject-btn {
            background-color: red;
            color: white;
            border: none;
        }

        .details-btn {
            background-color: blue;
            color: white;
            border: none;
        }

        /* Modal styles */
        #detailsModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            float: right;
            font-size: 25px;
            cursor: pointer;
        }

        h2 {
            text-decoration: underline;

        }
    </style>
</head>

<body>
    <h2>Applicants</h2>
    <div id="message"></div> <!-- Error/Success Message -->
    <table id="jobApplications">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Jobseeker Name</th>
                <th>Applied Date</th>
                <th>Company Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="applicationData">
            <!-- Job application rows will appear here -->
        </tbody>
    </table>

    <!-- Modal for showing more details -->
    <div id="detailsModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Application Details</h3>
            <p><strong>Job Title:</strong> <span id="modalJobTitle"></span></p>
            <p><strong>Jobseeker Name:</strong> <span id="modalJobseekerName"></span></p>
            <p><strong>Applied Date:</strong> <span id="modalAppliedDate"></span></p>
            <p><strong>Company Name:</strong> <span id="modalCompanyName"></span></p>
            <p><strong>Email:</strong> <span id="modalEmail"></span></p>
            <p><strong>Phone:</strong> <span id="modalPhone"></span></p>
            <p><strong>skills:</strong> <span id="modalSkills"></span></p>
            <p><strong>Status:</strong> <span id="modalStatus"></span></p>
            <button class="accept-btn" id="acceptBtn">Accept</button>
            <button class="reject-btn" id="rejectBtn">Reject</button>
        </div>
    </div>
    <!--selected applicants for the further process-->
    <div id="selectedApplicants">
        <h2>Selected Applicants</h2>
        <span id="message2"></span>
        <table id="selectedApplications">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Jobseeker Name</th>
                    <th>Applied Date</th>
                    <th>Company Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="selectedapplicationData">
                <!-- Job application rows will appear here -->
            </tbody>
        </table>
    </div>
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchJobApplications() {
                $.ajax({
                    url: '../Backend/controllers/jobApplicationController.php?action=getCompanyApplications',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            displayApplications(response.jobapplication);
                        } else {
                            $('#message').html(`<p style="color: red;">${response.message}</p>`);
                        }
                    },
                    error: function() {
                        $('#message').html('<p style="color: red;">Failed to fetch job applications.</p>');
                    }
                });
            }

            function displayApplications(applications) {
                const tableBody = $('#applicationData');
                tableBody.empty();

                applications.forEach(app => {
                    const row = `
                        <tr>
                            <td>${app.job_title}</td>
                            <td>${app.jobseeker_name}</td>
                            <td>${app.created_at}</td>
                            <td>${app.company_name}</td>
                            <td>
                                <button class="details-btn" data-id="${app.id}" data-job='${JSON.stringify(app)}'>Show More</button>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });

                $('.details-btn').on('click', function() {
                    const application = $(this).data('job');
                    $('#modalJobTitle').text(application.job_title);
                    $('#modalJobseekerName').text(application.jobseeker_name);
                    $('#modalAppliedDate').text(application.created_at);
                    $('#modalCompanyName').text(application.company_name);
                    $('#modalEmail').text(application.email);
                    $('#modalPhone').text(application.phone);
                    $('#modalStatus').text(application.status);
                    $('#modalSkills').text(application.skills);
                    $('#acceptBtn').data('id', application.id);
                    $('#rejectBtn').data('id', application.id);
                    $('#detailsModal').fadeIn();
                });
            }

            // Accept Application
            $('#acceptBtn').on('click', function() {
                const applicationId = $(this).data('id');
                console.log(applicationId);
                updateApplicationStatus(applicationId, 'accept', '');
            });

            // Reject Application
            $('#rejectBtn').on('click', function() {
                const applicationId = $(this).data('id');
                console.log(applicationId);
                const reason = prompt("Enter the reason for rejection:");
                if (reason) {
                    updateApplicationStatus(applicationId, 'reject', reason);
                }
            });


            function updateApplicationStatus(applicationId, status, rejectionReason = '') {
                $.ajax({
                    url: '../Backend/controllers/jobApplicationController.php',
                    type: 'POST',
                    data: {
                        action: 'updateApplicationStatus',
                        application_id: applicationId,
                        status: status,
                        rejection_reason: rejectionReason
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Application status updated successfully.');
                            //    $(`#row-${id}`).fadeOut();
                            //    //close the div
                            //    $('#detailsModal').modal('hide');

                            fetchJobApplications(); // Refresh the table
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        alert('oopsss sorrrry !!!.');
                    }
                });
            }
            $('.close').on('click', function() {
                $('#detailsModal').fadeOut();
            });

            function fetchSelectedJobApplications() {
                $.ajax({
                    url: '../Backend/controllers/jobApplicationController.php?action=getSelectedApplications',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            displaySelectedApplications(response.jobapplication);
                        } else {
                            $('#message2').html(`<p style="color: red;">${response.message}</p>`);
                        }
                    },
                    error: function() {
                        $('#message2').html('<p style="color: red;">Failed to fetch selected job applications.</p>');
                    }
                });
            }

            function displaySelectedApplications(applications) {
                const tableBody = $('#selectedapplicationData');
                tableBody.empty();

                applications.forEach(app => {
                    const row = `
                        <tr>
                            <td>${app.job_title}</td>
                                <td>${app.jobseeker_name}</td>
                                <td>${app.created_at}</td>
                                <td>${app.company_name}</td>
                                </tr>
                                `;
                    tableBody.append(row);
                });
            }

            fetchJobApplications();
            fetchSelectedJobApplications();
        });
    </script>

</body>

</html>