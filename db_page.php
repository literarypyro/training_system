<?php
function retrieveHelpdeskDb(){
	//$db=new mysqli("localhost","root","","helpdesk_system");
//	$db=new mysqli("aida-support","admin","123456","helpdesk_system");
	$db=new mysqli("addeveraturda-s","admin","123456","helpdesk_system");

	//if(isNullDb($db)=="false"){
		//$db=new mysqli("","root","123456","helpdesk_backup");
	//}
	return $db;

}
function localOnlyDb(){
	$db=new mysqli("localhost","root","123456","training");
	//if(isNullDb($db)=="false"){
		//$db=new mysqli("localhost","root","123456","helpdesk_backup");
//	}
	return $db;
}
function retrieveTrainingDb(){
	//$db=new mysqli("localhost","root","","training");
	//$db=new mysqli("aida-support","admin","123456","training");
	//$db=new mysqli("addeveraturda-s","admin","123456","training");

	$db=new mysqli("localhost","root","","training");

	return $db;

}

function retrieveTemporaryDb(){
	$db=new mysqli("addeveraturda-s","admin","123456","temporary_training");

	return $db;



}




function retrieveEvaluationDb(){
	$db=new mysqli("localhost","root","","evaluation");

	return $db;
}

function retrieveGradingDb(){
	$db=new mysqli("addeveraturda-s","admin","123456","training");
//	$db=new mysqli("aida-support","admin","123456","grading_system");
//	$db=new mysqli("localhost","root","","grading_system");
	return $db;
}
?>