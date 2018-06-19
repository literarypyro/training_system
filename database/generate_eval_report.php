<?php 
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");

?>
<?php
function grade($ranking){
	if($ranking==1){ $score="1 - Poor"; }
	else if($ranking==2){ $score="2 - Fair"; }
	else if($ranking==3){ $score="3 - Good"; }
	else if($ranking==4){ $score="4 - Very Good"; }
	else if($ranking==5){ $score="5 - Excellent"; }

return $score;
}


?>
<?php


/**

	$event_id=$_GET['view'];	
	
	
	
	

	
	$trainingTitle=$row['training_title'];
	$batchNumber=$row['batch_number'];
	$startDate=date("d F",strtotime($row['start_date']));
	$endDate=date("d F Y",strtotime($row['end_date']));
	$time=$startDate." - ".$endDate;
	
	
	$count=$nm;
	
	$filename="manual/grading_form.xls";

	$datePrintout=date("Ymd H-i-s");
	
	$newFilename="printout/Grading Report ".$datePrintout.".xls";
	copy($filename,$newFilename);
	*/
	$event_id=$_POST['view'];	
//	$db=new mysqli("localhost","root","","training");
	$db=retrieveTrainingDb();
	
	$sql="select * from training_instance where id='".$event_id."'";
	
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$row=$rs->fetch_assoc();
	$trainingTitle=$row['training_title'];
	$batchNumber=$row['batch_number'];
	$startDate=date("d F",strtotime($row['start_date']));
	$endDate=date("d F Y",strtotime($row['end_date']));
	$time=$startDate." - ".$endDate;	
	
	
	

	$datePrintout=date("Ymd H-i-s");
	
	$newFilename="printout/Trainer Evaluation Report ".$datePrintout.".xls";
	
	$workSheetName="Trainer Evaluation Report";	
	$workbookname=$newFilename;
	$excel=startCOMGiven();
	
	

	
		
		$ExWs=createWorksheet($excel,$workSheetName,"create");	
	
	
		$excel->getActiveSheet()->getPageSetup()->setFitToPage(true);
	
	
	addImage(setRange("A1","B7"),$excel,"c:/report/records picture2.jpg","true",$ExWs);
	addImage(setRange("H1","I7"),$excel,"c:/report/support_staff logo.png","true",$ExWs);	
	
			//	addImage(setRange("A1","B7"),$excel,"c:/report/records picture2.jpg","true",$ExWs);
	addContent(setRange("c2","g2"),$excel,"Republic of the Philippines","true",$ExWs);
	addContent(setRange("c3","g3"),$excel,"DEPARTMENT OF TRANSPORTATION","true",$ExWs);
	addContent(setRange("c4","g4"),$excel,"AND COMMUNICATIONS","true",$ExWs);
	addContent(setRange("c5","g5"),$excel,"METRO RAIL TRANSIT III EDSA LINE","true",$ExWs);
	addContent(setRange("c6","g6"),$excel,"(DOTC-MRT3)","true",$ExWs);	

	
	$excel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
	$excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
	$excel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
	$excel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
	
	
	$excel->getActiveSheet()->getStyle("C2:G2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	$excel->getActiveSheet()->getStyle("C3:G3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	$excel->getActiveSheet()->getStyle("C4:G4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	$excel->getActiveSheet()->getStyle("C5:G5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	$excel->getActiveSheet()->getStyle("C6:G6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	

	$excel->getActiveSheet()->getStyle("C2:G2")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("C3:G3")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("C4:G4")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("C5:G5")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("C6:G6")->getFont()->setBold(true);	
	
	//$excel->getActiveSheet()->getStyle("A2:I3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	//$excel->getActiveSheet()->getStyle("A2:I3")->getFont()->setBold(true);	
	
	addContent(setRange("c12","e12"),$excel,$trainingTitle,"true",$ExWs);
	addContent(setRange("h12","i12"),$excel,$time,"true",$ExWs);	
	
	addContent(setRange("a9","i9"),$excel,"SUPPORT STAFF","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("A9:I9")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("A9:I9")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	$excel->getActiveSheet()->getStyle("A9:I9")->getFont()->setSize(14);
	
	addContent(setRange("a10","i10"),$excel,"TRAINER EVALUATION REPORT","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("A10:I10")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("A10:I10")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	$excel->getActiveSheet()->getStyle("A10:I10")->getFont()->setSize(14);	
	
	addContent(setRange("a12","b12"),$excel,"Title of Training","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("A12:B12")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("A12:B12")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	$excel->getActiveSheet()->getStyle("A12:B12")->getFont()->setSize(12);	

	addContent(setRange("f12","g12"),$excel,"Training Date","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("F12:G12")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("F12:G12")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	$excel->getActiveSheet()->getStyle("F12:G12")->getFont()->setSize(12);	

	addContent(setRange("a14","c15"),$excel,"Trainer Name","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("A14:C15")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("A14:C15")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	


	
	addContent(setRange("d14","g14"),$excel,"Average Score","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("D14:G14")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("D14:G14")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	

	addContent(setRange("d15","d15"),$excel,"Content","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("D15:D15")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("D15:D15")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	
	addContent(setRange("e15","e15"),$excel,"Delivery","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("E15:E15")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("E15:E15")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	
	addContent(setRange("f15","f15"),$excel,"Language","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("F15:F15")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("F15:F15")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	
	addContent(setRange("g15","g15"),$excel,"Program","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("G15:G15")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("G15:G15")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	
	addContent(setRange("H14","I15"),$excel,"Total Score","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("H14:I15")->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle("H14:I15")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	


	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
			),
		),
	);
	$excel->getActiveSheet()->getStyle("A14:C15")->applyFromArray($styleArray);
	$excel->getActiveSheet()->getStyle("D14:G14")->applyFromArray($styleArray);
	$excel->getActiveSheet()->getStyle("D15:D15")->applyFromArray($styleArray);
	$excel->getActiveSheet()->getStyle("E15:E15")->applyFromArray($styleArray);
	$excel->getActiveSheet()->getStyle("F15:F15")->applyFromArray($styleArray);
	$excel->getActiveSheet()->getStyle("G15:G15")->applyFromArray($styleArray);
	$excel->getActiveSheet()->getStyle("H14:I15")->applyFromArray($styleArray);	
	
	$trainerClause="";
	
	if($_POST['trainer']=="ALL"){
		$trainerClause="";
	}
	else {
		$trainerClause="and trainer_id='".$_POST['trainer']."'";
	
	}
	

//	$db=new mysqli("localhost","root","","training");
	$db=retrieveTrainingDb();
	$db2=retrieveEvaluationDb();
//	$db2=new mysqli("localhost","root","","evaluation");
	$sql="SELECT * FROM trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id where event_id='".$event_id."' ".$trainerClause;
	$rs=$db->query($sql);
	
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);

	$constant=16;
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$rowCount=$constant+$i;
		
		$trainerName=strtoupper($row['lastName']).", ".$row['firstName'];		
		addContent(setRange("a".$rowCount,"c".$rowCount),$excel,$trainerName,"true",$ExWs);	
		$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->applyFromArray($styleArray);	
		$trainer_id=$row['trainer_id'];
		/*
		$sql2="select * from training_evaluation where event_id='".$event_id."' and trainer_id='".$trainer_id."'";
		$rs2=$db2->query($sql2);
		$row2=$rs2->fetch_assoc();
		$evaluation_id=$row['id'];
		*/
		$sqlA="SELECT avg(ranking) as content FROM evaluation_instance where event_id='".$event_id."' and trainer_id='".$trainer_id."' and part='1'";
		$sqlB="SELECT avg(ranking) as delivery from evaluation_instance where event_id='".$event_id."' and trainer_id='".$trainer_id."' and part='2'";
		$sqlC="SELECT avg(ranking) as language from evaluation_instance where event_id='".$event_id."' and trainer_id='".$trainer_id."' and part='3'";
		$sqlD="SELECT avg(ranking) as program from evaluation_instance where event_id='".$event_id."' and trainer_id='".$trainer_id."' and part='4'";

		$sqlTotal="SELECT avg(ranking) as total from evaluation_instance where event_id='".$event_id."' and trainer_id='".$trainer_id."'";



		$rsA=$db2->query($sqlA);
		$rsB=$db2->query($sqlB);
		$rsC=$db2->query($sqlC);
		$rsD=$db2->query($sqlD);
		$rsTotal=$db2->query($sqlTotal);

		
		
		$rowA=$rsA->fetch_assoc();
		$rowB=$rsB->fetch_assoc();
		$rowC=$rsC->fetch_assoc();
		$rowD=$rsD->fetch_assoc();
		$rowTotal=$rsTotal->fetch_assoc();

		
		$content=number_format($rowA['content'],0);
		$delivery=number_format($rowB['delivery'],0);
		$language=number_format($rowC['language'],0);
		$program=number_format($rowD['program'],0);
		$total=number_format($rowTotal['total'],0);

		addContent(setRange("d".$rowCount,"D".$rowCount),$excel,grade($content),"true",$ExWs);	
		$excel->getActiveSheet()->getStyle("D".$rowCount.":D".$rowCount)->applyFromArray($styleArray);	
		addContent(setRange("e".$rowCount,"e".$rowCount),$excel,grade($delivery),"true",$ExWs);	
		$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->applyFromArray($styleArray);	
		addContent(setRange("f".$rowCount,"f".$rowCount),$excel,grade($language),"true",$ExWs);	
		$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->applyFromArray($styleArray);	
		addContent(setRange("g".$rowCount,"g".$rowCount),$excel,grade($program),"true",$ExWs);	
		$excel->getActiveSheet()->getStyle("G".$rowCount.":G".$rowCount)->applyFromArray($styleArray);	
		addContent(setRange("h".$rowCount,"i".$rowCount),$excel,grade($total),"true",$ExWs);	
		$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->applyFromArray($styleArray);	
	
	}
	
	/**
	
	
	
	
	//SELECT * FROM evaluation_category inner join category on category_parts=category.id
	
	
	$workSheetName="Evaluation List";
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);
		
	
	
 	$ExWs=createWorksheet($excel,$workSheetName,"create");		

	
	addContent(setRange("A3","AP3"),$excel,strtoupper("Grades of ".$trainingTitle." Batch ".$batchNumber),"true",$ExWs);
	addContent(setRange("A4","AP4"),$excel,strtoupper($time),"true",$ExWs);

	
	
	$sqlTrainee="select * from class_instance where training_event_id='".$event_id."'";
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;
	
	$rowCount=7;
	$rowCount++;
	
	$start=$rowCount;
	$end=$rowCount*1+($nmTrainee*1-1);
	
	
	for($i=0;$i<$nmTrainee;$i++){

//	for($i=0;$i<3;$i++){
	
		$rowTrainee=$rsTrainee->fetch_assoc();
		$name=$rowTrainee['lastName'].", ".$rowTrainee['firstName'];
		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$name,"true",$ExWs);

		$excel->getActiveSheet()->getStyle('A'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('A'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('A'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$excel->getActiveSheet()->getStyle('A'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

		$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setSize(13);		
		$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setName('Arial');


		$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);
		

		addContent(setRange("B".$rowCount,"B".$rowCount),$excel,"=IF(D".$rowCount.">=2.99,\"Passed\",\"Failed\")","true",$ExWs);
		$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		$excel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);		

		$excel->getActiveSheet()->getStyle('B'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('B'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('B'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('B'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);		
		





		addContent(setRange("C".$rowCount,"C".$rowCount),$excel,"=RANK(D".$rowCount.",D".$start.":D".$end.",0)","true",$ExWs);
		$excel->getActiveSheet()->getStyle("C".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("C".$rowCount)->getFont()->setName('Arial');

		$excel->getActiveSheet()->getStyle('C'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('C'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('C'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('C'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	

		$excel->getActiveSheet()->getStyle("C".$rowCount.":C".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			

		addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"=(((IF(E".$rowCount."=0,AI".$rowCount.",E".$rowCount.")*.5)+(IF(AI".$rowCount."=0,E".$rowCount.",AI".$rowCount.")*.5))*.7)+(AS".$rowCount."*.3)","true",$ExWs);
		$excel->getActiveSheet()->getStyle("D".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("D".$rowCount)->getFont()->setName('Arial');
		
			
//		addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"=RANK(D".$rowCount.",D".$start.":D".$end.",0)","true",$ExWs);

		$excel->getActiveSheet()->getStyle('D'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('D'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('D'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('D'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);		
	//	=(E8*0.7)+(AS8*0.3)
		$excel->getActiveSheet()->getStyle("D".$rowCount.":D".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



		addContent(setRange("E".$rowCount,"E".$rowCount),$excel,"=AVERAGE(G".$rowCount.",K".$rowCount.",N".$rowCount.",Z".$rowCount.")","true",$ExWs);
		$excel->getActiveSheet()->getStyle("E".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("E".$rowCount)->getFont()->setName('Arial');
		
		$excel->getActiveSheet()->getStyle('E'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('E'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('E'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$excel->getActiveSheet()->getStyle('E'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);			
		$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		

		addContent(setRange("AI".$rowCount,"AI".$rowCount),$excel,"=AVERAGE(AK".$rowCount.",AN".$rowCount.")","true",$ExWs);
		$excel->getActiveSheet()->getStyle("AI".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("AI".$rowCount)->getFont()->setName('Arial');


		
		$excel->getActiveSheet()->getStyle('AP'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('AP'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('AP'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('AP'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);			

		
		$excel->getActiveSheet()->getStyle('AR'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('AR'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('AR'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$excel->getActiveSheet()->getStyle('AR'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);			

		
		
		
		
		addContent(setRange("F".$rowCount,"F".$rowCount),$excel,"=RANK(E".$rowCount.",E".$start.":E".$end.",0)","true",$ExWs);
		$excel->getActiveSheet()->getStyle("F".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("F".$rowCount)->getFont()->setName('Arial');
		
		$excel->getActiveSheet()->getStyle('F'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('F'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('F'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$excel->getActiveSheet()->getStyle('F'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);			
		$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		
//		if($i>0){	

			$excel->getActiveSheet()->getStyle('G'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$excel->getActiveSheet()->getStyle('G'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$excel->getActiveSheet()->getStyle('G'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$excel->getActiveSheet()->getStyle('G'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);			
			$excel->getActiveSheet()->getStyle("G".$rowCount.":G".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle("K".$rowCount.":K".$rowCount)->applyFromArray($styleArray);			
		$excel->getActiveSheet()->getStyle("M".$rowCount.":M".$rowCount)->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("N".$rowCount.":N".$rowCount)->applyFromArray($styleArray);			
		$excel->getActiveSheet()->getStyle("P".$rowCount.":P".$rowCount)->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("Z".$rowCount.":Z".$rowCount)->applyFromArray($styleArray);			
		$excel->getActiveSheet()->getStyle("AB".$rowCount.":AB".$rowCount)->applyFromArray($styleArray);			
		$excel->getActiveSheet()->getStyle("AC".$rowCount.":AC".$rowCount)->applyFromArray($styleArray);			
		$excel->getActiveSheet()->getStyle("AE".$rowCount.":AE".$rowCount)->applyFromArray($styleArray);			
		$excel->getActiveSheet()->getStyle("AF".$rowCount.":AF".$rowCount)->applyFromArray($styleArray);			
		$excel->getActiveSheet()->getStyle("AH".$rowCount.":AH".$rowCount)->applyFromArray($styleArray);			
		$excel->getActiveSheet()->getStyle("AK".$rowCount.":AK".$rowCount)->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("AM".$rowCount.":AM".$rowCount)->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("AN".$rowCount.":AN".$rowCount)->applyFromArray($styleArray);			
		$excel->getActiveSheet()->getStyle("AJ".$rowCount.":AJ".$rowCount)->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("AS".$rowCount.":AS".$rowCount)->applyFromArray($styleArray);			

		
		
		$excel->getActiveSheet()->getStyle('AI'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('AI'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('AI'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$excel->getActiveSheet()->getStyle('AI'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);			
		$excel->getActiveSheet()->getStyle("AI".$rowCount.":AI".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			
		
		
//		}

		
		
		addContent(setRange("J".$rowCount,"J".$rowCount),$excel,"=RANK(G".$rowCount.",G".$start.":G".$end.",0)","true",$ExWs);
		$excel->getActiveSheet()->getStyle("J".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("J".$rowCount)->getFont()->setName('Arial');
		
		$excel->getActiveSheet()->getStyle('J'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('J'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('J'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('J'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);			
		$excel->getActiveSheet()->getStyle("J".$rowCount.":J".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		addContent(setRange("M".$rowCount,"M".$rowCount),$excel,"=RANK(K".$rowCount.",K".$start.":K".$end.",0)","true",$ExWs);
		$excel->getActiveSheet()->getStyle("M".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("M".$rowCount)->getFont()->setName('Arial');


		addContent(setRange("P".$rowCount,"P".$rowCount),$excel,"=RANK(N".$rowCount.",N".$start.":N".$end.",0)","true",$ExWs);
		$excel->getActiveSheet()->getStyle("P".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("P".$rowCount)->getFont()->setName('Arial');

		addContent(setRange("AB".$rowCount,"AB".$rowCount),$excel,"=RANK(Z".$rowCount.",Z".$start.":Z".$end.",0)","true",$ExWs);
		$excel->getActiveSheet()->getStyle("AB".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("AB".$rowCount)->getFont()->setName('Arial');

		addContent(setRange("AJ".$rowCount,"AJ".$rowCount),$excel,"=RANK(AI".$rowCount.",AI".$start.":AI".$end.",0)","true",$ExWs);
		$excel->getActiveSheet()->getStyle("AJ".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("AJ".$rowCount)->getFont()->setName('Arial');
		
		addContent(setRange("AM".$rowCount,"AM".$rowCount),$excel,"=RANK(AK".$rowCount.",AK".$start.":AK".$end.",0)","true",$ExWs);
		$excel->getActiveSheet()->getStyle("AM".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("AM".$rowCount)->getFont()->setName('Arial');
		
		addContent(setRange("AP".$rowCount,"AP".$rowCount),$excel,"=RANK(AN".$rowCount.",AN".$start.":AN".$end.",0)","true",$ExWs);
		$excel->getActiveSheet()->getStyle("AP".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("AP".$rowCount)->getFont()->setName('Arial');


		
		$excel->getActiveSheet()->getStyle("M".$rowCount.":M".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle("AJ".$rowCount.":AJ".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		


		addContent(setRange("AT".$rowCount,"AT".$rowCount),$excel,"=RANK(AS".$rowCount.",AS".$start.":AS".$end.",0)","true",$ExWs);
		$excel->getActiveSheet()->getStyle("AT".$rowCount)->getFont()->setSize(10);		
		$excel->getActiveSheet()->getStyle("AT".$rowCount)->getFont()->setName('Arial');
		
		$excel->getActiveSheet()->getStyle('AT'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('AT'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('AT'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle('AT'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);			
		$excel->getActiveSheet()->getStyle("AT".$rowCount.":AT".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		
		$rowCount++;
		
	}
	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);
	//	$objPHPExcel->getActiveSheet()->getRowDimension('10')->setRowHeight(100);
	$rowCount++;	

	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);

	$rowCount++;

	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);
	$rowCount++;

		$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);

	addContent(setRange("A".$rowCount,"A".$rowCount),$excel,"Prepared by:","true",$ExWs);
		$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setSize(14);		
		$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setName('Arial');	
		
		
		addContent(setRange("G".$rowCount,"K".$rowCount),$excel,"Reviewed by:","true",$ExWs);
		$excel->getActiveSheet()->getStyle("G".$rowCount)->getFont()->setSize(14);		
		$excel->getActiveSheet()->getStyle("G".$rowCount)->getFont()->setName('Arial');		
		

		addContent(setRange("AI".$rowCount,"AJ".$rowCount),$excel,"Noted by:","true",$ExWs);
		$excel->getActiveSheet()->getStyle("AI".$rowCount)->getFont()->setSize(14);		
		$excel->getActiveSheet()->getStyle("AI".$rowCount)->getFont()->setName('Arial');	

		
	
	$rowCount++;
	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(12.75);

	$rowCount++;
	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(12.75);

	$rowCount++;
	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(12.75);

	$rowCount++;
	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(12.75);

	$rowCount++;
	
	addContent(setRange("A".$rowCount,"A".$rowCount),$excel,"CLARO V. MARTIN JR.","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setName('Arial');		
	$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setBold(true);		
	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);	


	addContent(setRange("G".$rowCount,"M".$rowCount),$excel,"LUIS A. SAMAN JR.","true",$ExWs);
	$excel->getActiveSheet()->getStyle("G".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("G".$rowCount)->getFont()->setName('Arial');		
	$excel->getActiveSheet()->getStyle("G".$rowCount)->getFont()->setBold(true);		

	addContent(setRange("AI".$rowCount,"AM".$rowCount),$excel,"OFELIA D. ASTRERA","true",$ExWs);
	$excel->getActiveSheet()->getStyle("AI".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("AI".$rowCount)->getFont()->setName('Arial');		
	$excel->getActiveSheet()->getStyle("AI".$rowCount)->getFont()->setBold(true);	

	

	$rowCount++;
	addContent(setRange("A".$rowCount,"A".$rowCount),$excel,"Senior TDO","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setName('Arial');		
	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);	
	
	addContent(setRange("G".$rowCount,"M".$rowCount),$excel,"Supervising TDO","true",$ExWs);
	$excel->getActiveSheet()->getStyle("G".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("G".$rowCount)->getFont()->setName('Arial');		
	
	addContent(setRange("AI".$rowCount,"AR".$rowCount),$excel,"Chief Transportation Development Officer","true",$ExWs);
	$excel->getActiveSheet()->getStyle("AI".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("AI".$rowCount)->getFont()->setName('Arial');		
	
	
	
	$rowCount++;
	addContent(setRange("A".$rowCount,"D".$rowCount),$excel,"Support Staff/AFC Center/Computer Section","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setName('Arial');		
	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);	

	addContent(setRange("G".$rowCount,"AC".$rowCount),$excel,"Support Staff/AFC Center/Computer Section","true",$ExWs);
	$excel->getActiveSheet()->getStyle("G".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("G".$rowCount)->getFont()->setName('Arial');		

	addContent(setRange("AI".$rowCount,"AR".$rowCount),$excel,"Support Staff/AFC Center/Computer Section","true",$ExWs);
	$excel->getActiveSheet()->getStyle("AI".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("AI".$rowCount)->getFont()->setName('Arial');		
	*/
	save($ExWb,$excel,$newFilename); 
	
	echo "Report has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
	
	/**

	CLARO V. MARTIN JR.			
Senior TDO			
Support Staff/AFC Center/Computer Section			

	

//=IF(D8>=2.99,"Passed","Failed")

	

	




	
//	$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setTextRotation(73);


	

	
	

		
		
	for ($i=65; $i<=90; $i++)
echo chr($i)."<br>"; 
	
	*/


?>
