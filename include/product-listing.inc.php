<?php 
   // print_R($_REQUEST);
   $loggedInUserDetailsArr = $functions->sessionExists();
   // print_r($loggedInUserDetailsArr);

   // if(isset($permalink) && !empty($permalink)){
   //    $permalink = $permalink;
   // }
   $productResult = $functions->getFilterProductlist($_REQUEST);
   if($functions->num_rows($productResult)>0){
      while($productDetails = $functions->fetch($productResult)){
            $productBanner = $functions->getImageUrl('products',$productDetails['main_image'],'crop','');
            $productPermalink = $functions->getProductDetailPageURL($productDetails['id'],$_REQUEST);
            if($loggedInUserDetailsArr['user_type']=="b2b"){
              if(!empty($productDetails['b2b_discount_price'])){
                $Jprice = $productDetails['b2b_discount_price'];
              }else{
                $Jprice = $productDetails['b2b_price'];
              }
            }else{
              if(!empty($productDetails['discount_price'])){
                $Jprice = $productDetails['discount_price'];
              }else{
                $Jprice = $productDetails['price'];
              }  
            }
            
?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 match Jprice" data-price="<?php echo $Jprice; ?>" data-id="<?php echo $productDetails['id']; ?>">
                <div class="productlist-item">
                    <div class="product-list-image">
                        <a href="<?php echo $productPermalink; //$permalink."/".$productDetails['permalink']; ?>">
                            <img src="<?php echo $productBanner; ?>" alt="<?php echo $productDetails["product_name"]; ?>" class="img-responsive">
                        </a>
                        <div class="hoverwishlist">
                            <ul>
                            <?php 
                                if(isset($loggedInUserDetailsArr["id"]) && !empty($loggedInUserDetailsArr["id"]) && isset($loggedInUserDetailsArr['user_type'])){
                            ?>
                                    <li>
                                        <button class="btn grey-btn wishlist clsWishlist" data-id="<?php echo $productDetails['id']; ?>" tabindex="0"><i class="fa fa-heart-o"></i></button>
                                    </li>
                                    <li>
                                        <a class="btn grey-btn wishlist" href="<?php echo $productPermalink; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                                    </li>

                                 <?php      }else{ ?>
                                    <li>
                                        <a href="<?php echo BASE_URL."/login.php?failed&cusLogin&redirect=".$productPermalink;  ?>" class="btn grey-btn wishlist" tabindex="0"><i class="fa fa-heart-o"></i></a>
                                    </li>
                                    <li>
                                        <a class="btn grey-btn wishlist" href="<?php echo $productPermalink; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                                    </li>
                     <?php      } ?>
                        
                                <li>
                                    <div class="priceDiv">
                                        <input type="hidden" name="available_qty" class="available_qty" value="<?php echo $productDetails["availability"]; ?>">
                                        <input type="hidden" name="b2b_min_qty" class="b2b_min_qty" value="<?php echo $productDetails["b2b_min_qty"]; ?>">
                                        <button type="button" name="addtoCart" value="<?php echo $productDetails['id']; ?>" class="btn grey-btn cartListingBtn" tabindex="0"><i class="fa fa-cart-plus" aria-hidden="true"></i></button>
                                    </div>
                                </li>
                                <li>
                                <a href="<?php echo $productPermalink; ?>" class="btn grey-btn wishlist">Buy Now</a>
                                </li>
                               
                            </ul>
                        </div>
                    </div>
                    <div class="product-list-details">
                        <a href="<?php echo $productPermalink; ?>"><h3><?php echo $productDetails['product_name']; ?></h3></a>
                        <p class="listpricebox">
                            <?php
                              // print_r($productDetails);die();
                                  if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2c"){
                                    if(!empty($productDetails['discount_price'])){
                                ?>
                                    <span class="sellprice"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['discount_price']; ?></span> 
                                    <!-- <strike class="disabled">
                                        <span class="sellprice"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['price']; ?></span>
                                    </strike> -->
                              <?php  }else{ ?>
                                        <span class="sellprice"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['price']; ?></span>
                            <?php    }
                                  }else if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2b"){
                                    if(!empty($productDetails['b2b_discount_price'])){
                            ?>
                                  <span class="sellprice"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['b2b_discount_price']; ?></span>
                                  <!-- <strike class="disabled">
                                      <span class="sellprice"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['b2b_price']; ?></span>
                                  </strike> -->
                            <?php  }else{ ?>
                                        <span class="sellprice"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['b2b_price']; ?></span>
                            <?php    }
                                  }else{
                                    if(!empty($productDetails['discount_price'])){
                                  ?>
                                    <span class="sellprice"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['discount_price']; ?></span>
                                    <!-- <strike class="disabled">
                                        <span class="sellprice"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['price']; ?></span>
                                    </strike> -->
                            <?php   }else{ ?>
                                        <span class="sellprice"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['price']; ?></span>
                            <?php   } 
                                  }
                            ?>
                        </p>
                        <?php 
                       // if(isset($loggedInUserDetailsArr["id"]) && !empty($loggedInUserDetailsArr["id"])){
                        ?>
                            <!-- <a href="javascript:;" class="buybtn listingBuyNow"  data-id="<?php echo $productDetails['id']; ?>" >Buy Now</a> -->
                        <?php 
                       // }else{ ?>
                            <!-- <a href="<//?php echo $productPermalink; ?>" class="buybtn">View Details</a> -->
                        <?php 
                       // } ?>         
                    </div>
                </div>
            </div>
<?php 
      }
   }else{ ?>
         <br><center style="margin-top: 0px;"><h3>Oops ! Products Not found.</h3></center><br><br><br><br>
<?php    
   } 
?>   