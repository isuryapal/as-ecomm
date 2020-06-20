<?php 
    ob_start();
    $loggedInUserDetailsArr = $functions->sessionExists();
    $cartArr = $cartObj->getCart();

    $amtArr = $functions->getCartAmountAndQuantity($cartObj, null);

?>
<div class="cart-sidebar">
    <div class="cart-sidebar-body-out">
        <div class="cart-sidebar-body">
                <div class="cart-head">
                    <h3>  <a class="close-cart-div"><i class="fa fa-angle-left" aria-hidden="true"></i> </a>Shopping Bag<span>(<?php echo $amtArr['items']; ?>)</span> </h3>
                </div>
            <?php 
            if($cartArr){
            ?>
                <div class="cart-body">
                    <div class="cart-list">
                        <?php
                        $subTotal = 0;
                        $finalTotal = 0;
                        //print_r($cartArr);
                        foreach($cartArr as $oneProduct){
                            $cartProductDetail = $functions->getUniqueProductById($oneProduct['productId']);
                            
                            //$productPrice = $functions->getProductPriceByPriceId($oneProduct['price_id']);
                            //$productSizeDetails = $functions->getUniqueProductById($oneProduct['productId']);

                            
                            $productBanner = $functions->getImageUrl('products',$cartProductDetail['main_image'],'crop','');    
                            

                            $currentPageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME); // eg: example.php
                            if($currentPageName=="input-group" || (isset($isCartPage) && $isCartPage=="2") || $currentPageName=="chekout-order-summary.php" || $currentPageName=="shipping.php" || $currentPageName=="payment-method.php"){
                                $cartIncrementClass = "checkoutPageIncrementFromCartBtn";
                                $cartDecrementClass = "checkoutPageDecrementFromCartBtn";
                                $cartRemoveClass = "checkoutPageRemoveFromCartBtn";

                                $couponCodeApplyId = "applyCouponCodeCheckoutBtn";
                                $couponCodeRemoveId = "removeCouponCodeCheckoutBtn";

                                $displayingCheckoutPageCart = true;
                            } else {
                                $cartIncrementClass = "incrementCartBtn";
                                $cartDecrementClass = "decrementCartBtn";
                                $cartRemoveClass = "removeFromCartBtn";

                                $couponCodeApplyId = "applyCouponCodeCartBtn";
                                $couponCodeRemoveId = "removeCouponCodeCartBtn";

                                $displayingCheckoutPageCart = false;
                            }
                            if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2c"){
                                if(!empty($cartProductDetail['discount_price'])) {
                                    $discountedPrice = $cartProductDetail['discount_price'];
                                    $price = $discountedPrice * $oneProduct['quantity'];
                                    unset($discountedPrice);
                                } else {
                                    $price = $cartProductDetail['price'] * $oneProduct['quantity'];
                                }
                            }else if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2b"){
                                if(!empty($cartProductDetail['b2b_discount_price'])) {
                                    $discountedPrice = $cartProductDetail['b2b_discount_price'];
                                    $price = $discountedPrice * $oneProduct['quantity'];
                                    unset($discountedPrice);
                                } else {
                                    $price = $cartProductDetail['b2b_price'] * $oneProduct['quantity'];
                                }
                            }else{
                                if(!empty($cartProductDetail['discount_price'])) {
                                    $discountedPrice = $cartProductDetail['discount_price'];
                                    $price = $discountedPrice * $oneProduct['quantity'];
                                    unset($discountedPrice);
                                } else {
                                    $price = $cartProductDetail['price'] * $oneProduct['quantity'];
                                }
                            }
                            $subTotal += $price;
                        ?>
                            <div class="crat-item">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-3 paddingr0 img-cart">
                                        <img src="<?php echo $productBanner; ?>">
                                    </div>
                                    <div class="col-md-9 col-sm-9 col-xs-9 text-left img-desc-cart">
                                        <h4 class="cart-p-name"><?php echo $cartProductDetail['product_name']; ?></h4>
                                        <a href="javascript:;" class="remove <?php echo $cartRemoveClass; ?>" data-id="<?php echo $cartProductDetail['id']; ?>">
                                            <span>
                                                <img src="<?php echo BASE_URL; ?>/images/delete2.png">
                                            </span>
                                        </a>
                                        <div class="numberiouis input-group1">
                                            <div class="value-button btn-number <?php echo $cartDecrementClass; ?>" <?php if($oneProduct['quantity']<=1){ echo 'disabled="disabled"'; } ?> data-type="minus" data-field="qty[<?php echo $cartProductDetail['id']; ?>]">-</div>
                                                <input type="number" class="cartQty" name="qty[<?php echo $cartProductDetail['id']; ?>]" value="<?php echo $oneProduct['quantity']; ?>" min="1" max="<?php echo $cartProductDetail['availability']; ?>" readonly/>
                                            <div class="value-button btn-number <?php echo $cartIncrementClass; ?>" data-type="plus" <?php if($oneProduct['quantity']>=$cartProductDetail['availability']){ echo 'disabled="disabled"'; } ?> data-type="plus" data-field="qty[<?php echo $cartProductDetail['id']; ?>]">+</div>
                                            
                                            <input type="hidden" name="productNo" value="<?php echo $cartProductDetail['id']; ?>" />
                                            <input type="hidden" value="<?php echo $cartProductDetail['availability']; ?>" name="available_qty" class="available_qty" />
                                            <input type="hidden" value="<?php echo $cartProductDetail['b2b_min_qty']; ?>" name="b2b_min_qty" class="b2b_min_qty" />
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 deno-price">
                                        <ul class="pricelist">
                                            <li class="priclisty">
                                                <p><i class="fa fa-inr" aria-hidden="true"></i><?php echo $price; ?></p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                    <?php 
                        } ?>
                    </div>
                    <hr class="paymentbf">
                    <div class="cart-total">
                        <h2>
                            Payment Details
                        </h2>
                        <div class="car-demop">
                            <div class="cart-sub-total">
                                <p class="sub-ttl-title pull-right width30"><i class="fa fa-inr"></i><?php echo $subTotal ?></p>
                                <p class="sub-ttl-title pull-right">Sub Total</p>

                                <div class="clearfix"></div>
                            </div>
                            <div>
                                <?php 
                                // echo  $subTotal;
                                //     print_r($loggedInUserDetailsArr);
                                $couponDiscount = 0;
                                if(isset($_SESSION[SITE_NAME]['couponCode'])){
                                    $subTotalArr = $functions->getNewSubtotalAfterCouponCode($subTotal, $cartObj, $loggedInUserDetailsArr);
                                    $couponDiscount = $subTotalArr['couponDiscount'];
                                    $subTotal = $subTotalArr['subTotal'];
                                }
                                ?>
                                <form class="apply-coupon-form">
                                    <div class="input-group input-group1">
                                        <input value="<?php if(isset($_SESSION[SITE_NAME]['couponCode'][0]['couponCode'])){ echo $_SESSION[SITE_NAME]['couponCode'][0]['couponCode']; } ?>" id="coupon-code" type="text" class="form-control" name="couponCode" placeholder="Enter Your Coupon Code">
                                        <?php 
                                            if( !isset($_SESSION[SITE_NAME]['couponCode'])){  ?>
                                                <button type="button" class="coupon-addon <?php echo $couponCodeApplyId; ?>">Apply</button>
                                        <?php 
                                            }else{ ?>
                                                <button type="button" class="coupon-addon <?php echo $couponCodeRemoveId; ?>">Remove</button>
                                        <?php 
                                            } ?>        
                                            
                                            <span class="font-red coupon-price width30">(-) Rs. <?php echo $couponDiscount;  ?></span>
                                        <div class="clearfix"></div>

                                    </div>
                                    <p class="couponErrorMsg"></p>
                                </form>
                            </div>
                            <?php
                            $shippingCharges = '';
                            if(isset($loggedInUserDetailsArr['id']) && isset($_SESSION[SITE_NAME]['BILLADDRESS']['shipping'])){ ?>
                                <div class="cart-delivery">
                                    <?php 
                                    // echo "CART PAGE ".$subTotal;
                                    // $shippingCharges = $functions->getShippingCharge($cartObj,$loggedInUserDetailsArr);
                                    // if(!empty($loggedInUserDetailsArr['id'])){
                                    $onePro = dirname(dirname(__DIR__));
                                    include_once $onePro.'/fedex/Rate/RateWebServiceClient.php';
                                    $shippingCharges = intval($content);
                                    // }else{
                                    //     $shippingCharges = 50;
                                    // }
                                    ?>
                                    <p class="sub-ttl-title pull-right rs-span width30"><i class="fa fa-inr"></i> <?php echo $shippingCharges; ?></p>
                                    <p class="sub-ttl-title pull-right">Delivery</p>
                                    <div class="clearfix"></div>
                                </div>
                            <?php } ?>
                            <div class="ttl-div">
                                <span class="span-ttl">Total </span> <span class="span-ttl-rs width30"><i class="fa fa-inr"></i><?php echo $subTotal + $shippingCharges; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart-footer">
                    <?php 
                        if(isset($loggedInUserDetailsArr['id']) && !empty($loggedInUserDetailsArr['id'])){
                    ?>
                            <a href="<?php echo BASE_URL; ?>/chekout-order-summary.php" class="shop-now-btn purple-btn text-center"> Proceed to Checkout <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                    <?php   
                        }else{ ?>
                            <a href="<?php echo BASE_URL."/login.php?redirect=chekout-order-summary.php"; ?>" class="shop-now-btn purple-btn text-center"> Proceed to Checkout <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                    <?php   
                        } ?>
                    
                </div>
    <?php   }else{
        ?>
                <br><br><br><br><br><center><h2>No Products in Cart</h2></center><br>
                <br>
                <center>
                    <br>
                        <img src="<?php echo BASE_URL; ?>/images/thankyou.png" class="thanksimg"> 
                    <br>
                    <br>
                    <ul class="reset">
                        <li><a href="<?php echo BASE_URL ?>" class="shop-now-btn dark-yellow-btn text-center">Continue Shopping</a></li>
                    </ul>
                </center>
    <?php   }   ?>
            </div>
    
    </div>
</div>
<div class="body-overlay"></div>
<?php 
    $cartHTML = ob_get_contents();
    ob_end_clean();
?>