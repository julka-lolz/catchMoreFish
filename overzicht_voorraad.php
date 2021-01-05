<?php
// initialize the session
session_start();

include 'database.php';

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
	header('location: login.php');
	exit;
}

$db = new database();

if (isset($_GET['productcode'])) {
	$productcode = $_GET['productcode'];

	$db->deleteVoorraad($productcode);
	// redirect to overview
	header("location: welcome_medewerker.php");
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
					<li><a class="active" href="welcome_medewerker.php">Home</a></li>
					<li><a href="add_Voorraad.php">Voorraad toevoegen</a></li>					
					<li><a href="contact.php">Contact</a></li>					
				</ul>
			</div>
				
			<div class="content">
								<?php
					$db = new database();
					$db->get_aantal_information(null);
					$results = $db->get_product_information();
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
							<th colspan="2">action</th>
						</tr>
					</thead>
					<?php foreach($results as $rows => $row){ ?>

						<?php $row_id = $row['productcode']; ?>
						<tr>
							<?php   foreach($row as $row_data){?>

					
								<td>
									<?php echo $row_data ?>
								</td>
							<?php } ?>

							<td>
								<a href="updateVoorraad.php?productcode=<?php echo $row_id; ?>&productcode=<?php echo $row['productcode']?>" class="edit_btn" >Edit</a>
							</td>
							<td>
								<a href="overzicht_voorraad.php?productcode=<?php echo $row_id; ?>&productcode=<?php echo $row['productcode']?>" class="del_btn">Delete</a>
							</td>
						</tr>
					<?php } ?>
				</table>
			</div>
		</div>
	</body>
</html>