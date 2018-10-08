<?php

session_start();


if(!isset($_SESSION['online_user'])){
  	header('Location: login.php');
  	exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

if($_POST['delete-user']){

	$id = $_POST['delete-user'];

	$sql = "DELETE FROM users WHERE idu=".$id;
	mysqli_query($conn,$sql);


	$sql = "DELETE FROM profiles WHERE uid=".$id;
	mysqli_query($conn,$sql);

	header("Location: ".$_SERVER['HTTP_REFERER'], true, 301);
	die();
}
if($_POST['deactivate-user']){

	$id = $_POST['deactivate-user'];

	$sql = "SELECT status FROM users WHERE idu = ".$id;
	$result = mysqli_query($conn,$sql);
	$row = $result->fetch_assoc();

	if($row['status'] == 0) $status = 1;
	elseif($row['status'] == 1) $status = 0;

	$sql = "UPDATE users SET status = ".$status." WHERE idu = ".$id;
	mysqli_query($conn,$sql);

	header("Location: ".$_SERVER['HTTP_REFERER'], true, 301);
	die();
}
if($_POST['edit-user']){

	$id = $_POST['edit-user'];

	header("Location: erstellen.php?id=".$id);
}

?>