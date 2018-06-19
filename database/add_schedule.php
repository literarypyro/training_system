<?php

$db=retrieveTrainingDb();
//$db=new mysqli("localhost","root","","training");
?>

<script language='javascript'>
function markEncodeData(element){
	if(element.checked==true){
		document.getElementById('searchResults2').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";
		document.getElementById('user_name').value="";	
	}

}

function retrieveProgram(id,type){

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

				document.getElementById('training_title').value=traineeTerms[1];
				document.getElementById('division_code').value=traineeTerms[3];				
				document.getElementById('selectedProgram').value=traineeTerms[0];
				document.getElementById('divCode').value=traineeTerms[2];	

				document.getElementById('searchResults').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";

				document.getElementById('programpageNumber').value=0;
				
			}

		}
	} 
	

	xmlHttp.open("GET","training_processing.php?getProgram="+id,true);
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

				document.getElementById('trainee_first_name').value=traineeTerms[1];
				document.getElementById('trainee_last_name').value=traineeTerms[2];
				document.getElementById('trainee_position').value=traineeTerms[4];
				document.getElementById('trainee_middle_name').value=traineeTerms[3];
				document.getElementById('divAssigned').value=traineeTerms[5];

				document.getElementById('user_name').value=traineeTerms[0];

				document.getElementById('searchResults2').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";

				document.getElementById('trainee_pageNumber').value=0;
				
			}

		}
	} 
	

	xmlHttp.open("GET","training_processing.php?getTrainee="+id,true);
	xmlHttp.send();	


}

function searchProgram(title){
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
				document.getElementById('searchResults').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";

			}
			else {

				var traineeTerms=traineeHTML.split("==>");
				
				var count=(traineeTerms.length)*1-1;

				var optionsGrid="";

				optionsGrid+="<td style='background-color: white;color:black;'>Look for Training Program Here:</td>";
				optionsGrid+="<td style='background-color: white;color:black;'>";
				
				optionsGrid+="<select multiple=true id='dynamicSel' name='dynamicSel'>";				
				

				for(var n=0;n<count;n++){
					var parts=traineeTerms[n].split(";");

					optionsGrid+="<option value='"+parts[0]+"' >";
					optionsGrid+=parts[1]+", "+parts[2];
					optionsGrid+="</option>";
					
				}
				optionsGrid+="</select>";
				optionsGrid+="<br><input type=button value='Get Training Program' onclick=retrieveProgram(document.getElementById('dynamicSel').value,'program') />";
				optionsGrid+="</td>";					

					
					
				document.getElementById('searchResults').innerHTML=optionsGrid;

				document.getElementById('programpageNumber').value="";
	
			
			}

		}
	} 
	
	//alert("training_processing.php?searchTrainee=Y&searchFName="+fName+"&searchLName="+lName);
	xmlHttp.open("GET","training_processing.php?searchProgram="+title,true);
	xmlHttp.send();	



}

function processSearch(fName,lName){
	var xmlHttp;
	
	var traineeHTML="";
	document.getElementById('user_name').value="";	


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
					
				document.getElementById('searchResults2').innerHTML=optionsGrid;

				
			}

		}
	} 
	
	//alert("training_processing.php?searchTrainee=Y&searchFName="+fName+"&searchLName="+lName);
	xmlHttp.open("GET","training_processing.php?searchTrainee=Y&searchFName="+fName+"&searchLName="+lName,true);
	xmlHttp.send();	


}

