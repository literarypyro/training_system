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
$_SESSION['training_page']="certification_template.php";
?>
<?php
if(isset($_POST['content_type'])){
	if($_POST['content_type']==""){
	}
	else {
		//$db=new mysqli("localhost","root","","training");
		$db=retrieveTrainingDb();
		
		
		$sql="select * from template where document_type='certification' and content_type='".$_POST['content_type']."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		

		if($_FILES['path']['name']==""){
			$target_path="";
		}
		else {
			if(file_exists("images/".basename($_FILES['path']['name']))){
				$target_path="images/".basename($_FILES['path']['name']);
				
			}
			else {
				$target_path = "images/";
				$target_path = $target_path.basename($_FILES['path']['name']); 
				move_uploaded_file($_FILES['path']['tmp_name'], $target_path);
			}
		}
		if(($_POST['content_type']=="footer_position1")||($_POST['content_type']=="footer_position2")){
			$content_order="2";
		
		}
		else if(($_POST['content_type']=="footer_location1")||($_POST['content_type']=="footer_location2")){
			$content_order="3";
		
		}		
		else {
			$content_order="1";
		
		}
		
		
		if($nm>0){
			$row=$rs->fetch_assoc();
			if($target_path==""){
				$target_path=$row['content_path'];
			}
			$sqlUpdate="update template set content='".$_POST['content']."',content_path='".$target_path."',content_order='".$content_order."' where document_type='certification' and content_type='".$_POST['content_type']."'";
			$rsUpdate=$db->query($sqlUpdate);		
		}
		else {
			$sqlUpdate="insert into template(content,content_path,content_type,document_type,content_order) values ('".$_POST['content']."','".$target_path."','".$_POST['content_type']."','certification',content_order='".$content_order."')";
			$rsUpdate=$db->query($sqlUpdate);		
		
		}
		
			echo "<script language='javascript'>";
			echo "window.open('generate_template.php?program_id=SAMPLE');";
			echo "</script>";
			
			$message="<font style='background-color:red;color:white;'>Template has been updated.</font>";			
			$message.="<br><br><font style='background-color:red;color:white;'>Click <a style='text-decoration:none' href='manual/training_program_SAMPLE.docx'>here</a> for the sample</font><br>";	
	}
}
else {
			echo "<script language='javascript'>";
			echo "window.open('generate_template.php?program_id=SAMPLE');";
			echo "</script>";
			
			$message="";			
			$message.="<font style='background-color:red;color:white;'>Click <a style='text-decoration:none' href='manual/training_program_SAMPLE.docx'>here</a> for Existing Template</font><br>";	

}
?>
<?php
echo "<script language='javascript'>";
echo "var template=new Array();";
//$db=new mysqli("localhost","root","","training");
$db=retrieveTrainingDb();
$sql="select * from template where document_type='certification'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	echo "template['".$row['content_type']."']=new Array();";
	echo "template['".$row['content_type']."']['content']='".$row['content']."';";
	echo "template['".$row['content_type']."']['path']='".$row['content_path']."';";

	
}
echo "</script>";
?>
<script language='javascript'>
function fillContent(contentType){
	document.getElementById('content').value="";

	document.getElementById('path').value="";
	if(template[contentType]['content']==""){
	}
	else {
		document.getElementById('content').value=template[contentType]['content'];
		document.getElementById('path').value=template[contentType]['path'];
	}

}


</script>
<link rel="stylesheet" type="text/css" href="helpdesk_staff2.css" />
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


	<table id='indexhere' align=center style='border: 1px solid gray' width=600>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Certification Template</h1>
	</th>
	</table>
	<br>
	<?php echo $message; ?>	
	<form enctype="multipart/form-data" action="certification_template.php" method="post">
	<input type="hidden" name="MAX_FILE_SIZE" value="100000" />			
	<table>
		<tr>
		<td style="background-color: #00cc66;color: black;" align=right>Content Type</td>
		<td style="background-color:white; color:black;">
			<select name='content_type' id='content_type' onchange='fillContent(this.value)'>
			<option></option>
			<option value='footer_name1'>Left Footer Name</option> 
			<option value='footer_position1'>Left Footer Position</option> 
			<option value='footer_location1'>Left Footer Location</option> 
			</select>
		</td>
		</tr>	
		<tr>
		<td style="background-color: #00cc66;color: black;" align=right>Text<br>Content</td>
		<td style="background-color:white; color:black;"><textarea name='content' id='content'></textarea></td>
		</tr>			
		<tr>
		<td colspan=2>(For images, text content gives name to picture,<br> but not caption)</td>
		</tr>
		<tr>
		<td style="background-color: #00cc66;color: black;" align=right>Content<br>Path</td>
		<td style="background-color:white; color:black;"><input type=file name='path' id='path'></input></td>
		</tr>			
		<tr>
		<td colspan=2>(For text content, leave Content Path blank)</td>
		</tr>
		<tr>
		<td align=center colspan=2><input type=submit value='Submit' /></td>
		</tr>		
	</table>
	</form>
	
	
	</td>
</tr>
<table>	
