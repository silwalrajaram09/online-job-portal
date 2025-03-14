<?php
// session_start();

// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: /ojs/admin/login.php");
//     exit;
// }

// $requestUri = $_SERVER['REQUEST_URI'];
// $basePath = '/ojs/admin/';

// $route = str_replace($basePath, '', $requestUri);
// $pagePath = "frontend/admin_pages/$route.php";

// if (file_exists($pagePath)) {
//     if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
//         include $pagePath;
//         exit;
//     }

//     // If directly accessed, load full layout
//     include "frontend/templates/admin_header.php";
//     include $pagePath;
//     include "frontend/templates/admin_footer.php";
// } else {
//     include "frontend/admin_404.php"; // Load 404 page
// }
?>
