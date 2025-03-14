<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="frontend/assets/css/loginform.css">

<head>
    <meta charset="UTF-8">
    <title>Job Portal</title>
    <style>
        #resetPasswordModal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4); /* Semi-transparent black background */
    padding-top: 60px;
    font-family: sans-serif; /* Consistent font */
}

#resetPasswordModalContent {
    background-color: #fefefe; /* White background for the modal content */
    margin: 5% auto; /* Centers the modal vertically and horizontally */
    padding: 20px;
    border: 1px solid #888; /* Light gray border */
    width: 80%; /* Adjust width as needed */
    max-width: 500px; /* Maximum width for responsiveness */
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); /* Subtle shadow */
    border-radius: 5px; /* Rounded corners */
}

#closeModal {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer; /* Indicate it's clickable */
}

#closeModal:hover,
#closeModal:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

#resetPasswordModal h2 {
    margin-top: 0;
    margin-bottom: 20px;
    text-align: center;
    color: #333;
}

#resetPasswordModal input[type="password"] {
    width: calc(100% - 22px); /* Account for padding and border */
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* Include padding and border in width */
}

#resetPasswordButton {
    background-color: #4CAF50; /* Green button */
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
}

#resetPasswordButton:hover {
    background-color: #45a049; /* Darker green on hover */
}

#resetMessage {
    margin-top: 15px;
    text-align: center;
}
    </style>
</head>

<body>

    <fieldset>
        <form action="" method="post" class="container" id="Form">
            <input type="hidden" name="action" value="login">
            <h3>login</h3>
            <div id="Message"></div>
            <div class="form-group">

                <i class="fas fa-user"></i>
                <label for="email" class="required">email</label>
                <input type="text" name="email" id="email">

            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>

                <label for="password" class="required">password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password">

                    <div class="checkbox-container" onclick="togglePassword()">
                        <input type="checkbox" id="show-password">
                        <span class="checkmark"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" id="role">
                        <option value="">select a role</option>
                        <option value="jobseeker">JobSeeker</option>
                        <option value="company">Company</option>

                    </select>

                </div>
            </div>
            <div class="form-group">
                <span>Remember me<input type="checkbox" name="rememberme" value=""></span>
            </div>

            <div class="form-group">
                <input type="submit" value="login">
            </div>
            <div class="form-group">
                <a href="#" id="forgetPasswordLink">Forget password?</a>
            </div>
            <p>
                Don't have an account? <a href="home">register here</a>
            </p>
        </form>
    </fieldset>
    <div id="resetPasswordModal" style="display: none;">
        <div id="resetPasswordModalContent">
            <span id="closeModal">&times;</span>
            <h2>Reset Password</h2>
            <div id="resetMessage"></div>
            <input type="hidden" id="resetEmail">
            <input type="password" id="newPassword" placeholder="New Password">
            <input type="password" id="confirmPassword" placeholder="confirmPassword">
            <button id="resetPasswordBtn">Reset Password</button>
           
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script src="frontend/assets/js/script.js"></script>
</body>

</html>