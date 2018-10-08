<?php

session_start();

if(!isset($_SESSION['online_user']))
{
    header('Location: login.php');
    exit();
}
require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

$sql = "SELECT * FROM users LEFT JOIN profiles ON users.idu = profiles.uid WHERE status = 1 ORDER BY lastname";

$result = mysqli_query($conn, $sql);

$usernames = array();

while($row = $result->fetch_assoc()){
    $usernames[$row['idu']] = $row['username'];
}

$sql = "SELECT DISTINCT year FROM statistics ORDER BY year DESC";

$result = mysqli_query($conn, $sql);

$years = array();
$index = 0;

while($row = $result->fetch_assoc()){
    $years[$index] = $row['year'];
    $index++;
}


$selectedYear = date('Y');
$selectedKW = date('W');

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DockB Hamburg - Backend - Statistiken - Daten eingeben</title>
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
                                                <li class="drop-in"><a href="daten_abrufen.php">Daten abrufen</a></li>
                                                <li class="drop-in active"><a href="#">Daten eingeben</a></li>
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
                        <h1 class="page-title">Statistiken » eingeben</h1>
                        <img class="small-logo" src="images/content/dockb_logo_small.png" alt="DockB Hamburg">
                        <div id="inner-window" class="enter-data-2">
                            <h2>Wochenstatistik KW<?php echo $selectedKW."/".$selectedYear; ?></h2>
                            <form action="update_statistic.php" method="post">
                            <table id="statistics-title">
                                <tr class="head">
                                    <th class="c01">Mitglied Anwesend</th>
                                    <th class="c02">Kontakt</th>
                                    <th class="c03">Gäste</th>
                                    <th class="c04">Referenz</th>
                                    <th class="c05">Umsätze&nbsp;<span>(intern, extern, neu, folge)</span></th>
                                </tr>
                            </table>
                            <table id="statistics">
                                <tr class="head">
                                    <th class="c01">Name</th>
                                    <th class="c02"></th>
                                    <th class="c03">gegeben</th>
                                    <th class="c04">erhalten</th>
                                    <th class="c05"></th>
                                    <th class="c06">gegeben</th>
                                    <th class="c07">erhalten</th>
                                    <th class="c08"></th>
                                </tr>
                            <?php
                                require_once "connect.php";

                                $conn = @new mysqli($host,$db_user,$db_password,$db_name);
                                echo '<input type="hidden" name="year" value="'.$selectedYear.'">';
                                echo '<input type="hidden" name="kw" value="'.$selectedKW.'">';
                                

                                foreach($usernames as $idu => $username){
                                    $sql = "SELECT * FROM statistics WHERE kw = ".$selectedKW." AND year = ".$selectedYear." AND uid = ".$idu;
                                    $result = mysqli_query($conn,$sql);
                                    $row = $result->fetch_assoc();
                                    $countRows = $result->num_rows;

                                    if($countRows > 0){
                                        $ids = $row['ids'];
                                        $present = $row['present'];
                                        $absent = $row['absent'];
                                        $time_out = $row['time_out'];
                                        $sick = $row['sick'];
                                        $representative = $row['representative'];
                                        $guest = $row['guest'];
                                        $givenContact = $row['given_contact'];
                                        $receviedContact = $row['recevied_contact'];
                                        $givenReferences = $row['given_references'];
                                        $receviedReferences = $row['recevied_references'];
                                    }
                                    else{
                                        $ids = 0;
                                        $present = 0;
                                        $absent = 0;
                                        $timeOut = 0;
                                        $sick = 0;
                                        $representative = 0;
                                        $guest = 0;
                                        $givenContact = 0;
                                        $receviedContact = 0;
                                        $givenReferences = 0;
                                        $receviedReferences = 0;
                                    }
    
                                    echo '<tr class="table-content">
                                            <td class="c01">'.$username.'</td>';
                                                if($absent != 0){
                                                    if($time_out == 1){
                                                        echo '<td class="c02 checked">
                                                            <input id="member-present-'.$idu.'" type="checkbox" name="member-present-'.$idu.'" value="1" checked>to
                                                            <input id="member-present-'.$idu.'" type="hidden" name="member-present-'.$idu.'" value="1">
                                                          </td>';
                                                    }
                                                    elseif ($sick == 1) {
                                                        echo '<td class="c02 checked">
                                                            <input id="member-present-'.$idu.'" type="checkbox" name="member-present-'.$idu.'" value="2" checked>sc
                                                            <input id="member-present-'.$idu.'" type="hidden" name="member-present-'.$idu.'" value="2">
                                                          </td>';
                                                    }
                                                    elseif ($representative == 1) {
                                                        echo '<td class="c02 checked">
                                                            <input id="member-present-'.$idu.'" type="checkbox" name="member-present-'.$idu.'" value="3" checked>re
                                                            <input id="member-present-'.$idu.'" type="hidden" name="member-present-'.$idu.'" value="3">
                                                          </td>';
                                                    }
                                                }
                                                else{
                                                    echo '<td class="c02">
                                                            <input id="member-present-'.$idu.'" type="checkbox" name="member-present-'.$idu.'" value="0">
                                                            <input id="member-present-'.$idu.'" type="hidden" name="member-present-'.$idu.'" value="0">
                                                          </td>';
                                                }
                                    
                                    echo    '<td class="c03">
                                                <input type="number" name="given-contact-'.$idu.'" min="0" value="'.$givenContact.'" class="edit">
                                            </td>
                                            <td class="c04">
                                                <input type="number" name="recevied-contact-'.$idu.'" min="0" value="'.$receviedContact.'" class="edit">
                                            </td>
                                            <td class="c05">
                                                <input type="number" name="guest-'.$idu.'" min="0" value="'.$guest.'" class="edit">
                                            </td>
                                            <td class="c06">
                                                <input type="number" name="given-references-'.$idu.'" min="0" value="'.$givenReferences.'" class="edit">
                                            </td>
                                            <td class="c07">
                                                <input type="number" name="recevied-references-'.$idu.'" min="0" value="'.$receviedReferences.'" class="edit">
                                            </td>
                                           <td class="c08">      
                                             <input type="number" name="turnover0-'.$idu.'" min="0" value="0" class="edit wide">
                                                <div class="euro">EUR</div>
                                                <div class="radios">
                                                    <input type="radio" name="type0-'.$idu.'" id="int" value="0" checked>
                                                    <label for="int">int</label>
                                                    <input type="radio" name="type0-'.$idu.'" id="ext" value="1">
                                                    <label for="ext">ext</label>
                                                    <input type="radio" name="type1-'.$idu.'" id="n" value="0" checked>
                                                    <label for="n">n</label>
                                                    <input type="radio" name="type1-'.$idu.'" id="f" value="1">
                                                    <label for="f">f</label>
                                                </div>';

                                                $sql = "SELECT * FROM turnovers WHERE kw = ".$selectedKW." AND year = ".$selectedYear." AND uid = ".$idu;
                                                $result = mysqli_query($conn,$sql);
                                                $radioIndex = 2;
                                                $turnoverIndex = 1;
                                                while($row = $result->fetch_assoc()){

                                        echo ' <input type="number" name="turnover'.$turnoverIndex.'-'.$idu.'" min="0" value="'.$row['turnover'].'" class="edit wide">
                                                <div class="euro">EUR</div>
                                                <div class="radios">';
                                                    if($row['internal'] != 0){
                                                        echo'<input type="radio" name="type'.$radioIndex.'-'.$idu.'" id="int" value="0" checked>
                                                             <label for="int">int</label>';
                                                    }
                                                    else{
                                                        echo'<input type="radio" name="type'.$radioIndex.'-'.$idu.'" id="int" value="0">
                                                             <label for="int">int</label>';    
                                                    }

                                                    if($row['external'] != 0){
                                                        echo'<input type="radio" name="type'.$radioIndex.'-'.$idu.'" id="ext" value="1" checked>
                                                             <label for="ext">ext</label>';
                                                    }
                                                    else{
                                                        echo'<input type="radio" name="type'.$radioIndex.'-'.$idu.'" id="ext" value="1">
                                                             <label for="ext">ext</label>';
                                                    }

                                                    if($row['initial'] != 0){
                                                        echo'<input type="radio" name="type'.($radioIndex + 1).'-'.$idu.'" id="n" value="0" checked>
                                                             <label for="n">n</label>';
                                                    }
                                                    else{
                                                        echo'<input type="radio" name="type'.($radioIndex + 1).'-'.$idu.'" id="n" value="0">
                                                             <label for="n">n</label>';
                                                    }

                                                    if($row['next'] != 0){
                                                        echo'<input type="radio" name="type'.($radioIndex + 1).'-'.$idu.'" id="f" value="1" checked>
                                                            <label for="f">f</label>';
                                                    }
                                                    else{
                                                        echo'<input type="radio" name="type'.($radioIndex + 1).'-'.$idu.'" id="f" value="1">
                                                            <label for="f">f</label>';
                                                    }
                                                    echo '  </div>';
                                                    $radioIndex+=2;
                                                    $turnoverIndex++;
                                                }
                                        echo    '</td>
                                              </tr>';
                                        }
                                        
                                    ?>
                            </table>
                            <div class="seat-button">
                                <input type="submit" name="save-update-profile" class="btn save-button" value="Speichern">
                            </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
        <?php 
                echo'<div id="window-override">
                        <div class="kind-window">
                            <div class="row">  
                            <form id="checkbox123" action="daten_bearbeiten.php?years='.$selectedYear.'&from='.$selectedKW.'" method="post">
                                    <div class="col-xs-3 but-table">
                                        <label for="time-out">Auszeit</label><br>
                                        <input type="radio" name="unpresent-type" id="time-out" value="1" onclick="present(this.value,1)">
                                    </div>
                                    <div class="col-xs-6 but-table">
                                        <label for="illness-vacation">Krankheit/Urlaub</label><br>
                                        <input type="radio" name="unpresent-type" id="illness-vacation" value="2" onclick="present(this.value,2)">
                                    </div>
                                    <div class="col-xs-3 but-table">
                                        <label for="representative">Vertreter</label><br>
                                        <input type="radio" name="unpresent-type" id="representative" value="3" onclick="present(this.value,3)">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
                ?>
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
    <script >
    function present(id,val){
        document.getElementsByName("member-present-"+id)[0].value = val;
        document.getElementsByName("member-present-"+id)[1].value = val;
    }

    $("input[type='checkbox']").change(function () {
        var str = $(this).attr("name");
        str = str.replace('member-present-','');
        if ($(this).is(":checked")) {
            $("#time-out").val(str);
            $("#illness-vacation").val(str);
            $("#representative").val(str);
        }
        else{
            present(str,0);
        }
    });


    </script>
</body>

</html>