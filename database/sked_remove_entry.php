<?php
require("../db_page.php");

$db2=retrieveTemporaryDb();
?>
<?php
if(isset($_GET['trainer'])){
	$update="delete from trainer where id='".$_GET['trainer']."'";
	$updateRS=$db2->query($update);
}

if(isset($_GET['participant'])){
	$update="delete from participant where id='".$_GET['participant']."'";
	$updateRS=$db2->query($update);
}
?>
<?php
echo "<script language='javascript'>";
echo "window.opener.location.reload();";

echo "</script>";

?>