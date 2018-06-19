<?php
$db=retrieveTrainingDb();
//$db=new mysqli("localhost","root","","training");
$sql="select * from login";
$rs=$db->query($sql);

$shuttle=$rs;
$nm=$shuttle->num_rows;
for($i=0;$i<$nm;$i++){
	$dgrid[$i*1+1]=$rs->fetch_assoc();


}
$newgrid=count($dgrid)*1+1;
?>
<?php
echo "<script language='javascript'>";
echo "var row=new Array();";
echo "var pageindex=1;";
echo "var limit=".$newgrid."*1-1;";
for($i=1;$i<$newgrid;$i++){
	echo "row[".$i."]=new Array();";
	echo "row[".$i."]['firstName']='".$dgrid[$i]['firstName']."';";
	echo "row[".$i."]['lastName']='".$dgrid[$i]['lastName']."';";
	echo "row[".$i."]['password']='".$dgrid[$i]['password']."';";
	echo "row[".$i."]['username']='".$dgrid[$i]['username']."';";



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

		document.getElementById('first_name').value="";
		document.getElementById('last_name').value="";
		document.getElementById('password').value="";
		document.getElementById('username').value="";
		document.getElementById('user_name').value="";
	
		document.getElementById('first_name').value=row[pgindex*1-1]['firstName'];
		document.getElementById('last_name').value=row[pgindex*1-1]['lastName'];
		document.getElementById('password').value=row[pgindex*1-1]['password'];
		document.getElementById('username').value=row[pgindex*1-1]['username'];

		document.getElementById('user_name').value=row[pgindex*1-1]['username'];		

		}
	}
	else if(action=="next"){
		if(pgindex==limit){
			alert("You have reached the end of the record");
		}
		else {
		document.getElementById('pageNumber').value=pgindex*1+1;
		document.getElementById('first_name').value="";
		document.getElementById('last_name').value="";
		document.getElementById('password').value="";
		document.getElementById('username').value="";

		document.getElementById('user_name').value="";

		document.getElementById('first_name').value=row[pgindex*1+1]['firstName'];
		document.getElementById('last_name').value=row[pgindex*1+1]['lastName'];
		document.getElementById('password').value=row[pgindex*1+1]['password'];
		document.getElementById('username').value=row[pgindex*1+1]['username'];

		document.getElementById('user_name').value=row[pgindex*1+1]['username'];		
		}
	}
	else {
		if(pgindex>limit){
			alert("You have reached the end of the record");
			document.getElementById('pageNumber').value=limit;
			pgindex=limit;
		}
		else if(pgindex<=0){
			alert("You have reached the end of the record");
			document.getElementById('pageNumber').value=1;
			pgindex=1;
		}
		
		

		document.getElementById('pageNumber').value=pgindex*1;
		document.getElementById('first_name').value="";
		document.getElementById('last_name').value="";
		document.getElementById('password').value="";
		document.getElementById('username').value="";

		document.getElementById('user_name').value="";


		document.getElementById('first_name').value=row[pgindex*1]['firstName'];
		document.getElementById('last_name').value=row[pgindex*1]['lastName'];
		document.getElementById('password').value=row[pgindex*1]['password'];
		document.getElementById('username').value=row[pgindex*1]['username'];

		document.getElementById('user_name').value=row[pgindex*1]['username'];	
	
	
	}

}
</script>
<form action="training_database.php?pp=m3&action=edit" method="post">
	<table align=center>
		<tr><th  style="background-color:#ed5214;color:white;" colspan=2>Edit Login User</th></tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">
			First Name
			</td>
			<td  style="background-color: white; color:black;">
			<input type=text id='first_name' name='first_name' value="<?php echo $dgrid[1]["firstName"]; ?>" size=30 />
			</td>
		</tr>	
		<tr>
			<td style="background-color: #00cc66;color: black;">
			Last Name
			</td>
			<td  style="background-color: white; color:black;">
				<input type=text id='last_name' name='last_name' value="<?php echo $dgrid[1]["lastName"]; ?>" size=30 />
			</td>
		</tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">Username</td>
			<td style="background-color: white; color:black;"><input type='text' value="<?php echo $dgrid[1]["username"]; ?>" id='username' name='username' size=30 /></td>
		</tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">Password</td>
			<td style="background-color: white; color:black;"><input type='password' value="<?php echo $dgrid[1]["password"]; ?>" id='password' name='password' size=30 /></td>
		</tr>
		<tr>
			<td colspan=2 style='background-color: #ed5214;'>
			<a href='#' style='color:white;text-decoration: none;' onclick="iterate(document.getElementById('pageNumber').value,'prev');"><<</a>
			<input id='pageNumber' style="text-align:center" type="text" name="pageNumber" size=4 value='1' onkeyup="iterate(document.getElementById('pageNumber').value,'index');" />	
			<a href='#' style='color:white;text-decoration: none;'  onclick="iterate(document.getElementById('pageNumber').value,'next');">>></a>
			</td>
	
		</tr>
		<tr>
			<td colspan=2 align=center><input type=submit value='Edit' /><input type=hidden name='user_name' id='user_name' value="<?php echo $dgrid[1]['username']; ?>" /></td>
		</tr>


		</table>


</form>