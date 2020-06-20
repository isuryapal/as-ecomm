<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	//print_r($_GET);
	if(isset($_POST['state']) && !empty($_POST['state'])){
			$state_id = $admin->escape_string($admin->strip_all($_POST['state']));
		$citiesSQL = $admin->query("SELECT * FROM ".PREFIX."cities WHERE state_id='".$state_id."'");
		$selectContent = '';
		$selectContent .= '<option value="">Select City</option>';
		
			
		while($cities = $admin->fetch($citiesSQL)) {
			
			$selectContent .= '<option value="'.$cities['id'].'">'.$cities['name'].'</option>';
			
		}
		$ajaxResponse = array();
		$ajaxResponse['status'] = 1;
		$ajaxResponse['selectContent'] = $selectContent;
		echo json_encode($selectContent);
	}
?>