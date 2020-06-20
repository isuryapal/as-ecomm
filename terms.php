<?php 
   Include_once("include/functions.php");
   $functions = New Functions();

   $cmsDetails =  $functions->gerCMSDetails();
?>
<!DOCTYPE>
<html>
   <head>
	<title>Arvind Sanitary</title>
      <?php include("include/header-link.php");?>
   </head>
   <body class="home">
      <!--Top start menu head-->       
      <?php include("include/header.php");?>
      <div class="only-breadcrumbs">
   		<div class="container">
   			<ul class="breadcrumbs">
   				<li><a href="<?php echo BASE_URL; ?>">Home</a></li>
   				<li>Terms & Condition</li>
   			</ul>
   		</div>
   	</div>
      <div class="container"><br>
	     <div class="richtext">
        <?php echo $cmsDetails["term_condition"]; ?>
         </div>
      </div>
         <!--Main End Code Here-->
      <!--footer start menu head-->
      <?php include("include/footer.php");?> 
      <!--footer end menu head-->
      <?php include("include/footer-link.php");?>
   </body>
</html>