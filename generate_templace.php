<?php
require_once 'PHPWord/PHPWord.php';


$db=new mysqli("localhost","root","","training");
$sql="select *,training_instance.id as eventId from training_instance inner join diploma_release on training_instance.id=diploma_release.training_program_id where diploma_release.release_id='".$_GET['diplomaId']."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();


$trainingTitle=$row['training_title'];
$startdate=$row['start_date'];
$enddate=$row['end_date'];
$releaseDate=$row['release_date'];
$programId=$row['program_id'];
$eventId=$row['eventId'];


$db=new mysqli("localhost","root","","training");
$sql="select *  from class_instance where training_event_id='".$eventId."' and traineeId='".$_GET['traineeId']."'";

$rs=$db->query($sql);
$row=$rs->fetch_assoc();
$traineeName=$row['firstName']." ".$row['lastName'];
$surname=$row['lastName'];

$diplomaFilename="Certification ".$_GET['timestamp'].".docx";


$PHPWord = new PHPWord();

//$document = $PHPWord->loadTemplate('manual/training_program_'.$programId.'.docx');

$document = $PHPWord->loadTemplate('manual/training_program_3.docx');


$document->setValue('Release Date', date("d F Y",strtotime($releaseDate)));
$document->setValue('Training Title', $trainingTitle);

$document->setValue('Trainee', $traineeName);
$document->setValue('Last Name', $surname);
$document->setValue('Start Date', date("F d",strtotime($startdate)));
$document->setValue('End Date', date("F d",strtotime($enddate)));
$document->setValue('Year', date("Y",strtotime($enddate)));

$document->save('printout/'.$diplomaFilename);
echo "<script language='javascript'>";
echo "window.close();";

echo "</script>";
?>