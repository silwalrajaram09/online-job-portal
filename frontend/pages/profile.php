<?php

require_once 'dashboard.php';

$activeSessionKey = null;
foreach ($_SESSION as $key => $value) {
    if (strpos($key, 'user_') === 0) {
        $activeSessionKey = $key;
        break;
    }
}
if (!$activeSessionKey || !isset($_SESSION[$activeSessionKey])) {
    echo "<script>
        alert('Session expired or unauthorized access! Redirecting to home.');
        window.location.href = 'home';
    </script>";
    exit; 
}
$user = $_SESSION[$activeSessionKey];
$role = $user['role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobPortal</title>
    <link rel="stylesheet" href="frontend/pages/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #loading {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            color: #333;
        }

        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
        }

        /* Profile Container */
        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .profile-header img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 2px solid #3498db;
        }

        .profile-header button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .profile-header button:hover {
            background-color: #2980b9;
        }

        /* Form Styles */
        .profile-details label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .profile-details input,
        .profile-details textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        /* Actions */
        .profile-actions {
            display: flex;
            justify-content: space-between;
        }

        .profile-actions button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #3498db;
            color: #fff;
        }

        .profile-actions button:hover {
            background-color: #2980b9;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                align-items: center;
            }

            .profile-header img {
                margin-bottom: 15px;
            }

            .profile-actions {
                flex-direction: column;
                gap: 10px;
            }
        }

        .skill-button {
            display: inline-block;
            margin: 5px;
            padding: 8px 12px;
            border: 1px solid #007bff;
            background-color: #007bff;
            color: #ffffff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        #updateProfileModal {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 50%;
            max-width: 500px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            padding: 20px;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 22px;
            font-weight: bold;
            cursor: pointer;
            color: #555;
        }

        .close:hover {
            color: red;
        }

        h3 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .error {
            color: red;
            font-size: 12px;
            display: none;
        }

        #saveProfile {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        #saveProfile:hover {
            background-color: #2980b9;
        }

        #statusMessage {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        /* .skill-button:hover {
            background-color: #007bff;
            color: #ffffff;
        } */
    </style>
</head>
    </style>
</head>

