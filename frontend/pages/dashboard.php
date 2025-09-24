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
    <title>Dashboard - JobPortal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --primary-color: #667eea;
            --primary-dark: #5a67d8;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --success-color: #10b981;
            --error-color: #ef4444;
            --warning-color: #f59e0b;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --bg-card: #ffffff;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
            --border-radius-lg: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Modern Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 3rem;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            border-radius: var(--border-radius);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: var(--transition);
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(102, 126, 234, 0.1);
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .theme-toggle {
            background: none;
            border: 2px solid var(--border-color);
            color: var(--text-secondary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .theme-toggle:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            border: 3px solid white;
            box-shadow: var(--shadow-md);
        }

        .profile-avatar:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            min-width: 200px;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-top: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition);
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 1rem;
            background: linear-gradient(135deg, var(--bg-secondary), rgba(249, 250, 251, 0.8));
            border-bottom: 1px solid var(--border-color);
        }

        .dropdown-header strong {
            display: block;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .dropdown-header small {
            color: var(--text-secondary);
            text-transform: capitalize;
        }

        .dropdown-item {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-item:hover {
            background: var(--bg-secondary);
            color: var(--primary-color);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border-color);
        }

        /* Main Content Area */
        .main-content {
            margin-top: 90px;
            padding: 2rem;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            .navbar-left {
                flex-direction: column;
                gap: 1rem;
                width: 100%;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.25rem;
            }

            .nav-link {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .main-content {
                margin-top: 160px;
                padding: 1rem;
            }

            .logo {
                font-size: 1.3rem;
            }
        }

        /* Dark mode styles */
        body.dark-mode {
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
            color: #e2e8f0;
        }

        body.dark-mode .navbar {
            background: rgba(26, 32, 44, 0.95);
            border-bottom-color: rgba(255, 255, 255, 0.1);
        }

        body.dark-mode .nav-link {
            color: #cbd5e0;
        }

        body.dark-mode .nav-link:hover,
        body.dark-mode .nav-link.active {
            color: #667eea;
        }

        body.dark-mode .dropdown-menu {
            background: #2d3748;
            border-color: #4a5568;
        }

        body.dark-mode .dropdown-header {
            background: #1a202c;
        }

        body.dark-mode .dropdown-item {
            color: #cbd5e0;
        }

        body.dark-mode .dropdown-item:hover {
            background: #4a5568;
            color: #667eea;
        }

        /* Animations */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar {
            animation: slideDown 0.5s ease-out;
        }

        /* Role-specific styling */
        .role-jobseeker .nav-link.active {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .role-company .nav-link.active {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--error-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Loading state */
        .nav-link.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .nav-link.loading::after {
            content: '';
            width: 12px;
            height: 12px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 0.5rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="<?= $role === 'jobseeker' ? 'role-jobseeker' : 'role-company' ?>">
    <header class="navbar">
        <div class="navbar-container">
            <div class="navbar-left">
                <div class="logo">
                    <i class="fas fa-briefcase"></i>
                    JobPortal
                </div>
                <nav class="nav-links">
                    <a href="/ojs/dashboard" class="nav-link" data-page="dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="/ojs/dashboard/profile" class="nav-link" data-page="profile">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>

                    <?php if ($role === 'jobseeker') { ?>
                        <a href="/ojs/dashboard/jobs" class="nav-link" data-page="jobs">
                            <i class="fas fa-search"></i>
                            <span>Jobs</span>
                        </a>
                        <a href="/ojs/dashboard/applications" class="nav-link" data-page="applications">
                            <i class="fas fa-file-alt"></i>
                            <span>Applications</span>
                            <span class="notification-badge" id="applicationsBadge" style="display: none;">0</span>
                        </a>
                    <?php } ?>

                    <?php if ($role === 'company') { ?>
                        <a href="/ojs/dashboard/viewjobs" class="nav-link" data-page="jobs">
                            <i class="fas fa-briefcase"></i>
                            <span>Manage Jobs</span>
                        </a>
                        <a href="/ojs/dashboard/candidates" class="nav-link" data-page="candidates">
                            <i class="fas fa-users"></i>
                            <span>Candidates</span>
                            <span class="notification-badge" id="candidatesBadge" style="display: none;">0</span>
                        </a>
                    <?php } ?>

                    <a href="/ojs/dashboard/feedback" class="nav-link" data-page="feedback">
                        <i class="fas fa-comment-alt"></i>
                        <span>Feedback</span>
                    </a>
                </nav>
            </div>
            <div class="navbar-right">
                <button id="themeToggle" class="theme-toggle" title="Toggle dark mode">
                    <i class="fas fa-moon"></i>
                </button>
                <div class="profile-dropdown">
                    <?php
                    $username = isset($user['email']) ? $user['email'] : 'U';
                    $displayName = '';
                    if ($role === 'jobseeker') {
                        $displayName = isset($user['first_name']) ? $user['first_name'] : $username;
                        $initial = strtoupper(substr($displayName, 0, 1));
                    } else {
                        $displayName = isset($user['company_name']) ? $user['company_name'] : $username;
                        $initial = strtoupper(substr($displayName, 0, 1));
                    }
                    ?>
                    <div class="profile-avatar" onclick="toggleProfileDropdown()">
                        <?= $initial ?>
                    </div>
                    <div class="dropdown-menu" id="profileDropdown">
                        <div class="dropdown-header">
                            <strong><?= htmlspecialchars($displayName) ?></strong>
                            <small><?= ucfirst($role) ?></small>
                        </div>
                        <a href="/ojs/dashboard/profile" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            View Profile
                        </a>
                        <a href="/ojs/dashboard/settings" class="dropdown-item">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="../frontend/pages/logout.php" class="dropdown-item" style="color: var(--error-color);">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <!-- Page content will be loaded here -->
    </main>

    <script>
        $(document).ready(function() {
            // Initialize theme
            initializeTheme();
            
            // Set active nav link based on current page
            setActiveNavLink();
            
            // Initialize notification badges
            updateNotificationBadges();
            
            // Theme toggle functionality
            $('#themeToggle').click(function() {
                toggleTheme();
            });
            
            // Navigation click handlers
            $('.nav-link').click(function(e) {
                // Add loading state
                $(this).addClass('loading');
                
                // Remove loading state after navigation
                setTimeout(() => {
                    $(this).removeClass('loading');
                }, 1000);
            });

            // Close dropdown when clicking outside
            $(document).click(function(e) {
                if (!$(e.target).closest('.profile-dropdown').length) {
                    $('#profileDropdown').removeClass('show');
                }
            });

            // Update badges periodically
            setInterval(updateNotificationBadges, 30000); // Every 30 seconds
        });

        function toggleProfileDropdown() {
            $('#profileDropdown').toggleClass('show');
        }

        function initializeTheme() {
            const savedTheme = localStorage.getItem('jobportal-theme');
            const themeToggle = $('#themeToggle');
            
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
                themeToggle.html('<i class="fas fa-sun"></i>');
            }
        }

        function toggleTheme() {
            const body = document.body;
            const themeToggle = $('#themeToggle');
            
            body.classList.toggle('dark-mode');
            const isDark = body.classList.contains('dark-mode');
            
            themeToggle.html(isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>');
            localStorage.setItem('jobportal-theme', isDark ? 'dark' : 'light');
            
            // Add animation effect
            themeToggle.css('transform', 'scale(1.2)');
            setTimeout(() => {
                themeToggle.css('transform', '');
            }, 200);
        }

        function setActiveNavLink() {
            const currentPath = window.location.pathname;
            $('.nav-link').removeClass('active');
            
            $('.nav-link').each(function() {
                const href = $(this).attr('href');
                if (currentPath.includes(href) || (href === '/ojs/dashboard' && currentPath === '/ojs/dashboard')) {
                    $(this).addClass('active');
                }
            });
        }

        function updateNotificationBadges() {
            // Update applications badge for job seekers
            if ($('#applicationsBadge').length) {
                $.ajax({
                    url: '../Backend/controllers/notificationController.php',
                    data: { action: 'getApplicationsCount' },
                    success: function(response) {
                        if (response.success && response.count > 0) {
                            $('#applicationsBadge').text(response.count).show();
                        } else {
                            $('#applicationsBadge').hide();
                        }
                    }
                });
            }
            
            // Update candidates badge for companies
            if ($('#candidatesBadge').length) {
                $.ajax({
                    url: '../Backend/controllers/notificationController.php',
                    data: { action: 'getCandidatesCount' },
                    success: function(response) {
                        if (response.success && response.count > 0) {
                            $('#candidatesBadge').text(response.count).show();
                        } else {
                            $('#candidatesBadge').hide();
                        }
                    }
                });
            }
        }

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case '1':
                        e.preventDefault();
                        window.location.href = '/ojs/dashboard';
                        break;
                    case '2':
                        e.preventDefault();
                        window.location.href = '/ojs/dashboard/profile';
                        break;
                    case '3':
                        if ($('[data-page="jobs"]').length) {
                            e.preventDefault();
                            window.location.href = $('[data-page="jobs"]').attr('href');
                        }
                        break;
                }
            }
        });

        // Service worker registration for PWA features
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => console.log('SW registered'))
                    .catch(error => console.log('SW registration failed'));
            });
        }
    </script>
</body>
</html>