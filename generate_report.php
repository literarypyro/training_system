<?php 
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("db_page.php");
require("excel functions.php");
?>
<?php


$dateFilter=$_SESSION['date_filter'];
$fromMonth=$_SESSION['fromMonth'];
$fromDay=$_SESSION['fromDay'];
$fromYear=$_SESSION['fromYear'];

$toMonth=$_SESSION['toMonth'];
$toDay=$_SESSION['toDay'];
$toYear=$_SESSION['toYear'];

$batch_no=$_SESSION['batch_no'];

if(isset($_GET['cType'])){
	$_SESSION['courseType']=$_GET['cType'];
}


$dateMonthly=$dateFilter;

$_SESSION['training_page']="trainer_report.php";

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

//	$dateFrom='2011-05-27 00:00:00';
//	$dateTo='2011-06-30 23:23:59';




	//$db=new mysqli("localhost","root","","training");
	$db=retrieveTrainingDb();

	if(isset($_GET['trainingEventView'])){

		$datePrintout=date("Ymd H-i-s");
	
		$newFilename="printout/Training Event Report ".$datePrintout.".xls";

		$workbookname=$newFilename;
		$excel=startCOMGiven();

	}
	else {
	
	$sql="select *,(select count(*) from class_instance where training_event_id=training_instance.id) as attendees from training_instance where start_date between '".$dateFrom."' and '".$dateTo."' group by program_id order by batch_number*1";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	$count=$nm;
	
	$filename="manual/monthly report guide.xls";
	$datePrintout=date("Ymd H-i-s");
	
	$newFilename="printout/Monthly Report ".$datePrintout.".xls";
	copy($filename,$newFilename);
	
	$workSheetName="Participants Chart";
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);
	

	
 	$ExWs=createWorksheet($excel,$workSheetName,"create");		
	$excel->getActiveSheet()->getRowDimension('12')->setRowHeight(120);
	$excel->getActiveSheet()->getRowDimension('13')->setRowHeight(18);

	
	$excel->getActiveSheet()->getColumnDimension('A')->setWidth(26.57);

	
	//////addImage(setRange("A1","B7"),$excel,"c:/report/records picture2.jpg","true",$ExWs);
	addContent(setRange("b2","F2"),$excel,"Republic of the Philippines","true",$ExWs);
	$excel->getActiveSheet()->getStyle("B2:F2")->getFont()->setBold(true);
	addContent(setRange("b3","F3"),$excel,"DEPARTMENT OF TRANSPORTATION","true",$ExWs);
	$excel->getActiveSheet()->getStyle("B3:F3")->getFont()->setBold(true);
	addContent(setRange("b4","F4"),$excel,"AND COMMUNICATIONS","true",$ExWs);
	$excel->getActiveSheet()->getStyle("B4:F4")->getFont()->setBold(true);
	addContent(setRange("b5","F5"),$excel,"METRO RAIL TRANSIT III EDSA LINE","true",$ExWs);
	addContent(setRange("b6","F6"),$excel,"(DOTC-MRT3)","true",$ExWs);



	
	$excel->getActiveSheet()->getStyle("B2:F2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B3:F3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B4:F4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B5:F5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B6:F6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	
	$rowCount=10;
	
	addContent(setRange("a".$rowCount,"h".$rowCount),$excel,"Monthly Training Report","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount,"H".$rowCount)->getFont()->setSize(14);	
	$excel->getActiveSheet()->getStyle("A".$rowCount,"H".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$rowCount++;
	
	addContent(setRange("a".$rowCount,"h".$rowCount),$excel,"From ".date("F d, Y",strtotime($dateFrom))." to ".date("F d, Y",strtotime($dateTo)),"true",$ExWs);
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$prefix=chr(66+($i*1));
		$cell=strtoupper($prefix."12");
//		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$event["Program".$row['program_id']]["cell"]=$prefix;
		addContent(setRange($cell,$cell),$excel,$row['training_title'],"false",$ExWs);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getFont()->setBold(true);		
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setWrapText(true);		
		
	}
	$excel->getActiveSheet()->getStyle("A13:H13")->getFont()->setBold(true);
	$excel->getActiveSheet()->getStyle("A13:H13")->getFont()->setSize(14);
	$excel->getActiveSheet()->getStyle("A13:H13")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	addContent(setRange("a13","h13"),$excel,strtoupper("Participants"),"true",$ExWs);
	
	$sql="SELECT * FROM class_instance inner join training_instance on class_instance.training_event_id=training_instance.id where start_date between '".$dateFrom."' and '".$dateTo."' group by traineeId";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$rowCount=$rowCount*1+3;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$cell=strtoupper("A".$rowCount);

		addContent(setRange($cell,$cell),$excel,strtoupper($row['lastName']).", ".$row['firstName'],"false",$ExWs);
	

		$sql2="SELECT * FROM class_instance inner join training_instance on class_instance.training_event_id=training_instance.id where start_date between '".$dateFrom."' and '".$dateTo."' and traineeId='".$row['traineeId']."'";

		$rs2=$db->query($sql2);

		$nm2=$rs2->num_rows;
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$markCell=$event["Program".$row2['program_id']]["cell"];
			if($event["Program".$row2['program_id']]["Student".$row2['traineeId']]['count']>1){
				$event["Program".$row2['program_id']]["Student".$row2['traineeId']]['content'].=", #".$row2['batch_number'].", ".date("m/d/Y",strtotime($row2['start_date']));
			}
			else {
				$event["Program".$row2['program_id']]["Student".$row2['traineeId']]['content']="#".$row2['batch_number'].", ".date("m/d/Y",strtotime($row2['start_date']));
			
			
			}
			$event["Program".$row2['program_id']]["Student".$row2['traineeId']]['count']++;
			
			$markCell.=$rowCount;

			$excel->getActiveSheet()->getStyle($markCell.":".$markCell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($markCell.":".$markCell)->getAlignment()->setWrapText(true);
			addContent(setRange($markCell,$markCell),$excel,$event["Program".$row2['program_id']]["Student".$row2['traineeId']]['content'],"false",$ExWs);
		}
		
		$rowCount++;
	
	}
	
	}
	$workSheetName="Training Events Report";	
	if(isset($_GET['trainingEventView'])){
 	$ExWs=createWorksheet($excel,$workSheetName,"create");	

	}
	else {
	
	$ExWs=setActiveWorksheet($excel,$workSheetName,"1");	
	}

	$rowCount=1;
	$excel->getActiveSheet()->getRowDimension('10')->setRowHeight(18);	
	$excel->getActiveSheet()->getRowDimension('12')->setRowHeight(18);
	$excel->getActiveSheet()->getRowDimension('13')->setRowHeight(18);

	if($_SESSION['courseType']=="all"){	
	$excel->getActiveSheet()->getColumnDimension('A')->setWidth(26.57);

	$limit="E";
	}
	else {
	$excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);
	$excel->getActiveSheet()->getColumnDimension('c')->setWidth(26.57);
	$limit="N";
	
	}
	////addImage(setRange("A1","B7"),$excel,"c:/report/records picture2.jpg","true",$ExWs);
