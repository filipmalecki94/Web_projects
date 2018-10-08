<?php

session_start();

if(!isset($_SESSION['online_user'])){
    header('Location: login.php');
    exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

if($conn->connect_errno!=0){
    echo "error: ".$conn->connect_errno;
}
else{

    $sql = "SELECT password FROM users WHERE idu=".$_SESSION['idu'];
    $result = mysqli_query($conn, $sql); 
    $row = $result->fetch_assoc();

    $password = $row['password'];
    $newPassword = md5($_POST['new-password']);
    $repeatNewPassword = md5($_POST['new-password-repeat']);

    if(md5($_POST['current-password']) == $password){

        $sql = 'UPDATE users SET password="'.$newPassword.'" WHERE idu='.$_SESSION['idu'];

        if ($conn->query($sql) === TRUE) {
            //echo "Record updated successfully";
        }
        else{
            echo "Error updating record: " . $conn->error;
        }
    
    }
    header('Location: mein_profil.php');
}
?>