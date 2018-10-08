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
    $sql = "SELECT * FROM users LEFT JOIN profiles ON users.idu = profiles.uid WHERE status = 1 ORDER BY lastname";

    $result = mysqli_query($conn, $sql);

    $usernames = array();
    $index = 0;

    while($row = $result->fetch_assoc()){
        $usernames[$index] = $row['username'];
        $ids[$index] = $row['idu'];
        $index++;
    }
}

if(isset($_POST['edit-button'])){
    $editFlag = $_POST['edit-button'];

    $edit_sql_val = "SELECT * FROM invitations WHERE idi = ".$editFlag;

    $result = mysqli_query($conn, $edit_sql_val);
    $row = $result->fetch_assoc();                                  

    $editValues = array("date" => $row['date'],
                    "firstname" => $row['firstname'],
                    "lastname" => $row['lastname'],
                    "firm" => $row['firm'],
                    "telephone" => $row['telephone'],
                    "email" => $row['email'],
                    "web" => $row['web']);
    }
    else{
        $editFlag = 0;
        $editValues = array("date" => "0000-00-00",
                          "firstname" => "",
                          "lastname" => "",
                          "firm" => "",
                          "telephone" => "",
                          "email" => "",
                          "web" => "");
    }
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DockB Hamburg - Backend - Einladungen</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="images/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="images/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Main -->
    <link href="css/main.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-xs-3 big-logo">
                    <img src="images/header/dockb_logo.png" alt="DockB Hamburg">
                </div>
            </div>
        </div>
    </header>
    <section id="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-3 side-menu">
                    <nav class="menu">
                        <ul>
                            <li><a href="mein_profil.php">Mein Profil</a></li>
                            <li class="active"><a href="einladungen.php">Einladungen</a></li>
                            <li><a href="gaesteliste.php">Gästeliste</a></li>
                            <li><a href="gaeste_archiv.php">Gäste Archiv</a></li>
                            <?php
                                $sql = "SELECT level FROM users WHERE idu=".$_SESSION['idu'];
                                $result = mysqli_query($conn,$sql);
                                $row = $result->fetch_assoc();

                                if($row['level'] == 3) echo '<li><a href="mitglieder.php">Mitglieder</a></li>';
                            
                                if($row['level'] == 2 || $row['level'] == 3){
                                    echo '<li><a href="daten_abrufen.php">Statistiken</a></li>';
                                }
                            ?>
                            
                            <li><a href="logout.php"><span><img src="images/menu/icon_logout.png" alt="Logout"></span>Logout</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-xs-9 contents">
                    <div class="main-window">
                        <h1 class="page-title">Einladungen</h1>
                        <img class="small-logo" src="images/content/dockb_logo_small.png" alt="DockB Hamburg">
                        <div id="inner-window">
                            <form name="add-invitation" action="add_invitation.php" method="post">
                                <div class="row">
                                    <div class="col-xs-3 form-radio">
                                        <label for="as-guest">Als Gast von</label>
                                        <input type="radio" id="as-guest" name="as-who" value="guest" checked="checked">
                                    </div>
                                    <div class="col-xs-4 form-radio">
                                        <label for="as-deputy">Als Vertretung für</label>
                                        <input type="radio" id="as-deputy" name="as-who" value="representative">
                                    </div>
                                    <div class="col-xs-5 form-window">
                                        <select name="users">
                                            <?php
                                            if(isset($_POST['edit-button'])){
                                                $sql = "SELECT uid FROM invitations WHERE idi=".$_POST['edit-button'];
                                                $result = mysqli_query($conn, $sql);
                                                $row = $result->fetch_assoc();
                                            }
                                            foreach ($usernames as &$username) {
                                                if($row['uid'] == $ids[array_search($username,$usernames)]){
                                                    echo '<option selected="selected" value="'.$ids[array_search($username,$usernames)].'">'.$username.'</option>';
                                                }
                                                else{
                                                    echo '<option value="'.$ids[array_search($username,$usernames)].'">'.$username.'</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label for="meeting-date">Datum des Treffens</label>
                                    </div>
                                    <div class="col-xs-4 col-xs-pull-1 form-window">
                                        <?php echo ' <input id="meeting-date" type="date" name="meeting" required value="'.$editValues['date'].'">';?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                        <?php echo ' <input type="text" name="first-name" placeholder="Vorname" maxlength="190" required value='.$editValues['firstname'].'>';?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                        <?php echo '  <input type="text" name="surname" placeholder="Nachname" maxlength="190" required value="'.$editValues['lastname'].'">';?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                        <?php echo '  <input type="text" name="company" placeholder="Firma" maxlength="190" value="'.$editValues['firm'].'">';?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                        <?php echo '   <input type="text" name="phone" placeholder="Telefon" value="'.$editValues['telephone'].'">';?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                        <?php echo '  <input type="email" name="email" placeholder="E-Mail" maxlength="300" required value="'.$editValues['email'].'">';?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                        <?php echo '  <input type="text" name="homepage" placeholder="Webseite" maxlength="190" value="'.$editValues['web'].'">';?>
                                    </div>
                                </div>
                                <div class="seat-button">
                                   <?php 
                                    echo '<button type="submit" name="add-invitation" class="btn send-button" value="'.$editFlag.'">Einladen</button>'; ?>
                                </div>
                            </form>
                        </div>
                        <h1 style="float: left; margin: 35px 0px 25px 55px;" class="window-title-outside">Meine Einladungen</h1>
                        <div id="inner-window">
                            <form method="post">
                            <?php
                              require_once "connect.php";

                              $conn = @new mysqli($host,$db_user,$db_password,$db_name);

                              if($conn->connect_errno!=0){
                                echo "error: ".$conn->connect_errno;
                              }else{
                                if (isset($_POST['delete-button'])) {
                                  $delete = "DELETE FROM invitations WHERE idi = ".$_POST['delete-button'];
                                  if (mysqli_query($conn, $delete)) {
                                    //echo "Record deleted succesfully";
                                  }else{
                                    //echo "Error deleting record: " . mysqli_error($conn);
                                  }
                                }
                                $sql = "SELECT * FROM invitations WHERE uid=".$_SESSION['idu']." ORDER BY date DESC";

                                $result = mysqli_query($conn, $sql); 

                                while($row = $result->fetch_assoc()){
                                echo '<div class="row invited '.$row['idi'].'">';
                                echo '  <div class="col-xs-3 date">';
                                echo '      <p>'.$row['date'].'</p>';
                                echo '  </div>';
                                echo '  <div class="col-xs-9 person">';
                                echo '      <div class="name">';
                                echo '          <p><strong>'.$row['firstname'].' '.$row['lastname'].'</strong></p>';
                                echo '      </div>';
                                echo '      <div class="company">';
                                echo '          <p>'.$row['firm'].'</p>';
                                echo '      </div>';
                                echo '      <div class="buttons">
                                                <button type="submit" name="edit-button" class="btn edit-button" value='.$row['idi'].'>Bearbeiten</button>
                                                <button type="submit" name="delete-button" class="btn delete-button" value='.$row['idi'].'>Löschen</button>
                                            </div>
                                        </div>
                                    </div>';
                                }
                              }
                            ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- footer -->
    <footer>
        <div class="footer-bottom-strap"></div>
    </footer>
    <!-- end footer -->
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Main -->
    <script src="js/main.js"></script>
</body>

</html>
