<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DockB Hamburg - Backend - Login</title>
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
    <div class="container">
        <div class="row">
            <div class="col-xs-12 logo">
                <img src="images/header/dockb_logo.png" alt="DockB Hamburg">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 login">
                <div id="login-window">
                    <form action="login_script.php" method="post">
                        <div class="login-header">
                            <?php
                            if(!isset($_SESSION['error'])){
                                echo '<p>Bitte loggen Sie sich mit Ihren Zugangsdaten ein.</p>';    
                            }else{
                                echo $_SESSION['error'];
                            }
                            ?>
                            
                        </div>
                        <div class="row input-group">
                            <div class="col-xs-2 icon">
                                <img src="images/content/icon_user.png" alt="Benutzername">
                            </div>
                            <div class="col-xs-10 input">
                                <input name="login" type="text" class="form-control" id="login-input" placeholder="Benutzername">
                            </div>
                        </div>
                        <div class="row input-group">
                            <div class="col-xs-2 icon">
                                <img src="images/content/icon_password.png" alt="Passwort">
                            </div>
                            <div class="col-xs-10 input">
                                <input name="password" type="password" class="form-control" id="password-input" placeholder="Passwort">
                            </div>
                        </div>
                        <div class="login-button">
                            <input type="submit" class="btn btn-primary" value="Anmelden">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
