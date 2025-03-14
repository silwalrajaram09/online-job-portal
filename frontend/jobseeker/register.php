<?php
//require_once '../includes/function.php';
$err = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $email2 = $_POST['eamil2'] ?? '';
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $fname= $_POST['fname'] ??'';
    $lname= $_POST['lname'] ?? '';
    $phone= $_POST['phone'] ?? '';
    // if(isset($_POST['email']) && !empty($_POST['email']) && trim($_POST['email'])){
    //     $email= $_POST['email'];
    // } else{
    //     $err['email']='email is required';
    // }
    //email validation
    // if(checkRequiredField('email')){
    //    if(validateEmail($_POST['email'])){
    //         $email= $_POST['email'];
    //    } else{
    //         $err['email']= 'email must be valid format';
    //    }
    // } else{
    //     $err['email']='email is required';
    // }
    // //second email validation 
    // if (checkRequiredField('email2')) {
    //     if (validateEmail($_POST['email2'])) {
    //         $email2 = $_POST['email2'];
    //         if ($email == $email2) {
    //             $email2 = $_POST['email2'];  
    //         }else{
    //             $err['email2']='email must be mathced';
    //         }
    //     } else {
    //         $err['email2'] = 'email must be vlaid format';
    //     }
    // } else {
    //     $err['email2'] = 'Email is required';
    // }
    if (checkRequiredField('email') && checkRequiredField('email2')) {
        $email = $_POST['email'];
        $email2 = $_POST['email2'];
    
        // Validate the format of both email fields
        if (validateEmail($email) && validateEmail($email2)) {
            // Check if both emails match
            if ($email === $email2) {
                $email = $_POST['email'];
                $email2 = $_POST['email2'];
            } else {
                $err['email2'] = 'Emails must match.';
            }
        } else {
            $err['email'] = 'Email must be in a valid format.';
            $err['email2'] = 'Email must be in a valid format.';
        }
    } else {
        if (!checkRequiredField('email')) {
            $err['email'] = 'Email is required.';
        }
        if (!checkRequiredField('email2')) {
            $err['email2'] = 'Email confirmation is required.';
        }
    }
    
    //password validation 

    // if (checkRequiredField('password')) {
    //     if (matchPattern($password, "/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*(),.?\":{}|<>]).{8,}$/")){
    //         $password= $_POST['password'];
    //     } else{
    //         $err['password']='Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
    //     }
    // } else{
    //     $err['password']='password is required';
    // } 
    // //seond passoword validation
    // if (checkRequiredField('password2')) {
    //     if (matchPattern($password, "/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*(),.?\":{}|<>]).{8,}$/")){
    //         if($password== $password2){

    //             $password= $_POST['password'];
    //         } else{
    //             $err['password2']='password must be same';
    //         }
    //     } else{
    //         $err['password']='Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
    //     }
    // } else{
    //     $err['password']='password is required';
    // } 
    if (checkRequiredField('password2') && checkRequiredField('password')) {
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
    
        if (preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*(),.?\":{}|<>]).{8,}$/", $password)) {
            if ($password === $password2) {
                $password = $_POST['password'];
                $password2 = $_POST['password2'];
                $hash_password= password_hash($password,PASSWORD_DEFAULT);
               // $hash_password2= password_hash($password2,PASSWORD_DEFAULT);

            } else {
                $err['password2'] = 'Passwords must match.';
            }
        } else {
            $err['password'] = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
        }
    } else {
        if (!checkRequiredField('password')) {
            $err['password'] = 'Password is required.';
        }
        if (!checkRequiredField('password2')) {
            $err['password2'] = 'Password confirmation is required.';
        }
    }
    
    //name validation 
    if(checkRequiredField('fname')){
        $fname= $_POST['fname'];
    } else{

        $err['fname']= 'first name is required';
    }
    if(checkRequiredField('lname')){
        $lname= $_POST['lname'];
    } else{

        $err['lname']= 'first name is required';
    }
    
    //phone number validation 
    if (checkRequiredField('phone')) {
        $phone = $_POST['phone'];
        if(matchPattern($phone,'/^(98|97)\d{8}$/')){
            $phone = $_POST['phone'];
        } else{
            $err['phone'] = 'Enter a valid  phone number';
        }
    } else {
        $err['phone'] = 'Enter phone';
    }
    //select option validation
    if (checkRequiredField('job_category')) {
        $job_category = $_POST['job_category'];
        
        if ($job_category === "") {
            $err['job_category'] = 'Please select a job category.';
        } else {
            $valid_categories = ['software developer', 'hardware engineer', 'web designer'];
            if (!in_array($job_category, $valid_categories)) {
                $err['job_category'] = 'Invalid job category selected.';
            }
        }
    } else {
        $err['job_category'] = 'Job category is required.';
    }
    if (count($err) == 0) {
        if (regsistration($email,$hash_password,$fname,$lname,$phone,$job_category)) {
              $err['success'] =  'registration  success';
        } else {
              $err['failed'] = 'registration  Failed';
        }
      }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Jobseeker Registration</title>
    <link rel="stylesheet" href="../assets/css/register.css">
</head>

<body>
    <div class="container">
        <h1>Jobseeker Registration</h1>
        <p>Fill out the form below to create a free account. Once you create an account, log in to the system and create your profile to start applying
            to the jobs you are looking for. It's all free.</p>
        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <h2>Login Information</h2>
            <fieldset>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="text" id="email" name="email" value="<?php echo $email ? $email : ''; ?>">
                   
                    <?php echo displayErrorMessage($err, 'email') ?>
                </div>

                <div class="form-group">
                    <label for="email2">Retype Email Address:</label>
                    <input type="text" id="email2" name="email2" value="<?php echo $email2 ? $email2 : ''; ?>">
                    <?php echo displayErrorMessage($err, 'email2') ?>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="*******" value="<?php echo $password? $password: '';?>">
                    <?php echo displayErrorMessage($err, 'password') ?>
                </div>

                <div class="form-group">
                    <label for="password2">Retype Password:</label>
                    <input type="password" id="password2" name="password2" placeholder="*******" value="<?php echo $password2? $password2: '';?>">
                    <?php echo displayErrorMessage($err, 'password2') ?>
                </div>
                <hr id="line">
                <h2>Personal Information</h2>
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" id="fname" name="fname" value="<?php echo $fname? $fname: '';?>">
                    <?php echo displayErrorMessage($err,'fname') ?>

                </div>

                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" id="lname" name="lname" value="<?php echo $lname?$lname:''?>">
                    <?php echo displayErrorMessage($err,'lname')?>
                </div>

                <div class="form-group">
                    <label for="phone">Contact Number:</label>
                    <input type="tel" id="contact_number" name="phone" value="<?php echo $phone? $phone:'';?>">
                    <?php echo displayErrorMessage($err,'phone')?>

                </div>
                <hr>
                <h2>Job Preference</h2>
                <div class="form-group">
                    <label for="job_category">Prefer Job Category:</label>
                    <select id="job_category" name="job_category">
                        <option value="">Select a category</option>
                        <option value="software">Software Engineer</option>
                        <option value="hardware">Hardware Engineer</option>
                        <option value="web">Web Developer</option>
                    </select>
                    <?php echo displayErrorMessage($err,'job_category')?>
                </div>
            </fieldset>
            <div class="form-group">

                <input type="submit" value="create account">
            </div>
        </form>

        <p>By creating an account with us, you're confirming that you've read our <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></p>

        <p>Do you already have an account with us? <a href="#">Click here to login</a></p>
    </div>

    
</body>

</html>