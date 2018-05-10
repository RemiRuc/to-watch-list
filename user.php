<?php 
session_start(); 
include('templates/check.php');
include('templates/bdd.php');

	?>

	<!DOCTYPE html>
	<html>
	<head>
		<title>Towatchlist - <?php echo $_SESSION['login']; ?></title>
		<?php include('templates/head.php') ?>
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
	<table>
		<thead> <!-- En-tête du tableau -->
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


				?>
			<tr>
	           <td><a href="series.php?id=<?php echo $serie['idSerie'] ?>"><?php echo $serie['nomSerie'] ?></a></td>
	           <td><?php echo $vu."/".$total; ?></td>
	           <td>Modifier</td>
	           <td><a href="removeserie.php?id=<?php echo $serie['idSerie'] ?>">Supprimer</a></td>
	       </tr>
				<?php
				//echo "<li><a href=series.php?id=".$serie['idSerie'].">".$serie['nomSerie']."</a></li>";	
			}
			?>
		</tbody>
	</table>
			<?php
		}

		?>
	</div>
	
	</body>
	</html>
