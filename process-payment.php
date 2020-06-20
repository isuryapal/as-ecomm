<?php
	include_once 'include/functions.php';
	$functions = new Functions();
	if(!$loggedInUserDetailsArr = $functions->sessionExists()){
		header("location: ".BASE_URL);
		exit;
	}
	if(isset($_GET['paymentMode']) && !empty($_GET['paymentMode'])) {
		include_once('include/classes/Cart.class.php');
		if(!isset($cartObj)){
			$cartObj = new Cart();
		}
		if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']) && !empty($_SESSION[SITE_NAME]['BILLADDRESS']['shipping'])){
			$getAddress = $functions->getAddressByAddressid($_SESSION[SITE_NAME]['BILLADDRESS']['shipping'],$loggedInUserDetailsArr);
			if($functions->num_rows($getAddress)>0){
				$addressShipDetails = $functions->fetch($getAddress);
			} else {
				header("location:chekout-order-summary.php");
				exit;
			}
		} else{
			header("location:chekout-order-summary.php");
			exit;
		}
		$orderDetails = $functions->processTransaction($cartObj, $loggedInUserDetailsArr, $_REQUEST);
		$finalGatewatAmt = $orderDetails['cartPriceDetails']['finalTotal'];

		if(isset($_GET['paymentMode']) && $_GET['paymentMode']=="COD"){
			header("location:payment-complete-cod.php?txnId=".$orderDetails['txnId']);
			exit;
		}
		//prin
	}
?>
<!DOCTYPE>
<html>
<head>
	<title>Arvind Sanitary</title>
	<?php include("include/header-link.php");?>
	<style>
		.razorpay-payment-button{
			display: none;
		}
	</style>
</head>
<body class="thankyoubody">
	<!--Top start menu head-->
	<?php include("include/header.php");?>
	<div class="content-body">
		<section class="breadcrumbesection">
			<h1>Processing Payment</h1>
		</section>

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<section class="thankyou text-center">
						<div class="site-header" id="header">
							<h1 class="site-header__title" data-lead-id="site-header-title">Processing Payment!</h1>
						</div>

						<!-- <div class="main-content">
							<p class="main-content__body" data-lead-id="main-content-body"><i class="fa fa-refresh fa-spin"></i> Please wait while we redirect you to payment gateway...</p>
							<form action="<?php echo BASE_URL ?>/payment-complete.php" method="POST" class="paybutton">
								<script
									src="https://checkout.razorpay.com/v1/checkout.js"
									data-key="<?php echo RAZORPAY_API_KEY ?>" 
									data-amount="<?php echo $finalGatewatAmt*100; ?>"
									data-buttontext="Pay with Razorpay"
									data-name="Online dental shopping"
									data-description="Medical Equipments"
									data-image="<?php echo LOGO ?>"
									data-prefill.name="<?php echo $loggedInUserDetailsArr['first_name']; ?>"
									data-prefill.email="<?php echo $loggedInUserDetailsArr['email']; ?>"
									data-prefill.contact="<?php echo $loggedInUserDetailsArr['mobile']; ?>"
									data-modal.ondismiss="<?php echo BASE_URL; ?>/chekout-order-summary.php"
									data-theme.color="#0f974d"
									data-redirect ="true"
								></script>
								<input type="hidden" value="Hidden Element" name="hidden">
								<input type="hidden" value="<?php echo RAZORPAY_API_KEY; ?>" name="payment">
							</form>
						</div> --> 
					</section>
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
		$(document).ready(function() {
			$(".razorpay-payment-button").click();
		});
	</script>
	<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
	<script type="text/javascript">
		var txn_id 			= "<?php echo $orderDetails['txnId']; ?>";			
		

		var options = {
			"key": "<?php echo RAZORPAY_API_KEY; ?>",
			"amount": <?php echo ($finalGatewatAmt * 100) ?>, // 2000 paise = INR 20
			"currency": "INR",
			"name": "Arvind Sanitary",
			"description": "Arvind Sanitary",
			"image": "<?php echo LOGO ?>",
			"handler": function (response){
				window.location.replace("<?php echo BASE_URL; ?>/payment-complete.php?success=true&txnId="+txn_id);
			},
			"modal": {
		        "ondismiss": function(){
		        	window.location.replace("<?php echo BASE_URL; ?>/chekout-order-summary.php");
		        }
		    },
			"prefill": {
				"name": "<?php echo $loggedInUserDetailsArr['first_name']; ?>",
				"email": "<?php echo $loggedInUserDetailsArr['email']; ?>",
				"contact": "<?php echo $loggedInUserDetailsArr['mobile']; ?>"
			},
			"notes": {
				"address": ""
			},
			"theme": {
				"color": "#000000"
			}
		};
		var rzp1 = new Razorpay(options);
		
		rzp1.open();
	</script>
</body>
</html>