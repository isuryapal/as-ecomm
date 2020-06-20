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
				<li>About Us</li>
			</ul>
		</div>
	</div>
     
           <!-- <//?php echo $cmsDetails['about_us']; ?> -->
           <section class="wel-arvind">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 match centerflex">
                        <div class="wel-text ">
                            <!-- <h2>Welcome To Arvind Sanitary</h2> -->
                            <p>
                                <?php echo $cmsDetails['about_us']; ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-4 match">
                        <div class="img-box">
                            <img src="images/welimg.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    
     
	  
         <!--Main End Code Here-->
      <!--footer start menu head-->
      <?php include("include/footer.php");?> 
      <!--footer end menu head-->
      <?php include("include/footer-link.php");?>
         <script>
         
         </script>
   </body>
</html>