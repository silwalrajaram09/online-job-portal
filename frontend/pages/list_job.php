<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <style>
        .job-card {
            position: relative;
            background-color: #fff;

            border: 1px solid #e0e0e0;

            border-radius: 8px;

            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

            padding: 15px;

            margin-bottom: 20px;

            transition: box-shadow 0.3s ease;

        }



        .job-card:hover {

            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);

        }



        .job-details {

            margin-bottom: 10px;

        }



        .job-title {

            font-size: 18px;

            font-weight: bold;

            color: #007bff;

            margin: 0;

            margin-bottom: 5px;

            text-decoration: none;

        }



        .job-meta {

            font-size: 14px;

            color: #555;

        }



        .apply-button {

            padding: 8px 15px;

            background-color: #007bff;

            color: #fff;

            font-size: 14px;

            border: none;

            border-radius: 5px;

            cursor: pointer;

            text-decoration: none;

            transition: background-color 0.3s ease;

        }



        .apply-button:hover {

            background-color: #0056b3;

        }



        .status {

            color: green;

            font-weight: bolder;

        }



        .more-details {

            display: none;

            margin-top: 10px;

            border-top: 1px solid #eee;

            padding-top: 10px;

        }



        .created-at {

            position: absolute;

            top: 5px;

            right: 5px;

            font-size: 12px;

            color: #888;

        }



        #job-application-form {

            display: flex;

            flex-direction: column;

            align-items: stretch;

            /* Stretch inputs to full width */

        }



        #job-application-form h3 {

            text-align: center;

            margin-bottom: 20px;

            color: #333;

            font-size: 24px;

        }



        #job-application-form label {

            margin-top: 10px;

            font-weight: bold;

            color: #555;

            text-align: left;

            /* Align labels to the left */

        }



        #job-application-form input[type="text"],

        #job-application-form input[type="email"],

        #job-application-form input[type="phone"] {

            padding: 12px;

            margin-top: 5px;

            border: 1px solid #ccc;

            border-radius: 5px;

            font-size: 16px;

            transition: border-color 0.3s ease;

        }



        #job-application-form input[type="text"]:focus,

        #job-application-form input[type="email"]:focus,

        #job-application-form input[type="phone"]:focus {

            border-color: #007bff;

            outline: none;

            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);

        }



        #job-application-form button[type="submit"] {

            padding: 12px 20px;

            margin-top: 20px;

            background-color: #007bff;

            color: white;

            border: none;

            border-radius: 5px;

            font-size: 16px;

            cursor: pointer;

            transition: background-color 0.3s ease;

        }



        #job-application-form button[type="submit"]:hover {

            background-color: #0056b3;

        }



        #job-application-form br {

            display: none;

            /* Hide <br> tags for better spacing */

        }
    </style>
</head>

