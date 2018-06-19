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
$_SESSION['training_page']="trainer_report.php";
?>
<?php
if(isset($_POST['date_filter'])){
	$dateMonthly=$_POST['date_filter'];

	//$db=new mysqli("localhost","root","","training");
	$db=retrieveTrainingDb();
	$fromMonth=$_POST['fromMonth'];
	$fromDay=$_POST['fromDay'];
	$fromYear=$_POST['fromYear'];


	$toMonth=$_POST['toMonth'];
	$toDay=$_POST['toDay'];
	$toYear=$_POST['toYear'];

	$_SESSION['date_filter']=$dateMonthly;
	$_SESSION['fromMonth']=$fromMonth;
	$_SESSION['fromDay']=$fromDay;
	$_SESSION['fromYear']=$fromYear;

	$_SESSION['toMonth']=$toMonth;
	$_SESSION['toDay']=$toDay;
	$_SESSION['toYear']=$toYear;
	
	$_SESSION['batch_no']=$_POST['batch_no'];

//	echo $_SESSION['date_filter'];
	if($dateMonthly=="dRange"){
		$dateFrom=$fromYear."-".$fromMonth."-".$fromDay." 00:00:00";
		$dateTo=$toYear."-".$toMonth."-".$toDay." 23:59:59";		
	
	}
	else if($dateMonthly=="daily"){
		$dateFrom=$fromYear."-".$fromMonth."-".$fromDay." 00:00:00";
		$dateTo=$fromYear."-".$fromMonth."-".$fromDay." 23:59:59";
		
		
		
		
	}
	else if($dateMonthly=="monthly"){
		$dateFrom=$fromYear."-".$fromMonth."-01 00:00:00";
		$limit=date("t",strtotime($fromYear."-".$fromMonth."-01"));
		$dateTo=$fromYear."-".$fromMonth."-".$limit." 23:59:59";		
		
	}
	else if($dateMonthly=="yearly"){
		$dateFrom=$fromYear."-01-01 00:00:00";
		$dateTo=$fromYear."-12-31 23:59:59";
	
	}
	
	$courseClause="";
	if(isset($_POST['selectedCourse'])){
		if($_POST['selectedCourse']==""){
			$_SESSION['courseType']="all";		
		
		}
		else {
			$_SESSION['courseType']="single";
			$_SESSION['courseId']=$_POST['selectedCourse'];
			$courseClause=" and program_id='".$_POST['selectedCourse']."'";
			
			if($_POST['batch_no']==""){ }
			else {
				$courseClause.=" and batch_number='".$_POST['batch_no']."'";
			
			}
			
		}
	}
	$sqlC="select *,(select count(*) from class_instance where training_event_id=training_instance.id) as attendees from training_instance where start_date between '".$dateFrom."' and '".$dateTo."' ".$courseClause." order by start_date desc";

	$rsC=$db->query($sqlC);
	$nmC=$rsC->num_rows;
	$count=$nmC;

}
?>
<?php
$db=retrieveTrainingDb();
//$db=new mysqli("localhost","root","","training");
$sql="select * from training_programs";
$rs=$db->query($sql);
$nm=$rs->num_rows;

echo "<script language='javascript' >";
echo "var course=new Array();";
echo "var courseCount=0;";

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	echo "course[courseCount]=new Array();";
	if($row['division_code']=="OTH"){
		$office=$row['alt_office'];
	}
	else {
		$office=$row['division_code'];
	}
	echo "course[courseCount]['training_title']=\"".$row['training_title'].", ".$office."\";";
	

	echo "course[courseCount]['division']=\"".$row['division_code']."\";";
	echo "course[courseCount]['ID']='".$row['id']."';";
	echo "courseCount++;";

}

echo "</script>";
?>
<link rel="stylesheet" type="text/css" href="helpdesk_staff2.css" />
<script language='javascript'>
var searchTrainer=new Array();
var searchCount=0;
function fillSearchList(searchterm){
	document.getElementById('training_list').innerHTML="";
//	document.getElementById('trainer_list').length=0;
	searchTrainer.length=0;
	searchCount=0;
	if(searchterm==""){
		searchterm="xxxxxxxxxxxxxx";
	
	}


		for(i=0;i<course.length;i++){
			if((course[i]['training_title'].toLowerCase()).indexOf(searchterm)>-1){
				searchTrainer[searchCount]=new Array();
				searchTrainer[searchCount]['training_title']=course[i]['training_title'];
				searchTrainer[searchCount]['division']=course[i]['division'];
				searchTrainer[searchCount]['ID']=course[i]['ID'];
				searchCount++;
			}
		
		}


	


	var optionsGrid="";

	if(searchTrainer.length>0){
		optionsGrid+="<select id='course_sel' name='course_sel'>";		
	
		for(i=0;i<searchTrainer.length;i++){
			optionsGrid+="<option value='"+searchTrainer[i]['ID']+"' >";
			optionsGrid+=searchTrainer[i]['training_title'];
			optionsGrid+="</option>";
			
			if(i==0){
				document.getElementById('selectedCourse').value=searchTrainer[i]['ID'];
			}
			
		}	
		optionsGrid+="</select>";
		document.getElementById('training_list').innerHTML=optionsGrid;
	}
}


