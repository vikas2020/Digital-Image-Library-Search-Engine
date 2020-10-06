<!-- db connection -->
<?php  include 'config.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Image Search</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="main">
        <div id="header">
            <a href="<?php echo $URL; ?>">Home</a>
        </div>
        <div id="content">
            <h1>Login</h1>
            <form  class="loginForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="username" />
                </div>
                <div class="form-group">
                    <label>password</label>
                    <input type="password" name="password" />
                </div>
                <input type="submit" class="btn" name="login" value="Login" />
                <span class="dont-have"><a href="<?php echo $URL.'register.php' ?>">Register</a></span>
                <span class="forgot"><a href="<?php echo $URL.'forgot.php' ?>">Forgot Password</a></span>
            </form>
            <?php
                if(isset($_POST['login'])){
                    if(  $_POST['username'] == ''){

                       echo '<div class="message error">Please Fill All The Fields.</div>';

                    }else if( $_POST['password'] == ''){

                        echo '<div class="message error">Please Fill All The Fields.</div>';

                    }else{

                        $username = $_POST["username"];
                        $password = md5($_POST["password"]);
                            // check username and password
                        $sql = "SELECT first_name,email_address FROM users WHERE email_address = '$username' AND  password = '$password'";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {

                            while($row = mysqli_fetch_assoc($result)) {
                                /* Start the session */
                                session_start();
                                /* Set session variables */
                                $_SESSION["first_name"] = $row['first_name'];
                                $_SESSION["email_address"] = $row['email_address'];

                                header("location: {$URL}");
                            }

                        }else{
                            echo "<div class='message error'>Email and Password Not Matched.</div>";
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
            
            
