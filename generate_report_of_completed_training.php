<?php 
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");
require("db_page.php");
?>
<?php
if(isset($_POST['event_id'])){
	$event_id=$_POST['event_id'];
	
	//Data Entry
//	$db=new mysqli("localhost","root","","training");
	$db=retrieveTrainingDb();
	$sql="select * from training_instance where id='".$event_id."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	
	$trainingTitle=$row['training_title'];
	$startDate=date("F d",strtotime($row['start_date']));
	$endDate=date("F d, Y",strtotime($row['end_date']));
	$period=$startDate." - ".$endDate;
	
	
	//$sql2="select * from training_schedule where event_id='".$event_id."' group by date order by date";
	
	$sql2="select * from training_events where id='".$event_id."'";
	$rs2=$db->query($sql2);
	$row2=$rs2->fetch_assoc();
	$requiredAttendance=$row2['no_of_days'];
	//$requiredAttendance=$rs2->num_rows;
	
	if($requiredAttendance>1){
		$duration=$requiredAttendance." days";
	}
	else {
		$duration=$requiredAttendance." day";
	}

	$sql2="select * from training_schedule where event_id='".$event_id."' group by date order by date";
	$rs2=$db->query($sql2);

	
	$row2=$rs2->fetch_assoc();
	
	$venue=$row2['location'];
	if($venue==""){
		$venue="MRT3 Training Room";
	}

	$sql="SELECT * FROM trainer_class inner join trainer_list on trainer_id=trainer_list.id where event_id='".$event_id."'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		
		$trainer[$i]['firstName']=$row['firstName'];
		$trainer[$i]['lastName']=$row['lastName'];
		$trainer[$i]['midInitial']=$row['midInitial'];
		
		if($trainer[$i]['midInitial']==""){
		}
		else {
			$trainer[$i]['midInitial']=" ".substr($trainer[$i]['midInitial'],0,1).".";
		}
		
	}

	$sql="SELECT * FROM class_instance inner join division on class_instance.department=division.division_code where training_event_id='".$event_id."'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;

	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		
		$trainee[$i]['firstName']=$row['firstName'];
		$trainee[$i]['lastName']=$row['lastName'];
		$trainee[$i]['midInitial']=$row['midInitial'];
		$trainee[$i]['designation']=$row['designation'];
		$trainee[$i]['division']=$row['shortcut'];		

		if($trainee[$i]['midInitial']==""){
		}
		else {
			$trainee[$i]['midInitial']=" ".substr($row['midInitial'],0,1).".";
		}
		
		
		$trainee[$i]['ID']=$row['traineeId'];
	}

