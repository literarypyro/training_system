<?php
require_once 'PHPWord/PHPWord.php';


$diplomaFilename="Request ".$_GET['timestamp'].".docx";


$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate('manual/request form.docx');


$document->setValue('Date', date("F d, Y",strtotime($releaseDate)));
$document->setValue('Support-OIC', "ENGR. OFELIA D. ASTRERA");

$document->setValue('OIC Position', "Chief Transportation Development Officer");
$document->setValue('Salutation', "Madam");
$document->setValue('StartDate', date("F d",strtotime($startdate)));
$document->setValue('EndDate', date("F d, Y",strtotime($enddate)));
$document->setValue('Training Title', ucfirst($trainingTitle));
$document->setValue('Batch Number', trim($batch));

$document->save('printout/'.$diplomaFilename);

$section = $PHPWord->createSection();


echo "<script language='javascript'>";
echo "window.close();";

echo "</script>";
?>