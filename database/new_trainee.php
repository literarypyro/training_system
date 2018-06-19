<form action="training_database.php?pp=1&action=add" method="post">
	<table align=center style='border: 1px solid gray'>
		<tr><th  style="background-color:#ed5214;color:white;" colspan=2>Add New Participant</th></tr>
		<tr>
			<td  style="background-color: #00cc66;color: black;">
			First Name
			</td>
			<td  style="background-color: white; color:black;">
			<input type=text name='first_name' size=30 />
			</td>
		</tr>	
		<tr>
			<td style="background-color: #00cc66;color: black;">
			Last Name
			</td>
			<td style="background-color: white; color:black;">
				<input type=text name='last_name' size=30 />
			</td>
		</tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">
			Middle Name
			</td>
			<td style="background-color: white; color:black;">
				<input type=text name='midInitial' size=30 />
			</td>
		</tr>		
		<tr>
			<td style="background-color: #00cc66;color: black;">Designation</td>
			<td style="background-color: white; color:black;"><input type='text' name='position' size=30 /></td>
		</tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">Division Assigned<br>(Optional)</td>
			<td style="background-color: white; color:black;">
			<select name='divAssigned' id='divAssigned'>
				<option></option>
				<?php
				$db=retrieveTrainingDb();
				//$db=new mysqli("localhost","root","","training");
				$sql="select * from division";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();
				?>
					<option value='<?php echo $row['division_code']; ?>'><?php echo $row['division_name']; ?></option>
				<?php	
				
				}
				?>	
			</select>
			</td>
		</tr>
		<tr>
			<td style="background-color:#ed5214;color:white;" colspan=2 align=center><input type=submit value='Submit' /><input type=hidden name='user_name' value='<?php echo $_POST['login_user']; ?>' /></td>
		</tr>
		</table>


</form>