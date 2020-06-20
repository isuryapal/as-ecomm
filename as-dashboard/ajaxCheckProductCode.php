<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$user = new AdminFunctions();
	$response = 'false';
	//print_r($_POST);
	if(isset($_POST['product_code']) && !empty($_POST['product_code'])){
		$product_code = $user->escape_string($user->strip_all($_POST['product_code']));
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$id = $user->escape_string($user->strip_all($_POST['id']));
		}else{
			$id='';
		}
		if($user->isProductCodeIsUnique($product_code,$id)){
			$response = 'true';
		} else {
			$response = 'false';
		}
	} else {
		$response = 'false';
	}
	echo $response;
?>