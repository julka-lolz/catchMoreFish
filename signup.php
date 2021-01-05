<?php

include 'database.php';
include 'helper.php';

// ik check hier of mijn form gesubmit is
if(isset($_POST['submit'])){

	// array met de values van de name attribute van mijn reuiqred input fields
	$fieldnames = array(
		'voorletters', 'achternaam', 'gebruikersnaam', 'wachtwoord', 'rewachtwoord'
	); 

	$helper = new helper();
	$fields_validated = $helper->field_validation($fieldnames);

	if($fields_validated){

		// maak een instance van de database class en sla deze op in je db variable

		$db = new database();

		$voorletters = $_POST["voorletters"];		
		$achternaam = $_POST["achternaam"];		
		$gebruikersnaam = $_POST["gebruikersnaam"];
		$wachtwoord = $_POST["wachtwoord"];
		$rewachtwoord = $_POST["rewachtwoord"];

		echo 'is signup'."<br>";

		$db->addAccount($voorletters, $db::MEDEWERKER, $achternaam, $gebruikersnaam, $wachtwoord);
	}
}
?>
<html>	
	<head>
		 <!-- include css file -->
		<link rel="stylesheet" href="style.css">
	</head>
		<div>
			<form action="signup.php" method="post">
				<label for="Voorletters"><b>voorletters</b><br></label>
				<input type="text" placeholder="Vul in je voorletters" name="voorletters" required><br><br>				
				<label for="Achternaam"><b>Achternaam</b><br></label>
				<input type="text" placeholder="Vul in je achternaam" name="achternaam" required><br><br>				
				<label for="Gebruikersnaam"><b>Gebruikersnaam</b><br></label>
				<input type="text" placeholder="Vul in je gebruikersnaam" name="gebruikersnaam" required><br><br>
				<label for="Wachtwoord"><b>Wachtwoord</b><br></label>
				<input type="password" placeholder="Vul in je wachtwoord" name="wachtwoord" required><br><br>
				<label for="Herhaal wachtwoord"><b>Herhaal wachtwoord</b><br></label>
				<input type="password" placeholder="Herhaal je wachtwoord" name="rewachtwoord" required><br><br>   
				<input type="submit" name="submit">
		</div>
		<a href="login.php"> Terug naar inloggen</a><br>
</html>