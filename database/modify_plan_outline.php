<?php
if(isset($_POST['selectedEvent'])){
	$outline_entry=explode(';;',$_POST['schedules_plan']);
	$selEvent_id=$_POST['selectedEvent'];
	
//	$db=new mysqli("localhost","root","","evaluation");
	$db=retrieveEvaluationDb();
	
	$sql="delete from plan_outline where event_id='".$selEvent_id."'";
	$rs=$db->query($sql);


	$sql="delete from plan_items where event_id='".$selEvent_id."'";
	$rs=$db->query($sql);

	for($i=0;$i<(count($outline_entry)*1-1);$i++){
		$outline_parts=explode(":",$outline_entry[$i]);
		
		$outline_item=$outline_parts[0];

//		$db2=new mysqli("localhost","root","","evaluation");
		
		$db2=retrieveEvaluationDb();
		$sql2="insert into plan_outline(event_id,outline_item) values ('".$selEvent_id."',\"".$outline_item."\")";
		$rs2=$db2->query($sql2);
		$outline_id=$db2->insert_id;
		
		$sub_items=explode("<<>>",$outline_parts[1]);
		for($k=0;$k<(count($sub_items)*1-1);$k++){
			$sql3="insert into plan_items(event_id,plan_outline_id,item_description) values ('".$selEvent_id."','".$outline_id."',\"".$sub_items[$k]."\")";
			$rs3=$db2->query($sql3);
		
		
		
		
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
var selectedChoice="";


</script>
<?php
echo "<script language='javascript'>";
echo "var trainingEvent=new Array();";
echo "var trainingEventCount=1;";
echo "var outline=new Array();";
echo "var itemList=new Array();";
echo "var itemListCount=new Array();";
echo "var outlineCount=new Array();";
echo "var course=new Array();";
echo "var course_outline=new Array();";
echo "var course_items=new Array();";
echo "var course_count=new Array();";
echo "var course_ID=new Array();";

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
	
	echo "outline['Event_".$row2['id']."']=new Array();";
	echo "itemList['Event_".$row2['id']."']=new Array();";	
	echo "itemListCount['Event_".$row2['id']."']=0;";
	echo "outlineCount['Event_".$row2['id']."']=0;";


	
	
	
	echo "day['Event_".$row2['id']."']=new Array();";
	echo "dayCount['Event_".$row2['id']."']=new Array();";
	echo "noOfDays['Event_".$row2['id']."']=0;";
	
	echo "tempDayCount['Event_".$row2['id']."']=new Array();";
	echo "dayList['Event_".$row2['id']."']=new Array();";
	echo "dayListCount['Event_".$row2['id']."']=0;";


//	$outlineDb=new mysqli("localhost","root","","evaluation");
	
	$outlineDb=retrieveTrainingDb();
	$outlineSQL="select * from plan_outline where event_id='".$row2['id']."' order by id";
	$outlineRS=$outlineDb->query($outlineSQL);
	$outlineNM=$outlineRS->num_rows;
	
	for($n=0;$n<$outlineNM;$n++){
		$outlineRow=$outlineRS->fetch_assoc();
		
		echo "itemList['Event_".$row2['id']."'][itemListCount['Event_".$row2['id']."']]='".$n."';";
		echo "itemListCount['Event_".$row2['id']."']++;";

		echo "outline['Event_".$row2['id']."']['Outline_".$n."']=new Array();";
		echo "outline['Event_".$row2['id']."']['Outline_".$n."']['ID']='".$outlineRow['id']."';";
		echo "outline['Event_".$row2['id']."']['Outline_".$n."']['description']='".$outlineRow['outline_item']."';";
		echo "outline['Event_".$row2['id']."']['Outline_".$n."']['items']=new Array();";
		echo "outline['Event_".$row2['id']."']['Outline_".$n."']['count']=0;";
		$subitemSQL="select * from plan_items where event_id='".$row2['id']."' and plan_outline_id='".$outlineRow['id']."'";

		$subitemRS=$outlineDb->query($subitemSQL);
		$subitemNM=$subitemRS->num_rows;
		
		for($k=0;$k<$subitemNM;$k++){
			$subitemRow=$subitemRS->fetch_assoc();
			echo "outline['Event_".$row2['id']."']['Outline_".$n."']['count']++;";

			echo "outline['Event_".$row2['id']."']['Outline_".$n."']['items']['Item_".$k."']=new Array();";
			echo "outline['Event_".$row2['id']."']['Outline_".$n."']['items']['Item_".$k."']['ID']='".$subitemRow['id']."';";
			echo "outline['Event_".$row2['id']."']['Outline_".$n."']['items']['Item_".$k."']['description']='".$subitemRow['item_description']."';";

		}
	}
	
	
	
	
//	$scheduleDb=new mysqli("localhost","root","","evaluation");
	$scheduleDb=retrieveTrainingDb();
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


//	$scheduleDb=new mysqli("localhost","root","","evaluation");
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


function fillDynamic(){
	
	var selectGrid="";
	var objectiveGrid="";
	objectiveGrid+="<tr><th style='background-color: #ed5214;color: white;' colspan=2>Course Outline</th></tr>";

	for(var i=0;i<course_outline.length;i++){

		objectiveGrid+="<tr><th id='Item "+i+"' style='background-color: #00cc66;color: black;' colspan=2>";
		objectiveGrid+="<a href='#objectiveTable' style='text-decoration:none;color:white;' onclick=markForDeletion('"+i+"','','Item') >";

		
		objectiveGrid+=course_outline[i];

		objectiveGrid+="</a>";

		objectiveGrid+="</th></tr>";

		
		selectGrid+="<option value='"+i+"'>";
		selectGrid+=course_outline[i];
		selectGrid+="</option>";
		
		for(var k=0;k<course_count[i];k++){
			objectiveGrid+="<tr><td id='Sub-Item "+i+"_"+k+"' style='background-color: white;color: black;' colspan=2>";			
			objectiveGrid+="<a href='#objectiveTable' style='text-decoration:none;color:black;' onclick=markForDeletion('"+i+"','"+k+"','Sub-Item') >";
	
			objectiveGrid+=course_items[i][k];			

		//	objectiveGrid+=course["Outline_"+i]['items']["Item_"+k]['description'];			
			objectiveGrid+="</a>";		
		
			objectiveGrid+="</td></tr>";		
		
		}



	}

	objectiveGrid+="<tr><td style=' background-color: #ed5214;' colspan=2></td></tr>";
	
	
	document.getElementById('toItem').innerHTML=selectGrid;
	
	document.getElementById('objectiveTable').innerHTML=objectiveGrid;

}

function fillObjectives(eventID){
	selectedEvtId=eventID;
	day_objectives.length=0;
	course.length=0;
	course_outline.length=0;
	course_items.length=0;
	
	for(var i=0;i<itemList['Event_'+selectedEvtId].length;i++){
	
	
		course_outline[i]=outline['Event_'+selectedEvtId]["Outline_"+i]['description'];
		course_ID[i]=itemList['Event_'+selectedEvtId][i];	
		
		course_count[i]=0;
		
		course_items[i]=new Array();
/*		
		course_outline[i]=outline['Event_'+selectedEvtId]["Outline_"+i]['description'];
		
		course_count[i]=0;
		
		course_items[i]=new Array();
	*/	
//echo "var course_outline=new Array();";
//echo "var course_items=new Array();";

		
		for(var k=0;k<outline['Event_'+selectedEvtId]["Outline_"+i]['count'];k++){

	
			course_count[i]++;
			
			course_items[i][k]=outline['Event_'+selectedEvtId]["Outline_"+i]['items']["Item_"+k]['description'];


		}
	


	
	}
	


	fillDynamic();

}
function includeObjectives(){
	var selectedEvt=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];

	if(selectedEvt==""){
	}
	else {
		if(selectedChoice=="item"){
			var newItem=document.getElementById('newItem').value;
			var iteration=itemList["Event_"+selectedEvt].length;

			itemList["Event_"+selectedEvt].push(iteration*1+1);
			
			
			
			course_outline[iteration]=newItem;
			course_ID[iteration]=iteration*1+1;
			course_count[iteration]=0;
			
			course_items[iteration]=new Array();
		
			/*

	
			course["Outline_"+iteration]=new Array();
			course["Outline_"+iteration]['ID']=iteration*1+1;
			course["Outline_"+iteration]['description']=newItem;
			course["Outline_"+iteration]['items']=new Array();
			course["Outline_"+iteration]['count']=0;		

			course["Outline_"+iteration]=new Array();
			course["Outline_"+iteration]['ID']=iteration*1+1;
			course["Outline_"+iteration]['description']=newItem;
			course["Outline_"+iteration]['items']=new Array();
			course["Outline_"+iteration]['count']=0;	
			
			*/
			
			
			document.getElementById('newItem').value="";
		
		}
		else if(selectedChoice=="subItem"){
			var selectedItem=document.getElementById('toItem').value;
			var iteration=course_count[selectedItem];		
			var selectedSubItem=document.getElementById('newSubItem').value;

			course_items[selectedItem][iteration]=selectedSubItem;

			/*
			
			course["Outline_"+selectedItem]['items']["Item_"+iteration]=new Array();
			course["Outline_"+selectedItem]['items']["Item_"+iteration]['ID']=iteration;
			course["Outline_"+selectedItem]['items']["Item_"+iteration]['description']=selectedSubItem;
			*/
			course_count[selectedItem]++;
			document.getElementById('newSubItem').value="";
		}
		else {
			alert("You haven't indicated an item to insert.");
		}

		fillDynamic();

	}
	
}

function removeObjectives(){
	var type=document.getElementById('deletionType').value;
	var selectedEvtId=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	
	var animals=new Array();

	animals[0]=new Array();
	animals[0]["apex"]="dog";
	animals[0]["apogee"]="goat";
	animals[0]["perigee"]="carabao"
	
			
	animals[1]=new Array();
	animals[1]["apex"]="dove";			
	animals[1]["apogee"]="pigeon";			
	animals[1]["perigee"]="eagle";			
			
			
			
	//alert(animals[0]["apex"]);
	animals[0].splice(0,1);
	//alert(animals[0]["apex"]);
	
	
	//I was testing Multidimensional array here
	//Multidimensional array cannot splice with string indices
			
		// course_outline[i]=new Array();
		// course_outline[i]['ID']=itemList['Event_'+selectedEvtId][i];
		// course_outline[i]['description']=outline['Event_'+selectedEvtId]["Outline_"+i]['description'];
		// course_outline[i]['count']=0;		
		// course_items[i]=new Array();
	
	
	if(type=="Sub-Item"){
		var subitem=document.getElementById('deletionSubItem').value;
		var item=document.getElementById('deletionItem').value;

		course_items[item].splice(subitem,1);
		course_count[item]--;
		
		document.getElementById('deletionType').value="";	
		document.getElementById('deletionItem').value="";	
		document.getElementById('deletionSubItem').value="";			
	
	}
	else if(type=="Item"){
	//	alert('a');	
		var subitem=document.getElementById('deletionSubItem').value;
		var item=document.getElementById('deletionItem').value;

		course_outline.splice(item,1);
		course_ID.splice(item,1);
		course_items.splice(item,1);
		course_count.splice(item,1);
	
	//	alert(item);

	//	alert(course["Outline_"+item]['description']);
	
	//	alert(course.length);
	//	course["Outline_"+item]['items'].length=0;
		document.getElementById('deletionType').value="";	
		document.getElementById('deletionItem').value="";	
		document.getElementById('deletionSubItem').value="";			
	}
	fillDynamic();
}
function markForDeletion(itemNo,subItemNo,type){
	
	if(document.getElementById('deletionType').value==""){
	}
	else {
		if(document.getElementById('deletionType').value=="Item"){
			var selectedItem="Item "+document.getElementById('deletionItem').value;

			document.getElementById(selectedItem).parentNode.style.backgroundColor="#00cc66";
			document.getElementById(selectedItem).parentNode.style.color='white';
			document.getElementById(selectedItem).style.backgroundColor="#00cc66";
			document.getElementById(selectedItem).style.color='white';
			document.getElementById('deletionSubItem').value="";
		}
		else if(document.getElementById('deletionType').value=="Sub-Item"){


			var selectedItem="Sub-Item "+document.getElementById('deletionItem').value+"_"+document.getElementById('deletionSubItem').value;

			document.getElementById(selectedItem).parentNode.style.backgroundColor="white";
			document.getElementById(selectedItem).parentNode.style.color='black';
			document.getElementById(selectedItem).style.backgroundColor="white";
			document.getElementById(selectedItem).style.color='black';			
			
		}	
		
		
	}
	document.getElementById('deletionType').value=type;	
	document.getElementById('deletionItem').value=itemNo;	
	document.getElementById('deletionSubItem').value=subItemNo;	

	
	if(type=="Item"){
		var selectedItem="Item "+itemNo;

			alert(selectedItem);	
		document.getElementById(selectedItem).parentNode.style.backgroundColor="#95cbe9";
		document.getElementById(selectedItem).parentNode.style.color='black';
		
		document.getElementById(selectedItem).style.backgroundColor="#95cbe9";
		document.getElementById(selectedItem).style.color='black';
	
	
	}
	else if(type=="Sub-Item"){
		var selectedItem="Sub-Item "+itemNo+"_"+subItemNo;

		document.getElementById(selectedItem).parentNode.style.backgroundColor="#95cbe9";
		document.getElementById(selectedItem).parentNode.style.color='black';	
		document.getElementById(selectedItem).style.backgroundColor="#95cbe9";
		document.getElementById(selectedItem).style.color='black';	
	
	}

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


function finalizeSubmit(){
	document.getElementById('schedules_plan').value="";
	document.getElementById('selectedEvent').value="";

	var selectedEvent=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	
	var finalizeGrid="";
	for(var i=0;i<course_outline.length;i++){
		finalizeGrid+=course_outline[i]+":";
		
		for(var k=0;k<course_items[i].length;k++){
			finalizeGrid+=course_items[i][k]+"<<>>";
		
		}

		finalizeGrid+=";;";
	}
	document.getElementById('selectedEvent').value=selectedEvent;
		
	document.getElementById('schedules_plan').value=finalizeGrid;
	
	document.forms["objectives_form"].submit();
}

function enableCheck(element){
	if(element.id=="addItem"){
		if(element.checked){
			document.getElementById('addSubItem').checked=false;
			document.getElementById('newItem').disabled=false;		

			document.getElementById('newSubItem').disabled=true;		
			document.getElementById('toItem').disabled=true;			
			selectedChoice="item";	
		}
		else {
			document.getElementById('newItem').disabled=true;		
		
			selectedChoice="";		
		}
	
	}
	else if(element.id=="addSubItem"){
		if(element.checked){

			document.getElementById('addItem').checked=false;
			document.getElementById('newSubItem').disabled=false;		
			document.getElementById('toItem').disabled=false;			

			document.getElementById('newItem').disabled=true;		
			selectedChoice="subItem";	
		}
		else {
			document.getElementById('newSubItem').disabled=true;		
			document.getElementById('toItem').disabled=true;			
		
			selectedChoice="";		
		}
	
	
	}



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
			Course Outline
			</th>
		</tr>
		
		<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>
	</table>
	<input type="hidden" id="deletionItem" name="deletionItem" />	
	<input type="hidden" id="deletionSubItem" name="deletionSubItem" />	
	<input type="hidden" id="deletionType" name="deletionType" />	
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
			<input type='checkbox' name='addItem' id='addItem' onclick='enableCheck(this)' />Add New Outline Item
			</th>
			</tr>
			<tr>
			<td width=100% style='background-color:white; color:black;'>
			<textarea name='newItem' id='newItem' cols=45 disabled ></textarea>
			</td>
			</tr>	
		</table>
		<br>
		<table align=center  width=400>

			<tr>
			<th style="background-color: #00cc66;color: black;">
			<input type='checkbox' name='addSubItem' id='addSubItem' onclick='enableCheck(this)' />Add New Outline Sub-Item
			</th>
			</tr>
			<tr>
			<td width=100% style='background-color:white; color:black;'>
			To Item:  <select name='toItem' id='toItem' disabled ></select>
			<textarea name='newSubItem' id='newSubItem' cols=45  disabled ></textarea>
			</td>
			</tr>	
		</table>		
	</td>
	
	
	
	</tr>
	<tr>
	<td align=center>
	<form id='objectives_form' name='objectives_form' action='training_plan_outline.php?a=outline' method='post'>
	<input type=hidden id='selectedEvent' name='selectedEvent' />
	<input type=hidden  id='schedules_plan' name='schedules_plan' />
	<input type=button value='Submit Outline' onclick='finalizeSubmit();' />
	</form>
	</td>
	<td colspan=2>
	&nbsp;
	</td>
	</tr>
	</table>
	