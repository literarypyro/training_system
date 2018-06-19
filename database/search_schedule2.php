<script language="javascript">
var diplomaRelease=new Array();
var certificateRequest=new Array();

</script>
<?php
$db=retrieveTrainingDb();	
//$db=new mysqli("localhost","root","","training");
$latestDate=date("Y-m-d",strtotime(date("Y-m-d")."-1 day"));

$currentYear=date("Y");


$_SESSION['training_page']="search_schedule2.php";

?>



<script language="javascript">
	//alert(trainingEvent[1]['trainees'][1]);


</script>

<script language="javascript">
var searchTraineeGrid=new Array();
var searchTraineeCount=1;

function selectEvent(index,direction){
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

				document.getElementById('training_title').value="";

				document.getElementById('batch').value="";

				document.getElementById('period').value="";
				selectedTrainingID="";


				document.getElementById('training_title').value=traineeTerms[1];

				document.getElementById('batch').value=traineeTerms[2];

				document.getElementById('period').value="From "+traineeTerms[3]+" to "+traineeTerms[4];
				selectedTrainingID=traineeTerms[0];
		
	
				


				document.getElementById('trainingPageNumber').value=index;				
				
				fillClass(traineeTerms[0],traineeTerms[2],"From "+traineeTerms[3]+" to "+traineeTerms[4]);
				
				
			}

		}
	} 
	
	var idx=index;
	if(direction=="next"){
		idx=index-1;
	
	}
	
	
	
	
	
	xmlHttp.open("GET","training_processing.php?indexEvent="+idx,true);
	xmlHttp.send();	



}

function fillEvent(event_id){

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
			}
			else {
			
				var traineeTerms=traineeHTML.split("==>");
				var count=traineeTerms.length*1-1;

				var newGrid="";
				newGrid+="<tr><th>Training Name</th><th>Batch Number</th><th>Period</th><th>Training Certificate</th>";
				//<th>Request for Certification</th></tr>";
				newGrid+="<tr><td colspan=5></td></tr>";				
				
				for(i=0;i<count;i++){
					var parts=traineeTerms[i].split(";");

					newGrid+="<tr>";
					newGrid+="<td>"+parts[1]+"</td>";
					newGrid+="<td>"+parts[2]+"</td>";
					newGrid+="<td>"+parts[3]+"</td>";
					newGrid+="<td>"+parts[4]+" | ";
					newGrid+="<a href='#' onclick=\"makeRequest('"+parts[0]+"','"+event_id+"')\"><span id='event_req_"+parts[0]+"'>Request</span></a></td>";
			
			
//					<a href='#' onclick='makeRequest(\'"+parts[0]+"\',\'"+event_id+"\')'></td>";
//					newGrid+="<td>&nbsp;</td>";
					
					
					newGrid+="</tr>";
				
				}
				document.getElementById('alterTable').innerHTML=newGrid;					


			}
//			alert(traineeCount);
//			traineesFill();

		}
	} 


	xmlHttp.open("GET","training_processing.php?listEvent="+event_id,true);
	xmlHttp.send();	



}
function processSearch(name){
	var xmlHttp;
	
	var traineeHTML="";
	//document.getElementById('user_name').value="";	


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
				document.getElementById('searchResults').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";

			}
			else {

				var traineeTerms=traineeHTML.split("==>");
				
				var count=(traineeTerms.length)*1-1;

				var optionsGrid="";
				optionsGrid+="<td style='background-color: white;color: black;'>Look for Name Here:</td>";
				optionsGrid+="<td style='background-color: white;color:black;'>";
				optionsGrid+="<select multiple=true id='dynamicSel' name='dynamicSel'>";
				for(var n=0;n<count;n++){
					var parts=traineeTerms[n].split(";");

					optionsGrid+="<option value='"+parts[0]+"' >";
					optionsGrid+=parts[2]+", "+parts[1];
					optionsGrid+="</option>";
					
				}
					

				optionsGrid+="</select>";
				optionsGrid+="<br><input type=button value='Get Participant' onclick=retrieveTrainee(document.getElementById('dynamicSel').value,'student') />";

				optionsGrid+="</td>";
					
				document.getElementById('searchResults').innerHTML=optionsGrid;

				
			}

		}
	} 
	
	//alert("training_processing.php?searchTrainee=Y&searchFName="+fName+"&searchLName="+lName);
	xmlHttp.open("GET","training_processing.php?searchTrainee=Y&searchFName="+name+"&searchLName="+name,true);
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

