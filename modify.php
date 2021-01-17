<?php
    // db connection
    include 'config.php';

    /* Start the session */
    session_start();
      // check session exists
    if(!isset($_SESSION['email_address'])){
        header("location: :".$URL."login.php");
    }
    include 'header.php';
?>

    <div id="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h1>Modify</h1>
        <?php
        $user = $_SESSION['email_address'];

        $sql = "SELECT * FROM users WHERE email_address ='".$user."'";
        $result = mysqli_query($conn,$sql);

        ?>
            <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                <?php
                while($row = mysqli_fetch_assoc($result)){ ?>
                <div class="form-group">
                    <label class="text-white">First Name</label>
                    <input type="text" class="form-control" name="first_name" value="<?php echo $row['first_name']; ?>"/>
                </div>
                <div class="form-group">
                    <label class="text-white">Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="<?php echo $row['last_name']; ?>"/>
                </div>
                
                <div class="form-group">
                    <label class="text-white">Mobile Number</label>
                    <input type="text" class="form-control" name="mobile" value="<?php echo $row['mobile']; ?>"/>
                </div>
                <input type="submit" class="btn btn-success" name="update" value="Update" />
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
                        if(!session_id()) session_start();
                        $_SESSION['first_name'] = $first_name;

                        header("Location: ".$URL."profile.php");
                            
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
       
        