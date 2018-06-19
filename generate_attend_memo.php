<?php
require_once 'PHPWord/PHPWord.php';
?>
<?php
$diplomaFilename="Memo to Attend ".date("Y-m-d His").".docx";
$PHPWord = new PHPWord();
$document = $PHPWord->loadTemplate("manual/memo_to_attend.docx");

$db=new mysqli("localhost","root","","training");
$sql="select * from class_instance where training_event_id='".$_POST['eventID_submit']."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
$traineeClause="";
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$traineeClause.=strtoupper($row['firstName'])." ".strtoupper($row['lastName']);
	$traineeCount=(strlen($row['firstName'])*1)+1+(strlen($row['lastName'])*1);
	$space=30-$traineeCount;
	if($i==($nm-1)){
	}
	else {
		for($a=0;$a<$space;$a++){
			$traineeClause.=" ";	
		}
	}
}

$db=new mysqli("localhost","root","","training");
$sql="select * from training_instance where id='".$_POST['eventID_submit']."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();
$trainingTitle=$row['training_title'];
$batchNumber=$row['batch_number'];
$sDate=date("Y-m-d",strtotime($row['start_date']));
$eDate=date("Y-m-d",strtotime($row['end_date']));


$scheduleSQL="select * from training_schedule where event_id='".$_POST['eventID_submit']."' and date='".$sDate."'";
$scheduleRS=$db->query($scheduleSQL);
$scheduleRow=$scheduleRS->fetch_assoc();

$period="";
if($sDate==$eDate){
	$period="on ".date("F d, Y",strtotime($sDate));
	

}
else {
	$period="on ".date("F d",strtotime($sDate))." - ".date("F d, Y",strtotime($eDate));

}

$start_time=date("h:i A",strtotime($scheduleRow['start_time']));
$end_time=date("h:i A",strtotime($scheduleRow['end_time']));

$timeClause="from ".$start_time." to ".$end_time;
$venue=$scheduleRow['location'];
if($venue==""){
	$venue="MRT3 Training Room";
}

$period.=" ".$timeClause;
//$purpose=" for practical exercises and evaluation";


if(isset($_POST['purpose'])){	
	$purpose=$_POST['purpose'];	
}
if($purpose==""){

}
else {
	$purpose=" ".$purpose;
}
if(isset($_POST['additionClause'])){	
	$additionClause=$_POST['additionClause'];	
}

if($additionClause==""){

}
else {
	$additionClause=" ".$additionClause;
}

$objectives="to update your knowledge on the special safety rules during Backward Driving Procedure";

if(isset($_POST['objectives'])){
	$objectives=$_POST['objectives'];
}

$body="You are hereby directed to attend on official time, the ".$trainingTitle." Training to be conducted by the Support Staff on ".$period." at the ".$venue.$additionClause.$purpose.".";

$body2="This training aims ".$objectives.".";


$subjectTitle=strtoupper($trainingTitle)." TRAINING (Batch ".$batchNumber.")";


$dateRelease=date("d F Y");


$document->setValue('Trainee-Clause', $traineeClause);
$document->setValue('Subject Title', $subjectTitle);
$document->setValue('Date', $dateRelease);
$document->setValue('Body', $body);
$document->setValue('Body2', $body2);

$document->save('printout/'.$diplomaFilename);


echo "Report has been generated!  Click Here: <a href='printout/".$diplomaFilename."' style='text-decoration:none;color:red;'>".$diplomaFilename."</a>";

/*
$db=new mysqli("localhost","root","","training");
$sql="select * from training_instance where id='".$_POST['event_id']."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();
$trainingTitle=$row['training_title'];
$startdate=$row['start_date'];
$enddate=$row['end_date'];

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



$title=strtoupper($trainingtitle." (Batch ".$batch_number.")");



/*
You are hereby directed to attend on official time, the ${Training Title} Training to 
be conducted by the Support Staff on ${Period Clause} from ${Time} at the ${Training Venue}
for ${purpose}${and from}${Time-2}${at the-2}${Training Venue-2}${for}${purpose-2}.

*/

/*

This ${n-day} training aims ${Training Objectives}.

*/
/*
$body="With reference to the ".$trainingTitle." Training scheduled on ".$period.", may I request for the availability of a two-car train to be used during ".$activity.$clause." at the ".$venue.", from ".$startTime." to ".$endTime.".";



$document->setValue('Date', $releaseDate);
$document->setValue("position", $position);
$document->setValue("location", $location);
$document->setValue("to name", $addressee1);
$document->setValue('Addressee2', $addressee2);
$document->setValue('Body', $body);



*/
?>
<br><br>
<form action="memo_generate.php" method="post">
<table>
<tr>
	<th valign=center style="vertical-align:center;background-color:#ed5214;color:white;" colspan="2">
	Modify Printout (Add/Edit Details)
	</th>
</tr>
<tr>
	<th align=center style="vertical-align:center;background-color: #00cc66;color: white;" width=30%>Additional Clause</th>
	<td valign=center style="vertical-align:center;background-color:white;color:black;" width=70%><textarea name='additionClause' cols=60%>and from 1:00 PM to 5:30 PM at the Depot Stabling Area</textarea></td>
</tr>
<tr>
	<th align=center style="vertical-align:center;background-color: #00cc66;color: white;" width=30%>Training Objectives</th>
	<td valign=center style="vertical-align:center;background-color:white;color:black;" width=70%><textarea name='objectives' cols=60%>to update your knowledge on the special safety rules during Backward Driving Procedure</textarea></td>

	</tr>
<tr>
	<th align=center style="vertical-align:center;background-color: #00cc66;color: white;" width=30%>Purpose</th>
	<td valign=center style="vertical-align:center;background-color:white;color:black;" width=70%><textarea name='purpose' cols=60%>for physical and training evaluations</textarea></td>
</tr>
<tr>
<td colspan=2 align=center><input type=hidden name='memoType' value='mATrainees' /><input type=hidden name='eventID_submit' value='<?php echo $_POST['eventID_submit']; ?>' /><input type=submit value='Modify Printout' /></td>
</tr>
</table>
</form>
<br>
<a style="text-decoration:none;color:red;" href="trainer_aid.php?a=memo">Go Back to Previous Page</a>