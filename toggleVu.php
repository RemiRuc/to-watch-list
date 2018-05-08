<?php 
session_start(); 
include('templates/check.php');
include('templates/bdd.php');


if ( (isset($_POST['idSerie'])) && (isset($_POST['idEpisode'])) && (isset($_POST['vu'])) ) {
	$requete = $bdd->prepare('UPDATE episodes SET vu = :vu WHERE idSerie=:idSerie AND numEpisode=:idEpisode');
	$requete->execute(array('vu' => $_POST['vu'],'idSerie' => $_POST['idSerie'], 'idEpisode' => $_POST['idEpisode']));
}
?>