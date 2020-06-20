<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	// $pageName = "Product Types";
	// $pageURL = 'product-types-delete.php';
	$parentPageURL = 'category-master.php';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	//$admin->checkUserPermissions('category_delete',$loggedInUserDetailsArr);
	if(isset($_GET['id'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));		
		$editedby = $admin->escape_string($admin->strip_all($_GET['editedby']));
		if(!isset($id) || empty($id)){
			header("location:".$parentPageURL."?deletefail");
			exit;
		}

		//delete from database
		//$result = $admin->deleteCategory($id,$editedby);
		
		$sql = "select * from ".PREFIX."category_master where id='".$id."'";
		//echo $sql; exit;
		$result = $admin->query($sql);
		if($admin->num_rows($result)>0){
			$cat = $admin->fetch($result);
			
			$admin->unlinkImage("category", $cat['image_name'], "large");
			$admin->unlinkImage("category", $cat['image_name'], "crop");

			$sqlSub = "SELECT * FROM ".PREFIX."sub_category_master WHERE `category_id`='".$cat['id']."'";
			$subcat = $admin->query($sqlSub);
			if($admin->num_rows($subcat)>0){
				$delSub = "DELETE FROM ".PREFIX."sub_category_master WHERE category_id='".$cat['id']."'";
				$admin->query($delSub);
			}
			$delCat = "DELETE FROM ".PREFIX."category_master WHERE `id`='".$id."'";
			$admin->query($delCat);
		}



		header("location:".$parentPageURL."?deletesuccess");
	}
?>