//				document.getElementById('name').value=traineeTerms[1]+" "+traineeTerms[2];
				document.getElementById('labName').value=traineeTerms[2]+", "+traineeTerms[1];

				document.getElementById('position').value=traineeTerms[4];

				document.getElementById('searchResults').innerHTML="<td style='background-color: #ed5214;'></td><td style='background-color: #ed5214;'></td>";
				
				if(traineeTerms[5]==""){	
				}else {
				document.getElementById('cert_division').value=traineeTerms[6];

				}
				document.getElementById("reportId").value=traineeTerms[0];	


				document.getElementById("cert_id").value=traineeTerms[0];
				document.getElementById("cert_name").value=traineeTerms[1]+" "+traineeTerms[2];	

				document.getElementById('reportType').value='tr';
				document.getElementById('traineePageNumber').value=0;
				fillEvent(traineeTerms[0]);
			}

		}
	} 
	

	xmlHttp.open("GET","training_processing.php?getTrainee="+id,true);
	xmlHttp.send();	


}



function selectTrainee(index,direction){
	if(direction=="next"){
		index++;
	}
	else if(direction=="index") {
		index=index*1;
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


	
				document.getElementById('name').value="";
				document.getElementById('position').value="";

				document.getElementById("reportId").value="";
	
				
//				document.getElementById('name').value=traineeTerms[2]+", "+traineeTerms[1];
				document.getElementById('labName').value=traineeTerms[2]+", "+traineeTerms[1];
				if(traineeTerms[5]==""){	
				}else {
				document.getElementById('cert_division').value=traineeTerms[6];
				}
				
				
				document.getElementById('position').value=traineeTerms[4];
				document.getElementById("reportId").value=traineeTerms[0];	
				document.getElementById('reportType').value='tr';
				document.getElementById('cert_division').value=traineeTerms[5];
			
				document.getElementById('traineePageNumber').value=index;
				fillEvent(traineeTerms[0]);
				
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


function generateReport(){
	window.open("generate_report3.php?sT="+document.getElementById('reportType').value+"&D="+document.getElementById('reportId').value);

}


function makeRequest(event_id,trainee_id){

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
				alert("Update Failed.");
			}
			else {
				document.getElementById('event_req_'+event_id).innerHTML="Requested";		
			}

		}
	} 
	
	
	xmlHttp.open("GET","training_processing.php?makeRequest="+event_id+"&tID="+trainee_id,true);
	xmlHttp.send();	





}


</script>


	<table id='indexhere' align=center style='border: 1px solid gray' width=600>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Search Participant Information</h1>
	</th>
	</table>
	

	<table id='dynamicProgram' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Search Participant</th></tr>
		<tr>
		<td>
			<?php
			
			$db=new mysqli("localhost","root","","training");
			
			$sql="select * from trainee_list  group by lastName order by lastName"; 
			$rs=$db->query($sql);
			
			$nm=$rs->num_rows;
			?>
			<select name='name' id='name' onchange="retrieveTrainee(this.value,'student')">
			<?php

			for($nn=0;$nn<$nm;$nn++){
				
				$row=$rs->fetch_assoc();
			?>
			<option value='<?php echo $row['id']; ?>'><?php echo $row['lastName'].", ".$row['firstName']; ?></option>
			<?php
			}
			?>		
			</select>
			
			

			<?php
			/**
		<td style="background-color:white; color:black;">
			Name
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='name' name='name' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 onkeyup='processSearch(this.value)' />
			<input type=button id='searchText' value='?' onclick="processSearch(document.getElementById('name').value);" />
			</td>
		</tr>	
		
			**/
			?>
			</td>
			</tr>
			<tr>
			<td style="background-color:white; color:black;">
			Name
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='labName' name='labName' />
			</td>
			</tr>
		<tr>
			<td style="background-color:white; color:black;">
			Position
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='position' name='position' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 />
			</td>
		</tr>
		<tr id='searchResults' name='searchResults' >
		<td style='background-color: #ed5214;'></td>
		<td style='background-color: #ed5214;'></td>
		</tr>
		
		<tr>
			<td align=center colspan=2 style='background-color: #ed5214;'>

			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="selectTrainee(document.getElementById('traineePageNumber').value,'prev');"><<</a>

	<!--		||

			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="iterateTrainee(document.getElementById('traineePageNumber').value,'prev');"><<</a>
-->
			<input id='traineePageNumber' style="text-align:center" type="text" name="traineePageNumber" size=4 value='' onkeyup="selectTrainee(document.getElementById('traineePageNumber').value,'index');" />	
