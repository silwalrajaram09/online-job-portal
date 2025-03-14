<?php
session_start();

// Retrieve the active session key
$activeSessionKey = null;

// Loop through all session keys to find the logged-in user
foreach ($_SESSION as $key => $value) {
    if (strpos($key, 'user_') === 0) {
        $activeSessionKey = $key;
        break;
    }
}

// Redirect to login page if no active session is found
if (!$activeSessionKey || !isset($_SESSION[$activeSessionKey])) {
    header('Location: /ojs/frontend/');
    exit;
}

// Get user details from the active session
$user = $_SESSION[$activeSessionKey];
$role = $user['role'];

// Render shared content
echo "<h1>Welcome to the Dashboard</h1>";
echo "<p>Logged in as: " . htmlspecialchars($user['email']) . "</p>";

// Role-based content
if ($role === 'jobseeker') {
    echo "<h2>Jobseeker Dashboard</h2>";
    echo "<p>Jobseeker-specific content here.</p>";
} elseif ($role === 'company') {
    echo "<h2>Company Dashboard</h2>";
    echo "<p>Company-specific content here.</p>";
} else {
    echo "<p>Unknown role. Please contact support.</p>";
}
?>