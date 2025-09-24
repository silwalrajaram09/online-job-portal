<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Registration - JobPortal</title>
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
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            padding: 2rem 0;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,64L48,80C96,96,192,128,288,128C384,128,480,96,576,85.3C672,75,768,85,864,112C960,139,1056,181,1152,181.3C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') center/cover no-repeat;
            animation: float 8s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(2deg); }
        }

        /* Registration container */
        .registration-container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .registration-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .registration-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 20px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M600,112.77C268.63,112.77,0,65.52,0,7.23V120H1200V7.23C1200,65.52,931.37,112.77,600,112.77Z" fill="rgba(255,255,255,0.95)"></path></svg>') center/cover no-repeat;
        }

        .registration-header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .registration-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .progress-bar {
            background: rgba(255, 255, 255, 0.2);
            height: 6px;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 1rem;
        }

        .progress-fill {
            background: var(--accent-color);
            height: 100%;
            width: 0%;
            transition: var(--transition);
            border-radius: 3px;
        }

        /* Form content */
        .registration-content {
            padding: 2rem;
        }

        .section {
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            position: relative;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .form-label.required::after {
            content: ' *';
            color: var(--error-color);
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1rem;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input.error {
            border-color: var(--error-color);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-input.success {
            border-color: var(--success-color);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--bg-primary);
            color: var(--text-primary);
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Skills selection */
        .skills-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .skill-item {
            padding: 1rem;
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .skill-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: var(--transition);
        }

        .skill-item:hover::before {
            left: 100%;
        }

        .skill-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .skill-item.selected {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .skill-item.selected::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            font-size: 0.8rem;
        }

        /* Alert messages */
        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            display: none;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--error-color);
            color: #991b1b;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--success-color);
            color: #065f46;
        }

        .alert-info {
            background: rgba(102, 126, 234, 0.1);
            border: 1px solid var(--primary-color);
            color: #1e40af;
        }

        /* Submit button */
        .submit-section {
            background: var(--bg-secondary);
            padding: 2rem;
            margin: 2rem -2rem -2rem;
            border-top: 1px solid var(--border-color);
        }

        .btn {
            width: 100%;
            padding: 1.2rem;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-loading {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Footer links */
        .registration-footer {
            text-align: center;
            padding: 1.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .registration-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .registration-footer a:hover {
            text-decoration: underline;
        }

        /* Password strength indicator */
        .password-strength {
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: var(--transition);
            border-radius: 2px;
        }

        .password-strength-weak { background: var(--error-color); width: 33%; }
        .password-strength-medium { background: var(--warning-color); width: 66%; }
        .password-strength-strong { background: var(--success-color); width: 100%; }

        /* Responsive design */
        @media (max-width: 768px) {
            .registration-container {
                margin: 1rem;
                border-radius: var(--border-radius);
            }

            .registration-content {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .skills-container {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                gap: 0.75rem;
            }

            .skill-item {
                padding: 0.75rem;
                font-size: 0.9rem;
            }

            .registration-header h1 {
                font-size: 1.8rem;
            }

            .submit-section {
                margin: 1.5rem -1.5rem -1.5rem;
            }
        }

        /* Animation classes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .section.animate {
            animation: fadeIn 0.6s ease-out;
        }

        /* Form validation states */
        .field-valid::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--success-color);
        }

        .field-error::after {
            content: '\f00d';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--error-color);
        }

        /* Tooltip for skills */
        .skill-item:hover::before {
            content: 'Click to select';
            position: absolute;
            bottom: -2rem;
            left: 50%;
            transform: translateX(-50%);
            background: var(--text-primary);
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            font-size: 0.8rem;
            white-space: nowrap;
            z-index: 10;
        }

        .skill-item.selected:hover::before {
            content: 'Selected';
        }
    </style>
</head>

<body>
    <div class="registration-container fade-in">
        <div class="registration-header">
            <h1><i class="fas fa-user-plus"></i> Job Seeker Registration</h1>
            <p>Create your profile and start your career journey with us</p>
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </div>

        <div class="registration-content">
            <div id="Message" class="alert"></div>

            <form id="registrationForm" method="post">
                <input type="hidden" name="action" value="registerJobseeker">

                <!-- Login Information Section -->
                <div class="section animate" data-section="1">
                    <h2 class="section-title">
                        <i class="fas fa-sign-in-alt"></i>
                        Login Information
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email" class="form-label required">Email Address</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" id="email" name="email" class="form-input" 
                                       placeholder="Enter your email address" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label required">Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" id="password" name="password" class="form-input" 
                                       placeholder="Create a strong password" required minlength="8">
                            </div>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="section animate" data-section="2">
                    <h2 class="section-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fname" class="form-label required">First Name</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" id="fname" name="fname" class="form-input" 
                                       placeholder="Enter your first name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lname" class="form-label required">Last Name</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" id="lname" name="lname" class="form-input" 
                                       placeholder="Enter your last name" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone" class="form-label required">Contact Number</label>
                            <div class="input-wrapper">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" id="phone" name="phone" class="form-input" 
                                       placeholder="Enter your phone number" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Preferences Section -->
                <div class="section animate" data-section="3">
                    <h2 class="section-title">
                        <i class="fas fa-briefcase"></i>
                        Job Preferences
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="job_category" class="form-label required">Preferred Job Category</label>
                            <div class="input-wrapper">
                                <i class="fas fa-tags input-icon"></i>
                                <select id="job_category" name="job_category" class="form-select" required>
                                    <option value="">Loading categories...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Select Your Skills</label>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">
                            Choose the skills that best represent your expertise. You can select multiple skills.
                        </p>
                        <div class="skills-container" id="skillsContainer">
                            <!-- Skills will be loaded here -->
                        </div>
                        <input type="hidden" id="selected-skills" name="skills" value="">
                    </div>
                </div>

                <div class="submit-section">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-user-plus"></i>
                        Create Account
                    </button>
                </div>
            </form>
        </div>

        <div class="registration-footer">
            <p>
                By creating an account, you confirm that you've read our 
                <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
            </p>
            <p>
                Already have an account? <a href="../login">Sign in here</a>
            </p>
        </div>
    </div>

    <script src="/ojs/frontend/assets/js/script.js"></script>
    <script>
        $(document).ready(function() {
            let selectedSkills = [];
            let formProgress = 0;

            // Predefined skills array
            const availableSkills = [
                'JavaScript', 'PHP', 'Python', 'Java', 'HTML', 'CSS', 'SQL',
                'Node.js', 'React', 'Angular', 'Vue.js', 'Laravel', 'Django',
                'Spring Boot', 'MongoDB', 'MySQL', 'PostgreSQL', 'Redis',
                'AWS', 'Docker', 'Kubernetes', 'Git', 'Linux', 'TypeScript'
            ];

            // Initialize skills container
            function initializeSkills() {
                const skillsContainer = $('#skillsContainer');
                skillsContainer.empty();

                availableSkills.forEach(skill => {
                    const skillElement = $(`
                        <div class="skill-item" data-skill="${skill}">
                            ${skill}
                        </div>
                    `);
                    skillsContainer.append(skillElement);
                });

                // Add skill selection handlers
                $('.skill-item').click(function() {
                    const skillName = $(this).data('skill');
                    toggleSkill(skillName, $(this));
                });
            }

            // Toggle skill selection
            function toggleSkill(skillName, element) {
                if (selectedSkills.includes(skillName)) {
                    // Remove skill
                    selectedSkills = selectedSkills.filter(s => s !== skillName);
                    element.removeClass('selected');
                } else {
                    // Add skill
                    selectedSkills.push(skillName);
                    element.addClass('selected');
                }

                // Update hidden input
                $('#selected-skills').val(selectedSkills.join(','));
                updateProgress();
            }

            // Load job categories
            function loadJobCategories() {
                $.ajax({
                    url: '../backend/controllers/user.php',
                    method: 'GET',
                    data: { action: 'fetch_all_categories' },
                    dataType: 'json',
                    success: function(response) {
                        const dropdown = $('#job_category');
                        dropdown.empty();
                        dropdown.append('<option value="">Select Job Category</option>');

                        if (response.error) {
                            showMessage(response.error, 'error');
                        } else if (Array.isArray(response)) {
                            response.forEach(function(category) {
                                dropdown.append(
                                    `<option value="${category.id}">${category.category_name}</option>`
                                );
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading categories:', error);
                        $('#job_category').html('<option value="">Failed to load categories</option>');
                        showMessage('Failed to load job categories. Please refresh the page.', 'error');
                    }
                });
            }

            // Form validation functions
            function validateEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function validatePassword(password) {
                return password.length >= 8;
            }

            function validatePhone(phone) {
                const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
                return phoneRegex.test(phone.replace(/\s/g, ''));
            }

            function updatePasswordStrength(password) {
                const strengthBar = $('#passwordStrengthBar');
                let strength = 0;

                const checks = [
                    password.length >= 8,
                    /[a-z]/.test(password),
                    /[A-Z]/.test(password),
                    /\d/.test(password),
                    /[^a-zA-Z\d]/.test(password)
                ];

                strength = checks.filter(check => check).length;

                strengthBar.removeClass('password-strength-weak password-strength-medium password-strength-strong');

                if (strength < 3) {
                    strengthBar.addClass('password-strength-weak');
                } else if (strength < 5) {
                    strengthBar.addClass('password-strength-medium');
                } else {
                    strengthBar.addClass('password-strength-strong');
                }
            }

            // Real-time validation
            $('#email').on('blur', function() {
                const email = $(this).val().trim();
                const wrapper = $(this).closest('.input-wrapper');
                
                if (email && validateEmail(email)) {
                    $(this).removeClass('error').addClass('success');
                    wrapper.removeClass('field-error').addClass('field-valid');
                } else if (email) {
                    $(this).removeClass('success').addClass('error');
                    wrapper.removeClass('field-valid').addClass('field-error');
                } else {
                    $(this).removeClass('error success');
                    wrapper.removeClass('field-error field-valid');
                }
                updateProgress();
            });

            $('#password').on('input', function() {
                const password = $(this).val();
                const wrapper = $(this).closest('.input-wrapper');
                
                updatePasswordStrength(password);
                
                if (password && validatePassword(password)) {
                    $(this).removeClass('error').addClass('success');
                    wrapper.removeClass('field-error').addClass('field-valid');
                } else if (password) {
                    $(this).removeClass('success').addClass('error');
                    wrapper.removeClass('field-valid').addClass('field-error');
                }
                updateProgress();
            });

            $('#fname, #lname').on('blur', function() {
                const value = $(this).val().trim();
                const wrapper = $(this).closest('.input-wrapper');
                
                if (value && value.length >= 2) {
                    $(this).removeClass('error').addClass('success');
                    wrapper.removeClass('field-error').addClass('field-valid');
                } else if (value) {
                    $(this).removeClass('success').addClass('error');
                    wrapper.removeClass('field-valid').addClass('field-error');
                }
                updateProgress();
            });

            $('#phone').on('blur', function() {
                const phone = $(this).val().trim();
                const wrapper = $(this).closest('.input-wrapper');
                
                if (phone && validatePhone(phone)) {
                    $(this).removeClass('error').addClass('success');
                    wrapper.removeClass('field-error').addClass('field-valid');
                } else if (phone) {
                    $(this).removeClass('success').addClass('error');
                    wrapper.removeClass('field-valid').addClass('field-error');
                }
                updateProgress();
            });

            $('#job_category').on('change', function() {
                updateProgress();
            });

            // Update progress bar
            function updateProgress() {
                let completedFields = 0;
                const totalFields = 6; // email, password, fname, lname, phone, category, skills

                if ($('#email').hasClass('success')) completedFields++;
                if ($('#password').hasClass('success')) completedFields++;
                if ($('#fname').hasClass('success')) completedFields++;
                if ($('#lname').hasClass('success')) completedFields++;
                if ($('#phone').hasClass('success')) completedFields++;
                if ($('#job_category').val()) completedFields++;
                if (selectedSkills.length > 0) completedFields++;

                const progress = (completedFields / totalFields) * 100;
                $('#progressFill').css('width', progress + '%');
            }

            // Form submission
            $('#registrationForm').submit(function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                const submitBtn = $('#submitBtn');
                const originalText = submitBtn.html();

                submitBtn.html('<div class="btn-loading"><div class="spinner"></div> Creating Account...</div>');
                submitBtn.prop('disabled', true);

                const formData = $(this).serialize();

                $.ajax({
                    url: '../backend/controllers/user.php', // Adjust path as needed
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    timeout: 15000,
                    success: function(response) {
                        if (response.success) {
                            showMessage('Account created successfully! Redirecting to login...', 'success');
                            
                            // Success animation
                            $('.registration-container').addClass('fade-out');
                            
                            setTimeout(() => {
                                window.location.href = '../login';
                            }, 2000);
                        } else {
                            showMessage(response.message || 'Registration failed. Please try again.', 'error');
                            
                            // Shake animation for error
                            $('.registration-container').css('animation', 'shake 0.5s ease-in-out');
                            setTimeout(() => {
                                $('.registration-container').css('animation', '');
                            }, 500);
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Registration failed. Please try again.';
                        
                        if (status === 'timeout') {
                            errorMessage = 'Request timed out. Please check your connection.';
                        } else if (xhr.status === 422) {
                            errorMessage = 'Please check your information and try again.';
                        } else if (xhr.status === 500) {
                            errorMessage = 'Server error. Please try again later.';
                        }
                        
                        showMessage(errorMessage, 'error');
                        console.error('Registration error:', error);
                    },
                    complete: function() {
                        submitBtn.html(originalText);
                        submitBtn.prop('disabled', false);
                    }
                });
            });

            // Form validation
            function validateForm() {
                let isValid = true;
                const fields = {
                    email: { value: $('#email').val().trim(), validator: validateEmail },
                    password: { value: $('#password').val(), validator: validatePassword },
                    fname: { value: $('#fname').val().trim(), validator: (v) => v.length >= 2 },
                    lname: { value: $('#lname').val().trim(), validator: (v) => v.length >= 2 },
                    phone: { value: $('#phone').val().trim(), validator: validatePhone },
                    job_category: { value: $('#job_category').val(), validator: (v) => v !== '' }
                };

                // Validate all fields
                Object.keys(fields).forEach(fieldName => {
                    const field = fields[fieldName];
                    const element = $(`#${fieldName}`);
                    
                    if (!field.value) {
                        element.addClass('error').removeClass('success');
                        isValid = false;
                    } else if (!field.validator(field.value)) {
                        element.addClass('error').removeClass('success');
                        isValid = false;
                    } else {
                        element.removeClass('error').addClass('success');
                    }
                });

                // Check if at least one skill is selected
                if (selectedSkills.length === 0) {
                    showMessage('Please select at least one skill.', 'error');
                    isValid = false;
                }

                if (!isValid) {
                    showMessage('Please fill in all required fields correctly.', 'error');
                }

                return isValid;
            }

            // Utility functions
            function showMessage(message, type) {
                const messageDiv = $('#Message');
                const icon = type === 'success' ? 'check-circle' : 
                           type === 'error' ? 'exclamation-triangle' : 'info-circle';
                
                messageDiv.removeClass('alert-error alert-success alert-info')
                         .addClass(`alert-${type}`)
                         .html(`<i class="fas fa-${icon}"></i> ${message}`)
                         .fadeIn();

                // Scroll to message
                $('html, body').animate({
                    scrollTop: messageDiv.offset().top - 100
                }, 300);

                // Auto-hide success messages
                if (type === 'success') {
                    setTimeout(() => {
                        messageDiv.fadeOut();
                    }, 5000);
                }
            }

            // Initialize everything
            initializeSkills();
            loadJobCategories();

            // Add shake animation CSS
            const shakeCSS = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
                    20%, 40%, 60%, 80% { transform: translateX(10px); }
                }
                .fade-out {
                    animation: fadeOut 1s ease-out;
                }
                @keyframes fadeOut {
                    to { opacity: 0; transform: scale(0.95); }
                }
            `;
            
            const style = document.createElement('style');
            style.textContent = shakeCSS;
            document.head.appendChild(style);

            // Auto-focus first input
            $('#email').focus();
        });
    </script>
</body>
</html>