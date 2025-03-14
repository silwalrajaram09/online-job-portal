<?php

$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);


$base_path = '/ojs';
if (strpos($request, $base_path) === 0) {
    $request = substr($request, strlen($base_path));
}
if ($request === '/backend/route/router.php') {
    http_response_code(403);
    die('Access Denied');
}
$routes = [
    '/home' => '../../frontend/index.php', 
    '/login' => '../../frontend/login.php',
    '/forgetPassword' => '../../frontend/forgetpassword.php',
    '/register'  => '../../frontend/index.php', 
    '/register/jobseeker' => '../../frontend/register.php',
    '/register/company' => '../../frontend/cregister.php',
    '/dashboard' => '../../frontend/pages/dashboard.php',

   '/dashboard/profile' => '../../frontend/pages/profile.php',
    '/dashboard/jobs' => '../../frontend/pages/list_job.php',
    '/dashboard/applications' => '../../frontend/pages/applications.php',
    '/dashboard/viewjobs' => '../../frontend/pages/job-details.php',
    '/dashboard/candidates' => '../../frontend/pages/candidates.php',
    '/dashboard/feedback' => '../../frontend/pages/feedback.php',
     '/ojs-admin' => '../frontend/admin/index.php',
];

// Check if the route exists
if (array_key_exists($request, $routes)) {
    require_once __DIR__ . '/' . $routes[$request];
} else {
    require_once __DIR__ . '/../../frontend/404.php';

    // if (isset($_SESSION['user_id'])) {
    //     // If user is logged in, redirect to dashboard
    //     header("Location: /ojs/dashboard");
    //     exit;
    // } else {
    //     // If user is not logged in, redirect to home
    //     header("Location: /ojs/home");
    //     exit;
    // }

}



?>
