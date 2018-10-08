<?php
session_start();

if(!isset($_SESSION['online_user'])){
  header('Location: login.php');
  exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

$uploadOk = 1;
$imageFileType = strtolower(pathinfo(basename($_FILES["image_portrait"]["name"]),PATHINFO_EXTENSION));

$target_dir = "images/profiles/portraits/";
$filename =  $_POST['upload_portrait'] .".". $imageFileType;
$target_file = $target_dir . $filename;

// Check if image file is a actual image or fake image
if(isset($_POST["upload_portrait"])) {
    $check = getimagesize($_FILES["image_portrait"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check file size
if ($_FILES["image_portrait"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
      unlink($target_file);
    if (move_uploaded_file($_FILES["image_portrait"]["tmp_name"], $target_file)) {
      // if($_POST['upload_portrait'] == $_SESSION['idu']){
      //   $_SESSION['portrait'] = $filename;
      // }
      $sql = "UPDATE profiles SET portrait='".$filename."' WHERE uid=".$_POST['upload_portrait'];
      mysqli_query($conn, $sql);
        //echo "The file ". basename( $_FILES["image_portrait"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

  header("Location: ".$_SERVER['HTTP_REFERER'], true, 301);
  die();


?>