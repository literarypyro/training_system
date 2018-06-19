<?php 
$db=retrieveTrainingDb();
//$db=new mysqli("localhost","root","","training");
$sql="select * from coverage";
$rs=$db->query($sql);
$nm=$rs->num_rows;
//$placemessage="Lord, magkatuluyan lang kami ni yfe, ako na yata ang pinakamaswerteng lalake sa buong mundo";

?>
<?php
echo "<script language='javascript'>";
echo "var coverage=new Array();";
echo "var coverageCount=1;";
echo "var inclusiveData=new Array();";
echo "var inclusivecount=0;";
	
	
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	if($i==0){
		$presentID=$row['id'];
		$presentDescription=$row['coverage_description'];
	}
	echo "coverage[coverageCount]=new Array();";

	echo "coverage[coverageCount]['id']='".$row['id']."';";

	echo "coverage[coverageCount]['category']='".ucfirst($row['coverage_description'])."';";
	echo "coverageCount++;";


}
echo "</script>";
?>
<script language='javascript'>
var typeOfCategory="select";
var searchCoverageGrid=new Array();
var searchCoverageCount=1;


function iterate(pgindex,action){

	var coverageGrid="";	
	var selectedCategoryId="";	
	coverageGrid="<tr><th style='background-color: #00cc66;color: black;'><input type='checkbox' name='selectCoverage' id='selectCoverage' checked onclick='enableThis(this)' />Select from Existing Coverage</th></tr>";
	if(action=="prev"){
		if(pgindex==1){
			alert("You have reached the end of the record.");
			
		}
		else {
			document.getElementById('pageNumber').value=pgindex*1-1;
			pgindex=pgindex*1-1;	


			document.getElementById('coverageTable').innerHTML="";



		}

		coverageGrid+="<tr><td style='background-color:white;color:black;'>"+coverage[pgindex*1]['category']+"</td></tr>";	



	}
	else if(action=="next"){
		if(pgindex==(coverageCount*1-1)){
			alert("You have reached the end of the record.");
			
		}
		else {
			document.getElementById('pageNumber').value=pgindex*1+1;
			document.getElementById('coverageTable').innerHTML="";
			
			pgindex++;
		}

		coverageGrid+="<tr><td style='background-color:white;color:black;'>"+coverage[pgindex*1]['category']+"</td></tr>";	



	}

	else {
		if(pgindex>(coverageCount*1-1)){		
			alert("You have reached the end of the record.");
			pgindex=coverageCount*1-1;
		}

		else if(pgindex<1){			
			alert("You have reached the end of the record.");
			pgindex=1;
			
		}		

		document.getElementById('pageNumber').value=pgindex*1;
		document.getElementById('coverageTable').innerHTML="";

		coverageGrid+="<tr><td style='background-color:white;color:black;'>"+coverage[pgindex*1]['category']+"</td></tr>";	
	}	

	coverageGrid+="<tr><td style='background-color: #ed5214;' colspan=2></td></tr></table>";
	document.getElementById('coverageTable').innerHTML=coverageGrid;
	
	typeOfCategory="select";
	addCoverage.checked=false;
	selectCoverage.checked=true;
	newCoverage.disabled=true;


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
		if(typeOfCategory=="select"){
			var selectCoverId=coverage[document.getElementById('pageNumber').value]['id'];
			var algorithm="false";
			for(i=0;i<inclusiveData.length;i++){
				if(selectCoverId==inclusiveData[i]['id']){
					algorithm="true";	
				}
			}
			if(algorithm=="true"){
				alert("Coverage Item already listed.");
			}
			else {
				inclusiveData[inclusivecount]=new Array();
				inclusiveData[inclusivecount]['id']=coverage[document.getElementById('pageNumber').value]['id'];
				inclusiveData[inclusivecount]['name']=coverage[document.getElementById('pageNumber').value]['category'];
				inclusiveData[inclusivecount]['index']=inclusivecount;

				inclusivecount++;
			}
		}

		else if(typeOfCategory=="add"){
			if(newCoverage.value==""){
				alert("Coverage item not specified");
			}
			else {
				inclusiveData[inclusivecount]=new Array();
				inclusiveData[inclusivecount]['id']="ADD";
				inclusiveData[inclusivecount]['name']=newCoverage.value;
				inclusiveData[inclusivecount]['index']=inclusivecount;
				newCoverage.value="";
				inclusivecount++;
			}
		}
		fillDynamic();

	}

}

function fillDynamic(){

	dynamicCoverage.innerHTML="";
	dynamicGrid='<tr><th style="background-color: #00cc66;color: black;">Coverage</th></tr>';
	for(i=0;i<inclusiveData.length;i++){
	dynamicGrid+="<tr><td style='background-color:white;color:black;'><a href='#' id='"+i+"' onclick='markForDeletion(this);' style='text-decoration:none;background-color:white;color:black;'>"+inclusiveData[i]['name']+"</a></td></tr>";
	}
	dynamicGrid+='<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>';
	dynamicCoverage.innerHTML=dynamicGrid;
	document.getElementById('forDeletion').value="";



}

