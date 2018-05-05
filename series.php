<?php 
session_start(); 
include('templates/check.php');
include('templates/bdd.php');

	$requete = "SELECT * FROM episodes WHERE idSerie=".$_GET["id"];
	$requeteTitre = "SELECT nomSerie AS titre FROM series WHERE idSerie='".$_GET["id"]."'";
	$reponse = $bdd->query($requete);
	$reponseTitre = $bdd->query($requeteTitre);
	$titre=$reponseTitre->fetch();
	$verif = $reponse->fetch();
	if ($verif==false) {
		header('Location: user.php');
		exit();
	} else {

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $titre['titre']; ?></title>
	<?php include('templates/head.php') ?>
	<meta charset="utf-8">
</head>
<body>
	<header>
		<img src="img/toWatchListLogo.png">
		<a href="deconnexion.php">Se déconnecter</a>
	</header>
	<h1><?php echo $titre['titre']; ?> !</h1>
	<div id="liste">
		<ul>
			<?php
		while($donnees = $reponse->fetch()){
			/*if ($donnees['vu']==0) {*/
				echo "<li class='episodeList pasVu'>Episode ".$donnees['numEpisode']."</li>";
			/*} else {
				echo "<li class='episodeList vu'>Episode ".$donnees['numEpisode']."</li>";
			}*/
		}
		?>
		</ul>
			

	</div>

</body>
</html>

<?php
	}

?>