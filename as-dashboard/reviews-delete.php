<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$parentPageURL = 'reviews.php';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	//$admin->checkUserPermissions('review_delete',$loggedInUserDetailsArr);
	// print_r($_GET);die();
	if(isset($_GET['id'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$product_id = $admin->escape_string($admin->strip_all($_GET['productId']));
		if(!isset($id) || empty($id)){
			if(isset($_GET['page']) && !empty($_GET['page'])){
				header("location:".$parentPageURL."?deletefail&page=".$_GET['page']);	
			}else{
				header("location:".$parentPageURL."?deletefail");
			}
			
			exit;
		}

		//delete from database
		//$data = $admin->getUniqueReviewById($id);
		$result = $admin->deleteReview($id);
		$data = $admin->getUniqueReviewById($id);
		// print_r($data);die();
		$query = "select AVG(rating) as rating_all from ".PREFIX."reviews where product_id='".$_GET['productId']."' and active='Yes'";
		
		$sql = $admin->query($query);
		$results =$admin->fetch($sql);
		
		if(empty($results['rating_all'])){
			$query_avg = "update ".PREFIX."product_master set avg_rating='".$results['rating_all']."' where id='".$_GET['productId']."'";
		}
		$admin->query($query_avg);
		
		if(isset($_GET['page']) && !empty($_GET['page'])){
			header("location:".$parentPageURL."?deletesuccess&page=".$_GET['page']);
		}else{
			header("location:".$parentPageURL."?deletesuccess");
		}
		exit;
	}
?>