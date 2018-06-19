<?php
	$dateLatest=date("Y-m-d",strtotime(date("Y-m-d")."-14 days"));
	$dateLatest.=" 23:23:59";

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
	

$startHour="12";
$startMinute="0";
$startHalf="PM";

$endHour="5";
$endMinute="30";
$endHalf="PM";


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
	document.getElementById('event_id').value=selectedTrainingID;
	
}
function retrieveEvent(item){
	iterateTraining(item*1,"index");

	document.getElementById('searchResults').innerHTML="<td style='background-color: #ed5214;'></td><td style='background-color: #ed5214;'></td>";
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
</script>
<form action='generate_availability.php' method='post' >
	<table align=center style=" border: 1px solid gray;" width=90%>
	<tr>
	<th valign=center style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Request for Train Availability</h1>
	</th>
	</tr>
	</table>
	<br>
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

	<table width=60% style='border: 1px solid gray' >
	<tr>
	<th valign=center style="vertical-align:center;background-color:#ed5214;color:white;" colspan=2 >Format Request Form
	</th>
	</tr>
	<tr>
	<td valign=center style="vertical-align:center;background-color: #00cc66;color: black;" width=30%>Letter Date
	
	
	</td>
	<td valign=center style="vertical-align:center;background-color:white;color:black;" width=70%>
		<select id='releaseMonth' name='releaseMonth'>
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
		<select id='releaseDay' name='releaseDay'>
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
		<select id='releaseYear' name='releaseYear'>
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
	<tr>
	<td valign=center style="vertical-align:center;background-color: #00cc66;color: black;">Addressee (Prefix, First Name, Mid. Initial, Surname)
	</td>
	<td valign=center style="vertical-align:center;background-color:white;color:black;">
	<input type=text size=6 name='prefix' value='Engr.' /> 
	<input type=text name='firstName' value='Velson' /> 	
	<input type=text size=3 name='midInitial' value='' /> 	
	<input type=text name='lastName' value='Manapat' /> 
	</td>
	</tr>
	<tr>
	<tr>
	<td valign=center style="vertical-align:center;background-color: #00cc66;color: black;">Addressee Position, Company
	</td>
	<td valign=center style="vertical-align:center;background-color:white;color:black;">
	<input type=text name='position' value='Vehicle Manager' />,  	
	<input type=text name='company' value='TES-Philippines' /> 	
	</td>
	</tr>
	<tr>

	<td valign=center style="vertical-align:center;background-color: #00cc66;color: black;" >Type of Activity
	</td>
	<td valign=center style="vertical-align:center;background-color:white;color:black;"><input type=text name='activity' value='practical exercises' />
	</td>
	</tr>
	<tr>
	<td valign=center style="vertical-align:center;background-color: #00cc66;color: black;">Additional Clause
	</td>
	<td valign=center style="vertical-align:center;background-color:white;color:black;">
	<textarea rows=4 cols=50% name='additionalClause'> especially on Backward Driving Procedure</textarea>
	</td>
	</tr>	
	<tr>
	<td valign=center style="vertical-align:center;background-color: #00cc66;color: black;" >Testing Venue
	</td>
	<td valign=center style="vertical-align:center;background-color:white;color:black;"><textarea rows=4 cols=50% name='venue' >Depot Stabling Area</textarea>
	</td>
	</tr>
	</tr>	
	<tr>
	<td valign=center style="vertical-align:center;background-color: #00cc66;color: black;" >Schedule of Usage
	</td>
	<td valign=center style="vertical-align:center;background-color:white;color:black;">From  
		<font style="color:white;" color=white>|</font>
	<select id='startingHour' name='startingHour' onchange="enterShift('startingShift','AM')">
				<?php 
				for($m=1;$m<13;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					}
				?>
					<option <?php if($m*1==$startHour*1){ echo "selected"; } ?>>
					<?php echo $label; ?>
					</option>
				<?php
				}
				?>
				</select>
				
				<select id='startingMinute'  name='startingMinute' onchange="enterShift('startingShift','AM')">
				<?php 
				for($m=0;$m<60;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
				?>
					<option <?php if($m*1==$startMinute*1){ echo "selected"; } ?>><?php echo $label; ?></option>
				<?php
				}
				?>
				</select>
				<select id='startingHalf'  name='startingHalf' onchange="enterShift('startingShift','AM')">
				<option <?php if($startHalf=="AM"){ echo "selected"; } ?>>AM</option>
				<option <?php if($startHalf=="PM"){ echo "selected"; } ?>>PM</option>
				</select> 
		<font style="color:white;" color=white>|</font>
		to 
		<font style="color:white;" color=white>|</font>
		<select id='endingHour'  name='endingHour' onchange="enterShift('endingShift','PM')">
				<?php 
				for($m=1;$m<13;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
					
					
				?>
					<option  <?php if($m*1==$endHour*1){ echo "selected"; } ?>>
					<?php echo $label; ?>
					</option>
				<?php
				}
				?>
				</select>
				
				<select id='endingMinute'  name='endingMinute' onchange="enterShift('endingShift','PM')">
				<?php 
				for($m=0;$m<60;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
				?>
					<option <?php if($m*1==$endMinute*1){ echo "selected"; } ?>><?php echo $label; ?></option>
				<?php
				}
				?>
				</select>	
				<select id='endingHalf'  name='endingHalf' onchange="enterShift('endingShift','PM')">
				<option <?php if($endHalf=="AM"){ echo "selected"; } ?>>AM</option>
				<option <?php if($endHalf=="PM"){ echo "selected"; } ?>>PM</option>
				</select>

	
	
	</td>
	</tr>
	
	</table>
	<table width=60%>
	<tr>
	<td align=center><input type=hidden name='event_id' id='event_id' /><input type=submit value='Generate Printout' />
	</td>
	</tr>
	</table>
	</form>
	
