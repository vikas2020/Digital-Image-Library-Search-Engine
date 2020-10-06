<?php
    // db connection
    include 'config.php';

    /* Start the session */
    session_start();
      // check session exists
    if(!isset($_SESSION['email_address'])){
        header("location: :".$URL."login.php");
    }
?>
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
        <div class="header-links">
            <span>Hi, <?php echo $_SESSION['first_name']; ?> </span> |
            <a href="<?php echo $URL.'profile.php'; ?>">Profile</a> |
            <a href="<?php echo $URL.'logout.php'; ?>">Logout</a>
        </div>
    </div>
    <div id="content">
        <h1>Modify</h1>
        <?php
        $user = $_SESSION['email_address'];

        $sql = "SELECT * FROM users WHERE email_address ='".$user."'";
        $result = mysqli_query($conn,$sql);

        ?>
            <form class="loginForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                <?php
                while($row = mysqli_fetch_assoc($result)){ ?>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="<?php echo $row['first_name']; ?>"/>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="<?php echo $row['last_name']; ?>"/>
                </div>
                
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile" value="<?php echo $row['mobile']; ?>"/>
                </div>
                <input type="submit" class="btn" name="update" value="Update" />
            </form>
            <?php } ?>
            <?php

                if(isset($_POST["update"])){
                    $m = is_int($_POST['mobile']);
                    if($_POST['first_name'] == ''){

                       echo '<div class="message error">Please Fill All The Fields.</div>';

                    }else if($_POST['last_name'] == ''){

                        echo '<div class="message error">Please Fill All The Fields.</div>';

                    }else if($_POST['mobile'] == '' || $m){

                        echo '<div class="message error">Please Fill Correct Number.</div>';

                    }else{

                        $first_name = $_POST['first_name'];
                        $last_name = $_POST['last_name'];
                        $mobile = $_POST['mobile'];
                        $email = $_SESSION['email_address'];
                            
                        $sql1 = "UPDATE users SET first_name = '".$first_name."', last_name = '".$last_name."', mobile=".$mobile." WHERE email_address='".$email."'";

                        // echo $sql1; exit;
                        $result = mysqli_query($conn,$sql1);
                        session_start();
                        $_SESSION['first_name'] = $first_name;

                        header("Location: ".$URL."profile.php");
                            
                    }
                }
                // db close connection
                mysqli_close($conn);
            ?>
        </div>
    </div>
</body>
</html>
       
        