function selectProgram(index,direction){
	if(direction=="next"){
		index++;
	}
	else if(direction=="prev"){
		index--;
	}
	else if(direction=="index"){
		index=index*1;
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
				document.getElementById('division_code').value="";
				document.getElementById('selectedProgram').value="";
				document.getElementById('divCode').value="";
				
				document.getElementById('training_title').value=traineeTerms[1];
				
				if(traineeTerms[3]=="OTH"){
					document.getElementById('division_code').value=traineeTerms[4];				

				}
				else {
					document.getElementById('division_code').value=traineeTerms[3];				
				
				}
				document.getElementById('selectedProgram').value=traineeTerms[0];
				document.getElementById('divCode').value=traineeTerms[2];				
	
				document.getElementById('programpageNumber').value=index;				
			}

		}
	} 
	
	var idx=index;
	if(direction=="next"){
		idx=index-1;
	
	}
	
	
	xmlHttp.open("GET","training_processing.php?indexProgram="+idx,true);
	xmlHttp.send();	



}
function selectTrainee(index,direction){
	if(direction=="next"){
		index++;
	}
	else if(direction=="prev"){
		index--;
	}
	else if(direction=="index"){
		index=index*1;
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


	
				document.getElementById('trainee_first_name').value="";
				document.getElementById('trainee_last_name').value="";
				document.getElementById('trainee_position').value="";
				document.getElementById('trainee_middle_name').value="";
				document.getElementById('divAssigned').value="";

				document.getElementById('user_name').value="";
	
				
				document.getElementById('trainee_first_name').value=traineeTerms[1];
				document.getElementById('trainee_last_name').value=traineeTerms[2];
				document.getElementById('trainee_position').value=traineeTerms[4];
				document.getElementById('trainee_middle_name').value=traineeTerms[3];
				document.getElementById('divAssigned').value=traineeTerms[5];

				document.getElementById('user_name').value=traineeTerms[0];
				
				document.getElementById('trainee_pageNumber').value=index;
				
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

function selectTrainer(index,direction){
	if(direction=="next"){
		index++;
	}
	else if(direction=="prev") {
		index--;
	}
	else if(direction=="index"){
		index=index*1
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

	
				
				document.getElementById('first_name').value=trainerTerms[1];
				document.getElementById('last_name').value=trainerTerms[2];
				document.getElementById('position').value=trainerTerms[3];


				
				document.getElementById('trainerpageNumber').value=index;
				
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

</script>



	<?php
	if(isset($_GET['setYear'])){
		$setYear=$_GET['setYear'];
		$setYear2=$setYear-1;	
	}
	else {
		$setYear=date("Y");	
	}
	$setYear2=$setYear-1;	
	$startYear=1999;	

	if(isset($_GET['setMonth'])){
		$setMonth=$_GET['setMonth'];	
	}
	else {
		$setMonth=date("m");	
	}
	
	if($setMonth==2){
		$limitMonth="29";
	}	
	else if(($setMonth==4)||($setMonth==6)||($setMonth==9)||($setMonth==11)){
		$limitMonth="30";
	}	
	else {
		$limitMonth="31";
	}	

	for($n=$startYear;$n<=$setYear;$n++){	

		for($i=1;$i<=$limitMonth;$i++){
			if($i<10){
				$tag="0".$i;
			}
			else {
				$tag=$i;
			}
			$dateTraining[$n.$setMonth.$tag]="0";	
	
	
		}
	}

	$db=retrieveTrainingDb();
	//$db=new mysqli("localhost","root","","training");
	

	$sql="select count(*) as entries,min(start_time) as earliest,start_date from training_events inner join training_schedule on training_events.start_date=training_schedule.date order by start_date desc";
	$rs=$db->query($sql);	
	
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$dateTraining[date("Ymd",strtotime($row['start_date']))]=$row['entries'];
		$gridDate[date("Ymd",strtotime($row['start_date']))]=$dateTraining[date("Ymd",strtotime($row['start_date']))];
		//echo $row['entries'];
		//echo $dateTraining[date("Ymd",strtotime($row['date']))];
		//$dateTraining[date("Ymd",strtotime($row['date']))]['earliest']=$row['earliest'];
		$dateEarliest[date("Ymd",strtotime($row['start_date']))]=$row['earliest'];
	}

	?>
	<?php
	echo "<script language='javascript'>";
	echo "var dateTraining=new Array();";
	echo "var setYear='".$setYear."';";
	echo "var setMonth='".$setMonth."';";
	echo "var dataGridStyle=new Array();";
	echo "var gridLabelColumn=new Array();";
	echo "var dataGridColumn=new Array();";
	echo "var labelTheMonth=new Array();";
	echo "var dateWeekday=new Array();";	
	
	for($k=1;$k<=12;$k++){
		if($k==2){
			$monthEnd=29;
		}
		else if(($k==4)||($k==6)||($k==11)||($k==9)){
			$monthEnd=30;
		}
		else {
		
			$monthEnd=31;
		
		} 
		
		if($k<10){ 
			$lbMonth="0".$k;
		}
		else {
			$lbMonth=$k;
		}
		
		for($n=$startYear;$n<=$setYear;$n++){
			for($i=1;$i<=$monthEnd;$i++){
				if($i<10){
					$tag="0".$i;
				}
				else {
					$tag=$i;
				}
				
				$labelColumn=date("l, F d, Y",strtotime($setYear."-".$k."-".$tag));
	
				$gridStyle="style='background-color:white; color:black;'";
				$gridBackground="white";
				$aStyle="color:black;";
				$className="";

				if($dateTraining[$setYear.$lbMonth.$tag]>=1){
					$aStyle="color:white;";			
					$gridStyle="style='background-color:red; color:white;'";
					$gridBackground="red";
				}
			
			
				echo "dateTraining['".$n.$lbMonth.$tag."']=\"<tr><td id='".$n.$lbMonth.$tag."' ".$className." colspan=2 align=center ".$gridStyle." ><a style='text-decoration:none;".$aStyle."' href='#dynamicProgram' onclick=selectGrid('".$n.$lbMonth.$tag."','selectedGrid') >".$labelColumn."</a></td></tr>\";";	
				echo "dataGridStyle['".$n.$lbMonth.$tag."']=\"".$gridBackground."\";";
				echo "gridLabelColumn['".$n.$lbMonth.$tag."']=\"".date("F d, Y",strtotime($n."-".$lbMonth."-".$tag))."\";";
				echo "dataGridColumn['".$n.$lbMonth.$tag."']=\"".$labelColumn."\";";
				echo "dateWeekday['".$n.$lbMonth.$tag."']=\"".date("D",strtotime($n."-".$lbMonth."-".$tag))."\";";
			}



			echo "labelTheMonth['".$n.$lbMonth."']=\"".date("F Y",strtotime($n."-".$lbMonth."-01"))."\";";
		}

	}
	
	

	
	if($limitMonth>30){
		echo "var iterLimit=7;";
	}
	else {
		echo "var iterLimit=6;";
	}
	
	echo "var iteration=1";
	echo "</script>";
	$labelDay=date("F Y",strtotime($setYear."-".$setMonth."-01"));
	?>
	<script language="javascript">
	var inclusiveDates=new Array();
	var ponpon="";
	var no_days="";
	var inclusiveStart="";
	var inclusiveEnd="";
	var startShift="";
	var endShift="";
	var dateStarted="";
	var dateEnded="";
	var batchForSubmit="";
	

	var inclusiveCount=0;
	var inclusiveAMPM="";
	var inclusiveShift="";
	var inclusiveTrainees=new Array();
	var inclusiveTrainers=new Array();
	var traineeCount=0;
	var trainerCount=0;
	
	var programEnrolled="";
	var trainerEnrolled="";
	function adjustMonth(mm,yy,action){
		if (action=="prev"){
			mm=setMonth*1-1;
		}
		else {
			mm=setMonth*1+1;
		
		}
		
		
		if((mm>12)||(mm<1)){
		}
		else {
			if(mm<10){
				mm="0"+mm;
				
			}
			
			if((mm==4)||(mm==6)||(mm==9)||(mm==11)||(mm==2)){
				iterLimit=6;
			
			}
			else {
				iterLimit=7;
			}
			setMonth=mm;
			//location.href="training_database.php?setYear="+yy+"&setMonth="+mm;
			var newTable='<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Select Starting Date</th></tr>';
			for(i=1;i<=5;i++){
			
			
			newTable+=dateTraining[setYear+setMonth+"0"+i];
			}
			document.getElementById('dynamicTable').innerHTML=newTable;
			document.getElementById('pageNumber').value="1";
			document.getElementById('labelDay').value=labelTheMonth[setYear+setMonth];
			
		}
	}
	
	function selectGrid(gridCheck,selectCheck){
		var gridLabel=document.getElementById(selectCheck).value;
		if(gridLabel==""){
			processCheck(gridCheck,selectCheck);
		}
		else {
			if(gridLabel==gridCheck){
				document.getElementById(selectCheck).value="";
				document.getElementById(gridCheck).style.backgroundColor=dataGridStyle[gridCheck];
			}
			else {
				processCheck(gridCheck,selectCheck);
			}

		}
	}
	
	function processCheck(gridId,selector){
		//if(document.getElementById(gridId).style.backgroundColor=="red"){
			//alert("That day is fully booked");
		//}
		//else {
			
			document.getElementById('dayShift').length=0;
			document.getElementById('dayShift').options[0]=new Option("Starting 8:30AM","830AM");
			document.getElementById('dayShift').options[1]=new Option("Starting 9:30AM","930AM");
		
			var gridLabel=document.getElementById(selector).value;
			
			if(gridLabel==""){
			
			}
			else {
				if((document.getElementById(gridLabel))==null){
				
				}
				else {
					document.getElementById(gridLabel).style.backgroundColor=dataGridStyle[gridLabel];
				}
			}
			document.getElementById('selectedGrid').value=gridId;

			document.getElementById(document.getElementById('selectedGrid').value).style.backgroundColor='#95cbe9';
		//}
	}
	function selectEvent(index,direction){
		if(direction=="remove"){
			if(document.getElementById('existing_pageNumber').value==""){
			
			}
			else {
			
				//var selected=document.getElementById('existing_pageNumber').value;
				var msg=confirm("Would you like to delete this record?");
			
				if(msg){
					//document.getElementById('modifyId').value=trainingEvent[selected]['trainingId'];
					document.getElementById('action').value="Delete";
					
					document.forms['scheduleSubmit'].submit();
				}
			}
	
		}
		else if(direction=="add"){
			document.getElementById('selectedGrid').value="";
			document.getElementById('action').value="Add";
			document.getElementById('modifyId').value="";			
			document.getElementById('existing_pageNumber').value="";
			inclusiveCount=0;
			inclusiveDates.length=0;
			inclusiveShift.length=0;
			traineeCount=0;
			trainerCount=0;
			inclusiveTrainees.length=0;	
			inclusiveTrainers.length=0;	
			programEnrolled="";
			trainerEnrolled="";
			document.getElementById('selectedBatch').value="";

			if(inclusiveStart==""){
			
			}
			else {
				var changeColor="";		
				var eventInd=inclusiveStart;


				changeColor="white";
				var changeFontC="black";		
				dataGridStyle[inclusiveStart]="white";

				dateTraining[inclusiveStart]="<tr><td id='"+inclusiveStart+"' colspan=2 align=center style='background-color:white;color:black;' ><a style='text-decoration:none;color:black' href='#dynamicProgram' onclick=selectGrid('"+inclusiveStart+"','selectedGrid') >"+dataGridColumn[inclusiveStart]+"</a></td></tr>";	
				
				
			}

			inclusiveStart="";
			inclusiveEnd="";				
			//removeFromList("","events");
			
			fillProgram();


			inclusiveFill();

			fillTrainers("");
			fillClass("");			
		}
		else {
			turnEvent(index,direction);
		
		
		}
	
	
	}
		
	function turnEvent(index,direction){

	if(direction=="next"){
		index++;
	}
	else if(direction=="prev"){
		index--;
	}
	else if(direction=="index"){
		index=index*1;
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

				document.getElementById('action').value="Edit";
				document.getElementById('selectedGrid').value="";
				
				var selected=index;
				document.getElementById('existing_pageNumber').value=selected;
				document.getElementById('modifyId').value=traineeTerms[0];
				programEnrolled=traineeTerms[11];
			//	alert(traineeTerms[11]);
				//trainerEnrolled=trainingEvent[selected]['trainer'];

				inclusiveCount=0;
				inclusiveDates.length=0;
				inclusiveShift.length=0;
				traineeCount=0;
				trainerCount=0;
				inclusiveTrainees.length=0;
				inclusiveTrainers.length=0;
				document.getElementById('selectedBatch').value="";


/*
			for(i=0;i<trainingEvent[selected]['schedule'].length;i++){
				//inclusiveShift[inclusiveCount]=trainingEvent[selected]['shift'][i];
				if(i==0){
					inclusiveShift=trainingEvent[selected]['shift'][i];
				}
				inclusiveCount++;
			}
	
*/
			if(inclusiveStart==""){
			
			}
			else {
				var changeColor="";		
				var eventInd=inclusiveStart;


				changeColor="red";
				var changeFontC="white";		
				dataGridStyle[inclusiveStart]="red";

				dateTraining[inclusiveStart]="<tr><td id='"+inclusiveStart+"' colspan=2 align=center style='background-color:red;color:white;' ><a style='text-decoration:none;color:white;' href='#dynamicProgram' onclick=selectGrid('"+inclusiveStart+"','selectedGrid') >"+dataGridColumn[inclusiveStart]+"</a></td></tr>";	
			
			
			}

			inclusiveStart=traineeTerms[7];
			inclusiveEnd=traineeTerms[8];
			startShift=traineeTerms[9];
			endShift=traineeTerms[10];
			document.getElementById('selectedBatch').value=traineeTerms[2];
			no_days=traineeTerms[5];		
			
			fillProgram();


			inclusiveFill();

			fillTrainers(traineeTerms[0]);
			fillClass(traineeTerms[0]);		
	
				
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

	function fillProgram(){	
		var inclusiveGrid='<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Training Course</th></tr>';
		document.getElementById('programTable').innerHTML=inclusiveGrid;

		if(programEnrolled==""){

		}
		else {
			var programSelected=programEnrolled;
			document.getElementById('selectedProgram').value=programSelected;

			idProgram(programSelected);
			//alert(inclusiveGrid);


		}
		
	}
	function idProgram(title){
	
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

					var programLabel=traineeTerms[1]+", "+traineeTerms[2];
					var inclusiveGrid='<tr><td align=center style="background-color:white;color:black" ><a style="text-decoration:none;color:black;" onclick=markListElement("","") href="#dynamicProgram">'+programLabel+'</a></td></tr>';
					document.getElementById('programTable').innerHTML+=inclusiveGrid;					
				}

			}
		} 
		

		xmlHttp.open("GET","training_processing.php?getProgram="+title,true);
		xmlHttp.send();	
	
	}


	function newDates(type){
		if(type=="date"){
			if(inclusiveStart==""){
				processDates();
			
			}
			else {
				inclusiveStart="";
				inclusiveEnd="";
			
			
				removeFromList("","events");
				processDates();
			}
		
		}	
		else if(type=="trainer"){
			processTrainer();	
		}
		else if(type=="program"){
			processProgram();	
		}
		else if(type=="trainees"){
		
			if(document.getElementById('encodeNewParticipant').checked){
				insertTrainee();
			}
			else {
				processTrainee();	
			}
		}
	}

	function processProgram(){
		var programSelected=document.getElementById('selectedProgram').value;
		programEnrolled=programSelected;
		document.getElementById('selectedBatch').value=document.getElementById('batch_number').value;		
		fillProgram();
	}
	
	function processTrainer(){
		var trainerSelected=document.getElementById('trainerpageNumber').value;
		if(searchTrainee(inclusiveTrainers,trainerSelected)){
			alert("Trainer already listed");
		}
		else {
			inclusiveTrainers[trainerCount]=trainerSelected;
			trainerCount++;
			fillTrainer();
		}		

	}
	
	function fillTrainer(){
	

		
		
		var inclusiveGrid='<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Trainer</th></tr>';
		document.getElementById('trainerTable').innerHTML=inclusiveGrid;		
		
		for(i=0;i<(trainerCount);i++){
			idTrainer(inclusiveTrainers[i],i);
//			inclusiveGrid+='<tr><td id="trainer'+i+'" align=center style="background-color:white;color:black" ><a style="text-decoration:none;color:black;" href="#dynamicProgram" onclick=markListElement("'+i+'","trainers")>'+row[inclusiveTrainers[i]]['lastName']+', '+row[inclusiveTrainers[i]]['firstName']+'</a></td></tr>';
		}

		document.getElementById('trainerTable').innerHTML=inclusiveGrid;		

	}
	
	function searchTrainee(trainArray,traineVa){
		var algorithm=false;
		for(i=0;i<trainArray.length;i++){
			if(traineVa==trainArray[i]){
				algorithm=true;
			}
		}
		return algorithm;
	}
	
	function insertTrainee(){
		var xmlHttp;
		
		var firstName=document.getElementById('trainee_first_name').value;
		var lastName=document.getElementById('trainee_last_name').value;
		var trainee_position=document.getElementById('trainee_position').value;
		var midInitial=document.getElementById('trainee_middle_name').value;
		var divAssigned=document.getElementById('divAssigned').value;
		
	
		
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

				var traineeSelected=xmlHttp.responseText;

				inclusiveTrainees[traineeCount]=traineeSelected;
				traineeCount++;

				document.getElementById('user_name').value="";
				
				document.getElementById('trainee_pageNumber').value="";
				document.getElementById('trainee_first_name').value="";
				document.getElementById('trainee_last_name').value="";
				document.getElementById('trainee_position').value="";
				document.getElementById('trainee_middle_name').value="";
				document.getElementById('divAssigned').value="";




				traineesFill();		

			}
		}
	
		xmlHttp.open("GET","training_processing.php?insertTrainee=Y&firstName="+firstName+"&lastName="+lastName+"&midInitial="+midInitial+"&position="+trainee_position+"&division="+divAssigned,true);	

		xmlHttp.send();	
	
	
	
	}
	
	function processTrainee(){
	//	var traineeSelected=document.getElementById('trainee_pageNumber').value;
	var traineeSelected=document.getElementById('user_name').value;
	
	if(searchTrainee(inclusiveTrainees,traineeSelected)){
			alert("Trainee already in class");
		}
		else {
			if(traineeSelected==""){
			}
			else {
				inclusiveTrainees[traineeCount]=traineeSelected;
				traineeCount++;
				traineesFill();
			}
		}

	}
	

