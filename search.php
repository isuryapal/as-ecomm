<?php 
   Include_once("include/functions.php");
   $functions = New Functions();
   if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
     // $sql = $functions->getProductByfilterdata($_GET['product_id']);

   }else{
      header("location:".BASE_URL."?INVALISEARCH");
   }
?>
<!DOCTYPE>
<html>
   <head>
      <title>Arvind Sanitary</title>
      <?php include("include/header-link.php");?>
   </head>
   <body class="listing-page">
      <!--Top start menu head-->       
      <?php include("include/header.php");?>

	<div class="only-breadcrumbs">
      <div class="container">
         <ul class="breadcrumbs">
				<li><a href="<?php echo BASE_URL; ?>">Home</a></li>
					<li>Search</li>
			</ul>
		 </div>
	</div>
	<div class="inner-content">
		<div class="container">
			<?php
            $permalink ='search.php';
            Include_once "include/product-listing.inc.php";
         ?>
		</div>
	</div>
	  
         <!--Main End Code Here-->
      <!--footer start menu head-->
      <?php include("include/footer.php");?> 
      <!--footer end menu head-->
      <?php include("include/footer-link.php");?>
         <script>
       
         </script>

   </body>
</html>