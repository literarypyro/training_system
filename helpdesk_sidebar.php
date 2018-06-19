<?php
//background-color:#66ceae;
?>
<?php
$db=new mysqli("localhost","root","","training");
$sql="select * from login where username='".$_SESSION['username']."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();
$accountType=$row['account'];

?>
<td width="15%" height="100%" align=center valign="top"  style="background-color:#66ceae;border-left-style: solid; border-left-width: 1px; border-left-color:black; border-right-style: none; border-right-width: 1px; border-top-style: none; border-top-width: 1px; border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color:black; border-color:#FF600;" bordercolor="#FF6600">
<table width=100% >
<tr>
<td>
<div id="menuh" align=left>
<ul id=""><li><a <?php if($_SESSION['training_page']=="index.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="home_page.php">Home</a></li>
</ul>
<?php
if(($accountType=="Administrator")||($accountType=="Hybrid")){
?>

<ul>



<li><a <?php if($_SESSION['training_page']=="training_database.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="#">Training Database</a><ul>
<li><a href="#">Add New Entry</a>
<ul><li><a href="training_database.php?pp=PR">Training Program</a></li><li><a href="training_database.php?pp=1">Participant</a></li><li><a href="training_database.php?pp=2">Trainer</a></li><li><a href="training_database.php?pp=3">Login User</a></li></ul></li><li><a href="#">Modify Entry</a><ul><li><a href="training_database.php?pp=mPR">Training Program</a></li><li><a href="training_database.php?pp=m1">Participant</a></li><li><a href="training_database.php?pp=m2">Trainer</a></li><li><a href="training_database.php?pp=m3">Login User</a></li></ul></li></ul></li>

<li><a id="ASSIGN_REQUEST" <?php if($_SESSION['training_page']=="training_event.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="#">Training Events</a><ul><li><a href="training_event.php?a=ZZ">Add Training Schedules</a></li><li><a href="training_event.php?a=Cr">Edit Training Schedules</a></li>
<li><a href="training_event.php?a=md">Edit/Search Available Schedules</a></li></ul>


</ul>

<br>
<ul id="">
<li><a <?php if(($_SESSION['training_page']=="evaluation_form.php")){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?>  href="#">Trainer Evaluation</a>
	<ul>

	<li><a href="training_evaluation.php?a=eval">Generate Form</a>
	</li>
	<li><a href="training_evaluation.php?a=entry">Data Entry</a>
	</li>
	<li><a href="training_evaluation.php?a=report">Trainer Evaluation Report</a>
	</li>

	<li><a href="training_evaluation.php?a=stdt">Trainee Evaluation Form</a>
	</li>
	
	</ul>
</li>
</ul>

<br>

<ul id="">
<li><a id="FOR_PRINTOUT" <?php if($_SESSION['training_page']=="trainer_report.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="trainer_report.php">Monthly/Yearly Report</a></li>





<li><a <?php if(($_SESSION['training_page']=="search_schedule2.php")||($_SESSION['training_page']=="search_schedule.php")){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="#">Participant Information/Report</a><ul>
<li><a href="training_cert.php?a=srch2">Search By Participant</a></li>
<li><a href="training_cert.php?a=srch">Search By Training Event</a></li>
</ul>	
</li>
</ul>
<br>


<ul id="">
<li><a  <?php if($_SESSION['training_page']=="record_attendance.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="trainer_aid.php?a=record">Record Attendance</a>
</ul>
<ul>
		<li><a  <?php if($_SESSION['training_page']=="memo_generate.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?>  href="memo_generate.php?generate=complete_report2">Generate Attendance<br>Report</a></li>
</ul>		
<ul>
		<li><a  <?php if($_SESSION['training_page']=="remarks_attendance.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?>  href="remarks_attendance.php">Add Attendance Remarks</a></li>
</ul>	
<br>
<ul id="">
<li><a <?php if($_SESSION['training_page']=="trainer_aid.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="trainer_aid.php?a=diploma">Generate Diploma/Certification</a>
</li>
</ul>

<ul id="">
<li><a <?php if(($_SESSION['training_page']=="diploma_template.php")||($_SESSION['training_page']=="certification_template.php")){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="#">Modify Template</a>
<ul>
	<li><a href="diploma_template.php">Training Certificate/Diploma</a>
	<li><a href="certification_template.php">Certification</a>
</ul>
</li>
</ul>

<?php
}
if(($accountType=="Trainer")||($accountType=="Hybrid")){
	if($accountType=="Hybrid"){
?>
	<hr />
<?php
}
?>
<ul>
<li><a <?php if(($_SESSION['training_page']=="trainer_aid.php")||($_SESSION['training_page']=="memo_generate.php")){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="#">Trainer Aid (Forms and Data Entry)</a>
	<ul>
		<li><a href="trainer_aid.php?a=memo">Memo to Attend/<br>Completion</a></li>
		<li><a href="trainer_aid.php?a=train">Request for Train<br> Availability</a></li>	
		<li><a href="memo_generate.php?generate=complete_report">Report of Completed<br>Training</a></li>	
	</ul>
</li>
</ul>
<br>
<ul id="">
<li><a <?php if(($_SESSION['training_page']=="evaluation_form.php")){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?>  href="#">Trainer Evaluation</a>
	<ul>

	<li><a href="training_evaluation.php?a=eval">Generate Form</a>
	</li>
	<li><a href="training_evaluation.php?a=entry">Data Entry</a>
	</li>
	<li><a href="training_evaluation.php?a=report">Trainer Evaluation Report</a>
	</li>

	<li><a href="training_evaluation.php?a=stdt">Trainee Evaluation Form</a>
	</li>
	
	</ul>
</li>
</ul>



<br>

<ul id="">

<li><a id="ASSIGN_REQUEST" <?php if($_SESSION['training_page']=="training_event.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="#">Training Events</a><ul><li><a href="training_event.php?a=Cr">Manage Training Schedules</a></li>
<li><a href="training_event.php?a=md">Search Available Schedules</a></li></ul>
<li><a  <?php if($_SESSION['training_page']=="hourly_schedule.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?>  href="trainer_aid.php?a=hourly">Hourly Scheduler</a></li>
<li><a <?php if($_SESSION['training_page']=="training_plan_outline.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="#">Training Plan</a>
	<ul>
		<li><a href="training_plan_outline.php?a=objective">Objectives</a></li>
		<li><a href="training_plan_outline.php?a=outline">Course Outline</a></li>	
		<li><a href="training_plan_outline.php?a=schedule">Schedule</a></li>	
		<li><a href="training_plan_outline.php?a=materials">Materials/Facilities</a></li>	
		<li><a href="training_plan_outline.php?a=printout">Generate Printout</a></li>	

		</ul>
	</li>



<?php //if($_SESSION['training_page']=="training_plan.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> 

</ul>

<br>

<ul id="">
<li><a id="CLIENT_REPORT" <?php if($_SESSION['training_page']=="monthly_report.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="monthly_report.php">Trainer Report</a></li>
</ul>

<?php

}
?>

</div>
</td>
</td>
</table>
</td>






<?php


if($a=="truncated"){

?>

<ul id="">
<li><a <?php if(($_SESSION['training_page']=="evaluation_form.php")){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?>  href="#">Trainer Evaluation</a>
	<ul>

	<li><a href="training_evaluation.php?a=eval">Generate Form</a>
	</li>
	<li><a href="training_evaluation.php?a=entry">Data Entry</a>
	</li>
	<li><a href="training_evaluation.php?a=report">Trainer Evaluation Report</a>
	</li>

	<li><a href="training_evaluation.php?a=stdt">Trainee Evaluation Form</a>
	</li>
	
	</ul>
</li>
</ul>

<br>



<li>
<li><a id="CERTIFICATION" <?php if($_SESSION['training_page']=="training_cert.php"){ echo "class='active'"; echo " style='color: black;background-color: #ed5214;'"; } ?> href="#">Diploma/Certification</a>
<ul>
<li><a href="training_cert.php?a=dss">Diploma Release</a></li><li><a href="training_cert.php?a=cr">Certification Request</a></li>
<li><a href="training_cert.php?a=css">Certification Release</a></li>	
</ul>
</li>

<?php
}
?>



