<?php 
session_start(); 
include('templates/check.php');
include('templates/bdd.php');
?>

<?php

if (isset($_GET['id'])) {
	$req = $bdd->prepare('DELETE FROM series
						  WHERE idSerie=:idSerie');
	$req->execute(array(
	'idSerie' => $_GET['id'],
	));

	$req = $bdd->prepare('DELETE FROM episodes
						  WHERE idSerie=:idSerie');
	$req->execute(array(
	'idSerie' => $_GET['id'],
	));

	header("Location: user.php");
}

?>