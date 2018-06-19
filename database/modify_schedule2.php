
<?php
	$db=new mysqli("localhost","root","","training");
	$latestDate=date("Y-m-d",strtotime(date("Y-m-d")."-1 day"));

	$currentYear=date("Y");
	$sql="select * from training_instance where start_date between '".$latestDate." 00:00:00' and '".$currentYear."-12-31 23:23:59'";
	
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	
	
?>
<?php

	
	echo "<script language='javascript'>";
	echo "var trainingEvent=new Array();";
	echo "var trainingCount=1;";
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();

		$sql2="select * from class_instance where training_event_id='".$row['id']."'";
		$rs2=$db->query($sql2);
		
		$nm2=$rs2->num_rows;
		
		$sql3="select * from training_schedule where event_id='".$row['id']."'";
		$rs3=$db->query($sql3);
		
		$nm3=$rs3->num_rows;
		
			
		echo "trainingEvent[trainingCount]=new Array();";	
		echo "trainingEvent[trainingCount]['trainingTitle']='".$row['training_title']."';";	
		echo "trainingEvent[trainingCount]['batchNumber']='".$row['batch_number']."';";	
		echo "trainingEvent[trainingCount]['trainer']='".$row['trainer']."';";	

		echo "trainingEvent[trainingCount]['trainee']=new Array();";	
		echo "trainingEvent[trainingCount]['traineeTable']=new Array();";	

		echo "trainingEvent[trainingCount]['schedule']=new Array();";	
		echo "trainingEvent[trainingCount]['scheduleTable']=new Array();";	

		if($i==0){
			$trainingTitle=$row['training_title'];
			$trainingBatch=$row['batch_number'];
			$trainingTrainer=$row['trainer'];
			for($k=0;$k<$nm2;$k++){
				$row2=$rs2->fetch_assoc();
				$trainingTrainee[$k]=strtoupper($row2['lastName']).", ".$row2['firstName'];	
				echo "trainingEvent[trainingCount]['trainee'][".$k."]='".strtoupper($row2['lastName']).", ".$row2['firstName']."';";	
				echo "trainingEvent[trainingCount]['traineeTable'][".$k."]='<tr><td colspan=2 align=center style=\"background-color: white; color:black;\"><input type=text id=\"training_title\" name=\"training_title\" value=\"".$trainingTrainee[$k]."\" id=\"title\" size=50% /></td></tr>';";
			}
			for($m=0;$m<$nm3;$m++){
				$row3=$rs3->fetch_assoc();
				$trainingSchedule[$m]=date("F d, Y",strtotime($row3['date'])).", ".date("h",strtotime($row3['start_time']))."-".date("hA",strtotime($row3['end_time']));	
				
				echo "trainingEvent[trainingCount]['schedule'][".$m."]='".$trainingSchedule[$m]."';";	
				echo "trainingEvent[trainingCount]['scheduleTable'][".$m."]='<tr><td colspan=2 align=center style=\"background-color: white; color:black;\"><input type=text id=\"training_title\" name=\"training_title\" value=\"".$trainingSchedule[$m]."\" id=\"title\" size=50% /></td></tr>';";

			
			}
		}
		else {
			for($k=0;$k<$nm2;$k++){
				echo "trainingEvent[trainingCount]['trainee'][".$k."]='".strtoupper($row2['lastName']).", ".$row2['firstName']."';";	
				echo "trainingEvent[trainingCount]['traineeTable'][".$k."];";	
				echo "trainingEvent[trainingCount]['traineeTable'][".$k."]='<tr><td colspan=2 align=center style=\"background-color: white; color:black;\"><input type=text id=\"training_title\" name=\"training_title\" value=\"".$trainingTrainee[$k]."\" id=\"title\" size=50% /></td></tr>';";

			}
			for($m=0;$m<$nm3;$m++){
				$row3=$rs3->fetch_assoc();
				$trainingSchedule[$m]=date("F d, Y",strtotime($row3['date'])).", ".date("h",strtotime($row3['start_time']))."-".date("hA",strtotime($row3['end_time']));	
				
				echo "trainingEvent[trainingCount]['schedule'][".$m."]='".$trainingSchedule[$m]."';";	
				echo "trainingEvent[trainingCount]['scheduleTable'][".$m."]='<tr><td colspan=2 align=center style=\"background-color: white; color:black;\"><input type=text id=\"training_title\" name=\"training_title\" value=\"".$trainingSchedule[$m]."\" id=\"title\" size=50% /></td></tr>';";

/**
				<tr>
			<td align=center colspan=2 style="background-color: white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $trainingSchedule[$i]; ?>" id='title' size=50% />
			</td>
		</tr>
	*/		
			}

		}
		



		echo "trainingCount++;";
	}
	
	echo "</script>";
	
	
	

