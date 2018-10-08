<?php

session_start();

if(!isset($_SESSION['online_user'])){
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
    <title>DockB Hamburg - Backend - Mein Profil</title>
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
                            <li class="active"><a href="#">Mein Profil</a></li>
                            <li><a href="einladungen.php">Einladungen</a></li>
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
                        <h1 class="page-title">Mein Profil</h1>
                        <img class="small-logo" src="images/content/dockb_logo_small.png" alt="DockB Hamburg">
                        <div id="inner-window">
                            <h2 class="window-title">Firmenlogo</h2>
                            <div class="member-photo">
                                <?php 
                                echo '<img src="images/profiles/logos/'.$_SESSION['logo'].'" alt="Firmenlogo">';
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
                                    <button class="btn send-button" type="submit" name="upload_logo" value=<?php echo $_SESSION['idu'];?>>Hochladen</button>
                                </div>
                              </form>
                        </div>
                        <div id="inner-window">
                            <h2 class="window-title">Ihr Portait</h2>
                            <div class="member-photo">
                                 <?php 
                                echo '<img src="images/profiles/portraits/'.$_SESSION['portrait'].'" alt="Portrait">';
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
                                    <button class="btn send-button" type="submit" name="upload_portrait" value=<?php echo $_SESSION['idu'];?>>Hochladen</button>
                                </div>
                              </form>
                        </div>
                        <div id="inner-window">
                            <h2 class="window-title">Persönliche Daten ändern</h2>
                            <form name="update-profile" action="update_profile.php" method="post">
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                        <?php
                                        echo '<input type="text" name="name" placeholder="Vorname und Nachname" maxlength="42" required value="'.$_SESSION['name'].'">';
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                        <?php
                                        echo '<input type="text" name="firm" placeholder="Firma" maxlength="190" required value="'.$_SESSION['firm'].'">';
                                        ?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                        <?php
                                        echo '<input type="text" name="function" placeholder="Funktion" maxlength="190" required value="'.$_SESSION['function'].'">';
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                        <?php
                                        echo '<input type="text" name="street" placeholder="Straße" maxlength="190" required value="'.$_SESSION['street'].'">';
                                        ?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                        <?php
                                        echo '<input type="text" name="number" placeholder="Nummer" maxlength="70" required value="'.$_SESSION['number'].'">';
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                        <?php
                                        echo '<input type="text" name="postalcode" placeholder="Postleitzahl" pattern="[0-9]{5}" required value="'.$_SESSION['postalcode'].'">';
                                        ?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                        <?php
                                        echo '<input type="text" name="city" placeholder="Ort" maxlength="190" required value="'.$_SESSION['city'].'">';
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                        <?php
                                        echo '<input type="text" name="telephone" placeholder="Telefon" pattern="[0-9]{15}" value="'.$_SESSION['telephone'].'">';
                                        ?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                        <?php
                                        echo '<input type="text" name="mobile" placeholder="Mobil" pattern="[0-9]{15}" value="'.$_SESSION['mobile'].'">';
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-window">
                                        <?php
                                        echo '<input type="email" name="email" placeholder="E-Mail" maxlength="300" value="'.$_SESSION['email'].'">';
                                        ?>
                                    </div>
                                    <div class="col-xs-6 form-window">
                                        <?php
                                        echo '<input type="text" name="web" placeholder="Webseite" maxlength="190" value="'.$_SESSION['web'].'">';
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                        <?php
                                        echo '<textarea class="summary" name="summary" placeholder="Warum ich bei Dock B bin (WP - Kurzbeschreibung)" maxlength="420" >'.$_SESSION['summary'].'</textarea>';
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                        <?php
                                        echo '<textarea class="description" name="description" placeholder="Unternehmensprofil (WP - Beschreibung)" maxlength="600" >'.$_SESSION['description'].'</textarea>';
                                        ?>
                                    </div>
                                </div>
                                <div class="seat-button">
                                    <input type="submit" name="save-update-profile" class="btn save-button" value="Speichern">
                                </div>
                            </form>
                        </div>
                        <div id="inner-window">
                            <h2 class="window-title">Benutzerdaten ändern</h2><br>
                            <p>Die Änderungen werden erst beim nächsten Login wirksam.</p>
                            <form action="change_password.php" method="post">
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                        <input type="email" name="email-change-password" placeholder="E-Mail" maxlength="30">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                        <input type="password" name="current-password" placeholder="Passwort (aktuel)" maxlength="32">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                        <input type="password" name="new-password" placeholder="Neues Passwort" maxlength="32">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 form-window">
                                        <input type="password" name="new-password-repeat" placeholder="Neues Passwort bestätigen" maxlength="32">
                                    </div>
                                </div>
                                <div class="seat-button">
                                <input type="submit" name="save-change-password" class="btn save-button" value="Speichern">
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
