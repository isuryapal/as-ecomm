<?php

$mimes = array('text/csv');
//print_r($_FILES); exit;
if(empty($_FILES['csv_upload']['type'])){
	//print_r($_FILES); exit;
	header("location:product-master.php?OnlyCsv");
	exit;
}
$file = $_FILES['csv_upload']['name'];
move_uploaded_file($_FILES['csv_upload']['tmp_name'],'csv/'.$file);
$handle = fopen('csv/'.$file,"r");
$x=0;
$i=0;

$ErrorArr = array();
$dataErro = array();
while(($fileop = fgetcsv($handle,0,",")) != false) {
	if($x>0){
		$productname = $fileop[$i++];
		$productcode = $fileop[$i++];
		$hsncode = $fileop[$i++];
		$avaibility = $fileop[$i++];
		$price = $fileop[$i++];
		$discprice = $fileop[$i++];
		$b2bprice = $fileop[$i++];
		$b2bdiscprice = $fileop[$i++];
		$b2bminqty = $fileop[$i++];
		$gsttax = $fileop[$i++];
		$featured = $fileop[$i++];
		$active = $fileop[$i++];
		$category = $fileop[$i++];
		$subcategory = $fileop[$i++];
		$subsubcategory = $fileop[$i++];
		$subsubsubcategory = $fileop[$i++];
		$subsubsubsubcategory = $fileop[$i++];
		$isfeatured = $fileop[$i++];
		$bestseller = $fileop[$i++];
		$description = $fileop[$i++];
		$permalink = $admin->getValidatedPermalink($productname);
		$date = date('Ymdhis');
		$created = date("Y-m-d h:i:s");

		$productDetail = "SELECT * FROM ".PREFIX."product_master WHERE product_code = '".$productcode."'";
		$productRes = $admin->query($productDetail);

		if($admin->num_rows($productRes) > 0){
			header("location:product-master.php?codealreadyexist");
			exit();
		}else{

			$insSql = "INSERT INTO ".PREFIX."product_master(`product_name`, `product_code`, `hsn_code`, `availability`, `price`, `discount_price`, `b2b_price`, `b2b_discount_price`, `b2b_min_qty`, `tax`, `description`, `active`, `permalink`, `time`, `feature_product`, is_feature, best_seller, created) VALUES ('".$productname."','".$productcode."','".$hsncode."','".$avaibility."','".$price."','".$discprice."','".$b2bprice."','".$b2bdiscprice."','".$b2bminqty."','".$gsttax."','".$description."','".$active."','".$permalink."' ,'".$date."','".$featured."', '".$isfeatured."', '".$bestseller."', '".$created."')";
			$admin->query($insSql);

			$product_id = $admin->last_insert_id();
			if(!empty($category)){
					$categoryArr = explode(",",$category);
					foreach($categoryArr as $category){
						$catSelect = $admin->query("SELECT * FROM ".PREFIX."category_master WHERE category_name = '".$category."' and active ='Yes'");
						if($admin->num_rows($catSelect)>0){
							$category_id = $admin->fetch($catSelect);
							$category_id = $admin->escape_string($admin->strip_all($category_id['id']));
							$addCat = "INSERT INTO ".PREFIX."product_category_mapping(`category_id`, `product_id`) VALUES ('".$category_id."','".$product_id."')";
							$admin->query($addCat);
						}
					}
			}else{
				header("location:product-master.php?insertcategory");
				exit();
			}
			if(!empty($subcategory)){
					$subcategoryArr = explode(",",$subcategory);
					foreach($subcategoryArr as $subcategory){
						$subcatSelect = $admin->query("SELECT * FROM ".PREFIX."sub_category_master WHERE sub_category_name = '".$subcategory."' and active ='1'");
						if($admin->num_rows($subcatSelect)>0){
							$subcategory_id = $admin->fetch($subcatSelect);
							$subcategory_id = $admin->escape_string($admin->strip_all($subcategory_id['id']));
							$addSubCat = "INSERT INTO ".PREFIX."product_subcategory_mapping(`subscategory_id`, `product_id`) VALUES ('".$subcategory_id."','".$product_id."')";
							$admin->query($addSubCat);
						}
					}
			}
			if(!empty($subsubcategory)){
					$subsubcategoryArr = explode(",",$subsubcategory);
					foreach($subsubcategoryArr as $subsubcategory){
						$subsubcatSelect = $admin->query("SELECT * FROM ".PREFIX."subsubCategory WHERE subcategory_name = '".$subsubcategory."' and active ='Yes'");
						if($admin->num_rows($subsubcatSelect)>0){
							$subsubcategory_id = $admin->fetch($subsubcatSelect);
							$subsubcategory_id = $admin->escape_string($admin->strip_all($subsubcategory_id['id']));
							$addSubSubCat = "INSERT INTO ".PREFIX."product_subsubcategory_mapping(`subsubcategory_id`, `product_id`) VALUES ('".$subsubcategory_id."','".$product_id."')";
							$admin->query($addSubSubCat);
						}
					}
			}
			if(!empty($subsubsubcategory)){
					$subsubsubcategoryArr = explode(",",$subsubsubcategory);
					foreach($subsubsubcategoryArr as $subsubsubcategory){
						$subsubsubcatSelect = $admin->query("SELECT * FROM ".PREFIX."subsubsubCategory WHERE subsubsub_name = '".$subsubsubcategory."' and active ='1'");
						if($admin->num_rows($subsubsubcatSelect)>0){
							$subsubsubcategory_id = $admin->fetch($subsubsubcatSelect);
							$subsubsubcategory_id = $admin->escape_string($admin->strip_all($subsubsubcategory_id['id']));
							$addSubSubSubCat = "INSERT INTO ".PREFIX."product_subsubsubcategory_mapping(`subsubsubcategory_id`, `product_id`) VALUES ('".$subsubsubcategory_id."','".$product_id."')";
							$admin->query($addSubSubSubCat);
						}
					}
			}
			if(!empty($subsubsubsubcategory)){
					$subsubsubsubcategoryArr = explode(",",$subsubsubsubcategory);
					foreach($subsubsubsubcategoryArr as $subsubsubsubcategory){
						$subsubsubsubcatSelect = $admin->query("SELECT * FROM ".PREFIX."subsubsubsubCategory WHERE subsubsubsub_name = '".$subsubsubsubcategory."' and active ='1'");
						if($admin->num_rows($subsubsubsubcatSelect)>0){
							$subsubsubsubcategory_id = $admin->fetch($subsubsubsubcatSelect);
							$subsubsubsubcategory_id = $admin->escape_string($admin->strip_all($subsubsubsubcategory_id['id']));
							$addSubSubSubSubCat = "INSERT INTO ".PREFIX."product_subsubsubsubcategory_mapping(`subsubsubsubcategory_id`, `product_id`) VALUES ('".$subsubsubsubcategory_id."','".$product_id."')";
							$admin->query($addSubSubSubSubCat);
						}
					}
			}
		}
	}
	$x++;
	$i = 0;
}
//print_r($ErrorArr);
//exit;
$errorArr = implode(",",$ErrorArr);
$dataErros = implode(",",$dataErro);
header("location:product-master.php?success&errorArr=".$errorArr."&dataErros=".$dataErros);
exit;

?>