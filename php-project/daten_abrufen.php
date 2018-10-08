<?php

session_start();

if(!isset($_SESSION['online_user'])){
    header('Location: login.php');
    exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

$sql = "SELECT DISTINCT year FROM statistics ORDER BY year DESC";
$result = mysqli_query($conn, $sql);

$years = array();
$index = 0;

while($row = $result->fetch_assoc()){
    $years[$index] = utf8_encode($row['year']);
    $index++;
}

if(!isset($_POST['period'])){
    $_POST['period'] = $_GET['from'];
}

if(isset($_GET['years']) && isset($_POST['period'])){

    $sql = "SELECT SUM(present), SUM(absent), SUM(guest) FROM statistics WHERE year = ".$_GET['years']." AND kw = ".$_POST['period'];

    $result = mysqli_query($conn,$sql);

    $row = $result->fetch_assoc();

    $present = $row['SUM(present)'];
    $absent = $row['SUM(absent)'];
    $guest = $row['SUM(guest)'];
    
    $gap = 1;

    do{
        $sql = "SELECT COUNT(kw), SUM(present), SUM(absent), SUM(guest) FROM statistics WHERE year=".$_GET['years']." AND kw=".$_POST['period']."-".$gap;
        $result = mysqli_query($conn,$sql);
        $row1 = $result->fetch_assoc();

        $gap++;
    }while($row1['COUNT(kw)'] == 0 && $kwDiff > 0);

    if($row1['SUM(present)'] == 0){
        $presentPC = "~";
    }
    else {
        $presentPC = round(100*$row['SUM(present)']/$row1['SUM(present)']-100);
    }

    if($row1['SUM(absent)'] == 0){
        $absentPC = "~";
    }
    else{
        $absentPC = round(100*$row['SUM(absent)']/$row1['SUM(absent)']-100);
    }

    if($row1['SUM(guest)'] == 0){
        $guestPC = "~";
    }
    else{
        $guestPC = round(100*$row['SUM(guest)']/$row1['SUM(guest)']-100);
    }

}
else{

    if(!isset($_GET['from'])){

        $sql = "SELECT MAX(year),MAX(kw) FROM statistics WHERE year = (SELECT MAX(year) FROM statistics)";
        $result = mysqli_query($conn,$sql);
        $row2 = $result->fetch_assoc();
    
        $_POST['period'] = $row2['MAX(kw)'];
        $_GET['years'] = $row2['MAX(year)'];
        $_GET['from'] = $row2['MAX(kw)'];
        $_GET['to'] = date('W');
    }

    $sql = "SELECT SUM(present), SUM(absent), SUM(guest) FROM statistics WHERE year = ".$_GET['years']." AND kw = ".$_GET['from'];

    $result = mysqli_query($conn,$sql);
    $row = $result->fetch_assoc();

    $present = $row['SUM(present)'];
    $absent = $row['SUM(absent)'];
    $guest = $row['SUM(guest)'];

    $gap = 1;
    
    do{
        $kwDiff = $_GET['from']-$gap;
        $sql = "SELECT COUNT(kw), SUM(present), SUM(absent), SUM(guest) FROM statistics WHERE year = ".$_GET['years']." AND kw=".$kwDiff;
        
        $result = mysqli_query($conn,$sql);
        $row1 = $result->fetch_assoc();

        $gap++;
    } while($row1['COUNT(kw)'] == 0 && $kwDiff > 0);

    if($row1['SUM(present)'] == 0){
        $presentPC = "~";
    }
    else {
        $presentPC = round(100*$row['SUM(present)']/$row1['SUM(present)']-100);
    }

    if($row1['SUM(absent)'] == 0){
        $absentPC = "~";
    }
    else{
        $absentPC = round(100*$row['SUM(absent)']/$row1['SUM(absent)']-100);
    }

    if($row1['SUM(guest)'] == 0){
        $guestPC = "~";
    }
    else{
        $guestPC = round(100*$row['SUM(guest)']/$row1['SUM(guest)']-100);
    }

}

?>


<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DockB Hamburg - Backend - Statistiken - Daten abrufen</title>
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

                                if($row['level'] == 3) echo '<li><a href="mitglieder.php">Mitglieder</a></li>';
                            
                                if($row['level'] == 2 || $row['level'] == 3){
                                    echo '<li class="active">
                                            <a href="daten_abrufen.php">Statistiken</a>
                                            <ul class="drop">
                                                <li class="drop-in active"><a href="#">Daten abrufen</a></li>
                                                <li class="drop-in"><a href="daten_eingeben.php">Daten eingeben</a></li>
                                                <li class="drop-in"><a href="daten_bearbeiten.php">Daten bearbeiten</a></li>
                                            </ul>
                                        </li>';
                                }
                            
                            ?>
                            <li><a href="logout.php"><span><img src="images/menu/icon_logout.png" alt="Logout"></span>Logout</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-xs-9 contents">
                    <div class="main-window">
                        <h1 class="page-title">Statistiken » Daten abrufen</h1>
                        <img class="small-logo" src="images/content/dockb_logo_small.png" alt="DockB Hamburg">
                        <div id="inner-window" class="before">
                            <h2>Zeitraum auswählen</h2>
                            <form action="daten_abrufen.php" method="get">
                                <div class="row">
                                    <div class="col-xs-4 form-window calendar">
                                        <select name="years">
                                            <?php
                                            foreach ($years as &$year) {
                                              echo '<option value="'.$year.'">Jahr '.$year.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-xs-3 calendar">
                                        <p>Kalenderwoche</p>
                                    </div>
                                    <div class="col-xs-2 form-window calendar">
                                        <input type="number" name="from" min="1" max="52" <?php echo 'value="'.$_GET['from'].'"';?>>
                                    </div>
                                    <div class="col-xs-1 calendar">
                                        <p>bis</p>
                                    </div>
                                    <div class="col-xs-2 form-window calendar">
                                        <input type="number" name="to" min="1" max="52"<?php echo 'value="'.$_GET['to'].'"';?>>
                                    </div>
                                </div>
                                <div class="seat-button">
                                    <?php
                                        $pdfLink = "print.php?years=".$_GET['years']."&from=".$_GET['from']."&to=".$_GET['to'];
                                        echo '<a href="'.$pdfLink.'" name="pdf" class="btn recall-button" style="position: relative;float: left;">PDF</a>';
                                    ?>
                                    <button type="submit" class="btn recall-button">Abrufen</button>
                                </div>
                            </form>
                        </div>
                        <h1 class="page-title before">Statistiken » Daten abrufen</h1>
                        <div id="inner-window" class="before">
                            <?php
                                echo    '<form name="period-choice" action="daten_abrufen.php?years='.$_GET['years'].'&from='.$_GET['from'].'&to='.$_GET['to'].'" method="post">
                                            <ul class="period-choice">';
                                            if($_GET['from'] > 0 && $_GET['to'] > 0){
                                                $choiceIndex = 1;
                                                for($index = $_GET['from']; $index <= $_GET['to']; $index++){
                                                    $sql = "SELECT COUNT(kw) FROM statistics WHERE year=".$_GET['years']." AND kw=".$index;
                                                    $result = mysqli_query($conn,$sql);
                                                    $row = $result->fetch_assoc();
                                                    
                                                    if($row['COUNT(kw)'] > 0){
                                                echo'   <li>
                                                            <input type="radio" id="choice-'.$choiceIndex.'" name="period" onclick="this.form.submit();" value="'.$index.'">
                                                            <label for="choice-'.$choiceIndex.'">'.$_GET['years'].' - KW '.$index.'</label>
                                                        </li>';
                                                    }
                                                    $choiceIndex++;
                                                }
                                            }
                                            '</ul>
                                        </form>';
                            ?>
                        </div>
                        <div id="inner-window" class="after">

                            <?php
                                if(isset($_GET['years']) && isset($_POST['period']))
                                    echo '<h2>'.$_GET['years'].' KW '.$_POST['period'].'</h2>';

                                echo'
                                        <div class="row col-title">
                                        <div class="col-xs-8">
                                            <p>Mitglieder</p>
                                        </div>
                                        <div class="col-xs-3 col-xs-push-1">
                                            <p>Gäste</p>
                                        </div>
                                    </div>
                                    <div class="row table-col-title">
                                        <div class="col-xs-4">
                                            <p>Anwesend</p>
                                        </div>
                                        <div class="col-xs-4">
                                            <p>Abwesend</p>
                                        </div>
                                    </div>
                                    <div class="row row-top">
                                        <div class="col-xs-4 col-01">
                                            <p>'.$present.'</p>
                                        </div>
                                        <div class="col-xs-4 col-02">
                                            <p>'.$absent.'</p>
                                        </div>
                                        <div class="col-xs-3 col-xs-push-1 col-03">
                                            <p>'.$guest.'</p>
                                        </div>
                                    </div>
                                    <div class="row row-bottom">
                                        <div class="col-xs-4 col-01">
                                            <p>'.$presentPC.'%</p>
                                        </div>
                                        <div class="col-xs-4 col-02">
                                            <p>'.$absentPC.'%</p>
                                        </div>
                                        <div class="col-xs-3 col-xs-push-1 col-03">
                                            <p>'.$guestPC.'%</p>
                                        </div>
                                    </div>';
                            ?>
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