//	addContent(setRange("b2",$limit."2"),$excel,"Republic of the Philippines","true",$ExWs);
	$excel->getActiveSheet()->getStyle("B2:".$limit."2")->getFont()->setBold(true);
//	addContent(setRange("b3",$limit."3"),$excel,"DEPARTMENT OF TRANSPORTATION","true",$ExWs);
	$excel->getActiveSheet()->getStyle("B3:".$limit."3")->getFont()->setBold(true);

	//addContent(setRange("b4",$limit."4"),$excel,"AND COMMUNICATIONS","true",$ExWs);
	addContent(setRange("b4",$limit."4"),$excel,"DOTC-MRT3","true",$ExWs);
	$excel->getActiveSheet()->getStyle("b4:".$limit."4")->getFont()->setSize(14);

	$excel->getActiveSheet()->getStyle("B4:".$limit."4")->getFont()->setBold(true);
//	addContent(setRange("b5",$limit."5"),$excel,"METRO RAIL TRANSIT III EDSA LINE","true",$ExWs);
	addContent(setRange("b5",$limit."5"),$excel,"Support Staff/AFC Center/Computer Section","true",$ExWs);
	$excel->getActiveSheet()->getStyle("b5:".$limit."5")->getFont()->setSize(14);

	$excel->getActiveSheet()->getRowDimension('4')->setRowHeight(18);
	$excel->getActiveSheet()->getRowDimension('5')->setRowHeight(18);

	
	
