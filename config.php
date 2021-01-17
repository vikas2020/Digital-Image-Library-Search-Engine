<?php
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "image_search";
    /* Create connection*/
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    /* Check connection*/
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $URL = 'http://localhost/cs518/search_engine/';

    

?>