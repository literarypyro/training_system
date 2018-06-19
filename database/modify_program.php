<?php
//$db=new mysqli("localhost","root","","training");
$db=retrieveTrainingDb();
$sql="select * from training_programs";
$rs=$db->query($sql);

$shuttle=$rs;
$nm=$shuttle->num_rows;
for($i=1;$i<=$nm;$i++){
	$dgrid[$i*1]=$rs->fetch_assoc();
}
$newgrid=count($dgrid)*1+1;
?>
<?php
echo "<script language='javascript'>";
echo "var row=new Array();";
echo "var pageindex=1;";

echo "var limit=".$newgrid."*1-1;";

echo "var eventCount=1;";
$coviterate=0;

for($i=1;$i<$newgrid;$i++){

	echo "row[eventCount]=new Array();";
	echo "row[eventCount]['name_id']='".$dgrid[$i]['id']."';";
	echo "row[eventCount]['training_title']='".$dgrid[$i]['training_title']."';";
	echo "row[eventCount]['division_code']='".$dgrid[$i]['division_code']."';";
	
	echo "row[eventCount]['coverage']=new Array();";
	echo "row[eventCount]['coverageId']=new Array();";

	echo "row[eventCount]['coverageTable']=new Array();";	
	echo "row[eventCount]['count']=0;";	

	echo "eventCount++;";
	
//	$sql="select *,coverage.id as cov_id from coverage inner join training_coverage on coverage.id=coverage_id where training_program='".$dgrid[$i]['id']."'";

//	echo "alert(\"".$sql."\");";	
	$db=retrieveTrainingDb();
	//$db=new mysqli("localhost","root","","training");
	$sql="select *,coverage.id as cov_id from coverage inner join training_coverage on coverage.id=coverage_id where training_program='".$dgrid[$i]['id']."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($m=0;$m<$nm;$m++){
		$row=$rs->fetch_assoc();
		if($row['training_program']==$dgrid[1]['id']){
			$coverageExternal[$coviterate]['id']=$row['cov_id'];
			$coverageExternal[$coviterate]['description']=$row['coverage_description'];
			$coviterate++;
		}
		echo "row[".$row['training_program']."]['coverage'][row[".$row['training_program']."]['count']]='".ucfirst($row['coverage_description'])."';";
		echo "row[".$row['training_program']."]['coverageId'][row[".$row['training_program']."]['count']]='".$row['cov_id']."';";
	//	echo "row[".$row['training_program']."]['coverageTable'][row[".$row['training_program']."]['count']]='';";	
		echo "row[".$row['training_program']."]['count']++;";
	}

	
}
/*
$coviterate=0;

$db=new mysqli("localhost","root","","training");
$sql="select *,coverage.id as cov_id from coverage inner join training_coverage on coverage.id=coverage_id";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	if($row['training_program']==$dgrid[1]['id']){
		$coverageExternal[$coviterate]['id']=$row['cov_id'];
		$coverageExternal[$coviterate]['description']=$row['coverage_description'];
		$coviterate++;
	}
	echo "row[".$row['training_program']."]['coverage'][row[".$row['training_program']."]['count']]='".ucfirst($row['coverage_description'])."';";
	echo "row[".$row['training_program']."]['coverageId'][row[".$row['training_program']."]['count']]='".$row['cov_id']."';";
//	echo "row[".$row['training_program']."]['coverageTable'][row[".$row['training_program']."]['count']]='';";	
	echo "row[".$row['training_program']."]['count']++;";
}
*/
echo "</script>";
?>

<script language="javascript">
function selectProgram(index,direction){
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
				document.getElementById('division_code').value="";
				selectedProgram="";
	
				
				document.getElementById('training_title').value=traineeTerms[1];
				document.getElementById('division_code').value=traineeTerms[2];
				selectedProgram=traineeTerms[0];

				document.getElementById('pageNumber').value=index;
				listCoverage(traineeTerms[0]);
				
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


function listCoverage(trainingID){

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

				var count=(traineeTerms.length)*1-1;
				coverageGrid="<tr><th style='background-color: #00cc66;color: black;'>Coverage</th></tr>";				
				for(var n=0;n<count;n++){

					var parts=traineeTerms[n].split(";");
					coverageGrid+="<tr><td style='background-color: white; color: black;'><a href='#' id='"+parts[0]+"' onclick='markForDeletion(this);' style='text-decoration:none;background-color:white;color:black;'>"+parts[1]+"</a></td></tr>";	


				
				}

				coverageGrid+="<tr><td style=' background-color: #ed5214;' colspan=2></td></tr>";
				if(coverageGrid==""){}
				else {
					document.getElementById('coverageTable').innerHTML=coverageGrid;			
				}				
		
				
				

				
			}

		}
	} 
	
