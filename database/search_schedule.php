<script language="javascript">
var diplomaRelease=new Array();
var certificateRequest=new Array();

</script>
<?php
//$db=new mysqli("localhost","root","","training");
$db=retrieveTrainingDb();	
$latestDate=date("Y-m-d",strtotime(date("Y-m-d")."-1 day"));

$_SESSION['training_page']="search_schedule.php";




?>
<script language="javascript">
	//alert(trainingEvent[1]['trainees'][1]);


</script>

<script language="javascript">

var searchEventGrid=new Array();
var searchEventCount=1;

function fillClass(event_id,batch,period){

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
				newGrid+="<tr><th>Participant</th><th>Batch #</th><th>Period</th><th>Training Certificate</th>";
				//<th>Request for Certification</th></tr>";
				newGrid+="<tr><td colspan=5></td></tr>";
				
				
				for(i=0;i<count;i++){
					var parts=traineeTerms[i].split(";");

					newGrid+="<tr>";
					newGrid+="<td>"+parts[2]+", "+parts[1]+"</td>";
					newGrid+="<td>"+batch+"</td>";
					newGrid+="<td>"+period+"</td>";
					newGrid+="<td>"+parts[3]+"</td>";
//					newGrid+="<td>&nbsp;</td>";
					
					
					newGrid+="</tr>";
				
				}
				document.getElementById('alterTable').innerHTML=newGrid;					


			}
//			alert(traineeCount);
			traineesFill();

		}
	} 

	if(batch=="all"){

		xmlHttp.open("GET","training_processing.php?listClass="+event_id+"&batch="+batch,true);

	}
	else {
		xmlHttp.open("GET","training_processing.php?listClass="+event_id,true);
	}
	xmlHttp.send();	



}


function selectEvent(index,direction){
	if(direction=="next"){
		index++;
	}
	else if(direction=="index"){
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

				document.getElementById('training_title').value="";

				document.getElementById('batch').value="";


				selectedTrainingID="";


				document.getElementById('training_title').value=traineeTerms[1];

				document.getElementById('batch').value=traineeTerms[2];

				selectedTrainingID=traineeTerms[0];
				document.getElementById('selectedProgram').value=traineeTerms[11];	
				document.getElementById('reportType').value="evt";
				document.getElementById('reportId').value=traineeTerms[0];
				fillClass(traineeTerms[0],traineeTerms[2],"From "+traineeTerms[3]+" to "+traineeTerms[4]);


				document.getElementById('trainingPageNumber').value=index;				
				
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

function processBatch(batch){
	if(batch=="all"){

		var program=document.getElementById('selectedProgram').value;
		processSearch(program,batch);
	}
}

function idEvent(id){

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
		
				document.getElementById('searchResults').innerHTML="<td style='background-color: #ed5214;'></td><td style='background-color: #ed5214;'></td>";
			}
			else {
				var traineeTerms=traineeHTML.split(";");
				
				document.getElementById('training_title').value=traineeTerms[1];

				document.getElementById('batch').value=traineeTerms[2];

				selectedTrainingID=traineeTerms[0];
		
				document.getElementById('reportType').value="evt";
				document.getElementById('reportId').value=traineeTerms[0];
				
				fillClass(traineeTerms[0],traineeTerms[2],"From "+traineeTerms[3]+" to "+traineeTerms[4]);
				
				document.getElementById('searchResults').innerHTML="<td style='background-color: #ed5214;'></td><td style='background-color: #ed5214;'></td>";
			
				document.getElementById('selectedProgram').value=traineeTerms[5];
				document.getElementById('trainingPageNumber').value=0;
				//fillEvent(traineeTerms[0]);
			}

		}
	} 

	xmlHttp.open("GET","training_processing.php?getEvent="+id,true);
	xmlHttp.send();	


}
function processSearch(training_title,batch){
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
				document.getElementById('searchResults').innerHTML="<td style='background-color: #ed5214;'></td><td style='background-color: #ed5214;'></td>";

			}
			else {

				var traineeTerms=traineeHTML.split("==>");
				
				var count=(traineeTerms.length)*1-1;
				
				
				var optionsGrid="";
				optionsGrid+="<td style='background-color: white;color: black;'>Look for Training Program Here:</td>";
				optionsGrid+="<td style='background-color: white;color:black;'>";
				optionsGrid+="<select multiple=true id='dynamicSel' name='dynamicSel'>";
				for(var n=0;n<count;n++){
					var parts=traineeTerms[n].split(";");

					optionsGrid+="<option value='"+parts[0]+"' >";
					optionsGrid+=parts[1]+" #"+parts[2]+", From "+parts[3]+" to "+parts[4];
					optionsGrid+="</option>";
					
				}
					

				optionsGrid+="</select>";
				optionsGrid+="<br><input type=button value='Get Training Program' onclick=idEvent(document.getElementById('dynamicSel').value) />";

				optionsGrid+="</td>";
					
				document.getElementById('searchResults').innerHTML=optionsGrid;

				
			}

		}
	} 

	xmlHttp.open("GET","training_processing.php?searchEvent="+training_title+"&searchBatch="+batch);
	xmlHttp.send();	


}



