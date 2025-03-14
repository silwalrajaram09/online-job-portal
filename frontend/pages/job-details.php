<?php
session_start();
if (isset($_SESSION['message'])) {
    echo "<div style='color: green; font-weight: bold; margin-bottom: 10px;'>
            {$_SESSION['message']}
          </div>";
    unset($_SESSION['message']); 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <style>
        /* Modal styles */
        #jobFormModal {
            display: none;
            /* Hidden by default */
            position: fixed;
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Black w/ opacity */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
            padding-top: 60px;
            border-radius: 30px;
        }

        /* Modal content */
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }

        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 12px;
        }

        table th {
            background-color: #f4f4f4;
        }

        #errorMessage {
            color: red;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <h2>Job Details</h2>
    <button id="addJobBtn">+NewJob</button>
    <div class="search">
        <input type="text" id="searchInput" placeholder="Search for a job">
    </div>
    <!-- Container for job post form -->
    <div id="jobFormModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            
            <div id="FormContainer"></div> <!-- Form will be injected here -->
        </div>
    </div>
    <!-- Modal for Job Updating -->

    <table id="jobTable">
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Title</th>
                <th>Company</th>
                <th>Category</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Type</th>
                <th>Posted Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Rows will be dynamically populated here -->
        </tbody>
    </table>

    <p id="errorMessage"></p>
    <!-- link the ajax-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Open the modal and load the job post form
            $('#addJobBtn').on('click', function () {
    // Ask for confirmation before loading the job post form
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to add a new job post?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, add job!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Display loading SweetAlert
            Swal.fire({
                title: 'Loading Job Post Form...',
                text: 'Please wait while we load the job post form.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading(); // Show loading spinner
                }
            });

            // Perform AJAX request to fetch the job post form
            $.ajax({
                url: '../frontend/company/post-job.php', 
                type: 'GET',
                success: function (data) {
                    Swal.close(); 
                    $('#FormContainer').html(data); 
                    $('#jobFormModal').fadeIn(); 
                },
                error: function () {
                    // Show error alert if the request fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to load the job post form. Please try again later.',
                        confirmButtonText: 'Close'
                    });
                }
            });
        } else {
            // Log or handle if the user cancels
            console.log('Add job operation cancelled by user.');
        }
    });
});


            // Close the modal when the close button is clicked
            $('.close').on('click', function() {
                $('#jobFormModal').fadeOut(); // Hide the modal
            });

            // Close the modal if the user clicks outside of it
            $(window).on('click', function(event) {
                if ($(event.target).is('#jobFormModal')) {
                    $('#jobFormModal').fadeOut(); // Hide the modal if the user clicks outside
                }
            });
        });

        function fetchJobs() {
            $.ajax({
                url: '../backend/controllers/jobcontroller.php?action=getCompanyJobs',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        populateJobTable(data.jobs);
                    } else {
                        $('#errorMessage').text(data.message || 'Failed to fetch jobs.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching jobs:', error);
                    $('#errorMessage').text('Error loading jobs. Please try again later.');
                }
            });
        }



        // Populate the table with job data
        function populateJobTable(jobs) {
            const tableBody = $('#jobTable tbody');
            tableBody.empty(); // Clear any existing rows

            jobs.forEach(function(job) {
                const row = `
            <tr id="jobRow_${job.id}">
               
                <td>${job.title}</td>
                <td>${job.company_name}</td>
                <td>${job.category_name}</td>
                <td>${job.location}</td>
                <td>${job.salary ? `$${job.salary}` : 'N/A'}</td>
                <td>${job.job_type}</td>
                <td>${job.created_at}</td>
                <td>${job.status}</td>
                <td>
                   
                    <button class="edit-btn" data-id="${job.id}">Edit</button>
                    <button class="delete-btn" data-id="${job.id}">Delete</button>
                </td>
                
            </tr>
        `;
                tableBody.append(row);
            });
            //    update 
            //    $('.edit-btn').on('click', function () {
            //     const jobId = $(this).data('id');
            //     updatejob(jobId); // Call the update function with the job ID
            // });
            $('.edit-btn').on('click', function () {
    const jobId = $(this).data('id');
    
    // Ask for confirmation before proceeding
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to update this job?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Save jobId in session storage
            sessionStorage.setItem('jobId', jobId);
            console.log("jobid" + jobId);

            // Display loading SweetAlert
            Swal.fire({
                title: 'Loading Job Details...',
                text: 'Please wait while we load the job update form.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading(); // Show loading spinner
                }
            });

            // Perform AJAX request to fetch the job form
            $.ajax({
                url: '../frontend/company/update_job.php',
                type: 'GET',
                data: {
                    id: jobId
                },
                success: function (data) {
                    Swal.close(); // Close the loading SweetAlert on success
                    $('#FormContainer').html(data); // Inject the form into the modal
                    $('#jobFormModal').fadeIn(); // Show the modal
                },
                error: function () {
                    // Show error alert if the request fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to load the update job form. Please try again later.',
                        confirmButtonText: 'Close'
                    });
                }
            });
        } else {
            // Log or handle if the user cancels
            console.log('Update operation cancelled by user.');
        }
    });
});

            $('.delete-btn').on('click', function() {
                const jobId = $(this).data('id');
                deleteJob(jobId); // Call the delete function with the job ID
            });
        }

        function deleteJob(jobId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This job will be permanently deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../backend/controllers/jobcontroller.php?action=delete', // Backend URL
                        type: 'GET',
                        data: {
                            id: jobId
                        },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: result.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false,
                                });
                                $(`#jobRow_${jobId}`).remove(); // Remove the row from the table
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: result.message,
                                    icon: 'error',
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to delete the job. Please try again.',
                                icon: 'error',
                            });
                        },
                    });
                }
            });
        }


        // Load jobs on page load
        $(document).ready(function() {
            fetchJobs();
        });
    </script>
</body>

</html>