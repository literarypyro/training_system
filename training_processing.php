<?php
require("db_page.php");
?>
<?php
if(isset($_GET['trainingID'])){
	$db=new mysqli("localhost","root","","training");
	$sql="select * from training_instance where id='".$_GET['trainingID']."'";
	$rs=$db->query($sql);	
	
}

if(isset($_GET['searchTrainee'])){
	$fName=$_GET['searchFName'];
	$lName=$_GET['searchLName'];

	$db=retrieveTrainingDb();
	$sql="select * from trainee_list where firstName like '".$fName."%%' or lastName like '".$lName."%%' order by lastName"; 
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$result="";
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			$result.=$row['id'].";";
			$result.=$row['firstName'].";";
			$result.=$row['lastName']."==>";

	
		}
		echo $result;
	}
	else {
		echo "None available.";
	}
}



if(isset($_GET['searchProgram'])){
	$training=$_GET['searchProgram'];

	$db=retrieveTrainingDb();
	$sql="select * from training_programs where training_title like '".$training."%%' order by training_title"; 
	
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$result="";
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			$result.=$row['id'].";";
			$result.=$row['training_title'].";";
			$result.=$row['division_code'].";";
			$result.=$row['alt_office']."==>";

	
		}
		echo $result;
	}
	else {
		echo "None available.";
	}
}

if(isset($_GET['getProgram'])){
	$db=retrieveTrainingDb();
	$index=$_GET['getProgram'];

	$sqlTrainee="SELECT * FROM training_programs inner join division on training_programs.division_code=division.division_code  where training_programs.id='".$index."'";
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;
	//echo $sqlTrainee;
	if($nmTrainee>0){
		for($i=0;$i<$nmTrainee;$i++){
			$rowTrainee=$rsTrainee->fetch_assoc();	
			$result=$rowTrainee['id'].";";
			$result.=$rowTrainee['training_title'].";";
			$result.=$rowTrainee['division_code'].";";
			$result.=$rowTrainee['division_name'].";";


		
		
		}
		echo $result;

	}
	else {
		echo "None available";
	}



}


if(isset($_GET['getTrainee'])){
	$db=retrieveTrainingDb();
	$index=$_GET['getTrainee'];
	
	$sqlTrainee="select * from trainee_list where id='".$index."'";
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;
	//echo $sqlTrainee;
	if($nmTrainee>0){
		for($i=0;$i<$nmTrainee;$i++){
			$rowTrainee=$rsTrainee->fetch_assoc();	
			$result=$rowTrainee['id'].";";
			$result.=$rowTrainee['firstName'].";";
			$result.=$rowTrainee['lastName'].";";
			$result.=$rowTrainee['midInitial'].";";
			$result.=$rowTrainee['designation'].";";
			$result.=$rowTrainee['department'].";";
			
			if($rowTrainee['department']==""){
				
			}
			else {
				$divSQL="select * from division where division_code='".$rowTrainee['department']."'";
				$divRS=$db->query($divSQL);
				$divRow=$divRS->fetch_assoc();
				
				$result.=$divRow['division_name'].";";
			}
		

			

			echo $result;

		
		
		}
	}
	else {
		echo "None available";
	}
}

if(isset($_GET['getTrainer'])){
	$db=retrieveTrainingDb();
	$index=$_GET['getTrainer'];
	
	$sqlTrainee="select * from trainer_list where id='".$index."'";
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;
	//echo $sqlTrainee;
	if($nmTrainee>0){
		for($i=0;$i<$nmTrainee;$i++){
			$rowTrainee=$rsTrainee->fetch_assoc();	
			$result=$rowTrainee['id'].";";
			$result.=$rowTrainee['firstName'].";";
			$result.=$rowTrainee['lastName'].";";
			$result.=$rowTrainee['midInitial'].";";
			$result.=$rowTrainee['position'].";";

			echo $result;

		
		
		}
	}
	else {
	//	echo "None available";
		echo $sqlTrainee;
	}
}

