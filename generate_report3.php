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
if(($_SESSION['training_page']=="search_schedule2.php")||($_SESSION['training_page']=="search_schedule.php")){
	
	$datePrintout=date("Ymd H-i-s");
	
	$newFilename="printout/Participants Report ".$datePrintout.".xls";
	
	$workSheetName="Participants Report";	
	$workbookname=$newFilename;
	$excel=startCOMGiven();
 	$ExWs=createWorksheet($excel,$workSheetName,"create");	


				
			$excel->getActiveSheet()->getColumnDimension('A')->setWidth(22);
			$excel->getActiveSheet()->getColumnDimension('B')->setWidth(7);				
			$excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);	
			$excel->getActiveSheet()->getColumnDimension('D')->setWidth(9);	
			$excel->getActiveSheet()->getColumnDimension('E')->setWidth(18);	
			$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20.88);				
//			$excel->getActiveSheet()->getColumnDimension('G')->setWidth(14.63);				
//			$excel->getActiveSheet()->getColumnDimension('H')->setWidth(14.63);				
			
			styleCellArea(setRange("b2","e2"),"true","false",$ExWs,$excel);
			styleCellArea(setRange("b3","e3"),"true","false",$ExWs,$excel);
			styleCellArea(setRange("b4","e4"),"true","false",$ExWs,$excel);

			
		//	addImage(setRange("A1","B7"),$excel,"c:/report/records picture2.jpg","true",$ExWs);
			addContent(setRange("b2","e2"),$excel,"Republic of the Philippines","true",$ExWs);
			addContent(setRange("b3","e3"),$excel,"DEPARTMENT OF TRANSPORTATION","true",$ExWs);
			addContent(setRange("b4","e4"),$excel,"AND COMMUNICATIONS","true",$ExWs);
			addContent(setRange("b5","e5"),$excel,"METRO RAIL TRANSIT III EDSA LINE","true",$ExWs);
			addContent(setRange("b6","e6"),$excel,"(DOTC-MRT3)","true",$ExWs);

			$excel->getActiveSheet()->getStyle("B2:E2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B3:E3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B4:E4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B5:E5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle("B6:E6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			
			$rowCount=9;
			$rowCount++;
			$rr[0]="A".$rowCount;
			$rr[1]="F".$rowCount;
			
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setSize(14);
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setBold(true);		
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setSize(14);

			addContent($rr,$excel,"Participants Report","true",$ExWs);

			$rowCount++;
			//For the Title
			//$db=new mysqli("localhost","root","","training");	
			$db=retrieveTrainingDb();	
			if($_GET['sT']=='tr'){
				$nID=$_GET['D'];

				$ret="select * from trainee_list where id='".$nID."'";
				$retRs=$db->query($ret);
				$retRow=$retRs->fetch_assoc();

				
				
				$sql="SELECT * FROM training_instance inner join class_instance on class_instance.training_event_id=training_instance.id where class_instance.traineeId in				
				(select id from trainee_list where lastName='".$retRow['lastName']."' and firstName='".$retRow['firstName']."')	";



				
				$rs=$db->query($sql);
				$row=$rs->fetch_assoc();
				$title="For ".strtoupper($row['lastName']).", ".$row['firstName'];	

			}	
			else if($_GET['sT']=="evt"){
				$nID=$_GET['D'];
				
		
				$sql="SELECT * FROM training_instance inner join class_instance on class_instance.training_event_id=training_instance.id where training_instance.id='".$nID."'";

				$rs=$db->query($sql);
				$row=$rs->fetch_assoc();

				$title=ucwords($row['training_title']).", #".$row['batch_number'];	
			}
			else if($_GET['sT']=="prg"){
				$nID=$_GET['D'];
				$sql="SELECT * FROM training_instance inner join class_instance on class_instance.training_event_id=training_instance.id where training_instance.program_id='".$nID."' order by batch_number";

				$rs=$db->query($sql);
				$row=$rs->fetch_assoc();
				$title=ucwords($row['training_title']);	
			}
			$rr[0]="A".$rowCount;
			$rr[1]="F".$rowCount;
			
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setSize(14);
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setBold(true);		
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle($rr[0].":".$rr[1])->getFont()->setSize(14);

			addContent($rr,$excel,$title,"true",$ExWs);
			
			
			
			
			$rowCount++;
			
			$rr[0]="A".$rowCount;
			$rr[1]="A".$rowCount;
			$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			styleCellArea(setRange($rr[0],$rr[1]),"true","false",$ExWs,$excel);
			
			
			$alternate="Participant";
			if($_GET['sT']=='tr'){
				$alternate="Training Course";
			}
				
			addContent($rr,$excel,$alternate,"true",$ExWs);
			$rr[0]="B".$rowCount;
			$rr[1]="B".$rowCount;
			$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			styleCellArea(setRange($rr[0],$rr[1]),"true","false",$ExWs,$excel);
			$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->getAlignment()->setWrapText(true);
			addContent($rr,$excel,"Batch #","true",$ExWs);

			$rr[0]="C".$rowCount;
			$rr[1]="D".$rowCount;
			$excel->getActiveSheet()->getStyle("C".$rowCount.":D".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			styleCellArea(setRange($rr[0],$rr[1]),"true","false",$ExWs,$excel);

			
			addContent($rr,$excel,"Period","true",$ExWs);
			$rr[0]="E".$rowCount;
			$rr[1]="E".$rowCount;
			$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			styleCellArea(setRange($rr[0],$rr[1]),"true","false",$ExWs,$excel);
			$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setWrapText(true);
			addContent($rr,$excel,"Training Certificate","false",$ExWs);

			$rr[0]="F".$rowCount;
			$rr[1]="F".$rowCount;
			$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			styleCellArea(setRange($rr[0],$rr[1]),"true","false",$ExWs,$excel);
			$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setWrapText(true);

			addContent($rr,$excel,"Request for Certification","false",$ExWs);
			$rowCount++;	
			$db=retrieveTrainingDb();	
		//	$db=new mysqli("localhost","root","","training");			
			if($_GET['sT']=="tr"){
				$nID=$_GET['D'];
				
				$ret="select * from trainee_list where id='".$nID."'";
				$retRs=$db->query($ret);
				$retRow=$retRs->fetch_assoc();

				
				
				$sql="SELECT * FROM training_instance inner join class_instance on class_instance.training_event_id=training_instance.id where class_instance.traineeId in				
				(select id from trainee_list where lastName='".$retRow['lastName']."' and firstName='".$retRow['firstName']."')	";

				
//				$sql="SELECT * FROM training_instance inner join class_instance on class_instance.training_event_id=training_instance.id where class_instance.traineeId='".$nID."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				
				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();
					$rr[0]="A".$rowCount;
					$rr[1]="A".$rowCount;
//					$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setWrapText(true);					
					$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					addContent($rr,$excel,$row['training_title'],"false",$ExWs);
					$rr[0]="B".$rowCount;
					$rr[1]="B".$rowCount;
					$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					addContent($rr,$excel,$row['batch_number'],"true",$ExWs);
					$rr[0]="C".$rowCount;
					$rr[1]="D".$rowCount;
					$excel->getActiveSheet()->getStyle("C".$rowCount.":D".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sDate=date("M. d",strtotime($row['start_date']));
					$eDate=date("M. d, Y",strtotime($row['end_date']));
					$excel->getActiveSheet()->getStyle("C".$rowCount.":D".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					
					addContent($rr,$excel,$sDate." - ".$eDate,"true",$ExWs);					
					
					$diplomaSQL="select * from diploma_release where trainee_id='".$nID."' and training_program_id='".$row['training_event_id']."' order by release_date desc limit 1";

					$diplomaRS=$db->query($diplomaSQL);
					$diplomaNM=$diplomaRS->num_rows;
					if($diplomaNM>0){
						$diplomaRow=$diplomaRS->fetch_assoc();
						$diplomaDate=date("M. d, Y",strtotime($diplomaRow['release_date']));
						if($diplomaRow['status']=="Issuance"){
							$status="Issued, ".$diplomaDate;
						
						}
						else {
							$status="Re-Issued, ".$diplomaDate;
						
						}
						
					
					}
					else {
						$status="Un-Issued";
					
					
					}

					$rr[0]="E".$rowCount;
					$rr[1]="E".$rowCount;
					$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setWrapText(true);
					addContent($rr,$excel,$status,"false",$ExWs);
					$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					$diplomaSQL="select * from certificate_request where trainee_id='".$nID."' and training_program_id='".$row['training_event_id']."' order by request_date desc limit 1";

					$diplomaRS=$db->query($diplomaSQL);
					$diplomaNM=$diplomaRS->num_rows;

					if($diplomaNM>0){
						$diplomaRow=$diplomaRS->fetch_assoc();
						$diplomaDate=date("M. d, Y",strtotime($diplomaRow['request_date']));
						if($diplomaRow['status']=="Claimed"){
							$status="For: ".$diplomaRow['type'].", ".$diplomaDate;
							$status.=", Claimed: ".date("M. d, Y",strtotime(str_replace(", Claimed: ","",$diplomaRow['remarks']))); 
						}
						else {
							$status="For: ".$diplomaRow['type'].", ".$diplomaDate;
							$status.=", Unclaimed";
						}
						
					
					}
					else {
						$status="No Requests Made";
					
					
					}					
					
					$rr[0]="F".$rowCount;
					$rr[1]="F".$rowCount;
					$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setWrapText(true);
					addContent($rr,$excel,$status,"false",$ExWs);
					$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					$rowCount++;
					
				}
			
			}
			else if($_GET['sT']=="evt"){
				$nID=$_GET['D'];
				$sql="SELECT * FROM training_instance inner join class_instance on class_instance.training_event_id=training_instance.id where training_instance.id='".$nID."'";

				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();
					$rr[0]="A".$rowCount;
					$rr[1]="A".$rowCount;
					$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setWrapText(true);					
					$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					addContent($rr,$excel,strtoupper($row['lastName']).", ".$row['firstName'],"false",$ExWs);
					$rr[0]="B".$rowCount;
					$rr[1]="B".$rowCount;
					$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					addContent($rr,$excel,$row['batch_number'],"true",$ExWs);

					$rr[0]="C".$rowCount;
					$rr[1]="D".$rowCount;
					$excel->getActiveSheet()->getStyle("C".$rowCount.":D".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sDate=date("M. d",strtotime($row['start_date']));
					$eDate=date("M. d, Y",strtotime($row['end_date']));
					$excel->getActiveSheet()->getStyle("C".$rowCount.":D".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					
					addContent($rr,$excel,$sDate." - ".$eDate,"true",$ExWs);	

					$diplomaSQL="select * from diploma_release where trainee_id='".$row['traineeId']."' and training_program_id='".$nID."' order by release_date desc limit 1";

					$diplomaRS=$db->query($diplomaSQL);
					$diplomaNM=$diplomaRS->num_rows;
					if($diplomaNM>0){
						$diplomaRow=$diplomaRS->fetch_assoc();
						$diplomaDate=date("M. d, Y",strtotime($diplomaRow['release_date']));
						if($diplomaRow['status']=="Issuance"){
							$status="Issued, ".$diplomaDate;
						
						}
						else {
							$status="Re-Issued, ".$diplomaDate;
						
						}
						
					
					}
					else {
						$status="Un-Issued";
					
					
					}

					$rr[0]="E".$rowCount;
					$rr[1]="E".$rowCount;
					$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setWrapText(true);
					addContent($rr,$excel,$status,"false",$ExWs);
					$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					$diplomaSQL="select * from certificate_request where trainee_id='".$row['traineeId']."' and training_program_id='".$nID."' order by request_date desc limit 1";

					$diplomaRS=$db->query($diplomaSQL);
					$diplomaNM=$diplomaRS->num_rows;

					if($diplomaNM>0){
						$diplomaRow=$diplomaRS->fetch_assoc();
						$diplomaDate=date("M. d, Y",strtotime($diplomaRow['request_date']));
						if($diplomaRow['status']=="Claimed"){
							$status="For: ".$diplomaRow['type'].", ".$diplomaDate;
							$status.=", Claimed: ".date("M. d, Y",strtotime(str_replace(", Claimed: ","",$diplomaRow['remarks']))); 
						}
						else {
							$status="For: ".$diplomaRow['type'].", ".$diplomaDate;
							$status.=", Unclaimed";
						}
					}
					else {
						$status="No Requests Made";
					
					
					}					
					
					$rr[0]="F".$rowCount;
					$rr[1]="F".$rowCount;
					$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setWrapText(true);
					addContent($rr,$excel,$status,"false",$ExWs);
					$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					$rowCount++;
				}			
			}
			else if($_GET['sT']=="prg"){
				$nID=$_GET['D'];
				$sql="SELECT * FROM training_instance inner join class_instance on class_instance.training_event_id=training_instance.id where training_instance.program_id='".$nID."' order by batch_number";

				$rs=$db->query($sql);
				$nm=$rs->num_rows;


				$batchInteger=0;
				$rowFirst="";

				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();
					$rr[0]="A".$rowCount;
					$rr[1]="A".$rowCount;
					$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setWrapText(true);					
					$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					addContent($rr,$excel,strtoupper($row['lastName']).", ".$row['firstName'],"false",$ExWs);
					$rr[0]="B".$rowCount;
					$rr[1]="B".$rowCount;
					$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle("B".$rowCount.":B".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					
					if($rowFirst==$row['batch_number']){
					
					}
					else {
						$rowFirst=$row['batch_number'];
						addContent($rr,$excel,$row['batch_number'],"true",$ExWs);
					}

					$rr[0]="C".$rowCount;
					$rr[1]="D".$rowCount;
					$excel->getActiveSheet()->getStyle("C".$rowCount.":D".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sDate=date("M. d",strtotime($row['start_date']));
					$eDate=date("M. d, Y",strtotime($row['end_date']));
					$excel->getActiveSheet()->getStyle("C".$rowCount.":D".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					
					addContent($rr,$excel,$sDate." - ".$eDate,"true",$ExWs);	

					$diplomaSQL="select * from diploma_release where trainee_id='".$row['traineeId']."' and training_program_id='".$row['training_event_id']."' order by release_date desc limit 1";

					$diplomaRS=$db->query($diplomaSQL);
					$diplomaNM=$diplomaRS->num_rows;
					if($diplomaNM>0){
						$diplomaRow=$diplomaRS->fetch_assoc();
						$diplomaDate=date("M. d, Y",strtotime($diplomaRow['release_date']));
						if($diplomaRow['status']=="Issuance"){
							$status="Issued, ".$diplomaDate;
						
						}
						else {
							$status="Re-Issued, ".$diplomaDate;
						
						}
						
					
					}
					else {
						$status="Un-Issued";
					
					
					}

					$rr[0]="E".$rowCount;
					$rr[1]="E".$rowCount;
					$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setWrapText(true);
					addContent($rr,$excel,$status,"false",$ExWs);
					$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					$diplomaSQL="select * from certificate_request where trainee_id='".$row['traineeId']."' and training_program_id='".$row['training_event_id']."' order by request_date desc limit 1";

					$diplomaRS=$db->query($diplomaSQL);
					$diplomaNM=$diplomaRS->num_rows;

					if($diplomaNM>0){
						$diplomaRow=$diplomaRS->fetch_assoc();
						$diplomaDate=date("M. d, Y",strtotime($diplomaRow['request_date']));
						if($diplomaRow['status']=="Claimed"){
							$status="For: ".$diplomaRow['type'].", ".$diplomaDate;
							$status.=", Claimed: ".date("M. d, Y",strtotime(str_replace(", Claimed: ","",$diplomaRow['remarks']))); 
						}
						else {
							$status="For: ".$diplomaRow['type'].", ".$diplomaDate;
							$status.=", Unclaimed";
						}
						
					
					}
					else {
						$status="No Requests Made";
					
					
					}					
					
					$rr[0]="F".$rowCount;
					$rr[1]="F".$rowCount;
					$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setWrapText(true);
					addContent($rr,$excel,$status,"false",$ExWs);
					$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					$rowCount++;
				}						
			
			}		
						
			save($ExWb,$excel,$newFilename); 		
		echo "Report has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
}
?>
