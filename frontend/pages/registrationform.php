<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration Form</title>
    <!-- <link rel="stylesheet" href="css/registrationform.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #dfe9f5;
            background: linear-gradient(to right,#ccc,#dfe9f5);

        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            text-align: center;
            /* background: linear-gradient(red,green,blue); */
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0px 15px 20px gray;
        }
        .container legend{
            font-size: 2rem;
        }

        .form-group {
            position: relative;
            /* margin-bottom: 5px; */
            padding: 10px;
        }
        

        input[type="text"],
        input[type="password"],input[type="number"],input[type="date"] {
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            outline: none;
            /* border: transparent; */
            border-bottom: 2px solid black; 
            border-top: transparent;
            border-left: transparent;
            border-right: transparent;
            /* border: 2px solid #ccc; */
            border-radius: 5px;
            font-size: 16px;
            /* /transition: border-color o.3s; */
            background-color: transparent;
            
            
        }

        .form-group label::after {
            content: "*";
            color: red;
        }

        input[type="text"]:focus,input[type="password"]:focus,input[type="number"]:focus,input[type="date"]:focus {
            outline: none;
            /* //border-color: dodgerblue; */
            border-bottom: 2px solid dodgerblue;
            border-top: transparent;
            border-left: transparent;
            border-right: transparent;
            background-color: transparent;


        }
        .form-group i{
            float: left;
        }

        .form-group label {
            float: left; 
            margin-left: 10px;
          }

        input[type="submit"],input[type="reset"] {
            padding: 5px;
            width: 30%;
            box-sizing: border-box;
            border: 1px solid ;
            font-size: 20px;
            cursor: pointer;
            background-color: transparent;
            border-radius: 20px;
            color: #693e63;
            outline: none;
            font-weight: bold;

        }

        input[type="submit"]:hover {
            background-color:transparent ;
            color: dodgerblue;
          
            transition: ease-in-out 0.5s;
        }
        input[type="reset"]:hover {
            background-color:transparent ;
            color: dodgerblue;
          
            transition: ease-in-out 0.5s;
        }
       
        fieldset{
            outline:none;
            border: transparent;
        }
        form p{
            color: #000;
            font-size: medium;

        }
        form p a{
            text-decoration: none;
            color: #693e63;
        }
        form p a:hover{
            color: dodgerblue;
            transition: ease-in 0.3s;
           text-decoration: underline;
          
        }

        
        
    </style>
