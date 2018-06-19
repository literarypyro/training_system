<?php
require("db_page.php");
?>
<?php
$db2=retrieveTemporaryDb();
$db=retrieveTrainingDb();
if(isset($_GET['retrieveID'])){
		$erase="delete from participant";
		$eraseRS=$db2->query($erase);
		
		$erase="delete from trainer";
		$eraseRS=$db2->query($erase);
		
		$erase="delete from schedule";
		$eraseRS=$db2->query($erase);
		
		$erase="delete from event";
		$eraseRS=$db2->query($erase);
		
		$training_course="";
		$batch="";

		
		$sql="select * from training_events where id='".$_GET['retrieveID']."'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;
		
		if($nm>0){
			$update="insert into event(id,program_id,batch) (select id,training_program_id,batch_number from training.training_events where id='".$_GET['retrieveID']."' limit 1)";
			
			$updateRS=$db2->query($update);
			
			$update="insert into schedule(event_id,date_from,date_to,no_of_days) (select id,start_date,end_date,no_of_days from training.training_events where id='".$_GET['retrieveID']."' limit 1)";
			$updateRS=$db2->query($update);
		
			$update="insert into trainer(event_id,trainer_id) (select event_id,trainer_id from training.trainer_class where event_id='".$_GET['retrieveID']."')";
			$updateRS=$db2->query($update);
		
			$update="insert into participant(participant_id,first_name,middle_name,last_name,designation,department,event_id,remarks) (select traineeId,firstName,midInitial,lastName,designation,department,training_event_id,remarks from training.class_instance where training_event_id='".$_GET['retrieveID']."')";

			$updateRS=$db2->query($update);
		
		}
		header("Location: training_event.php?a=AA");
	
//		echo "<script language='javascript'>";
		//echo "";
		//echo "window.opener.location.reload();";
		
		//echo "window.open('training_event.php?a=AA','_self);";
		//echo "self.close();";
//		echo "</script>";		

	
	//header("Location: training_event.php?a=ZA");		

}
?>