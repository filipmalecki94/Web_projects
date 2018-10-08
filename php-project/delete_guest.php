<?php

session_start();


if(!isset($_SESSION['online_user'])){
  header('Location: login.php');
  exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

$delete = "DELETE FROM guests WHERE idg = ".$_POST['delete-button'];

if (mysqli_query($conn, $delete)) {
	//echo "Record deleted succesfully";
}
else{
	echo "Error deleting record: " . mysqli_error($conn);
}

  	header("Location: ".$_SERVER['HTTP_REFERER'], true, 301);
  	die();
?>