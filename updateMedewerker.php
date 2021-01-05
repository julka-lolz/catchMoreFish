﻿﻿<?php

include 'database.php';
include 'helper.php';

//make field falidation as extra security for sql injections.
if(isset($_POST['submit'])){
	
	$fieldnames = array(
		'medewerkerscode','medewerker_admin', 'voorletters', 'achternaam', 'gebruikersnaam', 'wachtwoord'
	); 
	$helper = new helper();
	$fields_validated = $helper->field_validation($fieldnames);

	if($fields_validated){	
		
		$db = new database();
		echo 'connected';
		$medewerkerscode = $_POST["medewerkerscode"];
		$medewerker_admin = $_POST["medewerker_admin"];
		$voorletters = $_POST["voorletters"];
		$achternaam = $_POST["achternaam"];
		$gebruikersnaam = $_POST["gebruikersnaam"];
		$wachtwoord = $_POST["wachtwoord"];

		echo 'Current medewerker is updated'."<br>";

		$db->updateMedewerker($medewerkerscode, $medewerker_admin,$voorletters,$achternaam,$gebruikersnaam, $wachtwoord);
	}
}
?>
<html>	
	<head>	
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

	</head>
		<div>
			<form action="updateMedewerker.php" method="post">



				<div class="form-group">
				<!-- codes moeten niet met de hand ingevuld worden -->
				<label for="medewerkerscode"><b>Medewerkerscode</b><br></label>
				<input type="text" class="form-control" placeholder="Vul uw code in" name="medewerkerscode"><br><br>
				</div>

				<div class="form-group">
				<!-- gebruik select ipv inputfield -->
				<label for="Medewerker_admin"><b>Medewerker/Admin</b><br></label>
				<input type="text" class="form-control" placeholder="Kies tussen een admin en een medewerker" name="medewerker_admin"><br><br>
				</div>

				<div class="form-group">
				<label for="Voorletters"><b>Voorletters</b><br></label>
				<input type="text"  class="form-control" placeholder="Vul uw voorletters in" name="voorletters"><br><br>
				</div>

				<div class="form-group">
				<label for="Achternaam"><b>Achternaam</b><br></label>
				<input type="text" class="form-control" placeholder="Vul uw achternaam in" name="achternaam"><br><br>
				</div>

				<div class="form-group">
				<label for="Gebruikersnaam"><b>Gebruikersnaam</b><br></label>
				<input type="text" class="form-control" placeholder="Vul uw gebruikersnaam in" name="gebruikersnaam"><br><br>	
				</div>

				<div class="form-group">
				<label for="Wachtwoord"><b>Wachtwoord</b><br></label>
				<input type="password" class="form-control" placeholder="Vul uw wachtwoord in" name="wachtwoord"><br><br>	
				</div>
				<input type="submit" name="submit">
		</div>
		<a href="welcome_admin.php"> Back home</a><br>
</html>