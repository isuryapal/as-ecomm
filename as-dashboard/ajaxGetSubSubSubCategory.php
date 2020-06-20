<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	//print_r($_GET);
	if(isset($_GET['Subsubsubcategory']) && !empty($_GET['Subsubsubcategory'])){
		if(is_array($_GET['Subsubsubcategory'])){
			$Subsubsubcategory = implode(",", $_GET['Subsubsubcategory']);
		}else{
			$Subsubsubcategory = $admin->escape_string($admin->strip_all($_GET['Subsubsubcategory']));
		}
		$subCategorySQL = $admin->getAllSubSubSubCategories($Subsubsubcategory);
		$selectContent = '';
		//$selectContent .= '<option value="">Select Sub SubCategory</option>';
		
			
		while($subCategory = $admin->fetch($subCategorySQL)) {
			
			$selectContent .= '<option value="'.$subCategory['id'].'">'.$subCategory['subsubsub_name'].'</option>';
			
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