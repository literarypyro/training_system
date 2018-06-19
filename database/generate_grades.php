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
	$programId=$row['program_id'];
	
	
	$start=date("Y-m-d",strtotime($row['start_date']));
	$end=date("Y-m-d",strtotime($row['end_date']));
	
	if($start==$end){
		$time=$endDate;
	
	}
	else {
		$time=$startDate." - ".$endDate;
	}
	
	$count=$nm;
	
	$filename="manual/grading template.xls";

	$datePrintout=date("Ymd H-i-s");
	
	$newFilename="printout/Grading Report ".$datePrintout.".xls";
	copy($filename,$newFilename);
	


	$workSheetName="Evaluation List";
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);
		
	
	
 	$ExWs=createWorksheet($excel,$workSheetName,"create");		

	
//	addContent(setRange("A3","AP3"),$excel,strtoupper("Grades of ".$trainingTitle." Batch ".$batchNumber),"true",$ExWs);
//	addContent(setRange("A4","AP4"),$excel,strtoupper($time),"true",$ExWs);
	


	addContent(setRange("E6","E6"),$excel,"Non-Tech Ave.","true",$ExWs);
	addContent(setRange("F6","F6"),$excel,"Rank","true",$ExWs);
	
	
	$initialPrefix="";
	
	$columnCount=66;
	$columnCount++;
	$columnCount++;
	$columnCount++;
	$columnCount++;
	$columnCount++;

	
	$colDb=retrieveGradingDb();
	$colSQL="select * from nontech inner join nontech_fields on nontech.field=nontech_fields.id	where program_id='".$programId."'";
	$colRs=$colDb->query($colSQL);
