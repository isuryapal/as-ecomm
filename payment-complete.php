<?php
	include_once 'include/functions.php';
	$functions = new Functions();

	if(!$loggedInUserDetailsArr = $functions->sessionExists()){
		header("location: ".BASE_URL."/login.php");
      	exit;
	}

	/*if(!isset($_POST['razorpay_payment_id'])) {
		header("location: payment-error.php?INVALIDACCESS");
		exit;
	}

	if(RAZORPAY_API_KEY != $_POST['payment']){
		header("location: payment-error.php?INVALIDACCESS");
		exit;
	}*/
	// print_r($_POST);

	if(isset($_GET['txnId']) && !empty($_GET['txnId'])){
		$txnId = $functions->escape_string($functions->strip_all($_GET['txnId']));
		include_once('include/classes/Cart.class.php');
		if(!isset($cartObj)){
			$cartObj = new Cart();
		}
		include_once 'fedex/ShipOnlineWebServiceClient.php';
		echo $content."========";
		include_once 'fedex/CreatePickUpWebServiceClient.php';
		echo $content;
		die();
		$result = $functions->completePurchaseOfProductOrder($loggedInUserDetailsArr, $txnId);
		if($result){
			$purchaseDetails = $functions->getPurchasedProductOrderDetails($loggedInUserDetailsArr['id'], $txnId);
			if($purchaseDetails){
				// prepare data for email
				$order = $purchaseDetails['order'];
				$orderDetails = $purchaseDetails['orderDetails'];

				//$paymentAmount = $orderDetails['cartPriceDetails']['finalTotal'];

				$customerDetails = $functions->getUniqueRegisteredUserById($order['customer_id']);

				// send email
				$emailSubject = "Invoice - ".$order['txn_id']." - Arvind Sanitary";
				include_once("include/cart/invoice-email.inc.php"); // $invoiceMsg
				include_once("include/classes/Email.class.php");
				$emailObj = new Email();
				$emailObj->setEmailBody($invoiceMsg);
				$emailObj->setSubject($emailSubject);
				//$emailObj->setAdminAddress(ADMIN_EMAIL);

				// == TEST ==
					// echo $invoiceMsg;
					// exit;
				// == TEST ==

				$emailObj->setAddress($customerDetails['email']); // send email to registered email
				$emailObj->sendEmail(); // UNCOMMENT

				header("location: ".BASE_URL."/thankyou.php?txnId=".$txnId);
				exit;
			} else {
				// email was not sent, because order with that txn_id for that customer was NOT found
				// payment status was not updated
				header("location: ".BASE_URL."/payment-error.php?error=TRANSACTIONPAYMENTFAILED");
				exit;
			}
		} else {
			header("location: ".BASE_URL."/payment-error.php?error=TRANSACTIONFAILED");
			exit;
		}
	} else {
		header("location: ".BASE_URL."/payment-error.php");
		exit;
	}
?>
<!DOCTYPE>
<html>
<head>
	<title>Arvind Sanitary</title>
	<?php include("include/header-link.php");?>
</head>
<body class="login-body headerback">
	<!--Top start menu head-->
	<?php include("include/header.php");?>

	<!--Main End Code Here-->
	<section class="thankyou text-center">
		<div class="site-header" id="header">
			<h1 class="site-header__title" data-lead-id="site-header-title">THANK YOU!</h1>
		</div>

		<div class="main-content">
			<?php
				if(isset($_GET['error']) && !empty($_GET['error'])){ 
					$errorArr = explode("|", $_GET['error']);
					foreach($errorArr as $oneError){
						switch($oneError){
							case "INVALIDURL":
								$text1 = "<div class=\"alert alert-danger\"><li><i class=\"fa fa-warning\"></i> This link is no longer active</li></ul></div>";
								$text2 = "Please Try Again.";
								break;
							default:
								break;
						}
					}
				} else if(isset($_GET['success'])) {
					//$text1  = 'Thank you for verifying your email address';
					$text1  = 'Thank you for verifying your email address';
					$text2  = "<button class='login-btn1 btn btn-success btn-arrow health-care-btn submit-btn' type='button' onclick='window.location.replace(\" ".BASE_URL."/login.php \")'> <i class='fa fa-user' aria-hidden='true'></i> LET'S GET STARTED!</button>";
				}
			?>
			<div class="container">
				<p>&nbsp;</p>
				<h2 class="text-center"><?php echo $text1; ?></h2>
				<p><center><?php echo $text2; ?></center></p>
				<p>&nbsp;</p>
			</div>
		</div>
	</section>
	<!--footer start menu head-->
	<?php include("include/footer.php");?> 
	<!--footer end menu head-->
	<?php include("include/footer-link.php");?>
</body>
</html>