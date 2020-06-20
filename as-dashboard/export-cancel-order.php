<?php	
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	
	$tableName = 'refund_request';
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	
	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}	
	
	// header("Content-type: text/x-csv");
	// header('Content-Disposition: attachment; filename=data.csv');
	header("Content-Type: application/vnd.ms-excel"); 
	header("Content-type: application/octet-stream");
	// header('Content-Type: image/jpeg');
	header("Content-Disposition: attachment; filename=download_cancel_orders.xls"); 
	header("Pragma: no-cache"); 
	header("Expires: 0");  
	
	if(isset($_REQUEST['success'])){
		// if(isset($_GET['ordre_id']) && !empty($_GET['ordre_id'])) {
		// 	$orderId = $admin->escape_string(($admin->strip_all($_GET['ordre_id'])));
		// 	$orderDetails = $admin->getOrderbyId($orderId);
		// 	//$orderTypeWhereArr[] = " ordre_id='".$orderId."'";
		// }

		$sql = "SELECT * FROM ".PREFIX.$tableName."";
		//echo $sql; exit; 
		$results = $admin->query($sql);
		// $row = $admin->fetch($results);
		echo "Order Txn. No \tImage \tProduct Name \tPrice \tCancel Reason \tRequest Date \tAction\n";
		
		
		$str ="";
		while($row = $admin->fetch($results)) {
			$order = $admin->getOrderbyId($row['ordre_id']);
			$orderDetils = $admin->getOrderDetailsbyId($row['order_detail_pal']);
			$productDetails = $admin->getUniqueProductById($orderDetils['product_id']);
			$image_name = strtolower(pathinfo($productDetails['main_image'], PATHINFO_FILENAME));
			$image_ext = strtolower(pathinfo($productDetails['main_image'], PATHINFO_EXTENSION));
			if(!empty($image_name) && !empty($image_ext)){
				$imageUrl = BASE_URL."/images/products/".$image_name.'_crop.'.$image_ext;	
			}else{
				$imageUrl = BASE_URL."/images/products/no_img.jpg";
			}
			$unitPrice = $orderDetils['customer_price'];
			$unitDiscountedPrice = $orderDetils['customer_discount_price'];
			$quantity = $orderDetils['quantity'];
			if(!empty($unitDiscountedPrice)){
				$totalPrice = $quantity * $unitDiscountedPrice;
				$totalPriceMsg = 'Rs. '.$unitDiscountedPrice.' x '.$quantity.' unit';
				$displayPrice = $unitDiscountedPrice;
			} else {
				$totalPrice = $quantity * $unitPrice;
				$totalPriceMsg = 'Rs. '.$unitPrice.' x '.$quantity.' unit';
				$displayPrice = $unitPrice;
			}
			if($orderDetils['payment_discount']>0) {
				$paymentDiscountAmount = $totalPrice*($orderDetils['payment_discount']/100);
				$totalPrice = $totalPrice - $paymentDiscountAmount;
			}
		
			echo $admin->escape_string($admin->strip_all($order['txn_id']))."\t";
			echo $imageUrl."\t";
			echo $admin->escape_string($admin->strip_all($productDetails['product_name']))."\t";
			echo $admin->escape_string($admin->strip_all($totalPrice))."\t";
			echo $admin->escape_string($admin->strip_all($row['refund_in']))."\t";
			echo date('d /m /Y H:i:s',strtotime($row['created']))."\t";
			echo $admin->escape_string($admin->strip_all($orderDetils['refund_status']))."\t";
			echo "\n";
		}
	}
?>