function idTrainee(id,type){

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
				var naming;
				naming=traineeTerms[2]+", "+traineeTerms[1];

				document.getElementById('fillParticipant').value=naming;
				document.getElementById('traineesTable').innerHTML+='<tr><td id="trainee'+type+'" align=center style="background-color:white;color:black" ><a style="text-decoration:none;color:black;" href="#traineesTable" onclick=markListElement("'+type+'","trainees")>'+naming+'</a></td></tr>'		
			}

		}
	} 

	xmlHttp.open("GET","training_processing.php?getTrainee="+id,true);
	xmlHttp.send();	

}
	
	
	
function idTrainer(id,type){

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
				var naming;
				naming=traineeTerms[2]+", "+traineeTerms[1];

				//document.getElementById('fillParticipant').value=naming;
				document.getElementById('trainerTable').innerHTML+='<tr><td id="trainer'+type+'" align=center style="background-color:white;color:black" ><a style="text-decoration:none;color:black;" href="#traineesTable" onclick=markListElement("'+type+'","trainers")>'+naming+'</a></td></tr>'		
			}

		}
	} 
	
	xmlHttp.open("GET","training_processing.php?getTrainer="+id,true);
	xmlHttp.send();	

}
		


	
	
	function traineesFill(){
		var inclusiveGrid='<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Participants</th></tr>';
	//	alert(inclusiveGrid);
		document.getElementById('traineesTable').innerHTML=inclusiveGrid;
		//alert("Count : "+traineeCount);
		for(i=0;i<traineeCount;i++){

//			alert(
			idTrainee(inclusiveTrainees[i],i);
			//name=document.getElementById('fillParticipant').value;
			//inclusiveGrid+='<tr><td id="trainee'+i+'" align=center style="background-color:white;color:black" ><a style="text-decoration:none;color:black;" href="#traineesTable" onclick=markListElement("'+i+'","trainees")>'+name+'</a></td></tr>';
			
		}
//		document.getElementById('selectedTrainer').value=trainerSelected;
		document.getElementById('traineesTable').innerHTML=inclusiveGrid;
	}
	
	function formatValue(eval){
		if(eval*1<10){
			eval="0"+eval;
		
		}
		return eval;
	}
	
	
	function processDates(){
		var dateSelected=document.getElementById('selectedGrid').value;
		dateSelected=document.getElementById('startingYear').value+""+formatValue(document.getElementById('startingMonth').value)+""+formatValue(document.getElementById('startingDay').value);

		var nextStamp="";		

		nextStamp=document.getElementById('endingYear').value+""+formatValue(document.getElementById('endingMonth').value)+""+formatValue(document.getElementById('endingDay').value);

		if(dateSelected>nextStamp){
			alert("Error.  Invalid Date Range.");
			
		}
		else {		
				inclusiveStart=dateSelected;
				inclusiveEnd=nextStamp;
				no_days=document.getElementById('no_days').value;


				startShift=document.getElementById('startingShift').value;
				endShift=document.getElementById('endingShift').value;


				var selectedProgram=document.getElementById('existing_pageNumber');

				if(selectedProgram.value==""){
				}
				else {
				//	trainingEvent[selectedProgram.value]['startdate']=dateSelected;
				//	trainingEvent[selectedProgram.value]['enddate']=nextStamp;
					
				}
				
				//	inclusiveShift=document.getElementById("dayShift").value;
				inclusiveCount++;

				
				
				//	}
	//	}
			inclusiveFill();
		}
	}

	function inclusiveFill(){
		var inclusiveGrid='<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Period</th></tr>';
		var startingShift=document.getElementById('startingShift').value;
		var endingShift=document.getElementById('endingShift').value;
		
		startingShift=startShift;
		endingShift=endShift;
		//	alert(startShift);	
		if(inclusiveStart==""){
		
		}
		else {

			inclusiveGrid+='<tr><td id="'+inclusiveStart+startingShift+'" class="'+startingShift+'" align=center style="background-color:white; color:black;"   colspan=2><a href="#dynamicProgram" style="text-decoration:none;color:black;" onclick=markListElement("'+inclusiveStart+'/'+startingShift+'/START","events") >Start Date: '+gridLabelColumn[inclusiveStart]+' / '+startingShift+'</a></td></tr>';
			inclusiveGrid+='<tr><td id="'+inclusiveEnd+endingShift+'" class="'+endingShift+'" align=center style="background-color:white; color:black;"   colspan=2><a href="#dynamicProgram" style="text-decoration:none;color:black;" onclick=markListElement("'+inclusiveEnd+'/'+endingShift+'/END","events") >End Date: '+gridLabelColumn[inclusiveEnd]+' / '+endingShift+'</a></td></tr>';
			inclusiveGrid+='<tr><td align=center style="background-color:white; color:black;"   colspan=2><a href="#dynamicProgram" style="text-decoration:none;color:black;"  >Number of Days: '+no_days+'</a></td></tr>';

			}
		document.getElementById('inclusiveTable').innerHTML=inclusiveGrid;

		
		var selectedGrid=document.getElementById('selectedGrid').value;

		if(selectedGrid==""){
		}
		else {

			dataGridStyle[selectedGrid]="red";
			dateTraining[selectedGrid]="<tr><td id='"+selectedGrid+"' colspan=2 align=center style='background-color:red;color:white;' ><a style='text-decoration:none;color:white' href='#dynamicProgram' onclick=selectGrid('"+selectedGrid+"','selectedGrid') >"+dataGridColumn[selectedGrid]+"</a></td></tr>";	
			document.getElementById(selectedGrid).style.backgroundColor="red";
			document.getElementById(selectedGrid).style.color="white";
			document.getElementById(selectedGrid).getElementsByTagName('a')[0].style.color="white";

		}



		document.getElementById('selectedGrid').value="";

	}
	
	function markListElement(element,listName){
		if(listName=="trainees"){
			if(document.getElementById('selectedTrainee').value==""){
				document.getElementById('trainee'+element).style.backgroundColor="#95cbe9";
			}
			else {
				document.getElementById('trainee'+document.getElementById('selectedTrainee').value).style.backgroundColor="white";
				document.getElementById('trainee'+element).style.backgroundColor="#95cbe9";
			}
			document.getElementById('selectedTrainee').value=element;

		}
		else if(listName=="events"){
			var previous=document.getElementById("selectedInclusive").value;
			document.getElementById("selectedInclusive").value=element;
			var ids=element.split('/');
			document.getElementById(ids[0]+ids[1]).style.backgroundColor="#95cbe9";
			if(previous==""){
			}
			else {
				var ids2=previous.split('/');
				document.getElementById(ids2[0]+ids2[1]).style.backgroundColor="white";
				document.getElementById(ids2[0]+ids2[1]).style.color="black";
			}

			
		}
		else if(listName=="trainers"){
			if(document.getElementById('selectedTra').value==""){
				document.getElementById('trainer'+element).style.backgroundColor="#95cbe9";
			}
			else {
				document.getElementById('trainer'+document.getElementById('selectedTra').value).style.backgroundColor="white";
				document.getElementById('trainer'+document.getElementById('selectedTra').value).style.color="black";
				document.getElementById('trainer'+element).style.backgroundColor="#95cbe9";
			}
			document.getElementById('selectedTra').value=element;

		}	
	}
	
	function removeFromList(selectedGridId,type){
		if(type=="trainees"){
			inclusiveTrainees.splice(document.getElementById('selectedTrainee').value,1);
			traineeCount--;
			traineesFill();
			document.getElementById('selectedTrainee').value="";

		}
		else if(type=="program"){
			programEnrolled="";
			fillProgram();
		}
		else if(type=="trainer"){
			inclusiveTrainers.splice(document.getElementById('selectedTra').value,1);
			trainerCount--;
			trainerEnrolled="";
			fillTrainer();
		}
		
		else if(type=="events"){

			no_days="";
			inclusiveStart="";
			inclusiveEnd="";
			startShift="";
			endShift="";
		//	inclusiveFill();

			var inclusiveGrid='<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Period</th></tr>';

			document.getElementById('inclusiveTable').innerHTML=inclusiveGrid;
			document.getElementById("selectedInclusive").value="";


		}		

	}
	
	function finalPreparation(){
		var count=0;
		if(inclusiveTrainers.length>0){
			count++;
		}
		if(programEnrolled==""){
		}
		else{
			count++;
		}
		if(inclusiveStart==""){
		}
		else {
			count++;
		}
		if(inclusiveEnd==""){
		}
		else {
			count++;
		}
		if(document.getElementById('selectedBatch').value==""){
		
		}
		else {
			count++;
		}
		
		if(inclusiveTrainees.length>0){
			count++;
		}
		return count;
	}
	
	function createSchedule(action){
		if(finalPreparation<6){
			alert("The Training details are still incomplete.");
		}
		else {
			var vbmsg=confirm("Are you ready to submit your schedule? Double-check if necessary.");
			if(vbmsg){
				document.getElementById('trainerSubmit').value=inclusiveTrainers;
				document.getElementById('programSubmit').value=programEnrolled;
				document.getElementById('batchSubmit').value=document.getElementById('selectedBatch').value;
				document.getElementById('traineeSubmit').value=inclusiveTrainees;
				document.getElementById('dateSubmit').value=inclusiveDates;
				document.getElementById('startSubmit').value=inclusiveStart;
				document.getElementById('endSubmit').value=inclusiveEnd;
				document.getElementById('startshiftSubmit').value=startShift;
				document.getElementById('endshiftSubmit').value=endShift;				
				document.getElementById('no_daysSubmit').value=no_days;		
				document.forms["scheduleSubmit"].submit();
			}
		}
	
	}

