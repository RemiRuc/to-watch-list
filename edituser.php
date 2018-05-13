<?php
session_start();
include('templates/bdd.php');
if ((isset($_GET['attempt']))&&(isset($_SESSION['id'])) ) {
  if (isset($_POST['login'])) {
    $login=$_POST['login'];
    if ( (strlen($login)>3) && (strlen($login)<20) ) {
      $req = $bdd->prepare('SELECT count(*) as total FROM user WHERE pseudo=:pseudo');
      $req->execute(array(':pseudo' => $login));
      if($req->fetch()['total']==0){
        $req = $bdd->prepare('UPDATE user SET pseudo = :pseudo WHERE idUser=:idUser');
        $req->execute(array('pseudo' => $login,
                            'idUser' => $_SESSION["id"] ));
        $_SESSION['login']=$login;
        header('Location: user.php');
      } else {$message="Ce pseudo à déjà été pris";}
    } else {$message="Le pseudo doit contenir entre 4 et 20 caractères";}


  } else if (isset($_POST['password']) && (isset($_POST['passwordRepeat'])) ){
      $password=$_POST['password'];
      $passwordRepeat=$_POST['passwordRepeat'];
      if ( (strlen($password)>3) && (strlen($password)<20) ) {
        if (strcmp($password, $passwordRepeat)===0) {
          $password=password_hash($password, PASSWORD_DEFAULT);
          $req = $bdd->prepare('UPDATE user SET mdp = :mdp WHERE idUser=:idUser');
          $req->execute(array('mdp' => $password,
                              'idUser' => $_SESSION["id"] ));

          header('Location: user.php');
        } else {$message="Les 2 mots de passes ne sont pas identiques";}
      } else {$message="Le mot de passe doit contenir entre 4 et 20 caractères";}


  } else if ((isset($_FILES['image']))&& (isset($_FILES['image']['name']))) {
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
          $_SESSION['img']=$lien_user;

          header('Location: user.php');
        } else {$message="Erreur lors de l'importation.";}
      } else {$message= "Le type de fichier n'est pas bon";}
    } else {$message= "Votre photo est trop grande";}
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>ToWatchList - Modifier mon compte</title>
  <?php include('templates/head.php'); ?>
  <meta charset="utf-8">
</head>
<body>
  <?php include ('templates/header.php'); ?>
  <div  class="user">
    <div class="cache"></div>
      
    <section id="inscription">
      <?php if (isset($message)) {
          echo '<div class="error"><i class="fas fa-times"></i> '.$message.'</div>';
      } ?>
      <ul>
        <li class="scroll" id="loginLi">Modifier son pseudo </li>
        <li class="scroll" id="imgLi">Changer de photo de profil</li>
        <li class="scroll" id="passwordLi">Modifier son mot de passe</li>
        <li id="removeLi"><a id="removeuser" href="removeuser.php" onclick="if(window.confirm('Voulez-vous vraiment supprimer votre compte ?')){return true;}else{return false;}">Supprimer son compte</a></li>
      </ul>
      <div id="sliderForm">
        
        
      </div>
    </section>
  </div>


<script>
  var loginForm='<form id="loginForm" method="post" action="edituser.php?attempt=ok">'+
                  '<label for="pseudoIns">Modifier son pseudo</label>'+
                  '<input value="<?php echo $_SESSION['login'] ?>" type="text" name="login">'+
                  '<input type="submit" name="btInscription">'+
                '</form>';
  var imgForm='<form id="imgForm" method="post" action="edituser.php?attempt=ok" enctype="multipart/form-data">'+
                '<div>'+
                  '<p>Photo de profil : </p>'+
                  '<label id="imgInpLabel" for="imgInp">Changer</label>'+
                  '<div class="profilImg profilImgForm"></div>'+
                '</div>'+
                '<input id="imgInp" type="file" name="image">'+
                '<input type="submit" name="btInscription">'+
              '</form>';
  var passwordForm='<form id="passwordForm" method="post" action="edituser.php?attempt=ok">'+
                      '<label for="mdp1Ins">Modifier son mot de passe</label>'+
                      '<input type="password" name="password">'+
                      '<label for="mdp2Ins">Confirmer le mot de passe</label>'+
                      '<input type="password" name="passwordRepeat">'+
                      '<input type="submit" name="btInscription">'+
                    '</form>';
  $('.scroll').click(function(){
    var id=this.id;
    $('#sliderForm').css("height",'0px');
    window.setTimeout(function(){
                  getForm(id);
                 $('#sliderForm').css("height",'220px');    
                  }, 700);
  });

  function getForm(id){
    console.log(id);
    switch(id){
      case 'loginLi':
        $('#sliderForm').html(loginForm);
        break;
      case 'imgLi':
        $('#sliderForm').html(imgForm);
        addEvent()
      break;
        case 'passwordLi':
        $('#sliderForm').html(passwordForm);
        break;
      case 'removeLi':
        //
        break;
    }
  }

</script>

<script>
  /**IMAGE PREVIEW**/
  function addEvent(){
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('.profilImgForm').css('background-image', 'url('+e.target.result+')');
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#imgInp").change(function() {
      readURL(this);
    });
  }
  </script>
</body>
</html>