function selectOption(elementName,elementValue){
	var elm=document.getElementById(elementName);
	for(i=0;i<elm.options.length;i++){
		if(elm.options[i].value==elementValue){
			elm.options[i].selected=true;
		}
	}

}
function rangeThis(rangevalue){
	if(rangevalue=="dRange"){
		document.getElementById('toMonth').disabled=false;

		document.getElementById('toDay').disabled=false;
		document.getElementById('toYear').disabled=false;
	}
	else {
		document.getElementById('toMonth').value="";
		document.getElementById('toDay').value="";
		document.getElementById('toYear').value="";
		document.getElementById('toMonth').disabled=true;
		document.getElementById('toDay').disabled=true;
		document.getElementById('toYear').disabled=true;


		}

}
function prepareSubmit(){
	if(document.getElementById('training_title').value==""){
		document.forms['trainerForm'].submit();
	
	}
	else {
		document.getElementById('selectedCourse').value=document.getElementById('course_sel').value;
		document.forms['trainerForm'].submit();
	}
}
</script>
<title>Training Database System</title>

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
		<!--Heading Table-->
	<form name='trainerForm' id='trainerForm'  action='trainer_report.php' method=post>

	<?php 

	$headingTable="
	<table align=center width=100%>
	<tr><th style='border: 1px solid gray; color: black;background-color: #00cc66;'><h2>MONTHLY TRAINING REPORT</h2></th>
	</tr>
	</table>";	
	echo $headingTable;

	?>
	<b>Enter Training Course:</b>
	<input type=text name='training_title' id='training_title' onkeyup='fillSearchList(this.value)' /> <span id='training_list'></span><input type=hidden name='selectedCourse' id='selectedCourse' />
	<br>	
	<b>Enter Batch No.</b><input type=text size=5 name='batch_no' id='batch_no' /> Leave Blank to Cover All
	<br>
	<b>Period Covered:</b>
	<select name='date_filter' onchange='rangeThis(this.value)'>
		<option <?php if(($_POST['date_filter']=="dRange")||($_POST['date_filter']=="")) { echo "selected=true"; } ?> value='dRange'>Date Range:</option> 
		<option <?php if($_POST['date_filter']=="daily") { echo "selected=true"; } ?> value='daily'>Daily</option> 
		<option <?php if($_POST['date_filter']=="monthly") { echo "selected=true"; } ?> value='monthly' >Monthly</option> 
		<option <?php if($_POST['date_filter']=="yearly") { echo "selected=true"; } ?> value='yearly'>Annually</option> 
	</select>
	<b>From:</b> 	
		<?php
		retrieveMonthListHTML("fromMonth");
		retrieveDayListHTML("fromDay");
		retrieveYearListHTML("fromYear");
		?>
	
	<b>To:</b> 
		<?php
		retrieveMonthListHTML("toMonth");
		retrieveDayListHTML("toDay");
		retrieveYearListHTML("toYear");
		?>
	
	<?php
	echo "<script language='javascript'>";
	echo "document.getElementById('fromMonth').value='".$fromMonth."';";
	echo "document.getElementById('fromDay').value='".$fromDay."';";
	echo "document.getElementById('fromYear').value='".$fromYear."';";

	echo "document.getElementById('toMonth').value='".$toMonth."';";
	echo "document.getElementById('toDay').value='".$toDay."';";
	echo "document.getElementById('toYear').value='".$toYear."';";
	
	echo "</script>";
	
	?>
	
	
	
	<input type=button value='Filter' onclick='prepareSubmit()' />	
	</form>
	<br>
	
<?php

if($count>0){	

?>

	<table id='alterTable' width=100%>
<?php
if($courseClause==""){
}
else {
	$titleSQL="select * from training_programs where id='".$_POST['selectedCourse']."'";
	$titleRS=$db->query($titleSQL);
	$titleRow=$titleRS->fetch_assoc();
	

?>
	<tr>
	<th colspan=5><h2><?php echo $titleRow['training_title']; ?></h2></th>
	</tr>
<?php
}
?>	
	
	<tr>
	<th>Training Course</th>
	<th>Batch Number</th>
	<th>Start Date</th>
	<th>End Date</th>
	<th>Number of Participants</th>
	</tr>
<?php
	for($i=0;$i<$nmC;$i++){
		$row=$rsC->fetch_assoc();
	?>
	<tr>
		<td><a href='#' onclick='window.open("editEvent2.php?retrieveID=<?php echo $row['id'];?>","_blank")'><?php echo $row['training_title']; ?></a>
		</td>
		<td><?php echo "#".$row['batch_number']; ?></td>
		<td><?php echo date("F d, Y",strtotime($row['start_date'])); ?></td>
		<td><?php echo date("F d, Y",strtotime($row['end_date'])); ?></td>
		<td><?php echo $row['attendees']; ?></td>
	</tr>

<?php
	}

?>
	</table>
	<br>
	<div align="center">
	<input type=button value='Generate Printout' name='makePrintout' onclick="window.open('generate_report.php');" />
	
	
<?php
}
?>


	</td>
</tr>
<table>	