function fillTrainers(event_id){
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

				for(i=0;i<count;i++){
					var parts=traineeTerms[i].split(";");
					inclusiveTrainers[trainerCount]=parts[0];
					trainerCount++;
				
				}

			}
			fillTrainer();
		}
	} 

	xmlHttp.open("GET","training_processing.php?listTrainers="+event_id,true);
	xmlHttp.send();	



}

function fillClass(event_id){

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

				for(i=0;i<count;i++){
					var parts=traineeTerms[i].split(";");

					inclusiveTrainees[traineeCount]=parts[0];
					traineeCount++;
				
				}

			}
//			alert(traineeCount);
			traineesFill();

		}
	} 


	xmlHttp.open("GET","training_processing.php?listClass="+event_id,true);
	xmlHttp.send();	



}


var searchProgramGrid=new Array();
var searchProgramCount=1;

var searchTraineeGrid=new Array();
var searchTraineeCount=1;



function enterShift(shiftName,shiftType){
	var prefix="";
	if(shiftType=='AM'){
		prefix="starting";
	
	}
	else if(shiftType=='PM'){
		prefix="ending";
	
	}
	document.getElementById(shiftName).value=(document.getElementById(prefix+"Hour").value+document.getElementById(prefix+"Minute").value+document.getElementById(prefix+"Half").value);
	
}


