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
$_SESSION['training_page']="training_event.php";
?>
<?php
if(isset($_POST['trainerSubmit'])){
	
	if($_POST['action']=="Add"){
		$date=explode(",",$_POST['dateSubmit']);
	//	$date=$_POST['dateSubmit'];
		for($i=0;$i<count($date);$i++){
			$year[$i]=substr($date[$i],0,4);
			$month[$i]=substr($date[$i],4,2);
			$day[$i]=substr($date[$i],6,2);
			$dateString[$i]=$year[$i]."-".$month[$i]."-".$day[$i];
		}
		
		$dateStart=$_POST['startSubmit'];
		$dateEnd=$_POST['endSubmit'];

		$batch=$_POST['batchSubmit'];
		$no_days=$_POST['no_daysSubmit'];
		$dateCount=count($date);
		$dateCount--;
		$trainee=explode(",",$_POST['traineeSubmit']);

		$trainer=explode(",",$_POST['trainerSubmit']);

		
		$shift=explode(",",$_POST['shiftSubmit']);
		if($shift[0]=="830AM"){
			$startTime="08:30:00";
			$endTime="17:30:00";
		}
		else if($shift=="930AM"){
			$startTime="09:30:00";
			$endTime="17:30:00";
		
		}
		else {
			$startTime="08:30:00";
			$endTime="17:30:00";
		
		}

		$sshift=$_POST['startshiftSubmit'];
		if($sshift=="830AM"){
			$sstartTime="08:30:00";
			$sendTime="17:30:00";
		}
		else if($sshift=="930AM"){
			$sstartTime="09:30:00";
			$sendTime="17:30:00";
		
		}
		else {
			$amorpm=substr($sshift,strlen($sshift)*1-2,2);
			if($amorpm=="PM"){
				$sshift+=1200*1;
			}
			if($eshift>=2400){
				$sshift-=2400*1;
			}
			
			$timeConstruct=str_replace($amorpm,"",$sshift);

			$hourConstruct=($timeConstruct/100)-((".".($timeConstruct%100))*1);
			$minuteConstruct=substr($timeConstruct,strlen($timeConstruct)*1-2,2);

			$sstartTime=$hourConstruct.":".$minuteConstruct.":00";
			$sendTime="17:30:00";
		
		}



		$eshift=$_POST['endshiftSubmit'];
		if($eshift=="830AM"){
			$estartTime="08:30:00";
			$eendTime="17:30:00";
		}
		else if($eshift=="930AM"){
			$estartTime="09:30:00";
			$eendTime="17:30:00";
		
		}
		else {
			$amorpm=substr($eshift,strlen($eshift)*1-2,2);
			if($amorpm=="PM"){
				$eshift+=1200*1;
			}
			if($eshift>=2400){
				$eshift-=2400*1;
			}
			
			$timeConstruct=str_replace($amorpm,"",$eshift);

			$hourConstruct=($timeConstruct/100)-((".".($timeConstruct%100))*1);
			$minuteConstruct=substr($timeConstruct,strlen($timeConstruct)*1-2,2);

			$estartTime=$hourConstruct.":".$minuteConstruct.":00";
			$eendTime="17:30:00";
		
		}
		

		//$db=new mysqli("localhost","root","","training");
		$db=retrieveTrainingDb();
		$sql="select * from training_events where training_program_id='".$_POST['programSubmit']."'";
		$rs=$db->query($sql);
		
//		$batchNumber=$rs->num_rows;
//		$batchNumber++;
		$batchNumber=$batch;
		
		$year=substr($dateStart,0,4);
		$month=substr($dateStart,4,2);
		$day=substr($dateStart,6,2);
		$dateBegin=$year."-".$month."-".$day;
		
		$year2=substr($dateEnd,0,4);
		$month2=substr($dateEnd,4,2);
		$day2=substr($dateEnd,6,2);
		$dateFinish=$year2."-".$month2."-".$day2;
		

		$sql="insert into training_events(training_program_id,batch_number,start_date,end_date,no_of_days) values ";
		$sql.="('".$_POST['programSubmit']."','".$batchNumber."','".$dateBegin." ".$sstartTime."','".$dateFinish." ".$estartTime."','".$no_days."')";
		$rs=$db->query($sql);
		$event_id=$db->insert_id;
		
		
		$year=substr($dateStart,0,4);
		$month=substr($dateStart,4,2);
		$day=substr($dateStart,6,2);
		$dateBegin=$year."-".$month."-".$day;

			$sql="insert into training_schedule(event_id,date,start_time,end_time) values ";
			$sql.="('".$event_id."','".$dateBegin."','".$dateBegin." ".$sstartTime."','".$dateBegin." ".$sendTime."')";		
			$rs=$db->query($sql);

			$sql="insert into training_schedule(event_id,date,start_time,end_time,activity) values ";
			$sql.="('".$event_id."','".$dateBegin."','".$dateBegin." 12:00:00','".$dateBegin." 13:15:00','Lunch')";		
			$rs=$db->query($sql);
			
			
		if($dateStart==$dateEnd){
		}
		else {
			$year=substr($dateEnd,0,4);
			$month=substr($dateEnd,4,2);
			$day=substr($dateEnd,6,2);
			$dateFinish=$year."-".$month."-".$day;

				$sql="insert into training_schedule(event_id,date,start_time,end_time) values ";
				$sql.="('".$event_id."','".$dateFinish."','".$dateFinish." ".$estartTime."','".$dateFinish." ".$eendTime."')";		
				$rs=$db->query($sql);

				$sql="insert into training_schedule(event_id,date,start_time,end_time,activity) values ";
				$sql.="('".$event_id."','".$dateFinish."','".$dateFinish." 12:00:00','".$dateFinish." 13:15:00','Lunch')";		
				$rs=$db->query($sql);
		}
		for($i=0;$i<count($trainee);$i++){
			$sql="insert into class_list(training_events_id,trainee_id) values ";
			$sql.="('".$event_id."','".$trainee[$i]."')";		
			$rs=$db->query($sql);
		}

		for($i=0;$i<count($trainer);$i++){
			$sql="insert into trainer_class(event_id,trainer_id) values ";
			$sql.="('".$event_id."','".$trainer[$i]."')";	
			$rs=$db->query($sql);
		}
//		$caption="The schedule has been created!";
		$_SESSION['previewID']=$event_id;
		
		require("generate_preview.php");
		$caption=$_SESSION['caption'];		
		
		
		
	}
	else if($_POST['action']=="Delete"){
		$db=retrieveTrainingDb();
		//$db=new mysqli("localhost","root","","training");


		$sql="delete from training_events where id='".$_POST['modifyId']."'";
		$rs=$db->query($sql);
		
		$sql="delete from training_schedule where event_id='".$_POST['modifyId']."'";
		$rs=$db->query($sql);		
	
		$sql="delete from trainer_class where event_id='".$_POST['modifyId']."'";
		$rs=$db->query($sql);	

		$sql="delete from class_list where training_events_id='".$_POST['modifyId']."'";
		$rs=$db->query($sql);	

		$sql="delete from diploma_release where training_program_id='".$_POST['modifyId']."'";
		$rs=$db->query($sql);	

		$sql="delete from certificate_request where training_program_id='".$_POST['modifyId']."'";
		$rs=$db->query($sql);		

		$caption="The schedule has been deleted";	
		
	}
	else if($_POST['action']=="Edit"){
		$date=explode(",",$_POST['dateSubmit']);
	//	$date=$_POST['dateSubmit'];
		for($i=0;$i<count($date);$i++){
			$year[$i]=substr($date[$i],0,4);
			$month[$i]=substr($date[$i],4,2);
			$day[$i]=substr($date[$i],6,2);
			$dateString[$i]=$year[$i]."-".$month[$i]."-".$day[$i];
		}
		
		$dateCount=count($date);
		$dateCount--;
		$trainee=explode(",",$_POST['traineeSubmit']);
		$trainer=explode(",",$_POST['trainerSubmit']);		
		$shift=explode(",",$_POST['shiftSubmit']);
		if($shift[0]=="830AM"){
			$startTime="08:30:00";
			$endTime="17:30:00";
		}
		else if($shift=="930AM"){
			$startTime="09:30:00";
			$endTime="17:30:00";
		
		}
		else {
			$startTime="08:30:00";
			$endTime="17:30:00";
		
		}

		$sshift=$_POST['startshiftSubmit'];
		if($sshift=="830AM"){
			$sstartTime="08:30:00";
			$sendTime="17:30:00";
		}
		else if($sshift=="930AM"){
			$sstartTime="09:30:00";
			$sendTime="17:30:00";
		
		}
		else {
			$amorpm=substr($sshift,strlen($sshift)*1-2,2);
			if($amorpm=="PM"){
				$sshift+=1200*1;
			}
			if($sshift>=2400){
				$sshift-=2400*1;
			}
			
			$timeConstruct=str_replace($amorpm,"",$sshift);

			$hourConstruct=($timeConstruct/100)-((".".($timeConstruct%100))*1);
			$minuteConstruct=substr($timeConstruct,strlen($timeConstruct)*1-2,2);

			$sstartTime=$hourConstruct.":".$minuteConstruct.":00";
			$sendTime="17:30:00";
		
		}



		$eshift=$_POST['endshiftSubmit'];
		if($eshift=="830AM"){
			$estartTime="08:30:00";
			$eendTime="17:30:00";
		}
		else if($eshift=="930AM"){
			$estartTime="09:30:00";
			$eendTime="17:30:00";
		
		}
		else {
			$amorpm=substr($eshift,strlen($eshift)*1-2,2);
			if($amorpm=="PM"){
				$eshift+=1200*1;
			}
			if($eshift>=2400){
				$eshift-=2400*1;
			}
			
			$timeConstruct=str_replace($amorpm,"",$eshift);

			$hourConstruct=($timeConstruct/100)-((".".($timeConstruct%100))*1);
			$minuteConstruct=substr($timeConstruct,strlen($timeConstruct)*1-2,2);

			$estartTime=$hourConstruct.":".$minuteConstruct.":00";
			$eendTime="17:30:00";
		
		}
		
		$dateStart=$_POST['startSubmit'];
		$dateEnd=$_POST['endSubmit'];
		$batch=$_POST['batchSubmit'];

		$year=substr($dateStart,0,4);
		$month=substr($dateStart,4,2);
		$day=substr($dateStart,6,2);
		$dateBegin=$year."-".$month."-".$day;
		
		$year2=substr($dateEnd,0,4);
		$month2=substr($dateEnd,4,2);
		$day2=substr($dateEnd,6,2);
		$dateFinish=$year2."-".$month2."-".$day2;
		
		//$db=new mysqli("localhost","root","","training");	
		$db=retrieveTrainingDb();
		$sql="update training_events set batch_number='".$batch."', start_date='".$dateBegin." ".$sstartTime."',end_date='".$dateFinish." ".$estartTime."',training_program_id='".$_POST['programSubmit']."',no_of_days='".$_POST['no_daysSubmit']."' where id='".$_POST['modifyId']."'";
		//echo $sql;
		$rs=$db->query($sql);
		$event_id=$_POST['modifyId'];
		
		$sql="delete from training_schedule where event_id='".$_POST['modifyId']."'";
		$rs=$db->query($sql);
		//echo $sql;
		$sql="delete from class_list where training_events_id='".$_POST['modifyId']."'";
		$rs=$db->query($sql);
		//echo $sql;
		$sql="delete from trainer_class where event_id='".$_POST['modifyId']."'";
		$rs=$db->query($sql);
		//echo $sql;
		
		$year=substr($dateStart,0,4);
		$month=substr($dateStart,4,2);
		$day=substr($dateStart,6,2);
		$dateBegin=$year."-".$month."-".$day;

			$sql="insert into training_schedule(event_id,date,start_time,end_time) values ";
			$sql.="('".$event_id."','".$dateBegin."','".$dateBegin." ".$sstartTime."','".$dateBegin." ".$sendTime."')";		
			$rs=$db->query($sql);
			
			$sql="insert into training_schedule(event_id,date,start_time,end_time,activity) values ";
			$sql.="('".$event_id."','".$dateBegin."','".$dateBegin." 12:00:00','".$dateBegin." 13:15:00','Lunch')";		
			$rs=$db->query($sql);
			
		if($dateStart==$dateEnd){
		}
		else {
			$year=substr($dateEnd,0,4);
			$month=substr($dateEnd,4,2);
			$day=substr($dateEnd,6,2);
			$dateFinish=$year."-".$month."-".$day;

				$sql="insert into training_schedule(event_id,date,start_time,end_time) values ";
				$sql.="('".$event_id."','".$dateFinish."','".$dateFinish." ".$estartTime."','".$dateFinish." ".$eendTime."')";		
				$rs=$db->query($sql);
				$sql="insert into training_schedule(event_id,date,start_time,end_time,activity) values ";
				$sql.="('".$event_id."','".$dateFinish."','".$dateFinish." 12:00:00','".$dateFinish." 13:15:00','Lunch')";		
				$rs=$db->query($sql);	

	}
		for($i=0;$i<count($trainee);$i++){
			$sql="insert into class_list(training_events_id,trainee_id) values ";
			$sql.="('".$event_id."','".$trainee[$i]."')";		
			$rs=$db->query($sql);
		}
		for($i=0;$i<count($trainer);$i++){
			$sql="insert into trainer_class(event_id,trainer_id) values ";
			$sql.="('".$event_id."','".$trainer[$i]."')";
			$rs=$db->query($sql);

		}
	
		$_SESSION['previewID']=$_POST['modifyId'];
		
		require("generate_preview.php");
		$caption=$_SESSION['caption'];
		//?previewID=".$_POST['modifyID']);
//		$caption="Training Event details have been changed!";
	
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
<?php echo $caption; ?>
	
<?php
	if($_GET['a']=="Cr"){
		require("database/add_schedule.php");
	}
	else if($_GET['a']=="md"){
		require("database/modify_schedule.php");
	}
	else if($_GET['a']=="ZZ"){
		require("database/schedule_main.php");
	
	}
	else if($_GET['a']=="AA"){
		require("database/schedule_main2.php");
	
	}	

?>

	</td>
</tr>
<table>	