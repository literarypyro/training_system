<?php




$db=new mysqli("localhost","root","","training");
$latestDate=date("Y-m-d",strtotime(date("Y-m-d")."-1 day"));

$currentYear=date("Y");
//$sql2="select * from training_instance where start_date between '".$latestDate." 00:00:00' and '".$currentYear."-12-31 23:23:59'";

$sql2="select * from training_instance";
$rs2=$db->query($sql2);
$nm2=$rs2->num_rows;

//$sql4="select * from (trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id) inner join training_events on event_id=training_events.id where start_date between '".$latestDate." 00:00:00' and '".$currentYear."-12-31 23:23:59'";

$sql4="select * from (trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id) inner join training_events on event_id=training_events.id";

$rs4=$db->query($sql4);
		
$nm4=$rs4->num_rows;
//$sql="select *,(select count(*) from diploma_release where trainee_id=class_list.trainee_id and training_program_id=class_list.training_events_id and type='certification') as certification_count from (class_list inner join training_events on class_list.training_events_id=training_events.id) inner join trainee_list on trainee_id=trainee_list.id where start_date between '".$latestDate." 00:00:00' and '".$currentYear."-12-31 23:23:59'";
$sql="select *,(select count(*) from diploma_release where trainee_id=class_list.trainee_id and training_program_id=class_list.training_events_id and type='certification') as certification_count from (class_list inner join training_events on class_list.training_events_id=training_events.id) inner join trainee_list on trainee_id=trainee_list.id";

$rs=$db->query($sql);

$nm=$rs->num_rows;

echo "<script language='javascript'>";
echo "var trainingEvent=new Array();";
echo "var trainingEventCount=1;";

echo "var trainees=new Array();";
echo "var traineeCount=new Array();";
echo "var trainers=new Array();";
echo "var trainerCount=new Array();";
for($i=0;$i<$nm2;$i++){
	$row2=$rs2->fetch_assoc();
	echo "trainingEvent[trainingEventCount]=new Array();";
	echo "trainingEvent[trainingEventCount]['title']=\"".$row2['training_title']."\";";
	echo "trainingEvent[trainingEventCount]['batch']=\"".$row2['batch_number']."\";";
	echo "trainingEvent[trainingEventCount]['start_time']=\"".date("F d, Y",strtotime($row2['start_date']))."\";";
	echo "trainingEvent[trainingEventCount]['end_time']=\"".date("F d, Y",strtotime($row2['end_date']))."\";";
	echo "trainingEvent[trainingEventCount]['trainer']=\"".$row2['trainer']."\";";	
	echo "trainingEvent[trainingEventCount]['ID']=\"".$row2['id']."\";";	

	echo "trainees['".$row2['id']."']=new Array();";
	echo "traineeCount['".$row2['id']."']=1;";

	echo "trainers['".$row2['id']."']=new Array();";
	echo "trainerCount['".$row2['id']."']=1;";
	

//	echo "trainingEvent[trainingEventCount]['traineesFname']=new Array();";
//	echo "trainingEvent[trainingEventCount]['traineesLname']=new Array();";
//	echo "trainingEvent[trainingEventCount]['traineesPosition']=new Array();";


//	echo "trainingEvent[trainingEventCount]['id']='".$row2['id']."'";
	
	

//	echo "trainingEvent[trainingEventCount]['traineeCount']=1;";
	echo "trainingEventCount++;";
	
}

