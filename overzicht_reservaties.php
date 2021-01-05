<?php
// initialize the session
session_start();

include 'database.php';

$db = new database();

	if (isset($_GET['reservatiecode'])) {
		$reservatiecode = $_GET['reservatiecode'];	
		// redirect to overview
		header("location: welcome_admin.php");
		exit;
	}

	if(isset($_POST['export'])){
	$filename = "user_data_export.xls";
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");
	$print_header = false;

	$result = $db->get_reservatie_information();
	if(!empty($result)){
		foreach($result as $row){
			if(!$print_header){
				echo implode("\t", array_keys($row)) ."\n";
				$print_header=true;
			}
			echo implode("\t", array_values($row)) ."\n";
		}
	}
	exit;
		   
}

?>

<html>
	<head>
	<title>Welcome!</title>
	<style>
		body{
			font-family: calibri;
			background-color: #00e5ff;
			color: #0720bf;
		}
		.header{
			margin: auto;
			height: 100;
		}
		.header img{
			float: left;
		}
		.header p{
			text-align: center;			
		}
		.header a{
			float: right;
			border: 2px solid black;
			border-radius: 8px;				
		}
		ul {
		  list-style-type: none;
		  margin: 0;
		  padding: 0;
		}
		li a {
		  display: block;
		  width: 150;
		  background-color: white;
		}
		.navbar{
			float:left;
			text-align: center;
		}
		.content{
			text-align: center;
			padding-left: 20%;
			padding-right:10%;
		}
		.midden{
			padding-top:10;
		}

	</style>
	</head>
	<body>
		<div class="header">
			<img src="img/logo.jpg" alt="Logo" width="120" height="100">
			<p>Catch more Fish</p>
			<a href="logout.php">Logout</a>
		</div>
		<div class="midden">
			<div class="navbar">
				<ul>
					<li><a class="active" href="welcome_admin.php">Home</a></li>										
										
				</ul>
			</div>
				
			<div class="content">
								<?php
					$db = new database();					
					$results = $db->get_reservatie_information();					
					$columns = array_keys($results[0]);
					?>

				<table>
					<thead>
						<tr>
							<?php foreach($columns as $column){ ?>
								<th>
									<strong> <?php echo $column ?> </strong>
								</th>
							<?php } ?>
							
						</tr>
					</thead>
					<?php foreach($results as $rows => $row){ ?>

						<?php $row_id = $row['reservatiecode']; ?>
						<tr>
							<?php   foreach($row as $row_data){?>

					
								<td>
									<?php echo $row_data ?>
								</td>
							<?php } ?>							
						</tr>
					<?php } ?>
				</table>
				<form action='overzicht_reservaties.php' method='POST'>
					<input type='submit' name='export' value='Export to excel file' />
				</form>
			</div>
		</div>
	</body>
</html>