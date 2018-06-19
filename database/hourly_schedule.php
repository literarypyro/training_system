
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
//$db=new mysqli("localhost","root","","training");

$db=retrieveTrainingDb();
$sql="select * from training_schedule order by date";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){

echo "<script language='javascript'>";
echo "var schedules=new Array();";
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();

	echo "schedules[".$row['id']."]=new Array();";	
	echo "schedules[".$row['id']."]['day']='".date("d",strtotime($row['date']))."';";	
	echo "schedules[".$row['id']."]['month']='".date("m",strtotime($row['date']))."';";	
	echo "schedules[".$row['id']."]['year']='".date("Y",strtotime($row['date']))."';";	
	echo "schedules[".$row['id']."]['startHour']='".date("h",strtotime($row['start_time']))."';";	
	echo "schedules[".$row['id']."]['startMinute']='".date("i",strtotime($row['start_time']))."';";	
	echo "schedules[".$row['id']."]['startShift']='".date("A",strtotime($row['start_time']))."';";	
	echo "schedules[".$row['id']."]['endHour']='".date("h",strtotime($row['end_time']))."';";	
	echo "schedules[".$row['id']."]['endMinute']='".date("i",strtotime($row['end_time']))."';";	
	echo "schedules[".$row['id']."]['endShift']='".date("A",strtotime($row['end_time']))."';";	
	echo "schedules[".$row['id']."]['location']='".$row['location']."';";	
	echo "schedules[".$row['id']."]['activity']='".$row['activity']."';";	

	
	}
echo "</script>";
}
?>
<?php
	$dateLatest=date("Y-m-d",strtotime(date("Y-m-d")."-5 days"));
	//$db=new mysqli("localhost","root","","training");
	
	$db=retrieveTrainingDb();
	
	$sql="select *,(select count(training_event_id) from class_instance where training_event_id=training_instance.id) as trainee_count from training_instance where start_date>'".$dateLatest."' order by start_date";

	$rs2=$db->query($sql);
	$nm2=$rs2->num_rows;
		
echo "<script language='javascript'>";
echo "var trainingEvent=new Array();";
echo "var trainingEventCount=1;";

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

	echo "trainingEventCount++;";
	}



echo "</script>";	
?>
<?php
if(isset($_POST['selectedID'])){
	if($_POST['selectedID']==""){
	}
	else {
	//	$db=new mysqli("localhost","root","","training");
		
		$db=retrieveTrainingDb();
		$sql="select * from training_schedule where id='".$_POST['selectedID']."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		$selectedDate=date("Y-m-d",strtotime($row['date']));
		$selectedEvent=$row['event_id'];
		
		$sql="delete from training_schedule where id='".$_POST['selectedID']."'";
		$rs=$db->query($sql);
		
		$sql="select * from training_schedule where event_id='".$selectedEvent."' and date like '".$selectedDate."%%'";
		$rs=$db->query($sql);
		$trainingSchedules=$rs->num_rows;
		
		if($trainingSchedules==0){
			$dbEval=new mysqli("localhost","root","","evaluation");
			$sql="delete from attendance where event_id='".$selectedEvent."' and  date like '".$selectedDate."%%'";
			$rs=$dbEval->query($sql);
		
		}
	}

}

?>

<script language="javascript">

var searchEventGrid=new Array();
var searchEventCount=1;


function selectEvent(index,direction){
	if(direction=="next"){
		index++;
	}
	else {
		index--;
	}
	var xmlHttp;
	
	var traineeHTML="";

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlHttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			traineeHTML=xmlHttp.responseText;
			
			if(traineeHTML=="None available"){
				alert("You have reached the end of the record.");
			}
			else {
				var traineeTerms=traineeHTML.split(";");

				document.getElementById('training_title').value="";

				document.getElementById('batch_no').value="";

				document.getElementById('event_period').value="";
				selectedTrainingID="";

				document.getElementById('training_title').value=traineeTerms[1];

				document.getElementById('batch_no').value=traineeTerms[2];

				document.getElementById('event_period').value="From "+traineeTerms[3]+" to "+traineeTerms[4];
				var selectedTrainingID=traineeTerms[0];
				document.getElementById('event_id').value=selectedTrainingID;	


				document.getElementById('trainingPageNumber').value=index;				
				//fillAttendance(traineeTerms[0]);

				
			}

		}
	} 
	
	var idx=index;
	if(direction=="next"){
		idx=index-1;
	
	}
	
	
	xmlHttp.open("GET","training_processing.php?indexEvent="+idx,true);
	xmlHttp.send();	



}