<body>
   
        <div class="profile-container">
            <div class="profile-header">
                <div class="header-left">
                    <h1>Profile</h1>
                    <p>Welcome, <strong id="user-name"></strong></p>
                </div>
                <button><a href="../frontend/pages/logout.php">logout</a></button>
                <!-- <button><a href="../frontend/pages/updateProfile.php">logout</a></button> -->
            </div>

            <div id="profile-content">

            </div>
            <button id="updateProfileBtn">Update Profile</button>

            <!-- Update Profile Modal -->
            <div id="updateProfileModal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Update Profile</h2>
                    <p id="statusMessage"></p>
                    <div id="updateFormContainer"></div>
                   
                </div>
            </div>
        </div>
 
   
    <script>
        $(document).ready(function() {
            // Make an AJAX GET request to fetch profile information
            $.ajax({
                url: "../Backend/models/profile.php", // Adjust the path to your backend file
                type: "GET",
                dataType: "json",
                success: function(response) {
                    // Check if the response indicates success
                    if (response.success) {
                        // Role-based profile rendering
                        if (response.role === 'jobseeker') {
                            $('#user-name').text(response.data.first_name || 'User');

                            // Generate jobseeker profile content
                            let skillsButtons = '';
                            if (response.data.skills) {
                                const skills = response.data.skills.split(','); // Split skills string into an array
                                skills.forEach(skill => {
                                    skillsButtons += `<button class="skill-button">${skill.trim()}</button>`;
                                });
                            }

                            $('#profile-content').html(`
                                <h2>Jobseeker Profile</h2>
                                <p><strong>Name:</strong> ${response.data.first_name ?? 'Not Available'} ${response.data.last_name ?? ''}</p>
                                <p><strong>Phone:</strong> ${response.data.phone ?? 'Not Available'}</p>
                                <p><strong>Job Category :</strong> ${response.data.category_name ?? 'Not Available'}</p>
                                <p><strong>Skills:</strong></p>
                                <div id="skills-container">
                                    ${skillsButtons || '<p>No skills available.</p>'}
                                </div>
                            `);


                        } else if (response.role === 'company') {
                            $('#user-name').text(response.data.company_name || 'User');
                            $('#profile-content').html(`
                            <h2>Company Profile</h2>
                            <p><strong>Company Name:</strong> ${response.data.company_name ?? 'Not Available'}</p>
                            <p><strong>Industry:</strong> ${response.data.industry ?? 'Not Available'}</p>
                            <p><strong>Location:</strong> ${response.data.location ?? 'Not Available'}</p>
                        `);
                        }
                    } else {
                        // Display message if the response indicates failure
                        $('#profile-content').html(`
                        <p style="color: red;"><strong>Error:</strong> ${response.message}</p>
                    `);
                    }
                },
                error: function(xhr, status, error) {
                    // Log error and display a friendly message
                    console.error("Error:", error);
                    $('#profile-content').html(`
                    <p style="color: red;">Error loading profile information. Please try again later.</p>
                `);
                }
            });
            function fetchUserProfile() {
                $.ajax({
                    url: "../Backend/models/profile.php",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            let formHtml = '';
                            if (response.role === 'jobseeker') {
                                formHtml = `
                                    <label>First Name:</label>
                                    <input type="text" id="fname" value="${response.data.first_name || ''}">
                                    
                                    <label>Last Name:</label>
                                    <input type="text" id="lname" value="${response.data.last_name || ''}">

                                    <label>Phone:</label>
                                    <input type="text" id="phone" value="${response.data.phone || ''}">

                                    <label>Skills:</label>
                                    <input type="text" id="skills" value="${response.data.skills || ''}">

                                    <button id="saveProfile">Save</button>
                                `;
                            } else {
                                formHtml = `
                                    <label>Company Name:</label>
                                    <input type="text" id="company_name" value="${response.data.company_name || ''}">

                                    <label>Industry:</label>
                                    <input type="text" id="industry" value="${response.data.industry || ''}">

                                    <label>Location:</label>
                                    <input type="text" id="location" value="${response.data.location || ''}">

                                    <button id="saveProfile">Save</button>
                                `;
                            }
                            $("#updateFormContainer").html(formHtml);
                        } else {
                            alert("Error fetching profile data.");
                        }
                    },
                    error: function () {
                        alert("Error loading profile data.");
                    }
                });
            }

            // Open Modal & Fetch Profile Data
            $("#updateProfileBtn").click(function () {
                fetchUserProfile();
                $("#updateProfileModal").fadeIn();
            });

            // Save Profile Data
            $(document).on("click", "#saveProfile", function () {
                let updatedData = {
                    fname: $("#fname").val(),
                    lname: $("#lname").val(),
                    phone: $("#phone").val(),
                    skills: $("#skills").val(),
                    company_name: $("#company_name").val(),
                    industry: $("#industry").val(),
                    location: $("#location").val()
                };

                $.ajax({
                    url: "../Backend/models/updateProfile.php",
                    type: "POST",
                    data: JSON.stringify(updatedData),
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $("#statusMessage").text("Profile updated successfully!").css("color", "green");

                            // Re-fetch updated data after saving
                            setTimeout(() => {
                                fetchUserProfile();
                            }, 1000);
                        } else {
                            $("#statusMessage").text("Update failed. Please try again.").css("color", "red");
                        }
                    },
                    error: function () {
                        $("#statusMessage").text("Error updating profile.").css("color", "red");
                    }
                });
            });

            // Close Modal
            $(".close").click(function () {
                $("#updateProfileModal").fadeOut();
            });

            $(window).click(function (event) {
                if (event.target.id === "updateProfileModal") {
                    $("#updateProfileModal").fadeOut();
                }
            });
        });
    </script>         
</body>

</html>