﻿﻿<?php

include 'database.php';
include 'helper.php';

//make field falidation as extra security for sql injections.
if(isset($_POST['submit'])){
	
	$fieldnames = array(
		'productcode', 'klantcode', 'notes', 'reservatie_datum', 'reservatie_tijd'
	);
	$helper = new helper();
	$fields_validated = $helper->field_validation($fieldnames);

	if($fields_validated){	
		
		$db = new database();
		echo 'connected';
		print_r($_POST);

		$productcode = $_POST["productcode"];
		$klantcode = $_POST["klantcode"];
		$notes = $_POST["notes"];	
		$reservatie_datum = $_POST["reservatie_datum"];	
		$reservatie_tijd = $_POST["reservatie_tijd"];	

		echo 'Current reservation is added'."<br>";

		$db->addReservatie($productcode, $klantcode, $notes, $reservatie_datum, $reservatie_tijd);
	}
}
?>
<html>	
	<head>	
	</head>
		<div>
			<form action="voorraadReserveren.php" method="post">
							
				<input type="hidden" name="productcode" value="<?php echo htmlspecialchars($_GET['productcode']);?>">				
				<input type="hidden" name="klantcode" value="<?php echo htmlspecialchars($_GET['klantcode']);?>">				
				<label for="type"><b>notes</b><br></label>
				<input type="text" placeholder="Vul in je notitie" name="notes"><br><br>	
				<label for="reservatie_datum"><b>Reservatie datum</b><br></label>
				<input type="date" placeholder="Vul in je reservatie datum" name="reservatie_datum"><br><br>					
				<label for="reservatie_tijd"><b>Reservatie tijd</b><br></label>
				<input type="time" placeholder="Vul in reservatie tijd" name="reservatie_tijd"><br><br>				
				<input type="submit" name="submit">
		</div>
		<a href="welcome_klant.php"> Terug</a><br>
</html>