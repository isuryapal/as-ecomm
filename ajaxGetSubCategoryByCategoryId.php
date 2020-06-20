<?php
	Include_once("include/functions.php");
    $functions = New Functions();

	$output	= '<option value="" disabled>Please Select Sub Category</option>';
	
	if(isset($_POST['category_id'])){
		if(is_array($_POST['category_id'])){
			$category_id = implode(",", $_POST['category_id']);
		}else{
			$category_id = $admin->escape_string($admin->strip_all($_POST['category_id']));
		}
		
		$subCategoryDetails = $functions->getAllSubCategoriesByCategoryId($category_id);
		if($functions->num_rows($subCategoryDetails) > 0){
			while($subCategories = $functions->fetch($subCategoryDetails)){
				$output	.= '<option value="'.$subCategories['id'].'">'.$subCategories['sub_category_name'].'</option>';
			}
		}
	}
	echo $output;
	exit;
?>