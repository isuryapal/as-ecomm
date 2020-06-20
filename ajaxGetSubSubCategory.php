<?php
	Include_once("include/functions.php");
    $functions = New Functions();

    if(isset($_GET['Subsubcategory']) && !empty($_GET['Subsubcategory'])){
		if(is_array($_GET['Subsubcategory'])){
			$Subsubcategory = implode(",", $_GET['Subsubcategory']);
		}else{
			$Subsubcategory = $admin->escape_string($admin->strip_all($_GET['Subsubcategory']));
		}
		$subCategorySQL = $functions->getAllSubSubCategories($Subsubcategory);
		$selectContent = '';
		$selectContent .= '<option value="">Sub Sub Category</option>';
		
			
		while($subCategory = $functions->fetch($subCategorySQL)) {
			
			$selectContent .= '<option value="'.$subCategory['id'].'">'.$subCategory['subcategory_name'].'</option>';
			
		}
	}
	$ajaxResponse = array();
	$ajaxResponse['status'] = 1;
	$ajaxResponse['selectContent'] = $selectContent;
	echo json_encode($ajaxResponse);
?>