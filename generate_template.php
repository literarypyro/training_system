<?php
require_once 'PHPWord/PHPWord.php';

$PHPWord = new PHPWord();


$section = $PHPWord->createSection();
$lineNumber=0;

if(isset($_GET['program_id'])){
	if($_GET['program_id']=="SAMPLE"){
	}
	else {
		$db=new mysqli("localhost","root","","training");
		$sql="select * from training_coverage inner join coverage on coverage_id=coverage.id where training_program='".$_GET['program_id']."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
	}
}

$header = $section->createHeader(array('width'=>50, 'height'=>50));
$header->addText(" ");
$header->addText(" ");
$header->addText(" ");
$header->addText(" ");

$header = $section->createFooter(array('width'=>50, 'height'=>50));
$header->addText(" ",array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$header->addText(" ",array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$header->addText(" ",array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));



$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

$section->addText('${Release Date}', array('name'=>'Arial', 'size'=>'13','spacing'=>0));

$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

$section->addText('C E R T I F I C A T I O N', array('name'=>'Arial', 'size'=>'14', 'underline'=>PHPWord_Style_Font::UNDERLINE_SINGLE,'bold'=>'true'),array('align'=>"center",'spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

$textrun = $section->createTextRun(array('spacing'=>0,'align'=>'both'));
$PHPWord->addFontStyle('BoldText', array('bold'=>true,'name'=>'Arial', 'size'=>'13'));

$textrun->addText('This certifies that ',array('name'=>'Arial', 'size'=>'13'));
$textrun->addText('Mr. ${Trainee} ', 'BoldText');
$textrun->addText('satisfactorily completed and passed the ${Training Title} training conducted by the DOTC - MRT3, Support Staff from ',array('name'=>'Arial', 'size'=>'13'));
$textrun->addText('${Start Date} to ${End Date} ${Year}', 'BoldText');
$textrun->addText(', covering the following courses:',array('name'=>'Arial', 'size'=>'13'),array('spaceAfter'=>0));
$lineNumber=13;

$PHPWord->addParagraphStyle('P-Style', array('spaceAfter'=>120,'indLeft'=>12));

$PHPWord->addFontStyle('myOwnStyle', array('name'=>'Arial', 'size'=>'13'));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0,'spaceAfter'=>0));

$listStyle = array('listType'=>PHPWord_Style_ListItem::TYPE_SQUARE_FILLED);

if($_GET['program_id']=="SAMPLE"){
	for($i=0;$i<2;$i++){
		$coverageText=str_replace("'","","Sample Coverage");
		$section->addListItem($coverageText, 0,  'myOwnStyle', $listStyle, 'P-Style');
		$lineNumber++;
	}

}
else {
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$coverageText=str_replace("'","",$row['coverage_description']);
		$section->addListItem($coverageText, 0,  'myOwnStyle', $listStyle, 'P-Style');
		$lineNumber++;
	}

}


$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$textrun = $section->createTextRun(array('spacing'=>0,'align'=>'both'));
$lineNumber++;

$textrun->addText('This certification is being issued upon the request of ',array('name'=>'Arial', 'size'=>'13'));
$textrun->addText('Mr. ${Last Name} ', 'BoldText');
$textrun->addText('for whatever purpose it may serve. ',array('name'=>'Arial', 'size'=>'13'));
for($i=0;$i<3;$i++){
	$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0,'spaceAfter'=>0));
}
/**
$lineNumber=$lineNumber*1+8;
while($lineNumber<36){
	$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0,'spaceAfter'=>0));
	$lineNumber++;
}
*/
$db=new mysqli("localhost","root","","training");
$sqlFooter1="SELECT * FROM template where document_type='certification' and content_type like 'footer%%1' order by content_order";
$rsFooter1=$db->query($sqlFooter1);
$nmFooter1=$rsFooter1->num_rows;

$rowFooter1=$rsFooter1->fetch_assoc();
$section->addText($rowFooter1['content'], 'BoldText', array('spacing'=>0,'spaceAfter'=>0));

$rowFooter1=$rsFooter1->fetch_assoc();
$section->addText($rowFooter1['content']." ", array('name'=>'Arial', 'size'=>'13'), array('spacing'=>0,'spaceAfter'=>0));

if($nmFooter1>=3){
$rowFooter1=$rsFooter1->fetch_assoc();
$section->addText($rowFooter1['content'], array('name'=>'Arial', 'size'=>'13'), array('spacing'=>0,'spaceAfter'=>0));
}


$diplomaFilename="training_program_".$_GET['program_id'].".docx";


$document = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');

$document->save('manual/'.$diplomaFilename);
echo "<script language='javascript'>";
echo "window.close();";

echo "</script>";
?>