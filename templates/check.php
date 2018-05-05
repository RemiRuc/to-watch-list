<?php

if ( (!isset($_SESSION['login'])) or (!isset($_SESSION['id'])) ) {
    header('Location: http://'.$_SERVER["HTTP_HOST"].'/accesrefused.php');
}

?>