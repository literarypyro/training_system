<?php
session_start();
?>
<link rel="stylesheet" type="text/css" href="helpdesk_staff2.css" />
<?php
require_once 'PHPWord/PHPWord.php';
?>
<?php
if(isset($_GET['generate'])){
	$_SESSION['training_page']="memo_generate.php";
}
?>
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
if(isset($_GET['generate'])){


	$generation=$_GET['generate'];
	
	if($generation=="complete_report"){
		require("generate_report_of_completed_training.php");

	}
	else if($generation=="complete_report2"){
		require("generate_report_of_completed_training2.php");

	}	
	
}

if(isset($_POST['memoType'])){
	$type=$_POST['memoType'];
	if($type=="completion"){
		require("generate_completion.php");
	
	
	}
	else if($type=="mATrainees"){
		require("generate_attend_memo.php");
	
	
	}
	else if($type=="terminal"){
		require("generate_memo_terminal.php");
	
	
	}
	else if($type=="mAbsences"){
		require("generate_non_attendance.php");
	}
}


?>
</td>
</tr>
<table>	