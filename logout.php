<?php
	include_once 'include/functions.php';
    $functions = new Functions();

	$functions->logoutSession();
	header("location:".BASE_URL);
	exit;
?>
