<?php 
   Include_once("include/functions.php");
   $functions = New Functions();
   $loggedInUserDetailsArr = $functions->sessionExists();
?>
<!DOCTYPE>
<html>
   <head>
      <title>Heres Your Present</title>
      <?php include("include/header-link.php");?>
   </head>
   <body class="listing-page">
      <!--Top start menu head-->       
      <?php include("include/header.php");?>

	<div class="inner-banner">
		<img src="images/inner-banner.png" alt=""/>
		<div class="inner-banner-ctn">
 				<h1>Thank You</h1>
				<ul class="breadcrumbs">
					<li><a href="<?php echo BASE_URL; ?>">Home</a></li>
 					<li>Thank You</li>
				</ul>
 		</div>
	</div>
	  
	<div class="inner-content">
		<div class="container">
			<?php if(isset($_GET['bulkSuccess'])){ ?>
               <div class="alert alert-success" role="alert">
                 Your bulk order request successfully placed. Our team will reach back to you shortly.. 
               </div>
         <?php } ?>  
         <?php if(isset($_GET['txnId']) && !empty($_GET['txnId'])){ ?>
               <div class="alert alert-success" role="alert">
                 Your order with order no "<?php echo $_GET['txnId']; ?>", is placed successfully.
               </div>
         <?php } if(isset($_GET['subsccess'])){ ?>
               <div class="alert alert-success" role="alert">
                 User Subscribed Successfully.
               </div>
         <?php } ?>
         <?php   
            if(isset($_GET['registersuccess'])){
               $mail = $functions->escape_string($functions->strip_all($_GET['thankyou']));
               $de_Email = base64_decode($mail);
      ?>
               <div class="alert alert-success ">
                 Thank you for registering with <?php echo SITE_NAME; ?>, we have sent you an email <strong><?php echo "(".$de_Email.")"; ?></strong> with the verification link, please verify the email to activate your account.
               </div>
      <?php
            }
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