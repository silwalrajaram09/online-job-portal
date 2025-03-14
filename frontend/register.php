<!DOCTYPE html>
<html>

<head>
    <title>Jobseeker Registration</title>
    <link rel="stylesheet" href="/ojs/frontend/assets/css/register.css">
    <style>
        #skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 10px 0;
        }

        .skill-item {
            padding: 10px 15px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .skill-item.selected {
            background-color: #007bff;
            color: white;
            border-color: #0056b3;
        }

        .skill-item:hover {
            background-color: #e0e0e0;
        }
    </style>

</head>

<body>
    <div class="container">
        <h1>Jobseeker Registration</h1>
        <p>Fill out the form below to create a free account. Once you create an account, log in to the system and create your profile to start applying
            to the jobs you are looking for. It's all free.</p>
        <hr>
        <form action="" method="post" id="Form">
            <input type="hidden" name="action" value="registerJobseeker">
            <div id="Message"></div>
            <h2>Login Information</h2>
            <fieldset>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="text" id="email" name="email" value="">


                </div>


                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="*******" value="">

                </div>


                <hr id="line">
                <h2>Personal Information</h2>
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" id="fname" name="fname" value="">


                </div>

                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" id="lname" name="lname" value="">

                </div>

                <div class="form-group">
                    <label for="phone">Contact Number:</label>
                    <input type="tel" id="contact_number" name="phone" value="">


                </div>
                <hr>
                <h2>Job Preference</h2>
                <div class="form-group">
                    <label for="job_category">Prefer Job Category:</label>
                    <select id="job_category" name="job_category">
                        <!-- <option value="">Select a category</option> -->
                        <!-- <option value="software">Software Engineer</option>
                        <option value="hardware">Hardware Engineer</option>
                        <option value="web">Web Developer</option> -->
                    </select>

                </div>
                <label for="skills">Select Your Skills:</label><br>

                <div id="skills-container">
                    <div class="skill-item" data-skill="JavaScript">JavaScript</div>
                    <div class="skill-item" data-skill="PHP">PHP</div>
                    <div class="skill-item" data-skill="Python">Python</div>
                    <div class="skill-item" data-skill="Java">Java</div>
                    <div class="skill-item" data-skill="HTML">HTML</div>
                    <div class="skill-item" data-skill="CSS">CSS</div>
                    <div class="skill-item" data-skill="SQL">SQL</div>
                    <div class="skill-item" data-skill="Node.js">Node.js</div>
                    <div class="skill-item" data-skill="React">React</div>
                    <div class="skill-item" data-skill="Angular">Angular</div>
                </div>

                <input type="hidden" id="selected-skills" name="skills" value="">
            </fieldset>
            <div class="form-group">

                <input type="submit" value="create account">
            </div>
        </form>

        <p>By creating an account with us, you're confirming that you've read our <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></p>

        <p>Do you already have an account with us? <a href="../login">Click here to login</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/ojs/frontend/assets/js/script.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch job categories dynamically via AJAX
            $.ajax({
                url: '../backend/controllers/user.php', // Modify this path if needed
                method: 'GET',
                data: {
                    action: 'fetch_all_categories'
                }, // Action to fetch all categories
                dataType: 'json',
                success: function(response) {
                    let dropdown = $('#job_category');
                    dropdown.empty(); // Clear existing options
                    dropdown.append('<option value="">Select Job Category</option>'); // Default option

                    if (response.error) {
                        alert(response.error); // If there's an error
                    } else {
                        // Loop through each category and add it to the dropdown
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
        });
        document.addEventListener("DOMContentLoaded", function() {
            const skillItems = document.querySelectorAll(".skill-item");
            const selectedSkillsInput = document.getElementById("selected-skills");

            // Array to store selected skill names
            const selectedSkills = [];

            skillItems.forEach((item) => {
                item.addEventListener("click", () => {
                    const skillName = item.getAttribute("data-skill");

                    // Toggle selection
                    if (selectedSkills.includes(skillName)) {
                        // Remove skill from selected
                        const index = selectedSkills.indexOf(skillName);
                        selectedSkills.splice(index, 1);
                        item.classList.remove("selected");
                    } else {
                        // Add skill to selected
                        selectedSkills.push(skillName);
                        item.classList.add("selected");
                    }

                    // Update hidden input value with a comma-separated string of skill names
                    selectedSkillsInput.value = selectedSkills.join(",");
                });
            });
        });
    </script>
</body>

</html>