<?php
   include_once 'include/functions.php';


   $functions = new Functions(); 
   $permalink = '';
   $displayName ='Brand';
   if(isset($_GET['permalink']) && !empty($_GET['permalink'])){
      $breadcrumbs = '';
      $brandPerma = $functions->escape_string($functions->strip_all($_GET['permalink']));
      $brandDetails = $functions->getBrandbyPermlink($brandPerma);
      // echo $brandDetails['id'];
      // die();
      
      if(isset($brandDetails['id']) && !empty($brandDetails['id'])){
         $brandId = $functions->escape_string($functions->strip_all($brandDetails['id']));
         // $productGet = $functions->getProductbyBrandId($brandId);
         // die();
         $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
         $breadcrumbs .= "<li><a href='javascript:;'>".ucwords($brandDetails['brand_name'])."</a></li>";
         $permalink = BASE_URL."/".$brandDetails['permalink'];
         $displayName  = $brandDetails['brand_name'];
         $catBanner = $functions->getImageUrl('brand',$brandDetails['image_name'],'crop','');
      }else{
         header("location".BASE_URL."?INVALIDBRANDID");
         exit;
      }
      $mtitle = $brandDetails['brand_name'];
   }

   if(isset($_GET['category_id']) && !empty($_GET['category_id'])){
      $breadcrumbs = '';
      $categoryId = $functions->escape_string($functions->strip_all($_GET['category_id']));
      $catDetails = $functions->getCategorybyPermlink($categoryId);
      
      if(isset($catDetails['id']) && !empty($catDetails['id'])){
         $catId = $functions->escape_string($functions->strip_all($catDetails['id']));
         $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
         $breadcrumbs .= "<li><a href='javascript:;'>".ucwords($catDetails['category_name'])."</a></li>";
         $permalink = BASE_URL."/".$brandDetails['permalink']."/".$catDetails['permalink'];
         $displayName  = $catDetails['category_name'];
         $catBanner = $functions->getImageUrl('category',$catDetails['banner_image'],'crop','');
      }else{
         header("location".BASE_URL."?INVALIDCATID");
         exit;
      }
      $mtitle = $catDetails['meta_title'];
      $mdecription = $catDetails['meta_description'];
      $mkey = $catDetails['meta_keyword'];
   }
   
  
   if(isset($_GET['sub_category_id']) && !empty($_GET['sub_category_id'])){
      $breadcrumbs = '';
      $subCategoryDetails  = $functions->getSuBCatByPermalink($_GET['sub_category_id'],$categoryId);
      // print_r($subCategoryDetails);
      if(isset($subCategoryDetails['id']) && !empty($subCategoryDetails['id'])){
         $brandId = $functions->escape_string($functions->strip_all($brandDetails['id']));
         $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$brandDetails['permalink']."'>".ucwords($brandDetails['category_name'])."</a></li>";
         $catBanner = $functions->getImageUrl('category',$brandDetails['banner_image'],'crop','');
         $permalink = BASE_URL."/".$brandDetails['permalink']."/".$brandDetails['category_name']."/".$subCategoryDetails['sub_category_permalink'];
         $displayName  = $subCategoryDetails['sub_category_name'];
      }else{
         header("location".BASE_URL."?INVALIDbrandID");
         exit;
      }
   
   }

   if(isset($_GET['subSub_category_id']) && !empty($_GET['subSub_category_id'])){
      $breadcrumbs = '';
      $subSubCategoryDetails  = $functions->getSubSubCatByPermalink($_GET['subSub_category_id']);
      //print_r($subSubCategoryDetails);
      if(isset($subSubCategoryDetails['id']) && !empty($subSubCategoryDetails['id'])){
         $brandId = $functions->escape_string($functions->strip_all($brandDetails['id']));
         $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$brandDetails['permalink']."'>".ucwords($brandDetails['category_name'])."</a></li>";
         $breadcrumbs .= "<li><a href='javascript:;'>".ucwords($subSubCategoryDetails['subcategory_name'])."</a></li>";
         $catBanner = $functions->getImageUrl('category',$brandDetails['banner_image'],'crop','');
         $permalink = BASE_URL."/".$brandDetails['permalink']."/".$brandDetails['category_name']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink'];
         $displayName  = $subSubCategoryDetails['subcategory_name'];
      }else{
         header("location".BASE_URL."?INVALIDbrandID");
         exit;
      }

   }

