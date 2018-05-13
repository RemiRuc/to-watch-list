<?php 
session_start(); 
include('templates/check.php');
include('templates/bdd.php');
	?>

	<?php
	if (isset($_GET['id'])) {

		$req = $bdd->prepare('SELECT * FROM series WHERE idSerie = :idSerie LIMIT 1');
        $req->execute([':idSerie' => $_GET['id']]);
        $serie=$req->fetch();

        $req = $bdd->prepare('SELECT lien FROM image WHERE idImage = :idImage LIMIT 1');
        $req->execute([':idImage' => $serie['idImage']]);
        $image=$req->fetch();

        $req = $bdd->prepare('SELECT COUNT(*) as total FROM episodes WHERE idSerie=:idSerie GROUP BY saison');
        $req->execute([':idSerie' => $_GET['id']]);
        $countSerie=$req->fetchAll();

		if (isset($_GET['attempt'])) {
		$id = $_GET['id'];
		if ((isset($_POST['nomSerie']))&&(isset($_POST['nbrSaison']))&&(isset($_POST['saison1']))&&(isset($_SESSION['id']))&&(isset($_FILES['image']))&& (isset($_FILES['image']['name'])) && (!empty($_POST['nomSerie']))) {
			$nom=$_POST['nomSerie'];
			$nbrSaison=$_POST['nbrSaison'];
			
			for ($i=0; $i < $nbrSaison; $i++) {
				if (!isset($_POST["saison".($i+1)])) {
					$nbrEpisodes[]=1;
				} else {
					$nbrEpisodes[]=$_POST["saison".($i+1)];
				}
			}
			

						$req = $bdd->prepare('DELETE FROM episodes
						  WHERE idSerie=:idSerie');
						$req->execute(array(
						'idSerie' => $_GET['id'],
						));

						$req = $bdd->prepare('UPDATE series SET nomSerie = :nomSerie WHERE idSerie = :idSerie ');
						$req->execute(array('nomSerie' => $nom, 'idSerie' => $id));
						$numeroEpisode=0;
						$req2 = $bdd->prepare('INSERT INTO episodes(idSerie, idUser, saison, numEpisode) VALUES(:idSerie, :idUser, :saison, :numEpisode)');
						for ($i=0; $i < $nbrSaison; $i++) { 
							for ($j=0; $j < $nbrEpisodes[$i]; $j++) {
								$numeroEpisode++;
								$saison=$i+1;
								$req2->execute(array('idSerie' => $id, 'idUser' => $_SESSION['id'], 'saison' => $saison, 'numEpisode' => $numeroEpisode));
							}
						}
						header('Location: user.php');
					
		}else{$message="Veuillez remplir tous les champs du formulaire";}


		if (isset($_FILES['image'])){
			$tailleMax=2097152;
			$extensionsValides= array('jpg','jpeg','png','gif');
			if ($_FILES['image']['size']<=$tailleMax) {
				$extensionUpload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
				if (in_array($extensionUpload, $extensionsValides)) {
					$lien="img/series/".$_FILES['image']['name'];
					$resultat = move_uploaded_file($_FILES['image']['tmp_name'], $lien);
					if ($resultat) {
						$lien="img/series/".$_FILES['image']['name'];
						$sql= $bdd->prepare("UPDATE `image` SET `lien` = '$lien' WHERE idImage = (SELECT idImage FROM series WHERE idSerie= $id)");
						$sql->execute();
						$imageId=$bdd->lastInsertId();
					} else {$message="Erreur lors de l'importation.";}
				} else {$message= "Le type de fichier n'est pas bon";}
			} else {$message= "Votre photo est trop grande";}
		}
	}
	}
	?>

	<?php
		
	?>
	
<!DOCTYPE html>
<html>
<head>
	<title>To Watch List - Modifier la serie</title>
	<?php include('templates/head.php'); ?>
	<meta charset="utf-8">
</head>
<body >
	<?php include ('templates/header.php'); ?>
	<?php 
	?>
	<div class="user">
		<div class="cache"></div>
		<form id="formSerie" method="post" action="editserie.php?attempt=ok&amp;id=<?php echo $_GET['id'] ?>" enctype="multipart/form-data">
			<?php 
				if (isset($message)) {
				    echo '<div class="error alert_pages"><i class="fas fa-times"></i> '.$message.'</div>';
				}
			?>
				<div>
					<label>Nom de la serie :</label>
					<input value="<?php echo $serie['nomSerie'] ?>" type="text" name="nomSerie">
				</div>
				<div>
					<p>Image de la serie :</p>
					<label id="imgInpLabel" for="imgInp">Choisir une image</label>
					<input id="imgInp" type="file" name="image">
				</div>

				<div>
					<label>Nombre de saison :</label>
					<select id="nbrSaison" name="nbrSaison">
						<option <?php if (count($countSerie)==1) {echo "selected";} ?> value="1">1</option>
					    <option <?php if (count($countSerie)==2) {echo "selected";} ?> value="2">2</option>
					    <option <?php if (count($countSerie)==3) {echo "selected";} ?> value="3">3</option>
					    <option <?php if (count($countSerie)==4) {echo "selected";} ?> value="4">4</option>
					    <option <?php if (count($countSerie)==5) {echo "selected";} ?> value="5">5</option>
					    <option <?php if (count($countSerie)==6) {echo "selected";} ?> value="6">6</option>
					    <option <?php if (count($countSerie)==7) {echo "selected";} ?> value="7">7</option>
					    <option <?php if (count($countSerie)==8) {echo "selected";} ?> value="8">8</option>
					    <option <?php if (count($countSerie)==9) {echo "selected";} ?> value="9">9</option>
					</select>
				</div>
			<ul id="nbrEpisode">
				<?php
					for ($i=0; $i < count($countSerie); $i++) { 
						echo '<li><label>Saison '.($i+1).'</label> <div><input class="nbrEpisode" type="number" value='.$countSerie[$i]['total'].' min="1" max="30" name="saison'.($i+1).'"> épisode(s)</div></li>';
					}
				?>
			</ul>
			<input type="submit" name="bouttonSerie">
		</form>
	</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
	/**FORM**/
	//$("#nbrEpisode").append('<li><label>Saison 1</label><div><input class="nbrEpisode" type="number" value="1" min="1" max="30" name="saison1"> épisode(s)</div></li>');
	$('#nbrSaison').change(function() {
		var nbr=$('#nbrSaison').val();
		if ((nbr>=1)&&(nbr<=9)) {
			$("#nbrEpisode").html("");
			for (i=0; i<nbr; i++){
				$("#nbrEpisode").append('<li><label>Saison '+(i+1)+'</label> <div><input class="nbrEpisode" type="number" value="1" min="1" max="30" name="saison'+(i+1)+'"> épisode(s)</div></li>');
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

	/**IMAGE PREVIEW**/
	$('.user').css('background-image', 'url("/<?php echo $image['lien']?>")');
	$('.cache').css('background-color', 'grey');

	function readURL(input) {
	  if (input.files && input.files[0]) {
	    var reader = new FileReader();

	    reader.onload = function(e) {
	    	$('.user').css('background-size', 'cover');
	    	$('.user').css('background-image', 'url('+e.target.result+')');
	    	$('.cache').css('background-color', '#C90505');
	    }

	    reader.readAsDataURL(input.files[0]);
	  }
	}

	$("#imgInp").change(function() {
	  readURL(this);
	});
</script>
</body>
</html>