<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JobPortal</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow: auto;
        }

        /* Animated background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,64L48,80C96,96,192,128,288,128C384,128,480,96,576,85.3C672,75,768,85,864,112C960,139,1056,181,1152,181.3C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') center/cover no-repeat;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Login container */
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
        
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
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
            font-size: 1.1rem;
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

        /* Password field special styling */
        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: var(--transition);
            z-index: 2;
        }

        .password-toggle:hover {
            background: var(--bg-secondary);
            color: var(--primary-color);
        }

        /* Select styling */
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

        /* Checkbox styling */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1.5rem 0;
        }

        .custom-checkbox {
            position: relative;
            display: inline-block;
        }

        .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            width: 0;
            height: 0;
        }

        .checkbox-checkmark {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            position: relative;
            transition: var(--transition);
            cursor: pointer;
        }

        .checkbox-checkmark::after {
            content: '';
            position: absolute;
            display: none;
            left: 6px;
            top: 2px;
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .custom-checkbox input:checked + .checkbox-checkmark {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .custom-checkbox input:checked + .checkbox-checkmark::after {
            display: block;
        }

        .checkbox-label {
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        /* Button styling */
        .btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
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
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Links */
        .forgot-password {
            text-align: center;
            margin: 1rem 0;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-password a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .signup-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-secondary);
        }

        .signup-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
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

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--bg-primary);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            padding: 2rem;
            max-width: 400px;
            width: 90%;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-secondary);
            transition: var(--transition);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: var(--bg-secondary);
            color: var(--error-color);
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }
        }

        /* Animation classes */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-up {
            animation: slideUp 0.5s ease-out;
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
    </style>
</head>

<body>
    <div class="login-container slide-up">
        <div class="login-header">
            <h1><i class="fas fa-briefcase"></i> JobPortal</h1>
            <p>Welcome back! Please sign in to your account</p>
        </div>

        <div id="Message" class="alert"></div>

        <form id="loginForm" method="post">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label for="email" class="form-label required">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Enter your email" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label required">Password</label>
                <div class="input-wrapper password-container">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Enter your password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="passwordIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="role" class="form-label required">Login As</label>
                <div class="input-wrapper">
                    <i class="fas fa-user-tag input-icon"></i>
                    <select id="role" name="role" class="form-select" required>
                        <option value="">Select your role</option>
                        <option value="jobseeker">Job Seeker</option>
                        <option value="company">Company</option>
                    </select>
                </div>
            </div>

            <div class="checkbox-group">
                <div class="custom-checkbox">
                    <input type="checkbox" id="rememberMe" name="rememberme">
                    <span class="checkbox-checkmark"></span>
                </div>
                <label for="rememberMe" class="checkbox-label">Remember me for 30 days</label>
            </div>

            <button type="submit" class="btn btn-primary" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>
            <div class="form-group">
              
                <div class="forgot-password">
                    <a href="#" id="forgetPasswordLink">Forgot your password?</a>
                </div>
    
                <div class="signup-link">
                    <p>Don't have an account? <a href="home">Create one here</a></p>
                </div>
            </div>
        </form>
    </div>

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Reset Password</h2>
                <button class="modal-close" id="closeModal">&times;</button>
            </div>
            
            <div id="resetMessage" class="alert"></div>
            
            <form id="resetPasswordForm">
                <input type="hidden" id="resetEmail">
                
                <div class="form-group">
                    <label for="newPassword" class="form-label required">New Password</label>
                    <div class="input-wrapper password-container">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="newPassword" class="form-input" placeholder="Enter new password" required minlength="8">
                        <button type="button" class="password-toggle" onclick="toggleNewPassword()">
                            <i class="fas fa-eye" id="newPasswordIcon"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword" class="form-label required">Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="confirmPassword" class="form-input" placeholder="Confirm new password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="resetPasswordBtn">
                    <i class="fas fa-key"></i>
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <script src="frontend/assets/js/script.js"></script>
    <script>
        $(document).ready(function() {
            // Enhanced form validation
            function validateForm() {
                let isValid = true;
                const email = $('#email').val().trim();
                const password = $('#password').val().trim();
                const role = $('#role').val();

                // Reset previous states
                $('.form-input, .form-select').removeClass('error success');
                
                // Email validation
                if (!email) {
                    $('#email').addClass('error');
                    isValid = false;
                } else if (!isValidEmail(email)) {
                    $('#email').addClass('error');
                    showMessage('Please enter a valid email address.', 'error');
                    isValid = false;
                } else {
                    $('#email').addClass('success');
                }

                // Password validation
                if (!password) {
                    $('#password').addClass('error');
                    isValid = false;
                } else if (password.length < 6) {
                    $('#password').addClass('error');
                    showMessage('Password must be at least 6 characters long.', 'error');
                    isValid = false;
                } else {
                    $('#password').addClass('success');
                }

                // Role validation
                if (!role) {
                    $('#role').addClass('error');
                    isValid = false;
                } else {
                    $('#role').removeClass('error');
                }

                return isValid;
            }

            // Enhanced form submission
            $('#loginForm').submit(function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    return;
                }

                const loginBtn = $('#loginBtn');
                const originalText = loginBtn.html();
                
                // Show loading state
                loginBtn.html('<div class="btn-loading"><div class="spinner"></div> Signing in...</div>');
                loginBtn.prop('disabled', true);

                const formData = $(this).serialize();

                $.ajax({
                    url: 'backend/controllers/authController.php', // Adjust path as needed
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    timeout: 15000,
                    success: function(response) {
                        if (response.success) {
                            showMessage('Login successful! Redirecting...', 'success');
                            
                            // Success animation
                            setTimeout(() => {
                                window.location.href = response.redirect || 'dashboard';
                            }, 1500);
                        } else {
                            showMessage(response.message || 'Login failed. Please try again.', 'error');
                            
                            // Shake animation for error
                            $('.login-container').css('animation', 'shake 0.5s ease-in-out');
                            setTimeout(() => {
                                $('.login-container').css('animation', '');
                            }, 500);
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Connection error. Please try again.';
                        
                        if (status === 'timeout') {
                            errorMessage = 'Request timed out. Please check your connection.';
                        } else if (xhr.status === 404) {
                            errorMessage = 'Service not found. Please contact support.';
                        } else if (xhr.status === 500) {
                            errorMessage = 'Server error. Please try again later.';
                        }
                        
                        showMessage(errorMessage, 'error');
                        console.error('Login error:', error);
                    },
                    complete: function() {
                        // Reset button
                        loginBtn.html(originalText);
                        loginBtn.prop('disabled', false);
                        //redirect dashboard

                    }
                });
            });

            // Real-time validation
            $('#email').on('blur', function() {
                const email = $(this).val().trim();
                if (email && !isValidEmail(email)) {
                    $(this).addClass('error').removeClass('success');
                } else if (email) {
                    $(this).addClass('success').removeClass('error');
                }
            });

            $('#password').on('input', function() {
                const password = $(this).val();
                updatePasswordStrength(password);
            });

            // Forgot password functionality
            $('#forgetPasswordLink').click(function(e) {
                e.preventDefault();
                const email = $('#email').val().trim();
                
                if (!email) {
                    showMessage('Please enter your email address first.', 'info');
                    $('#email').focus();
                    return;
                }
                
                if (!isValidEmail(email)) {
                    showMessage('Please enter a valid email address.', 'error');
                    $('#email').focus();
                    return;
                }
                
                $('#resetEmail').val(email);
                $('#resetPasswordModal').fadeIn();
            });

            // Reset password form
            $('#resetPasswordForm').submit(function(e) {
                e.preventDefault();
                
                const newPassword = $('#newPassword').val();
                const confirmPassword = $('#confirmPassword').val();
                const email = $('#resetEmail').val();

                if (newPassword !== confirmPassword) {
                    showResetMessage('Passwords do not match.', 'error');
                    return;
                }

                if (newPassword.length < 8) {
                    showResetMessage('Password must be at least 8 characters long.', 'error');
                    return;
                }

                const resetBtn = $('#resetPasswordBtn');
                const originalText = resetBtn.html();
                
                resetBtn.html('<div class="btn-loading"><div class="spinner"></div> Resetting...</div>');
                resetBtn.prop('disabled', true);

                // Simulate API call for password reset
                setTimeout(() => {
                    showResetMessage('Password reset instructions sent to your email.', 'success');
                    setTimeout(() => {
                        $('#resetPasswordModal').fadeOut();
                        $('#resetPasswordForm')[0].reset();
                        $('#passwordStrengthBar').removeClass().css('width', '0%');
                    }, 2000);
                    
                    resetBtn.html(originalText);
                    resetBtn.prop('disabled', false);
                }, 2000);
            });

            // Modal close functionality
            $('#closeModal, .modal').click(function(e) {
                if (e.target === this) {
                    $('#resetPasswordModal').fadeOut();
                }
            });

            // Password confirmation validation
            $('#confirmPassword').on('input', function() {
                const newPassword = $('#newPassword').val();
                const confirmPassword = $(this).val();
                
                if (confirmPassword && newPassword !== confirmPassword) {
                    $(this).addClass('error').removeClass('success');
                } else if (confirmPassword) {
                    $(this).addClass('success').removeClass('error');
                }
            });
        });

        // Utility functions
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showMessage(message, type) {
            const messageDiv = $('#Message');
            messageDiv.removeClass('alert-error alert-success alert-info')
                     .addClass(`alert-${type}`)
                     .html(`<i class="fas fa-${getAlertIcon(type)}"></i> ${message}`)
                     .fadeIn();
                     
            // Auto-hide success messages
            if (type === 'success') {
                setTimeout(() => {
                    messageDiv.fadeOut();
                }, 3000);
            }
        }

        function showResetMessage(message, type) {
            const messageDiv = $('#resetMessage');
            messageDiv.removeClass('alert-error alert-success alert-info')
                     .addClass(`alert-${type}`)
                     .html(`<i class="fas fa-${getAlertIcon(type)}"></i> ${message}`)
                     .fadeIn();
        }

        function getAlertIcon(type) {
            switch(type) {
                case 'success': return 'check-circle';
                case 'error': return 'exclamation-triangle';
                case 'info': return 'info-circle';
                default: return 'info-circle';
            }
        }

        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordField.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        function toggleNewPassword() {
            const passwordField = document.getElementById('newPassword');
            const passwordIcon = document.getElementById('newPasswordIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordField.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        function updatePasswordStrength(password) {
            const strengthBar = document.getElementById('passwordStrengthBar');
            if (!strengthBar) return;

            let strength = 0;
            const checks = [
                password.length >= 8,
                /[a-z]/.test(password),
                /[A-Z]/.test(password),
                /\d/.test(password),
                /[^a-zA-Z\d]/.test(password)
            ];

            strength = checks.filter(check => check).length;

            strengthBar.className = 'password-strength-bar';
            
            if (strength < 3) {
                strengthBar.classList.add('password-strength-weak');
            } else if (strength < 5) {
                strengthBar.classList.add('password-strength-medium');
            } else {
                strengthBar.classList.add('password-strength-strong');
            }
        }

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

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && $('#resetPasswordModal').is(':visible')) {
                $('#resetPasswordModal').fadeOut();
            }
        });

        // Auto-focus first input on load
        window.addEventListener('load', function() {
            document.getElementById('email').focus();
        });

        // Add loading animation on page transitions
        window.addEventListener('beforeunload', function() {
            document.body.style.opacity = '0.8';
        });
    </script>
</body>
</html>