</script>



	
	<table width=100%>
	<tr>
	<td width=10%>&nbsp;
	</td>
	<th style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Manage Training Schedules</h1>
	</th>
	<td width=10%>&nbsp;
	</td>
	</tr>
	<tr>
	<td width=10%>
	</td>
	<th>
	</th>
	<td width=10%>
	</td>
	</tr>
	<tr>
	<td width=10%>&nbsp;
	</td>
	<td valign="top" width=35%>
		<table id='programTable' name='programTable' width=300 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Training Course</th></tr>
		</table>
		<input type=hidden id='selectedPro' />

		<div align=center>Batch Number:<input type=text id='selectedBatch' name='selectedBatch' size=5 /></div>

	</td>
	<td width=10% valign=center align=center>
	<input type=button value='<< - Entry' onclick='newDates("program")' />
	<br>
	<br>
	<br>
	<input type=button value='>> - Remove' onclick='removeFromList("","program")' />

	</td>
	<td width=35%>
	
	<table id='dynamicProgram' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Change Training Course</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			Title of New Training<br> Course
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 onkeyup="searchProgram(document.getElementById('training_title').value);" />
			<input type=button id='searchText' value='?' onclick="searchProgram(document.getElementById('training_title').value);" />
			</td>
		</tr>	
		<tr>
			<td style="background-color:white; color:black;">
			Division
			</td>
			<td style="background-color:white; color:black;">
				<input type=text id='division_code' name='division_code' value="<?php echo $ndgrid[1]['division_name']; ?>" id='title' size=30 />
			</td>
		</tr>
		<tr>
			<td style="background-color:white; color:black;">
			Batch Number
			</td>
			<td style="background-color:white; color:black;">
				<input type=text id='batch_number' name='batch_number' id='title' size=4 />
				<input type=hidden id='selectedProgram' name='selectedProgram' value="<?php echo $ndgrid[1]['id']; ?>" />
				<input type=hidden id='divCode' name='divCode' value="<?php echo $ndgrid[1]['division_code']; ?>" />
			
			
			</td>
		</tr>	
		<tr id='searchResults' name='searchResults' >
			<td style="background-color:white; color:black;"></td>
			<td style="background-color:white; color:black;"></td>
		</tr>
		<tr>
			<td colspan=2 style='background-color: #ed5214;'>
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="selectProgram(document.getElementById('programpageNumber').value,'prev');"><<</a>
<!--
			||
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="iterateProgram(document.getElementById('programpageNumber').value,'prev');"><<</a>
-->
			<input id='programpageNumber' style="text-align:center" type="text" name="pageNumber" size=4 onkeyup="selectProgram(document.getElementById('programpageNumber').value,'index');" />	
		<!--	<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="iterateProgram(document.getElementById('programpageNumber').value,'next');">>></a>
		||	
		-->
		<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="selectProgram(document.getElementById('programpageNumber').value,'next');">>></a>

			</td>
	
		</tr>
	
	</table>
			<input type=hidden id='selectedProgram' />
	</td>
	<td width=10%>&nbsp;
	</td>
	
	</tr>
	<!--Trainer--><tr>
		<td width=10%>&nbsp;
	</td>
	<td valign="top" width=200>
		<table id='trainerTable' width=300 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Trainer</th></tr>
		</table>
		<input type=hidden id='selectedTra' />

	</td>
	<td width=100 valign=center align=center>
	<input type=button value='<< - Entry' onclick='newDates("trainer")' />
	<br>
	<br>
	<br>
	<input type=button value='>> - Remove' onclick='removeFromList("","trainer")' />

	</td>
	<td width=400>
	
	<table width=400 align=center style='border: 1px solid gray'>
			<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Add/Edit Trainers</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			First Name
			</td>
			<td  style="background-color:white; color:black;">
			<input type=text id='first_name' name='first_name' value="<?php echo $dgrid[1]["firstName"]; ?>" size=30 />
			</td>
		</tr>	
		<tr>
			<td  style="background-color:white; color:black;">
			Last Name
			</td>
			<td  style="background-color:white; color:black;">
				<input type=text id='last_name' name='last_name' value="<?php echo $dgrid[1]["lastName"]; ?>" size=30 />
			</td>
		</tr>
		<tr>
			<td style="background-color:white; color:black;">Position</td>
			<td style="background-color:white; color:black;"><input type='text' id='position' name='position' value="<?php echo $dgrid[1]["position"]; ?>" size=30 /></td>
		</tr>
		<tr>
			<td style="background-color:white; color:black;"></td>
			<td style="background-color:white; color:black;"></td>
		</tr>
		
		<tr>
			<td colspan=2 style='background-color: #ed5214;'>
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="selectTrainer(document.getElementById('trainerpageNumber').value,'prev');"><<</a>
			<input id='trainerpageNumber' style="text-align:center" type="text" name="pageNumber" size=4 onkeyup="selectTrainer(document.getElementById('trainerpageNumber').value,'index');" />	
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="selectTrainer(document.getElementById('trainerpageNumber').value,'next');">>></a>

			</td>
	
		</tr>
	
	</table>
			<input type=hidden id='selectedTrainer' />
	</td>
		<td width=10%>&nbsp;
	</td>
	</tr>
	<!--Dates--><tr>
		<td width=10%>&nbsp;
	</td>
	<td valign="top" width=200>
		<table id='inclusiveTable' width=300 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Period</th></tr>
		</table>
		<input type=hidden id='selectedInclusive' />

	</td>
	<td width=100 valign=center align=center>
	<input type=button value='<< - Entry' onclick='newDates("date")' />
	<br>
	<br>
	<br>
	<input type=button value='>> - Remove' onclick='removeFromList("","events")' />

	</td>
	<td width=400>
	

	<table id='dynamicTable' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Select Starting Date</th></tr>
		
		<tr>
			<td  align=center colspan=2 style='background-color:white; color:black;'>
		<select id='startingMonth'>
