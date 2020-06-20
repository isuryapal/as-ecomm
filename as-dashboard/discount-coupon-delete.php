<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	// $pageName = "Product Types";
	// $pageURL = 'product-types-delete.php';
	$parentPageURL = 'discount-coupon-master.php';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	if(isset($_GET['id'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		if(!isset($id) || empty($id)){
			if(isset($_GET['page']) && !empty($_GET['page'])){
				header("location:".$parentPageURL."?deletefail&page=".$_GET['page']);
			}else{
				header("location:".$parentPageURL."?deletefail");
			}
			exit;
		}
		//delete from database
		$result = $admin->deleteDiscountCoupon($id);
		if(isset($_GET['page']) && !empty($_GET['page'])){
			header("location:".$parentPageURL."?deletesuccess&page=".$_GET['page']);
		}else{
			header("location:".$parentPageURL."?deletesuccess");
		}
	}
?>