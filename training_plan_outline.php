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
$_SESSION['training_page']="training_plan_outline.php";
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
	if($_GET['a']=="printout"){
		require("training_plan.php");
	}
	else if($_GET['a']=="objective"){
		require("database/modify_objective.php");
	}
	else if($_GET['a']=="materials"){
		require("database/modify_materials.php");
	}
	else if($_GET['a']=="schedule"){
		require("database/modify_plan_sked.php");
	}	
	else if($_GET['a']=="outline"){
		require("database/modify_plan_outline.php");
	}		
?>

	</td>
</tr>
<table>	