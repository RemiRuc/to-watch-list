<?php

if ( (!isset($_SESSION['admin'])) ) {
    header('Location: http://'.$_SERVER["HTTP_HOST"].'/accesrefused.php');
}

?>