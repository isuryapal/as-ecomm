<?php
	Include_once("include/functions.php");
    $functions = New Functions();

	if(isset($_GET['q'])){
		$q = trim($functions->escape_string($functions->strip_all($_GET['q'])));
		$resultString = "";
		$query = "select DISTINCT(product_name) from ".PREFIX."product_master where description like '%".$q."%' or product_name like '%".$q."%' and active='Yes' order by permalink like '".$q."%' asc";
		$result = $functions->query($query);
		while($row=$functions->fetch($result)){
			//$resultString .= substr($row['product_name'],0,45);
			//$resultString .= "PRO-".$row['product_name'];
			$resultString .= $row['product_name'];
			$resultString .= "D#K";
		}
		
		/*$query = "select DISTINCT(category_name) from ".PREFIX."category_master where ( meta_title like '%".$q."%' or category_name like '%".$q."%')  and active='Yes' order by permalink like '".$q."%' asc";
		$result = $functions->query($query);
		while($row=$functions->fetch($result)){
			//$resultString .= substr($row['product_name'],0,45);
			//$resultString .= "CAT-".$row['category_name'];
			$resultString .= $row['category_name'];
			$resultString .= "D#K";
		}*/
		

		
		// time to return result to AJAX call
		echo $resultString;
		//echo $query;
	} else {
		// echo "No results found";
	}
?>