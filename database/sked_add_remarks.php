<?php
require("../db_page.php");
$db=retrieveTrainingDb();

$db2=retrieveTemporaryDb();

?>
<?php

$participant_id=$_GET['participant'];

if(isset($_POST['indexId'])){
	$index_id=$_POST['indexId'];
	$update="update participant set remarks='".$_POST['remarks']."' where id='".$index_id."'";
	$updateRS=$db2->query($update);

	echo "<script language='javascript'>";
	echo "window.opener.location.reload();";

	
	//echo "window.opener.location='../training_event.php?a=ZZ';";
	echo "</script>";
	
}
?>
<script language='javascript'>

function submitForm(){

	document.forms['setAsForm'].submit();
//		window.opener.location="../training_event.php?a=ZZ";
//		window.opener.location.reload();



}





</script>

<br>
<form name='setAsForm' id='setAsForm' action='sked_add_remarks.php' method='post'>
<table width=600  style='border: 1px solid gray'>
<tr style="background-color: #00cc66;color: white;" >
<td>Add Remarks</td>
<td><textarea name='remarks' rows=5 cols=30 ></textarea>
</td>	
</tr>
<tr>
<td colspan=2 align=center><input type=hidden name='indexId' id='indexId' value='<?php echo $participant_id; ?>' /><input type='button' value='Add Remarks' onclick='submitForm()' /></td>
</tr>
</table>
</form>