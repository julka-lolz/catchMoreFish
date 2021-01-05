<?php
class helper{

	public function field_validation($fieldnames){	
	
		// check if $fieldnames is an array
		if(is_array($fieldnames)){

			// if array, the code underneath will be executed
			$error = False; //as long as the $error = false we're good :)

			foreach($fieldnames as $fieldname){
				
				if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])){ 
					//echo $_POST[$fieldname] . "<br>";
					$error = True;
				}
			}

			echo $error;
			if(!$error){ // not false returns true. we don't want any errors'
				return true;
			}


			return false;
		}else{
			return "Fieldnames must be an array";
		}
		
	}
}
?>