//	addContent(setRange("b6",$limit."6"),$excel,"(DOTC-MRT3)","true",$ExWs);
	
	$excel->getActiveSheet()->getStyle("B2:".$limit."2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B3:".$limit."3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B4:".$limit."4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B5:".$limit."5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B6:".$limit."6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	
	$rowCount=10;
	
	addContent(setRange("a".$rowCount,"o".$rowCount),$excel,"Training Report","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount,"O".$rowCount)->getFont()->setSize(14);	
	$excel->getActiveSheet()->getStyle("A".$rowCount,"O".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$rowCount++;
	if($_SESSION['courseType']=="all"){	

		addContent(setRange("a".$rowCount,"o".$rowCount),$excel,"From ".date("F d, Y",strtotime($dateFrom))." to ".date("F d, Y",strtotime($dateTo)),"true",$ExWs);
	
		$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);	

		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setSize(14);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		

		$rowCount++;
		
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setBold(true);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setSize(14);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		addContent(setRange("a".$rowCount,"o".$rowCount),$excel,strtoupper("Training Events Conducted"),"true",$ExWs);
		$rowCount++;
		$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(34);	
		$cell=strtoupper("A".$rowCount);
		addContent(setRange($cell,$cell),$excel,"Training Course","true",$ExWs);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getFont()->setBold(true);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setWrapText(true);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->applyFromArray($styleArray);

		
		$cell=strtoupper("B".$rowCount);
		addContent(setRange($cell,$cell),$excel,"Batch No.","true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setWrapText(true);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->applyFromArray($styleArray);

				
		$cell=strtoupper("C".$rowCount);
		$cell2=strtoupper("E".$rowCount);

		addContent(setRange($cell,$cell2),$excel,"Period","true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

				
		$cell=strtoupper("F".$rowCount);
		$cell2=strtoupper("F".$rowCount);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);

		addContent(setRange($cell,$cell2),$excel,"# of Trainees","true",$ExWs);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

		$cell=strtoupper("G".$rowCount);

		$cell2=strtoupper("H".$rowCount);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);
		addContent(setRange($cell,$cell2),$excel,"Trainers","true",$ExWs);

		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

		
		$rowCount++;
		
		if(isset($_GET['trainingEventView'])){
			$sql="select *,(select count(*) from class_instance where training_event_id=training_instance.id) as attendees from training_instance where training_instance.id='".$_GET['trainingEventView']."'";

		}
		else {
			if($batch_no==""){ } else { $batch_clause=" and batch_number='".$batch_no."' "; }
			$sql="select *,(select count(*) from class_instance where training_event_id=training_instance.id) as attendees from training_instance where start_date between '".$dateFrom."' and '".$dateTo."'".$batch_clause." order by batch_number*1";

		}		
		$rs=$db->query($sql);
		$nm=$rs->num_rows;

		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();

			$sqlLabel="select * from trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id where event_id='".$row['id']."'";
			$rsLabel=$db->query($sqlLabel);
			$nmLabel=$rsLabel->num_rows;
			$rowCountStart=$rowCount;
			$rowCountEnd=$rowCount*1+($nmLabel*1-1);
			for($k=0;$k<$nmLabel;$k++){
				$rowLabel=$rsLabel->fetch_assoc();

	/*			if($k==0){
					$rowCountStart=$rowCount;
				}
				else if($k==($nmLabel*1-1)){
					$rowCountEnd=$rowCount;
				}
	*/
				$rr[0]="G".$rowCount;
				$rr[1]="H".$rowCount;					
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setWrapText(true);
				addContent($rr,$excel,strtoupper($rowLabel['lastName']).", ".$rowLabel['firstName'],"true",$ExWs);
				$rowCount++;
			}

			
			$cell=strtoupper("A".$rowCountStart);
			$cell2=strtoupper("A".$rowCountEnd);
			
			if($cell>$cell2){ $rowCountStart--; $cell=strtoupper("A".$rowCountStart); }
			
			addContent(setRange($cell,$cell2),$excel,$row['training_title'],"true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);		
			$cell=strtoupper("B".$rowCountStart);
			$cell2=strtoupper("B".$rowCountEnd);
			addContent(setRange($cell,$cell2),$excel,$row['batch_number'],"true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			
			$cell=strtoupper("C".$rowCountStart);
			$cell2=strtoupper("E".$rowCountEnd);

			addContent(setRange($cell,$cell2),$excel,date("F d",strtotime($row['start_date']))." - ".date("F d, Y",strtotime($row['end_date'])),"true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$cell=strtoupper("F".$rowCountStart);
			$cell2=strtoupper("F".$rowCountEnd);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			addContent(setRange($cell,$cell2),$excel,$row['attendees'],"true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					
	//		addContent(setRange($cell,$cell2),$excel,$row['attendees'],"true",$ExWs);
			$rowCount=$rowCountEnd;
		
			$rowCount++;

		}
	}	
	else if($_SESSION['courseType']=="single"){
//		$excel->getActiveSheet()->setBreak( 'P500' , PHPExcel_Worksheet::BREAK_COLUMN );	

		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		
		
		
		$excel->getActiveSheet()->getRowDimension('11')->setRowHeight(18);
		$rowCount--;
		$courseID=$_SESSION['courseId'];
		$progLabel="select * from training_programs where id='".$courseID."'";
		$rsProg=$db->query($progLabel);
		$rowPRog=$rsProg->fetch_assoc();
	
		$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);	

		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setSize(14);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
	
		$rowCount++;
		
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setBold(true);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setSize(14);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		addContent(setRange("a".$rowCount,"o".$rowCount),$excel,strtoupper($rowPRog['training_title']),"true",$ExWs);
		
		$rowCount++;
		$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(34);	
		$cell=strtoupper("A".$rowCount);
		addContent(setRange($cell,$cell),$excel,"Batch No.","true",$ExWs);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setWrapText(true);

		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->applyFromArray($styleArray);

				
			
			
		$cell=strtoupper("B".$rowCount);
		$cell2=strtoupper("C".$rowCount);

		addContent(setRange($cell,$cell2),$excel,"Name of Participants","true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

				
		$cell=strtoupper("D".$rowCount);
		$cell2=strtoupper("F".$rowCount);

		addContent(setRange($cell,$cell2),$excel,"Period of Training","true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

				
		$cell=strtoupper("G".$rowCount);
		$cell2=strtoupper("I".$rowCount);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);

		addContent(setRange($cell,$cell2),$excel,"Issued Certificate","true",$ExWs);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

		$cell=strtoupper("J".$rowCount);
		$cell2=strtoupper("L".$rowCount);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);

		addContent(setRange($cell,$cell2),$excel,"Remarks","true",$ExWs);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

		
		
		$cell=strtoupper("M".$rowCount);
		$cell2=strtoupper("O".$rowCount);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);

		addContent(setRange($cell,$cell2),$excel,"Trainers","true",$ExWs);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

		
		
		//$excel->getActiveSheet()->freezePane("P".($rowCount+1));
		

		if($batch_no==""){ } else { $batch_clause=" and batch_number='".$batch_no."' "; }
		
		$rowCount++;
		$sql="select *,(select count(*) from class_instance where training_event_id=training_instance.id) as attendees from training_instance where start_date between '".$dateFrom."' and '".$dateTo."' and program_id='".$courseID."'".$batch_clause." order by batch_number*1";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		
		$counter=13;
		
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();

			$sqlLabel="select *,(select release_date from diploma_release where trainee_id=class_instance.traineeId and training_program_id=class_instance.training_event_id order by release_date desc limit 1) as release_date  from class_instance where training_event_id='".$row['id']."' order by lastName";
			$rsLabel=$db->query($sqlLabel);
			$nmLabel=$rsLabel->num_rows;
			$rowCountStart=$rowCount;
			$rowCountEnd=$rowCount*1+($nmLabel*1-1);
			
			
			if($counter==13){

			$sqlTrainer="select * from trainer_class inner join trainer_list on trainer_id=trainer_list.id where event_id='".$row['id']."' order by lastName";
			$rsTrainer=$db->query($sqlTrainer);
			$nmTrainer=$rsTrainer->num_rows;
			$rowCountTrainStart=$rowCount;
			
			$rowCountTrainEnd=$rowCount*1+($nmTrainer*1-1);
			
			
				for($k=0;$k<$nmTrainer;$k++){
					$rowTrainer=$rsTrainer->fetch_assoc();
					
					$rr[0]="M".$rowCountTrainStart;
					$rr[1]="O".$rowCountTrainStart;					
					$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setWrapText(true);
					addContent($rr,$excel,strtoupper($rowTrainer['lastName']).", ".$rowTrainer['firstName']." ".$rowTrainer['midInitial'],"true",$ExWs);
				
					$rowCountTrainStart++;	
				
				}	
			}
			for($k=0;$k<$nmLabel;$k++){
				$rowLabel=$rsLabel->fetch_assoc();
				
				if(($k==0)||($counter==13)){
					$cell=strtoupper("A".$rowCount);
					$cell2=strtoupper("A".$rowCount);
										
					addContent(setRange($cell,$cell2),$excel,$row['batch_number'],"true",$ExWs);
					$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$cell=strtoupper("D".$rowCount);
					$cell2=strtoupper("F".$rowCount);

					addContent(setRange($cell,$cell2),$excel,date("F d",strtotime($row['start_date']))." - ".date("F d, Y",strtotime($row['end_date'])),"true",$ExWs);
					//$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					//$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					
				}

				if($counter==13){

					$rsTrainer=$db->query($sqlTrainer);
					$nmTrainer=$rsTrainer->num_rows;
					$rowCountTrainStart=$rowCount;
					
					$rowCountTrainEnd=$rowCount*1+($nmTrainer*1-1);
				
				
					for($mm=0;$mm<$nmTrainer;$mm++){
						$rowTrainer=$rsTrainer->fetch_assoc();
						
						$rr[0]="M".$rowCountTrainStart;
						$rr[1]="O".$rowCountTrainStart;					
						$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setWrapText(true);
						addContent($rr,$excel,strtoupper($rowTrainer['lastName']).", ".$rowTrainer['firstName']." ".$rowTrainer['midInitial'],"true",$ExWs);
					
						$rowCountTrainStart++;	
					
					}	
				}
				addContent(setRange("B".$rowCount,"B".$rowCount),$excel,$k*1+1,"true",$ExWs);

				
				
				
				$rr[0]="C".$rowCount;
				$rr[1]="C".$rowCount;					
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setWrapText(true);
				addContent($rr,$excel,iconv('ISO-8859-1','UTF-8',strtoupper(str_replace("ñ","Ñ",$rowLabel['lastName']))).", ".$rowLabel['firstName']." ".$rowLabel['midInitial'],"true",$ExWs);

				if($rowLabel['release_date']==""){
				$issueDate="";
				}
				else {
				
					$issueDate="Issued Certification, ".date("m/d/Y",strtotime($rowLabel['release_date']));
				//$issueDate=date("F d, Y",strtotime($rowLabel['release_date']));
				}
				$rr[0]="G".$rowCount;
				$rr[1]="I".$rowCount;					
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setWrapText(true);
				addContent($rr,$excel,$issueDate,"true",$ExWs);

				$rr[0]="J".$rowCount;
				$rr[1]="L".$rowCount;					
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setWrapText(true);
				addContent($rr,$excel,$rowLabel['remarks'],"true",$ExWs);

				$styleArray = array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
					),
				);				
				$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->applyFromArray($styleArray);
				$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->applyFromArray($styleArray);
				$excel->getActiveSheet()->getStyle("C".$rowCount.":C".$rowCount)->applyFromArray($styleArray);
				$excel->getActiveSheet()->getStyle("D".$rowCount.":F".$rowCount)->applyFromArray($styleArray);
				$excel->getActiveSheet()->getStyle("G".$rowCount.":I".$rowCount)->applyFromArray($styleArray);
				$excel->getActiveSheet()->getStyle("J".$rowCount.":L".$rowCount)->applyFromArray($styleArray);
				$excel->getActiveSheet()->getStyle("M".$rowCount.":O".$rowCount)->applyFromArray($styleArray);

				
				
				
//				if($counter==39){
				if($counter==29){
					$counter=13;
					$rowCount+=9; //addition		

					addContent(setRange("B".($rowCount-7),"C".($rowCount-7)),$excel,"Printed By:","true",$ExWs);  //addition
					addContent(setRange("F".($rowCount-7),"I".($rowCount-7)),$excel,"Reviewed By:","true",$ExWs);  //addition

					addContent(setRange("L".($rowCount-7),"O".($rowCount-7)),$excel,"Noted By:","true",$ExWs);  //addition
					
					
					$excel->getActiveSheet()->getStyle("B".($rowCount-5).":D".($rowCount-5))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$excel->getActiveSheet()->getStyle("F".($rowCount-5).":I".($rowCount-5))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$excel->getActiveSheet()->getStyle("L".($rowCount-5).":O".($rowCount-5))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


					addContent(setRange("B".($rowCount-4),"D".($rowCount-4)),$excel,"REINALOU ANTOINETTE T. PASCUA","true",$ExWs);  //addition

					addContent(setRange("F".($rowCount-4),"I".($rowCount-4)),$excel,"","true",$ExWs);  //addition
					
					addContent(setRange("L".($rowCount-4),"O".($rowCount-4)),$excel,"OFELIA D. ASTRERA","true",$ExWs);  //addition
					
					$excel->getActiveSheet()->getStyle("B".($rowCount-4).":D".($rowCount-4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle("L".($rowCount-4).":O".($rowCount-4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					addContent(setRange("B".($rowCount-3),"D".($rowCount-3)),$excel,"Senior Administrative Assistant I","true",$ExWs);  //addition
					addContent(setRange("L".($rowCount-3),"O".($rowCount-3)),$excel,"Chief, Support Staff/AFC Center/","true",$ExWs);  //addition
					addContent(setRange("F".($rowCount-3),"I".($rowCount-3)),$excel,"Trainer","true",$ExWs);  //addition


					$excel->getActiveSheet()->getStyle("B".($rowCount-3).":D".($rowCount-3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle("L".($rowCount-3).":O".($rowCount-3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle("F".($rowCount-3).":I".($rowCount-3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					addContent(setRange("L".($rowCount-2),"O".($rowCount-2)),$excel,"Computer Section","true",$ExWs);  //addition
					$excel->getActiveSheet()->getStyle("L".($rowCount-2).":O".($rowCount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					addContent(setRange("B".($rowCount-1),"D".($rowCount-1)),$excel,"Date Printed: ".date("m/d/Y"),"true",$ExWs);  //addition
					
					
	//				addContent(setRange("b".($rowCount+2),"N".($rowCount+2)),$excel,"Republic of the Philippines","true",$ExWs);
					$excel->getActiveSheet()->getStyle("B".($rowCount+2).":N".($rowCount+2))->getFont()->setBold(true);
	//				addContent(setRange("b".($rowCount+3),"N".($rowCount+3)),$excel,"DEPARTMENT OF TRANSPORTATION","true",$ExWs);
					$excel->getActiveSheet()->getStyle("B".($rowCount+3).":N".($rowCount+3))->getFont()->setBold(true);
	//				addContent(setRange("b".($rowCount+4),"N".($rowCount+4)),$excel,"AND COMMUNICATIONS","true",$ExWs);
					addContent(setRange("b".($rowCount+4),"N".($rowCount+4)),$excel,"DOTC-MRT3","true",$ExWs);
					$excel->getActiveSheet()->getStyle("b".($rowCount+4).":N".($rowCount+4))->getFont()->setSize(14);
					$excel->getActiveSheet()->getStyle("b".($rowCount+5).":N".($rowCount+5))->getFont()->setSize(14);

					$excel->getActiveSheet()->getRowDimension(($rowCount+4))->setRowHeight(18);
					$excel->getActiveSheet()->getRowDimension(($rowCount+5))->setRowHeight(18);

					
					$excel->getActiveSheet()->getStyle("B".($rowCount+4).":N".($rowCount+4))->getFont()->setBold(true);
//					addContent(setRange("b".($rowCount+5),"N".($rowCount+5)),$excel,"METRO RAIL TRANSIT III EDSA LINE","true",$ExWs);
					addContent(setRange("b".($rowCount+5),"N".($rowCount+5)),$excel,"Support Staff/AFC Center/Computer Section","true",$ExWs);

//					addContent(setRange("b".($rowCount+6),"N".($rowCount+6)),$excel,"(DOTC-MRT3)","true",$ExWs);
					
					
					$excel->getActiveSheet()->getRowDimension(($rowCount+10))->setRowHeight(18);	
					$excel->getActiveSheet()->getRowDimension(($rowCount+13))->setRowHeight(18);
					$excel->getActiveSheet()->getRowDimension(($rowCount+11))->setRowHeight(18);
					$excel->getActiveSheet()->getRowDimension(($rowCount+12))->setRowHeight(34);
					
					
			$excel->getActiveSheet()->getStyle("B".($rowCount+2).":N".($rowCount+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B".($rowCount+3).":N".($rowCount+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B".($rowCount+4).":N".($rowCount+4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B".($rowCount+5).":N".($rowCount+5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B".($rowCount+6).":N".($rowCount+6))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			addContent(setRange("a".($rowCount+10),"o".($rowCount+10)),$excel,"Training Report","true",$ExWs);
			$excel->getActiveSheet()->getStyle("A".($rowCount+10),"O".($rowCount+10))->getFont()->setSize(14);	
			$excel->getActiveSheet()->getStyle("A".($rowCount+10),"O".($rowCount+10))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			
			
		$rowCount+=10;
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setSize(14);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
	
		$rowCount++;
		
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setBold(true);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setSize(14);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		addContent(setRange("a".$rowCount,"o".$rowCount),$excel,strtoupper($rowPRog['training_title']),"true",$ExWs);
		
		$rowCount++;
		$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(34);	
		$cell=strtoupper("A".$rowCount);
		addContent(setRange($cell,$cell),$excel,"Batch No.","true",$ExWs);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setWrapText(true);

		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->applyFromArray($styleArray);

				
			
			
		$cell=strtoupper("B".$rowCount);
		$cell2=strtoupper("C".$rowCount);

		addContent(setRange($cell,$cell2),$excel,"Name of Participants","true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

				
		$cell=strtoupper("D".$rowCount);
		$cell2=strtoupper("F".$rowCount);

		addContent(setRange($cell,$cell2),$excel,"Period of Training","true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

				
		$cell=strtoupper("G".$rowCount);
		$cell2=strtoupper("I".$rowCount);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);

		addContent(setRange($cell,$cell2),$excel,"Issued Certificate","true",$ExWs);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

		$cell=strtoupper("J".$rowCount);
		$cell2=strtoupper("L".$rowCount);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);

		addContent(setRange($cell,$cell2),$excel,"Remarks","true",$ExWs);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

		
		
		$cell=strtoupper("M".$rowCount);
		$cell2=strtoupper("O".$rowCount);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);

		addContent(setRange($cell,$cell2),$excel,"Trainers","true",$ExWs);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);


	

		
				$rowCount++;
				
	
				
					
//					$rowCount+=13;

					//					$rowCount++;
				}
				else {
					$counter++;

					$rowCount++;
				}

			}



			
	//		$rowCount++;
	//		$rowCount++;
	
	
			$rowCount+=(38-$counter);

			addContent(setRange("B".($rowCount-7),"C".($rowCount-7)),$excel,"Printed By:","true",$ExWs);  //addition
			addContent(setRange("F".($rowCount-7),"I".($rowCount-7)),$excel,"Reviewed By:","true",$ExWs);  //addition

			addContent(setRange("L".($rowCount-7),"O".($rowCount-7)),$excel,"Noted By:","true",$ExWs);  //addition
			
			
			$excel->getActiveSheet()->getStyle("B".($rowCount-5).":D".($rowCount-5))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$excel->getActiveSheet()->getStyle("F".($rowCount-5).":I".($rowCount-5))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$excel->getActiveSheet()->getStyle("L".($rowCount-5).":O".($rowCount-5))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


			addContent(setRange("B".($rowCount-4),"D".($rowCount-4)),$excel,"REINALOU ANTOINETTE T. PASCUA","true",$ExWs);  //addition
			
			addContent(setRange("F".($rowCount-4),"I".($rowCount-4)),$excel,"","true",$ExWs);  //addition

			addContent(setRange("L".($rowCount-4),"O".($rowCount-4)),$excel,"OFELIA D. ASTRERA","true",$ExWs);  //addition
			
			$excel->getActiveSheet()->getStyle("B".($rowCount-4).":D".($rowCount-4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("L".($rowCount-4).":O".($rowCount-4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					addContent(setRange("B".($rowCount-3),"D".($rowCount-3)),$excel,"Senior Administrative Assistant I","true",$ExWs);  //addition
					addContent(setRange("F".($rowCount-3),"I".($rowCount-3)),$excel,"Trainer","true",$ExWs);  //addition

					addContent(setRange("L".($rowCount-3),"O".($rowCount-3)),$excel,"Chief, Support Staff/AFC Center/","true",$ExWs);  //addition
					$excel->getActiveSheet()->getStyle("B".($rowCount-3).":D".($rowCount-3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle("F".($rowCount-3).":I".($rowCount-3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$excel->getActiveSheet()->getStyle("L".($rowCount-3).":O".($rowCount-3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					addContent(setRange("L".($rowCount-2),"O".($rowCount-2)),$excel,"Computer Section","true",$ExWs);  //addition
					$excel->getActiveSheet()->getStyle("L".($rowCount-2).":O".($rowCount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			addContent(setRange("B".($rowCount-1),"D".($rowCount-1)),$excel,"Date Printed: ".date("m/d/Y"),"true",$ExWs);  //addition
					
			
//			addContent(setRange("b".($rowCount+2),"N".($rowCount+2)),$excel,"Republic of the Philippines","true",$ExWs);
			$excel->getActiveSheet()->getStyle("B".($rowCount+2).":N".($rowCount+2))->getFont()->setBold(true);
//			addContent(setRange("b".($rowCount+3),"N".($rowCount+3)),$excel,"DEPARTMENT OF TRANSPORTATION","true",$ExWs);
			$excel->getActiveSheet()->getStyle("B".($rowCount+3).":N".($rowCount+3))->getFont()->setBold(true);
//			addContent(setRange("b".($rowCount+4),"N".($rowCount+4)),$excel,"AND COMMUNICATIONS","true",$ExWs);
			addContent(setRange("b".($rowCount+4),"N".($rowCount+4)),$excel,"DOTC-MRT3","true",$ExWs);

			$excel->getActiveSheet()->getStyle("B".($rowCount+4).":N".($rowCount+4))->getFont()->setBold(true);
//			addContent(setRange("b".($rowCount+5),"N".($rowCount+5)),$excel,"METRO RAIL TRANSIT III EDSA LINE","true",$ExWs);
			addContent(setRange("b".($rowCount+5),"N".($rowCount+5)),$excel,"Support Staff/AFC Center/Computer Section","true",$ExWs);
					$excel->getActiveSheet()->getStyle("b".($rowCount+4).":N".($rowCount+4))->getFont()->setSize(14);
					$excel->getActiveSheet()->getStyle("b".($rowCount+5).":N".($rowCount+5))->getFont()->setSize(14);
					$excel->getActiveSheet()->getRowDimension(($rowCount+4))->setRowHeight(18);
					$excel->getActiveSheet()->getRowDimension(($rowCount+5))->setRowHeight(18);

//			addContent(setRange("b".($rowCount+6),"N".($rowCount+6)),$excel,"(DOTC-MRT3)","true",$ExWs);

			$excel->getActiveSheet()->getStyle("B".($rowCount+2).":N".($rowCount+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B".($rowCount+3).":N".($rowCount+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B".($rowCount+4).":N".($rowCount+4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B".($rowCount+5).":N".($rowCount+5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B".($rowCount+6).":N".($rowCount+6))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	
	
			addContent(setRange("a".($rowCount+10),"o".($rowCount+10)),$excel,"Training Report","true",$ExWs);
			$excel->getActiveSheet()->getStyle("A".($rowCount+10),"O".($rowCount+10))->getFont()->setSize(14);	
			$excel->getActiveSheet()->getStyle("A".($rowCount+10),"O".($rowCount+10))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			


			
			
			
			$excel->getActiveSheet()->getRowDimension(($rowCount+10))->setRowHeight(18);	
			$excel->getActiveSheet()->getRowDimension(($rowCount+13))->setRowHeight(18);
			$excel->getActiveSheet()->getRowDimension(($rowCount+11))->setRowHeight(18);
			$excel->getActiveSheet()->getRowDimension(($rowCount+12))->setRowHeight(34);


		$rowCount+=10;
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setSize(14);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
	
		$rowCount++;
		
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setBold(true);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getFont()->setSize(14);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":O".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		addContent(setRange("a".$rowCount,"o".$rowCount),$excel,strtoupper($rowPRog['training_title']),"true",$ExWs);
		
		$rowCount++;
		$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(34);	
		$cell=strtoupper("A".$rowCount);
		addContent(setRange($cell,$cell),$excel,"Batch No.","true",$ExWs);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setWrapText(true);

		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->applyFromArray($styleArray);

				
			
			
		$cell=strtoupper("B".$rowCount);
		$cell2=strtoupper("C".$rowCount);

		addContent(setRange($cell,$cell2),$excel,"Name of Participants","true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

				
		$cell=strtoupper("D".$rowCount);
		$cell2=strtoupper("F".$rowCount);

		addContent(setRange($cell,$cell2),$excel,"Period of Training","true",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

				
		$cell=strtoupper("G".$rowCount);
		$cell2=strtoupper("I".$rowCount);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);

		addContent(setRange($cell,$cell2),$excel,"Issued Certificate","true",$ExWs);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

		$cell=strtoupper("J".$rowCount);
		$cell2=strtoupper("L".$rowCount);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);

		addContent(setRange($cell,$cell2),$excel,"Remarks","true",$ExWs);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);

		
		
		$cell=strtoupper("M".$rowCount);
		$cell2=strtoupper("O".$rowCount);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);

		addContent(setRange($cell,$cell2),$excel,"Trainers","true",$ExWs);
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);
			
			
			
			
			$rowCount++;
			
//			$rowCount+=13;
			$counter=13;
		}
	
	}
	save($ExWb,$excel,$newFilename); 
	
	echo "Report has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
	
	/**

	
	



	

	




	
//	$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setTextRotation(73);


	

	
	

		
		
	for ($i=65; $i<=90; $i++)
echo chr($i)."<br>"; 
	
	*/


?>
