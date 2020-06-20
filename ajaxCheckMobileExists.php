<?php
    include_once 'include/functions.php';
    $functions = new Functions();

    $response = 'false';

    $id = "";
	if(isset($_POST['mobile']) && !empty($_POST['mobile'])){
		$mobile = $functions->escape_string($functions->strip_all($_POST['mobile']));

		if(isset($_POST['id']) && !empty($_POST['id'])){
			$id = $functions->escape_string($functions->strip_all($_POST['id']));
			$id = " and id<>'".$id."'";

		}
		$checkUserExistSQL = $functions->query("select * from ".PREFIX."customers where mobile='".$mobile."' $id");

		if($functions->num_rows($checkUserExistSQL)>0){
			$response="false";
		} else{
			$response="true";
		}
	}
	echo $response;
?>