<?php 
session_start(); 
include('templates/check.php');
include('../templates/bdd.php');
?>

<?php

if (isset($_GET['idUser'])) {
	$req = $bdd->prepare('DELETE FROM user WHERE idUser=:idUser');
	$req->execute(array('idUser' => $_GET['idUser']));

	$req = $bdd->prepare('DELETE FROM series WHERE idUser=:idUser');
	$req->execute(array('idUser' => $_GET['idUser']));

	$req = $bdd->prepare('DELETE FROM episodes WHERE idUser=:idUser');
	$req->execute(array('idUser' => $_GET['idUser']));
} else if (isset($_GET['idSerie'])){
	$req = $bdd->prepare('DELETE FROM series WHERE idUser=:idUser');
	$req->execute(array('idUser' => $_GET['idUser']));

	$req = $bdd->prepare('DELETE FROM episodes WHERE idUser=:idUser');
	$req->execute(array('idUser' => $_GET['idUser']));
}

header('Location: back.php');

?>