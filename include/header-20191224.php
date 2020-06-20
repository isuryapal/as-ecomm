<?php
  
  if(isset($_POST['addSubscription'])){
        if(!empty($_POST['email'])){
            $sql = "INSERT INTO ".PREFIX."subscription(email) VALUES ('".$_POST['email']."')";

            $functions->query($sql);

            header("location:thankyou.php?subsccess");

            exit;

        }else{

            header("location:thankyou.php?emailRequired");

            exit;

        }
    }
	
  include_once('include/classes/Cart.class.php');
  $cartObj = new Cart();
  $amtArr = $functions->getCartAmountAndQuantity($cartObj, null);
  $loggedInUserDetailsArr = $functions->sessionExists();
  $contactUsCMSDetails  = $functions->getContactUsCmsMasterDetails();
?> 


<!-- Loader start -->
<div id="loader-wrapper">
    <div class="loader">            
        <img src="<?php echo BASE_URL; ?>/images/logoold.png">
        <h2>Please Wait</h2>
    </div>
</div>
<!-- Loader end -->


<div class="wrapper">	
<header>
    <div class="top-header">
        <div class="container">
            <ul class="pull-left list-inline">
                <li><img src="<?php echo BASE_URL; ?>/images/phone.png"/><a href="javascript:;"><?php echo $contactUsCMSDetails["contact"]; ?></a></li>
				<li><img src="<?php echo BASE_URL; ?>/images/msg.png"/> <a href="mailto:<?php echo $contactUsCMSDetails["email"]; ?>"><?php echo $contactUsCMSDetails["email"]; ?></a></li>
            </ul>
            <div class="pull-right list-inline top-wishlist">
                <?php 
                    if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="Customer"){
                ?>
                        <a href="<?php echo BASE_URL; ?>/cus-mywishlist.php"><img src="<?php echo BASE_URL; ?>/images/wishlist-white.png" alt=""/> Wishlist</a> 
            <?php   } ?>
            </div>
        </div>
    </div>
    <div class="middle-header">
        <div class="container">
            <div class="row item-center">
                <div class="logo-box">
					<a href="<?php echo BASE_URL; ?>" class="logo">
					    <img src="<?php echo BASE_URL; ?>/images/logo.png" alt="logo"/>
					</a>
				</div>
                <div class="header-search-box">
                    <div class="head-search-bar">
                        <div class="cd-dropdown-wrapper">
                            <a class="cd-dropdown-trigger" href="#0">Category</a>
                            <nav class="cd-dropdown">
                                <h2>Category</h2>
                                <a href="#0" class="cd-close">Close</a>
                                    <ul class="cd-dropdown-content">
                                        <?php 
                                        $categoryMenuRS = $functions->getAllActiveCategoriesList1();
                                        while($categoryMenu = $functions->fetch($categoryMenuRS)) {
                                              $subCategoryMenuByCategoryRS = $functions->getActiveSubCategoriesByCategoryId($categoryMenu['id']);
                                              
                                        ?>
                                                <li class="has-children">
                                                    <a class="makeitpossible" href="<?php echo BASE_URL.'/'.$categoryMenu['permalink'] ?>"><?php echo $categoryMenu['category_name'] ?>
                                                    </a>
                                                    <a class="menucssmakeitpossible"></a>
                                                    <?php 
                                                    if($functions->num_rows($subCategoryMenuByCategoryRS)>0){
                                                    ?>
                                                        <ul class="cd-secondary-dropdown is-hidden">
                                                            <li class="go-back">
                                                                <a class="makeitpossible" href="<?php echo BASE_URL.'/'.$categoryMenu['permalink'] ?>"><?php echo $categoryMenu['category_name']; ?></a>
                                                                    <a class="menucssmakeitpossible"></a></li>
                                                          <!-- <li class="see-all"><a href="#0">All Clothing</a></li> -->
                                                          <?php 
                                                            while($subCategoryMenu = $functions->fetch($subCategoryMenuByCategoryRS)) {
                                                              //$typeMenuBySubCategoryRS = $functions->getActiveTypeBySubCategoryId($subCategoryMenu['id']);
                                                      ?>
                                                                <li class="has-children">
                                                                    <a class="makeitpossible" href="<?php echo BASE_URL.'/'.$categoryMenu['permalink'].'/'.$subCategoryMenu['sub_category_permalink'] ?>"><?php echo $subCategoryMenu['sub_category_name']; ?></a>
                                                                     <a class="menucssmakeitpossible"></a>
                                                                    <ul class="is-hidden">
                                                                        <?php 
                                                                        //if($functions->num_rows($typeMenuBySubCategoryRS)>0){
                                                                        ?>
                                                                        <li class="go-back">
                                                                            <a class="makeitpossible" href="<?php echo BASE_URL.'/'.$categoryMenu['permalink'].'/'.$subCategoryMenu['sub_category_permalink'] ?>"><?php echo $subCategoryMenu['sub_category_name']; ?></a>
                                                                             <a class="menucssmakeitpossible"></a>
                                                                        </li>
                                                                        <li>
                                                                          <?php 
                                                                            $sqlSub = "SELECT * FROM ".PREFIX."subsubCategory WHERE `sub_category_id`='".$subCategoryMenu['id']."' and active='Yes'";
                                                                              //echo $sqlSub; exit;
                                                                              $getproduct = $functions->query($sqlSub);
                                                                              while($getproductbysubcategory = $functions->fetch($getproduct)){
                                                                          ?>
                                                                                <a href="<?php echo BASE_URL.'/'.$categoryMenu['permalink'].'/'.$subCategoryMenu['sub_category_permalink'].'/'.$getproductbysubcategory['permalink']; ?>"><?php echo $getproductbysubcategory['subcategory_name']; ?></a>
                                                                          <?php 
                                                                            } ?>
                                                                        </li>
                                                                    <?php 
                                                                      //} ?>    
                                                                    </ul>
                                                                </li>
                                                        <?php    
                                                            }
                                                        ?>
                                                    </ul>
                                                <?php 
                                                } ?>    
                                            </li>
                                      <?php
                                        } ?>      
                                    </ul> <!-- .cd-dropdown-content -->
                                </nav> 
                            </div>
                        <form action="<?php echo BASE_URL; ?>/search.php" metho="GET" id="searchFrm">
							<input type="text" placeholder="Search" id="search_text" value="<?php if(isset($_GET['product_id']) && !empty($_GET['product_id'])){ echo $_GET['product_id']; }?>" name="product_id" required />
							<button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
						</form>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <?php 
                  //print_r($loggedInUserDetailsArr);
                ?>
                <div class="sign-in-box">
					<img src="<?php echo BASE_URL; ?>/images/my-account.png"/>
					<?php 
                    if(isset($loggedInUserDetailsArr['id']) && !empty($loggedInUserDetailsArr['id'])){
                    ?>
                        <div class="after-login">
					        <a href="javascript:;" style="display:block;"><?php echo ucwords($loggedInUserDetailsArr['first_name']); ?><span>&nbsp;</span></a>
					        <div class="log-dropdown">
						        <ul>
							        <!-- <li class="username">Vinayak Patil</li> -->
                                    <?php 
                                        if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="Customer"){
                                    ?>
							                <li><a href="<?php echo BASE_URL; ?>/cus-my-account.php">My Account</a></li>
                                    <?php 
                                        }else{ ?>  
                                            <li><a href="<?php echo BASE_URL; ?>/vendor-myaccount.php">My Account</a></li>      
                                    <?php   
                                        } ?>        
							        <li><a href="<?php echo BASE_URL; ?>/logout.php">Logout</a></li>
						        </ul>
					        </div>
					    </div>
                  <?php 
                     }else{
                  ?> 
					    <a class="before-login">
                            <a href="<?php echo BASE_URL.'/login.php'; ?>">Sign In</a>
                            <a href="<?php echo BASE_URL.'/register.php'; ?>">Sign Up</a>
                        </a>
                  <?php 
                    } ?>  
				</div>
                <div class="header-cart-box cart-icon">
					<img src="<?php echo BASE_URL; ?>/images/my-cart.png"/>My Cart <span>(<span class="cartCount"><?php echo $amtArr['items']; ?></span> items)</span>
                </div>
            </div>  
        </div>
    </div>
    <?php 
       // print_r($loggedInUserDetailsArr['user_type']);
    ?>
    <div class="cart-notification fadeInDown animated" style="display: none;">
            <div class="container">
            <div class="cart-notification-container alert"><i class="fa fa-check"></i> Cart updated</div>
            </div>
        </div>
</header>
<div class="cart_section">
    <div id="cart-wrapper">
        <?php 
            include("include/cart/cart-inc.php");
            echo $cartHTML;
        ?>
    </div>
</div>