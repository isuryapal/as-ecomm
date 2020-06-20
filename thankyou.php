<?php 
   include_once 'include/functions.php';
   $functions = new Functions();
?>
<!DOCTYPE>
<html>
   <head>
	<title>Arvind Sanitary</title>
      <?php include("include/header-link.php");?>
   </head>
   <body class="template-body">
      <!--Top start menu head-->       
      <?php include("include/header.php");?>

      <div class="main-content">
         <div class="container">
            <div class="row">
               <div class="thankyoubox">
                  <h1>Thank you!</h1>
                  <?php 
                  if(isset($_GET['registersuccess'])){
                  ?>
                        <p class="thankp"><b>Successfully Registred with us.</b></p>
               <?php 
                     }else{ ?>
						<?php if(isset($_GET['subsccess'])){  ?>
							<p class="thankp"><b>User Subscription</b></p>
						<?php }else{ ?>
							<p class="thankp"><b>Thank You For Shopping</b></p>
						<?php } 
						} ?>         
                  <img src="<?php echo BASE_URL;?>/images/logo.png" class="thanksimg">
                  <div class="thanksbox">
                     <p class="thankscontent">
                        <?php if(isset($_GET['bulkSuccess'])){ ?>
                              <div class="alert alert-success" role="alert">
                                Your bulk order request successfully placed. Our team will reach back to you shortly.. 
                              </div>
                        <?php } ?> 

                        <?php if(isset($_GET['txnId']) && !empty($_GET['txnId'])){ ?>
                              

                              <div class="alert alert-success" role="alert">
                                Your order with order no "<?php echo $_GET['txnId']; ?>", is placed successfully.
                              </div>

                              <p><strong>Note:</strong> Please Check Your Spam Mail if you didn't receive Your Order Email. </p>

                        <?php }

                              if(isset($_GET['codTxnId']) && !empty($_GET['codTxnId'])){ ?>
                              <div class="alert alert-success" role="alert">
                                Your order with order no "<?php echo $_GET['codTxnId']; ?>", is confirmed successfully.
                              </div>

                              <p><strong>Note:</strong> Please Check Your Spam Mail if you didn't receive Your Order Email. </p>
                              
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
                     </p>
                     <a href="<?php echo BASE_URL; ?>">Back to Home</a>
                  </div>
               </div>
            </div>
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