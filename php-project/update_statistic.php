<?php

session_start();

if(!isset($_SESSION['online_user'])){
    header('Location: login.php');
    exit();
}

require_once "connect.php";

$conn = @new mysqli($host,$db_user,$db_password,$db_name);

$updateData = array();

$selectedYear = $_POST['year'];
$selectedKW = $_POST['kw'];



foreach ($_POST as $index => $value) {
	if($index != "save" && $index != "year" && $index != "kw"){
		$id = substr($index, strrpos($index, '-') + 1);
		if($value == "") $value = 0;
	$updateData[$id][substr(str_replace($id,"",$index),0,-1)] = $value;
	}	
}

foreach($updateData as $index => $value){
	$sql = "SELECT COUNT(*) FROM statistics WHERE kw = ".$selectedKW." AND year = ".$selectedYear." AND uid = ".$index;
    $result = mysqli_query($conn,$sql);
    $row = $result->fetch_assoc();
    //var_dump($row);
    $time_out = 0;
	$sick = 0;
	$representative = 0;
    if($value['member-present'] == 0){
    	$present = 1;
    	$absent = 0;
    }else{
    	$present = 0;
    	$absent = 1;
    	if($value['member-present'] == 1)
    		$time_out = 1;
    	if($value['member-present'] == 2)
    		$sick = 1;
    	if($value['member-present'] == 3)
    		$representative = 1;
    }

    if($row['COUNT(*)'] > 0){
    	//update
    	$sql = "UPDATE statistics SET present = ".$present.", absent = ".$absent.", time_out = ".$time_out.", sick = ".$sick.", representative = ".$representative.", guest = ".$value['guest'].", given_contact = ".$value['given-contact'].", recevied_contact = ".$value['recevied-contact'].", given_references = ".$value['given-references'].", recevied_references = ".$value['recevied-references']." WHERE kw = ".$selectedKW." AND year = ".$selectedYear." AND uid = ".$index;
    	
    	if (mysqli_query($conn, $sql)) {
            //echo "update successful";
          } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
    }else{
        //insert
        $sql = "INSERT INTO statistics (kw, year, uid, present, absent, time_out, sick, representative, guest, given_contact, recevied_contact, given_references, recevied_references) VALUES (".$selectedKW.", ".$selectedYear.", ".$index.", ".$present.", ".$absent.", ".$time_out.", ".$sick.", ".$representative.", ".$value['guest'].", ".$value['given-contact'].", ".$value['recevied-contact'].", ".$value['given-references'].", ".$value['recevied-references'].")";

        if (mysqli_query($conn, $sql)) {
            //echo "update successful";
          } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
    }

echo $turnoverIndex;
    $turnoverIndex = 0;
    while(isset($value['turnover'.$turnoverIndex])){

    	if($value['turnover'.$turnoverIndex] == 0){
 $turnoverIndex++;}
    	else{
            echo $value['turnover'.$turnoverIndex];
    	$internal = 0;
    	$external = 0;
    	$initial = 0;
    	$next = 0;
    	$turnover = $value['turnover'.$turnoverIndex];

    	if($value['type'.(2*$turnoverIndex)] == 0){
    		$internal = $value['turnover'.$turnoverIndex];
    	}else{
    		$external = $value['turnover'.$turnoverIndex];
    	}
    	if($value['type'.(2*$turnoverIndex+1)] == 0){
    		$initial = $value['turnover'.$turnoverIndex];
    	}else{
    		$next = $value['turnover'.$turnoverIndex];
    	}


    	$sql = "SELECT COUNT(*), turnover FROM turnovers WHERE kw = ".$selectedKW." AND year = ".$selectedYear." AND uid = ".$index." AND internal = ".$internal." AND external = ".$external." AND initial = ".$initial." AND next = ".$next." AND turnover = ".$turnover;
        
    	$result = mysqli_query($conn,$sql);
    	$row = $result->fetch_assoc();
    	
    	if($row['COUNT(*)'] > 0 ){//&& $row['turnover'] != $turnover){
    		//update
            // var_dump($row);
    		$sql = "UPDATE turnovers SET uid = ".$index.", kw = ".$selectedKW.", year = ".$selectedYear.", internal = ".$internal.", external = ".$external.", initial = ".$initial.", next = ".$next.", turnover = ".$turnover;
            
    		if (mysqli_query($conn, $sql)) {
	            echo "update successful";
	          } else {
	            echo "Error: " . $sql . "<br>" . $conn->error;
	          }
    	}elseif($row['COUNT(*)'] == 0){
    		//insert
    		$sql = "INSERT INTO turnovers (uid, kw, year, internal, external, initial, next, turnover) VALUES (".$index.", ".$selectedKW.", ".$selectedYear.", ".$internal.", ".$external.", ".$initial.", ".$next.", ".$turnover.")";

    		if (mysqli_query($conn, $sql)) {
	            echo "insert successful";
	          } else {
	            echo "Error: " . $sql . "<br>" . $conn->error;
	          }
    	}

    	$turnoverIndex++;
    	}
	}

    //var_dump($_SERVER['HTTP_REFERER']);
    
}
header("Location: ".$_SERVER['HTTP_REFERER']);

?>