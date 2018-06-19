<?php
	require("../db_page.php");
	$db=retrieveTrainingDb();
	$db2=retrieveTemporaryDb();
	
	if(isset($_POST['training_program'])){
		$search="select * from event";
		$searchRS=$db2->query($search);
		$searchNM=$searchRS->num_rows;

		if($searchNM>0){
			$searchRow=$searchRS->fetch_assoc();
			$update="update event set program_id='".$_POST['training_program']."', batch='".$_POST['batch_number']."'";
		}
		else {
			$update="insert into event(program_id,batch) values ('".$_POST['training_program']."','".$_POST['batch_number']."')";
		
		}
		
		$updateRS=$db2->query($update);
		
		//$direct="Zm";
		echo "<script language='javascript'>";
		//echo "";
		echo "window.opener.location.reload();";
			
		//echo "window.opener.location='../training_event.php?a='+".$_GET['direct'].";";
		echo "</script>";		
		
		
	}
?>
<script language='javascript'>
function submitForm(){

	document.forms['eventForm'].submit();
	//window.opener.location="../training_event.php?a=ZZ";
	//	window.opener.location.reload();



}
</script>
<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post' id='eventForm' name='eventForm' >
<table id='dynamicProgram' width=600 align=center style='border: 1px solid gray'>
	<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Change Training Course</th></tr>
	<tr>
		<td style="background-color:white; color:black;">
		Title of New Training Course
		</td>
		<td style="background-color:white; color:black;">
		<select name='training_program'>
		<?php	
		$sql="select * from training_programs order by training_title";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			
			if($row['division_code']=="OTH"){
				$office=$row['alt_office'];
			
			}
			else {
				$office=$row['division_code'];
			
			}
			
			
		?>	
		<option value="<?php echo $row['id']; ?>"><?php echo $row['training_title'].", ".$office; ?></option>	
			
			
		<?php 
		}
		?>
		</select>
		</td>
	</tr>	
	<tr>
		<td style="background-color:white; color:black;">
		Batch Number
		</td>
		<td style="background-color:white; color:black;">
			<input type=text id='batch_number' name='batch_number' size=4 />
		</td>
	</tr>	
	<tr>
		<td colspan=2 align=center>
			<input type='button' value='Submit' onclick='submitForm()' />	
		</td>
	</tr>
</table>
</form>