<?php
session_start();
include('templates/bdd.php');
if ( (isset($_SESSION['login'])) && (isset($_SESSION['id'])) ) {
    header('Location: accesrefused.php');
} else {
    if (isset($_GET['token'])) {
      $token_validation=$_GET['token'];
      $req = $bdd->prepare('SELECT idUser, token_validation FROM user WHERE token_validation=:token_validation');
      $req->execute(array(':token_validation' => $token_validation));
      $tokenBdd=$req->fetch();
        if ($tokenBdd==false) {
          $message="Il n'y a aucun compte a valider";
        } else {
          $id=$tokenBdd['idUser'];
          $req = $bdd->prepare('UPDATE user SET token_validation=NULL, validate=1 WHERE idUser=:id');
          $req->execute(array(':id' => $id));
          $message="Votre compte a été validé avec succès !";
        }
    } else {
      header('Location: accesrefused.php');
    }
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>To watch list - Verfication</title>
        <?php include('templates/head.php') ?>
    </head>
    <body class="index">
      <div class="cache"></div>
        <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <section id="connexion">        
        <img src="img/toWatchListLogo.png">
        

        <?php
        if (isset($message)) {
            ?><p><?php echo $message ?></p><?php
        }
        ?>
        <a href="index.php">Retour à l'accueil</a>
        </section>

        <script src="js/vendor/modernizr-3.5.0.min.js"></script>
        <script src="js/vendor/less.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.2.1.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
        <script>
            window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
            ga('create','UA-XXXXX-Y','auto');ga('send','pageview')
        </script>
        <script src="https://www.google-analytics.com/analytics.js" async defer></script>
    </body>
</html>

<?php
}
?>
