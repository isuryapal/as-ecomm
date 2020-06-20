<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';

	$admin = new AdminFunctions();
	
	if(isset($_POST['category_id'])){
		if(is_array($_POST['category_id'])){
			$category_id = implode(",", $_POST['category_id']);
		}else{
			$category_id = $admin->escape_string($admin->strip_all($_POST['category_id']));
		}
		
		$subCategoryDetails = $admin->getAllSubCategoriesByCategoryId($category_id);
		if($admin->num_rows($subCategoryDetails) > 0){
			while($subCategories = $admin->fetch($subCategoryDetails)){
				$output	.= '<option value="'.$subCategories['id'].'">'.$subCategories['sub_category_name'].'</option>';
			}
		}
	}
	echo $output;
	exit;
?>