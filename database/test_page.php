<script language='javascript'>
var eventCounter=0;

function iterateTraining(indexNum,action){
	



}
function processProfile(searchkey){

	var xmlHttp;
	var courseFactory;
	var objectiveGrid="";
	var descriptionGrid="";

	var profileGrid="";

	document.getElementById('selectedUser').innerHTML="";

//	document.getElementById('description').innerHTML="";		
//	document.getElementById('objectives').innerHTML="";		
			
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

			courseFactory=xmlHttp.responseText;
			

			if(courseFactory=="No profile available"){
			//	alert(wordFactory);
			}
			else {
			
				var terms=courseFactory.split(">>>");
				var count=(terms.length)*1-1;
				for(var n=0;n<count;n++){

					var parts=terms[n].split("<<");
					profileGrid+="<option value='"+parts[0]+"'>"+parts[1]+"</option>";
					
//					objectiveGrid=parts[1];
//					descriptionGrid=parts[0];
				
				}
				

				
				
				document.getElementById('selectedUser').innerHTML=profileGrid;
				//document.getElementById('description').innerHTML=descriptionGrid;		
				//document.getElementById('objectives').innerHTML=objectiveGrid;	
			
			}

		}
	} 
	

	
	xmlHttp.open("GET","processing.php?getProfile="+searchkey,true);
	xmlHttp.send();




}


</script>
<table align=center style=" border: 1px solid gray;" width=90%>
<tr>
<th valign=center style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 ><h1>Record Attendance</h1>
</th>
</tr>
</table>
<br>
<table  align=center>
<tr>
<td colspan=2 width=50%>

<table style=" border: 1px solid gray;" width=400>
<tr>
<th style='background-color:#ed5214;color:white;' colspan=2>Select Training Event</th>
</tr>
<tr>
<td style='background-color: #00cc66;color: black;'>Training Title</td>
<td style="background-color:  white; color:black; border: 1px solid gray;" ><input type=text name='training_title' id='training_title' size=30%  onkeyup="searchEvent(document.getElementById('training_title').value);" ><input type=button value='?'  onclick="searchEvent(document.getElementById('training_title').value);"  /></td>
</tr>
<tr>
<td style='background-color: #00cc66;color: black;'>Batch Number</td>
<td style="background-color:  white; color:black; border: 1px solid gray;" ><input type=text name='batch_no' id='batch_no' size=10%></td>
</tr>

<tr>
<td style='background-color: #00cc66;color: black;'>Period</td>
<td style="background-color:  white; color:black; border: 1px solid gray;" ><textarea name='event_period' id='event_period' cols=30%></textarea></td>
</tr>
		<tr id='searchResults' name='searchResults' >
		<td style='background-color: #ed5214;'></td>
		<td style='background-color: #ed5214;'></td>
		</tr>		
		<tr>
			<td colspan=2 style='background-color: #ed5214;' align=center>
			<a href='#dynamicProgram' style='color:white;text-decoration: none;' onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'prev');"><<</a>
			<input id='trainingPageNumber' style="text-align:center" type="text" name="trainingPageNumber" size=4 value='' onkeyup="iterateTraining(document.getElementById('trainingPageNumber').value,'index');" />	
			<a href='#dynamicProgram' style='color:white;text-decoration: none;'  onclick="iterateTraining(document.getElementById('trainingPageNumber').value,'next');">>></a>
			</td>
	
		</tr>
</table>
<form action='trainer_aid.php?a=record&print=1' method=post >
<table   id='attendanceTable' name='attendanceTable' width=400>
<tr>
<th style='background-color:#ed5214;color:white;'>Attendance Date</th>
</tr>
<tr>
	<td style='background-color: #ed5214;' ></td>
</tr>	
</table>
</form>
</td>
<td width=50% valign=top>
<table  style=" border: 1px solid gray;"  id='attendanceTrainee' name='attendanceTrainee' width=100%>
<tr>
<th style='background-color:#ed5214;color:white;' colspan=2>Attendance</th>
</tr>
<tr >
	<td style='background-color: #ed5214;' colspan=2></td>
</tr>	
</table>


</td>

</tr>
<tr>
	<th valign=center style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 >
	</th>

</tr>
<tr>
<td valign=top id='studentList'>
</td>
<td valign=center align=center id='changeAttendance'>
</td>
<td valign=top id='attendanceDynamic'>
</td>

</tr>

<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align=center id='submitButton'></td>
</tr>
<!--
<tr>
	<th valign=center style="vertical-align:center;background-color:#ed5214;color:white;" colspan=3 >
	</th>
</tr>
-->

</table>
