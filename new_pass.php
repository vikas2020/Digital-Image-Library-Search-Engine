<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Image Search</title>
  <!-- style.css -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <!-- main -->
	<div id="main">
    <!-- header -->
		<div id="header">
			<a href="<?php echo $URL; ?>">Home</a>
		</div>
    <!-- / header -->
    <!-- content -->
		<div id="content">
      <h1>New Password</h1>
     <?php
      // db connection
      include 'config.php'; ?>

        <form class="loginForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
          <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_pass" />
          </div>
          <input type="submit" name="change" class="btn" value="Submit" />
        </form>
        <?php

        if(isset($_POST['change'])){
          if( $_POST['new_pass'] == ''){
            echo '<div class="message error">Please Enter The New Password.</div>';
          }else{
            $new_pass = md5($_POST['new_pass']);
            session_start();
            $email = $_SESSION['forgot_pass'];

              // update passord
            $sql2 = 'UPDATE users SET password="'.$new_pass.'" WHERE email_address="'.$email.'"';
            // echo $sql2; exit;
            $result2 = mysqli_query($conn, $sql2);
            if($result2 == '1'){
              // select email address to show message
              
                echo '<div class="message success">Password Updated.</div>
                  <a href="'.$URL.'login.php" class="to-login">Go To Login</a>';
                session_unset();
                session_destroy();
              
            }
          }
        }
      
      
      // close db connection
        mysqli_close($conn);
      ?>
		</div>
    <!-- / content -->
	</div>
  <!-- / main -->

</body>
</html>
