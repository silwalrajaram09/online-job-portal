<?php
require_once '../includes/function.php';
$err = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $email2 = $_POST['eamil2'] ?? '';
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $cname = $_POST['company_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $ctype = $_POST['ctype'] ?? '';
    $city = $_POST['city'] ?? '';
    $location = $_POST['location'] ?? '';
    $name = $_POST['name'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $pemail = $_POST['pemail'] ?? '';



    //email validation
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
    //company name validation 
    if (checkRequiredField('company_name')) {
        $cname = $_POST['company_name'];

        if (matchPattern($cname, '/^[a-zA-Z0-9\s\.\-&]+$/')) {
            $cname = $_POST['company_name'];
        } else {
            $err['company_name'] = 'Company name should be in a valid format';
        }
    } else {
        $err['company_name'] = 'Company name is required';
    }
    //phone number validation
    if (checkRequiredField('phone')) {
        $phone = $_POST['phone'];
        if (matchPattern($phone, '/^(98|97)\d{8}$/')) {
            $phone = $_POST['phone'];
        } else {
            $err['phone'] = 'Enter a valid  phone number';
        }
    } else {
        $err['phone'] = 'Enter phone';
    }
    //company type validation
    if (checkRequiredField('ctype')) {
        $ctype = $_POST['ctype'];
    } else {
        $err['ctype'] = 'please select the company type';
    }
    //city validation
    if (checkRequiredField('city')) {
        $city = $_POST['city'];
    } else {
        $err['city'] = 'please select the city';
    }
    //location validation
    if (checkRequiredField('location')) {
        $location = $_POST['location'];
        if (matchPattern($location, '/^[a-zA-Z0-9\s,.-]+$/')) {
            $location = $_POST['location'];
        } else {
            $err['location'] = 'location should be in valid format';
        }
    } else {
        $err['location'] = 'location is required';
    }
    //contact person name validation
    if (checkRequiredField('name')) {
        $name = $_POST['name'];
        if (matchPattern($name, "/^['a-zA-Z\s'-]+$/")) {
            $name = $_POST['name'];
        } else {
            $err['name'] = 'name should be in valid format';
        }
    } else {

        $err['name'] = 'name is required';
    }
    //contact vlaidation
    if (checkRequiredField('mobile')) {
        $mobile = $_POST['mobile'];
        if (matchPattern($mobile, '/^(98|97)\d{8}$/')) {
            $mobile = $_POST['mobile'];
        } else {
            $err['mobile'] = 'Enter a valid  mobile number';
        }
    } else {
        $err['mobile'] = 'Enter mobile number';
    }
    //email validation
    if (checkRequiredField('pemail')) {
        if (validateEmail($_POST['pemail'])) {
            $pemail = $_POST['pemail'];
        } else {
            $err['pemail'] = 'email must be valid format';
        }
    } else {
        $err['pemail'] = 'email is required';
    }
    if (count($err) == 0) {
        if (companyRegistration($email, $hash_password, $cname, $phone, $ctype, $city, $location, $name, $mobile, $pemail)) {
            $err['success'] = 'company registration success';
        } else {
            $err['failed'] = 'comapany registration failed';
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
    <link rel="stylesheet" href="../assets/css/register.css">
</head>

<body>
    <div class="container">
        <h2>company Registration</h2>
        <form action="<?php echo  $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>login information</h2>
            <hr>
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
                <input type="password" id="password" name="password" placeholder="*******" value="<?php echo $password ? $password : ''; ?>">
                <?php echo displayErrorMessage($err, 'password') ?>
            </div>

            <div class="form-group">
                <label for="password2">Retype Password:</label>
                <input type="password" id="password2" name="password2" placeholder="*******" value="<?php echo $password2 ? $password2 : ''; ?>">
                <?php echo displayErrorMessage($err, 'password2') ?>
            </div>
            <h2>company information</h2>
            <hr>
            <div class="form-group">
                <label for="company_name">Comapany name</label>
                <input type="text" name="company_name" id="" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $cname ? $cname : ''; ?>">
                <?php echo displayErrorMessage($err, 'company_name') ?>

            </div>
            <div class="form-group">
                <label for="phone">Primary phone</label>
                <input type="tel" name="phone" id="phne" class="form-control" value="<?php echo $phone ? $phone : ''; ?>">
                <?php echo displayErrorMessage($err, 'phone') ?>
            </div>
            <div class="form-group">
                <label for="ctype">company type</label>
                <select name="ctype" id="ctype">
                    <option value="">selct company type</option>
                    <option value="itcompany">IT company</option>
                    <option value="ngo">NGO</option>
                    <option value="accounting">accounting</option>
                    <option value="advertising">advertising</option>
                    <option value="art">Art</option>
                </select>
                <?php echo displayErrorMessage($err, 'ctype') ?>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <select name="city" id="city">
                    <option value="">select location </option>
                    <option value="ktm">Kathmandu</option>
                    <option value="pokhara">pokhara</option>
                    <option value="lalitpur">lalitpur</option>

                </select>
                <?php echo displayErrorMessage($err, 'city') ?>
            </div>
            <div class="form-group">
                <label for="location">location</label>
                <input type="text" name="location" id="location" value="<?php echo $location ? $location : ''; ?>">
                <?php echo displayErrorMessage($err, 'location') ?>
            </div>
            <hr>
            <h2>contact person details</h2>
            <div class="form-group">
                <label for="name">Contact person name</label>
                <input type="text" name="name" id="name" value="<?php echo $name ? $name : ''; ?>">
                <?php echo displayErrorMessage($err, 'name') ?>

            </div>
            <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="tel" name="mobile" id="mobile" value="<?php echo $mobile ? $mobile : ''; ?>">
                <?php echo displayErrorMessage($err, 'mobile') ?>
            </div>
            <div class="form-group">
                <label for="pemail">Contact Person Email</label>
                <input type="text" name="pemail" id="pemail" value="<?php echo $pemail ? $pemail : ''; ?>">
                <?php echo displayErrorMessage($err, 'pemail') ?>
            </div>
            <div class="form-group">

                <input type="submit" value="create account">
            </div>
        </form>
        <p>By creating an account with us, you're confirming that you've read our <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></p>

        <p>Do you already have an account with us? <a href="../pages/loginform.php">Click here to login</a></p>
    </div>
</body>

</html>