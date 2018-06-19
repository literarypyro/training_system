<?php
require("db_page.php");
?>
<?php
error_reporting(E_ALL);

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');

/** PHPPowerPoint */
include 'PHPPowerPoint.php';

/** PHPPowerPoint_IOFactory */
include 'PHPPowerPoint/IOFactory.php';


if($_GET['diplomaId']=="SAMPLE"){
$trainingTitle="Sample Training Course";
$startdate=date("Y-m-d");
$enddate=date("Y-m-d");
$releaseDate=date("Y-m-d");


}
else {
$db=retrieveTrainingDb();
$sql="select *,training_instance.id as eventId from training_instance inner join diploma_release on training_instance.id=diploma_release.training_program_id where diploma_release.release_id='".$_GET['diplomaId']."'";

$rs=$db->query($sql);
$row=$rs->fetch_assoc();


$trainingTitle=$row['training_title'];
$startdate=$row['start_date'];
$enddate=$row['end_date'];
$releaseDate=$row['release_date'];
$eventId=$row['eventId'];
}


if($_GET['traineeId']=="SAMPLE"){
	$traineeName="JOHN H. DOE";
}
else{
$db=retrieveTrainingDb();
$sql="select * from class_instance where training_event_id='".$eventId."' and traineeId='".$_GET['traineeId']."'";

$rs=$db->query($sql);
$row=$rs->fetch_assoc();
$traineeName=strtoupper($row['firstName']." ".$row['lastName']);
}
$diplomaFilename="Diploma ".$_GET['timestamp'].".pptx";

// Create new PHPPowerPoint object
echo date('H:i:s') . " Create new PHPPowerPoint object\n";
$objPHPPowerPoint = new PHPPowerPoint();

echo date('H:i:s') . " Remove first slide\n";
$objPHPPowerPoint->removeSlideByIndex(0);

$slide = $objPHPPowerPoint->createSlide();

$db=retrieveTrainingDb();
$sqlbg="select * from template where document_type='diploma' and content_type='background_image'";
$rsbg=$db->query($sqlbg);
$rowbg=$rsbg->fetch_assoc();

	// Add background image

    $shape = $slide->createDrawingShape();
 //   $shape->setName($rowbg['content']);
//    $shape->setDescription($rowbg['content']);

	$shape->setName("Background Image");
    $shape->setDescription("Background Image");

//    $shape->setPath($rowbg['content_path']);
    $shape->setPath("images/certificate_borders.jpg");

    $shape->setWidth(955);
    $shape->setHeight(720);
    $shape->setOffsetX(0);
    $shape->setOffsetY(0);

	// Add logo image
    $shape = $slide->createDrawingShape();
    $shape->setName('Background');
    $shape->setDescription('Background');
    $shape->setPath('images/certificate_logo.jpg');
    $shape->setWidth(89);
    $shape->setHeight(94);
    $shape->setOffsetX(73);
    $shape->setOffsetY(80);	
	
		echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $slide->createRichTextShape();
$shape->setHeight(34);
$shape->setWidth(267);
$shape->setOffsetX(337);
$shape->setOffsetY(89);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER );

$textRun = $shape->createTextRun('REPUBLIC OF THE PHILIPPINES');
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");

$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );
	
	
			echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $slide->createRichTextShape();
$shape->setHeight(55);
$shape->setWidth(869);
$shape->setOffsetX(34);
$shape->setOffsetY(113);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER );

$textRun = $shape->createTextRun('Department of Transportation and Communications');
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");

$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );
$shape->createBreak();	
	
$textRun = $shape->createTextRun('Metro Rail Transit 3 (DOTC - MRT3) ');
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");

$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );	
	
		echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $slide->createRichTextShape();
$shape->setHeight(34);
$shape->setWidth(236);
$shape->setOffsetX(357);
$shape->setOffsetY(200);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER );
$textRun = $shape->createTextRun('This certificate is issued to');
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");

$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );


	
	
		echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $slide->createRichTextShape();
$shape->setHeight(353);
$shape->setWidth(859);
$shape->setOffsetX(49);
$shape->setOffsetY(209);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER );

$textRun = $shape->createTextRun('');
$textRun->getFont()->setSize(18);
$textRun->getFont()->setName("Calibri");

$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );
$shape->createBreak();	
$shape->createBreak();	
$shape->createBreak();	

$textRun = $shape->createTextRun('for having completed the training for');
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");
$shape->createBreak();	
$textRun = $shape->createTextRun(' ');
$textRun->getFont()->setSize(12);
$textRun->getFont()->setName("Calibri");
$shape->createBreak();	
$textRun = $shape->createTextRun(' ');
$textRun->getFont()->setSize(12);
$textRun->getFont()->setName("Calibri");
$shape->createBreak();	
$textRun = $shape->createTextRun($trainingTitle);
$textRun->getFont()->setBold(true);
$textRun->getFont()->setSize(18);
$textRun->getFont()->setName("Arial Black");
$shape->createBreak();	

