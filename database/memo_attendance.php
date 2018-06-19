<?php
	$dateLatest=date("Y-m-d",strtotime(date("Y-m-d")."-14 days"));
	$dateLatest.=" 23:23:59";

	//$db=new mysqli("localhost","root","","training");
	$db=retrieveTrainingDb();
	$sql="select *,(select count(training_event_id) from class_instance where training_event_id=training_instance.id) as trainee_count from training_instance where start_date>'".$dateLatest."' order by start_date";
	$rs2=$db->query($sql);
	$nm2=$rs2->num_rows;
		


	
	
	$sqlTrainee="select * from trainee_list";
	$rsTrainee=$db->query($sqlTrainee);
	$nmTrainee=$rsTrainee->num_rows;	

echo "<script language='javascript'>";
echo "var trainingEvent=new Array();";
echo "var trainingEventCount=1;";

echo "var trainers=new Array();";
echo "var trainerCount=new Array();";

echo "var attendance=new Array();";
echo "var attendCount=new Array();";

echo "var complete=new Array();";
echo "var incomplete=new Array();";

echo "var students=new Array();";
echo "var studentCount=new Array();";

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

		$sqlCount="select * from training_schedule where event_id='".$row2['id']."' group by date";
		$rsCount=$db->query($sqlCount);
		$nmCount=$rsCount->num_rows;

		
		
		echo "trainingEvent[trainingEventCount]['date_count']=\"".$nmCount."\";";	
			

		
		echo "trainers['".$row2['id']."']=new Array();";
		echo "trainerCount['".$row2['id']."']=1;";	
		
		echo "students['Event_".$row2['id']."']=new Array();";	
		echo "studentCount['Event_".$row2['id']."']=1;";
		echo "attendance['Event_".$row2['id']."']=new Array();";
		
		echo "complete['Event_".$row2['id']."']=new Array();";
		echo "incomplete['Event_".$row2['id']."']=new Array();";

		
		
		
			//$db2=new mysqli("localhost","root","","evaluation");
			$db2=retrieveEvaluationDb();
			$sqlAttend="select count(*) as attends, attendance_sked.* from attendance_sked where event_id='".$row2['id']."' group by trainee_id";
		
	//		$sqlAttend="select count(*) as attends, attendance.* from attendance where event_id='".$row2['id']."' group by trainee_id";
			//echo "alert(\"".$sqlAttend."\");";

			$rsAttend=$db2->query($sqlAttend);	
			$nmAttend=$rsAttend->num_rows;
			for($m=0;$m<$nmAttend;$m++){
			$rowAttend=$rsAttend->fetch_assoc();
				echo "attendance['Event_".$rowAttend['event_id']."']['Trainee_".$rowAttend['trainee_id']."']='".$rowAttend['attends']."';";
				

				
			}		
		
		
		
		
		
		
		
		
		echo "trainingEventCount++;";
		
		
		
		
		
		
	}
	
	$sql4="select * from (trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id) inner join training_events on event_id=training_events.id where start_date>'".$dateLatest."'";

	
	$rs4=$db->query($sql4);
			
	$nm4=$rs4->num_rows;	
	for($i=0;$i<$nm4;$i++){
		$row4=$rs4->fetch_assoc();
		echo "trainers['".$row4['event_id']."'][trainerCount[\"".$row4['event_id']."\"]]=new Array();";
		echo "trainers['".$row4['event_id']."'][trainerCount[\"".$row4['event_id']."\"]]['ID']='".$row4['trainer_id']."';";
		echo "trainers['".$row4['event_id']."'][trainerCount[\"".$row4['event_id']."\"]]['name']='".strtoupper($row4['lastName']).", ".$row4['firstName']."';";
		echo "trainers['".$row4['event_id']."'][trainerCount[\"".$row4['event_id']."\"]]['position']='".$row4['position']."';";
		echo "trainerCount[\"".$row4['event_id']."\"]++;";
	}
	$db2=retrieveEvaluationDb();
	//$db2=new mysqli("localhost","root","","evaluation");
	//$sqlAttend="select count(*) as attends,trainee_id,event_id,date from attendance inner join training.training_instance group by event_id,trainee_id";
	/*
	$sqlAttend="select count(*) as attends,attendance.trainee_id,attendance.event_id,attendance.date from attendance inner join training.training_instance on attendance.event_id=training.training_instance.id  where start_date>'".$dateLatest."' group by attendance.trainee_id";
	//echo "alert(\"".$sqlAttend."\");";

	$rsAttend=$db2->query($sqlAttend);	
	$nmAttend=$rsAttend->num_rows;
	for($i=0;$i<$nmAttend;$i++){
	$rowAttend=$rsAttend->fetch_assoc();
		echo "attendance['Event_".$rowAttend['event_id']."']['Trainee_".$rowAttend['trainee_id']."']='".$rowAttend['attends']."';";
	}
*/


echo "</script>";	
	
?>
<?php
echo "<script language='javascript'>";	
$db=retrieveTrainingDb();
//$db=new mysqli("localhost","root","","training");
$sql="select * from class_instance inner join training_instance on class_instance.training_event_id=training_instance.id  where start_date>'".$dateLatest."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	echo "students['Event_".$row['training_event_id']."'][studentCount['Event_".$row['training_event_id']."']]='".$row['traineeId']."';";	
	echo "studentCount['Event_".$row['training_event_id']."']++;";
}

echo "</script>";		
?>
<script language="javascript">

var searchEventGrid=new Array();
var searchEventCount=1;
var completeCount=0;
var incompleteCount=0;

