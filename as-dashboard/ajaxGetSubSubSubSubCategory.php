<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	//print_r($_GET);
	if(isset($_GET['Subsubsubsubcategory']) && !empty($_GET['Subsubsubsubcategory'])){
		if(is_array($_GET['Subsubsubsubcategory'])){
			$Subsubsubsubcategory = implode(",", $_GET['Subsubsubsubcategory']);
		}else{
			$Subsubsubsubcategory = $admin->escape_string($admin->strip_all($_GET['Subsubsubsubcategory']));
		}
		$subCategorySQL = $admin->getAllSubSubSubSubCategories($Subsubsubsubcategory);
		$selectContent = '';
		//$selectContent .= '<option value="">Select Sub SubCategory</option>';
		
			
		while($subCategory = $admin->fetch($subCategorySQL)) {
			
			$selectContent .= '<option value="'.$subCategory['id'].'">'.$subCategory['subsubsubsub_name'].'</option>';
			
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