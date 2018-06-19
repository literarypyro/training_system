
<script language="javascript">
var currentEventId="";
var currentDates=new Array();
var currentAttendance=new Array();
</script>

<?php
if(isset($_POST['submitEventID'])){
	
	//$db=new mysqli("localhost","root","","evaluation");
	$db=retrieveEvaluationDb();
	$submitEventID=$_POST['submitEventID'];
	$submitDates=explode(",",$_POST['submitDates']);
	for($i=1;$i<count($submitDates);$i++){

		$submitAttendance[$submitDates[$i]]=explode(",",$_POST['submitAttendance-'.$submitDates[$i]]);
	}
	$sql="delete from attendance where event_id='".$submitEventID."'";	
	$rs=$db->query($sql);

	for($i=1;$i<count($submitDates);$i++){
		for($k=1;$k<count($submitAttendance[$submitDates[$i]]);$k++){
			$sql="insert into attendance(event_id,trainee_id,date) values ('".$submitEventID."','".$submitAttendance[$submitDates[$i]][$k]."','".$submitDates[$i]."')";	
			$rs=$db->query($sql);
		}
	

	}


}
?>
<?php
	$dateLatest=date("Y-m-d",strtotime(date("Y-m-d")."-14 days"));
//	$dateLatest.=" 23:23:59";

	//$db=new mysqli("localhost","root","","training");
	
	$db=retrieveTrainingDb();	
	
	
	$sql="select *,(select count(training_event_id) from class_instance where training_event_id=training_instance.id) as trainee_count from training_instance where start_date>'".$dateLatest."' order by start_date";
	//$sql="select *,(select count(training_event_id) from class_instance where training_event_id=training_instance.id) as trainee_count from training_instance order by start_date desc";

	$rs2=$db->query($sql);
	$nm2=$rs2->num_rows;
		

//	$sqlDate="select *,min(start_time) as earliest,max(end_time) as latest from training_schedule  inner join training_instance on training_instance.id=training_schedule.event_id group  by event_id,date order by start_date desc";

	$sqlDate="select *,min(start_time) as earliest,max(end_time) as latest from training_schedule  inner join training_instance on training_instance.id=training_schedule.event_id where start_date>'".$dateLatest."' group  by event_id,date";
	$rsDate=$db->query($sqlDate);

	$nmDate=$rsDate->num_rows;	
	
	$sqlTrainee="select * from trainee_list";
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;	


	
echo "<script language='javascript'>";
echo "var trainee=new Array();";
echo "var traineeCount=1;";
for($i=0;$i<$nmTrainee;$i++){
	$rowTrainee=$rsTrainee->fetch_assoc();

	echo "trainee['".$rowTrainee['id']."']=new Array();";
	echo "trainee['".$rowTrainee['id']."']['ID']='".$rowTrainee['id']."';";
	echo "trainee['".$rowTrainee['id']."']['name']='".strtoupper($rowTrainee['lastName']).", ".$rowTrainee['firstName']."';";
	echo "trainee['".$rowTrainee['id']."']['department']=\"".$rowTrainee['department']."\";";
	echo "trainee['".$rowTrainee['id']."']['designation']=\"".$rowTrainee['designation']."\";";
}
echo "</script>";


echo "<script language='javascript'>";
echo "var trainingEvent=new Array();";
echo "var trainingEventCount=1;";

echo "var dates=new Array();";
echo "var datesCount=new Array();";

echo "var attendance=new Array();";
echo "var attendanceCount=new Array();";

