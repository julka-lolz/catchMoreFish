﻿﻿<?php

include 'database.php';
include 'helper.php';

//make field falidation as extra security for sql injections.
if(isset($_POST['submit'])){
	
	$fieldnames = array(
		'lev_code', 'leverancier', 'telefoon'
	);
	$helper = new helper();
	$fields_validated = $helper->field_validation($fieldnames);

	if($fields_validated){	
		
		$db = new database();
		echo 'connected';

		$lev_code = $_POST["lev_code"];		
		$leverancier = $_POST["leverancier"];		
		$telefoon = $_POST["telefoon"];	

		echo 'Current fabriek is updated'."<br>";

		$db->updateFabriek($lev_code, $leverancier, $telefoon);
	}
}
?>
<html>	
	<head>	
	</head>
		<div>
			<form action="updateFabriek.php" method="post">
				<label for="lev_code"><b>Lev_code</b><br></label>
				<input type="text" placeholder="Vul in je lev_code" name="lev_code" ><br><br>				
				<label for="leverancier"><b>Leverancier</b><br></label>
				<input type="text" placeholder="Vul in je leverancier" name="leverancier" ><br><br>				
				<label for="telefoon"><b>telefoon</b><br></label>
				<input type="text" placeholder="Vul in je telefoon" name="telefoon" ><br><br>				 
				<input type="submit" name="submit">
		</div>
		<a href="welcome_admin.php"> Terug</a><br>
</html>