//	var idx=index;
//	if(direction=="next"){
//		idx=index-1;
	
//	}
	
	
	xmlHttp.open("GET","training_processing.php?listCoverage="+trainingID,true);
	xmlHttp.send();	
	

}
</script>
<?php 
$db=retrieveTrainingDb();
//$db=new mysqli("localhost","root","","training");
$sql="select * from coverage";
$rs=$db->query($sql);
$nm=$rs->num_rows;
?>
<?php
echo "<script language='javascript'>";
echo "var coverage=new Array();";
echo "var coverageCounter=1;";
echo "var inclusiveCover=new Array();";
echo "var inclusivecovercount=1;";

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	if($i==0){
		$presentID=$row['id'];
		$presentDescription=$row['coverage_description'];
	}
	echo "coverage[coverageCounter]=new Array();";

	echo "coverage[coverageCounter]['id']='".$row['id']."';";

	echo "coverage[coverageCounter]['category']='".ucfirst($row['coverage_description'])."';";
	
	echo "coverageCounter++;";


}
echo "</script>";
?>

<script language='javascript'>
var typeOfCategory="select";

function iterate(pgindex,action){

	var coverageGrid="";	
	var selectedProgram="";	
	if(action=="prev"){
		if(pgindex=="1"){
			alert("You have reached the end of the record");
		}
		else {
			document.getElementById('pageNumber').value=pgindex*1-1;

			//document.getElementById('name_id').value="";

			document.getElementById('training_title').value="";
			document.getElementById('division_code').value="";

			//document.getElementById('name_id').value=row[pgindex*1-1]['name_id'];

			document.getElementById('training_title').value=row[pgindex*1-1]['training_title'];
			document.getElementById('division_code').value=row[pgindex*1-1]['division_code'];

			selectedProgram=row[pgindex*1-1]['name_id'];
			itemCoverage(selectedProgram);


		}
	}
	else if(action=="next"){

		if(pgindex==limit){
			alert("You have reached the end of the record");
		}
		else {

			document.getElementById('pageNumber').value=pgindex*1+1;

			document.getElementById('training_title').value="";

			document.getElementById('division_code').value="";
			//document.getElementById('name_id').value="";
			document.getElementById('training_title').value=row[pgindex*1+1]['training_title'];
			document.getElementById('division_code').value=row[pgindex*1+1]['division_code'];
			//document.getElementById('name_id').value=row[pgindex*1+1]['name_id'];

			selectedProgram=row[pgindex*1+1]['name_id'];
			itemCoverage(selectedProgram);
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
		
		document.getElementById('training_title').value="";
		document.getElementById('division_code').value="";
	
		document.getElementById('training_title').value=row[pgindex*1]['training_title'];
		document.getElementById('division_code').value=row[pgindex*1]['division_code'];

		selectedProgram=row[pgindex*1]['name_id'];
		itemCoverage(selectedProgram);
	}
}

function itemCoverage(selectedProg){
		coverageGrid="<tr><th style='background-color: #00cc66;color: black;'>Coverage</th></tr>";

		for(i=0;i<row[selectedProg]['count'];i++){
			coverageGrid+="<tr><td style='background-color: white; color: black;'><a href='#' id='"+i+"' onclick='markForDeletion(this);' style='text-decoration:none;background-color:white;color:black;'>"+row[selectedProg]['coverage'][i]+"</a></td></tr>";	

		}	

		coverageGrid+="<tr><td style=' background-color: #ed5214;' colspan=2></td></tr>";
		if(coverageGrid==""){}
		else {
			document.getElementById('coverageTable').innerHTML=coverageGrid;			

		}

}

function iterateCoverage(pgindex,action){
	if(action=="prev"){

		if(pgindex=="1"){
			alert("You have reached the end of the record");
		}
		else {
			document.getElementById('coverpageNumber').value=pgindex*1-1;
			fillCoverageDynamic(document.getElementById('coverpageNumber').value);

		}
	}
	else if(action=="next"){

		if(pgindex==(coverageCounter*1-1)){
			alert("You have reached the end of the record");
		}	
		else {

			document.getElementById('coverpageNumber').value=pgindex*1+1;
			fillCoverageDynamic(document.getElementById('coverpageNumber').value);
		
		}
	}
	else {
		if(pgindex<1){
			alert("You have reached the end of the record");
			pgindex=1;
		}	
		if(pgindex>(coverageCounter*1-1)){
			alert("You have reached the end of the record");
			pgindex=coverageCounter*1-1;
		}		
		document.getElementById('coverpageNumber').value=pgindex;
		fillCoverageDynamic(document.getElementById('coverpageNumber').value);
	}
	
	typeOfCategory="select";
	addCoverage.checked=false;
	selectCoverage.checked=true;
	newCoverage.disabled=true;

}

function fillCoverageDynamic(covindex){
	var ncoverageGrid="";
	ncoverageGrid="<tr><th style='background-color: #00cc66;color: black;'><input type='checkbox' name='selectCoverage' id='selectCoverage' checked  onclick='enableThis(this)' />Select from Existing Coverage</th></tr>";
	ncoverageGrid+="<tr><td style='background-color:white;color:black;'>"+coverage[covindex]['category']+"</td></tr>";
	document.getElementById('dynamicCoverageTable').innerHTML=ncoverageGrid;
	

}

function enableThis(checker){
	if(checker.id=='addCoverage'){
		if(checker.checked==true){
			document.getElementById("selectCoverage").checked=false;
			newCoverage.disabled=false;
			typeOfCategory="add";

		}
		else {
			newCoverage.disabled=true;		
			typeOfCategory="";

		}
	}
	else if(checker.id=='selectCoverage'){
		if(checker.checked==true){
			addCoverage.checked=false;
			newCoverage.disabled=true;
			typeOfCategory="select";

		}
		else {
			typeOfCategory="";
		}

	}

}

function includeCoverage(){
	if((selectCoverage.checked==false)&&(addCoverage.checked==false)){
		alert("You have not selected a coverage to add.");
	}
	else {
		var selectedIterate=document.getElementById('pageNumber').value;
		var selectedEvent=row[selectedIterate]['name_id'];

		if(typeOfCategory=="select"){
			var selectCoverId=document.getElementById('coverpageNumber').value;
			var algorithm="false";
			for(i=0;i<row[selectedEvent]['coverageId'].length;i++){
				if(selectCoverId==row[selectedEvent]['coverageId'][i]){
					algorithm="true";	
				}
			}
			

			if(algorithm=="true"){
				alert("Coverage Item already listed.");
			}
			else {

				row[selectedEvent]['coverageId'][row[selectedEvent]['count']]=coverage[selectCoverId]['id'];
				row[selectedEvent]['coverage'][row[selectedEvent]['count']]=coverage[selectCoverId]['category'];
				row[selectedEvent]['count']++;
												
			}
		}

		else if(typeOfCategory=="add"){
			if(newCoverage.value==""){
				alert("Coverage item not specified");
			}
			else {
				row[selectedEvent]['coverageId'][row[selectedEvent]['count']]="ADD";
				row[selectedEvent]['coverage'][row[selectedEvent]['count']]=newCoverage.value;

				newCoverage.value="";

				row[selectedEvent]['count']++;
				
			}
		}
		itemCoverage(selectedEvent);

	}	
	
	
	
}	

function markForDeletion(element){

	if(document.getElementById('forDeletion').value==""){
	}
	else {
		var selectedItem=document.getElementById('forDeletion').value;
		document.getElementById(selectedItem).parentNode.style.backgroundColor="white";
		document.getElementById(selectedItem).parentNode.style.color='black';
		
		document.getElementById(selectedItem).style.backgroundColor="white";
		document.getElementById(selectedItem).style.color='black';

	}
	
	document.getElementById('forDeletion').value=element.id;
	document.getElementById(element.id).parentNode.style.backgroundColor="#95cbe9";
	document.getElementById(element.id).parentNode.style.color='black';

	document.getElementById(element.id).style.backgroundColor="#95cbe9";
	document.getElementById(element.id).style.color='black';
}

function removeCoverage(){
	var indexDeletion=document.getElementById('forDeletion').value;
	var selectedIterate=document.getElementById('pageNumber').value;
	var selectedEvent=row[selectedIterate]['name_id'];
	
	row[selectedEvent]['coverage'].splice(indexDeletion,1);
	row[selectedEvent]['coverageId'].splice(indexDeletion,1);
	
	row[selectedEvent]['count']--;
	
	document.getElementById('forDeletion').value="";
	itemCoverage(selectedEvent);

}

function validateSubmit(){
	var count=0;
	var selectedIterate=document.getElementById('pageNumber').value;
	var selectedEvent=row[selectedIterate]['name_id'];

	if(document.getElementById('training_title').value==""){
		
	}	
	else {
		count++;
	}

	if(document.getElementById('division_code').value==""){

	}
	else {
		count++;
	}

	if(row[selectedEvent]['coverage'].length>0){
		count++;
	}
	
	if(count==3){

		finalPreparation(selectedEvent);	
	}
	else {
		alert("Some form details have not been completed.");

	}

}

function finalPreparation(evtid){
	document.getElementById('finalName').value=document.getElementById('training_title').value;
	document.getElementById('finalDivision').value=document.getElementById('division_code').value;
	document.getElementById('finalId').value=row[evtid]['name_id'];
	var finalCoverage="";
	for(i=0;i<row[evtid]['coverageId'].length;i++){
		finalCoverage+=row[evtid]['coverageId'][i]+"="+row[evtid]['coverage'][i]+"&&";	

	}	
	

	document.getElementById('finalCategory').value=finalCoverage;
	document.forms["program_submit"].submit();		
}

</script>
<script language="javascript">
var searchEventGrid=new Array();
var searchEventCount=1;

var searchCoverageGrid=new Array();
var searchCoverageCount=1;

function searchProgram(searchkey){
		document.getElementById('pageNumber').value="";
		var searchWord=searchkey;
		document.getElementById('eventSearch').innerHTML="";		
		if(searchWord==""){
			searchWord="xxxxxxxxxxxxxx";
		}
		searchEventGrid.length=0;
		
		for(i=1;i<(eventCount);i++){
			if(row[i]['training_title'].toLowerCase()==searchWord.toLowerCase()){
				searchEventGrid[searchEventCount]=new Array();
				searchEventGrid[searchEventCount]['index']=i;
				searchEventGrid[searchEventCount]['name_id']=row[i]['name_id'];
				searchEventGrid[searchEventCount]['training_title']=row[i]['training_title'];
				searchEventGrid[searchEventCount]['division_code']=row[i]['division_code'];
				searchEventCount++;
				
			}
			else if((row[i]['training_title'].toLowerCase()).indexOf(searchWord.toLowerCase())>-1){
				searchEventGrid[searchEventCount]=new Array();
				searchEventGrid[searchEventCount]['index']=i;
				searchEventGrid[searchEventCount]['name_id']=row[i]['name_id'];
				searchEventGrid[searchEventCount]['training_title']=row[i]['training_title'];
				searchEventGrid[searchEventCount]['division_code']=row[i]['division_code'];
				searchEventCount++;
			}
			else {
			}
		}

		if(searchEventCount>1){
			var optionsGrid="";
			optionsGrid+="<td style='vertical-align:top'><font color='white'>Select Training Program Here:</font></td>";
			optionsGrid+="<td align=center><select multiple=true id='dynamicSel' name='dynamicSel'>";
			for(i=1;i<searchEventCount;i++){
				optionsGrid+="<option value='"+searchEventGrid[i]['index']+"' >";
				optionsGrid+=searchEventGrid[i]['training_title']+", "+searchEventGrid[i]['division_code'];
				optionsGrid+="</option>";
				
			}
			optionsGrid+="</select>";
			optionsGrid+="<br><input type=button value='Get Training Program' onclick=retrieveProgram(document.getElementById('dynamicSel').value) /></td>";
			
			document.getElementById('eventSearch').innerHTML=optionsGrid;
		}
		else {
			document.getElementById('eventSearch').innerHTML="";
		}
		
		searchEventCount=1;



}
function retrieveProgram(item){
	
	document.getElementById('pageNumber').value=item;
	iterate(item*1,"index");
	document.getElementById('eventSearch').innerHTML="";


}

function searchCoverage(searchkey){
	document.getElementById('searchNewCoverage').innerHTML="";
	if(searchkey.length>3){
	document.getElementById('coverpageNumber').value="";

	var searchWord=searchkey;

	if(searchWord==""){
		searchWord="xxxxxxxxxxxxxx";
	}
	searchCoverageGrid.length=0;

	for(i=1;i<(coverageCounter);i++){
		if(coverage[i]['category'].toLowerCase()==searchWord.toLowerCase()){
			searchCoverageGrid[searchCoverageCount]=new Array();
			searchCoverageGrid[searchCoverageCount]['index']=i;
			searchCoverageGrid[searchCoverageCount]['id']=coverage[i]['id'];
			searchCoverageGrid[searchCoverageCount]['category']=coverage[i]['category'];
			searchCoverageCount++;
			
		}
		else if((coverage[i]['category'].toLowerCase()).indexOf(searchWord.toLowerCase())>-1){
			searchCoverageGrid[searchCoverageCount]=new Array();
			searchCoverageGrid[searchCoverageCount]['index']=i;
			searchCoverageGrid[searchCoverageCount]['id']=coverage[i]['id'];
			searchCoverageGrid[searchCoverageCount]['category']=coverage[i]['category'];
			searchCoverageCount++;
		}
		else {
		}
	}
	if(searchCoverageCount>1){
		var optionsGrid="";
		optionsGrid+="<td style='vertical-align:top'><font color='white'>Check for the Coverage Here:</font>";
		optionsGrid+="<br><select style='width:100%;' multiple=true id='mdynamicSel' name='mdynamicSel'>";
		for(i=1;i<searchCoverageCount;i++){
			optionsGrid+="<option value='"+searchCoverageGrid[i]['index']+"' >";
			optionsGrid+=searchCoverageGrid[i]['category'];
			optionsGrid+="</option>";
			
		}
		optionsGrid+="</select>";
		optionsGrid+="<br><input type=button value='Get Coverage' onclick=retrieveCoverage(document.getElementById('mdynamicSel').value) /></td>";
		
		document.getElementById('searchNewCoverage').innerHTML=optionsGrid;
	}
	else {
		document.getElementById('searchNewCoverage').innerHTML="";
	}
	
	searchCoverageCount=1;		
	}
}

function retrieveCoverage(item){
	document.getElementById('coverpageNumber').value=item;
	iterateCoverage(item*1,"index");
	document.getElementById('searchNewCoverage').innerHTML="";
}


</script>
<table width=100% align=center >
	<tr>
	<td>
		<table  align=center  width=400>
		<tr><th  style="background-color:#ed5214;color:white;" colspan=2>Edit Training Program</th></tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">
			Title of New Program
			</td>
			<td  style="background-color: white; color:black;">
			<input type=text id='training_title' name='training_title' value="<?php echo $dgrid[1]['training_title']; ?>" id='title' size=30 onkeyup='searchProgram(this.value);' />
			<input type=button value='?' onclick="searchProgram(document.getElementById('training_title').value);" />
			</td>
		</tr>	
		<tr>
			<td style="background-color: #00cc66;color: black;">
			Division
			</td>
			<td  style="background-color: white; color:black;">
				<select id='division_code' name='division_code'>
				<?php
				$db=new mysqli("localhost","root","","training");
				$sql="select * from division";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();
				?>
					<option value='<?php echo $row['division_code']; ?>' <?php if($dgrid[1]['division_code']==$row['division_code']) { echo "selected"; } ?>><?php echo $row['division_name']; ?></option>
				<?php	
				
				}
				?>		
				</select>
			</td>
		</tr>
		<tr><td style=" background-color: #ed5214;" colspan=2><div id='eventSearch' name='eventSearch'></div><input type="hidden" id="forDeletion" name="forDeletion" /></td></tr>
		</table>
		<table align=center  width=400>
		<tr>
			<td colspan=2 style='background-color: #ed5214;'>

			<a href='#' style='color:white;text-decoration: none;'  onclick="selectProgram(document.getElementById('pageNumber').value,'prev');"><<</a>

