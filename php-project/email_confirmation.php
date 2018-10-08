<?php

session_start();


if(!isset($_SESSION['online_user'])){
  header('Location: login.php');
  exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

$idi = $_GET['idi'];
$code = $_GET['code'];

$sql = "SELECT * FROM invitations WHERE idi = ".$idi;

$result = mysqli_query($conn, $sql);
$row = $result->fetch_assoc();

$db_code = $row['confirm_code'];

if($code == $db_code){
	$sql = "UPDATE invitations SET status=1 WHERE idi=".$idi;

    if (mysqli_query($conn, $sql)) {

    }else{
    	echo "Error: " . $sql . "<br>" . $conn->error;
    }
}else{
	echo "Error: " . $sql . "<br>" . $conn->error;
}

header('Location: https://dockb-hamburg.com');
die();
?>