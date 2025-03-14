<?php
    session_start();

    session_destroy();
    
    setcookie('username', '', time() - 3600, '/'); // 1 hour ago, '/' makes the cookie available across the entire domain
    setcookie('password', '', time() - 3600, '/');

    header('location:loginform.php');


?>