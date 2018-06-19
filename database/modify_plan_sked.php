<?php


if(isset($_POST['selectedEvent'])){
//	$insertDb=new mysqli("localhost","root","","evaluation");
	$insertDb=retrieveEvaluationDb();
	$dayItems=explode("&&",$_POST['schedules_plan']);

	$insertSQL="delete from plan_schedule where event_id='".$_POST['selectedEvent']."'";
	$insertRS=$insertDb->query($insertSQL);


	
	for($i=0;$i<(count($dayItems)*1-1);$i++){
		$listItems=explode(":: ",$dayItems[$i]);
		$selectedDay=$listItems[0];
		$activities=explode(",",$listItems[1]);
		
		for($m=0;$m<count($activities);$m++){
			$insertSQL="insert into plan_schedule(event_id,day,activity) values ('".$_POST['selectedEvent']."','".$selectedDay."','".$activities[$m]."')";
			$insertRS=$insertDb->query($insertSQL);
		}
	}
}




?>


<?php
//$db=new mysqli("localhost","root","","training");
$db=retrieveTrainingDb();
$sql2="select *,training_instance.id as program_event_id from training_instance";
$rs2=$db->query($sql2);
$nm2=$rs2->num_rows;
?>
<script language="javascript">
var plan_objectives=new Array();
var selectedEvtId="";
var day_objectives=new Array();



</script>
<?php
echo "<script language='javascript'>";
echo "var trainingEvent=new Array();";
echo "var trainingEventCount=1;";


echo "var day=new Array();";
echo "var dayCount=new Array();";
echo "var noOfDays=new Array();";
echo "var tempDayCount=new Array();";
echo "var dayList=new Array();";
echo "var dayListCount=new Array();";

for($i=0;$i<$nm2;$i++){
	$row2=$rs2->fetch_assoc();
	echo "trainingEvent[trainingEventCount]=new Array();";
	echo "trainingEvent[trainingEventCount]['title']=\"".$row2['training_title']."\";";

	echo "trainingEvent[trainingEventCount]['batch']=\"".$row2['batch_number']."\";";
	echo "trainingEvent[trainingEventCount]['start_time']=\"".date("F d, Y",strtotime($row2['start_date']))."\";";
	echo "trainingEvent[trainingEventCount]['end_time']=\"".date("F d, Y",strtotime($row2['end_date']))."\";";
	echo "trainingEvent[trainingEventCount]['trainer']=\"".$row2['trainer']."\";";	
	echo "trainingEvent[trainingEventCount]['ID']=\"".$row2['program_event_id']."\";";	

	echo "trainingEvent[trainingEventCount]['program']=\"".$row2['program_id']."\";";	

	echo "day['Event_".$row2['id']."']=new Array();";
	echo "dayCount['Event_".$row2['id']."']=new Array();";
	echo "noOfDays['Event_".$row2['id']."']=0;";
	
	echo "tempDayCount['Event_".$row2['id']."']=new Array();";
	echo "dayList['Event_".$row2['id']."']=new Array();";
	echo "dayListCount['Event_".$row2['id']."']=0;";


//	$scheduleDb=new mysqli("localhost","root","","evaluation");
	$scheduleDb=retrieveEvaluationDb();
	$scheduleSQL="select * from plan_schedule where event_id='".$row2['id']."' group by day order by day";
	$scheduleRS=$scheduleDb->query($scheduleSQL);	
	$scheduleNM=$scheduleRS->num_rows;
	for($m=0;$m<$scheduleNM;$m++){
		$scheduleRow=$scheduleRS->fetch_assoc();
		echo "day['Event_".$row2['id']."']['Day_".$scheduleRow['day']."']=new Array();";
		echo "dayCount['Event_".$row2['id']."']['Day_".$scheduleRow['day']."']=0;";

		echo "dayList['Event_".$row2['id']."'][dayListCount['Event_".$row2['id']."']]='".$scheduleRow['day']."';";
		echo "dayListCount['Event_".$row2['id']."']++;";
		
		echo "tempDayCount['Event_".$row2['id']."']['Day_".$scheduleRow['day']."']=0;";
		echo "noOfDays['Event_".$row2['id']."']++;";
	//	echo "alert('Event_".$row2['id']."');";			
	//	echo "alert(noOfDays['Event_".$row2['id']."']);";		
	}


	//$scheduleDb=new mysqli("localhost","root","","evaluation");
	$scheduleDb=retrieveEvaluationDb();
	$scheduleSQL="select * from plan_schedule where event_id='".$row2['id']."' order by day";
	
	
	$scheduleRS=$scheduleDb->query($scheduleSQL);	
	$scheduleNM=$scheduleRS->num_rows;
	for($m=0;$m<$scheduleNM;$m++){
		$scheduleRow=$scheduleRS->fetch_assoc();
		echo "day['Event_".$row2['id']."']['Day_".$scheduleRow['day']."'][dayCount['Event_".$row2['id']."']['Day_".$scheduleRow['day']."']]='".$scheduleRow['activity']."';";
		echo "dayCount['Event_".$row2['id']."']['Day_".$scheduleRow['day']."']++;";
		
		echo "tempDayCount['Event_".$row2['id']."']['Day_".$scheduleRow['day']."']++;";	
	}

	echo "trainingEventCount++;";
	
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
			document.getElementById('batch').value=trainingEvent[pgindex*1-1]['batch'];

			
			
			//			document.getElementById('trainer').value=trainingEvent[pgindex*1-1]['trainer'];
		
//			document.getElementById('period').value="From "+(trainingEvent[pgindex*1-1]['start_time'])+" to "+(trainingEvent[pgindex*1-1]['end_time']);

//			document.getElementById('traineeGrid').innerHTML=traineeGrid;
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
			document.getElementById('batch').value=trainingEvent[pgindex*1+1]['batch'];



			//	document.getElementById('trainer').value=trainingEvent[pgindex*1+1]['trainer'];
		//	document.getElementById('period').value="From "+(trainingEvent[pgindex*1+1]['start_time'])+" to "+(trainingEvent[pgindex*1+1]['end_time']);

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
			document.getElementById('batch').value=trainingEvent[pgindex*1]['batch'];




			//	document.getElementById('trainer').value=trainingEvent[pgindex*1+1]['trainer'];
		//	document.getElementById('period').value="From "+(trainingEvent[pgindex*1]['start_time'])+" to "+(trainingEvent[pgindex*1]['end_time']);

			selectedTrainingID=trainingEvent[pgindex*1]['ID'];
			
		
	
	
	}
	
	fillObjectives(selectedTrainingID);

	
}

