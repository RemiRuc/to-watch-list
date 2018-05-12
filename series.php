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

	$requeteImage = "SELECT lien FROM image WHERE idImage = (SELECT idImage FROM series WHERE idSerie='".$_GET["id"]."')";
	//$reponse = $bdd->query($requete);
	$reponseImage = $bdd->query($requeteImage);
	$image=$reponseImage->fetch();

	$requete = $bdd->prepare('SELECT * FROM episodes WHERE idSerie=:id ORDER BY numEpisode ASC');
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
	<?php include('templates/head.php'); ?>
	<meta charset="utf-8">
</head>
<body>
	<?php include ('templates/header.php'); ?>
	<div class="user">
		<div class="cache"></div>
		<div id="series-contenu">
			<h1 id="series-titre"><?php echo $titre['titre']; ?></h1>
			<div id="series-list">
				<?php
						$requete2 = $bdd->prepare('SELECT vu, count(*) as total FROM `episodes` WHERE `idSerie`=:idSerie GROUP BY vu');
						$requete2->execute(array('idSerie' => $_GET['id']));
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
						$pourcent=($vu/$total)*100;
						if ($pourcent==0) {
							echo "<div class='progress-bar'></div>";
						}else{
							echo "<div class='progress-bar' style='width:".$pourcent."%;background:green;'></div>";
						}
						
				?>
				<img id="series-img" src="<?php echo$image['lien']; ?>">
				<div id="series-liste">
					<ul>
						<?php
						$actualSeason=1;
					foreach($episodes as $episode){
						if ($episode['saison']!==$actualSeason) {
							$actualSeason=$episode['saison'];
							echo "<h2>saison ".$actualSeason."</h2>";
						}
						if ($episode['vu']==0) {
							echo "<li id='".$episode['numEpisode']."' class='episodeList pasVu'><p>Episode ".$episode['numEpisode']."</p><p><i class='fas fa-eye'></i></p></li>";
						} else {
							echo "<li id='".$episode['numEpisode']."' class='episodeList vu'><p>Episode ".$episode['numEpisode']."</p><p><i class='fas fa-eye'></i></p></li>";
						}
					}
					?>
					</ul>		
				</div>
			</div>
		</div>
	</div>
		
		

	<script type="text/javascript">
		var idSerie=<?php echo $_GET['id']; ?>;
		$(".episodeList").click(function(){
			var classList=this.classList;
			var idEpisode=this.id;
			var html=this;
			
			for (var i = classList.length - 1; i >= 0; i--) {
				if (classList[i]=='vu') {
					toggleVu(html, idSerie, idEpisode, 0);

				} else if(classList[i]=='pasVu') {
					toggleVu(html, idSerie, idEpisode, 1);
				}
			}

    		function toggleVu(html, idSerie, idEpisode, vu){
    			$.ajax({
			       url : 'toggleVu.php',
			       type : 'POST',
			       data : {'idEpisode':idEpisode, 'idSerie':idSerie, 'vu':vu,},
			       success : function(code_html, statut){
			        	if (vu===0) {
			        		html.classList.remove('vu');
							html.classList.add('pasVu');
			        	} else if (vu===1){
			        		html.classList.remove('pasVu');
							html.classList.add('vu');
			        	}
			       },

			       error : function(resultat, statut, erreur){
			         
			       },

			       complete : function(resultat, statut){

			       }

			    });
    		}
		});

	</script>

</body>
</html>

<?php
	}

?>