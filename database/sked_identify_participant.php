<?php
require("../db_page.php");
$db=retrieveTrainingDb();

$db2=retrieveTemporaryDb();

?>
<?php
$first_name=str_replace("%F1","ñ",str_replace("%D1","Ñ",$_GET['fI']));
$last_name=str_replace("%F1","ñ",str_replace("%D1","Ñ",$_GET['lI']));
$middle_name=str_replace("%F1","ñ",str_replace("%D1","Ñ",$_GET['mI']));

$participant_id=$_GET['id'];

if(isset($_POST['setAs'])){
	if($_POST['setAs']=="existing"){
		$existing_id=$_POST['existingId'];
		$index_id=$_POST['indexId'];
		
		$update="update participant set participant_id='".$existing_id."' where id='".$index_id."'";
		$updateRS=$db2->query($update);
	}
	else if($_POST['setAs']=="new"){
		$index_id=$_POST['indexId'];		
		$retrieve="select * from participant where id='".$index_id."'";
		$retrieveRS=$db2->query($retrieve);
		$retrieveNM=$retrieveRS->num_rows;
		
		if($retrieveNM>0){
			$retrieveRow=$retrieveRS->fetch_assoc();
			
			$fName_entry=$retrieveRow['first_name'];			
			$lName_entry=$retrieveRow['last_name'];			
			$mName_entry=$retrieveRow['middle_name'];			
			$designation_entry=$retrieveRow['designation'];			
			$department_entry=$retrieveRow['department'];			
			
			$insert="insert into trainee_list(firstName,lastName,designation,department,midInitial) ";
			$insert.=" values (\"".$fName_entry."\",\"".$lName_entry."\",'".$designation_entry."','".$department_entry."',\"".$mName_entry."\")";
			$insertRS=$db->query($insert);

			$existing_id=$db->insert_id;
			
			$update="update participant set participant_id='".$existing_id."' where id='".$index_id."'";
			
			$updateRS=$db2->query($update);
		
		}
	}
	echo "<script language='javascript'>";
	echo "window.opener.location.reload();";

	
	//echo "window.opener.location='../training_event.php?a=ZZ';";
	echo "</script>";
	
}
?>
<script language='javascript'>
function setIdentity(fName,midInitial,lName,tId){
	document.getElementById('first_name').value=fName;
	document.getElementById('middle_name').value=midInitial;
	document.getElementById('last_name').value=lName;
	document.getElementById('existingId').value=tId;
	
	document.getElementById('setAs').value="existing";
		
	
	
}

function newIdentity(element_value){
	if(element_value=="new"){

		document.getElementById('first_name').value="";
		document.getElementById('middle_name').value="";
		document.getElementById('last_name').value="";
		document.getElementById('existingId').value="";

	}

}
function submitForm(){

	document.forms['setAsForm'].submit();
//		window.opener.location="../training_event.php?a=ZZ";
//		window.opener.location.reload();



}





</script>
<table width=600 style='border: 1px solid gray' >
<tr  style="background-color: #00cc66;color: white;" >
	<th>First Name</th>
	<th>Middle Name</th>
	<th>Last Name</th>
	<th>Designation</th>
	<th>Division</th>
	<th>&nbsp;</th>
</tr>
<?php
if(isset($_GET['fI'])){
	$sql="select * from trainee_list where firstName like '".$first_name."%%' and lastName like '".$last_name."%%' and midInitial like '".$middle_name."%%' order by lastName";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
		
	?>	
	<tr style="background-color:white; color:black;">
		<td><?php echo $row['firstName']; ?></td>
		<td><?php echo $row['midInitial']; ?></td>
		<td><?php echo $row['lastName']; ?></td>
		<td><?php echo $row['designation']; ?></td>
		<td><?php echo $row['department']; ?></td>
		<td><a href='#' onclick='setIdentity("<?php echo $row['firstName']; ?>","<?php echo $row['midInitial']; ?>","<?php echo $row['lastName']; ?>","<?php echo $row['id']; ?>")'>Identify</a></td>
	</tr>
<?php	
	}
}
?>
</table>
<br>
<form name='setAsForm' id='setAsForm' action='sked_identify_participant.php' method='post'>
<table width=600  style='border: 1px solid gray'>
<tr style="background-color: #00cc66;color: white;" >
<td>Set if New/Existing</td>
<td>
	<select name='setAs' id='setAs' onchange='newIdentity(this.value)'>
		<option value='new'>New</option>
		<option value='existing'>Existing</option>
	</select>
</td>	
</tr>
<tr>
<th colspan=2>Records (if existing)</th>
</tr>
<tr>
<td style="background-color:white; color:black;">First Name</td>
<td><input type=text name='first_name' id='first_name' size='20' /></td>
</tr>
<tr>
<td style="background-color:white; color:black;">Middle Name</td>
<td><input type=text name='middle_name' id='middle_name' size='20' /></td>
</tr>
<tr>
<td style="background-color:white; color:black;">Last Name</td>
<td><input type=text name='last_name' id='last_name' size='20' /></td>
</tr>
<tr>
<td colspan=2 align=center><input type=hidden name='existingId' id='existingId' /><input type=hidden name='indexId' id='indexId' value='<?php echo $participant_id; ?>' /><input type='button' value='Identify' onclick='submitForm()' /></td>
</tr>
</table>
</form>