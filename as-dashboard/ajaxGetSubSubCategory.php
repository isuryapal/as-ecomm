<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	//print_r($_GET);
	if(isset($_GET['Subsubcategory']) && !empty($_GET['Subsubcategory'])){
		if(is_array($_GET['Subsubcategory'])){
			$Subsubcategory = implode(",", $_GET['Subsubcategory']);
		}else{
			$Subsubcategory = $admin->escape_string($admin->strip_all($_GET['Subsubcategory']));
		}
		$subCategorySQL = $admin->getAllSubSubCategories($Subsubcategory);
		$selectContent = '';
		//$selectContent .= '<option value="">Select Sub SubCategory</option>';
		
			
		while($subCategory = $admin->fetch($subCategorySQL)) {
			
			$selectContent .= '<option value="'.$subCategory['id'].'">'.$subCategory['subcategory_name'].'</option>';
			
		}
		$ajaxResponse = array();
		$ajaxResponse['status'] = 1;
		$ajaxResponse['selectContent'] = $selectContent;
		echo json_encode($ajaxResponse);
	}else{
		$ajaxResponse = array();
		$ajaxResponse['status'] = 1;
		$ajaxResponse['selectContent'] = "";
		echo json_encode($ajaxResponse);
	}
?>