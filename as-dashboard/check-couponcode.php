<?php
	/*echo "true";
	 include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	//echo 'true';
	$name = $admin->escape_string($admin->strip_all($_POST['coupon_code']));
	if(isset($_POST['coupon_code'])) {
		$name = $admin->escape_string($admin->strip_all($_POST['coupon_code']));
		$result=$admin->query("select * from ".PREFIX."discount_coupon_maste where coupon_code='$name'");
	} else {
		$response = 'false';
	}
	if($admin->num_rows($result)>0){
		$response =  'true';
	}else{
		$response = 'false';
	}
	echo $response; */
?>

<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	
	$response = 'false';

	if(isset($_POST['coupon_code']) && !empty($_POST['coupon_code'])){
		$coupon_code = trim($admin->escape_string($admin->strip_all($_POST['coupon_code'])));

		if(isset($_POST['id'])){
			$id = $admin->escape_string($admin->strip_all($_POST['id']));
			$checkDataExistSQL = $admin->query("select * from ".PREFIX."discount_coupon_master where coupon_code like '".$coupon_code."' and id<>'".$id."' ");
		} else {
			$checkDataExistSQL = $admin->query("select * from ".PREFIX."discount_coupon_master where coupon_code like '".$coupon_code."' ");
		}

		if($admin->num_rows($checkDataExistSQL)>0){
			$response="false";
		} else{
			$response="true";
		}
	}
	echo $response;
?>