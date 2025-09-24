<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Registration - JobPortal</title>
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,256L48,240C96,224,192,192,288,197.3C384,203,480,245,576,250.7C672,256,768,224,864,213.3C960,203,1056,213,1152,234.7C1248,256,1344,288,1392,304L1440,320L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path></svg>') center/cover no-repeat;
            animation: float 10s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-40px) scale(1.02); }
        }

        /* Registration container */
        .registration-container {
            max-width: 900px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .registration-header {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            padding: 2.5rem;
            text-align: center;
            position: relative;
        }

        .registration-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 25px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M600,112.77C268.63,112.77,0,65.52,0,7.23V120H1200V7.23C1200,65.52,931.37,112.77,600,112.77Z" fill="rgba(255,255,255,0.95)"></path></svg>') center/cover no-repeat;
        }

        .registration-header h1 {
            font-size: 2.4rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .registration-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .progress-steps {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: var(--transition);
            position: relative;
        }

        .step.active {
            background: var(--accent-color);
            transform: scale(1.1);
        }

        .step.completed {
            background: var(--success-color);
        }

        .step.completed::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 0.8rem;
        }

        /* Form content */
        .registration-content {
            padding: 2.5rem;
        }

        .section {
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
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
            font-size: 0.95rem;
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
            font-size: 1.1rem;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: 1.2rem 1.2rem 1.2rem 3.2rem;
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
            transform: translateY(-1px);
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
            padding: 1.2rem 1.2rem 1.2rem 3.2rem;
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
            background-size: 1.2rem;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Alert messages */
        .alert {
            padding: 1.2rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            display: none;
            font-weight: 500;
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

        /* Submit section */
        .submit-section {
            background: linear-gradient(135deg, var(--bg-secondary), rgba(249, 250, 251, 0.8));
            padding: 2.5rem;
            margin: 2rem -2.5rem -2.5rem;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .btn {
            padding: 1.3rem 3rem;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            min-width: 200px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: var(--shadow-lg);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
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
            gap: 0.75rem;
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
            padding: 2rem;
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
            background: var(--bg-secondary);
        }

        .registration-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .registration-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .registration-container {
                margin: 1rem;
                border-radius: var(--border-radius);
            }

            .registration-content {
                padding: 2rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .registration-header h1 {
                font-size: 2rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .submit-section {
                margin: 2rem -2rem -2rem;
                padding: 2rem;
            }

            .btn {
                width: 100%;
                padding: 1.2rem;
            }

            .progress-steps {
                gap: 0.75rem;
            }

            .step {
                width: 35px;
                height: 35px;
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

        /* Enhanced form styling */
        .form-input:hover {
            border-color: var(--primary-color);
        }

        .form-select:hover {
            border-color: var(--primary-color);
        }

        /* Company type badge */
        .company-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-left: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="registration-container fade-in">
        <div class="registration-header">
            <h1>
                <i class="fas fa-building"></i>
                Company Registration
            </h1>
            <p>Join our platform and connect with talented professionals</p>
            <div class="progress-steps">
                <div class="step active" data-step="1">1</div>
                <div class="step" data-step="2">2</div>
                <div class="step" data-step="3">3</div>
            </div>
        </div>

        <div class="registration-content">
            <div id="Message" class="alert"></div>

            <form id="registrationForm" method="post">
                <input type="hidden" name="action" value="registerCompany">

                <!-- Login Information Section -->
                <div class="section animate" data-section="1">
                    <h2 class="section-title">
                        <i class="fas fa-sign-in-alt"></i>
                        Login Information
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email" class="form-label required">Company Email</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" id="email" name="email" class="form-input" 
                                       placeholder="company@example.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label required">Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" id="password" name="password" class="form-input" 
                                       placeholder="Create a secure password" required minlength="8">
                            </div>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Information Section -->
                <div class="section animate" data-section="2">
                    <h2 class="section-title">
                        <i class="fas fa-building"></i>
                        Company Information
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="company_name" class="form-label required">Company Name</label>
                            <div class="input-wrapper">
                                <i class="fas fa-building input-icon"></i>
                                <input type="text" id="company_name" name="company_name" class="form-input" 
                                       placeholder="Enter company name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label required">Primary Phone</label>
                            <div class="input-wrapper">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" id="phone" name="phone" class="form-input" 
                                       placeholder="Company phone number" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="company_type" class="form-label required">Company Type</label>
                            <div class="input-wrapper">
                                <i class="fas fa-industry input-icon"></i>
                                <select id="company_type" name="company_type" class="form-select" required>
                                    <option value="">Loading company types...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="city" class="form-label required">City</label>
                            <div class="input-wrapper">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <select id="city" name="city" class="form-select" required>
                                    <option value="">Select city</option>
                                    <option value="ktm">Kathmandu</option>
                                    <option value="pokhara">Pokhara</option>
                                    <option value="lalitpur">Lalitpur</option>
                                    <option value="bhaktapur">Bhaktapur</option>
                                    <option value="biratnagar">Biratnagar</option>
                                    <option value="birgunj">Birgunj</option>
                                    <option value="dharan">Dharan</option>
                                    <option value="butwal">Butwal</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="location" class="form-label required">Detailed Address</label>
                            <div class="input-wrapper">
                                <i class="fas fa-location-arrow input-icon"></i>
                                <input type="text" id="location" name="location" class="form-input" 
                                       placeholder="Street address, building name, etc." required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Person Information Section -->
                <div class="section animate" data-section="3">
                    <h2 class="section-title">
                        <i class="fas fa-user-tie"></i>
                        Contact Person Details
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_name" class="form-label required">Contact Person Name</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" id="contact_name" name="contact_name" class="form-input" 
                                       placeholder="Full name of contact person" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mobile" class="form-label required">Mobile Number</label>
                            <div class="input-wrapper">
                                <i class="fas fa-mobile-alt input-icon"></i>
                                <input type="tel" id="mobile" name="mobile" class="form-input" 
                                       placeholder="Contact person mobile" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="contact_email" class="form-label required">Contact Person Email</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" id="contact_email" name="contact_email" class="form-input" 
                                       placeholder="Contact person email address" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="submit-section">
                    <p style="margin-bottom: 1.5rem; color: var(--text-secondary);">
                        Ready to connect with top talent? Create your company account now!
                    </p>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-building"></i>
                        Create Company Account
                    </button>
                </div>
            </form>
        </div>

        <div class="registration-footer">
            <p>
                By creating an account, you agree to our 
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
            let currentStep = 1;

            // Load company types
            function loadCompanyTypes() {
                $.ajax({
                    url: '../backend/controllers/user.php',
                    method: 'GET',
                    data: { action: 'fetch_all_company_type' },
                    dataType: 'json',
                    success: function(response) {
                        const dropdown = $('#company_type');
                        dropdown.empty();
                        dropdown.append('<option value="">Select Company Type</option>');

                        if (response.error) {
                            showMessage(response.error, 'error');
                        } else if (Array.isArray(response)) {
                            response.forEach(function(type) {
                                dropdown.append(
                                    `<option value="${type.id}">${type.type_name}</option>`
                                );
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading company types:', error);
                        $('#company_type').html('<option value="">Failed to load types</option>');
                        showMessage('Failed to load company types. Please refresh the page.', 'error');
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
                return phoneRegex.test(phone.replace(/[\s\-]/g, ''));
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
                updateSteps();
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
                updateSteps();
            });

            $('#company_name, #contact_name').on('blur', function() {
                const value = $(this).val().trim();
                const wrapper = $(this).closest('.input-wrapper');
                
                if (value && value.length >= 2) {
                    $(this).removeClass('error').addClass('success');
                    wrapper.removeClass('field-error').addClass('field-valid');
                } else if (value) {
                    $(this).removeClass('success').addClass('error');
                    wrapper.removeClass('field-valid').addClass('field-error');
                }
                updateSteps();
            });

            $('#phone, #mobile').on('blur', function() {
                const phone = $(this).val().trim();
                const wrapper = $(this).closest('.input-wrapper');
                
                if (phone && validatePhone(phone)) {
                    $(this).removeClass('error').addClass('success');
                    wrapper.removeClass('field-error').addClass('field-valid');
                } else if (phone) {
                    $(this).removeClass('success').addClass('error');
                    wrapper.removeClass('field-valid').addClass('field-error');
                }
                updateSteps();
            });

            $('#contact_email').on('blur', function() {
                const email = $(this).val().trim();
                const wrapper = $(this).closest('.input-wrapper');
                
                if (email && validateEmail(email)) {
                    $(this).removeClass('error').addClass('success');
                    wrapper.removeClass('field-error').addClass('field-valid');
                } else if (email) {
                    $(this).removeClass('success').addClass('error');
                    wrapper.removeClass('field-valid').addClass('field-error');
                }
                updateSteps();
            });

            $('#location').on('blur', function() {
                const location = $(this).val().trim();
                const wrapper = $(this).closest('.input-wrapper');
                
                if (location && location.length >= 5) {
                    $(this).removeClass('error').addClass('success');
                    wrapper.removeClass('field-error').addClass('field-valid');
                } else if (location) {
                    $(this).removeClass('success').addClass('error');
                    wrapper.removeClass('field-valid').addClass('field-error');
                }
                updateSteps();
            });

            $('#company_type, #city').on('change', function() {
                updateSteps();
            });

            // Update progress steps
            function updateSteps() {
                // Step 1: Login info
                const step1Complete = $('#email').hasClass('success') && $('#password').hasClass('success');
                
                // Step 2: Company info
                const step2Complete = $('#company_name').hasClass('success') && 
                                     $('#phone').hasClass('success') && 
                                     $('#company_type').val() && 
                                     $('#city').val() && 
                                     $('#location').hasClass('success');
                
                // Step 3: Contact info
                const step3Complete = $('#contact_name').hasClass('success') && 
                                     $('#mobile').hasClass('success') && 
                                     $('#contact_email').hasClass('success');

                // Update step indicators
                $('.step[data-step="1"]').toggleClass('completed', step1Complete);
                $('.step[data-step="2"]').toggleClass('completed', step2Complete);
                $('.step[data-step="3"]').toggleClass('completed', step3Complete);

                // Update active step
                if (!step1Complete) {
                    currentStep = 1;
                } else if (!step2Complete) {
                    currentStep = 2;
                } else if (!step3Complete) {
                    currentStep = 3;
                } else {
                    currentStep = 4; // All complete
                }

                $('.step').removeClass('active');
                if (currentStep <= 3) {
                    $(`.step[data-step="${currentStep}"]`).addClass('active');
                }
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
                            // Show success animation
                            $('.step').addClass('completed');
                            
                            showMessage('Company account created successfully! Redirecting to login...', 'success');
                            
                            setTimeout(() => {
                                window.location.href = '../login';
                            }, 2500);
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
                    company_name: { value: $('#company_name').val().trim(), validator: (v) => v.length >= 2 },
                    phone: { value: $('#phone').val().trim(), validator: validatePhone },
                    company_type: { value: $('#company_type').val(), validator: (v) => v !== '' },
                    city: { value: $('#city').val(), validator: (v) => v !== '' },
                    location: { value: $('#location').val().trim(), validator: (v) => v.length >= 5 },
                    contact_name: { value: $('#contact_name').val().trim(), validator: (v) => v.length >= 2 },
                    mobile: { value: $('#mobile').val().trim(), validator: validatePhone },
                    contact_email: { value: $('#contact_email').val().trim(), validator: validateEmail }
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

                if (!isValid) {
                    showMessage('Please fill in all required fields correctly.', 'error');
                    
                    // Focus on first error field
                    const firstError = $('.form-input.error:first');
                    if (firstError.length) {
                        firstError.focus();
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 300);
                    }
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

            // Initialize
            loadCompanyTypes();

            // Add shake animation CSS
            const shakeCSS = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
                    20%, 40%, 60%, 80% { transform: translateX(10px); }
                }
            `;
            
            const style = document.createElement('style');
            style.textContent = shakeCSS;
            document.head.appendChild(style);

            // Auto-focus first input
            $('#email').focus();

            // Add keyboard navigation
            $(document).keydown(function(e) {
                if (e.key === 'Enter' && !e.target.matches('button[type="submit"]')) {
                    e.preventDefault();
                    const inputs = $('.form-input, .form-select').not(':disabled');
                    const currentIndex = inputs.index(e.target);
                    if (currentIndex < inputs.length - 1) {
                        inputs.eq(currentIndex + 1).focus();
                    }
                }
            });
        });
    </script>
</body>
</html>