<?php
//check the sesion if exist redirect to dashboard
session_start();
$activeSessionKey = null;

// Loop through all session keys to find the logged-in user
foreach ($_SESSION as $key => $value) {
    if (strpos($key, 'user_') === 0) {
        $activeSessionKey = $key;
        break;
    }
}

// Redirect to login page if  active session is found
if ($activeSessionKey || isset($_SESSION[$activeSessionKey])) {
    header('Location: dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobPortal - Find Your Dream Job</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            line-height: 1.6;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* Modern Header */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            padding: 1rem 0;
            transition: var(--transition);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        nav a {
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            position: relative;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        nav a:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .dropdown {
            position: relative;
        }

        .dropbtn {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .dropbtn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .dropdown-content {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            min-width: 200px;
            box-shadow: var(--shadow-xl);
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-top: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition);
        }

        .dropdown-content.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-content a {
            display: block;
            padding: 1rem;
            color: var(--text-primary);
            text-decoration: none;
            transition: var(--transition);
        }

        .dropdown-content a:hover {
            background: var(--bg-secondary);
            color: var(--primary-color);
        }

        /* Hero Section */
        #hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 120px 0 80px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        #hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,64L48,80C96,96,192,128,288,128C384,128,480,96,576,85.3C672,75,768,85,864,112C960,139,1056,181,1152,181.3C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center/cover no-repeat;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }

        #hero h2 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ffffff, rgba(255, 255, 255, 0.8));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        #hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        /* Modern Search Bar */
        #search-bar {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 1rem;
            box-shadow: var(--shadow-xl);
            display: flex;
            gap: 1rem;
            max-width: 600px;
            margin: 2rem auto;
            position: relative;
            z-index: 2;
        }

        #search-bar input {
            flex: 1;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--bg-secondary);
        }

        #search-bar input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        #search-bar button {
            background: linear-gradient(135deg, var(--warning-color), #ea580c);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #search-bar button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Job Listings Section */
        #jobs {
            padding: 80px 0;
            background: var(--bg-secondary);
        }

        .jobs-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        #jobList {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .job-card {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .job-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            transition: var(--transition);
        }

        .job-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .job-card:hover::before {
            width: 100%;
            opacity: 0.05;
        }

        .job-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .job-meta span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .job-status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        .job-details {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .job-details p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .job-details strong {
            color: var(--text-primary);
        }

        .job-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        /* Pagination */
        #pagination-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 3rem;
        }

        #pagination-controls button {
            padding: 0.75rem 1.5rem;
            background: white;
            color: var(--text-primary);
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        #pagination-controls button:hover:not(:disabled) {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        #pagination-controls button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        #pageNumber {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: var(--border-radius);
            font-weight: 600;
        }

        /* About & Contact Sections */
        #about, #contact {
            padding: 80px 0;
            max-width: 1200px;
            margin: 0 auto;
            padding-left: 2rem;
            padding-right: 2rem;
        }

        #about {
            background: white;
        }

        #contact {
            background: var(--bg-secondary);
        }

        .contact-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
            font-family: inherit;
        }

        /* Footer */
        footer {
            background: var(--text-primary);
            color: white;
            text-align: center;
            padding: 2rem 0;
        }

        /* Loading States */
        .loading {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }

            nav ul {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }

            #hero h2 {
                font-size: 2rem;
            }

            #search-bar {
                flex-direction: column;
                margin: 2rem 1rem;
            }

            #jobList {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .job-actions {
                flex-direction: column;
            }

            #pagination-controls {
                flex-wrap: wrap;
            }
        }

        /* Animations */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Enhanced job card states */
        .job-card.expanded {
            transform: scale(1.02);
            z-index: 10;
        }

        .no-jobs {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .no-jobs i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <i class="fas fa-briefcase"></i>
                JobPortal
            </div>
            <nav>
                <ul>
                    <li><a href="#jobs"><i class="fas fa-search"></i> Browse Jobs</a></li>
                    <li><a href="#about"><i class="fas fa-info-circle"></i> About Us</a></li>
                    <li><a href="#contact"><i class="fas fa-envelope"></i> Contact</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <a href="login" class="btn btn-outline">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
                <div class="dropdown">
                    <button class="btn dropbtn" onclick="toggleDropdown()">
                        <i class="fas fa-user-plus"></i>
                        Sign Up
                    </button>
                    <div class="dropdown-content" id="myDropdown">
                        <a href="register/jobseeker">
                            <i class="fas fa-user"></i>
                            Register as Job Seeker
                        </a>
                        <a href="register/company">
                            <i class="fas fa-building"></i>
                            Register as Company
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="hero">
        <div class="hero-content">
            <h2>Your Dream Job Awaits!</h2>
            <p>Connect with top companies and discover amazing career opportunities that match your skills and aspirations.</p>
            <div id="search-bar">
                <input type="text" id="jobTitle" placeholder="Job Title or Keywords">
                <input type="text" id="location" placeholder="Location">
                <button onclick="searchJobs()">
                    <i class="fas fa-search"></i>
                    Search
                </button>
            </div>
        </div>
    </section>

    <section id="jobs">
        <div class="jobs-container">
            <h2 class="section-title">Latest Job Listings</h2>
            <div id="jobList">
                <div class="loading-jobs" style="text-align: center; padding: 3rem;">
                    <div class="loading">
                        <div class="spinner"></div>
                        Loading amazing opportunities...
                    </div>
                </div>
            </div>
            <div id="pagination-controls">
                <button id="prevPage" onclick="changePage(-1)">
                    <i class="fas fa-chevron-left"></i>
                    Previous
                </button>
                <span id="pageNumber">1</span>
                <button id="nextPage" onclick="changePage(1)">
                    Next
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <section id="about">
        <h2 class="section-title">About Us</h2>
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-secondary);">
                We bridge the gap between talented job seekers and forward-thinking companies. 
                Our platform offers a seamless experience for exploring diverse career opportunities, 
                building professional connections, and advancing your career journey.
            </p>
        </div>
    </section>

    <section id="contact">
        <h2 class="section-title">Contact Us</h2>
        <div class="contact-form">
            <form id="contactForm">
                <div class="form-group">
                    <input type="text" id="name" name="name" class="form-input" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" class="form-input" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <textarea id="message" name="message" class="form-input form-textarea" placeholder="Your Message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-paper-plane"></i>
                    Send Message
                </button>
            </form>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 JobPortal. All Rights Reserved. | Built with ❤️ for job seekers worldwide</p>
    </footer>

    <script>
        // Enhanced JavaScript with better UX
        let jobsData = [];
        let filteredJobs = [];
        let currentPage = 1;
        const jobsPerPage = 6;

        // Toggle Dropdown with enhanced animation
        function toggleDropdown() {
            const dropdown = document.getElementById("myDropdown");
            dropdown.classList.toggle("show");
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            if (!e.target.matches('.dropbtn')) {
                const dropdown = document.getElementById("myDropdown");
                dropdown.classList.remove("show");
            }
        });

        // Enhanced job fetching with better error handling
        function fetchJobs() {
            $.ajax({
                url: "Backend/controllers/JobController.php",
                data: { "action": "getAll" },
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success && response.jobs) {
                        jobsData = response.jobs;
                        filteredJobs = [...jobsData];
                        displayJobs();
                        updatePaginationControls();
                    } else {
                        showNoJobs("No job listings available at the moment.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching jobs:", error);
                    showNoJobs("Unable to load job listings. Please try again later.");
                }
            });
        }

        // Enhanced job display with animations
        function displayJobs() {
            const startIndex = (currentPage - 1) * jobsPerPage;
            const endIndex = startIndex + jobsPerPage;
            const jobsToShow = filteredJobs.slice(startIndex, endIndex);
            
            if (jobsToShow.length === 0) {
                showNoJobs("No jobs match your search criteria.");
                return;
            }

            let jobHtml = "";
            jobsToShow.forEach((job, index) => {
                const statusColor = job.status === 'active' ? 'var(--success-color)' : 'var(--warning-color)';
                jobHtml += `
                    <div class="job-card fade-in-up" style="animation-delay: ${index * 0.1}s">
                        <h3>${escapeHtml(job.title)}</h3>
                        <div class="job-meta">
                            <span><i class="fas fa-building"></i> ${escapeHtml(job.company_name)}</span>
                            <span><i class="fas fa-map-marker-alt"></i> ${escapeHtml(job.location)}</span>
                            <span><i class="fas fa-calendar"></i> ${formatDate(job.created_at)}</span>
                        </div>
                        <div class="job-status" style="background: ${statusColor};">
                            ${escapeHtml(job.status)}
                        </div>
                        
                        <div class="job-details" style="display: none;">
                            <p><strong>Description:</strong> ${escapeHtml(job.description) || "Not specified"}</p>
                            <p><strong>Requirements:</strong> ${escapeHtml(job.requirements) || "Not specified"}</p>
                            <p><strong>Salary:</strong> ${escapeHtml(job.salary) || "Not disclosed"}</p>
                            <p><strong>Job Type:</strong> ${escapeHtml(job.job_type) || "Not specified"}</p>
                            <p><strong>Application Deadline:</strong> ${formatDate(job.application_deadline)}</p>
                        </div>
                        
                        <div class="job-actions">
                            <button class="btn btn-secondary view-more-btn" onclick="toggleDetails(this)">
                                <i class="fas fa-eye"></i>
                                View Details
                            </button>
                            <button class="btn btn-primary" onclick="applyNow()">
                                <i class="fas fa-paper-plane"></i>
                                Apply Now
                            </button>
                        </div>
                    </div>
                `;
            });

            $("#jobList").html(jobHtml);
            updatePaginationControls();
        }

        // Show no jobs state
        function showNoJobs(message) {
            $("#jobList").html(`
                <div class="no-jobs">
                    <i class="fas fa-search"></i>
                    <h3>${message}</h3>
                    <p>Try adjusting your search criteria or check back later for new opportunities.</p>
                </div>
            `);
        }

        // Enhanced toggle details function
        function toggleDetails(button) {
            const jobCard = button.closest(".job-card");
            const detailsDiv = jobCard.querySelector(".job-details");
            const isVisible = detailsDiv.style.display !== "none";
            
            if (!isVisible) {
                detailsDiv.style.display = "block";
                button.innerHTML = '<i class="fas fa-eye-slash"></i> Hide Details';
                jobCard.classList.add("expanded");
            } else {
                detailsDiv.style.display = "none";
                button.innerHTML = '<i class="fas fa-eye"></i> View Details';
                jobCard.classList.remove("expanded");
            }
        }

        // Enhanced apply now function
        function applyNow() {
            Swal.fire({
                title: 'Login Required',
                text: 'Please login to apply for this position.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Login Now',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login';
                }
            });
        }

        // Enhanced search function with debouncing
        let searchTimeout;
        function searchJobs() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const jobTitle = $("#jobTitle").val().toLowerCase().trim();
                const location = $("#location").val().toLowerCase().trim();

                filteredJobs = jobsData.filter(job => {
                    const titleMatch = !jobTitle || job.title.toLowerCase().includes(jobTitle);
                    const locationMatch = !location || job.location.toLowerCase().includes(location);
                    return titleMatch && locationMatch;
                });

                currentPage = 1;
                displayJobs();
                
                // Show search results feedback
                if (jobTitle || location) {
                    const resultsCount = filteredJobs.length;
                    const searchTerms = [jobTitle, location].filter(term => term).join(' in ');
                    
                    if (resultsCount === 0) {
                        Swal.fire({
                            title: 'No Results Found',
                            text: `No jobs found for "${searchTerms}". Try different keywords.`,
                            icon: 'info',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        toast.fire({
                            icon: 'success',
                            title: `Found ${resultsCount} job${resultsCount !== 1 ? 's' : ''} for "${searchTerms}"`
                        });
                    }
                }
            }, 300);
        }

        // Pagination functions
        function updatePaginationControls() {
            const totalPages = Math.ceil(filteredJobs.length / jobsPerPage);
            
            $("#prevPage").prop("disabled", currentPage === 1);
            $("#nextPage").prop("disabled", currentPage === totalPages || filteredJobs.length === 0);
            $("#pageNumber").text(`${currentPage} of ${totalPages || 1}`);
            
            // Hide pagination if only one page
            if (totalPages <= 1) {
                $("#pagination-controls").hide();
            } else {
                $("#pagination-controls").show();
            }
        }

        function changePage(step) {
            const totalPages = Math.ceil(filteredJobs.length / jobsPerPage);
            const newPage = currentPage + step;
            
            if (newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                displayJobs();
                
                // Smooth scroll to top of job list
                document.getElementById('jobs').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
        }

        // Enhanced contact form submission
        $(document).ready(function() {
            $("#contactForm").submit(function(e) {
                e.preventDefault();

                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                
                // Show loading state
                submitBtn.html('<div class="loading"><div class="spinner"></div> Sending...</div>');
                submitBtn.prop('disabled', true);

                const formData = {
                    name: $("#name").val().trim(),
                    email: $("#email").val().trim(),
                    message: $("#message").val().trim(),
                };

                // Basic client-side validation
                if (!formData.name || !formData.email || !formData.message) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Information',
                        text: 'Please fill in all required fields.',
                    });
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                    return;
                }

                $.ajax({
                    url: "backend/config/send_mail.php",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    timeout: 10000, // 10 second timeout
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Message Sent!',
                                text: response.message || 'Thank you for contacting us. We\'ll get back to you soon!',
                                timer: 5000
                            });
                            $("#contactForm")[0].reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Sending Failed',
                                text: response.message || 'Unable to send message. Please try again.',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Something went wrong. Please try again later.';
                        
                        if (status === 'timeout') {
                            errorMessage = 'Request timed out. Please check your connection and try again.';
                        } else if (xhr.status === 404) {
                            errorMessage = 'Service not found. Please contact support.';
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Error',
                            text: errorMessage,
                        });
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.html(originalText);
                        submitBtn.prop('disabled', false);
                    }
                });
            });

            // Initialize page
            fetchJobs();
            
            // Add real-time search
            let searchDebounce;
            $("#jobTitle, #location").on('input', function() {
                clearTimeout(searchDebounce);
                searchDebounce = setTimeout(searchJobs, 500);
            });
            
            // Add keyboard shortcuts
            $(document).keydown(function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch(e.key) {
                        case '/':
                            e.preventDefault();
                            $("#jobTitle").focus();
                            break;
                    }
                }
            });
        });

        // Utility functions
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatDate(dateString) {
            if (!dateString) return 'Not specified';
            
            try {
                const date = new Date(dateString);
                const now = new Date();
                const diffTime = Math.abs(now - date);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays === 1) return '1 day ago';
                if (diffDays < 7) return `${diffDays} days ago`;
                if (diffDays < 30) return `${Math.floor(diffDays / 7)} week${Math.floor(diffDays / 7) !== 1 ? 's' : ''} ago`;
                
                return date.toLocaleDateString();
            } catch (error) {
                return dateString;
            }
        }

        // Add smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading animation on page transitions
        window.addEventListener('beforeunload', function() {
            document.body.style.opacity = '0.8';
        });

        // Add intersection observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                }
            });
        }, observerOptions);

        // Observe elements when they're added to DOM
        function observeNewElements() {
            document.querySelectorAll('.job-card:not(.fade-in-up)').forEach(card => {
                observer.observe(card);
            });
        }

        // Call observeNewElements after displaying jobs
        const originalDisplayJobs = displayJobs;
        displayJobs = function() {
            originalDisplayJobs();
            setTimeout(observeNewElements, 100);
        };
    </script>
</body>
</html>