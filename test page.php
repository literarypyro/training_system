<style type='text/css' media='print'>



@media print {
	@page { margin: 0; }
	body { margin-top: 2in; }
	font: 14pt Arial, "Times New Roman", Times, serif;
	line-height: 1.5;
	#header {
	font: 18pt Arial, "Times New Roman", Times, serif;
	
	
	}
}	





@page {
  size: 5.5in 8.5in;
}


@page {
  size: A4 portrait;
}

@page :left {
  margin-left: 2cm;
	margin-right: 2cm;
}

@page :right {
  margin-left: 2cm;
	margin-right: 2cm;

 }

@page :first {

}

@page :blank {
  @top-center { content: "This page is intentionally left blank." }
}


</style>
<style type='text/css' media='screen'>

@media screen {
	p {
		margin-left:2cm;
			
		margin-right:2cm;
	}
}
</style>


<?php
require("db_page.php");
			$db=retrieveTrainingDb();	

	$nID=$_GET['D'];
				
	$ret="select * from trainee_list where id='".$nID."'";
	$retRs=$db->query($ret);
	$retRow=$retRs->fetch_assoc();

				
				
	$sql="SELECT * FROM training_instance inner join class_instance on class_instance.training_event_id=training_instance.id where class_instance.traineeId in				
	(select id from trainee_list where lastName='".$retRow['lastName']."' and firstName='".$retRow['firstName']."')	";

?>

<body onclick='window.print()'>
<div id='header' align=center><strong><u>C E R T I F I C A T I O N</u></strong></div>
<br>
<br>
<p>This is to certify that <strong>MR. <?php echo strtoupper($retRow['firstName']." ".$retRow['lastName']); ?></strong> of the Transport Division attended
the training courses conducted by the Support Staff/Computer Section/AFCS Office at the MRT3 Depot, North Ave., corner EDSA, North Triangle
Area, Quezon City:</p>

	<table style="border:none;" width="100%">
	<?php
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				
				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();

					$sDate=date("F d, Y",strtotime($row['start_date']));
					$eDate=date("F d, Y",strtotime($row['end_date']));
					
					
					if($sDate==$eDate){
						$period=$sDate;
					}
					else {
						
						$mm1=date("m",strtotime($sDate));
						$mm2=date("m",strtotime($eDate));
						if($mm1==$mm2){
							$part1=date("F d",strtotime($sDate));
							$part2=date("-d, Y",strtotime($eDate));
							
							$period=$part1.$part2;
						}
						else {
							$part1=date("F d",strtotime($sDate));
							$part2=date("-F d, Y",strtotime($eDate));
							$period=$part1.$part2;
							
						}
						
					}
					
	?>
	
	<tr>
	<td width=15%>
	&nbsp;
	</td>
	<td width=30% align=left>
	
	<?php echo $period; ?>
	
	</td>
	<td align=center width=10%>-</td>
	<td width=45%><?php echo $row['training_title']; ?></td>
	</tr>
	<?php
				}
	
	?>



	</table>

<p>This certification is being issued upon the request of Mr. <?php echo $retRow['lastName']; ?> in connection with his application for
Senior Transportation Development Officer (STDO) position</p>

<p>Done this 28th day of September 2017.</p>

<br>
<br>
<br>
<table style="border:none;" width=100%>
<tr>
<td width=50% align=center>
<strong>OFELIA D. ASTRERA</strong><br>
Chief, Support Staff/Computer Section/AFCS Office

</td>
<td width=50% align=center>
<strong>LUIS A. SAMAN, JR.</strong><br>
Supervising Transportation Development Officer

</td>

</tr>
</table>
</body>