<?php
include_once '../includes/function.php';

if(isset($_COOKIE['emali'])){
    session_start();
    $_SESSION['user']= $_COOKIE;
    header('location:dashboard.php');
}
if(isset($_GET['msg'])&& $_GET['msg']==1){
    echo " please login to continue";
}
$err = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Username (or email) validation
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $err = [];
        if(checkRequiredField('email')){
               if(validateEmail($_POST['email'])){
                    $email= $_POST['email'];
               } else{
                    $err['email']= 'email must be valid format';
               }
            } else{
                $err['email']='email is required';
            }
        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $password = $_POST['password'];
            //$encrypted_password= $password;
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // if (!preg_match('/^[a-zA-Z][0-9A-Za-z_!@#$%^&]{5,20}$/', $password)) {
            //     $err['password'] = 'invalid password';
            // }
        } else {
            $err['password'] = 'Enter Password';
        }
        // role validation 
        $valid_roles=['jobseeker','company','admin'];
        if(!in_array($role,$valid_roles)){
            $err['role']='please select the valid roles';
        } else{
            $role=$_POST['role'];
        }
        if (count($err) == 0) {
                try{
                    $connection= mysqli_connect('localhost','root','','online_job_portal');
                    $selectquery= "select *from users where email='$email' OR password=' $hashedPassword' AND role='$role'";
                    $result= mysqli_query($connection,$selectquery);
                    $users=[];
                    if(mysqli_num_rows($result)==0){
                        echo '<script> alert("invalid email and password")
                        </script>';
                    } else{
                        $user= mysqli_fetch_assoc($result);
                        session_start();
                        $_SESSION['user']=$user;

                        if(isset($_POST['remember'])){
                            setcookie('email',$user['email'].time()+(7*24*60*60));
                            setcookie('password',$user['password'].time()+(7*24*60*60));
                        }
                        echo "login success";
                        print_r($user);
                        echo $_SESSION['user']['email'];
                        if($_SESSION['user']['role']=== 'admin'){
                           header('location:../jobseeker/dashboard.php');
                        }
                        if($_SESSION['user']['role']=== 'jobseeker'){
                            header('location:../jobseeker/dashboard.php');
                        }
                        if($_SESSION['user']['role']=== 'company'){
                            echo 'hello from company';
                        }
                       // header('location:dashboard.php');
                    }
                } catch(Exception $ex){
                    echo " database error" . $ex-> getMessage();

                }

        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/loginform.css">
</head>

<body>
    <fieldset>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="container">
            <h3>login</h3>
        
            <div class="form-group">

                <i class="fas fa-user"></i>
                <label for="email" class="required">email</label>
                <input type="text" name="email" id="email">
            <span class="error"><?php echo displayErrorMessage($err,'email')?></span>  
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>

                <label for="password" class="required">password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" >
                    <span class="error"><?php echo displayErrorMessage($err,'password')?></span> 
                    <div class="checkbox-container" onclick="togglePassword()">
                        <input type="checkbox" id="show-password">
                        <span class="checkmark"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" id="role">
                        <option value="">select a role</option>
                        <option value="jobseeker">JobSeeker</option>
                        <option value="company">Company</option>
                        <option value="admin">Admin</option>
                    </select>
                    <span class="error"><?php echo displayErrorMessage($err,'role')?></span> 
                </div>
            </div>
            <div class="form-group">
                <span>Remember me<input type="checkbox" name="remember" value=""></span>
            </div>
            <div class="form-group">
                <input type="submit" value="login">
            </div>

            <p>
                Don't have an account? <a href="registrationform.php">register here</a>
            </p>
        </form>
    </fieldset>
    <!-- <table border="1px">
        <tr>
            <th>username</th>
            <th>password</th>
        </tr>
        <tr>
            <td><?php echo $username; ?></td>
            <td><?php echo $password; ?></td>
    
        </tr>
    </table> -->
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const showPasswordCheckbox = document.getElementById('show-password');

            // Toggle the type attribute and checkbox status
            passwordField.type = showPasswordCheckbox.checked ? 'text' : 'password';
        }
    </script>
</body>

</html>