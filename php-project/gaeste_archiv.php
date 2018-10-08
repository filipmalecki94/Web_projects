<?php

session_start();

if(!isset($_SESSION['online_user']))
{
    header('Location: login.php');
    exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

?>


<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DockB Hamburg - Backend - Gäste Archiv</title>
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
                            <li class="active"><a href="gaeste_archiv.php">Gäste Archiv</a></li>
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
                        <h1 class="page-title">Gäste Archiv</h1>
                        <img class="small-logo" src="images/content/dockb_logo_small.png" alt="DockB Hamburg">
                        <div id="inner-window">
                            <?php
                              require_once "connect.php";

                              $conn = @new mysqli($host,$db_user,$db_password,$db_name);

                              if($conn->connect_errno!=0){
                                echo "error: ".$conn->connect_errno;
                              }
                              else{
                                $sql = "SELECT * FROM guests ORDER BY lastname";

                                $result = mysqli_query($conn, $sql); 

                                while($row = $result->fetch_assoc()){
                                  echo '    <form class="row invited" action="delete_guest.php" method="post">
                                                <div class="col-xs-12 person">
                                                    <div class="name">
                                                        <p><strong>'.$row['firstname'].' '.$row['lastname'].'</strong></p>
                                                    </div>
                                                    <div class="company">
                                                        <p>'.$row['firm'].'</p>
                                                    </div>
                                                    <div class="phone">
                                                        <p>'.$row['telephone'].'</p>
                                                    </div>
                                                    <div class="e-mail">
                                                        <span></span><a href="'.$row['email'].'">'.$row['email'].'</a>
                                                    </div>
                                                    <div class="web">
                                                        
                                                        <div style="padding: 0px;" class="buttons">
                                                        <span></span><a href="'.addhttp($row['web']).'">'.$row['web'].'</a>
                                                        
                                                        <button style="position: relative;float: right;" type="submit" name="delete-button" class="btn delete-button" value="'.$row['idg'].'">Löschen</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>';
                                }

                              }
                            ?>
                            <div class="no-guests">
                                <p>Es wurden keine Gäste eingeladen</p>
                            </div>
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
