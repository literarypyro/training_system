<?php




$db=new mysqli("localhost","root","","training");
$latestDate=date("Y-m-d",strtotime(date("Y-m-d")."-1 day"));

$currentYear=date("Y");
$sql2="select * from training_instance where start_date between '".$latestDate." 00:00:00' and '".$currentYear."-12-31 23:23:59'";
$rs2=$db->query($sql2);
$nm2=$rs2->num_rows;


$sql="select * from (class_list inner join training_events on class_list.training_events_id=training_events.id) inner join trainee_list on trainee_id=trainee_list.id where start_date between '".$latestDate." 00:00:00' and '".$currentYear."-12-31 23:23:59'";
$rs=$db->query($sql);

$nm=$rs->num_rows;

echo "<script language='javascript'>";
echo "var trainingEvent=new Array();";
echo "var trainingEventCount=1;";

echo "var trainees=new Array();";
echo "var traineeCount=new Array();";

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






	echo "traineeCount[\"".$row['training_events_id']."\"]++;";
	
}
echo "</script>";
?>
<script language="javascript">
	//alert(trainingEvent[1]['trainees'][1]);


</script>

<script language="javascript">
function iterateTraining(pgindex,action){
	if(action=="prev"){

		if(pgindex==1){
			alert("You have reached the end of the record.");
		
		}
		else {
			document.getElementById('trainingPageNumber').value=pgindex*1-1;
			

			document.getElementById('training_title').value=trainingEvent[pgindex*1-1]['title'];
			document.getElementById('batch').value=trainingEvent[pgindex*1-1]['batch'];
			document.getElementById('trainer').value=trainingEvent[pgindex*1-1]['trainer'];
		
			document.getElementById('period').value="From "+(trainingEvent[pgindex*1-1]['start_time'])+" to "+(trainingEvent[pgindex*1-1]['end_time']);

			document.getElementById('training_id').value=trainingEvent[pgindex*1-1]['ID'];
			
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
			document.getElementById('trainer').value=trainingEvent[pgindex*1+1]['trainer'];
			document.getElementById('period').value="From "+(trainingEvent[pgindex*1+1]['start_time'])+" to "+(trainingEvent[pgindex*1+1]['end_time']);
			document.getElementById('training_id').value=trainingEvent[pgindex*1+1]['ID'];
		
		}
	
	
	}
	else {
		
	
	
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

	var currentRecord=trainingEvent[document.getElementById('trainingPageNumber').value]['ID'];
	if(action=="prev"){

		if(pgindex==1){
			alert("You have reached the end of the record.");
		
		}
		else {
			document.getElementById('first_name').value=trainees[currentRecord][pgindex*1-1]['first_name'];
			document.getElementById('last_name').value=trainees[currentRecord][pgindex*1-1]['last_name'];
			document.getElementById('position').value=trainees[currentRecord][pgindex*1-1]['position'];
			document.getElementById('traineePageNumber').value=pgindex*1-1;
			document.getElementById('trainee_id').value=trainees[currentRecord][pgindex*1-1]['ID'];

		
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

		}
	}	
	
	
}


</script>
<form action="form_generation.php" method="post">
	<table id='indexhere' align=center style='border: 1px solid gray' width=600>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Print Out Diploma</h1>
	</th>
	</table>
	<table id='dynamicProgram' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: white;"	colspan=2>Select Training Event</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			Training Title
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 />
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
			Trainer
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='trainer' name='trainer' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 />
			</td>
		</tr>			
		<tr>
			<td style="background-color:white; color:black;">
			Period
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='period' name='period' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 />
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
<br>
	<table id='dynamicProgram' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: white;"	colspan=2>Enrolled Trainees</th></tr>
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
	<table id='dynamicProgram' width=400 align=center>
	<tr><td align=center><input type=submit value='Print Out Diploma' /></td></tr>	
	</table>
</form>