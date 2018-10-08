<?php

require_once("inc/dompdf/dompdf_config.inc.php");

	$summen = array('present' => '','absent' => '','time_out' => '','sick' => '','vertreter' => '','vertreter' => '','given_contact' => '','given_contact' => '','recevied_contact' => '','guest' => '','given_references' => '','recevied_references' => '','internal' => '','external' => '','initial' => '','next' => '','turnover' => '','altturnover' => '','sum' => '');

	$deaktiviert = array('present' => '','absent' => '','time_out' => '','sick' => '','vertreter' => '','vertreter' => '','given_contact' => '','given_contact' => '','recevied_contact' => '','guest' => '','given_references' => '','recevied_references' => '','internal' => '','external' => '','initial' => '','next' => '','turnover' => '','altturnover' => '','sum' => '');

	function geldHer($betrag) {
		if(empty($betrag)) { $betrag = 0; }
		return number_format( $betrag , 0 , ',' , '.' );
	}

	$html = '<!DOCTYPE html>
<html lang="de-DE">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="robots" content="" />
		
		<title>'.$_GET['years'] . ' - KW ' . $_GET['from'] . ' bis ' . $_GET['to'].'</title>
		<style media="all">
			/* =Clearfix (all browsers)--------------------------------*/.clearfix:after {content: ".";display: block;height: 0;clear: both;visibility: hidden;}/* IE6 */ * html .clearfix {height: 1%;}/* IE7 */*:first-child+html .clearfix {min-height: 1px;}
			body {
				font-family: arial, sans-serif;
				font-size: 8pt;
				color: #000000;
			}
			td {
				text-align: center;

				border-bottom: 1px solid #96aec1;
				border-left: 1px solid #96aec1;
				padding-top: 3px;
				padding-bottom: 3px;
				padding-left: 5px;
				padding-right: 5px;
				margin: 0;
			}
			table,th {
				border-bottom: 1px solid #96aec1;
				border-spacing: 0;
			}
			table {
				border-right: 1px solid #96aec1;
				border-top: 1px solid #96aec1;
			}
			th {
				vertical-align: bottom;
				border-bottom: 1px solid #96aec1;
				border-left: 1px solid #96aec1;

			}
		</style>
	</head>
	<body class="land">
		<div class="clearfix" style="padding-bottom: 50px; text-align: right">
