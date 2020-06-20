<?php	
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	
	$tableName 	= 'order_details';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}	

	$whereClause = '';
	$fromDateTime ='';
	$toDateTime ='';

	if(isset($_GET['report']) && !empty($_GET['report']) && $_GET['report']=="most_viewed"){
		if(isset($_GET['fromDate']) && !empty($_GET['fromDate'])){
			$fromDate = $admin->escape_string($admin->strip_all($_GET['fromDate']));
			$fromDateTime = date('Y-m-d', strtotime($fromDate))." 00:00:00";
		}

		if(isset($_GET['toDate']) && !empty($_GET['toDate'])){
			$toDate = $admin->escape_string($admin->strip_all($_GET['toDate']));
			$toDateTime = date('Y-m-d', strtotime($toDate))." 23:59:59";
		}
		if(!empty($fromDateTime) && !empty($toDateTime)){
			$whereClause .= "  and created between '$fromDateTime' and '$toDateTime'";
		}

		$viewSql = "SELECT * FROM ".PREFIX."product_views where id<>0 $whereClause order by views DESC";
		// echo $sql;
		$result = $admin->query($viewSql);
	}

	if(isset($_GET['report']) && !empty($_GET['report']) && $_GET['report']=="most_sold"){
		if(isset($_GET['fromDate']) && !empty($_GET['fromDate'])){
			$fromDate = $admin->escape_string($admin->strip_all($_GET['fromDate']));
			$fromDateTime = date('Y-m-d', strtotime($fromDate))." 00:00:00";
		}

		if(isset($_GET['toDate']) && !empty($_GET['toDate'])){
			$toDate = $admin->escape_string($admin->strip_all($_GET['toDate']));
			$toDateTime = date('Y-m-d', strtotime($toDate))." 23:59:59";
		}
		if(!empty($fromDateTime) && !empty($toDateTime)){
			$whereClause .= "  and created between '$fromDateTime' and '$toDateTime'";
		}

		$sql = "SELECT product_id FROM ".PREFIX.$tableName." where id<>0 $whereClause group by product_id desc";
		// echo $sql;
		$result = $admin->query($sql);
	}

	if(isset($_GET['report']) && !empty($_GET['report']) && $_GET['report']=="least_sold"){
		if(isset($_GET['fromDate']) && !empty($_GET['fromDate'])){
			$fromDate = $admin->escape_string($admin->strip_all($_GET['fromDate']));
			$fromDateTime = date('Y-m-d', strtotime($fromDate))." 00:00:00";
		}

		if(isset($_GET['toDate']) && !empty($_GET['toDate'])){
			$toDate = $admin->escape_string($admin->strip_all($_GET['toDate']));
			$toDateTime = date('Y-m-d', strtotime($toDate))." 23:59:59";
		}
		if(!empty($fromDateTime) && !empty($toDateTime)){
			$whereClause .= "  and created between '$fromDateTime' and '$toDateTime'";
		}

		$sql = "SELECT product_id FROM ".PREFIX.$tableName." where id<>0 $whereClause group by product_id asc";
		// echo $sql;
		$result = $admin->query($sql);
	}

	if(isset($_GET['report']) && !empty($_GET['report']) && $_GET['report']=="sales_report"){
		if(isset($_GET['fromDate']) && !empty($_GET['fromDate'])){
			$fromDate = $admin->escape_string($admin->strip_all($_GET['fromDate']));
			$fromDateTime = date('Y-m-d', strtotime($fromDate))." 00:00:00";
		}

		if(isset($_GET['toDate']) && !empty($_GET['toDate'])){
			$toDate = $admin->escape_string($admin->strip_all($_GET['toDate']));
			$toDateTime = date('Y-m-d', strtotime($toDate))." 23:59:59";
		}
		if(!empty($fromDateTime) && !empty($toDateTime)){
			$whereClause .= "  and created between '$fromDateTime' and '$toDateTime'";
		}

		$sql = "SELECT * FROM ".PREFIX."order where id<>0 $whereClause order by id DESC";
		// echo $sql;
		$result = $admin->query($sql);
	}

	header("Content-type: text/x-csv");
	header('Content-Disposition: attachment; filename=data.csv');
	header("Content-Type: application/vnd.ms-excel"); 
	header("Content-type: application/octet-stream");
	header('Content-Type: image/jpeg');
	header("Content-Disposition: attachment; filename=".date('d_D_M_Y-H:i:s')."Customers-Compaint-Report.xls"); 
	header("Pragma: no-cache"); 
	header("Expires: 0");


	//$sql ="SELECT * FROM ".PREFIX."inventory_master order by id DESC";
	//$invetory = $admin->query($sql);
 ?>
