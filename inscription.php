<?php
session_start();
include('templates/bdd.php');
if ( (isset($_SESSION['login'])) && (isset($_SESSION['id'])) ) {
    header('Location: accesrefused.php');
} else {
    if ( (isset($_POST['login'])) && (isset($_POST['mail'])) && (isset($_POST['password'])) && (isset($_POST['passwordRepeat'])) && (isset($_FILES['image']))&& (isset($_FILES['image']['name'])) ) {
      $login=$_POST['login'];
      $mail=$_POST['mail'];
      $password=$_POST['password'];
      $passwordRepeat=$_POST['passwordRepeat'];
      if ( (strlen($login)>3) && (strlen($login)<20) ) {
        if ( (strlen($password)>3) && (strlen($password)<20) ) {
          if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            if (strcmp($password, $passwordRepeat)===0) {
              $req = $bdd->prepare('SELECT count(*) as total FROM user WHERE pseudo=:pseudo');
              $req->execute(array(':pseudo' => $login));
              if($req->fetch()['total']==0){
                  $req = $bdd->prepare('SELECT count(*) as total FROM user WHERE mail=:mail');
                  $req->execute(array(':mail' => $mail));
                if ($req->fetch()['total']==0) {
                  $token= openssl_random_pseudo_bytes(16);
                  $token=bin2hex($token);
                  $password=password_hash($password, PASSWORD_DEFAULT);
                  $tailleMax=2097152;
                  $extensionsValides= array('jpg','jpeg','png','gif');
                  if ($_FILES['image']['size']<=$tailleMax) {
                    $extensionUpload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
                    if (in_array($extensionUpload, $extensionsValides)) {
                      $lien="img/users/".$_FILES['image']['name'];
                      $resultat = move_uploaded_file($_FILES['image']['tmp_name'], $lien);
                      if ($resultat) {
                        $lien_user="img/users/".$_FILES['image']['name'];
                        $sql= $bdd->prepare("INSERT INTO `image_user`(`lien_user`) VALUES ('$lien_user')");
                        $sql->execute();
                        $imageId=$bdd->lastInsertId();

                        $req = $bdd->prepare('INSERT INTO user(pseudo, mdp, mail, token_validation, creation_date, idImage_user) VALUES (:pseudo, :mdp, :mail, :token_validation, :creation_date, :idImage_user)');
                        $req->execute(array(':pseudo' => $login,
                                             ':mdp' => $password,
                                             ':mail' => $mail,
                                             ':token_validation' => $token,
                                             ':creation_date' => date ('Y-m-d H:i:s', time()),
                                             ':idImage_user' => $imageId ));
                        $header="MIME-Version: 1.0\r\n";
                        $header.='From:"Verification To watch list"<subscription.verification@towatchlist.com>'."\n";
                        $header.='Content-Type:text/html; charset="uft-8"'."\n";
                        $header.='Content-Transfer-Encoding: 8bit';
                        $mailBody="
                        <html>
                        <body>
                        <a href=towatchlist.local/verification.php?token=".$token.">Vérifiez votre compte</a>
                        </body>
                        </html>
                         ";
                        mail($mail, 'To watch list - Verification de compte', $mailBody,$header);
                        header('Location: index.php?subscribe=done');
                      } else {$message="Erreur lors de l'importation.";}
                    } else {$message= "Le type de fichier n'est pas bon";}
                  } else {$message= "Votre photo est trop grande";}
                } else {$message="Ce mail à déjà été pris";}
              } else {$message="Ce pseudo à déjà été pris";}
            } else {$message="Les 2 mots de passes ne sont pas identiques";}
          } else {$message="Veuillez entrer une adresse mail valide";}
        } else {$message="Le mot de passe doit contenir entre 4 et 20 caractères";}
      } else {$message="Le pseudo doit contenir entre 4 et 20 caractères";}
    }
?>

<!DOCTYPE html>
<html>
<head>
	<title>ToWatchList - Inscription</title>
	<?php include('templates/head.php'); ?>
	<meta charset="utf-8">
</head>
<body class="index">
  <div class="cache"></div>
  <?php if (isset($message)) {
      echo '<div class="error"><i class="fas fa-times"></i> '.$message.'</div>';
  } ?>
	<section id="inscription">
		<img src="img/toWatchListLogo.png">
		<form method="post" action="Inscription.php" enctype="multipart/form-data">
      <p>Photo de profil : </p>
      <label id="imgInpLabel" for="imgInp">Choisir une image</label>
      <input id="imgInp" type="file" name="image">
			<label for="pseudoIns">Entrez un pseudo</label>
			<input type="text" name="login">
			<label for="mailIns">Entrez un mail</label>
			<input type="text" name="mail">
			<label for="mdp1Ins">Entrez un mot de passe</label>
			<input type="password" name="password">
			<label for="mdp2Ins">Répétez le mot de passe</label>
			<input type="password" name="passwordRepeat">
			<input type="submit" name="btInscription">
		</form>
    <a href="index.php">Retour à la page connexion</a>
	</section>
</body>
</html>

<?php
}
?>