echo "var students=new Array();";
echo "var studentCount=new Array();";

	for($i=0;$i<$nm2;$i++){
		if($i==0){
			$eventId=$row2['id'];
		}

		$row2=$rs2->fetch_assoc();
		echo "trainingEvent[trainingEventCount]=new Array();";
		echo "trainingEvent[trainingEventCount]['title']=\"".$row2['training_title']."\";";
		echo "trainingEvent[trainingEventCount]['batch']=\"".$row2['batch_number']."\";";
		echo "trainingEvent[trainingEventCount]['start_time']=\"".date("F d, Y",strtotime($row2['start_date']))."\";";
		echo "trainingEvent[trainingEventCount]['end_time']=\"".date("F d, Y",strtotime($row2['end_date']))."\";";
		echo "trainingEvent[trainingEventCount]['ID']=\"".$row2['id']."\";";	
//		echo "trainingEventCount++;";
		
		echo "students['Event_".$row2['id']."']=new Array();";	
		echo "studentCount['Event_".$row2['id']."']=1;";

		
		echo "attendance['Event_".$row2['id']."']=new Array();";
		echo "attendanceCount['Event_".$row2['id']."']=new Array();";

		echo "dates['".$row2['id']."']=new Array();";
		echo "datesCount['".$row2['id']."']=1;";	
		
		
		//$dbAttendance=new mysqli("localhost","root","","training");
		
		
		$dbAttendance=retrieveTrainingDb();	
		$sqlAttendance="select * from training_schedule where event_id='".$row2['id']."'";	
		$rsAttendance=$dbAttendance->query($sqlAttendance);
		$nmAttendance=$rsAttendance->num_rows;
		if($nmAttendance>0){
			for($m=0;$m<$nmAttendance;$m++){
			$rowAttendance=$rsAttendance->fetch_assoc();
				echo "attendance['Event_".$rowAttendance['event_id']."']['".date("Y-m-d",strtotime($rowAttendance['date']))."']=new Array();";
				echo "attendanceCount['Event_".$rowAttendance['event_id']."']['".date("Y-m-d",strtotime($rowAttendance['date']))."']=1;";
			
			
			}
		}	
		
		//$dbAttendance=new mysqli("localhost","root","","evaluation");
		$db=retrieveEvaluationDb();
		$sqlAttendance="select * from attendance where event_id='".$row2['id']."'";
		$rsAttendance=$dbAttendance->query($sqlAttendance);
		$nmAttendance=$rsAttendance->num_rows;	
		if($nmAttendance>0){
			for($m=0;$m<$nmAttendance;$m++){
			$rowAttendance=$rsAttendance->fetch_assoc();

				echo "attendance['Event_".$rowAttendance['event_id']."']['".date("Y-m-d",strtotime($rowAttendance['date']))."'][attendanceCount['Event_".$rowAttendance['event_id']."']['".date("Y-m-d",strtotime($rowAttendance['date']))."']]='".$rowAttendance['trainee_id']."';";
			
				
				echo "attendanceCount['Event_".$rowAttendance['event_id']."']['".date("Y-m-d",strtotime($rowAttendance['date']))."']++;";

			}
		}		

	//	$dbClass=new mysqli("localhost","root","","training");
		$dbClass=retrieveTrainingDb();	
		$sqlClass="select * from class_instance where training_event_id='".$row2['id']."'";
		$rsClass=$dbClass->query($sqlClass);
		$nmClass=$rsClass->num_rows;

		for($m=0;$m<$nmClass;$m++){
			$rowClass=$rsClass->fetch_assoc();
			echo "students['Event_".$rowClass['training_event_id']."'][studentCount['Event_".$rowClass['training_event_id']."']]='".$rowClass['traineeId']."';";	
			echo "studentCount['Event_".$rowClass['training_event_id']."']++;";

		}

		echo "trainingEventCount++;";		
	}

	for($i=0;$i<$nmDate;$i++){
		$rowDate=$rsDate->fetch_assoc();
		echo "dates['".$rowDate['event_id']."'][datesCount[\"".$rowDate['event_id']."\"]]=new Array();";

		echo "dates['".$rowDate['event_id']."'][datesCount[\"".$rowDate['event_id']."\"]]['ID']='".date("Y-m-d",strtotime($rowDate['date']))."';";

		echo "dates['".$rowDate['event_id']."'][datesCount[\"".$rowDate['event_id']."\"]]['label']='".date("F d, Y",strtotime($rowDate['date'])).", ".date("hiA",strtotime($rowDate['earliest']))." - ".date("hiA",strtotime($rowDate['latest']))."';";
		echo "dates['".$rowDate['event_id']."'][datesCount[\"".$rowDate['event_id']."\"]]['classLabel']='".date("F d, Y",strtotime($rowDate['date']))."';";
		echo "datesCount[\"".$rowDate['event_id']."\"]++;";
	}
	

echo "</script>";	
	

?>


<script language="javascript">

