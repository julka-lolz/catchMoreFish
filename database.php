<?php

class database{

	private $host;
	private $username;
	private $password;
	private $database;
	private $charset;
	private $db;

	 // create class constants (admin and medewerker).
	const ADMIN = 1;
	const MEDEWERKER = 2;
	const KLANT = 3;

	//create function construct
	public function __construct(){

		$this->host = 'localhost';
		$this->gebruikersnaam = 'root';
		$this->wachtwoord = '';
		$this->database = 'examen';
		$this->charset = 'utf8';	

		try{
			// connectie with database			
			$dsn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
			$this->db = new PDO($dsn, $this->gebruikersnaam, $this->wachtwoord);
			$this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			//echo 'Succesfully connected to the database'."<br>";
		}	// catch error
		catch (PDOexception $a){			
			$this->db->rollback();
			echo "Signup has failed: ".$a->getMessage();
			throw $a;
			
		}

	}

	private function new_account($gebruikersnaam){
		//this function checks if the account already exists.		
		$stmt = $this->db->prepare('SELECT * FROM medewerker WHERE gebruikersnaam=:gebruikersnaam');
		$stmt->execute(['gebruikersnaam'=>$gebruikersnaam]);
		$result = $stmt->fetch();
		//the if loop checks if the account exists
		if(is_array($result) && count($result) > 0){
			return false;//does exists
		}

		return true;//does not exist
	}

	private function is_admin($gebruikersnaam){
		//this function checks if the user is an admin.
		$sql = "SELECT medewerker_admin FROM medewerker WHERE gebruikersnaam = :gebruikersnaam";
		$stmt = $this->db->prepare($sql);
		$stmt->execute(['gebruikersnaam'=>$gebruikersnaam]);
		// result is an associative array (key-value pair)
		$result = $stmt->fetch();
		
		if($result['medewerker_admin'] == self::ADMIN){
			return true;//user is an admin
		}

		// user is not admin
		return false;
	}

	

	public function login($gebruikersnaam, $wachtwoord){
		
		$sql = "
			SELECT 
				medewerkerscode,
				medewerker_admin,
				wachtwoord
			FROM medewerker			
			WHERE gebruikersnaam = :gebruikersnaam
		";
		echo $sql;

		// prepare returns an empty statement object. there is no data stored in $stmt.
		$stmt = $this->db->prepare($sql);
		// execute prepared statement. pass arg, which is an associative array. 		
		$stmt->execute(['gebruikersnaam'=>$gebruikersnaam]);
		// fetch should return an associative array (key, value pair)
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// check $result is an array
		if(is_array($result)){
		echo '1';
			// apply count on if $result is an array (and thus if user exists, only existing users should be able to login)
			if(count($result) > 0){
			echo '2';
				// get hashed_password from database result with key 'wachtwoord'
				$hashed_password = $result['wachtwoord'];
				var_dump( password_verify($wachtwoord, $hashed_password));

				// verify that user exists and that provided password is the same as the hashed password
				if($gebruikersnaam && password_verify($wachtwoord, $hashed_password)){
					session_start();
	
					// store userdata in session variable (=array)					
					$_SESSION['medewerkerscode'] = $result['medewerscode'];
					$_SESSION['gebruikersnaam'] = $gebruikersnaam;
					$_SESSION['medewerker_admin'] = $result['medewerker_admin'];
					$_SESSION['loggedin'] = true;
	
					// check if user is an administrator. If so, redirect to the admin page.
					// if not administrator, redirect to user page.
					if($this->is_admin($gebruikersnaam)){
						header("location: welcome_admin.php");
						//make sure that code below redirect does not get executed when redirected.
						exit;
					}

					// redirect user to the medewerker-page if not admin.
					header("location: welcome_medewerker.php");
					exit;
				}else{
					// returned an error message to show in span element in login form (login.php).
					return "Incorrect username and/or password. Please change your input and try again.";
				}
			}
		}else{
			// no matching user found in db. Make sure not to tell the user directly.
			return "Failed to login. Please try again";
		}
	}


