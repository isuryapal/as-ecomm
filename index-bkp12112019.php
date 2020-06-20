<?php 
	include_once 'include/functions.php';
   	$functions = new Functions();
   	$sliderDetails = $functions->getSliderbBanner();
   	$homePageCms = $functions->getHomePageCms();

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
	  
        <section class="banner-sec">
            <div class="container">
				<div class="main-slider">
					<?php 
						while($bannerDetails = $functions->fetch($sliderDetails)){
							$sliderBanner = $functions->getImageUrl('slider-banner',$bannerDetails['image_name'],'crop','');
					?>
							<div>
								<div class="slide-box">
									<a href="<?php if(!empty($bannerDetails['link'])){ echo $bannerDetails['link']; }else{ echo "javascript:;"; }   ?>"><img src="<?php echo $sliderBanner; ?>"/></a>
								</div>
							</div>
					<?php 
						} 
						/*<div>
							<div class="slide-box">
								<img src="images/slider-1.png" alt=""/>
								<div class="container">
									<div class="slide-box-inner">
										<div class="row">
											<div class="col-sm-5">
												<div class="slide-box-left">
													<img src="images/slide-prod-1.png" alt=""/>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="slide-box-right">
													<h2>We ensue the <span>lowest and best price - 2 </span></h2>
													<p>for each medicine</p>
													<a href="">Shop now</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>*/
					?>
					
				</div>
			</div>
        </section>
	  
		<div class="top-brands">
			<div class="container">
				<div class="top-brands-inner">
					<h4>Top Brands</h4>
					<?php 
						$brandData = $functions->getActiveBrand();
						if($functions->num_rows($brandData)>0){
					?>
							<div class="brands-list-slider">
								<?php 
									while($brandDetails = $functions->fetch($brandData)){
										$brandBanner = $functions->getImageUrl('brand',$brandDetails['image_name'],'crop','');

								?>	
										<a href="javascript:;"><img src="<?php echo $brandBanner; ?>"/></a>
								<?php 
									} ?>
							</div>
				<?php 
						} ?>
					<div class="tb-triangle"></div>
				</div>
			</div>
		</div>
	  
	  <section class="home-main-content">
			<div class="container">
				<div class="row reverse-content">
					<div class="col-sm-9">
						<div class="hmc-right">
							<div class="section-heading">
								<h4 class="bordered">Featured Products</h4>
							</div>
							<!-- Featred slider Start Here--->

							<div class="featured-slider">
								<?php 
									$fetaureData = $functions->getFeaturedProduct();
									if($functions->num_rows($fetaureData)>0){
										while($fetaureProduct = $functions->fetch($fetaureData)){
										$productPermalink = $functions->getProductDetailPageURL($fetaureProduct['id']);
										$productBanner = $functions->getImageUrl('products',$fetaureProduct['main_image'],'crop','');
								?>
											<div class="fetured-div-slide">
												<a href="<?php echo $productPermalink; ?>" class="product-box">
													<img src="<?php echo $productBanner; ?>" alt=""/>
													<div class="prod-details">
														<h5><?php echo $fetaureProduct['product_name']; ?></h5>
														<p class="price">
															<?php 
																if(!empty($fetaureProduct['discount_price'])){
															?>
																	<i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['discount_price']; ?> 
																	<strike class="disabled">
																		<i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['price']; ?>
																	</strike>
														<?php 	}else{ ?>
																	<i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['price']; ?>
														<?php 	} ?>	
														</p>
														<div class="priceDiv">
					                                        <input type="hidden" name="available_qty" class="available_qty" value="<?php echo $fetaureProduct["availability"]; ?>">
					                                    </div>
					                                    <?php 
					                                    	if($fetaureProduct['availability']>0){ ?>
																<button class="bt default-btn listingBuyNow" data-id="<?php echo $fetaureProduct['id']; ?>">Buy Now</button>
													<?php 	} ?>
													</div>
												</a>
											</div>
							<?php 		}
									}
							 ?>
								
								<?php 
									/*<div class="fetured-div-slide">
										<a href="detail.php" class="product-box">
											<img src="images/fp-2.png" alt=""/>
											<div class="prod-details">
												<h5>Waldent Eco Plus Airotor Waldent Eco Plus Airotor</h5>
												<p class="price"><i class="fa fa-inr" aria-hidden="true"></i> 255 <strike class="disabled"><i class="fa fa-inr" aria-hidden="true"></i> 355</strike></p>
												<button class="bt default-btn">Buy Now</button>
											</div>
										</a>
									</div>*/
								?>
							</div>
							
							<!-- Featred slider End Here--->
							
							<div class="horizontal-banner">
								<div class="row">
									<div class="col-sm-4">
										<div class="nb-inner">
											<?php 
												$add_baneer_two = $functions->getImageUrl('home_cms',$homePageCms['image_name_two'],'crop','');
											?>
											<a href="<?php echo $homePageCms['adUrlOne']; ?>"><img src="<?php echo $add_baneer_two; ?>" /></a>
										</div>
									</div>
									<div class="col-sm-8">
										<div class="nb-inner">
											<?php 
												$add_baneer_three = $functions->getImageUrl('home_cms',$homePageCms['image_name_three'],'crop','');
											?>
											<a href="<?php echo $homePageCms['adUrlThree']; ?>"><img src="<?php echo $add_baneer_three; ?>" /></a>
										</div>
									</div>
								</div>
							</div>
							
							<div class="section-heading">
								<h4 class="bordered">Latest Products</h4>
							</div>
							
							<!-- latest-prod-slider Start Here--->
							<?php 
								$latestProductData = $functions->gelatestProduct();
								if($functions->num_rows($latestProductData)>0){
							?>
									<div class="latest-prod-slider">
										<?php 
										while($latestProduct = $functions->fetch($latestProductData)){
											$productPermalink = $functions->getProductDetailPageURL($latestProduct['id']);
											$latestProductProductBanner = $functions->getImageUrl('products',$latestProduct['main_image'],'crop','');
										?>
											<div class="fetured-div-slide">
												<div class="product-box">
													<a href="<?php echo $productPermalink; ?>"><img src="<?php echo $latestProductProductBanner; ?>" alt="<?php echo $latestProduct['product_name']; ?>"/></a>
													<div class="prod-details">
														<a href="<?php echo $productPermalink; ?>"><h5><?php echo $latestProduct['product_name']; ?></h5></a>
														<?php 
															if(!empty($latestProduct['discount_price'])){
														?>
																<p class="price">
																	<i class="fa fa-inr" aria-hidden="true"></i> <?php echo $latestProduct['discount_price']; ?> 
																	<strike class="disabled"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $latestProduct['price']; ?></strike>
																</p>
														<?php 
															}else{ ?>
																<p class="price">
																	<i class="fa fa-inr" aria-hidden="true"></i><?php echo $latestProduct['price']; ?> 
																</p>	
														<?php 
															}	?>		
														
														<div class="wc-btn-group">
															<ul class="list-inline">
																<li>
																	<?php 
										                                if(isset($loggedInUserDetailsArr["id"]) && !empty($loggedInUserDetailsArr["id"]) && $loggedInUserDetailsArr['user_type']=="Customer"){
										                            ?>
																			<button type="button" class="btn grey-btn wishlist clsWishlist" data-id="<?php echo $latestProduct['id']; ?>"><img src="<?php echo BASE_URL; ?>/images/wishlist.png" alt=""/>Wishlist</button>
																	<?php 
																		}else{ ?>
																			<a href="<?php echo BASE_URL."/login.php?failed&cusLogin";  ?>" class="btn grey-btn wishlist" tabindex="0"><i class="fa fa-heart-o"></i>Wishlist</a>
																	<?php 
																		} ?>			
																</li>
																<?php 
					                                    			if($latestProduct['availability']>0){ ?>
																		<li>
																			<button type="button" value="<?php echo $latestProduct['id']; ?>" class="btn grey-btn cartListingBtn"><img src="<?php echo BASE_URL; ?>/images/cart.png"/></button>
																		</li>
															<?php 	} ?>
															</ul>
														</div>
														<div class="priceDiv">
					                                        <input type="hidden" name="available_qty" class="available_qty" value="<?php echo $latestProduct["availability"]; ?>">
					                                    </div>
					                                    <?php 
					                                    	if($latestProduct['availability']>0){ ?>
																<button class="bt default-btn listingBuyNow" data-id="<?php echo $latestProduct['id']; ?>">Buy Now</button>
														<?php 	
															} ?>
													</div>
												</div>
											</div>
									<?php 
										} ?>
									</div>
							<?php 
								} ?>		
							<!-- latest-prod-slider End Here--->
						</div>
					</div>
					<div class="col-sm-3">
						<div class="hmc-left">
							<div class="ad-banner">
								<?php 
									$addimage_name_one = $functions->getImageUrl('home_cms',$homePageCms['image_name_one'],'crop','');
								?>
								<a href="<?php echo $homePageCms['adUrlOne']; ?>"><img src="<?php echo $addimage_name_one; ?>" /></a>
							</div>
							<div class="side-featured-tabs">
								<div class="sft-box">
									<img src="images/delivery.png" alt=""/>
									<span>
										<h5>Free Delivery</h5>
										<p>100% Money Back</p>
									</span>
								</div>
								<div class="sft-box">
									<img src="<?php echo BASE_URL; ?>/images/support.png" alt=""/>
									<span>
										<h5>Power Support</h5>
										<p>On Order Over $50</p>
									</span>
								</div>
								<div class="sft-box">
									<img src="<?php echo BASE_URL; ?>/images/security.png" alt=""/>
									<span>
									<h5>Secure Payment</h5>
									<p>Discount ALl Items</p>
									</span>
								</div>
								<div class="sft-box">
									<img src="<?php echo BASE_URL; ?>/images/money.png" alt=""/>
									<span>
									<h5>Money Guarantee</h5>
									<p>100% Money Back</p>
									</span>
								</div>
							</div>
						
							<div class="best-seller">
								<?php 
									$sllerProdcut = $functions->getBestSellerProduct();
									if($functions->num_rows($sllerProdcut)>0){
								?>	
										<h5>Best Seller</h5>
										<div class="best-seller-slider">
										<?php 
											while($bestSellecr = $functions->fetch($sllerProdcut)){
												$productBanner = $functions->getImageUrl('products',$bestSellecr['main_image'],'crop','');
            									$productPermalink = $functions->getProductDetailPageURL($bestSellecr['id']);
										?>	
											  	<div>
													<div class="fetured-div-slide">
														<a href="<?php echo $productPermalink; ?>" class="product-box">
															<img src="<?php echo $productBanner; ?>" alt="<?php echo $bestSellecr["product_name"]; ?>"/>
															<div class="prod-details">
																<h5><?php echo ucwords($bestSellecr["product_name"]); ?></h5>
																<?php 
																	if(!empty($bestSellecr["discount_price"])){
																?>
																		<p class="price"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $bestSellecr["discount_price"]; ?><strike class="disabled"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $bestSellecr["price"]; ?></strike></p>
																<?php 
																	}else{ ?>
																		<p class="price"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $bestSellecr["price"]; ?></p>
																<?php 
																	} ?>		

															</div>
														</a>
													</div>
											  	</div>
										<?php 
											} ?>  	
										</div>
								 <?php 
									} ?> 	
							</div>
						</div>
					</div>
				</div>
			</div>
	  </section>
	  	<?php 
	  	$sql = "SELECT * FROM ".PREFIX."testimonials WHERE `active`='yes' order by id DESC";
        $testiResult = $functions->query($sql);
		if($functions->num_rows($testiResult)>0){
	  	?>
		  	<section class="home-testimonial">
				<div class="container">
					<div class="section-title">
					<h3>Testimonials</h3>
					<div>
					
					<div class="testimonial-slider">
						<?php 
							while($tData = $functions->fetch($testiResult)){
								$tImage = $functions->getImageUrl("testimonials",$tData["image"],"crop","");
						?>
									<div class="ts-inner">
										<div class="describe-box">
											<p><?php echo $tData['testimonial']; ?></p>
											<div class="tm-triangle"></div>
										</div>
										<div class="user-detail">
											<img src="<?php echo $tImage; ?>"/>
											<span>
												<h5><?php echo $tData['name']; ?></h5>
												<p><?php echo $tData['position']; ?></p>
											</span>
										</div>
									</div>
						<?php 
							} ?>			
						
		 			</div>
				</div>
		  	</section>
	  	<?php 
	  	} ?>
	  
         <!--Main End Code Here-->
      <!--footer start menu head-->
      <?php include("include/footer.php");?> 
      <!--footer end menu head-->
      <?php include("include/footer-link.php");?>
         <script>

         </script>
   </body>
</html>