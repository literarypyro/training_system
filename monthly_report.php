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
$_SESSION['training_page']="monthly_report.php";
?>
<?php

//$db=new mysqli("localhost","root","","training");
$db=retrieveTrainingDb();
$sql="select * from trainer_list order by lastName";
$rs=$db->query($sql);
$nm=$rs->num_rows;

echo "<script language='javascript' >";
echo "var trainer=new Array();";
echo "var trainerCount=0;";

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	echo "trainer[trainerCount]=new Array();";
	echo "trainer[trainerCount]['lastName']=\"".$row['lastName']."\";";
	echo "trainer[trainerCount]['firstName']=\"".$row['firstName']."\";";
	echo "trainer[trainerCount]['ID']='".$row['id']."';";
	echo "trainerCount++;";

}

echo "</script>";
?>
<script language="javascript">
var searchTrainer=new Array();
var searchCount=0;

function fillSearchList(searchterm){
	document.getElementById('trainer_list').innerHTML="";
//	document.getElementById('trainer_list').length=0;
	searchTrainer.length=0;
	searchCount=0;
	if(searchterm==""){
		searchterm="xxxxxxxxxxxxxx";
	
	}

	if(searchterm.indexOf(',')>-1){
		var searchkeys=new Array();
		searchkeys.length=0;
		searchkeys=searchterm.split(',');
		if(searchkeys[0]==""){
			searchkeys[0]="xxxxxxxxxxxxxxxxxx";
		}
		
		if(searchkeys[1]==""){
			searchkeys[1]="xxxxxxxxxxxxxxxxxx";
		}

		for(i=0;i<trainer.length;i++){
			if(((trainer[i]['lastName'].toLowerCase()).indexOf(searchkeys[0])>-1)||((trainer[i]['firstName'].toLowerCase()).indexOf(searchkeys[0])>-1)){
				searchTrainer[searchCount]=new Array();
				searchTrainer[searchCount]['firstName']=trainer[i]['firstName'];
				searchTrainer[searchCount]['lastName']=trainer[i]['lastName'];
				searchTrainer[searchCount]['ID']=trainer[i]['ID'];
				searchCount++;
				
			}
			else if(((trainer[i]['lastName'].toLowerCase()).indexOf(searchkeys[1])>-1)||((trainer[i]['firstName'].toLowerCase()).indexOf(searchkeys[1])>-1)){
				searchTrainer[searchCount]=new Array();
				searchTrainer[searchCount]['firstName']=trainer[i]['firstName'];
				searchTrainer[searchCount]['lastName']=trainer[i]['lastName'];
				searchTrainer[searchCount]['ID']=trainer[i]['ID'];
				searchCount++;

			}
		}
		
	}
	else {
		for(i=0;i<trainer.length;i++){
			if((trainer[i]['lastName'].toLowerCase()).indexOf(searchterm.trim())>-1){
				searchTrainer[searchCount]=new Array();
				searchTrainer[searchCount]['firstName']=trainer[i]['firstName'];
				searchTrainer[searchCount]['lastName']=trainer[i]['lastName'];
				searchTrainer[searchCount]['ID']=trainer[i]['ID'];
				searchCount++;
			
			}
			else if((trainer[i]['firstName'].toLowerCase()).indexOf(searchterm.trim())>-1){
				searchTrainer[searchCount]=new Array();
				searchTrainer[searchCount]['firstName']=trainer[i]['firstName'];
				searchTrainer[searchCount]['lastName']=trainer[i]['lastName'];
				searchTrainer[searchCount]['ID']=trainer[i]['ID'];
				searchCount++;
			
			}
		
		}

	}


	var optionsGrid="";

	if(searchTrainer.length>0){
		optionsGrid+="<select id='trainer_sel' name='trainer_sel'>";		
	
		for(i=0;i<searchTrainer.length;i++){
			optionsGrid+="<option value='"+searchTrainer[i]['ID']+"' >";
			optionsGrid+=searchTrainer[i]['lastName'].toUpperCase()+", "+searchTrainer[i]['firstName'];
			optionsGrid+="</option>";
			
			if(i==0){
				document.getElementById('selectedTrainer').value=searchTrainer[i]['ID'];
			}
			
		}	
		optionsGrid+="</select>";
		document.getElementById('trainer_list').innerHTML=optionsGrid;
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
</script>
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
function prepareSubmit(){
	if(document.getElementById('trainer_name').value==""){
		document.forms['trainerForm'].submit();
	
	}
	else {
		document.getElementById('selectedTrainer').value=document.getElementById('trainer_sel').value;
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
	<form name='trainerForm' id='trainerForm' action='monthly_report.php' method=post>
	<?php 
	$headingTable="
	<table align=center width=100%>
	<tr><th valign='center' style='border: 1px solid gray; color: black;background-color: #00cc66;'><h2>MONTHLY TRAINER REPORT</h2></th>
	</tr>
	</table>";	
	echo $headingTable;

	?>
	<b>Enter Trainer Name:</b>
	<input type=text name='trainer_name' id='trainer_name' onkeyup='fillSearchList(this.value)' /> <span id='trainer_list'></span><input type=hidden name='selectedTrainer' id='selectedTrainer' />
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
			
	$fromMonth=$_POST['fromMonth'];
	$fromDay=$_POST['fromDay'];
	$fromYear=$_POST['fromYear'];


	$toMonth=$_POST['toMonth'];
	$toDay=$_POST['toDay'];
	$toYear=$_POST['toYear'];
			
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
	
<?php
	if(isset($_POST['selectedTrainer'])){
		$dateMonthly=$_POST['date_filter'];
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
		
	
		if($_POST['selectedTrainer']==""){
			$_SESSION['trainerType']="all";
//			$db=new mysqli("localhost","root","","training");
			$db=retrieveTrainingDb();
		
			$sql="SELECT *,(select count(*) from class_instance where training_event_id=training_instance.id) as attendees from training_instance where start_date between '".$dateFrom."' and '".$dateTo."' order by start_date";
			$_SESSION['trainerSQL']=$sql;
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			
			if($nm>0){
	?>
				<table id='alterTable' width=100%>
					<tr>
					<th>Training Name</th>
					<th>Batch Number</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Number of Participants</th>
					<th>Trainers</th>
					
					</tr>	
	<?php	
				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();	
					
					$sqlLabel="select * from trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id where event_id='".$row['id']."'";
					$rsLabel=$db->query($sqlLabel);
					$nmLabel=$rsLabel->num_rows;		
	?>
					<tr>
						<td><?php echo $row['training_title']; ?></td>
						<td><?php echo "#".$row['batch_number']; ?></td>
						<td><?php echo date("F d, Y",strtotime($row['start_date'])); ?></td>
						<td><?php echo date("F d, Y",strtotime($row['end_date'])); ?></td>
						<td><?php echo $row['attendees']; ?></td>			
						<td>
						<?php
						for($k=0;$k<$nmLabel;$k++){
							$rowLabel=$rsLabel->fetch_assoc();
							echo strtoupper($rowLabel['lastName']).", ".$rowLabel['firstName']."<br>";
						}
						
						?>
						</td>
						

					</tr>		

	<?php
				}

	?>
				</table>
				<div align=center>
				<input type=button value='Generate Printout' name='makePrintout' onclick="window.open('generate_report2.php');" />
				</div>				
	<?php				
			}
		}
		
		else {
			$_SESSION['trainerType']="single";

//			$db=new mysqli("localhost","root","","training");
			$db=retrieveTrainingDb();
			$sql="SELECT *,(select count(*) from class_instance where training_event_id=training_instance.id) as attendees FROM trainer_class inner join training_instance on event_id=training_instance.id where trainer_class.trainer_id='".$_POST['selectedTrainer']."' and start_date between '".$dateFrom."' and '".$dateTo."'";
			$_SESSION['trainerSQL']=$sql;

			$rs=$db->query($sql);
			$nm=$rs->num_rows;

	
			$sqlLabel="select * from trainer_list where id='".$_POST['selectedTrainer']."'";
			$_SESSION['trainerSQL2']=$sqlLabel;

			$rsLabel=$db->query($sqlLabel);
			$rowLabel=$rsLabel->fetch_assoc();
	?>
	<table align=center width=100%>
	<tr><th valign='center' style='border: 1px solid gray; color: black;background-color: #00cc66;'><h2><?php echo strtoupper($rowLabel['lastName']).", ".$rowLabel['firstName']; ?></h2></th>
	</tr>
	</table>

	<table id='alterTable' width=100%>
	<tr>
	<th>Training Course</th>
	<th>Batch Number</th>
	<th>Start Date</th>
	<th>End Date</th>
	<th>Number of Attendees</th>
	</tr>
	<?php
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
		<tr>
			<td><?php echo $row['training_title']; ?></td>
			<td><?php echo "#".$row['batch_number']; ?></td>
			<td><?php echo date("F d, Y",strtotime($row['start_date'])); ?></td>
			<td><?php echo date("F d, Y",strtotime($row['end_date'])); ?></td>
			<td><?php echo $row['attendees']; ?></td>			
			
	
		</tr>
	<?php
	}
	
	
	?>
	
	
	</table>
	<div align=center>
	<input type=button value='Generate Printout' name='makePrintout' onclick="window.open('generate_report2.php');" />
	</div>
	
<?php			
		
		
		}
	
	
	}
	
?>

	</td>
</tr>
<table>	