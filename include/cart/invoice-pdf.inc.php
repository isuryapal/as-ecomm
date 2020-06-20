<?php
	if(isset($functions)){
		$pdfObj = $functions;
	} else if(isset($admin)){
		$pdfObj = $admin;
	}
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Invoice</title>
    <style>


		 @import url(https://fonts.googleapis.com/css?family=Roboto:300); /*Calling our web font*/

		/* Some resets and issue fixes */
        #outlook a { padding:0; }
		body{ width:100% !important; -webkit-text; size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; }     
        .ReadMsgBody { width: 100%; }
        .ExternalClass {width:100%;} 
        .backgroundTable {margin:0 auto; padding:0; width:100%;!important;} 
        table td {border-collapse: collapse;}
        .ExternalClass * {line-height: 115%;}	
        
        /* End reset */
		
		
		/* These are our tablet/medium screen media queries */
        @media screen and (max-width: 630px){
                
				
			/* Display block allows us to stack elements */                      
            *[class="mobile-column"] {display: block;} 
			
			/* Some more stacking elements */
            *[class="mob-column"] {float: none !important;width: 100% !important;}     
			     
			/* Hide stuff */
            *[class="hide"] {display:none !important;}          
            
			/* This sets elements to 100% width and fixes the height issues too, a god send */
			*[class="100p"] {width:100% !important; height:auto !important;}			        
				
			/* For the 2x2 stack */			
			*[class="condensed"] {padding-bottom:40px !important; display: block;}
			
			/* Centers content on mobile */
			*[class="center"] {text-align:center !important; width:100% !important; height:auto !important;}            
			
			/* 100percent width section with 20px padding */
			*[class="100pad"] {width:100% !important; padding:20px;} 
			
			/* 100percent width section with 20px padding left & right */
			*[class="100padleftright"] {width:100% !important; padding:0 20px 0 20px;} 
			
			/* 100percent width section with 20px padding top & bottom */
			*[class="100padtopbottom"] {width:100% !important; padding:20px 0px 20px 0px;} 
			
		
        }
			
        
    </style>
</head>
<body style="padding:0; margin:0">
	<font face="'Roboto', Arial, sans-serif">
	<table border="0" cellpadding="0" cellspacing="0" style="margin: 0; padding: 0" width="100%">
		<tr>
			<td align="center" valign="top">
					<img src="<?php echo LOGO; ?>" alt="<?php echo SITE_NAME; ?>" border="0" style="display:block" />
				<div style="border-bottom:solid 1px #000;margin-bottom:10px"></div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div style="text-align:center; color:#000;margin-bottom:20px">ORDER <?php if($order['payment_mode']=='COD' && $order['payment_status']=='Payment Pending' ){ echo "CONFIRMATION"; } else { echo "INVOICE"; } ?></div><br/>
				<?php /* <div style="font-size:0.5em">
					<p>Dear <?php echo $customerDetails['first_name'].' '.$customerDetails['last_name']; ?>,</p>
					<p>Please find the invoice for your order numbered <strong><?php echo $order['txn_id']; ?></strong></p>
				</div> */ ?>
			</td>
		</tr>
	</table>

	<table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="#fff" class="100p">
		<tr>
			<td style="font-size:16px; color:#000;border:1px solid #000">
					<div style="padding:10px; width:300px;"><strong>Vendor :</strong><br>
						<?php 
							echo SITE_NAME."<br>";
							//$receiverContactUsInfo = $pdfObj->getContactUsInfo();
							//echo $receiverContactUsInfo['address'];
						?>
					</div>
					<div style="padding:10px; width:300px;"><strong>Pan : </strong><br>
						<span style="text-transform: uppercase;"></span>

					</div>
					<div style="padding:10px; width:300px;"><strong>GST No : </strong><br>
						<span style="text-transform: uppercase;"></span>

					</div>
			</td>
			<td style="font-size:16px; color:#000;border:1px solid #000">
				<div style="padding:10px; width:300px;"><strong>Recipient :</strong></div>
				<div style="padding:10px; width:300px;">
<?php 
					echo $order['billing_fname'].',<br/>'.
					$order['billing_address_line1'].',<br/>';
					if(!empty($order['billing_address_line2'])){
						echo $order['billing_address_line2'].',<br/>';
					}
					echo $order['billing_city'].' - '.
					$order['billing_pincode'].'<br/>'.
					$order['billing_state'].'<br/>';
?>

				</div>
			</td>
		</tr>
	</table>

	<table border="0" cellspacing="0" cellpadding="10" bgcolor="#fff" style="color:#000;font-size:14px;">
		<tr>
			<td style="border:1px solid #000">Invoice No : <?php echo $order['txn_id']; ?></td>
		</tr>
	</table>
	
	<table border="0" cellspacing="0" cellpadding="10" bgcolor="#fff" style="color:#000;font-size:14px;">
		<tr>
			
			<td style="border:1px solid #000">Order No : <?php echo $order['txn_id']; ?></td>
			<td style="border:1px solid #000">Order Date : <?php echo date('d F, Y H:i:s A', strtotime($order['created'])); ?></td>
			<td style="border:1px solid #000">Payment Mode : <?php echo $order['payment_mode']; ?></td>
		</tr>
	</table>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#fff" class="100p">
		<tr>
			<td>

				<div style="margin:10px 0 0 0; float:left;">
					<table border="1" cellspacing="0" cellpadding="0" style="margin: 20px 0 0 0; font-size:12px;vertical-align:middle;">
						<tr>
							<td style="vertical-align:middle;line-height:30px;">&nbsp; Sr. No</td>
							<td style="vertical-align:middle;line-height:30px;">&nbsp; Products</td>
							<td style="vertical-align:middle;line-height:30px;">&nbsp; Price</td>
							<td style="vertical-align:middle;line-height:30px;">&nbsp; Qty</td>
							<td style="vertical-align:middle;line-height:30px;">&nbsp; Amount</td>
						</tr>
							<?php 
								$cntX=1;
								$subTotal = 0;
								$finalTotal = 0;
								$taxTotal = 0;
								$gst_amt='0';
								$Taxorder = 0;
								$gstdata = 0;
								foreach($orderDetails as $oneOrder){
									$productDetails = $pdfObj->getUniqueProductById($oneOrder['product_id']);
									$quantity = $oneOrder['quantity'];

									$unitPrice = $oneOrder['customer_price'];
									$unitDiscountedPrice = $oneOrder['customer_discount_price'];

									if(!empty($unitDiscountedPrice)){
										$totalPrice = $quantity * $unitDiscountedPrice;
									} else {
										$totalPrice = $quantity * $unitPrice;
									}
									if($oneOrder['payment_discount']>0) {
										$paymentDiscountAmount = $totalPrice*($oneOrder['payment_discount']/100);
										$totalPrice = $totalPrice - $paymentDiscountAmount;
									}
									$subTotal += $totalPrice;
									?>
									<tr>
										<td>
											<table cellpadding="5" cellspacing="0" border="0">
												<tr>
													<td><?php echo $cntX++; ?><br/></td>
												</tr>
											</table>
										</td>
										<td>
											<table cellpadding="5" cellspacing="0" border="0">
												<tr>
													<td><?php echo $productDetails['product_name']; ?><br/></td>
												</tr>
											</table>
										</td>
										<td>
											<table cellpadding="5" cellspacing="0" border="0">
												<tr>
													<td>
														<strong>Rs. <?php 
															if(!empty($unitDiscountedPrice)){
																echo $unitDiscountedPrice;
															} else {
																echo $unitPrice;
															} ?>
														</strong>
													</td>
												</tr>
											</table>
										</td>
										
										<td>
											<table cellpadding="5" cellspacing="0" border="0">
												<tr>
													<td><?php echo $quantity; ?></td>
												</tr>
											</table>
										</td>
										<td>
											<table cellpadding="5" cellspacing="0" border="0">
												<tr>
													<td><strong>Rs. <?php echo $totalPrice; ?></strong></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td cellpadding="20" cellspacing="0" colspan="2" style="padding: 10px;">
											<table cellpadding="5" cellspacing="0" border="0">
												<tr>
													<td><strong>Amount in word</strong></td>
												</tr>
											</table>
										</td>
										<td cellpadding="20" cellspacing="0" style="padding: 10px;" colspan="6">
											<table cellpadding="5" cellspacing="0" border="0">
												<tr>
													<td><strong><?php echo ucfirst($pdfObj->getIndianCurrency(round($totalPrice))); ?> only</strong></td>
												</tr>
											</table>
										</td>
									</tr>
									<?php
								}
							?>
					</table>

					<div style="float:right; margin:20px 0 0 0; text-align:right;color:#2a8e9d;font-size:16px;">
						
						<div><strong style="color:#2a1966;">Subtotal: <span style="margin-left:10px;">Rs. <?php echo $subTotal; ?></span></strong></div>
							
							<?php

								// CHECK IF DISCOUNT COUPON IS USED
									if(isset($functions)){ // file opened by user
										$couponDiscountAmount = $pdfObj->getRedeemedCouponAmount($loggedInUserDetailsArr['id'], $order['id']);
									} else if(isset($admin)){ // file opened by admin
										$couponDiscountAmount = $pdfObj->getRedeemedCouponAmount($order['customer_id'], $order['id']);
									}
									if(!empty($couponDiscountAmount)){
										$finalTotal = $subTotal - $couponDiscountAmount;
										?>
										<div>
											<strong style="color:#2a1966;">
												Coupon Discount: <span style="margin-left:10px;" >Rs. <?php echo $couponDiscountAmount; ?></span>
											</strong>
										</div>
										<?php				
									} else {
										$finalTotal = $subTotal;
									} ?>
									
									<?php 
								// CHECK IF DISCOUNT COUPON IS USED
								
								// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
									if(!empty($order['shipping_charges'])){
										$finalTotal += $order['shipping_charges'];
										?>
										<div><strong style="color:#2a1966;">Shipping Charges: <span style="margin-left:10px;">Rs. <?php echo $order['shipping_charges']; ?></span></strong></div>
										<?php				
									}  
								// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
							?>
					
						<div>
							<strong style="color:#2a1966;">
								Final Total: <span style="margin-left:10px;">Rs. <?php echo $finalTotal; ?>.</span>
							</strong>
						</div>
					</div>
				</div>
				<div style=" margin-top:30px; font-size:20px; text-align:center; padding:5px; color:#000; font-weight: bold;">
				This is the computer generated invoice signature not required.
				</div>
				<div style="border-top:1px solid #ddd; margin-top:100px; font-size:15px; text-align:center; padding:20px; color:#848484">
					Contact customer care at info@arvindsanitary.com or call 022-28595033 in case of any query
				</div>

			</td>
		</tr>
	</table>

	</font>
</body>
</html>

<?php 
	$invoiceMsg = ob_get_contents();
	ob_end_clean();
?>