function retrieveEvent(item){

	iterateTraining(item*1,"index");
	document.getElementById('searchResults').innerHTML="<td style='background-color: #ed5214;'></td><td style='background-color: #ed5214;'></td>";


}

function searchBatch(key){
	var currentRecord=trainingEvent[document.getElementById('trainingPageNumber').value]['program'];
	if(key==0){
		key=1;
		document.getElementById('batch').value=key;	
	}
	var progLabr="programId_"+currentRecord;

	if(trainingProgram[progLabr][key]==null){
		if(key.toLowerCase()=="all"){
			document.getElementById('trainingPageNumber').value="";
			iterateNewDynamic(progLabr);
			document.getElementById("reportType").value="prg";	
			document.getElementById("reportId").value=currentRecord;				
			
		}
	}
	else {
		document.getElementById('trainingPageNumber').value="";
		iterateTraining(trainingProgram['programId_'+currentRecord][key],"index");
	}
}
function generateReport(){
	window.open("generate_report3.php?sT="+document.getElementById('reportType').value+"&D="+document.getElementById('reportId').value);

}

</script>


	<table id='indexhere' align=center style='border: 1px solid gray' width=600>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Search Participant Information</h1>
	</th>
	</table>

	<table id='dynamicProgram' width=500 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Select Training Event</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			Training Title
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 onkeyup="processSearch(document.getElementById('training_title').value,'');" />
			<input type=button id='searchText' value='?' onclick="processSearch(document.getElementById('training_title').value,'');" />
			<input type=hidden id='selectedProgram' name='selectedProgram' />
			</td>
		</tr>	
		<tr>
			<td style="background-color:white; color:black;">
			Batch Number
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='batch' name='batch' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 onkeyup="processBatch(document.getElementById('batch').value);" />
			<input type=button id='searchBch' value='?' onclick="processBatch(document.getElementById('batch').value);" />
			</td>
		</tr>
		<!--	
		<tr>
			<td style="background-color:white; color:black;">
			Period
			</td>
			<td style="background-color:white; color:black;">
			<textarea id='period' name='period' value="<?php //echo $ndgrid[1]['training_title']; ?>" cols=30></textarea>
			</td>
		</tr>
		-->
		<tr id='searchResults' name='searchResults' >
		<td style='background-color: #ed5214;'></td>
		<td style='background-color: #ed5214;'></td>
		</tr>		
		<tr>
			<td colspan=2 style='background-color: #ed5214;' align=center>
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="selectEvent(document.getElementById('trainingPageNumber').value,'prev');"><<</a>
<!--||
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'prev');"><<</a>
-->
			<input id='trainingPageNumber' style="text-align:center" type="text" name="trainingPageNumber" size=4 value='' onkeyup="selectEvent(document.getElementById('trainingPageNumber').value,'index');" />	
		<!--	<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'next');">>></a>
||-->
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="selectEvent(document.getElementById('trainingPageNumber').value,'next');">>></a>


			</td>
	
		</tr>		
	</table>
	<table id='trainerTable' align=center  width=500>
		<tr>
			<th style="background-color: #00cc66;color: black;">
			Trainer(s)
			</th>
		</tr>
		<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>
	</table>
	<br>
	<table id='alterTable' width=100%>
		<tr>
			<th>Participant</th>
			<th>Batch #</th>
			<th>Period</th>
			<th>Training Certificate</th>
			<!--
			<th>Request for Certification</th>
			<th>Number of Attendees</th>
-->			
		</tr>
		<tr>
			<td colspan=5></td>
		</tr>
	</table>
	<br>	
	<div align=center><input type=button value='Generate Printout' onclick='generateReport()' /></div>
	<div align=center><input type=hidden id='reportType' /></div>
	<div align=center><input type=hidden id='reportId' /></div>	
	

	
