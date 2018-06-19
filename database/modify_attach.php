
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
