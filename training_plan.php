<?php
require_once 'PHPWord/PHPWord.php';
?>

<?php

$event_id=1;

if(isset($_POST['event_id'])){
$event_id=$_POST['event_id'];

}


//$db=new mysqli("localhost","root","","training");
$db=retrieveTrainingDb();
$sql="select * from class_instance where training_event_id='".$event_id."'";
$rs=$db->query($sql);

$participant_count=$rs->num_rows;

$PHPWord = new PHPWord();


$section = $PHPWord->createSection();

// Define table style arrays
//$styleTable = array('borderSize'=>6, 'borderColor'=>'006699', 'cellMargin'=>80);
$styleFirstRow = array('borderBottomSize'=>18,  'bgColor'=>'000000','align'=>'center');
//'borderBottomColor'=>'0000FF',
// Define cell style arrays
$styleCell = array('valign'=>'top','align'=>'center');
//$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);

// Define font style for first row
$fontStyle = array('bold'=>true,'size'=>'20', 'Color'=>'ffffff');

// Add table style
$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

// Add table
$table = $section->addTable('myOwnTableStyle');

$db=retrieveTrainingDb();
//$db=new mysqli("localhost","root","","training");
$trainingSQL="select * from training_instance where id='".$event_id."'";
$rsSQL=$db->query($trainingSQL);
$rowSQL=$rsSQL->fetch_assoc();
$trainingTitle=$rowSQL['training_title'];
$start_date=date("F d",strtotime($rowSQL['start_date']));
$end_date=date("F d, Y",strtotime($rowSQL['end_date']));
$batch_number=$rowSQL['batch_number'];

// Add row
$table->addRow(900);
$cell=$table->addCell(10500, $styleCell);
$cell->addText('TRAINING PLAN FOR '.strtoupper($trainingTitle).', BATCH #'.$batch_number, $fontStyle, array('align'=>'center'));


$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');

$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

