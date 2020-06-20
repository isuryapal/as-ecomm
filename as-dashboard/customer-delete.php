<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$customerPageURL = 'customers.php';
	$wholesalerPageURL = 'wholesaler.php';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	//print_r($_GET); exit;
	if(isset($_GET['id'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$editedby = $admin->escape_string($admin->strip_all($_GET['editedby']));
		if(!isset($id) || empty($id)){
			if(isset($_GET['page']) && !empty($_GET['page'])){
				header("location:".$customerPageURL."?deletefail&page=".$_GET['page']."&cType=".$_GET['cType']);
			}else{
				header("location:".$customerPageURL."?deletefail&cType=".$_GET['cType']);
			}	
			exit;
		}

		//delete from database
		$result = $admin->deleteCustomer($id,$editedby);
		if(isset($_GET['page']) && !empty($_GET['page'])){
			header("location:".$customerPageURL."?deletesuccess&page=".$_GET['page']."&cType=".$_GET['cType']);
		}else{
			header("location:".$customerPageURL."?deletesuccess&cType=".$_GET['cType']);
		}
	}
	if(isset($_GET['wid'])){
		$id = $admin->escape_string($admin->strip_all($_GET['wid']));
		if(!isset($id) || empty($id)){
			if(isset($_GET['page']) && !empty($_GET['page'])){ 
				header("location:".$wholesalerPageURL."?deletefail&page=".$_GET['page']);
			}else{
				header("location:".$wholesalerPageURL."?deletefail");
			}	
			exit;
		}

		//delete from database
		$result = $admin->deleteCustomer($id);
		if(isset($_GET['page']) && !empty($_GET['page'])){ 
			header("location:".$wholesalerPageURL."?deletesuccess&page=".$_GET['page']);
		}else{
				header("location:".$wholesalerPageURL."?deletefail");
			}
	}
?>