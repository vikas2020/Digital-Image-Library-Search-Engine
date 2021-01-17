<!-- db connection -->
<?php  include 'config.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Search</title>
    <link rel="stylesheet" href="css/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="main">
        <div id="header">
            <nav class="navbar navbar-expand-md">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo $URL; ?>">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="advancesearch.php">Advanced</a>
            </li>
            
        </ul>
    </div>
    
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav right ml-auto">
            
            <?php
                  if(!session_id()){ session_start(); }
                if(isset($_SESSION['email_address'])){ ?>
                    <li class="nav-item name">
                        <?php  echo 'Hi, '.$_SESSION['first_name']; ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $URL.'profile.php'; ?>">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $URL.'logout.php'; ?>">Logout</a>
                    </li>
                <?php }else{ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $URL.'login.php'; ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $URL.'register.php'; ?>">Register</a>
                    </li>
                <?php } ?>
        </ul>
    </div>
</nav>

            <!-- <div class="header-links">
            <?php
                  //session_start();
                if(isset($_SESSION['email_address'])){ ?>
                    <span>Hi, <?php echo $_SESSION['first_name']; ?> </span> |
                    <a href="<?php echo $URL.'profile.php'; ?>">Profile</a> |
                    <a href="<?php echo $URL.'logout.php'; ?>">Logout</a>
                <?php }else{ ?>
                    <a href="<?php echo $URL.'login.php'; ?>">Login</a> |
                    <a href="<?php echo $URL.'register.php'; ?>">Register</a>
                <?php } ?>            
            </div> -->
        </div>