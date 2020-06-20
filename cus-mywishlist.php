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

	if(isset($_GET['delid']) && !empty($_GET['delid'])){
		$delid = $functions->escape_string($functions->strip_all($_GET['delid']));
		$sql = "DELETE FROM ".PREFIX."customers_wishlist WHERE `id`='".$delid."'";
		//echo $sql; exit; 
		$functions->query($sql);
		header("location:cus-mywishlist.php?delesuccess");
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
				<li>My Wishlist</li>
			</ul>
		</div>
    </div>
    <section class="mywishlist">
        <div class="inner-content bt">
            <div class="container">
            	<div class="ac-detail-nav-box">
	                <ul class="ac-detail-nav">
						<?php if($loggedInUserDetailsArr['user_type']=="b2b"){ ?>
                			<li><a href="vendor-myaccount.php"><i class="fa fa-user-o" aria-hidden="true"></i>  My Account</a></li>	
                		<?php }else{ ?>
                			<li><a href="cus-my-account.php"><i class="fa fa-user-o" aria-hidden="true"></i>  My Account</a></li>
                		<?php } ?>
	                    <li><a href="<?php echo BASE_URL; ?>/cus-myorder.php"><i class="fa fa-bars" aria-hidden="true"></i>My Orders</a></li>
	                    <li class="active"><a href="<?php echo BASE_URL; ?>/cus-mywishlist.php"><i class="fa fa-heart-o" aria-hidden="true"></i> Wishlist</a></li>
	                    <li><a href="<?php echo BASE_URL; ?>/cus-myaddressbook.php"><i class="fa fa-map-marker" aria-hidden="true"></i> Address Book</a></li>
	                    <div class="clearfix"></div>
	                </ul>
            	</div>
                <div class="row">
	            	<?php 
	            		if(isset($_GET['delesuccess'])){ ?>
							<div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">X</span><span class="sr-only">Close</span></button>
								<i class="icon-checkmark3"></i> Product successfully removed from whishlist.
							</div><br/>
					<?php 
						}

						$wishlist = $functions->getWishlistByUserId($loggedInUserDetailsArr['id']);	 
					?>
					<div class="col-lg-10 col-lg-pull-1 col-lg-push-1 col-md-12 wishadd">
	 					<div class="field-box">
	 						<?php 
	 							if($functions->num_rows($wishlist)>0){

	 						?>
									<div class="table-responsive">
									 	<table class="table">
											<thead>
											  	<tr>
													<th>Sr.No.</th>
													<th>Product Image</th>
													<th class="product-details">Product Name</th>
													<th>Price</th>
													<th>Action</th>
				 							  	</tr>
											</thead>
											<tbody>
												<?php 
													$i = 1;
													while($whishlistDetails = $functions->fetch($wishlist)){
														$productDetails = $functions->getUniqueProductById($whishlistDetails['product_id']);
														$productBanner = $functions->getImageUrl('products',$productDetails['main_image'],'crop','');
														if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2c"){
						                                    if(!empty($productDetails['discount_price'])){
																$price = $productDetails['discount_price'];
															}else{
																$price = $productDetails['price'];
															}
														}else if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2b"){
						                                    if(!empty($productDetails['b2b_discount_price'])){
																$price = $productDetails['b2b_discount_price'];
															}else{
																$price = $productDetails['b2b_price'];
															}
														}else{
															if(!empty($productDetails['discount_price'])){
																$price = $productDetails['discount_price'];
															}else{
																$price = $productDetails['price'];
															}
														}
												?>
													  	<tr>
															<td><?php echo $i; ?></td>
															<td>
																<div class="product-image">
																	<a href="<?php echo $productBanner; ?>" data-fancybox="images" data-caption="My caption">
																		<img src="<?php echo $productBanner; ?>" alt="" class="wishlistimg"/>
																	</a>
																</div>
															</td>
															<td><p><?php echo $productDetails["product_name"]; ?></p></td>
															<td><i class="fa fa-inr" aria-hidden="true"></i><?php echo $price; ?></td>
															<td>
																<div class="priceDiv">
							                                        <input type="hidden" name="available_qty" class="available_qty" value="<?php echo $productDetails["availability"]; ?>">
							                                        <input type="hidden" name="b2b_min_qty" class="b2b_min_qty" value="<?php echo $productDetails["b2b_min_qty"]; ?>">
							                                    </div>
							                                	<?php if($productDetails['availability']>0){ ?>
							                                   	 	<button type="button" value="<?php echo $productDetails['id']; ?>" class="movetocart cartListingBtn">Move to Cart</button>
							                                	<?php }else{ ?>
							                                		<span class="wishoutofstock" style="color:red;">Out of Stock</span>
							                                	<?php } ?>	
																<a onclick="return confirm('Are you sure you want to delete this product from wishlist?');"  href="<?php echo BASE_URL."/cus-mywishlist.php?delid=".$whishlistDetails['id']; ?>" class="delete-btn wishlistdeletebtn"><img style="width:17px;" src="<?php echo BASE_URL; ?>/images/delete.png"/></a>
															</td>
						 							  	</tr>
				 							  <?php $i++;
				 									} ?>
				 							</tbody>
									  	</table>
									</div>
						<?php 
								}else{ ?>
									<center><p>No Products In Wishlist</p></center>
						<?php 	} ?>			
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
   </body>
</html>