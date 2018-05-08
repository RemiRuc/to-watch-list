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
				echo "<li id='".$episode['numEpisode']."' class='episodeList pasVu'>Episode ".$episode['numEpisode']."</li>";
			} else {
				echo "<li id='".$episode['numEpisode']."' class='episodeList vu'>Episode ".$episode['numEpisode']."</li>";
			}
		}
		?>
		</ul>		

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