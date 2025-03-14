<?php
    session_start();
    $activeSessionKey = null;
  
    foreach ($_SESSION as $key => $value) {
        if (strpos($key, 'user_') === 0) {
            $activeSessionKey = $key;
            break;
        }
    }
    if (isset($_SERVER['HTTP_REFERER'])) {
        $_SESSION['last_page'] = $_SERVER['HTTP_REFERER'];
    }
    
    // Use last visited page from session if available
    $previousPage = $_SESSION['last_page'] ?? 'javascript:history.back()';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <!-- <link rel="stylesheet" href="/ojs/frontend/assets/css/style.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 80px;
            font-weight: bold;
            color: #ff4757;
        }
        p {
            font-size: 18px;
            color: #555;
        }
        .btn-home {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-home:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- <h1>404</h1>
        <p>Oops! The page you are looking for does not exist.</p>
        <a href="/ojs/home" class="btn-home">Go to Homepage</a> -->
        <h1>404</h1>
        <p>Oops! The page you are looking for does not exist.</p>
        <a href="<?= htmlspecialchars($previousPage) ?>" class="btn">Go Back</a>
        <?php if (!$activeSessionKey): ?>
            <a href="/ojs/home" class="btn">Go to Homepage</a>
        <?php endif; ?>
    </div>
</body>
</html>
<!-- RewriteEngine On
RewriteBase /ojs/

# Avoid infinite redirect loops
RewriteCond %{REQUEST_URI} !^/backend/route/route.php$

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ backend/routes/router.php [QSA,L] -->