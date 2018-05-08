<?php 
session_start(); 
include('templates/check.php');
include('templates/bdd.php');

	if (!isset($_GET["id"])){
		header('Location: user.php');
	}

	//$requete = "SELECT * FROM episodes WHERE idSerie=".$_GET["id"];
	$requeteTitre = "SELECT nomSerie AS titre FROM series WHERE idSerie='".$_GET["id"]."'";
	//$reponse = $bdd->query($requete);
	$reponseTitre = $bdd->query($requeteTitre);
	$titre=$reponseTitre->fetch();

	$requete = $bdd->prepare('SELECT * FROM episodes WHERE idSerie=:id');
	$requete->execute(array('id' => $_GET['id']));

	$episodes=$requete->fetchAll();

	if (count($episodes)===0) {
		header('Location: user.php');
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
	<?php include ('templates/header.php'); ?>
	<h1><?php echo $titre['titre']; ?> !</h1>
	<div id="liste">
		<ul>
			<?php
			$actualSeason=1;
		foreach($episodes as $episode){
			if ($episode['saison']!==$actualSeason) {
				$actualSeason=$episode['saison'];
				echo "<h2>saison ".$actualSeason."</h2>";
			}
			if ($episode['vu']==0) {
				echo "<li class='episodeList pasVu'>Episode ".$episode['numEpisode']."</li>";
			} else {
				echo "<li class='episodeList vu'>Episode ".$episode['numEpisode']."</li>";
			}
		}
		?>
		</ul>		

	</div>

</body>
</html>

<?php
	}

?>