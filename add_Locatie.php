<?php

include 'database.php';
include 'helper.php';

// ik check hier of mijn form gesubmit is
if(isset($_POST['submit'])){

	// array met de values van de name attribute van mijn reuiqred input fields
	$fieldnames = array(
		'locatiecode', 'locatie'
	); 

	$helper = new helper();
	$fields_validated = $helper->field_validation($fieldnames);

	if($fields_validated){

		// maak een instance van de database class en sla deze op in je db variable

		$db = new database();

		$locatiecode = $_POST["locatiecode"];		
		$locatie = $_POST["locatie"];			

		echo 'is signup'."<br>";

		$db->addLocatie($locatiecode, $locatie);
	}
}
?>
<html>	
	<head>
		
	</head>
		<div>
			<form action="add_Locatie.php" method="post">
				<label for="locatiecode"><b>Locatiecode</b><br></label>
				<input type="text" placeholder="Vul in je locatiecode" name="locatiecode" required><br><br>				
				<label for="locatie"><b>Locatie</b><br></label>
				<input type="text" placeholder="Vul in je locatie" name="locatie" required><br><br>				
							 
				<input type="submit" name="submit">
		</div>
		<a href="welcome_admin.php"> Terug</a><br>
</html>