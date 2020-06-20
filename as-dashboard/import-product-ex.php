<?php	
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	
	$tableName 	= 'product_master';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}	

	header("Content-type: text/x-csv");
	header('Content-Disposition: attachment; filename=data.csv');
	header("Content-Type: application/vnd.ms-excel"); 
	header("Content-type: application/octet-stream");
	header('Content-Type: image/jpeg');
	header("Content-Disposition: attachment; filename=".date('d_D_M_Y-H:i:s')."dealer_insert.csv"); 
	header("Pragma: no-cache"); 
	header("Expires: 0");


	//$sql ="SELECT * FROM ".PREFIX."inventory_master order by id DESC";
	//$invetory = $admin->query($sql);
 ?>
productname,productcode,hsncode,avaibility,price,discprice,b2bprice,b2bdiscprice,b2bminqty,gsttax,featured,active,category,subcategory,subsubcategory,subsubsubcategory,subsubsubsubcategory,isfeatured,bestseller,description
value,value,value,value,value,value,value,value,value,0/5/12/18/28,Yes/No,Yes/No,value,value,value,value,value,Yes/No,Yes/No,value