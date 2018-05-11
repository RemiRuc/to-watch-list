<?php 
session_start(); 
include('templates/check.php');
include('templates/bdd.php');
?>

<?php

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$sql2 = $bdd->prepare("SELECT lien FROM `image` WHERE idImage = (SELECT idImage FROM series WHERE idSerie = $id)");
	$sql2->execute();
	$img=$sql2->fetchAll();
	foreach ($img as $image) {
		unlink($image['lien']);
	}

	$sql= $bdd->prepare("DELETE FROM `image` WHERE idImage = (SELECT idImage FROM series WHERE idSerie = $id)");
    $sql->execute();

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