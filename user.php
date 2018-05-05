<?php 
session_start(); 
include('templates/check.php');
include('templates/bdd.php');

	?>

	<!DOCTYPE html>
	<html>
	<head>
		<title>Towatchlist - <?php echo $_SESSION['login']; ?></title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<meta charset="utf-8">
	</head>
	<body>
		<header>
			<img src="img/toWatchListLogo.png">
			<a href="deconnexion.php">Se déconnecter</a>
		</header>
		<h1>Salut <?php echo $_SESSION['login']; ?> !</h1>
		<div id="liste">

		<?php 

		$requete = "SELECT * FROM series WHERE idUser='".$_SESSION["id"]."'";
		$reponse = $bdd->query($requete);
		$nbLignes = $bdd->query("SELECT count(*) AS nb FROM series WHERE idUser='".$_SESSION["id"]."'");
		$nbLigne = $nbLignes->fetch();

		if ($nbLigne['nb']==0) {
			echo "<a href='createserie.php'>Créer votre première série !</a>";
		} else {
			echo "<ul>";
			while($donnees = $reponse->fetch()){
				echo "<li><a href=series.php?id=".$donnees['idSerie'].">".$donnees['nomSerie']."</a></li>";	
			}
			echo "</ul>";
		}

		?>
	</div>
	
	</body>
	</html>