//	$colRow=$colRs->fetch_assoc();
	$rrNo=$colRs->num_rows;
	$cellPrefix=$columnCount;
	$start=chr($cellPrefix*1-2)."5";	


	$i=0;



	
	
	for($n=0;$n<$rrNo;$n++){
			$RR=3;
			$RR=3+$n;
			$cellPrefix=$columnCount*1+($i*1);
			$colRow=$colRs->fetch_assoc();
			if($cellPrefix>90){
				$i=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=66;
				
				$cellPrefix=$columnCount*1+($i*1);
			}
			else {
				$i++;
			}
			$prefix=$initialPrefix.chr($cellPrefix);	

			addContent(setRange($prefix."6",$prefix."6"),$excel,$colRow['field'],"true",$ExWs);
			$cellPrefix++;
			
			if($cellPrefix>90){
				$i=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=66;
				
				$cellPrefix=$columnCount*1+($i*1);
			}
			else {
				$i++;
			}

			$prefix=$initialPrefix.chr($cellPrefix);	
		
			addContent(setRange($prefix."6",$prefix."6"),$excel,"RANK","true",$ExWs);

			
	}
	$end=$prefix."5";

	addContent(setRange($start,$end),$excel,"Non Technical Evaluation","true",$ExWs);
	$excel->getActiveSheet()->getStyle($start.":".$end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	//$i++;
	$cellPrefix=$columnCount*1+($i*1);

	$prefix=$initialPrefix.chr($cellPrefix);	
	
	$start=$prefix."5";	
	
	addContent(setRange($prefix."6",$prefix."6"),$excel,"Tech Ave.","true",$ExWs);

	$i++;
	$cellPrefix=$columnCount*1+($i*1);

	$prefix=$initialPrefix.chr($cellPrefix);	
	addContent(setRange($prefix."6",$prefix."6"),$excel,"RANK","true",$ExWs);

	$colDb=retrieveGradingDb();
	$colSQL="select * from tech where program_id='".$programId."' order by rank";
	$colRs=$colDb->query($colSQL);
	
	$colNM=$colRs->num_rows;
	$i++;	

	for($n=0;$n<$colNM;$n++){
		
		$colRow=$colRs->fetch_assoc();
			$cellPrefix=$columnCount*1+($i*1);
			if($cellPrefix>90){
				$i=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=65;

				$cellPrefix=$columnCount*1+($i*1);
				$i++;
			}
			else {
				$i++;
			}

			$prefix=$initialPrefix.chr($cellPrefix);	

			addContent(setRange($prefix."6",$prefix."6"),$excel,$colRow['field'],"true",$ExWs);

			$cellPrefix++;

			if($cellPrefix>90){
				$i=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=65;
				
				$cellPrefix=$columnCount*1+($i*1);
				
				$i++;
			}
			else {
				$i++;
			}			
			
			
			$prefix=$initialPrefix.chr($cellPrefix);	
		
			addContent(setRange($prefix."6",$prefix."6"),$excel,"RANK","true",$ExWs);
			$cellPrefix++;
	
	}
	$end=$prefix."5";		
	addContent(setRange($start,$end),$excel,"Technical Evaluation","true",$ExWs);
	$excel->getActiveSheet()->getStyle($start.":".$end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	


	if($programId==3){
		$cellPrefix--;
//		$cellPrefix=$columnCount*1+($i*1);
		$columnCount=$cellPrefix+1;
		$cellPrefix=$columnCount;
		
		$prefix=$initialPrefix.chr($cellPrefix);	
		
		$start=$prefix."5";	
		
		addContent(setRange($prefix."6",$prefix."6"),$excel,"General Average","true",$ExWs);

		$i++;
		$cellPrefix=$columnCount*1+1;

		$prefix=$initialPrefix.chr($cellPrefix);	
		addContent(setRange($prefix."6",$prefix."6"),$excel,"RANK","true",$ExWs);		

		$colDb=retrieveGradingDb();
		$colSQL="select * from practical where program_id='".$programId."' order by rank";

		$colRs=$colDb->query($colSQL);
	
		$colNM=$colRs->num_rows;
		$i++;				
		$k=0;

		$columnCount++;
		$columnCount++;
		for($n=0;$n<$colNM;$n++){

			$colRow=$colRs->fetch_assoc();
			$cellPrefix=$columnCount*1+($k*1);

			if($cellPrefix>90){
				$k=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=65;

				$cellPrefix=$columnCount*1+($k*1);
				$k++;
			}
			else {
				$k++;
			}

			$prefix=$initialPrefix.chr($cellPrefix);	

			addContent(setRange($prefix."6",$prefix."6"),$excel,$colRow['fields'],"true",$ExWs);
			
			$cellPrefix++;

			if($cellPrefix>90){
				$k=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=65;
				
				$cellPrefix=$columnCount*1+($k*1);
				
				$k++;
			}
			else {
				$k++;
			}			
			
			
			$prefix=$initialPrefix.chr($cellPrefix);	
		
			addContent(setRange($prefix."6",$prefix."6"),$excel,"RANK","true",$ExWs);
			$cellPrefix++;

		}	
	
		
		
		$end=$prefix."5";		
		addContent(setRange($start,$end),$excel,"Practical Evaluation","true",$ExWs);
		$excel->getActiveSheet()->getStyle($start.":".$end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	}
	else {
			$pref=$initialPrefix.chr($cellPrefix);	


			$cP2=$cellPrefix+2;
			$z=0;
			if($cP2>90){
				$z=0;
				$initial="A";
				$cP2=65+($z*1);
			
				$z++;
			}
			else {
				$z++;
			
			}
			$pref2=$initial.chr($cP2);
			
			addContent(setRange($pref."4",$pref2."5"),$excel,"ON THE JOB TRAINING","true",$ExWs);	
			$excel->getActiveSheet()->getStyle($pref."4:".$pref2."5")->getAlignment()->setWrapText(true);					

			addContent(setRange($pref."6",$pref."6"),$excel,"GRADE","true",$ExWs);
			$excel->getActiveSheet()->getStyle($pref."6",$pref."6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$cellPrefix++;
			$initial="";
			$z=0;
			if($cellPrefix>90){
				$z=0;
				$initial="A";
				$cellPrefix=65+($z*1);
			
				$z++;
			}
			else {
				$z++;
			
			}			
				$pref=$initial.chr($cellPrefix);

			addContent(setRange($pref."6",$pref."6"),$excel,"EQUIVALENT GRADE","true",$ExWs);
			$cellPrefix++;
			$initial="";
			$z=0;
			if($cellPrefix>90){
				$z=0;
				$initial="A";
				$cellPrefix=65+($z*1);
			
				$z++;
			}
			else {
				$z++;
			
			}		
				$pref=$initial.chr($cellPrefix);				
			

			addContent(setRange($pref."6",$pref."6"),$excel,"RANK","true",$ExWs);
	}

		$sqlTrainee="select * from class_instance where training_event_id='".$event_id."'";
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;
	
	$rowCount=7;
	//$rowCount++;
	
	$start=$rowCount;
	$end=$rowCount*1+($nmTrainee*1-1);

	
	for($i=0;$i<$nmTrainee;$i++){

//	for($i=0;$i<3;$i++){
		$nonTechAve="=AVERAGE(";
		
		$rowTrainee=$rsTrainee->fetch_assoc();
		$name=$rowTrainee['lastName'].", ".$rowTrainee['firstName'];
		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$name,"true",$ExWs);

		addContent(setRange("B".$rowCount,"B".$rowCount),$excel,"=IF(D".$rowCount.">=2.99,\"Passed\",\"Failed\")","true",$ExWs);
		$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		addContent(setRange("C".$rowCount,"C".$rowCount),$excel,"=RANK(D".$rowCount.",D".$start.":D".$end.",0)","true",$ExWs);


		$columnCount=69;
		$colDb=retrieveGradingDb();
		$colSQL="select * from nontech inner join nontech_fields on nontech.field=nontech_fields.id	where program_id='".$programId."'";
		$colRs=$colDb->query($colSQL);		
		$rrNo=$colRs->num_rows;
		//$i=0;
		$k=0;
		$columnCount+=2;
		$rankPrefix=$columnCount-1;
		$pref=chr($rankPrefix);
		$pref2=chr($columnCount);
	
		addContent(setRange($pref.$rowCount,$pref.$rowCount),$excel,"=RANK(".$pref2.$rowCount.",".$pref2.$start.":".$pref2.$end.",0)","true",$ExWs);

		
		$nonTechPrefix=chr(69);

		$initialPrefix="";
		for($m=0;$m<$rrNo;$m++){
			$colRow=$colRs->fetch_assoc();
			$RR=3;
			$RR=3+$m;
			$cellPrefix=$columnCount*1+($k*1);
			
			if($cellPrefix>90){
				$k=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=66;
				
				$cellPrefix=$columnCount*1+($k*1);
			}
			else {
				//$k++;
			}

			$prefix2=$initialPrefix.chr($cellPrefix);				
			
			$cellPrefix++;

			if($cellPrefix>90){
				$k=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=66;
				
				$cellPrefix=$columnCount*1+($k*1);
			}
			else {
				$k++;
			}
			
			$prefix=$initialPrefix.chr($cellPrefix);	
			$columnCount++;

			//addContent(setRange($prefix.$rowCount,$prefix.$rowCount),$excel,"RANK","true",$ExWs);
			if($m==0){
				$nonTechAve.=$prefix2.$rowCount;
			}
			else {
				if($m==($rrNo-1)){
					if($colRow['field']=="ORAL"){
						$nonTechAve.=")+".$prefix2.$rowCount;

					}
					else {
						$nonTechAve.=",".$prefix2.$rowCount;
						$nonTechAve.=")";


					}
				}
				else {
					$nonTechAve.=",".$prefix2.$rowCount;
				}				
			}
			addContent(setRange($prefix.$rowCount,$prefix.$rowCount),$excel,"=RANK(".$prefix2.$rowCount.",".$prefix2.$start.":".$prefix2.$end.",0)","true",$ExWs);

			

		
		}

//		$nonTechAve.=")";
		addContent(setRange($nonTechPrefix.$rowCount,$nonTechPrefix.$rowCount),$excel,$nonTechAve,"true",$ExWs);

		$colDb=retrieveGradingDb();
		$colSQL="select * from tech where program_id='".$programId."' order by rank";

		$colRs=$colDb->query($colSQL);
	
		$colNM=$colRs->num_rows;
		$i++;				
		$k=0;
//		echo $columnCount;
//		echo $rrNo;
//		$columnCount=87;	
//		echo $columnCount;
		$columnCount+=$rrNo;

		$techPrefix=chr($columnCount);
		
		$initialPrefix="";		
		$techAve="=AVERAGE(";

		for($n=0;$n<=$colNM;$n++){
		$colRow=$colRs->fetch_assoc();
			$cellPrefix=$columnCount*1+($k*1);

			if($cellPrefix>90){
				$k=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=65;

				$cellPrefix=$columnCount*1+($k*1);
				$k++;
			}
			else {
				$k++;
			}
			
			$prefix2=$initialPrefix.chr($cellPrefix);
			
			$cellPrefix++;

			if($cellPrefix>90){
				$k=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=65;
				
				$cellPrefix=$columnCount*1+($k*1);
				
				$k++;
			}
			else {
				$k++;
			}			
			
			
			$prefix=$initialPrefix.chr($cellPrefix);	


			addContent(setRange($prefix.$rowCount,$prefix.$rowCount),$excel,"=RANK(".$prefix2.$rowCount.",".$prefix2.$start.":".$prefix2.$end.",0)","true",$ExWs);

			if($n==0){

			}
			else if($n==1){
				$techAve.=$prefix2.$rowCount;
			}
			else {
				$techAve.=",".$prefix2.$rowCount;
			}			
		}	
		
		$techAve.=")";

		
		if($programId==3){
			//$columnCount=$cellPrefix;
			$colDb=retrieveGradingDb();
			$colSQL="select * from practical where program_id='".$programId."' order by rank";

			$colRs=$colDb->query($colSQL);
		
			$colNM=$colRs->num_rows;
			$i++;				
			$k=0;
			$rrNo=$colNM;
	//		echo $columnCount;
	//		echo $rrNo;
	//		$columnCount=87;	
	//		echo $columnCount;
			$columnCount+=$rrNo;

			$techPrefix=chr($columnCount);
			
			//	$initialPrefix="";		
				$techAve="=AVERAGE(";

				for($n=0;$n<=$colNM;$n++){
				$colRow=$colRs->fetch_assoc();
					$cellPrefix=$columnCount*1+($k*1);
					if($cellPrefix>90){
						$k=0;
						//$prefixIter+=1;
						$initialPrefix="A";
						$columnCount=65;

						$cellPrefix=$columnCount*1+($k*1);
						$k++;
					}
					else {
						$k++;
					}
					
					$prefix2=$initialPrefix.chr($cellPrefix);

					
					$cellPrefix++;

					if($cellPrefix>90){
						$k=0;
						//$prefixIter+=1;
						$initialPrefix="A";
						$columnCount=65;
						
						$cellPrefix=$columnCount*1+($k*1);
						
						$k++;
					}
					else {
						$k++;
					}			
					
					
					$prefix=$initialPrefix.chr($cellPrefix);	


					addContent(setRange($prefix.$rowCount,$prefix.$rowCount),$excel,"=RANK(".$prefix2.$rowCount.",".$prefix2.$start.":".$prefix2.$end.",0)","true",$ExWs);

					if($n==0){

					}
					else if($n==1){
						$techAve.=$prefix2.$rowCount;
					}
					else {
						$techAve.=",".$prefix2.$rowCount;
					}			
				}	
				
				$techAve.=")";		
				
				$techPrefix=$initialPrefix.$techPrefix;
				addContent(setRange($techPrefix.$rowCount,$techPrefix.$rowCount),$excel,$techAve,"true",$ExWs);
				
		}
		else {
			$cellPrefix++;
			$cellPrefix++;
			$z=0;
			$initial="";			

			
			if($cellPrefix>90){
				$initial="A";
				$col=65;
						
				$cellPrefix=$col*1+($z*1);				
				$z++;
			
			}
			else {
				$z++;
			}

			$pref2=$initial.chr($cellPrefix);
			
			
			$cellPrefix++;
			$z=0;
			if($cellPrefix>90){
				$initial="A";
				$col=65;
						
				$cellPrefix=$col*1+($z*1);				
				$z++;
			
			}
			else {
				$z++;
			}

			$pref=$initial.chr($cellPrefix);
			
					addContent(setRange($pref.$rowCount,$pref.$rowCount),$excel,"=RANK(".$pref2.$rowCount.",".$pref2.$start.":".$pref2.$end.",0)","true",$ExWs);


			
			/*

			
			
			echo $cellPrefix;
*/
		}			
		/**	
			echo $prefix;
			*/
			/*
			if($cellPrefix>90){
				$z=0;
				$initial="A";
				$cP2=65+($z*1);
			
				$z++;
			}
			else {
				$z++;
			
			}	
			$pref=$initial.chr($cellPrefix);	
			//$prefix=$initialPrefix.chr($columnCount+2);
	
			*/
			/*		
			$cellPrefix2=$cellPrefix+2;
			$k=0;			

				if($cellPrefix2>90){
						$k=0;
						//$prefixIter+=1;
						$initialPrefix="A";
						
						$cellPrefix2=65+($k*1);
						
						$k++;
					}
					else {
						$k++;
					}				
			
			
			$pref2=$initialPrefix.chr($cellPrefix2);

	
*/
	
			
			
			



	//	echo "ponponpon";
		

		
		/*
		
					$cellPrefix=$columnCount;


			*/
		$titleStart=$nonTechPrefix;
		$k=0;
		$cellPrefix=$columnCount+($colNM-1);
		if($cellPrefix>90){
			$k=0;
			//$prefixIter+=1;
			$initialPrefix="A";
			$columnCount=65;
		
			$cellPrefix=$columnCount*1+($k*1);
			
			$k++;
		}
		else {
			$k++;
		}			
			
			
		$prefix=$initialPrefix.chr($cellPrefix);			
		
		
		$titleEnd=$prefix;
		
	
		

		addContent(setRange($titleStart."4",$titleEnd."4"),$excel,"TRAINING MODULE EVALUATION","true",$ExWs);
		$excel->getActiveSheet()->getStyle($titleStart."4:".$titleEnd."4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$columnCount=$cellPrefix+1;
		$prefix=$initialPrefix.chr($columnCount);
		$columnCount2=$columnCount+2;
		$prefix2=$initialPrefix.chr($columnCount2);
		

		addContent(setRange("B2","T2"),$excel,"GRADES OF ".strtoupper($trainingTitle)." BATCH ".$batchNumber,"true",$ExWs);		

		addContent(setRange("B3","T3"),$excel,$time,"true",$ExWs);		


		
		
/*
		$colDb=new mysqli("localhost","root","","grading_system");
		$colSQL="select * from tech where program_id='".$programId."' order by rank";
		$colRs=$colDb->query($colSQL);
	
		$colNM=$colRs->num_rows;
		$i++;				
		
		for($n=0;$n<$colNM;$n++){
		
		$colRow=$colRs->fetch_assoc();
			$cellPrefix=$columnCount*1+($i*1);
			if($cellPrefix>90){
				$i=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=65;

				$cellPrefix=$columnCount*1+($i*1);
				$i++;
			}
			else {
				$i++;
			}

			$prefix=$initialPrefix.chr($cellPrefix);	

			//addContent(setRange($prefix.$rowCount,$prefix.$rowCount),$excel,$colRow['field'],"true",$ExWs);

			$cellPrefix++;

			if($cellPrefix>90){
				$i=0;
				//$prefixIter+=1;
				$initialPrefix="A";
				$columnCount=65;
				
				$cellPrefix=$columnCount*1+($i*1);
				
				$i++;
			}
			else {
				$i++;
			}			
			
			
			$prefix=$initialPrefix.chr($cellPrefix);	
		
			addContent(setRange($prefix.$rowCount,$prefix.$rowCount),$excel,"RANK","true",$ExWs);
			$cellPrefix++;
	
		}	
*/		
/**

		for($n=0;$n<$rrNo;$n++){
		

		}
*/		
	/*	
	
*/		
		
	//	$excel->getActiveSheet()->getStyle('A'.$rowCount)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	//	$excel->getActiveSheet()->getStyle('A'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	//	$excel->getActiveSheet()->getStyle('A'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	//	$excel->getActiveSheet()->getStyle('A'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

	//	$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setSize(13);		
	//	$excel->getActiveSheet()->getStyle("A".$rowCount)->getFont()->setName('Arial');


	//	$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(18);
		

		
		
	//	$excel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);		

//		$excel->getActiveSheet()->getStyle('B'.$rowCount)->getBorders()->getTop()->setBorder//Style(PHPExcel_Style_Border::BORDER_THIN);
//		$excel->getActiveSheet()->getStyle('B'.$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//		$excel->getActiveSheet()->getStyle('B'.$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//		$excel->getActiveSheet()->getStyle('B'.$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);		
		




/*
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
	
	$rowCount++;	
	
	}
	

/*
//		echo $columnCount;
//		echo $rrNo;
//		$columnCount=87;	
//		echo $columnCount;
//		$columnCount+=$rrNo;

				
		
			*/
/*

	*/
	//	}
	
	//}	
	


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
