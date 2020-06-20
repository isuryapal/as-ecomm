<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}

	$parentPageURL = 'sub-category-master.php';

	$cat_id = $admin->escape_string($admin->strip_all($_GET['cat_id']));

	if(isset($_GET['sub_category_id'])){
		$admin->checkUserPermissions('sub_category_delete',$loggedInUserDetailsArr);
		$id = $admin->escape_string($admin->strip_all($_GET['sub_category_id']));
		
	}

	if(!isset($id) || empty($id)){
		header("location:".$parentPageURL."?deletefail&cat_id=".$cat_id);
		exit;
	}

	//delete from database
	$result = $admin->deleteSubCategoryById($id);
	header("location:".$parentPageURL."?deletesuccess&cat_id=".$cat_id);
	exit;
?>