<?php
session_start();
?>
<?php
require("db_page.php");
?>
<?php
if(isset($_POST['username'])){
//$db=new mysqli("localhost","root","","training");
$db=retrieveTrainingDb();

$sql="select * from login where username='".$_POST['username']."' and password='".$_POST['password']."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
	if($nm>0){
		$date=date("Y-m-d H:i:s");
		
		$db=new mysqli("localhost","root","","training");
		$sql="insert into log_history(log_time, username,type) values ('".$date."','".$_POST['username']."','login')";
		$rs=$db->query($sql);
		$_SESSION['username']=$_POST['username'];
		header("Location: home_page.php");
	}
}
$db=retrieveTrainingDb();
//$db=new mysqli("localhost","root","","training");
$sql="select * from login";
$rs=$db->query($sql);
$nm=$rs->num_rows;

?>
	<title>Training Database System</title>
	<body style="background-image:url('body background.jpg');background-color:#66ceae;">

	<table width=100%>
	<tr>
	<td style='background-color:#ed5214' align=left width=30%><img src="images/support_staff.png" style="width:100%; height:400;" />
	</td>
	<td style='background-color:#ed5214' align=left width=70%><img src="images/4train.jpg" style="width:100%; height:400;" />
	</td>
	</tr>
	</table>
<link rel="stylesheet" type="text/css" href="helpdesk_staff2.css" />

<form action="index.php" method=post >
<table align=center width="25%"  bgcolor="#FFFFFF"  bordercolor="#CCCCCC" style="border: 1px solid gray;">
<tr>
<td align=center colspan=2 style="vertical-align:center;background-color:#ed5214;color:white;">
Log-In
</td>
</tr>
<tr>
<td style='border: 1px solid gray;	background-color: #00cc66;'>Enter Username</td>
<td style='border: 1px solid gray;	background-color: white;'>
<select name='username'>
<?php
for($i=0;$i<$nm;$i++){
$row=$rs->fetch_assoc();

?>
	<option value="<?php echo $row['username']; ?>" ><?php echo strtoupper($row['lastName']).", ".$row['firstName']; ?></option>

<?php

}
?>
</select>
</td>
</tr>
<tr>
<td style='border: 1px solid gray;	background-color: #00cc66;'>Enter Password</td>
<td style='border: 1px solid gray;	background-color: white;'>
<input type=password name='password' />
</td>
</tr>
<tr>
<td align=center colspan=2 style="vertical-align:center;background-color:#ed5214;color:white;">
<input type=submit value='Login' />
</td>
</tr>
</table>
</form>
</body>