<?php
//require("../db_page.php");

$db2=retrieveTemporaryDb();



?>
<?php
$eventSQL="select *,event.id as eventId from event inner join training.training_programs on program_id=training.training_programs.id";

$eventRS=$db2->query($eventSQL);
$eventNM=$eventRS->num_rows;

if($eventNM>0){
	$eventRow=$eventRS->fetch_assoc();
	$eventId=$eventRow['eventId'];
	$training_course=$eventRow['training_title'];
	$batch=$eventRow['batch'];
}
else {
	$training_course="&nbsp;";
	$batch="&nbsp;";
}
?>
<?php
if(isset($_GET['insert'])){
	$sql="select * from event";
	$rs=$db2->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();
	
		$training_title=$row['program_id'];
		$batch=$row['batch'];
		
		$dateSQL="select * from schedule where event_id='".$row['id']."'";
		$dateRS=$db2->query($dateSQL);
		
		$dateNM=$dateRS->num_rows;
		if($dateNM>0){
			$dateRow=$dateRS->fetch_assoc();
			$date_from=$dateRow['date_from'];	
			$date_to=$dateRow['date_to'];	
			$no_of_days=$dateRow['no_of_days'];
			
		
		}	
		
		$insert="insert into training_events(training_program_id,batch_number,start_date,end_date,no_of_days) values ";
		$insert.="('".$training_title."','".$batch."','".$date_from."','".$date_to."','".$no_of_days."')";
		$insertRS=$db->query($insert);
		
		$reference_index=$db->insert_id;
		
	
	
		$trainerSQL="select * from trainer where event_id='".$row['id']."'";
		$trainerRS=$db2->query($trainerSQL);
		
		$trainerNM=$trainerRS->num_rows;
		
		for($m=0;$m<$trainerNM;$m++){
			$trainerRow=$trainerRS->fetch_assoc();
			
			$insert="insert into trainer_class(event_id,trainer_id) values ('".$reference_index."','".$trainerRow['trainer_id']."')";
			$insertRS=$db->query($insert);

		}
	
	
		$traineeSQL="select * from participant where event_id='".$row['id']."'";
		$traineeRS=$db2->query($traineeSQL);
		
		$traineeNM=$traineeRS->num_rows;
	
		for($m=0;$m<$traineeNM;$m++){
			$traineeRow=$traineeRS->fetch_assoc();
			
			$insert="insert into class_list(training_events_id,trainee_id,remarks) values ('".$reference_index."','".$traineeRow['participant_id']."','".$traineeRow['remarks']."')";
			
			$insertRS=$db->query($insert);

		}
	
		
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
		echo "Data inserted.";
	}
}
?>
<?php
if(isset($_GET['delete'])){
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
		echo "Data flushed.";

}
?>

<script language='javascript'>
function submitDetails(){
	var message=confirm("Are you ready to submit the details?");
	if(message){
		window.open("training_event.php?a=ZZ&insert=Y","_self");
	}

}

function flushChanges(){
	var message=confirm("Cancel Insert?");
	if(message){
		window.open("training_event.php?a=ZZ&delete=Y","_self");
	}

}



</script>
	<table width=100%>
	<tr>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Add Training Schedules</h1>
	</th>
	</tr>
	</table>
	<br>
