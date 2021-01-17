<?php
    // database connection
    include 'config.php';
    if(!session_id()){ session_start(); }
    if(isset($_SESSION['email_address'])){
        header('Location: '.$URL);
    }
?>
<?php  include 'header.php'; ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
        <div id="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <h1>Register</h1>
                        <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                            <div class="form-group">
                                <label class="text-white">First Name</label>
                                <input type="text" class="form-control" name="first_name" />
                            </div>
                            <div class="form-group">
                                <label class="text-white">Last Name</label>
                                <input type="text" class="form-control" name="last_name" />
                            </div>
                            <div class="form-group">
                                <label class="text-white">Email </label>
                                <input type="email" class="form-control" name="email" />
                            </div>
                            <div class="form-group">
                                <label class="text-white">Password</label>
                                <input type="password" class="form-control" name="password" />
                            </div>
                            <div class="form-group">
                                <label class="text-white">Mobile Number</label>
                                <input type="text" class="form-control" name="mobile" />
                            </div>
                            <div class="g-recaptcha" data-sitekey="<?php echo '6LeNPC4aAAAAAC0qGOaVTu4zostyBn-G8qXjGD0Y'; ?>"></div>
                            <input type="submit" class="btn btn-success" name="submit" value="Submit" />
                            <!-- <span class="already-have">Already Have an Account ? <a href="<?php //echo $URL.'login.php'; ?>">Login</a></span> -->
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

                                echo '<div class="message success">Success. Please check your email.
                                </div>';


                            }
                            
                       } }
                    }
                }
                // db close connection
                mysqli_close($conn);
            ?>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
</body>
</html>