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
            <span>Hello, <?php echo $_SESSION['first_name']; ?> </span> |
            <a href="<?php echo $URL.'profile.php'; ?>">Profile</a> |
            <a href="<?php echo $URL.'logout.php'; ?>">Logout</a>
        </div>
    </div>
    <div id="content">
        <h1>Profile</h1>
        <?php
        $user = $_SESSION['email_address'];

        $sql = "SELECT * FROM users WHERE email_address ='".$user."'";
        $result = mysqli_query($conn,$sql);

        ?>
        <table width="400px" border="1px">
            <?php
                while($row = mysqli_fetch_assoc($result)){ ?>
                <tr>
                    <td>Name</td>
                    <td><?php echo $row['first_name']. ' ' .$row['last_name']; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo $row['email_address']; ?></td>
                </tr>
                <tr>
                    <td>Mobile</td>
                    <td><?php echo $row['mobile']; ?></td>
                </tr>
            <?php } ?>
            
            
        </table>
        <span class="modify"><a class="btn" href="modify.php">Modify Details</a></span>
    </div>
  </div>
  </body>
</html>
    



