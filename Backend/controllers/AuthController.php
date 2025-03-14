<?php
require_once 'Database.php';
require_once 'User.php';


class AuthController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function loginForm()
    {
        include_once 'frontend/login.php';
    }
    public function loginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'login') {
            header('Content-Type: application/json');

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $role = trim($_POST['role']);
            $rememberMe = isset($_POST['rememberme']) ? $_POST['rememberme'] : false;

            // Validate input
            if (empty($email) || empty($password) || empty($role)) {
                echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
                exit;
            }

            // Find user by email
            $user = User::findByEmail($email);

            if ($user) {
                // Verify password and role
                if (password_verify($password, $user->password) && $user->role === $role) {
                    session_start();

                    // Generate a unique session key for the user
                    $sessionKey = "user_{$user->role}_{$user->id}_" . session_id();

                    $_SESSION['current_user_key'] = $sessionKey;
                    // Store user details in the session using the unique key
                    $_SESSION[$sessionKey] = [
                        'id' => $user->id,
                        'email' => $user->email,
                        'role' => $user->role
                    ];

                    // Handle "Remember Me" functionality
                    if ($rememberMe) {
                        // Set cookies for 30 days
                        $expiryTime = time() + (30 * 24 * 60 * 60); // 30 days
                        setcookie('remember_email', $email, $expiryTime, "/");
                        setcookie('remember_token', base64_encode($password), $expiryTime, "/"); // Store encoded password
                        setcookie('remember_role', $role, $expiryTime, "/");
                    } else {
                        // Clear cookies if "Remember Me" is not checked
                        setcookie('remember_email', '', time() - 3600, "/");
                        setcookie('remember_token', '', time() - 3600, "/");
                        setcookie('remember_role', '', time() - 3600, "/");
                    }

                    // Respond with success and redirect URL
                    $redirectUrl = '/ojs/dashboard';

                    echo json_encode([
                        'success' => true,
                        'message' => 'Login successful.',
                        'redirect' => $redirectUrl
                    ]);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid email, password, or role.']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'User not found.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            exit;
        }
    }



    /**
     * Handles jobseeker registration.
     */
    public function registerJobseeker()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'registerJobseeker') {
            header('Content-Type: application/json');

            // Retrieve and sanitize form inputs
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password'] ?? '');
            $role = 'jobseeker'; // Default role for jobseekers
            $firstName = htmlspecialchars(trim($_POST['fname'] ?? ''));
            $lastName = htmlspecialchars(trim($_POST['lname'] ?? ''));
            $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
            $jobCategory = htmlspecialchars(trim($_POST['job_category'] ?? ''));
            $skills = isset($_POST['skills']) ? $_POST['skills'] : '';

            if (empty($email) || empty($password) || empty($firstName) || empty($lastName) || empty($phone) || empty($jobCategory)) {
                echo json_encode(['success' => false, 'message' => 'All fields are required.']);
                exit;
            }
            if (empty($skills)) {
                echo json_encode(['success' => false, 'message' => 'Skills are required.']);
                exit;
            }
            // 2. Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
                exit;
            }

            // 3. Validate password length
            if (strlen($password) < 8) {
                echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long.']);
                exit;
            }
            //validate the first name and last name only contain letters and spaces
            if (!preg_match('/^[a-zA-Z\s]+$/', $firstName)) {
                echo json_encode(['success' => false, 'message' => 'First name can only contain letters and spaces.']);
                exit;
            }
            if (!preg_match('/^[a-zA-Z\s]+$/', $lastName)) {
                echo json_encode(['success' => false, 'message' => 'First name can only contain letters and spaces.']);
                exit;
            }


            // 4. Validate phone number format (example: numeric and 10 digits)
            if (!preg_match('/^(98|97)[0-9]{10}$/', $phone)) {
                echo json_encode(['success' => false, 'message' => 'phone number must be start 98 or 97 and 10 digits.']);
                exit;
            }



            // === Register User ===
            $userId = $this->user->registerUser($email, $password, $role); // Insert into `users` table
            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'User registration failed.']);
                exit;
            }

            // === Register Jobseeker Details ===
            $result = $this->user->registerJobseekerDetails($userId, $firstName, $lastName, $phone, $jobCategory, $skills); // Insert into `jobseeker` table
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Registration successful.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save jobseeker details.']);
            }
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method or action.']);
            exit;
        }
    }



    /**
     * Handles company registration.
     */
    public function registerCompany()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'registerCompany') {
            header('Content-Type: application/json');

            // Sanitize input
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
            $companyName = htmlspecialchars(trim($_POST['company_name']));
            $phone = htmlspecialchars(trim($_POST['phone']));
            $companyType = htmlspecialchars(trim($_POST['company_type']));
            $city = htmlspecialchars(trim($_POST['city']));
            $location = htmlspecialchars(trim($_POST['location']));
            $contactName = htmlspecialchars(trim($_POST['contact_name']));
            $contactEmail = filter_var(trim($_POST['contact_email']), FILTER_SANITIZE_EMAIL);

            // Input validation
            if (
                empty($email) || empty($password) || empty($companyName) || empty($phone) ||
                empty($companyType) || empty($city) || empty($location) ||
                empty($contactName) || empty($contactEmail)
            ) {
                echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
                return;
            }

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
                return;
            }
            if (strlen($password) < 8) {
                echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long.']);
                exit;
            }
            // Register user in `users` table
            $userId = $this->user->registerUser($email, $password, 'company');
            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'User registration failed.']);
                return;
            }

            // Register company details in `companyRegistration` table
            $isCompanyRegistered = $this->user->registerCompanyDetails(
                $userId,
                $companyName,
                $phone,
                $companyType,
                $city,
                $location,
                $contactName,
                $contactEmail
            );

            if ($isCompanyRegistered) {
                echo json_encode(['success' => true, 'message' => 'Registration successful.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to register company details.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method or action.']);
        }
    }
    
}

// Handle the form submission for registration or login based on form input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();

    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'registerJobseeker') {
            $auth->registerJobseeker();
        } elseif ($_POST['action'] === 'registerCompany') {
            $auth->registerCompany();
        } elseif ($_POST['action'] === 'login') {
            $auth->loginAction();
        } 
    }
}