	public function loginKlant($gebruikersnaam, $wachtwoord){
		
		$sql = "
			SELECT 
				klantcode,
				medewerker_admin,
				wachtwoord
			FROM klant			
			WHERE gebruikersnaam = :gebruikersnaam
		";
		echo $sql;

		// prepare returns an empty statement object. there is no data stored in $stmt.
		$stmt = $this->db->prepare($sql);
		// execute prepared statement. pass arg, which is an associative array. 		
		$stmt->execute(['gebruikersnaam'=>$gebruikersnaam]);
		// fetch should return an associative array (key, value pair)
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// check $result is an array
		if(is_array($result)){
		echo '1';
			// apply count on if $result is an array (and thus if user exists, only existing users should be able to login)
			if(count($result) > 0){
			echo '2';
				// get hashed_password from database result with key 'wachtwoord'
				$hashed_password = $result['wachtwoord'];
				var_dump( password_verify($wachtwoord, $hashed_password));

				// verify that user exists and that provided password is the same as the hashed password
				if($gebruikersnaam && password_verify($wachtwoord, $hashed_password)){
					session_start();
	
					// store userdata in session variable (=array)					
					$_SESSION['klantcode'] = $result['klantcode'];
					$_SESSION['gebruikersnaam'] = $gebruikersnaam;
					$_SESSION['medewerker_admin'] = $result['medewerker_admin'];
					$_SESSION['loggedin'] = true;				


					// redirect user to the medewerker-page if not admin.
					header("location: welcome_klant.php");
					exit;
				}else{
					// returned an error message to show in span element in login form (login.php).
					return "Incorrect username and/or password. Please change your input and try again.";
				}
			}
		}else{
			// no matching user found in db. Make sure not to tell the user directly.
			return "Failed to login. Please try again";
		}
	}

	// ADD FUNCTIONS