if(isset($_GET['indexTrainee'])){
	$db=retrieveTrainingDb();
	$index=$_GET['indexTrainee'];
	
	$sqlTrainee="select * from trainee_list limit ".$index.",1";
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;
	
	if($nmTrainee>0){
		for($i=0;$i<$nmTrainee;$i++){
			$rowTrainee=$rsTrainee->fetch_assoc();	
			$result=$rowTrainee['id'].";";
			$result.=$rowTrainee['firstName'].";";
			$result.=$rowTrainee['lastName'].";";
			$result.=$rowTrainee['midInitial'].";";
			$result.=$rowTrainee['designation'].";";
			$result.=$rowTrainee['department'].";";
		
			echo $result;

		
		
		}
	}
	else {
		echo "None available";
	
	}
}
if(isset($_GET['indexTrainer'])){
	$db=retrieveTrainingDb();
	$index=$_GET['indexTrainer'];
	
	$sqlTrainee="select * from trainer_list limit ".$index.",1";
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;
	
	if($nmTrainee>0){
		for($i=0;$i<$nmTrainee;$i++){
			$rowTrainee=$rsTrainee->fetch_assoc();	
			$result=$rowTrainee['id'].";";
			$result.=$rowTrainee['firstName'].";";
			$result.=$rowTrainee['lastName'].";";
			$result.=$rowTrainee['position'].";";
			echo $result;
		
		}
	}
	else {
		echo "None available";
	
	}
}
/*
if(isset($_GET['indexLogin'])){
	$db=retrieveTrainingDb();
	$index=$_GET['indexLogin'];
	
	$sqlTrainee="select * from trainer_list limit ".$index.",1";
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;
	
	if($nmTrainee>0){
		for($i=0;$i<$nmTrainee;$i++){
			$rowTrainee=$rsTrainee->fetch_assoc();	
			$result=$rowTrainee['id'].";";
			$result.=$rowTrainee['firstName'].";";
			$result.=$rowTrainee['lastName'].";";
			$result.=$rowTrainee['position'].";";
			echo $result;
		
		}
	}
	else {
		echo "None available";
	
	}
}*/
if(isset($_GET['indexEvent'])){
	$db=retrieveTrainingDb();
	$index=$_GET['indexEvent'];
	
	$sqlTrainee="SELECT training_programs.id as program_id,training_events.id as event_id,training_events.*,training_programs.* FROM training_events inner join training_programs on training_program_id=training_programs.id order by start_date desc limit ".$index.",1";

	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;
	
	if($nmTrainee>0){
		for($i=0;$i<$nmTrainee;$i++){
		

			$rowTrainee=$rsTrainee->fetch_assoc();	
			$result=$rowTrainee['event_id'].";";
			$result.=$rowTrainee['training_title'].";";
			$result.=$rowTrainee['batch_number'].";";
			$result.=date("F d, Y",strtotime($rowTrainee['start_date'])).";";
			$result.=date("F d, Y",strtotime($rowTrainee['end_date'])).";";
			$result.=$rowTrainee['no_of_days'].";";
			$result.=$rowTrainee['training_title'].", ".$rowTrainee['division_code'].";";
			$result.=date("Ymd",strtotime($rowTrainee['start_date'])).";";
			$result.=date("Ymd",strtotime($rowTrainee['end_date'])).";";

			
			$startShiftSQL="select min(start_time) as earliest, max(end_time) as latest from training_schedule where event_id='".$rowTrainee['event_id']."' and date='".date("Y-m-d",strtotime($rowTrainee['start_date']))."' group by date";
			
			$sShiftRS=$db->query($startShiftSQL);
			$sShiftRow=$sShiftRS->fetch_assoc();


			$earliestStart=$sShiftRow['earliest'];
			$latestStart=$sShiftRow['latest'];

			$date=date("Y-m-d",strtotime($row['end_date']));

			$eShiftSQL="select min(start_time) as earliest, max(end_time) as latest from training_schedule where event_id='".$rowTrainee['event_id']."' and date='".date("Y-m-d",strtotime($rowTrainee['end_date']))."' group by date";
			$eShiftRS=$db->query($eShiftSQL);
			$eShiftRow=$eShiftRS->fetch_assoc();
			$earliestEnd=$eShiftRow['earliest'];
			$latestEnd=$eShiftRow['latest'];			
			
			$result.=date("giA",strtotime($earliestStart)).";";
			$result.=date("giA",strtotime($earliestEnd)).";";			
			$result.=$rowTrainee['training_program_id'].";";

			echo $result;
		
		}
	}
	else {
		echo "None available";
	
	}
}
if(isset($_GET['indexProgram'])){
	$db=retrieveTrainingDb();
	$index=$_GET['indexProgram'];
	
	$sqlTrainee="select * from training_programs inner join division on training_programs.division_code=division.division_code limit ".$index.",1";
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;
	
	if($nmTrainee>0){
		for($i=0;$i<$nmTrainee;$i++){
			$rowTrainee=$rsTrainee->fetch_assoc();	
			$result=$rowTrainee['id'].";";
			$result.=$rowTrainee['training_title'].";";
			$result.=$rowTrainee['division_code'].";";
			$result.=$rowTrainee['division_name'].";";			
			echo $result;
		
		}
	}
	else {
		echo "None available";
	
	}
}

if(isset($_GET['listCoverage'])){
	$db=retrieveTrainingDb();
	$index=$_GET['listCoverage'];	
	
	$sql="select *,coverage.id as cov_id from coverage inner join training_coverage on coverage.id=coverage_id where training_program='".$index."'";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$result="";
		for($m=0;$m<$nm;$m++){
			$row=$rs->fetch_assoc();
			
			$result.=$row['id'].";";
			$result.=ucfirst($row['coverage_description'])."==>";
			echo $result;
		}	
	}
	else {
		echo "None available";
	
	}

}

