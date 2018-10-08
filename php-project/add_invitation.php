<?php

echo '<meta charset="utf-8">';

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
    $as_who = $_POST['as-who'];
    $selectOption = $_POST['users'];

    if($as_who == "guest"){
        $guest = $selectOption;
        $representative = 0;
    }
    elseif ($as_who == "representative") {
        $guest = 0;
        $representative = $selectOption;
  }

    if($_POST['add-invitation'] == 0){

        $confirm_code = md5(uniqid(rand(), true));

        $sql = "INSERT INTO `invitations` ( `uid`, `date`, `firstname`, `lastname`, `firm`, `telephone`, `email`, `web`, `status`, `as_representative`, `as_guest`, `confirm_code`) VALUES (".$_POST['users'].",'".$_POST['meeting']."','".$_POST['first-name']."','".$_POST['surname']."','".$_POST['company']."','".$_POST['phone']."','".$_POST['email']."','".$_POST['homepage']."',0,".$representative.",".$guest.",'".$confirm_code."')";

        mysqli_query($conn, $sql);

        $sql = "SELECT MAX(idi) FROM invitations ";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        
        $maxID = $row['MAX(idi)'];

        $sql = "SELECT name,email FROM profiles WHERE uid=".$_POST['users'];
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();

        if($as_who == "guest"){
            $message = 
            '
            '.$row['name'].' hat Sie zu einem Dock B Netzwerktreffen eingeladen.

            Nutzen Sie bitte den folgenden Link, um Ihre Teilnahme am Treffen zu bestaetigen:
            https://dockb-hamburg.com/backend/WORKING/Filip/email_confirmation.php?idi=$maxID&code=$confirm_code

            Termin: '.$_POST['meeting'].'

            Ort: RAINVILLES ELBTERRASSEN, Rainvilleterrasse 4, 22765 Hamburg

            Bitte bringen Sie ca. 40 Visitenkarten und den Frühstückbeitrag in Höhe von 15 EUR passend in bar mit.';
        }
        elseif($as_who == "representative"){
            $message = 
            " 
            ".$row['name']." hat Sie zu einem Dock B Netzwerktreffen als Vertreter eingeladen.

            Nutzen Sie bitte den folgenden Link, um Ihre Teilnahme am Treffen zu bestaetigen:

            https://dockb-hamburg.com/backend/WORKING/Filip/email_confirmation.php?idi=$maxID&code=$confirm_code

            Termin: ".$_POST['meeting']."

            Ort: RAINVILLES ELBTERRASSEN, Rainvilleterrasse 4, 22765 Hamburg

            Bitte bringen Sie bitte ca. 40 Visitenkarten mit.";
        }

        mail($_POST['email'],"Einladung zum Dock B Netzwerktreffen",$message,"From: ".$row['email']);

        $repeat = false;

        $sql = "SELECT * FROM guests";
        $result = mysqli_query($conn,$sql);
        
        while($row = $result->fetch_assoc()){
            if($row['firstname'] == $_POST['first-name'] && $row['lastname'] == $_POST['surname'] && $row['firm'] == $_POST['company'] && $row['telephone'] == $_POST['phone'] && $row['email'] == $_POST['email'] && $row['web'] == $_POST['homepage']){
                $repeat = true;
            }
        }
        if($repeat == false){
            $sql = "INSERT INTO `guests` (`firstname`, `lastname`, `firm`, `telephone`, `email`, `web`) VALUES ('".$_POST['first-name']."','".$_POST['surname']."','".$_POST['company']."','".$_POST['phone']."','".$_POST['email']."','".$_POST['homepage']."')";
            if (mysqli_query($conn, $sql)) {
                //echo "insert guest successful";
            }
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    else{
        $sql = "UPDATE invitations SET uid='".$_POST['users']."', date='".$_POST['meeting']."', firstname='".$_POST['first-name']."', lastname='".$_POST['surname']."', firm='".$_POST['company']."', telephone='".$_POST['phone']."', email='".$_POST['email']."', web='".$_POST['homepage']."', as_representative='".$representative."', as_guest='".$guest."' WHERE idi = ".$_POST['add-invitation'];

        if (mysqli_query($conn, $sql)) {
            //echo "Update successful";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

echo '<form name="header" id="header" action="einladungen.php" method="post">';
echo '</form>';

if (isset($_POST['delete-button'])) {
  header("Location: ".$_SERVER['HTTP_REFERER'], true, 301);
  die();
}
?>

<html>
  <body>
    <script type="text/javascript">
        document.getElementById('header').submit(); // SUBMIT FORM
    </script>
  </body>
</html>