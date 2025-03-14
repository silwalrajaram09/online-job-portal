<?php
// authcontroller.php

header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_job_portal"; // Your database name

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
            case 'verifyEmail':
                if (isset($_POST['email'])) {
                    session_start();
                    $email = $_POST['email'];
                    //store into the session
                    $_SESSION['email'] = $email;
                    // Check if email exists in the users table
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    $count = $stmt->fetchColumn();

                    if ($count > 0) {
                        echo json_encode(['success' => true, 'message' => 'Email verified']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Email not found']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Email parameter missing']);
                }
                break;

            case 'resetPassword':
                if (isset($_POST['newPassword'])) {
                    session_start();
                    //session email
                    $email = $_SESSION['email'];
                   
                    $password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);

                    $stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':email', $email);

                    if ($stmt->execute()) {
                        echo json_encode(['success' => true, 'message' => 'Password reset successful']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to reset password']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Email or password parameter missing']);
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Action parameter missing']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn = null;
?>