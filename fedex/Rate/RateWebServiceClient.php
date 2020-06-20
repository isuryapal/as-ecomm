<?php
ob_start();
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 12.0.0
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$loggedInUserDetailsArr = $functions->sessionExists();
// print_r($loggedInUserDetailsArr);
$cartArr = $cartObj->getCart();
// if($cartArr){
	$subTotal = 0;
	$finalTotal = 0;
	$value = 0;
	// print_r($defaultAddress);
	foreach($cartArr as $oneProduct){
		$cartProductDetail = $functions->getUniqueProductById($oneProduct['productId']);

		$productBanner = $functions->getImageUrl('products',$cartProductDetail['main_image'],'crop','');

		if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2c"){
            if(!empty($cartProductDetail['discount_price'])) {
                $discountedPrice = $cartProductDetail['discount_price'];
                $unitPrice = $discountedPrice;
                $price = $discountedPrice * $oneProduct['quantity'];
                unset($discountedPrice);
            } else {
            	$unitPrice = $cartProductDetail['price'];
                $price = $cartProductDetail['price'] * $oneProduct['quantity'];
            }
        }else if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2b"){
            if(!empty($cartProductDetail['b2b_discount_price'])) {
                $discountedPrice = $cartProductDetail['b2b_discount_price'];
                $unitPrice = $discountedPrice;
                $price = $discountedPrice * $oneProduct['quantity'];
                unset($discountedPrice);
            } else {
            	$unitPrice = $cartProductDetail['price'];
                $price = $cartProductDetail['b2b_price'] * $oneProduct['quantity'];
            }
        }else{
            if(!empty($cartProductDetail['discount_price'])) {
                $discountedPrice = $cartProductDetail['discount_price'];
                $unitPrice = $discountedPrice;
                $price = $discountedPrice * $oneProduct['quantity'];
                unset($discountedPrice);
            } else {
            	$unitPrice = $cartProductDetail['price'];
                $price = $cartProductDetail['price'] * $oneProduct['quantity'];
            }
        }
		$subTotal += $price;
		$value += $oneProduct['quantity'];
	}
	if( isset($loggedInUserDetailsArr) && !empty($loggedInUserDetailsArr) && count($loggedInUserDetailsArr)>0 &&
		isset($_SESSION[SITE_NAME]['couponCode']) && !empty($_SESSION[SITE_NAME]['couponCode'])){ // user is logged in, apply discount
	
		$subTotalArr = $functions->getNewSubtotalAfterCouponCode($subTotal, $cartObj, $loggedInUserDetailsArr);
		$couponDiscount = $subTotalArr['couponDiscount'];
		$finalTotal = $subTotalArr['subTotal'];
	}else{ 
		$finalTotal = $subTotal;
	}
	if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['Billing']) && !empty($_SESSION[SITE_NAME]['BILLADDRESS']['Billing'])){
		$defaultAddress = $functions->getByIdAddress($_SESSION[SITE_NAME]['BILLADDRESS']['Billing']);
	}else{
		$defaultAddress = $functions->getPrimaryAddress($loggedInUserDetailsArr['id']);	
	}
	if($functions->num_rows($defaultAddress)>0){
		$defaultAddress = $functions->fetch($defaultAddress);
		$_SESSION[SITE_NAME]['BILLADDRESS']['Billing'] = $defaultAddress['id'];
		if($defaultAddress['state']="Maharashtra"){
			$stateCode = "MH";
		}elseif($defaultAddress['state']="Andhra Pradesh"){
			$stateCode = "AP";
		}elseif($defaultAddress['state']="Arunachal Pradesh"){
			$stateCode = "AR";
		}elseif($defaultAddress['state']="Assam"){
			$stateCode = "AS";
		}elseif($defaultAddress['state']="Bihar"){
			$stateCode = "BR";
		}elseif($defaultAddress['state']="Chhattisgarh"){
			$stateCode = "CG";
		}elseif($defaultAddress['state']="Goa"){
			$stateCode = "GA";
		}elseif($defaultAddress['state']="Gujarat"){
			$stateCode = "GJ";
		}elseif($defaultAddress['state']="Haryana"){
			$stateCode = "HR";
		}elseif($defaultAddress['state']="Himachal Pradesh"){
			$stateCode = "HP";
		}elseif($defaultAddress['state']="Jammu and Kashmir"){
			$stateCode = "JK";
		}elseif($defaultAddress['state']="Jharkhand"){
			$stateCode = "JH";
		}elseif($defaultAddress['state']="Karnataka"){
			$stateCode = "KA";
		}elseif($defaultAddress['state']="Kerala"){
			$stateCode = "KL";
		}elseif($defaultAddress['state']="Madhya Pradesh"){
			$stateCode = "MP";
		}elseif($defaultAddress['state']="Manipur"){
			$stateCode = "MN";
		}elseif($defaultAddress['state']="Meghalaya"){
			$stateCode = "ML";
		}elseif($defaultAddress['state']="Mizoram"){
			$stateCode = "MZ";
		}elseif($defaultAddress['state']="Nagaland"){
			$stateCode = "NL";
		}elseif($defaultAddress['state']="Orissa"){
			$stateCode = "OR";
		}elseif($defaultAddress['state']="Punjab"){
			$stateCode = "PB";
		}elseif($defaultAddress['state']="Rajasthan"){
			$stateCode = "RJ";
		}elseif($defaultAddress['state']="Sikkim"){
			$stateCode = "SK";
		}elseif($defaultAddress['state']="Tamil Nadu"){
			$stateCode = "TN";
		}elseif($defaultAddress['state']="Tripura"){
			$stateCode = "TR";
		}elseif($defaultAddress['state']="Uttarakhand"){
			$stateCode = "UK";
		}elseif($defaultAddress['state']="Uttar Pradesh"){
			$stateCode = "UP";
		}elseif($defaultAddress['state']="West Bengal"){
			$stateCode = "WB";
		}elseif($defaultAddress['state']="Tamil Nadu"){
			$stateCode = "TN";
		}elseif($defaultAddress['state']="Tripura"){
			$stateCode = "TR";
		}elseif($defaultAddress['state']="Andaman and Nicobar Islands"){
			$stateCode = "AN";
		}elseif($defaultAddress['state']="Chandigarh"){
			$stateCode = "CH";
		}elseif($defaultAddress['state']="Dadra and Nagar Haveli"){
			$stateCode = "DH";
		}elseif($defaultAddress['state']="Daman and Diu"){
			$stateCode = "DD";
		}elseif($defaultAddress['state']="Delhi"){
			$stateCode = "DL";
		}elseif($defaultAddress['state']="Lakshadweep"){
			$stateCode = "LD";
		}elseif($defaultAddress['state']="Pondicherry"){
			$stateCode = "PY";
		}
	}else{
		$stateCode = 'MH';
		$defaultAddress = array();
		$defaultAddress['customer_fname'] = 'Demo';
		$defaultAddress['customer_contact'] = '0000000000';
		$defaultAddress['address1'] = 'Fxxx';
		$defaultAddress['address2'] = 'Fxxx';
		$defaultAddress['city'] = 'Mumbai';
		$defaultAddress['pincode'] = '400066';
	}
	
	require_once('fedex/fedex-common.php');

	$newline = "<br />";
	//The WSDL is not included with the sample code.
	//Please include and reference in $path_to_wsdl variable.
	$path_to_wsdl = "fedex/Rate/RateService_v26.wsdl";

	ini_set("soap.wsdl_cache_enabled", "0");
	 
	$opts = array(
		  'ssl' => array('verify_peer' => false, 'verify_peer_name' => false)
		);
	$client = new SoapClient($path_to_wsdl, array('trace' => 1,'stream_context' => stream_context_create($opts)));  // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

	$request['WebAuthenticationDetail'] = array(
		'ParentCredential' => array(
			'Key' => getProperty('parentkey'),
			'Password' => getProperty('parentpassword')
		),
		'UserCredential' => array(
			'Key' => getProperty('key'), 
			'Password' => getProperty('password')
		)
	); 
	$request['ClientDetail'] = array(
		'AccountNumber' => getProperty('shipaccount'), 
		'MeterNumber' => getProperty('meter')
	);
	$request['TransactionDetail'] = array('CustomerTransactionId' => 'testingRate');
	$request['Version'] = array(
		'ServiceId' => 'crs', 
		'Major' => '26', 
		'Intermediate' => '0', 
		'Minor' => '0'
	);
	$request['ReturnTransitAndCommit'] = true;
	$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
	$request['RequestedShipment']['ShipTimestamp'] = date('c');
	$request['RequestedShipment']['ServiceType'] = 'FEDEX_EXPRESS_SAVER'; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
	$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
	$request['RequestedShipment']['TotalInsuredValue']=array(
		'Ammount'=>$finalTotal,
		'Currency'=>'INR'
	);
	$request['RequestedShipment']['Shipper'] = addShipperRate();
	// if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['Billing']) && !empty($_SESSION[SITE_NAME]['BILLADDRESS']['Billing'])){
	$request['RequestedShipment']['Recipient'] = addRecipientRate($defaultAddress,$stateCode);
	// }
	$request['RequestedShipment']['ShippingChargesPayment'] = addShippingChargesPaymentRate();
	$request['RequestedShipment']['PackageCount'] = $value;
	$request['RequestedShipment']['RequestedPackageLineItems'] = addPackageLineItemRate($value);
	$request['RequestedShipment']['CustomsClearanceDetail'] = addCustomsClearanceDetailRate($finalTotal, $value);


	try {
		if(setEndpoint('changeEndpoint')){
			$newLocation = $client->__setLocation(setEndpoint('endpoint'));
		}
		
		$response = $client -> getRates($request);
	        
	    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){  	
	    	$rateReply = $response -> RateReplyDetails;
	    	// echo '<table border="1">';
	     //    echo '<tr><td>Service Type</td><td>Amount</td><td>Delivery Date</td></tr><tr>';
	    	// $serviceType = '<td>'.$rateReply -> ServiceType . '</td>';
	    	if($rateReply->RatedShipmentDetails && is_array($rateReply->RatedShipmentDetails)){
				$amount = number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",");
			}elseif($rateReply->RatedShipmentDetails && ! is_array($rateReply->RatedShipmentDetails)){
				$amount = number_format($rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",");
			}
	        // if(array_key_exists('DeliveryTimestamp',$rateReply)){
	        // 	$deliveryDate= '<td>' . $rateReply->DeliveryTimestamp . '</td>';
	        // }else if(array_key_exists('TransitTime',$rateReply)){
	        // 	$deliveryDate= '<td>' . $rateReply->TransitTime . '</td>';
	        // }else {
	        // 	$deliveryDate='<td>&nbsp;</td>';
	        // }
	        echo round(str_replace(",","",$amount));
	        // echo '</tr>';
	        // echo '</table>';

	        
	        // printSuccess($client, $response);
	    }else{
	        // printError($client, $response);
	    } 
	    writeToLog($client);    // Write to log file   
	 //    echo "<pre>";
		// print_r($response);
		// echo "----------------------------";
		// print_r($request);
	} catch (SoapFault $exception) {
	   // printFault($exception, $client);        
	}



	function addShipperRate(){
		$shipper = array(
			'Tins' => array(
				'TinType' => 'BUSINESS_NATIONAL',
				'Number' => '27AADCB2230M1ZT'
			),
			'Contact' => array(
				'PersonName' => 'Arvind Ranaut',
				'CompanyName' => 'Arvind Sanitary',
				'PhoneNumber' => '8080080406'
			),
			'Address' => array(
				'StreetLines' => array('AK Compound, Sai Wardha Estate, Nalasopara-E'),
				'City' => 'Thane',
				'StateOrProvinceCode' => 'MH',
				'PostalCode' => '401208',
				'CountryCode' => 'IN'
			)
		);
		return $shipper;
	}
		function addRecipientRate($defaultAddress,$stateCode){
			$recipient = array(
				'Contact' => array(
					'PersonName' => $defaultAddress['customer_fname'],
					'CompanyName' => $defaultAddress['customer_fname'],
					'PhoneNumber' => $defaultAddress['customer_contact']
				),
				'Address' => array(
					'StreetLines' => array($defaultAddress['address1']." ".$defaultAddress['address2']),
					'City' => $defaultAddress['city'],
					'StateOrProvinceCode' => $stateCode,
					'PostalCode' => $defaultAddress['pincode'],
					'CountryCode' => 'IN',
					'Residential' => true
				)
			);
			return $recipient;	                                    
		}
	function addShippingChargesPaymentRate(){
		$shippingChargesPayment = array(
			'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
			'Payor' => array(
				'ResponsibleParty' => array(
					'AccountNumber' => getProperty('billaccount'),
					'CountryCode' => 'IN',
				)
			)
		);
		return $shippingChargesPayment;
	}
	function addCustomsClearanceDetailRate($finalTotal,$value){
		$CustomsClearanceDetail = array(
			'DutiesPayment' => array(
				'PaymentType' => 'SENDER'
			),
			'DocumentContent' => 'NON_DOCUMENTS',
			'CustomsValue' => array(
				'Currency' => 'INR',
				'Amount' => $finalTotal
			),
			'CommercialInvoice' => array(
				'Purpose' => 'SOLD'
			),
			'Commodities' => array(
				'NumberOfPieces' => 1,
				'Description' => 'Sanitary',
				'CountryOfManufacture' => 'IN',
				'Weight' => array(
					'Units' => 'KG',
					'Value' => 1
				),
				'Quantity' => $value,
				'QuantityUnits' => 'CM',
				'UnitPrice' => array(
					'Currency' => 'INR',
					'Amount' => $finalTotal
				),
				'CustomsValue' => array(
					'Currency' => 'INR',
					'Amount' => $finalTotal
				),
			),
		);
		return $CustomsClearanceDetail;
	}
	function addLabelSpecificationRate(){
		$labelSpecification = array(
			'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
			'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
			'LabelStockType' => 'PAPER_7X4.75'
		);
		return $labelSpecification;
	}
	// function addSpecialServices(){
	// 	$specialServices = array(
	// 		'SpecialServiceTypes' => array('COD'),
	// 		'CodDetail' => array(
	// 			'CodCollectionAmount' => array(
	// 				'Currency' => 'INR', 
	// 				'Amount' => 150
	// 			),
	// 			'CollectionType' => 'CASH' // ANY, GUARANTEED_FUNDS
	// 		)
	// 	);
	// 	return $specialServices; 
	// }
	function addPackageLineItemRate($value){
		$packageLineItem = array(
			'SequenceNumber'=>1,
			'GroupPackageCount'=>1,
			'Weight' => array(
				'Value' => 20.0,
				'Units' => 'KG'
			),
			'Dimensions' => array(
				'Length' => 108,
				'Width' => 5,
				'Height' => 5,
				'Units' => 'CM'
			)
		);
		return $packageLineItem;
	}
// }
$content = ob_get_contents();
ob_end_clean();
?>