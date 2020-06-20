<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	// $pageName = "Product Types";
	// $pageURL = 'product-types-delete.php';
	$parentPageURL = 'subSub-subSubCategory-master.php';
	$subsubsubcate_id = $admin->escape_string($admin->strip_all($_GET['subsubsubcate_id']));
	$subsubcate_id = $admin->escape_string($admin->strip_all($_GET['subsubcate_id']));
	$category_id = $admin->escape_string($admin->strip_all($_GET['category_id']));
	$subcate_id = $admin->escape_string($admin->strip_all($_GET['subcate_id']));

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	$admin->checkUserPermissions('category_delete',$loggedInUserDetailsArr);
	if(isset($_GET['id'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		if(!isset($id) || empty($id)){
			header("location:".$parentPageURL."?deletefail&subsubsubcate_id=".$subsubsubcate_id."&subsubcate_id=".$subsubcate_id."&subcate_id=".$subcate_id."&category_id=".$category_id);
			exit;
		}

		//delete from database
		$result = $admin->deletesubSubSubSubCategory($id);
		header("location:".$parentPageURL."?deletesuccess&subsubsubcate_id=".$subsubsubcate_id."&subsubcate_id=".$subsubcate_id."&subcate_id=".$subcate_id."&category_id=".$category_id);
	}
?>