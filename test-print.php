<?php
require_once 'PHPWord/PHPWord.php';
?>
<?php

$diplomaFilename="test ".date("Y-m-d His").".docx";
$PHPWord = new PHPWord();
$document = $PHPWord->loadTemplate('manual/memo to train_layout.docx');


$trainerList="MIRIAM ANDRES\r\n MERIVIE BANGALAN\r\n MARIBEL DELA CRUZ\r\n NATIVIDAD DIMAYA\r\n JOCELYN PEDARIA\r\n MARIBEL PELOBELLO\r\n LIEUNESSA REDULLA\r\n JOCELYN SANCHA";



$document->setValue("b","b");
$document->setValue("c","c");
$document->setValue("d","d");
$document->setValue("sdasd","sdasd");
$document->setValue("sds","sds");

$document->save('printout/'.$diplomaFilename);

echo "Report has been generated!  Click Here: <a href='printout/".$diplomaFilename."' style='text-decoration:none;color:red;'>".$diplomaFilename."</a>";

?>