<!--			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="iterateTrainee(document.getElementById('traineePageNumber').value,'next');">>></a>
||-->
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="selectTrainee(document.getElementById('traineePageNumber').value,'next');">>></a>

			</td>
	
		</tr>		
	</table>
<!--
	<br>
	<table>
		<tr><th style="background-color: #00cc66;color: white;"	colspan=2>Select Training Event</th></tr>
	
	
	
	
	</table>
	
	
	-->
	<br>
	<table id='alterTable' width=100%>
		<tr>
			<th>Training Course</th>
			<th>Batch Number</th>
			<th>Period</th>
			<th>Training Certificate</th>
			<!--
			<th>Request for Certification</th>
		-->
		</tr>
		<tr>
			<td colspan=5></td>
		</tr>
	</table>

	<table id='dynamicProgram2' width=100%>
	</table>
			<br>
	<div align=center><input type=button value='Generate Printout' onclick='generateReport()' /></div>

	<div align=center><input type=hidden id='reportType' /></div>
	<div align=center><input type=hidden id='reportId' /></div>	
<br>
	<table id='dynamicProgram' width=400 align=center  style='visibility:hidden;enabled:false;   border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Select Training Event</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			Training Title
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 />
			</td>
		</tr>	
		<tr>
			<td style="background-color:white; color:black;">
			Batch Number
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='batch' name='batch' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 />
			</td>
		</tr>
		
		<tr>
			<td style="background-color:white; color:black;">
			Period
			</td>
			<td style="background-color:white; color:black;">
			<textarea id='period' name='period' value="<?php echo $ndgrid[1]['training_title']; ?>" cols=30></textarea>
			</td>
		</tr>	
		<tr>
			<td colspan=2 style='background-color: #ed5214;' align=center>
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="selectEvent(document.getElementById('trainingPageNumber').value,'prev');"><<</a>
<!--||
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'prev');"><<</a>
		-->	
			<input id='trainingPageNumber' style="text-align:center" type="text" name="trainingPageNumber" size=4 value='' onkeyup="iterateTraining(document.getElementById('trainingPageNumber').value,'index');" />	
<!--			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'next');">>></a>

||-->
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="selectEvent(document.getElementById('trainingPageNumber').value,'next');">>></a>


			</td>
	
		</tr>		
	</table>
	<table id='trainerTable' align=center style="visibility:hidden;enabled:false;"  width=400>
		<tr>
			<th style="background-color: #00cc66;color: black;">
			Trainer(s)
			</th>
		</tr>
		<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>
	</table>
	
	
	
	<table id='dynamicDiplomas'   style="visibility:hidden;enabled:false;"  width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: white;"	colspan=2>Diploma Issuances</th></tr>
	</table>
	<table id='dynamicCertificates'   style="visibility:hidden;enabled:false;"  width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: white;"	colspan=2>Certification Requests</th></tr>
	</table>	
	
	<form action='print_certification.php' method='post' target='blank'>
	<table>
		<tr><th>Generate Certification</th></tr>
		<tr><td>For 
			<select name='gender'>
				<option value='mr.'>MR</option>
				<option value='ms.'>MS</option>
			</select>
			<input type='text' name='cert_name' id='cert_name' />
			<input type='hidden' name='cert_id' id='cert_id' />
			</td>
			
		</tr>
		<tr>
			<td>Of the 
				<input type='text' name='cert_division' id='cert_division' />
			</td>
		</tr>	
		<tr>
			<td>For the Purpose Of
			<textarea name='purpose'>
			</textarea>
			<br>
			<input type=checkbox  name='excludeClause'>Do not include this section</input>
		</tr>
		<tr>
			<td>Trainings Range From: 
			<select name='cert_from_mo'>
			<?php
			for($i=1;$i<=12;$i++){
			?>	
				<option value='<?php echo $i; ?>'><?php echo date("F",strtotime(date("Y")."-".$i."-01")); ?></option>
			
			<?php
			
			}
			?>
			</select>
			<input type='text' name='cert_from_yr' value='<?php echo date("Y"); ?>' />
			</td>
		</tr>
		<tr>
			<td>To:
			<select name='cert_to_mo'>
			<?php
			for($i=1;$i<=12;$i++){
			?>	
				<option value='<?php echo $i; ?>'><?php echo date("F",strtotime(date("Y")."-".$i."-01")); ?></option>
			
			<?php
			
			}
			?>
			</select>
			<input type='text' name='cert_to_yr' value='<?php echo date("Y"); ?>' />
			
			</td>
		</tr>



		<tr>
			<td><input type='submit' value='Generate Printout'></td>
		</tr>	
	</table>
	</form>
	