<?php
if(isset($_GET['trainingID'])){
	$db=new mysqli("localhost","root","","training_system");
	$sql="select * from training_instance where id='".$_GET['trainingID']."'";
	$rs=$db->query($sql);	
	
	
	
	
}

?>