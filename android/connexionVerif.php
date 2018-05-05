<?php
	$host_name = 'db726744168.db.1and1.com';
	$database = 'db726744168';
	$user_name = 'dbo726744168';
	$password = 'Towatch77!';

	$verification=0;
	$id=0;

	$dbh = null;
	try {
		$dbh = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
	} catch (PDOException $e) {
		echo "Erreur!: " . $e->getMessage() . "<br/>";
		die();
	}

	$pseudo = $_POST['pseudo'];
	$mdp = $_POST['mdp'];
	$resultat = "";
	$reponse = $dbh->query("SELECT * FROM user");
		while($donnees = $reponse->fetch()){
			if ((strcmp($donnees["pseudo"],$pseudo)==0)&&(strcmp($donnees["mdp"],$mdp)==0)) {
				$verification=1;
				$id=$donnees["idUser"];
			}
		}


	if ($verification==1) {
		$reponse2 = $dbh->query("SELECT * FROM series WHERE idUser='".$id."'");
		while ($donnees2=$reponse2->fetch()) {
			$resultat .= $donnees2['nomSerie'].'#';	
		}
		echo $resultat;
	} else {
		echo "Failure";
	}
?>