for($i=0;$i<$nm;$i++){

	$row=$rs->fetch_assoc();
//	echo "alert('".$row['training_events_id']."');";
//	echo "trainingEvent[".$row['training_events_id']."]['trainees'][trainingEvent[\"".$row['training_events_id']."\"]['traineeCount']]=\"".$row['trainee_id']."\";";
	echo "trainees['".$row['training_events_id']."'][traineeCount[\"".$row['training_events_id']."\"]]=new Array();";
	echo "trainees['".$row['training_events_id']."'][traineeCount[\"".$row['training_events_id']."\"]]['ID']='".$row['id']."';";
	echo "trainees['".$row['training_events_id']."'][traineeCount[\"".$row['training_events_id']."\"]]['first_name']='".$row['firstName']."';";
	echo "trainees['".$row['training_events_id']."'][traineeCount[\"".$row['training_events_id']."\"]]['last_name']='".$row['lastName']."';";
	echo "trainees['".$row['training_events_id']."'][traineeCount[\"".$row['training_events_id']."\"]]['position']='".$row['designation']."';";
	echo "trainees['".$row['training_events_id']."'][traineeCount[\"".$row['training_events_id']."\"]]['certificate_count']='".$row['certification_count']."';";





	echo "traineeCount[\"".$row['training_events_id']."\"]++;";
	
}
for($i=0;$i<$nm4;$i++){

	$row4=$rs4->fetch_assoc();
//	echo "alert('".$row['training_events_id']."');";
//	echo "trainingEvent[".$row['training_events_id']."]['trainees'][trainingEvent[\"".$row['training_events_id']."\"]['traineeCount']]=\"".$row['trainee_id']."\";";
//	echo "alert('a');";
	echo "trainers['".$row4['event_id']."'][trainerCount[\"".$row4['event_id']."\"]]=new Array();";
	echo "trainers['".$row4['event_id']."'][trainerCount[\"".$row4['event_id']."\"]]['ID']='".$row4['trainer_id']."';";
	echo "trainers['".$row4['event_id']."'][trainerCount[\"".$row4['event_id']."\"]]['name']='".strtoupper($row4['lastName']).", ".$row4['firstName']."';";




	echo "trainers['".$row4['event_id']."'][trainerCount[\"".$row4['event_id']."\"]]['position']='".$row4['position']."';";

	
	echo "trainerCount[\"".$row4['event_id']."\"]++;";
	
}
echo "</script>";
?>
<script language="javascript">
	//alert(trainingEvent[1]['trainees'][1]);


</script>

<script language="javascript">
function iterateTraining(pgindex,action){
	if(action=="prev"){

		if((pgindex==1)||(pgindex=="")){
			alert("You have reached the end of the record.");
		
		}
		else {
			document.getElementById('trainingPageNumber').value=pgindex*1-1;
			

			document.getElementById('training_title').value=trainingEvent[pgindex*1-1]['title'];
			document.getElementById('batch').value=trainingEvent[pgindex*1-1]['batch'];
		
			document.getElementById('period').value="From "+(trainingEvent[pgindex*1-1]['start_time'])+" to "+(trainingEvent[pgindex*1-1]['end_time']);

			document.getElementById('training_id').value=trainingEvent[pgindex*1-1]['ID'];
			var selectedTrainingID=trainingEvent[pgindex*1-1]['ID'];
			var trainerGrid='<tr><th style="background-color: #00cc66;color: white;">Trainer(s) for the Event</th></tr>';


			var trainerGridCount=trainers[selectedTrainingID].length;


			for(i=1;i<trainerGridCount;i++){

				trainerGrid+="<tr><td align=center style='background-color:white; color:black;'>"+trainers[selectedTrainingID][i]['name']+"</td></tr>";
			
			}
			
			trainerGrid+='<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>';
			document.getElementById('trainerTable').innerHTML=trainerGrid;
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
			document.getElementById('period').value="From "+(trainingEvent[pgindex*1+1]['start_time'])+" to "+(trainingEvent[pgindex*1+1]['end_time']);
			document.getElementById('training_id').value=trainingEvent[pgindex*1+1]['ID'];
			
			var selectedTrainingID=trainingEvent[pgindex*1+1]['ID'];
			var trainerGrid='<tr><th style="background-color: #00cc66;color: white;">Trainer(s) for the Event</th></tr>';


			var trainerGridCount=trainers[selectedTrainingID].length;


			for(i=1;i<trainerGridCount;i++){

				trainerGrid+="<tr><td align=center style='background-color:white; color:black;'>"+trainers[selectedTrainingID][i]['name']+"</td></tr>";
			
			}
			
			trainerGrid+='<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>';
			document.getElementById('trainerTable').innerHTML=trainerGrid;
		}
	
	
	}
	else {
		if((trainingEventCount*1-1)==0){
			alert("You have reached the end of the record.");
			
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
			document.getElementById('period').value="From "+(trainingEvent[pgindex*1]['start_time'])+" to "+(trainingEvent[pgindex*1]['end_time']);
			document.getElementById('training_id').value=trainingEvent[pgindex*1]['ID'];
			
			var selectedTrainingID=trainingEvent[pgindex*1]['ID'];
			var trainerGrid='<tr><th style="background-color: #00cc66;color: white;">Trainer(s) for the Event</th></tr>';


			var trainerGridCount=trainers[selectedTrainingID].length;


			for(i=1;i<trainerGridCount;i++){

				trainerGrid+="<tr><td align=center style='background-color:white; color:black;'>"+trainers[selectedTrainingID][i]['name']+"</td></tr>";
			
			}
			
			trainerGrid+='<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>';
			document.getElementById('trainerTable').innerHTML=trainerGrid;			
			
			
			
		}
			
	
	
	}
	
	document.getElementById('first_name').value="";
	document.getElementById('last_name').value="";
	document.getElementById('position').value="";
	document.getElementById('traineePageNumber').value="";
	document.getElementById('trainee_id').value="";

}

