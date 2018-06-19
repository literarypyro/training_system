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
	if($_GET['issueType']=="diploma"){
	



		$db=new mysqli("localhost","root","","training");
	
		$postDate=date("Y-m-d",strtotime($_GET['issueDate']));


		$training_program_id=$_GET['event_id'];
		$trainee_id=$_GET['trainee'];
		$release_date=$postDate;
		
		$sql="select * from diploma_release where trainee_id='".$_GET['trainee']."' and training_program_id='".$_GET['event_id']."' and type='diploma'";
		$rs=$db->query($sql);	
		$nm=$rs->num_rows;
		
		if($nm>0){
			$status="Re-Issuance";
		}
		else {
			$status="Issuance";
		
		
		}
		
		//$status=$_POST['issuanceType'];

		$hour=date("H");
		$minute=date("i");
		$second=date("s");
			
			
		$timestamp=date("Y-m-d H:i:s",strtotime($release_date." ".$hour.":".$minute.":".$second));			
//		$sql="insert into diploma_release(trainee_id,training_program_id,status,release_date,type) values ('".$trainee_id."','".$training_program_id."','".$status."','".$timestamp."','diploma')";
		$sql="insert into diploma_release(trainee_id,training_program_id,status,release_date,type) values ('".$trainee_id."','".$training_program_id."','".$status."','".$postDate."','diploma')";

			
		$rs=$db->query($sql);
		$diploma_id=$db->insert_id;


		$sql="update class_list set diploma_release='Claimed' where trainee_id='".$trainee_id."' and training_events_id='".$training_program_id."' and diploma_release='Unclaimed'";
		$rs=$db->query($sql);	
			
		$timestamp=date("Y-m-d His");
		echo "<script language='javascript'>";

//		echo "window.open('print_outline2.php?diplomaId=".$diploma_id."&traineeId=".$trainee_id."&timestamp=".$timestamp."');";
		echo "window.open('print_outline2.php?diplomaId=".$diploma_id."&traineeId=".$trainee_id."&timestamp=".$postDate."');";

//		echo 'window.open("print_outline2.php?eventId="'.$training_program_id.'"&traineeId="'.$trainee_id.');';
//		echo "alert('Diploma Generated')";

		echo "</script>";		
		
		$message="<font style='background-color:red;color:white;'>The entry has been added to the database.</font>";			
		$message.="<br><br><font style='background-color:red;color:white;'>Click <a style='text-decoration:none' href='printout/Diploma ".$timestamp.".pptx'>here</a> for the diploma</font><br>";	
		
	}
	
	else if($_GET['issueType']=="certification"){
	
		$db=new mysqli("localhost","root","","training");
	
		$postDate=$_GET['issuanceDate'];

		$training_program_id=$_GET['event_id'];
		$trainee_id=$_GET['trainee'];
		$release_date=$postDate;
	
		$sql="select * from diploma_release where trainee_id='".$_GET['trainee']."' and training_program_id='".$_GET['event_id']."' and type='diploma'";
		$rs=$db->query($sql);	
		$nm=$rs->num_rows;
		
		if($nm>0){
			$status="Re-Issuance";
		}
		else {
			$status="Issuance";
		
		
		}

		$hour=date("H");
		$minute=date("i");
		$second=date("s");
			
		$timestamp=date("Y-m-d H:i:s",strtotime($release_date." ".$hour.":".$minute.":".$second));			
		$sql="insert into diploma_release(trainee_id,training_program_id,status,release_date,type) values ('".$trainee_id."','".$training_program_id."','".$status."','".$timestamp."','certification')";
			
		$rs=$db->query($sql);
		$diploma_id=$db->insert_id;
			
			
		$sql="update certificate_request set status='Claimed', remarks=concat(remarks,', Claimed: ".$timestamp."') where trainee_id='".$trainee_id."' and training_program_id='".$training_program_id."' and status='Unclaimed'";

		$rs=$db->query($sql);			
			
		$timestamp=date("Y-m-d His");
		echo "<script language='javascript'>";
		echo "window.open('print_outline.php?diplomaId=".$diploma_id."&traineeId=".$trainee_id."&timestamp=".$timestamp."');";
//			echo 'window.open("print_outline2.php?eventId="'.$training_program_id.'"&traineeId="'.$trainee_id.');';
		echo "</script>";
			
		$message="<font style='background-color:red;color:white;'>The entry has been added to the database.</font>";			
		$message.="<br><br><font style='background-color:red;color:white;'>Click <a style='text-decoration:none' href='printout/Certification ".$timestamp.".docx'>here</a> for the Certification</font><br>";	
	
	
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
<title>Training Database System</title>

	<body style="background-image:url('body background.jpg');">

	<?php
	require("training_header.php");
	?>
<?php echo $caption; ?>
<table width="100%"  bgcolor="#FFFFFF" cellpadding="5px" bordercolor="#CCCCCC" style="border-left-width: 1px; border-right-width: 1px; border-bottom-width: 1px">
<tr>
	<th style='border: 1px solid gray;	background-color: #00cc66;' colspan=2 align=right><marquee style='font: bold 18px "Trebuchet MS", Arial, sans-serif;color:white;'>Support Staff/AFC Center/Computer Section</marquee></th>
</tr>

<tr>
	<?php 
	require("helpdesk_sidebar.php");
	//background-color:#66ceae; 
	?>
	<td width="85%" rowspan=2 valign="top"  style="background-color:#66ceae; border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color:black;" bordercolor="#FF6600">
	<?php

	?>
	
<?php

	echo $message;
	require("database/diploma_attendance.php");
	?>
	
	</td>
</tr>
<table>	