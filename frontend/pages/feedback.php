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
$id= $user['id'];
$email= $user['email'];
$role = $user['role'];
//print_r( $_SESSION[$activeSessionKey]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Feedback</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    .feedback-form {
        width: 400px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: sans-serif;
    }

    .feedback-form label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .feedback-form input[type="text"],
    .feedback-form textarea,
    .feedback-form select {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .feedback-form textarea {
        resize: vertical; /* Allow vertical resizing */
        min-height: 100px;
    }

    .feedback-form button {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .feedback-form button:hover {
        background-color: #0056b3;
    }

    .feedback-form .error-message,
    .feedback-form .success-message {
        margin-top: 10px;
        padding: 10px;
        border-radius: 4px;
    }

    .feedback-form .error-message {
        background-color: #ffe6e6;
        border: 1px solid #ff0000;
        color: #ff0000;
    }

    .feedback-form .success-message {
        background-color: #e6ffe6;
        border: 1px solid #008000;
        color: #008000;
    }

</style>
</head>
<body>
    <div class="feedback-form">
        <h2><?php echo $user['role'] ?> Feedback</h2>
        <div id="feedbackMessage"></div>
        
    <form id="feedbackForm">
        <input type="hidden" name="user_type" value="<?php echo $user['role'] ?>">
        <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">

        <label for="user_email">Email:</label>
        <input type="text" id="user_email" name="user_email" value="<?php echo $email ?>" readonly>

        <label for="feedback_text">Feedback:</label>
        <textarea id="feedback_text" name="feedback_text" placeholder="Enter your feedback" required></textarea>

        <label for="rating">Rating (optional):</label>
        <select id="rating" name="rating">
            <option value="">Select Rating</option>
            <option value="1">1 Star</option>
            <option value="2">2 Stars</option>
            <option value="3">3 Stars</option>
            <option value="4">4 Stars</option>
            <option value="5">5 Stars</option>
        </select>

        <button type="button" id="submitFeedback">Submit Feedback</button>
    </form>

    
</div>


  

    <script>
        $(document).ready(function() {
        $("#submitFeedback").click(function() {
            if ($("#feedback_text").val().trim() === "") {
                $("#feedbackMessage").html('<div class="error-message">Please enter your feedback.</div>');
                return;
            }

            $("#feedbackMessage").html("Submitting...");

            $.ajax({
                url: '../Backend/controllers/feedbackController.php?action=submit_feedback',
                type: 'POST',
                dataType: 'json',
                data: $("#feedbackForm").serialize(),
                success: function(response) {
                    if (response.success) {
                        $("#feedbackMessage").html('<div class="success-message">' + response.message + '</div>');
                        $("#feedbackForm")[0].reset();
                        setTimeout(function() {
                            $("#feedbackMessage").empty();
                        }, 3000);
                    } else {
                        $("#feedbackMessage").html('<div class="error-message">' + response.message + '</div>');
                    }
                },
                error: function() {
                    $("#feedbackMessage").html('<div class="error-message">An error occurred. Please try again.</div>');
                }
            });
        });
    });
       
    </script>
</body>
</html>