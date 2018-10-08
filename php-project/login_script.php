<?php

session_start();


if(!isset($_POST['login']) || !isset($_POST['password'])){
	header('Location: login.php');
	exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

if($conn->connect_errno!=0){
	echo "error: ".$conn->connect_errno;
}else{
	$login = $_POST['login'];
	$password = md5($_POST['password']);

	$login = htmlentities($login, ENT_QUOTES, "UTF-8");
	$password = htmlentities($password, ENT_QUOTES, "UTF-8");

	if($result = @$conn->query(
	sprintf("SELECT * FROM users WHERE username='%s' AND password='%s'",
	mysqli_real_escape_string($conn,$login),
	mysqli_real_escape_string($conn,$password)))){
		$count = $result->num_rows;

		if($count > 0){
			$_SESSION['online_user'] = true;

			$row = $result->fetch_assoc();

			$_SESSION['idu'] = $row['idu'];

			unset($_SESSION['error']);
			$result->close();

			$sql = "SELECT * FROM profiles WHERE uid=".$_SESSION['idu'];

		  $result = mysqli_query($conn, $sql); 

		  $row = $result->fetch_assoc();

	    $_SESSION['idp'] = $row['idp'];
		$_SESSION['uid'] = $row['uid'];	
		$_SESSION['name'] = $row['name'];
		$_SESSION['firm'] = $row['firm'];
		$_SESSION['function'] = $row['function'];
		$_SESSION['street'] = $row['street'];
		$_SESSION['number'] = $row['number'];
		$_SESSION['postalcode'] = $row['postalcode'];
		$_SESSION['city'] = $row['city'];
		$_SESSION['telephone'] = $row['telephone'];
		$_SESSION['mobile'] = $row['mobile'];
		$_SESSION['email'] = $row['email'];
		$_SESSION['web'] = $row['web'];
		$_SESSION['summary'] = $row['summary'];
		$_SESSION['description'] = $row['description'];
	  	$_SESSION['logo'] = $row['logo'];
	  	$_SESSION['portrait'] = $row['portrait'];
	  	$_SESSION['status'] = $row['status'];
	  	$_SESSION['level'] = $row['level'];
		$_SESSION['logo_path'] = "images/profiles/logos/";
	  	$_SESSION['portrait_path'] = "images/profiles/portraits/";

	  	$sql = "UPDATE users SET last_login = '".date("Y-m-d H:i:s")."' WHERE idu = ".$_SESSION['idu'];
	  	mysqli_query($conn, $sql);

		header('Location: mein_profil.php');
		}else{
			
			if(empty($login)){
				$_SESSION['error'] = '<p style="color:red">Bitte geben Sie Ihren Benutzernamen ein</p>';
			}
			elseif(empty($password)){
				$_SESSION['error'] = '<p style="color:red">Bitte geben Sie ein Passwort ein</p>';
			}
			else{
				$_SESSION['error'] = '<p style="color:red">Unbekannte Benutzernamen-/Passwort-Kombination</p>';
			}

			unset($login);
			unset($password);

			header('Location: login.php');
		}
	}
}
?>