<?php
		for($i=1;$i<13;$i++){
		$year=date("Y");
		$selectedMonth=date("m");
		?>
		<option <?php if($i==$selectedMonth){ echo "selected"; } ?> value='<?php echo $i; ?>' >
		<?php echo date("F",strtotime($year."-".$i."-01")); ?>
		</option>
<?php
		}
?>	
		</select>
		
		<font style="color:white;" color=white>|</font>
		<select id='startingDay'>
<?php
		for($i=1;$i<32;$i++){
		$year=date("Y");
		$selectedDay=date("d");

		?>
		<option <?php if($i==$selectedDay){ echo "selected"; } ?> value='<?php echo $i; ?>' >
		<?php echo $i; ?>
		</option>
<?php
		}
?>	
		</select>
		<font style="color:white;" color=white>|</font>
		<select id='startingYear'>
<?php
		$startYear=date("Y")*1-5;
		$endYear=$startYear*1+16;
		$selectedYear=date("Y");
		for($i=1999;$i<$endYear;$i++){
		?>
		<option <?php if($i==$selectedYear){ echo "selected"; } ?> value='<?php echo $i; ?>' >
		<?php echo $i; ?>
		</option>
<?php
		}
?>	
		</select>
</td>
</tr>		

		</table>
		<table  width=400 align=center style='border: 1px solid gray'>

		<tr>
			<td>
				Time: 


				<select id='startingHour' onchange="enterShift('startingShift','AM')">
				<?php 
				for($m=1;$m<13;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
					
					
				?>
					<option <?php if($m==8){ echo "selected"; } ?>>
					<?php echo $label; ?>
					</option>
				<?php
				}
				?>
				</select>
				
				<select id='startingMinute' onchange="enterShift('startingShift','AM')">
				<?php 
				for($m=0;$m<60;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
				?>
					<option <?php if($m==30){ echo "selected"; } ?>><?php echo $label; ?></option>
				<?php
				}
				?>
				</select>
				<select id='startingHalf' onchange="enterShift('startingShift','AM')">
				<option selected>AM</option>
				<option>PM</option>
				</select>
				
				<input type=hidden id='startingShift' value='0830AM' />
			</td>
		</tr>
		</table>	
	<table id='dynamicTable' width=400 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Select Ending Date</th></tr>
		
		<tr>
			<td  align=center colspan=2 style='background-color:white; color:black;'>
		<select id='endingMonth'>
