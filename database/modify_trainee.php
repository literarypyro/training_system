<?php

//$db=new mysqli("localhost","root","","training");
$db=retrieveTrainingDb();
$sql="select * from trainee_list";
$rs=$db->query($sql);

$shuttle=$rs;
$nm=$shuttle->num_rows;
for($i=0;$i<$nm;$i++){
	$dgrid[$i*1+1]=$rs->fetch_assoc();


}
$shuttleNum="1";
if($shuttleNum==0){
	$shuttleNum="";
}


$newgrid=count($dgrid)*1+1;
?>
<?php
echo "<script language='javascript'>";
echo "var row=new Array();";
echo "var pageindex=1;";
echo "var limit=".$newgrid."*1-1;";
echo "var count=1;";
for($i=1;$i<$newgrid;$i++){
	echo "row[count]=new Array();";
	echo "row[count]['firstName']='".$dgrid[$i]['firstName']."';";
	echo "row[count]['lastName']='".$dgrid[$i]['lastName']."';";
	echo "row[count]['midInitial']='".$dgrid[$i]['midInitial']."';";

	echo "row[count]['designation']='".$dgrid[$i]['designation']."';";
	echo "row[count]['department']='".$dgrid[$i]['department']."';";

	echo "row[count]['id']='".$dgrid[$i]['id']."';";
	echo "count++;";

}


echo "</script>";
?>
<script language='javascript'>


function processTrainee(index,direction){
	if(direction=="next"){
		index++;
	}
	else {
		index--;
	}
	var xmlHttp;
	
	var traineeHTML="";
	


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
			traineeHTML=xmlHttp.responseText;
			
			if(traineeHTML=="None available"){
				alert("You have reached the end of the record.");
			}
			else {
				var traineeTerms=traineeHTML.split(";");


	
				document.getElementById('first_name').value="";
				document.getElementById('last_name').value="";
				document.getElementById('position').value="";
				document.getElementById('midInitial').value="";
				document.getElementById('divAssigned').value="";

				document.getElementById('user_name').value="";
	
				
				document.getElementById('first_name').value=traineeTerms[1];
				document.getElementById('last_name').value=traineeTerms[2];
				document.getElementById('position').value=traineeTerms[4];
				document.getElementById('midInitial').value=traineeTerms[3];
				document.getElementById('divAssigned').value=traineeTerms[5];

				document.getElementById('user_name').value=traineeTerms[0];
				
				document.getElementById('pageNumber').value=index;
				
			}

		}
	} 
	
	var idx=index;
	if(direction=="next"){
		idx=index-1;
	
	}
	
	
	xmlHttp.open("GET","training_processing.php?indexTrainee="+idx,true);
	xmlHttp.send();	


}