<!--			<a href='#' style='color:white;text-decoration: none;' onclick="iterate(document.getElementById('pageNumber').value,'prev');"><<</a>-->
			<input id='pageNumber' name='pageNumber' style="text-align:center" type="text" name="pageNumber" size=4 value='1' onkeyup="iterate(document.getElementById('pageNumber').value,'index');" />	
<!--			<a href='#' style='color:white;text-decoration: none;'  onclick="iterate(document.getElementById('pageNumber').value,'next');">>></a>-->

			<a href='#' style='color:white;text-decoration: none;'  onclick="selectProgram(document.getElementById('pageNumber').value,'next');">>></a>

			</td>
		</tr>		
		</table>
	</td>
	<td colspan=2>&nbsp;</td>
	</tr>
	<tr>
	<td style='vertical-align:top' >
		<table id='coverageTable' align=center  width=400>
			<tr>
				<th style="background-color: #00cc66;color: black;">
				Coverage
				</th>
			</tr>
			<?php
			for($i=0;$i<$coviterate;$i++){
			?>
			<tr>
			<td style='background-color: white; color: black;'><a href='#' id='<?php echo $i; ?>' onclick='markForDeletion(this);' style='text-decoration:none;background-color:white;color:black;'><?php echo $coverageExternal[$i]['description']; ?></a></td>
			</tr>
			
			<?php
			}

			?>

			<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>
		</table>
	</td>
	<td align=center vertical-align=center>
	<input type=button value='<< - Entry' onclick='includeCoverage();' />
	<br><br>
	<input type=button value='>> - Remove' onclick='removeCoverage();' />
	</td>
	<td style='vertical-align:top;'>
	<table id='dynamicCoverageTable'  align=center  width=400>
	<tr>
	<th style="background-color: #00cc66;color: black;">
		<input type='checkbox' name='selectCoverage' id='selectCoverage' checked  onclick='enableThis(this)' />Select from Existing Coverage
	</th>
	
	</tr>


	<tr>
	

	<td style='background-color:white;color:black;'>
	<?php	
	echo $presentDescription;	
	?></td>
	</tr>	
	</table>
		<table align=center  width=400>
		<tr><td align=left style='background-color: #ed5214;'  colspan=2 id='searchNewCoverage'></td></tr>
		<tr>
			<td align=left colspan=2 style='background-color: #ed5214;'>
			<a href='#' style='color:white;text-decoration: none;' onclick="iterateCoverage(document.getElementById('coverpageNumber').value,'prev');"><<</a>
			<input id='coverpageNumber' name='coverpageNumber' style="text-align:center" type="text" name="coverpageNumber" size=4 value='1' onkeyup="iterateCoverage(document.getElementById('coverpageNumber').value,'index');" />	
			<a href='#' style='color:white;text-decoration: none;'  onclick="iterateCoverage(document.getElementById('coverpageNumber').value,'next');">>></a>
			</td>
	
		</tr>		
		</table>	

		<table align=center  width=400>

			<tr>
			<th style="background-color: #00cc66;color: black;">
			<input type='checkbox' name='addCoverage' id='addCoverage' onclick='enableThis(this)' />Add New Coverage
			</th>
			</tr>
			<tr>
			<td width=100% style='background-color:white; color:black;'>

			<textarea name='newCoverage' id='newCoverage' cols=45 disabled onkeyup='searchCoverage(this.value)' ></textarea>
			</td>
			</tr>	
		</table>
	</td>
	</tr>
	<tr>
	<td>
	<form id='program_submit' name='program_submit' action="training_database.php?pp=mPR&action=edit" method="post">

	<table width=400 align=center style='border: 1px solid gray'>
	<tr><td align=center style=" background-color: #ed5214;" colspan=2><input type=button value='Edit' onclick='validateSubmit();' />
		<input type="hidden" id='finalName' name='finalName' />
		<input type="hidden" id='finalDivision' name='finalDivision' />
		<input type="hidden" id='finalCategory' name='finalCategory' cols=50 />
		<input type="hidden" id='finalId' name='finalId' />			
	</td>

	</tr>
	</table>
	</form>
	</td>
	<td colspan=2>&nbsp;</td>
	</tr>	
</table>	
