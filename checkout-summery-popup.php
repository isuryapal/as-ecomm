<?php 
	Include_once("include/functions.php");
	$functions = New Functions();
	//print_r($_GET);
	if(!$loggedInUserDetailsArr = $functions->sessionExists()){
		header("location: ".BASE_URL."/login.php");
      	exit;
	}
	//print_r($_GET);
	
	//print_r($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']);
	$setType ='';
	
	if(isset($_GET['setType']) && !empty($_GET['setType'])){
		$setType = $functions->escape_string($functions->strip_all($_GET['setType']));
		$id = $functions->escape_string($functions->strip_all($_GET['id']));
		if($setType=="shipping"){
			$_SESSION[SITE_NAME]['BILLADDRESS']['shipping'] = $id;
		}
		if($setType=="Billing"){
			$_SESSION[SITE_NAME]['BILLADDRESS']['Billing'] = $id;
		}
		//print_r($_SESSION[SITE_NAME]['BILLADDRESS']);
		echo '<script type="text/javascript">window.parent.location.href="chekout-order-summary.php";parent.jQuery.fancybox.close();</script>';
		//header("location:checkout-summery-popup.php?".$setType."=".$setType);
		exit;
	}	

	if(isset($_GET['shipping'])){
		$setType = $_GET['shipping'];
	}elseif(isset($_GET['Billing'])){
		$setType = $_GET['Billing'];
	}
?>
<!DOCTYPE>
<html>
   	<head>
      	<title>Arvind Sanitary</title>
      	<?php include("include/header-link.php");?>
   	</head>
   	<body class="checkout-summery-popup-page" id="custom-scrollbar">
     
		<form class="checkout-add-form">
			<h4 class="title15">Select <?php echo $setType; ?> Address</h4>
			<?PHP 
				$userAddressData = $functions->getAddressByuserId($loggedInUserDetailsArr['id']);
					if($functions->num_rows($userAddressData)>0){
						while($addressDetails = $functions->fetch($userAddressData)){
			?>
							<div class="address-pop">
								<?php 
									if(isset($_GET['Billing'])){
								?>
										<label class="container-r"><?php echo ucwords($addressDetails["customer_fname"]).", ".$addressDetails["address1"]; ?>
											<input type="radio" name="radio2" <?php if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['Billing']) && $_SESSION[SITE_NAME]['BILLADDRESS']['Billing'] == $addressDetails["id"]){ echo "checked"; } ?> value="<?php echo $addressDetails["id"]; ?>" <?php ?>>
											<span class="checkmark-r"></span>
										</label>
								<?php 	
									}if(isset($_GET['shipping'])){ ?>
										<label class="container-r"><?php echo ucwords($addressDetails["customer_fname"]).", ".$addressDetails["address1"]; ?>
											<input type="radio" name="radio2" <?php if(isset($_SESSION[SITE_NAME]['BILLADDRESS']['shipping']) && $_SESSION[SITE_NAME]['BILLADDRESS']['shipping'] == $addressDetails["id"]){ echo "checked"; } ?> value="<?php echo $addressDetails["id"]; ?>" <?php ?>>
											<span class="checkmark-r"></span>
										</label>
								<?php 
									} ?>		
							</div>	
			<?php 
						}
					}else{
			?>
						<br><br><br><center><h4>Please Add <?php echo $setType; ?> Address.</h4><br><br></center>
			<?php 
					}
			?>
			<!-- <input type="submit" name="update-address" class="shop-now-btn black-btn pull-left" value="Update"> -->
			<a href="<?php echo BASE_URL;?>/add-address.php?<?php echo $setType."=".$setType; ?>" class="shop-now-btn black-btn">Add New Address</a>
			<div class="clearfix"></div>
		</form>

 	  
         <!--Main End Code Here-->
 
      <!--footer end menu head-->
      <?php include("include/footer-link.php");?>
      	<script>
	      	$('input[type=radio][name=radio2]').change(function() {
			    window.location.href = "<?php echo BASE_URL; ?>/checkout-summery-popup.php?setType=<?php echo $setType; ?>&id="+this.value;
			});
	  	</script>
   </body>
</html>