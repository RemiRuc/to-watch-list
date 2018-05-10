<?php
session_start();
include('../templates/bdd.php');
if ( (isset($_SESSION['login'])) && (isset($_SESSION['id'])) ) {
    header('../Location: user.php');
} else if ((isset($_SESSION['admin']))) {

}

if ( (isset($_POST['login'])) && (isset($_POST['password'])) ) {
    $login=$_POST['login'];
    $password=$_POST['password'];
    if ( (strlen($login)>3) && (strlen($login)<20)) {
        if ( (strlen($password)>3) && (strlen($password)<20)) {
            $req = $bdd->prepare('SELECT * FROM admin WHERE login = :pseudo LIMIT 1');
            $req->execute([':pseudo' => $login]);
            $result=$req->fetch();
            if ($result==false) {
                $message = "Le mot de passe ou le login n'est pas valide";
            } else {
                if (password_verify($password, $result['password'])) {
                    $_SESSION['admin']=true;
                    header('Location: back.php');
                } else {$message="Le mot de passe ou le login n'est pas valide";}
            }
        } else {$message="Le mot de passe doit être compris entre 4 et 20 caractères";}
    } else {$message="Le login doit être compris entre 4 et 20 caractères";}
}

if (isset($_GET['subscribe'])) {
    $message="Vous avez été inscrit avec succès, vérifiez votre boite mail pour activer votre compte.";
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>To Watch List - back</title>
    <?php include('../templates/head.php') ?>
	<meta charset="utf-8">
</head>
<body>
		<img src="/img/toWatchListLogo.png">
		<form method="post" action="index.php">
			<input placeholder="Pseudo" type="text" name="login">
			<input placeholder="Mot de passe" type="password" name="password">
			<input type="submit" name="btConnexion">
			<?php if (isset($message)) {echo $message;} ?>
		</form>

</body>
</html>