	public function addAccount($voorletters, $medewerker_admin=self::MEDEWERKER, $achternaam, $gebruikersnaam, $wachtwoord){
					
		//this functions inserts data from the form into the database.
		try{
			//transaction 			
			$this->db->beginTransaction();

			if(!$this->new_account($gebruikersnaam)){
				return "Gebruikersnaam bestaat al. Gebruik een ander gebruikersnaam, en probeer opnieuw.";
			}

			echo "begin transatie"."<br>";
			//add account	
			$sql1 = "
			INSERT INTO medewerker(
				medewerkerscode,
				medewerker_admin,
				voorletters,				
				achternaam,
				gebruikersnaam,
				wachtwoord 
			) 
			VALUES(
				:medewerkerscode,
				:medewerker_admin,
				:voorletters,				
				:achternaam,
				:gebruikersnaam,
				:wachtwoord
			)";

			echo "sql statement: ".$sql1."<br>";
			// prepare
			$stmt1 = $this->db->prepare($sql1);							
			//$type_id = 1;
			$hashPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);
			$medewerkerscode = $this->db->lastInsertId();
			//execute
			$stmt1->execute([
				'medewerkerscode' => $medewerkerscode,
				'medewerker_admin'=>$medewerker_admin,
				'voorletters'=>$voorletters,				
				'achternaam'=>$achternaam,
				'gebruikersnaam'=>$gebruikersnaam,
				'wachtwoord'=>$hashPassword
			]);
			
			$this->db->commit();
			echo "gelukt";
			
			// check if there's a session (created in login, should only visit here in case of admin login)
			if(isset($_SESSION) && $_SESSION['medewerker_admin'] == self::ADMIN){
				return "New user has been succesfully added to the database";
			}
		}
		catch (PDOexception $a){			
			$this->db->rollback();
			echo "Signup failed: ".$a->getMessage();
			throw $a;
			
		}
	}


	public function addKlant($voorletters, $medewerker_admin=self::KLANT, $achternaam, $gebruikersnaam, $wachtwoord){
					
		//this functions inserts data from the form into the database.
		try{
			//transaction 			
			$this->db->beginTransaction();

			if(!$this->new_account($gebruikersnaam)){
				return "Gebruikersnaam bestaat al. Gebruik een ander gebruikersnaam, en probeer opnieuw.";
			}

			echo "begin transatie"."<br>";
			//add account	
			$sql1 = "
			INSERT INTO klant(
				klantcode,
				medewerker_admin,
				voorletters,				
				achternaam,
				gebruikersnaam,
				wachtwoord 
			) 
			VALUES(
				:klantcode,
				:medewerker_admin,
				:voorletters,				
				:achternaam,
				:gebruikersnaam,
				:wachtwoord
			)";

			echo "sql statement: ".$sql1."<br>";
			// prepare
			$stmt1 = $this->db->prepare($sql1);							
			//$type_id = 1;
			$hashPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);			
			//execute
			$stmt1->execute([		
				'klantcode'=>NULL,
				'medewerker_admin'=>$medewerker_admin,
				'voorletters'=>$voorletters,				
				'achternaam'=>$achternaam,
				'gebruikersnaam'=>$gebruikersnaam,
				'wachtwoord'=>$hashPassword
			]);
			
			$this->db->commit();
			echo "gelukt";			
		}
		catch (PDOexception $a){			
			$this->db->rollback();
			echo "Signup failed: ".$a->getMessage();
			throw $a;
			
		}
	}


	public function addFabriek($lev_code, $leverancier, $telefoon){
					
		//this functions inserts data from the form into the database.
		try{
			//transaction 			
			$this->db->beginTransaction();

			echo "begin transatie"."<br>";
			//add account	
			$sql1 = "
			INSERT INTO leverancier(
				lev_code,
				leverancier,
				telefoon 
			) 
			VALUES(
				:lev_code,
				:leverancier,
				:telefoon
			)";

			echo "sql statement: ".$sql1."<br>";
			// prepare
			$stmt1 = $this->db->prepare($sql1);						
			
			//execute
			$stmt1->execute([
				'lev_code' => $lev_code,
				'leverancier'=>$leverancier,
				'telefoon'=>$telefoon
			]);
			
			$this->db->commit();
			echo "gelukt";			
		}
		catch (PDOexception $a){			
			$this->db->rollback();
			echo "Signup failed: ".$a->getMessage();
			throw $a;
			
		}
	}

	public function addLocatie($locatiecode, $locatie){
					
		//this functions inserts data from the form into the database.
		try{
			//transaction 			
			$this->db->beginTransaction();

			echo "begin transatie"."<br>";
			//add account	
			$sql1 = "
			INSERT INTO locatie(
				locatiecode,
				locatie
			) 
			VALUES(
				:locatiecode,
				:locatie
			)";

			echo "sql statement: ".$sql1."<br>";
			// prepare
			$stmt1 = $this->db->prepare($sql1);						
			
			//execute
			$stmt1->execute([
				'locatiecode' =>$locatiecode,
				'locatie'=>$locatie
			]);
			
			$this->db->commit();
			echo "gelukt";			
		}
		catch (PDOexception $a){			
			$this->db->rollback();
			echo "Signup failed: ".$a->getMessage();
			throw $a;
			
		}
	}

		public function addVoorraad($product, $type, $lev_code, $inkoopprijs, $verkoopprijs, $productcode){
					
		//this functions inserts data from the form into the database.
		try{
			//transaction 			
			$this->db->beginTransaction();

			echo "begin transatie"."<br>";
			//add account	
			$sql1 = "
			INSERT INTO artikel(
				productcode,
				product,
				type,
				lev_code,
				inkoopprijs,
				verkoopprijs
			) 
			VALUES(				
				:productcode,
				:product,
				:type,
				:lev_code,
				:inkoopprijs,
				:verkoopprijs
			)";

			echo "sql statement: ".$sql1."<br>";
			// prepare
			$stmt1 = $this->db->prepare($sql1);						
			
			//execute
			$stmt1->execute([				
				'productcode'=>$productcode,
				'product'=>$product,
				'type'=>$type,
				'lev_code'=>$lev_code,
				'inkoopprijs'=>$inkoopprijs,
				'verkoopprijs'=>$verkoopprijs
			]);
			
			$this->db->commit();
			echo "gelukt";			
		}
		catch (PDOexception $a){			
			$this->db->rollback();
			echo "Signup failed: ".$a->getMessage();
			throw $a;
			
		}
	}
	
	public function addReservatie($productcode, $klantcode, $notes, $reservatie_datum, $reservatie_tijd){
					
		//this functions inserts data from the form into the database.
		try{
			//transaction 			
			$this->db->beginTransaction();

			//echo "begin transatie"."<br>";
			//add account	
			$sql1 = "
			INSERT INTO reservaties(
				reservatiecode,
				productcode,
				klantcode,
				notes,
				reservatie_datum,
				reservatie_tijd
			) 
			VALUES(				
				NULL,
				:productcode,
				:klantcode,
				:notes,
				:reservatie_datum,
				:reservatie_tijd
			)";

			//echo "sql statement: ".$sql1."<br>";
			// prepare
			$stmt1 = $this->db->prepare($sql1);						
			
			//execute
			$stmt1->execute([				
				'productcode'=>$productcode,
				'klantcode'=>$klantcode,
				'notes'=>$notes,				
				'reservatie_datum'=>$reservatie_datum,
				'reservatie_tijd'=>$reservatie_tijd
			]);
			
			$this->db->commit();
			echo "Je reservatie is gelukt";			
		}
		catch (PDOexception $a){			
			$this->db->rollback();
			echo "Signup failed: ".$a->getMessage();
			throw $a;
			
		}
	}

	//GET DATA FUNCTIONS


	// totale waarde van de voorraad tonen -> per locatie inkoopprijs/verkoopprijs) -> stel amsterdam heeft 5 hengels. die kosten 10 euro. dan moet de totale waarde van de 5 hengels tonen (=50 euro). totale verkoopsom. stel ook 3 haakjes van 1 euro. dan zou de totale prijs 53 euro zijn.
	// minimum voor de voorraad implementeren (notification/message als voorraad onder minimum komt) -> ontwerpdocumentatie: notification als voorraad < 5.

	public function get_voorraad_information(){		
		$sql = "SELECT voorraad.aantal, artikel.product, locatie.locatie 
				FROM ((voorraad 
				INNER JOIN artikel on voorraad.productcode = artikel.productcode) 
				INNER JOIN locatie on voorraad.locatiecode = locatie.locatiecode)";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([]);		
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}

	public function get_medewerker_information(){
		$sql = "SELECT medewerkerscode, voorletters, achternaam, gebruikersnaam FROM medewerker";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();		
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}

	public function get_fabriek_information(){
		$sql = "SELECT lev_code, leverancier, telefoon FROM leverancier";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();		
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}

	public function get_locatie_information(){
		$sql = "SELECT locatiecode, locatie FROM locatie";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([]);		
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}

	public function get_product_information(){
		$sql = "SELECT artikel.productcode, artikel.product, artikel.type, leverancier.leverancier, artikel.inkoopprijs, artikel.verkoopprijs, voorraad.aantal 
				FROM artikel 
				INNER JOIN leverancier on artikel.lev_code = leverancier.lev_code
				INNER join voorraad on artikel.productcode=voorraad.productcode";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([]);		
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);			
		return $results;		
	}

	public function get_aantal_information(){
		$sql = "SELECT aantal FROM voorraad ";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([]);		
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$minimaal_aantal = 5;
		foreach ($results as $value)
		{//Omdat $results een array is met als inhoudt meerdere Arrays loopen wij 2 keer. 1 keer om de resultaten te 'openen' en daarna nog eens om door de voorraad van het artikel zelf te loopen.
			foreach($value as $voorraadhvl)
			{
				if($voorraadhvl <= $minimaal_aantal)
				{
					echo "<font color='red'><b>One of your items is below minimum stock. Please restock!</b></font>";
				}
			}
		}
/*		foreach($results as $voorraadnu)
		{
			if($results.$voorraadnu <= 5)
			{
				echo "<script type='text/javascript'>alert('The stock is under the minimum stock. Restock it!');</script>";
			}
		}*/
//			if($results <= 5){
//				echo "<script type='text/javascript'>alert('The stock is under the minimum stock. Restock it!');</script>";
//			}
		return $results;		
	}

	/*public function ifUnderMinQuantity(){
		
		if (get_aantal_information() <= 5) {
			echo "<script type='text/javascript'>alert('The stock is under the minimum stock. Restock it!');</script>";
		}
	}*/

	public function get_reservatie_information(){		
		$sql = "SELECT reservatiecode, artikel.product, klant.voorletters, klant.achternaam, notes, reservatie_datum, reservatie_tijd 
		FROM ((reservaties 
		INNER JOIN artikel on reservaties.productcode=artikel.productcode)
		INNER JOIN klant on reservaties.klantcode=klant.klantcode);";
		/*$sql = "SELECT reservatiecode, artikel.product, notes, reservatie_datum, reservatie_tijd 
		FROM reservaties 
		INNER JOIN artikel on reservaties.productcode=artikel.productcode";*/
		$stmt = $this->db->prepare($sql);
		$stmt->execute([]);		
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}

	// DELETE FUNCTIONS

	public function deleteMedewerker($medewerkerscode){
	
		try{
			$this->db->beginTransaction();

			$stmt = $this->db->prepare("DELETE FROM medewerker WHERE medewerkerscode=:medewerkerscode");
			$stmt->execute(['medewerkerscode'=>$medewerkerscode]);			

			$this->db->commit();

		}catch(PDOexception $e){
			$this->db->rollback();
			echo 'Error: '.$e->getMessage();
		}
	}

	public function deleteFabriek($lev_code){
	
		try{
			$this->db->beginTransaction();

			$stmt = $this->db->prepare("DELETE FROM leverancier WHERE lev_code=:lev_code");
			$stmt->execute([
			'lev_code'=>$lev_code
			]);			

			$this->db->commit();

		}catch(PDOexception $e){
			$this->db->rollback();
			echo 'Error: '.$e->getMessage();
		}
	}

	public function deleteLocatie($locatiecode){
	
		try{
			$this->db->beginTransaction();

			$stmt = $this->db->prepare("DELETE FROM locatie WHERE locatiecode=:locatiecode");
			$stmt->execute([
			'locatiecode'=>$locatiecode
			]);			

			$this->db->commit();

		}catch(PDOexception $e){
			$this->db->rollback();
			echo 'Error: '.$e->getMessage();
		}
	}

	public function deleteVoorraad($productcode){
	
		try{
			$this->db->beginTransaction();

			$stmt = $this->db->prepare("DELETE FROM artikel WHERE productcode=:productcode");
			$stmt->execute([
			'productcode'=>$productcode
			]);			

			$this->db->commit();

		}catch(PDOexception $e){
			$this->db->rollback();
			echo 'Error: '.$e->getMessage();
		}
	}

	// EDITING/UPDATING FUNCTIONS

	public function updateMedewerker($medewerkerscode, $medewerker_admin, $voorletters, $achternaam, $gebruikersnaam, $wachtwoord){
	
		try{
			//begin the transaction
			$this->db->beginTransaction();
			echo "Beginning the transaction"."<br>";			
			$sql = "UPDATE medewerker SET medewerkerscode=:medewerkerscode, medewerker_admin=:medewerker_admin, voorletters=:voorletters, achternaam=:achternaam, gebruikersnaam=:gebruikersnaam, wachtwoord=:wachtwoord";
			echo "sql statement: ".$sql."<br>";
			//prepare
			$stmt = $this->db->prepare($sql);			
			//execute 			
			$stmt->execute([
			'medewerkerscode'=>$medewerkerscode,
			'medewerker_admin'=>$medewerker_admin,			
			'voorletters'=>$voorletters,
			'achternaam'=>$achternaam,
			'gebruikersnaam'=>$gebruikersnaam,
			'wachtwoord'=>$wachtwoord
			]);
			
			$this->db->commit();
			echo "Medewerker is updated";
			header("location: welcome_admin.php");
		}
		catch (PDOexception $a){			
			$this->db->rollback();
			echo "Signup failed: ".$a->getMessage();
			throw $a;
			
		}
	}

	public function updateFabriek($lev_code, $leverancier, $telefoon){
	
		try{
			//begin the transaction
			$this->db->beginTransaction();
			echo "Beginning the transaction"."<br>";			
			$sql = "UPDATE leverancier SET leverancier=:leverancier, telefoon=:telefoon WHERE lev_code=:lev_code";
			echo "sql statement: ".$sql."<br>";
			//prepare
			$stmt = $this->db->prepare($sql);			
			//execute 			
			$stmt->execute([
			'lev_code'=>$lev_code,
			'leverancier'=>$leverancier,			
			'telefoon'=>$telefoon
			]);
			
			$this->db->commit();
			echo "Fabriek is updated";
			header("location: welcome_admin.php");
		}
		catch (PDOexception $a){			
			$this->db->rollback();
			echo "Signup failed: ".$a->getMessage();
			throw $a;
			
		}
	}

	public function updateLocatie($locatie, $locatiecode){
	
		try{
			//begin the transaction
			

			$this->db->beginTransaction();

			echo "Beginning the transaction"."<br>";			
			$sql = "UPDATE locatie SET locatie=:locatie WHERE locatiecode=:locatiecode";
			echo "sql statement: ".$sql."<br>";
			//prepare
			$stmt = $this->db->prepare($sql);			
			//execute 			
			$stmt->execute([			
			'locatiecode'=>$locatiecode,
			'locatie'=>$locatie			
			]);
			
			$this->db->commit();
			echo "locatie is updated";
			//header("location: welcome_admin.php");
		}
		catch (PDOexception $a){			
			$this->db->rollback();
			echo "Signup failed: ".$a->getMessage();
			throw $a;
			
		}
	}

	public function updateVoorraad($product, $type, $lev_code, $inkoopprijs, $verkoopprijs, $productcode){
	
		try{
			//begin the transaction
			$this->db->beginTransaction();
			echo "Beginning the transaction"."<br>";			
			$sql = "UPDATE artikel SET product=:product, type=:type, lev_code=:lev_code, inkoopprijs=:inkoopprijs, verkoopprijs=:verkoopprijs WHERE productcode=:productcode";
			echo "sql statement: ".$sql."<br>";
			//prepare
			$stmt = $this->db->prepare($sql);			
			//execute 			
			$stmt->execute([			
			'productcode'=>$productcode,
			'product'=>$product,
			'type'=>$type,
			'lev_code'=>$lev_code,
			'inkoopprijs'=>$inkoopprijs,
			'verkoopprijs'=>$verkoopprijs
			]);
			
			$this->db->commit();
			echo "voorraad is updated";
			header("location: welcome_medewerker.php");
		}
		catch (PDOexception $a){			
			$this->db->rollback();
			echo "Signup failed: ".$a->getMessage();
			throw $a;
			
		}
	}

}
?>