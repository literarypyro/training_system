<?php 
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");

?>
<?php



$_SESSION['training_page']="trainer_report.php";

	$db=retrieveTrainingDb();
	//$db=new mysqli("localhost","root","","training");
	
	$datePrintout=date("Ymd H-i-s");
	
	$newFilename="printout/Training Event Preview ".$datePrintout.".xls";
	
	$workbookname=$newFilename;
	$excel=new PHPExcel();
	$workSheetName="Training Events Report";	
	
 	$ExWs=createWorksheet($excel,$workSheetName,"create");		

	
		
	
	
	
	$excel->getActiveSheet()->getRowDimension('10')->setRowHeight(18);	
	$excel->getActiveSheet()->getRowDimension('12')->setRowHeight(18);
	$excel->getActiveSheet()->getRowDimension('13')->setRowHeight(18);

	$excel->getActiveSheet()->getColumnDimension('B')->setWidth(26.57);
	
	$limit="G";
	//addImage(setRange("A1","B7"),$excel,"c:/report/records picture2.jpg","true",$ExWs);
	addContent(setRange("b2",$limit."2"),$excel,"Republic of the Philippines","true",$ExWs);

	$rowCount=1;

	$excel->getActiveSheet()->getStyle("B2:".$limit."2")->getFont()->setBold(true);
	addContent(setRange("b3",$limit."3"),$excel,"DEPARTMENT OF TRANSPORTATION","true",$ExWs);
	$excel->getActiveSheet()->getStyle("B3:".$limit."3")->getFont()->setBold(true);
	addContent(setRange("b4",$limit."4"),$excel,"AND COMMUNICATION","true",$ExWs);
	$excel->getActiveSheet()->getStyle("B4:".$limit."4")->getFont()->setBold(true);
	addContent(setRange("b5",$limit."5"),$excel,"METRO RAIL TRANSIT III EDSA LINE","true",$ExWs);
	addContent(setRange("b6",$limit."6"),$excel,"(DOTC-MRT3)","true",$ExWs);
	
	$excel->getActiveSheet()->getStyle("B2:".$limit."2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B3:".$limit."3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B4:".$limit."4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B5:".$limit."5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B6:".$limit."6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	//$db=new mysqli("localhost","root","","training");
	$db=retrieveTrainingDb();
	$sql="select * from training_instance where id='".$_SESSION['previewID']."'";

	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	
	
	$rowCount=10;
	
	addContent(setRange("a".$rowCount,"h".$rowCount),$excel,"Training Event Report","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount,"H".$rowCount)->getFont()->setSize(14);	
	$excel->getActiveSheet()->getStyle("A".$rowCount,"H".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":H".$rowCount)->getFont()->setBold(true);
	$rowCount++;
	addContent(setRange("a".$rowCount,"h".$rowCount),$excel,"For ".strtoupper($row['training_title']).", Batch ".$row['batch_number'],"true",$ExWs);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":H".$rowCount)->getFont()->setBold(true);

		$courseID=$_SESSION['courseId'];
		$progLabel="select * from training_programs where id='".$courseID."'";
		$rsProg=$db->query($progLabel);
		$rowPRog=$rsProg->fetch_assoc();
	
		$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);	

		$excel->getActiveSheet()->getStyle("A".$rowCount.":H".$rowCount)->getFont()->setSize(14);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":H".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		

		$rowCount++;
		
		$excel->getActiveSheet()->getStyle("A".$rowCount.":H".$rowCount)->getFont()->setBold(true);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":H".$rowCount)->getFont()->setSize(14);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":H".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		addContent(setRange("a".$rowCount,"h".$rowCount),$excel,strtoupper($rowPRog['training_title']),"true",$ExWs);
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
		addContent(setRange($cell,$cell),$excel,"Name of Participants","true",$ExWs);
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

				
		$cell=strtoupper("F".$rowCount);
		$cell2=strtoupper("H".$rowCount);
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



		
		$rowCount++;

		$sql="select *,(select count(*) from class_instance where training_event_id=training_instance.id) as attendees from training_instance where training_instance.id='".$_SESSION['previewID']."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;


			$row=$rs->fetch_assoc();

			$sqlLabel="select *,(select release_date from diploma_release where trainee_id=class_instance.traineeId and training_program_id=class_instance.training_event_id order by release_date desc limit 1) as release_date  from class_instance where training_event_id='".$row['id']."' order by lastName";

			$rsLabel=$db->query($sqlLabel);
			$nmLabel=$rsLabel->num_rows;
			$rowCountStart=$rowCount;
			$rowCountEnd=$rowCount*1+($nmLabel*1-1);
			for($k=0;$k<$nmLabel;$k++){
				$rowLabel=$rsLabel->fetch_assoc();
				
				if($k==0){
					$cell=strtoupper("A".$rowCount);
					$cell2=strtoupper("A".$rowCount);
					
					addContent(setRange($cell,$cell2),$excel,$row['batch_number'],"true",$ExWs);
					$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$cell=strtoupper("C".$rowCount);
					$cell2=strtoupper("E".$rowCount);

					addContent(setRange($cell,$cell2),$excel,date("F d",strtotime($row['start_date']))." - ".date("F d, Y",strtotime($row['end_date'])),"true",$ExWs);
					//$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					//$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					
				}
				
				
				$rr[0]="B".$rowCount;
				$rr[1]="B".$rowCount;				

				$middleName=substr($rowLabel['midInitial'],0,1);
				if($middleName==""){ }
				else { $middleName.="."; }
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setWrapText(true);
				addContent($rr,$excel,str_replace("Ñ","N",strtoupper($rowLabel['lastName'])).", ".$rowLabel['firstName']." ".$middleName,"true",$ExWs);

				if($rowLabel['release_date']==""){
				$issueDate="";
				}
				else {
				$issueDate=date("F d, Y",strtotime($rowLabel['release_date']));
				}
				$rr[0]="F".$rowCount;
				$rr[1]="H".$rowCount;					
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setWrapText(true);
				addContent($rr,$excel,$issueDate,"true",$ExWs);



				$rowCount++;
			}
			$rowCount++;
			$rowCount++;
		

	
	save($ExWb,$excel,$newFilename); 
	
	$_SESSION['caption']="Preview has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
	
	/**

	
	



	

	




	
//	$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setTextRotation(73);


	

	
	

		
		
	for ($i=65; $i<=90; $i++)
echo chr($i)."<br>"; 
	
	*/


?>
