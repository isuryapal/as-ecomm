<?php 
	include_once 'include/functions.php';
   	$functions = new Functions(); 

   	$sql = "SELECT * FROM ".PREFIX."product_master order by id DESC";
   	$productResult = $functions->query($sql);
   	if($functions->num_rows($productResult)>0){
   		while($productDetails = $functions->fetch($productResult)){
   				if(isset($productDetails['category_id']) && !empty($productDetails['category_id'])){
   					$sql = "INSERT INTO ".PREFIX."product_category_mapping(`category_id`, `product_id`) VALUES ('".$productDetails['category_id']."','".$productDetails['id']."')";
   					$functions->query($sql);
   				}
   				if(isset($productDetails['sub_cat_id']) && !empty($productDetails['sub_cat_id'])){
   					$sql = "INSERT INTO ".PREFIX."product_subcategory_mapping(`subscategory_id`, `product_id`) VALUES ('".$productDetails['sub_cat_id']."','".$productDetails['id']."')";
   					$functions->query($sql);
   				}
   				if(isset($productDetails['subsub_categor_id']) && !empty($productDetails['subsub_categor_id'])){
   					$sql = "INSERT INTO ".PREFIX."product_subsubcategory_mapping(`subsubcategory_id`, `product_id`) VALUES ('".$productDetails['subsub_categor_id']."','".$productDetails['id']."')";
   					$functions->query($sql);
   				}

   		}
   		echo "ALL PRODUCT MAPP SUCCESSFULLY";
   	}


	
?>