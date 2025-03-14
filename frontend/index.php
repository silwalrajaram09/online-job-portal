<?php
//check the sesion if exist redirect to dashboard
session_start();
$activeSessionKey = null;

// Loop through all session keys to find the logged-in user
foreach ($_SESSION as $key => $value) {
    if (strpos($key, 'user_') === 0) {
        $activeSessionKey = $key;
        break;
    }
}

// Redirect to login page if  active session is found
if ($activeSessionKey || isset($_SESSION[$activeSessionKey])) {
    header('Location: dashboard');
    exit;
}
// header("Location: /ojs/home");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal</title>
    <link rel="stylesheet" href="frontend/assets/css/style.css">
    <style>
        #search-bar {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px auto;
            max-width: 600px;
            padding: 10px;

            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        #search-bar input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        #search-bar input:focus {
            border-color: #007bff;
            box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.5);
        }

        #search-bar button {
            padding: 10px 15px;
            background-color: #f39c12;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #search-bar button:hover {
            background-color: rgba(219, 139, 11, 0.91);
        }

        #pagination-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        #pagination-controls button {
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        #pagination-controls button:hover {
            background-color: #0056b3;
        }

        #pagination-controls button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="main-container">
        <header>
            <h1>JobPortal</h1>

            <nav>
                <ul>
                    <li><a href="#jobs">Browse Jobs</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <button class="login-btn" onclick="showLoginForm()"> <a href="login">Login</a>
                </button>
                <div class="dropdown">
                    <button class="dropbtn" onclick="toggleDropdown()">Sign Up</button>
                    <div class="dropdown-content" id="myDropdown" style="display: none;">
                        <!-- <a href="javascript:void(0)" onclick="showRegistrationForm()" >Register as Job Seeker</a>
                        <a href="javascript:void(0)" onclick="showCompanyForm()">Register as Company</a> -->
                        <a href="register/jobseeker">Register as jobseeker</a>
                        <a href="register/company">Register as Company</a>

                    </div>
                </div>
            </div>
        </header>
        <section id="hero">
            <h2>Your Dream Job Awaits!</h2>
            <div id="search-bar">
                <input type="text" id="jobTitle" placeholder="Job Title" required>
                <input type="text" id="location" placeholder="Location" required>
                <button onclick="searchJobs()">Search</button>
            </div>
        </section>

        <section id="jobs">
            <h2>Latest Job Listings</h2>
            <div id="jobList"></div>
        </section>
        <div id="pagination-controls">
            <button id="prevPage" onclick="changePage(-1)">Prev</button>
            <span id="pageNumber">1</span>
            <button id="nextPage" onclick="changePage(1)">Next</button>
        </div>
        <section id="about">
            <h2>About Us</h2>
            <p>We connect job seekers with top companies. Explore a wide range of job opportunities.</p>
        </section>
        <section id="contact">
            <h2>Contact Us</h2>
            <form id="contactForm">
                <input type="text" id="name" name="name" placeholder="Your Name" required><br>
                <input type="email" id="email" name="email" placeholder="Your Email" required><br>
                <textarea id="message" name="message" placeholder="Your Message" required></textarea><br>
                <button type="submit">Send Message</button>
            </form>
        </section>
    </div>

    <!-- Modal -->
    <div id="loginModal" class="modal" style="display: none;">
        <div class="modal-content" id="modalContent">
            <span class="close" onclick="closeModal()">&times;</span>
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
    <footer>
        <p>&copy; 2024 JobPortal. All Rights Reserved.</p>
    </footer>
    <script src="https://smtpjs.com/v3/smtp.js"></script>


    <script src="assets/js/script.js"></script>
    <script>
        // Toggle Dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById("myDropdown");
            dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
        }

        let jobsData = [];
        let currentPage = 1;
        const jobsPerPage = 6;

        // Fetch jobs from backend
        function fetchJobs() {
            $.ajax({
                url: "Backend/controllers/JobController.php",
                data: {
                    "action": "getAll"
                },
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        jobsData = response.jobs;
                        displayJobs(jobsData);
                        updatePaginationControls();
                    } else {
                        $("#jobList").html("<p>No jobs available.</p>");
                    }
                },
                error: function() {
                    $("#jobList").html("<p>Error fetching job listings.</p>");
                }
            });
        }

        // Function to display jobs with pagination
        function displayJobs(filteredJobs = jobsData) {
            let startIndex = (currentPage - 1) * jobsPerPage;
            let endIndex = startIndex + jobsPerPage;
            let jobsToShow = filteredJobs.slice(startIndex, endIndex);
            let jobHtml = "";

            if (jobsToShow.length === 0) {
                jobHtml = "<p>No jobs available.</p>";
            } else {
                jobsToShow.forEach((job) => {
                    jobHtml += `
                <div class="job-card" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                    <h3>${job.title}</h3>
                    <p><strong>Company:</strong> ${job.company_name}</p>
                    <p><strong>Location:</strong> ${job.location}</p>
                    <p><strong>Posted:</strong> ${job.created_at}</p>
                    <p><strong>Status:</strong> ${job.status}</p>
                    
                    <!-- Initially hidden detailed section -->
                    <div class="job-details" style="display: none; margin-top: 10px;">
                        <p><strong>Description:</strong> ${job.description}</p>
                        <p><strong>Requirements:</strong> ${job.requirements || "Not specified"}</p>
                        <p><strong>Salary:</strong> ${job.salary || "Not disclosed"}</p>
                         <p><strong>job type :</strong> ${job.job_type || "Not disclosed"}</p>
                        <p><strong>Application Deadline:</strong> ${job.application_deadline}</p>
                    </div>
                    
                    <!-- View More Button -->
                    <button class="view-more-btn" onclick="toggleDetails(this)">View More</button>
                    
                    <!-- Apply Button -->
                    <button onclick="applyNow()">Apply Now</button>
                </div>
            `;
                });
            }

            $("#jobList").html(jobHtml);
            updatePaginationControls();
        }

        function applyNow() {
            alert('Please login first!!');
            window.location.href = 'login'; 
        }

        // Function to update pagination controls
        function updatePaginationControls() {
            let totalPages = Math.ceil(jobsData.length / jobsPerPage);

            $("#prevPage").prop("disabled", currentPage === 1);
            $("#nextPage").prop("disabled", currentPage === totalPages || jobsData.length === 0);
        }

        // Function to move to the next page
        function nextPage() {
            let totalPages = Math.ceil(jobsData.length / jobsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                displayJobs();
            }
        }

        // Function to move to the previous page
        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                displayJobs();
            }
        }

        // Function to filter jobs based on search input
        function searchJobs() {
            let jobTitle = $("#jobTitle").val().toLowerCase();
            let location = $("#location").val().toLowerCase();

            let filteredJobs = jobsData.filter(job =>
                (job.title.toLowerCase().includes(jobTitle) || jobTitle === "") &&
                (job.location.toLowerCase().includes(location) || location === "")
            );

            currentPage = 1; // Reset to first page after search
            displayJobs(filteredJobs);
        }

        // Toggle job details on "View More" button click
        function toggleDetails(button) {
            let details = $(button).prev(".job-details");
            details.toggle();
            $(button).text(details.is(":visible") ? "View Less" : "View More");
        }

        // Fetch jobs on page load
        $(document).ready(fetchJobs);

        function toggleDetails(button) {
            const jobCard = button.closest(".job-card");
            const detailsDiv = jobCard.querySelector(".job-details");
            if (detailsDiv.style.display === "none" || detailsDiv.style.display === "") {
                detailsDiv.style.display = "block";
                button.innerText = "View Less";
                jobCard.style.height = "auto";

            } else {
                detailsDiv.style.display = "none";
                button.innerText = "View More";
                jobCard.style.height = "";
                jobCard.style.boxShadow = "";
            }
        }


        // Function to change pages
        function changePage(step) {
            currentPage += step;
            displayJobs();
        }

        // function sendEmail() {
        //         var email = document.getElementById("email").value;
        //         var name = document.getElementById("name").value;
        //         var message = document.getElementById("message").value;

        //         var messageBody = `
        //     <h2>New Contact Form Submission</h2>
        //     <p><strong>Name:</strong> ${name}</p>
        //     <p><strong>Email:</strong> ${email}</p>
        //     <p><strong>Message:</strong> ${message}</p>
        // `;

        //         Email.send({
        //             Host: "smtp.gmail.com",
        //             Username: "silwamrajaram2@gmail.com", // Replace with your Gmail
        //             Password: "hzzg kcbk jemb hnhr", // Use App Password, NOT your actual password
        //             To: "rajaramsilwal819@gmail.com", // Replace with recipient's email
        //             From: "silwamrajaram2@gmail.com", // Must match your Gmail
        //             ReplyTo: email, // User's email for replies
        //             Subject: "New Contact Form Message",
        //             Body: messageBody
        //         }).then(
        //             message => {
        //                 if (message === 'OK') {
        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: 'Message Sent!',
        //                         text: 'Your message has been sent successfully.'
        //                     });
        //                     document.getElementById("contactForm").reset(); // Reset the form
        //                 } else {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Oops...',
        //                         text: 'Something went wrong! Please try again later.'
        //                     });
        //                 }
        //             }
        //         );
        //     }
        $(document).ready(function() {
            $("#contactForm").submit(function(e) {
                e.preventDefault();

                var formData = {
                    name: $("#name").val().trim(),
                    email: $("#email").val().trim(),
                    message: $("#message").val().trim(),
                };

                $.ajax({
                    url: "backend/config/send_mail.php",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Message Sent!',
                                text: response.message,
                            });
                            $("#contactForm")[0].reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message,
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong. Try again later.',
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>