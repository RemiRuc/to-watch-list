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
		<h1>Salut <?php echo $_SESSION['login']; ?> !</h1>
		<div id="liste">
			<?php 
				$requete = $bdd->prepare('SELECT * FROM series WHERE idUser=:idUser');
				$requete->execute(array('idUser' => $_SESSION["id"]));
				$series=$requete->fetchAll();

				if (count($series)==0) {
					echo "<a href='createserie.php'>Créer votre première série !</a>";
				} else {
			?>
				<?php
					foreach($series as $serie){
						$requete2 = $bdd->prepare('SELECT count(*) as total FROM `episodes` WHERE `idSerie`=:idSerie GROUP BY vu');
						$requete2->execute(array('idSerie' => $serie['idSerie']));
						$requetTab=$requete2->fetchAll();
						$vu=$requetTab[1]['total'];
						$pasVu=$requetTab[0]['total'];
						$total=$vu+$pasVu;
						if ($vu == 0) {
							$vu=0;
						}

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
	                    		<a href="#"><i class="fas fa-edit"></i></a>
		                    	<a href="#"><i class="fas fa-trash-alt"></i></a>
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

				<!-- <table>
					<thead> 
					   	<tr>
					   	    <th>Serie</th>
					   	    <th>Avancement</th>
						    <th>Modifier</th>
						    <th>Supprimer</th>
						</tr>
					</thead>
					<tbody>
						 <?php
						foreach($series as $serie){
							$requete2 = $bdd->prepare('SELECT count(*) as total FROM `episodes` WHERE `idSerie`=:idSerie GROUP BY vu');
							$requete2->execute(array('idSerie' => $serie['idSerie']));
							$requetTab=$requete2->fetchAll();
							$vu=$requetTab[1]['total'];
							$pasVu=$requetTab[0]['total'];
							$total=$vu+$pasVu;


							?>
						<tr>
				           <td><a href="series.php?id=<?php echo $serie['idSerie'] ?>"><?php echo $serie['nomSerie'] ?></a></td>
				           <td><?php echo $vu."/".$total; ?></td>
				           <td>Modifier</td>
				           <td>Supprimer</td>
				       </tr>
							<?php
							//echo "<li><a href=series.php?id=".$serie['idSerie'].">".$serie['nomSerie']."</a></li>";	
						}
						?>
					</tbody>
				</table> -->
			<?php
			}

			?>
		</div>
	
	</body>
</html>
