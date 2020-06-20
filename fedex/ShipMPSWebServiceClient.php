<?php
// if(is_object($response)){
	// Copyright 2009, FedEx Corporation. All rights reserved.
	// Version 12.0.0
	// $res = $response;
	// $response = json_decode(json_encode($response),true);
	if($_GET['id'] && $_GET['idNum'] && $_GET['count'] && $_GET['count']>1){
		$a = $_GET['id'];
		$b = 2;
		$c = $_GET['count'];
		for($k=$b;$k>=$b&&$k<=$c;$k++){
			$loggedInUserDetailsArr = $functions->sessionExists();
			// print_r($loggedInUserDetailsArr);
			$cartArr = $cartObj->getCart();
			// if($cartArr){
				$subTotal = 0;
				$finalTotal = 0;
				$value = 0;
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
				}
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
			require_once('fedex-common.php');

			//The WSDL is not included with the sample code.
			//Please include and reference in $path_to_wsdl variable.
			$path_to_wsdl = "ShipService_v25.wsdl";

			// PDF label files. Change to file-extension .png for creating a PNG label (e.g. shiplabel.png)
			// define('SHIP_LABEL', 'shiplabel.pdf');  
			// define('COD_LABEL', 'codlabel.pdf'); 

			ini_set("soap.wsdl_cache_enabled", "0");

			// $response =json_decode($res, true);
			// $trackid = 'FEDEX';
			// $Tracknum = '794656920190';
			$trackid = $_GET['id'];
			$tracknum = $_GET['idNum'];

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
			$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Intra India Shipping Request using PHP ***');
			$request['Version'] = array(
				'ServiceId' => 'ship', 
				'Major' => '25', 
				'Intermediate' => '0', 
				'Minor' => '0'
			);
			$request['RequestedShipment'] = array(
				'ShipTimestamp' => date('c'),
				'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
				'ServiceType' => 'FEDEX_EXPRESS_SAVER', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_EXPRESS_SAVER
				'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
				'TotalWeight' => array(
					'Units' => 'KG',
					'Value' => 2
				),
				'Shipper' => addShipper4(),
				'Recipient' => addRecipient4($defaultAddress,$stateCode),
				'ShippingChargesPayment' => addShippingChargesPayment4(),
				'SpecialServicesRequested' => addSpecialServices4(), //Used for Intra-India shipping - cannot use with PRIORITY_OVERNIGHT
				'CustomsClearanceDetail' => addCustomClearanceDetail4($finalTotal,$value),                                                                                                      
				'LabelSpecification' => addLabelSpecification4(),
				'MasterTrackingId' => array(
					'TrackingIdType' => $trackid,
					'TrackingNumber' => $tracknum
				),
				'CustomerSpecifiedDetail' => array('MaskedData'=> 'SHIPPER_ACCOUNT_NUMBER'), 
				'PackageCount' => $c,                                       
				'RequestedPackageLineItems' => array(
					'0' => addPackageLineItem14($c,$k)
				)
			);



			try{
				if(setEndpoint('changeEndpoint')){
					$newLocation = $client->__setLocation(setEndpoint('endpoint'));
				}
				
				$response = $client->processShipment($request); // FedEx web service invocation

			    if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR'){
			        printSuccess($client, $response);

			        // Create PNG or PDF labels
			        // Set LabelSpecification.ImageType to 'PNG' for generating a PNG labels
			        $fp = fopen(SHIP_LABEL, 'wb');   
			        fwrite($fp, ($response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image));
			        fclose($fp);
			        echo 'Label <a href="./'.SHIP_LABEL.'">'.SHIP_LABEL.'</a> was generated.';           
			        
			        // $fp = fopen(COD_LABEL, 'wb');   
			        // fwrite($fp, ($response->CompletedShipmentDetail->AssociatedShipments->Label->Parts->Image));
			        // fclose($fp);
			        // echo 'Label <a href="./'.COD_LABEL.'">'.COD_LABEL.'</a> was generated.';   
			    }else{
			        printError($client, $response);
			    }
				writeToLog($client);    // Write to log file
				// header("location:ShipMPSWebServiceClient.php?response=".$response);
			} catch (SoapFault $exception) {
			    printFault($exception, $client);
			}



			function addShipper4(){
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
						'StreetLines' => array('AK Compound, Sai Wardha Estate,'),
						'StreetLines' => array('Nalasopara-E'),
						'City' => 'Thane',
						'StateOrProvinceCode' => 'MH',
						'PostalCode' => '401208',
						'CountryCode' => 'IN'
					)
				);
				return $shipper;
			}
			function addRecipient4($defaultAddress,$stateCode){
				$recipient = array(
					'Contact' => array(
					'PersonName' => $defaultAddress['customer_fname'],
					'CompanyName' => $defaultAddress['customer_fname'],
					'PhoneNumber' => $defaultAddress['customer_contact']
					),
					'Address' => array(
						'StreetLines' => array($defaultAddress['address1']),
						'StreetLines' => array($defaultAddress['address2']),
						'City' => $defaultAddress['city'],
						'StateOrProvinceCode' => $stateCode,
						'PostalCode' => $defaultAddress['pincode'],
						'CountryCode' => 'IN',
						'Residential' => true
					)
				);
				return $recipient;	                                    
			}
			function addShippingChargesPayment4(){
				$shippingChargesPayment = array(
					'PaymentType' => 'SENDER',
			        'Payor' => array(
						'ResponsibleParty' => array(
							'AccountNumber' => getProperty('billaccount'),
							'Contact' => null,
							'Address' => array('CountryCode' => 'IN')
						)
					)
				);
				return $shippingChargesPayment;
			}
			function addLabelSpecification4(){
				$labelSpecification = array(
					'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
					'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
					'LabelStockType' => 'PAPER_7X4.75'
				);
				return $labelSpecification;
			}
			function addSpecialServices4(){
				$specialServices = array(
					'SpecialServiceTypes' => array('COD'),
					'CodDetail' => array(
						'CodCollectionAmount' => array(
							'Currency' => 'INR', 
							'Amount' => 150
						),
						'CollectionType' => 'CASH' // ANY, GUARANTEED_FUNDS
					)
				);
				return $specialServices; 
			}
			function addCustomClearanceDetail4($finalTotal,$value){
				$customerClearanceDetail = array(
					'DutiesPayment' => array(
						'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
						'Payor' => array(
							'ResponsibleParty' => array(
								'AccountNumber' => getProperty('dutyaccount'),
								'Contact' => null,
								'Address' => array(
									'CountryCode' => 'IN'
								)
							)
						)
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
						'NumberOfPieces' => $value,
						'Description' => 'Sanitary',
						'CountryOfManufacture' => 'IN',
						'Weight' => array(
							'Units' => 'KG', 
							'Value' => 1.0
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
						)
					)
				);
				return $customerClearanceDetail;
			}
			function addPackageLineItem14($c,$k){
				$packageLineItem = array(
					'SequenceNumber'=>$k,
					'GroupPackageCount'=>$c,
					'Weight' => array(
						'Value' => 50.0,
						'Units' => 'KG'
					),
					'Dimensions' => array(
						'Length' => 108,
						'Width' => 5,
						'Height' => 5,
						'Units' => 'CM'
					),
					'CustomerReferences' => array(
						'CustomerReferenceType' => 'CUSTOMER_REFERENCE', // valid values CUSTOMER_REFERENCE, INVOICE_NUMBER, P_O_NUMBER and SHIPMENT_INTEGRITY
						'Value' => 'GR4567892'
					)
				);
				return $packageLineItem;
			}
		}
	}
?>