<?php

session_start();


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
    <link rel="stylesheet" href="css/style.css">
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
    </style>
</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>JobPortal</h2>
                <button class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            </div>
            <div id="loading">Loading...</div>
            <ul class="sidebar-menu">
                <li><a href="/ojs/dashboard" data-page=""><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="/ojs/dashboard/profile" data-page="profile"><i class="fas fa-user"></i> <span>Profile</span></a></li>

                <?php if ($role === 'jobseeker') { ?>
                    <li><a href="/ojs/dashboard/jobs" data-page="jobs"><i class="fas fa-briefcase"></i> <span>Jobs</span></a></li>
                    <li><a href="/ojs/dashboard/applications" data-page="applications"><i class="fas fa-file-alt"></i> <span>Applications</span></a></li>
                <?php } ?>

                <?php if ($role === 'company') { ?>
                    <li><a href="/ojs/dashboard/viewjobs" data-page="viewjobs"><i class="fas fa-briefcase"></i> <span>Jobs</span></a></li>
                    <li><a href="/ojs/dashboard/candidates" data-page="candidates"><i class="fas fa-users"></i><span>Candidates</span></a></li>
                <?php } ?>

                <li><a href="/ojs/dashboard/feedback" data-page="feedback"><i class="fas fa-comments"></i> <span>Feedback</span></a></li>
            </ul>



        </aside>
      </div>
    <script src="js/dashboard.js"></script>

</body>

</html>