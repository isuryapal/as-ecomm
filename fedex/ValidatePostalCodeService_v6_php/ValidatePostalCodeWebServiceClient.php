<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 12.0.0

require_once('fedex-common.php');

$newline = "<br />";
//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "CountryService_v6.wsdl";

ini_set("soap.wsdl_cache_enabled", "0");
 
$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

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
$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Validate Postal Code Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'cnty', 
	'Major' => '6', 
	'Intermediate' => '0', 
	'Minor' => '0'
);

$request['Address'] = array(
	'PostalCode' => '400068',
	'CountryCode' => 'IN'
);

$request['CarrierCode'] = 'FDXE';


try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	
	$response = $client -> validatePostal($request);
        
    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){  	
    	//printSuccess($client, $response);

		//loop through array that is returned in the reply
		echo "<table>\n";
		printPostalDetails($response -> PostalDetail, "");
		echo "</table>\n";

	}else{
       // printError($client, $response);
		echo "Product Delivery Not Available";
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
   printFault($exception, $client);        
}

function printString($spacer, $key, $value){
	if(is_bool($value)){
		if($value)$value='true';
		else $value='false';
	}
	echo "<pre>";
	print_r($spacer);
	print_r($key);
	print_r($value); 
	if(!empty($value)){
		echo "Product Delivery Available";
	}else{
		echo "Product Delivery Not Available";
	}
	//echo '<tr><td>'.$spacer. $key .'</td><td>'.$value.'</td></tr>';
}

function printPostalDetails($details, $spacer){
	$x=1;
	if($x==1){
		foreach($details as $key => $value){
			if(is_array($value) || is_object($value)){
				$newSpacer = $spacer. '&nbsp;&nbsp;&nbsp;&nbsp;';
				//echo '<tr><td>'. $spacer . $key.'</td><td>&nbsp;</td></tr>';
				printPostalDetails($value, $newSpacer);
			}elseif(empty($value)){
				//printString($spacer, $key, $value);
				echo "Product Delivery Not Available";
			}else{
				printString($spacer, $key, $value);
			}
			$x++;
		}
	}
	
}

?>