<?php 
   include_once 'include/functions.php';
      $functions = new Functions(); 
   // print_r($_GET);
    $loggedInUserDetailsArr = $functions->sessionExists();

    $permalink = '';
     $displayName ='Products';
      if(isset($_GET['product_name']) && !empty($_GET['product_name']) && isset($_GET['product_code']) &&  !empty($_GET['product_code'])){
          $breadcrumbs = '';
          $product_name = $functions->escape_string($functions->strip_all($_GET['product_name']));
          $product_code = $functions->escape_string($functions->strip_all($_GET['product_code']));
          $productPermalink = $product_name."/".$product_code;
          $productDetails = $functions->getProductByproductPermalink($productPermalink);
          
          $ip = $_SERVER['REMOTE_ADDR'];
          $ipCheckSql = "SELECT * FROM ".PREFIX."product_views WHERE ip='".$ip."' and product_id='".$productDetails['id']."'";
          $ipCheckRes = $functions->query($ipCheckSql);
          if($functions->num_rows($ipCheckRes)>0){
            $ipCheckRow = $functions->fetch($ipCheckRes);
            $oldView = $ipCheckRow['views'];
            $viewUpSql = "UPDATE ".PREFIX."product_views SET views='".$oldView."' WHERE ip='".$ip."' and product_id='".$productDetails['id']."'";
            $functions->query($viewUpSql);
          }else{
            $views = 1;
            $ipInSql = "INSERT INTO ".PREFIX."product_views (product_id, views, ip, created) VALUES ('".$productDetails['id']."','".$views."','".$ip."','".date('Y-m-d h:i:s')."')";
            $queryIp = $functions->query($ipInSql);
          }
      }
      
      if(isset($_GET['cat_permalink']) && !empty($_GET['cat_permalink'])){
          $breadcrumbs = '';
          
          $sqlCatPerma = "SELECT * FROM ".PREFIX."product_category_mapping WHERE `product_id`='".$productDetails['id']."'";
          $catResult = $functions->query($sqlCatPerma);
          $catDetails = $functions->fetch($catResult);
         
   
          if(isset($catDetails['category_id']) && !empty($catDetails['category_id'])){
              $categoryId = $functions->escape_string($functions->strip_all($catDetails['category_id']));
              $catDetails = $functions->getUniqueCategoryById($categoryId);
            
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
      //echo $breadcrumbs;
      //print_r($_GET);
    
      if(isset($_GET['sub_category_permalink']) && !empty($_GET['sub_category_permalink'])){
          $breadcrumbs = '';
          //$subCategoryDetails  = $functions->getSuBCatByPermalink($_GET['sub_category_id'],$catId);
          
          $sqlsubCatPerma = "SELECT * FROM ".PREFIX."product_subcategory_mapping WHERE `product_id`='".$productDetails['id']."'";
          $subCatResult = $functions->query($sqlsubCatPerma);
          $subCategoryDetails = $functions->fetch($subCatResult);
          //print_r($subCategoryDetails);
          if(isset($subCategoryDetails['subscategory_id']) && !empty($subCategoryDetails['subscategory_id'])){
              $subCategoryDetails = $functions->getUniqueSubCategoryById($subCategoryDetails['subscategory_id']);
            
              $catId = $functions->escape_string($functions->strip_all($catDetails['id']));
            $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
            $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."'>".ucwords($catDetails['category_name'])."</a></li>";
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
          $sqlSubSubCatPerma = "SELECT * FROM ".PREFIX."product_subsubcategory_mapping WHERE `product_id`='".$productDetails['id']."'";
          $subSubCatResult = $functions->query($sqlSubSubCatPerma);
          $subSubDetails = $functions->fetch($subSubCatResult);
          
          $subSubCategoryDetails = $functions->getuniqueSusuCategory($subSubDetails['subsubcategory_id']);
          if(isset($subSubCategoryDetails['id']) && !empty($subSubCategoryDetails['id'])){
            $catId = $functions->escape_string($functions->strip_all($catDetails['id']));
            $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
            $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."'>".ucwords($catDetails['category_name'])."</a></li>";
            $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."'>".ucwords($subSubCategoryDetails['subcategory_name'])."</a></li>";
            
           $subSubcatBanner = $functions->getImageUrl('subsubcategory',$subSubCategoryDetails['banner_image'],'crop','');
           $permalink = BASE_URL."/".$catDetails['category_name']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink'];
           $displayName  = $subSubCategoryDetails['subcategory_name'];
            $subsubCatPermlink =  "/".$subSubCategoryDetails['permalink'];
          }else{
            header("location".BASE_URL."?INVALIDCATID");
            exit;
          }
   
      }
   
      if(isset($_GET['subSubSub_category_permalink']) && !empty($_GET['subSubSub_category_permalink'])){
          $breadcrumbs = '';
          $sqlSubSubSubCatPerma = "SELECT * FROM ".PREFIX."product_subsubsubcategory_mapping WHERE `product_id`='".$productDetails['id']."'";
          $subSubSubCatResult = $functions->query($sqlSubSubSubCatPerma);
          $subSubSubDetails = $functions->fetch($subSubSubCatResult);
          
          $subSubSubCategoryDetails = $functions->getuniqueSuSusuCategory($subSubSubDetails['subsubsubcategory_id']);
          if(isset($subSubSubCategoryDetails['id']) && !empty($subSubSubCategoryDetails['id'])){
            $catId = $functions->escape_string($functions->strip_all($catDetails['id']));
            $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
            $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."'>".ucwords($catDetails['category_name'])."</a></li>";
            $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."'>".ucwords($subSubCategoryDetails['subcategory_name'])."</a></li>";
            $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."/".$subSubSubCategoryDetails['subsubsub_permalink']."'>".ucwords($subSubSubCategoryDetails['subsubsub_name'])."</a></li>";
          $subSubSubcatBanner = $functions->getImageUrl('sub_subsubcategory',$subSubSubCategoryDetails['banner_image'],'crop','');
           $permalink = BASE_URL."/".$catDetails['category_name']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."/".$subSubSubCategoryDetails['subsubsub_permalink'];
           $displayName  = $subSubSubCategoryDetails['subsubsub_name'];
            $subsubsubCatPermlink =  "/".$subSubSubCategoryDetails['subsubsub_permalink'];
          }else{
            header("location".BASE_URL."?INVALIDCATID");
            exit;
          }
   
      }
      
      if(isset($_GET['subSubSubSub_category_permalink']) && !empty($_GET['subSubSubSub_category_permalink'])){
          $breadcrumbs = '';
          $sqlSubSubSubSubCatPerma = "SELECT * FROM ".PREFIX."product_subsubsubsubcategory_mapping WHERE `product_id`='".$productDetails['id']."'";
          $subSubSubSubCatResult = $functions->query($sqlSubSubSubSubCatPerma);
          $subSubSubSubDetails = $functions->fetch($subSubSubSubCatResult);
          
          $subSubSubSubCategoryDetails = $functions->getuniqueSuSuSusuCategory($subSubSubSubDetails['subsubsubsubcategory_id']);
          if(isset($subSubSubSubCategoryDetails['id']) && !empty($subSubSubSubCategoryDetails['id'])){
            $catId = $functions->escape_string($functions->strip_all($catDetails['id']));
            $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
            $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."'>".ucwords($catDetails['category_name'])."</a></li>";
            $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."'>".ucwords($subSubCategoryDetails['subcategory_name'])."</a></li>";
            $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."/".$subSubSubCategoryDetails['subsubsub_permalink']."'>".ucwords($subSubSubCategoryDetails['subsubsub_name'])."</a></li>";
            $breadcrumbs .= "<li><a href='".BASE_URL."/".$catDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."/".$subSubSubCategoryDetails['subsubsub_permalink']."/".$subSubSubSubCategoryDetails['subsubsubsub_permalink']."'>".ucwords($subSubSubSubCategoryDetails['subsubsubsub_name'])."</a></li>";
            $subSubSubcatBanner = $functions->getImageUrl('subsub_subsubcategory',$subSubSubSubCategoryDetails['banner_image'],'crop','');
           $permalink = BASE_URL."/".$catDetails['category_name']."/".$subCategoryDetails['sub_category_permalink']."/".$subSubCategoryDetails['permalink']."/".$subSubSubCategoryDetails['subsubsub_permalink']."/".$subSubSubSubCategoryDetails['subsubsubsub_permalink'];
           $displayName  = $subSubSubSubCategoryDetails['subsubsubsub_name'];
            $subsubsubsubCatPermlink =  "/".$subSubSubSubCategoryDetails['subsubsubsub_permalink'];
          }else{
            header("location".BASE_URL."?INVALIDCATID");
            exit;
          }
   
      }
      $rationgresult =0;
   
   if(isset($_GET['product_name']) && !empty($_GET['product_name']) && isset($_GET['product_code']) &&  !empty($_GET['product_code'])){
        $breadcrumbs = '';
        $product_name = $functions->escape_string($functions->strip_all($_GET['product_name']));
        $product_code = $functions->escape_string($functions->strip_all($_GET['product_code']));
        $productPermalink = $product_name."/".$product_code;
   
        $productDetails = $functions->getProductByproductPermalink($productPermalink);
        //$subCategoryDetails  = $functions->getUniqueSubCategoryById($productDetails['sub_category_id']);
        //print_r($productDetails);
        if(isset($productDetails['id']) && !empty($productDetails['id'])){
          // PRODUCT FOUND
          if(isset($_GET['cat_permalink']) && !empty($_GET['cat_permalink'])){
            $categoryDetails = $functions->getUniqueCategoryProductById($productDetails['id']);
            $breadcrumbs .= "<li><a href=".BASE_URL.">Home</a></li>";
            if(isset($categoryDetails['id']) && !empty($categoryDetails['id'])){
              $breadcrumbs .= "<li><a href='".BASE_URL."/".$categoryDetails['permalink']."'>".ucwords($categoryDetails['category_name'])."</a></li>";
            }
          }
   
          if(isset($_GET['sub_category_permalink']) && !empty($_GET['sub_category_permalink'])){
            $subCategoryDetails = $functions->getUniqueSubCategoryByProductId($productDetails['id']);
            if(isset($subCategoryDetails['id']) && !empty($subCategoryDetails['id'])){
              $breadcrumbs .= "<li><a href='".BASE_URL."/".$categoryDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."'>".ucwords($subCategoryDetails['sub_category_name'])."</a></li>";
            }
          }
          
          if(isset($_GET['subSub_category_permalink']) && !empty($_GET['subSub_category_permalink'])){
            $subsubCategoryDetails = $functions->getuniqueSusuCategoryByProductId($productDetails['id']);
            if(isset($subsubCategoryDetails['id']) && !empty($subsubCategoryDetails['id'])){
              $breadcrumbs .= "<li><a href='".BASE_URL."/".$categoryDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subsubCategoryDetails['permalink']."'>".ucwords($subsubCategoryDetails['subcategory_name'])."</a></li>";
            }
          }
   
          if(isset($_GET['subSubSub_category_permalink']) && !empty($_GET['subSubSub_category_permalink'])){
            $subsubsubCategoryDetails = $functions->getuniqueSuSusuCategoryByProductId($productDetails['id']);
            if(isset($subsubsubCategoryDetails['id']) && !empty($subsubsubCategoryDetails['id'])){
              $breadcrumbs .= "<li><a href='".BASE_URL."/".$categoryDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subsubCategoryDetails['permalink']."/".$subsubsubCategoryDetails['subsubsub_permalink']."'>".ucwords($subsubsubCategoryDetails['subsubsub_name'])."</a></li>";
            }
          }
   
          if(isset($_GET['subSubSubSub_category_permalink']) && !empty($_GET['subSubSubSub_category_permalink'])){
            $subsubsubsubCategoryDetails = $functions->getuniqueSuSuSusuCategoryByProductId($productDetails['id']);
            if(isset($subsubsubsubCategoryDetails['id']) && !empty($subsubsubsubCategoryDetails['id'])){
              $breadcrumbs .= "<li><a href='".BASE_URL."/".$categoryDetails['permalink']."/".$subCategoryDetails['sub_category_permalink']."/".$subsubCategoryDetails['permalink']."/".$subsubsubCategoryDetails['subsubsub_permalink']."/".$subsubsubsubCategoryDetails['subsubsubsub_permalink']."'>".ucwords($subsubsubsubCategoryDetails['subsubsubsub_name'])."</a></li>";
            }
          }
        
          $breadcrumbs .= "<li><a href='javascript:;'>".ucwords($productDetails['product_name'])."</a></li>";
          
          $main_image = $functions->getImageUrl('products',$productDetails['main_image'],'crop','');
          $image_one = $functions->getImageUrl('products',$productDetails['image_one'],'crop','');
          $image_two = $functions->getImageUrl('products',$productDetails['image_two'],'crop','');
          $image_three = $functions->getImageUrl('products',$productDetails['image_three'],'crop','');
          $image_four = $functions->getImageUrl('products',$productDetails['image_four'],'crop','');
          $rationgresult = $functions->getRatingByProductId($productDetails["id"]);
            }else{
              header("location:".BASE_URL."?INVALIDPRODUCTPERMALINK");
              exit;
            }
         }
         // $productVideoList = $user->getAllProductVideosByProductId($pet_id);
   
     
   ?>
<!DOCTYPE>
<html>
   <head>
      <title><?php echo $productDetails['meta_title'] ?></title>
      <meta name="keywords" content="<?php echo $productDetails['meta_keywords']; ?>" />
      <meta name="description" content="<?php echo $productDetails['meta_description']; ?>" />
      <?php include("include/header-link.php");?>
   </head>
   <body class="detail-body">
      <!--Top start menu head-->       
      <?php include("include/header.php");?>
      <div class="only-breadcrumbs">
         <div class="container">
            <ul class="breadcrumbs">
               <?php echo $breadcrumbs; ?>
            </ul>
         </div>
      </div>
      <div class="inner-content">
         <section class="detail-sec">
            <div class="container">
               <div class="row">
                  <div class="col-md-4 col-sm-4 productimgbox">
                     <div class="">
                        <div class="zoom-left">
                           <div class="row zoom-container">
                              <div class="col-md-12 col-sm-12 col-xs-12 match paddingl0">
                                 <img id="zoom_03" src="<?php echo $main_image; ?>" data-zoom-image="<?php echo $main_image; ?>" width="100%" />
                                 <?php
                                 if(!empty($productDetails['video_one'])){
                                 ?>
                                   <!-- <iframe style="display:none" class="videoShow vmdemo1"  src="<?php //echo BASE_URL."/videos/products/".$productDetails['video_one']; ?>" frameborder="0" height="250" allow="autoplay; encrypted-media" allowfullscreen></iframe> -->

                                   <video  style="display:none" class="videoShow vmdemo1" width="320" height="240" controls>
                                     <source  src="<?php echo BASE_URL."/videos/products/".$productDetails['video_one']; ?>" type="video/mp4">
                                  </video>
                           <?php } ?>
                                  <?php
                                 if(!empty($productDetails['video_two'])){
                                 ?>
                                  <video  style="display:none" class="videoShow vmdemo2" width="320" height="240" controls>
                                    <source  src="<?php echo BASE_URL."/videos/products/".$productDetails['video_two']; ?>" type="video/mp4">
                                 </video>
                                  
                           <?php } ?>
                                  <?php
                                 if(!empty($productDetails['video_three'])){
                                 ?>
                                  <video  style="display:none" class="videoShow vmdemo3" width="320" height="240" controls>
                                    <source  src="<?php echo BASE_URL."/videos/products/".$productDetails['video_three']; ?>" type="video/mp4">
                                 </video>

                           <?php } ?>
                                  <?php
                                 if(!empty($productDetails['video_four'])){
                                 ?>
                                   <video  style="display:none" class="videoShow vmdemo4" width="320" height="240" controls>
                                    <source  src="<?php echo BASE_URL."/videos/products/".$productDetails['video_four']; ?>" type="video/mp4">
                                  </video>
                           <?php } ?>
                                  <?php
                                 if(!empty($productDetails['video_five'])){
                                 ?>
                                  <video  style="display:none" class="videoShow vmdemo5" width="320" height="240" controls>
                                    <source  src="<?php echo BASE_URL."/videos/products/".$productDetails['video_five']; ?>" type="video/mp4">
                                  </video>
                           <?php } ?>

                              </div>
                              <div class="col-md-12 col-sm-12 col-xs-12 match">
                                 <div id="gallery_01">
                                    <a href="#" class="elevatezoom-gallery active" data-update="" data-image="<?php echo $main_image; ?>" data-zoom-image="<?php echo $main_image; ?>"><img src="<?php echo $main_image; ?>" width="100" /></a> 
                                    <?php 
                                       if(!empty($productDetails['image_one'])){
                                       ?>
                                    <a href="#" class="elevatezoom-gallery active" data-update="" data-image="<?php echo $image_one; ?>" data-zoom-image="<?php echo $image_one; ?>">
                                      <img src="<?php echo $image_one; ?>" width="100" />
                                    </a>
                                    <?php   }if(!empty($productDetails['image_two'])){ ?>
                                    <a href="#" class="elevatezoom-gallery active" data-update="" data-image="<?php echo $image_two; ?>" data-zoom-image="<?php echo $image_two; ?>"><img src="<?php echo $image_two; ?>" width="100" /></a>
                                    <?php   }if(!empty($productDetails['image_three'])){ ?>     
                                    <a href="#" class="elevatezoom-gallery active" data-update="" data-image="<?php echo $image_three; ?>" data-zoom-image="<?php echo $image_three; ?>"><img src="<?php echo $image_three; ?>" width="100" /></a>
                                    <?php }if(!empty($productDetails['image_four'])){ ?>  
                                    <a href="#" class="elevatezoom-gallery active" data-update="" data-image="<?php echo $image_four; ?>" data-zoom-image="<?php echo $image_four; ?>"><img src="<?php echo $image_four; ?>" width="100" /></a>
                                    <?php   } ?>    
                                    <!-- product videos::start -->
                                    <?php
                                    if(!empty($productDetails['video_one'])){
                                    ?>
                                    <span>
                                       <div class="hasVideo">                     
                                          <a href="javascript:void();" class="videoOpen demonvideo1">
                                          <img class="xzoom-gallery4" width="80"  src="<?php echo BASE_URL; ?>/images/videoThumb.png"  alt="videoThumb" title="">
                                          </a>
                                       </div>
                                    </span>
                              <?php } ?>
                                    <?php
                                    if(!empty($productDetails['video_two'])){
                                    ?>
                                    <span>
                                       <div class="hasVideo">                     
                                          <a href="javascript:void();" class="videoOpen demonvideo2">
                                          <img class="xzoom-gallery4" width="80"  src="<?php echo BASE_URL; ?>/images/videoThumb.png"  alt="videoThumb" title="">
                                          </a>
                                       </div>
                                    </span>
                              <?php } ?>
                                    <?php
                                    if(!empty($productDetails['video_three'])){
                                    ?>
                                    <span>
                                       <div class="hasVideo">                     
                                          <a href="javascript:void();" class="videoOpen demonvideo3">
                                          <img class="xzoom-gallery4" width="80"  src="<?php echo BASE_URL; ?>/images/videoThumb.png"  alt="videoThumb" title="">
                                          </a>
                                       </div>
                                    </span>
                              <?php } ?>
                                    <?php
                                    if(!empty($productDetails['video_four'])){
                                    ?>
                                    <span>
                                       <div class="hasVideo">                     
                                          <a href="javascript:void();" class="videoOpen demonvideo4">
                                          <img class="xzoom-gallery4" width="80"  src="<?php echo BASE_URL; ?>/images/videoThumb.png"  alt="videoThumb" title="">
                                          </a>
                                       </div>
                                    </span>
                              <?php } ?>
                                    <?php
                                    if(!empty($productDetails['video_five'])){
                                    ?>
                                    <span>
                                       <div class="hasVideo">                     
                                          <a href="javascript:void();" class="videoOpen demonvideo5">
                                          <img class="xzoom-gallery4" width="80"  src="<?php echo BASE_URL; ?>/images/videoThumb.png"  alt="videoThumb" title="">
                                          </a>
                                       </div>
                                    </span>
                              <?php } ?>
                                    <!-- product videos::end -->
                                 </div>
                              </div>
                           </div>
                           <div class="clearfix"></div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-8 col-sm-8 make2">
                     <div class="right-side-details">
                        <div class="sharebtn">
                          <a class="a2a_dd" href="https://www.addtoany.com/share">
                            <i class="fa fa-share-alt"></i>
                          </a>
                        </div>
                        <p class="prod-id"><?php echo $productDetails['product_code']; ?></p>
                        <h2 class="product-name" style="border-bottom:0px !important;"><?php echo ucwords($productDetails['product_name']); ?></h2>
                        <ul class="review-strip list-inline">
                           <!--<li>
                              <div class="ratingDiv" id="ratingDiv1">
                                <span class="ratingSpan star3"></span>
                              </div>
                              </li>-->
                           <?php
                              if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2c"){
                              if(isset($productDetails['discount_price']) && !empty($productDetails['discount_price'])){
                              ?>
                           <li>
                              <p class="price_big-txt">
                                 <i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['discount_price']; ?><strike class="disabled">
                                 <i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['price']; ?></strike>
                              </p>
                           </li>
                           <?php 
                              }else{ ?>
                           <li>
                              <p class="price_big-txt">
                                 <i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['price']; ?></strike>
                              </p>
                           </li>
                           <?php 
                              }
                              }else if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2b"){
                              if(!empty($productDetails['b2b_discount_price'])){
                              ?>
                           <li>
                              <p class="price_big-txt">
                                 <i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['b2b_discount_price']; ?><strike class="disabled">
                                 <i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['b2b_price']; ?></strike>
                              </p>
                           </li>
                           <?php 
                              }else{ ?>
                           <li>
                              <p class="price_big-txt">
                                 <i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['b2b_price']; ?></strike>
                              </p>
                           </li>
                           <?php    
                              }
                                }else{
                                if(!empty($productDetails['discount_price'])){
                              ?>
                           <li>
                              <p class="price_big-txt">
                                 <i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['discount_price']; ?><strike class="disabled">
                                 <i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['price']; ?></strike>
                              </p>
                           </li>
                           <?php   }else{ ?>
                           <li>
                              <p class="price_big-txt">
                                 <i class="fa fa-inr" aria-hidden="true"></i><?php echo $productDetails['price']; ?></strike>
                              </p>
                           </li>
                           <?php   } 
                              }
                              ?>  
                           
                        </ul>
                        <?php
                           if(isset($productDetails['short_description']) && !empty($productDetails['short_description'])){
                            //$desc = nl2br($functions->escape_string($functions->strip_all($productDetails['description'])));
                           ?>
                        <div class="prod-description">
                           <p><?php echo $productDetails['short_description']; ?>
                           </p>
                        </div>
                        <?php   
                           } 
                           $inCartQty = '1';
                                if(isset($cartObj)){
                                // $cartObj = new Cart();
                                $tempInCartQty = $cartObj->getProductQuantity($productDetails['id'], $functions);
                                if($tempInCartQty){
                                  $inCartQty = $tempInCartQty;
                                }
                            }
                           ?>
                        <div class="input-num priceDiv">
                           <?php 
                              if($productDetails['availability']>0){
                              ?>    
                           <span>Quantity</span>
                           <ul class="list-inline">
                              <li><button class="btn-number" data-type="minus" data-field="productCount">-</button></li>
                              <li class="numm"><input type="number" id="number" name="productCount" value="<?php echo $inCartQty; ?>" min="<?php if($loggedInUserDetailsArr['user_type']=='b2b'){ echo $productDetails['b2b_min_qty']; }else{ echo "1"; } ?>" max="<?php echo $productDetails['availability']; ?>" readonly></li>
                              <li><button class="btn-number" data-type="plus" data-field="productCount">+</button></li>
                              <input type="hidden" id="available_qty" class="available_qty" value="<?php echo $productDetails['availability']; ?>" name="available_qty" >
                              <input type="hidden" name="b2b_min_qty" class="b2b_min_qty" value="<?php echo $productDetails['b2b_min_qty']; ?>">
                           </ul>
                           <?php 
                              } ?>    
                        </div>
                        <ul class="btn-groups list-inline">
                           <li>
                              <?php 
                                 if($productDetails['availability']>0){
                                 ?>
                              <button name="cartBtn" id="cartBtn" data-id="<?php echo $productDetails['id'];  ?>"> <img class="bynoww" src="<?php echo BASE_URL; ?>/images/bynow.png" alt=""> Add to Cart</button>
                              <?php }else{ ?>
                              <button name="OUTOFSTOC" id="OUTOFSTOC" ><span style="color:red;">Out of stock</span></button>
                              <?php } ?>    
                           </li>
                           <li>
                              <?php 
                                 if(isset($loggedInUserDetailsArr['id']) && !empty($loggedInUserDetailsArr['id']) && isset($loggedInUserDetailsArr['user_type'])){ 
                                 
                                 ?>
                              <button type="button" class="clsWishlist" data-id="<?php echo $productDetails['id'];  ?>" ><img src="<?php echo BASE_URL; ?>/images/wishlist.png" alt=""> Wishlist</button>
                              <?php 
                                 }else{ ?>
                              <a  class="wishlistbtnnew" href="<?php echo BASE_URL; ?>/login.php?failed&cusLogin&redirect=<?php echo $permalink; ?>"><img src="<?php echo BASE_URL; ?>/images/wishlist.png" alt=""> Wishlist</a>    
                              <?php 
                                 } ?>   
                           </li>
                           <li>
                              <?php 
                                 if($productDetails['availability']>0){
                                 ?>
                              <button id="buyNowBtn" data-id="<?php echo $productDetails['id']; ?>" value="<?php echo $productDetails['id']; ?>"> Buy Now</button>
                              <?php
                                 } ?>
                           </li>
                        </ul>
                        <?php 
                           if(!empty($productDetails['description'])){ ?>
                        <div class="description">
                           <h3 class="borded-heading"></h3>
                           <div class="section-heading">
                              <h4 class="bordered">Description</h4>
                           </div>
                           <div class="desc-inner">
                              <?php echo $productDetails['description']; ?>
                           </div>
                        </div>
                        <?php 
                           } 
                           $starRating1 = 0;
                           $starRating2 = 0;
                           $starRating3 = 0;
                           $starRating4 = 0;
                           $starRating5 = 0;
                           $totalRating = 0;
                           $rating1Percent = 0;
                           $rating2Percent = 0;
                           $rating3Percent = 0;
                           $rating4Percent = 0;
                           $rating5Percent = 0;
                           
                           $staRCountData = $functions->getProductReviewPercentagebyProductid($productDetails['id']);
                                    // print_r($functions->fetch($staRCountData));die();
                           if($functions->num_rows($staRCountData)>0){
                            while($starCount = $functions->fetch($staRCountData)){
                              
                              if(round($starCount['rating'])==1){
                                
                                $starRating1 =  $starCount['starCount'];
                                
                           
                              }elseif(round($starCount['rating'])==2){
                                $starRating2 =  $starCount['starCount'];
                           
                           
                              }elseif(round($starCount['rating'])==3){
                                $starRating3 =  $starCount['starCount'];
                           
                           
                              }elseif(round($starCount['rating'])==4){
                                $starRating4 =  $starCount['starCount'];
                           
                           
                              }elseif(round($starCount['rating'])==5){
                                $starRating5 =  $starCount['starCount'];
                           
                           
                              }
                              $starRating =  $starCount['starCount'];
                           
                            } 
                           
                            if(!empty($starRating)) {
                              $rating1Percent = ($starRating1 / $starRating * 100);
                              $rating2Percent = ($starRating2 / $starRating * 100);
                              $rating3Percent = ($starRating3 / $starRating * 100);
                              $rating4Percent = ($starRating4 / $starRating * 100);
                              $rating5Percent = ($starRating5 / $starRating * 100);
                            }
                           
                            /*echo $rating1Percent."<br>";
                            echo $rating2Percent."<br>";
                            echo $rating3Percent."<br>";
                            echo $rating4Percent."<br>";
                            echo $rating5Percent."<br>";*/
                           }
                           
                           ?>
                        <div class="review-sec">
                           <div class="section-heading">
                              <h4 class="bordered">Review</h4>
                           </div>
                           <div class="review-result">
                              <ul class="review-result-strip list-inline">
                                 <li>
                                    <div class="ratingDiv" id="ratingDiv1">
                                       <span class="ratingSpan star<?php echo str_replace(".","",$productDetails['avg_rating']);  ?>"></span>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="total-reviews">
                                       <p><?php if(!empty($productDetails['avg_rating'])){ ?><span><?php echo $productDetails['avg_rating']; ?>/5</span><?php } ?><?php echo $functions->num_rows($rationgresult); ?> Reviews</p>
                                    </div>
                                 </li>
                                 <li class="product-review-btn">
                                    <?php 
                                       if(isset($loggedInUserDetailsArr["id"]) && !empty($loggedInUserDetailsArr["id"]) && $loggedInUserDetailsArr['user_type']){
                                       ?>
                                    <a data-fancybox="" data-type="iframe" data-src="<?php echo BASE_URL; ?>/write-a-review.php?product_id=<?php echo $productDetails['id'];  ?>" href="javascript:;" class="btn default-btn">Write a Review</a>
                                    <?php }else{ ?>
                                    <a href="<?php echo BASE_URL; ?>/login.php?failed&cusLogin&redirect=<?php echo $permalink; ?>" class="btn default-btn">Write a product Review</a>
                                    <?php } ?>    
                                 </li>
                              </ul>
                              <img src="<?php echo BASE_URL; ?>/images/star-levels.png" alt="" style="width:auto;">
                              <div class="reviews_left">
                                 <div class="skill-shortcode">
                                    <div class="skill">
                                       <div class="progress">
                                          <div class="progress-bar" role="progressbar" style="width: <?php echo $rating5Percent; ?>%;">
                                             <span class="progress-bar-span">5 star</span>
                                             <span class="perc_only"><?php echo $rating5Percent; ?>%</span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="skill">
                                       <div class="progress">
                                          <div class="progress-bar" role="progressbar" style="width: <?php echo $rating4Percent; ?>%;">
                                             <span class="progress-bar-span">4 star</span>
                                             <span class="perc_only"><?php echo $rating4Percent; ?>%</span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="skill">
                                       <div class="progress">
                                          <div class="progress-bar" role="progressbar" style="width:  <?php echo $rating3Percent; ?>%;">
                                             <span class="progress-bar-span">3 star</span>
                                             <span class="perc_only"><?php echo $rating3Percent; ?>%</span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="skill">
                                       <div class="progress">
                                          <div class="progress-bar" role="progressbar" style="width:  <?php echo $rating2Percent; ?>%;">
                                             <span class="progress-bar-span">2 star</span>
                                             <span class="perc_only"> <?php echo $rating2Percent; ?>%</span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="skill">
                                       <div class="progress">
                                          <div class="progress-bar" role="progressbar" style="width:  <?php echo $rating1Percent; ?>%;">
                                             <span class="progress-bar-span">1 star</span>
                                             <span class="perc_only"><?php echo $rating1Percent; ?>%</span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <ul class="review-list">
                              <?php 
                                 if($functions->num_rows($rationgresult)>0){
                                  while($userDetails = $functions->fetch($rationgresult)){
                                 ?>
                              <li>
                                 <div class="reviewreview-list-inner">
                                    <h4><?php echo  $userDetails['name'] ?></h4>
                                    <h6>
                                       <?php echo "on".date('d F Y' ,strtotime($userDetails['created'])); ?>
                                    </h6>
                                    <h6>
                                       <div class="ratingDiv" id="ratingDiv1">
                                          <span class="ratingSpan star<?php echo str_replace(".","", $userDetails['rating']); ?>"></span>
                                       </div>
                                       <br>
                                       <p><?php echo  $userDetails['review']; ?></p>
                                    </h6>
                                 </div>
                              </li>
                              <?php     }
                                 } 
                                 ?>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <?php 
            $reslatedData = $functions->getRelatedProduct($productDetails["id"]);
              if($functions->num_rows($reslatedData)>0){
            ?>
         <section class="similar-product">
            <div class="container">
               <!-- Featred slider Start Here--->
               <div class="section-heading">
                  <h4 class="bordered">Similar Products</h4>
               </div>
               <div class="similar-prod-slider">
                  <?php 
                     while($relatedproductdetails = $functions->fetch($reslatedData)){
                      $productRelatedDetails = $functions->getproductByid($relatedproductdetails["related_product_id"]);
                                        if(!empty($productRelatedDetails['id'])){
                                        $main_image = $functions->getImageUrl('products',$productRelatedDetails['main_image'],'crop','');
                                        $detailsPageURL = $functions->getProductDetailPageURL($productRelatedDetails['id'],$_GET);
                     ?>
                  <div class="productlist-item match">
                     <div class="product-list-image">
                        <a href="<?php echo $detailsPageURL; ?>"><img src="<?php echo $main_image ?>" alt="<?php echo $productRelatedDetails['product_name']; ?>" class="img-responsive">
                        </a>
                        <div class="wc-btn-group fp similarhover">
                           <ul class="list-inline">
                              <li>
                                 <?php 
                                    if(isset($loggedInUserDetailsArr["id"]) && !empty($loggedInUserDetailsArr["id"]) && $loggedInUserDetailsArr['user_type'] == "b2c"){
                                    
                                    ?>
                                 <button type="button"  class="btn grey-btn wishlist clsWishlist" data-id="<?php echo $productRelatedDetails['id']; ?>" tabindex="0">
                                    <img src="<?php echo BASE_URL; ?>/images/wishlist.png" alt="">
                                  </button>
                                  
                                 <?php 
                                    }else{ ?>
                                 <a href="<?php echo BASE_URL."/login.php?failed&cusLogin"; ?>" class="btn grey-btn wishlist" tabindex="0"><img src="<?php echo BASE_URL; ?>/images/wishlist.png" alt=""> </a>
                                 <?php 
                                    } ?>    
                              </li>
                              <li>
                                  <a class="btn grey-btn wishlist" href="<?php echo BASE_URL."/".$productRelatedDetails['permalink']; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                              </li>

                              <?php 
                                 if($productRelatedDetails['availability']>0){
                                 ?>
                              <li>
                                <button type="button" value="<?php echo $productRelatedDetails['id']; ?>" class="btn grey-btn cartListingBtn" tabindex="0" >
                                  <!-- <img src="<?php //echo BASE_URL; ?>/images/cart.png" alt=""> -->
                                  <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                </button></li>
                              <?php 
                                 } ?>
                                 
                                 <li>
                                   <a href="#" class="btn grey-btn wishlist">Buy Now</a>
                                 </li>
                           </ul>
                        </div>
                     </div>
                     <div class="product-list-details">
                        <a href="<?php echo $detailsPageURL; ?>">
                           <h3><?php echo ucwords($productRelatedDetails['product_name']); ?></h3>
                        </a>
                        <?php 
                           if(isset($productRelatedDetails['discount_price']) && !empty($productRelatedDetails['discount_price'])){ 
                           ?>
                        <p class="listpricebox">
                           <span class="sellprice"><i class="fa fa-inr"></i><?php echo $productRelatedDetails['price']; ?></span>
                           <!-- <span class="retailprice"><i class="fa fa-inr"></i><//?php echo $productRelatedDetails['discount_price']; ?></span> -->
                        </p>
                        <?php 
                           }else{ ?>
                        <p class="listpricebox">
                           <span class="sellprice"><i class="fa fa-inr"></i><?php echo $productRelatedDetails['price']; ?></span>
                        </p>
                        <?php 
                           } 
                           if($productRelatedDetails['availability']>0){  ?>
                        <a class="buybtn listingBuyNow" data-id="<?php echo $productRelatedDetails['id']; ?>">Buy Now</a>
                        <?php 
                           } ?>
                     </div>
                     <div class="priceDiv">
                        <input type="hidden" name="available_qty" class="available_qty" value="<?php echo $productDetails["availability"]; ?>">
                     </div>
                     <div class="priceDivQty">
                        <input type="hidden" name="b2b_min_qty" class="b2b_min_qty" value="<?php echo $productDetails['b2b_min_qty']; ?>">
                     </div>
                  </div>
                  <?php 
                     }  
                     } ?>
               </div>
               <!-- Featred slider End Here--->
            </div>
         </section>
         <?php  } 
            ?>      
      </div>
      <!--Main End Code Here-->
      <!--footer start menu head-->
      <?php include("include/footer.php");?> 
      <!--footer end menu head-->
      <?php include("include/footer-link.php");?>
       <script async src="<?php echo BASE_URL; ?>/js/add-to-any.js"></script>
      <script>
         $(function($) {
         
            // define the gallery object
            var $gallery = $('#gallery_01');
         
            // Build array of objects to open in Fancybox.
            var $imgs = [];
            $('a', $gallery).each(function() {
            $imgs.push({'src': $(this).data('zoom-image')});
            });
         
               if ($(window).width() > 780) {
                  var elevateZoomOptions = {
                  gallery:'gallery_01',
                  cursor: 'pointer',
                   zoomType:'window',
                  easing : true,
                  scrollZoom : true,
                  galleryActiveClass: "active",
                  imageCrossfade: true
                  // loadingIcon: "<?php //echo BASE_URL; ?>/images/ajax-loader.gif"
                };
                 
                 $("#zoom_03").elevateZoom(elevateZoomOptions); 
               
         
               // Bind Fancybox to clicking the zoom image.
               // Open it to the currently active index.
               $("#zoom_03").on("click", function(e) {
               e.preventDefault();
               var active_index = $('.active', $gallery).index();
               $.fancybox.open($imgs, false, active_index);
               });
               }
               if ($(window).width() < 770) {
                   var elevateZoomOptions = {
                   gallery:'gallery_01',
                   cursor: 'pointer',
                   zoomType:'none',
                   easing : true,
                   scrollZoom : true,
                   galleryActiveClass: "active",
                   imageCrossfade: true
                   // loadingIcon: "<?php //echo BASE_URL; ?>/images/ajax-loader.gif"
                 };
                 
                 $("#zoom_03").elevateZoom(elevateZoomOptions); 
               
         
               // Bind Fancybox to clicking the zoom image.
               // Open it to the currently active index.
               $("#zoom_03").on("click", function(e) {
               e.preventDefault();
               var active_index = $('.active', $gallery).index();
               $.fancybox.open($imgs, false, active_index);
         });
         
               }
         
              $('.gallery').on('click', '.slick-slide', function(e){
                if($(this).hasClass('hasVideo')){
                  $('.videoShow').show();
                } else {
                  $('.videoShow').hide();
                }
              });
              //  $('#videoOpen1').click(function(){
              //  $('#videoShow1').show();
              // });
              
              // $('.videoOpen').click(function(){
              //     $(this).parent(".hasVideo").find(".videoShow").toggle();
              // });
               $('.demonvideo1').click(function(){
                 $(".vmdemo1").show();
                 $(".zoomContainer").hide();           
                 $(".vmdemo2").hide();
                 $(".vmdemo3").hide();
                 $(".vmdemo4").hide();
                 $(".vmdemo5").hide();
               });
               $('.demonvideo2').click(function(){
                 $(".vmdemo1").hide();
                 $(".zoomContainer").hide();                     
                 $(".vmdemo2").show();
                 $(".vmdemo3").hide();
                 $(".vmdemo4").hide();
                 $(".vmdemo5").hide();
               });
               $('.demonvideo3').click(function(){
                 $(".vmdemo1").hide();
                 $(".vmdemo2").hide();
                 $(".zoomContainer").hide();                     
                 $(".vmdemo3").show();
                 $(".vmdemo4").hide();
                 $(".vmdemo5").hide();
               });
               $('.demonvideo4').click(function(){
                 $(".vmdemo1").hide();
                 $(".vmdemo2").hide();
                 $(".zoomContainer").hide();                     
                 $(".vmdemo3").hide();
                 $(".vmdemo4").show();
                 $(".vmdemo5").hide();
               });
               $('.demonvideo5').click(function(){
                 $(".vmdemo1").hide();
                 $(".vmdemo2").hide();
                 $(".zoomContainer").hide();                     
                 $(".vmdemo3").hide();
                 $(".vmdemo4").hide();
                 $(".vmdemo5").show();
               });
              
              $('.elevatezoom-gallery img').click(function(){
               $('.videoShow').hide();
               $(".zoomContainer").show();                    
              }); 
        });
      </script>
      <script>
         $(document).ready(function(){
           $(".scroll").mCustomScrollbar({
          theme: "inset-dark",
          scrollButtons: {enable:true}
           });
         });      
      </script>   
      <script>
         //featured-slider
         
         $('.similar-prod-slider').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: false,
            autoplaySpeed: 2000,
            dots: false,
            centerMode: true,
            arrows: true,
             responsive: [
                 {
                   breakpoint: 1025,
                   settings: {
                     slidesToShow: 3,
                     slidesToScroll: 1,
                   }
                 },
                 {
                   breakpoint: 992,
                   settings: {
                     slidesToShow: 2,
                     slidesToScroll: 1
                   }
                 },
                 {
                   breakpoint: 768,
                   settings: {
                     slidesToShow: 2,
                     slidesToScroll: 1
                   }
                 },
                 {
                   breakpoint: 481,
                   settings: {
                     slidesToShow: 1,
                     slidesToScroll: 1
                   }
                 },
               ]
         });
         
           $( document ).scroll(function() {
            
             var scroll = $(window).scrollTop();
             if($(window).width() > 768){
               if(scroll > 100){
                   $(".productimgbox").addClass("scrollproductimage");
               } 
             }
             
           });
         
          
         
      </script>

   </body>
</html>