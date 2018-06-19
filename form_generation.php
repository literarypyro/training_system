<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
require("form functions.php");
require("db_page.php");
?>
<?php
$_SESSION['training_page']="form_generation.php";
?>
<?php
	if(isset($_POST['training_id'])){
		if(isset($_POST['trainee_id'])){
			$training_program_id=$_POST['training_id'];
			$trainee_id=$_POST['trainee_id'];
			$release_date=date("Y-m-d");
			$status="Re-Issuance";
			$db=new mysqli('localhost','root','','training');
 			$sql="insert into diploma_release(trainee_id,training_program_id,status,release_date) values ('".$trainee_id."','".$training_program_id."','".$status."','".$release_date."')";

			$rs=$db->query($sql);
			$timestamp=date("Y-m-d His");
			echo "<script language='javascript'>";
			echo "window.open('print_outline2.php?eventId=".$training_program_id."&traineeId=".$trainee_id."&timestamp=".$timestamp."');";
//			echo 'window.open("print_outline2.php?eventId="'.$training_program_id.'"&traineeId="'.$trainee_id.');';
			echo "</script>";

			
			$message="<font style='background-color:red;color:white;'>Diploma printout has been recorded.</font>";			
			$message.="<br><br><font style='background-color:red;color:white;'>Click <a style='text-decoration:none' href='printout/Diploma ".$timestamp.".pptx'>here</a> for the diploma</font><br>";	
		}
	}




?>
<link rel="stylesheet" type="text/css" href="helpdesk_staff2.css" />
<script language='javascript'>
function selectOption(elementName,elementValue){
	var elm=document.getElementById(elementName);
	for(i=0;i<elm.options.length;i++){
		if(elm.options[i].value==elementValue){
			elm.options[i].selected=true;
		}
	}

}
</script>
<title>Update Helpdesk Staff Status</title>
	<body style="background-image:url('body background.jpg');">

	<div align=center><img src="helpdesk Header.jpg" style="width:45%; height:200;" /></div>
	<div align="right" width=100%><a style='color:white;	font: bold 14px "Trebuchet MS", Arial, sans-serif;' href="logout.php">Log Out</a></div>

<?php echo $caption; ?>
<table width="100%"  bgcolor="#FFFFFF" cellpadding="5px" bordercolor="#CCCCCC" style="border-left-width: 1px; border-right-width: 1px; border-bottom-width: 1px">
<tr>
	<th style='border: 1px solid gray;	background-color: #00cc66;' colspan=2 align=right>&nbsp;</th>
</tr>

<tr>
	<?php 
	require("helpdesk_sidebar.php");
	//background-color:#66ceae; 
	?>
	<td width="85%" rowspan=2 valign="top"  style="background-color:#66ceae; border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color:black;" bordercolor="#FF6600">
	
	
<?php
	echo $message;
	require("database/printout_diploma.php");
	
?>

	</td>
</tr>
<table>	