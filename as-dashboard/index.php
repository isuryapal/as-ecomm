<?php
  include 'include/config.php';
  include 'include/admin-functions.php';
	$admin = new AdminFunctions();
	if($admin->sessionExists()){
		header("location: category-master.php");
		exit();
	}else{
		header("location: admin-login.php");
		exit();
	}
?>