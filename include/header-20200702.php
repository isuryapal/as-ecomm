<?php
 $basename = basename($_SERVER['REQUEST_URI']);
 $currentPage = pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME);
?>
<?php
    if (isset($_POST['addSubscription'])) {
        if (!empty($_POST['email'])) {
            $sql = "INSERT INTO ".PREFIX."subscription (email) VALUES ('".$_POST['email']."')";

            $functions->query($sql);
    
            header("location:thankyou.php?subsccess");
    
            exit();
    
        }else{
    
          header("location:thankyou.php?emailRequired");
    
          exit();
        }
    }
    
    include_once("include/classes/Cart.class.php");
    $cartObj = new Cart;
    $amtArr = $functions->getCartAmountAndQuantity($cartObj, null);
    $loggedInUserDetailsArr = $functions->sessionExists();
    // print_r($loggedInUserDetailsArr);die();
    $contactUsCMSDetails = $functions->getContactUsCmsMasterDetails();
    ?> 
<!-- Loader start -->
<div id="loader-wrapper">
    <div class="loader">
        <img src="<?php echo BASE_URL; ?>/images/loaders.gif">
       
    </div>
</div>
<!-- Loader end -->
<div class="wrapper">
<header class="header">
    <div class="upperhead">
        <div class="container-fluid">
            <div class="soc-icons">
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-google-plus"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
            </div>
            <div class="right-side">
                <ul>
                    <li><i class="fa fa-phone" aria-hidden="true"></i><a href="tel:+918048006399">+91 8048006399</a></li>
                    <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:info@sanitaryarvind.com">info@sanitaryarvind.com</a></li>
                    <?php
                    if(isset($loggedInUserDetailsArr['id']) && !empty($loggedInUserDetailsArr['id'])){
                    ?>
                    <?php 
                        if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2c"){
                    ?>
                            <li><a href="<?php echo BASE_URL; ?>/cus-my-account.php">My Account</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/logout.php">Logout</a></li>
                    <?php 
                        }else{ ?>  
                            <li><a href="<?php echo BASE_URL; ?>/vendor-myaccount.php">My Account</a></li>        
                            <li><a href="<?php echo BASE_URL; ?>/logout.php">Logout</a></li>                    
                    <?php   
                        } ?>
                    <!-- <li class="bigg">Hello, <?php //echo ucfirst($loggedInUserDetailsArr['first_name']); ?></li> -->
                    <?php
                    }else{
                    ?>
                    <li class="bigg"><a href="<?php echo BASE_URL;?>/login.php">Login</a></li>
                    <li class="bigg1"><a href="<?php echo BASE_URL;?>/register.php">Sign Up</a></li>
                    <?php 
                    } 
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="cart-notification fadeInDown animated" style="display: none;">
        <div class="container">
        <div class="cart-notification-container alert">Product added to wishlist</div>
        </div>
    </div>
    <div class="downhead">
        <div class="container-fluid">
            
            
            <a href="<?php echo BASE_URL;?>/index.php" class="logo"><img src="<?php echo BASE_URL;?>/images/logo.png" alt=""></a>

            <div class="header-rgt">
                <ul>
                    <!-- <li class="cart-seach">
                        <div class="srchh">
                            <div class="onclickkk">
                                <img src="images/search.png" alt="">
                            </div>
                                <input class="srchbar" type="text" style="display:none;">
                             <div class="header-cart-box cart-icon">
                                <img src="<?php echo BASE_URL; ?>/images/cart.png" alt="">
                            </div>
                        </div>                  
                    </li> -->
                    <li class="headermobilesearch">

                    <form action="<?php echo BASE_URL; ?>/search.php" class=" navbar-form navbar-left" method="GET" id="searchFrm1">
                        <div class="input-group">
                            <input type="text" placeholder="Search" class="form-control" id="search_text" value="<?php if(isset($_GET['product_id']) && !empty($_GET['product_id'])){ echo $_GET['product_id']; }?>" name="product_id" required />
                            <div class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                            </div>
                        </div>
                    </form>
                </li>
                </ul>
            </div>

            <input class="menu-btn" type="checkbox" id="menu-btn" />
            <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
            <div class="header-cart-box cart-icon mobilecart">
                <img src="<?php echo BASE_URL; ?>/images/cart.png" alt="">
            </div>
            <ul class="menu headermenu">
            <li class="headersearch">
            <form action="<?php echo BASE_URL; ?>/search.php" class="navbar-form navbar-left" method="GET" id="searchFrm">
                <div class="input-group">
                    <input type="text" placeholder="Search" class="form-control" id="search_text" value="<?php if(isset($_GET['product_id']) && !empty($_GET['product_id'])){ echo $_GET['product_id']; }?>" name="product_id" required />
                    <div class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                        <i class="glyphicon glyphicon-search"></i>
                    </button>
                    </div>
                </div>
            </form>
             </li>
                <li><a <?php if($currentPage=='index.php') { echo 'class="active"'; }?> href="<?php echo BASE_URL;?>/index.php">Home</a></li>
                <li><a <?php if($currentPage=='about.php') { echo 'class="active"'; }?> href="<?php echo BASE_URL;?>/about.php">About Us</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" >Our Product <b class="caret"></b></a>
                    <ul class="dropdown-menu multi-level dropdownmenu1">
                        <!-- <li><a href="listing.php">Product 1</a></li> -->
                        <?php
                        $catSql = "SELECT * FROM ".PREFIX."category_master order by category_name";
                        $catResult = $functions->query($catSql);
                        if($functions->num_rows($catResult)>0){
                            while($catRow = $functions->fetch($catResult)){
                        ?>
                        <li class="dropdown-submenu">
                            <div class="droperarrow"></div>
                            <a href="<?php echo BASE_URL."/".$catRow['permalink']; ?>" class="dropdown-toggle" ><?php echo $catRow['category_name']; ?></a>
                            <!-- Product 2 -->
                            <ul class="dropdown-menu dropdown-subsubmenu">
                                <?php
                                $subCatSql = "SELECT * FROM ".PREFIX."sub_category_master WHERE category_id = ".$catRow['id']." order by sub_category_name";
                                $subCatResult = $functions->query($subCatSql);
                                if($functions->num_rows($subCatResult)>0){
                                    while($subCatRow = $functions->fetch($subCatResult)){
                                ?>
                                <li class="dropdown-submenu <?php if($functions->num_rows($subCatResult)>0){ echo "has-children"; } ?>">
                                    <div class="droperarrow"></div>
                                    <a href="<?php echo BASE_URL."/".$catRow['permalink']."/".$subCatRow['sub_category_permalink']; ?>" class="dropdown-toggle" ><?php echo $subCatRow['sub_category_name']; ?></a>
                                    <!-- Product 3 -->
                                        <?php
                                        $subsubCatSql = "SELECT * FROM ".PREFIX."subsubCategory WHERE sub_category_id = ".$subCatRow['id']." order by subcategory_name";
                                        $subsubCatResult = $functions->query($subsubCatSql);
                                        if($functions->num_rows($subsubCatResult)>0){
                                        ?>
                                            <ul class="dropdown-menu dropdown-subsubsubmenu">
                                        <?php
                                            while($subsubCatRow = $functions->fetch($subsubCatResult)){
                                        ?>
                                        <li class="dropdown-submenu">
                                            <div class="droperarrow"></div>
                                            <a href="<?php echo BASE_URL."/".$catRow['permalink']."/".$subCatRow['sub_category_permalink']."/".$subsubCatRow['permalink']; ?>" class="dropdown-toggle" ><?php echo $subsubCatRow['subcategory_name']; ?></a>
                                            <!-- Product 4 -->
                                                <?php
                                                $subsubsubCatSql = "SELECT * FROM ".PREFIX."subsubsubCategory WHERE subsubcate_id = ".$subsubCatRow['id']." order by subsubsub_name";
                                                $subsubsubCatResult = $functions->query($subsubsubCatSql);
                                                if($functions->num_rows($subsubsubCatResult)>0){
                                                ?>
                                                    <ul class="dropdown-menu dropdown-subsubsubsubmenu">
                                                <?php
                                                    while($subsubsubCatRow = $functions->fetch($subsubsubCatResult)){
                                                ?>
                                                <li class="dropdown-submenu">
                                                    <div class="droperarrow"></div>
                                                    <a href="<?php echo BASE_URL."/".$catRow['permalink']."/".$subCatRow['sub_category_permalink']."/".$subsubCatRow['permalink']."/".$subsubsubCatRow['subsubsub_permalink']; ?>" class="dropdown-toggle" ><?php echo $subsubsubCatRow['subsubsub_name']; ?></a>
                                                    <!-- Product 4 -->
                                                        <?php
                                                        $subsubsubsubCatSql = "SELECT * FROM ".PREFIX."subsubsubsubCategory WHERE subsubsubcate_id = ".$subsubsubCatRow['id']." order by subsubsubsub_name";
                                                        $subsubsubsubCatResult = $functions->query($subsubsubsubCatSql);
                                                        if($functions->num_rows($subsubsubsubCatResult)>0){
                                                        ?>
                                                            <ul class="dropdown-menu dropdown-subsubsubsubmenu">
                                                        <?php
                                                            while($subsubsubsubCatRow = $functions->fetch($subsubsubsubCatResult)){
                                                        ?>
                                                        <li class="dropdown-submenu">
                                                        <div class="droperarrow"></div>    
                                                        <a href="<?php echo BASE_URL."/".$catRow['permalink']."/".$subCatRow['sub_category_permalink']."/".$subsubCatRow['permalink']."/".$subsubsubCatRow['subsubsub_permalink']."/".$subsubsubsubCatRow['subsubsubsub_permalink']; ?>" class="dropdown-toggle" ><?php echo $subsubsubsubCatRow['subsubsubsub_name']; ?></a>
                                                        </li>
                                                        <?php
                                                            }
                                                        ?>
                                                        </ul>
                                                        <?php
                                                        }
                                                        ?>
                                                </li>
                                                <?php
                                                    }
                                                ?>
                                                </ul>
                                                <?php
                                                }
                                                ?>
                                        </li>
                                        <?php
                                            }
                                        ?>
                                        </ul>
                                        <?php
                                        }
                                        ?>
                                </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                    <?php 
                        }
                    } ?>
                    </ul>
                </li>
                <li><a <?php if($currentPage=='contact.php') { echo 'class="active"'; }?> href="<?php echo BASE_URL;?>/contact.php">Contact Us</a></li>
                <li class="cartmenu">
                    <div class="header-cart-box cart-icon">
                        <img src="<?php echo BASE_URL; ?>/images/cart.png" alt="">
                    </div>
                </li>
            </ul>
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