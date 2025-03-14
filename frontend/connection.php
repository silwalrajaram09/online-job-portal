<?php
        $email = $_POST['email'];
        try {
            $connection = new mysqli('localhost', 'root', '', 'online_job_portal');
            $query = "SELECT * FROM users WHERE email = '$email'";
            $result = $connection->query($query);
           
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                echo "<span class = 'error'>email already exists</span>";
            } else {
                echo "<span class = 'success'>email available</span>";
            }
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
   
?>