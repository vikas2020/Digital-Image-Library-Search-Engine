<!-- db connection -->
<?php  include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Image Search</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="main">
        <div id="header">
            <a href="<?php echo $URL; ?>">Home</a>
        </div>
        <div id="content">
            <h1>Register</h1>
            <form class="loginForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" />
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" />
                </div>
                <div class="form-group">
                    <label>Email </label>
                    <input type="email" name="email" />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" />
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile" />
                </div>
                <input type="submit" class="btn" name="submit" value="Submit" />
                <span class="already-have">Already Have an Account ? <a href="<?php echo $URL.'login.php'; ?>">Login</a></span>
            </form>
            
            <?php

                if(isset($_POST["submit"])){
                    $m = is_int($_POST['mobile']);
                    if($_POST['first_name'] == ''){

                       echo '<div class="message error">Please Fill All The Fields.</div>';

                    }else if($_POST['last_name'] == ''){

                        echo '<div class="message error">Please Fill All The Fields.</div>';

                    }else if($_POST['email'] == ''){

                       echo '<div class="message error">Please Fill All The Fields.</div>';

                    }else if($_POST['password'] == ''){

                        echo '<div class="message error">Please Fill All The Fields.</div>';

                    }else if($_POST['mobile'] == '' || $m){

                        echo '<div class="message error">Please Fill Correct Number.</div>';

                    }else{

                        $first_name = $_POST['first_name'];
                        $last_name = $_POST['last_name'];
                        $username = $_POST['email'];
                        $password = md5($_POST['password']);
                        $mobile = $_POST['mobile'];
                            // check email address is exists in db
                        $sql1 = 'SELECT email_address FROM users WHERE email_address="'.$username.'"';
                        $result1 = mysqli_query($conn, $sql1);

                        if(mysqli_num_rows($result1) > 0){
                          // if exists
                            echo "<div class='message error'>Username is already Exists.</div>";

                        }else{
                                // if not exists
                            $sql = "INSERT INTO users(first_name,last_name,email_address,password,mobile) VALUES ('{$first_name}','{$last_name}', '{$username}', '{$password}','{$mobile}')";
                            $result = mysqli_query($conn,$sql);
                            if($result == '1'){

                                echo '<div class="message success">Registered Successfully Please login again.
                                </div>';


                            }
                            
                        }
                    }
                }
                // db close connection
                mysqli_close($conn);
            ?>
        </div>
    </div>
</body>
</html>
       
        