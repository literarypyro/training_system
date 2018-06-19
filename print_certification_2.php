<style type='text/css' media='print'>



@media print {
	@page { margin: 0; }
	body { margin-top: 2in; 
	text-align:justify;
	font-size: 14pt;
	font-family: "Segoe UI";
		table {
			
			font-size: 14pt;
			font-family: "Segoe UI";
			
		}
	}


	
	table {
	font-size: 14pt;
	font-family: "Segoe UI";
	}
	#header {
	font-size: 14pt;
	font-family: "Segoe UI";
	}

	
}	





@page {
  size: 5.5in 8.5in;
}


@page {
  size: A4 portrait;
}

@page :left {
  margin-left: 1in;
	margin-right: 1in;
}

@page :right {
  margin-left: 1in;
	margin-right: 1in;

 }

@page :first {

}

@page :blank {
  @top-center { content: "This page is intentionally left blank." }
}

body {
	font-size: 14pt;
	font-family: "Segoe UI";
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

	$nID=$_POST['cert_id'];
				
	$ret="select * from trainee_list where id='".$nID."'";
	$retRs=$db->query($ret);
	$retRow=$retRs->fetch_assoc();

	$from_date=date("Y-m-d",strtotime($_POST['cert_from_yr']."-".($_POST['cert_from_mo']*1))."-1 day");
	$to_date=date("Y-m-d",strtotime($_POST['cert_to_yr']."-".($_POST['cert_to_mo']*1))."+1 day");
				
	$sql="SELECT * FROM training_instance inner join class_instance on class_instance.training_event_id=training_instance.id where class_instance.traineeId in				
	(select id from trainee_list where lastName='".$retRow['lastName']."' and firstName='".$retRow['firstName']."') and start_date>'".$from_date."' and end_date<'".$to_date."'";
	?>

<body onclick='window.print()'>
<div id='header' align=center><strong><u>C E R T I F I C A T I O N</u></strong></div>
<br>
<p>This is to certify that <strong><?php echo strtoupper($_POST['gender']); ?> <?php echo strtoupper($retRow['firstName']." ".$retRow['midInitial']." ".$retRow['lastName']); ?></strong> of the <?php echo $_POST['cert_division']; ?> attended
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
	<td width=10%>
	&nbsp;
	</td>
	<td width=30% align=left>
	
	<?php echo $period; ?>
	
	</td>
	<td align=center width=10%>-</td>
	<td width=50%><?php echo ucwords(strtolower($row['training_title'])); ?></td>
	</tr>
	<?php



		$training_program_id=$row['training_event_id'];


		$timestamp=date("Y-m-d H:i:s");			
		$certsql="insert into diploma_release(trainee_id,training_program_id,status,release_date,type) values ('".$nID."','".$training_program_id."','Issuance','".$timestamp."','certification')";
		$certrs=$db->query($certsql);
		//$diploma_id=$db->insert_id;
		$certsql="insert into diploma_release(trainee_id,training_program_id,status,release_date,type) values ('".$nID."','".$training_program_id."','Issuance','".$timestamp."','diploma')";
		$certrs=$db->query($certsql);
			
			
		$ccsql="insert into certificate_request(status, remarks,trainee_id,training_program_id) values ('Claimed','Claimed: ".$timestamp."','".$nID."','".$training_program_id."'";

		$ccrs=$db->query($ccsql);			
















	}
	
	?>



	</table>

<p>This certification is being issued upon the request of <?php echo ucwords($_POST['gender']); ?> <?php echo $retRow['lastName']; ?> in connection with <?php echo $_POST['purpose']; ?>.</p>



<p>Done this <?php echo date("jS")." day of ".date("M Y"); ?>.</p>

<br>
<br>
<table style="border:none;" width=100% >
<tr>
<td >
<strong>OFELIA D. ASTRERA</strong><br>
Chief, Support Staff/Computer Section/AFCS Office

</td>

</tr>
</table>
</body>
<?php
//		$postDate=$_GET['issuanceDate'];

//		$training_program_id=$_GET['event_id'];
//		$trainee_id=$_GET['trainee'];
//		$release_date=$postDate;
	
//		$sql="select * from diploma_release where trainee_id='".$_GET['trainee']."' and training_program_id='".$_GET['event_id']."' and type='diploma'";
//		$rs=$db->query($sql);	
//		$nm=$rs->num_rows;
		
//		if($nm>0){
//			$status="Re-Issuance";
//		}
//		else {
//			$status="Issuance";
		
		
//		}

//		$hour=date("H");
//		$minute=date("i");
//		$second=date("s");
			
			

?>