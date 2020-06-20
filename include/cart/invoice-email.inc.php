<?php
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
<body style="padding:0; margin:0; background:#687079" bgcolor="#687079">
<table border="0" cellpadding="0" cellspacing="0" style="margin: 0; padding: 0" width="100%">
    <tr>
        <td align="center" valign="top">
            <table width="640" border="0" cellspacing="0" cellpadding="0" class="hide">
                <tr>
                    <td height="20"></td>
                </tr>
            </table>
            <table width="640" cellspacing="0" border="0" cellpadding="21" bgcolor="#fff" class="100p" style="border-bottom:solid 1px #ddd;">
                <tr>
                    <td background="#fff" bgcolor="#fff" width="640" valign="top" class="100p">
						<div>
							<table width="640" border="0" cellspacing="0" cellpadding="20" class="100p">
								<tr>
									<td valign="top">
										<table border="0" cellspacing="0" cellpadding="0" width="600" class="100p">
											<tr>
												<td align="center" width="100%" class="100p">
													<a href="<?php echo BASE_URL; ?>" target="_blank" >
														<img  src="<?php echo LOGO; ?>" alt="<?php echo SITE_NAME; ?>" width="200" border="0" style="display:block" /></a>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>
                    </td>
                </tr>
            </table>
            <table width="640" border="0" cellspacing="0" cellpadding="20" bgcolor="#ffffff" class="100p">
                <tr>
                    <td style="font-size:16px; color:#444;">
						<font face="'Roboto', Arial, sans-serif">

						<p>Dear <?php echo $customerDetails['first_name'];
										if(!empty($customerDetails['last_name'])){
											echo ' '.$customerDetails['last_name']; 
										} ?>,</p>
						<p>Please find the invoice for your order numbered <strong><?php echo $order['txn_id']; ?></strong></p>

						<div style="border-top:solid 1px #ddd;">
							<div style="text-align:center; color:#000;"><h2>RETAIL INVOICE</h2></div>

							<div style="width:640px; height:auto; border:1px solid #ddd; margin:10px 0 0 0 ">
								<div style="width:100%;">
									<div style="float:left; padding:10px; width:300px;"><strong>Vendor :</strong></div>
									<div style="float:right; padding:10px; width:300px;"><strong>Recipient :</strong></div>
								</div>
								<div style="clear:both;"></div>
							</div>
							<div style="width:640px; height:auto; border:1px solid #ddd; margin:-1px 0 0 0;">
								<div style="width:100%;">
									<div style="float:left; padding:10px; width:300px;">
										<?php //echo SITE_NAME."<br>";
											// $receiverContactUsInfo = $pdfObj->getContactUsInfo();
											//echo $receiverContactUsInfo['address'];
										?><?php echo SITE_NAME; ?><br>
										<div style="padding:10px; width:300px;"><strong>Pan : </strong><br>
										<span style="text-transform: uppercase;"></span>

									</div>
									<div style="padding:10px; width:300px;"><strong>GST No : </strong><br>
										<span style="text-transform: uppercase;"></span>

									</div>
									</div>
									<div style="float:right; padding:10px; width:300px;">
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
								</div>
								<div style="clear:both;"></div>
							</div>
							
							<div style="width:640px; height:auto; border:1px solid #ddd; margin:10px 0 0 0 ">
								<div style="width:100%; font-size:12px;">
									<div style="display:inline-block; padding:8px; width:30%; border-right:1px solid #ddd;">Order No :</div>
									<div style="display:inline-block; padding:8px; width:30%; border-right:1px solid #ddd;">Invoice No :</div>
									<div style="display:inline-block; padding:8px; width:29%;">Order Date :</div>
									<?php /* <div style="float:left;  margin:10px; width:100px; border-right:1px solid medium #000;">Shipping Date :</div>
									<div style="float:left;  margin:10px; width:100px; border-right:1px solid medium #000;">Shipping Time :</div> */ ?>
								</div>
							</div>
							<div style="width:640px; height:auto; border:1px solid #ddd; margin:-1px 0 0 0 ">
								<div style="width:100%; font-size:12px;">
									<div style="display:inline-block; padding:8px; width:30%; border-right:1px solid #ddd;"><?php echo $order['txn_id']; ?></div>
									<div style="display:inline-block; padding:8px; width:30%; border-right:1px solid #ddd;"><?php echo $order['txn_id']; ?></div>
									<div style="display:inline-block; padding:8px; width:29%;"><?php echo date('d F, Y H:i:s A', strtotime($order['created'])); ?></div>
									<?php /*<div style="float:left; margin:10px; width:100px; border-right:1px solid medium #000;">'.$pd['delivery_date'].'</div>
									<div style="float:left; margin:10px; width:100px; border-right:1px solid medium #000;">'.$pd['delivery_time'].'</div> */ ?>
								</div>
							</div>


							<div style="width:640px; margin:10px 0 0 0; float:left;">
								<table width="640" border="0" cellspacing="0" cellpadding="0" style="margin: 20px 0 0 0; font-size:12px;">
									<tr>
										<td width="500" style="padding:5px;border:solid 1px #ddd;">Products</td>
										<td width="94" style="padding:5px;border:solid 1px #ddd;">Price</td>
										<td width="92" style="padding:5px;border:solid 1px #ddd;">Qty</td>
										<td width="99" style="padding:5px;border:solid 1px #ddd;">Amount</td>
									</tr>
									<?php 							
										$subTotal = 0;
										$finalTotal = 0;
										foreach($orderDetails as $oneOrder){
											$productDetails = $functions->getUniqueProductById($oneOrder['product_id']);
											$quantity = $oneOrder['quantity'];

											//$productSizeDetails = $functions->getProductSizeDetailsBySizeAndProductId($oneOrder['size'], $oneOrder['product_id']);

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
												<td style="padding:5px;border:solid 1px #ddd;">
													<p><?php echo $productDetails['product_name']; ?>
													</p>
												</td>
												
												<td style="text-align:left;padding:5px;border:solid 1px #ddd;">
													<strong>Rs. <?php 
														if(!empty($unitDiscountedPrice)){
															echo $unitDiscountedPrice;
														} else {
															echo $unitPrice;
														} ?>
													</strong>
												</td>
												<td style="text-align:center;padding:5px;border:solid 1px #ddd;"><?php echo $quantity; ?></td>
												<td style="text-align:left;padding:5px;border:solid 1px #ddd;"><strong>Rs. <?php echo $totalPrice; ?></strong></td>
											</tr>
											<tr>
										<?php /* <td cellpadding="20" cellspacing="0" colspan="2" style="padding: 10px;">
											<table cellpadding="5" cellspacing="0" border="0">
												<tr>
													<td><strong>Amount in word</strong></td>
												</tr>
											</table>
										</td>
										<td cellpadding="20" cellspacing="0" style="padding: 10px;" colspan="4">
											<table cellpadding="5" cellspacing="0" border="0">
												<tr>
													<td><strong><?php echo ucfirst($functions->getIndianCurrency(round($totalPrice))); ?> only</strong></td>
												</tr>
											</table>
										</td> */ ?>
									</tr>
											<?php
										}
									?>
								</table>
								<div style="float:right; margin:20px 0 0 0; text-align:right;color:#2a8e9d">
									
									<div>
										<strong style="color:#2a1966;">
											Subtotal: <span style="margin-left:10px;">Rs. <?php echo $subTotal; ?></span>
										</strong>
									</div>
								
									<?php
									// CHECK IF DISCOUNT COUPON IS USED
										$couponDiscountAmount = $functions->getRedeemedCouponAmount($loggedInUserDetailsArr['id'], $order['id']);
										
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
										}

									// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
										if(!empty($order['shipping_charges'])){
											$finalTotal += $order['shipping_charges'];
											?>
											<div>
												<strong style="color:#2a1966;">
													Shipping Charges: <span style="margin-left:10px;">Rs. <?php echo $order['shipping_charges']; ?></span>
												</strong>
											</div>
											<?php
										}
									// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL

									?>
									<div>
										<strong style="color:#2a1966;">
											Final total: <span style="margin-left:10px;">Rs. <?php echo $finalTotal; ?></span>
										</strong>
									</div>
								</div>
							</div>

							<div style="clear:both"></div>
								<div style=" margin-top:30px; font-size:20px; text-align:center; padding:5px; color:#000; font-weight: bold;">
							This is the computer generated invoice signature not required.
							</div>
							<div style="border-top:1px solid #ddd; margin-top:100px; font-size:15px; text-align:center; padding:20px; color:#848484">
								Contact customer care at info@sanitaryarvind.com or call +91 8048006399 in case of any query
							</div>
						</div>

						</font>
                    </td>
                </tr>
            </table>

           
		</td>
    </tr>
</table>
</body>
</html>

<?php 
	$invoiceMsg = ob_get_contents();
	ob_end_clean();
?>