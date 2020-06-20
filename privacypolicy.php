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
				<li><a href="index.php">Home</a></li>
				<li>Privacy Policy</li>
			</ul>
		</div>
	</div>
      <div class="container">
         <div class="richtext">
            <?php echo $cmsDetails['privacy_policy']; ?>
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