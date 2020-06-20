<?php 
	
	Include_once("include/functions.php");
    $functions = New Functions();
    
	if(!$loggedInUserDetailsArr = $functions->sessionExists()){
        header("location: ".BASE_URL."/login.php");
        exit;
    }
    if(!$loggedInUserDetailsArr['user_type']){
        header("location: ".BASE_URL."/login.php?failed&cusLogin");
        exit;   
    }
    $customerOrders = $functions->getCompletedOrdersByCustomerId($loggedInUserDetailsArr['id']);
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
				<li>My Order</li>
			</ul>
		</div>
    </div>
    <section class="orderreceived">
        <div class="inner-content bt">
            <div class="container">
            	<div class="ac-detail-nav-box">
                	<ul class="ac-detail-nav">
						<?php if($loggedInUserDetailsArr['user_type']=="b2b"){ ?>
                			<li><a href="vendor-myaccount.php"><i class="fa fa-user-o" aria-hidden="true"></i>  My Account</a></li>	
                		<?php }else{ ?>
                			<li><a href="cus-my-account.php"><i class="fa fa-user-o" aria-hidden="true"></i>  My Account</a></li>
                		<?php } ?>
	                    <li class="active"><a href="cus-myorder.php"><i class="fa fa-bars" aria-hidden="true"></i>My Orders</a></li>
	                    <li><a href="<?php echo BASE_URL; ?>/cus-mywishlist.php"><i class="fa fa-heart-o" aria-hidden="true"></i> Wishlist</a></li>
	                    <li><a href="<?php echo BASE_URL; ?>/cus-myaddressbook.php"><i class="fa fa-map-marker" aria-hidden="true"></i> Address Book</a></li>
	                    <div class="clearfix"></div>
                	</ul>
            	</div>
                <div class="row">
				<!-- <div class="col-sm-10 col-sm-pull-1 col-sm-push-1"> -->
					<div class="col-sm-12">
					<div class="field-box noshadow">
					<?php
						if($functions->num_rows($customerOrders)>0){
					?>
							<div class="table-responsive">
							 	<table class=" table ordertable">
									<thead>
								  		<tr>
											<th>Sr.No.</th>
											<th>Payment Method</th>
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
											while($order = $functions->fetch($customerOrders)) {
										?>
										  		<tr>
													<td><?php echo $i++ ?></td>
													<td><?php echo $order['payment_mode']; ?></td>
													<td><?php echo date('d F, Y', strtotime($order['created'])); ?></td>
													<td><?php echo $order['txn_id']; ?></td>
													<td><i class="fa fa-inr" aria-hidden="true"></i><?php echo $functions->getCustomerPurchaseAmount($loggedInUserDetailsArr['id'], $order['txn_id']); ?></td>
			                                		<td><?php echo $order['order_status']; ?></td>
													<td>
														<a href="<?php echo BASE_URL; ?>/order-details-pdf.php?txnId=<?php echo $order['txn_id']; ?>" class="details_view" target="_blank">
															<i class="fa fa-file-text-o" aria-hidden="true"></i> View Details
														</a> | 
														<a href="javascript:;" data-fancybox data-type="iframe" data-src="<?php echo BASE_URL; ?>/refund-request.php?OrderID=<?php echo $order['txn_id']; ?>" class="trackord">Cancel Order</a>	
														
													</td>
										  		</tr>
										<?php 
											} ?>
									</tbody>
							  	</table>
							</div>
					<?php 
						}else{ ?>
							<br><br><br><center><p>No Order Placed Yet.</p></center>
						<?php } ?>		
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
               
         </script>
   </body>
</html>