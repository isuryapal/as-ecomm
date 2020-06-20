<?php 
	Include_once("include/functions.php");
    $functions = New Functions();
    
    if(!$loggedInUserDetailsArr = $functions->sessionExists()){
        header("location: ".BASE_URL."/login.php");
        exit;
    }
    
    if($loggedInUserDetailsArr['user_type'] == "b2c"){
        header("location: ".BASE_URL."/login.php?failed&cusLogin");
        exit;   
    }

    if(isset($_GET['orderStatus']) && !empty($_GET['orderStatus']) && isset($_GET['orderDetailsID']) && !empty($_GET['orderDetailsID'])){

    	$orderStatus = $functions->escape_string($functions->strip_all($_GET['orderStatus']));
    	$orderDetailsID = $functions->escape_string($functions->strip_all($_GET['orderDetailsID']));
    	
    	$sql = "SELECT * FROM ".PREFIX."order_details WHERE `id`='".$orderDetailsID."'";
    	$orderDetailsResult = $functions->query($sql);
    	$orderDetails = $functions->fetch($orderDetailsResult);

    	$updatesql ="UPDATE ".PREFIX."order_details SET `vendor_order_status`='".$orderStatus."' WHERE `order_id`='".$orderDetails['order_id']."' and `vendor_id`='".$orderDetails['vendor_id']."'";
    	//echo $updatesql;

    	$functions->query($updatesql);

    	
    	$sql = "SELECT * FROM ".PREFIX."order_details WHERE `id`='".$orderDetailsID."'";
    	$orderDetailsResult = $functions->query($sql);
    	$orderDetails = $functions->fetch($orderDetailsResult);


    	$orderSql = "SELECT * FROM ".PREFIX."order_details WHERE `order_id`='".$orderDetails['order_id']."' and (vendor_order_status='In Process' or vendor_order_status='Pending')";
    	//echo $orderSql; exit;
    	
    	$orderDetailsSql = $functions->query($orderSql);
    	if($functions->num_rows($orderDetailsSql)==0){
    		//$orderDetail = $functions->fetch($orderDetailsSql);
    		$update ="UPDATE ".PREFIX."order SET `order_status`='Completed' where id='".$orderDetail['order_id']."'";
    		$functions->query($update);
    	}

    	header("location:vendor-orderreceived.php?OrderStatusSucess");
    	exit;

    }
?>
<!DOCTYPE>
<html>
   <head>
	<title>Arvind Sanitary</title>
      <?php include("include/header-link.php");?>
   </head>
   <body class="home">
      <!--Top start menu head-->       
      <?php include("include/header.php");?>
      
      <div class="only-breadcrumbs">
		<div class="container">
			<ul class="breadcrumbs">
				<li><a href="index.php">Home</a></li>
				<li>Order Received</li>
			</ul>
		</div>
    </div>
    <section class="orderreceived">
        <div class="inner-content bt">
            <div class="container">
            	<div class="ac-detail-nav-box">
	                <ul class="ac-detail-nav">
	                    <li> <a href="vendor-myaccount.php"><i class="fa fa-user-o" aria-hidden="true"></i>  My Account</a></li>
	                    <li class="active"><a href="vendor-orderreceived.php"><i class="fa fa-bars" aria-hidden="true"></i> Order Received</a></li>
	                    <li><a href="vendor-myproducts.php"><i class="fa fa-heart-o" aria-hidden="true"></i> My Products</a></li>
	                    <div class="clearfix"></div>
	                </ul>
	            </div>
                <div class="row">
                	<?php 
                		if(isset($_GET['OrderStatusSucess'])){
                	?>
                			<div class="alert alert-success">
                                Order Status Successfully Updated.
                            </div>
                <?php 	} ?>
				<div class="col-lg-10 col-lg-pull-1 col-lg-push-1 col-md-12">
					<div class="field-box noshadow">
						<?php 
							$vendorOrders = $functions->getVendorOrderDetailsByvendorID($loggedInUserDetailsArr['id']);
							if($functions->num_rows($vendorOrders)>0){
						?>
							<div class="table-responsive">
							 	<table class="table">
									<thead>
									  <tr>
										<th>Sr.No.</th>
										<th>Order Date</th>
										<th>Order ID</th>
										<th>Total Amount</th>
										<th>Order Status</th>
										<th>Invoice</th>
									  </tr>
									</thead>
									<tbody>
									<?php 
										$i=1;	
										while($orderDetails = $functions->fetch($vendorOrders)){
											$productDetails = $functions->getUniqueProductById($orderDetails['product_id']);
											if(isset($orderDetails['customer_discount_price']) && !empty($orderDetails['customer_discount_price'])){
												$price = $orderDetails['customer_discount_price'] * $orderDetails['quantity'];
											}else{
												$price = $orderDetails['customer_price'] * $orderDetails['quantity'];
											}
											$orders = $functions->getOrderbyOrderId($orderDetails['order_id']);

									?>		
										  	<tr>
												<td><?php echo $i;  ?></td>
												<td><?php echo date('d F, Y', strtotime($orders['created'])); ?></td>
												<td><?php echo $orders['txn_id']; ?></td>
												<td><i class="fa fa-inr" aria-hidden="true"></i><?php echo $functions->getCustomerPurchaseVendorAmount($loggedInUserDetailsArr['id'], $orders['txn_id']); ?></td>
												<td class="getOrderId">
													<input type="hidden" name="orderDetails_id" class="orderDetails_id" value="<?php echo $orderDetails['id']; ?>">
													<?php //echo $orders['order_status']; ?>
													
													<select class="form-control orderStatus" name="orderStatus">
														<option value="">Select Order Status</option>
														<option value="In Process" <?php if(isset($orderDetails['vendor_order_status']) && $orderDetails['vendor_order_status']=="In Process"){ echo 'selected="selected"'; } ?>>In Process</option>
														<option value="Pending" <?php if(isset($orderDetails['vendor_order_status']) && $orderDetails['vendor_order_status']=="Pending"){ echo 'selected="selected"'; } ?>>Pending</option>
														<option value="Completed" <?php if(isset($orderDetails['vendor_order_status']) && $orderDetails['vendor_order_status']=="Completed"){ echo 'selected="selected"'; } ?>>Completed</option>
														
													</select>
			                                 	</td>
												<td><i class="fa fa-file-text-o" aria-hidden="true"></i><a target="_blank" href="<?php echo BASE_URL; ?>/order-details-pdf.php?txnId=<?php echo $orders['txn_id']; ?>&vendor_id=<?php echo $loggedInUserDetailsArr['id'];  ?>">View Invoice</a></td>
										  	</tr>
									<?php 
										$i++;
										} ?>  	
									</tbody>
							 	</table>
							</div>
						<?php 
							}else{ ?>
								<br><br><br><center><p>No Orders Placed Yet.</p></center>
						<?php 
							} ?>		
					</div>
				</div>
			</div>
            </div>
        </div>
    </section>
	  
      
	  
         <!--Main End Code Here-->
      <!--footer start menu head-->
      <?php include("include/footer.php");?> 
      <!--footer end menu head-->
      <?php include("include/footer-link.php");?>
        <script>
           	$(function() {
			    $(".orderStatus").change(function() {
			        var orderStatus =  $('option:selected', this).val();
			        var orderDetailsID = $(this).closest('.getOrderId').find('.orderDetails_id').val();
			        //alert(orderDetailsID);

			        window.location.href = "vendor-orderreceived.php?orderStatus="+orderStatus+"&orderDetailsID="+orderDetailsID;
			    });
			});
        </script>
   </body>
</html>