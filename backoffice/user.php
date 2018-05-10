<?php 
session_start(); 
include('templates/check.php');
include('../templates/bdd.php');
?>

<?php

    $requete = $bdd->prepare('SELECT * FROM series');
    $requete->execute();
    $series=$requete->fetchAll();

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
        <th>Nom</th>
        <th>Avancement</th> 
        <th>Suppression</th>
      </tr>
      <?php
      if (count($series)==0){
        echo "<tr>";
            echo "<td COLSPAN=3>Tableau vide</td>";
        echo "</tr>";
      } else {
        foreach ($series as $serie) {
            $requete2 = $bdd->prepare('SELECT vu, count(*) as total FROM `episodes` WHERE `idSerie`=:idSerie GROUP BY vu');
                $requete2->execute(array('idSerie' => $serie['idSerie']));
                $requetTab=$requete2->fetchAll();
                foreach ($requetTab as $key) {
                    if (count($requetTab)<2) {
                        if ($key['vu']==1) {
                            $vu=$key['total'];
                            $pasVu=0;
                        } else {
                            $vu=0;
                            $pasVu=$key['total'];
                        }
                    } else {
                        if ($key['vu']==1) {
                            $vu=$key['total'];
                        } else {
                            $pasVu=$key['total'];
                        }
                    }
                }
                $total=$vu+$pasVu;

            echo "<tr>";
                echo "<td>".$serie['nomSerie']."</td>";
                echo "<td>".$vu."/".$total."</td>";
                echo "<td>".$user['mail']."</td>";
                echo "<td><a href='remove.php?idSerie=".$user['idUser']."'>Supprimer</a></td>";
            echo "</tr>";
        }
      }
      ?>
    </table>
        
    
    </body>
    </html>