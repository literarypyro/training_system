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
	if($_GET['a']=="record"){
		$_SESSION['training_page']="record_attendance.php";
	
	}	
	else if($_GET['a']=="hourly"){
		$_SESSION['training_page']="hourly_schedule.php";
	}
	
	else {
		$_SESSION['training_page']="trainer_aid.php";
	
	}

if(isset($_POST['selMonth'])){

	$sshift=($_POST['startingHour']."".$_POST['startingMinute'])*1;
	$amorpm=$_POST['startingHalf'];
	if($amorpm=="PM"){
		if($_POST['startingHour']=="12"){
		}
		else {
			$sshift+=1200*1;
		}
	}
	else {
		if($_POST['startingHour']=="12"){

			$sshift+=1200*1;
		}
	}
	if($sshift>=2400){
		$sshift-=2400*1;
	}
	$timeConstruct=str_replace($amorpm,"",$sshift);

	$hourConstruct=($timeConstruct/100)-((".".($timeConstruct%100))*1);
	$minuteConstruct=substr($timeConstruct,strlen($timeConstruct)*1-2,2);

	$sstartTime=$hourConstruct.":".$minuteConstruct.":00";
	$a=$hourConstruct.$minuteConstruct*1;	

	$sshift=($_POST['endingHour']."".$_POST['endingMinute'])*1;
	$amorpm=$_POST['endingHalf'];
	if($amorpm=="PM"){
		if($_POST['endingHour']=="12"){
		}
		else {
			$sshift+=1200*1;
		}
	}
	else {
		if($_POST['endingHour']=="12"){
			$sshift+=1200*1;
		}
	
	}
	
	
	if($sshift>=2400){
		$sshift-=2400*1;
	}	
	$timeConstruct=str_replace($amorpm,"",$sshift);

	$hourConstruct=($timeConstruct/100)-((".".($timeConstruct%100))*1);
	$minuteConstruct=substr($timeConstruct,strlen($timeConstruct)*1-2,2);	
	
	$sendTime=$hourConstruct.":".$minuteConstruct.":00";
	$b=$hourConstruct.$minuteConstruct*1;	
	if($b<$a){
		$a=$sstartTime;
		$sstartTime=$sendTime;
		$sendTime=$a;
	}
	
	
	
	$event_id=$_POST['eventID'];
	$dateBegin=date("Y-m-d",strtotime($_POST['selYear']."-".$_POST['selMonth']."-".$_POST['selDay']));
//	$db=new mysqli("localhost","root","","training");
	$db=retrieveTrainingDb();	
	$sql="insert into training_schedule(event_id,date,start_time,end_time,location,activity) values ";
	$sql.="('".$event_id."','".$dateBegin."','".$dateBegin." ".$sstartTime."','".$dateBegin." ".$sendTime."','".$_POST['location']."','".$_POST['activity']."')";		

	$rs=$db->query($sql);

	
	$verifySQL="select * from training_instance where id='".$event_id."'";
	$verifyRS=$db->query($verifySQL);
	$verifyRow=$verifyRS->fetch_assoc();
	
	$startDate=$verifyRow['start_date'];
	$endDate=$verifyRow['end_date'];
	
	if(strtotime($dateBegin)<strtotime($startDate)){
		$update="update training_events set start_date='".$dateBegin." ".$sstartTime."' where id='".$event_id."'";
		$rs=$db->query($update);
	}
	if(strtotime($dateBegin)>strtotime($endDate)){
		$update="update training_events set end_date='".$dateBegin." ".$sendTime."' where id='".$event_id."'";
		$rs=$db->query($update);
	}

	

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
<title>Trainer Database Entry</title>

	<body style="background-image:url('body background.jpg');">

	<?php
	require("training_header.php");
	?>
<?php echo $caption; ?>
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

	if($_GET['a']=='hourly'){
		require("database/hourly_schedule.php");

	}
	else if($_GET['a']=="memo"){
		require("database/memo_attendance.php");
	
	
	}
	else if($_GET['a']=="train"){
		require("database/train_availability.php");
	}

	else if($_GET['a']=="record"){
		//$_SESSION['training_page']=="record_attendance.php";
		require("database/record_attendance.php");
	}	
	else if($_GET['a']=="diploma"){
		//$_SESSION['training_page']=="record_attendance.php";
		require("database/diploma_attendance.php");
	}	

	
?>

	</td>
</tr>
<table>	