<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 4.0.0

require_once('fedex-common.php');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "ValidationAvailabilityAndCommitmentService_v13.wsdl";

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
$request['TransactionDetail'] = array('CustomerTransactionId' => 'testing');
$request['Version'] = array(
	'ServiceId' => 'vacs', 
	'Major' => '13',
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['Origin'] = array(
	'PostalCode' => '401208', // Origin details
    'CountryCode' => 'IN'
);
$request['Destination'] = array(
	'PostalCode' => '400066', // Destination details
 	'CountryCode' => 'IN'
 );
$request['ShipDate'] = getProperty('serviceshipdate');
$request['CarrierCode'] = 'FDXE'; // valid codes FDXE-Express, FDXG-Ground, FDXC-Cargo, FXCC-Custom Critical and FXFR-Freight
// $request['Service'] = 'STANDARD_OVERNIGHT'; // valid code STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
$request['Packaging'] = 'YOUR_PACKAGING'; // valid code FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
$request['RequestedShipment'] = array(
	'Shipper' => getProperty('shipper'), 
	'Recipient' => getProperty('recipient'),
	'ShipTimestamp' => getProperty('serviceshipdate'),
	'DropoffType' => 'REGULAR_PICKUP',
	'ServiceType' => 'FEDEX_EXPRESS_SAVER',
	'PackagingType' => 'YOUR_PACKAGING',
	'RequestedPackageLineItems' =>array(
	'SequenceNumber' => '111', 
	'GroupNumber' => '5', 
	'GroupPackageCount' => '10', 
	'Weight' => array(
	'Units' => 'KG', 
	'Value' => '50', 
	), 
	'Dimensions' => array(
	'Length' => '25', 
	'Width' => '50.5', 
	'Height' => '10', 
	'Units' => 'CM'	)
));


try {
		if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->serviceAvailability($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
        echo 'The following service type(s) are available.'. Newline;
        echo '<table border="1">';
        foreach ($response->Options as $optionKey => $option){
        	echo '<tr><td><table>';
        	if(is_string($option)){
				echo '<tr><td>' . $optionKey . '</td><td>' . $option . '</td></tr>';
        	}else{           
				foreach($option as $subKey => $subOption){
					echo '<tr><td>' . $subKey . '</td><td>' . $subOption . '</td></tr>';
				}
        	}
        	echo '</table></td></tr>';
        }
        echo'</table>';
        
    	printSuccess($client, $response);
    }else{
        printError($client, $response);
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
    printFault($exception, $client);
}
?>