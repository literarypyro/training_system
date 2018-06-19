<?php
require_once 'PHPWord/PHPWord.php';
?>
<?php
require("db_page.php");
//$db=new mysqli("localhost","root","","training");

$db=retrieveTrainingDb();
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

$diplomaFilename="Train Availability ".date("Y-m-d His").".docx";
$PHPWord = new PHPWord();
$document = $PHPWord->loadTemplate('manual/request_for_train_availability.docx');

$document->setValue('Date', $releaseDate);
$document->setValue("position", $position);
$document->setValue("location", $location);
$document->setValue("to name", $addressee1);
$document->setValue('Addressee2', $addressee2);


$body="With reference to the ".$trainingTitle." Training scheduled on ".$period.", may I request for the availability of a two-car train to be used during ".$activity.$clause." at the ".$venue.", from ".$startTime." to ".$endTime.".";
$document->setValue('Body', $body);



$document->save('printout/'.$diplomaFilename);




	echo "Report has been generated!  Click Here: <a href='printout/".$diplomaFilename."' style='text-decoration:none;color:red;'>".$diplomaFilename."</a>";

?>