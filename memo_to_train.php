<?php
require_once 'PHPWord/PHPWord.php';


$diplomaFilename="testing.docx";


$PHPWord = new PHPWord();
$PHPWord->addFontStyle('BoldText', array('bold'=>true,'name'=>'Arial', 'size'=>'12'));

$section = $PHPWord->createSection();
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText("MEMORANDUM", "BoldText");
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText("To                :", array('name'=>'Arial', 'size'=>'12','spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText("Subject        :", array('name'=>'Arial', 'size'=>'12','spacing'=>0));

$document = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$document->save('printout/'.$diplomaFilename);

//$document = $PHPWord->loadTemplate('printout/'.$diplomaFilename);

//$document->setValue('Trainer Clause', "hey\r\nhey\r\nhye\r\nhey\r\nhey\r\nhey\r\n");





//$document->save('printout/'.$diplomaFilename);


echo "<a href='printout/".$diplomaFilename."'>HEre</a>";
/*
echo "<script language='javascript'>";
echo "window.close();";

echo "</script>";
*/
?>