function retrieveTrainee(id,type){

	var xmlHttp;
	
	var traineeHTML="";

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
			traineeHTML=xmlHttp.responseText;
			
			if(traineeHTML=="None available"){
				alert("You have reached the end of the record.");
			}
			else {
				var traineeTerms=traineeHTML.split(";");


	
				document.getElementById('first_name').value="";
				document.getElementById('last_name').value="";
				document.getElementById('position').value="";
				document.getElementById('midInitial').value="";
				document.getElementById('divAssigned').value="";

				document.getElementById('user_name').value="";
	
				
				document.getElementById('first_name').value=traineeTerms[1];
				document.getElementById('last_name').value=traineeTerms[2];
				document.getElementById('position').value=traineeTerms[4];
				document.getElementById('midInitial').value=traineeTerms[3];
				document.getElementById('divAssigned').value=traineeTerms[5];

				document.getElementById('user_name').value=traineeTerms[0];
				document.getElementById('searchResults2').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";
				
				document.getElementById('pageNumber').value=0;
				
			}

		}
	} 
	

	xmlHttp.open("GET","training_processing.php?getTrainee="+id,true);
	xmlHttp.send();	


}
function processSearch(fName,lName){
	var xmlHttp;
	
	var traineeHTML="";
	


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
			traineeHTML=xmlHttp.responseText;
			
			if(traineeHTML=="None available"){
				document.getElementById('searchResults2').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";

			}
			else {
				var traineeTerms=traineeHTML.split("==>");
				var count=(traineeTerms.length)*1-1;

				var optionsGrid="";
				optionsGrid+="<td style='background-color: #00cc66;color: white;'>Look for Name Here:</td>";
				optionsGrid+="<td style='background-color: white;color:black;'>";
				optionsGrid+="<select multiple=true id='dynamicSel' name='dynamicSel'>";
				for(var n=0;n<count;n++){
					var parts=traineeTerms[n].split(";");

					optionsGrid+="<option value='"+parts[0]+"' >";
					optionsGrid+=parts[2]+", "+parts[1];
					optionsGrid+="</option>";
					
				}
					

				optionsGrid+="</select>";
				//optionsGrid+="<br><input type=button value='Get Participant' onclick=retrieveEvent(document.getElementById('dynamicSel').value,'student') />";
				optionsGrid+="<br><input type=button value='Get Participant' onclick=retrieveTrainee(document.getElementById('dynamicSel').value,'student') />";

				optionsGrid+="</td>";
					
				document.getElementById('searchResults2').innerHTML=optionsGrid;

				
			}

		}
	} 
	
	//alert("training_processing.php?searchTrainee=Y&searchFName="+fName+"&searchLName="+lName);
	xmlHttp.open("GET","training_processing.php?searchTrainee=Y&searchFName="+fName+"&searchLName="+lName,true);
	xmlHttp.send();	


}



