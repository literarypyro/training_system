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
	$eventId=$_GET['evt'];

	$datePrintout=date("Ymd H-i-s");
	
	$newFilename="printout/Hourly Schedule ".$datePrintout.".xls";
	
	$workSheetName="Participants Chart";
	$workbookname=$newFilename;
	//$excel=loadExistingWorkbook($workbookname);
		$excel=startCOMGiven();

	
 	$ExWs=createWorksheet($excel,$workSheetName,"create");		

//$db=new mysqli("localhost","root","","training");

$db=retrieveTrainingDb();
$sql="select * from training_schedule where event_id='".$eventId."' group by date order by date";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$labelSQL="select * from training_instance where id='".$eventId."'";
	$labelRS=$db->query($labelSQL);
	$labelRow=$labelRS->fetch_assoc();
	
	
	
	$rowCount=2;	
	
	addContent(setRange("a".$rowCount,"j".$rowCount),$excel,strtoupper("Hourly Schedule of Activities"),"true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount,"J".$rowCount)->getFont()->setSize(14);	
	$excel->getActiveSheet()->getStyle("A".$rowCount,"J".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":J".$rowCount)->getFont()->setBold(true);	
	$rowCount++;
	
	$training_title="Train Driver";
	
	addContent(setRange("a".$rowCount,"j".$rowCount),$excel,strtoupper($labelRow['training_title']).", (BATCH ".$labelRow['batch_number'].")","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount,"J".$rowCount)->getFont()->setSize(14);	
	$excel->getActiveSheet()->getStyle("A".$rowCount,"J".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":J".$rowCount)->getFont()->setBold(true);	

	$rowCount+=2;

	

	

	for($i=1;$i<=$nm;$i++){
		$row=$rs->fetch_assoc();

		$labelSQL="select * from training_instance where id='".$eventId."'";
		$labelRS=$db->query($labelSQL);
		$labelRow=$labelRS->fetch_assoc();
		
		addContent(setRange("a".$rowCount,"j".$rowCount),$excel,"Day ".$i." (".date("d F Y",strtotime($row['date'])).")","true",$ExWs);
		$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
					),
				),
			);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":J".$rowCount)->applyFromArray($styleArray);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":J".$rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
		$excel->getActiveSheet()->getStyle("A".$rowCount,"J".$rowCount)->getFont()->setSize(12);	
		$excel->getActiveSheet()->getStyle("A".$rowCount,"J".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":J".$rowCount)->getFont()->setBold(true);	
		$rowCount++;
		
		addContent(setRange("a".$rowCount,"c".$rowCount),$excel,"Time","true",$ExWs);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->getFont()->setSize(12);	
		$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->getFont()->setBold(true);	
		$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
					),
				),
			);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->applyFromArray($styleArray);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);

		
		addContent(setRange("D".$rowCount,"G".$rowCount),$excel,"Activity","true",$ExWs);
		$excel->getActiveSheet()->getStyle("D".$rowCount.":G".$rowCount)->getFont()->setSize(12);	
		$excel->getActiveSheet()->getStyle("D".$rowCount.":G".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("D".$rowCount.":G".$rowCount)->getFont()->setBold(true);	
		$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
					),
				),
			);
		$excel->getActiveSheet()->getStyle("D".$rowCount.":G".$rowCount)->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("D".$rowCount.":G".$rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);

		addContent(setRange("H".$rowCount,"J".$rowCount),$excel,"Location","true",$ExWs);
		$excel->getActiveSheet()->getStyle("H".$rowCount,"J".$rowCount)->getFont()->setSize(12);	
		$excel->getActiveSheet()->getStyle("H".$rowCount,"J".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":J".$rowCount)->getFont()->setBold(true);	
		$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
					),
				),
			);
		$excel->getActiveSheet()->getStyle("H".$rowCount.":J".$rowCount)->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("H".$rowCount.":J".$rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
		$rowCount++;
		$hourlysql="select * from training_schedule where event_id='".$eventId."' and date='".$row['date']."' order by start_time";
		$hourlyrs=$db->query($hourlysql);
		$hourlynm=$hourlyrs->num_rows;
		for($a=0;$a<$hourlynm;$a++){
			$hourlyRow=$hourlyrs->fetch_assoc();
			
		addContent(setRange("a".$rowCount,"C".$rowCount),$excel,date("h:i A",strtotime($hourlyRow['start_time']))."-".date("h:i A",strtotime($hourlyRow['end_time'])),"true",$ExWs);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->getFont()->setSize(12);	
		$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	
		addContent(setRange("D".$rowCount,"G".$rowCount),$excel,$hourlyRow['activity'],"true",$ExWs);
		$excel->getActiveSheet()->getStyle("D".$rowCount.":G".$rowCount)->getFont()->setSize(12);	
		$excel->getActiveSheet()->getStyle("D".$rowCount.":G".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle("D".$rowCount.":G".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("D".$rowCount.":G".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("D".$rowCount.":G".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		
		addContent(setRange("H".$rowCount,"J".$rowCount),$excel,$hourlyRow['location'],"true",$ExWs);
		$excel->getActiveSheet()->getStyle("H".$rowCount.":J".$rowCount)->getFont()->setSize(12);	
		$excel->getActiveSheet()->getStyle("H".$rowCount.":J".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
		$excel->getActiveSheet()->getStyle("H".$rowCount.":J".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("H".$rowCount.":J".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("H".$rowCount.":J".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
		$rowCount++;
			
			
			
			
			
			
			
		}
		$rowCount++;
	}

}
	
	save($ExWb,$excel,$newFilename); 
	
	echo "Report has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
	



?>