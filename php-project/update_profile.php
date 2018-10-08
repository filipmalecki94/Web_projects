<?php
session_start();

if(!isset($_SESSION['online_user'])){
    header('Location: login.php');
    exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

if($conn->connect_errno!=0){
 // echo "error: ".$conn->connect_errno;
}else{
    $name = $_SESSION['name'] = $_POST['name'];
    $firm = $_SESSION['firm'] = $_POST['firm'];
    $function = $_SESSION['function'] = $_POST['function'];
    $street = $_SESSION['street'] = $_POST['street'];
    $number = $_SESSION['number'] = $_POST['number'];
    $postalcode = $_SESSION['postalcode'] = $_POST['postalcode'];
    $city = $_SESSION['city'] = $_POST['city'];
    $telephone = $_SESSION['telephone'] = $_POST['telephone'];
    $mobile = $_SESSION['mobile'] = $_POST['mobile'];
    $email = $_SESSION['email'] = $_POST['email'];
    $web = $_SESSION['web'] = $_POST['web'];
    $summary = $_SESSION['summary'] = $_POST['summary'];
    $description = $_SESSION['description'] = $_POST['description'];

    $sql = 'UPDATE profiles
          SET name="'.$name.'", firm="'.$firm.'", function="'.$function.'", street="'.$street.'", number="'.$number.'", postalcode="'.$postalcode.'", city="'.$city.'", telephone="'.$telephone.'", mobile="'.$mobile.'", email="'.$email.'", web="'.$web.'", summary="'.$summary.'", description="'.$description.'" WHERE uid='.$_SESSION['uid'];

    if ($conn->query($sql) === TRUE) {
    //     echo "Record updated successfully";
    } 
    else {
        echo "Error updating record: " . $conn->error;
    }
}
header('Location: mein_profil.php');
?>