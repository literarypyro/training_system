
<?php

	if((isset($_GET['setMonth']))&&(isset($_GET['setYear']))){
		$setMonth=$_GET['setMonth'];
		$setYear=$_GET['setYear'];
		
		if($setMonth<0){
			$setMonth="1";
		}
		if($setMonth>12){
			$setMonth="12";
		}
		
		
	}
	else {
		$setMonth="01";
		$setYear="1999";
	
	}
	$labelDay=date("F Y",strtotime($setYear."-".$setMonth."-01"));
	
	$db=retrieveTrainingDb();
	//$db=new mysqli("localhost","root","","training");
	$startDate=date("Y-m-d",strtotime($setYear."-".$setMonth."-01"));
	$endDate=date("Y-m-d",strtotime($setYear."-".$setMonth."-31"));

	$sql="select * from training_instance where start_date between '".$startDate." 00:00:00' and '".$endDate." 23:23:59'";
//	$sql="select * from training_instance order by start_date desc";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	
	
?>
	<script language="javascript">
	function scrollDate(){
		var setMonth=document.getElementById('labelMonth').value;
		var setYear=document.getElementById('labelYear').value;
		window.open("training_event.php?a=md&setYear="+setYear+"&setMonth="+setMonth,'_self');
	}
	function deleteRecord(eventID){
		var check=confirm("Delete the Training Event?");
		
		if(check){
			window.open("delete_event.php?eventID="+eventID,"_blank");
		}
	}
	</script>
	<table id='indexhere' align=center style='border: 1px solid gray' width=600>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Search Training Schedules</h1>
	</th>
	</table>
	<table  align=center style='border: 1px solid gray' width=400>
		<tr>
			<td align=center colspan=2 style='background-color: #ed5214;'>
			<a href='<?php echo $PHP_SELF."?a=md&setYear=".($setYear*1-1)."&setMonth=01"; ?>' style='color:white;text-decoration: none;' ><<</a>
			<font color=#ed5214>||</font>
			<a href='<?php echo $PHP_SELF."?a=md&setYear=".$setYear."&setMonth=".($setMonth*1-1); ?>' style='color:white;text-decoration: none;'><</a>
			<select id='labelMonth'  onchange='scrollDate()'>
	<?php
			for($i=1;$i<13;$i++){
			$year=date("Y");
			$selectedMonth=date("m");
			?>
			<option value='<?php echo $i; ?>' >
			<?php echo date("F",strtotime($year."-".$i."-01")); ?>
			</option>
	<?php
			}
	?>	
			</select>	
			<select id='labelYear' onchange='scrollDate()'>
			<?php
					$startYear=date("Y")*1-5;
					$endYear=$startYear*1+16;
					$selectedYear=date("Y");
					for($i=1999;$i<$endYear;$i++){
					?>
					<option value='<?php echo $i; ?>' >
					<?php echo $i; ?>
					</option>
			<?php
					}
			?>	
					</select>


			<?php
			echo "<script language='javascript'>";
			echo "document.getElementById('labelMonth').value='".$setMonth."';";
			echo "document.getElementById('labelYear').value='".$setYear."';";
			
			echo "</script>";
			
			?>
			<a href='<?php echo $PHP_SELF."?a=md&setYear=".$setYear."&setMonth=".($setMonth*1+1); ?>'  style='color:white;text-decoration: none;' >></a>
			<font color=#ed5214>||</font>

			<a href='<?php echo $PHP_SELF."?a=md&setYear=".($setYear*1+1)."&setMonth=01"; ?>' style='color:white;text-decoration: none;'  >>></a>
			</td>
	
		</tr>

	</table>	
	
	<table id='alterTable' width=100%>
		<tr>
		<th>Training Course</th>
		<th>Batch Number</th>
		<th>Start Date</th>
		<th>End Date</th>
		<th>Number of Participants</th>
		</tr>
		<?php
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			
			$sql2="select * from class_instance where training_event_id='".$row['id']."'";
			$rs2=$db->query($sql2);
			
			$nm2=$rs2->num_rows;
			
			$sql3="select * from training_schedule where event_id='".$row['id']."'";
			$rs3=$db->query($sql3);
			
			$nm3=$rs3->num_rows;
			
			$sql4="select * from trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id where event_id='".$row['id']."'";
			$rs4=$db->query($sql4);
			$nm4=$rs4->num_rows;
		?>
			<tr>
				<td><a href='#' onclick='window.open("editEvent2.php?retrieveID=<?php echo $row['id'];?>")'><?php echo $row['training_title']; ?></a></td>
				<td align=center><?php echo $row['batch_number']; ?></td>
				<td align=center><?php echo date("F d, Y",strtotime($row['start_date'])); ?></td>
				<td align=center><?php echo date("F d, Y",strtotime($row['end_date'])); ?></td>
				<td align=center><?php echo $nm2; ?> <a href='#' onclick='deleteRecord("<?php echo $row['id']; ?>");'>X</a></td>
				
			</tr>
		<?php
		}
		
		?>
	</table>
<?php
?>