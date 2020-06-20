<?php
	Include_once("include/functions.php");
	$functions = New Functions();
	//print_r($_GET);
	if(!$loggedInUserDetailsArr = $functions->sessionExists()){
		header("location: login.php");
		exit;
	}
	else{
		if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
			$product_id = $functions->escape_string($functions->strip_all($_GET['product_id']));
			$prodType = '';
			if(isset($_GET['prodType']) && !empty($_GET['prodType'])){
				$prodType = $functions->escape_string($functions->strip_all($_GET['prodType']));
			}

			$query = "select * from ".PREFIX."customers_wishlist where product_id='".$product_id."' and customer_id='".$loggedInUserDetailsArr['id']."'";
			$result = $functions->query($query);
			$str = "";
			if($functions->num_rows($result)>0){
				$str .= "Product Already Added to Wishlist";
				//$str .= "";
			}else{
				$query = "insert into ".PREFIX."customers_wishlist(customer_id, product_id , product_type) values ('".$loggedInUserDetailsArr['id']."', '".$product_id."', '".$prodType."')";
				$functions->query($query);
				//$str .= "<i class='fa fa-heart-o'></i> Added to wishlist";
				$str .= "Added to wishlist";
			}
			
			$response = $str;
			//echo $response;
			echo json_encode($response);
		}
	}

?>