var searchEventGrid=new Array();
var searchEventCount=1;
function iterateTraining(pgindex,action){
	var selectedTrainingID="";
	if(action=="prev"){
		if((pgindex==1)||(pgindex=="")){
			alert("You have reached the end of the record.");
		
		}
		else {
			document.getElementById('trainingPageNumber').value=pgindex*1-1;
			document.getElementById('training_title').value=trainingEvent[pgindex*1-1]['title'];
			document.getElementById('batch_no').value=trainingEvent[pgindex*1-1]['batch'];
			document.getElementById('event_period').value="From "+(trainingEvent[pgindex*1-1]['start_time'])+" to "+(trainingEvent[pgindex*1-1]['end_time']);
			selectedTrainingID=trainingEvent[pgindex*1-1]['ID'];


			
		}
	}
	else if(action=="next"){
		
		if(pgindex==trainingEventCount*1-1){
			
			alert("You have reached the end of the record.");
		
		}
		else {
			
			document.getElementById('trainingPageNumber').value=pgindex*1+1;

			document.getElementById('training_title').value=trainingEvent[pgindex*1+1]['title'];

			document.getElementById('batch_no').value=trainingEvent[pgindex*1+1]['batch'];
			document.getElementById('event_period').value="From "+(trainingEvent[pgindex*1+1]['start_time'])+" to "+(trainingEvent[pgindex*1+1]['end_time']);

			selectedTrainingID=trainingEvent[pgindex*1+1]['ID'];


		}
	
	
	}
	else {
		if(pgindex>trainingEventCount*1-1){
			alert("You have reached the end of the record.");
			pgindex=trainingEventCount*1-1;
		}		
		if(pgindex<1){
			alert("You have reached the end of the record.");
			pgindex=1;
			
		
		}
		document.getElementById('trainingPageNumber').value=pgindex*1;

		document.getElementById('training_title').value=trainingEvent[pgindex*1]['title'];
		document.getElementById('batch_no').value=trainingEvent[pgindex*1]['batch'];
		document.getElementById('event_period').value="From "+(trainingEvent[pgindex*1]['start_time'])+" to "+(trainingEvent[pgindex*1]['end_time']);

		selectedTrainingID=trainingEvent[pgindex*1]['ID'];

			
	
	}
	
	currentEventId="";
	currentDates.length=0;
	currentAttendance.length=0;
	
	fillAttendanceDates(selectedTrainingID);
	getTheAttendance(1);
	fillClassList();	
	variablesForSubmission(selectedTrainingID); 
	getCurrent(1);
}

function searchEvent(searchkey){

	var searchWord=searchkey;
	
	if(searchWord==""){
		searchWord="xxxxxxxxxxxxxx";
	}
	searchEventGrid.length=0;
	
	for(i=1;i<(trainingEventCount);i++){
		if(trainingEvent[i]['title'].toLowerCase()==searchWord.toLowerCase()){
		
			searchEventGrid[searchEventCount]=new Array();
	
			searchEventGrid[searchEventCount]['index']=i;
			searchEventGrid[searchEventCount]['ID']=trainingEvent[i]['ID'];
			searchEventGrid[searchEventCount]['title']=trainingEvent[i]['title'];
			searchEventGrid[searchEventCount]['batch']=trainingEvent[i]['batch'];
			searchEventGrid[searchEventCount]['period']="From "+trainingEvent[i]['start_time']+" to "+trainingEvent[i]['end_time'];
			searchEventCount++;
			
		}
		else if((trainingEvent[i]['title'].toLowerCase()).indexOf(searchWord.toLowerCase())>-1){
			searchEventGrid[searchEventCount]=new Array();

			searchEventGrid[searchEventCount]['index']=i;
			searchEventGrid[searchEventCount]['ID']=trainingEvent[i]['ID'];
			searchEventGrid[searchEventCount]['title']=trainingEvent[i]['title'];
			searchEventGrid[searchEventCount]['batch']=trainingEvent[i]['batch'];
			searchEventGrid[searchEventCount]['period']="From "+trainingEvent[i]['start_time']+" to "+trainingEvent[i]['end_time'];
			searchEventCount++;
		}
		else {
		}
	}

	if(searchEventCount>1){
		var optionsGrid="";
		optionsGrid+="<td  style='background-color: #00cc66;color: black;'>Look for Training Program Here:</td>";
		optionsGrid+="<td style='background-color: white;color:black;'>";
		optionsGrid+="<select multiple=true id='dynamicSel' name='dynamicSel'>";
		for(i=1;i<searchEventCount;i++){
			optionsGrid+="<option value='"+searchEventGrid[i]['index']+"' >";
			optionsGrid+=searchEventGrid[i]['title']+" #"+searchEventGrid[i]['batch']+", "+searchEventGrid[i]['period'];
			optionsGrid+="</option>";
			
		}
			

		optionsGrid+="</select>";
		optionsGrid+="<br><input type=button value='Get Training Program' onclick=retrieveEvent(document.getElementById('dynamicSel').value) />";
		optionsGrid+="</td>";
			
		document.getElementById('searchResults').innerHTML=optionsGrid;
	}
	else {
		document.getElementById('searchResults').innerHTML="<td style='background-color: #ed5214;'></td><td style='background-color: #ed5214;'></td>";
	}
	
	searchEventCount=1;
	
}
function retrieveEvent(item){
	iterateTraining(item*1,"index");

	document.getElementById('searchResults').innerHTML="<td style='background-color: #ed5214;'></td><td style='background-color: #ed5214;'></td>";
}