?>
<!DOCTYPE>
<html>
   <head>
	<title><?php echo $mtitle; ?></title>
      <meta name="title" content="<?php echo $mtitle; ?>">
      <!-- <meta name="description" content="<?php echo $mdecription; ?>"> -->
      <!-- <meta name="keywords" content="<?php echo $mkey; ?>"> -->
      <?php include("include/header-link.php");?>
   </head>
   <body class="listing-body">
      <!--Top start menu head-->       
      <?php include("include/header.php");?>
      
      <div class="only-breadcrumbs">
         <div class="container">
            <ul class="breadcrumbs">
               <?php echo $breadcrumbs; ?>
            </ul>
         </div>
      </div>

      <section class="listing-section ">
         <div class="container-fluid">
            <div class="row reverselistingsection">
               <div class="col-md-3">
                  <div class="sidebar">
                     <section class="Price-filter-section">
                        <?php 
                              if(isset($brandId) && !empty($brandId)){
                                 $brandId = $brandId;
                              }else{
                                 if(isset($catProductID) && !empty($catProductID) && is_array($catProductID)){
                                    $brandId = implode(",", $catProductID);
                                 }else{
                                    $brandId = 0;
                                 }
                              }
                              $result = $functions->getAttributeCategory($brandId);
                              $attrArr = array();
                              if($functions->num_rows($result)>0){
                                 while($rows = $functions->fetch($result)){
                                   $attributeDetaisl =  $functions->getAttributByCateId($rows['id']);
                                   if($functions->num_rows($attributeDetaisl)>0){
                                       while($attribute = $functions->fetch($attributeDetaisl)){
                                          if(!in_array($attribute['id'], $attrArr)){
                                             $attrArr[] = $attribute['id'];
                           ?>
                                             <h5><img src="<?php echo BASE_URL; ?>/images/bars.png" alt="" style="width:auto;"> <?php echo ucwords($attribute['attribute_name']); ?></h5>
                                                <div class="listingfilter">
                                                   <div class="dropdown multiselect open">
                                                         <?php 
                                                            $attrFeature =  $functions->getAttributeFeaturebyAttrId($attribute['id']);
                                                            if($functions->num_rows($attrFeature)>0){
                                                               while($attr = $functions->fetch($attrFeature)){
                                                                  //$productmap = $functions->isAttributeFeaturemMapWithAnyProduct($attr['id']);
                                                                  //if($productmap){
                                                         ?>
                                                                     <label class="pfcontainer">
                                                                        <span class="pflabel"><?php echo ucwords($attr['feature']); ?><!-- (<span>2</span>) -->  </span>
                                                                        <input type="checkbox" class="attrFeature" id="action<?php echo $attr['id']; ?>" value="<?php echo $attr['id'] ?>">
                                                                        <span class="pfcheckmark"></span>
                                                                     </label>
                                                         <?php    //} 
                                                               }
                                                            }
                                                         ?>  
                                                      
                                                   </div>
                                                </div>
                              <?php          }
                                          }
                                       }
                                    }
                                 }
                              ?>
                        
                     </section>
                     <?php
                        $featureResult =  $functions->getFeaturedBrandProduct($brandId);
                        if($functions->num_rows($featureResult)>0){
                     ?>
                           <section class="featureslider">
                              <h5>Feature Slider</h5>
                              <div class="featureslliderul">
                                 <?php 
                                    while($fetureData = $functions->fetch($featureResult)){
                                       $productBanner = $functions->getImageUrl('products',$fetureData['main_image'],'crop','');
                                       $productPermalink = $functions->getProductDetailPageURL($fetureData['id']);
                                 ?>
                                    
                                       <div class="fetauresliderli">
                                          <div>
                                             <div class="fsitem-img">
                                                <img src="<?php echo $productBanner; ?>" alt="<?php echo $fetureData["product_name"]; ?>">
                                             </div>
                                             <div class="fsitem-details">
                                                <a href="<?php echo $productPermalink;?>"><h4 class="fsitemname">
                                                   <?php echo $fetureData['product_name']; ?>
                                                </h4></a>
                                                <p>
                                                   <?php 
                                                      if(isset($fetureData['discount_price']) && !empty($fetureData['discount_price'])){
                                                   ?>
                                                         <span class="fsitem-sell-price"><i class="fa fa-inr"></i><?php echo $fetureData['discount_price']; ?></span>
                                                         <span class="fsitem-mrp"><i class="fa fa-inr"></i><?php echo $fetureData['price']; ?></span>
                                                   <?php 
                                                      }else{
                                                   ?>
                                                         <span class="fsitem-sell-price"><i class="fa fa-inr"></i><?php echo $fetureData['price']; ?></span>
                                                   <?php 
                                                      } ?>      
                                                </p>
                                             </div>
                                          </div>
                                       </div>
                              <?php 
                                    } ?>
                              </div>
                           </section>
                     <?php 
                        } ?>      
                  </div>
               </div>
               <div class="col-md-9">
                  <div class="products-list-box">
                     <div class="product-list-box-settings row">
                        <div class="col-lg-8  col-sm-6  match">
                           <h4><?php echo ucwords($displayName); ?></h4>
                        </div>
                        <div class="col-lg-4  col-sm-6  match sortbox">
                           <div class="sortdropdown">
                              <label>SORT BY</label>
                              <select class='selectdropdown sortBy' id="sortBy" name="sortBy">
                                    <option value="higher">Price high to low</option>
                                    <option value="lower">Price low to high</option>
                                    <option value="popular">Popular</option>                        
                              </select>
                           </div>
                        </div>
                  </div>
                  <div class="productlist-grid row">
                     <div class="AjaxFilters">
                        <?php 
                           include_once"include/product-listing.inc.php";
                        ?>                  
                     </div>
                     <div class="showLoader" style="display: none;">
                        <center><img src="<?php echo BASE_URL."/images/ajax-loader.gif";?>" style="width: 6%;"><br><br>
                        <h3 style="color:#fa283896;">Loading Please Wait......</h3></center>
                     </div>
                     <input type="hidden" name="permalink" id="permalink" value="<?php echo $permalink; ?>">
                     <input type="hidden" name="brand_id" id="brand_id" value="<?php if(isset($_GET['permalink']) && !empty($_GET['permalink'])){ echo $brandDetails['id']; }  ?>">
                     <input type="hidden" name="category_id" id="category_id" value="<?php if(isset($_GET['category_id']) && !empty($_GET['category_id'])){ echo $_GET['category_id']; }  ?>">
                     <input type="hidden" name="sub_category_id" id="sub_category_id" value="<?php if(isset($_GET['sub_category_id']) && !empty($_GET['sub_category_id'])){ echo $_GET['sub_category_id']; }  ?>">
                     <input type="hidden" name="subSub_category_id" id="subSub_category_id" value="<?php if(isset($_GET['subSub_category_id']) && !empty($_GET['subSub_category_id'])){ echo $_GET['subSub_category_id']; }  ?>">

                     <input type="hidden" name="limitCount" class="limitCount" value="30">
                  

                  </div>
                  <?php 
                     $totalProductCountResult = $functions->getBrandTotalActiveProductCount($brandDetails['id']); 
                     $totalProductCount = $functions->num_rows($totalProductCountResult);
                     if($totalProductCount>12){
                  ?>
                        <div class="loadmore">
                          <button id="loadMore">Load More</button>
                        </div>
                  <?php 
                     } ?>
                     <input type="hidden" name="totalProductCount" class="totalProductCount" value="<?php echo $totalProductCount; ?>">   
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

               //$("#loadMore").click(function() {
               $(document).on("click", "#loadMore", function() {
                  var limitcount = $('.limitCount').val();
                  var newVal = parseInt(limitcount);
                  
                  $('.limitCount').val(newVal+30);
                  var totalProduct = $('.totalProductCount').val();
                  
                  if(newVal >= parseInt(totalProduct)){
                     $('.loadmore').hide();
                  }

                  filterFunction();
               });
               
               $(document).on("change", "#sortBy", function() {
                 filterFunction();
               });
               $(document).on("change", ".attrFeature", function() {
                 filterFunction();
               });

               /*$("#sortBy").change(function() {
                  filterFunction();
               });
               $(".attrFeature").change(function() {
                  filterFunction();
               });*/
            });
            
            function filterFunction(){
               $(".showLoader").show();
               $('.AjaxFilters').hide();
               var sortBy = $('#sortBy').find(":selected").val();
               
               var permalink = $('#permalink').val();
               var permalink = $('#brand_id').val();
               var category_id = $('#category_id').val();
               var sub_category_id = $('#sub_category_id').val();
               var subSub_category_id = $('#subSub_category_id').val();
               var limit = $('.limitCount').val();
              
               var attrId = [];
               $(".attrFeature").each(function(){
                  if($(this).prop("checked") == true) {
                     attrId.push($(this).val());
                  }
               });
               //console.log(attrId);

               $.ajax({
                  url:"<?php echo BASE_URL; ?>/product-filter.inc.php",
                  data:{sortBy:sortBy,permalink:permalink,category_id:category_id,sub_category_id:sub_category_id,subSub_category_id:subSub_category_id,attrId:attrId,limit:limit},
                  type:"POST",
                  success: function(response){
                     console.log("response",response);
                     $(".showLoader").hide();
                     $('.AjaxFilters').show();
                     
                     $('.AjaxFilters').html(response);
                     MatchHeight1();

                     //$('.cartListingBtn').on('click', onRemoveFromCart);
                     $('.cartListingBtn').on('click', cartListingBtn);
                     $('.removeFromCartBtn').on('click', onRemoveFromCart);
                     $('.incrementCartBtn').on('click', onIncrementFromCart);
                     $('.decrementCartBtn').on('click', onDecrementFromCart);
                     // == CART SIDE POPUP ==

                     // == CHECKOUT PAGE ==
                     $('.checkoutPageRemoveFromCartBtn').on('click', onRemoveFromCartOnCheckoutPage);
                     $('.checkoutPageIncrementFromCartBtn').on('click', onIncrementFromCartOnCheckoutPage);
                     $('.checkoutPageDecrementFromCartBtn').on('click', onDecrementFromCartOnCheckoutPage);
                     if(typeof disableEnter == 'function'){
                        $('input[name="couponCode"], .checkoutQty').on('keypress', disableEnter);
                     }
                     // == CHECKOUT PAGE ==

                     // == COUPON CODE ==
                     $('.applyCouponCodeCartBtn').on('click', applyCouponCode);
                     $('.removeCouponCodeCartBtn').on('click', removeCouponCode);

                     $('.applyCouponCodeCheckoutBtn').on('click', applyCouponCodeOnCheckoutPage);
                     $('.removeCouponCodeCheckoutBtn').on('click', removeCouponCodeOnCheckoutPage);

                  },
                  error: function(){
                     console.log("Unable to load data, please try again");
                  },
                  complete: function(response){
                     
                  }
               });
            }

            $('.featureslliderul').slick({
              slidesToShow: 4,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 5000,
              vertical:true,
              verticalSwiping: true,
              responsive: [
                  {
                    breakpoint: 992,
                    settings: {
                      vertical:false,
                      verticalSwiping: false,
                      slidesToShow:3,
                    }
                  },
                  {
                    breakpoint: 768,
                    settings: {
                      vertical:false,
                      verticalSwiping: false,
                      slidesToShow:2,
                    }
                  },
                  {
                    breakpoint: 501,
                    settings: {
                      vertical:false,
                      verticalSwiping: false,
                      slidesToShow:1,
                    }
                  },
                  {
                    breakpoint: 396,
                    settings: {
                        vertical:false,
                        verticalSwiping: false,
                        slidesToShow:1,
                    }
                  }
                ]
            });

            $('.selectdropdown').niceSelect();


           

         </script>
   </body>
</html>