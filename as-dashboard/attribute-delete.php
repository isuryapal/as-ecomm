<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	
	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}

	$parentPageURL = 'attribute-master.php';
	if(isset($_GET['attribute_id'])){
		//$admin->checkUserPermissions('attribute_delete',$loggedInUserDetailsArr);
		$id = $admin->escape_string($admin->strip_all($_GET['attribute_id']));
	}

	if(!isset($id) || empty($id)){
		header("location:".$parentPageURL."?deletefail");
		exit;
	}

	//delete from database
	$result = $admin->deleteAttributeById($id);
	header("location:".$parentPageURL."?deletesuccess");
	exit;
?>