<?php
require("db_page.php");
?>
<?php
$db=retrieveTrainingDb();
?>

<?php

if(isset($_GET['eventID'])){
	

	$update="delete from diploma_release where training_program_id='".$_GET['eventID']."'";
	$updateRS=$db->query($update);	

	$update="delete from certificate_request where training_program_id='".$_GET['eventID']."'";
	$updateRS=$db->query($update);		

	$update="delete from class_list where training_events_id='".$_GET['eventID']."'";
	$updateRS=$db->query($update);

	$update="delete from trainer_class where event_id='".$_GET['eventID']."'";
	$updateRS=$db->query($update);

	
	$update="delete from training_schedule where event_id='".$_GET['eventID']."'";
	$updateRS=$db->query($update);

	$update="delete from training_events where id='".$_GET['eventID']."'";
	$updateRS=$db->query($update);
	
	
	







	echo "<script language='javascript'>";
	echo "window.opener.location.reload();";
	echo "alert(\"Training Event deleted\");";
	echo "self.close();";
	echo "</script>";



}
?>