function iterateTraining(pgindex,action){
	var selectedTrainingID="";

	completeCount=0;
	incompleteCount=0;
	
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

	var trainerGrid="<tr><th style='background-color:#ed5214;color:white;' colspan=2>Course Trainers</th></tr>";
	var trainerGridCount=trainers[selectedTrainingID].length;

	for(i=1;i<trainerGridCount;i++){
		trainerGrid+="<tr><td align=center style='background-color:white; color:black;'>"+trainers[selectedTrainingID][i]['name']+"</td></tr>";
	}

	
	trainerGrid+='<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>';
	document.getElementById('trainerTable').innerHTML=trainerGrid;
	
	measureAttendance();	

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

function measureAttendance(){



	var requiredCount=trainingEvent[document.getElementById('trainingPageNumber').value]['date_count'];
	var selectedID=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];

	var currentStudent="";
	var gridCount=students["Event_"+selectedID].length;
	var currentCount=0;

	
	for(i=1;i<gridCount;i++){
		currentStudent=students["Event_"+selectedID][i];
		if((attendance["Event_"+selectedID]["Trainee_"+currentStudent]==null)){
			attendance["Event_"+selectedID]["Trainee_"+currentStudent]=0;
		}
		currentCount=attendance['Event_'+selectedID]['Trainee_'+currentStudent];

		if((currentCount<requiredCount)||(currentCount==null)){

			incomplete["Event_"+selectedID][incompleteCount]=currentStudent;
			incompleteCount++;
		}
		else if(currentCount>=requiredCount) {
		
		
			complete["Event_"+selectedID][completeCount]=currentStudent;
			completeCount++;
		}
	}

	fillTables();
}

function fillTables(){
	var selectedID=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	var attendanceGrid="";
	attendanceGrid+="<tr><th style='background-color:#ed5214;color:white;' colspan=2>Complete Attendance</th></tr>";

	var attendanceGridCount=complete["Event_"+selectedID].length;	

	//alert(attendanceGridCount);	
	for(i=0;i<attendanceGridCount;i++){
		attendanceGrid+="<tr><td  style='background-color:white;color:black;' align=center>"+trainee[complete["Event_"+selectedID][i]]['name']+"</td></tr>";
			
	}


	
	
	attendanceGrid+="<tr><td style='background-color: #ed5214;' colspan=2></td></tr>";	
	
	
	document.getElementById('completeAttend').innerHTML=attendanceGrid;

	attendanceGrid="<tr><th style='background-color:#ed5214;color:white;' colspan=2>Incomplete Attendance</th></tr>";
	var attendanceGridCount=incomplete["Event_"+selectedID].length;	
	for(i=0;i<attendanceGridCount;i++){
		attendanceGrid+="<tr><td  style='background-color:white;color:black;'  align=center>"+trainee[incomplete["Event_"+selectedID][i]]['name']+"</td></tr>";
			
	}


	attendanceGrid+="<tr><td style='background-color: #ed5214;' colspan=2></td></tr>";	

	document.getElementById('incompleteAttend').innerHTML=attendanceGrid;
	prepareEventID();

}
function prepareEventID(){
	var selectedID=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	document.getElementById("eventID_submit").value=selectedID;
	
}

function preparePrintout(){
	var selectedID=document.getElementById("eventID_submit").value;
	if(selectedID==""){
		alert("Error.  You haven't selected a task to print out.");
	
	}
	else {
		document.forms['memo_generate'].submit();
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


	<table style=" border: 1px solid gray;" width=90%>
	<tr>
	<th valign=center style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Memo to Attend/<br>Completion/Non-Attendance</h1>
	</th>
	</tr>
	</table>

<br>
<table>
<tr>
<td width=20%>

<table style=" border: 1px solid gray;" width=100%>
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

</td>
<td width=30% valign=top>
<table  id='trainerTable' name='trainerTable' style=" border: 1px solid gray;" width=80%>
<tr>
<th style='background-color:#ed5214;color:white;' colspan=2>Course Trainers</th>
</tr>
<tr    >
	<td style='background-color: #ed5214;' colspan=2></td>
</tr>	
</table>
</td>
</tr>
<tr>
<td  valign=top>
<table id='completeAttend' name='completeAttend' style=" border: 1px solid gray;" width=100%>
<tr>
<th style='background-color:#ed5214;color:white;' colspan=2>Complete Attendance</th>
</tr>
<tr>
	<td style='background-color: #ed5214;' colspan=2></td>
</tr>	
</table>


</td>
<td  valign=top>
<table valign=top id='incompleteAttend' name='incompleteAttend' style=" border: 1px solid gray;" width=80%>
<tr>
	<th style='background-color:#ed5214;color:white;' colspan=2>Incomplete Attendance</th>
</tr>
<tr>
	<td style='background-color: #ed5214;' colspan=2></td>
</tr>	
</table>


</td>
</tr>
<tr>
<td align=center colspan=2>
<form id='memo_generate' name='memo_generate' action="memo_generate.php" method="post">
<select name='memoType' >
<!--<option value='mATrainers'>Memo to Attend: Trainers</option>-->
<option value='mATrainees'>Memo to Attend: Participants</option>
<option value='completion'>Memo for Completion</option>
<option value='terminal'>Terminal Report</option>
<option value='mAbsences'>Memo for Non-Attendance</option>
</select>
<input type=hidden name='eventID_submit' id='eventID_submit' />
<input type=button value='Generate Printout' onclick='preparePrintout()' />
</form>
</td>
</tr>


</table>