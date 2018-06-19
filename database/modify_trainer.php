<?php
$db=retrieveTrainingDb();
//$db=new mysqli("localhost","root","","training");
$sql="select * from trainer_list";
$rs=$db->query($sql);

$shuttle=$rs;
$nm=$shuttle->num_rows;
for($i=0;$i<$nm;$i++){
	$dgrid[$i*1+1]=$rs->fetch_assoc();


}
$newgrid=count($dgrid)*1+1;
?>
<?php
echo "<script language='javascript'>";
echo "var row=new Array();";
echo "var pageindex=1;";
echo "var limit=".$newgrid."*1-1;";
for($i=1;$i<$newgrid;$i++){
	echo "row[".$i."]=new Array();";
	echo "row[".$i."]['firstName']='".$dgrid[$i]['firstName']."';";
	echo "row[".$i."]['lastName']='".$dgrid[$i]['lastName']."';";
	echo "row[".$i."]['position']='".$dgrid[$i]['position']."';";
	echo "row[".$i."]['id']='".$dgrid[$i]['id']."';";



}


echo "</script>";
?>
<script language='javascript'>
function processTrainer(index,direction){
	if(direction=="next"){
		index++;
	}
	else {
		index--;
	}
	var xmlHttp;
	
	var trainerHTML="";
	


	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlHttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			trainerHTML=xmlHttp.responseText;
			
			if(trainerHTML=="None available"){
				alert("You have reached the end of the record.");
			}
			else {
				var trainerTerms=trainerHTML.split(";");



	
				document.getElementById('first_name').value="";
				document.getElementById('last_name').value="";
				document.getElementById('position').value="";

				document.getElementById('user_name').value="";
	
				
				document.getElementById('first_name').value=trainerTerms[1];
				document.getElementById('last_name').value=trainerTerms[2];
				document.getElementById('position').value=trainerTerms[3];

				document.getElementById('user_name').value=trainerTerms[0];
				
				document.getElementById('pageNumber').value=index;
				
			}

		}
	} 
	
	var idx=index;
	if(direction=="next"){
		idx=index-1;
	
	}
	
	
	xmlHttp.open("GET","training_processing.php?indexTrainer="+idx,true);
	xmlHttp.send();	


}

function iterate(pgindex,action){
	if(action=="prev"){
		if(pgindex=="1"){
			alert("You have reached the end of the record");
		}
		else {
		document.getElementById('pageNumber').value=pgindex*1-1;

		document.getElementById('first_name').value="";
		document.getElementById('last_name').value="";
		document.getElementById('position').value="";

		document.getElementById('user_name').value="";

		
		
		
		document.getElementById('first_name').value=row[pgindex*1-1]['firstName'];
		document.getElementById('last_name').value=row[pgindex*1-1]['lastName'];
		document.getElementById('position').value=row[pgindex*1-1]['position'];
		document.getElementById('user_name').value=row[pgindex*1-1]['id'];		

		}
	}
	else if(action=="next"){
		if(pgindex==limit){
			alert("You have reached the end of the record");
		}
		else {
	document.getElementById('pageNumber').value=pgindex*1+1;
		document.getElementById('first_name').value="";
		document.getElementById('last_name').value="";
		document.getElementById('position').value="";
		document.getElementById('user_name').value="";


		document.getElementById('first_name').value=row[pgindex*1+1]['firstName'];
		document.getElementById('last_name').value=row[pgindex*1+1]['lastName'];
		document.getElementById('position').value=row[pgindex*1+1]['position'];
		document.getElementById('user_name').value=row[pgindex*1+1]['id'];	
		}
	}
	else {
		if(pgindex>limit){
			alert("You have reached the end of the record");
			document.getElementById('pageNumber').value=limit;
			pgindex=limit;
		}
		else if(pgindex<=0){
			alert("You have reached the end of the record");
			document.getElementById('pageNumber').value=1;
			pgindex=1;
		}
		
		

		document.getElementById('first_name').value="";
		document.getElementById('last_name').value="";
		document.getElementById('position').value="";
		document.getElementById('user_name').value="";

		document.getElementById('first_name').value=row[pgindex]['firstName'];
		document.getElementById('last_name').value=row[pgindex]['lastName'];
		document.getElementById('position').value=row[pgindex]['position'];
		document.getElementById('user_name').value=row[pgindex]['id'];	
	
	
	}

}
</script>
<form action="training_database.php?pp=m2&action=edit" method="post">
	<table id='mod' align=center>
		<tr><th  style="background-color:#ed5214;color:white;" colspan=2>Edit Trainer</th></tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">
			First Name
			</td>
			<td  style="background-color: white; color:black;">
			<input type=text id='first_name' name='first_name' value="<?php echo $dgrid[1]["firstName"]; ?>" size=30 />
			</td>
		</tr>	
		<tr>
			<td style="background-color: #00cc66;color: black;">
			Last Name
			</td>
			<td  style="background-color: white; color:black;">
				<input type=text id='last_name' name='last_name' value="<?php echo $dgrid[1]["lastName"]; ?>" size=30 />
			</td>
		</tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">Position</td>
			<td  style="background-color: white; color:black;"><input type='text' id='position' name='position' value="<?php echo $dgrid[1]["position"]; ?>" size=30 /></td>
		</tr>
		<tr>
			<td colspan=2 style='background-color: #ed5214;'>
			<a href='#' style='color:white;text-decoration: none;' onclick="processTrainer(document.getElementById('pageNumber').value,'prev');"><<</a>

			<!--<a href='#' style='color:white;text-decoration: none;' onclick="iterate(document.getElementById('pageNumber').value,'prev');"><<</a>-->
			<input id='pageNumber' style="text-align:center" type="text" name="pageNumber" size=4 value='1' onkeyup="iterate(document.getElementById('pageNumber').value,'index');" />	
			<!--<a href='#' style='color:white;text-decoration: none;'  onclick="iterate(document.getElementById('pageNumber').value,'next');">>></a>-->
			<a href='#' style='color:white;text-decoration: none;'  onclick="processTrainer(document.getElementById('pageNumber').value,'next');">>></a>

			</td>
	
		</tr>
		<tr>
			<td colspan=2 align=center><input type=submit value='Edit' /><input type=hidden name='user_name' id='user_name' value="<?php echo $dgrid[1]['id']; ?>" /></td>
		</tr>


		</table>


</form>