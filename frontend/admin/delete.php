<?php
    $id= $_GET['id'];
    try{
        $connection= mysqli_connect('localhost','root','','online_job_portal');
        $deletesql= "DELETE FROM users WHERE id='$id'";
        $result= mysqli_query($connection,$deletesql);
        if($result){
            header('location: manage-user.php');
    } else{
        echo "user delete failed";
    }
    } catch (Exception $e) {    
        echo "Error: " . $e->getMessage();
        }
    ?>