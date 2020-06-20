<?php
	Include_once("include/functions.php");
    $functions = New Functions();
    
	$response = 'false';
	//print_r($_POST);
	if(isset($_POST['product_code']) && !empty($_POST['product_code'])){
		$product_code = $functions->escape_string($functions->strip_all($_POST['product_code']));
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$id = $functions->escape_string($functions->strip_all($_POST['id']));
		}else{
			$id='';
		}
		if($functions->isProductCodeIsUnique($product_code,$id)){
			$response = 'true';
		} else {
			$response = 'false';
		}
	} else {
		$response = 'false';
	}
	echo $response;
?>