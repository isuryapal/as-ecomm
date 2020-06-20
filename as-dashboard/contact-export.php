<?php	
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	
	$tableName 	= 'contact_us';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}	
	
	// header("Content-type: text/x-csv");
	// header('Content-Disposition: attachment; filename=data.csv');
	header("Content-Type: application/vnd.ms-excel"); 
	header("Content-type: application/octet-stream");
	// header('Content-Type: image/jpeg');
	header("Content-Disposition: attachment; filename=".time()."-download_contact.xls"); 
	header("Pragma: no-cache"); 
	header("Expires: 0");	
	
	if(isset($_REQUEST['success'])){
		$sql="SELECT * FROM ".PREFIX.$tableName." order by created Desc";
		$results = $admin->query($sql);
		
		echo "Name  \tEmail \tContact \tAddress \tFeedback \tContact Date  \n";
		
		
		$str ="";
		while($enquireyData = $admin->fetch($results)) {

			echo $admin->escape_string($admin->strip_all($enquireyData['name']))."\t";
			echo $admin->escape_string($admin->strip_all($enquireyData['email']))."\t";
			echo $admin->escape_string($admin->strip_all($enquireyData['mobile']))."\t";
			echo $admin->escape_string($admin->strip_all($enquireyData['address']))."\t";
			echo $admin->escape_string($admin->strip_all($enquireyData['feedback']))."\t";
			echo $admin->escape_string($admin->strip_all(date('d-m-Y',strtotime($enquireyData['created']))))."\t";
			echo "\n";
		}
	}
?>