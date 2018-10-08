<?php

session_start();

if(!isset($_SESSION['online_user']))
{
    header('Location: login.php');
    exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);
?>


<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DockB Hamburg - Backend - Gästeliste</title>
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
                            <li class="active"><a href="gaesteliste.php">Gästeliste</a></li>
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
                        <h1 class="page-title">Gästeliste für kommenden Freitag</h1>
                        <img class="small-logo" src="images/content/dockb_logo_small.png" alt="DockB Hamburg">
                        <div id="inner-window">
                            <form action="" method="post">
                                <div class="row choice-term">
                                    <div class="col-xs-3">
                                        <label for="term-date">Termin wählen:</label>
                                    </div>
                                    <div class="col-xs-5 form-window">
                                        <?php

                                            require_once "connect.php";
                                            $conn = @new mysqli($host,$db_user,$db_password,$db_name);
          
                                            if(!isset($_POST['show-guest-list'])){
                                                $sql = "SELECT MAX(date) FROM `invitations`";

                                                $result = mysqli_query($conn, $sql);
                                                $row = $result->fetch_assoc();

                                                $date = $row['MAX(date)'];
                                                echo '<input id="term-date" type="date" name="term" value="'.$date.'">';
                                            }else{
                                                echo '<input id="term-date" type="date" name="term" >';
                                            }
                                        ?>
                                    </div>
                                    <div class="col-xs-4">
                                        <button type="submit" name="show-guest-list" class="btn show-button">Anzeigen</button>
                                    </div>
                                </div>
                            </form>
                            <?php


                                  if($conn->connect_errno!=0)
                                  {
                                    echo "error: ".$conn->connect_errno;
                                  }
                                  else{
                                    if(isset($_POST['term'])){
                                        $date = $_POST['term'];
                                    }
                                    $sql = "SELECT * FROM invitations WHERE date='".$date."' ORDER BY idi";

                                    $result = mysqli_query($conn, $sql); 

                                    while($row = $result->fetch_assoc())
                                    {
                                    echo '<div class="row invited '.$row['idi'].'">';
                                    echo '    <div class="col-xs-12 person">';
                                    echo '        <div class="name">';
                                    echo '            <p><strong>'.$row['firstname'].' '.$row['lastname'].'</strong></p>';
                                    echo '        </div>';
                                    echo '        <div class="company">';
                                    echo '            <p>'.$row['firm'].'</p>';
                                    echo '        </div>';
                                    if($row['as_guest'] != 0){

                                    $sql1 = "SELECT * FROM profiles WHERE uid=".$row['as_guest'];

                                    $result1 = mysqli_query($conn, $sql1); 
                                    $row1 = $result1->fetch_assoc();

                                    echo '        <div class="invited-by">';
                                    echo '            <p>'.$row1['name'].'</p>';
                                    echo '        </div>';
                                    }
                                    if($row['as_representative'] != 0){
                                    
                                    $sql1 = "SELECT * FROM profiles WHERE uid=".$row['as_representative'];

                                    $result1 = mysqli_query($conn, $sql1); 
                                    $row1 = $result1->fetch_assoc();

                                    echo '        <div class="deputy-for">';
                                    echo '            <p>'.$row1['name'].'</p>';
                                    echo '        </div>';
                                    }
                                    if($row['status'] == 0){
                                    echo '        <div id="status" class="unconfirmed">';
                                    echo '            <p></p>';
                                    echo '        </div>';
                                    }
                                    else{
                                    echo '        <div id="status" class="confirmed">';
                                    echo '            <p></p>';
                                    echo '        </div>'; 
                                    }
                                    echo '    </div>';
                                    echo '</div>';
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