$objectiveClause="This training plan is designed to obtain the following objectives:";
$section->addText("Objective:", array('name'=>'Arial', 'size'=>'16'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText($objectiveClause, array('name'=>'Arial', 'size'=>'12','italic'=>'true'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$objectiveCount=0;

$objectiveDB=retrieveEvaluationDb();
//$objectiveDB=new mysqli("localhost","root","","evaluation");
$objectiveSQL="select * from plan_objectives where event_id='".$event_id."'";
$objectiveRS=$objectiveDB->query($objectiveSQL);
$objectiveNM=$objectiveRS->num_rows;

for($i=0;$i<$objectiveNM;$i++){
	$objectiveRow=$objectiveRS->fetch_assoc();
	$objectiveItem[$objectiveCount]=$objectiveRow['objectives'];
	$objectiveCount++;
}

if($objectiveCount>0){
	//$listStyle = array('listType'=>PHPWord_Style_ListItem::TYPE_NUMBER);

	for($i=0;$i<$objectiveCount;$i++){
	
		$section->addListItem($objectiveItem[$i], 0, array('name'=>'Arial', 'size'=>'12'), null);
		$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

	}
	
}




$inclusiveDates="select * from training_schedule where event_id='".$event_id."' group by date";


$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

$noOfTrainees="select * from class_list where training_events_id='".$event_id."'";
$rsTrainees=$db->query($noOfTrainees);
$nmTrainees=$rsTrainees->num_rows;

$traineeClause="For this training, ".$nmTrainees." participants will be attending the course.";


$section->addText("No. of Trainees:", array('name'=>'Arial', 'size'=>'16'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText($traineeClause, array('name'=>'Arial', 'size'=>'12','italic'=>'true'), array('spacing'=>0));


$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

$dateClause="This training shall be conducted from ".$start_date." to ".$end_date.".";

$section->addText("Inclusive Dates:", array('name'=>'Arial', 'size'=>'16'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText($dateClause, array('name'=>'Arial', 'size'=>'12','italic'=>'true'), array('spacing'=>0));

$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

$outlineClause="This course shall be conducted in accordance with the following outline:";
$section->addText("Course Outline:", array('name'=>'Arial', 'size'=>'16'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText($outlineClause, array('name'=>'Arial', 'size'=>'12','italic'=>'true'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

//$courseOutline="";

//$outlineDb=new mysqli("localhost","root","","evaluation");
$outlineDb=retrieveEvaluationDb();

$outlineSchedule="select * from plan_outline where event_id='".$event_id."' order by id";
$outlineRS=$outlineDb->query($outlineSchedule);
$outlineNM=$outlineRS->num_rows;

for($i=0;$i<$outlineNM;$i++){
	$outlineRow=$outlineRS->fetch_assoc();
	
	$section->addListItem($outlineRow['outline_item'], 0, array('name'=>'Arial', 'size'=>'12'), null);
	$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
	
	
//	$itemDb=new mysqli("localhost","root","","evaluation");	
	$itemDb=retrieveEvaluationDb();
	$itemSQL="select * from plan_items where event_id='".$event_id."' and plan_outline_id='".$outlineRow['id']."' order by id";
	$itemRS=$itemDb->query($itemSQL);
	$itemNM=$itemRS->num_rows;
	
	for($m=0;$m<$itemNM;$m++){
		$itemRow=$itemRS->fetch_assoc();
		$section->addListItem($itemRow['item_description'], 1, array('name'=>'Arial', 'size'=>'12'), null);
		$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
	
	
	}
	$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
}





$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$planSchedule="";

$planClause="The tentative schedule of activities shall be based on the following, subject to change depending on the training outcome:";

$section->addText("Training Schedule:", array('name'=>'Arial', 'size'=>'16'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText($planClause, array('name'=>'Arial', 'size'=>'12','italic'=>'true'), array('spacing'=>0));

$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));



//$planDb=new mysqli("localhost","root","","evaluation");
$planDb=retrieveEvaluationDb();
$planSchedule="select * from plan_schedule where event_id='".$event_id."' group by day order by day*1";
$planRS=$planDb->query($planSchedule);
$planNM=$planRS->num_rows;

for($i=0;$i<$planNM;$i++){
	$planRow=$planRS->fetch_assoc();
	
	$section->addText("	Day ".$planRow['day'], array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
	$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
	
	
	
	$planSQL="select * from plan_schedule where event_id='".$event_id."' and day='".$planRow['day']."' order by id";
	$rsSQL=$planDb->query($planSQL);
	$nmSQL=$rsSQL->num_rows;
	
	for($m=0;$m<$nmSQL;$m++){
		$rowSQL=$rsSQL->fetch_assoc();
		$section->addListItem($rowSQL['activity'], 1, array('name'=>'Arial', 'size'=>'12'), null);
		$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
	
	
	}
	$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
}





$materialClause="The following are the training materials / modules and facilities necessary for this training course:";

$section->addText("Materials & Facilities:", array('name'=>'Arial', 'size'=>'16'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText($materialClause, array('name'=>'Arial', 'size'=>'12','italic'=>'true'), array('spacing'=>0));
		$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));

//$dbMaterial=new mysqli("localhost","root","","evaluation");
$dbMaterial=retrieveEvaluationDb();
$sqlMaterial="select * from plan_materials where event_id='".$event_id."'";
$rsMaterial=$dbMaterial->query($sqlMaterial);
$nmMaterial=$rsMaterial->num_rows;
if($nmMaterial>0){
	$listStyle = array('listType'=>PHPWord_Style_ListItem::TYPE_NUMBER);

	for($i=0;$i<$nmMaterial;$i++){
		$rowMaterial=$rsMaterial->fetch_assoc();
		$section->addListItem($rowMaterial['material'], 0, array('name'=>'Arial', 'size'=>'12'), $listStyle);
		$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));


	}
}
/*
$section->addListItem('List Item 2', 0, null, $listStyle);
$section->addListItem('List Item 3', 0, null, $listStyle);
$section->addTextBreak(2);

*/



$trainerCount=0;
$noOfTrainers="select * from trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id where event_id='".$event_id."'";
$rsTrainers=$db->query($noOfTrainers);
$nmTrainers=$rsTrainers->num_rows;
for($i=0;$i<$nmTrainers;$i++){
	$rowTrainers=$rsTrainers->fetch_assoc();
	$trainer[$trainerCount]=$rowTrainers['position']." ".$rowTrainers['firstName']." ".$rowTrainers['lastName'];

	$trainerCount++;
	
	
}

$trainerClause="";
$trainerClause="The Support Staff shall conduct this training facilitated by ";

for($i=0;$i<$trainerCount;$i++){
	if($i==0){
		$trainerClause.=$trainer[$i];

	}
	else if($i==($trainerCount*1-1)){
		$trainerClause.=" and ".$trainer[$i];

	}
	else {
		$trainerClause.=", ".$trainer[$i];


	}

}

$trainerClause.=" with the assistance of Ms. Aida D. Deveraturda.";
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText("Facilitators:", array('name'=>'Arial', 'size'=>'16'), array('spacing'=>0));

$section->addText(" ", array('name'=>'Arial', 'size'=>'12'), array('spacing'=>0));
$section->addText($trainerClause, array('name'=>'Arial', 'size'=>'12','italic'=>'true'), array('spacing'=>206));

$newFilename="Training Plan ".date("Y-m-d His");
$objWriter->save("printout/".$newFilename.".docx");

// Add cells
//$table->addCell(2000, $styleCell)->addText('Row 1', $fontStyle);

//$table->addCell(2000, $styleCell)->addText('Row 1', $fontStyle);
//$table->addCell(2000, $styleCell)->addText('Row 2', $fontStyle);
//$table->addCell(2000, $styleCell)->addText('Row 3', $fontStyle);
//$table->addCell(2000, $styleCell)->addText('Row 4', $fontStyle);
//$table->addCell(500, $styleCellBTLR)->addText('Row 5', $fontStyle);




?>
<?php
require("training_plan_form.php");
?>
<?php
echo "<br>";
echo "A Training plan was generated.  Click here: <a href='printout/".$newFilename.".docx' style='text-decoration:none;color:red;'>".$newFilename."</a>";

?>