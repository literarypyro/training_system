<?php
require("../db_page.php");
$db=retrieveTrainingDb();
$db2=retrieveTemporaryDb();

if($_POST['trainer']){

	$eventSQL="select * from event";
	$eventRS=$db2->query($eventSQL);
	
	$eventRow=$eventRS->fetch_assoc();
	$eventId=$eventRow['id'];
	
	$update="insert into trainer(event_id,trainer_id) values ('".$eventId."','".$_POST['trainer']."')";
	$updateRS=$db2->query($update);
	
	echo "<script language='javascript'>";
	echo "window.opener.location.reload();";

	
	//echo "window.opener.location='../training_event.php?a=ZZ';";
	echo "</script>";	
	
	
}	

?>
<script language='javascript'>
function submitForm(){
	document.forms['trainerForm'].submit();
//	window.opener.location="../training_event.php?a=ZZ";
	//window.opener.location.reload();
}
</script>
	<form name='trainerForm' id='trainerForm' action='sked_add_trainer.php' method='post'>
	<table width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Add Trainer</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			Trainer
			</td>
			<td style="background-color:white; color:black;">
			<select name='trainer'>
			<?php
			$sql="select * from trainer_list order by lastName";	
			$rs=$db->query($sql);
			
			$nm=$rs->num_rows;
			for($i=0;$i<$nm;$i++){
				$row=$rs->fetch_assoc();
			?>
				<option value='<?php echo $row['id']; ?>'><?php echo $row['lastName'].", ".$row['firstName']; ?></option>
			<?php
			}
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td align=center colspan=2><input type=button value='Submit' onclick='submitForm()' /></td>
		</tr>
	</table>
	</form>