function iterateTrainee(pgindex,action){
	/**
var traineesGrid=new Array();
var traineesFMGrid=new Array();
var traineesLMGrid=new Array();
var traineesPSGrid=new Array();
*/
	var diplomaCount=0;
	if(document.getElementById('trainingPageNumber').value==""){
	}
	else {
		var currentRecord=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
		if(action=="prev"){

			if((pgindex==1)||(pgindex=="")){
				alert("You have reached the end of the record.");
			
			}
			else {
				document.getElementById('first_name').value=trainees[currentRecord][pgindex*1-1]['first_name'];
				document.getElementById('last_name').value=trainees[currentRecord][pgindex*1-1]['last_name'];
				document.getElementById('position').value=trainees[currentRecord][pgindex*1-1]['position'];
				document.getElementById('traineePageNumber').value=pgindex*1-1;
				document.getElementById('trainee_id').value=trainees[currentRecord][pgindex*1-1]['ID'];
				
				document.getElementById('issuanceType').length=0;

				diplomaCount=trainees[currentRecord][pgindex*1-1]['certificate_count'];
				if(diplomaCount>0){
					document.getElementById('issuanceType').options[0]=new Option("Re-Issuance","Re-Issuance");
				}
				else {
					document.getElementById('issuanceType').options[0]=new Option("Issuance","Issuance");
				
				}
				diplomaCount=0;

			}
		}
		else if(action=="next"){
			if(pgindex==trainees[currentRecord].length*1-1){
				alert("You have reached the end of the record.");
			
			}
			else {
			
				document.getElementById('first_name').value=trainees[currentRecord][pgindex*1+1]['first_name'];
				document.getElementById('last_name').value=trainees[currentRecord][pgindex*1+1]['last_name'];
				document.getElementById('position').value=trainees[currentRecord][pgindex*1+1]['position'];
			
				document.getElementById('traineePageNumber').value=pgindex*1+1;

				document.getElementById('trainee_id').value=trainees[currentRecord][pgindex*1+1]['ID'];

				document.getElementById('issuanceType').length=0;

				diplomaCount=trainees[currentRecord][pgindex*1+1]['certificate_count'];
				if(diplomaCount>0){
					document.getElementById('issuanceType').options[0]=new Option("Re-Issuance","Re-Issuance");
				}
				else {
					document.getElementById('issuanceType').options[0]=new Option("Issuance","Issuance");
				
				}
				diplomaCount=0;

			}
		}	
		else {
			if((trainees[currentRecord].length*1-1)==0){
				alert("You have reached the end of the record.");
			}
			else {
				if(pgindex>(trainees[currentRecord].length*1-1)){
					alert("You have reached the end of the record.");
					pgindex=trainees[currentRecord].length*1-1;
				}
				if(pgindex<1){
					alert("You have reached the end of the record.");
					pgindex=1;
				}
			
				document.getElementById('first_name').value=trainees[currentRecord][pgindex*1]['first_name'];
				document.getElementById('last_name').value=trainees[currentRecord][pgindex*1]['last_name'];
				document.getElementById('position').value=trainees[currentRecord][pgindex*1]['position'];
			
				document.getElementById('traineePageNumber').value=pgindex*1;

				document.getElementById('trainee_id').value=trainees[currentRecord][pgindex*1]['ID'];
				
				document.getElementById('issuanceType').length=0;
				diplomaCount=trainees[currentRecord][pgindex*1]['certificate_count'];
				if(diplomaCount>0){
					document.getElementById('issuanceType').options[0]=new Option("Re-Issuance","Re-Issuance");
				}
				else {
					document.getElementById('issuanceType').options[0]=new Option("Issuance","Issuance");
				
				}
				diplomaCount=0;			
			
			}	
		}	
	
	}
	
}


</script>
<script language='javascript'>
var searchEventGrid=new Array();
var searchEventCount=1;

function scriptTraining(searchkey){
	document.getElementById('trainingPageNumber').value="";
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
		optionsGrid+="<td>Look for Training Program Here:<br>";
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
		document.getElementById('searchResults').innerHTML="";
	}
	
	searchEventCount=1;
	
}
function retrieveEvent(item){

	iterateTraining(item*1,"index");
	document.getElementById('searchResults').innerHTML="";


}

