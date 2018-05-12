<?php 
session_start(); 
include('templates/check.php');
include('templates/bdd.php');

	?>

<!DOCTYPE html>
<html>
	<head>
		<title>Towatchlist - <?php echo $_SESSION['login']; ?></title>
		<?php include('templates/head.php'); ?>
		<meta charset="utf-8">
	</head>
	<body>
		<?php include ('templates/header.php'); ?>
		<?php 
			$requete = $bdd->prepare('SELECT * FROM series WHERE idUser=:idUser');
			$requete->execute(array('idUser' => $_SESSION["id"]));
			$series=$requete->fetchAll();
		?>
		<h1>Salut <?php echo $_SESSION['login']; ?>, voici tes séries :</h1>
		<div id="liste">
				<?php
					foreach($series as $serie){
						$requete2 = $bdd->prepare('SELECT vu, count(*) as total FROM `episodes` WHERE `idSerie`=:idSerie GROUP BY vu');
						$requete2->execute(array('idSerie' => $serie['idSerie']));
						$requetTab=$requete2->fetchAll();
						foreach ($requetTab as $key) {
							if (count($requetTab)<2) {
								if ($key['vu']==1) {
									$vu=$key['total'];
									$pasVu=0;
								} else {
									$vu=0;
									$pasVu=$key['total'];
								}
							} else {
								if ($key['vu']==1) {
									$vu=$key['total'];
								} else {
									$pasVu=$key['total'];
								}
							}
						}
						$total=$vu+$pasVu;

						$requete3 = $bdd->prepare('SELECT * FROM `image` WHERE `idImage`= :idImage');
						$requete3->execute(array('idImage' => $serie['idImage']));
						$images=$requete3->fetchAll();
						foreach($images as $image){

				?>
				<div class="series">
					<div class="series-img">
						<div class="series-cache"></div>
						<img src="<?php echo $image['lien']; ?>">
	                    <div class="series-titre">
	                    	<h2><a href="series.php?id=<?php echo $serie['idSerie'] ?>"><?php echo $serie['nomSerie'] ?></a></h2>
	                    	<h3><?php echo $vu."/".$total; ?></h3>
	                    	<div class="series-modif">
	                    		<a href="editserie.php?id=<?php echo $serie['idSerie'] ?>"><i class="fas fa-edit"></i></a>
		                    	<a href="removeserie.php?id=<?php echo $serie['idSerie'] ?>"><i class="fas fa-trash-alt"></i></a>
	                    	</div>
	                    </div>
					</div>
				</div>
				<?php
						}
					}
				?>
				<div class="series">
					<div class="series-img">
						<div class="ajout-cache"></div>
						<div class="series-titre">
							<a href="createserie.php">
	                    		<h2>Ajouter</h3>
	                    		<i class="fas fa-plus series-plus"></i>
	                    	</a>
	                    </div>
					</div>
				</div>
		</div>
	
	</body>
</html>
