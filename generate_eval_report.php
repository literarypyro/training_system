<?php 
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");

?>
<?php

/**

	$event_id=$_GET['view'];	
	
	
	
	
	$db=new mysqli("localhost","root","","training");
	
	
	$sql="select * from training_instance where id='".$event_id."'";
	
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$row=$rs->fetch_assoc();
	
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


	$datePrintout=date("Ymd H-i-s");
	
	$newFilename="printout/Trainer Evaluation Report ".$datePrintout.".xls";
	
	$workSheetName="Trainer Evaluation Report";	
	$workbookname=$newFilename;
	$excel=startCOMGiven();
	
	

	
		
		$ExWs=createWorksheet($excel,$workSheetName,"create");	
	

	
				addImage(setRange("A1","B7"),$excel,"c:/report/records picture2.jpg","true",$ExWs);
			addContent(setRange("b2","g2"),$excel,"Republic of the Philippines","true",$ExWs);
			addContent(setRange("b3","g3"),$excel,"DEPARTMENT OF TRANSPORTATION","true",$ExWs);
			addContent(setRange("b4","g4"),$excel,"AND COMMUNICATIONS","true",$ExWs);
			addContent(setRange("b5","g5"),$excel,"METRO RAIL TRANSIT III EDSA LINE","true",$ExWs);
			addContent(setRange("b6","g6"),$excel,"(DOTC-MRT3)","true",$ExWs);	
	
	
	
	//	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);
	

	

/**
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