<?php if(isset($result) && $_GET['report']=="most_viewed" || $_GET['report']=="most_sold" || $_GET['report']=="least_sold"){ ?>
<table border="1px" style="border-collapse:collapse">
	<thead>
		<tr>
			<th>#</th>
			<th>Product Image URL</th>
			<th>Product Name</th>
			<th>Product Code</th>
			<th>Active</th>
			<th>Created</th>
		</tr>
	</thead>
	<tbody>
		<?php
			if(isset($_GET['report']) && $_GET['report']=="most_viewed"){
				$x = 1;
				if($admin->num_rows($result)>0){
					while($orderRow = $admin->fetch($result)){
						$prosql = "SELECT * FROM ".PREFIX."product_master WHERE id='".$orderRow['product_id']."'";
						$proRes = $admin->query($prosql);
						while($row = $admin->fetch($proRes)){
							$file_name = str_replace('', '-', strtolower( pathinfo($row['main_image'], PATHINFO_FILENAME)));
							$ext = pathinfo($row['main_image'], PATHINFO_EXTENSION);
							if(!empty($row['main_image'])){
								$url =  BASE_URL."/images/products/".$file_name.'_crop.'.$ext;
							}else{
								$url = BASE_URL.'/images/default.jpg';
							}
			?>
					<tr>
						<td><?php echo $x++; ?></td>
						<td><?php echo $url; ?></td>
						<td><?php echo $row['product_name']; ?></td>
						<td><?php echo $row['product_code']; ?></td>
						<td><?php echo $row['active']; ?></td>
						<td><?php echo date('d-m-Y', strtotime($row['created'])); ?></td>
					</tr>
			<?php
						}
					}
				}
			}else{
			?>
			<?php
				$x = 1;
				while($orderRow = $admin->fetch($result)){
					// $countSql = "SELECT count(product_id) as count FROM ".PREFIX.$tableName." WHERE product_id='".$orderRow['product_id']."' $whereClause";
					// $countRes = $admin->query($countSql);
					// $countRow = $admin->fetch($countRes);
					// if($_GET['report']=="most_sold"){
					// 	$num = $countRow['count']>1;
					// }elseif($_GET['report']=="least_sold"){
					// 	$num = $countRow['count']<2;
					// }
					// if($num){
					$prosql = "SELECT * FROM ".PREFIX."product_master WHERE id='".$orderRow['product_id']."'";
					$proRes = $admin->query($prosql);
					while($row = $admin->fetch($proRes)){
						$file_name = str_replace('', '-', strtolower( pathinfo($row['main_image'], PATHINFO_FILENAME)));
						$ext = pathinfo($row['main_image'], PATHINFO_EXTENSION);
						if(!empty($row['main_image'])){
							$url =  BASE_URL."/images/products/".$file_name.'_crop.'.$ext;
						}else{
							$url = BASE_URL.'/images/default.jpg';
						}
			?>
					<tr>
						<td><?php echo $x++; ?></td>
						<td><?php echo $url; ?></td>
						<td><?php echo $row['product_name']; ?></td>
						<td><?php echo $row['product_code']; ?></td>
						<td><?php echo $row['active']; ?></td>
						<td><?php echo date('d-m-Y', strtotime($row['created'])); ?></td>
					</tr>
			<?php
					}
						// }	
				}
			}
			?>
	</tbody>
</table>
	<?php }else{ ?>
	<table border="1px" style="border-collapse:collapse">
	<thead>
		<tr>
			<th>#</th>
			<th width="10%">Customer Name</th>
			<th width="10%">Customer Contact</th>
			<th>Order No.</th>
			<th>Order Date</th>
			<th>Amount</th>
			<th>Order Status</th>
			<th>Payment Status</th>
			<th>Payment Mode</th>
		</tr>
	</thead>
	<tbody>
		<?php
							$x = 1;
							while($row = $admin->fetch($result)){
								$customerDetails = $admin->getUniqueCustomersById($row['customer_id']);
	?>
								<tr>
									<td><?php echo $x++; ?></td>
									<td><?php echo $row['billing_fname']. ' '.$row['billing_lname']; ?></td>
									<td>
										<?php echo $row['shipping_email'];?><br>
										<?php echo $customerDetails['mobile'] ?>
									</td>
									<td>
										<?php echo $row['txn_id'];?><br>
										<?php
											$purchaseDetails = $admin->getProductOrderDetails($row['txn_id']);
											$orderDetails = $purchaseDetails['orderDetails'];
											foreach($orderDetails as $oneOrder){
												$productDetails = $admin->getUniqueProductById($oneOrder['product_id']);
												$quantity = $oneOrder['quantity'];
												echo $productDetails['product_name'].' - ('.$oneOrder['size'].') - ('.$quantity.')';
												echo '<br>';
											}
										?>
									</td>
									<td><?php echo date('d-m-Y G:i:s', strtotime($row['created'])); ?></td>
									<td><?php echo round($admin->getCustomerPurchaseAmount($row['txn_id'])); ?></td>
									<td>
	<?php 					if($row['order_status']=="In Process"){ ?>
									<span class="label label-warning">
										<?php echo $row['order_status']; ?>
									</span>
	<?php					} else if($row['order_status']=="Completed"){ ?>
									<span class="label label-success">
										<?php echo $row['order_status']; ?>
									</span>
	<?php					} else if($row['order_status']=="Cancelled"){ ?>
									<span class="label label-danger">
										<?php echo $row['order_status']; ?>
									</span>
	<?php 					} else if($row['order_status']=="Shipped"){ ?>
									<span class="label label-default">
										<?php echo $row['order_status']; ?>
									</span>
	<?php 					} else {
									echo $row['order_status'];
							} ?>
									
									</td>
									<td>
	<?php 					if($row['payment_status']=="Payment Pending"){ ?>
									<span class="label label-warning">
										<?php echo $row['payment_status']; ?>
									</span>
	<?php					} else if($row['payment_status']=="Payment Complete"){ ?>
									<span class="label label-success">
										<?php echo $row['payment_status']; ?>
									</span>
	<?php					} else if($row['payment_status']=="Payment Failed"){ ?>
									<span class="label label-danger">
										<?php echo $row['payment_status']; ?>
									</span>
	<?php 					} else if($row['payment_status']=="Payment Refunded"){ ?>
									<span class="label label-default">
										<?php echo $row['payment_status']; ?>
									</span>
	<?php 					} else {
									echo $row['payment_status'];
							} ?>
									</td>
									<td><?php echo $row['payment_mode'];?></td>
								</tr>
	<?php
							}
	?>
	</tbody>
</table>
<?php } ?>