function fillAttendanceDates(trainingID){

	var attendanceGrid="<tr><th style='background-color:#ed5214;color:white;' colspan=2>Attendance Date</th></tr>";
	attendanceGrid+="<tr><td align=center style='background-color:white; color:black;'><select name='attendanceDate' id='attendanceDate' onchange='getTheAttendance(this.value)'>";
	var attendanceGridCount=dates[trainingID].length;	

	for(i=1;i<attendanceGridCount;i++){
		if(i==1){
			//getTheAttendance(i);
		}
		attendanceGrid+="<option value='"+i+"'>"+dates[trainingID][i]['label']+"</option>";
			
	}

	attendanceGrid+="</select>";
	attendanceGrid+="<input type='hidden' name='eventID' id='eventID' value='"+trainingID+"' />";
	
	attendanceGrid+="</td></tr>";
	attendanceGrid+="<tr><td style='background-color: #ed5214;' colspan=2></td></tr>";
	attendanceGrid+="<tr><td align=center colspan=2><input type=submit value='Printout Attendance Form' /></td></tr>";
	document.getElementById('attendanceTable').innerHTML=attendanceGrid;
}
function getTheAttendance(dateSelected){
	var attendanceGrid="<tr><th style='background-color:#ed5214;color:white;' colspan=2>Attendance</th></tr>";
	var selectedTrainingID=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];	
	var dateForAttendance=dates[selectedTrainingID][dateSelected]['ID'];
	//alert(selectedTrainingID);