<table width='60%' id='programTable' name='programTable' width=300 align=center style='border: 1px solid gray'>
<tr><th style="background-color: #00cc66;color:black;"	colspan=2>Training Course</th></tr>
<tr><th style="background-color: white;color:black;" colspan=2><?php echo $training_course; ?></th></tr>
<tr><th width=40% style="background-color: #00cc66;color: black;">Batch</th><th style="background-color: white;color: black;"><?php echo $batch; ?></th></tr>
</table>
<br>
<div align=center>
<a href='training_event.php?a=ZZ' onclick='window.open("database/sked_add_event.php")'>Add/Change</a> 
</div>
<br>
<table id='trainerTable' width='60%' align=center style='border: 1px solid gray'>
<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Trainer</th></tr>
<?php
if($eventNM>0){
	$trainerSQL="select *,trainer.id as trainerId from trainer inner join training.trainer_list on trainer.trainer_id=training.trainer_list.id where event_id='".$eventId."'";
	$trainerRS=$db2->query($trainerSQL);
	
	$trainerNM=$trainerRS->num_rows;

	for($i=0;$i<$trainerNM;$i++){
		$trainerRow=$trainerRS->fetch_assoc();
	?>	
		<tr>	
		<td style="background-color: white;color: black;"><?php echo $trainerRow['lastName'].", ".$trainerRow['firstName']; ?></td>
		<td style="background-color: white;color: black;"><a href='#' onclick='window.open("database/sked_remove_entry.php?trainer=<?php echo $trainerRow['trainerId']; ?>")'>Remove</a></td>
		</tr>
	<?php
	}
}
?>
</table>
<br>
<?php 
if($eventNM>0){
?>
<div align=center>
<a href='training_event.php?a=ZZ' onclick='window.open("database/sked_add_trainer.php")'>Add</a><!--enable only if Event has been added -->
</div>
<br>
<?php
}
?>
<table id='inclusiveTable' width='60%' align=center style='border: 1px solid gray'>
<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Period</th></tr>
<?php
if($eventNM>0){
	$periodSQL="select * from schedule where event_id='".$eventId."'";
	$periodRS=$db2->query($periodSQL);
		
	$periodNM=$periodRS->num_rows;	
	if($periodNM>0){
	$periodRow=$periodRS->fetch_assoc();
	?>
	
	<tr><th style="background-color: white;color: black;">Period From</th><th style="background-color: white;color: black;"><?php echo date("F d, Y H:ia",strtotime($periodRow['date_from'])); ?></th></tr>
	<tr><th style="background-color: white;color: black;">Period To</th><th style="background-color: white;color: black;"><?php echo date("F d, Y H:ia",strtotime($periodRow['date_to'])); ?></th></tr>
	<tr><th style="background-color: white;color: black;">Number of Days</th><th style="background-color: white;color: black;"><?php echo $periodRow['no_of_days']; ?></th></tr>

	<?php	
	
	}
}
?>
</table>
<br>
<?php 
if($eventNM>0){
?>
<div align=center>
<a href='training_event.php?a=ZZ'  onclick='window.open("database/sked_add_period.php")' >Add/Change</a><!--enable only if Event has been added -->
</div>
<br>
<?php
}
?>
<table id='traineesTable' width='60%' align=center style='border: 1px solid gray'>
<tr><th style="background-color: #00cc66;color: black;"	colspan=4>Participants</th></tr>
<?php
if($eventNM>0){
	$participantSQL="select * from participant where event_id='".$eventId."'";
	$participantRS=$db2->query($participantSQL);
	
	$participantNM=$participantRS->num_rows;
	$identify_count=0;
	
	
	
	for($i=0;$i<$participantNM;$i++){
		$participantRow=$participantRS->fetch_assoc();
		
		if($participantRow['participant_id']==""){
			$identify_count++;
		}
	?>	
		<tr>	
		<td style="background-color: white;color: black;"><?php echo $participantRow['last_name'].", ".$participantRow['first_name']." ".$participantRow['middle_name']; ?></td>
		<td style="background-color: white;color: black;"><a href='#' onclick='window.open("database/sked_identify_participant.php?fI=<?php echo $participantRow['first_name']; ?>&mI=<?php echo $participantRow['middle_name']; ?>&lI=<?php echo $participantRow['last_name']; ?>&id=<?php echo $participantRow['id']; ?>")' ><?php if($participantRow['participant_id']==""){ echo "Identify"; } else { echo "Identified"; } ?></a></td>
		<td style="background-color: white;color: black;"><a href='#' onclick='window.open("database/sked_remove_entry.php?participant=<?php echo $participantRow['id']; ?>")'>Remove</a></td>
		<td style="background-color: white;color: black;"><?php echo $participantRow['remarks']; ?> <a href='#' onclick='window.open("database/sked_add_remarks.php?participant=<?php echo $participantRow['id']; ?>")'>Edit Remarks</a></td>

		</tr>
	<?php
	}
}
?>
</table>
<br>
<?php 
if($eventNM>0){
?>
<div align=center>
<a href='training_event.php?a=ZZ'  onclick='window.open("database/sked_add_participant.php")'>Add</a><!--enable only if Event has been added -->
</div>
<br>
<?php
}
?>
<br>
<?php 
if($identify_count==0){
?>
<table width=60% align=center>
<tr>
<th><input type=button value='Flush Changes' onclick='flushChanges()' /></th>
<th><input type=button value='Submit Details' onclick='submitDetails()' /></th></tr>
</table>
<?php
}
?>
<?php
//Add Identify column to Participants.  Participants can be entered directly, but they need to be
//identified to make it connectable to database

//Add Remove column to Trainers and Participants, to give option to remove data
?>













