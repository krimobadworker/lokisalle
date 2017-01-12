<!DOCTYPE html>
<html>
<head>
	<title>Lokisalle - acceuil</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/main.js"></script>
</head>
<body>
	<header>
	
		<nav>
			<div class="row">
				<div class="col-md-3 col-md-offset-2 ">
					<ul  class="nav nav-pills menu-gauche">
						<li role="presentation" class="dropdown"><a href="<?= RACINE_SITE .'index.php'?>">Lokisalle</a></li>
						<li role="presentation" class="dropdown"><a href="">Qui Sommes Nous</a></li>
						<li role="presentation" class="dropdown"><a href="">Contact</a></li>
					</ul>
				</div>
				<div class="col-md-4 col-md-offset-3">
					<ul  class="nav nav-pills menu-droit col-md-5 col-md-offset-2">
						<li role="presentation" class="active"><a href="">Espace Membre</a>
							<ul class="dropdown-menu">
								<li role="presentation" class="active"><a  class="dropdown-toggle" data-toggle="dropdown" href="#">Inscription</a></li>
								<li role="presentation" class="active"><a  class="dropdown-toggle" data-toggle="dropdown" href="#" >Connexion</a></li>
								<li role="presentation" class="active"><a  class="dropdown-toggle" data-toggle="dropdown" href="#" >Profil</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="clear"></div>
	</header>
	<section>