<?php 
include 'config.php';

if(isset($_POST['fav_id'])){
	$id = $_POST['fav_id'];
	if(!session_id()) session_start();
	$sql = "SELECT * FROM likes WHERE user_id='{$_SESSION['user']}' AND item_id='{$id}'";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result) == 0){
		$insert = "INSERT INTO likes(user_id,item_id) VALUES('{$_SESSION['user']}','{$id}')";
		$result1 = mysqli_query($conn,$insert);
		if($result1){ echo 'true'; exit; }
	}else{
		$delete = "DELETE FROM likes WHERE user_id='{$_SESSION['user']}' AND item_id='{$id}'";
		$result1 = mysqli_query($conn,$delete);
		if($result1){ echo 'true'; exit; }
	}
}


 ?>

