<form action="training_database.php?pp=2&action=add" method="post">
	<table  align=center style='border: 1px solid gray'>
		<tr><th style="background-color:#ed5214;color:white;" colspan=2>Add New Trainer</th></tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">
			First Name
			</td>
			<td style="background-color: white; color:black;">
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
			<td style="background-color: #00cc66;color: black;">Position</td>
			<td style="background-color: white; color:black;"><input type='text' name='position' size=30 /></td>
		</tr>
		<tr>
			<td style="background-color:#ed5214;color:white;"  colspan=2 align=center><input type=submit value='Submit' /><input type=hidden name='user_name' value='<?php echo $_POST['login_user']; ?>' /></td>
		</tr>
		</table>


</form>