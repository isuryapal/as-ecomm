<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 5.0.0
ob_start();
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
require_once('fedex/fedex-common.php');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "fedex/PickupService_V20.wsdl";

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
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Create Pickup Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'disp', 
	'Major' => 20, 
	'Intermediate' => 0, 
	'Minor' => 0
);
$request['OriginDetail'] = array(
	'PickupLocation' => array(
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
		),
	),
   	'PackageLocation' => 'FRONT', // valid values NONE, FRONT, REAR and SIDE
    'BuildingPartCode' => 'BUILDING', // valid values APARTMENT, BUILDING, DEPARTMENT, SUITE, FLOOR and ROOM
    'BuildingPartDescription' => '3B',
    'ReadyTimestamp' => getProperty('pickuptimestamp'), // Replace with your ready date time
    'CompanyCloseTime' => '20:00:00'
);
$request['PackageCount'] = $value;
$request['TotalWeight'] = array(
	'Value' => '1.0', 
	'Units' => 'KG' // valid values LB and KG
); 
$request['CarrierCode'] = 'FDXE'; // valid values FDXE-Express, FDXG-Ground, FDXC-Cargo, FXCC-Custom Critical and FXFR-Freight
//$request['OversizePackageCount'] = '1';
$request['CourierRemarks'] = 'This is a test.  Do not pickup';
$request['CommodityDescription'] = 'Sanitary Item.';
$request['CountryRelationship'] = 'DOMESTIC';



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->createPickup($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
        echo 'Pickup confirmation number is: '.$response -> PickupConfirmationNumber .Newline;
        echo 'Location: '.$response -> Location .Newline;
        printSuccess($client, $response);
    }else{
        printError($client, $response);
			echo "<pre>";print_r($response);echo "=========================<br>";print_r($request);
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
    printFault($exception, $client);
    printSuccess($client, $response);              
}
$content = ob_get_contents();
ob_end_clean();
?>