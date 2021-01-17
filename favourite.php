<?php 
include 'config.php';
if(isset($_POST['fav_id'])){
	$id = $_POST['fav_id'];
	if(!session_id()) session_start();
	$sql = "SELECT * FROM favourites WHERE user_id='{$_SESSION['user']}' AND item_id='{$id}'";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result) == 0){
		$insert = "INSERT INTO favourites(user_id,item_id) VALUES('{$_SESSION['user']}','{$id}')";
		$result1 = mysqli_query($conn,$insert);
		if($result1){ echo 'true'; exit; }
	}

}
if(isset($_POST['remove_fav'])){
	$id = $_POST['remove_fav'];
	if(!session_id()) session_start();
	$sql = "DELETE FROM favourites WHERE user_id='{$_SESSION['user']}' AND item_id='{$id}'";
	$result = mysqli_query($conn,$sql);
	if($result){ echo 'true'; exit; }
}
 ?>

