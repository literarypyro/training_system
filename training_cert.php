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
$_SESSION['training_page']="training_cert.php";
	if($_GET['a']=="srch"){
		$_SESSION['training_page']="search_schedule.php";
	}	
	else if($_GET['a']=="srch2"){
		$_SESSION['training_page']="search_schedule2.php";
	}	
if(isset($_GET['action'])){
	//$db=new mysqli("localhost","root","","training");
	$db=retrieveTrainingDb();	
	
	
	if($_GET['action']=="add"){
		if($_GET['a']=="dss"){
			$year=$_POST['issuanceYear'];
			$month=$_POST['issuanceMonth'];
			$day=$_POST['issuanceDay'];
			$postDate=$year."-".$month."-".$day;


			$training_program_id=$_POST['training_id'];
			$trainee_id=$_POST['trainee_id'];
			$release_date=$postDate;
			$status=$_POST['issuanceType'];

			$hour=date("H");
			$minute=date("i");
			$second=date("s");
			
			
			$timestamp=date("Y-m-d H:i:s",strtotime($release_date." ".$hour.":".$minute.":".$second));			
 			$sql="insert into diploma_release(trainee_id,training_program_id,status,release_date,type) values ('".$trainee_id."','".$training_program_id."','".$status."','".$timestamp."','diploma')";
			
			$rs=$db->query($sql);
			$diploma_id=$db->insert_id;


			$sql="update class_list set diploma_release='Claimed' where trainee_id='".$trainee_id."' and training_events_id='".$training_program_id."' and diploma_release='Unclaimed'";
			$rs=$db->query($sql);	
			

			$timestamp=date("Y-m-d His");
			echo "<script language='javascript'>";
			echo "window.open('print_outline2.php?diplomaId=".$diploma_id."&traineeId=".$trainee_id."&timestamp=".$timestamp."');";
//			echo 'window.open("print_outline2.php?eventId="'.$training_program_id.'"&traineeId="'.$trainee_id.');';
			echo "</script>";

			
			$message="<font style='background-color:red;color:white;'>The entry has been added to the database.</font>";			
			$message.="<br><br><font style='background-color:red;color:white;'>Click <a style='text-decoration:none' href='printout/Diploma ".$timestamp.".pptx'>here</a> for the diploma</font><br>";	
		}
		else if($_GET['a']=="css"){
			$year=$_POST['issuanceYear'];
			$month=$_POST['issuanceMonth'];
			$day=$_POST['issuanceDay'];
			$postDate=$year."-".$month."-".$day;


			$training_program_id=$_POST['training_id'];
			$trainee_id=$_POST['trainee_id'];
			$release_date=$postDate;
			$status=$_POST['issuanceType'];
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
		
		
		else if($_GET['a']=="cr"){
			$year=$_POST['issuanceYear'];
			$month=$_POST['issuanceMonth'];
			$day=$_POST['issuanceDay'];
			$postDate=$year."-".$month."-".$day;

			$request_date=$postDate;
			$training_program_id=$_POST['training_id'];
			$trainee_id=$_POST['trainee_id'];
			$request_date=$postDate;
			$type=$_POST['issuanceType'];
			$remarks=$_POST['remarks'];
		
		
			$sql="insert into certificate_request(training_program_id,trainee_id,request_date,type,remarks) values ('".$training_program_id."','".$trainee_id."','".$request_date."','".$type."','".$remarks."')";
			$rs=$db->query($sql);
			$diploma_id=$db->insert_id;			
			
			$timestamp=date("Y-m-d His");
			echo "<script language='javascript'>";
			echo "window.open('print_outline4.php?requestId=".$diploma_id."&traineeId=".$trainee_id."&timestamp=".$timestamp."');";
//			echo 'window.open("print_outline2.php?eventId="'.$training_program_id.'"&traineeId="'.$trainee_id.');';
			echo "</script>";
			
			$message="<font style='background-color:red;color:white;'>The entry has been added to the database.</font>";			
			$message.="<br><br><font style='background-color:red;color:white;'>Click <a style='text-decoration:none' href='printout/Request ".$timestamp.".docx'>here</a> for the Request Form</font><br>";	
			
		}
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
<title>Training Database System</title>

	<body style="background-image:url('body background.jpg');">

	<?php
	require("training_header.php");
	?>
<?php echo $caption; ?>
<table width="100%"  bgcolor="#FFFFFF" cellpadding="5px" bordercolor="#CCCCCC" style="border-left-width: 1px; border-right-width: 1px; border-bottom-width: 1px">
<tr>
	<th style='border: 1px solid gray;	background-color: #00cc66;' colspan=2 align=right><marquee style='font: bold 18px "Trebuchet MS", Arial, sans-serif;color:black;'>Support Staff/AFC Center/Computer Section</marquee></th>
</tr>

<tr>
	<?php 
	require("helpdesk_sidebar.php");
	//background-color:#66ceae; 
	?>
	<td width="85%" rowspan=2 valign="top"  style="background-color:#66ceae; border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color:black;" bordercolor="#FF6600">

	
<?php

	echo $message;
	if($_GET['a']=='dss'){


		require("database/diploma_release.php");
	}
	else if($_GET['a']=='cr'){
		require("database/certificate_request.php");

		}
	else if($_GET['a']=="css"){


		require("database/certification_release.php");
	}	
			
	else if($_GET['a']=="srch"){
		require("database/search_schedule.php");
	}	
	else if($_GET['a']=="srch2"){
		require("database/search_schedule2.php");
	}	
	
	
?>

	</td>
</tr>
<table>	