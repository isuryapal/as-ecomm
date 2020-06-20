<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	// $loggedInUserDetailsArr = $admin->getLoggedInUserDetails();
	
	if(isset($_GET['id'])){
		$attribute_id = $admin->escape_string($admin->strip_all($_GET['id']));
		$sql_query = $admin->getUniqueAttributeById($attribute_id);
		$result = $admin->updateAttributeStatus($attribute_id, $sql_query['active']);
	}
?>
<script type="text/javascript">
	window.history.back();
</script>