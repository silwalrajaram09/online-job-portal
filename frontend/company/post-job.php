
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* General Form Styling */
        /* body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        } */

        #jobForm {
            background: #fff;
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #jobFormContainer label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
           
        }

        #jobFormContainer input,
        #jobFormContainer textarea,
        #jobFormContainer select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        #jobFormContainer input:focus,
        #jobFormContainer textarea:focus,
        #jobFormContainer select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        #jobFormContainer #postJobBtn {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        #jobFormContainer #postJobBtn:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        #jobFormContainer #postJobBtn:active {
            transform: scale(0.95);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #jobForm {
                padding: 15px;
            }

            #postJobBtn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
<div id="jobFormContainer">
    <form action="" id="jobForm" method="post">
    
        <h4>Jop post</h4>
        <p id="Message"></p>
        <!-- Job Category -->
        <label for="job_category">Job Category:</label>
        <select id="job_category" name="job_category" >
            <!-- Dynamically populate options from the `job_categories` table -->
        </select>

        
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title"  placeholder="Enter job title">

       
        <label for="description">Job Description:</label>
        <textarea id="description" name="description"  placeholder="Enter job description"></textarea>

       
        <label for="location">Location:</label>
        <input type="text" id="location" name="location"  placeholder="Enter job location">

        
        <label for="salary">Salary:</label>
        <input type="number" id="salary" name="salary" step="0.01" placeholder="Enter job salary">

        
        
        <label for="job_type">Job Type:</label>
        <select id="job_type" name="job_type" >
            <option value="Full-time">Full-time</option>
            <option value="Part-time">Part-time</option>
            <option value="Freelance">Freelance</option>
        </select>

        <label for="Requirements">Requirements:</label>
        <input type="text" id="requirements" name="requirements" >

        <label for="application_deadline">Application Deadline:</label>
        <input type="date" id="application_deadline" name="application_deadline" >

       
        <!-- <label for="requirements">Requirements:</label>
        <textarea id="requirements" name="requirements" placeholder="Enter job requirements"></textarea> -->

        
        <button type="submit" id="postJobBtn">Post Job</button>
    </formac>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- <script src="js/post-job.js"></script> -->
      <!-- // Add animation effect on button click
        document.getElementById('postJobBtn').addEventListener('click', function() {
            const button = this; // Reference the button
            // Visual feedback for posting
            button.innerHTML = "Posting...";
            button.style.backgroundColor = "#28a745"; // Change to green
            button.style.transform = "scale(0.95)";

            // Simulate server submission (replace this with your actual AJAX call)
            setTimeout(() => {
                // Reset button and show success
                button.innerHTML = "Post Job";
                button.style.backgroundColor = "#007bff"; // Reset to original color
                button.style.transform = "scale(1)";
                alert("Job posted successfully!"); // Placeholder for actual server response
                document.getElementById('jobForm').reset(); // Reset form fields
            }, 2000);
        }); -->
    <script>
    
        $(document).ready(function() {
            // Fetch job categories dynamically via AJAX
            $.ajax({
                url: '../backend/controllers/user.php', // Modify this path if needed
                method: 'GET',
                data: {
                    action: 'fetch_all_categories'
                }, 
                dataType: 'json',
                success: function(response) {
                    let dropdown = $('#job_category');
                    dropdown.empty(); 
                    dropdown.append('<option value="">Select Job Category</option>'); // Default option

                    if (response.error) {
                        alert(response.error); 
                    } else {
                      
                        response.forEach(function(category) {
                            dropdown.append(
                                `<option value="${category.id}">${category.category_name}</option>`
                            );
                        });
                    }
                },
                error: function() {
                    alert('Failed to load categories.');
                }
            });
            $("#jobForm").submit(function (e) {
    e.preventDefault(); // Prevent the default form submission

    // Show confirmation dialog before proceeding with the form submission
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to create this job post?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, create it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Display loading SweetAlert
            Swal.fire({
                title: 'Creating Job Post...',
                text: 'Please wait while your job post is being created.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading(); // Show loading spinner
                }
            });

            // Perform AJAX request to submit the form
            $.ajax({
                url: "../Backend/controllers/JobController.php?action=create", 
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reset the form
                            $("#jobForm")[0].reset();

                            // Hide the modal or div
                            $('#jobFormModal').fadeOut(); // Hide modal (adjust based on your modal's ID)
                            $('#FormContainer').html(''); // Clear the form container if needed
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                            confirmButtonText: 'Close'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        confirmButtonText: 'Close'
                    });
                }
            });
        } else {
            console.log('Job creation cancelled by user.');
        }
    });
});

           });

    </script> 
   
</body>

</html>