</script>
<form action="training_cert.php?a=css&action=add" method="post">
	<table id='indexhere' align=center style='border: 1px solid gray' width=600>
	<tr>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Issuance of Certification</h1>
	</th>
	</tr>
	</table>
	<table id='dynamicProgram' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: white;"	colspan=2>Select Training Event</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			Training Title
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 onkeyup='scriptTraining(this.value);'  />
			<input type=button value='?' onclick='scriptTraining(document.getElementById("training_title").value);' />
			<input type=hidden id='training_id' name='training_id' />
			
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
		<tr>
			<td style="background-color:white; color:black;">
			Period
			</td>
			<td style="background-color:white; color:black;">
			<textarea id='period' name='period' value="<?php echo $ndgrid[1]['training_title']; ?>" cols=30></textarea>
			</td>
		</tr>	
		<tr>
			<td colspan=2 style='background-color: #ed5214;' align=center>
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'prev');"><<</a>
			<input id='trainingPageNumber' style="text-align:center" type="text" name="trainingPageNumber" size=4 value='' onkeyup="iterateTraining(document.getElementById('trainingPageNumber').value,'index');" />	
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'next');">>></a>
			</td>
	
		</tr>		
	</table>
	<table  width=400 align=center style='border: 1px solid gray'>
		<tr><td id='searchResults' style="background-color: #ed5214;color: white;"	colspan=2></td></tr>
	</table>
	<table id='trainerTable' align=center  width=400>
		<tr>
			<th style="background-color: #00cc66;color: white;">
			Trainer(s)
			</th>
		</tr>
		<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>
	</table>
	
<br>
	<table id='dynamicProgram' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: white;"	colspan=2>Enrolled Participants</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			First Name
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='first_name' name='first_name' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 />
			<input type=hidden id='trainee_id' name='trainee_id' />
			</td>
		</tr>	
		<tr>
			<td style="background-color:white; color:black;">
			Last Name
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='last_name' name='last_name' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 />
			</td>
		</tr>
		<tr>
			<td style="background-color:white; color:black;">
			Position
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='position' name='position' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 />
			</td>
		</tr>			
		<tr>
			<td align=center colspan=2 style='background-color: #ed5214;'>
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="iterateTrainee(document.getElementById('traineePageNumber').value,'prev');"><<</a>
			<input id='traineePageNumber' style="text-align:center" type="text" name="traineePageNumber" size=4 value='' onkeyup="iterateTrainee(document.getElementById('traineePageNumber').value,'index');" />	
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="iterateTrainee(document.getElementById('traineePageNumber').value,'next');">>></a>
			</td>
	
		</tr>		
	</table>
	<table id='dynamicProgram' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: white;"	colspan=2>Date of Issuance:</th></tr>
		<tr><td align=center style="background-color:white; color:black;">
		<select name='issuanceMonth'>
<?php
		for($i=1;$i<13;$i++){
		$year=date("Y");
		$selectedMonth=date("m");
		?>
		<option <?php if($i==$selectedMonth){ echo "selected"; } ?> value='<?php echo $i; ?>' >
		<?php echo date("F",strtotime($year."-".$i."-01")); ?>
		</option>
<?php
		}
?>	
		</select>
		
		<font style="color:white;" color=white>|</font>
		<select name='issuanceDay'>
<?php
		for($i=1;$i<32;$i++){
		$year=date("Y");
		$selectedDay=date("d");

		?>
		<option <?php if($i==$selectedDay){ echo "selected"; } ?> value='<?php echo $i; ?>' >
		<?php echo $i; ?>
		</option>
<?php
		}
?>	
		</select>
		<font style="color:white;" color=white>|</font>
		<select name='issuanceYear'>
<?php
		$startYear=date("Y")*1-5;
		$endYear=$startYear*1+16;
		$selectedYear=date("Y");
		for($i=$startYear;$i<$endYear;$i++){
		?>
		<option <?php if($i==$selectedYear){ echo "selected"; } ?> value='<?php echo $i; ?>' >
		<?php echo $i; ?>
		</option>
<?php
		}
?>	
		</select>
		</td>
		
		</tr>
	
	
	</table>
	<table id='dynamicProgram' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: white;"	colspan=2>Type of Issuance:</th></tr>
		<tr><td align=center style="background-color:white; color:black;">
		<select name='issuanceType' id='issuanceType'>
			<option>Re-Issuance</option>
		</select>
		</td>
		</tr>
	</table>
	<table id='dynamicProgram' width=400 align=center>
	<tr><td align=center><input type=submit value='Release (Print out) Diploma' /></td></tr>	
	</table>
</form>