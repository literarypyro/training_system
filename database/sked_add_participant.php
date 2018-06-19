<?php
require("../db_page.php");
$db=retrieveTrainingDb();
$db2=retrieveTemporaryDb();








if(isset($_POST['trainee_last_name'])){
	$eventSQL="select * from event";
	$eventRS=$db2->query($eventSQL);
	
	$eventRow=$eventRS->fetch_assoc();
	$eventId=$eventRow['id'];	
	
	$update="insert into participant(event_id,first_name,middle_name,last_name,designation,department) ";
	$update.=" values ('".$eventId."',\"".$_POST['trainee_first_name']."\",\"".$_POST['trainee_middle_name']."\",";
	$update.="\"".$_POST['trainee_last_name']."\",'".$_POST['trainee_position']."','".$_POST['divAssigned']."')";
	$rs=$db2->query($update);
	
	echo "<script language='javascript'>";
	echo "window.opener.location.reload();";

//	echo "window.opener.location='../training_event.php?a=ZZ';";
	echo "</script>";
}
?>


<script language='javascript'>
function submitForm(){
	document.forms['participantForm'].submit();
//	window.opener.location="../training_event.php?a=ZZ";
	//window.opener.location.reload();
}
</script>


		<form name='participantForm' id='participantForm' action="sked_add_participant.php" method="post">

		<table width=400 id='dynamicTrainees' align=center style='border: 1px solid gray'>
		<tr><th  style="background-color: #00cc66;color: white;" colspan=2>Add Participants</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			First Name
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='trainee_first_name' name='trainee_first_name' size=30 />
			</td>
		</tr>	
		<tr>
			<td style="background-color:white; color:black;">
			Last Name
			</td>
			<td style="background-color:white; color:black;">
				<input type=text id='trainee_last_name' name='trainee_last_name' size=30 />
			</td>
		</tr>
		<tr>
			<td style="background-color:white; color:black;">
			Middle Name
			</td>
			<td style="background-color:white; color:black;">
				<input type=text id='trainee_middle_name' name='trainee_middle_name' size=30 />
			</td>
		</tr>
		<tr>
			<td style="background-color:white; color:black;">Designation</td>
			<td style="background-color:white; color:black;"><input type='text' id='trainee_position' name='trainee_position' size=30 /></td>
		</tr>
		<tr>
			<td style="background-color:white; color:black;">Division Assigned</td>
			<td style="background-color:white; color:black;">
			<select name='divAssigned' id='divAssigned'>
				<option></option>
				<?php
//				$db=new mysqli("localhost","root","","training");
				
				$sql="select * from division";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();
				?>
					<option value='<?php echo $row['division_code']; ?>'><?php echo $row['division_name']; ?></option>
				<?php	
				}
//				$db->insert_id();
				?>	
			</select>
			</td>
		</tr>	
		<tr>
		<td colspan=2 align=center><input type=button value='Submit' onclick='submitForm()'	/></td>
		</tr>
		</table>
		</form>