//	var attendanceGridCount=dates[trainingID].length;	
	attendanceGrid+="<tr><th style='background-color: #00cc66;color:black;' colspan=2>"+dates[selectedTrainingID][dateSelected]['label']+"</th></tr>";

	
	if(attendanceCount["Event_"+selectedTrainingID][dateForAttendance]>1){
		var attendanceGridCount=attendance["Event_"+selectedTrainingID][dateForAttendance].length;	

		for(i=1;i<(attendanceGridCount);i++){
			attendanceGrid+="<tr><td align=center style='background-color: white;color:black;'>"+trainee[attendance["Event_"+selectedTrainingID][dateForAttendance][i]]['name']+"</td></tr>";
		}	
	}

	attendanceGrid+="<tr><td style='background-color: #ed5214;' colspan=2></td></tr>";
	document.getElementById('attendanceTrainee').innerHTML=attendanceGrid;
	
}
function fillClassList(){
	var selectedTrainingID=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];		
	var attendanceGrid="<table id='classindex' style=' border: 1px solid gray;'  width=100%>";
	attendanceGrid+="<tr><th style='background-color:#ed5214;color:white;' colspan=2>Class List</th></tr>";

	var attendanceGridCount=students["Event_"+selectedTrainingID].length;	

	for(i=1;i<attendanceGridCount;i++){
		attendanceGrid+="<tr><td id='trainee"+i+"' align=center style='background-color: white;color:black;'>";
		attendanceGrid+="<a href='#classindex' style='text-decoration:none; color:black;' onclick=\"markElement('add','"+i+"')\">";
	//	alert(students["Event_"+selectedTrainingID][i]);
		attendanceGrid+=trainee[students["Event_"+selectedTrainingID][i]]['name'];
		attendanceGrid+="</a></td></tr>";
	}	
	attendanceGrid+="</table>";
	attendanceGrid+="<input type=hidden id='selectedTrainee' />";


	
	document.getElementById('studentList').innerHTML=attendanceGrid;
	

	attendanceGrid="<table  style=' border: 1px solid gray;'  width=100%>";	
	attendanceGrid+="<tr><th style='background-color:#ed5214;color:white;' colspan=2>Select Date of Attendance</th></tr>";
	
	attendanceGrid+="<tr><td align=center style='background-color:white; color:black;'>";
	attendanceGrid+="<select id='currentSelectedDate' onchange='getCurrent(this.value)'>";

	var attendanceGridCount=dates[selectedTrainingID].length;	
	
	for(i=1;i<attendanceGridCount;i++){

		attendanceGrid+="<option value='"+i+"'>"+dates[selectedTrainingID][i]['label']+"</option>";
			
	}

	attendanceGrid+="</select></td></tr>";
	
	
	attendanceGrid+="<tr><td style='background-color: #ed5214;' colspan=2></td></tr>";
	attendanceGrid+="</table>";	
	attendanceGrid+="<table id='currentAttendance' name='currentAttendance' style=' border: 1px solid gray;'  width=100%>";	
	attendanceGrid+="<tr><th style='background-color:#00cc66;color:white;' colspan=2></th></tr>";

	
	attendanceGrid+="</table>";	
	attendanceGrid+="<input type=hidden id='traineeForRemoval' name='traineeForRemoval' />";
	
	document.getElementById('attendanceDynamic').innerHTML=attendanceGrid;	
	
	attendanceGrid="<input type=button value='>> - Entry' onclick='addAttendance()' />";
	attendanceGrid+="<br>";
	attendanceGrid+="<br>";

	attendanceGrid+="<input type=button value='<< - Remove' onclick='removeAttendance()' />";
	document.getElementById('changeAttendance').innerHTML=attendanceGrid;	
	
	attendanceGrid="<form id='submitForm' name='submitForm' action='trainer_aid.php?a=record' method=post>";

	attendanceGrid+="<input type=hidden id='submitEventID' name='submitEventID'  />";
	attendanceGrid+="<input type=hidden id='submitDates' name='submitDates'	/>";
	
	
	var attendanceGridCount=dates[selectedTrainingID].length;	
	for(i=1;i<attendanceGridCount;i++){
		attendanceGrid+="<input type=hidden id='submitAttendance-"+dates[selectedTrainingID][i]['ID']+"' name='submitAttendance-"+dates[selectedTrainingID][i]['ID']+"' />";
			
	}	
	
	


	attendanceGrid+="<input type=button value='Submit Training Attendance' onclick='prepareSubmission()' />";
	attendanceGrid+="</form>";

	document.getElementById('submitButton').innerHTML=attendanceGrid;	
	
	//getCurrent(1);
}
function variablesForSubmission(trainingID){

	var selectedTrainingID=trainingID;

	currentEventId=selectedTrainingID;
	var attendanceGridCount=dates[trainingID].length;	

	for(i=1;i<attendanceGridCount;i++){
		currentDates[i]=dates[trainingID][i]['ID'];

		if(attendanceCount["Event_"+selectedTrainingID][currentDates[i]]>1){
			var currentGridCount=attendanceCount["Event_"+selectedTrainingID][currentDates[i]];	

			currentAttendance[currentDates[i]]=new Array();			
			
			for(m=1;m<currentGridCount;m++){
				currentAttendance[currentDates[i]][m]=attendance["Event_"+selectedTrainingID][currentDates[i]][m];
			}	
		}
		else {
			currentAttendance[currentDates[i]]=new Array();			

		}
	}

}
function getCurrent(dateSelected){
	refillAttendanceList();
/*
	var attendanceGrid="<tr><th style='background-color:#ed5214;color:white;' colspan=2>Attendance</th></tr>";
	var selectedTrainingID=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];	
	var dateForAttendance=dates[selectedTrainingID][dateSelected]['ID'];

	attendanceGrid+="<tr><th style='background-color: #00cc66;color: white;' colspan=2>"+dates[selectedTrainingID][dateSelected]['label']+"</th></tr>";

	
	if(attendanceCount["Event_"+selectedTrainingID][dateForAttendance]>1){
		var attendanceGridCount=attendance["Event_"+selectedTrainingID][dateForAttendance].length;	

		for(i=1;i<attendanceGridCount;i++){
			attendanceGrid+="<tr><td align=center style='background-color: white;color:black;'>"+trainee[attendance["Event_"+selectedTrainingID][dateForAttendance][i]]['name']+"</td></tr>";
		}	
	}
	attendanceGrid+="<tr><td style='background-color: #ed5214;' colspan=2></td></tr>";
	document.getElementById('currentAttendance').innerHTML=attendanceGrid;
*/
}
function refillAttendanceList(){
	var selectedID=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	var dateSelected=dates[selectedID][document.getElementById('currentSelectedDate').value]['ID'];
	
	var attendanceGrid="<tr><th style='background-color:#ed5214;color:white;' colspan=2>Attendance</th></tr>";
	attendanceGrid+="<tr><th style='background-color: #00cc66;color: black;' colspan=2>"+dates[selectedID][document.getElementById('currentSelectedDate').value]['label']+"</th></tr>";

	
//	if(attendanceCount["Event_"+selectedTrainingID][dateSelected]>1){
		var attendanceGridCount=currentAttendance[dateSelected].length;	
		for(i=1;i<attendanceGridCount;i++){
			attendanceGrid+="<tr><td id='removal"+i+"' align=center style='background-color: white;color:black;'><a style='text-decoration:none;color:black;' href='#' onclick='markElement(\"remove\",\""+i+"\")' >";
			attendanceGrid+=trainee[currentAttendance[dateSelected][i]]['name'];
			attendanceGrid+="</a></td></tr>";
		}	
//	}
	attendanceGrid+="<tr><td style='background-color: #ed5214;' colspan=2></td></tr>";
	document.getElementById('currentAttendance').innerHTML=attendanceGrid;	
}

