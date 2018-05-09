<?php 
session_start(); 
include('templates/check.php');
include('templates/bdd.php');
	?>

	<?php
	if (isset($_GET['attempt'])) {
		if ((isset($_POST['nomSerie']))&&(isset($_POST['nbrSaison']))&&(isset($_POST['saison1']))&&(isset($_SESSION['id']))) {
			$nom=$_POST['nomSerie'];
			$nbrSaison=$_POST['nbrSaison'];
			for ($i=0; $i < $nbrSaison; $i++) {
				if (($_POST["saison".($i+1)]==0)||(is_null($_POST["saison".($i+1)]))) {
					$nbrEpisodes[]=1;
				} else {
					$nbrEpisodes[]=$_POST["saison".($i+1)];
				}
			}

			$req = $bdd->prepare('INSERT INTO series(nomSerie, idUser) VALUES(:nomSerie, :idUser)');
			$req->execute(array('nomSerie' => $nom, 'idUser' => $_SESSION['id']));
			$serieId=$bdd->lastInsertId();
			$numeroEpisode=0;
			$req2 = $bdd->prepare('INSERT INTO episodes(idSerie, idUser, saison, numEpisode) VALUES(:idSerie, :idUser, :saison, :numEpisode)');
			for ($i=0; $i < $nbrSaison; $i++) { 
				for ($j=0; $j < $nbrEpisodes[$i]; $j++) {
					$numeroEpisode++;
					$saison=$i+1;
					$req2->execute(array('idSerie' => $serieId, 'idUser' => $_SESSION['id'], 'saison' => $saison, 'numEpisode' => $numeroEpisode));
				}
			}
		}else{$message="Veuillez remplir tous les champs du formulaire";}
	}
		
	?>
<!DOCTYPE html>
<html>
<head>
	<title>To Watch List - Creer une serie</title>
	<?php include('templates/head.php'); ?>
	<meta charset="utf-8">
</head>
<body>
	<?php if (isset($message)) {
	    echo '<div class="error alert_pages"><i class="fas fa-times"></i> '.$message.'</div>';
	} ?>
	<?php include ('templates/header.php'); ?>
	<form id="formSerie" method="post" action="createserie.php?attempt=ok">
		<div id="infoSerieForm">
			<label>Nom de la serie :</label>
			<input type="text" name="nomSerie">
			<label>Nombre de saison :</label>
			<select id="nbrSaison" name="nbrSaison">
				<option value="1">1</option>
			    <option value="2">2</option>
			    <option value="3">3</option>
			    <option value="4">4</option>
			    <option value="5">5</option>
			    <option value="6">6</option>
			    <option value="7">7</option>
			    <option value="8">8</option>
			    <option value="9">9</option>
			</select>
		</div>
		<ul id="nbrEpisode">
			
		</ul>
		<input type="submit" name="bouttonSerie">
	</form>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
	$("#nbrEpisode").append('<li><label>Saison 1</label><input class="nbrEpisode" type="number" value="1" min="1" max="30" name="saison1"> épisode(s)</li>');
	$('#nbrSaison').change(function() {
		var nbr=$('#nbrSaison').val();
		if ((nbr>=1)&&(nbr<=9)) {
			$("#nbrEpisode").html("");
			for (i=0; i<nbr; i++){
				$("#nbrEpisode").append('<li><label>Saison '+(i+1)+'</label><input class="nbrEpisode" type="number" value="1" min="1" max="30" name="saison'+(i+1)+'"> épisode(s)</li>');
			}
		}
	});

	$(".nbrEpisode").change(function(e){
		if(e.target.value<1){
			e.target.value=1;
		} else if(e.target.value>30){
			e.target.value=30;
		}
	});
</script>
</body>
</html>