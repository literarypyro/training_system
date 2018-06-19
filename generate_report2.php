<?php 
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");

?>
<?php
if($_SESSION['training_page']=="monthly_report.php"){
	$datePrintout=date("Ymd H-i-s");
	
	$newFilename="printout/Trainer Report ".$datePrintout.".xls";
//	copy($filename,$newFilename);
	
	
	$workbookname=$newFilename;
	$excel=startCOMGiven();
	$workSheetName="Trainer Report";	
 	$ExWs=createWorksheet($excel,$workSheetName,"create");	


	if($_SESSION['trainerType']=="single"){
		$db=new mysqli("localhost","root","","training");
		$sql=$_SESSION['trainerSQL'];

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm>0){

		
		
			$excel->getActiveSheet()->getColumnDimension('A')->setWidth(17.5);
			$excel->getActiveSheet()->getColumnDimension('C')->setWidth(8.13);	
			$excel->getActiveSheet()->getColumnDimension('D')->setWidth(8.13);	
			$excel->getActiveSheet()->getColumnDimension('E')->setWidth(8.13);	
			$excel->getActiveSheet()->getColumnDimension('F')->setWidth(8.13);				
			
			styleCellArea(setRange("b2","g2"),"true","false",$ExWs,$excel);
			styleCellArea(setRange("b3","g3"),"true","false",$ExWs,$excel);
			styleCellArea(setRange("b4","g4"),"true","false",$ExWs,$excel);

			
			addImage(setRange("A1","B7"),$excel,"c:/report/records picture2.jpg","true",$ExWs);
			addContent(setRange("b2","g2"),$excel,"Republic of the Philippines","true",$ExWs);
			addContent(setRange("b3","g3"),$excel,"DEPARTMENT OF TRANSPORTATION","true",$ExWs);
			addContent(setRange("b4","g4"),$excel,"AND COMMUNICATIONS","true",$ExWs);
			addContent(setRange("b5","g5"),$excel,"METRO RAIL TRANSIT III EDSA LINE","true",$ExWs);
			addContent(setRange("b6","g6"),$excel,"(DOTC-MRT3)","true",$ExWs);

			$excel->getActiveSheet()->getStyle("B2:G2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B3:G3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B4:G4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B5:G5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B6:G6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			
			$rowCount=9;

			$rr[0]="A".$rowCount;
			$rr[1]="I".$rowCount;
			
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setSize(14);
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setBold(true);		
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setSize(14);


			$sql2=$_SESSION['trainerSQL2'];
			$rs2=$db->query($sql2);

			$row2=$rs2->fetch_assoc();
			addContent($rr,$excel,"Trainer Report for ","true",$ExWs);
			$rowCount++;			
			$rr[0]="A".$rowCount;
			$rr[1]="I".$rowCount;
			
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setSize(14);
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setBold(true);		
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setSize(14);
			
			addContent($rr,$excel,strtoupper($row2['lastName']).", ".$row2['firstName'],"true",$ExWs);			
			$sqlLabel=$_SESSION['trainerSQL2'];
			$rsLabel=$db->query($sqlLabel);
			$rowLabel=$rsLabel->fetch_assoc();	
			
			$rowCount++;
			$rr[0]="A".$rowCount;
			$rr[1]="B".$rowCount;
			$excel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			styleCellArea(setRange($rr[0],$rr[1]),"true","false",$ExWs,$excel);
			$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					),
				),
			);
			$excel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount)->applyFromArray($styleArray);
			
			addContent($rr,$excel,"Training Course","true",$ExWs);
			$rr[0]="C".$rowCount;
			$rr[1]="C".$rowCount;
			$excel->getActiveSheet()->getStyle("C".$rowCount.":C".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			styleCellArea(setRange($rr[0],$rr[1]),"true","false",$ExWs,$excel);
			$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					),
				),
			);
			$excel->getActiveSheet()->getStyle("C".$rowCount.":C".$rowCount)->applyFromArray($styleArray);
			addContent($rr,$excel,"Batch #","true",$ExWs);

			$rr[0]="D".$rowCount;
			$rr[1]="E".$rowCount;
			$excel->getActiveSheet()->getStyle("D".$rowCount.":E".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			styleCellArea(setRange($rr[0],$rr[1]),"true","false",$ExWs,$excel);
			$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					),
				),
			);
			$excel->getActiveSheet()->getStyle("D".$rowCount.":E".$rowCount)->applyFromArray($styleArray);
			
			addContent($rr,$excel,"Start Date","true",$ExWs);
			$rr[0]="F".$rowCount;
			$rr[1]="G".$rowCount;
			$excel->getActiveSheet()->getStyle("F".$rowCount.":G".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			styleCellArea(setRange($rr[0],$rr[1]),"true","false",$ExWs,$excel);
			$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					),
				),
			);
			$excel->getActiveSheet()->getStyle("F".$rowCount.":G".$rowCount)->applyFromArray($styleArray);
			addContent($rr,$excel,"End Date","true",$ExWs);

			$rr[0]="H".$rowCount;
			$rr[1]="I".$rowCount;
			$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			styleCellArea(setRange($rr[0],$rr[1]),"true","false",$ExWs,$excel);
			$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					),
				),
			);
			$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->applyFromArray($styleArray);
			addContent($rr,$excel,"# of Attendees","true",$ExWs);

	
			$rowCount++;
			for($m=0;$m<$nm;$m++){
				$row=$rs->fetch_assoc();	
				
					
				$rr[0]="A".$rowCount;
				$rr[1]="B".$rowCount;				
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setWrapText(true);
				addContent($rr,$excel,$row['training_title'],"true",$ExWs);
				$styleArray = array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
					),
				);
				$excel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount)->applyFromArray($styleArray);
				$rr[0]="C".$rowCount;
				$rr[1]="C".$rowCount;				
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				addContent($rr,$excel,"#".$row['batch_number'],"true",$ExWs);
				$styleArray = array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
					),
				);
				$excel->getActiveSheet()->getStyle("C".$rowCount.":C".$rowCount)->applyFromArray($styleArray);

				$rr[0]="D".$rowCount;
				$rr[1]="E".$rowCount;				
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				addContent($rr,$excel,date("F d, Y",strtotime($row['start_date'])),"true",$ExWs);
				$styleArray = array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
					),
				);
				$excel->getActiveSheet()->getStyle("D".$rowCount.":E".$rowCount)->applyFromArray($styleArray);
				
				$rr[0]="F".$rowCount;
				$rr[1]="G".$rowCount;				
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				addContent($rr,$excel,date("F d, Y",strtotime($row['end_date'])),"true",$ExWs);				
				$styleArray = array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
					),
				);
				$excel->getActiveSheet()->getStyle("F".$rowCount.":G".$rowCount)->applyFromArray($styleArray);

				$rr[0]="H".$rowCount;
				$rr[1]="I".$rowCount;				
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				addContent($rr,$excel,$row['attendees'],"true",$ExWs);	
				$styleArray = array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
					),
				);
				$excel->getActiveSheet()->getStyle("H".$rowCount.":I".$rowCount)->applyFromArray($styleArray);

				$rowCount++;
			}			
			


			save($ExWb,$excel,$newFilename); 	

		}
		
		echo "Report has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
	
		
	
	}
	
	else if($_SESSION['trainerType']=="all"){
	
		$db=new mysqli("localhost","root","","training");
		$sql=$_SESSION['trainerSQL'];


		
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		$rowCount=1;
		if($nm>0){

			
			$excel->getActiveSheet()->getColumnDimension('A')->setWidth(17.5);
			$excel->getActiveSheet()->getColumnDimension('C')->setWidth(8.13);	
			$excel->getActiveSheet()->getColumnDimension('D')->setWidth(8.13);	
			$excel->getActiveSheet()->getColumnDimension('E')->setWidth(8.13);	
			$excel->getActiveSheet()->getColumnDimension('F')->setWidth(8.13);				
			
			styleCellArea(setRange("b2","g2"),"true","false",$ExWs,$excel);
			styleCellArea(setRange("b3","g3"),"true","false",$ExWs,$excel);
			styleCellArea(setRange("b4","g4"),"true","false",$ExWs,$excel);

			
			addImage(setRange("A1","B7"),$excel,"c:/report/records picture2.jpg","true",$ExWs);
			addContent(setRange("b2","g2"),$excel,"Republic of the Philippines","true",$ExWs);
			addContent(setRange("b3","g3"),$excel,"DEPARTMENT OF TRANSPORTATION","true",$ExWs);
			addContent(setRange("b4","g4"),$excel,"AND COMMUNICATIONS","true",$ExWs);
			addContent(setRange("b5","g5"),$excel,"METRO RAIL TRANSIT III EDSA LINE","true",$ExWs);
			addContent(setRange("b6","g6"),$excel,"(DOTC-MRT3)","true",$ExWs);

			$excel->getActiveSheet()->getStyle("B2:G2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B3:G3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B4:G4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B5:G5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B6:G6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			
			$rowCount=9;

			$rr[0]="A".$rowCount;
			$rr[1]="H".$rowCount;
			

			
			//			$excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);				
			$excel->getActiveSheet()->getColumnDimension('G')->setWidth(14);			

			$excel->getActiveSheet()->getColumnDimension('H')->setWidth(16.5);			
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setBold(true);		
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setSize(14);
			
			addContent($rr,$excel,"Trainer List Report","true",$ExWs);

			$rowCount++;
			$rr[0]="A".$rowCount;
			$rr[1]="A".$rowCount;
			$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			
			addContent($rr,$excel,"Training Course","true",$ExWs);
			$rr[0]="B".$rowCount;
			$rr[1]="B".$rowCount;
			$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			addContent($rr,$excel,"Batch #","true",$ExWs);

			$rr[0]="C".$rowCount;
			$rr[1]="D".$rowCount;
			$excel->getActiveSheet()->getStyle("C".$rowCount.":D".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			
			addContent($rr,$excel,"Start Date","true",$ExWs);
			$rr[0]="E".$rowCount;
			$rr[1]="F".$rowCount;
			$excel->getActiveSheet()->getStyle("E".$rowCount.":F".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			addContent($rr,$excel,"End Date","true",$ExWs);

			$rr[0]="G".$rowCount;
			$rr[1]="G".$rowCount;
			$excel->getActiveSheet()->getStyle("G".$rowCount.":G".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			addContent($rr,$excel,"# of Participants","true",$ExWs);
			$rr[0]="H".$rowCount;
			$rr[1]="H".$rowCount;
			$excel->getActiveSheet()->getStyle("H".$rowCount.":H".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			addContent($rr,$excel,"Trainers","true",$ExWs);
	
			$rowCount++;
			$rowCountStart=$rowCount;
			$rowCountEnd=$rowCount;
			
			for($m=0;$m<$nm;$m++){
				$row=$rs->fetch_assoc();	
				$sqlLabel="select * from trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id where event_id='".$row['id']."'";
				$rsLabel=$db->query($sqlLabel);
				$nmLabel=$rsLabel->num_rows;
						
				$rowCountStart=$rowCount;
				$rowCountEnd=$rowCount*1+($nmLabel*1-1);
				for($k=0;$k<$nmLabel;$k++){
					$rowLabel=$rsLabel->fetch_assoc();

/*					if($k==0){
						$rowCountStart=$rowCount;
					}
					else if($k==($nmLabel*1-1)){
						$rowCountEnd=$rowCount;

					}
*/
					$rr[0]="H".$rowCount;
					$rr[1]="H".$rowCount;					
					$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setWrapText(true);
					addContent($rr,$excel,strtoupper($rowLabel['lastName']).", ".$rowLabel['firstName'],"false",$ExWs);
					$rowCount++;

				}
				
					
				$rr[0]="A".$rowCountStart;
				$rr[1]="A".$rowCountEnd;				
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setWrapText(true);
				addContent($rr,$excel,$row['training_title'],"true",$ExWs);

				$rr[0]="B".$rowCountStart;
				$rr[1]="B".$rowCountEnd;				
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				addContent($rr,$excel,"#".$row['batch_number'],"true",$ExWs);

				$rr[0]="C".$rowCountStart;
				$rr[1]="D".$rowCountEnd;				
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				addContent($rr,$excel,date("F d, Y",strtotime($row['start_date'])),"true",$ExWs);
				
				$rr[0]="E".$rowCountStart;
				$rr[1]="F".$rowCountEnd;				
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				addContent($rr,$excel,date("F d, Y",strtotime($row['end_date'])),"true",$ExWs);				

				$rr[0]="G".$rowCountStart;
				$rr[1]="G".$rowCountEnd;				
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				addContent($rr,$excel,$row['attendees'],"true",$ExWs);	
				$rowCount=$rowCountEnd;
				$rowCount++;
			}

	



			
			
			save($ExWb,$excel,$newFilename); 	

		}	
	
		echo "Report has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
	
	
	}






}
?>
