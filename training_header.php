	<table width=100%>
	<tr>
	<td style='background-color:#ed5214' align=left width=30%><img src="images/support_staff.png" style="width:100%; height:400;" />
	</td>
	<td style='background-color:#ed5214' align=left width=70%><img src="images/4train.jpg" style="width:100%; height:400;" />
	</td>
	
	</tr>
	</table>
	<?php
	$db=new mysqli("localhost","root","","training");
	$sql="select * from login where username='".$_SESSION['username']."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	
	?>
	
	
	<div align="right" width=100%><b>Welcome! <?php echo $row['firstName']." ".$row['lastName']; ?> </b><a style='color:red;text-decoration:none;	font: bold 14px "Trebuchet MS", Arial, sans-serif;' href="logout.php">Log Out</a></div>
