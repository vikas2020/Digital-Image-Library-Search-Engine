<?php
    // database connection
    include 'config.php';
    if(!session_id()){ session_start(); }
    if(isset($_SESSION['email_address'])){
        header('Location: '.$URL);
    }
?>
<?php include 'header.php'; ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
        <div id="content">
            <div class="container"> 
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <h1>Login</h1>
                        <form  class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                            <div class="form-group">
                                <label class="text-white">Email Address</label>
                                <input type="email" class="form-control" name="username" />
                            </div>
                            <div class="form-group">
                                <label class="text-white">password</label>
                                <input type="password" class="form-control" name="password" />
                            </div>
                            <div class="g-recaptcha" data-sitekey="<?php echo '6LeNPC4aAAAAAC0qGOaVTu4zostyBn-G8qXjGD0Y'; ?>"></div>
                            <input type="submit" class="btn btn-success" name="login" value="Login" />
                            <!-- <span class="dont-have"><a href="<?php //echo $URL.'register.php' ?>">Register</a></span> -->
                            <!-- <span class="forgot"><a href="<?php //echo $URL.'forgot.php' ?>">Forgot Password</a></span> -->
                        </form>
                    </div>
                </div>
            </div>  
            
            
            <?php
                if(isset($_POST['login'])){
                    
                    if(  $_POST['username'] == ''){

                       echo '<div class="message error">Please Fill All The Fields.</div>';

                    }else if( $_POST['password'] == ''){

                        echo '<div class="message error">Please Fill All The Fields.</div>';

                    }else if(!isset($_POST['g-recaptcha-response'])){
                        echo '<div class="message error">Fill The Recaptcha.</div>';
                    }else{
                        //reCAPTCHA validation
                    // Google reCAPTCHA API secret key 
                        $secretKey = '6LeNPC4aAAAAAEQR3_36RC9X_Ze0XdDyQNP2_pCy'; 
                         
                        // Verify the reCAPTCHA response 
                        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']); 
                         
                        // Decode json data 
                        $responseData = json_decode($verifyResponse); 
                        if(empty($responseData->success)){
                            echo '<div class="message error">Fill The Recaptcha.</div>';
                        }else{
                            $username = $_POST["username"];
                            $password = md5($_POST["password"]);
                                // check username and password
                            $sql = "SELECT first_name,id,email_address FROM users WHERE email_address = '$username' AND  password = '$password'";
                            // echo $sql; exit;
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {

                                while($row = mysqli_fetch_assoc($result)) {
                                    /* Start the session */
                                    if(!session_id()){
                                        session_start();
                                    }
                                    
                                    /* Set session variables */
                                    $_SESSION["user"] = $row['id'];
                                    $_SESSION["first_name"] = $row['first_name'];
                                    $_SESSION["email_address"] = $row['email_address'];

                                    header("location: {$URL}");
                                }

                            }else{
                                echo "<div class='message error'>Email and Password Not Matched.</div>";
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
            
            