var searchTraineeGrid=new Array();
var searchTraineeCount=1;
function iterate(pgindex,action){
	if(action=="prev"){
		if((pgindex*1-1)<0){
			alert("You have reached the end of the record");
		}
		else {
			if(pgindex=="1"){
				alert("You have reached the end of the record");
			}
			else {
				document.getElementById('pageNumber').value=pgindex*1-1;

				document.getElementById('first_name').value="";
				document.getElementById('last_name').value="";
				document.getElementById('position').value="";
				document.getElementById('midInitial').value="";
				document.getElementById('divAssigned').value="";

				document.getElementById('user_name').value="";
				
				document.getElementById('first_name').value=row[pgindex*1-1]['firstName'];
				document.getElementById('last_name').value=row[pgindex*1-1]['lastName'];
				document.getElementById('midInitial').value=row[pgindex*1-1]['midInitial'];
				document.getElementById('position').value=row[pgindex*1-1]['designation'];
				document.getElementById('divAssigned').value=row[pgindex*1-1]['department'];

				document.getElementById('user_name').value=row[pgindex*1-1]['id'];
			}
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
			document.getElementById('midInitial').value="";
			document.getElementById('position').value="";
			document.getElementById('divAssigned').value="";

			document.getElementById('user_name').value="";

			document.getElementById('first_name').value=row[pgindex*1+1]['firstName'];
			document.getElementById('last_name').value=row[pgindex*1+1]['lastName'];
			document.getElementById('position').value=row[pgindex*1+1]['designation'];
			document.getElementById('midInitial').value=row[pgindex*1+1]['midInitial'];
			document.getElementById('divAssigned').value=row[pgindex*1+1]['department'];

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
		
		document.getElementById('pageNumber').value=pgindex;

		document.getElementById('first_name').value="";
		document.getElementById('last_name').value="";
		document.getElementById('position').value="";
		document.getElementById('divAssigned').value="";
		document.getElementById('midInitial').value="";
		document.getElementById('user_name').value="";

		document.getElementById('first_name').value=row[pgindex]['firstName'];
		document.getElementById('last_name').value=row[pgindex]['lastName'];
		document.getElementById('midInitial').value=row[pgindex]['midInitial'];		
		document.getElementById('position').value=row[pgindex]['designation'];
		document.getElementById('divAssigned').value=row[pgindex]['department'];

		document.getElementById('user_name').value=row[pgindex]['id'];
	
	
	}

}

function retrieveEvent(item,type){
	if(type=="student"){
		iterate(item*1,"index");
		document.getElementById('searchResults2').innerHTML='<td style="background-color:white; color:black;"></td><td style="background-color:white; color:black;"></td>';
	
	}
}

function searchName(fName,lName){
	document.getElementById('pageNumber').value="";
	var surnameSearch="";
	var initialSearch="";

	surnameSearch=lName;
	initialSearch=fName;
	if(surnameSearch==""){
		surnameSearch="xxxxxxxxxxxxxx";
	}
	if(initialSearch==""){
		initialSearch="xxxxxxxxxxxxxx";
	}		
		

	searchTraineeGrid.length=0;	
	for(i=1;i<(count);i++){

		if((row[i]['lastName'].toLowerCase()==surnameSearch.toLowerCase())&&((row[i]['firstName'].toLowerCase())==initialSearch.toLowerCase())){
			
			searchTraineeGrid[searchTraineeCount]=new Array();
			searchTraineeGrid[searchTraineeCount]['index']=i;
			searchTraineeGrid[searchTraineeCount]['firstName']=row[i]['firstName'];
			searchTraineeGrid[searchTraineeCount]['lastName']=row[i]['lastName'];
			searchTraineeGrid[searchTraineeCount]['designation']=row[i]['designation'];
			searchTraineeGrid[searchTraineeCount]['department']=row[i]['department'];	
			searchTraineeCount++;
			
		}
	
		else if((row[i]['lastName'].toLowerCase()).indexOf(surnameSearch.toLowerCase())>-1){
			searchTraineeGrid[searchTraineeCount]=new Array();
			searchTraineeGrid[searchTraineeCount]['index']=i;
			searchTraineeGrid[searchTraineeCount]['firstName']=row[i]['firstName'];
			searchTraineeGrid[searchTraineeCount]['lastName']=row[i]['lastName'];
			searchTraineeGrid[searchTraineeCount]['designation']=row[i]['designation'];
			searchTraineeGrid[searchTraineeCount]['department']=row[i]['department'];
		
			searchTraineeCount++;

		}
		else if((row[i]['firstName'].toLowerCase()).indexOf(initialSearch.toLowerCase())>-1){
			searchTraineeGrid[searchTraineeCount]=new Array();
			searchTraineeGrid[searchTraineeCount]['index']=i;
			searchTraineeGrid[searchTraineeCount]['firstName']=row[i]['firstName'];
			searchTraineeGrid[searchTraineeCount]['lastName']=row[i]['lastName'];
			searchTraineeGrid[searchTraineeCount]['designation']=row[i]['designation'];
			searchTraineeGrid[searchTraineeCount]['department']=row[i]['department'];
	
			searchTraineeCount++;
		}
		else {
		}

	}
	
	if(searchTraineeCount>1){
		var optionsGrid="";
		optionsGrid+="<td style='background-color: #00cc66;color: white;'>Look for Name Here:</td>";
		optionsGrid+="<td style='background-color: white;color:black;'>";
		optionsGrid+="<select multiple=true id='dynamicSel' name='dynamicSel'>";
		for(i=1;i<searchTraineeCount;i++){
			optionsGrid+="<option value='"+searchTraineeGrid[i]['index']+"' >";
			optionsGrid+=searchTraineeGrid[i]['lastName']+", "+searchTraineeGrid[i]['firstName'];
			optionsGrid+="</option>";
			
		}
			

		optionsGrid+="</select>";
		optionsGrid+="<br><input type=button value='Get Participant' onclick=retrieveEvent(document.getElementById('dynamicSel').value,'student') />";
		optionsGrid+="</td>";
			
		document.getElementById('searchResults2').innerHTML=optionsGrid;
	}
	else {
		document.getElementById('searchResults2').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";
	}

	
	searchTraineeCount=1;
	
}
</script>

</script>

<form action="training_database.php?pp=m1&action=edit" method="post">
	<table id='mod' align=center>
		<tr>
			<th style="background-color:#ed5214;color:white;" colspan=2>Change Participant Data</th>
		</tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">
			First Name
			</td>
			<td  style="background-color: white; color:black;">
<!--				<input type=text id='first_name' name='first_name' value="<?php// echo $dgrid[1]["firstName"]; ?>" size=30 onkeyup="searchName(document.getElementById('first_name').value,document.getElementById('last_name').value);" /> -->

				<input type=text id='first_name' name='first_name' value="<?php echo $dgrid[1]["firstName"]; ?>" size=30 onkeyup="processSearch(document.getElementById('first_name').value,document.getElementById('last_name').value);" /> 

				<input type=button id='searchText' value='?' onclick="searchName(document.getElementById('first_name').value,document.getElementById('last_name').value);" />

			</td>
		</tr>	
		<tr>
			<td style="background-color: #00cc66;color: black;">
			Last Name
			</td>
			<td  style="background-color: white; color:black;">
				<input type=text id='last_name' name='last_name' value="<?php echo $dgrid[1]["lastName"]; ?>" size=30 onkeyup="searchName(document.getElementById('first_name').value,document.getElementById('last_name').value);" />
				<input type=button id='searchText' value='?' onclick="searchName(document.getElementById('first_name').value,document.getElementById('last_name').value);" />

			</td>
		</tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">
			Middle Name
			</td>
			<td  style="background-color: white; color:black;">
				<input type=text id='midInitial' name='midInitial' value="<?php echo $dgrid[1]["midInitial"]; ?>" size=30  />
			<!--
				<input type=text id='midInitial' name='midInitial' value="<?php // echo $dgrid[1]["midInitial"]; ?>" size=30 onkeyup="searchName(document.getElementById('first_name').value,document.getElementById('last_name').value);" />

				<input type=button id='searchText' value='?' onclick="searchName(document.getElementById('first_name').value,document.getElementById('last_name').value);" />
				-->
			</td>
		</tr>		
		<tr>
			<td style="background-color: #00cc66;color: black;">Designation</td>
			<td  style="background-color: white; color:black;"><input type='text' id='position' name='position' value="<?php echo $dgrid[1]["designation"]; ?>" size=30 /></td>
		</tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">Division Assigned<br>(Optional)</td>
			<td style="background-color: white; color:black;">
			<select name='divAssigned' id='divAssigned'>
				<option></option>
				<?php
				$db=retrieveTrainingDb();
				//$db=new mysqli("localhost","root","","training");
				$sql="select * from division";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();
				?>
					<option value='<?php echo $row['division_code']; ?>'><?php echo $row['division_name']; ?></option>
				<?php	
				}
				?>	
			</select>
			
			
			
			
			</td>
		</tr>
		<tr id='searchResults2' name='searchResults2' >
			<td style="background-color:white; color:black;"></td>
			<td style="background-color:white; color:black;"></td>
		</tr>			
		<tr>
			<td colspan=2 style='background-color: #ed5214;'>
			<a href='#mod' style='color:white;text-decoration: none;'  onclick="processTrainee(document.getElementById('pageNumber').value,'prev');"><<</a>

<!--			<a href='#' style='color:white;text-decoration: none;' onclick="iterate(document.getElementById('pageNumber').value,'prev');"><<</a>-->
			<input id='pageNumber' style="text-align:center" type="text" name="pageNumber" size=4 value='<?php echo $shuttleNum; ?>' onkeyup="iterate(document.getElementById('pageNumber').value,'index');" />	
		<!--	<a href='#' style='color:white;text-decoration: none;'  onclick="iterate(document.getElementById('pageNumber').value,'next');">>></a>-->
			<a href='#mod' style='color:white;text-decoration: none;'  onclick="processTrainee(document.getElementById('pageNumber').value,'next');">>></a>

		</td>
	
		</tr>
		<tr>
			<td colspan=2 align=center><input type=submit value='Edit' /><input type=hidden name='user_name' id='user_name' value="<?php echo $dgrid[1]['id']; ?>" /></td>
		</tr>
		</table>
</form>