<?php

    session_start();
    if(!isset($_SESSION['user'])){
        header('location:loginform.php?msg=1');
    }
?>