?>
<script language='javascript'>
function iterate(pgindex,action){
	if(action=="prev"){
		if(pgindex=="1"){
			alert("You have reached the end of the record");
		}
		else {
		document.getElementById('pageNumber').value=pgindex*1-1;

		document.getElementById('training_title').innerHTML="";
		document.getElementById('batch_number').innerHTML="";
		document.getElementById('trainer').value="";


//		document.getElementById('trainee_year').value="";

		document.getElementById('training_title').innerHTML=trainingEvent[pgindex*1-1]['trainingTitle'];
		document.getElementById('batch_number').innerHTML=trainingEvent[pgindex*1-1]['batchNumber'];
		document.getElementById('trainer').value=trainingEvent[pgindex*1-1]['trainer'];
		var traineeGrid='<tr><td align=center colspan=2 style="background-color: #00cc66;color: white;">Trainees for the Class</td></tr>';
		
		var traineeGridCount=trainingEvent[pgindex*1-1]['traineeTable'].length;
		for(i=0;i<traineeGridCount;i++){
			
			traineeGrid+=trainingEvent[pgindex*1-1]['traineeTable'][i];
		
		
		}
		traineeGrid+='<tr><td colspan=2 style=" background-color: #ed5214;" colspan=2></td></tr>';

		document.getElementById('traineeGrid').innerHTML=traineeGrid;
		
		var scheduleGrid='<tr><td align=center colspan=2 style="background-color: #00cc66;color: white;">Schedules Listed</td></tr>';
		var scheduleGridCount=trainingEvent[pgindex*1-1]['scheduleTable'].length;
		for(i=0;i<scheduleGridCount;i++){
			
			scheduleGrid+=trainingEvent[pgindex*1-1]['scheduleTable'][i];
		
		
		}
		document.getElementById('scheduleGrid').innerHTML=scheduleGrid;		
		
		//document.getElementById('trainee_year').value=student[pgindex*1-1]['yearAcquired'];
		}
	}
	else if(action=="next"){
		if(pgindex==trainingCount*1-1){
			alert("You have reached the end of the record");
		}
		else {
			document.getElementById('pageNumber').value=pgindex*1+1;
			document.getElementById('training_title').innerHTML="";
			document.getElementById('batch_number').innerHTML="";
			document.getElementById('trainer').value="";
//			document.getElementById('trainee_year').value="";

		document.getElementById('training_title').innerHTML=trainingEvent[pgindex*1+1]['trainingTitle'];
		document.getElementById('batch_number').innerHTML=trainingEvent[pgindex*1+1]['batchNumber'];
		document.getElementById('trainer').value=trainingEvent[pgindex*1+1]['trainer'];
		document.getElementById('traineeGrid').innerHTML='<tr><td align=center colspan=2 style="background-color: #00cc66;color: white;">Trainees for the Class</td></tr>';

		var traineeGrid='<tr><td align=center colspan=2 style="background-color: #00cc66;color: white;">Trainees for the Class</td></tr>';
		
		var traineeGridCount=trainingEvent[pgindex*1+1]['traineeTable'].length;
		for(i=0;i<traineeGridCount;i++){
			
			traineeGrid+=trainingEvent[pgindex*1+1]['traineeTable'][i];
		
		
		}
		traineeGrid+='<tr><td colspan=2 style=" background-color: #ed5214;" colspan=2></td></tr>';

		document.getElementById('traineeGrid').innerHTML=traineeGrid;

		
		var scheduleGrid='<tr><td align=center colspan=2 style="background-color: #00cc66;color: white;">Schedules Listed</td></tr>';
		var scheduleGridCount=trainingEvent[pgindex*1+1]['scheduleTable'].length;
		for(i=0;i<scheduleGridCount;i++){
			
			scheduleGrid+=trainingEvent[pgindex*1+1]['scheduleTable'][i];
		
		
		}

		document.getElementById('scheduleGrid').innerHTML=scheduleGrid;		
		//	document.getElementById('trainee_year').value=student[pgindex*1+1]['yearAcquired'];
			}
	}
	else {
		
		if(pgindex>trainingCount*1-1){
			alert("You have reached the end of the record");
			document.getElementById('pageNumber').value=trainingCount*1-1;
			pgindex=trainingCount;
		}
		else if(pgindex<=0){
			alert("You have reached the end of the record");
			document.getElementById('pageNumber').value=1;
			pgindex=1;
		}
		
		

		document.getElementById('training_title').innerHTML="";
		document.getElementById('batch_number').innerHTML="";
		document.getElementById('trainer').value="";
//		document.getElementById('trainee_year').value="";

		document.getElementById('training_title').innerHTML=trainingEvent[pgindex]['trainingTitle'];
		document.getElementById('batch_number').innerHTML=trainingEvent[pgindex]['batchNumber'];
		document.getElementById('trainer').value=trainingEvent[pgindex]['trainer'];
		document.getElementById('traineeGrid').innerHTML='<tr><td align=center colspan=2 style="background-color: #00cc66;color: white;">Trainees for the Class</td></tr>';

		//		document.getElementById('trainee_year').value=student[pgindex]['yearAcquired'];
		var traineeGrid='<tr><td align=center colspan=2 style="background-color: #00cc66;color: white;">Trainees for the Class</td></tr>';
		
		var traineeGridCount=trainingEvent[pgindex]['traineeTable'].length;
		for(i=0;i<traineeGridCount;i++){
			
			traineeGrid+=trainingEvent[pgindex]['traineeTable'][i];
		
		
		}
		traineeGrid+='<tr><td colspan=2 style=" background-color: #ed5214;" colspan=2></td></tr>';
		
		
		document.getElementById('traineeGrid').innerHTML=traineeGrid;

		var scheduleGrid='<tr><td align=center colspan=2 style="background-color: #00cc66;color: white;">Schedules Listed</td></tr>';
		var scheduleGridCount=trainingEvent[pgindex]['scheduleTable'].length;
		for(i=0;i<scheduleGridCount;i++){
			
			scheduleGrid+=trainingEvent[pgindex]['scheduleTable'][i];
		
		
		}
		document.getElementById('scheduleGrid').innerHTML=scheduleGrid;
	}

}
</script>
	<table id='indexhere' align=center style='border: 1px solid gray' width=600>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Edit Training Module</h1>
	</th>
	</table>
	<table  align=center style='border: 1px solid gray' width=400>
		<tr><th  style='background-color: #ed5214; color:white;' colspan=2>&nbsp;</th></tr>
		<tr>
			<td style="background-color: #00cc66;color: white;">
			Title of Program
			</td >
			<td width=220 id='trainingTitleLabel' style="background-color: white; color:black;"><a id='training_title' style="text-decoration:none;" href="#indexhere"><?php echo $trainingTitle; ?></a>
			</td>
		</tr>	
		<tr>
			<td style="background-color: #00cc66;color: white;">
			Batch Number
			</td>
			<td style="background-color: white; color:black;"><a id='batch_number' style="text-decoration:none;" href="#indexhere"><?php echo $trainingBatch; ?></a>
			
			</td>
		</tr>
		<tr>
			<td style="background-color: #00cc66;color: white;">
			Trainer for the Event
			</td>
			<td style="background-color: white; color:black;"><a id='trainer' style="text-decoration:none;" href="#indexhere"><?php echo $trainingTrainer; ?></a>
			</td>
		</tr>
		<tr>
			<td style=" background-color: #ed5214;" colspan=2>
			</td>
		</tr>
		</table>
		<table id='traineeGrid' align=center width=400>
		<tr>
			<td align=center colspan=2 style="background-color: #00cc66;color: white;">
			Trainees for the Class
			</td>
		</tr>
		<?php
		
		for($i=0;$i<count($trainingTrainee);$i++){
		
		?>
		<tr>
			<td colspan=2 align=center style="background-color: white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $trainingTrainee[$i]; ?>" id='title' size=50% />
			</td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td colspan=2 style=" background-color: #ed5214;" colspan=2>
			</td>
		</tr>
		</table>
		<table id='scheduleGrid' align=center width=400>
		<tr>
			<td colspan=2 align=center style="background-color: #00cc66;color: white;">
			Schedules Listed
			</td>
		</tr>
		<?php
		
		for($i=0;$i<count($trainingSchedule);$i++){
		
		?>
		<tr>
			<td align=center colspan=2 style="background-color: white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $trainingSchedule[$i]; ?>" id='title' size=50% />
			</td>
		</tr>
		<?php
		}
		?>
		</table>
		<table  align=center width=400>
		<tr>
			<td colspan=2 style='background-color: #ed5214;'>
			<a href='#indexhere' style='color:white;text-decoration: none;' onclick="iterate(document.getElementById('pageNumber').value,'prev');"><<</a>
			<input id='pageNumber' style="text-align:center" type="text" name="pageNumber" size=4 value='1' onkeyup="iterate(document.getElementById('pageNumber').value,'index');" />	
			<a href='#indexhere' style='color:white;text-decoration: none;'  onclick="iterate(document.getElementById('pageNumber').value,'next');">>></a>
			</td>
	
		</tr>	
		<tr>
			<td colspan=2 align=center><input type=submit value='Edit' /><input type=hidden name='user_name' value='<?php echo $_POST['login_user']; ?>' /></td>
		</tr>
		</table>
<?php
?>