<?php
	include('database.php');
	include('SaveImage.class.php');
	include('classes/CSRF.class.php');
	// error_reporting(E_ALL);
	/*
	 * AdminFunctions
	 * v1 - updated loginSession(), logoutSession(), adminLogin()
	 */
	class AdminFunctions extends Database {
		private $userType = 'admin';

		// === LOGIN BEGINS ===
		function loginSession($userId, $userFirstName, $userLastName, $userType,$role) {
			/* DEPRECATED $_SESSION[SITE_NAME] = array(
				$this->userType."UserId" => $userId,
				$this->userType."UserFirstName" => $userFirstName,
				$this->userType."UserLastName" => $userLastName,
				$this->userType."UserType" => $this->userType
			); DEPRECATED */
			$_SESSION[SITE_NAME][$this->userType."UserId"] = $userId;
			$_SESSION[SITE_NAME][$this->userType."UserFirstName"] = $userFirstName;
			$_SESSION[SITE_NAME][$this->userType."UserLastName"] = $userLastName;
			$_SESSION[SITE_NAME][$this->userType."UserType"] = $this->userType;
			$_SESSION[SITE_NAME][$this->userType."role"] = $role;

			/*switch($userType){
				case:'admin'{
					break;
				}
				case:'supplier'{
					break;
				}
				case:'warehouse'{
					break;
				}
				
			}*/
		}
		
		
		function logoutSession() {
			if(isset($_SESSION[SITE_NAME])){
				if(isset($_SESSION[SITE_NAME][$this->userType."UserId"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserId"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->userType."UserFirstName"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserFirstName"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->userType."UserLastName"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserLastName"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->userType."UserType"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserType"]);
				}
				return true;
			} else {
				return false;
			}
		}
		function adminLogin($data, $successURL, $failURL = "admin-login.php?failed") {
			$username = $this->escape_string($this->strip_all($data['username']));
			$password = $this->escape_string($this->strip_all($data['password']));
			$query = "select * from ".PREFIX."admin where username='".$username."'";
			$result = $this->query($query);

			if($this->num_rows($result) == 1) { // only one unique user should be present in the system
				$row = $this->fetch($result);
				if(password_verify($password, $row['password'])) {
					$this->loginSession($row['id'], $row['fname'], $row['lname'], $this->userType,$row['role']);
					$this->close_connection();
					header("location: ".$successURL);
					exit;
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
		/* function sessionExists(){
			if( isset($_SESSION[SITE_NAME]) && 
				isset($_SESSION[SITE_NAME][$this->userType.'UserId']) && 
				isset($_SESSION[SITE_NAME][$this->userType.'UserType']) && 
				!empty($_SESSION[SITE_NAME][$this->userType.'UserId']) &&
				$_SESSION[SITE_NAME][$this->userType.'UserType']==$this->userType){

				return $loggedInUserDetailsArr = $this->getLoggedInUserDetails();
				// return true; // DEPRECATED
			} else {
				return false;
			}
		} */
		function sessionExists(){
			if($this->isUserLoggedIn()){
				return $loggedInUserDetailsArr = $this->getLoggedInUserDetails();
				// return true; // DEPRECATED
			} else {
				return false;
			}
		}
		function isUserLoggedIn(){
			if( isset($_SESSION[SITE_NAME]) && 
				isset($_SESSION[SITE_NAME][$this->userType.'UserId']) && 
				isset($_SESSION[SITE_NAME][$this->userType.'UserType']) && 
				!empty($_SESSION[SITE_NAME][$this->userType.'UserId']) &&
				$_SESSION[SITE_NAME][$this->userType.'UserType']==$this->userType){
				return true;
			} else {
				return false;
			}
		}
		function getSystemUserType() {
			return $this->userType;
		}
		function getLoggedInUserDetails(){
			$loggedInID = $this->escape_string($this->strip_all($_SESSION[SITE_NAME][$this->userType.'UserId']));
			$loggedInUserDetailsArr = $this->getUniqueUserById($loggedInID);
			return $loggedInUserDetailsArr;
		}
		
		function getUniqueUserById($userId) {
			$id = $this->escape_string($this->strip_all($userId));
			$query = "select * from ".PREFIX."admin where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		// === LOGIN ENDS ====

		// == EXTRA FUNCTIONS STARTS ==
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
				"-!-", "-!", "!-", " ! ", " !", "! ", "!","®");
			$escapedPermalink = str_replace($replace_keywords, '-', $permalink); 
			return strtolower($escapedPermalink);
		}
		function getUniquePermalink($permalink,$tableName,$main_menu,$newPermalink='',$num=1) {
			if($newPermalink=='') {
				$checkPerma = $permalink;
			} else {
				$checkPerma = $newPermalink;
			}
			$sql = $this->query("select * from ".PREFIX.$tableName." where permalink='$checkPerma' and main_menu='$main_menu'");
			if($this->num_rows($sql)>0) {
				$count = $num+1;
				$newPermalink = $permalink.$count;
				return $this->getUniquePermalink($permalink,$tableName,$main_menu,$newPermalink,$count);
			} else {
				return $checkPerma;
			}
		}
		function getActiveLabel($isActive){
			if($isActive){
				return 'Yes';
			} else {
				return 'No';
			}
		}
		function getImageUrl($imageFor, $fileName, $imageSuffix){
			$image_name = strtolower(pathinfo($fileName, PATHINFO_FILENAME));
			$image_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			switch($imageFor){
				case "banner":
					$fileDir = "../images/banner/";
					break;
				case "category":
					$fileDir = "../images/category/";
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
					$fileDir = "../images/products/";
					break;
				case "static_banner":
					$fileDir = "../images/static_banner/";
					break;
				case "occasion":
					$fileDir = "../images/occasion/";
					break;
				case "testimonials":
					$fileDir = "../images/testimonials/";
					break;
				case "MainBasket":
					$fileDir = "../images/MainBasket/";
					break;
				case "hamper":
					$fileDir = "../images/hamper/";
					break;
				case "web_banner":
					$fileDir = "../images/web_banner/";
					break;
				case "brand":
					$fileDir = "../images/brand/";
					break;
				case "home_cms":
					$fileDir = "../images/home_cms/";
					break;
				default:
					return false;
					break;
			}

			$imageUrl = $fileDir.$image_name."_".$imageSuffix.".".$image_ext;
			if(file_exists($imageUrl)){
				return $imageUrl;
				// $imageUrl = BASE_URL.'/'.$imageUrl;
			} else {
				return false;
				// $imageUrl = BASE_URL."/images/no_img.jpg";
			}
		}
		function unlinkImage($imageFor, $fileName, $imageSuffix){
			$imagePath = $this->getImageUrl($imageFor, $fileName, $imageSuffix);
			$status = false;
			if($imagePath!==false){
				$status = unlink($imagePath);
			}
			return $status;
		}
		function checkUserPermissions($permission,$loggedInUserDetailsArr) {
			$userPermissionsArray = explode(',',$loggedInUserDetailsArr['permissions']);
			if(!in_array($permission,$userPermissionsArray) and $loggedInUserDetailsArr['user_role']!='super') {
				header("location: dashboard.php");
				exit;
			}
		}
		
	
		
		
		//Add User functions starts
		
		function getUserById($userId) {
			$id = $this->escape_string($this->strip_all($userId));
			$query = "select * from ".PREFIX."user_master where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		function addCustomerDetail($data) {

			$first_name = $this->escape_string($this->strip_all($data['first_name']));
			$last_name = $this->escape_string($this->strip_all($data['last_name']));
			$state = $this->escape_string($this->strip_all($data['state']));
			$city = $this->escape_string($this->strip_all($data['city']));
			$contact = $this->escape_string($this->strip_all($data['contact']));
			$pincode = $this->escape_string($this->strip_all($data['pincode']));
			$email = $this->escape_string($this->strip_all($data['email']));
			$mobile = $this->escape_string($this->strip_all($data['mobile']));
			$cType = $this->escape_string($this->strip_all($data['cType']));
			$company_name = $this->escape_string($this->strip_all($data['company_name']));
			$gst_no = $this->escape_string($this->strip_all($data['gst_no']));
			$date = date("Y-m-d H:i:s");

			if(isset($data['password']) && !empty($data['password'])){
				$password = $this->escape_string($this->strip_all($data['password']));
				$password = password_hash($password, PASSWORD_DEFAULT);
			} else {
				$password = '';
			}
			$active = $this->escape_string($this->strip_all($data['active']));
			$user_verified = '1';
			//$customer_type = 'customer';
			
			//$ref_code = $this->GenrateReferralCode();
			$query = "insert into ".PREFIX."customers(first_name, last_name,  email, password, mobile, active, is_email_verified, user_verified , created, user_type , company_name, gst_no) values ('".$first_name."', '".$last_name."', '".$email."', '".$password."', '".$mobile."', '".$active."', '1', '".$user_verified."', '".$date."','".$cType."','".$company_name."','".$gst_no."')";
			$this->query($query);

			$customerId = $this->last_insert_id();
			if($cType =="b2c"){
				$this->addCustomerAddress($data, $customerId);
			}	
		}
		function updateCustomerDetail($data) {
			$updatedFilledArr = array();
			$id = $this->escape_string($this->strip_all($data['id']));
			
			
			$first_name = $this->escape_string($this->strip_all($data['first_name']));
			$last_name = $this->escape_string($this->strip_all($data['last_name']));
			$email = $this->escape_string($this->strip_all($data['email']));
			$active = $this->escape_string($this->strip_all($data['active']));
			
			$results = $this->getUniqueCustomersById($id);

			if(isset($data['password']) && !empty($data['password'])){
				$password = $this->escape_string($this->strip_all($data['password']));
				$password = password_hash($password, PASSWORD_DEFAULT);
			} else {
				$password = $results['password'];
			}
			if(isset($data['company_name']) && !empty($data['company_name'])){
				$company_name = $this->escape_string($this->strip_all($data['company_name']));
				$sql = "UPDATE ".PREFIX."customers SET `company_name`='".$company_name."' WHERE id='".$id."'";
				$this->query($sql);
			}
			if(isset($data['gst_no']) && !empty($data['gst_no'])){
				$gst_no = $this->escape_string($this->strip_all($data['gst_no']));
				$sql = "UPDATE ".PREFIX."customers SET `gst_no`='".$gst_no."' WHERE id='".$id."'";
				$this->query($sql);
			}
			
			
			
			$sql = "UPDATE ".PREFIX."customers SET `first_name`='".$first_name."',`last_name`='".$last_name."',`email`='".$email."',`password`='".$password ."',`active`='".$active."' WHERE id='".$id."'";
			$this->query($sql);
			
		}
		function addCustomerAddress($data, $customerId){
			$first_name = $this->escape_string($this->strip_all($data['first_name']));
			$last_name = $this->escape_string($this->strip_all($data['last_name']));
			$contact = $this->escape_string($this->strip_all($data['contact']));

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
			$default_address = $this->getDefaultCustomerAddressByCustomerId($customerId);
			if(count($default_address)>0){
			$is_preferred = $this->escape_string($this->strip_all($data['is_preferred']));
				$is_preferred = '0';
			}else{
				$is_preferred = '1';
			}
			$query = "insert into ".PREFIX."customers_address(customer_id, address1, address2, state, city, pincode, customer_fname, customer_contact,is_preferred) 
					values ('".$customerId."', '".$address1."', '".$address2."', '".$state."', '".$city."', '".$pincode."', '".$first_name."', '".$contact."', '".$is_preferred."')";
			$this->query($query);
			return $this->last_insert_id();
		}
		function updateCustomerAddress($data, $addressId){
			$addressId = $this->escape_string($this->strip_all($addressId));
			$customerId = $this->escape_string($this->strip_all($data['customer_id']));
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
				$pincode = '';
			}
			$state = $this->escape_string($this->strip_all($data['state']));
			$city = $this->escape_string($this->strip_all($data['city']));
			// $customer_contact = $this->escape_string($this->strip_all($data['customer_contact']));
			$customer_fname = $this->escape_string($this->strip_all($data['customer_fname']));
			// $customer_lname = $this->escape_string($this->strip_all($data['customer_lname']));
			// $is_preferred = $this->escape_string($this->strip_all($data['is_preferred']));
			
			$default_address = $this->getDefaultCustomerAddressByCustomerId($customerId);
			// if(count($default_address)>0){
			// 	if($is_preferred == '1'){
			// 		// clear all preferred address
			// 		$query = "update ".PREFIX."customers_address set is_preferred=0 where customer_id='".$customerId."'";
			// 		$this->query($query);

			// 		// set selected address as preferred
			// 		$query = "update ".PREFIX."customers_address set is_preferred='".$is_preferred."' where id='".$addressId."' and customer_id='".$customerId."'";
			// 		$this->query($query);
			// 	}else{
			// 		$query = "update ".PREFIX."customers_address set is_preferred=0 where customer_id='".$customerId."'";
			// 		$this->query($query);

			// 		// set selected address as preferred
			// 		$query = "update ".PREFIX."customers_address set is_preferred='1' where id='".$addressId."' and customer_id='".$customerId."'";
			// 		$this->query($query);
			// 	}
			// }else{
			// 	$query = "update ".PREFIX."customers_address set is_preferred=0 where customer_id='".$customerId."'";
			// 	$this->query($query);

			// 	// set selected address as preferred
			// 	$query = "update ".PREFIX."customers_address set is_preferred='1' where id='".$addressId."' and customer_id='".$customerId."'";
			// 	$this->query($query);	
			// }
			
			$query = "update ".PREFIX."customers_address set customer_fname='".$customer_fname."', address1='".$address1."', address2='".$address2."', state='".$state."', city='".$city."', pincode='".$pincode."' where id='".$addressId."' and customer_id='".$customerId."'";
			$this->query($query);
			// return $this->last_insert_id();
		}
		function getDefaultCustomerAddressByCustomerId($customerId){
			$customerId = $this->escape_string($this->strip_all($customerId));
			$query = "select * from ".PREFIX."customers_address where customer_id='".$customerId."' and setDefault='1'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function getCustomerAddressesByCustomerId($customerId){
			$customerId = $this->escape_string($this->strip_all($customerId));
			$query = "select * from ".PREFIX."customers_address where customer_id='".$customerId."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function getCustomerAddressesById($customerId,$Id){
			$customerId = $this->escape_string($this->strip_all($customerId));
			$Id = $this->escape_string($this->strip_all($Id));
			$query = "select * from ".PREFIX."customers_address where customer_id='".$customerId."' and id='".$Id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		//Add User functions ends

		

		//About Us Functions

		function getUniqueAboutUsById($id){
			$id = $this->escape_string($this->strip_all($id));
			$sql = "SELECT `image_name` FROM ".PREFIX."about_us WHERE `id`='".$id."'";
			$this->query($sql);
		}

		function updateAboutUsPage($data,$file){
			$allowTags = "<strong><em><b><p><u><ul><li><ol><s><sub><sup><h1><img><h2><h3><h4><h5><h6><div><i><span><br><table><tr><th><td><thead><tbody><a>";
			$id = $this->escape_string($this->strip_all($data['id']));
			$description = $this->escape_string($this->strip_selected($data['description'],$allowTags));
			
			$query = "update ".PREFIX."about_us set description='$description' where id = '".$id."' ";
			$this->query($query);
		}

		//Contact US functions

		function getUniqueContactById($id){
			$query = $this->query("select * from ".PREFIX."contactus where id = '".$id."' order by id DESC");
			return $this->fetch($query);
		}
		
		function addContact($data){
			$allowTags = "<strong><em><b><p><u><ul><li><ol><s><sub><sup><h1><img><h2><h3><h4><h5><h6><div><i><span><br><table><tr><th><td><thead><tbody><a>";
			$region = $this->escape_string($this->strip_all($data['region']));
			$address = $this->escape_string($this->strip_selected($data['address'], $allowTags));
			$display_order = $this->escape_string($this->strip_all($data['display_order']));

			$sql = "insert into ".PREFIX."contactus (region, address, display_order) values ('".$region."', '".$address."', '".$display_order."')";

			return $this->query($sql);

		}

		function updateContactUs($data){
			$allowTags = "<strong><em><b><p><u><ul><li><ol><s><sub><sup><h1><img><h2><h3><h4><h5><h6><div><i><span><br><table><tr><th><td><thead><tbody><a>";
			$id = $this->escape_string($this->strip_all($data['id']));
			$region = $this->escape_string($this->strip_all($data['region']));
			$address = $this->escape_string($this->strip_selected($data['address'], $allowTags));
			$display_order = $this->escape_string($this->strip_all($data['display_order']));

			$sql = "update ".PREFIX."contactus set region = '".$region."', address = '".$address."', display_order = '".$display_order."' where id = '".$id."' ";

			return $this->query($sql);
		}

		//Banner Functions

		// === BANNER STARTS ===
		function getAllBanners() {
			$query = "select * from ".PREFIX."banner_master";
			$sql = $this->query($query);
			return $sql;
		}

		function getUniqueBannerById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."banner_master where id='$id'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		function addBanner($data,$file) {
			
			
			$link = $this->escape_string($this->strip_all($data['link']));
			$active = $this->escape_string($this->strip_all($data['active']));
			$display_order = $this->escape_string($this->strip_all($data['display_order']));

			$sql = $this->query("select * from ".PREFIX."banner_master where display_order >= '".$display_order."' and display_order != '' order by display_order asc");
			$new_ordering	= $display_order;
			if($this->num_rows($sql)){
				while($row = $this->fetch($sql)){
					$new_ordering++;
					$this->query("update ".PREFIX."banner_master set display_order='".$new_ordering."' where id='".$row['id']."'");
				}
			}

			$date = date("Y-m-d H:i:s");
			$SaveImage = new SaveImage();
			$imgDir = '../images/banner/';
			if(isset($file['banner_img']['name']) && !empty($file['banner_img']['name'])){
				$banner_img = str_replace( " ", "-", $file['banner_img']['name'] );
				$file_name = strtolower( pathinfo($banner_img, PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$cropData = $this->strip_all($data['cropData1']);
				$banner_img = $SaveImage->uploadCroppedImageFileFromForm($file['banner_img'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
			} else {
				$banner_img = '';
			}

			$query = "insert into ".PREFIX."banner_master (image_name , link, active, display_order) values ('".$banner_img."',  '".$link."', '".$active."', '".$display_order."')";
			return $this->query($query);
		}
		
		function updateBanner($data,$file) {
			
			$id = $this->escape_string($this->strip_all($data['id']));
			$active = $this->escape_string($this->strip_all($data['active']));
			$link = $this->escape_string($this->strip_all($data['link']));
			$display_order = $this->escape_string($this->strip_all($data['display_order']));

			$SaveImage = new SaveImage();
			$imgDir = '../images/banner/';
			
			$Detail = $this->getUniqueBannerById($id);

			if($Detail['display_order'] > $display_order){
				$new_venue_ordering = $display_order;

				$this->query("update ".PREFIX."banner_master set display_order = (display_order + 1) where display_order >= '".$display_order."' and display_order < '".$Detail['display_order']."' and display_order != '' order by display_order asc");
				
				$this->query("update ".PREFIX."banner_master set display_order = '".$display_order."' where id = '".$id."' ");

			}else if($Detail['display_order'] < $display_order){
				$new_venue_ordering = $Detail['display_order'];

				$this->query("update ".PREFIX."banner_master set display_order = (display_order - 1) where display_order > '".$Detail['display_order']."' and display_order <= '".$display_order."' and display_order != '' order by display_order asc");

				$this->query("update ".PREFIX."banner_master set display_order = '".$display_order."' where id = '".$id."' ");
			}

			if(isset($file['banner_img']['name']) && !empty($file['banner_img']['name'])) {
				$banner_img = str_replace( " ", "-", $file['banner_img']['name'] );
				$file_name = strtolower( pathinfo($banner_img, PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				
				$cropData = $this->strip_all($data['cropData1']);
				$this->unlinkImage("banner", $Detail['image_name'], "large");
				$this->unlinkImage("banner", $Detail['image_name'], "crop");
				$banner_img = $SaveImage->uploadCroppedImageFileFromForm($file['banner_img'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
				$this->query("update ".PREFIX."banner_master set image_name='".$banner_img."' where id='$id'");
			}
			$query = "update ".PREFIX."banner_master set active='".$active."', link='".$link."', display_order='".$display_order."' where id='$id'";
			return $this->query($query);
		}

		function deleteBanner($id) {
			$id = $this->escape_string($this->strip_all($id));
			$Detail = $this->getUniqueBannerById($id);
			$this->unlinkImage("banner", $Detail['image_name'], "large");
			$this->unlinkImage("banner", $Detail['image_name'], "crop");
			$query = "delete from ".PREFIX."banner_master where id='$id'";
			$this->query($query);
			return true;
		}

		//Home page content functions

		


		
		
		//Product Master Functions

		function getUniqueProductById($id){
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."product_master where id = '".$id."' ";
			$sql = $this->query($query);
			return $this->fetch($sql);
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
			echo "<pre>";
			print_r($data); 
			exit;
			$product_name = $this->escape_string($this->strip_all($data['product_name']));
			$product_code = $this->escape_string($this->strip_all($data['product_code']));
			$hsn_code = $this->escape_string($this->strip_all($data['hsn_code']));
			$availability = $this->escape_string($this->strip_all($data['availability']));
			// $Subsub_category = $this->escape_string($this->strip_all($data['Subsub_category']));

			$date = date('Ymdhis');
			$permalink = $this->getValidatedPermalink($product_name);
			$permalink = $permalink."/".$date;
			$price = $this->escape_string($this->strip_all($data['price']));
			$b2b_price = $this->escape_string($this->strip_all($data['b2b_price']));
			$discount_price = $this->escape_string($this->strip_all($data['discount_price']));
			$b2b_discount_price = $this->escape_string($this->strip_all($data['b2b_discount_price']));
			// if(empty($discount_price)){
			// 	$discount_price = 0;
			// }
			// if(empty($b2b_discount_price)){
			// 	$b2b_discount_price = 0;
			// }
			$b2b_min_qty = $this->escape_string($this->strip_all($data['b2b_min_qty']));
			$tax = $this->escape_string($this->strip_all($data['tax']));
			$feature_product = $this->escape_string($this->strip_all($data['feature_product']));
			
			$category = 0;
			
			// $sub_cat = $this->escape_string($this->strip_all($data['sub_cat']));
			// $Subsub_category = $this->escape_string($this->strip_all($data['Subsub_category']));
			// $Subsubsub_category = $this->escape_string($this->strip_all($data['Subsubsub_category']));
			// $Subsubsubsub_category = $this->escape_string($this->strip_all($data['Subsubsubsub_category']));
			// $brand = $this->escape_string($this->strip_all($data['brand']));
			
			$tax = $this->escape_string($this->strip_all($data['tax']));
			$description =	$data['description'];
			$short_description =	$data['short_description'];
			$active = $this->escape_string($this->strip_all($data['active']));
			$is_feature = $this->escape_string($this->strip_all($data['is_feature']));

			$meta_title = $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keyword = $this->escape_string($this->strip_all($data['meta_keyword']));
			$meta_description = $this->escape_string($this->strip_all($data['meta_description']));
			$best_seller = $this->escape_string($this->strip_all($data['best_seller']));
			

			$SaveImage = new SaveImage();
			$imgDir = '../images/products/';
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

			$vidDir = "../videos/products/";
			if(isset($file['video_one']['name']) && !empty($file['video_one']['name'])){
				$file_name = strtolower( pathinfo($file['video_one']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				// $cropData = $this->strip_all($data['cropData5']);
	            $allowed_ext = array('video/mp4','video/avi','video/mov');
	           	$file_name         = 'hjp'.md5('hindcojobportalresumefile'.time().rand(0, 100));
	           	$video_one       = $SaveImage->uploadFileFromForm($file['video_one'], $vidDir, $file_name, $allowed_ext);
			} else {
				$video_one = '';
			}

			if(isset($file['video_two']['name']) && !empty($file['video_two']['name'])){
				$file_name = strtolower( pathinfo($file['video_two']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				// $cropData = $this->strip_all($data['cropData5']);
	            $allowed_ext = array('video/mp4','video/avi','video/mov');
	           	$file_name         = 'hjp'.md5('hindcojobportalresumefile'.time().rand(0, 100));
	           	$video_two       = $SaveImage->uploadFileFromForm($file['video_two'], $vidDir, $file_name, $allowed_ext);
			} else {
				$video_two = '';
			}

			if(isset($file['video_three']['name']) && !empty($file['video_three']['name'])){
				$file_name = strtolower( pathinfo($file['video_three']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				// $cropData = $this->strip_all($data['cropData5']);
	            $allowed_ext = array('video/mp4','video/avi','video/mov');
	           	$file_name         = 'hjp'.md5('hindcojobportalresumefile'.time().rand(0, 100));
	           	$video_three       = $SaveImage->uploadFileFromForm($file['video_three'], $vidDir, $file_name, $allowed_ext);
			} else {
				$video_three = '';
			}

			if(isset($file['video_four']['name']) && !empty($file['video_four']['name'])){
				$file_name = strtolower( pathinfo($file['video_four']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				// $cropData = $this->strip_all($data['cropData5']);
	            $allowed_ext = array('video/mp4','video/avi','video/mov');
	           	$file_name         = 'hjp'.md5('hindcojobportalresumefile'.time().rand(0, 100));
	           	$video_four       = $SaveImage->uploadFileFromForm($file['video_four'], $vidDir, $file_name, $allowed_ext);
			} else {
				$video_four = '';
			}

			if(isset($file['video_five']['name']) && !empty($file['video_five']['name'])){
				$file_name = strtolower( pathinfo($file['video_five']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				// $cropData = $this->strip_all($data['cropData5']);
	            $allowed_ext = array('video/mp4','video/avi','video/mov');
	           	$file_name         = 'hjp'.md5('hindcojobportalresumefile'.time().rand(0, 100));
	           	$video_five       = $SaveImage->uploadFileFromForm($file['video_five'], $vidDir, $file_name, $allowed_ext);
			} else {
				$video_five = '';
			}
			
			// $image_five ='';
			
			$createdDate = date('Y-m-d H:i:s');
			$sql = "INSERT INTO ".PREFIX."product_master(`product_name`, `product_code`, `hsn_code`, `availability`, `main_image`, `image_one`, `image_two`, `image_three`, `image_four`, `video_one`, `video_two`, `video_three`, `video_four`, `video_five`, `price`, `discount_price`, `b2b_price`, `b2b_discount_price`, `b2b_min_qty`, `tax`, `description`, `short_description`, `active`, `meta_title`, `meta_keyword`, `meta_description`, `permalink`, `time`, `feature_product`, is_feature, best_seller, created) VALUES ('".$product_name."','".$product_code."','".$hsn_code."','".$availability."','".$main_image."','".$image_one."','".$image_two."','".$image_three."','".$image_four."','".$video_one."','".$video_two."','".$video_three."','".$video_four."','".$video_five."','".$price."','".$discount_price."','".$b2b_price."','".$b2b_discount_price."','".$b2b_min_qty."','".$tax."','".$description."','".$short_description."','".$active."','".$meta_title."','".$meta_keyword."','".$meta_description."','".$permalink."' ,'".$date."','".$feature_product."', '".$is_feature."', '".$best_seller."', '".$createdDate."')";
			// echo $sql; exit;
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
			if(isset($data['Subsubsub_category']) && sizeof($data['Subsubsub_category'])>0){
				foreach ($data['Subsubsub_category'] as $subsubsubcategory_id) {
					$subsubcategory_id = $this->escape_string($this->strip_all($subsubsubcategory_id));
					$addSubsubsubCat = "INSERT INTO ".PREFIX."product_subsubsubcategory_mapping(`subsubsubcategory_id`, `product_id`) VALUES ('".$subsubcategory_id ."','".$product_id."')";
					$this->query($addSubsubsubCat);
				}
			}
			if(isset($data['Subsubsubsub_category']) && sizeof($data['Subsubsubsub_category'])>0){
				foreach ($data['Subsubsubsub_category'] as $subsubsubsubcategory_id) {
					$subsubsubsubcategory_id = $this->escape_string($this->strip_all($subsubsubsubcategory_id));
					$addSubsubsubsubCat = "INSERT INTO ".PREFIX."product_subsubsubsubcategory_mapping(`subsubsubsubcategory_id`, `product_id`) VALUES ('".$subsubsubsubcategory_id ."','".$product_id."')";
					$this->query($addSubsubsubsubCat);
				}
			}

			if(sizeof($data['recommended_product'])>0) {
				foreach($data['recommended_product'] as $key=>$value) {
					$related_products = $this->escape_string($this->strip_all($data['recommended_product'][$key]));
					$this->query("insert into ".PREFIX."products_related_products(product_id, related_product_id) values ('$product_id', '$related_products')");
				}
			}
			
			foreach($data['filter_name'] as $key=>$value) {
				$filter_name = $this->escape_string($this->strip_all($data['filter_name'][$key]));
				$filter_value = $this->escape_string($this->strip_all($data['filter_value'][$key]));
				$this->query("insert into ".PREFIX."product_attributes (product_id, attribute_feature_id) values ('$product_id', '$filter_value')");
			}

		}

		function updateProduct($data, $file){
					// echo "<pre>";print_r($data);print_r($file);die();
			$id = $this->escape_string($this->strip_all($data['id']));
			$product_name = $this->escape_string($this->strip_all($data['product_name']));
			$product_code = $this->escape_string($this->strip_all($data['product_code']));
			$hsn_code = $this->escape_string($this->strip_all($data['hsn_code']));
			$availability = $this->escape_string($this->strip_all($data['availability']));
			$date = date('Ymdhis');
		
			$price = $this->escape_string($this->strip_all($data['price']));
			$b2b_price = $this->escape_string($this->strip_all($data['b2b_price']));
			$discount_price = $this->escape_string($this->strip_all($data['discount_price']));
			$b2b_discount_price = $this->escape_string($this->strip_all($data['b2b_discount_price']));
			// if(empty($discount_price)){
			// 	$discount_price = 0;
			// }
			// if(empty($b2b_discount_price)){
			// 	$b2b_discount_price = 0;
			// }
			$b2b_min_qty = $this->escape_string($this->strip_all($data['b2b_min_qty']));
			$feature_product = $this->escape_string($this->strip_all($data['feature_product']));
			
			$category = '0';
			
			// $sub_cat = $this->escape_string($this->strip_all($data['sub_cat']));
			// $brand = $this->escape_string($this->strip_all($data['brand']));
			
			$tax = $this->escape_string($this->strip_all($data['tax']));
			$description =	$data['description'];
			$short_description =	$data['short_description'];
			$active = $this->escape_string($this->strip_all($data['active']));

			$meta_title = $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keyword = $this->escape_string($this->strip_all($data['meta_keyword']));
			$meta_description = $this->escape_string($this->strip_all($data['meta_description']));

			$SaveImage = new SaveImage();
			$imgDir = '../images/products/';
			
			$image_five ='';
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

			$vidDir = "../videos/products/";
			if(isset($file['video_one']['name']) && !empty($file['video_one']['name'])) {
				$file_name = strtolower( pathinfo($file['image_four']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$allowed_ext = array('video/mp4','video/avi','video/mov');
				$file_name         = 'hjp'.md5('hindcojobportalresumefile'.time().rand(0, 100));
				$this->unlinkImage($vidDir, $Detail['video_one']);
				$video_one       = $SaveImage->uploadFileFromForm($file['video_one'], $vidDir, $file_name, $allowed_ext);
				$this->query("update ".PREFIX."product_master set video_one='$video_one' where id='$id'");
			}
			if(isset($file['video_two']['name']) && !empty($file['video_two']['name'])) {
				$file_name = strtolower( pathinfo($file['image_four']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$allowed_ext = array('video/mp4','video/avi','video/mov');
				$file_name         = 'hjp'.md5('hindcojobportalresumefile'.time().rand(0, 100));
				$this->unlinkImage($vidDir, $Detail['video_two']);
				$video_two       = $SaveImage->uploadFileFromForm($file['video_two'], $vidDir, $file_name, $allowed_ext);
				$this->query("update ".PREFIX."product_master set video_two='$video_two' where id='$id'");
			}
			if(isset($file['video_three']['name']) && !empty($file['video_three']['name'])) {
				$file_name = strtolower( pathinfo($file['image_four']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$allowed_ext = array('video/mp4','video/avi','video/mov');
				$file_name         = 'hjp'.md5('hindcojobportalresumefile'.time().rand(0, 100));
				$this->unlinkImage($vidDir, $Detail['video_three']);
				$video_three       = $SaveImage->uploadFileFromForm($file['video_three'], $vidDir, $file_name, $allowed_ext);
				$this->query("update ".PREFIX."product_master set video_three='$video_three' where id='$id'");
			}
			if(isset($file['video_four']['name']) && !empty($file['video_four']['name'])) {
				$file_name = strtolower( pathinfo($file['image_four']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$allowed_ext = array('video/mp4','video/avi','video/mov');
				$file_name         = 'hjp'.md5('hindcojobportalresumefile'.time().rand(0, 100));
				$this->unlinkImage($vidDir, $Detail['video_four']);
				$video_four       = $SaveImage->uploadFileFromForm($file['video_four'], $vidDir, $file_name, $allowed_ext);
				$this->query("update ".PREFIX."product_master set video_four='$video_four' where id='$id'");
			}
			if(isset($file['video_five']['name']) && !empty($file['video_five']['name'])) {
				$file_name = strtolower( pathinfo($file['image_four']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$allowed_ext = array('video/mp4','video/avi','video/mov');
				$file_name         = 'hjp'.md5('hindcojobportalresumefile'.time().rand(0, 100));
				$this->unlinkImage($vidDir, $Detail['video_five']);
				$video_five       = $SaveImage->uploadFileFromForm($file['video_five'], $vidDir, $file_name, $allowed_ext);
				$this->query("update ".PREFIX."product_master set video_five='$video_five' where id='$id'");
			}
			
			// $feature_product = $this->escape_string($this->strip_all($data['feature_product']));
			// $best_product = $this->escape_string($this->strip_all($data['best_product']));
			// $upcoming_product = $this->escape_string($this->strip_all($data['upcoming_product']));
			
			// $Subsub_category = $this->escape_string($this->strip_all($data['Subsub_category']));
			$is_feature = $this->escape_string($this->strip_all($data['is_feature']));
			$best_seller = $this->escape_string($this->strip_all($data['best_seller']));

			$sql = "UPDATE ".PREFIX."product_master SET `product_name`='".$product_name."',`product_code`='".$product_code."',`hsn_code`='".$hsn_code."',`availability`='".$availability."',`price`='".$price."',`discount_price`='".$discount_price."',`b2b_price`='".$b2b_price."',`b2b_discount_price`='".$b2b_discount_price."',`b2b_min_qty`='".$b2b_min_qty."',`tax`='".$tax."',`description`='".$description."',`short_description`='".$short_description."',`active`='".$active."',`meta_title`='".$meta_title."',`meta_keyword`='".$meta_keyword."',`meta_description`='".$meta_description."',`permalink`='".$permalink."',`time`='".$time."',`feature_product`='".$feature_product."', best_seller='".$best_seller."', is_feature='".$is_feature."' WHERE id='".$id."'";
			// echo "<pre>".$sql; exit;
			$this->query($sql);
			//print_r($_POST); exit;
			$this->deletefiltersProductId($id);
			if(isset($data['filter_name']) && !empty($data['filter_name'])){
				foreach($data['filter_name'] as $key=>$value) {
					$filter_name = $this->escape_string($this->strip_all($data['filter_name'][$key]));
					$filter_value = $this->escape_string($this->strip_all($data['filter_value'][$key]));
					$this->query("insert into ".PREFIX."product_attributes (product_id, attribute_feature_id) values ('$id', '$filter_value')");
				}
			}

			$this->deleteRelatedProductsByProductId($id);
			if(!empty($data['recommended_product'])){
				foreach($data['recommended_product'] as $key=>$value) {
					$related_products = $this->escape_string($this->strip_all($data['recommended_product'][$key]));
					$this->query("insert into ".PREFIX."products_related_products(product_id, related_product_id) values ('$id', '$related_products')");
				}
			}
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

			$this->deleSubSubSubCategoryByProductId($id);
			
			if(isset($data['Subsubsub_category']) && sizeof($data['Subsubsub_category'])>0){
				foreach ($data['Subsubsub_category'] as $subsubsubcategory_id) {
					$subsubsubcategory_id = $this->escape_string($this->strip_all($subsubsubcategory_id));
					$addSubsubsubCat = "INSERT INTO ".PREFIX."product_subsubsubcategory_mapping(`subsubsubcategory_id`, `product_id`) VALUES ('".$subsubsubcategory_id ."','".$id."')";
					$this->query($addSubsubsubCat);
				}
			}

			$this->deleSubSubSubSubCategoryByProductId($id);
			
			if(isset($data['Subsubsubsub_category']) && sizeof($data['Subsubsubsub_category'])>0){
				foreach ($data['Subsubsubsub_category'] as $subsubsubsubcategory_id) {
					$subsubsubsubcategory_id = $this->escape_string($this->strip_all($subsubsubsubcategory_id));
					$addSubsubsubsubsubCat = "INSERT INTO ".PREFIX."product_subsubsubsubcategory_mapping(`subsubsubsubcategory_id`, `product_id`) VALUES ('".$subsubsubsubcategory_id ."','".$id."')";
					$this->query($addSubsubsubsubsubCat);
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
		function deleSubSubSubCategoryByProductId($product_id){
			$product_id = $this->escape_string($this->strip_all($product_id));
			$sql = "DELETE FROM ".PREFIX."product_subsubsubcategory_mapping WHERE `product_id`='".$product_id."'";
			$this->query($sql);
		}
		function deleSubSubSubSubCategoryByProductId($product_id){
			$product_id = $this->escape_string($this->strip_all($product_id));
			$sql = "DELETE FROM ".PREFIX."product_subsubsubsubcategory_mapping WHERE `product_id`='".$product_id."'";
			$this->query($sql);
		}
		function deleteRelatedProductsByProductId($product_id) {
			$product_id = $this->escape_string($this->strip_all($product_id));
			$this->query("delete from ".PREFIX."products_related_products where product_id='$product_id'");
		}
		
		function deleteAllProductMappingbyProeductID($productId){
			$productId = $this->escape_string($this->strip_all($productId));
			
			$sql = "DELETE FROM ".PREFIX."product_category WHERE `product_id`='".$productId."'";
			$this->query($sql);

			$sql = "DELETE FROM ".PREFIX."products_related_products WHERE `product_id`='".$productId."'";
			$this->query($sql);

			$sql = "DELETE FROM ".PREFIX."product_attributes WHERE `product_id`='".$productId."'";
			$this->query($sql);
		}
		function deletefiltersProductId($productId){
			$productId = $this->escape_string($this->strip_all($productId));
			$sql = "DELETE FROM ".PREFIX."product_attributes WHERE `product_id`='".$productId."'";
			//echo $sql;
			$this->query($sql);
		}
		function deleteProductCategoryMapping($productId){
			$productId = $this->escape_string($this->strip_all($productId));
			$sql = "DELETE FROM ".PREFIX."product_category WHERE `product_id`='".$productId."'";
			///echo $sql; exit;
			$this->query($sql);
		}
		function getHamperCategorybyProductId($product_id){
			$product_id = $this->escape_string($this->strip_all($product_id));	
			$hamperCatArr = array();
			
			$hamperData = $this->query("SELECT * FROM ".PREFIX."hamper_category_product_mapping WHERE `product_id`='".$product_id."'");
			while($gethamer = $this->fetch($hamperData)){
				//print_r($gethamer);
				$sql = "SELECT * FROM ".PREFIX."hamper_category_master WHERE `id`='".$gethamer['hamer_category_id']."'";
				//echo $sql;
				$hamerData = $this->query($sql);
				while($hamerDetails = $this->fetch($hamerData)){
					$hamperCatArr[] = $hamerDetails['id'];
				}

			}
			return $hamperCatArr;
		}

		// === UPDATE AND ADD BANNER ===
		function updateBannerById(){
			
		}
		// === UPDATE AND ADD BANNER :: END ===
		
		// === DISCOUNT COUPON STARTS ===
		function getAllDiscountCoupons() {
			$query = "select * from ".PREFIX."discount_coupon_master";
			$sql = $this->query($query);
			return $sql;
		}

		function getUniqueDiscountCouponById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."discount_coupon_master where id='$id'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		function addDiscountCoupon($data) {
			$coupon_code = trim($this->escape_string($this->strip_all($data['coupon_code'])));
			$coupon_type = $this->escape_string($this->strip_all($data['coupon_type']));
			$coupon_value = $this->escape_string($this->strip_all($data['coupon_value']));
			$minimum_purchase_amount = $this->escape_string($this->strip_all($data['minimum_purchase_amount']));
			$valid_from = $this->escape_string($this->strip_all($data['valid_from']));
			$valid_to = $this->escape_string($this->strip_all($data['valid_to']));
			$coupon_usage = $this->escape_string($this->strip_all($data['coupon_usage']));
			$coupon_apply = implode(",",$data['coupon_apply']);
			$active = $this->escape_string($this->strip_all($data['active']));
			
			$query = "insert into ".PREFIX."discount_coupon_master (coupon_code, coupon_type, coupon_value, valid_from, valid_to, coupon_usage, minimum_purchase_amount, coupon_apply, active) values ('$coupon_code', '$coupon_type', '$coupon_value', '$valid_from', '$valid_to', '$coupon_usage', '$minimum_purchase_amount', '$coupon_apply', '$active')";
			$this->query($query); 
			$couponId = $this->last_insert_id();

			return true;
		}
		function chkcouponAlreadyExists($product_id,$coupon_id){
			$product_id = $this->escape_string($this->strip_all($product_id));
			$coupon_id = $this->escape_string($this->strip_all($coupon_id));
			$chk= "SELECT * FROM ".PREFIX."products_discount_coupons WHERE product_id='".$product_id."' and coupon_id='".$coupon_id."'";
			//echo $chk."<br>"; 
			$result = $this->query($chk);
			return $this->num_rows($result); 
		}
		function updateDiscountCoupon($data) {
			$coupon_code = trim($this->escape_string($this->strip_all($data['coupon_code'])));
			$coupon_type = $this->escape_string($this->strip_all($data['coupon_type']));
			$coupon_value = $this->escape_string($this->strip_all($data['coupon_value']));
			$valid_from = $this->escape_string($this->strip_all($data['valid_from']));
			$valid_to = $this->escape_string($this->strip_all($data['valid_to']));
			$coupon_usage = $this->escape_string($this->strip_all($data['coupon_usage']));
			$coupon_apply = implode(",",$data['coupon_apply']);
			$active = $this->escape_string($this->strip_all($data['active']));
			$id = $this->escape_string($this->strip_all($data['id']));
			$minimum_purchase_amount = $this->escape_string($this->strip_all($data['minimum_purchase_amount']));

			$query = "update ".PREFIX."discount_coupon_master set coupon_code='$coupon_code', coupon_type='$coupon_type', coupon_value='$coupon_value', valid_from='$valid_from', valid_to='$valid_to', coupon_usage='$coupon_usage', minimum_purchase_amount='$minimum_purchase_amount', coupon_apply='$coupon_apply', active='$active' where id='$id'";
			$this->query($query);
		}

		function deleteDiscountCoupon($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "delete from ".PREFIX."discount_coupon_master where id='$id'";
			$this->query($query);
			return true;
		}
		
		// === DISCOUNT COUPON ENDS ===
		/*============================ category master ===============================*/

			// === CATEGORY STARTS ===
		function getUniqueCategoryById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."category_master where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function getAllCategories() {
			$query = "select * from ".PREFIX."category_master where active='Yes'";
			$sql = $this->query($query);
			return $sql;
		}
		function getcateogrybyId($id){
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."category_master where id='".$id."'";
			//echo $query; exit;
			return $this->query($query);
		}
		function addCategory($data,$file){
			
			$category_name = $this->escape_string($this->strip_all($data['category_name']));
			$active = $this->escape_string($this->strip_all($data['active']));
			$display_order = $this->escape_string($this->strip_all($data['display_order']));			
			$catPermalink = $this->getValidatedPermalink($category_name);

			// SEO details

			$meta_title = $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keyword = $this->escape_string($this->strip_all($data['meta_keyword']));
			$meta_description = $this->escape_string($this->strip_all($data['meta_description']));

			$SaveImage = new SaveImage();
			$imgDir = '../images/category/';

			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])){
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$cropData = $this->strip_all($data['cropData1']);
				
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
			} else {
				$banner_image = '';
			}

			if(isset($file['mobile_image']['name']) && !empty($file['mobile_image']['name'])){
				$file_name = strtolower( pathinfo($file['mobile_image']['name'], PATHINFO_FILENAME));
				$cropData = $this->strip_all($data['cropData2']);
				
				$mobile_image = $SaveImage->uploadCroppedImageFileFromForm($file['mobile_image'], 400, $cropData, $imgDir, $file_name.'-'.time().'-2');
			} else {
				$mobile_image = '';
			}

			$query = "insert into ".PREFIX."category_master(category_name, active, display_order, banner_image, mobile_image, permalink , meta_title, meta_keyword, meta_description) values ('".$category_name."', '".$active."', '".$display_order."','".$banner_image."', '".$mobile_image."','".$catPermalink."','".$meta_title."' ,'".$meta_keyword."' ,'".$meta_description."')";
			$this->query($query);
		
		}
		function updateCategory($data,$file) {
			
			$id = $this->escape_string($this->strip_all($data['id']));
			$category_name = $this->escape_string($this->strip_all($data['category_name']));
			$active = $this->escape_string($this->strip_all($data['active']));
			// $display_order = $this->escape_string($this->strip_all($data['display_order']));

			$meta_title = $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keyword = $this->escape_string($this->strip_all($data['meta_keyword']));
			$meta_description = $this->escape_string($this->strip_all($data['meta_description']));

			$catPermalink = $this->getValidatedPermalink($category_name);
			$SaveImage = new SaveImage();
			$imgDir = '../images/category/';
			
			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])) {
				

				$Detail = $this->getCatByID($id);
				$cropData = $this->strip_all($data['cropData1']);
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("category", $Detail['banner_image'], "large");
				$this->unlinkImage("category", $Detail['banner_image'], "crop");
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
				$sql="update ".PREFIX."category_master set banner_image='$banner_image' where id='$id'";
				//echo $sql; 
				$this->query($sql);
			}
			if(isset($file['mobile_image']['name']) && !empty($file['mobile_image']['name'])) {
				

				$Detail = $this->getCatByID($id);
				$cropData = $this->strip_all($data['cropData2']);
				$file_name = strtolower( pathinfo($file['mobile_image']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("category", $Detail['mobile_image'], "large");
				$this->unlinkImage("category", $Detail['mobile_image'], "crop");
				$mobile_image = $SaveImage->uploadCroppedImageFileFromForm($file['mobile_image'], 400, $cropData, $imgDir, $file_name.'-'.time().'-2');
				$sql="update ".PREFIX."category_master set mobile_image='$mobile_image' where id='$id'";
				// echo $sql;die(); 
				$this->query($sql);
			}

			$query = "update ".PREFIX."category_master set permalink='".$catPermalink."', category_name='".$category_name."', active='".$active."', display_order='".$display_order."',meta_title='".$meta_title."', meta_keyword='".$meta_keyword."',meta_description='".$meta_description."'  where id='".$id."'";
			$this->query($query);

			$category_id = $id;

		}	
		function getCatByID($id){
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."category_master where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		
		
		/*============================ category master::end ===============================*/

		/*===================== SUB CATEGORY BEGINS =====================*/

		/** * Function to get details of all sub categories */
		function getAllSubCategories() {
			$query = "select * from ".PREFIX."sub_category_master";
			$sql = $this->query($query);
			return $sql;
		}

		/** * Function to get details of all sub categories by category id */
		function getAllSubCategoriesByCategoryId($category_id) {
			$category_id = $this->escape_string($this->strip_all($category_id));
			$query = "select * from ".PREFIX."sub_category_master where category_id in (".$category_id.")";
			$sql = $this->query($query);
			return $sql;
		}

		/** * Function to get single sub category details by id */
		function getUniqueSubCategoryById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."sub_category_master where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		/** * Function to get single sub sub category details by id */
		function getuniqueSusuCategory($id){
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."subsubCategory where id='".$id."'"));	
		}

		/** * Function to get single sub sub sub category details by id */
		function getuniqueSubsubsubCategory($id){
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."subsubsubCategory where id='".$id."'"));	
		}

		/** * Function to get single sub sub sub sub category details by id */
		function getuniqueSubsubsubsubCategory($id){
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."subsubsubsubCategory where id='".$id."'"));	
		}

		/** * Function to get single sub category details by permalink */
		function getUniqueSubCategoryByPermalink($sub_category_permalink) {
			$sub_category_permalink = $this->escape_string($this->strip_all($sub_category_permalink));
			$query = "select * from ".PREFIX."sub_category_master where sub_category_permalink='".$sub_category_permalink."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		/** * Function to add sub category details */
		function addSubCategory($data) {
			$category_id		= $this->escape_string($this->strip_all($data['category_id']));
			$sub_category_name 	= $this->escape_string($this->strip_all($data['sub_category_name']));
			$active 			= $this->escape_string($this->strip_all($data['active']));
			$meta_title = $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keywords = $this->escape_string($this->strip_all($data['meta_keywords']));
			$og_description = $data['og_description'];
			// $description 		= $data['description'];
			
			$sub_category_permalink	= $this->getValidatedPermalink($sub_category_name);

			$SaveImage = new SaveImage();
			$imgDir = '../images/sub_category/';

			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])){
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$cropData = $this->strip_all($data['cropData1']);
				
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
			} else {
				$banner_image = '';
			}

			if(isset($file['mobile_image']['name']) && !empty($file['mobile_image']['name'])){
				$file_name = strtolower( pathinfo($file['mobile_image']['name'], PATHINFO_FILENAME));
				$cropData = $this->strip_all($data['cropData2']);
				
				$mobile_image = $SaveImage->uploadCroppedImageFileFromForm($file['mobile_image'], 400, $cropData, $imgDir, $file_name.'-'.time().'-2');
			} else {
				$mobile_image = '';
			}
			$date = date("Y-m-d h:i:s");
			$query = "insert into ".PREFIX."sub_category_master(category_id, sub_category_name, sub_category_permalink, banner_image, mobile_image, active, created, meta_title, meta_keywords, og_description) values ('".$category_id."', '".$sub_category_name."', '".$sub_category_permalink."', '".$banner_image."', '".$mobile_image."', '".$active."', '".$date."', '".$meta_title."', '".$meta_keywords."', '".$og_description."')";
			return $this->query($query);
		}

		/** * Function to update sub category details */
		function updateSubCategory($data,$file){
			$id 				= $this->escape_string($this->strip_all($data['id']));
			$category_id		= $this->escape_string($this->strip_all($data['category_id']));
			$sub_category_name 	= $this->escape_string($this->strip_all($data['sub_category_name']));
			$active 			= $this->escape_string($this->strip_all($data['active']));
			$meta_title = $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keywords = $this->escape_string($this->strip_all($data['meta_keywords']));
			$og_description = $this->escape_string($this->strip_all($data['og_description']));
			// $description 		= $data['description'];
			// print_r($data);die();
			$sub_category_permalink	= $this->getValidatedPermalink($sub_category_name);
			$SaveImage = new SaveImage();
			$imgDir = '../images/sub_category/';
			
			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])) {
				

				$Detail = $this->getSubCatByID($id);
				$cropData = $this->strip_all($data['cropData1']);
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("category", $Detail['banner_image'], "large");
				$this->unlinkImage("category", $Detail['banner_image'], "crop");
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
				$sql="update ".PREFIX."sub_category_master set banner_image='$banner_image' where id='$id'";
				// echo $sql;die(); 
				$this->query($sql);
			}
			if(isset($file['mobile_image']['name']) && !empty($file['mobile_image']['name'])) {
				

				$Detail = $this->getSubCatByID($id);
				$cropData = $this->strip_all($data['cropData2']);
				$file_name = strtolower( pathinfo($file['mobile_image']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("category", $Detail['mobile_image'], "large");
				$this->unlinkImage("category", $Detail['mobile_image'], "crop");
				$mobile_image = $SaveImage->uploadCroppedImageFileFromForm($file['mobile_image'], 400, $cropData, $imgDir, $file_name.'-'.time().'-2');
				$sql="update ".PREFIX."sub_category_master set mobile_image='$mobile_image' where id='$id'";
				// echo $sql;die(); 
				$this->query($sql);
			}
			
			$query = "update ".PREFIX."sub_category_master set category_id = '".$category_id."', sub_category_name = '".$sub_category_name."', sub_category_permalink = '".$sub_category_permalink."', active = '".$active."', meta_title = '".$meta_title."', meta_keywords = '".$meta_keywords."', og_description = '".$og_description."' where id='".$id."'";
			return $this->query($query);
		}
		function getSubCatByID($id){
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."sub_category_master where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		/** * Function to update sub category's active status by id */
		function updateSubCategoryStatus($subCategoryId, $status){
			$id 			= $this->escape_string($this->strip_all($subCategoryId));
			$sub_category_status 	= $this->escape_string($this->strip_all($status));
			
			if($sub_category_status == '1') {
				$sql_query = "update ".PREFIX."sub_category_master set active='0' where id = '".$id."'";
			} else if($sub_category_status == '0') {
				$sql_query = "update ".PREFIX."sub_category_master set active='1' where id = '".$id."'";
			}
			return $this->query($sql_query);
		}

		/** * Function to delete sub category by id */
		function deleteSubCategoryById($id){
			$id  	= $this->escape_string($this->strip_all($id));
			$SUBquery = "DELETE FROM ".PREFIX."subsubCategory WHERE `sub_category_id`='".$id."'";
			$this->query($SUBquery);
			
			$query = "DELETE FROM ".PREFIX."sub_category_master WHERE `id`='".$id."'";
			return $this->query($query);
		}
		/*===================== SUB CATEGORY ENDS =====================*/

		/*===================== Brand Add =========================*/
		function addBrand($data,$file){
			
			$brand_name = $this->escape_string($this->strip_all($data['brand_name']));
			$SaveImage = new SaveImage();
			$imgDir = '../images/brand/';
			$brand_permalink	= $this->getValidatedPermalink($brand_name);

			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])){
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$cropData = $this->strip_all($data['cropData1']);
				
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 150, $cropData, $imgDir, $file_name.'-'.time().'-2');
			} else {
				$banner_image = '';
			}

			$sql = "INSERT INTO ".PREFIX."brand_master(`brand_name`, `image_name`, `permalink`) VALUES ('".$brand_name."','".$banner_image."','".$brand_permalink."')";
			$this->query($sql);
		
		}
		function updateBrand($data,$file) {
			
			$id = $this->escape_string($this->strip_all($data['id']));
			$brand_name = $this->escape_string($this->strip_all($data['brand_name']));
			$brand_permalink	= $this->getValidatedPermalink($brand_name);

			$SaveImage = new SaveImage();
			$imgDir = '../images/brand/';
		
			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])) {
				
				$Detail = $this->getBrandByID($id);
				$cropData = $this->strip_all($data['cropData1']);
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("brand", $Detail['image_name'], "large");
				$this->unlinkImage("brand", $Detail['image_name'], "crop");
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 150, $cropData, $imgDir, $file_name.'-'.time().'-1');
				$sql="update ".PREFIX."brand_master set image_name='$banner_image' where id='$id'";
				//echo  $sql; exit;
				$this->query($sql);
			}

			$query = "update ".PREFIX."brand_master set brand_name='".$brand_name."', permalink='".$brand_permalink."' where id='".$id."'";
			$this->query($query);

		}	
		function getBrandByID($id){
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."brand_master where id='".$id."'";
			//echo $query;
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function GetBannerDataByID($bannerType){
			$bannerType = $this->escape_string($this->strip_all($bannerType));
			$sql = "SELECT * FROM ".PREFIX."web_banner_master WHERE `banner_type`='".$bannerType."'";
			$result = $this->query($sql);
			return $this->fetch($result);		
		}
		function getAllProducts(){
			$sql = "SELECT * FROM ".PREFIX."product_master WHERE `active`='Yes'";
			return $this->query($sql);
		}

		function addsubSubCategory($data,$file){
			$category_name = $this->escape_string($this->strip_all($data['name']));
			$active = $this->escape_string($this->strip_all($data['active']));
			$category_id = $this->escape_string($this->strip_all($data['category_id']));
			$meta_title = $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keywords = $this->escape_string($this->strip_all($data['meta_keywords']));
			$meta_description = $this->escape_string($this->strip_all($data['meta_description']));
			$banner_heading = $this->escape_string($this->strip_all($data['banner_heading']));
			$gender = $this->escape_string($this->strip_all($data['gender']));
			$description = $this->escape_string($this->strip_all($data['description']));
			
			$og_title = $this->escape_string($this->strip_all($data['og_title']));
			$og_keywords = $this->escape_string($this->strip_all($data['og_keywords']));
			$og_description = $this->escape_string($this->strip_all($data['og_description']));
			

			// if(isset($file['image_name']['name']) && !empty($file['image_name']['name'])) {
			// 	$SaveImage = new SaveImage();
			// 	$imgDir = '../images/sub-subcategories/';
			// 	if(isset($file['image_name']['name']) && !empty($file['image_name']['name'])){
			// 		$file_name = strtolower( pathinfo($file['image_name']['name'], PATHINFO_FILENAME));
			// 		$cropData = $this->strip_all($data['cropData1']);
			// 		if($data['category_id'] == "3"){
			// 			$image_name = $SaveImage->uploadCroppedImageFileFromForm($file['image_name'], 220, $cropData, $imgDir, $file_name.'-'.time().'-1');
			// 		}else{
			// 			if($subcatIds == '30'){
			// 				$image_name = $SaveImage->uploadCroppedImageFileFromForm($file['image_name'], 76, $cropData, $imgDir,  $file_name.'-'.time().'-1');	
			// 			}else{
			// 				$image_name = $SaveImage->uploadCroppedImageFileFromForm($file['image_name'], 220, $cropData, $imgDir,  $file_name.'-'.time().'-1');	
			// 			}
			// 		}
			// 	} else {
			// 		$image_name = '';
			// 	}
			// }
			$SaveImage = new SaveImage();
			$imgDir = '../images/subsubcategory/';

			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])){
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$cropData = $this->strip_all($data['cropData1']);
				
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
			} else {
				$banner_image = '';
			}

			if(isset($file['mobile_image']['name']) && !empty($file['mobile_image']['name'])){
				$file_name = strtolower( pathinfo($file['mobile_image']['name'], PATHINFO_FILENAME));
				$cropData = $this->strip_all($data['cropData2']);
				
				$mobile_image = $SaveImage->uploadCroppedImageFileFromForm($file['mobile_image'], 400, $cropData, $imgDir, $file_name.'-'.time().'-2');
			} else {
				$mobile_image = '';
			}

			if(empty($data['permalink'])){
				$permalink = $this->getValidatedPermalink($category_name);
			}else{
				$permalink = $this->getValidatedPermalink($data['permalink']);
			}

			
			$query="INSERT INTO ".PREFIX."subsubCategory(`sub_category_id`,`subcategory_name`,`banner_image`,`mobile_image`,`permalink`,`description`, `meta_title`,`meta_keywords`,`meta_description`,`banner_heading`,`active`,gender,og_title,og_keywords,og_description) VALUES ('".$category_id."','".$category_name."','".$banner_image."','".$mobile_image."','".$permalink."','".$description."','".$meta_title."','".$meta_keywords."','".$meta_description."','".$banner_heading."','".$active."','".$gender."','".$og_title."','".$og_keywords."','".$og_description."')";
			//echo $query;exit;
			$this->query($query);
			return true;
		}
		function updateSubSubCategory($data,$file) {
			
			$id 			= $this->escape_string($this->strip_all($data['id']));
			$category_name 	= $this->escape_string($this->strip_all($data['name']));
			$active 		= $this->escape_string($this->strip_all($data['active']));
			$meta_title 	= $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keywords 	= $this->escape_string($this->strip_all($data['meta_keywords']));
			// $meta_description 	= $this->escape_string($this->strip_all($data['meta_description']));
			// $banner_heading     = $this->escape_string($this->strip_all($data['banner_heading']));
			// $description 		= $this->escape_string($this->strip_all($data['description']));
			// $unlinkimg			= $this->escape_string($this->strip_all($data['unlinkimg']));
			$subcatIds 			= $this->escape_string($this->strip_all($data['subcatIds']));
			if(empty($data['permalink'])){
				$permalink = $this->getValidatedPermalink($category_name);
			}else{
				$permalink = $this->getValidatedPermalink($data['permalink']);
			}
			
			// $og_title = $this->escape_string($this->strip_all($data['og_title']));
			// $og_keywords = $this->escape_string($this->strip_all($data['og_keywords']));
			$og_description = $this->escape_string($this->strip_all($data['og_description']));

			// if(isset($file['image_name']['name']) && !empty($file['image_name']['name'])) {
			// 	$Detail = $this->getUniqueSubCategoryById($id);
			// 	$imgDir = '../images/sub-subcategories/';
			// 	$SaveImage = new SaveImage();
			// 		if(file_exists($imgDir.$unlinkimg)){
			// 			unlink($imgDir.$unlinkimg);
			// 		}
			// 		$cropData = $this->strip_all($data['cropData1']);
			// 		$this->unlinkImage("categories", $Detail['banner_image'], "large");
			// 		$this->unlinkImage("categories", $Detail['banner_image'], "crop");
			// 		$file_name = strtolower( pathinfo($file['image_name']['name'], PATHINFO_FILENAME));
			// 			if($data['category_id'] == "3"){
			// 				$image_name = $SaveImage->uploadCroppedImageFileFromForm($file['image_name'], 220, $cropData, $imgDir,  $file_name.'-'.time().'-1');
			// 			}else{
			// 				if($subcatIds == '30'){
			// 					//echo $subcatIds; exit;
			// 					$image_name = $SaveImage->uploadCroppedImageFileFromForm($file['image_name'], 76, $cropData, $imgDir,  $file_name.'-'.time().'-1');	
			// 				}else{
			// 					//echo $subcatIds; exit;
			// 					$image_name = $SaveImage->uploadCroppedImageFileFromForm($file['image_name'], 220, $cropData, $imgDir,  $file_name.'-'.time().'-1');	
			// 				}
			// 			}
			// 			$this->query("update ".PREFIX."subsubCategory set banner_image='$image_name' where id='$id'");
			// }
			$SaveImage = new SaveImage();
			$imgDir = '../images/subsubcategory/';
			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])) {
				

				$Detail = $this->getSubSubCatByID($id);
				$cropData = $this->strip_all($data['cropData1']);
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("subsubcategory", $Detail['banner_image'], "large");
				$this->unlinkImage("subsubcategory", $Detail['banner_image'], "crop");
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
				$sql="update ".PREFIX."subsubCategory set banner_image='$banner_image' where id='$id'";
				//echo $sql; 
				$this->query($sql);
			}
			if(isset($file['mobile_image']['name']) && !empty($file['mobile_image']['name'])) {
				

				$Detail = $this->getSubSubCatByID($id);
				$cropData = $this->strip_all($data['cropData2']);
				$file_name = strtolower( pathinfo($file['mobile_image']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("subsubcategory", $Detail['mobile_image'], "large");
				$this->unlinkImage("subsubcategory", $Detail['mobile_image'], "crop");
				$mobile_image = $SaveImage->uploadCroppedImageFileFromForm($file['mobile_image'], 400, $cropData, $imgDir, $file_name.'-'.time().'-2');
				$sql="update ".PREFIX."subsubCategory set mobile_image='$mobile_image' where id='$id'";
				// echo $sql;die(); 
				$this->query($sql);
			}

			$query = "update ".PREFIX."subsubCategory set subcategory_name='".$category_name."',permalink='".$permalink."', meta_title='".$meta_title."', meta_keywords='".$meta_keywords."', active='".$active."',og_description='".$og_description."' where id='".$id."'";
			$this->query($query);
		}
		function getSubSubCatByID($id){
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."subsubCategory where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		// sub-sub-subCategory

		function addSubSubSubCategory($data,$file){
			$category_name = $this->escape_string($this->strip_all($data['name']));
			$active = $this->escape_string($this->strip_all($data['active']));
			$category_id = $this->escape_string($this->strip_all($data['subsub_category_id']));
			$meta_title = $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keywords = $this->escape_string($this->strip_all($data['meta_keywords']));
			$meta_description = $this->escape_string($this->strip_all($data['og_description']));
			$created = date("Y-m-d h:i:s");

			if(empty($data['permalink'])){
				$permalink = $this->getValidatedPermalink($category_name);
			}else{
				$permalink = $this->getValidatedPermalink($data['permalink']);
			}

			$SaveImage = new SaveImage();
			$imgDir = '../images/sub_subsubcategory/';

			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])){
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$cropData = $this->strip_all($data['cropData1']);
				
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
			} else {
				$banner_image = '';
			}

			if(isset($file['mobile_image']['name']) && !empty($file['mobile_image']['name'])){
				$file_name = strtolower( pathinfo($file['mobile_image']['name'], PATHINFO_FILENAME));
				$cropData = $this->strip_all($data['cropData2']);
				
				$mobile_image = $SaveImage->uploadCroppedImageFileFromForm($file['mobile_image'], 400, $cropData, $imgDir, $file_name.'-'.time().'-2');
			} else {
				$mobile_image = '';
			}

			
			$query="INSERT INTO ".PREFIX."subsubsubCategory(`subsubcate_id`,`subsubsub_name`,`subsubsub_permalink`, `banner_image`, `mobile_image`, `og_description`, `meta_title`,`meta_keyword`,`active`,`created`) VALUES ('".$category_id."','".$category_name."','".$permalink."','".$banner_image."', '".$mobile_image."','".$meta_description."','".$meta_title."','".$meta_keywords."','".$active."','".$created."')";
			//echo $query;exit;
			$this->query($query);
			return true;
		}
		function updateSubSubSubCategory($data,$file) {
			
			$id 			= $this->escape_string($this->strip_all($data['id']));
			$category_name = $this->escape_string($this->strip_all($data['name']));
			$active = $this->escape_string($this->strip_all($data['active']));
			$category_id = $this->escape_string($this->strip_all($data['subsub_category_id']));
			$meta_title = $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keywords = $this->escape_string($this->strip_all($data['meta_keywords']));
			$meta_description = $this->escape_string($this->strip_all($data['og_description']));
			$created = date("Y-m-d h:i:s");
			
			if(empty($data['permalink'])){
				$permalink = $this->getValidatedPermalink($category_name);
			}else{
				$permalink = $this->getValidatedPermalink($data['permalink']);
			}
			$SaveImage = new SaveImage();
			$imgDir = '../images/sub_subsubcategory/';
			
			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])) {
				

				$Detail = $this->getSubSubSubCatByID($id);
				$cropData = $this->strip_all($data['cropData1']);
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("sub_subsubcategory", $Detail['banner_image'], "large");
				$this->unlinkImage("sub_subsubcategory", $Detail['banner_image'], "crop");
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
				$sql="update ".PREFIX."subsubsubCategory set banner_image='$banner_image' where id='$id'";
				//echo $sql; 
				$this->query($sql);
			}
			if(isset($file['mobile_image']['name']) && !empty($file['mobile_image']['name'])) {
				

				$Detail = $this->getSubSubSubCatByID($id);
				$cropData = $this->strip_all($data['cropData2']);
				$file_name = strtolower( pathinfo($file['mobile_image']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("sub_subsubcategory", $Detail['mobile_image'], "large");
				$this->unlinkImage("sub_subsubcategory", $Detail['mobile_image'], "crop");
				$mobile_image = $SaveImage->uploadCroppedImageFileFromForm($file['mobile_image'], 400, $cropData, $imgDir, $file_name.'-'.time().'-2');
				$sql="update ".PREFIX."subsubsubCategory set mobile_image='$mobile_image' where id='$id'";
				// echo $sql;die(); 
				$this->query($sql);
			}

			$query = "update ".PREFIX."subsubsubCategory set subsubsub_name='".$category_name."',og_description='".$meta_description."',subsubsub_permalink='".$permalink."', meta_title='".$meta_title."', meta_keyword='".$meta_keywords."', active='".$active."', created='".$created."' where id='".$id."'";
			$this->query($query);
		}
		function getSubSubSubCatByID($id){
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."subsubsubCategory where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		function getAllSubSubCategories($Subsubcategory_id) {
			$Subsubcategory_id = $this->escape_string($this->strip_all($Subsubcategory_id));
			$query = "select * from ".PREFIX."subsubCategory where sub_category_id in ($Subsubcategory_id)";
			//echo $query;
			$sql = $this->query($query);
			return $sql;
		}
		function getAllSubSubCategoriesbyProductID($productId,$suCatId) {
			$productId = $this->escape_string($this->strip_all($productId));
			$suCatId = $this->escape_string($this->strip_all($suCatId));
			$query = "SELECT * FROM ".PREFIX."product_subsubcategory_mapping WHERE `product_id`='".$productId."' and subsubcategory_id='".$suCatId."'";
			//echo $query;
			$sql = $this->query($query);
			return $sql;
		}

		// subsubsub category product mapping

		function getAllSubSubSubCategories($Subsubcategory_id) {
			$Subsubcategory_id = $this->escape_string($this->strip_all($Subsubcategory_id));
			$query = "select * from ".PREFIX."subsubsubCategory where subsubcate_id in ($Subsubcategory_id)";
			//echo $query;
			$sql = $this->query($query);
			return $sql;
		}
		function getAllSubSubSubCategoriesbyProductID($productId,$suCatId) {
			$productId = $this->escape_string($this->strip_all($productId));
			$suCatId = $this->escape_string($this->strip_all($suCatId));
			$query = "SELECT * FROM ".PREFIX."product_subsubsubcategory_mapping WHERE  `product_id`='".$productId."' and subsubsubcategory_id='".$suCatId."'";
			//echo $query;
			$sql = $this->query($query);
			return $sql;
		}

		// subsubsubsub category product mapping

		function getAllSubSubSubSubCategories($Subsubcategory_id) {
			$Subsubcategory_id = $this->escape_string($this->strip_all($Subsubcategory_id));
			$query = "select * from ".PREFIX."subsubsubsubCategory where subsubsubcate_id in ($Subsubcategory_id)";
			//echo $query;
			$sql = $this->query($query);
			return $sql;
		}
		function getAllSubSubSubSubCategoriesbyProductID($productId,$suCatId) {
			$productId = $this->escape_string($this->strip_all($productId));
			$suCatId = $this->escape_string($this->strip_all($suCatId));
			$query = "SELECT * FROM ".PREFIX."product_subsubsubsubcategory_mapping WHERE  `product_id`='".$productId."' and subsubsubsubcategory_id='".$suCatId."'";
			//echo $query;
			$sql = $this->query($query);
			return $sql;
		}

		// subsubsubsub-Category

		function addSubSubSubSubCategory($data,$file){
			$category_name = $this->escape_string($this->strip_all($data['name']));
			$active = $this->escape_string($this->strip_all($data['active']));
			$category_id = $this->escape_string($this->strip_all($data['subsubsubcate_id']));
			$meta_title = $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keywords = $this->escape_string($this->strip_all($data['meta_keywords']));
			$meta_description = $this->escape_string($this->strip_all($data['og_description']));
			$created = date("Y-m-d h:i:s");
			
			if(empty($data['permalink'])){
				$permalink = $this->getValidatedPermalink($category_name);
			}else{
				$permalink = $this->getValidatedPermalink($data['permalink']);
			}

			$SaveImage = new SaveImage();
			$imgDir = '../images/subsub_subsubcategory/';

			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])){
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$cropData = $this->strip_all($data['cropData1']);
				
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
			} else {
				$banner_image = '';
			}

			if(isset($file['mobile_image']['name']) && !empty($file['mobile_image']['name'])){
				$file_name = strtolower( pathinfo($file['mobile_image']['name'], PATHINFO_FILENAME));
				$cropData = $this->strip_all($data['cropData2']);
				
				$mobile_image = $SaveImage->uploadCroppedImageFileFromForm($file['mobile_image'], 400, $cropData, $imgDir, $file_name.'-'.time().'-2');
			} else {
				$mobile_image = '';
			}
			
			$query="INSERT INTO ".PREFIX."subsubsubsubCategory(`subsubsubcate_id`,`subsubsubsub_name`,`subsubsubsub_permalink`,`banner_image`, `mobile_image`,`og_description`, `meta_title`,`meta_keyword`,`active`,`created`) VALUES ('".$category_id."','".$category_name."','".$permalink."','".$banner_image."','".$mobile_image."','".$meta_description."','".$meta_title."','".$meta_keywords."','".$active."','".$created."')";
			//echo $query;exit;
			$this->query($query);
			return true;
		}
		function updateSubSubSubSubCategory($data,$file) {
			
			$id 			= $this->escape_string($this->strip_all($data['id']));
			$category_name = $this->escape_string($this->strip_all($data['name']));
			$active = $this->escape_string($this->strip_all($data['active']));
			$category_id = $this->escape_string($this->strip_all($data['subsubsubcate_id']));
			$meta_title = $this->escape_string($this->strip_all($data['meta_title']));
			$meta_keywords = $this->escape_string($this->strip_all($data['meta_keywords']));
			$meta_description = $this->escape_string($this->strip_all($data['og_description']));
			$created = date("Y-m-d h:i:s");
			
			if(empty($data['permalink'])){
				$permalink = $this->getValidatedPermalink($category_name);
			}else{
				$permalink = $this->getValidatedPermalink($data['permalink']);
			}

			$SaveImage = new SaveImage();
			$imgDir = '../images/subsub_subsubcategory/';
			
			if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])) {
				

				$Detail = $this->getSubSubSubSubCatByID($id);
				$cropData = $this->strip_all($data['cropData1']);
				$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("subsub_subsubcategory", $Detail['banner_image'], "large");
				$this->unlinkImage("subsub_subsubcategory", $Detail['banner_image'], "crop");
				$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 1366, $cropData, $imgDir, $file_name.'-'.time().'-1');
				$sql="update ".PREFIX."subsubsubsubCategory set banner_image='$banner_image' where id='$id'";
				//echo $sql; 
				$this->query($sql);
			}
			if(isset($file['mobile_image']['name']) && !empty($file['mobile_image']['name'])) {
				

				$Detail = $this->getSubSubSubSubCatByID($id);
				$cropData = $this->strip_all($data['cropData2']);
				$file_name = strtolower( pathinfo($file['mobile_image']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("subsub_subsubcategory", $Detail['mobile_image'], "large");
				$this->unlinkImage("subsub_subsubcategory", $Detail['mobile_image'], "crop");
				$mobile_image = $SaveImage->uploadCroppedImageFileFromForm($file['mobile_image'], 400, $cropData, $imgDir, $file_name.'-'.time().'-2');
				$sql="update ".PREFIX."subsubsubsubCategory set mobile_image='$mobile_image' where id='$id'";
				// echo $sql;die(); 
				$this->query($sql);
			}

			$query = "update ".PREFIX."subsubsubsubCategory set subsubsubsub_name='".$category_name."',og_description='".$meta_description."',subsubsubsub_permalink='".$permalink."', meta_title='".$meta_title."', meta_keyword='".$meta_keywords."', active='".$active."', created='".$created."' where id='".$id."'";
			$this->query($query);
		}
		function getSubSubSubSubCatByID($id){
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."subsubsubsubCategory where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		///////////// ATTRIBUTE START ////////////
		/** * Function to add attribute details */
		function addAttribute($data) {
			
			$attribute_name 	= $this->escape_string($this->strip_all($data['attribute_name']));
			$active 			= $this->escape_string($this->strip_all($data['active']));
			
			$attribute_permalink	= $this->getValidatedPermalink($attribute_name);
			
			$query = "insert into ".PREFIX."attribute_master(attribute_name, attribute_permalink, active) values ('".$attribute_name."', '".$attribute_permalink."', '".$active."')";
			$this->query($query);

			$id = $this->last_insert_id();

			if(isset($data['category_ids']) && count($data['category_ids']) > 0){
				foreach($data['category_ids'] as $key => $category_id){
					$category_id 	= $this->escape_string($this->strip_all($category_id));
					$query = "insert into ".PREFIX."category_attribute_list(category_id, attribute_id) values ('".$category_id."', '".$id."')";
					$this->query($query);
				}
			}

			if(isset($data['features']) && count($data['features']) > 0){
				foreach($data['features'] as $index => $feature){
					$feature_permalink	= $this->getValidatedPermalink($feature);
					$query = "insert into ".PREFIX."attribute_features(attribute_id, feature, feature_permalink) values ('".$id."', '".$feature."', '".$feature_permalink."')";
					$this->query($query);
				}
			}

			return $id;
		}

		/** * Function to update attribute details */
		function updateAttribute($data){
			$id 				= $this->escape_string($this->strip_all($data['id']));
			$attribute_name 	= $this->escape_string($this->strip_all($data['attribute_name']));
			$active 			= $this->escape_string($this->strip_all($data['active']));
			
			$attribute_permalink	= $this->getValidatedPermalink($attribute_name);
			
			$query = "update ".PREFIX."attribute_master set attribute_name = '".$attribute_name."', attribute_permalink = '".$attribute_permalink."', active = '".$active."' where id='".$id."'";
			$result = $this->query($query);

			if(isset($data['category_ids']) && count($data['category_ids']) > 0){
				$this->deleteCategoryAttributeListByAttributeId($id);
				foreach($data['category_ids'] as $key => $category_id){
					$category_id 	= $this->escape_string($this->strip_all($category_id));
					$query = "insert into ".PREFIX."category_attribute_list(category_id, attribute_id) values ('".$category_id."', '".$id."')";
					$this->query($query);
				}
			}

			$allAttributeFeatureDetails = $this->getAllAttributeFeaturesByAttributeId($id);
			while($attributeFeatures = $this->fetch($allAttributeFeatureDetails)){
				if(!in_array($attributeFeatures['id'], $data['attribute_feature_id'])){
					$this->deleteAttributeFeatureById($attributeFeatures['id']);
				}
			}

			if(isset($data['features']) && count($data['features']) > 0){
				// $this->deleteAllAttributeFeatureByAttributeId($id);
				foreach($data['features'] as $index => $feature){
					if(!empty($feature)){
						if(isset($data['attribute_feature_id'][$index]) && !empty($data['attribute_feature_id'][$index])){
							$feature_permalink	= $this->getValidatedPermalink($feature);
							$query = "update ".PREFIX."attribute_features set feature = '".$feature."', feature_permalink = '".$feature_permalink."' where id = '".$data['attribute_feature_id'][$index]."'";
						}else{
							$feature_permalink	= $this->getValidatedPermalink($feature);
							$query = "insert into ".PREFIX."attribute_features(attribute_id, feature, feature_permalink) values ('".$id."', '".$feature."', '".$feature_permalink."')";
						}
						$this->query($query);
					}
				}
			}

			return $result;
		}

		/** * Function to update attribute's active status by id */
		function updateAttributeStatus($subCategoryId, $status){
			$id 			= $this->escape_string($this->strip_all($subCategoryId));
			$attribute_status 	= $this->escape_string($this->strip_all($status));
			
			if($attribute_status == '1') {
				$sql_query = "update ".PREFIX."attribute_master set active='0' where id = '".$id."'";
			} else if($attribute_status == '0') {
				$sql_query = "update ".PREFIX."attribute_master set active='1' where id = '".$id."'";
			}
			return $this->query($sql_query);
		}

		/** * Function to delete attribute by id */
		function deleteAttributeById($id){
			$id  	= $this->escape_string($this->strip_all($id));
			
			$query = "DELETE FROM ".PREFIX."attribute_master where id = '".$id."'";
			$this->query($query);
			
			$attrFeat = $this->query("select * from ".PREFIX."attribute_features where `attribute_id`='".$id."'");
			if($this->num_rows($attrFeat)>0){
				while($result = $this->fetch($attrFeat)){
					$deleProeductAttr = "DELETE FROM ".PREFIX."product_attributes WHERE `attribute_feature_id`='".$result['id']."'";
					$this->query($deleProeductAttr);

					$deleteAttrFeature = "DELETE FROM ".PREFIX."attribute_features WHERE `id`='".$result['id']."'";
					$this->query($deleteAttrFeature);
				}
			}

			$deleteAttrCategory = "DELETE FROM ".PREFIX."category_attribute_list WHERE `attribute_id`='".$id."'";
			$this->query($deleteAttrCategory);

		}

		/** * Function to get details of all attribute features by attribute id */
		function getAllAttributeFeaturesByAttributeId($attribute_id) {
			$attribute_id = $this->escape_string($this->strip_all($attribute_id));
			$query = "select * from ".PREFIX."attribute_features where attribute_id = '".$attribute_id."' and is_deleted <>1";
			$sql = $this->query($query);
			return $sql;
		}

		/** * Function to delete attribute feature by id */
		function deleteAttributeFeatureById($id){
			$id  	= $this->escape_string($this->strip_all($id));
			$query 	= "DELETE FROM ".PREFIX."attribute_features WHERE `id`='".$id."'";
			return $this->query($query);
		}

		/** * Function to delete attribute feature by id */
		function deleteAllAttributeFeatureByAttributeId($attribute_id){
			$attribute_id  	= $this->escape_string($this->strip_all($attribute_id));
			$query 	= "delete from ".PREFIX."attribute_features where attribute_id = '".$attribute_id."'";

			return $this->query($query);
		}

		/** * Function to delete category attribute link by attribute id */
		function deleteCategoryAttributeListByAttributeId($attribute_id){
			$attribute_id  	= $this->escape_string($this->strip_all($attribute_id));
			$query 	= "delete from ".PREFIX."category_attribute_list where attribute_id = '".$attribute_id."'";

			return $this->query($query);
		}

		function getUniqueAttributeById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."attribute_master where id='".$id."' and active='1'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		/*===================== Attribute ENDS =====================*/

		/////////////////////////ATTRIBUTE/////////////////////
		function getProdcutCategoryByProductId($productID){
			$categoryArr = array();
			$productID = $this->escape_string($this->strip_all($productID));
			$sql="SELECT * FROM ".PREFIX."product_category_mapping WHERE `product_id`='".$productID."'";
			//echo $sql;
			$result = $this->query($sql);
			if($this->num_rows($result)>0){
				while($categories = $this->fetch($result)){
					$categoryArr[] = $categories['category_id'];
				}
			}
			return $categoryArr; 
		}
		function getAttributesByCategoryId($category_id) {
			$category_id = $this->escape_string($this->strip_all($category_id));
			$query = "select * from ".PREFIX."category_attribute_list where category_id in($category_id)";
			//echo $query; exit;
			$sql = $this->query($query);
			return $sql;
		}
		function getAttributeValues($attribute_id) {
			$attribute_id = $this->escape_string($this->strip_all($attribute_id));
			$query = "select * from ".PREFIX."attribute_features where attribute_id='$attribute_id'";
			return $this->query($query);
		}
		function getProductFilterValueByFilterId($attribute_id,$productID){
			//echo $attribute_id." ".$productID;
			$attributeArr = array();
			$sql = "SELECT * FROM ".PREFIX."product_attributes WHERE `product_id`='".$productID."'";
			$result = $this->query($sql);
			while($productAttr = $this->fetch($result)){
				$attributeArr[] = $productAttr['attribute_feature_id'] ;
			}
			return $attributeArr;

		}
		function getUniqueSubSubCategoryByIDs($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."subsubCategory where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function getUniqueReviewById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."reviews where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function updateReviews($data,$files) {
			$id = $this->escape_string($this->strip_all($data['id']));
			$product_id = $this->escape_string($this->strip_all($data['product_id']));
			$name = $this->escape_string($this->strip_all($data['name']));
			$email = $this->escape_string($this->strip_all($data['email']));
			$review_type = $this->escape_string($this->strip_all($data['review_type']));
			$rating = $this->escape_string($this->strip_all($data['rating']));
			$review = $this->escape_string($this->strip_all($data['review']));

			$test_image = "";
			
			$active = $this->escape_string($this->strip_all($data['active']));
			
			$query = "update ".PREFIX."reviews set name='".$name."', email='".$email."', review='".$review."', review_type='".$review_type."', image='".$test_image."', rating='".$rating."', active='".$active."' where id='".$id."'";
			$result = $this->query($query);
			
			$query = "select AVG(rating) as all_rating  from ".PREFIX."reviews where product_id='".$product_id."' and active='Yes'";
			$sql = $this->query($query);
			$results =$this->fetch($sql);
			
			
			$query_avg = "update ".PREFIX."product_master set avg_rating='".$results['all_rating']."' where id='".$product_id."'";
			$this->query($query_avg);
			
			return $result;
			
		}
		function getProductRelatedProductsInArray($product_id) {
			$product_id = $this->escape_string($this->strip_all($product_id));
			$query = "select * from ".PREFIX."products_related_products where product_id='$product_id'";
			$sql = $this->query($query);
			$recommended_array = array();
			while($result = $this->fetch($sql)) {
				array_push($recommended_array,$result['related_product_id']);
			}
			return $recommended_array;
		}

		function getUniqueCustomersById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."customers where id='$id'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function deleteCustomer($id,$editedby='') {
			$id = $this->escape_string($this->strip_all($id));
			$query = "delete from ".PREFIX."customers where id='".$id."'";
			$this->query($query);
		}

		// == ORDER START ==
		function getProductOrderDetails($txnId){
			$query = "select * from ".PREFIX."order where txn_id='".$txnId."'";
			$orderRS = $this->query($query);
			if($orderRS->num_rows>0){ // order with txn_id found
				$transactionArr = array();
				$orderDetails = $this->fetch($orderRS);

				$transactionArr['order'] = $orderDetails;
				$transactionArr['orderDetails'] = array();

				$query = "select * from ".PREFIX."order_details where order_id='".$orderDetails['id']."' and customer_id='".$orderDetails['customer_id']."'";
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
		function updateProductOrderDetails($data){
			$txnId = $this->escape_string($this->strip_all($data['txnId']));
			$orderStatus = $this->escape_string($this->strip_all($data['orderStatus']));
			$paymentStatus = $this->escape_string($this->strip_all($data['paymentStatus']));
			
			if(empty($orderStatus)){
				$orderStatus = 'NULL';
			} else {
				$orderStatus = "'".$orderStatus."'";
			}
			$orderRemark = $this->escape_string($this->strip_all($data['orderRemark']));

			if(isset($data['refundStatus']) && !empty($data['refundStatus'])){
				$refundStatus = $this->escape_string($this->strip_all($data['refundStatus']));
			}else{
				$refundStatus = "";
			}

			$query = "update ".PREFIX."order set payment_status='".$paymentStatus."', order_status=".$orderStatus.", refund_status='".$refundStatus."',  order_remark='".$orderRemark."' where txn_id='".$txnId."'";
			$this->query($query);
		}
		function deleteProductOrder($txnId) {
			$txnId = $this->escape_string($this->strip_all($txnId));
			$query = "update ".PREFIX."order set is_deleted='1' where txn_id='".$txnId."'";
			$this->query($query);
		}
		function getCustomerTotalPurchaseAmount($customerId){
			$customerId = $this->escape_string($this->strip_all($customerId));
			$customerPurchaseAmount = 0;
			$query = "select * from ".PREFIX."order where is_deleted=0 and payment_status='Payment Complete' and customer_id='".$customerId."'";
			$customerPurchasedOrdersRS = $this->query($query);
			while($oneCustomerPurchasedOrder = $this->fetch($customerPurchasedOrdersRS)){
				$purchaseDetails = $this->getProductOrderDetails($oneCustomerPurchasedOrder['txn_id']);
				if($purchaseDetails){
					$order = $purchaseDetails['order'];
					$orderDetails = $purchaseDetails['orderDetails'];
					// $customerDetails = $this->getUniqueCustomersById($order['customer_id']);
					$subTotal = 0;
					$finalTotal = 0;
					foreach($orderDetails as $oneOrder){
						$productDetails = $this->getUniqueProductById($oneOrder['product_id']);
						$quantity = $oneOrder['quantity'];
						$image_name = strtolower(pathinfo($productDetails['image_name'], PATHINFO_FILENAME));
						$image_ext = strtolower(pathinfo($productDetails['image_name'], PATHINFO_EXTENSION));
						$imageUrl = BASE_URL."/images/products/".$image_name.'_large.'.$image_ext;

						if($oneOrder['purchase_type']=="customer"){
							$unitPrice = $oneOrder['customer_price'];
							$unitDiscountedPrice = $oneOrder['customer_discount_price'];
						} else if($oneOrder['purchase_type']=="wholesaler"){
							$unitPrice = $oneOrder['wholesaler_price'];
							$unitDiscountedPrice = $oneOrder['wholesaler_discount_price'];
						} else { // error
							$unitPrice = 99999999;
							$unitDiscountedPrice = 0;
						}

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
						$finalTotal = $subTotal - $couponDiscountAmount;
					} else {
						$finalTotal = $subTotal;
					}
					// CHECK IF DISCOUNT COUPON IS USED

					// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
					if(!empty($order['shipping_charges'])){
						$finalTotal += $order['shipping_charges'];
					}
					// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL 

					// INCREMENT CUSTOMER PURCHASE AMOUNT
					$customerPurchaseAmount += $finalTotal;
				}
			}
			return $customerPurchaseAmount;
		}
		function getCustomerPurchaseAmount($txn_id){
			$txn_id = $this->escape_string($this->strip_all($txn_id));
			$purchaseDetails = $this->getProductOrderDetails($txn_id);
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

					$imageUrl = BASE_URL."/images/products/".$image_name.'_large.'.$image_ext;
					
					
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
					if($oneOrder['payment_discount']>0) {
						$paymentDiscountAmount = $totalPrice*($oneOrder['payment_discount']/100);
						$totalPrice = $totalPrice - $paymentDiscountAmount;
					}
					$subTotal += $totalPrice;
				}

				// CHECK IF DISCOUNT COUPON IS USED
				$couponDiscountAmount = $this->getRedeemedCouponAmount($order['customer_id'], $order['id']);
				if(!empty($couponDiscountAmount)){
					$finalTotal = $subTotal - $couponDiscountAmount;
				} else {
					$finalTotal = $subTotal;
				}
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

		function getOrderbyId($orderId){
			$orderId = $this->escape_string($this->strip_all($orderId));
			$sql = "SELECT * FROM ".PREFIX."order WHERE `id`='".$orderId."'";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function getOrderDetailsbyId($orderId){
			$orderId = $this->escape_string($this->strip_all($orderId));
			$sql = "SELECT * FROM ".PREFIX."order_details WHERE `id`='".$orderId."'";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		// == ORDER END ==
		// == COUPON CODE ==
		function getRedeemedCouponAmount($customerId, $orderId){
			$customerId = $this->escape_string($this->strip_all($customerId));
			$orderId = $this->escape_string($this->strip_all($orderId));
			$query = "select * from ".PREFIX."order_discount_coupons where order_id='".$orderId."' and customer_id='".$customerId."'";
			//echo $query; exit;
			$sql = $this->query($query);
			if($sql->num_rows>0){
				$totalDiscountAmount = 0;
				while($couponDetails = $this->fetch($sql)){
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
						$couponDiscountAmount = round((($couponDetails['coupon_value'] * $discountOnThisPrice) / 100), $precision);
					} else if($couponDetails['coupon_type']=="amount"){
						$couponDiscountAmount = round($couponDetails['coupon_value'], $precision);
					} else {
						$couponDiscountAmount = 0; // invalid values in database
					}
					//$totalDiscountAmount += $couponDiscountAmount;
					$totalDiscountAmount = $couponDiscountAmount;
				}
				return $totalDiscountAmount;
			} else {
				return 0;
			}
		}
		function getUniqueRegisteredUserById($id){
			$id = $this->escape_string($this->strip_all($id));
			return $this->fetch($this->query("select * from ".PREFIX."customers where id='".$id."' "));
		}
		// == COUPON CODE ==

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

		function getListOfCities(){
			$query = "select name as districtname from ".PREFIX."cities order by name asc";
			return $this->query($query);
		}
		function getListOfStates(){
			$query = "select * from ".PREFIX."states order by name asc";
			return $this->query($query);
		}
		// == SHIPPING CHARGES START ==
		function updateShippingChargesDetails($data){
			$Id = $this->escape_string($this->strip_all($data['id']));
			$gift_card = $this->escape_string($this->strip_all($data['gift_card']));
			if(isset($data['free_shipping_above']) && !empty($data['free_shipping_above'])){
				$free_shipping_above = $this->escape_string($this->strip_all($data['free_shipping_above']));
			}else{
				$free_shipping_above = '0';
			}
			if(isset($data['shipping_charges']) && !empty($data['shipping_charges'])){
				$shipping_charges = $this->escape_string($this->strip_all($data['shipping_charges']));
			}else{
				$shipping_charges = '0';
			}
			$query = "update ".PREFIX."shipping_charge set free_shipping_above=".$free_shipping_above.", shipping_charges='".$shipping_charges."', gift_card='".$gift_card."' , last_modified=now() where id='".$Id."'";
			return $this->query($query);
		}
		// == SHIPPING CHARGES END ==

		// === TESTIMONIAL STARTS ===
		function getUniqueTestimonialById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."testimonials where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function getAllTestimonials() {
			$query = "select * from ".PREFIX."testimonials";
			$sql = $this->query($query);
			return $sql;
		}
		function addTestimonial($data,$file){
			$name = $this->escape_string($this->strip_all($data['user_name']));
			$designation = $this->escape_string($this->strip_all($data['designation']));
			$testimonial = $data['message'];
			
			$SaveImage = new SaveImage();
			$imgDir = '../images/testimonials/';
			if(isset($file['image_name']['name']) && !empty($file['image_name']['name'])){
				$file_name = strtolower( pathinfo($file['image_name']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$cropData = $this->strip_all($data['cropData1']);
				$image_name = $SaveImage->uploadCroppedImageFileFromForm($file['image_name'], 100, $cropData, $imgDir, time().'-1');
			} else {
				$image_name = '';
			}
			$active = $this->escape_string($this->strip_all($data['active']));

			$query = "insert into ".PREFIX."testimonials(name, position, testimonial, image, active) values ('".$name."', '".$designation."', '".$testimonial."', '".$image_name."', '".$active."')";
			return $this->query($query);
		}
		function updateTestimonial($data,$file) {
			$id = $this->escape_string($this->strip_all($data['id']));
			$name = $this->escape_string($this->strip_all($data['user_name']));
			$designation = $this->escape_string($this->strip_all($data['designation']));
			$testimonial = $data['message'];
			$Detail = $this->getUniqueTestimonialById($id);
			
			$SaveImage = new SaveImage();
			$imgDir = '../images/testimonials/';
			if(isset($file['image_name']['name']) && !empty($file['image_name']['name'])) {
				$cropData = $this->strip_all($data['cropData1']);
				$file_name = strtolower( pathinfo($file['image_name']['name'], PATHINFO_FILENAME));
				$file_name = $this->getValidatedPermalink($file_name);
				$this->unlinkImage("testimonials", $Detail['image'], "large");
				$this->unlinkImage("testimonials", $Detail['image'], "crop");
				$image_name = $SaveImage->uploadCroppedImageFileFromForm($file['image_name'], 100, $cropData, $imgDir, time().'-1');
				$this->query("update ".PREFIX."testimonials set image='$image_name' where id='$id'");
			}
			$active = $this->escape_string($this->strip_all($data['active']));
			
			$query = "update ".PREFIX."testimonials set name='".$name."', position='".$designation."', testimonial='".$testimonial."', active='".$active."' where id='".$id."'";
			return $this->query($query);
		}
		function deleteTestimonial($id) {
			$id = $this->escape_string($this->strip_all($id));

			$query = "delete from ".PREFIX."testimonials where id='".$id."'";
			$this->query($query);
		}
		// === TESTIMONIAL ENDS ===
		function getUniqueSliderBannerById($staticBannerId) {
			$staticBannerId = $this->escape_string($this->strip_all($staticBannerId));
			$query = "select * from ".PREFIX."slider_banner where id='".$staticBannerId."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		function addSliderBanner($data,$file){
			print_r($data);die;
			$link 			= $this->escape_string($this->strip_all($data['link']));
			$active 		= $this->escape_string($this->strip_all($data['active']));

			if(isset($file['image_name']['name']) && !empty($file['image_name']['name'])) {
				$SaveImage = new SaveImage();
				$imgDir = '../images/slider-banner/';
				if(isset($file['image_name']['name']) && !empty($file['image_name']['name'])){
					$file_name = strtolower( pathinfo($file['image_name']['name'], PATHINFO_FILENAME));
					$cropData = $this->strip_all($data['cropData1']);
					
					$image_name = $SaveImage->uploadCroppedImageFileFromForm($file['image_name'], 1270, $cropData, $imgDir, $file_name.'-'.time().'-1');
				} else {
					$image_name = '';
				}
			}

			$query = "insert into ".PREFIX."slider_banner(image_name, link, active) values ('".$image_name."', '".$link."', '".$active."')";
			return $this->query($query);
		}

		function updateSliderBanner($data,$file) {
			$link 			= $this->escape_string($this->strip_all($data['link']));
			$active 		= $this->escape_string($this->strip_all($data['active']));
			$id 			= $this->escape_string($this->strip_all($data['id']));

			if(isset($file['image_name']['name']) && !empty($file['image_name']['name'])) {
				$SaveImage = new SaveImage();
				$imgDir = '../images/slider-banner/';
				$Detail = $this->getUniqueStaticBannerById($id);
				$cropData = $this->strip_all($data['cropData1']);
				$file_name = strtolower( pathinfo($file['image_name']['name'], PATHINFO_FILENAME));
				$this->unlinkImage("slider-banner", $Detail['image_name'], "large");
				$this->unlinkImage("slider-banner", $Detail['image_name'], "crop");
				$image_name = $SaveImage->uploadCroppedImageFileFromForm($file['image_name'], 1270, $cropData, $imgDir, $file_name.'-'.time().'-1');
				$sql="update ".PREFIX."slider_banner set image_name='$image_name' where id='$id'";
				//echo $sql; 
				$this->query($sql);
			}

			$query = "update ".PREFIX."slider_banner set active='".$active."', link='".$link."' where id='".$id."'";
			//echo $query; exit;
			return $this->query($query);
		}
		function getUniqueStaticBannerById($staticBannerId) {
			$staticBannerId = $this->escape_string($this->strip_all($staticBannerId));
			$query = "select * from ".PREFIX."static_banner where id='".$staticBannerId."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		function homepageCms($data,$file){
			//print_r($data);
			/*print_r($file);
			exit;*/
			$SaveImage = new SaveImage();
			$imgDir = '../images/home_cms/';

			$id = $this->escape_string($this->strip_all($data['id']));
			
			$adUrlOne = $this->escape_string($this->strip_all($data['adUrlOne']));
			$adUrlTwo = $this->escape_string($this->strip_all($data['adUrlTwo']));
			$adUrlThree = $this->escape_string($this->strip_all($data['adUrlThree']));
			
			$who_we_are 	 = $data['who_we_are'];
			$our_mission 	 = $data['our_mission'];
			$our_vision 	 = $data['our_vision'];
			$description 	 = $data['description'];
			//print_r($description); exit;
			if(!empty($id)){
					$Detail = $this->gethomeCmsById($id);

					
					if(isset($file['image_name_one']['name']) && !empty($file['image_name_one']['name'])) {

						$cropData = $this->strip_all($data['cropData1']);
						$file_name = strtolower( pathinfo($file['image_name_one']['name'], PATHINFO_FILENAME));
						$this->unlinkImage("home_cms", $Detail['image_name_one'], "large");
						$this->unlinkImage("home_cms", $Detail['image_name_one'], "crop");
						$image_name_one = $SaveImage->uploadCroppedImageFileFromForm($file['image_name_one'], 297, $cropData, $imgDir, $file_name.'-'.time().'-1');
						$sql="update ".PREFIX."home_cms set image_name_one='$image_name_one' where id='$id'";
						//echo $sql; 
						$this->query($sql);
					}
					if(isset($file['image_name_two']['name']) && !empty($file['image_name_two']['name'])) {
						
						
						$cropData = $this->strip_all($data['cropData2']);
						$file_name = strtolower( pathinfo($file['image_name_two']['name'], PATHINFO_FILENAME));
						$this->unlinkImage("home_cms", $Detail['image_name_two'], "large");
						$this->unlinkImage("home_cms", $Detail['image_name_two'], "crop");
						$image_name_two = $SaveImage->uploadCroppedImageFileFromForm($file['image_name_two'], 294, $cropData, $imgDir, $file_name.'-'.time().'-2');
						$sql="update ".PREFIX."home_cms set image_name_two='$image_name_two' where id='$id'";
						//echo $sql; 
						$this->query($sql);
					}
					if(isset($file['image_name_three']['name']) && !empty($file['image_name_three']['name'])) {
						
						
						$cropData = $this->strip_all($data['cropData3']);
						$file_name = strtolower( pathinfo($file['image_name_three']['name'], PATHINFO_FILENAME));
						$this->unlinkImage("home_cms", $Detail['image_name_three'], "large");
						$this->unlinkImage("home_cms", $Detail['image_name_three'], "crop");
						$image_name_three = $SaveImage->uploadCroppedImageFileFromForm($file['image_name_three'], 613, $cropData, $imgDir, $file_name.'-'.time().'-3');
						$sql="update ".PREFIX."home_cms set image_name_three='$image_name_three' where id='$id'";
						//echo $file_name;
						//echo $sql; exit;
						$this->query($sql);
					}
					if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])) {

						$cropData = $this->strip_all($data['cropData4']);
						$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
						$this->unlinkImage("home_cms", $Detail['banner_image'], "large");
						$this->unlinkImage("home_cms", $Detail['banner_image'], "crop");
						$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 630, $cropData, $imgDir, $file_name.'-'.time().'-4');
						$sql="update ".PREFIX."home_cms set banner_image='$banner_image' where id='$id'";
						
						$this->query($sql);
					}
					$update = "UPDATE ".PREFIX."home_cms SET who_we_are='".$who_we_are."', our_mission='".$our_mission."', our_vision='".$our_vision."', `description`='".$description."',adUrlOne='".$adUrlOne."', adUrlTwo='".$adUrlTwo."', adUrlThree='".$adUrlThree."' WHERE id='".$id."'";
					$this->query($update);

			}else{

				
				if(isset($file['image_name_one']['name']) && !empty($file['image_name_one']['name'])) {
				
					
					if(isset($file['image_name_one']['name']) && !empty($file['image_name_one']['name'])){
						$file_name = strtolower( pathinfo($file['image_name_one']['name'], PATHINFO_FILENAME));
						$cropData = $this->strip_all($data['cropData1']);
						
						$image_name_one = $SaveImage->uploadCroppedImageFileFromForm($file['image_name_one'], 297, $cropData, $imgDir, $file_name.'-'.time().'-1');
					} else {
						$image_name_one = '';
					}
				}
				if(isset($file['image_name_two']['name']) && !empty($file['image_name_two']['name'])) {
					
					if(isset($file['image_name_two']['name']) && !empty($file['image_name_two']['name'])){
						$file_name = strtolower( pathinfo($file['image_name_two']['name'], PATHINFO_FILENAME));
						$cropData = $this->strip_all($data['cropData2']);
						
						$image_name_two = $SaveImage->uploadCroppedImageFileFromForm($file['image_name_two'], 294, $cropData, $imgDir, $file_name.'-'.time().'-2');
					} else {
						$image_name_two = '';
					}
				}
				if(isset($file['image_name_three']['name']) && !empty($file['image_name_three']['name'])) {
					
					if(isset($file['image_name_three']['name']) && !empty($file['image_name_three']['name'])){
						$file_name = strtolower( pathinfo($file['image_name_three']['name'], PATHINFO_FILENAME));
						$cropData = $this->strip_all($data['cropData3']);
						
						$image_name_three = $SaveImage->uploadCroppedImageFileFromForm($file['image_name_three'], 613, $cropData, $imgDir, $file_name.'-'.time().'-3');
					} else {
						$image_name_three = '';
					}
				}	
				if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])) {
					
					if(isset($file['banner_image']['name']) && !empty($file['banner_image']['name'])){
						$file_name = strtolower( pathinfo($file['banner_image']['name'], PATHINFO_FILENAME));
						$cropData = $this->strip_all($data['cropData4']);
						
						$banner_image = $SaveImage->uploadCroppedImageFileFromForm($file['banner_image'], 630, $cropData, $imgDir, $file_name.'-'.time().'-4');
					} else {
						$banner_image = '';
					}
				}
				

				//$sql ="INSERT INTO ".PREFIX."about_us(`image_name_one`, `image_name_two`, `image_name_three`, `banner_image`, `description`) VALUES ('".$image_name_one."','".$image_name_two."','".$image_name_three."','".$banner_image."','".$description."')";

				$sql ="INSERT INTO ".PREFIX."home_cms(`image_name_one`, `image_name_two`, `image_name_three`, `banner_image`, `who_we_are`, `our_mission`, `our_vision`, `description`, adUrlOne, adUrlTwo, adUrlThree) VALUES ('".$image_name_one."','".$image_name_two."','".$image_name_three."','".$banner_image."','".$who_we_are."','".$our_mission."','".$our_vision."','".$description."','".$adUrlOne."','".$adUrlTwo."','".$adUrlThree."')";	
				$this->query($sql);
			}
		}
		
		function gethomepageCms(){
			$sql = "SELECT * FROM ".PREFIX."home_cms";
			$result = $this->query($sql);
			return  $this->fetch($result);
		}
		function gethomeCmsById($id){
			$sql = "SELECT * FROM ".PREFIX."home_cms where id='".$id."'";
			$result = $this->query($sql);
			return $this->fetch($result);
		}
		function deletesubSubCategory($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "delete from ".PREFIX."subsubCategory where id='".$id."'";
			$this->query($query);
			/* $sql = $this->query("select id from ".PREFIX."products where sub_category_id='$id'");
			while($detail = $this->fetch($sql)) {
				$product_id = $detail['id'];
				$this->deleteProduct($product_id);
			} */
		}
		function deletesubSubSubCategory($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "delete from ".PREFIX."subsubsubCategory where id='".$id."'";
			$this->query($query);
		}
		function deletesubSubSubSubCategory($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "delete from ".PREFIX."subsubsubsubCategory where id='".$id."'";
			$this->query($query);
		}
		function getVendorDetailsByvendorId($vendorId){
			$vendorId = $this->escape_string($this->strip_all($vendorId));
			$sql = "SELECT * FROM ".PREFIX."customers WHERE `id`='".$vendorId."'";
			$result = $this->query($sql);
			return $this->fetch($result);
		}

		function addMoreCustomerAddress($data){
			// echo "<pre>";
			// print_r($data);
			// echo "</pre>";
			// exit;
			$customerId = $this->escape_string($this->strip_all($data['customer_id']));
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
				$pincode = '';
			}
			$state = $this->escape_string($this->strip_all($data['state']));
			$city = $this->escape_string($this->strip_all($data['city']));
			// $customer_contact = $this->escape_string($this->strip_all($data['customer_contact']));
			$customer_fname = $this->escape_string($this->strip_all($data['customer_fname']));
			// $customer_lname = $this->escape_string($this->strip_all($data['customer_lname']));
			$default_address = $this->getDefaultCustomerAddressByCustomerId($customerId);
			
			if(count($default_address)>0){
			//$is_preferred = $this->escape_string($this->strip_all($data['is_preferred']));
				$is_preferred = '0';
			}else{
				$is_preferred = '1';
			}
		

			$query = "insert into ".PREFIX."customers_address(customer_id, address1, address2, pincode, state, city, customer_fname) 
					values ('".$customerId."', '".$address1."', '".$address2."', '".$pincode."', '".$state."', '".$city."', '".$customer_fname."')";		
			$this->query($query);
			return $this->last_insert_id();
		}

		function deleteReview($id){
			$id = $this->escape_string($this->strip_all($id));
			$sql = "DELETE FROM ".PREFIX."reviews WHERE id = '".$id."'";
			return $this->query($sql);
		}
	} 
?>