function prepareSubmission(){
	var vbmsg=confirm("Are you ready to submit the attendance records? Double-check if necessary.");
	if(vbmsg){
		document.getElementById('submitEventID').value=currentEventId;
		document.getElementById('submitDates').value=currentDates;
		var attendanceGridCount=dates[currentEventId].length;	
		for(i=1;i<attendanceGridCount;i++){
			document.getElementById('submitAttendance-'+dates[currentEventId][i]['ID']).value=currentAttendance[currentDates[i]];

		}	
		document.forms['submitForm'].submit();
	}
}
function markElement(purpose,element){
	
	if(purpose=="add"){
		if(document.getElementById('selectedTrainee').value==""){
			document.getElementById('trainee'+element).style.backgroundColor="#95cbe9";
		}
		else {
			document.getElementById('trainee'+document.getElementById('selectedTrainee').value).style.backgroundColor="white";
			document.getElementById('trainee'+element).style.backgroundColor="#95cbe9";
		}
		document.getElementById('selectedTrainee').value=element;
	}
	else if(purpose=="remove"){
		if(document.getElementById('traineeForRemoval').value==""){
			document.getElementById('removal'+element).style.backgroundColor="#95cbe9";
		}
		else {
			document.getElementById('removal'+document.getElementById('traineeForRemoval').value).style.backgroundColor="white";
			document.getElementById('removal'+element).style.backgroundColor="#95cbe9";
		}
		document.getElementById('traineeForRemoval').value=element;
	}
}
function searchParticipant(trainArray,traineVa){
	var algorithm=false;
	for(i=1;i<trainArray.length;i++){
		if(traineVa==trainArray[i]){
			algorithm=true;
		}
	}
	return algorithm;
}
function addAttendance(){

	var traineeSelected=document.getElementById('selectedTrainee').value;
	var selectedID=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	var dateSelected=dates[selectedID][document.getElementById('currentSelectedDate').value]['ID'];

	if(traineeSelected==""){
		alert("Error.  You haven't selected a participant.");
		
	}
	else {
		
		if(attendanceCount["Event_"+selectedID][dateSelected]>1){

			if(searchParticipant(currentAttendance[dateSelected],students["Event_"+selectedID][traineeSelected])){
				alert("Participant already in record");
			}
			else {

				var counter=currentAttendance[dateSelected].length;
				
				if(counter==0){ counter=1; }

				currentAttendance[dateSelected][counter]=students["Event_"+selectedID][document.getElementById('selectedTrainee').value];
			
				counter++;			
				document.getElementById('trainee'+document.getElementById('selectedTrainee').value).style.backgroundColor="white";
				document.getElementById('selectedTrainee').value="";		
				refillAttendanceList();

			}	
		}
		else {

			//currentAttendance[dateSelected]=new Array();
			if(searchParticipant(currentAttendance[dateSelected],students["Event_"+selectedID][traineeSelected])){
				alert("Participant already in record");
			}
			else {

				var counter=currentAttendance[dateSelected].length;
				if(counter==0){ counter=1; }

				//currentAttendance[dateSelected][counter]=students["Event_"+selectedID][document.getElementById('selectedTrainee').value];
				currentAttendance[dateSelected][counter]=students["Event_"+selectedID][document.getElementById('selectedTrainee').value];

				
				counter++;		
				document.getElementById('trainee'+document.getElementById('selectedTrainee').value).style.backgroundColor="white";
				document.getElementById('selectedTrainee').value="";		
				refillAttendanceList();
				
			}
		}
	}
}
function removeAttendance(){

	var traineeSelected=document.getElementById('traineeForRemoval').value;
	var selectedID=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	var dateSelected=dates[selectedID][document.getElementById('currentSelectedDate').value]['ID'];

	if(traineeSelected==""){
		alert("Error.  You haven't selected a participant.");
	}
	else {
		currentAttendance[dateSelected].splice(traineeSelected,1);
		refillAttendanceList();
	}

}

