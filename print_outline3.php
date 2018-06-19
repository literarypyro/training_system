
<?php

	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	require("powerpoint functions.php");

	include "PHPPowerpoint.php";

	include "PHPPowerpoint/IOFactory.php";

	 $filename="manual/certificate.pptx";
	 $newFilename="pptdocument1.ppt";
//	 copy($filename,$newFilename);
	 //$workSheetName="Helpdesk Form";	
	 //$workbookname=$newFilename;
	 //$excel=loadExistingWorkbook($workbookname);

	 $presentation=$newFilename;
	 $powerpoint=new PHPPowerPoint();
		// $powerpoint=loadExistingSlide($presentation);
	
 	 $slide=createSlide($powerpoint,$slideName,"create");
		$shape = $slide->createDrawingShape();

    $shape->setName('Background');
    $shape->setDescription('Background');
    $shape->setPath('images/certificate borders.jpg');
    $shape->setWidth(950);
    $shape->setHeight(720);
    $shape->setOffsetX(0);
    $shape->setOffsetY(0);

	   //  $shape = $slide->createDrawingShape();	 
	 // $shape = $PPSlide->createRichTextShape();
	 // $shape->setHeight(46);
	 // $shape->setWidth(859);
	 // $shape->setOffsetX(144);
	 // $shape->setOffsetY(267);
	 // $shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER );
	 // $textRun = $shape->createTextRun('Introduction to');
	 // $textRun->getFont()->setName("Segoe Print");

	 // $textRun->getFont()->setBold(true);
 // $textRun->getFont()->setSize(28);
 
 // $textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( '00000000' ) );
 
	
 
	 save($slideppt,$powerpoint,$presentation);
	

?>