function iterateTraining(pgindex,action){
	if(action=="prev"){

		if((pgindex==1)||(pgindex=="")){
			alert("You have reached the end of the record.");
		
		}
		else {
			document.getElementById('trainingPageNumber').value=pgindex*1-1;
			

			document.getElementById('training_title').value=trainingEvent[pgindex*1-1]['title'];

			document.getElementById('batch_no').value=trainingEvent[pgindex*1-1]['batch'];

			document.getElementById('event_period').value="From "+(trainingEvent[pgindex*1-1]['start_time'])+" to "+(trainingEvent[pgindex*1-1]['end_time']);
			var selectedTrainingID=trainingEvent[pgindex*1-1]['ID'];
			document.getElementById('event_id').value=selectedTrainingID;

			
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

			var selectedTrainingID=trainingEvent[pgindex*1+1]['ID'];
			document.getElementById('event_id').value=selectedTrainingID;

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

			var selectedTrainingID=trainingEvent[pgindex*1]['ID'];
			document.getElementById('event_id').value=selectedTrainingID;
			
	
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
function markElement(element,date){
	if(document.getElementById('selectedID').value==""){
		document.getElementById(date+" ID-"+element).style.backgroundColor="#95cbe9";
		document.getElementById(date+" location-"+element).style.backgroundColor="#95cbe9";
		document.getElementById(date+" activity-"+element).style.backgroundColor="#95cbe9";
	}
	else {
		var selected=document.getElementById('selectedID').value;
		var sdate=document.getElementById('selectedDate').value;

		document.getElementById(sdate+" ID-"+selected).style.backgroundColor="white";
		document.getElementById(sdate+" location-"+selected).style.backgroundColor="white";
		document.getElementById(sdate+" activity-"+selected).style.backgroundColor="white";


		document.getElementById(date+" ID-"+element).style.backgroundColor="#95cbe9";
		document.getElementById(date+" location-"+element).style.backgroundColor="#95cbe9";
		document.getElementById(date+" activity-"+element).style.backgroundColor="#95cbe9";
	
	
	}
	document.getElementById('selectedID').value=element;
	document.getElementById('selectedDate').value=date;
	
	document.getElementById('selMonth').value=schedules[element]['month'];
	document.getElementById('selDay').value=schedules[element]['day'];
	document.getElementById('selYear').value=schedules[element]['year'];
	
	document.getElementById('location').value=schedules[element]['location'];
	document.getElementById('activity').value=schedules[element]['activity'];

	document.getElementById('startingHour').value=schedules[element]['startHour'];
	document.getElementById('startingMinute').value=schedules[element]['startMinute'];
	document.getElementById('startingHalf').value=schedules[element]['startShift'];
	document.getElementById('endingHour').value=schedules[element]['endHour'];
	document.getElementById('endingMinute').value=schedules[element]['endMinute'];
	document.getElementById('endingHalf').value=schedules[element]['endShift'];
//	alert(element);
//	alert(schedules[element]['location']);
}
function preparePrintout(){
	window.open("generate_hourly.php?evt="+document.getElementById('event_id').value);


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



$shownMonth=date("m");
$shownDay=date("d");
$shownYear=date("Y");

//$db=new mysqli("localhost","root","","training");
$db=retrieveTrainingDb();
$sql="select * from training_schedule where event_id='".$eventId."' group by date order by date";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$labelSQL="select * from training_instance where id='".$eventId."'";
	$labelRS=$db->query($labelSQL);
	$labelRow=$labelRS->fetch_assoc();

?>

	<table id=indexhere width=80%>
	<tr>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Hourly Schedule of Activities<br><?php echo $labelRow['training_title'].", (BATCH ".$labelRow['batch_number'].")"; ?></h1>
	</th>
	</tr>
	</table>

<?php

for($i=1;$i<=$nm;$i++){
	$row=$rs->fetch_assoc();
	if($i==1){
		$startDate=$row['date'];
		$shownMonth=date("m",strtotime($row['date']));
		$shownDay=date("d",strtotime($row['date']));
		$shownYear=date("Y",strtotime($row['date']));
		
		$startHour=date("h",strtotime($row['start_time']));
		$startMinute=date("i",strtotime($row['start_time']));
		$startShift=date("A",strtotime($row['start_time']));
		
		
		$endHour=date("h",strtotime($row['start_time']."+1 hour"));
		$endMinute=date("i",strtotime($row['start_time']."+1 hour"));
		$endShift=date("A",strtotime($row['start_time']."+1 hour"));
	}
	else if($i==$nm){
		$endDate=$row['date'];
	
	
	}
	?>
	<table width=80%>
	<tr>
		<th width=20% style='background-color: #00cc66;color: black;'>Time</th>
		<th width=30% style='background-color: #00cc66;color: black;'><?php echo "Day ".$i." (".date("d F Y",strtotime($row['date'])).")"; ?></th>
		<th width=30% style='background-color: #00cc66;color: black;'>Location</th>
	</tr>
	<?php
	$hourlysql="select * from training_schedule where event_id='".$eventId."' and date='".$row['date']."' order by start_time";
	$hourlyrs=$db->query($hourlysql);
	$hourlynm=$hourlyrs->num_rows;
	for($a=0;$a<$hourlynm;$a++){
		$hourlyRow=$hourlyrs->fetch_assoc();
		?>
	<tr>	
		<td align=center id="<?php echo date("Y-m-d",strtotime($row['date']))." ID-".$hourlyRow['id']; ?>" name="<?php echo date("Y-m-d",strtotime($row['date']))." ID-".$hourlyRow['id']; ?>" style='background-color:white;color:black;'>
		<a href="#indexhere" style="text-decoration:none;color:red;" onclick="markElement('<?php echo $hourlyRow['id']; ?>','<?php  echo date("Y-m-d",strtotime($row['date'])); ?>')" >
		<?php echo date("h:i A",strtotime($hourlyRow['start_time']))." - ".date("h:i A",strtotime($hourlyRow['end_time'])); ?>
		</a>
		</td>
		<td name="<?php echo date("Y-m-d",strtotime($row['date']))." activity-".$hourlyRow['id']; ?>" id="<?php echo date("Y-m-d",strtotime($row['date']))." activity-".$hourlyRow['id']; ?>" style='background-color:white;color:black;'><?php echo $hourlyRow['activity']; ?></td>
		<td name="<?php echo date("Y-m-d",strtotime($row['date']))." location-".$hourlyRow['id']; ?>" id="<?php echo date("Y-m-d",strtotime($row['date']))." location-".$hourlyRow['id']; ?>" style='background-color:white;color:black;'><?php echo $hourlyRow['location']; ?></td>
	</tr>	
		<?php
	}
	?>

	</table>


	
	<br>
<?php	

}



?>



<table width=80%>
	<tr>
	<td align=center colspan=3>
	<form id='removeForm' name='removeForm' action='trainer_aid.php?a=hourly' method='post'>
	<input type=hidden id='selectedID' name='selectedID' />
	<input type=hidden id='selectedDate' name='selectedDate' />
	<input type=hidden id='event_id' name='event_id' value='<?php echo $_POST['event_id']; ?>' />
	<input type=submit value='Delete Row' />	
	</form>
	<input type=button value='Generate Printout' onclick='preparePrintout();'/>
	</td>
	</tr>
</table>
<br>


<?php
}
?>
<?php
if(isset($_POST['selMonth'])){

	$dStamp=$_POST['selYear']."-".$_POST['selMonth']."-".$_POST['selDay'];
	$startTime=strtotime($dStamp." ".$_POST['endingHour'].":".$_POST['endingMinute']."".$_POST['endingHalf']);

	
	$startHour=date("h",($startTime));
	$startMinute=date("i",$startTime);
	$startShift=date("A",$startTime);

	$endTime=strtotime(date("Y-m-d H:i:s",$startTime)."+1 hour");

	
	$endHour=date("h",$endTime);
	$endMinute=date("i",$endTime);
	$endShift=date("A",$endTime);
	
}

?>


<table>
<tr>
<td width=20% valign=top>
<form action='trainer_aid.php?a=hourly' method='post'>
<table width=100%>
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
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="selectEvent(document.getElementById('trainingPageNumber').value,'prev');"><<</a>
			<input id='trainingPageNumber' style="text-align:center" type="text" name="trainingPageNumber" size=4 value='' onkeyup="iterateTraining(document.getElementById('trainingPageNumber').value,'index');" />	
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="selectEvent(document.getElementById('trainingPageNumber').value,'next');">>></a>
			</td>
	
		</tr>
<tr>
<td colspan=2 align=center><input type=hidden name='event_id' id='event_id' value='<?php echo $_POST['event_id']; ?>' /><input type=hidden name='eventID' id='eventID' value='<?php echo $_POST['eventID']; ?>' /><input type='submit' value='Select Event' /></td>
</tr>
</table>
</form>
</td>

<td  width=30% valign=top>
<form action='trainer_aid.php?a=hourly' method='post'>
<table width=70%>
<tr>
<th style='background-color:#ed5214;color:white;' colspan=2>Enter New Activity</th>
</tr>
<tr>
	<th style="background-color: #00cc66;color: black;"	 >Date</th>
	<td style="background-color:  white; color:black; border: 1px solid gray;" 	>
<select name='selMonth' id='selMonth'>
<?php
		for($i=1;$i<13;$i++){
		$year=$shownYear;
		$selectedMonth=date("m");
		?>
		<option <?php if($shownMonth*1==$i*1){ echo "selected"; } ?> value='<?php echo $i; ?>'>
		<?php echo date("F",strtotime($year."-".$i."-01")); ?>
		</option>
<?php
		}
?>	

</select> 
<select name='selDay'  id='selDay'>
<?php
		for($i=1;$i<=31;$i++){
?>
		<option <?php if($shownDay*1==$i*1){ echo "selected"; } ?> value='<?php echo $i; ?>'><?php echo $i; ?></option>

<?php		
		}
?>		
</select> 
	<select name='selYear'  id='selYear'>
	<?php
		$yy=date("Y");
		$limit=$yy*1+12;
		for($i=1999;$i<=$limit;$i++){
	?>
		<option <?php if($shownYear*1==$i*1){ echo "selected"; } ?>><?php echo $i; ?></option>

	<?php	
		}
	?>
	</select>	
	
	
	
	
	
	
	</td>
</tr>
<tr>
	<th style="background-color: #00cc66;color: black;"	>Start Time</th>
	<td style="background-color:  white; color:black; border: 1px solid gray;">
				<select name='startingHour' id='startingHour' onchange="enterShift('startingShift','AM')">
				<?php 
				for($m=1;$m<13;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
					
					
				?>
					<option <?php if($m==$startHour*1){ echo "selected"; } ?>>
					<?php echo $label; ?>
					</option>
				<?php
				}
				?>
				</select>
				
				<select id='startingMinute' name='startingMinute' onchange="enterShift('startingShift','AM')">
				<?php 
				for($m=0;$m<60;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
				?>
					<option <?php if($m==$startMinute*1){ echo "selected"; } ?>><?php echo $label; ?></option>
				<?php
				}
				?>
				</select>
				<select name='startingHalf' id='startingHalf' onchange="enterShift('startingShift','AM')">
				<option <?php if($startShift=="AM"){ echo "selected"; } ?>>AM</option>
				<option <?php if($startShift=="PM"){ echo "selected"; } ?>>PM</option>
				</select>	
	
	
	</td>
</tr>
<tr>
	<th style="background-color: #00cc66;color: black;"	>End Time</th>
	<td style="background-color:  white; color:black; border: 1px solid gray;">
				<select name='endingHour' id='endingHour' onchange="enterShift('startingShift','AM')">
				<?php 
				for($m=1;$m<13;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
					
					
				?>
					<option <?php if($m==$endHour*1){ echo "selected"; } ?>>
					<?php echo $label; ?>
					</option>
				<?php
				}
				?>
				</select>
				
				<select name='endingMinute' id='endingMinute' onchange="enterShift('startingShift','AM')">
				<?php 
				for($m=0;$m<60;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
				?>
					<option <?php if($m==$endMinute*1){ echo "selected"; } ?>><?php echo $label; ?></option>
				<?php
				}
				?>
				</select>
				<select name='endingHalf' id='endingHalf' onchange="enterShift('startingShift','AM')">
				<option <?php if($endShift=="AM"){ echo "selected"; } ?>>AM</option>
				<option <?php if($endShift=="PM"){ echo "selected"; } ?>>PM</option>
				</select>	
	
	
	
	
	</td>
</tr>
<tr>
	<th style="background-color: #00cc66;color: black;"	>Location</th>
	<td style="background-color:  white; color:black; border: 1px solid gray;"><textarea name='location' id='location' cols=25>Training Room</textarea>
	</td>
</tr>
<tr>
	<th style="background-color: #00cc66;color: black;"	>Activity</th>
	<td style="background-color:  white; color:black; border: 1px solid gray;"><textarea name='activity' id='activity' rows=5 cols=25></textarea>
	
	
	
	
	
	</td>
</tr>
<tr><td align=center colspan=2><input type=hidden value='<?php echo $eventId; ?>' name='eventID' /><input type=hidden name='event_id' id='event_id' value='<?php echo $_POST['event_id']; ?>' /><input type=submit value='Submit Activity' /></td></tr>


</table>
</form>
</td>

</tr>
</table>
