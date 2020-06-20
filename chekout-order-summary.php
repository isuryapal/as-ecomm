<?php 
	include_once 'include/functions.php';
   	$functions = new Functions(); 
   	$redirect = 'chekout-order-summary.php';
	if(!$loggedInUserDetailsArr = $functions->sessionExists()){
		header("location: ".BASE_URL."/login.php?redirect=".$redirect);
      	exit;
	}
	if(!$loggedInUserDetailsArr['user_type']){
        header("location: ".BASE_URL."/login.php?failed&cusLogin");
        exit;   
    }
	if(isset($_GET['sameASBilling'])){
		$_SESSION[SITE_NAME]['BILLADDRESS']['shipping'] = $_SESSION[SITE_NAME]['BILLADDRESS']['Billing'];
		header("location:chekout-order-summary.php");
		exit;
	}
	if(isset($_GET['sameASBilling'])){
		if($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']){
			unset($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']);
		}
		header("location:chekout-order-summary.php");
		exit;
	}
?>
<!DOCTYPE>
<html>
   <head>
	<title>Arvind Sanitary</title>
      <?php include("include/header-link.php");?>
   </head>
   <body class="chekout-order-summary-page">
      <!--Top start menu head-->       
      <?php include("include/header.php");?>
      
      <div class="only-breadcrumbs">
		<div class="container">
			<ul class="breadcrumbs">
				<li><a href="<?php echo BASE_URL; ?>">Home</a></li>
				<li>Checkout</li>
			</ul>
		</div>
	</div>
	 
     
	<div class="inner-content bt">
		<div class="container">
			<div class="inner-wrapper">
				<h1 class="page-heading">CHECKOUT</h1>
				<section class="billing-shipping">
			 		<?php 	
						if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['Billing']) && !empty($_SESSION[SITE_NAME]['BILLADDRESS']['Billing'])){
							$defaultAddress = $functions->getByIdAddress($_SESSION[SITE_NAME]['BILLADDRESS']['Billing']);
						}else{
							$defaultAddress = $functions->getPrimaryAddress($loggedInUserDetailsArr['id']);	
						}
								
					 ?>
						<div class="billing-add match">
							<div class="box-inner-ctn">
								<h3>Billing Address</h3>
								<?php
									if($functions->num_rows($defaultAddress)>0){
							    		$defaultAddress = $functions->fetch($defaultAddress);
							    		$_SESSION[SITE_NAME]['BILLADDRESS']['Billing'] = $defaultAddress['id'];
								?>
										<p><b>Name</b><?php echo $defaultAddress['customer_fname']; ?></p>
										<p><b>Address</b><?php echo $functions->getDisplayAddress($defaultAddress,"<br>"); ?></p>
								<?php 
									} 	
								?>	
								<a class="checkout-summery-pop" data-fancybox="" data-type="iframe" data-src="" href="<?php echo BASE_URL; ?>/checkout-summery-popup.php?Billing=Billing">
									Change Address 
								<span><i class="fa fa-angle-down" aria-hidden="true"></i></span>
								</a>
								<p id="billError" style="color:red;"></p>
							</div>
						</div>
						<div class="shipping-add match">
							<div class="box-inner-ctn">
								<h3>Shipping Address</h3>
								<?php 
								if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']) && !empty($_SESSION[SITE_NAME]['BILLADDRESS']['shipping'])){
									$defaultAddress = $functions->getByIdAddress($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']);
									if($functions->num_rows($defaultAddress)>0){
							    		$defaultAddress = $functions->fetch($defaultAddress);
							    		$_SESSION[SITE_NAME]['BILLADDRESS']['shipping'] = $defaultAddress['id'];
								?>	
										<p><b>Name </b> <?php echo $defaultAddress['customer_fname']; ?></p>
										<p><b>Address </b><?php echo $functions->getDisplayAddress($defaultAddress,"<br>"); ?></p>
								<?php 
									}	
								}	?>
									<a class="checkout-summery-pop" data-fancybox="" data-type="iframe" data-src="" href="<?php echo BASE_URL; ?>/checkout-summery-popup.php?shipping=shipping">
									Select Address 
								<span><i class="fa fa-angle-down" aria-hidden="true"></i></span>
								</a>
								<p id="shipError" style="color:red;"></p>
								<div class="same-as-billing">
									<label class="container-ck">
										<input type="checkbox" value="<?php if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['Billing'])){ echo $_SESSION[SITE_NAME]['BILLADDRESS']['Billing']; } ?>" name="sameAsBilling" <?php if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']) && isset($_SESSION[SITE_NAME]['BILLADDRESS']['Billing']) && $_SESSION[SITE_NAME]['BILLADDRESS']['shipping']==$_SESSION[SITE_NAME]['BILLADDRESS']['Billing']){ echo "checked"; } ?>> Same as Billing Address
										<span class="checkmark-ck"></span>
									</label>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
				</section>
				<div id="checkout-cart-wrapper">
					<?php 
						include_once"include/cart/checkout-cart-page.inc.php";
						echo $checkoutCartPageHTML;
					?>
				</div>
			</div>
 		</div>
	</div>
	 
	 
	  
         <!--Main End Code Here-->
      <!--footer start menu head-->
      <?php include("include/footer.php");?> 
      <!--footer end menu head-->
      <?php include("include/footer-link.php");?>
        <script>
			$("[data-fancybox]").fancybox({
				iframe : {
					css : {
						width : '450px'
					}
				}
			});
        </script>
        <script>
			$(document).ready(function(){
		  		$(".fp-btn").click(function(){
					$(".login-box").addClass("switch-form");
		  		});
		  
		 		$(".back-btn").click(function(){
					$(".login-box").removeClass("switch-form");
		  		});
		 		
		 		$(document).on("click", "#proceedCheckout", function(){
		 			var billingAddress = "<?php if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['Billing']) && !empty($_SESSION[SITE_NAME]['BILLADDRESS']['Billing'])){ echo $_SESSION[SITE_NAME]['BILLADDRESS']['Billing']; } ?>"; 
				  	var shippingAddress = "<?php if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']) && !empty($_SESSION[SITE_NAME]['BILLADDRESS']['shipping'])){ echo $_SESSION[SITE_NAME]['BILLADDRESS']['shipping']; } ?>";
				  	
				  	if(shippingAddress=="" || shippingAddress=="0" || billingAddress=="" || billingAddress=="0"){
				  		$("#billError").text('Please choose Billing Address');
				  		$("#shipError").text('Please choose Shipping Address');
						$("html, body").animate({ scrollTop: 0 }, "slow");
						return false;
				  	}
				  	if(USER=="b2b"){
						var b2b_min_qty = $(this).closest('#checkout-cart-wrapper').find('input[name="b2b_min_qty"]').val();
						var quantity = parseInt(b2b_min_qty) + 1;
					}else{
						var quantity = 1;
					}
					if(USER=="b2b"){
						var b2b_min_qty = $(this).closest('#checkout-cart-wrapper').find('input[name="b2b_min_qty"]').val();
						if(parseInt(quantity) < parseInt(b2b_min_qty)) {
							alert("Select Minimum Quantity");
							return false;
						}
					}
				  	var paymentMode = $('input[name="payment_method"]:checked').val();
				  	//alert(paymentMode);
				  	//alert(shippingAddress+" "+shippingAddress+" "+billingAddress+" "+billingAddress);
				  	if(shippingAddress !="" && shippingAddress !="0" && billingAddress !="" && billingAddress !="0"){
				  		window.location.href = "<?php echo BASE_URL; ?>/process-payment.php?paymentMode="+paymentMode;
				  	}
				  });
			});
		</script>
		<script>
	      	$('input[type=checkbox][name=sameAsBilling]').change(function() {
			    //alert(this.value);
			    if(this.value !=''){
			    	window.location.href = "<?php echo BASE_URL; ?>/chekout-order-summary.php?sameASBilling";
			    }else{
			    	window.location.href = "<?php echo BASE_URL; ?>/chekout-order-summary.php?shippingdestroy";
			    }
			   
			});
      	</script>
		<script>
 			$('.checkout-summery-pop').fancybox({
          		iframe : {
                 	css : {
                     	width : '430px',
                     	height: '450px'
                 	}
          		},
	         	buttons : [
	         
	          		'close'
	          	],
          		afterClose: function () {
            		parent.location.reload(true);
         		}
          	});
		</script>
   </body>
</html>