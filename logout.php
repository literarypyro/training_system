<?php
session_start();
?>
<?php
require("db_page.php");
?>
<?php
//$db=new mysqli("localhost","root","","training");
$db=retrieveTrainingDb();
$loginSQL="insert into log_history(username,log_time, type) values ('".$_SESSION['username']."','".date("Y-m-d H:i:s")."','logout')";
$loginrs=$db->query($loginSQL);
$loginnm=$loginrs->num_rows;

session_destroy();

header('Location: index.php');


?>

