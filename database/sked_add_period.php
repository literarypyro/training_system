<?php
require("../db_page.php");

$db2=retrieveTemporaryDb();
?>
<?php
if(isset($_POST['startingYear'])){

	$start_month=$_POST['startingMonth'];
	$start_day=$_POST['startingDay'];
	$start_year=$_POST['startingYear'];

	$start_hour=$_POST['startingHour'];
	$start_minute=$_POST['startingMinute'];
	$start_half=$_POST['startingHalf'];

	if($start_half=='PM'){
		$start_hour+=(12*1);
		if($start_hour>=24){
			$start_hour=0;
		}
	}
	else {
		$start_hour=$start_hour;
		
	}

	$end_month=$_POST['endingMonth'];
	$end_day=$_POST['endingDay'];
	$end_year=$_POST['endingYear'];

	$end_hour=$_POST['endingHour'];
	$end_minute=$_POST['endingMinute'];
	$end_half=$_POST['endingHalf'];
	
	if($end_half=='PM'){
		$end_hour+=(12*1);
		if($end_hour>=24){
			$end_hour=0;
		}
	}
	else {
		$end_hour=$end_hour;
		
	}
	$no_of_days=$_POST['no_days'];
	
	$start_time=$start_year."-".$start_month."-".$start_day." ".$start_hour.":".$start_minute;
	$end_time=$end_year."-".$end_month."-".$end_day." ".$end_hour.":".$end_minute;
	
	$eventSQL="select * from event";
	$eventRS=$db2->query($eventSQL);
	
	$eventRow=$eventRS->fetch_assoc();
	$eventId=$eventRow['id'];

	
	$sked_sql="select * from schedule";
	$sked_rs=$db2->query($sked_sql);

	$sked_nm=$sked_rs->num_rows;
	
	if($sked_nm>0){
		$update="delete from schedule";
		$updateRS=$db2->query($update);

		$update="insert into schedule(event_id,date_from,date_to,no_of_days) values ('".$eventId."','".$start_time."','".$end_time."','".$no_of_days."')";
		$updateRS=$db2->query($update);
	
	}
	else {
		$update="insert into schedule(event_id,date_from,date_to,no_of_days) values ('".$eventId."','".$start_time."','".$end_time."','".$no_of_days."')";

		$updateRS=$db2->query($update);
	}
	
	echo "<script language='javascript'>";
	echo "window.opener.location.reload();";

//	echo "window.opener.location='../training_event.php?a=ZZ';";
	echo "</script>";
	
	
}
?>
	<script language='javascript'>
	function submitForm(){

		document.forms['periodForm'].submit();
//		window.opener.location="../training_event.php?a=ZZ";
//		window.opener.location.reload();



	}
	</script>
	<form name='periodForm' id='periodForm' action='sked_add_period.php' method='post'>
	<table id='dynamicTable' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Select Starting Date</th></tr>
		<tr>
		<td align=center colspan=2 style='background-color:white; color:black;'>
			<select id='startingMonth' name='startingMonth'>
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
			<select id='startingDay' name='startingDay'>
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
			<select id='startingYear' name='startingYear'>
			<?php
					$startYear=date("Y")*1-5;
					$endYear=$startYear*1+16;
					$selectedYear=date("Y");
					for($i=1999;$i<$endYear;$i++){
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
		<table  width=400 align=center style='border: 1px solid gray'>
		<tr>
			<td>
				Time: 


				<select id='startingHour' name='startingHour'>
				<?php 
				for($m=1;$m<13;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
					
					
				?>
					<option <?php if($m==8){ echo "selected"; } ?>>
					<?php echo $label; ?>
					</option>
				<?php
				}
				?>
				</select>
				
				<select id='startingMinute' name='startingMinute'>
				<?php 
				for($m=0;$m<60;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
				?>
					<option <?php if($m==30){ echo "selected"; } ?>><?php echo $label; ?></option>
				<?php
				}
				?>
				</select>
				<select id='startingHalf' name='startingHalf'>
				<option selected>AM</option>
				<option>PM</option>
				</select>
				<input type=hidden id='startingShift' value='0830AM' />
			</td>
		</tr>
		</table>
		<br>
		<table id='dynamicTable' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Select Ending Date</th></tr>
		
		<tr>
			<td  align=center colspan=2 style='background-color:white; color:black;'>
		<select id='endingMonth' name='endingMonth'>
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
		<select id='endingDay' name='endingDay'>
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
		<select id='endingYear' name='endingYear'>
<?php
		$startYear=date("Y")*1-5;
		$endYear=$startYear*1+16;
		$selectedYear=date("Y");
		for($i=1999;$i<$endYear;$i++){
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
		
		<table  width=400 align=center style='border: 1px solid gray'>

		<tr>
			<td>
				Time: 

				<select id='endingHour' name='endingHour' >
				<?php 
				for($m=1;$m<13;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
					
					
				?>
					<option <?php if($m==8){ echo "selected"; } ?>>
					<?php echo $label; ?>
					</option>
				<?php
				}
				?>
				</select>
				
				<select id='endingMinute' name='endingMinute'>
				<?php 
				for($m=0;$m<60;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
				?>
					<option <?php if($m==30){ echo "selected"; } ?>><?php echo $label; ?></option>
				<?php
				}
				?>
				</select>	
				<select id='endingHalf' name='endingHalf'>
				<option selected>AM</option>
				<option>PM</option>
				</select>
				
				<input type=hidden id='endingShift' value='0830AM' />

				
			</td>


			
		</tr>
		<tr>
		<td>Number of Days:
		<input type=text name='no_days' id='no_days' size=5 value='1' />
		</td>
		</tr>
	</table>
	<div width=400 align=center><input type='button' value='Submit' onclick='submitForm()' /></div>
	</form>
