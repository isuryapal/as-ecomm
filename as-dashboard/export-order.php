<?php	
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	
	$tableName = 'order_details';
	
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
	header("Content-Disposition: attachment; filename=download_orders.xls"); 
	header("Pragma: no-cache"); 
	header("Expires: 0");  
	
	if(isset($_REQUEST['success'])){
		if(isset($_GET['customerId']) && !empty($_GET['customerId'])) {
			$customerId = $admin->escape_string(($admin->strip_all($_GET['customerId'])));
			$customerDetails = $admin->getUniqueCustomersById($customerId);
			$orderTypeWhereArr[] = " customer_id='".$customerId."'";
		}
		if(isset($_GET['fromDate']) && !empty($_GET['fromDate']) && isset($_GET['toDate']) && !empty($_GET['toDate'])){
			$fromDate = trim($admin->escape_string($admin->strip_all($_GET['fromDate'])));
			$toDate = trim($admin->escape_string($admin->strip_all($_GET['toDate'])));
			$fromDateTime = date('Y-m-d', strtotime($fromDate))." 00:00:00";
			$toDateTime = date('Y-m-d', strtotime($toDate))." 23:59:59";
			$orderTypeWhereArr[] = "(created between '".$fromDateTime."' and '".$toDateTime."')";
		}
		if(isset($_GET['search_name']) && !empty($_GET['search_name'])){
			$search_name = trim($admin->escape_string($admin->strip_all($_GET['search_name'])));
			$orderTypeWhereArr[] = " txn_id like '%".$search_name."%' or shipping_fname like '%".$search_name."%' or billing_fname like '%".$search_name."%'";
		}
		if(isset($orderTypeWhereArr) and count($orderTypeWhereArr)>0){
			$orderTypeWhereStr = implode(" and ", $orderTypeWhereArr);
			if(!empty($orderTypeWhereStr)){
				$orderTypeWhereStr = ' and '.$orderTypeWhereStr;
			} else {
				$orderTypeWhereStr = '';
			}
		} else {
			$orderTypeWhereStr = '';
		}

		$sql = "SELECT * FROM ".PREFIX.$tableName." where id<>0 ".$orderTypeWhereStr." order by id DESC";
		//echo $sql; exit; 
		$results = $admin->query($sql);
		echo "Customer Name \tEmail \tOrder Id \tOrder Date \tAmount \tQuantity \tOrder Status \tPayment Status\n";
		
		
		$str ="";
		while($productDetails = $admin->fetch($results)) {
			$sql="SELECT * FROM ".PREFIX."customers where id='".$productDetails['customer_id']."'";
			$custDetails = $admin->fetch($admin->query($sql));


			$orderSql = "SELECT * FROM ".PREFIX."order where id='".$productDetails['order_id']."' ".$orderTypeWhereStr." order by id DESC";
			$orderDetails = $admin->fetch($admin->query($orderSql));
			if($orderDetails['payment_status']=="Payment Complete"){
				$invoice =  $orderDetails['txn_id']; 
			} else {
				$invoice =  '-';
			} 
		
			echo $admin->escape_string($admin->strip_all($custDetails['first_name']))."\t";
			echo $admin->escape_string($admin->strip_all($custDetails['email']))."\t";
			echo $admin->escape_string($admin->strip_all($orderDetails['txn_id']))."\t";
			echo date('d F, Y H:i:s A', strtotime($orderDetails['created']))."\t";
			echo $admin->getCustomerPurchaseAmount($orderDetails['txn_id'])."\t";
			echo $admin->escape_string($admin->strip_all($productDetails['quantity']))."\t";
			echo $admin->escape_string($admin->strip_all($orderDetails['order_status']))."\t";
			echo $admin->escape_string($admin->strip_all($orderDetails['payment_status']))."\t";
			echo "\n";
		}
	}
?>