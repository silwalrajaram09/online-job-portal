<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_job_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        // Check if the email exists
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row['id'];

            // Generate a unique token
            $token = bin2hex(random_bytes(32));
            $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expires in 1 hour

            // Store the token in the database
            $sql = "UPDATE users SET reset_token = '$token', token_expiry = '$expiry' WHERE id = $user_id";
            if ($conn->query($sql) === TRUE) {
                // Send password reset email
                $resetLink = "https://yourwebsite.com/reset_password.php?token=" . $token; // Replace with your actual URL

                $to = $email;
                $subject = "Password Reset Request";
                $message = "Click the following link to reset your password: " . $resetLink;
                $headers = "From: noreply@yourwebsite.com"; // Replace with your email

                if (mail($to, $subject, $message, $headers)) {
                    $_SESSION['message'] = "A password reset link has been sent to your email.";
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = "Failed to send password reset email.";
                    $_SESSION['message_type'] = 'error';
                }
            } else {
                $_SESSION['message'] = "Error updating record: " . $conn->error;
                $_SESSION['message_type'] = 'error';
            }
        } else {
            $_SESSION['message'] = "Email address not found.";
            $_SESSION['message_type'] = 'error';
        }
        header("Location: forgotPassword"); // Redirect back to the form
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: <?php echo $_SESSION['message_type'] === 'success' ? 'green' : 'red'; ?>;">
            <?php echo $_SESSION['message']; ?>
        </p>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <form method="post" action="forgot_password.php">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="Send Reset Link">
    </form>
</body>
</html>
<?php
$conn->close();
?>