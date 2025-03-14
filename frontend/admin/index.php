<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - JobPortal</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        .main-content {
            padding: 20px;
        }
        #content-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
            <button class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="#" data-page=""><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="#" data-page="manage-user.php"><i class="fas fa-users"></i> <span>Manage Users</span></a></li>
            <li><a href="#" data-page="manage_category.php"><i class="fas fa-tags"></i> <span>Manage category</span></a></li>
            <li><a href="#" data-page="show_jobpost.php"><i class="fas fa-briefcase"></i> <span>Manage Jobs</span></a></li>
            <li><a href="#" data-page="manage_Applications.php"><i class="fas fa-file-alt"></i> <span>Manage Applications</span></a></li>
            <li><a href="#" data-page="feedback.php"><i class="fas fa-comments"></i> <span>Feedback</span></a></li>
        
        </ul>
    </aside>

    <main class="main-content" id="main-content">
        <div id="content-container">
            <h2>Welcome, Admin</h2>
            <p>Select an option from the sidebar to manage the portal.</p>
            <button><a href="logout.php">logout</a></button>
        </div>
    </main>
</div>
<script src="js/script.js"></script>
<script>
    // function toggleSidebar() {
    //     document.querySelector('.sidebar').classList.toggle('collapsed');
    // }

    // $(document).ready(function() {
    //     $('.sidebar-menu a').click(function(e) {
    //         e.preventDefault();
    //         var page = $(this).data('page');
    //         if (page) {
    //             $('#content-container').load(page);
    //         } else {
    //             $('#content-container').html('<h2>Welcome, Admin</h2><p>Select an option from the sidebar to manage the portal.</p>'); // Default content
    //         }
    //     });
    // });
</script>

</body>
</html>