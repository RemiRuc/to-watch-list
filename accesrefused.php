<?php
session_start();
include('templates/bdd.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>ToWatchList - Acces refusé</title>
	<?php include('templates/head.php'); ?>
	<meta charset="utf-8">
</head>
<body class="index">
  <div class="cache"></div>
  	<div class="error"><i class="fas fa-times"></i> Vous n'êtes pas connecté.</div>
	<section id="inscription">
		<img src="img/toWatchListLogo.png">
    <a href="index.php">Retour à la page connexion</a>
	</section>
</body>
</html>