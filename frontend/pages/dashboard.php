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
    <link rel="stylesheet" href="frontend/pages/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding-top: 70px;
            /* Offset for fixed navbar */
            font-family: Arial, sans-serif;
            background-color: rgb(239, 240, 241);
        }

        header.navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgb(254, 255, 255);
            color: rgb(12, 15, 15);
            padding: 15px 20px;
            z-index: 999;
            flex-wrap: wrap;

            /* Enhanced shadow */
            box-shadow: 0 4px 12px rgba(228, 216, 216, 0.25), 0 1px 0 rgba(255, 255, 255, 0.05) inset;
        }


        .navbar-left {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 100px;
        }

        .logo {
            margin-right: 100px;
            font-size: 22px;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            align-items: center;
            margin-left: 100px;

        }

        .nav-links a {
            margin-right: 20px;
            margin-left: 20px;
            text-decoration: none;
            color: rgb(15, 17, 17);
            display: inline-flex;
            align-items: center;
        }

        /* .nav-links a i {
            margin-right: 5px;
        } */

        .nav-links a:hover {

            background-color: transparent;
            color: rgb(31, 34, 34);
        }

        .nav-links a:active {
            color: rgb(31, 34, 34);
            text-decoration: underline;
        }

        /* .theme-toggle {
            margin-left: 15px;
            background-color: transparent;
            border: 2px solid #ecf0f1;
            color: #ecf0f1;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background-color: #ecf0f1;
            color: #2c3e50;
        }
*/
        /* Light Theme */
        /* body.light-mode {
            background-color: #ffffff;
            color: #2c3e50;
        }

        body.light-mode header.navbar {
            background-color: #ffffff;
            color: #2c3e50;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        body.light-mode .nav-links a {
            color: #2c3e50;
        background-color: none;
        }

        body.light-mode .nav-links a:hover {
            text-decoration: underline;
            background-color:transparent;
            color: #3498db;
        }

        body.light-mode .theme-toggle {
            border-color: #2c3e50;
            color: #2c3e50;
        }

        body.light-mode .theme-toggle:hover {
            background-color: #2c3e50;
            color: #ffffff;
        } */

        .navbar-right {
            display: flex;
            gap: 20px;
        }

        .navbar-right .profile-icon {
            color: #ecf0f1;
            font-size: 1.8rem;
            text-decoration: none;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            background-color: #3498db;
            color: white;
            font-weight: bold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        


        @media (max-width: 768px) {
            .nav-links {
                display: flex;
                flex-direction: column;
                width: 100%;
                margin-top: 10px;
            }

            .nav-links a {
                margin: 5px 0;
            }

            .navbar-right {
                width: 100%;
                display: flex;
                justify-content: flex-end;
                margin-top: 10px;
            }
        }
    </style>
</head>

<body>

    <header class="navbar">
        <div class="navbar-left">
            <h2 class="logo">JobPortal</h2>
            <nav class="nav-links">
                <!-- <i class="fas fa-tachometer-alt"></i> -->
                <a href="/ojs/dashboard"></i> <span>Dashboard</span></a>
                <a href="/ojs/dashboard/profile"></i> <span>Profile</span></a>

                <?php if ($role === 'jobseeker') { ?>
                    <a href="/ojs/dashboard/jobs"></i> <span>Jobs</span></a>
                    <a href="/ojs/dashboard/applications"></i> <span>Applications</span></a>
                <?php } ?>

                <?php if ($role === 'company') { ?>
                    <a href="/ojs/dashboard/viewjobs"></i> <span>Jobs</span></a>
                    <a href="/ojs/dashboard/candidates"></i> <span>Candidates</span></a>
                <?php } ?>

                <a href="/ojs/dashboard/feedback"></i> <span>Feedback</span></a>
            </nav>
        </div>
        <div class="navbar-right">
            <!-- <button id="themeToggle" class="theme-toggle" title="Toggle theme">
                <i class="fas fa-moon"></i>
            </button> -->
            <?php
            $username = isset($user['email']) ? $user['email'] : 'U';
            $initial = strtoupper(substr($username, 0, 1));
            ?>
            <a href="/ojs/dashboard/profile" class="profile-avatar"><?= $initial ?></a>
        </div>

    </header>
    <script>
        // const themeToggle = document.getElementById("themeToggle");
        // const currentTheme = localStorage.getItem("theme");

        // if (currentTheme === "light") {
        //     document.body.classList.add("light-mode");
        //     themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        // }

        // themeToggle.addEventListener("click", () => {
        //     document.body.classList.toggle("light-mode");
        //     const isLight = document.body.classList.contains("light-mode");
        //     themeToggle.innerHTML = isLight ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        //     localStorage.setItem("theme", isLight ? "light" : "dark");
        // });
    </script>

</body>



</html>