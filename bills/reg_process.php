<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Bills</title>
        <link href="https://fonts.googleapis.com/css?family=Covered+By+Your+Grace|Permanent+Marker" rel="stylesheet">
            <link href="style.css" rel="stylesheet" type="text/css">

</head>
<body>
<div class = "heading">
<h1>Bills</h1>
</div>
<?php

	require_once('config.php'); 

    $success = NULL;
    $message = NULL;
    $email = NULL;
    $password = NULL;
    $name = NULL;
    $username = NULL;

	$connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}



    if(isset($_POST['action']))
    {          
        if($_POST['action']=="login")
        {
            $email = mysqli_real_escape_string($connection,$_POST['email']);
            $password = mysqli_real_escape_string($connection,$_POST['password']);
            $strSQL = mysqli_query($connection,"select * from users where email='".$email."' and password='".md5($password)."'");
            $Results = mysqli_fetch_array($strSQL);
            if(count($Results)>=1)
            {
                $name = $Results['name'];
                $username = $Results['username'];
                $message = " Login Sucessful!!";
                $success = 1;
            }
            else
            {
                $message = "Invalid email or password!!";
                $success = 0;
                
            }        
        }
        elseif($_POST['action']=="signup")
        {
            $name = mysqli_real_escape_string($connection,$_POST['name']);
            $email = mysqli_real_escape_string($connection,$_POST['email']);
            $username = mysqli_real_escape_string($connection,$_POST['username']);
            $password = mysqli_real_escape_string($connection,$_POST['password']);
            
            
            $username_sql = "SELECT username FROM users where username='".$username."'";
            $username_query = mysqli_query($connection,$username_sql);
            $username_result = mysqli_fetch_array($username_query);
            
            
            if(!preg_match('/^\w{0,}$/', $username)) { 
            // valid username is alphanumeric & longer than or equals 5 chars
                $message = "username cannot consist of special characters";
                $success = 0;
            }
            else if (count($username_result)>=1) {
                $message = "'".$username."' username already exist!!";
                $success = 0;                   
            }
            else{
                $query = "SELECT email FROM users where email='".$email."'";
                $result = mysqli_query($connection,$query);
                $numResults = mysqli_num_rows($result);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) // Validate email address
                {
                    $message =  "Invalid email address please type a valid email!!";
                    $success = 0;
                }
                elseif($numResults>=1)
                {
                    $message = $email." Email already exist!!";
                    $success = 0;
                }
                elseif($password == ""){
                    $message = "Password field cannot be empty";
                    $success = 0;
                }
                elseif($username == ""){
                    $message = "Username field cannot be empty";
                    $success = 0;
                }
                else
                {
                    mysqli_query($connection,"insert into users(name,email,username,password) values('".$name."','".$email."','".$username."','".md5($password)."')");
                    mysqli_query($connection, "CREATE TABLE `bills`.`".$username."` ( `id` INT NOT NULL AUTO_INCREMENT , `description` VARCHAR(250) NOT NULL , `cost` VARCHAR(250) NOT NULL , `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM CHARSET=utf8 COLLATE utf8_unicode_ci;");
                    $message = "Signup Sucessful!!";
                    $success = 1;
                }
            }
        }
    }
	
    if ($success == 1) {
        
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        $_SESSION['password'] = md5($password);
        $_SESSION['username'] = $username;
        $_SESSION['reg_msg'] = $message;

        header("Location: homepage.php");        
    }
    else if ($success == 0){
        header("Location: login_signup.php");
        $_SESSION['reg_msg'] = $message;
    }
?>


</body>
</html>
