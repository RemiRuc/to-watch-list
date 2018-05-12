<?php 
session_start(); 
include('templates/check.php');
include('templates/bdd.php');
?>

<?php

	$id = $_SESSION['id'];

	$sql= $bdd->prepare("DELETE FROM `image_user` WHERE idImage_user = (SELECT idImage_user FROM user WHERE idUser =$id)");
    $sql->execute();

    $sql= $bdd->prepare("DELETE FROM `image` WHERE idImage = (SELECT idImage FROM series WHERE idUser =$id)");
    $sql->execute();

    $req2 = $bdd->prepare('DELETE FROM series
						  WHERE idUser =:idUser');
	$req2->execute(array(
	'idUser' => $_SESSION['id']
	));

	$req3 = $bdd->prepare('DELETE FROM episodes
						  WHERE idUser =:idUser');
	$req3->execute(array(
	'idUser' => $_SESSION['id']
	));

	$req = $bdd->prepare('DELETE FROM user
						  WHERE idUser =:idUser');
	$req->execute(array(
	'idUser' => $_SESSION['id']
	));
	
	session_destroy();
	header('Location: index.php');

?>