<?php
		for($i=1;$i<13;$i++){
		$year=date("Y");
		$selectedMonth=date("m");
		?>
		<option <?php if($i==$selectedMonth){ echo "selected"; } ?> value='<?php echo $i; ?>' >
		<?php echo date("F",strtotime($year."-".$i."-01")); ?>
		</option>
<?php
		}
?>	
		</select>
		
		<font style="color:white;" color=white>|</font>
		<select id='endingDay'>
<?php
		for($i=1;$i<32;$i++){
		$year=date("Y");
		$selectedDay=date("d");

		?>
		<option <?php if($i==$selectedDay){ echo "selected"; } ?> value='<?php echo $i; ?>' >
		<?php echo $i; ?>
		</option>
<?php
		}
?>	
		</select>
		<font style="color:white;" color=white>|</font>
		<select id='endingYear'>
<?php
		$startYear=date("Y")*1-5;
		$endYear=$startYear*1+16;
		$selectedYear=date("Y");
		for($i=1999;$i<$endYear;$i++){
		?>
		<option <?php if($i==$selectedYear){ echo "selected"; } ?> value='<?php echo $i; ?>' >
		<?php echo $i; ?>
		</option>
<?php
		}
?>	
		</select>
</td>
</tr>		

		</table>		
		
		<table  width=400 align=center style='border: 1px solid gray'>

		<tr>
			<td>
				Time: 

				<select id='endingHour' onchange="enterShift('endingShift','PM')">
				<?php 
				for($m=1;$m<13;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
					
					
				?>
					<option <?php if($m==8){ echo "selected"; } ?>>
					<?php echo $label; ?>
					</option>
				<?php
				}
				?>
				</select>
				
				<select id='endingMinute' onchange="enterShift('endingShift','PM')">
				<?php 
				for($m=0;$m<60;$m++){
					$label=$m;
					if($label<10){
						$label="0".$label;
					
					}
				?>
					<option <?php if($m==30){ echo "selected"; } ?>><?php echo $label; ?></option>
				<?php
				}
				?>
				</select>	
				<select id='endingHalf' onchange="enterShift('endingShift','PM')">
				<option selected>AM</option>
				<option>PM</option>
				</select>
				
				<input type=hidden id='endingShift' value='0830AM' />

				
			</td>


			
		</tr>
		<tr>
		<td>Number of Days:
		<input type=text name='no_days' id='no_days' size=5 value='1' />
		
		</td>
		</tr>
		</table>
		<input type=hidden id='selectedGrid' />
	</td>
	<td>
	</td>
		<td width=10%>&nbsp;
	</td>
	</tr>
	<!--Trainees--><tr>
		<td width=10%>&nbsp;
	</td>
	<td valign="top" width=200>
		<table id='traineesTable' width=300 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Participants</th></tr>
		</table>
		<input type=hidden id='selectedTrainee' />

	</td>
	<td width=100 valign=center align=center>
	<input type=button value='<< - Entry' onclick='newDates("trainees")' />
	<br>
	<br>
	<br>
	<input type=button value='>> - Remove' onclick='removeFromList("","trainees")' />

	</td>
	<td width=400>
		<table width=400 id='dynamicTrainees' align=center style='border: 1px solid gray'>
		<tr><th  style="background-color: #00cc66;color: white;" colspan=2>Add Participants</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			First Name
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='trainee_first_name' name='trainee_first_name' value="<?php echo $student[1]["firstName"]; ?>" size=30 onkeyup="processSearch(document.getElementById('trainee_first_name').value,document.getElementById('trainee_last_name').value);" />
			<input type=button id='searchText' value='?' onclick="processSearch(document.getElementById('trainee_first_name').value,document.getElementById('trainee_last_name').value);" />
			</td>
		</tr>	
		<tr>
			<td style="background-color:white; color:black;">
			Last Name
			</td>
			<td style="background-color:white; color:black;">
				<input type=text id='trainee_last_name' name='trainee_last_name' value="<?php echo $student[1]["lastName"]; ?>" size=30 onkeyup="processSearch(document.getElementById('trainee_first_name').value,document.getElementById('trainee_last_name').value);" />
				<input type=button id='searchText' value='?' onclick="processSearch(document.getElementById('trainee_first_name').value,document.getElementById('trainee_last_name').value);" />
			</td>
		</tr>
		<tr>
			<td style="background-color:white; color:black;">
			Middle Name
			</td>
			<td style="background-color:white; color:black;">
				<input type=text id='trainee_middle_name' name='trainee_middle_name' value="<?php echo $student[1]["midInitial"]; ?>" size=30 />
				<!--
				onkeyup="searchName(document.getElementById('trainee_first_name').value,document.getElementById('trainee_last_name').value);"
				<input type=button id='searchText' value='?' onclick="searchName(document.getElementById('trainee_first_name').value,document.getElementById('trainee_last_name').value);" />
				-->
			</td>
		</tr>
		<tr>
			<td style="background-color:white; color:black;">Designation</td>
			<td style="background-color:white; color:black;"><input type='text' id='trainee_position' name='trainee_position' value="<?php echo $student[1]["designation"]; ?>" size=30 /></td>
		</tr>
		<tr>
			<td style="background-color:white; color:black;">Division Assigned</td>
			<td style="background-color:white; color:black;">
			<select name='divAssigned' id='divAssigned'>
				<option></option>
				<?php
