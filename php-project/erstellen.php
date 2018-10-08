<?php
session_start();

if(!isset($_SESSION['online_user'])){
    header('Location: login.php');
    exit();
}
require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);


$option1 = '<option value="1">Mitglied</option>';
$option2 = '<option value="2">Statistik</option>';
$option3 = '<option value="3">Administrator</option>';

if(isset($_GET['id'])){
    $sql = "SELECT * FROM users LEFT JOIN profiles ON idu = uid WHERE idu = ".$_GET['id'];
    $result = mysqli_query($conn,$sql);
    $row = $result->fetch_assoc();

    $id = $_GET['id'];
    $username = $row['username'];
    $email = $row['email'];
    $email1 = $row['email'];
    $level = $row['level'];
    $name = $row['name'];
    $firm = $row['firm'];
    $function = $row['function'];
    $street = $row['street'];
    $number = $row['number'];
    $postalcode = $row['postalcode'];
    $city = $row['city'];
    $telephone = $row['telephone'];
    $mobile = $row['mobile'];
    $web = $row['web'];
    $summary = $row['summary'];
    $description = $row['description'];
    $logo = $row['logo'];
    $portrait = $row['portrait'];

    if($level == 1) $option1 = '<option selected="selected" value="1">Mitglied</option>';
    elseif($level == 2) $option2 = '<option selected="selected" value="2">Statistik</option>';
    elseif($level == 3) $option3 = '<option selected="selected" value="3">Administrator</option>';

    $editFlag = true;
}
else{
    $sql = "SELECT MAX(idu) FROM users";
    $result = mysqli_query($conn,$sql);
    $row = $result->fetch_assoc();

    $id = $row['MAX(idu)']+1;
    $editFlag = false;
}


?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DockB Hamburg - Backend - Erstellen</title>
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

                                if($row['level'] == 3) echo '<li class="active">
                                                                <ul class="drop" >
                                                                    <a href="mitglieder.php">Mitglieder</a>
                                                                </ul>
                                                                <ul class="drop">
                                                                    <li class="drop-in active"><a href="#">Erstellen</a></li>
                                                                </ul>
                                                            </li>';
                            
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
                        <h1 class="page-title">Erstellen</h1>
                        <img class="small-logo" src="images/content/dockb_logo_small.png" alt="DockB Hamburg">
                        <div id="inner-window">
                            <h2 class="window-title">Benutzerdaten ändern</h2>
                            <?php
                                if(!$editFlag) echo '<form action="add_user.php" method="post">';
                                else echo '<form action="update_user.php" method="post">';
                            ?>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                    <?php
                                        echo '<input type="text" name="user-name" placeholder="Benutzername" value="'.$username.'">';
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                    <?php
                                        echo '<input type="email" name="email" placeholder="E-Mail" value="'.$email.'">';
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                    <?php
                                        echo '<input type="password" name="current-password" placeholder="Passwort" value="'.$password.'">';
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                    <?php
                                        echo '<select name="account-type">
                                                '.$option1.$option2.$option3.'
                                            </select>';
                                    ?>
                                    </div>
                                </div><br><br>
                                <h2 class="window-title">Persönliche Daten ändern</h2>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                    <?php
                                        echo '<input type="text" name="name" placeholder="Vorname und Nachname" value="'.$name.'">';
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                    <?php
                                        echo '<input type="text" name="company" placeholder="Firma" value="'.$firm.'">';
                                    ?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                    <?php
                                        echo '<input type="text" name="position" placeholder="Funktion" value="'.$function.'">';
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                    <?php
                                        echo '<input type="text" name="street" placeholder="Straße" value="'.$street.'">';
                                    ?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                    <?php
                                       echo '<input type="text" name="number" placeholder="Nummer" value="'.$number.'">';
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                    <?php
                                        echo '<input type="text" name="postcode" placeholder="Postleitzahl" value="'.$postalcode.'">';
                                    ?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                    <?php
                                        echo '<input type="text" name="place" placeholder="Ort" value="'.$city.'">';
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                    <?php
                                        echo '<input type="text" name="phone" placeholder="Telefon" value="'.$telephone.'">';
                                    ?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                    <?php
                                        echo '<input type="text" name="mobile" placeholder="Mobil" value="'.$mobile.'">';
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                    <?php
                                        echo '<input type="email" name="email1" placeholder="E-Mail" value="'.$email1.'">';
                                    ?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                    <?php
                                        echo '<input type="text" name="homepage" placeholder="Webseite" value="'.$web.'">';
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                    <?php
                                        echo '<textarea class="summary" name="summary" placeholder="Warum ich bei Dock B bin (WP - Kurzbeschreibung)">'.$summary.'</textarea>';
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                    <?php
                                        echo '<textarea class="description" name="description" placeholder="Unternehmensprofil (WP - Beschreibung)" maxlength="100000">'.$description.'</textarea>';
                                    ?>
                                    </div>
                                </div>
                                <div class="seat-button">
                                    <button type="submit" class="btn save-button" name="save" value=<?php echo $id;?>>Speichern</button>
                                </div>
                            </form>
                        </div>
                         <div id="inner-window">
                            <h2 class="window-title">Firmenlogo</h2>
                            <div class="member-photo">
                                <?php 
                                echo '<img src="images/profiles/logos/'.$logo.'" alt="Firmenlogo">';
                                ?>
                            </div>
                            <p><strong>Datei vom Computer hochladen</strong></p>
                               <form method="POST" action="upload_logo.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                <div>
                                  <input type="file" name="image_logo">
                                </div>
                                <p>Die Datei muss im JPEG-Format vorliegen und darf nicht breiter als 600 Pixel sein.</p>
                                <div class="seat-button">
                                    <button class="btn send-button" type="submit" name="upload_logo" value=<?php echo $id;?>>Hochladen</button>
                                </div>
                              </form>
                        </div>
                        <div id="inner-window">
                            <h2 class="window-title">Ihr Portait</h2>
                            <div class="member-photo">
                                 <?php 
                                echo '<img src="images/profiles/portraits/'.$portrait.'" alt="Portrait">';
                                ?>
                            </div>
                            <p><strong>Datei vom Computer hochladen</strong></p>
                            <form method="POST" action="upload_portrait.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                <div>
                                  <input type="file" name="image_portrait">
                                </div>
                                <p>Die Datei muss im JPEG-Format vorliegen und maximal 300x300 groß sein. Nicht quadratische Bilder werden automatisch beschnitten. Nach dem Hochladen kann das Neuladen dieser Seite nötig sein, um das neue Bild zu sehen.</p>
                                <div class="seat-button">
                                    <button class="btn send-button" type="submit" name="upload_portrait" value=<?php echo $id;?>>Hochladen</button>
                                </div>
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
