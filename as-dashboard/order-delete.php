<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	// $pageName = "Product Types";
	// $pageURL = 'product-types-delete.php';
	$parentPageURL = 'order.php';
	$urlParamArr = array();
	$urlParamStr = '';
	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	$admin->checkUserPermissions('customer_order_delete',$loggedInUserDetailsArr);
	
	if(isset($_GET['type']) && !empty($_GET['type'])){
		$orderType = $admin->escape_string(($admin->strip_all($_GET['type'])));
		$urlParamArr[] = 'type='.$orderType;
	}
	if(count($urlParamArr)>0){
		$urlParamStr = implode("&", $urlParamArr);
		$parentPageURL .= '?'.$urlParamStr;
	}

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	if(isset($_GET['txnId'])){
		$txnId = $admin->escape_string($admin->strip_all($_GET['txnId']));
		if(!isset($txnId) || empty($txnId)){
			header("location:".$parentPageURL."&deletefail");
			exit;
		}
		//delete from database
		// $result = $admin->deleteProductOrder($txnId);

		$query = "update ".PREFIX."order set is_deleted='1' where txn_id='".$txnId."'";
		$admin->query($query);

		header("location:".$parentPageURL."?deletesuccess");
		exit;
	} else {
		header("location:".$parentPageURL."?deletefail");
		exit;
	}

	/* 
	function deleteProductOrder($txnId, $admin) {
		$txnId = $admin->escape_string($admin->strip_all($txnId));
		$query = "update ".PREFIX."order set is_deleted='1' where txn_id='".$txnId."'";
		$admin->query($query);
	} */
?>