if(isset($_GET['participantInfo'])){
	$choice=$GET['participantType'];
	
	if($choice=="event"){

		$db=retrieveTrainingDb();
		$index=$_GET['participantInfo'];
	
		
		/**
		$sqlTrainee="SELECT * FROM class_list inner join training_instance on class_list.training_events_id=training_instance.id where trainee_id='".$index."' order by start_date desc";
		$rsTrainee=$db->query($sqlTrainee);
		$nmTrainee=$rsTrainee->num_rows;
	
		if($nmTrainee>0){
			for($i=0;$i<$nmTrainee;$i++){
				$rowTrainee=$rsTrainee->fetch_assoc();	
				$result=$rowTrainee['id'].";";
				$result.=$rowTrainee['training_title'].";";
				$result.=$rowTrainee['batch_number'].";";
				$result.=date("F d, Y",strtotime($rowTrainee['start_date'])).";";
				$result.=date("F d, Y",strtotime($rowTrainee['end_date'])).";";
				echo $result;
		
			}
		}
		
		else {
			echo "None available";
		
		}
		*/
	}
	
	
	
	else if($choice=="trainee"){
		

	
	
	}
}
if(isset($_GET['listTrainers'])){
	$event_id=$_GET['listTrainers'];
	
	$db=retrieveTrainingDb();
	$sql="select *,trainer_list.id as trainer_list_id  from (trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id) inner join training_events on event_id=training_events.id where event_id='".$event_id."'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	if($nm>0){
		$result="";
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			$result.=$row['trainer_list_id'].";";
			$result.=$row['firstName'].";";
			$result.=$row['lastName']."==>";
			

		}
		echo $result;	
	}
	else {
		echo "None available";
	}
}
if(isset($_GET['listClass'])){
	$event_id=$_GET['listClass'];
	
	$db=retrieveTrainingDb();
	$sql="select * from class_instance where training_event_id='".$event_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$result="";
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			
			$sqlDiploma="select * from diploma_release where trainee_id='".$row['traineeId']."' and training_program_id='".$event_id."' and type='diploma' order by release_date desc limit 1";
			$rsDiploma=$db->query($sqlDiploma);
			$nmDiploma=$rsDiploma->num_rows;
			
			if($nmDiploma>0){
				$rowDiploma=$rsDiploma->fetch_assoc();
				if($rowDiploma['status']=="Issuance"){
					$status="Issued";
				}
				else if($rowDiploma['status']=="Re-Issuance"){
					$status="Re-Issued";
				}				
				$diploma="<a href=\"printout/Diploma ".date("Y-m-d His",strtotime($rowDiploma['release_date'])).".pptx\" style=\"text-decoration:none\" >".$status.", ".date("F d, Y",strtotime($rowDiploma['release_date']))."</a>";		
				
			}
			else {
				$diploma="Un-Issued";
			
			}
		
		

			$result.=$row['traineeId'].";";
			$result.=$row['firstName'].";";
			$result.=$row['lastName'].";";
			$result.=$diploma."==>";

		}
		echo $result;		
	
	}
	else {
		echo "None available";
	}
}

if(isset($_GET['listEvent'])){
	$event_id=$_GET['listEvent'];
	$batchId=$_GET['batch'];

	$db=retrieveTrainingDb();
	if($batchId=="all"){
		$program=$_GET['listEvent'];	
		$sql="SELECT * FROM class_instance inner join training_instance on training_event_id=training_instance.id where program_id='".$program."'";
	
	}
	else {

	$ret="select * from trainee_list where id='".$event_id."'";
	$retRs=$db->query($ret);
	$retRow=$retRs->fetch_assoc();
	
	
	$sql="SELECT * FROM training_instance inner join class_instance on class_instance.training_event_id=training_instance.id where class_instance.traineeId in				
	(select id from trainee_list where lastName='".$retRow['lastName']."' and firstName='".$retRow['firstName']."')";

	
//		$sql="SELECT * FROM class_instance inner join training_instance on training_event_id=training_instance.id where traineeId='".$event_id."'";
	}
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$result="";
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			
			$sqlDiploma="select * from diploma_release where trainee_id='".$event_id."' and training_program_id='".$row['training_event_id']."' and type='diploma' order by release_date desc limit 1";
			$rsDiploma=$db->query($sqlDiploma);
			$nmDiploma=$rsDiploma->num_rows;
			
			if($nmDiploma>0){
				$rowDiploma=$rsDiploma->fetch_assoc();
				if($rowDiploma['status']=="Issuance"){
					$status="Issued";
				}
				else if($rowDiploma['status']=="Re-Issuance"){
					$status="Re-Issued";
				}				
				$diploma="<a href=\"printout/Diploma ".date("Y-m-d His",strtotime($rowDiploma['release_date'])).".pptx\" style=\"text-decoration:none\" >".$status.", ".date("F d, Y",strtotime($rowDiploma['release_date']))."</a>";		
				
			}
			else {
				$diploma="Un-Issued";
			
			}
		
		

			$result.=$row['training_event_id'].";";
			$result.=$row['training_title'].";";
			$result.=$row['batch_number'].";";
			$result.="From ".date("F d, Y",strtotime($row['start_date']))." to ".date("F d, Y",strtotime($row['start_date'])).";";

			$result.=$diploma."==>";

		}
		echo $result;		
	
	}
	else {
		echo "None available";
	}
}



