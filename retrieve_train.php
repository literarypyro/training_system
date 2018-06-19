<?php
$db=new mysqli("localhost","root","","training");

if(isset($_GET['trainer'])){
	$sql="select * from trainer_list";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		
		$trainer[$i]['id']=$row['id'];
		$trainer[$i]['name']=strtoupper($row['lastName']).", ".strtoupper($row['firstName']);
		
		
		
		
	
	}
	

	echo json_encode($trainer);


}

if(isset($_GET['trainee'])){
	$sql="select * from trainee_list";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		
		$trainee[$i]['id']=$row['id'];
		$trainee[$i]['name']=strtoupper($row['lastName']).", ".strtoupper($row['firstName']);
		
		
		
		
	
	}
	

	echo json_encode($trainee);


}


if(isset($_GET['program'])){
	$sql="select * from training_programs";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		
		$program[$i]['id']=$row['id'];
		$program[$i]['title']=strtoupper($row['training_title']).", ".strtoupper($row['division_code']);
		
		
		
		
	
	}
	

	echo json_encode($program);

}






if(isset($_GET['program_list'])){
	
	if(isset($_GET['sked'])){
		$clause="where start_date like ";
	}
	
  	$sql="select * from training_instance order by start_date desc";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();

		$sql2="select * from class_instance where training_event_id='".$row['id']."'";
		$rs2=$db->query($sql2);
			
		$nm2=$rs2->num_rows;
			
		$sql3="select * from training_schedule where event_id='".$row['id']."'";
		$rs3=$db->query($sql3);
			
		$nm3=$rs3->num_rows;
			
		$sql4="select * from trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id where event_id='".$row['id']."'";
		$rs4=$db->query($sql4);
		$nm4=$rs4->num_rows;


		$program[$i]['id']=$row['id'];
		$program[$i]['Training Course']=strtoupper($row['training_title']);
		$program[$i]['Batch No.']=$row['batch_number'];
		$program[$i]['Start Date']=date("F d, Y",strtotime($row['start_date']));
		$program[$i]['End Date']=date("F d, Y",strtotime($row['end_date']));
		
		
		
	
	}
	

	echo json_encode($program);


}

if(isset($_GET['program_list2'])){
	
	if(isset($_GET['sked'])){
		$clause="where start_date like ";
	}
	
	$sql="select *,(select count(*) from class_instance where training_event_id=training_instance.id) as attendees from training_instance order by start_date desc";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();

		$sql2="select * from class_instance where training_event_id='".$row['id']."'";
		$rs2=$db->query($sql2);
			
		$nm2=$rs2->num_rows;
			
		$sql3="select * from training_schedule where event_id='".$row['id']."'";
		$rs3=$db->query($sql3);
			
		$nm3=$rs3->num_rows;
			
		$sql4="select * from trainer_class inner join trainer_list on trainer_class.trainer_id=trainer_list.id where event_id='".$row['id']."'";
		$rs4=$db->query($sql4);
		$nm4=$rs4->num_rows;


		$program[$i]['id']=$row['id'];
		$program[$i]['Training Course']=strtoupper($row['training_title']);
		$program[$i]['Batch Number']=$row['batch_number'];
		$program[$i]['Start Date']=date("F d, Y",strtotime($row['start_date']));
		$program[$i]['End Date']=date("F d, Y",strtotime($row['end_date']));
		$program[$i]['Number of Participants']=$row['attendees'];

		
		
	
	}
	

	echo json_encode($program);


}



?>