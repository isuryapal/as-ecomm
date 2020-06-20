<?php 
   include_once 'include/functions.php';
  $functions = new Functions();
  $sliderDetails = $functions->getSliderbBanner();
  $homePageCms = $functions->getHomePageCms();
  $loggedInUserDetailsArr = $functions->sessionExists();
  ?>
<!DOCTYPE>
<html>
   <head>
      <title>Arvind Sanitary</title>
      <?php include("include/header-link.php");?>
      <style>
          .onhover a.product-box {
                display: none;
            }
            .trending .slick-slider .slick-slide img {
               height: 320px;
            }
      </style>
   </head>
   <body class="home">
      <!--Top start menu head-->       
      <?php include("include/header.php");?>
      <section class="banners">
         <div class="banner-slider">
          <?php
          if($functions->num_rows($sliderDetails)>0){
            while($sliderRow = $functions->fetch($sliderDetails)){
              $sliderBanner = $functions->getImageUrl('slider-banner',$sliderRow['image_name'],'crop','');
          ?>
            <div class="slide">
               <a href="<?php echo $sliderRow['link']; ?>"> <img src="<?php echo $sliderBanner; ?>" alt=""></a>
            </div>
          <?php
            }
          }else{
          ?>
            <div class="slide">
               <a href="listing.php"> <img src="images/design-1.png" alt=""></a>
            </div>
          <?php
          }
          ?>
            <!-- <div class="slide">
               <a href="listing.php"> <img src="images/design-1.png" alt=""></a>
            </div> -->
         </div>
      </section>
      <section class="wel-arvind">
         <div class="container">
            <div class="row">
               <div class="col-md-7">
                  <div class="wel-text">
                     <h2>Welcome To arvind sanitary</h2>
                     <p>
                        Established in 2014, Arvind Sanitary has its base in Palghar, Maharashtra and is a leader in the field of manufacturing of different designer basins, wash basins and many more attractive products. <br><br>
                        The USP of our products lies in the fact that they are procured from the most reliable sources and utmost care is taken to ensure that they are of the best and the most highest quality. We dedicate all our resources towards ensuring that our products meet the quality standards.Our constant dedication to quality has enabled us to ensure that we make a name for ourselves in this domain.
                     </p>
                  </div>
               </div>
               <div class="col-md-5">
                  <div class="img-box">
                     <img src="images/welimg.png" alt="">
                  </div>
               </div>
            </div>
         </div>
      </section>
      <section class="trending">
         <div class="container">
            <div class="title-main">
               <h2>TRENDING PRODUCTS</h2>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
               <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#home" role="tab" aria-controls="home">Latest Products</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#profile" role="tab" aria-controls="profile">Featured Products</a>
               </li>
            </ul>
            <div class="tab-content">
               <div class="tab-pane active" id="profile" role="tabpanel">
                  <div class="trend-slider">
                    <?php 
                        $fetaureData = $functions->getFeaturedProduct();
                        if($functions->num_rows($fetaureData)>0){
                            while($fetaureProduct = $functions->fetch($fetaureData)){
                              // print_r($fetaureProduct);die();
                            $productPermalink = $functions->getProductDetailPageURL($fetaureProduct['id'],$_GET);
                            $productBanner = $functions->getImageUrl('products',$fetaureProduct['main_image'],'crop','');
                    ?>

                     <div class="slide">
                        <a href="<?php echo $productPermalink; ?>" class="product-box">
                        <div class="slidebox">
                           <img src="<?php echo $productBanner; ?>" alt="">
                           <div class="prize-box">
                              <h4><?php echo $fetaureProduct['product_name']; ?></h4>
                              <h5>
                                <?php
                                  if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2c"){
                                    if(!empty($fetaureProduct['discount_price'])){
                                ?>
                                    <i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['discount_price']; ?> 
                                    <strike class="disabled">
                                        <i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['price']; ?>
                                    </strike>
                              <?php  }else{ ?>
                                        <i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['price']; ?>
                            <?php    }
                                  }else if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2b"){
                                    if(!empty($fetaureProduct['b2b_discount_price'])){
                            ?>
                                  <i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['b2b_discount_price']; ?> 
                                  <strike class="disabled">
                                      <i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['b2b_price']; ?>
                                  </strike>
                            <?php  }else{ ?>
                                        <i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['b2b_price']; ?>
                            <?php    }
                                  }else{
                                    if(!empty($fetaureProduct['discount_price'])){
                                  ?>
                                    <i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['discount_price']; ?> 
                                    <strike class="disabled">
                                        <i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['price']; ?>
                                    </strike>
                            <?php   }else{ ?>
                                        <i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetaureProduct['price']; ?>
                            <?php   } 
                                  }
                            ?>
                            </h5>
                           </div>
                           <div class="onhover priceDiv">
                            <?php
                            if(isset($loggedInUserDetailsArr["id"]) && !empty($loggedInUserDetailsArr["id"]) && isset($loggedInUserDetailsArr['user_type'])){
                            ?>
                              <!-- <a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a> -->
                              <a class="wishlist clsWishlist" data-id="<?php echo $fetaureProduct['id']; ?>" tabindex="0"><i class="fa fa-heart-o"></i></a>

                              <a class="btn grey-btn wishlist" href="<?php echo $productPermalink; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                        <?php }else{ ?>
                              <a href="<?php echo BASE_URL."/login.php?failed&cusLogin&redirect=".$productPermalink;  ?>" class="btn grey-btn wishlist" tabindex="0"><i class="fa fa-heart-o"></i></a>
                              <a class="btn grey-btn wishlist" href="<?php echo $productPermalink; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                              <!-- <a class="btn grey-btn wishlist" href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a> -->
                        <?php } ?>
                              <!-- <a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a> -->
                              <input type="hidden" name="available_qty" class="available_qty" value="<?php echo $fetaureProduct["availability"]; ?>">
                              <input type="hidden" name="b2b_min_qty" class="b2b_min_qty" value="<?php echo $fetaureProduct["b2b_min_qty"]; ?>">
                              <button type="button" name="addtoCart" value="<?php echo $fetaureProduct['id']; ?>" class="btn btn-grey cartListingBtn"><i class="fa fa-cart-plus" aria-hidden="true"></i></button>
                              <a href="<?php echo $productPermalink; ?>">Buy Now</a>
                           </div>
                        </div>
                    </a>
                     </div>
                    <?php       }
                            }
                     ?>
                  </div>
               </div>
               <div class="tab-pane" id="home" role="tabpanel">
                        <?php 
                $latestProductData = $functions->gelatestProduct();
                if($functions->num_rows($latestProductData)>0){
                ?>
                <div class="trend-slider1">
                    <?php 
                    while($latestProduct = $functions->fetch($latestProductData)){
                      // print_r($_GET);
                        $productPermalink = $functions->getProductDetailPageURL($latestProduct['id'],$_GET);
                        $latestProductProductBanner = $functions->getImageUrl('products',$latestProduct['main_image'],'crop','');
                    ?>

                     <div class="slide">
                        <a href="<?php echo $productPermalink; ?>" class="product-box">
                        <div class="slidebox">
                           <img src="<?php echo $latestProductProductBanner; ?>" alt="">
                           <div class="prize-box">
                              <h4><?php echo $latestProduct['product_name']; ?></h4>
                              <h5>
                                  <?php
                                  if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2c"){
                                    if(!empty($latestProduct['discount_price'])){
                                ?>
                                    <i class="fa fa-inr" aria-hidden="true"></i><?php echo $latestProduct['discount_price']; ?> 
                                    <strike class="disabled">
                                        <i class="fa fa-inr" aria-hidden="true"></i><?php echo $latestProduct['price']; ?>
                                    </strike>
                              <?php  }else{ ?>
                                        <i class="fa fa-inr" aria-hidden="true"></i><?php echo $latestProduct['price']; ?>
                            <?php    }
                                  }else if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2b"){
                                    if(!empty($latestProduct['b2b_discount_price'])){
                            ?>
                                  <i class="fa fa-inr" aria-hidden="true"></i><?php echo $latestProduct['b2b_discount_price']; ?> 
                                  <strike class="disabled">
                                      <i class="fa fa-inr" aria-hidden="true"></i><?php echo $latestProduct['b2b_price']; ?>
                                  </strike>
                            <?php  }else{ ?>
                                        <i class="fa fa-inr" aria-hidden="true"></i><?php echo $latestProduct['b2b_price']; ?>
                            <?php    }
                                  }else{
                                    if(!empty($latestProduct['discount_price'])){
                                  ?>
                                    <i class="fa fa-inr" aria-hidden="true"></i><?php echo $latestProduct['discount_price']; ?> 
                                    <strike class="disabled">
                                        <i class="fa fa-inr" aria-hidden="true"></i><?php echo $latestProduct['price']; ?>
                                    </strike>
                            <?php   }else{ ?>
                                        <i class="fa fa-inr" aria-hidden="true"></i><?php echo $latestProduct['price']; ?>
                            <?php   } 
                                  }
                            ?>
                            </h5>
                           </div>
                           <div class="onhover priceDiv">
                              <?php
                            if(isset($loggedInUserDetailsArr["id"]) && !empty($loggedInUserDetailsArr["id"]) && isset($loggedInUserDetailsArr['user_type'])){
                            ?>
                              <!-- <a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a> -->
                              <a class="wishlist clsWishlist" data-id="<?php echo $latestProduct['id']; ?>" tabindex="0"><i class="fa fa-heart-o"></i></a>
                              <a class="btn grey-btn wishlist" href="<?php echo $productPermalink; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>

                        <?php }else{ ?>
                              <a href="<?php echo BASE_URL."/login.php?failed&cusLogin&redirect=".$productPermalink;  ?>" class="btn grey-btn wishlist" tabindex="0"><i class="fa fa-heart-o"></i></a>
                              <a class="btn grey-btn wishlist" href="<?php echo $productPermalink; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                              <!-- <a class="btn grey-btn wishlist" href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a> -->
                        <?php } ?>
                              <!-- <a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a> -->
                              <input type="hidden" name="available_qty" class="available_qty" value="<?php echo $latestProduct["availability"]; ?>">
                              <input type="hidden" name="b2b_min_qty" class="b2b_min_qty" value="<?php echo $latestProduct["b2b_min_qty"]; ?>">
                              <button type="button" name="addtoCart" value="<?php echo $latestProduct['id']; ?>" class="btn btn-grey cartListingBtn"><i class="fa fa-cart-plus" aria-hidden="true"></i></button>
                              <a href="<?php echo $productPermalink; ?>">Buy Now</a>
                           </div>
                        </div>
                    </a>
                     </div>
                    <?php       }
                            }
                     ?>
                </div>
               </div>
            </div>
         </div>
      </section>
      <section class="testii">
         <div class="container">
            <div class="title-main">
               <h2>testimonials</h2>
            </div>
            <div class="test-slider">
                <?php
                $testimonialDetails = $functions->getAllTestimonials();
                $i=0;
                if($functions->num_rows($testimonialDetails)>0){
                  while($testimonialRow = $functions->fetch($testimonialDetails)){
                    $testimonialImage = $functions->getImageUrl('testimonials',$testimonialRow['image'],'crop','');
                    $cls_nam="";
                    if ($i%2 == 0) {
                      $cls_nam="reverse";
                    }
                    $i++;
                ?>
                <div class="slides <?php echo $cls_nam; ?>">
                  <div class="test-text">
                     <div class="quotess">
                        <img src="images/quotess.png" alt="">
                     </div>
                     <div class="richtext testimonialscroller">
                        <?php echo $testimonialRow['testimonial']; ?>
                     </div>
                     <h3><?php echo $testimonialRow['name']; ?></h3>
                  </div>
                  <div class="testimgg">
                     <div class="imgg">
                        <img src="<?php echo $testimonialImage; ?>" alt="">
                     </div>
                  </div>
               </div>
            <?php }
                } ?>
            </div>
         </div>
      </section>
      <section class="dell">
         <div class="container">
            <div class="row pad-wop">
               <div class="col-md-4">
                  <div class="dellll">
                     <div class="imggg">
                        <img src="images/free.png" alt="">
                     </div>
                     <h3>Free Delivery</h3>
                     <!-- <p>Lorem Ipsum is simply dummy text printing</p> -->
                  </div>
               </div>
               <div class="col-md-4 borderr">
                  <div class="dellll">
                     <div class="imggg">
                        <img src="images/free1.png" alt="">
                     </div>
                     <h3>24/7 Customer Support</h3>
                     <!-- <p>Lorem Ipsum is simply dummy text printing</p> -->
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="dellll">
                     <div class="imggg">
                        <img src="images/free2.png" alt="">
                     </div>
                     <h3>Return of Goods</h3>
                     <!-- <p>Lorem Ipsum is simply dummy text printing</p> -->
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
      <script></script>
   </body>
</html>
  