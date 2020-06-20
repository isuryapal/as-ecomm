<?php

//DELUXORA DB CRED.
/* incinson_deluxus	USER
incinson_deluxora	DB
auBTfWqxccQM		PASS */

	/*
	 * CONFIG
	 * - v1 - 
	 * - v2 - updated BASE CONFIG, error_reporting based on PROJECTSTATUS
	 * - v3 - added staging option
	 * - v3.1 - BUGFIX in staging option
	 */

	/* DEVELOPMENT CONFIG */
		// DEFINE('PROJECTSTATUS','LIVE');
		// DEFINE('PROJECTSTATUS','STAGING');
		DEFINE('PROJECTSTATUS','DEV');
	/* DEVELOPMENT CONFIG */

	/* TIMEZONE CONFIG */
	$timezone = "Asia/Calcutta";
	if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
	/* TIMEZONE CONFIG */

	if(PROJECTSTATUS=="LIVE"){
		error_reporting(0);
		DEFINE('BASE_URL','http://google.com');
		DEFINE('ADMIN_EMAIL','mkkf@gmail.com');
		DEFINE('RAZORPAY_API_KEY', '123'); // LIVE API KEY RAZORPAY

	} else if(PROJECTSTATUS=="STAGING"){
		error_reporting(E_ALL);
		DEFINE('BASE_URL','http://google.com');
		DEFINE('ADMIN_EMAIL','12@23.com');
		

	} else { // DEFAULT TO DEV
		error_reporting(E_ALL);
		DEFINE('BASE_URL','http://localhost/arvind-sanitary');
		DEFINE('ADMIN_EMAIL','surajraohrm@gmail.com');
		DEFINE('RAZORPAY_API_KEY', '123'); // LIVE API KEY RAZORPAY
		

	}

	/* BASE CONFIG */
		DEFINE('SITE_NAME','Arvind Sanitary');
		DEFINE('SITE_NAME_IN_EMAIL','Arvind Sanitary');
		DEFINE('TITLE','Administrator Panel | '.SITE_NAME);
		DEFINE('PREFIX','as_');
		DEFINE('COPYRIGHT',date('Y'));
		DEFINE('currentdate',date('Y-m-d H:i:s'));
		DEFINE('current_date',date('Y-m-d H:i:s'));
		DEFINE('LOGO', BASE_URL.'/images/logo.png');
		DEFINE('FAVICON', BASE_URL.'/images/favicon.png');
		

	/* BASE CONFIG */
?>