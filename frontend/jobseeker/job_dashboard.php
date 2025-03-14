<?php
    define('BASE_URL', '/ojs/');
    require_once __DIR__ . '/../../Backend/controllers/JobSeekerController.php';
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Jobseeker Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="dashboard">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>JobPortal</h2>
            <button class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        </div>
        <ul class="sidebar-menu">
    <li><a href=""><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li> <!-- Full page refresh -->
    <li><a href="#" data-page="profile.php"><i class="fas fa-user"></i> <span>Profile</span></a></li>
    <li><a href="#" data-page="jobs.php"><i class="fas fa-briefcase"></i> <span>Jobs</span></a></li>
    <li><a href="#" data-page="applications.php"><i class="fas fa-file-alt"></i> <span>Applications</span></a></li>
    <li><a href="#" data-page="networking.php"><i class="fas fa-network-wired"></i> <span>Networking</span></a></li>
    <li><a href="#" data-page="settings.php"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
</ul>

    </aside>
    <main class="main-content" id="main-content">
            <div id="content-container">
                <h2>Welcome to JobPortal</h2>
                <p>Select an option from the sidebar to get started.</p>
            </div>
        </main>
</div>

        <!-- Main Content -->
        <!-- <main class="main-content">
            <header>
                <h1>Welcome, <span id="username">Jobseeker</span></h1>
                <div class="header-actions">
                    <button id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </div>
            </header>

            <section id="dashboard" class="section">
                <h2>Dashboard Overview</h2>
                <div class="cards">
                    <div class="card">
                        <h3>Saved Jobs</h3>
                        <p id="savedJobsCount">0</p>
                    </div>
                    <div class="card">
                        <h3>Applications</h3>
                        <p id="applicationsCount">0</p>
                    </div>
                    <div class="card">
                        <h3>Connections</h3>
                        <p id="connectionsCount">0</p>
                    </div>
                </div>

                <div class="chart-container">
                    <canvas id="jobApplicationsChart"></canvas>
                </div>
            </section>

            <section id="profile" class="section">
                <h2>Profile</h2>
                <div class="profile-info">
                    <img src="img/default-profile.png" alt="Profile Picture" id="profilePicture">
                    <div>
                        <p>Email: <span id="userEmail">example@example.com</span></p>
                        <p>Resume: <span id="resumeStatus">Not Uploaded</span></p>
                        <button onclick="editProfile()">Edit Profile</button>
                        <button onclick="uploadResume()">Upload Resume</button>
                    </div>
                </div>
            </section>
        </main>
    </div> -->

    <script src="js/dashboard.js"></script>
</body>
</html>
