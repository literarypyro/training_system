<?php
require_once 'PHPWord/PHPWord.php';
?>
<?php
$diplomaFilename="Memo for Completion ".date("Y-m-d His").".docx";
$PHPWord = new PHPWord();
$document = $PHPWord->loadTemplate("manual/completion_memo.docx");


$db=new mysqli("localhost","root","","training");


$trainerSQL="SELECT * FROM trainer_class inner join trainer_list on trainer_id=trainer_list.id where event_id='".$_POST['eventID_submit']."'";
$trainerRS=$db->query($trainerSQL);
$trainerNM=$trainerRS->num_rows;

for($i=0;$i<$trainerNM;$i++){
	$trainerRow=$trainerRS->fetch_assoc();
	if($i==0){
		$trainer[$i]=ucfirst($trainerRow['firstName'])." ".ucfirst($trainerRow['lastName']);
	}
	else if($i==($trainerNM*1-1)){
		$trainer[$i].=" and ".ucfirst($trainerRow['firstName'])." ".ucfirst($trainerRow['lastName']);
	
	
	}
	else {
		$trainer[$i].=", ".ucfirst($trainerRow['firstName'])." ".ucfirst($trainerRow['lastName']);
	
	}
}
$trainerClause="by ";

for($i=0;$i<count($trainer);$i++){
	$trainerClause.=$trainer[$i];
	


}

$sql="select * from training_instance where id='".$_POST['eventID_submit']."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();
$trainingTitle=$row['training_title'];
$batchNumber=$row['batch_number'];
$sDate=$row['start_date'];
$eDate=$row['end_date'];

$eventID=$_POST['eventID_submit'];

$startDate=date("d F",strtotime($sDate));
$endDate=date("d F, Y",strtotime($eDate));

$periodClause="";
$periodClause.="on ".$startDate." to ".$endDate; 
//echo ucase
//ucfirst
$subjectTitle="COMPLETION OF ".strtoupper($trainingTitle)." TRAINING (BATCH ".$batchNumber.")";


$body="With regard to the ".$trainingTitle." training conducted ".$trainerClause.", and held ".$periodClause.", the Support Staff is pleased to report that the";
$body.=" following participants for Batch No. ".$batchNumber." completed the said training".$toObjectives.":";
if(isset($_POST['objectives'])){
	$toObjectives=$_POST['objectives'];
}

if($toObjectives==""){

}
else {
	$toObjectives=" aimed ".$toObjectives;

}


$additionalClause="";
if(isset($_POST['additionalClause'])){	
	$additionalClause=$_POST['additionalClause'];	
}

if($additionalClause==""){

}
else {
	
	$additionalClause=" ".$additionalClause.".";
}
$date=date("d F Y");

$addressee="HONORIO R. VITASA";
$addresseePosition="General Manager";
if(isset($_POST['addressee'])){
	$addressee=$_POST['addressee'];
}
if(isset($_POST['position'])){
	$addresseePosition=$_POST['position'];
}


//$document->setValue('Addressee', $addressee);
//$document->setValue('Addressee-Position', $addresseePosition);

$db=new mysqli("localhost","root","","training");
$sql="select * from training_schedule where event_id='".$eventID."' group by date";
$rs=$db->query($sql);

$completeAttendance=$rs->num_rows;
$completion=0;

$db2=new mysqli("localhost","root","","evaluation");
$completeAttend="select count(*) as attends,event_id,trainee_id,firstName,lastName,midInitial from attendance inner join training.trainee_list on attendance.trainee_id=training.trainee_list.id where attendance.event_id='".$eventID."' group by trainee_id";
$completeRS=$db2->query($completeAttend);
$completeNM=$completeRS->num_rows;

for($i=0;$i<$completeNM;$i++){
	$completeRow=$completeRS->fetch_assoc();
	$attends=$completeRow['attends'];
	$firstName=$completeRow['firstName'];
	$lastName=$completeRow['lastName'];
	$midInitial=$completeRow['midInitial'];
	if($midInitial==""){
	}
	else {
		$midInitial=" ".$midInitial;
	}
	
	if($attends>=$completeAttendance){
		$trainee[$completion]=$firstName.$midInitial."."." ".$lastName;
		$completion++;
	}

}


$traineeClause="";
for($i=0;$i<count($trainee);$i++){
	$traineeClause.=$trainee[$i];	
	$traineeCount=strlen($trainee[$i]);
	$space=30-$traineeCount;
	for($a=0;$a<$space;$a++){
		$traineeClause.=" ";	
	}
}


$document->setValue('Body', $body);
$document->setValue('Additional Information', $additionalClause);
$document->setValue('Subject Title', $subjectTitle);
$document->setValue('Memo Date', $date);
$document->setValue('Trainee_Clause',$traineeClause);