</head>
<body>
    <?php
        $err=[];
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if(isset($_POST['name'])&& !empty($_POST['name'])&&trim($_POST['name'])){
                $name=$_POST['name'];
                if(!preg_match('/^[A-Z][a-z\s]+([A-Z][a-z\s]+)+$/',$name)){
                        $err['name']="Enter valid name";
                 }
            } else{
               $err['name']='Enter name';
            } 
           #address
            if(isset($_POST['address'])&&!empty($_POST['address'])&& trim($_POST['address'])){
                $address=$_POST['address'];
                if(!preg_match('/^[\w\s]{4,30}$/',$address)){
                    $err['address']='Invalid address';
                }

            } else{
                $err['address']='Enter  your current address';
            }
            #email
            if(isset($_POST['email'])&& !empty($_POST['email'])){
                $email=$_POST['email'];
                if (!preg_match('/^[a-zA-Z]+[0-9\s]+([\@]+[a-zA-Z]+[\.]+[a-zA-Z]{3})$/',$email)) {
                     $err['email']='Invlaid Email';
                }
                // if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
                //     $err['email']='Invalid email';
                // }
            } else{
                $err['email']='Enter email';
            }
            #phone
            if(isset($_POST['phone'])&& !empty($_POST['phone'])&&trim($_POST['phone'])){
                $phone=$_POST['phone'];
                if(!preg_match('/^[9][6-8]+([0-9]{8})$/',$phone)){
                    $err['phone']="Enter valid number";
                }
            }else{
                $err['phone']='enter phone number';
            }
            #DateOfBirth
            if(isset($_POST['dob'])&& !empty($_POST['dob'])){
                $dob=$_POST['dob'];
                // if (!preg_match('/^(\d{2})+(\d{2})+(\d{4})$/',$dob)) {
                //     $err['dob']='Enter your valid DateOfBirth';
                // }
            }else{
                $err['dob']='Enter your valid   DateOfBirth';
            }
            #username
            if(isset($_POST['username']) && !empty($_POST['username']) && trim($_POST['username'])){
                $username=$_POST['username'];
                if(!preg_match('/^[\w]{4,16}$/',$username)){
                    $err['username']='Enter a valid username';

                }
            }else{
                $err['username']='Enter username';
            } 
            #password
            if(isset($_POST['password']) && !empty($_POST['password'])){
                $password=md5($_POST['password']);

            }else{
                $err['password']='Enter Password';
            }
            #gender
            if(isset($_POST['gender'])&& !empty($_POST['gender'])){
                $gender=$_POST['gender'];
            } else{
                $err['gender']='please slect one';
            }
            #country
            // if(isset($_POST['country'])&& !empty($_post['country'])){
            //     $country=$_post['country'];
            // }else{
            //     $err['country']='Please select your country';
            // }
            // #slectbox games
            // if(isset($_POST['games[]'])){
            //    $games[]=$_POST['games[]'];
            // } else{
            //    $err['games[]']='please select games';
            // }
            // #profile
            // if(isset($_POST['image'])&& !empty($_POST['image'])){
            //     $image=$_POST['image'];
            // }else{
            //     $err['image']='please upload a image';
            // }
            if (count($err)==0) {
                # process to check again
                try{
                $connection= mysqli_connect('localhost','root','','rajaram_db');
                $insertquery= "insert into tbl_registration(name,address,email,phone,dob,username,password,gender)values('$name','$address','$email','$phone','$dob','$username','$password','$gender') ";
                mysqli_query($connection,$insertquery);
                if($connection->affected_rows == 1 && $connection->insert_id>0){
                    //echo 'record insert  successful';
                    header('location:loginform.php');
                } else{
                    echo 'record insert  failed';

                }
                 } catch(Exception $ex){
                    echo " database error". $ex->getMessage();
                 }
            }
        }
      
 
    ?>
   
    <fieldset>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="container">
            <legend>Registration  Form</legend>
            <div class="form-group">
                <label class="required" for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter name" value="<?php echo isset($name)? $name:'';?>"/>
                <span style="color:red"><?php echo isset($err['name'])?$err['name']:'';?></span>
            </div>
            <div class="form-group">
                <label  class="required" for="address">Address</label>
                <input   type="text" id="address" placeholder="Enter address" name="address"  value="<?php echo isset($address)?$address:'';?>">
                <span style="color: red;"><?php echo isset($err['address'])?$err['address']:'';?></span>
            </div>
            <div class="form-group">
                <label class="required" for="email">Email</label>
                <input type="text" id="email" placeholder="example123@gmail.com" name="email" value="<?php echo isset($email)?$email:''?>">
                <span style="color: red;"><?php echo isset($err['email'])?$err['email']:'';?></span>
            </div>
            <div class="form-group">
                <label  class="required" for="phone">Phone</label>
                <input type="number" id="phone" placeholder="Enter phone" name="phone" value="<?php echo isset($phone)? $phone:'';?>">
                <span style="color: red;"> <?php echo isset($err['phone'])?$err['phone']:'';?></span>
            </div>
            <div class="form-group">
                <label  class="required" for="dob"><abbr title="Date of Birth">D.O.B.</abbr></label>
                <input type="date" id="dob" placeholder="Enter dob day/month/year" name="dob" value="<?php echo isset($dob)?$dob:'';?>">
                <span style="color: red;"><?php echo isset($err['dob'])?$err['dob']:'';?></span>
            </div>
            <div class="form-group">
                <label  class="required" for="username">Username</label>
                <input  type="text" id="username" placeholder="Enter username" name="username"value="<?php echo isset($username)? $username:'';?>">;
                <span  style="color:red"><?php  echo isset($err['username'])? $err['username']:''; ?></span>
            </div>
            <div class="form-group">
                <label  class="required" for="password">Password</label>
                <input  type="password" id="password" placeholder="Enter password" name="password">
                <span  style="color:red"><?php  echo isset($err['password'])? $err['password']:''; ?></span>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <input type="radio" id="male" name="gender" value="male" /> Male
                <input type="radio" id="female" name="gender" value="female" checked/> Female
                <span style="color:red"> <?php echo isset($err['gender'])?$err['gender']:'';?></span>
            </div>
            <!-- <div class="form-group">
                <label for="country">Country</label>
                <select name="country" id="country">
                    <option value="0">Select Country</option>
                    <option value="<?php echo isset($country)? $country:'';?>">Nepal</option>
                    <option value="<?php echo isset($country)? $country:'';?>">India</option>
                </select>
                <span style="color: red;"><?php echo isset($err['country'])?$err['country']:'';?></span>
            </div>
            <div class="form-group">
                <label for="games">Games</label>
                <input type="checkbox" id="football" name="games[]" value="football" /> Football
                <input type="checkbox" id="cricket" name="games[]" value="cricket" /> Cricket
                <span style="color: red;"><?php echo isset($err['games[]'])?$err['games[]']:'';?></span>
            </div>
            <div class="form-group">
                <label for="image">Profile Image</label>
                <input type="file" id="file" name="image" value="<?php echo isset($image)?$image:'';?>"/>
                <span style="color:red"><?php echo isset($err['image'])?$err['image']:'';?></span>
            </div> -->
            <P>.....or.......</P>
            <div class="icons">
                <i class="fab fa-google"></i>
                <i class="fab fa-facebook"></i>
            </div>
            <div class="form-group">
                <input class="submit" type="submit" value="Register"> 
                <input class="reset" type="reset" value="Clear">
            </div>
            <p>
                Already register? <a href="loginform.php">Sign in</a>
        </p>
        </form>
    </fieldset>
    <!-- <?php
        echo $name;
        echo $address;
        echo $email;
        echo $phonenumber;
        echo $dob;
        echo $username;
        echo $password;
        echo $gender;
        echo $country;
        echo $games;
    
    ?> -->
</body>
</html>