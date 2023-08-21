<?php
header('Content-Type: text/html; charset=utf-8');
$servername= "localhost";
$username= "root";
$password= "";
$nazivbaze= "1436214";

$db = mysqli_connect($servername,$username,$password,$nazivbaze) or
die('Error while connectiong to db!'.mysqli_error());
mysqli_set_charset($db, "utf8");

?>