//				$db=new mysqli("localhost","root","","training");
				$db=retrieveTrainingDb();
				$sql="select * from division";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();
				?>
					<option value='<?php echo $row['division_code']; ?>'><?php echo $row['division_name']; ?></option>
				<?php	
				
				}
//				$db->insert_id();
				?>	
			</select>
			</td>
		</tr>		
		<tr>
			<td style="background-color:white; color:black;" colspan=2><input type=checkbox id='encodeNewParticipant' name='encodeNewParticipant' onclick='markEncodeData(this)' />Encode as New Data</td>
		</tr>		

		<tr>
			<td style="background-color:white; color:black;"><input type=hidden value="<?php echo $student[1]["id"]; ?>" name='user_name' id='user_name' /></td>
			<td style="background-color:white; color:black;"><input type=hidden  name='fillParticipant' id='fillParticipant' /></td>
		</tr>		
		<tr id='searchResults2' name='searchResults2' >
			<td style="background-color:white; color:black;"></td>
			<td style="background-color:white; color:black;"></td>
		</tr>		
		
		<tr>
			<td colspan=2 style='background-color: #ed5214;'>
			<a href='#dynamicTrainees' style='color:white;text-decoration: none;'  onclick="selectTrainee(document.getElementById('trainee_pageNumber').value,'prev');"><<</a>

			<input id='trainee_pageNumber' style="text-align:center" type="text" name="trainee_pageNumber" size=4 onkeyup="selectTrainee(document.getElementById('trainee_pageNumber').value,'index');" />	
			<a href='#dynamicTrainees' style='color:white;text-decoration: none;'  onclick="selectTrainee(document.getElementById('trainee_pageNumber').value,'next');">>></a>

			</td>
	
		</tr>
		</table>
	
			<input type=hidden id='selectedTrainee' />
	</td>
		<td width=10%>&nbsp;
	</td>
	</tr>
	<tr>
		<td width=10%>&nbsp;
	</td>
	<td align=center>
	<input type=button value='Ready to Generate Schedule!' onclick=createSchedule("generate") />
	</td>
	<td>&nbsp;
	</td>
	<td>&nbsp;
	</td>
		<td width=10%>&nbsp;
	</td>
	</tr>
	<tr>
	<th>
	<form id='scheduleSubmit' method=post action="training_event.php?a=Cr">
		<input type=hidden id='trainerSubmit' name='trainerSubmit' />
		<input type=hidden id='programSubmit' name='programSubmit' />
		<input type=hidden id='traineeSubmit' name='traineeSubmit' />
		<input type=hidden id='dateSubmit' name='dateSubmit' />
		<input type=hidden id='startSubmit' name='startSubmit' />
		<input type=hidden id='batchSubmit' name='batchSubmit' />
		<input type=hidden id='endSubmit' name='endSubmit' />		
		<input type=hidden id='startshiftSubmit' name='startshiftSubmit' />
		<input type=hidden id='endshiftSubmit' name='endshiftSubmit' />
		<input type=hidden id='shiftSubmit' name='shiftSubmit' />
		<input type=hidden id='no_daysSubmit' name='no_daysSubmit' />

		<input type=hidden id='modifyId' name='modifyId' />
		<input type=hidden id='action' name='action' value='Add' />
	</form>
	</th>
	</tr>
	<tr>
	<td width=10%>&nbsp;
	</td>
	<td>

	<table id='schedulesExisting' width=300 align=center style='border: 1px solid gray'>
		<tr><th style='background-color: #ed5214;'	colspan=2>
		<a href='#dynamicTrainees' style='color:white;text-decoration: none;'  onclick="selectEvent(document.getElementById('existing_pageNumber').value,'remove');"><<</a>

		<font style="color: #ed5214;" color='#ed5214;'>||</font>
<!--		<a href='#dynamicTrainees' style='color:white;text-decoration: none;' onclick="iterateExisting(document.getElementById('existing_pageNumber').value,'prev');"><</a>
-->
		<a href='#dynamicTrainees' style='color:white;text-decoration: none;'  onclick="selectEvent(document.getElementById('existing_pageNumber').value,'prev');"><</a>

		<input id='existing_pageNumber' style="text-align:center" type="text" name="existing_pageNumber" size=4 onkeyup="selectEvent(document.getElementById('existing_pageNumber').value,'index');" />	

		<a href='#dynamicTrainees' style='color:white;text-decoration: none;'  onclick="selectEvent(document.getElementById('existing_pageNumber').value,'next');">></a>

		<!--
		<a href='#dynamicTrainees' style='color:white;text-decoration: none;'  onclick="iterateExisting(document.getElementById('existing_pageNumber').value,'next');">></a>
-->
		<font style="color: #ed5214;" color='#ed5214;'>||</font>
		<a href='#dynamicTrainees' style='color:white;text-decoration: none;'  onclick="selectEvent(document.getElementById('existing_pageNumber').value,'add');">>></a>
	
		</th></tr>
		</table>
	</td>
	<td>&nbsp;
	</td>
	<td>&nbsp;
	</td>
		<td width=10%>&nbsp;
	</td>
	</tr>

	
	
	
	</table>