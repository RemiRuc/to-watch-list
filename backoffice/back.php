<?php 
session_start(); 
include('templates/check.php');
include('../templates/bdd.php');
?>

<?php
    if (isset($_GET['sort'])) {
        switch ($_GET['sort']) {
            case 'id':
                $sort="idUser";
                $order="ASC";
                break;

            case 'pseudo':
                $sort="pseudo";
                $order="ASC";
                break;
            
            case 'mail':
                $sort="mail";
                $order="ASC";
                break;

            case 'validate':
                $sort="token_validation";
                $order="ASC";
                break;

            case 'creation':
                $sort="creation_date";
                $order="ASC";
                break;

            default:
                $sort="idUser";
                $order="ASC";
                break;
        }

        if (isset($_GET['order'])) {
            switch ($_GET['order']) {
                case 'asc':
                    $order="ASC";
                    break;
                
                default:
                    $order="DESC";
                    break;
            }
        }
    } else {
        $sort="idUser";
        $order="ASC";
    }
    $requete = $bdd->prepare('SELECT idUser, pseudo, mail, validate, token_validation, creation_date FROM user ORDER BY '.$sort.' '.$order);
    $requete->execute(array());
    $users=$requete->fetchAll();

?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Towatchlist - backoffice</title>
        <?php include('../templates/head.php') ?>
        <meta charset="utf-8">

    </head>
    <body>

    <?php include('templates/header.php') ?>

    <table id="t01">
      <tr>
        <th><a <?php if ( $order=="ASC") {echo 'href="back.php?sort=id&order=desc"';} else {echo 'href="back.php?sort=id&order=asc"';} ?>>ID</a></th>
        <th><a <?php if ( $order=="ASC") {echo 'href="back.php?sort=pseudo&order=desc"';} else {echo 'href="back.php?sort=pseudo&order=asc"';} ?>>pseudo</th> 
        <th><a <?php if ( $order=="ASC") {echo 'href="back.php?sort=mail&order=desc"';} else {echo 'href="back.php?sort=mail&order=asc"';} ?>>mail</th>
        <th><a <?php if ( $order=="ASC") {echo 'href="back.php?sort=creation&order=desc"';} else {echo 'href="back.php?sort=creation&order=asc"';} ?>>Date de creation</th>
        <th><a <?php if ( $order=="ASC") {echo 'href="back.php?sort=validate&order=desc"';} else {echo 'href="back.php?sort=validate&order=asc"';} ?>>Validation</th>
        <th>Suppression</th>
      </tr>
      <?php
        foreach ($users as $user) {
            echo "<tr>";
                echo "<td>".$user['idUser']."</td>";
                echo "<td><a href='user.php?id=".$user['idUser']."'>".$user['pseudo']."</a></td>";
                echo "<td>".$user['mail']."</td>";
                echo "<td>".$user['creation_date']."</td>";
                if ($user['validate']==1) {
                    echo "<td>Validé</td>";
                } else {
                    echo "<td>".$user['token_validation']."</td>";
                }
                echo "<td><a href='remove.php?idUser=".$user['idUser']."'>Supprimer</a></td>";
            echo "</tr>";
        }
      ?>
    </table>
        
    
    </body>
    </html>