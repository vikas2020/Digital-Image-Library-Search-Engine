<!-- db connection -->
<?php  include 'config.php'; ?>
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
            <div class="header-links">
            <?php
                       
                  session_start();
                if(isset($_SESSION['email_address'])){ ?>
                    <span>Hi, <?php echo $_SESSION['first_name']; ?> </span> |
                    <a href="<?php echo $URL.'profile.php'; ?>">Profile</a> |
                    <a href="<?php echo $URL.'logout.php'; ?>">Logout</a>
               
                <?php }else{ ?>
                    <a href="<?php echo $URL.'login.php'; ?>">Login</a> |
                    <a href="<?php echo $URL.'register.php'; ?>">Register</a>
                <?php } ?>            
            </div>
        </div>
        <div id="content">
            <h1>Image Search</h1>
            <form class="searchBox">
                <input type="" name="">
                <button class="btn">Search</button>
            </form>
        </div>
    </div>
</body>
</html>
            
            
