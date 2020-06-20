<?php
    include_once 'include/functions.php';
    $functions = new Functions();
    // echo"<pre>";print_r($_POST);die();
    $response = 'false';
    $id = "";
	if(isset($_POST['email']) && !empty($_POST['email'])){
		$email = $functions->escape_string($functions->strip_all($_POST['email']));
		
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$id = $functions->escape_string($functions->strip_all($_POST['id']));
			$id = " and id<>'".$id."'";

		}

		if(isset($_POST['user_type']) && !empty($_POST['user_type'])){
			$user_type = $functions->escape_string($functions->strip_all($_POST['user_type']));
			$user_type = " and user_type = '".$user_type."'";

		}

		// $("input[name=user_type]").val();
		$checkUserExistSQL = $functions->query("select * from ".PREFIX."customers where email='".$email."' $id");
		//echo $checkUserExistSQL;

		if($functions->num_rows($checkUserExistSQL)>0){
			$response="false";
		} else{
			$response="true";
		}
	}
	echo $response;
?>