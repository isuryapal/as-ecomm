<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$pageName = "Order";
	$parentPageURL = 'order.php';
	$pageURL = 'order-add.php';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}

	//include '../include/classes/CSRF.class.php';
	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);

	/* if(isset($_POST['register'])){
		if($csrf->check_valid('post')) {
			//$allowed_ext = array('image/jpeg','image/jpg');
			
			$email = trim($admin->escape_string($admin->strip_all($_POST['email'])));
			
			if(empty($email)){
				header("location:".$pageURL."?registerfail&msg=Please enter a email");
				exit();
			}
			else {
				//add to database
				$result = $admin->addWholesalerDetail($_POST);
				header("location:".$pageURL."?registersuccess");
			}
		}
	} */

	if(isset($_GET['edit'])){
		if(isset($_GET['txnId']) && !empty($_GET['txnId'])){
			$txnId = $admin->escape_string($admin->strip_all($_GET['txnId']));
			$purchaseDetails = $admin->getProductOrderDetails($txnId);
			if($purchaseDetails){
				$order = $purchaseDetails['order'];
				$orderDetails = $purchaseDetails['orderDetails'];
				$customerDetails = $admin->getUniqueCustomersById($order['customer_id']);
			} else {
				// order with that txn_id for that customer was NOT found
				header("location: ".$parentPageURL);
				exit;
			}
		} else {
			header("location: ".$parentPageURL);
			exit;
		}
	}

	if(isset($_POST['update'])) {
		if($csrf->check_valid('post')) {
			$txnId = trim($admin->escape_string($admin->strip_all($_POST['txnId'])));
			$orderStatus = trim($admin->escape_string($admin->strip_all($_POST['orderStatus'])));
			$orderRemark = trim($admin->escape_string($admin->strip_all($_POST['orderRemark'])));
			//update to database
			$admin->updateProductOrderDetails($_POST);

			header("location:".$pageURL."?updatesuccess&edit&txnId=".$txnId);
			exit();
		} else {
			header("location:".$pageURL."?updatefail&edit&txnId=".$txnId);
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo TITLE ?></title>
	<link rel="shortcut icon" href="<?php echo BASE_URL; ?>/img/favicon.png" type="image/png" />
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/londinium-theme.min.css" rel="stylesheet" type="text/css">
	<link href="css/styles.min.css" rel="stylesheet" type="text/css">
	<link href="css/icons.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/nanoscroller.css" rel="stylesheet">
	<link href="css/bootstrap-datetimepicker.css" rel="stylesheet">
	<link href="css/cover.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.1.10.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<!--<script src="js/Moment.js"></script>
	<script src="js/moment-with-locales.js"></script>
	<script src="js/bootstrap-datetimepicker.js"></script>-->
	<script type="text/javascript" src="js/jquery-ui.1.10.2.min.js"></script>
	<script type="text/javascript" src="js/plugins/charts/sparkline.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/uniform.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/select2.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/inputmask.js"></script>
	<script type="text/javascript" src="js/plugins/forms/autosize.js"></script>
	<script type="text/javascript" src="js/plugins/forms/inputlimit.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/listbox.js"></script>
	<script type="text/javascript" src="js/plugins/forms/multiselect.js"></script>
	<script type="text/javascript" src="js/plugins/forms/validate.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/tags.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/switch.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/uploader/plupload.full.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/uploader/plupload.queue.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/wysihtml5/wysihtml5.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/wysihtml5/toolbar.js"></script>
	<script type="text/javascript" src="js/plugins/interface/daterangepicker.js"></script>
	<script type="text/javascript" src="js/plugins/interface/fancybox.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/moment.js"></script>
	<script type="text/javascript" src="js/plugins/interface/jgrowl.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/datatables.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/colorpicker.js"></script>
	<script type="text/javascript" src="js/plugins/interface/fullcalendar.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/timepicker.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/collapsible.min.js"></script>
	<script>
		$(document).ready(function(){

			$('body').append('<div id="tableCopy" style="display:none"></div>');
			var tableCopy = $("#orderTable").clone();
			var headingRow = tableCopy.find('thead tr th');
			// console.log(headingRow[0]); // TEST
			// headingRow[0].remove();
			// headingRow[headingRow.length-1].remove();

			var rowArr = tableCopy.find('tbody tr');
			// console.log(rowArr); // TEST
			$.each(rowArr, function(index, oneRow){
				// console.log(rowArr[index]); // TEST
				// console.log(oneRow); // TEST
				// console.log($(oneRow).find('td')); // TEST
				var firstCell = $(oneRow).find('td');
				// firstCell[0].remove();
				// firstCell[firstCell.length-1].remove();
			})
			$("#tableCopy").html(tableCopy);

		});
	</script>
	<script type="text/javascript" src="js/application.js"></script>
	<script type="text/javascript" src="js/export-excel.js"></script>
	<script type="text/javascript" src="js/additional-methods.js"></script>

	<link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
	<script src="js/bootstrap-datepicker.min.js"></script>
	<script src="js/Moment.js"></script>


	<script type="text/javascript">
		/*$(document).ready(function() {
			$("#updateDeliveryDateForm").validate({
				rules: {
					"prod_delivery_date[]":{ required:true}
				},
				messages:{
					"prod_delivery_date[]":{required:"please select delivery date."}
				}
			});
		});*/
	</script>

</head>
<body class="sidebar-wide">
	<?php include 'include/navbar.php' ?>

	<div class="page-container">

		<?php include 'include/sidebar.php' ?>

		<div class="page-content">


			<div class="breadcrumb-line">
				<div class="page-ttle hidden-xs" style="float:left;">
					<?php  
						if(isset($_GET['edit'])){echo 'Edit '.$pageName;} 
						else {echo 'Add New '.$pageName;}
					?>
				</div>
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a></li>
					<li><a href="<?php echo $parentPageURL; ?>"><?php echo $pageName; ?></a></li>
					<li class="active">
						<?php  
							if(isset($_GET['edit'])){echo 'Edit '.$pageName;} 
							else {echo 'Add New '.$pageName;}
						?>
					</li>
				</ul>
			</div>

			
			<a href="<?php echo $parentPageURL; ?>" class="label label-primary">Back to <?php echo $pageName; ?></a><br/><br/>
			<?php
					if(isset($_GET['registersuccess'])){ ?>
						<div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<i class="icon-checkmark3"></i> <?php echo $pageName; ?> successfully added.
						</div><br/>
			<?php	} ?>
				
			<?php
					if(isset($_GET['registerfail'])){ ?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<i class="icon-close"></i> <strong><?php echo $pageName; ?> not added.</strong> <?php echo $admin->escape_string($admin->strip_all($_GET['msg'])); ?>.
						</div><br/>
			<?php	} ?>

			<?php
					if(isset($_GET['updatesuccess'])){ ?>
						<div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<i class="icon-checkmark3"></i> <?php echo $pageName; ?> successfully updated.
						</div><br/>
			<?php	} ?>


			<?php
					if(isset($_GET['updateProductDeliveryDateSuccess'])){ ?>
						<div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<i class="icon-checkmark3"></i> Product Delivery Dates successfully updated.
						</div><br/>
			<?php	} ?>

			<?php
					if(isset($_GET['updateProductDeliveryDateFailed'])){ ?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<i class="icon-close"></i> <strong>Product Delivery Dates couldn't updated.
						</div><br/>
			<?php	} ?>

			<?php
					if(isset($_GET['updatefail'])){ ?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<i class="icon-close"></i> <strong><?php echo $pageName; ?> not updated.</strong> <?php echo $admin->escape_string($admin->strip_all($_GET['msg'])); ?>.
						</div><br/>
			<?php	} ?>

			<form role="form" action="" method="post" id="form">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6 class="panel-title"><i class="icon-newspaper"></i> Order</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label>Order No.</label>
									<input type="text" class="form-control" disabled="disabled" required="required" name="" value="<?php if(isset($_GET['edit'])){ echo $order['txn_id']; }?>"/>
								</div>
								<div class="col-sm-3">
									<label>Order Date</label>
									<input type="text" class="form-control" disabled="disabled" required="required" name="" value="<?php if(isset($_GET['edit'])){ echo date('d F, Y H:i:s A', strtotime($order['created'])); }?>"/>
								</div>
								<div class="col-sm-3">
									<label>Payment Status</label>
									<input type="text" class="form-control" disabled="disabled" required="required" name="" value="<?php if(isset($_GET['edit'])){ echo $order['payment_status']; }?>"/>
								</div>
								<div class="col-sm-3">
									<label>Invoice No.</label>
									<input type="text" class="form-control" disabled="disabled" required="required" name="" value="<?php if(isset($_GET['edit']) && $order['payment_status']=="Payment Complete"){ echo $order['txn_id']; } else { echo '-'; }?>"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Billing Address</label>
									<textarea required="required" name="" class="form-control" disabled="disabled" rows="5"><?php if(isset($_GET['edit'])){
									$eol = '';
									echo 
									$order['billing_fname'].' '.$eol.
									$order['billing_address_line1'].', '.$eol;
									if(!empty($order['billing_address_line2'])){
										echo $order['billing_address_line2'].', '.$eol;
									}
									echo $order['billing_city'].' - '.$order['billing_pincode'].', '.$eol.
									$order['billing_state'];
									} ?></textarea>
								</div>
								<div class="col-sm-6">
									<label>Shipping Address</label>
									<textarea required="required" name="" class="form-control" disabled="disabled" rows="5"><?php if(isset($_GET['edit'])){
									echo 
									$order['shipping_fname'].' '.$eol.
									$order['shipping_address_line1'].', '.$eol;
									if(!empty($order['shipping_address_line2'])){
										echo $order['shipping_address_line2'].', '.$eol;
									}
									echo $order['shipping_city'].' - '.$order['shipping_pincode'].', '.$eol.
									$order['shipping_state'];
									} ?></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label>Payment Status</label>
									<select class="form-control" name="paymentStatus" id="paymentStatus">
										<?php 							
										if(isset($_GET['edit'])){ 
											if($order['payment_status']=="Payment Pending"){ ?>
												<option value="Payment Pending" <?php if(isset($_GET['edit']) && $order['payment_status']=="Payment Pending"){ echo 'selected="selected"'; } ?>>Payment Pending</option>
												<option value="Payment Complete" <?php if(isset($_GET['edit']) && $order['payment_status']=="Payment Complete"){ echo 'selected="selected"'; } ?>>Payment Complete</option>
												<option value="Payment Failed" <?php if(isset($_GET['edit']) && $order['payment_status']=="Payment Failed"){ echo 'selected="selected"'; } ?>>Payment Failed</option>
												<option value="Payment Refunded" <?php if(isset($_GET['edit']) && $order['payment_status']=="Payment Refunded"){ echo 'selected="selected"'; } ?>>Payment Refunded</option>
												<?php 								
											} else if($order['payment_status']=="Payment Complete"){ ?>
												<option value="Payment Complete" <?php if(isset($_GET['edit']) && $order['payment_status']=="Payment Complete"){ echo 'selected="selected"'; } ?>>Payment Complete</option>
												<option value="Payment Refunded" <?php if(isset($_GET['edit']) && $order['payment_status']=="Payment Refunded"){ echo 'selected="selected"'; } ?>>Payment Refunded</option>
												<?php 								
											} else if($order['payment_status']=="Payment Failed"){ ?>
												<option value="Payment Failed" <?php if(isset($_GET['edit']) && $order['payment_status']=="Payment Failed"){ echo 'selected="selected"'; } ?>>Payment Failed</option>
												<?php 								
											} else if($order['payment_status']=="Payment Refunded"){ ?>
												<option value="Payment Refunded" <?php if(isset($_GET['edit']) && $order['payment_status']=="Payment Refunded"){ echo 'selected="selected"'; } ?>>Payment Refunded</option>
												<?php 								
											}
										} ?>
									</select>
								</div>
								<div class="col-sm-3">
									<label>Order Status</label>
									<select class="form-control" name="orderStatus">
										<option value="">Select Order Status</option>
										<option value="In Process" <?php if(isset($_GET['edit']) && $order['order_status']=="In Process"){ echo 'selected="selected"'; } ?>>In Process</option>
										<option value="Shipped" <?php if(isset($_GET['edit']) && $order['order_status']=="Shipped"){ echo 'selected="selected"'; } ?>>Shipped</option>
										<option value="Completed" <?php if(isset($_GET['edit']) && $order['order_status']=="Completed"){ echo 'selected="selected"'; } ?>>Completed</option>
										<option value="Cancelled" <?php if(isset($_GET['edit']) && $order['order_status']=="Cancelled"){ echo 'selected="selected"'; } ?>>Cancelled</option>
									</select>
								</div>
								<div class="col-sm-6">
									<br>
									<label>Order Remarks</label>
									<textarea name="orderRemark" class="form-control" rows="5"><?php if(isset($_GET['edit'])){ echo $order['order_remark']; } ?></textarea>
								</div>
								<?php /*<div class="col-sm-3" id="refundStatus" style="display:none;">
									<br>
									<label>Refund Status</label>
									<select class="form-control" name="refundStatus" id="RefundStatusval">
										<!-- <option value="">Select Refund Status</option> -->
										<option value="Accepted" <?php if(isset($_GET['edit']) && $order['refund_status']=="Accepted"){ echo 'selected="selected"'; } ?>>Accepted</option>
										<option value="Rejected" <?php if(isset($_GET['edit']) && $order['refund_status']=="Rejected"){ echo 'selected="selected"'; } ?>>Rejected</option>
									</select>
								</div> */ ?>
							</div>
						</div>
						<div class="form-actions text-right">
							<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
							<?php
							if(isset($_GET['edit'])){
								if($order['payment_status']=="Payment Complete"){ ?>
									<a href="<?php echo BASE_URL; ?>/order-details-pdf.php?txnId=<?php echo $order['txn_id']; ?>" class="btn btn-default pull-left" title="Download Invoice PDF" target="_blank">
										<i class="fa fa-file-pdf-o text-danger"></i> Download Invoice
									</a>
									<?php
								} ?>
									
									<input type="hidden" class="form-control" name="txnId" required="required" value="<?php echo $order['txn_id'] ?>"/>
									<button type="submit" name="update" class="btn btn-warning"><i class="icon-pencil"></i>Update <?php echo $pageName; ?></button>
								<?php
							}  ?>
						</div>
					</div>
				</div>
			</form>

			<br/>


			

			<div class="panel panel-default">
				<div class="panel-heading">
					<h6 class="panel-title"><i class="icon-cart"></i> Order Details</h6>
				</div>
				<div class="panel-body">

					<form name="updateDeliveryDateForm" id="updateDeliveryDateForm" method="post" autocomplete="off">
						

						<table class="table" id="orderTable">
							<tr>
								<th>Product ID</th>
								<th>Refund Request</th>
								<th>Product Name</th>
								<th>Product Thumbnail</th>
								<th>Product Price</th>
								<th>Quantity</th>
								<th>Total Price</th>
							</tr>
							<?php
							$subTotal = 0;
							
							$finalTotal = 0;
							foreach($orderDetails as $oneOrder){
								//print_r($oneOrder);	
								$productDetails = $admin->getUniqueProductById($oneOrder['product_id']);
								$quantity = $oneOrder['quantity'];
								$image_name = strtolower(pathinfo($productDetails['main_image'], PATHINFO_FILENAME));
								$image_ext = strtolower(pathinfo($productDetails['main_image'], PATHINFO_EXTENSION));
								if(!empty($image_name) && !empty($image_ext)){
									$imageUrl = BASE_URL."/images/products/".$image_name.'_crop.'.$image_ext;	
								}
								else{
									$imageUrl = BASE_URL."/images/products/no_img.jpg";
								}

								$unitPrice = $oneOrder['customer_price'];
								$unitDiscountedPrice = $oneOrder['customer_discount_price'];

								if(!empty($unitDiscountedPrice)){
									$totalPrice = $quantity * $unitDiscountedPrice;
									$totalPriceMsg = 'Rs. '.$unitDiscountedPrice.' x '.$quantity.' unit';
									$displayPrice = $unitDiscountedPrice;
								} else {
									$totalPrice = $quantity * $unitPrice;
									$totalPriceMsg = 'Rs. '.$unitPrice.' x '.$quantity.' unit';
									$displayPrice = $unitPrice;
								}
								if($oneOrder['payment_discount']>0) {
									$paymentDiscountAmount = $totalPrice*($oneOrder['payment_discount']/100);
									$totalPrice = $totalPrice - $paymentDiscountAmount;
								}
								$subTotal += $totalPrice;
									?>
								<tr>
									<td data-title="Product ID"><?php echo $productDetails['permalink']; ?></td>
									<td data-title="Product ID">
										<?php 
											$sql = "SELECT * FROM ".PREFIX."refund_request WHERE order_detail_pal='".$oneOrder['id']."'";
											$result = $admin->query($sql);
											if($admin->num_rows($result)>0){
												//$refundOrderDetails = $admin->fetch($result);
												echo $oneOrder['refund_status']; 
											}else{
												 echo "";
											}
										?>
									</td>
									<td data-title="Product Name"><?php echo $productDetails['product_name']; ?></td>
									<td data-title="Product Thumbnail">
										<img src="<?php echo $imageUrl; ?>" alt="<?php echo $productDetails['product_name']; ?>" title="<?php echo $productDetails['product_name']; ?>" width="50" />
									</td>
									<td data-title="Product Price" align="middle">
										<i class="fa fa-inr"></i> <?php echo $displayPrice; ?>
									</td>
									<td data-title="Quantity" align="middle">
										<?php echo $quantity; ?>
									</td>
									<td data-title="Total Price" align="middle" title="<?php echo $totalPriceMsg; ?>">
										<i class="fa fa-inr"></i> <?php echo $totalPrice; ?>
									</td>
								</tr>
									<?php 
							}
								?>


							<!-- <tr>
								<td colspan="9">
									<input type="hidden" name="txnId" value="<?php echo $txnId; ?>" >
									<button type="submit" name="updateDeliveryDate" class="btn btn-success pull-right "><i class="icon-pencil"></i>Update Delivery Date</button>
								</td>
							</tr> -->

						</table>
					</form>
					<hr/>
					<!-- transaction details -->
						<div class="col-sm-12">
							<div class="col-sm-11 text-right">
								Subtotal:<br/>
								
								<?php 					
								
								// CHECK IF DISCOUNT COUPON IS USED
									$couponDiscountAmount = $admin->getRedeemedCouponAmount($order['customer_id'], $order['id']);
									
									if(!empty($couponDiscountAmount)){ ?>
										Coupon Discount:<br/>
										<?php 					
									}
									
									if(!empty($order['loyalty_points'])){ ?>
										Wallet:<br/>
										<?php
									}
								// CHECK IF DISCOUNT COUPON IS USED
								
								if(!empty($order['giftCard'])){ ?>
									Gift Card:<br/>
									Message:<br/>
									<?php					
								} 
								if(!empty($order['shipping_charges'])){ ?>
									Shipping Charges:<br/>
									<?php					
								} ?>
								
								Final Total:
							</div>

							<div class="col-sm-1">
								<i class="fa fa-inr"></i> <?php echo $subTotal; ?><br/>
									
									<?php 					
								// CHECK IF DISCOUNT COUPON IS USED
								
									if(!empty($couponDiscountAmount)){
										$finalTotal = $subTotal - $couponDiscountAmount;
										?>
										<i class="fa fa-inr"></i> <?php echo $couponDiscountAmount; ?><br/>
										<?php					
									} else {
										$finalTotal = $subTotal;
									}

									if(!empty($order['loyalty_points'])){
										$finalTotal = $finalTotal - $order['loyalty_points'];
										?>
										
										<i class="fa fa-inr"></i> <?php echo $order['loyalty_points']; ?><br/>
											<?php					
									} else {
										
										$finalTotal = $finalTotal;
									}

								// CHECK IF DISCOUNT COUPON IS USED

								// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
									if(!empty($order['giftCard'])){
										$finalTotal += $order['giftCard'];
										?>
										
										<i class="fa fa-inr"></i> <?php echo $order['giftCard']; ?><br/>
										<i class="fa fa-inr"></i> <?php echo $order['giftCardMessage']; ?><br/>
										<?php
									}
								// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL 
								// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
									if(!empty($order['shipping_charges'])){
										$finalTotal += $order['shipping_charges'];
										?>
										
										<i class="fa fa-inr"></i> <?php echo $order['shipping_charges']; ?><br/>
										<?php					
									}
								// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL 
									?>
								<i class="fa fa-inr"></i> <?php echo $finalTotal; ?>
							</div>
						</div>
					<!-- transaction details -->
				</div>
			</div>

<?php 	include "include/footer.php"; ?>
    
		</div>
	</div>
	<script type="text/javascript">
		var todayDate = new Date().getDate();
		//var endD= new Date(new Date().setDate(todayDate - 15));
		var currDate = new Date();
		$('.valid_date').datepicker({
		    format: 'dd-mm-yyyy',                       
		    autoclose: true,
		    startDate : currDate
		});
		/*$(function() {
			var refundStatus = $("#paymentStatus").val();
			//alert(refundStatus);
			if(refundStatus=="Payment Refunded"){
				$("#refundStatus").show();
			}

		    $("#paymentStatus").change(function() {
		        var paymentStatus = $('option:selected', this).text();
		        if(paymentStatus == "Payment Refunded"){
		        	$("#refundStatus").show();
		        }else{
		        	$("#refundStatus").hide();
		        }
		    });
		});*/
	</script>
</body>
</html>