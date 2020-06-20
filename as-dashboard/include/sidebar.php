<?php
	$userPermissionsArray = explode(',',$loggedInUserDetailsArr['permissions']);
	$basename = basename($_SERVER['REQUEST_URI']);
	$currentPage = pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME);
	//echo $_SERVER['REQUEST_URI'];
	//$sql = "ALTER TABLE `ds_subscription` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;";
	//$admin->query($sql);
?>
<div class="sidebar collapse">
    <div class="sidebar-content">
		<!-- Main navigation -->
		<ul class="navigation">
			<li <?php if($currentPage=='category-master.php' || $currentPage=='category-add.php' || $currentPage=='sub-category-master.php' || $currentPage=="sub-category-add.php") { echo 'class="active"'; }?>>
				<a href="category-master.php"><span>Category Master</span> <i class="icon-diamond"></i></a>
			</li>
			<!-- <li <?php //if($currentPage=='brand-master.php') { echo 'class="active"'; }?>>
				<a href="brand-master.php"><span>Brand Master</span> <i class="icon-diamond"></i></a>
			</li> -->
			<li <?php if($currentPage=='product-master.php' || $currentPage=='prdouct-add.php') { echo 'class="active"'; }?>>
				<a href="product-master.php"><span>Product Master</span> <i class="icon-diamond"></i></a>
			</li>
			<li <?php if($currentPage=='attribute-master.php' || $currentPage=='attribute-add.php') { echo 'class="active"'; }?>>
				<a href="attribute-master.php"><span>Attribute Master</span> <i class="icon-diamond"></i></a>
			</li>
			<li <?php if($currentPage=='reviews.php') { echo 'class="active"'; }?>>
				<a href="reviews.php"><span>Reviews</span> <i class="icon-diamond"></i></a>
			</li>
			<li <?php if(($currentPage=='customers.php' || $currentPage=='customers-add.php') && $_GET['cType']=="b2c") { echo 'class="active"'; }?>>
				<a href="customers.php?cType=b2c"><span>Customer Login master</span> <i class="icon-diamond"></i></a>
			</li>
			<li <?php if(($currentPage=='customers.php' || $currentPage=='customers-add.php') && $_GET['cType']=="b2b") { echo 'class="active"'; }?>>
				<a href="customers.php?cType=b2b"><span>Vendor Login master</span> <i class="icon-diamond"></i></a>
			</li>
			<li <?php if($currentPage=='refund-request.php') { echo 'class="active"'; }?>>
				<a href="refund-request.php"><span>Cancel Request</span> <i class="icon-book2"></i></a>
			</li>
			<li <?php if($currentPage=='order.php') { echo 'class="active"'; }?>>
				<a href="order.php"><span>Customer Orders</span> <i class="icon-book2"></i></a>
			</li>
			<!-- <li <?php //if($currentPage=='order.php') { echo 'class="active"'; }?>>
				<a href="order.php"><span>Customer Orders</span> <i class="icon-book2"></i></a>
			</li> -->
			<li <?php if($currentPage=='discount-coupon-master.php' || $currentPage=='discount-coupon-master.php') { echo 'class="active"'; }?>>
				<a href="discount-coupon-master.php"><span>Discount Coupon Master</span> <i class="icon-diamond"></i></a>
			</li>
			<!-- <li <?php //if($currentPage=='shipping_charges.php') { echo 'class="active"'; }?>>
				<a href="shipping_charges.php"><span>Shipping Charges</span> <i class="icon-diamond"></i></a>
			</li> -->
			<li <?php if($currentPage=='testimonial-master.php') { echo 'class="active"'; }?>>
				<a href="testimonial-master.php"><span>Testimonial</span> <i class="icon-diamond"></i></a>
			</li>
			<li class="has-ul">
				<a href="#" class="expand"><span>CMS components</span> <i class="icon-paragraph-justify2"></i></a>
				<ul class="hidden-ul" style="">
					<li><a href="about-us.php">About Us</a></li>
					<li><a href="terms.php">Terms and Conditions</a></li>
					 <!-- <li><a href="shipping-policy.php">Shipping Policy</a></li> -->
					<li><a href="faq-master.php">FAQ</a></li>
					<li><a href="return-policy.php">Return Policy</a></li>
					<li><a href="privacy-policy.php">Privacy Policy</a></li>
					
					<li><a href="contact-us-master.php">Contact Us</a></li>
					<li><a href="disclaimer.php">Disclaimer</a></li>
					<li><a href="copyright.php">Copyright</a></li>
				</ul>
			</li>
			<li <?php if($currentPage=='sliderBanner.php' or $currentPage=='sliderBanner-add.php') { echo 'class="active"'; }?>>
				<a href="sliderBanner.php"><span>Slider Banners</span> <i class="icon-diamond"></i></a>
			</li>
			<!-- <li <?php //if($currentPage=='homepage-cms.php') { echo 'class="active"'; }?>>
				<a href="homepage-cms.php"><span>Home Page CMS</span> <i class="icon-diamond"></i></a>
			</li> -->
			<li <?php if($currentPage=='subscription-master.php') { echo 'class="active"'; }?>>
				<a href="subscription-master.php"><span>Subscription Master</span> <i class="icon-diamond"></i></a>
			</li>
			<li <?php if($currentPage=='product-report.php') { echo 'class="active"'; }?>>
				<a href="product-report.php"><span>Report</span> <i class="icon-diamond"></i></a>
			</li>
		</ul>
      <!-- /main navigation -->
	</div>
</div>
 
<!-- /sidebar -->