<!--			<h1 style="display: inline-block; float: left">DIE STATISTIK</h1> -->
			<img src="images/logo.png" style="float: right" align="right" />
		</div>
		'.$_GET['years'].' KW '.$_GET['from'].' bis '.$_GET['to'].'
		<table cellspacing="0" cellpadding="2px" width="100%">
			<tr>
				<th rowspan="2">Nr.</th>
				<th rowspan="2">Name</th>
				<th rowspan="2">Anw.</th>
				<th rowspan="2">Abw.</th>
				<th rowspan="2">Ausz.</th>
				<th rowspan="2">Kr./Url.</th>
				<th rowspan="2">Vertr.</th>
				<th rowspan="2">Kontakt geg.</th>
				<th rowspan="2">Kontakt erh.</th>
				<th rowspan="2">'.utf8_decode('Gäste').'</th>
				<th rowspan="2">Ref. geg.</th>
				<th rowspan="2">Ref. erh.</th>
				<th colspan="5">Umsatz</th>
			</tr>
			<tr>
				<th>intern</th>
				<th>extern</th>
				<th>Erstauftrag</th>
				<th>Folgeauftrag</th>
				<th>kumuliert</th>
			</tr>';
				
				require_once "connect.php";

				$conn = @new mysqli($host,$db_user,$db_password,$db_name);

				$sql = "SELECT idu,firstname,lastname,username,status FROM users LEFT JOIN profiles ON users.idu = profiles.uid ORDER BY lastname ASC";

				$result = mysqli_query($conn,$sql);
				$count = $result->num_rows;

				while($member = $result->fetch_assoc()){
					$sql = '	SELECT	SUM(statistics.present) AS present,
										SUM(statistics.absent) AS absent,
										SUM(statistics.time_out) AS time_out,
										SUM(statistics.sick) AS sick,
										SUM(statistics.representative) AS representative,
										SUM(statistics.given_contact) AS given_contact,
										SUM(statistics.recevied_contact) AS recevied_contact,
										SUM(statistics.guest) AS guest,
										SUM(statistics.given_references) AS given_references,
										SUM(statistics.recevied_references) AS recevied_references

								FROM statistics
								WHERE (statistics.kw BETWEEN '.$_GET['from'].' AND '.$_GET['to'].')
								AND statistics.year = '.$_GET['years'].'
								AND statistics.uid = '.$member['idu'].'
							';

					$result1 = mysqli_query($conn,$sql);
					$count = $result1->num_rows;
					if($count > 0){
						$stats = $result1->fetch_assoc();
					}
					else {
						$html .= '<tr><td colspan="18">KEINE DATEN VERFÜGBAR!</td></tr>';
					}
					
					
					$sql = '	SELECT	SUM(turnovers.internal) AS internal,
										SUM(turnovers.external) AS external,
										SUM(turnovers.initial) AS initial,
										SUM(turnovers.next) AS next,
										SUM(turnovers.turnover) AS turnover
								FROM turnovers
								WHERE (turnovers.kw BETWEEN '.$_GET['from'].' AND '.$_GET['to'].')
								AND turnovers.year = '.$_GET['years'].'
								AND turnovers.uid = '.$member['idu'].'
							';
							
					$result2 = mysqli_query($conn,$sql);
					$count = $result2->num_rows;
					if($count > 0) {
						$userumsatz = $result2->fetch_assoc();
					}
												
					
					$sql = '	SELECT	SUM(turnovers.turnover) AS turnover
								FROM turnovers
								WHERE turnovers.kw < '.$_GET['to'].'
								AND turnovers.year = '.$_GET['years'].'
								AND turnovers.uid = '.$member['idu'].'
							';
							
					$result3 = mysqli_query($conn,$sql);
					$count = $result3->num_rows;
					if($count > 0) {
						$altumsatz = $result3->fetch_assoc();
					}
					//foreach($userstats as $stats) {
						$summe = $userumsatz['turnover'] + $altumsatz['turnover'];
						
						if($member['status'] == 0) {

							$deaktiviert['present']			+=	$stats['present'];
							$deaktiviert['absent']			+=	$stats['absent'];
							$deaktiviert['time_out']				+=	$stats['time_out'];
							$deaktiviert['sick']		+=	$stats['sick'];
							$deaktiviert['representative']			+=	$stats['representative'];
							$deaktiviert['given_contact']		+=	$stats['given_contact'];
							$deaktiviert['recevied_contact']	+=	$stats['recevied_contact'];
							$deaktiviert['guest']				+=	$stats['guest'];
							$deaktiviert['given_references']	+=	$stats['given_references'];
							$deaktiviert['recevied_references']	+=	$stats['recevied_references'];
							$deaktiviert['internal']				+=	$userumsatz['internal'];
							$deaktiviert['external']				+=	$userumsatz['external'];
							$deaktiviert['initial']					+=	$userumsatz['initial'];
							$deaktiviert['next']				+=	$userumsatz['next'];
							$deaktiviert['turnover']				+=	$userumsatz['turnover'];
							$deaktiviert['altturnover']			+=	$altumsatz['turnover'];
							$deaktiviert['sum']				+= $summe;

						} 
						//else {
							$html .= '<tr>
										<td style="text-align: left;">'.$member['idu'].'</td>
										<td style="text-align: left;">'.utf8_decode($member['username']).'</td>
										<td>'.(($member['idu'] != 29 ) ? $stats['present'] : 0).'</td>
										<td>'.$stats['absent'].'</td>
										<td>'.$stats['time_out'].'</td>
										<td>'.$stats['sick'].'</td>
										<td>'.$stats['representative'].'</td>
										<td>'.$stats['given_contact'].'</td>
										<td>'.$stats['recevied_contact'].'</td>
										<td>'.$stats['guest'].'</td>
										<td>'.$stats['given_references'].'</td>
										<td>'.$stats['recevied_references'].'</td>
										<td style="text-align: right;">'.geldHer($userumsatz['internal']).'&nbsp;</td>
										<td style="text-align: right;">'.geldHer($userumsatz['external']).'&nbsp;</td>
										<td style="text-align: right;">'.geldHer($userumsatz['initial']).'&nbsp;</td>
										<td style="text-align: right;">'.geldHer($userumsatz['next']).'&nbsp;</td>
										<td style="text-align: right;">'.geldHer($userumsatz['turnover']).'&nbsp;</td>
									</tr>';
						//}
						
						$summen['present']			+=	($member['id'] != 29 ) ? $stats['present'] : 0;
						$summen['absent']			+=	$stats['absent'];
						$summen['time_out']			+=	$stats['time_out'];
						$summen['sick']		+=	$stats['sick'];
						$summen['representative']		+=	$stats['representative'];
						$summen['given_contact']	+=	$stats['given_contact'];
						$summen['recevied_contact']	+=	$stats['recevied_contact'];
						$summen['guest']	+=	$stats['guest'];
						$summen['given_references']	+=	$stats['given_references'];
						$summen['recevied_references']+=	$stats['recevied_references'];
						$summen['internal']			+=	$userumsatz['internal'];
						$summen['external']			+=	$userumsatz['external'];
						$summen['initial']				+=	$userumsatz['initial'];
						$summen['next']			+=	$userumsatz['next'];
						$summen['turnover']			+=	$userumsatz['turnover'];
						$summen['altturnover']		+=	$altumsatz['turnover'];
						$summen['sum']			+= $summe;
						
					//}

				}
			
			$html .= '<tr style="background-color: #abd5f1; color: #000">
										<td style="text-align: left;">&nbsp;</td>
										<td style="text-align: left;">Deaktivierte Mitglieder</td>
										<td>'.$deaktiviert['present'].'</td>
										<td>'.$deaktiviert['absent'].'</td>
										<td>'.$deaktiviert['time_out'].'</td>
										<td>'.$deaktiviert['sick'].'</td>
										<td>'.$deaktiviert['representative'].'</td>
										<td>'.$deaktiviert['given_contact'].'</td>
										<td>'.$deaktiviert['recevied_contact'].'</td>
										<td>'.$deaktiviert['guest'].'</td>
										<td>'.$deaktiviert['given_references'].'</td>
										<td>'.$deaktiviert['recevied_references'].'</td>
										<td style="text-align: right;">'.geldHer($deaktiviert['internal']).'&nbsp;</td>
										<td style="text-align: right;">'.geldHer($deaktiviert['external']).'&nbsp;</td>
										<td style="text-align: right;">'.geldHer($deaktiviert['initial']).'&nbsp;</td>
										<td style="text-align: right;">'.geldHer($deaktiviert['next']).'&nbsp;</td>
										<td style="text-align: right;">'.geldHer($deaktiviert['turnover']).'&nbsp;</td>
									</tr>';


			$html .= '		
			<tr>
				<td colspan="2" style="color: #69afdc; font-weight: bold;">&nbsp;</td>
				<td style="color: #69afdc; font-weight: bold;">'.($summen['present']).'</td>
				<td style="color: #69afdc; font-weight: bold;">'.$summen['absent'].'</td>
				<td style="color: #69afdc; font-weight: bold;">'.$summen['time_out'].'</td>
				<td style="color: #69afdc; font-weight: bold;">'.$summen['sick'].'</td>
				<td style="color: #69afdc; font-weight: bold;">'.$summen['representative'].'</td>
				<td style="color: #69afdc; font-weight: bold;">'.$summen['given_contact'].'</td>
				<td style="color: #69afdc; font-weight: bold;">'.$summen['recevied_contact'].'</td>
				<td style="color: #69afdc; font-weight: bold;">'.$summen['guest'].'</td>
				<td style="color: #69afdc; font-weight: bold;">'.$summen['given_references'].'</td>
				<td style="color: #69afdc; font-weight: bold;">'.$summen['recevied_references'].'</td>
				<td style="text-align: right; color: #69afdc; font-weight: bold;">'.geldHer($summen['internal']).'&nbsp;</td>
				<td style="text-align: right; color: #69afdc; font-weight: bold;">'.geldHer($summen['external']).'&nbsp;</td>
				<td style="text-align: right; color: #69afdc; font-weight: bold;">'.geldHer($summen['initial']).'&nbsp;</td>
				<td style="text-align: right; color: #69afdc; font-weight: bold;">'.geldHer($summen['next']).'&nbsp;</td>
				<td style="text-align: right; color: #69afdc; font-weight: bold;">'.geldHer($summen['turnover']).'&nbsp;</td>
			</tr>

				<tr>
					<th rowspan="2">Nr.</th>
					<th rowspan="2">Name</th>
					<th rowspan="2">Anw.</th>
					<th rowspan="2">Abw.</th>
					<th rowspan="2">Ausz.</th>
					<th rowspan="2">Kr./Url.</th>
					<th rowspan="2">Vertr.</th>
					<th rowspan="2">Kontakt geg.</th>
					<th rowspan="2">Kontakt erh.</th>
					<th rowspan="2">'.utf8_decode('Gäste').'</th>
					<th rowspan="2">Ref. geg.</th>
					<th rowspan="2">Ref. erh.</th>
					<th colspan="5">Umsatz</th>
				</tr>
				<tr>
					<th>intern</th>
					<th>external</th>
					<th>Erstauftrag</th>
					<th>Folgeauftrag</th>
					<th>kumuliert</th>
				</tr>
				
		</table>

	</body>
</html>';

//echo $html;

$dompdf = new DOMPDF();
$dompdf->set_paper('a4','landscape');
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("statistik_".$_GET['from'].'-'.$_GET['from'].'-'.$_GET['to'].'.pdf');
?>