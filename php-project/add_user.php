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
$status = 1;
$level = $_POST['account-type'];
$since = date("Y-m-d");
$last_login = "0000-00-00";

$sql = "INSERT INTO users (idu, username, password, email, status, level, since, last_login) VALUES ($idu , '$username' , '$password' , '$email' , $status , $level , '$since' , '$last_login')";

if (!mysqli_query($conn, $sql)) {
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
$description = $_POST['description'];

list($firstName,$lastName) = explode(' ',$name);


$sql = "INSERT INTO profiles (uid, name, firstname, lastname, firm, function, street, number, postalcode, city, telephone, mobile, email, web, summary, description) VALUES 
		($idu , '$name' , '$firstName', '$lastName', '$firm' , '$function' , '$street' , '$number' , '$postalcode', '$city' , '$telephone' , '$mobile' , '$email' , '$web' , '$summary' , '$description')";

if (!mysqli_query($conn, $sql)) {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

header("Location: ".$_SERVER['HTTP_REFERER']."?id=".$idu, true, 301);
die();
?>