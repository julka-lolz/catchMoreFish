
<?php
// initialize the session
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
	header('location: loginKlant.php');
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
					<li><a class="active" href="welcome_klant.php">Home</a></li>
					<li><a href="overzicht_voorraad_klant.php">Overzicht voorraad </a></li>					
									
				</ul>
			</div>
			
			<div class="content">
				<?php echo "Welcome " . htmlentities( $_SESSION['gebruikersnaam']) ."!" ?>
			</div>
		</div>
	</body>
</html>