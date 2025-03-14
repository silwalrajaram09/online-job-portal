<?php
    
    try{

        $connection=mysqli_connect('localhost','root','','online_job_portal');
        if(!$connection){
            echo 'connection failed';
    } else{
        echo 'connection successfull';
    }
       
    } catch(Exception $ex){
        die('database error').$ex->getMessage();
    }

?>