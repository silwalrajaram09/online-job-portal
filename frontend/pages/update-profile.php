<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        /* Modal Styling */
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

        h2 {
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
    </style>
</head>
<body>

    <button id="updateProfileBtn">Update Profile</button>

    <!-- Update Profile Modal -->
    <div id="updateProfileModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Update Profile</h2>
            <div id="updateFormContainer"></div>
            <p id="statusMessage"></p>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            function fetchUserProfile() {
                $.ajax({
                    url: "../../Backend/models/profile.php",
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
                    url: "../../Backend/models/updateProfile.php",
                    type: "POST",
                    data: updatedData,
                    dataType: "json",
                    success: function (response) {
                       // console.error("Full Response:", xhr.responseText);
                        if (response.success) {
                            $("#statusMessage").text("Profile updated successfully!").css("color", "green");

                            // Re-fetch updated data after saving
                            setTimeout(() => {
                                fetchUserProfile();
                            }, 1000);
                        } else {
                            console.log(response.message);
                            $("#statusMessage").text(response.message|| "failed").css("color", "red");
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