</script>

<?php
if(isset($_POST['event_id'])){
	$eventId=$_POST['event_id'];
}
else {
	if(isset($_POST['eventID'])){
		$eventId=$_POST['eventID'];
	
	}
	else {
		$eventId="";
	}
}


?>


<table align=center style=" border: 1px solid gray;" width=90%>
<tr>
<th valign=center style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Record Attendance</h1>
</th>
</tr>
</table>

<br>
<table  align=center>
<tr>
<td colspan=2 width=50%>

<table style=" border: 1px solid gray;" width=400>
<tr>
<th style='background-color:#ed5214;color:white;' colspan=2>Select Training Event</th>
</tr>
<tr>
<td style='background-color: #00cc66;color: black;'>Training Title</td>
<td style="background-color:  white; color:black; border: 1px solid gray;" ><input type=text name='training_title' id='training_title' size=30%  onkeyup="searchEvent(document.getElementById('training_title').value);" ><input type=button value='?'  onclick="searchEvent(document.getElementById('training_title').value);"  /></td>
</tr>
<tr>
<td style='background-color: #00cc66;color: black;'>Batch Number</td>
<td style="background-color:  white; color:black; border: 1px solid gray;" ><input type=text name='batch_no' id='batch_no' size=10%></td>
</tr>

<tr>
<td style='background-color: #00cc66;color: black;'>Period</td>
<td style="background-color:  white; color:black; border: 1px solid gray;" ><textarea name='event_period' id='event_period' cols=30%></textarea></td>
</tr>
		<tr id='searchResults' name='searchResults' >
		<td style='background-color: #ed5214;'></td>
		<td style='background-color: #ed5214;'></td>
		</tr>		
		<tr>
			<td colspan=2 style='background-color: #ed5214;' align=center>
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'prev');"><<</a>
			<input id='trainingPageNumber' style="text-align:center" type="text" name="trainingPageNumber" size=4 value='' onkeyup="iterateTraining(document.getElementById('trainingPageNumber').value,'index');" />	
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'next');">>></a>
			</td>
	
		</tr>
</table>
<form action='trainer_aid.php?a=record&print=1' method=post >
<table   id='attendanceTable' name='attendanceTable' width=400>
<tr>
<th style='background-color:#ed5214;color:white;' >Attendance Date</th>
</tr>
<tr >
	<td style='background-color: #ed5214;' ></td>
</tr>	
</table>
</form>
</td>
<td width=50% valign=top>
<table  style=" border: 1px solid gray;"  id='attendanceTrainee' name='attendanceTrainee' width=100%>
<tr>
<th style='background-color:#ed5214;color:white;' colspan=2>Attendance</th>
</tr>
<tr >
	<td style='background-color: #ed5214;' colspan=2></td>
</tr>	
</table>


</td>

</tr>
<tr>
	<th valign=center style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 >
	</th>

</tr>
<tr>
<td valign=top id='studentList'>
</td>
<td valign=center align=center id='changeAttendance'>
</td>
<td valign=top id='attendanceDynamic'>
</td>

</tr>

<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align=center id='submitButton'></td>
</tr>
<!--
<tr>
	<th valign=center style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 >
	</th>
</tr>
-->

</table>
<?php
if(isset($_GET['print'])){
	require("generate_attendance_form.php");
}
else {
?>
<br>
<br>
<?php
}
?>