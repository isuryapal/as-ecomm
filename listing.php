<?php
   include_once 'include/functions.php';
   $functions = new Functions(); 
   // $permalink = '';
   $displayName ='Products';
   $breadcrumbs = '';
   // error_reporting(E_ALL);
   $checkUserLogedInOrNot = $functions->sessionExists();
   if(isset($_GET['cat_permalink']) && !empty($_GET['cat_permalink'])){
      $breadcrumbs = '';
      $permalink = $functions->escape_string($functions->strip_all($_GET['cat_permalink']));
      $catDetails = $functions->getCategorybyPermlink($permalink);
      
      if(isset($catDetails['id']) && !empty($catDetails['id'])){
         $catId = $functions->escape_string($functions->strip_all($catDetails['id']));
         $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
         $breadcrumbs .= "<li><a href='javascript:;'>".ucwords($catDetails['category_name'])."</a></li>";
         $permalink = BASE_URL."/".$catDetails['permalink'];
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
   if(isset($_GET['sub_category_permalink']) && !empty($_GET['sub_category_permalink'])){
      $breadcrumbs = '';
      $subCategoryDetails  = $functions->getSuBCatByPermalink($_GET['sub_category_permalink'],$catId);
      if(isset($subCategoryDetails['id']) && !empty($subCategoryDetails['id'])){
         $subCatId = $functions->escape_string($functions->strip_all($subCategoryDetails['id']));
         $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."'>".ucwords($catDetails['category_name'])."</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."'>".ucwords($subCategoryDetails['sub_category_name'])."</a></li>";
         $subCatBanner = $functions->getImageUrl('sub_category',$subCategoryDetails['banner_image'],'crop','');
         $permalink = BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink'];
         $displayName  = $subCategoryDetails['sub_category_name'];
      }else{
         header("location".BASE_URL."?INVALIDCATID");
         exit;
      }
   
   }
   if(isset($_GET['subSub_category_permalink']) && !empty($_GET['subSub_category_permalink'])){
      $breadcrumbs = '';
      $subSubCategoryDetails  = $functions->getSubSubCatByPermalink($_GET['subSub_category_permalink'],$subCatId);
      // print_r($subSubCategoryDetails);
      if(isset($subSubCategoryDetails['id']) && !empty($subSubCategoryDetails['id'])){
         $subsubcatId = $functions->escape_string($functions->strip_all($subSubCategoryDetails['id']));
         $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."'>".ucwords($catDetails['category_name'])."</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."'>".ucwords($subCategoryDetails['sub_category_name'])."</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."'>".ucwords($subSubCategoryDetails['subcategory_name'])."</a></li>";
         $subSubcatBanner = $functions->getImageUrl('subsubcategory',$subSubCategoryDetails['banner_image'],'crop','');
         $permalink = BASE_URL."/".$catDetails['category_name']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink'];
         $displayName  = $subSubCategoryDetails['subcategory_name'];
      }else{
         header("location".BASE_URL."?INVALIDCATID");
         exit;
      }

   }

   if(isset($_GET['subSubSub_category_permalink']) && !empty($_GET['subSubSub_category_permalink'])){
      $breadcrumbs = '';
      $subSubSubCategoryDetails  = $functions->getSubSubSubCatByPermalink($_GET['subSubSub_category_permalink'],$subsubcatId);
      if(isset($subSubSubCategoryDetails['id']) && !empty($subSubSubCategoryDetails['id'])){
         $subsubsubcatId = $functions->escape_string($functions->strip_all($subSubSubCategoryDetails['id']));
         $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."'>".ucwords($catDetails['category_name'])."</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."'>".ucwords($subCategoryDetails['sub_category_name'])."</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."'>".ucwords($subSubCategoryDetails['subcategory_name'])."</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."/".$subSubSubCategoryDetails['subsubsub_permalink']."'>".ucwords($subSubSubCategoryDetails['subsubsub_name'])."</a></li>";
         $subSubSubcatBanner = $functions->getImageUrl('sub_subsubcategory',$subSubSubCategoryDetails['banner_image'],'crop','');
         $permalink = BASE_URL."/".$catDetails['category_name']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."/".$subSubSubCategoryDetails['subsubsub_permalink'];
         $displayName  = $subSubSubCategoryDetails['subsubsub_name'];
      }else{
         header("location".BASE_URL."?INVALIDCATID");
         exit;
      }

   }

   if(isset($_GET['subSubSubSub_category_permalink']) && !empty($_GET['subSubSubSub_category_permalink'])){
      $breadcrumbs = '';
      $subSubSubSubCategoryDetails  = $functions->getSubSubSubSubCatByPermalink($_GET['subSubSubSub_category_permalink'],$subsubsubcatId);
      if(isset($subSubSubSubCategoryDetails['id']) && !empty($subSubSubSubCategoryDetails['id'])){
         $catId = $functions->escape_string($functions->strip_all($catDetails['id']));
         $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."'>".ucwords($catDetails['category_name'])."</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."'>".ucwords($subCategoryDetails['sub_category_name'])."</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."'>".ucwords($subSubCategoryDetails['subcategory_name'])."</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."/".$subSubSubCategoryDetails['subsubsub_permalink']."'>".ucwords($subSubSubCategoryDetails['subsubsub_name'])."</a></li>";
         $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."/".$subSubSubCategoryDetails['subsubsub_permalink']."/".$subSubSubSubCategoryDetails['subsubsubsub_permalink']."'>".ucwords($subSubSubSubCategoryDetails['subsubsubsub_name'])."</a></li>";
         $subSubSubcatBanner = $functions->getImageUrl('subsub_subsubcategory',$subSubSubSubCategoryDetails['banner_image'],'crop','');
         $permalink = BASE_URL."/".$catDetails['category_name']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."/".$subSubSubCategoryDetails['subsubsub_permalink']."/".$subSubSubSubCategoryDetails['subsubsubsub_permalink'];
         $displayName  = $subSubSubSubCategoryDetails['subsubsubsub_name'];
      }else{
         header("location".BASE_URL."?INVALIDCATID");
         exit;
      }

   }

?>
<!DOCTYPE>
<html>
   <head>
	<title><?php if(!empty($mtitle)){echo $mtitle;}else{echo "Products";} ?></title>
      <meta name="title" content="<?php echo $mtitle; ?>">
      <meta name="description" content="<?php echo $mdecription; ?>">
      <meta name="keywords" content="<?php echo $mkey; ?>">
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
                     <section class="Price-filter-section about">
                        <h5>Our Products<i class="fa fa-chevron-down "></i></h5>
                        <div class="listingfilter">
                           <div class="dropdown multiselect open">
                              <?php
                              $catSql = "SELECT * FROM ".PREFIX."category_master order by id";
                              $catRes = $functions->query($catSql);
                              while($catRow = $functions->fetch($catRes)){
                              ?>
                              <label class="pfcontainer">
                                 <a href="<?php echo BASE_URL."/".$catRow['permalink']; ?>">
                                 <span class="pflabel"><?php echo $catRow['category_name']; ?></span>
                                 </a>
                                 <!-- <input type="checkbox" class="attrFeature" id="action">
                                 <span class="pfcheckmark"></span> -->
                              </label>
                           <?php } ?>
                           </div>
                        </div>      
                     </section>
                     <?php
                     if(isset($catId) && !empty($catId)){
                                 $catId = $catId;
                              }else{
                                 if(isset($catProductID) && !empty($catProductID) && is_array($catProductID)){
                                    $catId = implode(",", $catProductID);
                                 }else{
                                    $catId = 0;
                                 }
                              }
                              $result = $functions->getAttributeCategory($catId);
                              $attrArr = array();
                              if($functions->num_rows($result)>0){
                     ?>
                     <section class="Price-filter-section aboutus">
                        <?php 
                                 while($rows = $functions->fetch($result)){
                                   $attributeDetaisl =  $functions->getAttributByCateId($rows['id']);
                                   if($functions->num_rows($attributeDetaisl)>0){
                                       while($attribute = $functions->fetch($attributeDetaisl)){
                                          if(!in_array($attribute['id'], $attrArr)){
                                             $attrArr[] = $attribute['id'];
                           ?>
                                             <h5><?php echo ucwords($attribute['attribute_name']); ?><i class="fa fa-chevron-down"></i></h5>
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
                              ?>
                        
                     </section>
                     <?php 
                        }
                        $featureResult =  $functions->getFeaturedProduct();
                        if($functions->num_rows($featureResult)>0){
                     ?>
                           <section class="featureslider">
                              <h5>Featured products</h5>
                              <div class="featureslliderul">
                                 <?php 
                                    while($fetureData = $functions->fetch($featureResult)){
                                       $productBanner = $functions->getImageUrl('products',$fetureData['main_image'],'crop','');
                                       if(empty($productBanner)){
                                          $productBanner = BASE_URL."/images/featureddefaultimg.jpg";
                                       }
                                       $productPermalink = $functions->getProductDetailPageURL($fetureData['id'],$_GET);
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
                                                       if(isset($checkUserLogedInOrNot['user_type']) && !empty($checkUserLogedInOrNot['user_type']) && $checkUserLogedInOrNot['user_type']=="b2c"){
                                                         if(!empty($fetureData['discount_price'])){
                                                     ?>
                                                         <span class="fsitem-sell-price"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetureData['discount_price']; ?></span> 
                                                         <!-- <strike class="disabled">
                                                             <span class="fsitem-sell-price"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetureData['price']; ?></span>
                                                         </strike> -->
                                                   <?php  }else{ ?>
                                                             <span class="fsitem-sell-price"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetureData['price']; ?></span>
                                                 <?php    }
                                                       }else if(isset($checkUserLogedInOrNot['user_type']) && !empty($checkUserLogedInOrNot['user_type']) && $checkUserLogedInOrNot['user_type']=="b2b"){
                                                         if(!empty($fetureData['b2b_discount_price'])){
                                                 ?>
                                                       <span class="fsitem-sell-price"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetureData['b2b_discount_price']; ?></span>
                                                       <!-- <strike class="disabled">
                                                           <span class="fsitem-sell-price"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetureData['b2b_price']; ?></span>
                                                       </strike> -->
                                                 <?php  }else{ ?>
                                                             <span class="fsitem-sell-price"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetureData['b2b_price']; ?></span>
                                                 <?php    }
                                                       }else{
                                                         if(!empty($fetureData['discount_price'])){
                                                       ?>
                                                         <span class="fsitem-sell-price"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetureData['discount_price']; ?></span>
                                                         <!-- <strike class="disabled">
                                                             <span class="fsitem-sell-price"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetureData['price']; ?></span>
                                                         </strike> -->
                                                 <?php   }else{ ?>
                                                             <span class="fsitem-sell-price"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $fetureData['price']; ?></span>
                                                 <?php   } 
                                                       }
                                                 ?>      
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
                        
                        <div class='col-md-8'>
                            <h1 class="listingheadinmg"><?php echo $displayName; ?></h1>
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
                     <input type="hidden" name="cat_permalink" id="cat_permalink" value="<?php if(isset($_GET['cat_permalink']) && !empty($_GET['cat_permalink'])){ echo $_GET['cat_permalink']; }  ?>">
                     <input type="hidden" name="sub_category_permalink" id="sub_category_permalink" value="<?php if(isset($_GET['sub_category_permalink']) && !empty($_GET['sub_category_permalink'])){ echo $_GET['sub_category_permalink']; }  ?>">
                     <input type="hidden" name="subSub_category_permalink" id="subSub_category_permalink" value="<?php if(isset($_GET['subSub_category_permalink']) && !empty($_GET['subSub_category_permalink'])){ echo $_GET['subSub_category_permalink']; }  ?>">
                     <input type="hidden" name="subSubSub_category_permalink" id="subSubSub_category_permalink" value="<?php if(isset($_GET['subSubSub_category_permalink']) && !empty($_GET['subSubSub_category_permalink'])){ echo $_GET['subSubSub_category_permalink']; }  ?>">
                     <input type="hidden" name="subSubSubSub_category_permalink" id="subSubSubSub_category_permalink" value="<?php if(isset($_GET['subSubSubSub_category_permalink']) && !empty($_GET['subSubSubSub_category_permalink'])){ echo $_GET['subSubSubSub_category_permalink']; }  ?>">

                     <input type="hidden" name="limitCount" class="limitCount" value="30">
                  

                  </div>
                  <?php 
                     $totalProductCountResult = $functions->getTotalActiveProductCount(); 
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
               
               // var permalink = $('#permalink').val();
               var cat_permalink = $('#cat_permalink').val();
               var sub_category_permalink = $('#sub_category_permalink').val();
               var subSub_category_permalink = $('#subSub_category_permalink').val();
               var subSubSub_category_permalink = $('#subSubSub_category_permalink').val();
               var subSubSubSub_category_permalink = $('#subSubSubSub_category_permalink').val();
               var limit = $('.limitCount').val();
              
               var attrId = [];
               $(".attrFeature").each(function(){
                  if($(this).prop("checked") == true) {
                     attrId.push($(this).val());
                  }
               });
               // console.log(attrId);

               $.ajax({
                  url:"<?php echo BASE_URL; ?>/product-filter.inc.php",
                  data:{sortBy:sortBy,cat_permalink:cat_permalink,sub_category_permalink:sub_category_permalink,subSub_category_permalink:subSub_category_permalink,subSubSub_category_permalink:subSubSub_category_permalink,subSubSubSub_category_permalink:subSubSubSub_category_permalink,attrId:attrId,limit:limit},
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

                     var sort = $("#sortBy").val();
                     $('.Jprice').sort(function (a, b) {
                        if(sort=='lower') {
                           return $(a).data('price') - $(b).data('price');
                        } else if(sort=='higher') {
                           return $(b).data('price') - $(a).data('price');
                        } else if(sort=='popular') {
                           return $(b).data('id') - $(a).data('id');
                        }
                     }).map(function () {
                        return $(this);
                     }).each(function (_, container) {
                        $(container).parent().append(container);
                     });
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