//	$db2=new mysqli("localhost","root","","evaluation");
	$db2=retrieveEvaluationDb();
	$completeCount=0;
	$incompleteCount=0;
	
	
	for($m=0;$m<count($trainee);$m++){
		$sql2="select * from attendance where event_id='".$event_id."' and trainee_id='".$trainee[$m]['ID']."'";
		$rs2=$db2->query($sql2);
		$nm2=$rs2->num_rows;
		if($nm2<$requiredAttendance){
			$incomplete[$incompleteCount]['firstName']=$trainee[$m]['firstName'];
			$incomplete[$incompleteCount]['lastName']=$trainee[$m]['lastName'];
			$incomplete[$incompleteCount]['midInitial']=$trainee[$m]['midInitial'];
			$incomplete[$incompleteCount]['ID']=$trainee[$m]['ID'];
			$incomplete[$incompleteCount]['designation']=$trainee[$m]['designation'];
			$incomplete[$incompleteCount]['division']=$trainee[$m]['division'];
			$incompleteCount++;
		}
		else {
			$complete[$completeCount]['firstName']=$trainee[$m]['firstName'];
			$complete[$completeCount]['lastName']=$trainee[$m]['lastName'];
			$complete[$completeCount]['midInitial']=$trainee[$m]['midInitial'];
			$complete[$completeCount]['ID']=$trainee[$m]['ID'];
			$complete[$completeCount]['designation']=$trainee[$m]['designation'];			
			$complete[$completeCount]['division']=$trainee[$m]['division'];
			$completeCount++;
			
		}
	}


	//Excel Entry
	$filename="manual/report of completion.xls";
	$datePrintout=date("Ymd H-i-s");
	
	$newFilename="printout/Report of Completed Training ".$datePrintout.".xls";
	copy($filename,$newFilename);
	
	$workSheetName="Report of Completed Training";
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);
	
	$ExWs=createWorksheet($excel,$workSheetName,"create");	
	
	addContent(setRange("D5","I5"),$excel,$trainingTitle,"true",$ExWs);	
	addContent(setRange("D7","I7"),$excel,$period,"true",$ExWs);	
	addContent(setRange("D9","I9"),$excel,$duration,"true",$ExWs);	
	addContent(setRange("D11","I11"),$excel,$venue,"true",$ExWs);	
	
	$start=13;
	for($i=0;$i<count($trainer);$i++){
		$rowCount=$start*1+$i;
		$name=$trainer[$i]['firstName'].$trainer[$i]['midInitial']." ".$trainer[$i]['lastName'];
		
		addContent(setRange("D".$rowCount,"I".$rowCount),$excel,$name,"true",$ExWs);		
		$excel->getActiveSheet()->getStyle("D".$rowCount.":I".$rowCount)->getFont()->setBold(true);
		$excel->getActiveSheet()->getStyle("D".$rowCount.":I".$rowCount)->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);		
			
		
	}
	$rowCount++;
	$rowCount++;

	addContent(setRange("A".$rowCount,"A".($rowCount*1+1)),$excel,"","true",$ExWs);		
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".($rowCount*1+1))->applyFromArray($styleArray);

	addContent(setRange("B".$rowCount,"D".($rowCount*1+1)),$excel,"NAME OF PARTICIPANT","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".($rowCount*1+1))->getFont()->setBold(true);
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".($rowCount*1+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".($rowCount*1+1))->applyFromArray($styleArray);



	
	addContent(setRange("E".$rowCount,"G".($rowCount*1+1)),$excel,"DESIGNATION","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".($rowCount*1+1))->getFont()->setBold(true);
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".($rowCount*1+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".($rowCount*1+1))->applyFromArray($styleArray);



	
	addContent(setRange("H".$rowCount,"I".($rowCount*1+1)),$excel,"DIVISION","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".($rowCount*1+1))->getFont()->setBold(true);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".($rowCount*1+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".($rowCount*1+1))->applyFromArray($styleArray);
	$rowCount+=2;

	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	$rowCount++;
	

	
	for($i=0;$i<count($complete);$i++){
		$number=$i*1+1;
		$name=$complete[$i]['firstName'].$complete[$i]['midInitial']." ".$complete[$i]['lastName'];

		
		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$number,"true",$ExWs);			
		$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		addContent(setRange("B".$rowCount,"D".$rowCount),$excel,$name,"true",$ExWs);			
		
		$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		addContent(setRange("E".$rowCount,"G".$rowCount),$excel,$complete[$i]['designation'],"true",$ExWs);			
		$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		addContent(setRange("H".$rowCount,"I".$rowCount),$excel,$complete[$i]['division'],"true",$ExWs);			
		$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$rowCount++;
	
	}

	addContent(setRange("A".$rowCount,"A".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	
	addContent(setRange("B".$rowCount,"D".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	
	addContent(setRange("E".$rowCount,"G".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	addContent(setRange("H".$rowCount,"I".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	$rowCount+=2;
	addContent(setRange("A".$rowCount,"I".($rowCount*1)),$excel,"ABSENT:","true",$ExWs);		
	$rowCount++;		


	addContent(setRange("A".$rowCount,"A".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	
	addContent(setRange("B".$rowCount,"D".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	
	addContent(setRange("E".$rowCount,"G".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	addContent(setRange("H".$rowCount,"I".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	$rowCount++;		
	for($i=0;$i<count($incomplete);$i++){
		$number=$i*1+1;
		$name=$incomplete[$i]['firstName'].$incomplete[$i]['midInitial']." ".$incomplete[$i]['lastName'];
		
		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$number,"true",$ExWs);			
		$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		addContent(setRange("B".$rowCount,"D".$rowCount),$excel,$name,"true",$ExWs);			
		
		$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		addContent(setRange("E".$rowCount,"G".$rowCount),$excel,$incomplete[$i]['designation'],"true",$ExWs);			
		$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		addContent(setRange("H".$rowCount,"I".$rowCount),$excel,$incomplete[$i]['division'],"true",$ExWs);			
		$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$rowCount++;
	
	}
	addContent(setRange("A".$rowCount,"A".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	
	addContent(setRange("B".$rowCount,"D".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	
	addContent(setRange("E".$rowCount,"G".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("E".$rowCount.":G".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	addContent(setRange("H".$rowCount,"I".($rowCount*1)),$excel,"","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$rowCount+=3;
	
	addContent(setRange("A".$rowCount,"I".($rowCount*1)),$excel,"Prepared by:","true",$ExWs);		

	$rowCount+=4;

	addContent(setRange("A".$rowCount,"I".($rowCount*1)),$excel,"AIDA D. DEVERATURDA","true",$ExWs);		
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getFont()->setBold(true);
	$rowCount++;

	addContent(setRange("A".$rowCount,"I".($rowCount*1)),$excel,"Computer Operator IV","true",$ExWs);		
	
	save($ExWb,$excel,$newFilename); 		
	echo "Printout has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
	echo "<br>";
	echo "<br>";
	
}
?>
<?php
$sql2="select *,training_instance.id as program_event_id from training_instance order by start_date desc";
$rs2=$db->query($sql2);
$nm2=$rs2->num_rows;
echo "<script language='javascript'>";


echo "var trainingEvent=new Array();";
echo "var trainingEventCount=1;";

for($i=0;$i<$nm2;$i++){
	$row2=$rs2->fetch_assoc();
	echo "trainingEvent[trainingEventCount]=new Array();";
	echo "trainingEvent[trainingEventCount]['title']=\"".$row2['training_title']."\";";
	echo "trainingEvent[trainingEventCount]['batch']=\"".$row2['batch_number']."\";";
	echo "trainingEvent[trainingEventCount]['start_time']=\"".date("F d, Y",strtotime($row2['start_date']))."\";";
	echo "trainingEvent[trainingEventCount]['end_time']=\"".date("F d, Y",strtotime($row2['end_date']))."\";";
	echo "trainingEvent[trainingEventCount]['trainer']=\"".$row2['trainer']."\";";	
	echo "trainingEvent[trainingEventCount]['ID']=\"".$row2['program_event_id']."\";";	

	echo "trainingEventCount++;";
	
}
echo "</script>";	

?>
<script language="javascript">
function iterateTraining(pgindex,action){
	if(action=="prev"){

		if((pgindex==1)||(pgindex=="")){
			alert("You have reached the end of the record.");
		
		}
		else {
			document.getElementById('trainingPageNumber').value=pgindex*1-1;
			

			document.getElementById('training_title').value=trainingEvent[pgindex*1-1]['title'];
			document.getElementById('batch').value=trainingEvent[pgindex*1-1]['batch'];
		
			document.getElementById('period').value="From "+(trainingEvent[pgindex*1-1]['start_time'])+" to "+(trainingEvent[pgindex*1-1]['end_time']);
			document.getElementById('training_id').value=trainingEvent[pgindex*1-1]['ID'];
			
			var selectedTrainingID=trainingEvent[pgindex*1-1]['ID'];
		}
	}
	else if(action=="next"){
	
		if(pgindex==trainingEventCount*1-1){
			alert("You have reached the end of the record.");
		
		}
		else {

			document.getElementById('trainingPageNumber').value=pgindex*1+1;


			document.getElementById('training_title').value=trainingEvent[pgindex*1+1]['title'];
			document.getElementById('batch').value=trainingEvent[pgindex*1+1]['batch'];
			document.getElementById('period').value="From "+(trainingEvent[pgindex*1+1]['start_time'])+" to "+(trainingEvent[pgindex*1+1]['end_time']);
			document.getElementById('training_id').value=trainingEvent[pgindex*1+1]['ID'];	

			var selectedTrainingID=trainingEvent[pgindex*1+1]['ID'];
			
		}
	
	
	}
	else {
		if((trainingEventCount*1-1)==0){
			alert("You have reached the end of the record.");
			
		}
		else {
			if(pgindex>trainingEventCount*1-1){
				alert("You have reached the end of the record.");
				pgindex=trainingEventCount*1-1;
				
			}		
			
			if(pgindex<1){
				alert("You have reached the end of the record.");
				pgindex=1;
				
			}		
			
			document.getElementById('trainingPageNumber').value=pgindex*1;

			document.getElementById('training_title').value=trainingEvent[pgindex*1]['title'];
			document.getElementById('batch').value=trainingEvent[pgindex*1]['batch'];
			document.getElementById('period').value="From "+(trainingEvent[pgindex*1]['start_time'])+" to "+(trainingEvent[pgindex*1]['end_time']);
			document.getElementById('training_id').value=trainingEvent[pgindex*1]['ID'];
			
			var selectedTrainingID=trainingEvent[pgindex*1]['ID'];

			
			
			
		}
			
	
	
	}
	

	document.getElementById('event_id').value=trainingEvent[selectedTrainingID]['ID'];
	
}




</script>
<script language='javascript'>
var searchEventGrid=new Array();
var searchEventCount=1;

function scriptTraining(searchkey){
	document.getElementById('trainingPageNumber').value="";
	var searchWord=searchkey;
	
	if(searchWord==""){
		searchWord="xxxxxxxxxxxxxx";
	}
	searchEventGrid.length=0;	

	for(i=1;i<(trainingEventCount);i++){
		if(trainingEvent[i]['title'].toLowerCase()==searchWord.toLowerCase()){
			searchEventGrid[searchEventCount]=new Array();
			searchEventGrid[searchEventCount]['index']=i;
			searchEventGrid[searchEventCount]['ID']=trainingEvent[i]['ID'];
			searchEventGrid[searchEventCount]['title']=trainingEvent[i]['title'];
			searchEventGrid[searchEventCount]['batch']=trainingEvent[i]['batch'];
			searchEventGrid[searchEventCount]['period']="From "+trainingEvent[i]['start_time']+" to "+trainingEvent[i]['end_time'];
			searchEventCount++;
		}
		else if((trainingEvent[i]['title'].toLowerCase()).indexOf(searchWord.toLowerCase())>-1){
			searchEventGrid[searchEventCount]=new Array();
			searchEventGrid[searchEventCount]['index']=i;
			searchEventGrid[searchEventCount]['ID']=trainingEvent[i]['ID'];
			searchEventGrid[searchEventCount]['title']=trainingEvent[i]['title'];
			searchEventGrid[searchEventCount]['batch']=trainingEvent[i]['batch'];
			searchEventGrid[searchEventCount]['period']="From "+trainingEvent[i]['start_time']+" to "+trainingEvent[i]['end_time'];
			searchEventCount++;


		}
		else {
		}
	}	

	if(searchEventCount>1){
		var optionsGrid="";
		optionsGrid+="<td>Look for Training Program Here:<br>";
		optionsGrid+="<select multiple=true id='dynamicSel' name='dynamicSel'>";
		for(i=1;i<searchEventCount;i++){
			optionsGrid+="<option value='"+searchEventGrid[i]['index']+"' >";
			optionsGrid+=searchEventGrid[i]['title']+" #"+searchEventGrid[i]['batch']+", "+searchEventGrid[i]['period'];
			optionsGrid+="</option>";
			
		}
			

			

		optionsGrid+="</select>";
		optionsGrid+="<br><input type=button value='Get Training Program' onclick=retrieveEvent(document.getElementById('dynamicSel').value) />";
		optionsGrid+="</td>";
			
		document.getElementById('searchResults').innerHTML=optionsGrid;
	}
	else {
		document.getElementById('searchResults').innerHTML="";
	}
	
	searchEventCount=1;
}
function retrieveEvent(item){
	iterateTraining(item*1,"index");
	document.getElementById('searchResults').innerHTML="";

	
	}

</script>
	<table id='indexhere' align=center style='border: 1px solid gray' width=600>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Submit Completed Training Report</h1>
	</th>
	</table>
	<table id='dynamicProgram' width=500 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Select Training Event</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			Training Title
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 onkeyup='scriptTraining(this.value);'  />
			<input type=button value='?' onclick='scriptTraining(document.getElementById("training_title").value);' />
			<input type=hidden id='training_id' name='training_id'  />
			</td>
		</tr>	
		<tr>
			<td style="background-color:white; color:black;">
			Batch Number
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='batch' name='batch' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 />
			</td>
		</tr>			
		<tr>
			<td style="background-color:white; color:black;">
			Period
			</td>
			<td style="background-color:white; color:black;">

			 
			<textarea id='period' name='period' value="<?php echo $ndgrid[1]['training_title']; ?>" cols=30></textarea>
			</td>
		</tr>	
		<tr>
			<td colspan=2 style='background-color: #ed5214;' align=center>
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'prev');"><<</a>
			<input id='trainingPageNumber' style="text-align:center" type="text" name="trainingPageNumber" size=4 value='' onkeyup="iterateTraining(document.getElementById('trainingPageNumber').value,'index');" />	
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'next');">>></a>
			</td>
	
		</tr>		
	</table>
		<table  width=500 align=center style='border: 1px solid gray'>
		<tr><td id='searchResults' style="background-color: #ed5214;color: white;"	colspan=2></td></tr>
	</table>
	<form action='memo_generate.php?generate=complete_report' method='post' >	
	<table align=center>	
	<tr>
	<td align=center colspan=2>
	<input type=hidden name='event_id' id='event_id' />
	<input type=submit value='Generate Printout' />
	</td>
	</tr>
	</table>
	</form>

