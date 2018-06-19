<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
require("form functions.php");
require("db_page.php");
?>
<?php
$_SESSION['training_page']="index.php";

?>
<?php
$updated=0;
if(isset($_POST['accid'])){
	$loginHour=adjustTime($_POST['loginamorpm'],$_POST['loginHour']);
	$loginMinute=$_POST['loginMinute'];
	$loginDay=$_POST['loginYear']."-".$_POST['loginMonth']."-".$_POST['loginDay'];
	$login_date=$loginDay." ".$loginHour.":".$loginMinute.":00";

	$actionTaken2=$_POST['action_taken'];
	$recommendation2=$_POST['recommendation'];
	$status2=$_POST['request_status'];
	
//	$db=new mysqli("localhost","root","","helpdesk_backup");
	$db=retrieveHelpdeskDb();
	$sql="update accomplishment set action_taken='".$actionTaken2."',recommendation='".$recommendation2."',accomplishment.status='".$status2."',accomplish_time='".$login_date."' where id='".$_POST['accid']."'"; 
	$rs=$db->query($sql);
	$updated++;
	
	}
?>

<link rel="stylesheet" type="text/css" href="helpdesk_staff2.css" />
<script language='javascript'>
function selectOption(elementName,elementValue){
	var elm=document.getElementById(elementName);
	for(i=0;i<elm.options.length;i++){
		if(elm.options[i].value==elementValue){
			elm.options[i].selected=true;
		}
	}

}
</script>
<title>Training Database System</title>
	<body style="background-image:url('body background.jpg');">

	<?php
	require("training_header.php");
	?>


<table width="100%"  bgcolor="#FFFFFF" cellpadding="5px" bordercolor="#CCCCCC" style="border-left-width: 1px; border-right-width: 1px; border-bottom-width: 1px">
<tr>
	<th style='border: 1px solid gray;	background-color: #00cc66;' colspan=2 align=right><marquee style='font: bold 18px "Trebuchet MS", Arial, sans-serif;color:black;'>Support Staff/AFC Center/Computer Section</marquee></th>
</tr>

<tr>
	<?php 
	require("helpdesk_sidebar.php");
	//background-color:#66ceae; 
	?>
	<td width="85%" rowspan=2 valign="top"  style="background-color:#66ceae; border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color:black;" bordercolor="#FF6600">
<?php
//$db=new mysqli("localhost","root","","helpdesk_backup");
		$dateLatest=date("Y-m-d",strtotime(date("Y-m-d")."-5 days"));
		//$db=new mysqli("localhost","root","","training");
		$db=retrieveTrainingDb();
		$sql="select *,(select count(training_event_id) from class_instance where training_event_id=training_instance.id) as trainee_count from training_instance where start_date>'".$dateLatest."'";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		//background-color:#ffe573;
		//background-color: #ff4500;
		//background-color: #0066cb;color: #ffcc35;
		
		
?>
	
	<form action='task_printout.php' method='post'>
	<table width=50% align=center>
	<tr>
	<th style='border: 1px solid gray;  color: black;background-color: #00cc66;'> 
	<h1>
	<?php echo strtoupper("Upcoming Training Programs"); ?>
	</h1>
	</th>
	</tr>
	</table>
	<?php
	if($updated>0){
		echo "<font color=red>Accomplishment was updated. Resubmit to view changes.";
	}
	?>
	</form>
	<?php
/**		$_SESSION['helpdesk_printout']=$_POST['task_id'];
		
		$db=new mysqli("localhost","root","","training");
		$sql2="select * from class_instance where training_event_id=''";
		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;
*/
		
		//$sql3="select * from computer where id='".$row2['unit_id']."'";
	//	$rs3=$db->query($sql3);
		//$row3=$rs3->fetch_assoc();
	
?>
	<form action='task_printout.php' method='post'>
	<table id='alterTable' width=100%>
	<tr>
	<th>Training Course</th>
	<th>Batch Number</th>
	<th>Start Date</th>
	<th>End Date</th>
	<th>Number of Participants</th>
	</tr>
	<?php
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
	<tr>
	<td><?php echo $row['training_title']; ?></td>
	<td align=center><?php echo "#".$row['batch_number']; ?></td>
	<td><?php echo date("F d, Y" ,strtotime($row['start_date'])); ?></td>
	<td><?php echo date("F d, Y",strtotime($row['end_date'])); ?></td>
	<td align=center><?php echo $row['trainee_count']; ?></td>
	</tr>
	<?php
	}
	?>
	</table>


	</td>
</tr>
</table>
</body>