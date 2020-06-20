<?php 
	ob_start();
	$cartArr = $cartObj->getCart();
	if($cartArr){
?>
		<section class="order-summery">
			<div class="cart-table-responsive">
				<table class="table">
				<thead>
				  <tr>
					<th>Item</th>
					<th>Product Name</th>
					<th>Quantity</th>
					<th>price</th>
					  </tr>
				</thead>
				<tbody>
					<?php
						$subTotal = 0;
						$finalTotal = 0;
						foreach($cartArr as $oneProduct){
							$cartProductDetail = $functions->getUniqueProductById($oneProduct['productId']);

							$productBanner = $functions->getImageUrl('products',$cartProductDetail['main_image'],'crop','');

							if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2c"){
                                if(!empty($cartProductDetail['discount_price'])) {
                                    $discountedPrice = $cartProductDetail['discount_price'];
                                    $unitPrice = $discountedPrice;
                                    $price = $discountedPrice * $oneProduct['quantity'];
                                    unset($discountedPrice);
                                } else {
                                	$unitPrice = $cartProductDetail['price'];
                                    $price = $cartProductDetail['price'] * $oneProduct['quantity'];
                                }
                            }else if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2b"){
                                if(!empty($cartProductDetail['b2b_discount_price'])) {
                                    $discountedPrice = $cartProductDetail['b2b_discount_price'];
                                    $unitPrice = $discountedPrice;
                                    $price = $discountedPrice * $oneProduct['quantity'];
                                    unset($discountedPrice);
                                } else {
                                	$unitPrice = $cartProductDetail['price'];
                                    $price = $cartProductDetail['b2b_price'] * $oneProduct['quantity'];
                                }
                            }else{
                                if(!empty($cartProductDetail['discount_price'])) {
                                    $discountedPrice = $cartProductDetail['discount_price'];
                                    $unitPrice = $discountedPrice;
                                    $price = $discountedPrice * $oneProduct['quantity'];
                                    unset($discountedPrice);
                                } else {
                                	$unitPrice = $cartProductDetail['price'];
                                    $price = $cartProductDetail['price'] * $oneProduct['quantity'];
                                }
                            }
							$subTotal += $price;
					?>
							   	<tr>
									<td>
										<div class="item-thumb">
											<img src="<?php echo $productBanner; ?>" alt=""/>
										</div>
									</td>
									<td>
										<ul class="product-name">
											<li><?php echo $cartProductDetail['product_name'];  ?></li>
										</ul>
									</td>
									<td class="input-group1">
										<div class="input-num">
											<ul class="list-inline">
												<li><button type="button" class="btn-number <?php echo $cartDecrementClass; ?>" <?php if($oneProduct['quantity']<=1){ echo 'disabled="disabled"'; } ?> data-type="minus" data-field="qty[<?php echo $cartProductDetail['id']; ?>]">-</button></li>
												<li><input type="number" id="number" name="qty[<?php echo $cartProductDetail['id']; ?>]" class="number percent_amount cartQty" value="<?php echo $oneProduct['quantity']; ?>" min="<?php if($loggedInUserDetailsArr['user_type']=='b2b'){ echo $cartProductDetail['b2b_min_qty']; }else{ echo "1"; } ?>" max="<?php echo $cartProductDetail['availability']; ?>" readonly></li>
												<li><button type="button" class="btn-number <?php echo $cartIncrementClass; ?>" data-type="plus" <?php if($oneProduct['quantity']>=$cartProductDetail['availability']){ echo 'disabled="disabled"'; } ?> data-type="plus" data-field="qty[<?php echo $cartProductDetail['id']; ?>]">+</button></li>
											</ul>
										</div>
										<input type="hidden" name="productNo" value="<?php echo $cartProductDetail['id']; ?>" />
										<input type="hidden" value="<?php echo $cartProductDetail['availability']; ?>" name="available_qty" class="available_qty" />
										<input type="hidden" name="b2b_min_qty" class="b2b_min_qty" value="<?php echo $cartProductDetail['b2b_min_qty']; ?>">
									</td>
									<td>
										<p class="carttableprice"> <i class="fa fa-inr" aria-hidden="true"></i><?php echo $price; ?></p>
									</td>
									<!-- <td><button class="delete-btn"><img src="images/delete.png"></button></td> -->
								</tr>
					<?php 
						} ?>
				</tbody>
				</table>
			</div>
			<div class="cart-tatal-sec">
				<div class="cart-total text-right">
					<div class="cart-sub-total">
						<p class="sub-ttl-title pull-right width30"><b><i class="fa fa-inr" aria-hidden="true"></i><?php echo $subTotal; ?></b></p>
						<p class="sub-ttl-title pull-right">Sub Total</p>
						<div class="clearfix"></div>
					</div>
					<div>
						<form class="apply-coupon-form">
							<?php
								// CHECK IF DISCOUNT COUPON IS VALID FOR THIS USER
								$loggedInUserDetailsArr = $functions->sessionExists();
								if( isset($loggedInUserDetailsArr) && !empty($loggedInUserDetailsArr) && count($loggedInUserDetailsArr)>0 &&
									isset($_SESSION[SITE_NAME]['couponCode']) && !empty($_SESSION[SITE_NAME]['couponCode'])){ // user is logged in, apply discount
								
									$subTotalArr = $functions->getNewSubtotalAfterCouponCode($subTotal, $cartObj, $loggedInUserDetailsArr);
									$couponDiscount = $subTotalArr['couponDiscount'];
									$finalTotal = $subTotalArr['subTotal'];
							?>
									<p>
										<span>Coupon Discount</span>
										<span>- <?php echo $couponDiscount; ?> INR 
										<button class="btn btn-danger removeCouponCodeCheckoutBtn" type="button">x</button></span>
										<div class="clearfix"></div>
									</p>
						<?php 	}else{ 
									$finalTotal = $subTotal;
							?>
										<div class="input-group input-group1">
											<input id="coupon-code" type="text" class="form-control" name="couponCode" placeholder="Enter Your Coupon Code">
											<span class="input-group-addon coupon-addon <?php echo $couponCodeApplyId; ?>">Apply</span>
											<!-- <br class="visible-xs"> -->
											<div class="clearfix"></div>
										</div>
						<?php 	} ?>				
									<p class="couponErrorMsg" style="color: rgb(255, 0, 0);"></p>

						</form>
					</div>
					<?php
					if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']) and isset($_SESSION[SITE_NAME]['BILLADDRESS']['Billing'])){ ?>
					<div class="summery cart-delivery ">
						<?php 
						$shippingCharges = 0;
						// $shippingCharges = $functions->getShippingCharge($cartObj,$loggedInUserDetailsArr)
						include_once 'fedex/Rate/RateWebServiceClient.php';
						$shippingCharges = $content;
						?>
						<p class="sub-ttl-title pull-right rs-span width30"><i class="fa fa-inr" aria-hidden="true"></i> <b><?php echo $shippingCharges; ?></b></p>
						<p class="sub-ttl-title pull-right delivery">Delivery</p>
						<div class="clearfix"></div>
					</div>
				<?php } ?>
					<div class="ttl-div">
						<span class="inclusive">(*Inclusive of all Taxes )</span>
						<span class="span-ttl span-ttl-rs">Total </span> <span class="span-ttl-rs width30"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $finalTotal + $shippingCharges; ?></span>
					</div>
						<div class="payment-mode">
						<ul class="list-inline">
							<li>
							Payment Method
							</li>
							<li>
								<label class="radio-container" for="online">COD
								<input type="radio" id="online" checked="checked" name="payment_method" value="COD" class="pay">
								<span class="checkmark"></span>
								</label>
							</li>
							<li>
								<label class="radio-container" for="cod">Online Payment
								<input type="radio" id="cod" name="payment_method" value="ONLINE" class="pay">
								<span class="checkmark"></span>
								</label>
							</li>
						</ul>
					</div>
					<div class="clearfix"></div>
					
					<div class="checkout-btns">
						<a href="<?php echo BASE_URL; ?>" class="shop-now-btn dark-yellow-btn text-center">Continue Shopping</a>
						<?php 	
							if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']) && !empty($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']) && isset($_SESSION[SITE_NAME]['BILLADDRESS']['Billing']) && !empty($_SESSION[SITE_NAME]['BILLADDRESS']['Billing'])){ ?>
								<a href="javascript:;" id="proceedCheckout" class="shop-now-btn purple-btn text-center" id="proceedCheckout">Proceed to CheckOut</a>
						<?php 
							}else{ ?>
								<a href="javascript:;" class="shop-now-btn purple-btn text-center" id="proceedCheckout">Proceed to CheckOut</a>	
						<?php 
							} ?>			
					</div>
				</div>
			</div>
		</section>
<?php
	}
	$checkoutCartPageHTML = ob_get_contents(); // do not change variable name $checkoutCartPageHTML
	ob_end_clean();
?>