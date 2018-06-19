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
$_SESSION['training_page']="training_database.php";
?>
<?php
if(isset($_GET['action'])){
	//$db=new mysqli("localhost","root","","training");
	$db=retrieveTrainingDb();
	if($_GET['action']=="edit"){
		if($_GET['pp']=="mPR"){
			$sql="update training_programs set training_title='".$_POST['finalName']."',division_code='".$_POST['finalDivision']."' where id='".$_POST['finalId']."'";
			$rs=$db->query($sql);
			$program_id=$_POST['finalId'];
			$sql="delete from training_coverage where training_program='".$_POST['finalId']."'";
			$rs=$db->query($sql);
			$coverCounter=0;			
			
			$categories=explode("&&",$_POST['finalCategory']);
			for($i=0;$i<count($categories)*1-1;$i++){
				$coverageElements=explode("=",$categories[$i]);
				if($coverageElements[0]=="ADD"){
					$sql="insert into coverage(coverage_description) values (\"".strtolower($coverageElements[1])."\")";					
					$rs=$db->query($sql);
					$inclusiveCoverage[$coverCounter]=$db->insert_id;
					$coverCounter++;
				}
				else {
					$inclusiveCoverage[$coverCounter]=$coverageElements[0];	
					$coverCounter++;
				}			
				
			
			}	
			for($i=0;$i<$coverCounter;$i++){
				$sql="insert into training_coverage(training_program,coverage_id) values ('".$program_id."','".$inclusiveCoverage[$i]."')";
				$rs=$db->query($sql);
				
			}
			
			if(file_exists("manual/training_program_".$program_id.".docx")){ 
				unlink("manual/training_program_".$program_id.".docx");
			}

			echo "<script language='javascript'>";
			echo "window.open('generate_template.php?program_id=".$program_id."');";
//			echo 'window.open("print_outline2.php?eventId="'.$training_program_id.'"&traineeId="'.$trainee_id.');';
			echo "</script>";
					
			
			$message="<font style='background-color:red;color:white;'>The entry has been added to the database.</font>";
			$message.="<br><br>";
			$message.="<font style='background-color:red;color:white;'>Certification Template has been updated.</font>";
			$message.="<br>";
			$message.="<font style='background-color:red;color:white;'>You can check the template <a style='text-decoration:none' href='manual/training_program_".$program_id.".docx'>here</a></font>";
			$message.="<br>";
		}
		else if($_GET['pp']=="m1"){
			$sql="update trainee_list set firstName='".$_POST['first_name']."',lastName='".$_POST['last_name']."',midInitial='".$_POST['midInitial']."',designation='".$_POST['position']."',department='".$_POST['divAssigned']."' where id='".$_POST['user_name']."'";
			$rs=$db->query($sql);
			$message="<font style='background-color:red;color:white;'>The Database Record has been updated.</font>";
		}
		else if($_GET['pp']=="m2"){
			$sql="update trainer_list set firstName='".$_POST['first_name']."',lastName='".$_POST['last_name']."',position='".$_POST['position']."' where id='".$_POST['user_name']."'";
			$rs=$db->query($sql);
			$message="<font style='background-color:red;color:white;'>The Database Record has been updated.</font>";
		}
		else if($_GET['pp']=="m3"){
			$sql="update login set firstName='".$_POST['first_name']."',lastName='".$_POST['last_name']."',password='".$_POST['password']."',username='".$_POST['username']."' where username='".$_POST['user_name']."'";
			$rs=$db->query($sql);
			$message="<font style='background-color:red;color:white;'>The Database Record has been updated.</font>";
		}
		
		
	}
	else if($_GET['action']=="add"){
		if($_GET['pp']=="PR"){
			$sql="insert into training_programs(training_title,division_code,alt_office) values (\"".$_POST['finalName']."\",'".$_POST['finalDivision']."','".$_POST['finalOther']."')";
			$rs=$db->query($sql);
			$program_id=$db->insert_id;
			
			$coverCounter=0;					

			$categories=explode("&&",$_POST['finalCategory']);
			for($i=0;$i<count($categories)*1-1;$i++){
				$coverageElements=explode("=",$categories[$i]);

				if($coverageElements[0]=="ADD"){
					$sql="select * from coverage where coverage_description=\"".strtolower($coverageElements[1])."\"";					
					$rs=$db->query($sql);
					$nm=$rs->num_rows;
					
					if($nm>0){
						$row=$rs->fetch_assoc();
						$inclusiveCoverage[$coverCounter]=$row['id'];
						$coverCounter++;
					}
					else {
						$sql="insert into coverage(coverage_description) values (\"".strtolower($coverageElements[1])."\")";					
						$rs=$db->query($sql);
						$inclusiveCoverage[$coverCounter]=$db->insert_id;
						$coverCounter++;
					}
				}
				else {
					$inclusiveCoverage[$coverCounter]=$coverageElements[0];
					$coverCounter++;
				}

			}


			for($i=0;$i<$coverCounter;$i++){
				$sql="insert into training_coverage(training_program,coverage_id) values ('".$program_id."','".$inclusiveCoverage[$i]."')";
		
				$rs=$db->query($sql);
				
			}


			echo "<script language='javascript'>";
			echo "window.open('generate_template.php?program_id=".$program_id."');";

//			echo 'window.open("print_outline2.php?eventId="'.$training_program_id.'"&traineeId="'.$trainee_id.');';
			echo "</script>";

			
			$message="<font style='background-color:red;color:white;'>The entry has been added to the database.</font>";
			$message.="<br><br>";
			$message.="<font style='background-color:red;color:white;'>Certification Template has been updated.</font>";
			$message.="<br>";
			$message.="<font style='background-color:red;color:white;'>You can check the template <a style='text-decoration:none' href='manual/training_program_".$program_id.".docx'>here</a></font>";
			$message.="<br>";
		}
		else if($_GET['pp']=="1"){
			$sql="insert into trainee_list(firstName,lastName,designation,department,midInitial) values ('".$_POST['first_name']."','".$_POST['last_name']."','".$_POST['position']."','".$_POST['divAssigned']."','".$_POST['midInitial']."')";
			$rs=$db->query($sql);
			$message="<font style='background-color:red;color:white;'>The entry has been added to the database.</font>";
		}
		else if($_GET['pp']=="2"){
			$sql="insert into trainer_list(firstName,lastName,position) values ('".$_POST['first_name']."','".$_POST['last_name']."','".$_POST['position']."')";
			$rs=$db->query($sql);
			$message="<font style='background-color:red;color:white;'>The entry has been added to the database.</font>";
		}
		else if($_GET['pp']=="3"){
			$sql="insert into login(firstName,lastName,password,username) values ('".$_POST['first_name']."','".$_POST['last_name']."','".$_POST['password']."','".$_POST['username']."')";
			$rs=$db->query($sql);
			$message="<font style='background-color:red;color:white;'>The entry has been added to the database.</font>";
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
	<?php echo $message; ?>
	
<?php
if($_GET['pp']=="1"){
	require("database/new_trainee.php");

}
else if($_GET['pp']=="2"){
	require("database/new_trainer.php");

}
else if($_GET['pp']=="3"){
	require("database/new_login.php");

}
else if($_GET['pp']=="PR"){
	require("database/new_program.php");

}
else if($_GET['pp']=="m1"){
	require("database/modify_trainee.php");

}
else if($_GET['pp']=="m2"){
	require("database/modify_trainer.php");

}
else if($_GET['pp']=="m3"){
	require("database/modify_login.php");

}
else if($_GET['pp']=="mPR"){
	require("database/modify_program.php");

}
else {
}
?>

	</td>
</tr>
<table>	