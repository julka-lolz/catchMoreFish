<?php
include 'database.php';
include 'helper.php';

//make field falidation as extra security for sql injections.
if(isset($_POST['submit'])){

	$fieldnames = array('gebruikersnaam', 'wachtwoord');
	$helper = new helper();
	$fields_validated = $helper->field_validation($fieldnames);
	
	if($fields_validated){
				
		$db = new database();

		$gebruikersnaam = $_POST['gebruikersnaam'];
		$wachtwoord = $_POST['wachtwoord'];

		echo 'hallo'.'<br>';		
		$loginError = $db->login($gebruikersnaam, $wachtwoord);
	}
}

?>
<html>
	<head>
	<style>
		div{
			float: center;
			text-align: center;			
			margin-left: 40%;
			margin-right:40%;
			border-style: inset;
			background-color: white;
		}
		body{
			font-family: calibri;
			background-color: #00e5ff;
			color: #0720bf;
		}
	</style>
	</head>
	<body>    
		<div>
			<h2>log in Medewerker & Admin</h2>
			<form action="login.php" method="post">
			<label for="Gebruikersnaam"><b>Gebruikersnaam</b><br></label>
			<input type="text" placeholder="Vul uw gebruikersnaam in" name="gebruikersnaam" required><br><br>
			<label for="Wachtwoord"><b>Wachtwoord</b><br></label>
			<input type="password" placeholder="Vul uw wachtwoord in" name="wachtwoord" required><br><br>
			<input type="submit" name="submit"><br><br>	
			<a href="signup.php"> Geen accound? Click hier en maak een nieuwe aan.</a><br>
		</div>
	</body>
</html>