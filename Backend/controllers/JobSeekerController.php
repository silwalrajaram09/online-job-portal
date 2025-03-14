<?php
require_once 'AuthController.php';
session_start(); // Start the session

// Check if session variables exist
if (isset($_SESSION['email']) && isset($_SESSION['user_id'])) {
    $response = [
        "status" => "success",
        "email" => $_SESSION['user_email'],
        "user_id" => $_SESSION['user_id']
    ];
} else {
    $response = [
        "status" => "error",
        "message" => "No session data found."
    ];
}


?>
