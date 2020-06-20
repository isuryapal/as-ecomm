<?php 
    include_once 'include/functions.php';
    $functions = new Functions();

   if($loggedInUserDetailsArr = $functions->sessionExists()){
      header("location: index.php");
      exit;
   }

   $errorArr = array();
   if(isset($_GET['success'])){
      
   } else if(isset($_GET['v']) && !empty($_GET['v'])){
      $v = $functions->escape_string($functions->strip_all($_GET['v']));
      if( (empty($v) || !preg_match("/^[A-z0-9]{1,}$/", $v)) ){
         $errorArr[] = "INVALIDURL";
      }
      if(count($errorArr)>0){
         $errorStr = implode("|", $errorArr);
         header("location: verify-customer-email.php?error=".$errorStr);
         exit;
      } else {
         $updatedRows = $functions->setUserEmailAsVerified($v);
         if($updatedRows>0){ // user was marked as active
            header("location: verify-customer-email.php?success");
            exit;
         } else { // user already marked as active or user does not exists
            header("location: verify-customer-email.php?error=INVALIDURL");
            exit;
         }
      }
   }
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
				<?php 
					if(isset($_GET['success'])) { 
				?>	
						<h1>Thank you!</h1>
						<p class="thankp"><b>Account Verify Successfully.</b></p>
						<img src="<?php echo BASE_URL; ?>/images/thankyou.png" class="thanksimg">
						<div class="thanksbox">
							<p class="thankscontent">
							
							</p>
							<a href="<?php echo BASE_URL."/login.php"; ?>">LET'S GET STARTED!</a>
						</div>
				  <?php 
					}
					if(isset($_GET['error']) && $_GET['error']=="INVALIDURL"){ ?>
						<p class="thankp" style="color:red;"><b>Verification URL No Logner Active.</b></p>
					<?php 
					} ?>
					
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