<?php
session_start();
include('templates/bdd.php');
if (isset($_GET['attempt'])) {
  if ( (isset($_POST['login'])) && (isset($_POST['password'])) && (isset($_POST['passwordRepeat'])) && (isset($_FILES['image']))&& (isset($_FILES['image']['name']))&&(isset($_SESSION['id'])) ) {
    $login=$_POST['login'];
    $password=$_POST['password'];
    $passwordRepeat=$_POST['passwordRepeat'];
    if ( (strlen($login)>3) && (strlen($login)<20) ) {
      if ( (strlen($password)>3) && (strlen($password)<20) ) {
          if (strcmp($password, $passwordRepeat)===0) {
            $req = $bdd->prepare('SELECT count(*) as total FROM user WHERE pseudo=:pseudo');
            $req->execute(array(':pseudo' => $login));
            if($req->fetch()['total']==0){
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
                  $id = $_SESSION['id'];
                  if ($resultat) {
                    $lien_user="img/users/".$_FILES['image']['name'];
                    $sql= $bdd->prepare("UPDATE `image_user` SET `lien_user` = '$lien_user' WHERE idImage_user = (SELECT idImage_user FROM user WHERE idUser= $id) ");
                    $sql->execute();

                    $req = $bdd->prepare('UPDATE user SET pseudo = :pseudo, mdp =  :mdp WHERE idUser=:idUser');
                    $req->execute(array(':pseudo' => $login,
                                         ':mdp' => $password,
                                         'idUser' => $_SESSION["id"] ));
                    header('Location: user.php');
                  } else {$message="Erreur lors de l'importation.";}
                } else {$message= "Le type de fichier n'est pas bon";}
              } else {$message= "Votre photo est trop grande";}
            } else {$message="Ce pseudo à déjà été pris";}
          } else {$message="Les 2 mots de passes ne sont pas identiques";}
      } else {$message="Le mot de passe doit contenir entre 4 et 20 caractères";}
    } else {$message="Le pseudo doit contenir entre 4 et 20 caractères";}
  }else{$message="Veuillez remplir tous les champs du formulaire";}
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>ToWatchList - Modifier mon compte</title>
  <?php include('templates/head.php'); ?>
  <meta charset="utf-8">
</head>
<body class="user">
  <div class="cache"></div>
  <?php if (isset($message)) {
      echo '<div class="error"><i class="fas fa-times"></i> '.$message.'</div>';
  } ?>
  <section id="inscription">
    <img src="img/toWatchListLogo.png">
    <form method="post" action="edituser.php?attempt=ok" enctype="multipart/form-data">
      <p>Changer de photo de profil : </p>
      <label id="imgInpLabel" for="imgInp">Choisir une image</label>
      <input id="imgInp" type="file" name="image">
      <label for="pseudoIns">Modifier un pseudo</label>
      <input type="text" name="login">
      <label for="mdp1Ins">Modifier un mot de passe</label>
      <input type="password" name="password">
      <label for="mdp2Ins">Confirmer le mot de passe</label>
      <input type="password" name="passwordRepeat">
      <input type="submit" name="btInscription">
    </form>
  </section>
</body>
</html>