if(isset($_GET['listSchedule'])){
	$event_id=$_GET['listClass'];
	
	$db=retrieveTrainingDb();
	$sql="select * from training_schedule where event_id='".$event_id."'";

	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	if($nm>0){
		$result="";
		for($i=0;$i<$nm;$i++){

			$row=$rs->fetch_assoc();
			$result.=$row['date'].";";
			$result.=$row['start_time'].";";
			$result.=$row['end_time']."==>";
			

		}
		echo $result;		
	
	}
	else {
		echo "None available";
	}
}
/*
if(isset($_GET['listTrainers'])){
	$event_id=$_GET['listTrainers'];
	
	$db=retrieveTrainingDb();
	$sql="select *,trainer_list.id as trainer_list_id  from (trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id) inner join training_events on event_id=training_events.id where event_id='".$event_id."'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	if($nm>0){
		$result="";
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			$result.=$row['trainer_list_id'].";";
			$result.=$row['firstName'].";";
			$result.=$row['lastName']."==>";
			
			echo $result;
		}
	
	}
	else {
		echo "None available";
	}
}
*/
if(isset($_GET['insertTrainee'])){
	$firstName=$_GET['firstName'];
	$lastName=$_GET['lastName'];
	$midInitial=$_GET['midInitial'];
	$position=$_GET['position'];
	$divAssigned=$_GET['division'];
	
	$db=retrieveTrainingDb();
	$sql="insert into trainee_list(firstName,lastName,designation,department,midInitial) values ('".$firstName."','".$lastName."','".$position."','".$divAssigned."','".$midInitial."')";
	$rs=$db->query($sql);
	
	$entry=$db->insert_id;
	echo $entry;

}

if(isset($_GET['searchEvent'])){
	$title=$_GET['searchEvent'];
	$batch=$_GET['searchBatch'];
	
	$db=retrieveTrainingDb();
	if($batch=="all"){
		$sql="select * from training_instance where program_id='".$title."' order by batch_number";	

	
	}
	else {
		$sql="select * from training_instance where training_title like '%%".$title."%%'";
	}
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	$result="";
		if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			$result.=$row['id'].";";
			$result.=$row['training_title'].";";		
			$result.=$row['batch_number'].";";
			$result.=date("F d, Y",strtotime($row['start_date'])).";";
			$result.=date("F d, Y",strtotime($row['end_date']))."==>";
		
		}
		
		echo $result;
	}
	else {
		echo "None available";
	
	}
}

if(isset($_GET['getEvent'])){
	$db=retrieveTrainingDb();
	$index=$_GET['getEvent'];
	
	$sqlTrainee="select * from training_instance where id='".$index."'";
	
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;
	//echo $sqlTrainee;
	if($nmTrainee>0){
		for($i=0;$i<$nmTrainee;$i++){
			$row=$rsTrainee->fetch_assoc();	

			$result.=$row['id'].";";
			$result.=$row['training_title'].";";		
			$result.=$row['batch_number'].";";
			$result.=date("F d, Y",strtotime($row['start_date'])).";";
			$result.=date("F d, Y",strtotime($row['end_date'])).";";
			$result.=$row['program_id']."";
			
			echo $result;

		
		
		}
	}
	else {
		echo "None available";
	}
}

if(isset($_GET['makeRequest'])){
	$event_id=$_GET['makeRequest'];
	$trainee_id=$_GET['tID'];

	$db=retrieveTrainingDb();

	
	
	
//	$sql="insert into certificate_request(status, remarks,trainee_id,training_program_id) values ('Claimed','Claimed: ".$timestamp."','".$nID."','".$training_program_id."'";


	$sql="insert into certificate_request(status, remarks,trainee_id,training_program_id) values ('Requested','".date("Y-m-d")."','".$trainee_id."','".$event_id."')";


	$rs=$db->query($sql);
	$nm=$rs->num_rows;
			
	
		
	
	
	
}

?>