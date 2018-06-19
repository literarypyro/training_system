<?php
$db=new mysqli("localhost","root","","training");
$sql2="select *,training_instance.id as program_event_id from training_instance";
$rs2=$db->query($sql2);
$nm2=$rs2->num_rows;
?>
<?php
echo "<script language='javascript'>";
echo "var trainingEvent=new Array();";
echo "var trainingEventCount=1;";

for($i=1;$i<=$nm2;$i++){
	$row2=$rs2->fetch_assoc();
	echo "trainingEvent[trainingEventCount]=new Array();";
	echo "trainingEvent[trainingEventCount]['title']=\"".$row2['training_title']."\";";

	echo "trainingEvent[trainingEventCount]['batch']=\"".$row2['batch_number']."\";";
	echo "trainingEvent[trainingEventCount]['start_time']=\"".date("F d, Y",strtotime($row2['start_date']))."\";";
	echo "trainingEvent[trainingEventCount]['end_time']=\"".date("F d, Y",strtotime($row2['end_date']))."\";";
	echo "trainingEvent[trainingEventCount]['trainer']=\"".$row2['trainer']."\";";	
	echo "trainingEvent[trainingEventCount]['ID']=\"".$row2['program_event_id']."\";";	
	echo "trainingEvent[trainingEventCount]['program']=\"".$row2['program_id']."\";";	
	
	echo "trainingEventCount++;";
	
	
	$ndgrid[$i]=$row2;
	
	
	
}
echo "</script>";	

?>
<script language="javascript">

var searchEventGrid=new Array();
var searchEventCount=1;

function iterateTraining(pgindex,action){
	var selectedTrainingID="";
	if(action=="prev"){

		if((pgindex==1)||(pgindex=="")){
			alert("You have reached the end of the record.");
		
		}
		else {
			document.getElementById('trainingPageNumber').value=pgindex*1-1;
			document.getElementById('training_title').value=trainingEvent[pgindex*1-1]['title'];
			document.getElementById('batch').value=trainingEvent[pgindex*1-1]['batch'];

			
			
			//			document.getElementById('trainer').value=trainingEvent[pgindex*1-1]['trainer'];
		
//			document.getElementById('period').value="From "+(trainingEvent[pgindex*1-1]['start_time'])+" to "+(trainingEvent[pgindex*1-1]['end_time']);

//			document.getElementById('traineeGrid').innerHTML=traineeGrid;
			selectedTrainingID=trainingEvent[pgindex*1-1]['ID'];


			
		}
	}
	else if(action=="next"){
		if(pgindex==trainingEventCount*1-1){
			alert("You have reached the end of the record.");
		
		}
		else {
			
			document.getElementById('trainingPageNumber').value=pgindex*1+1;
			document.getElementById('training_title').value=trainingEvent[pgindex*1+1]['title'];
			document.getElementById('batch').value=trainingEvent[pgindex*1+1]['batch'];



			//	document.getElementById('trainer').value=trainingEvent[pgindex*1+1]['trainer'];
		//	document.getElementById('period').value="From "+(trainingEvent[pgindex*1+1]['start_time'])+" to "+(trainingEvent[pgindex*1+1]['end_time']);

			selectedTrainingID=trainingEvent[pgindex*1+1]['ID'];


		}
	
	
	}
	else {
		if(pgindex>trainingEventCount*1-1){
			alert("You have reached the end of the record.");
			pgindex=trainingEventCount*1-1;
		}		
		if(pgindex<1){
			alert("You have reached the end of the record.");
			pgindex=1;
			
		
		}
			document.getElementById('trainingPageNumber').value=pgindex*1;
			document.getElementById('training_title').value=trainingEvent[pgindex*1]['title'];
			document.getElementById('batch').value=trainingEvent[pgindex*1]['batch'];



			//	document.getElementById('trainer').value=trainingEvent[pgindex*1+1]['trainer'];
		//	document.getElementById('period').value="From "+(trainingEvent[pgindex*1]['start_time'])+" to "+(trainingEvent[pgindex*1]['end_time']);

		selectedTrainingID=trainingEvent[pgindex*1]['ID'];
		//alert("A");					
		
	
	
	}
		
	document.getElementById('event_id').value=selectedTrainingID;
	
}

