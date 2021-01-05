﻿﻿<?php

include 'database.php';
include 'helper.php';


//make field falidation as extra security for sql injections.
if(isset($_POST['submit'])){
	
	$fieldnames = array(
		'locatie'
	);
	$helper = new helper();
	$fields_validated = $helper->field_validation($fieldnames);

	if($fields_validated){	
		
		$db = new database();
		echo 'connected';

		$locatie = $_POST["locatie"];	
		$locatiecode = $_POST["locatiecode"];
		echo 'Current locatie is updated'."<br>";

		$db->updateLocatie($locatie, $locatiecode);
		echo 'gelukt'; 
	}
}
?>
<html>	
	<head>	
	</head>
		<div>
			<form action="updateLocatie.php" method="post">							
				<label for="Locatie"><b>Locatie</b><br></label>
				<input type="hidden" name="locatiecode" value="<?php echo htmlspecialchars($_GET['locatiecode']);?>">
				<input type="text" placeholder="Vul in je locatie" name="locatie"><br><br>									 
				<input type="submit" name="submit">
		</div>
		<a href="welcome_admin.php"> Terug</a><br>
</html>