$textRun = $shape->createTextRun(' ');
$textRun->getFont()->setSize(8);
$textRun->getFont()->setName("Calibri");
$shape->createBreak();	

$textRun = $shape->createTextRun('conducted by the Support Staff/Computer Section/AFC Center');
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");
$shape->createBreak();	
	
if(date("m",strtotime($startdate))==date("m",strtotime($enddate))){
	$period=date("F d",strtotime($startdate))." - ".date("d, Y",strtotime($enddate));
	
}	
else {
	$period=date("F d",strtotime($startdate))." - ".date("F d, Y",strtotime($enddate));

}
	
	
$textRun = $shape->createTextRun('on '.$period);
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");
$shape->createBreak();	

$textRun = $shape->createTextRun(' ');
$textRun->getFont()->setSize(12);
$textRun->getFont()->setName("Calibri");
$shape->createBreak();	

$textRun = $shape->createTextRun('Given this '.date("jS",strtotime($releaseDate))." day of ".date("F Y",strtotime($releaseDate)));
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");
$shape->createBreak();	

$dbVenue=new mysqli("localhost","root","","training");
$sqlVenue="select * from template where document_type='diploma' and content_type='venue'";
$rsVenue=$dbVenue->query($sqlVenue);
$rowVenue=$rsVenue->fetch_assoc();


$textRun = $shape->createTextRun('at the '.$rowVenue['content']);
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");
$shape->createBreak();	
$textRun = $shape->createTextRun('North Triangle Area, Quezon City');
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");
$shape->createBreak();	


$shape = $slide->createRichTextShape();
$shape->setHeight(59);
$shape->setWidth(528);
$shape->setOffsetX(0);
$shape->setOffsetY(585);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER );

$db=new mysqli("localhost","root","","training");
$sqlFooter1="SELECT * FROM template where document_type='diploma' and content_type like 'footer%%1' order by content_order";
$rsFooter1=$db->query($sqlFooter1);
$nmFooter1=$rsFooter1->num_rows;


$rowFooter1=$rsFooter1->fetch_assoc();
$textRun = $shape->createTextRun(strtoupper($rowFooter1['content']));
$textRun->getFont()->setBold(true);
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");

$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );

$shape->createBreak();	
$rowFooter1=$rsFooter1->fetch_assoc();

$textRun = $shape->createTextRun($rowFooter1['content']);
$textRun->getFont()->setBold(false);
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");

$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );


if($nmFooter1>=3){
	$rowFooter1=$rsFooter1->fetch_assoc();
	$shape->createBreak();	
	$textRun = $shape->createTextRun($rowFooter1['content']);
	$textRun->getFont()->setBold(false);
	$textRun->getFont()->setSize(15);
	$textRun->getFont()->setName("Calibri");

	$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );

}

$shape = $slide->createRichTextShape();
$shape->setHeight(83);
$shape->setWidth(614);
$shape->setOffsetX(415);
$shape->setOffsetY(585);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER );

$db=new mysqli("localhost","root","","training");
$sqlFooter2="SELECT * FROM template where document_type='diploma' and content_type like 'footer%%2' order by content_order";
$rsFooter2=$db->query($sqlFooter2);
$nmFooter2=$rsFooter2->num_rows;

$rowFooter2=$rsFooter2->fetch_assoc();	
$textRun = $shape->createTextRun(strtoupper($rowFooter2['content']));
$textRun->getFont()->setBold(true);
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");

$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );

$shape->createBreak();
$rowFooter2=$rsFooter2->fetch_assoc();	
$textRun = $shape->createTextRun($rowFooter2['content']);
$textRun->getFont()->setBold(false);
$textRun->getFont()->setSize(15);
$textRun->getFont()->setName("Calibri");

$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );

if($nmFooter2>=3){
	$rowFooter2=$rsFooter2->fetch_assoc();
	$shape->createBreak();	
	$textRun = $shape->createTextRun($rowFooter2['content']);
	$textRun->getFont()->setBold(false);
	$textRun->getFont()->setSize(15);
	$textRun->getFont()->setName("Calibri");

	$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );

}


echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $slide->createRichTextShape();
$shape->setHeight(46);
$shape->setWidth(859);
$shape->setOffsetX(49);
$shape->setOffsetY(260);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER );

$textRun = $shape->createTextRun($traineeName);
$textRun->getFont()->setBold(true);
$textRun->getFont()->setSize(24);
$textRun->getFont()->setName("Arial Black");

$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );
	
echo date('H:i:s') . " Write to PowerPoint2007 format\n";
$objWriter = PHPPowerPoint_IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');
$objWriter->save("printout/".$diplomaFilename);

echo "<script language='javascript'>";
echo "window.close();";

echo "</script>";

?>