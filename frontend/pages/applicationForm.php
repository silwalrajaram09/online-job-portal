<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>

<form id="job-application-form">
    <input type="hidden" id="job_id" name="job_id" value="1"> 
    <input type="hidden" id="jobseeker_id" name="jobseeker_id"> 

    <label for="name">Full Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="phone">Phone Number:</label>
    <input type="text" id="phone" name="phone" required>

    <label for="skills">Skills:</label>
    <input type="text" id="skills" name="skills" readonly> <!-- Readonly for display -->

    <button type="submit">Apply</button>
</form>

<script>
$(document).ready(function () {
    // Fetch logged-in jobseeker details
    $.ajax({
        url: '../../Backend/controllers/JobApplicationController.php',
        type: 'POST',
        data: { action: 'fetch_jobseeker' },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#jobseeker_id').val(response.data.id);
                $('#name').val(response.data.name);
                $('#email').val(response.data.email);
                $('#phone').val(response.data.phone);
                $('#skills').val(response.data.skills);
            } else {
                alert(response.message);
            }
        },
        error: function () {
            alert('Error fetching jobseeker details.');
        }
    });

    // Apply for job
    $('#job-application-form').submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        const formData = {
            action: 'apply',
            job_id: $('#job_id').val(),
            jobseeker_id: $('#jobseeker_id').val(),
            name: $('#name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            skills: $('#skills').val()
        };

        $.ajax({
            url: '../../Backend/controllers/JobApplicationController.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    $('#job-application-form')[0].reset(); // Reset form after success
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('An error occurred while submitting the application.');
            }
        });
    });
});
</script>

</body>
</html>
