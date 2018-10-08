<?php
session_start();

if(!isset($_SESSION['online_user'])){
    header('Location: login.php');
    exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

$sql = "SELECT * FROM users LEFT JOIN profiles ON users.idu = profiles.uid ORDER BY lastname";

$result = mysqli_query($conn, $sql);

$usernames = array();

while($row = $result->fetch_assoc()){
    $usernames[$row['idu']] = $row['username'];
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DockB Hamburg - Backend - Mitglieder</title>
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
                            <li><a href="einladungen.php">Einladungen</a></li>
                            <li><a href="gaesteliste.php">Gästeliste</a></li>
                            <li><a href="gaeste_archiv.php">Gäste Archiv</a></li>
                            <?php
                                $sql = "SELECT level FROM users WHERE idu=".$_SESSION['idu'];
                                $result = mysqli_query($conn,$sql);
                                $row = $result->fetch_assoc();

                                if($row['level'] == 3){
                                 echo '<li class="active">
                                            <a href="#">Mitglieder</a>
                                            <ul class="drop">
                                                <li class="drop-in"><a href="erstellen.php">Erstellen</a></li>
                                            </ul>
                                        </li>';
                                }                
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
                        <h1 class="page-title">Mitglieder</h1>
                        <img class="small-logo" src="images/content/dockb_logo_small.png" alt="DockB Hamburg">
                        <?php
                        foreach($usernames as $idu => $username){
                            $sql = "SELECT * FROM users LEFT JOIN profiles ON users.idu = profiles.uid WHERE idu = ".$idu;
                            $result = mysqli_query($conn,$sql);
                            $row = $result->fetch_assoc();

                            $firm = $row['firm'];
                            $portrait = $row['portrait'];
                            $since = $row['since'];
                            $last_login = $row['last_login'];
                            $portrait = $row['portrait'];
                            $status = $row['status'];

                            $sql = "SELECT SUM(guest) FROM statistics WHERE uid = ".$idu;
                            $result = mysqli_query($conn,$sql);
                            $row = $result->fetch_assoc();

                            $guestInvited = $row['SUM(guest)'];

                         echo '<div id="inner-window">
                                    <p class="member-name">'.$username.'</p>
                                    <p class="member-company">'.$firm.'</p>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <img class="member-pic" src="images/profiles/portraits/'.$portrait.'" alt="'.$username.'">
                                        </div>
                                        <div class="col-xs-8">
                                            <p class="member-info">Mitglied seit dem <strong>'.$since.'</strong></p>
                                            <p class="member-info">Zuletzt an
                                            emeldet am <strong>'.$last_login.'</strong></p>
                                            <p class="member-info">Gäste eingeladen: <strong>'.$guestInvited.'</strong></p>
                                            <form action="edit_users.php" method="post" class="buttons">
                                                <button type="submit" name="delete-user" class="btn btn-danger" value="'.$idu.'">Löschen</button>';
                                                if($status == 1){
                                        echo   '<button type="submit" name="deactivate-user" class="btn btn-warning" value="'.$idu.'">Deaktivieren</button>';
                                                }
                                                elseif($status == 0){
                                        echo   '<button type="submit" name="deactivate-user" class="btn btn-warning" value="'.$idu.'">Aktivieren</button>';
                                                }
                                        echo   '<button type="submit" name="edit-user" class="btn btn-primary" value="'.$idu.'">Bearbeiten</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>';
                        }

                        ?>
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
