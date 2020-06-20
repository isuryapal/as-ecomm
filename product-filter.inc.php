<?php 
	Include_once("include/functions.php");
	$functions = New Functions();
	$loggedInUserDetailsArr = $functions->sessionExists();
	if(isset($_POST['permalink']) && !empty($_POST['permalink'])){
		$permalink = $_POST['permalink'];
	}
	Include_once"include/product-listing.inc.php";

?>