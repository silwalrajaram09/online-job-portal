<?php
date_default_timezone_set('Asia/Kathmandu');
function checkRequiredField($index)
{
    if (isset($_POST[$index]) && !empty($_POST[$index]) && trim($_POST[$index])) {
        return true;
    } else {
        return false;
    }
}

function displayErrorMessage($error, $index)
{
    if (array_key_exists($index, $error)) {
        return "<span class='error'>" . $error[$index] . " </span>";
    }
    return false;
}

function matchPattern($var, $pattern)
{
    if (preg_match($pattern, $var)) {
        return true;
    }
    return false;
}
function validateEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function displaySuccessMessage($error, $index)
{
    if (array_key_exists($index, $error)) {
        return "<span class='success'>" . $error[$index] . " </span>";
    }
    return false;
}

function regsistration($e, $p, $f, $l, $ph, $jc)
{
    try {
        $connect = new mysqli('localhost', 'root', '', 'online_job_portal');
        $sql = "insert into users(email,password,role) values('$e','$p','jobseeker')";
        if ($connect->query($sql)) {
            // Insert all details into the job_seeker table
            $sql_job_seeker = "INSERT INTO jobseeker (email, password, first_name, last_name, phone, job_category) 
                               VALUES ('$e', '$p', '$f', '$l', '$ph', '$jc')";

            if ($connect->query($sql_job_seeker)) {
                echo "Registration successful.";
            } else {
                echo "Error inserting into job_seeker table: " . $connect->error;
            }
        } else {
            echo "Error inserting into users table: " . $connect->error;
        }
    } catch (\Throwable $th) {
        die('Error: ' . $th->getMessage());
    } finally {
        $connect->close();
    }
}
function companyRegistration($e,$p,$cn,$cp,$ct,$city,$l,$pn,$pp,$pe){
    try {
        $connect = new mysqli('localhost', 'root', '', 'online_job_portal');
        $sql = "insert into users(email,password,role) values('$e','$p','company')";
        if ($connect->query($sql)) {
            // Insert all details into the company table
            $sql_company = "INSERT INTO company_registration (c_name, c_phone, c_type, city ,c_location, p_name,p_phone, p_email) 
                               VALUES ('$cn','$cp', '$ct','$city', '$l', '$pn', '$pp','$pe')";

            if ($connect->query($sql_company)) {
                echo " company Registration successful.";
            } else {
                echo "regsitration failed " . $connect->error;
            }
        } else {
            echo "regsitration failed " . $connect->error;
        }
    } catch (\Throwable $th) {
        die('Error: ' . $th->getMessage());
    } finally {
        $connect->close();
    }
}
function addStudent($n, $c, $f, $r, $p, $a, $d, $s)
{
    try {

        $connect = new mysqli('localhost', 'root', '', 'students_db');
        $sql = "insert into students_tbl(name,course,fee,rollno,phone,address,dob,status) values ('$n','$c','$f','$r','$p','$a','$d','$s')";
        $connect->query($sql);
        if ($connect->insert_id > 0 && $connect->affected_rows == 1) {
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        die('Error: ' . $th->getMessage());
    }
}

function getAllStudent()
{
    try {

        $connect = new mysqli('localhost', 'root', '', 'students_db');
        $sql = "select * from students_tbl";
        $result = $connect->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            //fetch data
            while ($record = $result->fetch_assoc()) {
                array_push($data, $record);
            }
        }
        return $data;
    } catch (\Throwable $th) {
        die('Error: ' . $th->getMessage());
    }
}

function getStudentById($id)
{
    try {

        $connect = new mysqli('localhost', 'root', '', 'students_db');
        $sql = "select * from students_tbl where id=$id";
        $result = $connect->query($sql);
        if ($result->num_rows == 1) {
            $record = $result->fetch_assoc();
            return $record;
        }
        return false;
    } catch (\Throwable $th) {
        die('Error: ' . $th->getMessage());
    }
}


function deleteStudent($id)
{
    try {
        $connect = new mysqli('localhost', 'root', '', 'students_db');
        $sql = "delete from students_tbl where id=$id";
        $connect->query($sql);
        if ($connect->affected_rows == 1) {
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        die('Error: ' . $th->getMessage());
    }
}

function updateStudent($i, $n, $c, $f, $r, $p, $a, $d, $s)
{
    try {
        $ud = date('Y-m-d H:i:s');
        $connect = new mysqli('localhost', 'root', '', 'students_db');
        $sql = "update students_tbl set name='$n',course='$c', fee='$f', rollno='$r', phone='$p', address='$a', dob='$d',status='$s',updated_at='$ud' where id=$i";
        $connect->query($sql);
        if ($connect->affected_rows == 1) {
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        die('Error: ' . $th->getMessage());
    }
}

function printStatus($s)
{
    if ($s == 1) {
        return 'Active';
    } else {
        return 'DeActive';
    }
}
