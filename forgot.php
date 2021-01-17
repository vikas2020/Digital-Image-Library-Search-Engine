<!-- db connection -->
<?php include 'config.php';?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Search</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="main">
        <div id="header">
            <a href="<?php echo $URL; ?>">Home</a>
        </div>
        <div id="content">
            <h1>Forgot Password</h1>
            <form class="loginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="form-group">
                    <label>Email / Username</label>
                    <input type="email" name="username" />
                </div>
                <input type="submit" name="submit" class="btn" value="Submit" />
            </form>
            <?php
                if(isset($_POST["submit"])){
                    if(!isset($_POST['username']) || $_POST['username'] == ''){
                        echo '<div class="message error"> Email is Required. </div>';
                    }else{
                        $email = $_POST['username'];
                            // 
                        $sql = "SELECT email_address FROM users WHERE email_address = '$email'";
                        $result = mysqli_query($conn,$sql);

                        if (mysqli_num_rows($result) > 0) {
                            /* Start the session */
                                session_start();
                                /* Set session variables */
                                $_SESSION["forgot_pass"] = $email;
                                header("location: {$URL}new_pass.php");

                        }else{
                           echo "<div class='message error'>Email Address does not Exist.</div>";
                        }
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>

