<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 6.0.0
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
date_default_timezone_set('Asia/Kolkata');
require_once('fedex-common.php');
echo $tracking_id = "794686746120";

	
	//The WSDL is not included with the sample code.
	//Please include and reference in $path_to_wsdl variable.
	$path_to_wsdl = "TrackService_v14.wsdl";

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
	$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Track Request using PHP ***');
	$request['Version'] = array(
		'ServiceId' => 'trck', 
		'Major' => '14', 
		'Intermediate' => '0', 
		'Minor' => '0'
	);
	$request['SelectionDetails'] = array(
		'PackageIdentifier' => array(
			'Type' => 'TRACKING_NUMBER_OR_DOORTAG',
			'Value' => $tracking_id // Replace with a valid customer reference
		),
		//'ShipDateRangeBegin' => getProperty('begindate'),
		//'ShipDateRangeEnd' => getProperty('enddate'),
		'ShipmentAccountNumber' => getProperty('shipaccount') // Replace with account used for shipment
	);
	try {
		
		if(setEndpoint('changeEndpoint')){
			$newLocation = $client->__setLocation(setEndpoint('endpoint'));
		}
		$response = $client ->track($request);
		//print_r($response);
		if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
			echo '<table border="1">';
			echo '<tr><th>Tracking Details</th><th>&nbsp;</th></tr>';
				trackDetails($response->CompletedTrackDetails->TrackDetails, '');
			echo '</table>';
		}
		 if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
			if($response->HighestSeverity != 'SUCCESS'){
				echo '<table border="1">';
				echo '<tr><th>Track Reply</th><th>&nbsp;</th></tr>';
					trackDetails($response->Notifications, '');
				echo '</table>';
			}else{
				if ($response->CompletedTrackDetails->HighestSeverity != 'SUCCESS'){
					echo '<table border="1">';
					echo '<tr><th>Shipment Level Tracking Details</th><th>&nbsp;</th></tr>';
					trackDetails($response->CompletedTrackDetails, '');
					echo '</table>';
				}else{
					echo '<table border="1">';
					echo '<tr><th>Package Level Tracking Details</th><th>&nbsp;</th></tr>';
					trackDetails($response->CompletedTrackDetails->TrackDetails, '');
					echo '</table>';
				}
			}
			printSuccess($client, $response);
		}else{
			printError($client, $response);
		}  
		
		writeToLog($client);    // Write to log file   
	} catch (SoapFault $exception) {
		printFault($exception, $client);
	}

?>