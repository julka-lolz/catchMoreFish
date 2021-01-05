﻿﻿<?php

include 'database.php';
include 'helper.php';

//make field falidation as extra security for sql injections.
if(isset($_POST['submit'])){
	
	$fieldnames = array(
		'product', 'type', 'lev_code', 'inkoopprijs', 'verkoopprijs'
	);
	$helper = new helper();
	$fields_validated = $helper->field_validation($fieldnames);

	if($fields_validated){	
		
		$db = new database();
		echo 'connected';

		$product = $_POST["product"];		
		$productcode = $_POST["productcode"];
		$type = $_POST["type"];
		$lev_code = $_POST["lev_code"];	
		$inkoopprijs = $_POST["inkoopprijs"];	
		$verkoopprijs = $_POST["verkoopprijs"];	

		echo 'Current fabriek is updated'."<br>";

		$db-> updateVoorraad($product, $type, $lev_code, $inkoopprijs, $verkoopprijs, $productcode);
	}
}
?>
<html>	
	<head>	
	</head>
		<div>
			<form action="updateVoorraad.php" method="post">
				<label for="product"><b>Product</b><br></label>
				<input type="hidden" name="productcode" value="<?php echo htmlspecialchars($_GET['productcode']);?>">
				<input type="text" name="product"><br><br>	
				<label for="type"><b>Type</b><br></label>
				<input type="text" placeholder="Vul in type" name="type"><br><br>	
				<label for="lev_code"><b>lev_code</b><br></label>
				<input type="text" placeholder="Vul in je lev_code" name="lev_code"><br><br>					
				<label for="inkoopprijs"><b>inkoopprijs</b><br></label>
				<input type="text" placeholder="Vul in inkoopprijs" name="inkoopprijs"><br><br>	
				<label for="verkoopprijs"><b>verkoopprijs</b><br></label>
				<input type="text" placeholder="Vul in verkoopprijs" name="verkoopprijs"><br><br>
				<input type="submit" name="submit">
		</div>
		<a href="welcome_medewerker.php"> Terug</a><br>
</html>