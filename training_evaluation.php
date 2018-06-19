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
	if($_GET['a']=="entry"){
		$_SESSION['training_page']="evaluation_form.php";
	}	
	else if($_GET['a']=="eval"){
		$_SESSION['training_page']="evaluation_form.php";
	
	}
	
	else if($_GET['a']=="stdt"){
		$_SESSION['training_page']="evaluation_form.php";
	
	}
	else if($_GET['a']=="prt"){
		$_SESSION['training_page']="evaluation_form.php";
	
	}
	else if($_GET['a']=="report"){
		$_SESSION['training_page']="evaluation_form.php";
	
	}	
	else if($_GET['a']=='prt2'){
		$_SESSION['training_page']="evaluation_form.php";
	
	
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
<title>Training Evaluation System</title>

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
	if($_GET['a']=='eval'){

		require("database/evaluation_form.php");

	}
	else if($_GET['a']=='entry'){
		require("database/evaluation_form2.php");
	
	
	}
	else if($_GET['a']=='stdt'){
		require("database/evaluation_form3.php");
	
	
	}


	else if($_GET['a']=='prt'){
		require("database/generate_grades.php");
	
	
	}
	else if($_GET['a']=='prt2'){
		require("database/generate_eval_report.php");
	
	
	}	
	
	
	else if($_GET['a']=='report'){
		require("database/evaluation_form4.php");
	
	
	}	
	
	
?>

	</td>
</tr>
<table>	