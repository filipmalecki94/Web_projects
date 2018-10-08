<?php

session_start();


if(!isset($_SESSION['online_user'])){
  header('Location: login.php');
  exit();
}


require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

$idu = $_POST['save'];
$username = $_POST['user-name'];
$password = md5($_POST['current-password']);
$email = $_POST['email'];
$level = $_POST['account-type'];

if(empty($_POST['current-password'])){
	$sql = "UPDATE users SET username = '$username' , email = '$email' , level = $level WHERE idu = $idu";
}
else{
	$sql = "UPDATE users SET username = '$username' , password = '$password' , email = '$email' , level = $level WHERE idu = $idu";
}

if (mysqli_query($conn, $sql)) {
    //echo "Update successful";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$name = $_POST['name'];
$firm = $_POST['company'];
$function = $_POST['position'];
$street = $_POST['street'];
$number = $_POST['number'];
$postalcode = $_POST['postcode'];
$city = $_POST['place'];
$telephone = $_POST['phone'];
$mobile = $_POST['mobile'];
$email = $_POST['email1'];
$web = $_POST['homepage'];
$summary = $_POST['summary'];
$description = htmlentities($_POST['description'], ENT_QUOTES, "UTF-8");

$sql = "UPDATE profiles SET name = '$name' , firm = '$firm' , function = '$function' , street = '$street' , number = '$number' , postalcode = '$postalcode' , city = '$city' , telephone = '$telephone' , mobile = '$mobile' , email = '$email' , web = '$web' , summary = '$summary' , description = '$description' WHERE uid = $idu";

if (mysqli_query($conn, $sql)) {
    //echo "Update successful";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

  header("Location: ".$_SERVER['HTTP_REFERER'], true, 301);
  die();
?>
