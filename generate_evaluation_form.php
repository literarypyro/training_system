<?php 
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");

?>
<?php
if($_SESSION['training_page']=="evaluation_form.php"){

	$event_id=$_POST['event_id'];
	$trainer=$_POST['trainer'];	

	if(($event_id=="")||($trainer=="")){
	}
	else {

	$datePrintout=date("Ymd H-i-s");

	$filename="manual/evaluation_form.xls";
	$datePrintout=date("Ymd H-i-s");
	
	$newFilename="printout/Evaluation Form ".$datePrintout.".xls";
	copy($filename,$newFilename);
	
	$workSheetName="Evaluation Form";
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

	$ExWs=createWorksheet($excel,$workSheetName,"create");	
	
	//$db=new mysqli("localhost","root","","training");
	$db=retrieveTrainingDb();
	$sql="select * from training_instance where id='".$_POST['event_id']."'";
	$rs=$db->query($sql);

	$row=$rs->fetch_assoc();
	$start_date=date("d F",strtotime($row['start_date']));
	$end_date=date("d F, Y",strtotime($row['end_date']));
	$trainingTitle=$row['training_title'];
	$period=$start_date." - ".$end_date;
	
	
	$sql2="select * from trainer_list where id='".$trainer."'";
	$rs2=$db->query($sql2);
	$row2=$rs2->fetch_assoc();
	$firstName=$row2['firstName'];
	$lastName=$row2['lastName'];
	$midInitial=" ".$row2['midInitial'];
	$trainerName=$firstName.$midInitial." ".$lastName;
	
	addContent(setRange("C4","E4"),$excel,$trainingTitle,"true",$ExWs);	
	
	addContent(setRange("H4","J4"),$excel,$period,"true",$ExWs);	
	
	addContent(setRange("B10","E10"),$excel,$trainerName,"true",$ExWs);	
	
	
					
	save($ExWb,$excel,$newFilename); 		
	echo "Printout has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
	echo "<br>";
	echo "<br>";
	}
}
?>
