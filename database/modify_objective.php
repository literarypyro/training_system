<?php
if(isset($_POST['selectedEvent'])){
	$objectives=explode(',',$_POST['objectives_plan']);
	
//	$insert_db=new mysqli("localhost","root","","evaluation");
	$insert_db=retrieveEvaluationDb();
	$sql="delete from plan_objectives where event_id='".$_POST['selectedEvent']."'";
	$rs=$insert_db->query($sql);

	for($i=0;$i<count($objectives);$i++){
		if($objectives[$i]==""){
		}
		else {
			$sql="insert into plan_objectives(event_id,objectives) values ('".$_POST['selectedEvent']."','".$objectives[$i]."')";
			$rs=$insert_db->query($sql);
				
		
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

</script>
<?php
echo "<script language='javascript'>";
echo "var trainingEvent=new Array();";
echo "var trainingEventCount=1;";


echo "var objectives=new Array();";
echo "var objectiveCount=new Array();";

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

	echo "objectives['Event_".$row2['id']."']=new Array();";
	echo "objectiveCount['Event_".$row2['id']."']=0;";
	
	
	$objectiveDb=new mysqli("localhost","root","","evaluation");
	$objectiveSQL="select * from plan_objectives where event_id='".$row2['id']."'";
	$objectiveRS=$objectiveDb->query($objectiveSQL);	
	$objectiveNM=$objectiveRS->num_rows;
	for($m=0;$m<$objectiveNM;$m++){
		$objectiveRow=$objectiveRS->fetch_assoc();
		echo "objectives['Event_".$row2['id']."'][objectiveCount['Event_".$row2['id']."']]=\"".$objectiveRow['objectives']."\";";
		echo "objectiveCount['Event_".$row2['id']."']++;";
		
	
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
	var selectedID=eventID;

	
	plan_objectives.length=0;
	for(var i=0;i<objectives['Event_'+eventID].length;i++){
		plan_objectives[i]=objectives['Event_'+eventID][i];
	
	}
	
	fillDynamic();

}
function markForDeletion(element){
	
	if(document.getElementById('forDeletion').value==""){
	}
	else {
		var selectedItem="objective_"+document.getElementById('forDeletion').value;
		document.getElementById(selectedItem).parentNode.style.backgroundColor="white";
		document.getElementById(selectedItem).parentNode.style.color='black';
		
		document.getElementById(selectedItem).style.backgroundColor="white";
		document.getElementById(selectedItem).style.color='black';

	}

	document.getElementById('forDeletion').value=element;
	document.getElementById("objective_"+element).parentNode.style.backgroundColor="#95cbe9";
	document.getElementById("objective_"+element).parentNode.style.color='black';

	document.getElementById("objective_"+element).style.backgroundColor="#95cbe9";
	document.getElementById("objective_"+element).style.color='black';
}

function fillDynamic(){
	
	var objectiveGrid="";
	objectiveGrid+="<tr><th style='background-color: #00cc66;color: black;'>Objective(s)</th></tr>";
	for(var i=0;i<plan_objectives.length;i++){
		objectiveGrid+="<tr><td id='objective_"+i+"'style='background-color: white;color: black;'>";
		objectiveGrid+="<a id='objective_"+i+"' style='text-decoration:none;color:black;' href='#objectiveTable' onclick=markForDeletion('"+i+"')>";

		objectiveGrid+=plan_objectives[i];

		objectiveGrid+="</a>";
	
		objectiveGrid+="</td></tr>";
		
	}
	

	objectiveGrid+="<tr><td style=' background-color: #ed5214;' colspan=2></td></tr>";
	
	document.getElementById('objectiveTable').innerHTML=objectiveGrid;

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
	var count=plan_objectives.length;
	var objective_text=document.getElementById("newObjective").value;
	plan_objectives[count]=objective_text;
	
	
	fillDynamic();
}
function removeObjectives(){

	var selectedItem=document.getElementById('forDeletion').value;
	plan_objectives.splice(selectedItem,1);
	document.getElementById('forDeletion').value="";

	fillDynamic();
}

function finalizeSubmit(){
	document.getElementById('objectives_plan').value="";
	document.getElementById('selectedEvent').value="";

	document.getElementById('objectives_plan').value=plan_objectives;
	
	var selectedEvent=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	document.getElementById('selectedEvent').value=selectedEvent;
	
	document.forms['objectives_form'].submit();
}


</script>
	<table id='indexhere' align=center style='border: 1px solid gray' width=600>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Modify Training Plan Objectives</h1>
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
	<table id='objectiveTable' align=center  width=500>
		<tr>
			<th style="background-color: #00cc66;color: black;">
			Objective(s)
			</th>
		</tr>
		<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>
	</table>
	<input type="hidden" id="forDeletion" name="forDeletion" />	
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
			Add New Objective
			</th>
			</tr>
			<tr>
			<td width=100% style='background-color:white; color:black;'>

			<textarea name='newObjective' id='newObjective' cols=45  ></textarea>
			</td>
			</tr>	
		</table>
	</td>
	
	
	
	</tr>
	<tr>
	<td align=center>
	<form id='objectives_form' name='objectives_form' action='training_plan_outline.php?a=objective' method='post'>
	<input type=hidden id='selectedEvent' name='selectedEvent' />
	<input type=hidden id='objectives_plan' name='objectives_plan' />
	<input type=button value='Submit Objectives' onclick='finalizeSubmit();' />
	</form>
	</td>
	<td colspan=2>
	&nbsp;
	</td>
	</tr>
	</table>
	