function searchEvent(searchkey){
	var searchWord=searchkey;
	
	if(searchWord==""){
		searchWord="xxxxxxxxxxxxxx";
	}
	searchEventGrid.length=0;
	
	for(i=1;i<(trainingEventCount);i++){
		if(trainingEvent[i]['title'].toLowerCase()==searchWord.toLowerCase()){
			searchEventGrid[searchEventCount]=new Array();
	
			searchEventGrid[searchEventCount]['index']=i;
			searchEventGrid[searchEventCount]['ID']=trainingEvent[i]['ID'];
			searchEventGrid[searchEventCount]['title']=trainingEvent[i]['title'];
			searchEventGrid[searchEventCount]['batch']=trainingEvent[i]['batch'];
			searchEventGrid[searchEventCount]['period']="From "+trainingEvent[i]['start_time']+" to "+trainingEvent[i]['end_time'];
			searchEventGrid[searchEventCount]['trainer']=trainingEvent[i]['trainer'];
			searchEventCount++;
			
		}
		else if((trainingEvent[i]['title'].toLowerCase()).indexOf(searchWord.toLowerCase())>-1){
			searchEventGrid[searchEventCount]=new Array();

			searchEventGrid[searchEventCount]['index']=i;
			searchEventGrid[searchEventCount]['ID']=trainingEvent[i]['ID'];
			searchEventGrid[searchEventCount]['title']=trainingEvent[i]['title'];
			searchEventGrid[searchEventCount]['batch']=trainingEvent[i]['batch'];
			searchEventGrid[searchEventCount]['period']="From "+trainingEvent[i]['start_time']+" to "+trainingEvent[i]['end_time'];
			searchEventGrid[searchEventCount]['trainer']=trainingEvent[i]['trainer'];
			searchEventCount++;
		}
		else {
		}
	}

	if(searchEventCount>1){
		var optionsGrid="";
		optionsGrid+="<td style='background-color: white;color:black;'>Look for Training Program Here:</td>";
		optionsGrid+="<td style='background-color: white;color:black;'>";
		optionsGrid+="<select multiple=true id='dynamicSel' name='dynamicSel'>";
		for(i=1;i<searchEventCount;i++){
			optionsGrid+="<option value='"+searchEventGrid[i]['index']+"' >";
			optionsGrid+=searchEventGrid[i]['title']+" #"+searchEventGrid[i]['batch']+", "+searchEventGrid[i]['period'];
			optionsGrid+="</option>";
			
		}
			

		optionsGrid+="</select>";
		optionsGrid+="<br><input type=button value='Get Training Program' onclick=retrieveEvent(document.getElementById('dynamicSel').value) />";
		optionsGrid+="</td>";
			
		document.getElementById('searchResults').innerHTML=optionsGrid;
	}
	else {
		document.getElementById('searchResults').innerHTML="<td style='background-color: #ed5214;'></td><td style='background-color: #ed5214;'></td>";
	}
	
	searchEventCount=1;
	
}

function retrieveEvent(item){

	iterateTraining(item*1,"index");
//	alert("A");
	document.getElementById('searchResults').innerHTML="<td style='background-color: #ed5214;'></td><td style='background-color: #ed5214;'></td>";


}
</script>
	<table id='dynamicProgram' width=500 align=center style='border: 1px solid gray'>
		<tr><th style="background-color: #00cc66;color: black;"	colspan=2>Select Training Event</th></tr>
		<tr>
			<td style="background-color:white; color:black;">
			Training Title
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $ndgrid[1]['training_title']; ?>" id='title' size=30 onkeyup="searchEvent(document.getElementById('training_title').value);" />
			<input type=button id='searchText' value='?' onclick="searchEvent(document.getElementById('training_title').value);" />
			</td>
		</tr>	
		<tr>
			<td style="background-color:white; color:black;">
			Batch Number
			</td>
			<td style="background-color:white; color:black;">
			<input type=text id='batch' name='batch' value="<?php echo $ndgrid[1]['batch_number']; ?>" id='title' size=30 />
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
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'prev');"><<</a>
			<input id='trainingPageNumber' style="text-align:center" type="text" name="trainingPageNumber" size=4 value='1' onkeyup="iterateTraining(document.getElementById('trainingPageNumber').value,'index');"  />	
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'next');">>></a>
			</td>
	
		</tr>		
	</table>
	<form action="training_plan_outline.php?a=printout" method="post">
	<div align='center' >
	<input type=hidden id='event_id' name='event_id' value='<?php echo $ndgrid[1]['id']; ?>' /><input type=submit value='Generate Printout' />
	</div>
	</form>