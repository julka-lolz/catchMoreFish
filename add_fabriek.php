<?php

include 'database.php';
include 'helper.php';

// ik check hier of mijn form gesubmit is
if(isset($_POST['submit'])){

	// array met de values van de name attribute van mijn reuiqred input fields
	$fieldnames = array(
		'lev_code', 'leverancier', 'telefoon'
	); 

	$helper = new helper();
	$fields_validated = $helper->field_validation($fieldnames);

	if($fields_validated){

		// maak een instance van de database class en sla deze op in je db variable

		$db = new database();

		$lev_code = $_POST["lev_code"];		
		$leverancier = $_POST["leverancier"];		
		$telefoon = $_POST["telefoon"];		

		echo 'is signup'."<br>";

		$db->addFabriek($lev_code, $leverancier, $telefoon);
	}
}
?>
<html>	
	<head>
		
	</head>
		<div>
			<form action="add_fabriek.php" method="post">
				<label for="lev_code"><b>Lev_code</b><br></label>
				<input type="text" placeholder="Vul in je lev_code" name="lev_code" required><br><br>				
				<label for="leverancier"><b>Leverancier</b><br></label>
				<input type="text" placeholder="Vul in je leverancier" name="leverancier" required><br><br>				
				<label for="telefoon"><b>telefoon</b><br></label>
				<input type="text" placeholder="Vul in je telefoon" name="telefoon" required><br><br>				 
				<input type="submit" name="submit">
		</div>
		<a href="welcome_admin.php"> Terug</a><br>
</html>