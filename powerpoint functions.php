<?php
function startCOMObject($full_class_name)
{
	//Name of the Namespace and Class 
	$excelApplication=new COM($full_class_name);
	return $excelApplication;
}

function startCOMGiven()
{
	$objPHPPowerpoint = new PHPPowerPoint();
	return $objPHPPowerpoint;

}

function loadExistingSlide($presentationName)
{
	$objReader = PHPPowerPoint_IOFactory::createReader("IReader");
	// $objReader->setReadDataOnly(true);
	$objPHPPowerPoint="";
//	$objPHPPowerPoint = $objReader->load($presentationName, $presentationName);
	// $objPHPExcel = PHPExcel_IOFactory::load($workBookName);
//	$objWriter = PHPExcel_IOFactory::createWriter($excelAPI, 'Excel5');
	//var_dump($objPHPExcel);
	return $objPHPPowerPoint;
}


function createWorkbook($powerPointAPI,$presentationName,$action,$powerpointApp)
{
	$workbook=null;
	if($action=="create"){
		$workbook=$excelAPI->createWorkbook($workBookName,$excelApp);	
	}
	else if($action=="open"){
		$workbook=$excelAPI->openWorkbookNoParams($workBookName,$excelApp);	
	}
	else {
	}
	return $workbook;
	
}


function createSlide($powerpointAPI,$workSheetName,$action){
	if($action=="create"){
		$slide=$powerpointAPI->createSlide();
//		$excelAPI->setActiveSheetIndex(0);
//		$excelAPI->getActiveSheet()->setTitle($workSheetName);
		
	}
//	else if($action=="open"){
//		$worksheet=$excelAPI->selectExistingWorksheet($workSheetName,$workBook);		
//	}

	else if($action=="openActive"){
		$slide=$powerpointAPI->getActiveSlide();
//		$excelAPI->getActiveSheet();
	}
	return $slide;
//	return $worksheet;

}

function setCoordinates($xaxis, $yaxis)
{
	$grid[0]=$xaxis;
	$grid[1]=$yaxis;
	return $grid;
}

function setObjectSize($height, $width){
	$size[0]=$height;
	$size[1]=$width;
	
	return $size;
	

}

function setObjectProperties($location,$dimensions,$slide){
		



}





function addContent($range,$excelAPI,$content,$merged,$excelWorksheet)
{
//	$excelAPI->setWorkSheetContent($content,$range[0],$range[1],$merged,$excelWorksheet);
	$cellRange=$range[0].':'.$range[1];
    if ($merged == "true")
    {
		$excelAPI->getActiveSheet()->mergeCells($range[0].':'.$range[1]);
	
	}
//	$excelAPI->getActiveSheet()->getStyle($cellRange)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//	$excelAPI->getActiveSheet()->getStyle($cellRange)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

	$excelAPI->getActiveSheet()->getStyle($cellRange)->getAlignment()->setWrapText(true);

	$excelAPI->getActiveSheet()->setCellValue($range[0], $content);	
									
}

function addImage($range,$excelAPI,$imageName,$merged,$excelWorksheet){
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('DOTC LOGO');
	$objDrawing->setDescription('Dotc Logo');
	$objDrawing->setPath($imageName);
//$objDrawing->setHeight(36);
	$objDrawing->setWorksheet($excelAPI->getActiveSheet());
	$objDrawing->setCoordinates(strtoupper($range[0]));


//	setImagesRoot('http://www.example.com');
//	$excelAPI->insertImage($imageName,$range[0],$range[1],$merged,$excelWorksheet);
}

function getContent($range,$excelAPI,$excelWorksheet){
	$content=$excelAPI->getWorksheetContent($range[0],$range[1],$excelWorksheet);		
	return $content;	
}

function setCellSize($range,$height,$width,$sheet,$excelAPI){
	$excelAPI->setRangeSize($height,$width,$sheet,$range[0],$range[1]);
}

function setHeadingArea($type,$range,$merged,$excelAPI,$excelWorksheet){
	if($type=="minor"){
//		$excelAPI->getActiveSheet()->getStyle($range[0].":".$range[1])->getFont()->setName('Arial');
		$excelAPI->getActiveSheet()->getStyle($range[0].":".$range[1])->getFont()->setSize(12); 
	}
	else {
//		$excelAPI->getActiveSheet()->getStyle($range[0].':'.$range[1])->getFont()->setName('Arial');
		$excelAPI->getActiveSheet()->getStyle($range[0].':'.$range[1])->getFont()->setSize(14); 

	}

	if($merged=="true"){
		$excelAPI->getActiveSheet()->mergeCells($range[0].":".$range[1]);
	}
	$excelAPI->getActiveSheet()->getStyle($range[0].":".$range[1])->getFont()->setBold(true);
	$excelAPI->getActiveSheet()->getStyle($range[0].":".$range[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$excelAPI->getActiveSheet()->getStyle($range[0].":".$range[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	
	
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);
	$excelAPI->getActiveSheet()->getStyle($range[0].":".$range[1])->applyFromArray($styleArray);

	
	
	
}

function setCellArea($range,$excelAPI,$excelWorksheet,$excelAPI){
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);
	$excelAPI->getActiveSheet()->getStyle($range[0].":".$range[1])->applyFromArray($styleArray);
	
	
}


function styleCellArea($range,$outlined,$bold,$excelWorksheet,$excelAPI){
	if($outlined=="true"){
		$excelAPI->getActiveSheet()->getStyle($range[0].":".$range[1])->getFont()->setBold(true);
	}
	if($bold=="true"){
		//$excelAPI->tableContent($range[0],$range[1],$excelWorksheet);
	}
}
function save($slide,$powerPointAPI,$presentation)
{
	$objWriter = PHPPowerPoint_IOFactory::createWriter($powerPointAPI, 'PowerPoint2007');
	$objWriter->save($presentation);
/**
	if($workbookName==""){
		$excelAPI->saveWorkbook($workbook);
	}
	else {
		$excelAPI->saveWorkbookCopy($workbookName,$workbook);
	}
	*/
}

function printCopy($workSheet,$excelAPI){
	$notification="";
	$notification=$excelAPI->printWorksheet($worksheet);
	return $notification;
}


function getRowNumber($i){
	$pageBreak=52;
	if((($i%52)==0)&&($i>0)){
		$i=$i+3;
	}
	else {
		$i=$i+1;
	}
	return $i;

}



?>