$document->save('printout/'.$diplomaFilename);




echo "Report has been generated!  Click Here: <a href='printout/".$diplomaFilename."' style='text-decoration:none;color:red;'>".$diplomaFilename."</a>";


?>

<?php

/*
	$traineeClause.=strtoupper($row['firstName'])." ".strtoupper($row['lastName']);
	*/


//${Batch Ordinal} Batch of Transport Personnel completed the ${dayNumber}-day ${Training Title} Training aimed ${Objective Paragraph}.";
//echo $body2;
/*
$difference=strtotime($enddate)-strtotime($startdate);
$period=date("d F",strtotime($startdate))." - ".date("d F, Y",strtotime($enddate));
if($difference==0){
	$period=date("d F Y",strtotime($startdate));
}
else {
	$period=date("d F",strtotime($startdate))." - ".date("d F, Y",strtotime($enddate));
}
$releaseDate=date("d F, Y",strtotime($_POST['releaseYear']."-".$_POST['releaseMonth']."-".$_POST['releaseDay']));
if($_POST['midInitial']==""){
$midInitial=" ";
}
else {
$midInitial=" ".$_POST['midInitial']." ";
}
$addressee1=strtoupper($_POST['prefix']." ".$_POST['firstName'].$midInitial.$_POST['lastName']);
$addressee2=$_POST['prefix']." ".$_POST['lastName'];
$position=$_POST['position'];
$location=$_POST['company'];
$clause=$_POST['additionalClause'];
$venue=$_POST['venue'];
$activity=$_POST['activity'];
$startTime=$_POST['startingHour'].":".$_POST['startingMinute']." ".$_POST['startingHalf'];
$endTime=$_POST['endingHour'].":".$_POST['endingMinute']." ".$_POST['endingHalf'];
$diplomaFilename="Memo to Attend ".date("Y-m-d His").".docx";
$PHPWord = new PHPWord();
$document = $PHPWord->loadTemplate("manual/memo_to_attend.docx");
$title=strtoupper("COMPLETION REPORT ON ".$trainingtitle." Training (Batch ".$batch_number.")");
*/
/*
*/

/*
The Support Staff is pleased to report that the ${Batch Ordinal} Batch of Transport Personnel completed the ${dayNumber}-day ${Training Title} Training aimed ${Objective Paragraph}.

*/
/*
$additionalInformation="";



$document->setValue('Date', $releaseDate);
$document->setValue("position", $position);
$document->setValue("location", $location);
$document->setValue("to name", $addressee1);
$document->setValue('Addressee2', $addressee2);
$document->setValue('Body', $body);



$document->save('printout/'.$diplomaFilename);




	echo "Report has been generated!  Click Here: <a href='printout/".$diplomaFilename."' style='text-decoration:none;color:red;'>".$diplomaFilename."</a>";
*/
?>
<br><br>
<form action="generate_completion.php" method="post">
<table>
<tr>
	<th valign=center style="vertical-align:center;background-color:#ed5214;color:white;" colspan="2">
	Modify Printout (Add/Edit Details)
	</th>
</tr>
<!--
<tr>
	<th align=center style="vertical-align:center;background-color: #00cc66;color: white;" width=30%>Addressee Name</th>
	<td valign=center style="vertical-align:center;background-color:white;color:black;" width=70%><input type=text name='addressee' value='HONORIO R. VITASA'></td>
</tr>
<tr>
	<th align=center style="vertical-align:center;background-color: #00cc66;color: white;" width=30%>Addressee Position</th>
	<td valign=center style="vertical-align:center;background-color:white;color:black;" width=70%><input type=text name='position' value='General Manager'></td>
</tr>
<tr>
	<th align=center style="vertical-align:center;background-color: #00cc66;color: white;" width=30%>Training Objectives</th>
	<td valign=center style="vertical-align:center;background-color:white;color:black;" width=70%><textarea name='objectives' cols=60%>to update your knowledge on the special safety rules during Backward Driving Procedure</textarea></td>
</tr>
-->
<tr>
	<th align=center style="vertical-align:center;background-color: #00cc66;color: white;" width=30%>Additional Information</th>
	<td valign=center style="vertical-align:center;background-color:white;color:black;" width=70%><textarea name='additionalClause' cols=60%>However, Ms. Jocelyn Sancha was not able to attend the training because she attended to her father's needs at the hospital</textarea></td>
</tr>
<tr>
<td colspan=2 align=center><input type=hidden name='memoType' value='completion' /><input type=hidden name='eventID_submit' value='<?php echo $_POST['eventID_submit']; ?>' /><input type=submit value='Modify Printout' /></td>
</tr>
</table>
</form>
<br>
<a style="text-decoration:none;color:red;" href="trainer_aid.php?a=memo">Go Back to Previous Page</a>