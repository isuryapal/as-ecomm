<?PHP 
	Include_once("include/functions.php");
    $functions = New Functions();
    
    if(!$loggedInUserDetailsArr = $functions->sessionExists()){
        header("location: ".BASE_URL."/login.php");
        exit;
    }
    
    if(!$loggedInUserDetailsArr['user_type']){
        header("location: ".BASE_URL."/login.php?failed&cusLogin");
        exit;   
    }
    if(isset($_GET['setDetaultAddress']) && isset($_GET['id']) && !empty($_GET['id'])){
		$defaultAddress = $functions->setAsDefaultAddress($_GET,$loggedInUserDetailsArr);
		if($defaultAddress){
			header("location:cus-myaddressbook.php?successDefault");
			exit;
		}else{
			header("location:cus-myaddressbook.php?failureDefault");
			exit;
		}
	}
	if(isset($_GET['delId']) && !empty($_GET['delId'])){
		$delId = $functions->escape_string($functions->strip_all($_GET['delId']));
		$sql = "DELETE FROM ".PREFIX."customers_address WHERE id='".$delId."'";
		$functions->query($sql);
		header("location:cus-myaddressbook.php?deletSuccess");
		exit;
	}
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
				<li>My Order</li>
			</ul>
		</div>
    </div>
    <section class="myaddressbook">
        <div class="inner-content bt">
            <div class="container">
                <div class="ac-detail-nav-box">
                	<ul class="ac-detail-nav">
                		<?php if($loggedInUserDetailsArr['user_type']=="b2b"){ ?>
                			<li><a href="vendor-myaccount.php"><i class="fa fa-user-o" aria-hidden="true"></i>  My Account</a></li>	
                		<?php }else{ ?>
                			<li><a href="cus-my-account.php"><i class="fa fa-user-o" aria-hidden="true"></i>  My Account</a></li>
                		<?php } ?>
	                    <li><a href="cus-myorder.php"><i class="fa fa-bars" aria-hidden="true"></i>My Orders</a></li>
	                    <li><a href="cus-mywishlist.php"><i class="fa fa-heart-o" aria-hidden="true"></i> Wishlist</a></li>
	                    <li class="active"><a href="cus-myaddressbook.php"><i class="fa fa-map-marker" aria-hidden="true"></i> Address Book</a></li>
	                    <div class="clearfix"></div>
                	</ul>
                </div>
                <div class="row">
                	<?php 
						if(isset($_GET['successDefault'])){
					?>
							<div class="alert alert-success">
			                    <p><i class="fa fa-check"></i>Address successfully marked as default billing address.</p>
			                </div>
					<?php 	
						}if(isset($_GET['deletSuccess'])){
					?>
							<div class="alert alert-success">
			                    <p><i class="fa fa-check"></i>Address successfully deleted.</p>
			                </div>
					<?php 	
						}if(isset($_GET['success'])){ ?>
	                		<br>
	                		<div class="alert alert-success">
	                    		<p><i class="fa fa-check"></i> Address saved successfully</p>
	                		</div>
					<?php   	
						} ?>
				<div class="col-md-10 col-md-pull-1 col-md-push-1 col-sm-12">
					<div class="row paddmesks">
						<?php 
							$userAddressData = $functions->getAddressByuserId($loggedInUserDetailsArr['id']);
							if($functions->num_rows($userAddressData)>0){
								while($addressDetails = $functions->fetch($userAddressData)){
						?>
								<div class="col-lg-4 col-md-6 col-sm-6">
									<div class="field-box match">
										<div class="field">
											<h5>Name</h5>
											<p><?php echo ucwords($addressDetails['customer_fname']); ?></h5>
										</div>
										<div class="field">
											<h5>Contact</h5>
											<p><?php echo $addressDetails['customer_contact']; ?></p>
										</div>
										<div class="field">
											<h5>Address</h5>
											<p><?php echo $addressDetails['address1'];  ?></p>
											<p><?php echo $addressDetails['address2'];  ?></p>
										</div>
										<div class="field">
											<ul class="list-inline field-btn-grp">
												<li><a class="change-address" data-fancybox="" data-type="iframe" data-src="<?php echo BASE_URL; ?>/add-address.php?q=<?php echo $addressDetails['id'] ?>" href="javascript:;">Edit Address</a></li>
												<li><a onclick="return confirm('Are you sure you want to delete this address?');" class="change-address" href="<?php echo BASE_URL."/cus-myaddressbook.php?delId=".$addressDetails['id'] ?>"><i class="fa fa-trash-o" aria-hidden="true"></i> </a></li>
											</ul>
										</div>
										<div class="field">
											<?php 
												if($addressDetails['setDefault']){
											?>
													<a href="javascript:;" class="setadif">Default Address</a>
											<?php 
												}else{ ?>
													<a onclick="return confirm('Are you sure you want set default address?');" href="<?php echo BASE_URL."/cus-myaddressbook.php?setDetaultAddress&id=".$addressDetails['id']; ?>" class="seta">Set As Default</a>
											<?php 	
												} ?>		
										</div>
									</div>
								</div>
						<?php 	}
							}else{ ?>
								<br><br><center style="margin-top:0px;"><h3>Address not Added yet.</h3></center>	
						<?php 
							} ?>
							<div class="clearfix"></div>
						<a class="btn address-btn" data-fancybox="" data-type="iframe" data-src="<?php echo BASE_URL; ?>/add-address.php" href="javascript:;">Add Address</a>
					</div>
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
      	$(".selectaddess").fancybox({
        	toolbar  : false,
			smallBtn : true,
			iframe : {
				css : {
					width : '1000px'
				}
			}
		});
    </script>
    <script>
    	$(document).ready(function(){
	    	$(".setDefault").click(function(){
		  		if($(this).prop("checked") == true){
		  		 	var result = confirm("Are sure you want to set this address as default address?");
					if (result) {
		  		 		window.location.href = "<?php echo BASE_URL; ?>/my-addressbook.php?setDetaultAddress&id="+this.value;
		  		 	}else{
						$(this).prop('checked', false);
					}
		  		}
		  	});
			$('.address-btn').fancybox({
		  		iframe : {
		         	css : {
		             	width : '800px',
		         	}
		  		},
		     	buttons : [
		      		'close'
		      	],
		  		afterClose: function () {
		    		parent.location.reload(true);
		 		}
		  	});
	  	});
	</script>
   </body>
</html>