function fillObjectives(eventID){
	selectedEvtId=eventID;
	day_objectives.length=0;

	for(var i=0;i<noOfDays['Event_'+eventID];i++){
		var dayNumber=i*1+1;
		day_objectives["Day_"+dayNumber]=new Array();
	
		for(var m=0;m<tempDayCount['Event_'+eventID]['Day_'+dayNumber];m++){
			day_objectives["Day_"+dayNumber][m]=day['Event_'+eventID]['Day_'+dayNumber][m];	
		}
	}
	

	fillDynamic();

}
function fillDynamic(){
	
	
	var objectiveGrid="";
	objectiveGrid+="<tr><th style='background-color: #ed5214;color: white;' colspan=2>Schedule</th></tr>";
	objectiveGrid+="<tr><th style='background-color: #00cc66;color: black;'>Day</th>";
	objectiveGrid+="<th style='background-color: #00cc66;color: black;'>Activity</th></tr>";
	


	for(var i=0;i<noOfDays['Event_'+selectedEvtId];i++){
		var dayNumber=i*1+1;		
		for(var m=0;m<tempDayCount['Event_'+selectedEvtId]['Day_'+dayNumber];m++){
			if(m==0){
				objectiveGrid+="<tr><th id='Day_"+dayNumber+"_"+m+"' style='background-color: white;color: black;'>"+dayNumber+"</th>";				
			
			
			}
			else {
				objectiveGrid+="<tr><th id='Day_"+dayNumber+"_"+m+"' style='background-color: white;color: black;'>&nbsp;</th>";				
			
			}
			
			objectiveGrid+="<th id='Day_"+dayNumber+"_"+m+"_x' style='background-color: white;color: black;'><a href='#objectiveTable' style='text-decoration:none;color:black;' onclick=markForDeletion('"+m+"','Day_"+dayNumber+"')>"+day_objectives["Day_"+dayNumber][m]+"</a></th></tr>";				
			
			
		}
	
	
	}

	objectiveGrid+="<tr><td style=' background-color: #ed5214;' colspan=2></td></tr>";
	
	
	
	document.getElementById('objectiveTable').innerHTML=objectiveGrid;

}
function removeObjectives(){
	var selectedDay=document.getElementById('forDay').value;
	var selectedID=document.getElementById('forDeletion').value;
	var selectedEvt=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	
	
	
	day_objectives[selectedDay].splice(selectedID,1);
	tempDayCount['Event_'+selectedEvt]['Day_'+selectedDay.replace("Day_","")]--;
	
	document.getElementById('forDeletion').value="";
	document.getElementById('forDay').value="";
	fillDynamic();
}
function markForDeletion(element,dayElement){
	
	if(document.getElementById('forDeletion').value==""){
	}
	else {
		var selectedItem=document.getElementById('forDay').value+"_"+document.getElementById('forDeletion').value;
		document.getElementById(selectedItem).parentNode.style.backgroundColor="white";
		document.getElementById(selectedItem).parentNode.style.color='black';
		
		document.getElementById(selectedItem).style.backgroundColor="white";
		document.getElementById(selectedItem).style.color='black';
		
		selectedItem=document.getElementById('forDay').value+"_"+document.getElementById('forDeletion').value+"_x";
		document.getElementById(selectedItem).parentNode.style.backgroundColor="white";
		document.getElementById(selectedItem).parentNode.style.color='black';
		
		document.getElementById(selectedItem).style.backgroundColor="white";
		document.getElementById(selectedItem).style.color='black';

	}

	document.getElementById('forDeletion').value=element;
	document.getElementById('forDay').value=dayElement;
	document.getElementById(dayElement+"_"+element).parentNode.style.backgroundColor="#95cbe9";
	document.getElementById(dayElement+"_"+element).parentNode.style.color='black';

	document.getElementById(dayElement+"_"+element).style.backgroundColor="#95cbe9";
	document.getElementById(dayElement+"_"+element).style.color='black';

	document.getElementById(dayElement+"_"+element+"_x").parentNode.style.backgroundColor="#95cbe9";
	document.getElementById(dayElement+"_"+element+"_x").parentNode.style.color='black';

	document.getElementById(dayElement+"_"+element+"_x").style.backgroundColor="#95cbe9";
	document.getElementById(dayElement+"_"+element+"_x").style.color='black';
	
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
			searchEventGrid[searchEventCount]['trainer']=trainingEvent[i]['trainer'];
			searchEventCount++;
			
		}
		else if((trainingEvent[i]['title'].toLowerCase()).indexOf(searchWord.toLowerCase())>-1){
			searchEventGrid[searchEventCount]=new Array();

			searchEventGrid[searchEventCount]['index']=i;
			searchEventGrid[searchEventCount]['ID']=trainingEvent[i]['ID'];
			searchEventGrid[searchEventCount]['title']=trainingEvent[i]['title'];
			searchEventGrid[searchEventCount]['batch']=trainingEvent[i]['batch'];
			searchEventGrid[searchEventCount]['period']="From "+trainingEvent[i]['start_time']+" to "+trainingEvent[i]['end_time'];
			searchEventGrid[searchEventCount]['trainer']=trainingEvent[i]['trainer'];
			searchEventCount++;
		}
		else {
		}
	}

	if(searchEventCount>1){
		var optionsGrid="";
		optionsGrid+="<td style='background-color: white;color:black;'>Look for Training Program Here:</td>";
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
function includeObjectives(){
	var inputDay=document.getElementById('day').value;
	var selectedEventID=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	var selectedText=document.getElementById('newObjective').value;
	
	if(noOfDays["Event_"+selectedEventID]==0){
		noOfDays["Event_"+selectedEventID]++;
	}
	
	if(tempDayCount['Event_'+selectedEventID]['Day_'+inputDay]==null){
		day_objectives["Day_"+inputDay]=new Array();

		dayList['Event_'+selectedEventID][dayListCount['Event_'+selectedEventID]]=inputDay;
		dayListCount['Event_'+selectedEventID]++;

		
		
		noOfDays["Event_"+selectedEventID]++;
		tempDayCount['Event_'+selectedEventID]['Day_'+inputDay]=1;
		day_objectives["Day_"+inputDay].push(selectedText);
	}
	else {
		tempDayCount['Event_'+selectedEventID]['Day_'+inputDay]++;
		day_objectives["Day_"+inputDay].push(selectedText);

	}
	fillDynamic();


	
}


function finalizeSubmit(){
	document.getElementById('schedules_plan').value="";
	document.getElementById('selectedEvent').value="";

	document.getElementById('schedules_plan').value="";
	
	//document.getElementById('schedules_plan').value=day_objectives;
	var selectedEvent=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	
	for(var i=0;i<dayList['Event_'+selectedEvent].length;i++){
		if(tempDayCount['Event_'+selectedEvent]['Day_'+dayList['Event_'+selectedEvent][i]]==0){		
			
		}
		else {
			document.getElementById('schedules_plan').value+=dayList['Event_'+selectedEvent][i]+":: "+day_objectives["Day_"+dayList['Event_'+selectedEvent][i]]+"&&";
		
		}
	}
	
	document.getElementById('selectedEvent').value=selectedEvent;
	
	document.forms['objectives_form'].submit();
}


</script>
	<table id='indexhere' align=center style='border: 1px solid gray' width=600>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Modify Training Plan Schedule</h1>
	</th>
	</table>
<table width=100% align=center >
	<tr>
	<td>

	<table id='dynamicProgram' width=500 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Select Training Event</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			Training Title
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 onkeyup="searchEvent(document.getElementById('training_title').value);" />
			<input type=button id='searchText' value='?' onclick="searchEvent(document.getElementById('training_title').value);" />
			</td>
		</tr>	
		<tr>
			<td style="background-color:white; color:black;">
			Batch Number
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='batch' name='batch' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 />
			</td>
		</tr>
		<!--	
		<tr>
			<td style="background-color:white; color:black;">
			Period
			</td>
			<td style="background-color:white; color:black;">
			<textarea id='period' name='period' value="<?php //echo $ndgrid[1]['training_title']; ?>" cols=30></textarea>
			</td>
		</tr>
		-->
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
	<td colspan=2>&nbsp;</td>	
	</tr>
	<tr>
	<td style='vertical-align:top' >	
	<table id='objectiveTable' align=center  width=500  style='border: 1px solid gray'>
		<tr>
			<th colspan=2 style="background-color: #ed5214;color: white;">
			Schedule
			</th>
		</tr>
		<tr>
			<th style="background-color: #00cc66;color: black;">
			Day
			</th>
			<th style="background-color: #00cc66;color: black;">
			Activity
			</th>
		</tr>	
		
		<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>
	</table>
	<input type="hidden" id="forDeletion" name="forDeletion" />	
	<input type="hidden" id="forDay" name="forDay" />	

	</td>	
	<td align=center vertical-align=center>
	<input type=button value='<< - Entry' onclick='includeObjectives();' />
	<br><br>
	<input type=button value='>> - Remove' onclick='removeObjectives();' />
	</td>
	
	<td style='vertical-align:top;'>

		<table align=center  width=400>

			<tr>
			<th style="background-color: #00cc66;color: black;">
			Add New Schedule
			</th>
			</tr>
			<tr>
			<td width=100% style='background-color:white; color:black;'>
			Day: <input type=text size=5 name='day' id='day' value='1' />
			<textarea name='newObjective' id='newObjective' cols=45  ></textarea>
			</td>
			</tr>	
		</table>
	</td>
	
	
	
	</tr>
	<tr>
	<td align=center>
	<form id='objectives_form' name='objectives_form' action='training_plan_outline.php?a=schedule' method='post'>
	<input type=hidden id='selectedEvent' name='selectedEvent' />
	<input type=hidden id='schedules_plan' name='schedules_plan' />
	<input type=button value='Submit Schedule' onclick='finalizeSubmit();' />
	</form>
	</td>
	<td colspan=2>
	&nbsp;
	</td>
	</tr>
	</table>
	