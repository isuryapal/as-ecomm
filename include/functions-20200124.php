<?php
	include_once('as-dashboard/include/database.php');
	include_once 'as-dashboard/include/config.php';
	include_once('as-dashboard/include/SaveImage.class.php');
	include_once('include/classes/Cart.class.php');
	/*
	 * 4Wall Functions
	 * v1 - updated loginSession(), logoutSession(), customerLogin()
	 * v2 - added $groupType option
	 * v3 - checks if user(customer) is verified or not while login
	 * v4 - added support for SaveImage.class.php
	 * v5 - added support for AJAX login
	 * v6 - checks if user is active or not while login
	 * v7 - replaced customerLogin() with userLogin(), 
			updated userLogin() to use ajaxCustomerLogin()
			checks if user(wholesale) is verified or not while login
	 * v8 - added userSocialLogin()
	 */
	class Functions extends Database {
		private $groupType = 'user'; // DEFAULT TO 'user'
		private $userType = 'customer'; // DEFAULT TO 'customer'
		private $availableUserType = array('customer', 'wholesaler'); // list of user types;

		
		/** * Function to get image directory */
		function getImageDir($imageFor){
			switch($imageFor){
				case "banner":
					$fileDir = "images/banner/";
					break;
				case "category":
					$fileDir = "images/category/";
					break;
				case "sub_category":
					$fileDir = "../images/sub_category/";
					break;
				case "subsubcategory":
					$fileDir = "../images/subsubcategory/";
					break;
				case "sub_subsubcategory":
					$fileDir = "../images/sub_subsubcategory/";
					break;
				case "subsub_subsubcategory":
					$fileDir = "../images/subsub_subsubcategory/";
					break;
				case "products":
					$fileDir = "images/products/";
					break;
				case "static_banner":
					$fileDir = "images/static_banner/";
					break;
				case "occasion":
					$fileDir = "images/occasion/";
					break;
				case "testimonials":
					$fileDir = "images/testimonials/";
					break;
				case "MainBasket":
					$fileDir = "images/MainBasket/";
					break;
				case "hamper":
					$fileDir = "images/hamper/";
					break;
				case "web_banner":
					$fileDir = "images/web_banner/";
					break;
				case "slider-banner":
					$fileDir = "images/slider-banner/";
					break;
				case "brand":
					$fileDir = "images/brand/";
					break;
				case "home_cms":
					$fileDir = "images/home_cms/";
					break;
				case "category":
					$fileDir = "images/category/";
					break;
				default:
					return false;
					break;
			}
			return $fileDir;
		}
		function getValidatedPermalink($permalink){ // v2
			$permalink = trim($permalink, '()');
			$replace_keywords = array("-:-", "-:", ":-", " : ", " :", ": ", ":",
				"-@-", "-@", "@-", " @ ", " @", "@ ", "@", 
				"-.-", "-.", ".-", " . ", " .", ". ", ".", 
				"-\\-", "-\\", "\\-", " \\ ", " \\", "\\ ", "\\",
				"-/-", "-/", "/-", " / ", " /", "/ ", "/", 
				"-&-", "-&", "&-", " & ", " &", "& ", "&", 
				"-,-", "-,", ",-", " , ", " ,", ", ", ",", 
				" ", "\r", "\n", 
				"---", "--", " - ", " -", "- ",
				"-#-", "-#", "#-", " # ", " #", "# ", "#",
				"-$-", "-$", "$-", " $ ", " $", "$ ", "$",
				"-%-", "-%", "%-", " % ", " %", "% ", "%",
				"-^-", "-^", "^-", " ^ ", " ^", "^ ", "^",
				"-*-", "-*", "*-", " * ", " *", "* ", "*",
				"-(-", "-(", "(-", " ( ", " (", "( ", "(",
				"-)-", "-)", ")-", " ) ", " )", ") ", ")",
				"-;-", "-;", ";-", " ; ", " ;", "; ", ";",
				"-'-", "-'", "'-", " ' ", " '", "' ", "'",
				'-"-', '-"', '"-', ' " ', ' "', '" ', '"',
				"-?-", "-?", "?-", " ? ", " ?", "? ", "?",
				"-+-", "-+", "+-", " + ", " +", "+ ", "+",
				"-!-", "-!", "!-", " ! ", " !", "! ", "!");
			$escapedPermalink = str_replace($replace_keywords, '-', $permalink); 
			return strtolower($escapedPermalink);
		}
		function moneyFormate($amt){
			return number_format($amt,2);
		}
		/** * Function to get image url */
		function getImageUrl($imageFor, $fileName, $imageSuffix, $dirPrefix = ""){
			$fileDir = $this->getImageDir($imageFor, $dirPrefix);
			//var_dump($fileDir);
			if($fileDir === false){ // custom directory not found, error!
				
				$fileDir = "../images/"; // add / at end
				$defaultImageUrl = $fileDir."default.jpg";
				return BASE_URL."/".$defaultImageUrl;
			} else { // process custom directory
				$defaultImageUrl = $fileDir."default.jpg";
				//var_dump($fileName);
				if(empty($fileName)){
					return BASE_URL."/".$defaultImageUrl;
				} else {
					$image_name = strtolower(pathinfo($fileName, PATHINFO_FILENAME));
					$image_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
					if(!empty($imageSuffix)){
						$imageUrl = $fileDir.$image_name."_".$imageSuffix.".".$image_ext;
					} else {
						$imageUrl = $fileDir.$image_name.".".$image_ext;
					}
					//echo $imageUrl;
					if(file_exists($imageUrl)){
						return BASE_URL."/".$imageUrl;
					} else {
						return BASE_URL."/".$defaultImageUrl;
					}
				}
			}
		}

		/** * Function to delete/unlink image file */
		function unlinkImage($imageFor, $fileName, $imageSuffix, $dirPrefix = ""){
			$fileDir = $this->getImageDir($imageFor, $dirPrefix);
			if($fileDir === false){ // custom directory not found, error!
				return false;
			} else { // process custom directory
				$defaultImageUrl = $fileDir."default.jpg";

				$imagePath = $this->getImageUrl($imageFor, $fileName, $imageSuffix, $dirPrefix);
				if($imagePath != $defaultImageUrl){
					$status = unlink($imagePath);
					return $status;
				} else {
					return false;
				}
			}
		}

		// === LOGIN BEGINS ===
		function loginSession($userId, $userFirstName, $userLastName='', $userType) {
			$_SESSION[SITE_NAME][$this->groupType."UserId"] = $userId;
			$_SESSION[SITE_NAME][$this->groupType."UserFirstName"] = $userFirstName;
			$_SESSION[SITE_NAME][$this->groupType."UserLastName"] = $userLastName;
			$_SESSION[SITE_NAME][$this->groupType."UserGroupType"] = $this->groupType;
			//$_SESSION[SITE_NAME][$this->groupType."UserType"] = $userType;
		}
		function logoutSession() {
			if(isset($_SESSION[SITE_NAME])){
				if(isset($_SESSION[SITE_NAME][$this->groupType."UserId"])){
					unset($_SESSION[SITE_NAME][$this->groupType."UserId"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->groupType."UserFirstName"])){
					unset($_SESSION[SITE_NAME][$this->groupType."UserFirstName"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->groupType."UserLastName"])){
					unset($_SESSION[SITE_NAME][$this->groupType."UserLastName"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->groupType."UserGroupType"])){
					unset($_SESSION[SITE_NAME][$this->groupType."UserGroupType"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->groupType."UserType"])){
					unset($_SESSION[SITE_NAME][$this->groupType."UserType"]);
				}
				
				unset($_SESSION[SITE_NAME]);
				return true;
			} else {
				return false;
			}
		}
		function sessionExists(){
			if($this->isUserLoggedIn()){
				return $loggedInUserDetailsArr = $this->getLoggedInUserDetails();
				// return true; // DEPRECATED
			} else {
				return false;
			}
		}
		function isUserLoggedIn(){

			if(isset($_SESSION[SITE_NAME]) && isset($_SESSION[SITE_NAME][$this->groupType.'UserId'])  && 
				!empty($_SESSION[SITE_NAME][$this->groupType.'UserId']) ){
				return true;
			} else {
				return false;
			}
		}
		function getSystemUserType() {
			return $_SESSION[SITE_NAME][$this->groupType.'UserType'];
		}
		function getLoggedInUserDetails(){
			$loggedInID = $this->escape_string($this->strip_all($_SESSION[SITE_NAME][$this->groupType.'UserId']));
			$loggedInUserDetailsArr = $this->getUniqueUserById($loggedInID);
			return $loggedInUserDetailsArr;
		}
		function getUniqueUserById($id) {
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."customers where id = '".$id."'"));
		}
		function getUserLastLoginDetails($userId) {
			$userId = $this->escape_string($this->strip_all($userId));
			
			$query = "select * from ".PREFIX."user_login_details where user_id='".$userId."' order by created desc limit 1,1";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		function userLoginVC($data, $successURL, $failURL = "login.php?failed"){

			// echo "<pre>";
			// print_r($_REQUEST);
			// exit;

			$email = $this->escape_string($this->strip_all($data['email']));
			$password = $this->escape_string($this->strip_all($data['password']));
			// $user_type = $this->escape_string($this->strip_all($_POST['user_type']));
			$result = $this->query("select * from ".PREFIX."customers where email='".$email."'");
			if($this->num_rows($result) == 1){
				// die();
				$row = $this->fetch($result);
				$passwordVerifyStatus = password_verify($password, $row['password']);
				if($passwordVerifyStatus){

					if($row['active']=='1'){
					//echo "777";
						$this->loginSession($row['id'], $row['first_name'], "", $this->userTypeAvailable[0]);
						//echo '<script type="text/javascript">parent.jQuery.fancybox.close();</script>';
						 header("location: ".$successURL." ");
						 exit;
					} else {

						if($row['is_email_verified']=='0'){
							$this->close_connection();
							header("location: ".BASE_URL."/".$failURL."&email-not-verified");
							exit;
						} else {
							$this->close_connection();
							header("location: ".BASE_URL."/".$failURL."&account-not-active");
							exit;
						}
					}
				} else {
					$this->close_connection();
					header("location: ".BASE_URL."/".$failURL."&wrong-password");
					exit;
				}

			} else {
				$this->close_connection();
				header("location: ".BASE_URL."/".$failURL);
				exit;
			}

		}

		function userLogin($data, $successURL, $failURL = "login.php?failed"){

			// echo "<pre>";
			// print_r($_REQUEST);
			// exit;

			$email = $this->escape_string($this->strip_all($data['email']));
			$password = $this->escape_string($this->strip_all($data['password']));

			$result = $this->query("select * from ".PREFIX."customers where email='".$email."'");
			if($this->num_rows($result) == 1){

				$row = $this->fetch($result);
				$passwordVerifyStatus = password_verify($password, $row['password']);
				if($passwordVerifyStatus){

					if($row['active']=='1'){
					//echo "777";
						$this->loginSession($row['id'], $row['first_name'], "", $this->userTypeAvailable[0]);
						//echo '<script type="text/javascript">parent.jQuery.fancybox.close();</script>';
						 header("location: ".$successURL." ");
						 exit;
					} else {

						if($row['is_email_verified']=='0'){
							$this->close_connection();
							header("location: ".BASE_URL."/".$failURL."&email-not-verified");
							exit;
						} else {
							$this->close_connection();
							header("location: ".BASE_URL."/".$failURL."&account-not-active");
							exit;
						}
					}
				} else {
					$this->close_connection();
					header("location: ".BASE_URL."/".$failURL."&wrong-password");
					exit;
				}

			} else {
				$this->close_connection();
				header("location: ".BASE_URL."/".$failURL);
				exit;
			}

		}

		function userLogin1($data, $successURL, $failURL = "login.php?failed"){
			$email = $this->escape_string($this->strip_all($data['email']));
			$password = $this->escape_string($this->strip_all($data['password']));

			$query = "select * from ".PREFIX."customers where email='".$email."' or mobile = '".$email."'";
			$result = $this->query($query);
			
			date_default_timezone_set('Asia/Kolkata');

			if($this->num_rows($result) == 1) { // only one unique user should be present in the system
				$row = $this->fetch($result);
				$passwordVerifyStatus = password_verify($password, $row['password']);
				if($passwordVerifyStatus/*  && $row['is_account_blocked'] == "0"&& $row['is_email_verified'] == "1"*/){

					if(isset($data['userType']) && !empty($data['userType'])){
						$userType = $this->escape_string($this->strip_all($data['userType']));
						// echo $userType;
						// exit;
						if($userType == $this->userTypeAvailable[0]){ // seller
							$this->loginSession($row['id'], $row['name'], "", $this->userTypeAvailable[0]);

							//check if individual or company option not selected
							if(empty($row['seller_type'])){
								header("location: ".BASE_URL."/social-sign-up.php");
								exit;
							} else {
								$dashboardURL = "my-inventory.php";
							}

						} else if($userType == $this->userTypeAvailable[1]){ // producer
							$this->loginSession($row['id'], $row['name'], "", $this->userTypeAvailable[1]);

							//check if individual or company option not selected
							if(empty($row['seller_type'])){

								$login_date_time	= date("Y-m-d H:i:s");
								$this->query("insert into ".PREFIX."user_login_details(user_id, created) values('".$row['id']."', '".$login_date_time."')");
								$this->close_connection();

								header("location: ".BASE_URL."/social-sign-up.php");
								exit;
							} else {
								$dashboardURL = "producer-dashboard.php";
							}

						} else { // default to seller
							$this->loginSession($row['id'], $row['name'], "", $this->userTypeAvailable[0]);

							//check if individual or company option not selected
							if(empty($row['seller_type'])){

								$login_date_time	= date("Y-m-d H:i:s");
								$this->query("insert into ".PREFIX."user_login_details(user_id, created) values('".$row['id']."', '".$login_date_time."')");
								$this->close_connection();

								header("location: ".BASE_URL."/social-sign-up.php");
								exit;
							} else {
								$dashboardURL = "my-inventory.php";
							}

						}
					} else if($row['user_type'] == 'seller'){ // seller
						$this->loginSession($row['id'], $row['first_name'], "", $this->userTypeAvailable[0]);

						//check if individual or company option not selected
						if(empty($row['seller_type'])){

							$login_date_time	= date("Y-m-d H:i:s");
							$this->query("insert into ".PREFIX."user_login_details(user_id, created) values('".$row['id']."', '".$login_date_time."')");
							$this->close_connection();

							header("location: ".BASE_URL."/social-sign-up.php");
							exit;
						} else {
							$dashboardURL = "my-inventory.php";
						}

					} else if($row['user_type'] == 'producer') { // producer
						$this->loginSession($row['id'], $row['first_name'], "", $this->userTypeAvailable[1]);

						//check if individual or company option not selected
						if(empty($row['seller_type'])){

							$login_date_time	= date("Y-m-d H:i:s");
							$this->query("insert into ".PREFIX."user_login_details(user_id, created) values('".$row['id']."', '".$login_date_time."')");
							$this->close_connection();

							header("location: ".BASE_URL."/social-sign-up.php");
							exit;
						} else {
							$dashboardURL = "producer-dashboard.php";
						}
					}
					
					$login_date_time	= date("Y-m-d H:i:s");
					$this->query("insert into ".PREFIX."user_login_details(user_id, created) values('".$row['id']."', '".$login_date_time."')");
					$this->close_connection();
					
					if(BASE_URL == $successURL){ // home page redirect
						header("location: ".$dashboardURL);
						exit;
					} else { // $successURL must be redirect URL
						header("location: ".$successURL);
						exit;
					}
				/*} else if($row['is_account_blocked'] == "1"){
					$this->close_connection();
					header("location: ".$failURL."&account-blocked");
					exit;*/
				} else {
					$this->close_connection();
					header("location: ".$failURL);
					exit;
				}
			} else {
				$this->close_connection();
				header("location: ".$failURL);
				exit;
			}
		}

		function userSocialLogin($email, $successURL, $failURL, $loginType){
			echo "abc";
			$email = $this->escape_string($this->strip_all($email));
			$loginType = $this->escape_string($this->strip_all($loginType));
			
			$result = $this->query("select * from ".PREFIX."customers where email='".$email."'");

			if($this->num_rows($result) == 1){ // only one unique user should be present in the system
				echo "abc1";
			//	exit;
				$row = $this->fetch($result);

				// if($row['user_type']==$loginType){

				// } else {
					
				// }

				//if($row['is_account_blocked']==0){
					// $this->loginSession($row['id'], $row['first_name'], $row['last_name'], $userType); // DEPRECATED
					$last_name = '';
					if(isset($row['last_name']) && !empty($row['last_name'])){
						$last_name = $row['last_name'];
					}

					$this->loginSession($row['id'], $row['first_name'], $last_name, '');
					// $this->close_connection(); // DO NOT UNCOMMENT IN 4 WALLS
					date_default_timezone_set('Asia/Kolkata');
					$login_date_time	= date("Y-m-d H:i:s");
					//$this->query("insert into ".PREFIX."user_login_details(user_id, created) values('".$row['id']."', '".$login_date_time."')");
					//$this->close_connection();
					
					//check if individual or company option not selected
					
					if(!empty($row['id'])){ 
						header("location: ".$successURL);
						exit;
					} else {
						header("location: ".BASE_URL."/sign-in.php");
						exit;
					}
					// echo '<script>parent.jQuery.fancybox.close();</script>';
					//exit;
					//return 1; // login success
				/*} else {
					$this->close_connection();
					header("location: ".$failURL."&account-not-active");
					exit;
					return -1; // account-not-active
					exit;
				}*/
			} else {
				exit;
				$this->close_connection();
				header("location: ".$failURL);
				exit;
				// return 0; // login failed
				// exit;
			}
		}


		function ajaxCustomerLogin($data) {
			$email = $this->escape_string($this->strip_all($data['email']));
			$password = $this->escape_string($this->strip_all($data['password']));
			$query = "select * from ".PREFIX."customers where email='".$email."' or mobile = '".$email."' ";
			$result = $this->query($query);

			if($this->num_rows($result) == 1) { // only one unique user should be present in the system
				$row = $this->fetch($result);
				$passwordVerifyStatus = password_verify($password, $row['password']);
				if($passwordVerifyStatus && $row['is_account_blocked'] == "0"/* && $row['is_email_verified'] == "1"*/){
					if($row['has_designer_account'] == 0){ // customer
						$this->loginSession($row['id'], $row['name'], "", $this->userTypeAvailable[0]);
					} else { // designer
						$this->loginSession($row['id'], $row['name'], "", $this->userTypeAvailable[1]);
					}
					$this->close_connection();
					return 1;
					exit;
				} else if($row['is_account_blocked'] == "1"){
					$this->close_connection();
					return -1;
					exit;
					// header("location: ".$failURL."&account-blocked");
				} else {
					$this->close_connection();
					return 0;
					exit;
				}
			} else {
				$this->close_connection();
				return 0;
				exit;
			}
		}

		// == CUSTOMER START ==
		function generateCustomerNo($prefix){
			$id = substr(str_shuffle("12345678901234567890"), 0, 8);
			$id = $prefix.'-'.$id;
			$query = "select * from ".PREFIX."customers where customer_no='".$id."'";
			$result = $this->query($query);
			if($result->num_rows>0){ // exists
				return $this->generateCustomerNo($prefix); // get another id
			} else {
				return $id;
			}
		}
		function getUniqueCustomerById($customerId){
			$customerId = $this->escape_string($this->strip_all($customerId));
			$query = "select * from ".PREFIX."customers where id='".$customerId."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function getUniqueCustomerByEmail($customerEmail) {
			$customerEmail = $this->escape_string($this->strip_all($customerEmail));
			$query = "select * from ".PREFIX."customers where email='".$customerEmail."'";
			$sql = $this->query($query);
			if($sql->num_rows>0){
				return $this->fetch($sql);
			}
		}
		function getCustomerVerificationLinkByCustomerEmail($customerEmail) {
			$customerEmail = $this->escape_string($this->strip_all($customerEmail));
			$query = "select * from ".PREFIX."customers where email='".$customerEmail."'";
			return $sql = $this->query($query);
		}
		function SliderBanner() {
			$query = "select * from ".PREFIX."slider_banner where active='Yes'";
			return $this->query($query);
		}
		function isCustomerEmailUnique($email) {
			$email = $this->escape_string($this->strip_all($email));
			$query = "select * from ".PREFIX."customers where email='".$email."'";
			$result = $this->query($query);
			if($result->num_rows>0){ // at lease one email exists 
				return false;
			} else {
				return true;
			}
		}
		function isCustomerEmailUniqueType($email,$user_type) {
			$email = $this->escape_string($this->strip_all($email));
			$query = "select * from ".PREFIX."customers where email='".$email."'";
			$result = $this->query($query);
			if($result->num_rows>0){ // at lease one email exists 
				return false;
			} else {
				return true;
			}
		}
		function isOrderIDValid($product_code) {
			$product_code = $this->escape_string($this->strip_all($product_code));
			$query = "select * from ".PREFIX."products where product_code='".$product_code."'";
			$result = $this->query($query);
			if($result->num_rows>0){ 
				return true;
			} else {
				return false;
			}
		}
		function addCustomer($data, $user_verified = '0'){
			
			$data['doNotSaveAddress'] = "Yes";
			$flag = $data['flag'];
			$first_name = $this->escape_string($this->strip_all($data['first_name']));
			$last_name = $this->escape_string($this->strip_all($data['last_name']));
			$gender = $this->escape_string($this->strip_all($data['gender']));
			//$dob = $this->escape_string($this->strip_all($data['dob']));

			// $state = $this->escape_string($this->strip_all($data['state']));
			// $city = $this->escape_string($this->strip_all($data['city']));
			$contact = $this->escape_string($this->strip_all($data['contact']));

			$email = $this->escape_string($this->strip_all($data['email']));
			$password = $this->escape_string($this->strip_all($data['password']));
			$password = password_hash($password, PASSWORD_DEFAULT);

			if(isset($data['receive_promotional_message']) && !empty($data['receive_promotional_message'])){
				$receive_promotional_message = $this->escape_string($this->strip_all($data['receive_promotional_message']));
			} else {
				$receive_promotional_message = '0';
			}
			if(isset($data['newsletter']) && !empty($data['newsletter'])){
				$receive_newsletter = $this->escape_string($this->strip_all($data['newsletter']));
			}else{
				$receive_newsletter = 0;
			}
			if(empty($receive_newsletter)){
				$receive_newsletter	=0;
			}
			//$active = '0';
			// $user_verified = '0'; // DEPRECATED
			$customer_type = 'customer';
			
			if(isset($data['flag']) && !empty($data['flag']) && $data['flag']=='facebook'){
				$logintype = 'facebook';
				$active=1;
			}elseif(isset($data['flag']) && !empty($data['flag']) && $data['flag']=='Google'){
				$logintype = 'Google';
				$active=1;
			}else{
				$logintype = 'website';
				$active=0;
			}
			
			$verification_link = md5(time().'deluxora'.$email.time());

			$customerNo = $this->generateCustomerNo('C');

			if(!$this->isNewsletterSubscribedByEmail($email) && $receive_newsletter){
				$this->addSubscribeNewsletter(array('email_newsletter' => $email));
			}

			$query = "insert into ".PREFIX."customers(customer_no, customer_type, first_name, last_name, mobile1, email, password, receive_promotional_message, receive_newsletter, active, verification_link, user_verified,login_type,created) values ('".$customerNo."', '".$customer_type."', '".$first_name."', '".$last_name."', '".$contact."', '".$email."', '".$password."', '".$receive_promotional_message."', '$receive_newsletter', '".$active."', '".$verification_link."', '".$user_verified."','".$logintype."','".current_date."')";
			
			$this->query($query);

			$customerId = $this->last_insert_id();
			$this->addCustomerAddress($data, $customerId, 1);

			$responseArr = array();
			$responseArr['id'] = $customerId;
			$responseArr['first_name'] = $first_name;
			$responseArr['last_name'] = $last_name;
			$responseArr['customer_type'] = $customer_type;
			$responseArr['email'] = $email;
			$responseArr['verification_link'] = $verification_link;

			return $responseArr;
		}
		function updateCustomer($data, $customerId, $file){
			
			$customerId = $this->escape_string($this->strip_all($customerId));
			$first_name = $this->escape_string($this->strip_all($data['first_name']));
			$last_name = $this->escape_string($this->strip_all($data['last_name']));
			$gender = $this->escape_string($this->strip_all($data['gender']));
			
			$date1 = strtr($data['dob'], '/', '-');
			$dob = date('Y-m-d',strtotime($date1));
			
			//echo $dobs." ".$data['dob']; exit;
			$contact = $this->escape_string($this->strip_all($data['contact']));
			$contact1 = $this->escape_string($this->strip_all($data['contact1']));
			
			$receive_newsletter = $this->escape_string($this->strip_all($data['newsletter']));
			//echo "asdasd".$receive_newsletter; exit();
            $martial_status = $this->escape_string($this->strip_all($data['martial_status']));
		
			if(isset($data['anniversary_date1']) && !empty($data['anniversary_date1'])){
				$anniversary_date = $this->escape_string($this->strip_all($data['anniversary_date1']));;
			}else{
				$anniversary_date = $this->escape_string($this->strip_all($data['anniversary_date']));;
			}
			/* echo $anniversary_date;
			exit; */ 
			
			$email = $this->escape_string($this->strip_all($data['email']));
			
			$companyname = $this->escape_string($this->strip_all($data['companyname']));
			$gstno = $this->escape_string($this->strip_all($data['gstno']));
			
			
			$customerDetails = $this->getUniqueCustomerById($customerId);
			
			// Rested Mobile Verfication if user Update New Contact
			if($customerDetails['mobile1'] != $contact){
				$updateVerification = "UPDATE ".PREFIX."customers SET `otpNumber`='',`Verify`='' WHERE id='".$customerId."'";
				$this->query($updateVerification);
			}
			
			if(!$this->isNewsletterSubscribedByEmail($customerDetails['email']) && $receive_newsletter){
				$this->addSubscribeNewsletter(array('email_newsletter' => $customerDetails['email']));
			} else if(!$receive_newsletter){
				$this->deleteNewsletterByEmail($customerDetails['email']);
			}
			$SaveImage = new SaveImage();
			$imgDir = 'images/profileImg/';
			//echo $imgDir; exit();
			if(isset($file['image_name']['name']) && !empty($file['image_name']['name'])) {
				$cropData = $this->strip_all($data['cropData1']);
				$file_name = strtolower( pathinfo($file['image_name']['name'], PATHINFO_FILENAME));
				$image_name = $SaveImage->uploadCroppedImageFileFromForm($file['image_name'], 1000, $cropData, $imgDir, $file_name.'-'.time().'-1');
				$sql= "update ".PREFIX."customers set profile_image='".$image_name."' where id='".$customerId."'";
				$this->query($sql);
			}
			$query = "update ".PREFIX."customers set first_name='".$first_name."', last_name='".$last_name."', mobile1='".$contact."', gender='".$gender."', dob='".$dob."', receive_newsletter='".$receive_newsletter."',email='".$email."',martial_status='".$martial_status."',anniversery_date='".$anniversary_date."',companyname='".$companyname."',gstno='".$gstno."' where id='".$customerId."'";
			//echo $query; exit;
			$this->query($query);

			$responseArr = array();
			$responseArr['first_name'] = $first_name;
			$responseArr['last_name'] = $last_name;
			$responseArr['email'] = $customerDetails['email'];

			return $responseArr;
		}
		

		function getAllActiveCategoriesList1() {
			$query = "select * from ".PREFIX."category_master where  active='Yes' order by category_name ASC";
			$sql = $this->query($query);
			return $sql;
		}
		function getActiveSubCategoriesByCategoryId($category_id){
			$category_id = $this->escape_string($this->strip_all($category_id));
			$query = "select * from ".PREFIX."sub_category_master where category_id='$category_id' and active='1' order by sub_category_permalink ASC";
			$sql = $this->query($query);
			return $sql;
		}
		function getActiveTypeBySubCategoryId($sub_category_id,$cateid='') {
			$sub_category_id = $this->escape_string($this->strip_all($sub_category_id));
			$cateid = $this->escape_string($this->strip_all($cateid));
			if(empty($cateid)){
				$cateid ='';
			}else{
				$cateid = ' and category_id='.$cateid;
			}
			$query = "select * from ".PREFIX."sub_category_master where category_id='$sub_category_id' $cateid and active='Yes' order by sub_category_permalink";
			return $this->query($query);
		}
		function getProductbyBrandId($id){
			$id = $this->escape_string($this->strip_all($id));
			$sql = "SELECT * FROM ".PREFIX."product_master WHERE brand_id = '$id'";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function getCategorybyPermlink($permalink){
			$permalink = $this->escape_string($this->strip_all($permalink));
			$sql = "SELECT * FROM ".PREFIX."category_master WHERE `active`='yes' and `permalink`='".$permalink."'";
			//echo $sql;
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function getSuBCatByPermalink($subPpermalink,$catid=''){
			$subPpermalink = $this->escape_string($this->strip_all($subPpermalink));
			$catid = $this->escape_string($this->strip_all($catid));
			$sql = "SELECT * FROM ".PREFIX."sub_category_master WHERE `active`='1' and category_id='".$catid."' and  `sub_category_permalink`='".$subPpermalink."'";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function getSubSubCatByPermalink($subSubPermalink,$subCatid=''){
			if(!empty($subCatid)){
				$subCatid = " and sub_category_id = '".$subCatid."'";
			}
			$subSubPermalink = $this->escape_string($this->strip_all($subSubPermalink));
			$sql = "SELECT * FROM ".PREFIX."subsubCategory WHERE `active`='Yes' and `permalink`='".$subSubPermalink."' $subCatid";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function getSubSubSubCatByPermalink($subSubSubPermalink,$subSubCatid=''){
			if(!empty($subSubCatid)){
				$subSubCatid = " and subsubcate_id = '".$subSubCatid."'";
			}
			$subSubSubPermalink = $this->escape_string($this->strip_all($subSubSubPermalink));
			$sql = "SELECT * FROM ".PREFIX."subsubsubCategory WHERE `active`='1' and `subsubsub_permalink`='".$subSubSubPermalink."' $subSubCatid";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function getSubSubSubSubCatByPermalink($subSubSubSubPermalink,$subSubSubCatid=''){
			if(!empty($subSubSubCatid)){
				$subSubSubCatid = " and subsubsubcate_id = '".$subSubSubCatid."'";
			}
			$subSubSubSubPermalink = $this->escape_string($this->strip_all($subSubSubSubPermalink));
			$sql = "SELECT * FROM ".PREFIX."subsubsubsubCategory WHERE `active`='1' and `subsubsubsub_permalink`='".$subSubSubSubPermalink."' $subSubSubCatid";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function getFilterProductlist($data){
			$brandId = '';
			$catId ='';
			$subCatid ='';
			$subSubCatId ='';
			$sortBy='';
			$whereClasue ='';
			// print_r($data);
			if(isset($data['permalink']) && !empty($data['permalink'])){
				$brandDetails  = $this->getBrandbyPermlink($data['permalink']);
				$brandId   = $brandDetails['id'];
				//$whereClasue .= " and category_id ='".$catId."'";
				$whereClasue .= " and brand_id = '$brandId'";
			}

			if(isset($data['cat_permalink']) && !empty($data['cat_permalink'])){
				$categoryDetails  = $this->getCategorybyPermlink($data['cat_permalink']);
				$catId   = $categoryDetails['id'];
				//$whereClasue .= " and category_id ='".$catId."'";
				$whereClasue .= " and id in (SELECT product_id FROM ".PREFIX."product_category_mapping WHERE `category_id` in($catId))";
			}
			$subCatid =0;
			if(isset($data['sub_category_permalink']) && !empty($data['sub_category_permalink'])){
				$subDetails  = $this->getSuBCatByPermalink($data['sub_category_permalink'], $catId);
				$subCatid   = $subDetails['id'];
				//$whereClasue .= " and sub_cat_id ='".$subCatid."'";
				//$whereClasue .= " and sub_cat_id ='".$subCatid."'";
				$whereClasue .= " and id in (SELECT product_id FROM ".PREFIX."product_subcategory_mapping WHERE `subscategory_id` in($subCatid))";
			}
			if(isset($data['subSub_category_permalink']) && !empty($data['subSub_category_permalink'])){
				$subSubDetails  = $this->getSubSubCatByPermalink($data['subSub_category_permalink'],$subCatid);
				$subSubCatId   = $subSubDetails['id'];
				//$whereClasue .= " and subsub_categor_id ='".$subSubCatId."'";
				$whereClasue .= " and id in (SELECT product_id FROM ".PREFIX."product_subsubcategory_mapping WHERE `subsubcategory_id` in($subSubCatId))";
			}
			if(isset($data['subSubSub_category_permalink']) && !empty($data['subSubSub_category_permalink'])){
				$subSubSubDetails  = $this->getSubSubSubCatByPermalink($data['subSubSub_category_permalink'],$subSubCatId);
				$subSubSubCatId   = $subSubSubDetails['id'];
				//$whereClasue .= " and subsub_categor_id ='".$subSubSubCatId."'";
				$whereClasue .= " and id in (SELECT product_id FROM ".PREFIX."product_subsubsubcategory_mapping WHERE `subsubsubcategory_id` in($subSubSubCatId))";
			}
			if(isset($data['subSubSubSub_category_permalink']) && !empty($data['subSubSubSub_category_permalink'])){
				$subSubSubSubDetails  = $this->getSubSubSubSubCatByPermalink($data['subSubSubSub_category_permalink'],$subSubSubCatId);
				$subSubSubSubCatId   = $subSubSubSubDetails['id'];
				//$whereClasue .= " and subsub_categor_id ='".$subSubSubSubCatId."'";
				$whereClasue .= " and id in (SELECT product_id FROM ".PREFIX."product_subsubsubsubcategory_mapping WHERE `subsubsubsubcategory_id` in($subSubSubSubCatId))";
			}
			
			if(isset($data['attrId']) && !empty($data['attrId'])){
				$attrId = implode(",",$data['attrId']);
				$whereClasue .=" and id in(select product_id from ".PREFIX."product_attributes where attribute_feature_id in(".$attrId."))";
			}

			if(isset($data['sortBy'])){
				$sortBy = $this->escape_string($this->strip_all($data['sortBy']));
			}
			if(isset($data['product_id']) && !empty($data['product_id'])){
				$whereClasue .= " and ( product_name like '%".$data['product_id']."%' or product_code like '%".$data['product_id']."%' or description like '%".$data['product_id']."%') ";
			}
			
			if(isset($sortBy) && !empty($sortBy) && $sortBy=="higher"){
				$orderBy = " order by price DESC";
			}else if(isset($sortBy) && !empty($sortBy) && $sortBy=="lower"){
				$orderBy = " order by price ASC";
				
			}else if(isset($sortBy) && !empty($sortBy) && $sortBy=="popular"){
				$orderBy = " order by id DESC";
				//Max PURCHASED PRODUCT LOGIC
			}else{
				$orderBy = " order by price DESC";	
			}
			$whereClasue .= $orderBy;

			if(isset($data['limit']) && !empty($data['limit'])){
				$limit = $data['limit'];
			}else{
				$limit = 30;
			}
			$sql = "SELECT * FROM ".PREFIX."product_master WHERE `active`='Yes' $whereClasue limit $limit";
			// echo $sql;
			return $this->query($sql);


		}

		function getAttributeCategory($catId =''){
			$whereClause = '';
			if(!empty($catId)){
				$catId = $this->escape_string($this->strip_all($catId));
				$whereClause = " and id in(".$catId.")";
			}
			$sql = "SELECT * FROM ".PREFIX."category_master where active='Yes' $whereClause";
			return $result = $this->query($sql);
		}
		function getAttributByCateId($carID){
			$carID = $this->escape_string($this->strip_all($carID));
			$sql = "SELECT * FROM ".PREFIX."attribute_master WHERE active='1' and `id` in( SELECT attribute_id FROM ".PREFIX."category_attribute_list WHERE `category_id`='".$carID."')";
			return $this->query($sql);
		}
		function getAttributeFeaturebyAttrId($attid){
			$attid = $this->escape_string($this->strip_all($attid));
			$sql ="SELECT * FROM ".PREFIX."attribute_features WHERE `attribute_id`='".$attid."'";
			return $this->query($sql);
		}
		function getFeaturedProduct(){
			$sql = "SELECT * FROM ".PREFIX."product_master WHERE `active`='Yes' and `is_feature`='Yes' order by id DESC LIMIT 20";
			return $this->query($sql);
		}
		function getFeaturedBrandProduct($id){
			$id = $this->escape_string($this->strip_all($id));
			$sql = "SELECT * FROM ".PREFIX."product_master WHERE `brand_id`='$id' and `active`='Yes' and `is_feature`='Yes' order by id DESc LIMIT 20";
			return $this->query($sql);
		}
		function getProductByproductPermalink($productPermalink){
			$productPermalink = $this->escape_string($this->strip_all($productPermalink));
			$sql ="SELECT * FROM ".PREFIX."product_master WHERE `permalink`='".$productPermalink."'";
			//echo $sql; 
			$productData = $this->query($sql);
			return  $this->fetch($productData);
		}

		function getProductDetailPageURL($product_id,$data){
			$product_id=$this->escape_string($this->strip_all($product_id));

			$productDetails = $this->getUniqueProductById($product_id);
			//print_r($productDetails);
			$brandPermaURL = '';
			$catPermaURL ='';
			$subCatPermaURl ='';
			$subSubPermaURL ='';
			$subSubSubPermaURL = '';
			$subSubSubSubPermaURL = '';
			
			// $sqlCatPerma = "SELECT * FROM ".PREFIX."product_master WHERE `id`='".$product_id."'";
			// $brandResult = $this->query($sqlbrandPerma);
			// if($this->num_rows($brandResult)>0){
			// 	$brandDetails = $this->fetch($brandResult);
			// 	$mainBrandDetail = $brandDetails['brand_id'];
			// 	$catPermaURL = $mainBrandDetail['permalink'];
			// 	$catPermaURL = $catPermaURL.'/';
			// }
			if(isset($data['cat_permalink']) && !empty($data['cat_permalink'])){
				$sqlCatPerma = "SELECT * FROM ".PREFIX."product_category_mapping WHERE `product_id`='".$product_id."'";
				$catResult = $this->query($sqlCatPerma);
				if($this->num_rows($catResult)>0){
					$catDetails = $this->fetch($catResult);
					$mainCatDetail = $this->getUniqueCategoryById($catDetails['category_id']);
					$catPermaURL = $mainCatDetail['permalink'];
					$catPermaURL = $catPermaURL.'/';
				}
			}
			
			if(isset($data['sub_category_permalink']) && !empty($data['sub_category_permalink'])){
				$sqlsubCatPerma = "SELECT * FROM ".PREFIX."product_subcategory_mapping WHERE `product_id`='".$product_id."'";
				$subCatResult = $this->query($sqlsubCatPerma);
				if($this->num_rows($subCatResult)>0){
					$subCatDetails = $this->fetch($subCatResult);
					$subCatDetail = $this->getUniqueSubCategoryById($subCatDetails['subscategory_id']);
					$subCatPermaURl = $subCatDetail['sub_category_permalink'].'/';
				}
			}

			if(isset($data['subSub_category_permalink']) && !empty($data['subSub_category_permalink'])){
				$sqlSubSubCatPerma = "SELECT * FROM ".PREFIX."product_subsubcategory_mapping WHERE `product_id`='".$product_id."'";
				$subSubCatResult = $this->query($sqlSubSubCatPerma);
				if($this->num_rows($subSubCatResult)>0){
					$subSubCatDetails = $this->fetch($subSubCatResult);
					$subsubCatDetails = $this->getuniqueSusuCategory($subSubCatDetails['subsubcategory_id']);
					$subSubPermaURL = $subsubCatDetails['permalink'].'/';
				}
			}

			if(isset($data['subSubSub_category_permalink']) && !empty($data['subSubSub_category_permalink'])){
				$sqlSubSubSubCatPerma = "SELECT * FROM ".PREFIX."product_subsubsubcategory_mapping WHERE `product_id`='".$product_id."'";
				$subSubSubCatResult = $this->query($sqlSubSubSubCatPerma);
				if($this->num_rows($subSubSubCatResult)>0){
					$subSubSubCatDetails = $this->fetch($subSubSubCatResult);
					$subsubSubCatIdDetails = $this->getuniqueSuSusuCategory($subSubSubCatDetails['subsubsubcategory_id']);
					$subSubSubPermaURL = $subsubSubCatIdDetails['subsubsub_permalink'].'/';
				}
			}

			if(isset($data['subSubSubSub_category_permalink']) && !empty($data['subSubSubSub_category_permalink'])){
				$sqlSubSubSubSubCatPerma = "SELECT * FROM ".PREFIX."product_subsubsubsubcategory_mapping WHERE `product_id`='".$product_id."'";
				$subSubSubSubCatResult = $this->query($sqlSubSubSubSubCatPerma);
				if($this->num_rows($subSubSubSubCatResult)>0){
					$subSubSubSubCatDetails = $this->fetch($subSubSubSubCatResult);
					$subsubSubSubCatIdDetails = $this->getuniqueSuSuSusuCategory($subSubSubSubCatDetails['subsubsubsubcategory_id']);
					$subSubSubSubPermaURL = $subsubSubSubCatIdDetails['subsubsubsub_permalink'].'/';
				}
			}
			//echo $subCatPermaURl;
			return BASE_URL.'/'.$catPermaURL.$subCatPermaURl.$subSubPermaURL.$subSubSubPermaURL.$subSubSubSubPermaURL.$productDetails['permalink'];
		}
		
		function getUniqueProductById($id) {
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."product_master where id='".$id."' and active='Yes'"));
		}
		function getUniqueCategoryById($id) {
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."category_master where id='".$id."'"));
		}
		function getUniqueSubCategoryById($id) {
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."sub_category_master where id='".$id."'"));
		}
		function getuniqueSusuCategory($id){
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."subsubCategory where id='".$id."'"));	
		}
		function getuniqueSuSusuCategory($id){
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."subsubsubCategory where id='".$id."'"));	
		}
		function getuniqueSuSuSusuCategory($id){
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."subsubsubsubCategory where id='".$id."'"));	
		}

		function getUniqueCategoryProductById($product_id){	
			$product_id = $this->escape_string($this->strip_all($product_id));
			$sqlCatPerma = "SELECT * FROM ".PREFIX."product_category_mapping WHERE `product_id`='".$product_id."'";
			$catResult = $this->query($sqlCatPerma);
			if($this->num_rows($catResult)>0){
				$catDetails = $this->fetch($catResult);
				return $this->getUniqueCategoryById($catDetails['category_id']);
			}
		}
		function getUniqueSubCategoryByProductId($product_id){
			$product_id = $this->escape_string($this->strip_all($product_id));
			$sqlsubCatPerma = "SELECT * FROM ".PREFIX."product_subcategory_mapping WHERE `product_id`='".$product_id."'";
			$subCatResult = $this->query($sqlsubCatPerma);
			if($this->num_rows($subCatResult)>0){
				$subCatDetails = $this->fetch($subCatResult);
				return $this->getUniqueSubCategoryById($subCatDetails['subscategory_id']);
			}
		}
		function getuniqueSusuCategoryByProductId($product_id){
			$product_id = $this->escape_string($this->strip_all($product_id));
			$sqlSubSubCatPerma = "SELECT * FROM ".PREFIX."product_subsubcategory_mapping WHERE `product_id`='".$product_id."'";
			$subSubCatResult = $this->query($sqlSubSubCatPerma);
			if($this->num_rows($subSubCatResult)>0){
				$subSubCatDetails = $this->fetch($subSubCatResult);
				return $this->getuniqueSusuCategory($subSubCatDetails['subsubcategory_id']);
			}
		}
		function getuniqueSuSusuCategoryByProductId($product_id){
			$product_id = $this->escape_string($this->strip_all($product_id));
			$sqlSubSubSubCatPerma = "SELECT * FROM ".PREFIX."product_subsubsubcategory_mapping WHERE `product_id`='".$product_id."'";
			$subSubSubCatResult = $this->query($sqlSubSubSubCatPerma);
			if($this->num_rows($subSubSubCatResult)>0){
				$subSubSubCatDetails = $this->fetch($subSubSubCatResult);
				return $this->getuniqueSuSusuCategory($subSubSubCatDetails['subsubsubcategory_id']);
			}
		}
		function getuniqueSuSuSusuCategoryByProductId($product_id){
			$product_id = $this->escape_string($this->strip_all($product_id));
			$sqlSubSubSubSubCatPerma = "SELECT * FROM ".PREFIX."product_subsubsubsubcategory_mapping WHERE `product_id`='".$product_id."'";
			$subSubSubSubCatResult = $this->query($sqlSubSubSubSubCatPerma);
			if($this->num_rows($subSubSubSubCatResult)>0){
				$subSubSubSubCatDetails = $this->fetch($subSubSubSubCatResult);
				return $this->getuniqueSuSuSusuCategory($subSubSubSubCatDetails['subsubsubsubcategory_id']);
			}
		}

		function addReviews($data, $userId, $productId){
			$customer_id = $this->escape_string($this->strip_all($userId));
			$productId = $this->escape_string($this->strip_all($data['product_id']));
			$name = $this->escape_string($this->strip_all($data['name']));
			$email = $this->escape_string($this->strip_all($data['email']));
			$review = $this->escape_string($this->strip_all($data['review']));
			if(isset($data['rating']) && !empty($data['rating'])){
				$rating = $this->escape_string($this->strip_all($data['rating']));
			}else{
				$rating = "0";
			}

			$active = "No";
			$query = "insert into ".PREFIX."reviews(customer_id, product_id, name, email, review, rating, active) values ('".$customer_id."', '".$productId."', '".$name."', '".$email."', '".$review."', '".$rating."', '".$active."')";
			$this->query($query);
			return $this->last_insert_id();	
		}
		function getProductReviewPercentagebyProductid($productId){

			$productId = $this->escape_string($this->strip_all($productId));
			$sql = "SELECT count(id) as starCount, rating FROM ".PREFIX."reviews WHERE `product_id`='".$productId."' and active='Yes' group by rating";
			/*echo $sql;*/ 
			return $this->query($sql);
		}
		function getRatingByProductId($productId){
			$productId = $this->escape_string($this->strip_all($productId));
			$sql = "SELECT * FROM ".PREFIX."reviews WHERE `product_id`='".$productId."' and active='Yes'";
			return $this->query($sql);
		}
		function getRelatedProduct($productId){
			$productId = $this->escape_string($this->strip_all($productId));
			$sql = "SELECT * FROM ".PREFIX."products_related_products WHERE `product_id`='".$productId."'";
			return $this->query($sql);
		}
		function getproductByid($productId){
			$productId = $this->escape_string($this->strip_all($productId));
			$sql = "SELECT * FROM ".PREFIX."product_master WHERE `id`='".$productId."' and active='Yes'";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function getTotalActiveProductCount(){
			$sql = "SELECT * FROM ".PREFIX."product_master WHERE `active`='Yes'";
			return $this->query($sql);
		}

		function getBrandTotalActiveProductCount($id){
			$id = $this->escape_string($this->strip_all($id));
			$sql = "SELECT * FROM ".PREFIX."product_master WHERE `brand_id`='".$id."' and `active`='Yes'";
			return $this->query($sql);
		}

		function addUser($data){ 
			$first_name 	= $this->escape_string($this->strip_all($data['first_name']));
			$email 	= $this->escape_string($this->strip_all($data['email']));
			$mobile 	= $this->escape_string($this->strip_all($data['mobile']));
			$company_name 	= $this->escape_string($this->strip_all($data['company_name']));
			$gst_no 	= $this->escape_string($this->strip_all($data['gst_no']));
			$user_type 	= $this->escape_string($this->strip_all($data['user_type']));
			//$FriendRefCode 	= $this->escape_string($this->strip_all($data['refCode']));
			
			$password = $this->escape_string($this->strip_all($data['password']));
			$passwordHASH = password_hash($password, PASSWORD_DEFAULT);
			$date = date("Y-m-d H:i:s");
			$emailVerificationToken = md5('DENTALSHOP'.time().$email);
			//$refCode = $this->GenrateReferralCode();
			$sql = "insert into ".PREFIX."customers(first_name, email, password, mobile, verification_link, company_name, gst_no, user_type, created) values('".$first_name."', '".$email."', '".$passwordHASH."', '".$mobile."', '".$emailVerificationToken."','".$company_name."', '".$gst_no."', '".$user_type."', '".$date."')";
			//echo $sql; exit;
			$this->query($sql);
			
			$userId = $this->last_insert_id();
			//$this->checkUserRefCode($FriendRefCode,$userId);
			return array(
				"userId" => $userId,
				"name" => $first_name,
				"email_verification_token" => $emailVerificationToken,
			);
		}
		function setUserEmailAsVerified($verificationToken) {
			$verificationToken = $this->escape_string($this->strip_all($verificationToken));
			$query = "update ".PREFIX."customers set is_email_verified='1', active='1', user_verified='1' where verification_link='".$verificationToken."'";
			$this->query($query);
			return $this->affected_rows();
		}
		function getUniqueUserByEmail($email){
			$email = $this->escape_string($this->strip_all($email));
			return $this->fetch($this->query("select * from ".PREFIX."customers where email='".$email."'"));
		}
		function setUserPasswordResetCode($email) {
			$email = $this->escape_string($this->strip_all($email));
			$userDetails = $this->getUniqueUserByEmail($email);

			$passwordResetToken = md5(time()."allaboutthem".$email);

			$query = "update ".PREFIX."customers set password_reset_token='".$passwordResetToken."' where id='".$userDetails['id']."'";
			$this->query($query);

			$response = array();
			$response['updateSuccess'] = $this->affected_rows();
			$response['name'] = $userDetails['first_name'];
			$response['email'] = $userDetails['email'];
			$response['passwordResetToken'] = $passwordResetToken;
			return $response;
		}
		function resetCustomerPassword($passwordResetToken, $newpassword_set) {
			$passwordResetToken = $this->escape_string($this->strip_all($passwordResetToken));
			$newpassword_set = $this->escape_string($this->strip_all($newpassword_set));
			$newPasswordHash = password_hash($newpassword_set, PASSWORD_DEFAULT);

			$customerDetailsRS = $this->query("select * from ".PREFIX."customers where password_reset_token='".$passwordResetToken."'");

			if($customerDetailsRS->num_rows>0){
				$customerDetails = $this->fetch($customerDetailsRS);
				$query = "update ".PREFIX."customers set password='".$newPasswordHash."', password_reset_token='password_was_reset' where id='".$customerDetails['id']."'";
				$this->query($query);
				return $this->affected_rows();
			} else {
				return 0;
			}
		}
		function generate_id($prefix, $randomNo, $tableName, $columnName){
			$chkprofile=$this->query("select ".$columnName." from ".PREFIX.$tableName." where ".$columnName." = '".$prefix.$randomNo."'");
			if($this->num_rows($chkprofile)>0){
				$randomNo = str_shuffle('1234567890123456789012345678901234567890');
				$randomNo = substr($randomNo,0,8);
				$this->generate_id($prefix, $randomNo, $tableName, $columnName);
			}else{
				return  $prefix.$randomNo;
			}
		}
		function getAddressByAddressid($addressID,$logeduser){
			$addressID = $this->escape_string($this->strip_all($addressID));
			$userId = $this->escape_string($this->strip_all($logeduser['id']));
			$sql = "SELECT * FROM ".PREFIX."customers_address WHERE `id`='".$addressID."' and customer_id='".$userId."'";
			return $this->query($sql);
		}
		// ================== order =====================================

		function getCartAmountAndQuantity($cartObj, $loggedInUserDetailsArr){
			$cartArr = $cartObj->getCart();
			if($cartArr){
				$subTotal = 0;
				$finalTotal = 0;

				//print_r($cartArr);

				foreach($cartArr as $oneProduct){
					$cartProductDetail = $this->getUniqueProductById($oneProduct['productId']);
					//$productPrice = $this->getProductPriceByPriceId($oneProduct['price_id']);

					if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2c"){
						$price = $cartProductDetail['price'];
						if(!empty($cartProductDetail['discount_price'])) {
							$price = $cartProductDetail['discount_price'];
						}
					}else if(isset($loggedInUserDetailsArr['user_type']) && !empty($loggedInUserDetailsArr['user_type']) && $loggedInUserDetailsArr['user_type']=="b2b"){
						$price = $cartProductDetail['b2b_price'];
						if(!empty($cartProductDetail['b2b_discount_price'])) {
							$price = $cartProductDetail['b2b_discount_price'];
						}
					}else{
						$price = $cartProductDetail['price'];
						if(!empty($cartProductDetail['discount_price'])) {
							$price = $cartProductDetail['discount_price'];
						}
					}
					$subTotal += ($price * $oneProduct['quantity']);
					
					// if(isset($price)) {
					// 	$subTotal += ($price * $oneProduct['quantity']);
					// 	unset($price); // clear variable for use in loop
					// } else { 
					// }
				}



				// CHECK IF DISCOUNT COUPON IS VALID FOR THIS USER
				if(isset($loggedInUserDetailsArr) && !empty($loggedInUserDetailsArr)){ // user is logged in, apply discount
					$subTotalArr = $this->getNewSubtotalAfterCouponCode($subTotal, $cartObj, $loggedInUserDetailsArr);
					$couponDiscount = $subTotalArr['couponDiscount'];
					$subTotal = $subTotalArr['subTotal'];
				} else {
					$couponDiscount = 0;
				}
				// CHECK IF DISCOUNT COUPON IS VALID FOR THIS USER

				// if(isset($_SESSION[SITE_NAME]['loyaltyCash'])) {
				// 	$loyalty_points = $_SESSION[SITE_NAME]['loyaltyCash'];
				// } else {
				// 	$loyalty_points = 0;
				// }
				// $subTotal = $subTotal-$loyalty_points;
				// if($subTotal<=0) {
				// 	$loyalty_points = $subTotal;
				// 	$subTotal = 0;
				// }

				if(isset($_SESSION[SITE_NAME]['loyaltyCash'])) {
					$loyalty_points = $_SESSION[SITE_NAME]['loyaltyCash'];
				} else {
					$loyalty_points = 0;
				}
				if(isset($_SESSION[SITE_NAME]['giftCard'])) {
					$giftCard = $_SESSION[SITE_NAME]['giftCard'];
				} else {
					$giftCard = 0;
				}
				$subTotal = $subTotal-$loyalty_points;
				if($subTotal<=0) {
					$loyalty_points = $subTotal;
					$subTotal = 0;
				}

				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
				$shippingCharges = $this->getShippingCharge($subTotal);
				$finalTotal = $subTotal + $shippingCharges;
				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
				
				$finalTotal = $finalTotal+$giftCard;
		
				return array(
					"items" => count($cartArr),
					"couponDiscount" => $couponDiscount,
					"subTotalAfterCouponDiscount" => $subTotal,
					"shippingCharges" => $shippingCharges,
					"loyalty_points" => $loyalty_points,
					"giftCard" => $giftCard,
					"finalTotal" => $finalTotal
				);
			} else { 
				return array(
					"items" => 0,
					"couponDiscount" => 0,
					"subTotalAfterCouponDiscount" => 0,
					"shippingCharges" => 0,
					"finalTotal" => 0
					);
			}
		}

		function processTransaction($cartObj, $loggedInUserDetailsArr, $data){ /* generate transaction id  */

			if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']) && !empty($_SESSION[SITE_NAME]['BILLADDRESS']['shipping'])){
				$getAddress = $this->getAddressByAddressid($_SESSION[SITE_NAME]['BILLADDRESS']['shipping'],$loggedInUserDetailsArr);
				if($this->num_rows($getAddress)>0){
					$addressShipDetails = $this->fetch($getAddress);
				}							
			}
			
			$shippingFName = $this->escape_string($this->strip_all($addressShipDetails["customer_fname"]));
			$shippingLName = "";
			$shipping_email = $this->escape_string($this->strip_all($addressShipDetails["customer_email"]));
			$shipping_contact = $this->escape_string($this->strip_all($addressShipDetails["customer_contact"]));
			$shippingState = $this->escape_string($this->strip_all($addressShipDetails["state"]));
			$shippingCity = $this->escape_string($this->strip_all($addressShipDetails["city"]));
			$shippingAddress1 = $this->escape_string($this->strip_all($addressShipDetails["address1"]));
			$shippingAddress2 = $this->escape_string($this->strip_all($addressShipDetails["address2"]));
			$shippingCompany = "";
			$shippingPincode = $this->escape_string($this->strip_all($addressShipDetails["pincode"]));
			
			if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['Billing']) && !empty($_SESSION[SITE_NAME]['BILLADDRESS']['Billing'])){ 
				$getAddress = $this->getAddressByAddressid($_SESSION[SITE_NAME]['BILLADDRESS']['Billing'],$loggedInUserDetailsArr);
				if($this->num_rows($getAddress)>0){
					$addressBillDetails = $this->fetch($getAddress);
				}
			}

			$billFName 	= $this->escape_string($this->strip_all($addressBillDetails["customer_fname"]));
			$billLName 	= "";
			$billState 	= $this->escape_string($this->strip_all($addressBillDetails["state"]));
			$billCity 	= $this->escape_string($this->strip_all($addressBillDetails["city"]));
			$billAddress1 = $this->escape_string($this->strip_all($addressBillDetails["address1"]));
			$billAddress2 = $this->escape_string($this->strip_all($addressBillDetails["address2"]));
			$billCompany = "";
			$billPincode = $this->escape_string($this->strip_all($addressBillDetails["pincode"]));
			
			$customer_id = $this->escape_string($this->strip_all($loggedInUserDetailsArr['id']));
			$payment_mode = $this->escape_string($this->strip_all($data['paymentMode']));

			//$txn_id = "HM-".date("YmdHis", time());
			//permaCode
				$prefix	= 'ODS-';
				$permalink 	= str_shuffle('1234567890');
				$permalink 	= substr($permalink,0,8);
				$txn_id 	= $this->generate_id($prefix, $permalink, 'order', 'txn_id');
			//permaCode::end

			$payment_status = 'Payment Pending';
			$order_status = 'In Process';
			$created = date('Y-m-d H:i:s');

			$query = "insert into ".PREFIX."order(
						txn_id, payment_mode, customer_id, billing_email, 
						billing_fname, billing_lname, billing_address_line1, billing_address_line2, billing_city, billing_pincode, billing_state,
						shipping_fname, shipping_lname, shipping_email, shipping_address_line1, shipping_address_line2, shipping_city, shipping_pincode, shipping_state,
						payment_status, created, order_status) 
					 values (
						'".$txn_id."', '".$payment_mode."', '".$loggedInUserDetailsArr['id']."', '".$loggedInUserDetailsArr['email']."', 
						'".$billFName."', '".$billLName."', '".$billAddress1."', '".$billAddress2."', '".$billCity."', '".$billPincode."', '".$billState."',
						'".$shippingFName."', '".$shippingLName."', '".$shipping_email."', '".$shippingAddress1."', '".$shippingAddress2."', '".$shippingCity."', '".$shippingPincode."', '".$shippingState."',
						'".$payment_status."', '".$created."', '".$order_status."')";
			//echo $query;
			//exit; 

			$this->query($query);

			$orderId = $this->last_insert_id();

			$cartArr = $cartObj->getCart();
			$_SESSION[SITE_NAME]['outOfStock'] = array();
			$processedProduct = 0;
			if($cartArr){
				foreach($cartArr as $oneProduct){
					$cartProductDetail = $this->getUniqueProductById($oneProduct['productId']);
					
					//$productPrice = $this->getProductPriceByPriceId($oneProduct['price_id']);
					//$productSizeDetails = $this->getProductSizeDetailsBySizeAndProductId($oneProduct['productId']);

					$quantity = $this->escape_string($this->strip_all($oneProduct['quantity']));

					if($cartProductDetail['availability'] >= $quantity) {
						$query = "insert into ".PREFIX."order_details(order_id, product_id, customer_id, quantity, customer_price, customer_discount_price, gst_rate, size, vendor_id) values ('".$orderId."', '".$cartProductDetail['id']."', '".$loggedInUserDetailsArr['id']."', '".$quantity."', '".$cartProductDetail['price']."', '".$cartProductDetail['discount_price']."', '".$cartProductDetail['tax']."', '0','".$cartProductDetail['vendor_id']."')";
						$this->query($query);
						$processedProduct = $processedProduct+1;
					} else {
						$productArr = array(
							"productId" => $oneProduct['productId'],
							//"price_id" => $oneProduct['price_id'],
							//"size" => $oneProduct['size'],
						);
						$_SESSION[SITE_NAME]['outOfStock'][] = $productArr;
						//$removeCart = $cartObj->removeProductFromCart($oneProduct['productId'], $oneProduct['price_id']);
						$removeCart = $cartObj->removeProductFromCart($oneProduct['productId']);
					}
				}
				if($processedProduct==0) {
					$this->query("delete from ".PREFIX."order where id='".$orderId."'");
				}
			}
			
			$amtArr = $this->getCartAmountAndQuantity($cartObj, $loggedInUserDetailsArr);
			$shippingCharges = $amtArr['shippingCharges'];
			$loyalty_points = "0";
			$giftCard = "0";
			$payment_status = 'Payment Pending';
			
			if(isset($_SESSION[SITE_NAME]['giftCard'])) {
				$giftCard = $_SESSION[SITE_NAME]['giftCard'];
				$giftCardMessage = $_SESSION[SITE_NAME]['giftCardMessage'];
			} else {
				$giftCard = 0;
				$giftCardMessage = '';
			}

			if($processedProduct>0) {
				$this->query("update ".PREFIX."order set shipping_charges='".$shippingCharges."', loyalty_points='".$loyalty_points."', giftCard='".$giftCard."', giftCardMessage='".$giftCardMessage."' where id='".$orderId."'");
			}

			return array(
						"orderId" => $orderId, 
						"txnId" => $txn_id, 
						"cartPriceDetails" => $amtArr, 
						"status" => "success", 
						"processedProduct" => $processedProduct
					);
		}
		function completePurchaseOfProductOrder($loggedInUserDetailsArr, $txnId){
			$loggedInUserId = $loggedInUserDetailsArr['id'];
			$query = "select * from ".PREFIX."order where txn_id='".$txnId."' and customer_id='".$loggedInUserId."'";
			$orderRS = $this->query($query);
			if($orderRS->num_rows>0){ // order with txn_id for that customer found
				$orderDetails = $this->fetch($orderRS);

				if($orderDetails['payment_mode']=='Online') {
					// update payment status of order
					$query = "update ".PREFIX."order set payment_status='Payment Complete', order_status='In Process' where id='".$orderDetails['id']."'";
					$this->query($query);
				} else if($orderDetails['payment_mode']=='COD') {
					$query = "update ".PREFIX."order set payment_status='Payment Pending', order_status='In Process' where id='".$orderDetails['id']."'";
					$this->query($query);
				}
				
				$loyaltyPointsUsed = $orderDetails['loyalty_points'];
				$customerDetails = $this->getUniqueUserById($orderDetails['customer_id']);
				$points = $customerDetails['wallet_balance'];
				//$newPoints = $points - $loyaltyPointsUsed;
				//if($newPoints<=0) {
					//$newPoints = 0;
				//}
				//$this->query("update ".PREFIX."customers set wallet_balance='".$newPoints."' where id='".$customerDetails['id']."'");

				$cartObj = new Cart();

				// UPDATE PRODUCT AVAILABLE QUANTITY
				$cartArr = $cartObj->getCart();
				$productCancelled = 0;
				$processedProduct = 0;
				$_SESSION[SITE_NAME]['outOfStock'] = array();
				if($cartArr){
					foreach($cartArr as $oneProduct){
						$cartProductDetail = $this->getUniqueProductById($oneProduct['productId']);
						//$productPrice = $this->getProductPriceByPriceId($oneProduct['price_id']);
						//$productSizeDetails = $this->getProductSizeDetailsBySizeAndProductId($oneProduct['productId']);
						$quantity = $this->escape_string($this->strip_all($oneProduct['quantity']));

						if($cartProductDetail['availability'] >= $quantity) {
							//$newQuantity = $cartProductDetail['availability'] - $quantity;
							
							$newQuantity = $cartProductDetail['availability'] - $quantity;

							//$query = "update ".PREFIX."products set availability='".$newQuantity."' where id='".$cartProductDetail['id']."'";
							$query = "update ".PREFIX."product_master set availability='".$newQuantity."' where id='".$cartProductDetail['id']."'";
							$this->query($query);

							$processedProduct = $processedProduct+1;

						} else {

							$previousOrderRS = $this->query("select * from ".PREFIX."order where id IN (select order_id from ".PREFIX."order_details where product_id='".$oneProduct['id']."' and quantity='".$quantity."') and payment_mode='COD' and order_status='In Process' order by created DESC LIMIT 1,1");
							
							if($this->num_rows($previousOrderRS)>0) {

								$prevOrder = $this->fetch($previousOrderRS);

								$prevOrderId = $this->escape_string($prevOrder['id']);
								
								$this->query("delete from ".PREFIX."order_details where product_id='".$oneProduct['id']."' and quantity='".$quantity."' and order_id='".$prevOrderId."'");
								
								$customerDetails = $this->getUniqueRegisteredUserById($prevOrder['customer_id']);

								// EMAIL CODE FOR ORDER CANCELLED
								
								$emailSubject = SITE_NAME." | ORDER CANCELLED - ".$prevOrder['txn_id'];
								
								include_once("include/emailers/order-cancel-email.inc.php"); // $emailMsg
								include_once("include/classes/Email.class.php");
								$emailObj = new Email();
								$emailObj->setEmailBody($emailMsg);
								$emailObj->setSubject($emailSubject);
								//$emailObj->setAdminAddress(ADMIN_EMAIL);

								$emailObj->setAddress($customerDetails['email']); // send email to registered email
								$emailObj->sendEmail(); // UNCOMMENT
								// EMAIL CODE FOR ORDER CANCELLED

								$checkOrderDetails = $this->query("select COUNT(*) from ".PREFIX."order_details where order_id='".$prevOrderId."'");
								if($this->num_rows($checkOrderDetails)==0) {
									$this->query("delete from ".PREFIX."order where id='".$prevOrderId."'");
								}
							} else {
								$productArr = array(
									"productId" => $oneProduct['productId'],
									//"price_id" => $oneProduct['price_id'],
									"size" => $oneProduct['size'],
								);
								$_SESSION[SITE_NAME]['outOfStock'][] = $productArr;
								$productCancelled = $productCancelled+1;
								$removeCart = $cartObj->removeProductFromCart($oneProduct['productId'], $oneProduct['size']);
							}
						}
					}
					if($processedProduct==0) {
						$this->query("delete from ".PREFIX."order where id='".$orderDetails['id']."'");
						header("location: payment-error.php?OUTOFSTOCK&payment");
						exit;
					}
				}
				// UPDATE PRODUCT AVAILABLE QUANTITY

				// CHECK IF COUPON CODE USED AND DISCOUNT COUPON IS VALID FOR THIS USER
				if(isset($_SESSION[SITE_NAME]['couponCode']) && !empty($_SESSION[SITE_NAME]['couponCode'])){

					$curCouponCode='';
					$preCouponCode='';
					
					foreach($_SESSION[SITE_NAME]['couponCode'] as $oneCoupon){
						$couponCode = $this->escape_string($this->strip_all($oneCoupon['couponCode']));

						$curCouponCode=$couponCode;

							$couponVerificationResult = $this->verifyCouponCode($couponCode, $loggedInUserId, $cartObj, $orderDetails['id']);

							if($couponVerificationResult['couponStatus']=="success"){
								$couponDetails = $couponVerificationResult['discountCouponDetails'];
								//$couponDiscountAmount = $couponVerificationResult['couponDiscount'];


								if($curCouponCode!=$preCouponCode || $couponDetails['coupon_type']=='percent'){

									$query = "INSERT INTO `".PREFIX."order_discount_coupons` (`order_id`, `discount_coupon_id`, `customer_id`, `coupon_code`, `coupon_type`, `coupon_value`, `valid_from`, `valid_to`, `coupon_usage`) VALUES ('".$orderDetails['id']."', '".$couponDetails['id']."', '".$loggedInUserId."', '".$couponDetails['coupon_code']."', '".$couponDetails['coupon_type']."', '".$couponDetails['coupon_value']."', '".$couponDetails['valid_from']."', '".$couponDetails['valid_to']."', '".$couponDetails['coupon_usage']."');";
									$this->query($query);


									$preCouponCode=$couponCode;
								}
							}

						// == TEST ==
							// echo "<pre>";
							// print_r($couponVerificationResult);
							// echo "</pre><hr/>";
						// == TEST ==
					}
					// exit; // TEST

					$this->removeAllCouponCodes();
				}
				// CHECK IF COUPON CODE USED AND DISCOUNT COUPON IS VALID FOR THIS USER

				// if(isset($_SESSION[SITE_NAME]['loyaltyCash'])) {
				// 	unset($_SESSION[SITE_NAME]['loyaltyCash']);
				// }
				
				if(isset($_SESSION[SITE_NAME]['loyaltyCash'])) {
					unset($_SESSION[SITE_NAME]['loyaltyCash']);
				}

				if(isset($_SESSION[SITE_NAME]['giftCard'])) {
					unset($_SESSION[SITE_NAME]['giftCard']);
					unset($_SESSION[SITE_NAME]['giftCardMessage']);
				}

				// CLEAR CART SESSION
				$cartObj->clearEntireCart();

				return true;
			} else {
				// ERROR
				return false;
			}
		}
		function getPurchasedProductOrderDetails($loggedInUserId, $txnId){
			$query = "select * from ".PREFIX."order where txn_id='".$txnId."' and customer_id='".$loggedInUserId."'";
			$orderRS = $this->query($query);
			if($orderRS->num_rows>0){ // order with txn_id for that customer found
				$transactionArr = array();
				$orderDetails = $this->fetch($orderRS);

				$transactionArr['order'] = $orderDetails;
				$transactionArr['orderDetails'] = array();

				$query = "select * from ".PREFIX."order_details where order_id='".$orderDetails['id']."' and customer_id='".$loggedInUserId."'";
				$orderDetailsRS = $this->query($query);

				while($row = $this->fetch($orderDetailsRS)){
					$transactionArr['orderDetails'][] = $row;
				}
				return $transactionArr;
			} else {
				// error
				return false;
			}
		}
		function purchaseOfProductOrderFailed($loggedInUserId, $txnId){
			$query = "select * from ".PREFIX."order where txn_id='".$txnId."' and customer_id='".$loggedInUserId."'";
			$orderRS = $this->query($query);
			if($orderRS->num_rows>0){ // order with txn_id for that customer found
				$orderDetails = $this->fetch($orderRS);

				// update payment status of order
				$query = "update ".PREFIX."order set payment_status='Payment Failed' where id='".$orderDetails['id']."'";
				$this->query($query);

				// $cartObj = new Cart();
				// $cartObj->clearEntireCart();

				return true;
			} else {
				// error
				return false;
			}
		}
		function getPurchasedOrdersByCustomerId($customerId){
			$customerId = $this->escape_string($this->strip_all($customerId));
			$query = "select * from ".PREFIX."order where customer_id='".$customerId."' and is_deleted=0 order by id desc";
			return $this->query($query);
		}
		function getCompletedOrdersByCustomerId($customerId){
			$customerId = $this->escape_string($this->strip_all($customerId));
			//$query = "select * from ".PREFIX."order where customer_id='".$customerId."' and is_deleted=0 and payment_status='Payment Complete' order by id desc";
			$query ="SELECT * FROM ".PREFIX."order WHERE `customer_id`='".$customerId."' and (`payment_status`='Payment Complete' or (`payment_status`='Payment Pending' and payment_mode='COD')) order by id DESC";
			return $this->query($query);
		}

		function getProductOrderDetails($loggedInUserId, $txnId){
			$query = "select * from ".PREFIX."order where txn_id='".$txnId."' and customer_id='".$loggedInUserId."'";
			$orderRS = $this->query($query);
			if($orderRS->num_rows>0){ // order with txn_id for that customer found
				$transactionArr = array();
				$orderDetails = $this->fetch($orderRS);

				$transactionArr['order'] = $orderDetails;
				$transactionArr['orderDetails'] = array();

				$query = "select * from ".PREFIX."order_details where order_id='".$orderDetails['id']."' and customer_id='".$loggedInUserId."'";
				$orderDetailsRS = $this->query($query);

				while($row = $this->fetch($orderDetailsRS)){
					$transactionArr['orderDetails'][] = $row;
				}
				return $transactionArr;
			} else {
				// error
				return false;
			}
		}
		
		function getVendorProductOrderDetails($loggedInUserId, $txnId){
			$query = "select * from ".PREFIX."order where txn_id='".$txnId."'";
			$orderRS = $this->query($query);
			if($orderRS->num_rows>0){ // order with txn_id for that customer found
				$transactionArr = array();
				$orderDetails = $this->fetch($orderRS);

				$transactionArr['order'] = $orderDetails;
				$transactionArr['orderDetails'] = array();

				$query = "select * from ".PREFIX."order_details where order_id='".$orderDetails['id']."' and vendor_id='".$loggedInUserId."'";
				
				$orderDetailsRS = $this->query($query);

				while($row = $this->fetch($orderDetailsRS)){
					$transactionArr['orderDetails'][] = $row;
				}
				return $transactionArr;
			} else {
				// error
				return false;
			}
		}

		function getCustomerPurchaseAmount($loggedInUserId, $txn_id){
			$txn_id = $this->escape_string($this->strip_all($txn_id));
			$loggedInUserId = $this->escape_string($this->strip_all($loggedInUserId));
			$purchaseDetails = $this->getProductOrderDetails($loggedInUserId, $txn_id);
			if($purchaseDetails){
				$order = $purchaseDetails['order'];
				$orderDetails = $purchaseDetails['orderDetails'];
				// $customerDetails = $this->getUniqueCustomersById($order['customer_id']);
				$subTotal = 0;
				$finalTotal = 0;
				$gst_amt='0';
				$Taxorder = 0;
				$gstdata = 0;
				foreach($orderDetails as $oneOrder){
					$productDetails = $this->getUniqueProductById($oneOrder['product_id']);
					$quantity = $oneOrder['quantity'];
					$image_name = strtolower(pathinfo($productDetails['main_image'], PATHINFO_FILENAME));
					$image_ext = strtolower(pathinfo($productDetails['main_image'], PATHINFO_EXTENSION));
					$Taxorder = $oneOrder['gst_rate'];
					$imageUrl = BASE_URL."/images/products/".$image_name.'_large.'.$image_ext;
					if(!empty($productDetails['main_image']) && $productDetails['main_image'] != '0'){
						$gst_tax = $productDetails['tax'];
					}
					
					
					$unitPrice = $oneOrder['customer_price'];
					$unitDiscountedPrice = $oneOrder['customer_discount_price'];

					if(!empty($unitDiscountedPrice)){
						$totalPrice = $quantity * $unitDiscountedPrice;
						$totalPriceMsg = 'Rs. '.$unitDiscountedPrice.' x '.$quantity.' unit';
						$displayPrice = $unitDiscountedPrice;
					} else {
						$totalPrice = $quantity * $unitPrice;
						$totalPriceMsg = 'Rs. '.$unitPrice.' x '.$quantity.' unit';
						$displayPrice = $unitPrice;
					}
					$subTotal += $totalPrice;
				}

				// CHECK IF DISCOUNT COUPON IS USED
				$couponDiscountAmount = $this->getRedeemedCouponAmount($order['customer_id'], $order['id']);
				if(!empty($couponDiscountAmount)){
					//echo "DIS".$couponDiscountAmount;
					$finalTotal = $subTotal - $couponDiscountAmount;
				} else {
					$finalTotal = $subTotal;
				}
				//echo $finalTotal;
				//exit;
				// CHECK IF DISCOUNT COUPON IS USED

				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
				if(!empty($order['shipping_charges'])){
					$finalTotal += $order['shipping_charges'];
				}
				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL 

				// INCREMENT CUSTOMER PURCHASE AMOUNT
				return $finalTotal;
			}else{
				return -1;
			}
		}
		function getCustomerPurchaseVendorAmount($vendorId, $txn_id){
			$txn_id = $this->escape_string($this->strip_all($txn_id));
			$vendorId = $this->escape_string($this->strip_all($vendorId));
			$purchaseDetails = $this->getVendorProductOrderDetails($vendorId, $txn_id);
			//print_r($purchaseDetails);
			if($purchaseDetails){
				$order = $purchaseDetails['order'];
				$orderDetails = $purchaseDetails['orderDetails'];
				// $customerDetails = $this->getUniqueCustomersById($order['customer_id']);
				$subTotal = 0;
				$finalTotal = 0;
				$gst_amt='0';
				$Taxorder = 0;
				$gstdata = 0;
				foreach($orderDetails as $oneOrder){
					$productDetails = $this->getUniqueProductById($oneOrder['product_id']);
					$quantity = $oneOrder['quantity'];
					$image_name = strtolower(pathinfo($productDetails['main_image'], PATHINFO_FILENAME));
					$image_ext = strtolower(pathinfo($productDetails['main_image'], PATHINFO_EXTENSION));
					$Taxorder = $oneOrder['gst_rate'];
					$imageUrl = BASE_URL."/images/products/".$image_name.'_large.'.$image_ext;
					if(!empty($productDetails['main_image']) && $productDetails['main_image'] != '0'){
						$gst_tax = $productDetails['tax'];
					}
					
					
					$unitPrice = $oneOrder['customer_price'];
					$unitDiscountedPrice = $oneOrder['customer_discount_price'];

					if(!empty($unitDiscountedPrice)){
						$totalPrice = $quantity * $unitDiscountedPrice;
						$totalPriceMsg = 'Rs. '.$unitDiscountedPrice.' x '.$quantity.' unit';
						$displayPrice = $unitDiscountedPrice;
					} else {
						$totalPrice = $quantity * $unitPrice;
						$totalPriceMsg = 'Rs. '.$unitPrice.' x '.$quantity.' unit';
						$displayPrice = $unitPrice;
					}
					$subTotal += $totalPrice;
				}

				// CHECK IF DISCOUNT COUPON IS USED
				$couponDiscountAmount = $this->getRedeemedVendorsCouponAmount($order['customer_id'], $order['id'], $vendorId);
				if(!empty($couponDiscountAmount)){
					//echo "DIS".$couponDiscountAmount;
					$finalTotal = $subTotal - $couponDiscountAmount;
				} else {
					$finalTotal = $subTotal;
				}
				//echo $finalTotal;
				//exit;
				// CHECK IF DISCOUNT COUPON IS USED

				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
				if(!empty($order['shipping_charges'])){
					$finalTotal += $order['shipping_charges'];
				}
				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL 

				// INCREMENT CUSTOMER PURCHASE AMOUNT
				return $finalTotal;
			}else{
				return -1;
			}
		}
		// ================== order =====================================

		function getShippingCharge($total){
			$result = $this->fetch($this->query("select * from ".PREFIX."shipping_charge"));
			if($total <= $result['free_shipping_above']){
				return $result['shipping_charges'];
			} else {
				return 0;
			}
		}
		/*function removeAllCouponCodes(){
			if(isset($_SESSION[SITE_NAME]['couponCode'])){
				unset($_SESSION[SITE_NAME]['couponCode']);
			}
		}*/
		// ================================ COUPON CODE ====================================
		
		function getCouponDetailsByCouponCode($couponCode){
			$couponCode = $this->escape_string($this->strip_all($couponCode));
			$today = date("Y-m-d");
			$query = "select * from ".PREFIX."discount_coupon_master where coupon_code='".$couponCode."' and active='Yes' and ('$today' >= valid_from and valid_to >= '$today')";
			//echo $query;
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function getApplicableProductIdsForCouponByCouponCode($couponCode){
			$couponCode = $this->escape_string($this->strip_all($couponCode));
			$couponDetails = $this->getCouponDetailsByCouponCode($couponCode);
			if(count($couponDetails)>0){
				$query = "select * from ".PREFIX."products_discount_coupons where coupon_id='".$couponDetails['id']."'";
				return $this->query($query);
			} else {
				return false;
			}
		}
		function isCouponApplicableForCustomer($couponId, $loggedInUserId, $orderId = 0){
			$today = date("Y-m-d");
			$query = "select * from ".PREFIX."discount_coupon_master where id='".$couponId."' and active='Yes' and ('$today' >= valid_from and valid_to >= '$today')";
			$masterCouponRS = $this->query($query);
			if($masterCouponRS->num_rows>0){
				$masterCouponDetails = $this->fetch($masterCouponRS);

				if($masterCouponDetails['coupon_usage']=="multiple"){ // anyone can use coupon
					return true;
				} else { // check if coupon is used at least once
					if(empty($orderId)){ // coupon code is being applied
						$query = "select * from ".PREFIX."order_discount_coupons where discount_coupon_id='".$couponId."' and customer_id='".$loggedInUserId."'";
					} else { // user is at payment gateway, allow single coupon code for same transaction
						$query = "select * from ".PREFIX."order_discount_coupons where discount_coupon_id='".$couponId."' and customer_id='".$loggedInUserId."' and order_id!='".$orderId."'";
					}
					$couponUseRS = $this->query($query);
					if($couponUseRS->num_rows>0){
						return false;
					} else {
						return true;
					}
				}
			} else {
				return false;
			}
		}
		function getNewSubtotalAfterCouponCode($subTotal, $cartObj, $loggedInUserDetailsArr){
			if( isset($loggedInUserDetailsArr) && !empty($loggedInUserDetailsArr) && count($loggedInUserDetailsArr)>0 &&
				isset($_SESSION[SITE_NAME]['couponCode']) && !empty($_SESSION[SITE_NAME]['couponCode'])){	
				$couponDiscount = 0;
				foreach($_SESSION[SITE_NAME]['couponCode'] as $oneCoupon){
					$couponCode = $this->escape_string($this->strip_all($oneCoupon['couponCode']));

					$couponVerificationResult = $this->verifyCouponCode($couponCode, $loggedInUserDetailsArr, $cartObj);
					// print_r($couponVerificationResult); // TEST
					// $couponDiscount += $couponVerificationResult['couponDiscount'];

					if($couponVerificationResult['couponStatus'] == 'success'){
						$couponDiscountValue = $couponVerificationResult['discountCouponDetails']['coupon_value'];
						$couponDiscountType = $couponVerificationResult['discountCouponDetails']['coupon_type'];
					}
				}

				if(!empty($couponDiscountType) && !empty($couponDiscountValue)){
					if($couponDiscountType == 'percent'){
						$couponDiscountAmount = 0;
						foreach($_SESSION[SITE_NAME]['couponCode'] as $oneCoupon){
							$couponCode = $this->escape_string($this->strip_all($oneCoupon['couponCode']));
							$today = date("Y-m-d");

							$query = "select * from ".PREFIX."discount_coupon_master where coupon_code='".$couponCode."' and active='Yes' and ('$today' >= valid_from and valid_to >= '$today')";
							$couponDetailsRS = $this->query($query);
							if($couponDetailsRS->num_rows>0){ // coupon is valid
								$couponDetails = $this->fetch($couponDetailsRS);
								if($couponDetails['minimum_purchase_amount'] <= $subTotal){
									// check if user has used the coupon code, only for single use coupon, not multiple use coupon
									if($couponDetails['coupon_usage']=="single"){
										// if($this->isCouponApplicableForUser($couponDetails['id'], $loggedInUserId)){ // DEPRECATED
										if($this->isCouponApplicableForUser($couponDetails['id'], $loggedInUserDetailsArr['id'], $orderId)){
											// coupon is used in past transaction
											$couponDiscountAmount = $this->getOneCouponCodeAmount($cartObj, $couponDetails, $loggedInUserDetailsArr['id']);
										}
									} else if($couponDetails['coupon_usage']=="multiple"){
										$couponDiscountAmount = $this->getOneCouponCodeAmount($cartObj, $couponDetails, $loggedInUserDetailsArr['id']);
									}
								} else{
									$this->removeAllCouponCodes();
								}
							}

							$couponDiscount += $couponDiscountAmount;
						}
					} else {
						$couponDiscountAmount = 0;
						foreach($_SESSION[SITE_NAME]['couponCode'] as $oneCoupon){
							$couponCode = $this->escape_string($this->strip_all($oneCoupon['couponCode']));
							$today = date("Y-m-d");

							$query = "select * from ".PREFIX."discount_coupon_master where coupon_code='".$couponCode."' and active='Yes' and ('$today' >= valid_from and valid_to >= '$today')";
							$couponDetailsRS = $this->query($query);
							if($couponDetailsRS->num_rows>0){ // coupon is valid
								$couponDetails = $this->fetch($couponDetailsRS);
								if($couponDetails['minimum_purchase_amount'] > $subTotal){
									$this->removeAllCouponCodes();
								}else{
									$couponDiscountAmount = $couponDetails['coupon_value'];
								}
							}
						}
						$couponDiscount = $couponDiscountValue;
					}
				}
			} else {
				$couponDiscount = 0;
			}
			$subTotal = $subTotal - $couponDiscount;

			/* if(($subTotal - $couponDiscount)>0){
				$subTotal = $subTotal - $couponDiscount;
			} else {
				$couponDiscount = 0;
				$this->removeCouponCodesForProductId($productId);
			} */
			return array(
				"subTotal" => $subTotal,
				"couponDiscount" => $couponDiscount
				);
		}
		function getOneCouponCodeAmount($cartObj, $couponDetails){
			$couponDiscountAmount = 0;

			$price = $this->getCartTotal();
			$discountOnThisPrice = ($price);

			$precision = 2;
			if($couponDetails['coupon_type']=="percent"){
				$couponDiscountAmount = round(( $discountOnThisPrice*($couponDetails['coupon_value']/100)  ) , $precision);
			} else if($couponDetails['coupon_type']=="amount"){
				$couponDiscountAmount = round($couponDetails['coupon_value'], $precision);
			}
			return $couponDiscountAmount;
		}

		function verifyCouponCode($couponCode, $loggedInUser, $cartObj, $orderId = 0){
			if(isset($_SESSION[SITE_NAME]['couponCode'])){ // check if session exists
				$couponDiscountAmount = 0;
				// == TEST ==
					// echo "<hr/>";
					// echo $orderId;
					// echo "<hr/>";
				// == TEST ==

				// check if coupon code is within time range and active
				$couponCode = $this->escape_string($this->strip_all($couponCode));
				$today = date("Y-m-d");
				$query = "select * from ".PREFIX."discount_coupon_master where coupon_code='".$couponCode."' and active='Yes' and ('$today' >= valid_from and valid_to >= '$today')";
				$couponDetailsRS = $this->query($query);
				if($couponDetailsRS->num_rows>0){ // coupon is valid
					$couponDetails = $this->fetch($couponDetailsRS);
					// check whether coupon code apply on specific customer type
					$couponCouponDetails = explode(",",$couponDetails['coupon_apply']);
					// print_r($couponCouponDetails);
					if(in_array($loggedInUser['user_type'], $couponCouponDetails)){
						// check if user has used the coupon code, only for single use coupon, not multiple use coupon
						if($couponDetails['coupon_usage']=="single"){
							// if($this->isCouponApplicableForCustomer($couponDetails['id'], $loggedInUserId)){ // DEPRECATED
							if($this->isCouponApplicableForCustomer($couponDetails['id'], $loggedInUser['id'], $orderId)){
								// coupon is used in past transaction
								// $couponDiscountAmount = $this->getOneCouponCodeAmount($productId, $cartObj, $couponDetails);
							} else {
								$this->removeAllCouponCodes($couponCode);
								return array(
									"couponStatus" => "coupon_removed"/*,
									"couponDiscount" => 0*/
								);
							}
						} else if($couponDetails['coupon_usage']=="multiple"){
							// $couponDiscountAmount = $this->getOneCouponCodeAmount($productId, $cartObj, $couponDetails);
						}
					}

					return array(
						"couponStatus" => "success",
						"discountCouponDetails" => $couponDetails/*,
						"couponDiscount" => floatval($couponDiscountAmount)*/
					);

				} else { // coupon invalid
					return array(
						"couponStatus" => "invalid_coupon"/*,
						"couponDiscount" => 0*/
					);
				}

			} else { // no coupon code applied
				return array(
					"couponStatus" => "no_coupon_entered"/*,
					"couponDiscount" => 0*/
				);
			}
		}
		function applyCouponCode($couponCode, $loggedInUserDetailsArr){

			$errorArr = array();
			if(isset($couponCode) && !empty($couponCode)){
				$couponCode = strip_tags($couponCode);
			} else {
				$errorArr[] = "ENTERCOUPONCODE";
			}

			$statusMessage = '';
			if(count($errorArr)>0){
				$errorStr = implode("|", $errorArr);
				return array(
						"response" => true,
						"responseMsg" => "Please enter coupon code",
						"couponCodeMsg" => "Please enter a coupon code",
						"error" => $errorStr
						);
			} else {
				// get coupon details
				$couponDetails = $this->getCouponDetailsByCouponCode($couponCode);
				if(count($couponDetails)>0){
					/*$productIdsRS = $this->getApplicableProductIdsForCouponByCouponCode($couponCode);
					if($productIdsRS===false){
						return array(
							"response" => true,
							"responseMsg" => "Please enter a valid coupon1",
							"couponCodeMsg" => "Please enter a valid coupon1",
							"error" => "INVALIDCOUPON"
						);
					}*/

					$couponDetails = $this->getCouponDetailsByCouponCode($couponCode);

					$isCouponApplicable = false;
					// $isCouponApplicable = $this->isCouponApplicableForCustomer($couponDetails['id'], $loggedInUserDetailsArr['id']); // DEPRECATED
					$isCouponApplicable = $this->isCouponApplicableForCustomer($couponDetails['id'], $loggedInUserDetailsArr['id'], 0);
					if($isCouponApplicable){ // customer has not used this coupon code yet

						$couponApplied = false;
						$price = $this->getCartTotal();
						// prepare product to add in session
						$couponCodeArr = array(
								"couponCode" => $couponCode,
							);

						if(isset($_SESSION[SITE_NAME]['couponCode'])){ // check if session exists
							$couponCodeInSession = array_column($_SESSION[SITE_NAME]['couponCode'], 'couponCode');
							if(in_array($couponCode, $couponCodeInSession)){ // coupon code already applied
								$statusMessage = "Coupon code already applied";
							} else {
								if($couponDetails['minimum_purchase_amount']>0 && $price < $couponDetails['minimum_purchase_amount']) {
									$statusMessage = "Coupon Code not valid";
								} else {
									$_SESSION[SITE_NAME]['couponCode'][] = $couponCodeArr;
									$statusMessage = "Coupon code applied";
									$couponApplied = true;
								}
							}

						} else { // create session, add coupon code for that product
							if($couponDetails['minimum_purchase_amount']>0 && $price < $couponDetails['minimum_purchase_amount']) {
								$statusMessage = "Coupon Code not valid";
							} else {
								$_SESSION[SITE_NAME]['couponCode'] = array($couponCodeArr);
								$statusMessage = "Coupon code applied";
								$couponApplied = true;
							}
						}
						if($couponApplied){ // coupon applied to at least one product
							return array(
								"response" => true,
								"responseMsg" => $statusMessage,
								"couponCodeMsg" => $statusMessage,
								"couponCodeArr" => $_SESSION[SITE_NAME]['couponCode'],
							);
						} else { // coupon code applied to 0 product, reject coupon code, product not in cart
							return array(
								"response" => true,
								"responseMsg" => "This coupon is not valid for any product in cart",
								"couponCodeMsg" => "This coupon is not valid for any product in cart",
								"error" => "INVALIDCOUPON"
							); 
						}
					} else {
						return array(
							"response" => true,
							"responseMsg" => "You have already used this coupon",
							"couponCodeMsg" => "You have already used this coupon",
							"error" => "COUPONUSED"
						);
					}
				} else {
					return array(
						"response" => true,
						"responseMsg" => "Please enter a valid coupon",
						"couponCodeMsg" => "Please enter a valid coupon",
						"error" => "INVALIDCOUPON"
					);
				}
			}
		}
		function removeAllCouponCodes(){
			if(isset($_SESSION[SITE_NAME]['couponCode'])){
				unset($_SESSION[SITE_NAME]['couponCode']);
			}
		}
		/*function removeCouponCodesForProductId($productId){
			if(isset($_SESSION[SITE_NAME]['couponCode'])){
				foreach($_SESSION[SITE_NAME]['couponCode'] as $oneCoupon){
					// $couponCode = $this->escape_string($this->strip_all($oneCoupon['couponCode']));
					$inSessionProductId = $this->escape_string($this->strip_all($oneCoupon['productId']));
					if($productId == $inSessionProductId){
						unset($_SESSION[SITE_NAME]['couponCode']);
						return true;
					}
				}
			}
			return false;
		}*/
		function removeCouponCodesForProductId($productId){
			if(isset($_SESSION[SITE_NAME]['couponCode'])){
				foreach($_SESSION[SITE_NAME]['couponCode'] as $index => $oneCoupon){
					// $couponCode = $this->escape_string($this->strip_all($oneCoupon['couponCode']));
					$inSessionProductId = $this->escape_string($this->strip_all($oneCoupon['productId']));
					if($productId == $inSessionProductId){
						unset($_SESSION[SITE_NAME]['couponCode'][$index]);
					}
				}
				if(count($_SESSION[SITE_NAME]['couponCode']) == 0){
					unset($_SESSION[SITE_NAME]['couponCode']);
				}
				return true;
			}
			return false;
		}
		function getRedeemedCouponAmount($customerId, $orderId){
			$customerId = $this->escape_string($this->strip_all($customerId));
			$orderId = $this->escape_string($this->strip_all($orderId));
			$query = "select * from ".PREFIX."order_discount_coupons where order_id='".$orderId."' and customer_id='".$customerId."'";

			$sql = $this->query($query);
			if($sql->num_rows>0){

				$totalDiscountAmount = 0;
				
				while($couponDetails = $this->fetch($sql)){
				

					//$query = "select * from ".PREFIX."order_details where order_id='".$orderId."' and product_id='".$couponDetails['product_id']."' and customer_id='".$customerId."'";
					$query = "select * from ".PREFIX."order_details where order_id='".$orderId."' and customer_id='".$customerId."'";
					$productDetailsRS = $this->query($query);
					$productDetails = $this->fetch($productDetailsRS);

					$quantityPurchased = $productDetails['quantity'];
					$price = $productDetails['customer_price'];
					if(!empty($productDetails['customer_discount_price'])) {
						$discountedPrice = $productDetails['customer_discount_price'];
						$price = $discountedPrice;
					}
					$discountOnThisPrice = ($price * $quantityPurchased);
					$precision = 2;
					
					if($couponDetails['coupon_type']=="percent"){

						//new script - 24/04/2019
							// $orderDetailsRS=$this->query("select * from ".PREFIX."order_details where order_id='".$orderId."' and customer_id='".$customerId."'");
							// $orderSubTotal=0;
							// while($productDetails = $this->fetch($orderDetailsRS)){
							// 	$quantityPurchased = $productDetails['quantity'];
							// 	$price = $productDetails['customer_price'];
							// 	if(!empty($productDetails['customer_discount_price'])) {
							// 		$discountedPrice = $productDetails['customer_discount_price'];
							// 		$price = $discountedPrice;
							// 	}
							// 	$discountOnThisPrice = ($price * $quantityPurchased);
							// 	$orderSubTotal += $discountOnThisPrice;
							// }
							// $couponDiscountAmount = round( ( $orderSubTotal*($couponDetails['coupon_value']/100) ) , $precision);
						// new script ends::end

						//old 
							$couponDiscountAmount = round((($couponDetails['coupon_value'] * $discountOnThisPrice) / 100), $precision);
						//old



					} else if($couponDetails['coupon_type']=="amount"){
						$couponDiscountAmount = round($couponDetails['coupon_value'], $precision);
					} else {
						$couponDiscountAmount = 0; // invalid values in database
					}
					$totalDiscountAmount += $couponDiscountAmount;
				}
				return $totalDiscountAmount;
			} else {
				return 0;
			}
		}
		function getRedeemedVendorsCouponAmount($customerId, $orderId, $vendorId){
			$customerId = $this->escape_string($this->strip_all($customerId));
			$orderId = $this->escape_string($this->strip_all($orderId));
			$query = "select * from ".PREFIX."order_discount_coupons where order_id='".$orderId."' and customer_id='".$customerId."'";

			$sql = $this->query($query);
			if($sql->num_rows>0){

				$totalDiscountAmount = 0;
				
				while($couponDetails = $this->fetch($sql)){
				

					//$query = "select * from ".PREFIX."order_details where order_id='".$orderId."' and product_id='".$couponDetails['product_id']."' and customer_id='".$customerId."'";
					$query = "select * from ".PREFIX."order_details where order_id='".$orderId."' and customer_id='".$customerId."' and vendor_id='".$vendorId."'";
					$productDetailsRS = $this->query($query);
					$productDetails = $this->fetch($productDetailsRS);

					$quantityPurchased = $productDetails['quantity'];
					$price = $productDetails['customer_price'];
					if(!empty($productDetails['customer_discount_price'])) {
						$discountedPrice = $productDetails['customer_discount_price'];
						$price = $discountedPrice;
					}
					$discountOnThisPrice = ($price * $quantityPurchased);
					$precision = 2;
					
					if($couponDetails['coupon_type']=="percent"){

						//new script - 24/04/2019
							// $orderDetailsRS=$this->query("select * from ".PREFIX."order_details where order_id='".$orderId."' and customer_id='".$customerId."'");
							// $orderSubTotal=0;
							// while($productDetails = $this->fetch($orderDetailsRS)){
							// 	$quantityPurchased = $productDetails['quantity'];
							// 	$price = $productDetails['customer_price'];
							// 	if(!empty($productDetails['customer_discount_price'])) {
							// 		$discountedPrice = $productDetails['customer_discount_price'];
							// 		$price = $discountedPrice;
							// 	}
							// 	$discountOnThisPrice = ($price * $quantityPurchased);
							// 	$orderSubTotal += $discountOnThisPrice;
							// }
							// $couponDiscountAmount = round( ( $orderSubTotal*($couponDetails['coupon_value']/100) ) , $precision);
						// new script ends::end

						//old 
							$couponDiscountAmount = round((($couponDetails['coupon_value'] * $discountOnThisPrice) / 100), $precision);
						//old



					} else if($couponDetails['coupon_type']=="amount"){
						$couponDiscountAmount = round($couponDetails['coupon_value'], $precision);
					} else {
						$couponDiscountAmount = 0; // invalid values in database
					}
					$totalDiscountAmount += $couponDiscountAmount;
				}
				return $totalDiscountAmount;
			} else {
				return 0;
			}
		}
		function getCartTotal(){
			$total = 0;
			if(isset($_SESSION[SITE_NAME]['cart'])) {
				foreach($_SESSION[SITE_NAME]['cart'] as $oneProduct) {
					$productPrice = $this->getUniqueProductById($oneProduct['productId']);
					if(!empty($productPrice['discount_price'])) {
						$discountedPrice = $productPrice['discount_price'];
						$price = $discountedPrice * $oneProduct['quantity'];
						unset($discountedPrice);
					} else {
						$price = $productPrice['price'] * $oneProduct['quantity'];
					}
					$total += $price;
				}
			}
			return $total;
		}
		// ================================ COUPON CODE ====================================

		function getByIdAddress($id){
			$id = $this->escape_string($this->strip_all($id));
			$sql = "SELECT * FROM ".PREFIX."customers_address WHERE `id`='".$id."'";
			return $this->query($sql);
		}
		function getPrimaryAddress($userId){
			$userId = $this->escape_string($this->strip_all($userId));
			$sql = "SELECT * FROM ".PREFIX."customers_address WHERE `customer_id`='".$userId."' and `setDefault`='1'";
			return $this->query($sql);
		}
		
		function getUniqueCustomerAddressById($id, $customerId){
			$id = $this->escape_string($this->strip_all($id));
			$customerId = $this->escape_string($this->strip_all($customerId));
			$query = "select * from ".PREFIX."customers_address where id='".$id."' and customer_id='".$customerId."'";
			//echo $query;exit();
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function addCustomerAddress($data, $customerId){

			$customerId = $this->escape_string($this->strip_all($customerId));
			$customer_fname = $this->escape_string($this->strip_all($data['customer_fname']));
			$customer_contact = $this->escape_string($this->strip_all($data['customer_contact']));
			$customer_email = $this->escape_string($this->strip_all($data['customer_email']));
			$state = $this->escape_string($this->strip_all($data['state']));
			$city = $this->escape_string($this->strip_all($data['city']));
			if(isset($data['address1'])){
				$address1 = $this->escape_string($this->strip_all($data['address1']));
			} else {
				$address1 = '';
			}
			if(isset($data['address2'])){
				$address2 = $this->escape_string($this->strip_all($data['address2']));
			} else {
				$address2 = '';
			}
			if(isset($data['pincode'])){
				$pincode = $this->escape_string($this->strip_all($data['pincode']));
			} else {
				$pincode = 0;
			}
			$query = "INSERT INTO ".PREFIX."customers_address(`customer_id`, `address1`, `address2`, `state`, `city`, `pincode`, `customer_fname`, `customer_contact`, `customer_email`) VALUES ('".$customerId."','".$address1."','".$address2."','".$state."','".$city."','".$pincode."','".$customer_fname."','".$customer_contact."','".$customer_email."')";
			$this->query($query);
			return $this->last_insert_id();
		}
		function updateCustomerAddress($data, $userId){
			$id = $this->escape_string($this->strip_all($data['id']));
			$first_name = $this->escape_string($this->strip_all($data['customer_fname']));
			$customer_email = $this->escape_string($this->strip_all($data['customer_email']));
			$contact = $this->escape_string($this->strip_all($data['customer_contact']));
			if(isset($data['address1'])){
				$address1 = $this->escape_string($this->strip_all($data['address1']));
			} else {
				$address1 = '';
			}
			if(isset($data['address2'])){
				$address2 = $this->escape_string($this->strip_all($data['address2']));
			} else {
				$address2 = '';
			}
		
			$state = $this->escape_string($this->strip_all($data['state']));
			$city = $this->escape_string($this->strip_all($data['city']));
			$pincode = $this->escape_string($this->strip_all($data['pincode']));
			
			$query = "UPDATE ".PREFIX."customers_address SET `address1`='".$address1."',`address2`='".$address2."',`state`='".$state."',`city`='".$city."',`pincode`='".$pincode."',`customer_fname`='".$first_name."',`customer_contact`='".$contact."',`customer_email`='".$customer_email ."'  WHERE id='".$id."'";
			//echo $query; exit;
			$this->query($query);
			// return $this->last_insert_id();
		}

		function getAddressByuserId($userid){
			$userid = $this->escape_string($this->strip_all($userid));
			$sql ="SELECT * FROM ".PREFIX."customers_address WHERE `customer_id`='".$userid."'";
			return $this->query($sql);
		}
		function setAsDefaultAddress($request,$userlogedDetails){
			$setDetaultAddress = $this->escape_string($this->strip_all($request['setDetaultAddress']));
			$id = $this->escape_string($this->strip_all($request['id']));
			$userId = $this->escape_string($this->strip_all($userlogedDetails['id']));

			$sql = "UPDATE ".PREFIX."customers_address SET setDefault='0' WHERE customer_id='".$userId."'";
			$this->query($sql);
			
			$_SESSION[SITE_NAME]['BILLADDRESS']['Billing'] = $id;
			$sql = "UPDATE ".PREFIX."customers_address SET setDefault='1' WHERE id='".$id."' and customer_id='".$userId."'";
			return $this->query($sql);
		}

		function getListOfCities(){
			$query = "select distinct districtname from ".PREFIX."pincode order by districtname asc";
			return $this->query($query);
		}
		function getListOfStates(){
			$query = "select * from ".PREFIX."states order by name asc";
			return $this->query($query);
		}
		function getDisplayAddress($oneAddress, $eol){
			$email='';
			$customer_contact='';
			if(!empty($oneAddress['email'])){
				$email = "Email / Contact : ".$oneAddress['email'].$eol;	
			}
			if(!empty($oneAddress['customer_contact'])){
				$customer_contact = "Contact : ".$oneAddress['customer_contact'].$eol;	
			}
			
			$displayAddress = $oneAddress['address1'].','.$eol;
			if(!empty($oneAddress['address2'])){
				$displayAddress .= $oneAddress['address2'].','.$eol;
			}
			$displayAddress .= $oneAddress['city'].' - '.$oneAddress['pincode'].$eol;
			$displayAddress .= ucfirst($oneAddress['state']).$eol;
			$displayAddress .= $email;
			$displayAddress .= $customer_contact;
			return $displayAddress;
		}
		function getUniqueRegisteredUserById($id){
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."customers where id='".$id."' "));
		}
		function getIndianCurrency($number) {
			$decimal = round($number - ($no = floor($number)), 2) * 100;
			$hundred = null;
			$digits_length = strlen($no);
			$i = 0;
			$str = array();
			$words = array(0 => '', 1 => 'one', 2 => 'two',
				3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
				7 => 'seven', 8 => 'eight', 9 => 'nine',
				10 => 'ten', 11 => 'eleven', 12 => 'twelve',
				13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
				16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
				19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
				40 => 'forty', 50 => 'fifty', 60 => 'sixty',
				70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
			$digits = array('', 'hundred','thousand','lakh', 'crore');
			while( $i < $digits_length ) {
				$divider = ($i == 2) ? 10 : 100;
				$number = floor($no % $divider);
				$no = floor($no / $divider);
				$i += $divider == 10 ? 1 : 2;
				if ($number) {
					$plural = (($counter = count($str)) && $number > 9) ? '' : null;
					//$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
					$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
					$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
				} else $str[] = null;
			}
			$Rupees = implode('', array_reverse($str));
			$paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
			//return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
			return ucfirst(($Rupees ? $Rupees . 'rupees ' : '') . $paise);
		}

		function updateRegisteredUser($data, $userId){

			$id = $this->escape_string($this->strip_all($userId));
			$name 	= $this->escape_string($this->strip_all($data['name']));
			$email 		= $this->escape_string($this->strip_all($data['email']));
			$mobile 		= $this->escape_string($this->strip_all($data['mobile']));
			$company_name 		= $this->escape_string($this->strip_all($data['company_name']));
			$gst_no 		= $this->escape_string($this->strip_all($data['gst_no']));
			
			if(isset($data['company_name']) && !empty($data['company_name'])) {
				$company_name = $this->escape_string($this->strip_all($data['company_name']));
				$this->query("update ".PREFIX."customers set company_name='".$company_name."' where id='".$id."' ");
			}

			if(isset($data['gst_no']) && !empty($data['gst_no'])) {
				$gst_no = $this->escape_string($this->strip_all($data['gst_no']));
				$this->query("update ".PREFIX."customers set gst_no='".$gst_no."' where id='".$id."' ");
			}

			if(!empty($data['password'])) {
				$password = $this->escape_string($this->strip_all($data['password']));
				$passwordHASH = password_hash($password, PASSWORD_DEFAULT);
				$this->query("update ".PREFIX."customers set password='".$passwordHASH."' where id='".$id."' ");
			}

			$this->query("update ".PREFIX."customers set email='".$email."', mobile='".$mobile."', first_name='".$name."', last_name='".$last_name."' where id='".$id."' ");
			return true;
		}
		function getOrderDetailsData($orderDetailsID){
			$orderDetailsID = $this->escape_string($this->strip_all($orderDetailsID));

			$sql = "SELECT * FROM ".PREFIX."refund_request WHERE `order_detail_pal`='".$orderDetailsID."'";
			$result =  $this->query($sql);
			if($this->num_rows($result)>0){
				return false;
			}else{
				return true;
			}
			//return $this->fetch($result);
		}
		function getWishlistByUserId($userID){
			$userID = $this->escape_string($this->strip_all($userID));
			$sql = "SELECT * FROM ".PREFIX."customers_wishlist WHERE `customer_id`='".$userID."' order by id DESC";
			//echo $sql;
			return  $this->query($sql);

		}
		function getAllSubCategoriesByCategoryId($category_id) {
			$category_id = $this->escape_string($this->strip_all($category_id));
			$query = "select * from ".PREFIX."sub_category_master where category_id in (".$category_id.")";
			$sql = $this->query($query);
			return $sql;
		}
		function getAllSubSubCategories($Subsubcategory_id) {
			$Subsubcategory_id = $this->escape_string($this->strip_all($Subsubcategory_id));
			$query = "select * from ".PREFIX."subsubCategory where sub_category_id in (".$Subsubcategory_id.")";
			//echo $query;
			$sql = $this->query($query);
			return $sql;
		}
		function getAllSubSubSubCategories($Subsubcategory_id) {
			$Subsubcategory_id = $this->escape_string($this->strip_all($Subsubcategory_id));
			$query = "select * from ".PREFIX."subsubsubCategory where subsubcate_id in (".$Subsubcategory_id.")";
			//echo $query;
			$sql = $this->query($query);
			return $sql;
		}
		function isProductCodeIsUnique($product_code,$id=''){
			$product_code = $this->escape_string($this->strip_all($product_code));
			$id = $this->escape_string($this->strip_all($id));
			if(!empty($id)){
				$id = " and id<>'".$id."'";
			}
			$sql = "SELECT * FROM ".PREFIX."product_master WHERE `product_code`='".$product_code."' $id";
			//echo $sql;
			$result = $this->query($sql);
			if($result->num_rows>0){ // at lease one email exists 
				return false;
			} else {
				return true;
			}
		}
		function addProduct($data, $file){

			/*print_r($data); 
			exit;*/
			$product_name = $this->escape_string($this->strip_all($data['product_name']));
			$product_code = $this->escape_string($this->strip_all($data['product_code']));
			$hsn_code = $this->escape_string($this->strip_all($data['hsn_code']));
			
			$category = $this->escape_string($this->strip_all($data['category']));
			$Subsub_category = $this->escape_string($this->strip_all($data['Subsub_category']));
			$sub_cat = $this->escape_string($this->strip_all($data['sub_cat']));
			
			$price = $this->escape_string($this->strip_all($data['price']));
			$discount_price = $this->escape_string($this->strip_all($data['discount_price']));
			$availability = $this->escape_string($this->strip_all($data['availability']));
			$tax = $this->escape_string($this->strip_all($data['tax']));
			$description =	$data['description'];
			$vendor_id = $this->escape_string($this->strip_all($data['vendor_id']));
			$brand = $this->escape_string($this->strip_all($data['brand']));

			$date = date('Ymdhis');
			$permalink = $this->getValidatedPermalink($product_name);
			$permalink = $permalink."/".$date;
			$discount_price = $this->escape_string($this->strip_all($data['discount_price']));
			if(empty($discount_price)){
				$discount_price = 0;
			}
			$SaveImage = new SaveImage();
			$imgDir = 'images/products/';

			if(isset($file['main_image']['name']) && !empty($file['main_image']['name'])){
				$file_name = strtolower( pathinfo($file['main_image']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$cropData = $this->strip_all($data['cropData1']);
				$main_image = $SaveImage->uploadCroppedImageFileFromForm($file['main_image'], 1000, $cropData, $imgDir, time().'-1');
			} else {
				$main_image = '';
			}

			if(isset($file['image_one']['name']) && !empty($file['image_one']['name'])){
				$file_name = strtolower( pathinfo($file['image_one']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$cropData = $this->strip_all($data['cropData2']);
				$image_one = $SaveImage->uploadCroppedImageFileFromForm($file['image_one'], 1000, $cropData, $imgDir, time().'-2');
			} else {
				$image_one = '';
			}
			
			if(isset($file['image_two']['name']) && !empty($file['image_two']['name'])){
				$file_name = strtolower( pathinfo($file['image_two']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$cropData = $this->strip_all($data['cropData3']);
				$image_two = $SaveImage->uploadCroppedImageFileFromForm($file['image_two'], 1000, $cropData, $imgDir, time().'-3');
			} else {
				$image_two = '';
			}
			
			if(isset($file['image_three']['name']) && !empty($file['image_three']['name'])){
				$file_name = strtolower( pathinfo($file['image_three']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$cropData = $this->strip_all($data['cropData4']);
				$image_three = $SaveImage->uploadCroppedImageFileFromForm($file['image_three'], 1000, $cropData, $imgDir, time().'-4');
			} else {
				$image_three = '';
			}
			
			if(isset($file['image_four']['name']) && !empty($file['image_four']['name'])){
				$file_name = strtolower( pathinfo($file['image_four']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$cropData = $this->strip_all($data['cropData5']);
				$image_four = $SaveImage->uploadCroppedImageFileFromForm($file['image_four'], 1000, $cropData, $imgDir, time().'-5');
			} else {
				$image_four = '';
			}
			
			$image_five ='';
			
			$createDate = date('Y-m-d H:i:s');
			$sql = "INSERT INTO ".PREFIX."product_master( `category_id`, `sub_cat_id`, `brand_id`, `product_name`, `product_code`, `hsn_code`, `availability`, `main_image`, `image_one`, `image_two`, `image_three`, `image_four`, `price`, `discount_price`, `tax`, `description`, `permalink`, `time`, subsub_categor_id, vendor_id, created, active) VALUES ('".$category."', '".$sub_cat."', '".$brand."', '".$product_name."', '".$product_code."', '".$hsn_code."', '".$availability."', '".$main_image."', '".$image_one."', '".$image_two."', '".$image_three."', '".$image_four."', '".$price."', '".$discount_price."', '".$tax."', '".$description."', '".$permalink."' , '".$date."', '".$Subsub_category."', '".$vendor_id."', '".$createDate."', 'No')";
			//echo $sql; exit;
			$this->query($sql);
			$product_id = $this->last_insert_id();

			if(isset($data['category']) && sizeof($data['category'])>0){
				foreach ($data['category'] as $category_id) {
					$category_id = $this->escape_string($this->strip_all($category_id));
					$addCat = "INSERT INTO ".PREFIX."product_category_mapping(`category_id`, `product_id`) VALUES ('".$category_id."','".$product_id."')";
					$this->query($addCat);
				}
			}
			if(isset($data['sub_cat']) && sizeof($data['sub_cat'])>0){
				foreach ($data['sub_cat'] as $subcategory_id) {
					$subcategory_id = $this->escape_string($this->strip_all($subcategory_id));
					$addSubCat = "INSERT INTO ".PREFIX."product_subcategory_mapping(`subscategory_id`, `product_id`) VALUES ('".$subcategory_id."','".$product_id."')";
					$this->query($addSubCat);
				}
			}
			if(isset($data['Subsub_category']) && sizeof($data['Subsub_category'])>0){
				foreach ($data['Subsub_category'] as $subsubcategory_id) {
					$subsubcategory_id = $this->escape_string($this->strip_all($subsubcategory_id));
					$addSubCat = "INSERT INTO ".PREFIX."product_subsubcategory_mapping(`subsubcategory_id`, `product_id`) VALUES ('".$subsubcategory_id ."','".$product_id."')";
					$this->query($addSubCat);
				}
			}

		}

		function updateProduct($data, $file){
			//print_r($file); exit;
			$id = $this->escape_string($this->strip_all($data['id']));
			$product_name = $this->escape_string($this->strip_all($data['product_name']));
			$product_code = $this->escape_string($this->strip_all($data['product_code']));
			$hsn_code = $this->escape_string($this->strip_all($data['hsn_code']));
			
			$category = $this->escape_string($this->strip_all($data['category']));
			$Subsub_category = $this->escape_string($this->strip_all($data['Subsub_category']));
			$sub_cat = $this->escape_string($this->strip_all($data['sub_cat']));
			
			$price = $this->escape_string($this->strip_all($data['price']));
			$discount_price = $this->escape_string($this->strip_all($data['discount_price']));
			$availability = $this->escape_string($this->strip_all($data['availability']));
			$tax = $this->escape_string($this->strip_all($data['tax']));
			$description =	$data['description'];
			$vendor_id = $this->escape_string($this->strip_all($data['vendor_id']));
			$brand = $this->escape_string($this->strip_all($data['brand']));

			$Detail = $this->getUniqueProductById($id);
			//print_r($Detail);
			$updatetime = ''; 
			$permalink = $this->getValidatedPermalink($product_name);
			if(!empty($Detail['time'])){
				$time = $Detail['time'];
			}else{
				$time = date('Ymdhis');
				$updatetime =", time='".$time."'";
			}

			$permalink = $permalink."/".$time;

			$SaveImage = new SaveImage();
			$imgDir = 'images/products/';


			if(isset($file['main_image']['name']) && !empty($file['main_image']['name'])) {
				$cropData = $this->strip_all($data['cropData1']);
				$file_name = strtolower( pathinfo($file['main_image']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$this->unlinkImage("products", $Detail['main_image'], "large");
				$this->unlinkImage("products", $Detail['main_image'], "crop");
				$main_image = $SaveImage->uploadCroppedImageFileFromForm($file['main_image'], 1000, $cropData, $imgDir, time().'-1');
				$this->query("update ".PREFIX."product_master set main_image='$main_image' where id='$id'");
			}
			if(isset($file['image_one']['name']) && !empty($file['image_one']['name'])) {
				$cropData = $this->strip_all($data['cropData2']);
				$file_name = strtolower( pathinfo($file['image_one']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$this->unlinkImage("products", $Detail['image_one'], "large");
				$this->unlinkImage("products", $Detail['image_one'], "crop");
				$image_one = $SaveImage->uploadCroppedImageFileFromForm($file['image_one'], 1000, $cropData, $imgDir, time().'-2');
				$this->query("update ".PREFIX."product_master set image_one='$image_one' where id='$id'");
			}
			if(isset($file['image_two']['name']) && !empty($file['image_two']['name'])) {
				$cropData = $this->strip_all($data['cropData3']);
				$file_name = strtolower( pathinfo($file['image_two']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$this->unlinkImage("products", $Detail['image_two'], "large");
				$this->unlinkImage("products", $Detail['image_two'], "crop");
				$image_two = $SaveImage->uploadCroppedImageFileFromForm($file['image_two'], 1000, $cropData, $imgDir, time().'-3');
				$this->query("update ".PREFIX."product_master set image_two='$image_two' where id='$id'");
			}
			if(isset($file['image_three']['name']) && !empty($file['image_three']['name'])) {
				$cropData = $this->strip_all($data['cropData4']);
				$file_name = strtolower( pathinfo($file['image_three']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$this->unlinkImage("products", $Detail['image_three'], "large");
				$this->unlinkImage("products", $Detail['image_three'], "crop");
				$image_three = $SaveImage->uploadCroppedImageFileFromForm($file['image_three'], 1000, $cropData, $imgDir, time().'-4');
				$this->query("update ".PREFIX."product_master set image_three='$image_three' where id='$id'");
			}
			if(isset($file['image_four']['name']) && !empty($file['image_four']['name'])) {
				$cropData = $this->strip_all($data['cropData5']);
				$file_name = strtolower( pathinfo($file['image_four']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$this->unlinkImage("products", $Detail['image_four'], "large");
				$this->unlinkImage("products", $Detail['image_four'], "crop");
				$image_four = $SaveImage->uploadCroppedImageFileFromForm($file['image_four'], 1000, $cropData, $imgDir, time().'-5');
				$this->query("update ".PREFIX."product_master set image_four='$image_four' where id='$id'");
			}
			
			

			$sql = "UPDATE ".PREFIX."product_master SET `category_id`='".$category."',`sub_cat_id`='".$sub_cat."',`brand_id`='".$brand."',`product_name`='".$product_name."',`product_code`='".$product_code."',`hsn_code`='".$hsn_code."',`availability`='".$availability."',`price`='".$price."',`discount_price`='".$discount_price."',`tax`='".$tax."',`description`='".$description."', `permalink`='".$permalink."', `time`='".$time."', subsub_categor_id='".$Subsub_category."', vendor_id='".$vendor_id."',active='No' WHERE id='".$id."'";
			//echo $sql; exit;
			// echo $sql; exit;
			$this->query($sql);

			$this->deleCategoryByProductId($id);			
			if(isset($data['category']) && sizeof($data['category'])>0){
				foreach ($data['category'] as $category_id) {
					$category_id = $this->escape_string($this->strip_all($category_id));
					$addCat = "INSERT INTO ".PREFIX."product_category_mapping(`category_id`, `product_id`) VALUES ('".$category_id."','".$id."')";
					$this->query($addCat);
				}
			}

			$this->deleSubCategoryByProductId($id);
			if(isset($data['sub_cat']) && sizeof($data['sub_cat'])>0){
				foreach ($data['sub_cat'] as $subcategory_id) {
					$subcategory_id = $this->escape_string($this->strip_all($subcategory_id));
					$addSubCat = "INSERT INTO ".PREFIX."product_subcategory_mapping(`subscategory_id`, `product_id`) VALUES ('".$subcategory_id."','".$id."')";
					$this->query($addSubCat);
				}
			}

			$this->deleSubSubCategoryByProductId($id);
			
			if(isset($data['Subsub_category']) && sizeof($data['Subsub_category'])>0){
				foreach ($data['Subsub_category'] as $subsubcategory_id) {
					$subsubcategory_id = $this->escape_string($this->strip_all($subsubcategory_id));
					$addSubsubCat = "INSERT INTO ".PREFIX."product_subsubcategory_mapping(`subsubcategory_id`, `product_id`) VALUES ('".$subsubcategory_id ."','".$id."')";
					$this->query($addSubsubCat);
				}
			}

		}
		function deleCategoryByProductId($product_id){
			$product_id = $this->escape_string($this->strip_all($product_id));
			$sql = "DELETE FROM ".PREFIX."product_category_mapping WHERE `product_id`='".$product_id."'";
			$this->query($sql);
		}
		function deleSubCategoryByProductId($product_id){
			$product_id = $this->escape_string($this->strip_all($product_id));
			$sql = "DELETE FROM ".PREFIX."product_subcategory_mapping WHERE `product_id`='".$product_id."'";
			$this->query($sql);
		}
		function deleSubSubCategoryByProductId($product_id){
			$product_id = $this->escape_string($this->strip_all($product_id));
			$sql = "DELETE FROM ".PREFIX."product_subsubcategory_mapping WHERE `product_id`='".$product_id."'";
			$this->query($sql);
		}
		function getVendorOrderDetailsByvendorID($vendorid){
			$vendorid = $this->escape_string($this->strip_all($vendorid));
			$sql ="SELECT * FROM ".PREFIX."order_details WHERE `vendor_id`='".$vendorid."' and order_id in  (select id from ".PREFIX."order where `payment_status`='Payment Complete' or (`payment_status`='Payment Pending' and payment_mode='COD')) group by order_id  order by id DESC";
			return $this->query($sql);
		}
		function getOrderbyOrderId($orderId){
			$orderId = $this->escape_string($this->strip_all($orderId));
			$sql ="SELECT * FROM ".PREFIX."order WHERE `id`='".$orderId."'";
			$result =  $this->query($sql);
			return $this->fetch($result);
		}
		function getSliderbBanner(){
			$sql = "SELECT * FROM ".PREFIX."slider_banner WHERE `active`='Yes' order by  id DESC";
			return $this->query($sql);
		}
		function getActiveBrand(){
			$sql = "SELECT * FROM ".PREFIX."brand_master order by  id DESC";
			return $this->query($sql);	
		}
		function getBrandbyPermlink($permalink){
			$permalink = $this->escape_string($this->strip_all($permalink));
			$sql = "SELECT * FROM ".PREFIX."brand_master WHERE `permalink`='".$permalink."'";
			//echo $sql;
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function gelatestProduct(){
			$sql = "SELECT * FROM ".PREFIX."product_master where active='Yes' order by  id DESC LIMIT 30";
			return $this->query($sql);
		}
		function getBestSellerProduct(){
			$sql = "SELECT * FROM ".PREFIX."product_master where active='Yes' and best_seller='Yes' order by  id DESC LIMIT 30";
			return $this->query($sql);
		}
		function contactUsRequest($data){

			$name = $this->escape_string($this->strip_all($data['name']));
			$email = $this->escape_string($this->strip_all($data['email']));
			$mobile = $this->escape_string($this->strip_all($data['mobile']));
			$message = $this->escape_string($this->strip_all($data['message']));
			$createDate = date('Y-m-d H:i:s');
			$sql = "INSERT INTO ".PREFIX."contact_us( `name`, `email`, `mobile`, `feedback`, created) VALUES ('".$name."','".$email."','".$mobile."','".$message."', '".$createDate."')";
			$this->query($sql);
		}

		function getContactUsCmsMasterDetails(){
			$sql = "SELECT * FROM ".PREFIX."contact_us_cms order by id DESC";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function gerCMSDetails(){
			$sql = "SELECT * FROM ".PREFIX."cms_master order by id DESC";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function getHomePageCms(){
			$sql = "SELECT * FROM ".PREFIX."home_cms";
			$result = $this->query($sql);
			return $this->fetch($result);

		}
		function getAdminDetails(){
			$sql ="SELECT * FROM ".PREFIX."admin WHERE `user_role`='super'";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function getAllSubSubCategoriesbyProductID($productId,$suCatId) {
			$productId = $this->escape_string($this->strip_all($productId));
			$suCatId = $this->escape_string($this->strip_all($suCatId));
			$query = "SELECT * FROM ".PREFIX."product_subsubcategory_mapping WHERE  `product_id`='".$productId."' and subsubcategory_id='".$suCatId."'";
			//echo $query;
			$sql = $this->query($query);
			return $sql;
		}
		function getAllSubSubSubCategoriesbyProductID($productId,$suCatId) {
			$productId = $this->escape_string($this->strip_all($productId));
			$suCatId = $this->escape_string($this->strip_all($suCatId));
			$query = "SELECT * FROM ".PREFIX."product_subsubcategory_mapping WHERE  `product_id`='".$productId."' and subsubcategory_id='".$suCatId."'";
			//echo $query;
			$sql = $this->query($query);
			return $sql;
		}
	}
?>