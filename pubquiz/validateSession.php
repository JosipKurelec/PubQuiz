<?php 
session_start();
$current_page = basename($_SERVER['PHP_SELF']);

if($current_page != 'login.php' && $current_page != 'uregister.php' && $current_page != 'qregister.php' && $current_page != 'qsettings.php'){
    if (!isset($_SESSION['username'])){
        header("Location: login.php");

        die();
    }
}
?>