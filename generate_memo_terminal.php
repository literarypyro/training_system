<?php
require_once 'PHPWord/PHPWord.php';
?>
<?php
$diplomaFilename="Terminal Report ".date("Y-m-d His").".docx";
$PHPWord = new PHPWord();
$document = $PHPWord->loadTemplate("manual/terminal_memo.docx");
?>
<?php

$db=new mysqli("localhost","root","","training");
$sql="select * from training_instance where id='".$_POST['eventID_submit']."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();
$trainingTitle=$row['training_title'];
$batchNumber=$row['batch_number'];
$sDate=date("Y-m-d",strtotime($row['start_date']));
$eDate=date("Y-m-d",strtotime($row['end_date']));

if($sDate==$eDate){
	$period="on ".date("F d, Y",strtotime($row['start_date']));
}
else {
	$start_date=date("F d",strtotime($row['start_date']));
	$end_date=date("F d, Y",strtotime($row['end_date']));
	$period="on ".$start_date." to ".$end_date;
}


$trainingSQL="SELECT * FROM training_schedule where event_id='".$_POST['eventID_submit']."' and location not in ('') group by location";
$trainingRS=$db->query($trainingSQL);
$trainingNM=$trainingRS->num_rows;
if($trainingNM>1){
	$venue="";
	for($i=0;$i<$trainingNM;$i++){
		$trainingRow=$trainingRS->fetch_assoc();
		if($i==0){
			$venue.=$trainingRow['location'];
		}
		else if($i==($trainingNM*1-1)){
			$venue.=" and at the ".$trainingRow['location'];
	
		}
		else {
			$venue.=", ".$trainingRow['location'];
		
		
		}
	
	}

}
else {
	$trainingRow=$trainingRS->fetch_assoc();
	$venue=$trainingRow['location'];
}
$trainerClause="";
$trainerSQL="SELECT * FROM trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id where event_id='".$_POST['eventID_submit']."'";
$trainerRS=$db->query($trainerSQL);
$trainerNM=$trainerRS->num_rows;
if($trainerNM==1){
	$trainerRow=$trainerRS->fetch_assoc();
	$trainerClause=ucfirst($trainerRow['position'])." ".ucfirst($trainerRow['firstName'])." ".ucfirst($trainerRow['lastName']);

}
else {
	$trainerRow=$trainerRS->fetch_assoc();
	$trainerClause=ucfirst($trainerRow['position'])." ".ucfirst($trainerRow['firstName'])." ".ucfirst($trainerRow['lastName']);

	$trainerClause=ucfirst($trainerRow['position'])." ".ucfirst($trainerRow['firstName'])." ".ucfirst($trainerRow['lastName']);

	for($i=1;$i<$trainerNM;$i++){
		$trainerRow=$trainerRS->fetch_assoc();
	
	
	}

}

$date=date("d F Y");

$subjectTitle=strtoupper("TERMINAL REPORT ON ".$trainingTitle." (BATCH ".$batchNumber.")");

$body="Hereunder are the significant accomplishment made during the conduct of the Training on ".$trainingTitle." conducted ".$period." at the ".$venue.$additionalClause.".";

$body2="Trainees are fully attentive with the lecture and actively participated on the practical evaluation.";

$body3="The participants had completed this training with satisfactory rating with the trainer, ".$trainerClause.".";

$document->setValue('Memo Date', $date);
$document->setValue('Body', $body);
$document->setValue('Body 2', $body2);
$document->setValue('Body 3', $body3);

$document->setValue('Subject Title', $subjectTitle);
//$document->setValue('Addressee', $addressee);
//$document->setValue('Addressee-Position', $addresseePosition);




$document->save('printout/'.$diplomaFilename);




echo "Report has been generated!  Click Here: <a href='printout/".$diplomaFilename."' style='text-decoration:none;color:red;'>".$diplomaFilename."</a>";




?>
<br>
<a style="text-decoration:none;color:red;" href="trainer_aid.php?a=memo">Go Back to Previous Page</a>