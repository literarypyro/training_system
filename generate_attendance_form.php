<?php 
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");
//require("db_page.php");
?>
<?php
	if($_SESSION['training_page']=="record_attendance.php"){
		//$db=new mysqli("localhost","root","","training");
		$db=retrieveTrainingDb();
		$sql="select * from training_instance where id='".$_POST['eventID']."'";
		$rs=$db->query($sql);

		$row=$rs->fetch_assoc();
		$start_date=date("d F",strtotime($row['start_date']));
		$end_date=date("d F, Y",strtotime($row['end_date']));
		$trainingTitle=$row['training_title'];
		$period=$start_date." - ".$end_date;

		$db=retrieveTrainingDb();
	//	$db=new mysqli("localhost","root","","training");
		$sql="select * from training_schedule where event_id='".$_POST['eventID']."' group by date";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		
		$period.=", (".$nm." days)";
		$filename="manual/attendance_form.xls";
		
		echo "Attendance Forms for the following dates have been printed:<br>";
		for($i=0;$i<$nm;$i++){
			$datePrintout=date("Ymd H-i-s");
			$row=$rs->fetch_assoc();
			$dateAttendance=date("Y-m-d",strtotime($row['date']));
			$dateLabel=date("F d, Y",strtotime($dateAttendance));
			$newFilename="printout/Attendance Form for ".$dateAttendance." - ".$datePrintout.".xls";
			$venue=$row['location'];
			copy($filename,$newFilename);
			if($venue==""){
				$venue="MRT3 Training Room";
			}
			
			$workSheetName="Attendance Sheet";
			$workbookname=$newFilename;
			$excel=loadExistingWorkbook($workbookname);

			$ExWs=createWorksheet($excel,$workSheetName,"create");				



			addContent(setRange("D6","I6"),$excel,$trainingTitle,"true",$ExWs);	
			addContent(setRange("D7","I7"),$excel,$period,"true",$ExWs);	
			addContent(setRange("D8","I8"),$excel,$venue,"true",$ExWs);	
			addContent(setRange("D9","I9"),$excel,$dateLabel,"true",$ExWs);	
			
			
		
			save($ExWb,$excel,$newFilename); 		
			echo "<a href='".$newFilename."' style='text-decoration:none;color:red;'>".$dateLabel."</a>";
			echo "<br>";	
		
		}
		
	
	}

?>