<body>
    <h2>Jobs</h2>
    <input type="text" id="titleSearchInput" placeholder="Search by title">
    <input type="text" id="locationSearchInput" placeholder="Search by location">
    <div id="loading" style="display:none;">Loading...</div>
    <div id="jobs-container">
    </div>
    <script>
        $(document).ready(function() {
            let allJobs = [];

            function loadJobs() {
                $('#loading').show();
                $.ajax({
                    url: '../Backend/controllers/JobController.php',
                    type: 'GET',
                    data: {
                        action: 'getAllJobs'
                    },
                    success: function(response) {
                        if (response.success) {
                            allJobs = response.jobs;
                            displayJobs(allJobs);
                            $('#loading').hide();
                        } else {
                            alert('Failed to fetch jobs.');
                            $('#loading').hide();
                        }
                    },
                    error: function() {
                        alert('Failed to load jobs.');
                        $('#loading').hide();
                    }
                });
            }

            function displayJobs(jobs) {
                const jobsContainer = $('#jobs-container');
                jobsContainer.empty();

                jobs.forEach(job => {
                    const jobCard = $('<div class="job-card"></div>');
                    const detailsDiv = $('<div class="job-details"></div>');
                    let deadlineDisplay = `<span class="deadline">closes Soon :${job.application_deadline}</span>`;

                    if (job.status === 'close') {
                        deadlineDisplay = '';
                    }
                    let statusColor = '';
                    if (job.status === 'open') {
                        statusColor = 'green';
                    } else if (job.status === 'close') {
                        statusColor = 'red';
                    }
                    detailsDiv.html(`
                        <a href="#" class="job-title">${job.description}</a> <br />
                        <div class="job-meta">
                            <span>&#128188; ${job.company_name}</span><br />
                            <span>&#127758; ${job.location}</span><br />
                            <span class="job-category">&#128204; <span> ${job.title}</span><br/>
                         <span class="status" style="color: ${statusColor};">${job.status}</span><br/>
                            ${deadlineDisplay}
                        </div>
                    `);

                    const moreDetailsDiv = $('<div class="more-details"></div>');
                    let moreDetailsHtml = `
                        <p><strong>Requirements:</strong> ${job.requirements || 'Not specified'}</p>
                        <p><strong>Responsibilities:</strong> ${job.responsibilities || 'Not specified'}</p>
                         <p><strong>job Type:</strong> ${job.job_type || 'Not specified'}</p>
                        <p><strong>Salary:</strong> ${job.salary || 'Not specified'}</p>
                    `;

                    if (job.status === 'open') {
                        moreDetailsHtml += `<a href="#" class="apply-button">Apply Now</a>`;
                    }

                    moreDetailsDiv.html(moreDetailsHtml);

                    const viewMoreButton = $('<button>View More</button>');
                    viewMoreButton.on('click', function() {
                        moreDetailsDiv.toggle();
                    });

                    const createdAtTime = $(`<div class="created-at">${moment(job.created_at).fromNow()}</div>`);

                    jobCard.append(detailsDiv, viewMoreButton, moreDetailsDiv, createdAtTime);
                    jobsContainer.append(jobCard);

                    moreDetailsDiv.find('.apply-button').on('click', function(event) {
                        event.preventDefault();
                        showApplicationForm(job);
                    });
                });
            }

            function showApplicationForm(job) {
                const formHtml = `
                    <form id="job-application-form" method="post">
                        <h3>Apply for Job: <span class="math-inline">${job.title}</span></h3>
                        <input type="hidden" id="job-id" name="job_id" value="${job.id}" />
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Enter your name"value="${job.first_name} ${job.last_name}"   /><br/>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email"value="${job.email}"  /><br/>
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" value="${job.phone}" placeholder="Enter your phone number"/><br/>
                        <label for="phone">skills:</label>
                        <input type="text" id="skills" name="skills" value="${job.skills}" readonly>
                        <button type="submit">Submit Application</button>
                    </form>
                `;

                const formContainer = $('<div class="form-container"></div>');
                formContainer.html(formHtml);

                const overlay = $('<div class="overlay"></div>');
                $('body').append(overlay, formContainer);

                formContainer.css({
                    position: "fixed",
                    top: "50%",
                    left: "50%",
                    transform: "translate(-50%, -50%)",
                    backgroundColor: "#fff",
                    padding: "30px",
                    boxShadow: "0 8px 20px rgba(0, 0, 0, 0.3)",
                    borderRadius: "12px",
                    zIndex: "1000",
                    width: "400px",
                    maxWidth: "90%",
                    textAlign: "center",
                    animation: "fadeIn 0.3s ease-out"
                });

                overlay.css({
                    position: "fixed",
                    top: "0",
                    left: "0",
                    width: "100vw",
                    height: "100vh",
                    backgroundColor: "rgba(0, 0, 0, 0.8)",
                    zIndex: "999",
                    backdropFilter: "blur(5px)"
                });

                $('head').append(`
                    <style>
                        @keyframes fadeIn {
                            from {
                                opacity: 0;
                                transform: translate(-50%, -60%);
                            }
                            to {
                                opacity: 1;
                                transform: translate(-50%, -50%);
                            }
                        }
                    </style>
                `);

                $("#job-application-form").on("submit", function(e) {
                    e.preventDefault();
                    if (!validateForm()) {
                        return;
                    }
                    const formData = new FormData(this);

                    fetch('../Backend/controllers/jobApplicationController.php?action=apply', {
                            method: 'POST',
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("Application submitted successfully!");
                                overlay.remove();
                                formContainer.remove();
                            } else {
                                alert("Failed to submit application: " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            alert("An error occurred while submitting the application.");
                        });
                });

                overlay.on("click", function() {
                    formContainer.remove();
                    overlay.remove();
                });
            }

            loadJobs();

            $('#titleSearchInput, #locationSearchInput').on('input', function() {
                const titleSearchTerm = $('#titleSearchInput').val().toLowerCase();
                const locationSearchTerm = $('#locationSearchInput').val().toLowerCase();

                const filteredJobs = allJobs.filter(job => {
                    const titleMatch = job.title.toLowerCase().includes(titleSearchTerm);
                    const locationMatch = job.location.toLowerCase().includes(locationSearchTerm);
                    return titleMatch && locationMatch;
                });
                displayJobs(filteredJobs);
            });

            function validateForm() {
                const nameInput = document.getElementById("name");
                const emailInput = document.getElementById("email");
                const phoneInput = document.getElementById("phone");

                const nameValue = nameInput.value.trim();
                const emailValue = emailInput.value.trim();
                const phoneValue = phoneInput.value.trim();

                let isValid = true;

                // Name Validation
                if (nameValue === "") {
                    alert("Name is required.");
                    nameInput.focus();
                    isValid = false;
                    return isValid;
                }

                // Email Validation
                if (emailValue === "") {
                    alert("Email is required.");
                    emailInput.focus();
                    isValid = false;
                    return isValid;
                } else if (!isValidEmail(emailValue)) {
                    alert("Invalid email format.");
                    emailInput.focus();
                    isValid = false;
                    return isValid;
                }

                // Phone Validation
                if (phoneValue === "") {
                    alert("Phone number is required.");
                    phoneInput.focus();
                    isValid = false;
                    return isValid;
                } else if (!isValidPhone(phoneValue)) {
                    alert("Invalid phone number format.");
                    phoneInput.focus();
                    isValid = false;
                    return isValid;
                }

                return isValid;
            }

            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function isValidPhone(phone) {
                const phoneRegex = /^98[0-9]{8}$/;; 
                return phoneRegex.test(phone);
            }
        });
    </script>
</body>

</html>