function searchTrainee(trainArray,traineVa){
	var algorithm=false;
	for(i=0;i<trainArray.length;i++){
		if(traineVa==trainArray[i]['id']){
			algorithm=true;
		}
	}
	return algorithm;
}

function validateSubmit(){
	var count=0;

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
	if(inclusiveData.length>0){
		count++;
	}
	
	if(count==3){
		finalPreparation();	
	}
	else {
		alert("Some form details have not been completed.");

	}

}

function finalPreparation(){
	document.getElementById('finalName').value=document.getElementById('training_title').value;
	document.getElementById('finalDivision').value=document.getElementById('division_code').value;
	document.getElementById('finalOther').value=document.getElementById('other_div').value;
	
	var finalCoverage="";
	for(i=0;i<inclusiveData.length;i++){
		finalCoverage+=inclusiveData[i]['id']+"="+inclusiveData[i]['name']+"&&";	

	}	
	

	document.getElementById('finalCategory').value=finalCoverage;
	
	document.forms["program_submit"].submit();		
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
	inclusiveData.splice(indexDeletion,1);
	inclusivecount--;
	fillDynamic();


}
function searchCoverage(searchkey){

	document.getElementById('searchNewCoverage').innerHTML="";
	if(searchkey.length>3){
	document.getElementById('pageNumber').value="";

	var searchWord=searchkey;

	if(searchWord==""){
		searchWord="xxxxxxxxxxxxxx";
	}
	searchCoverageGrid.length=0;

	for(i=1;i<(coverageCount);i++){
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
	document.getElementById('pageNumber').value=item;
	iterate(item*1,"index");
	document.getElementById('searchNewCoverage').innerHTML="";
}
//ezspycam.com
</script>



	<table width=100% align=center >
	<tr><td>
	<table align=center style='border: 1px solid gray'>
		<tr><th  style="background-color:#ed5214;color:white;" colspan=2>Add New Program</th></tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">
			Title of New Training Course
			</td>
			<td style="background-color: white; color:black;">
			<input type=text id='training_title' name='training_title' size=30 />
			</td>
		</tr>	
		<tr>
			<td style="background-color: #00cc66;color: black;">
			Division
			</td>
			<td style="background-color: white; color:black;">
				<select id='division_code' name='division_code'>
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
					<option value='OTH'>OTHER</option>

				
				</select>
			</td>
		</tr>
		<tr>
			<td style="background-color: #00cc66;color: black;">
			Division (If Other)
			</td>
			<td style="background-color: white; color:black;">
				<textarea name='other_div' id='other_div' style='width:100%;' placeholder='<?php echo $placemessage;?>' rows=4></textarea>
			</td>
		</tr>	
		<tr><td style=" background-color: #ed5214;" colspan=2><input type="hidden" id="forDeletion" name="forDeletion" /></td></tr>

		</table>
	</td>
	<td colspan=2>&nbsp;
	</td>
	</tr>
	<tr>
	<td style='vertical-align:top;'>
	<table id='dynamicCoverage' align=center  width=400>
	<tr>
	<th style="background-color: #00cc66;color: black;">
		Coverage
	</th>
	</tr>
	<tr><td style=" background-color: #ed5214;" colspan=2></td></tr>
	</table>
	</td>
	<td align=center vertical-align=center>
	<input type=button value='<< - Entry' onclick='includeCoverage();' />
	<br><br>
	<input type=button value='>> - Remove' onclick='removeCoverage();' />
	</td>
	<td style='vertical-align:top;'>
	<table id='coverageTable'  align=center  width=400>
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
		<tr><td style=" background-color: #ed5214;" colspan=2 id='searchNewCoverage'></td></tr>
		<tr>
			<td colspan=2 style='background-color: #ed5214;'>
			<a href='#' style='color:white;text-decoration: none;' onclick="iterate(document.getElementById('pageNumber').value,'prev');"><<</a>
			<input id='pageNumber' name='pageNumber' style="text-align:center" type="text" name="pageNumber" size=4 value='1' onkeyup="iterate(document.getElementById('pageNumber').value,'index');" />	
			<a href='#' style='color:white;text-decoration: none;'  onclick="iterate(document.getElementById('pageNumber').value,'next');">>></a>
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

			<textarea name='newCoverage' id='newCoverage' cols=45 disabled onkeyup="searchCoverage(this.value)" ></textarea>
			</td>
			</tr>	
	</table>
	</td>
	</tr>
	<tr>
	<td>
	<form id='program_submit' action="training_database.php?pp=PR&action=add" method="post">

	<table width=400 align=center style='border: 1px solid gray'>
	<tr><td align=center style=" background-color: #ed5214;" colspan=2><input type=button value='Submit' onclick='validateSubmit();' />
		<input type="hidden" id='finalName' name='finalName' />
		<input type="hidden" id='finalDivision' name='finalDivision' />
		<input type="hidden" id='finalOther' name='finalOther' />

		<input type="hidden" id='finalCategory' name='finalCategory' cols=50 />
			
	</td>

	</tr>
	</table>
	</form>
	</td>
	<td colspan=2>&nbsp;</td>
	</tr>


	</table>


</form>