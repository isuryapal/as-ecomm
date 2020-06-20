<?php 
    Include_once("include/functions.php");
    $functions = New Functions();
    
    if(!$loggedInUserDetailsArr = $functions->sessionExists()){
        header("location: ".BASE_URL."/login.php");
        exit;
    }
    // print_r($loggedInUserDetailsArr);die();
    
    if($loggedInUserDetailsArr['user_type'] == "b2c"){
        header("location: ".BASE_URL."/login.php?failed&cusLogin");
        exit;   
    }

    if(isset($_POST['id']) && !empty($_POST['id'])){
        $functions->updateRegisteredUser($_POST, $loggedInUserDetailsArr['id']);
        header("location:cus-my-account.php?success");
        exit;
    }

    if(isset($_GET['delid']) && !empty($_GET['delid'])){
		$delid = trim($functions->strip_all($_GET['delid']));

		$sql = "SELECT * FROM ".PREFIX."product_master WHERE id='".$delid."'";
		$result = $functions->query($sql);
		if($functions->num_rows($result)>0){
			$certi = $functions->fetch($result);

			$certimage = str_replace('', '-', strtolower( pathinfo($certi['main_image'], PATHINFO_FILENAME)));
			$certimage_ext = pathinfo($certi['main_image'], PATHINFO_EXTENSION);
			if(file_exists("images/products/".$certimage.'_crop.'.$certimage_ext)){
				unlink("images/products/".$certimage.'_crop.'.$certimage_ext);
				unlink("images/products/".$certimage.'_large.'.$certimage_ext);
			}

			$certimage = str_replace('', '-', strtolower( pathinfo($certi['image_one'], PATHINFO_FILENAME)));
			$certimage_ext = pathinfo($certi['image_one'], PATHINFO_EXTENSION);
			if(file_exists("images/products/".$certimage.'_crop.'.$certimage_ext)){
				unlink("images/products/".$certimage.'_crop.'.$certimage_ext);
				unlink("images/products/".$certimage.'_large.'.$certimage_ext);
			} 

			$certimage = str_replace('', '-', strtolower( pathinfo($certi['image_two'], PATHINFO_FILENAME)));
			$certimage_ext = pathinfo($certi['image_two'], PATHINFO_EXTENSION);
			if(file_exists("images/products/".$certimage.'_crop.'.$certimage_ext)){
				unlink("images/products/".$certimage.'_crop.'.$certimage_ext);
				unlink("images/products/".$certimage.'_large.'.$certimage_ext);
			} 
			
			$certimage = str_replace('', '-', strtolower( pathinfo($certi['image_three'], PATHINFO_FILENAME)));
			$certimage_ext = pathinfo($certi['image_three'], PATHINFO_EXTENSION);
			if(file_exists("images/products/".$certimage.'_crop.'.$certimage_ext)){
				unlink("images/products/".$certimage.'_crop.'.$certimage_ext);
				unlink("images/products/".$certimage.'_large.'.$certimage_ext);
			} 

			$certimage = str_replace('', '-', strtolower( pathinfo($certi['image_four'], PATHINFO_FILENAME)));
			$certimage_ext = pathinfo($certi['image_four'], PATHINFO_EXTENSION);
			if(file_exists("images/products/".$certimage.'_crop.'.$certimage_ext)){
				unlink("images/products/".$certimage.'_crop.'.$certimage_ext);
				unlink("images/products/".$certimage.'_large.'.$certimage_ext);
			} 

			//$admin->deleteAllProductMappingbyProeductID($delid);


			$Upsql = "DELETE FROM ".PREFIX."product_master WHERE `id`='".$delid."'";
			$functions->query($Upsql);
			header('Location:'.$pageURL.'?deletesuccess');
			exit;	
		}

		
	}

    $sql = "SELECT * FROM ".PREFIX."product_master WHERE `vendor_id`='".$loggedInUserDetailsArr['id']."' order by id DESC";
    $vendorProductResult = $functions->query($sql);
    //print_r($loggedInUserDetailsArr);
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
				<li>My Product</li>
			</ul>
		</div>
    </div>
    <section class="myproduct">
        <div class="inner-content bt">
            <div class="container">
                <div class="ac-detail-nav-box">
	                <ul class="ac-detail-nav">
	                    <li> <a href="vendor-myaccount.php"><i class="fa fa-user-o" aria-hidden="true"></i>  My Account</a></li>
	                    <li><a href="vendor-orderreceived.php"><i class="fa fa-bars" aria-hidden="true"></i> Order Received</a></li>
	                    <li class="active"><a href="vendor-myproducts.php"><i class="fa fa-heart-o" aria-hidden="true"></i> My Products</a></li>
	                    <div class="clearfix"></div>
					</ul>
				</div>
				<div class="addproduct">
					<a href="add-products.php">ADD PRODUCT</a>
				</div>
                <div class="row">
            	<?php 
                    if(isset($_GET['registersuccess'])){
                ?>
                        <div class="alert alert-success">
                            Product Added Successfully.
                        </div>
                <?php   
            		}
            		if(isset($_GET['deletesuccess'])){ ?>
            			<div class="alert alert-success">
                            Product Deleted Successfully.
                        </div>
            	<?php 

            		} ?>		
				<div class="col-sm-10 col-sm-pull-1 col-sm-push-1">
 					<div class="field-box">
 						<?php 
 							if($functions->num_rows($vendorProductResult)>0){
 						?>
								<div class="table-responsive">
								 	<table class="table">
										<thead>
									  		<tr>
												<th>Sr.No.</th>
												<th>Product Image</th>
												<th class="product-details">Product Name</th>
												<th>Price</th>
												<th>Action</th>
		 							  		</tr>
										</thead>
										<?php 
											$i=1;
											while($vendorProduct = $functions->fetch($vendorProductResult)){
												
												$productBanner = $functions->getImageUrl('products',$vendorProduct['main_image'],'crop','');
												if(isset($vendorProduct['b2b_discount_price']) && !empty($vendorProduct['b2b_discount_price'])){
													$price = $vendorProduct['b2b_discount_price'];
												}else{
													$price = $vendorProduct['b2b_price'];
												}

										?>
												<tbody>
												  	<tr>
														<td><?php echo $i; ?>.</td>
														<td><div class="product-image"><img style="width: 100px;" src="<?php echo $productBanner; ?>" alt="<?php echo $vendorProduct["product_name"]; ?>"/></div></td>
														<td><p><?php echo ucwords($vendorProduct["product_name"]); ?></p></td>
														<td><i class="fa fa-inr" aria-hidden="true"></i><?php echo $price; ?></td>
														<td>
															<a  href="<?php echo BASE_URL.'/add-products.php?edit&id='.$vendorProduct['id']; ?>" class="btn action-btn"><img src="<?php echo BASE_URL; ?>/images/edit.png" alt=""></a>
															<a href="<?php echo BASE_URL."/vendor-myproducts.php?delid=".$vendorProduct["id"]; ?>" class="delete-btn"><img style="width: 17px;" src="<?php echo BASE_URL; ?>/images/delete.png"/></a>
														</td>
					 							  	</tr>
				 								</tbody>
			 							<?php 
			 								$i++;
			 								} ?>	
								  	</table>
								</div>
						<?php 
							}else{ ?>
								<br><br><br><center><p>Product not yet added.</p></center>
						<?php 
							} ?>		
					</div>
				</div>
			</div>
            </div>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
    <?php include("include/footer-link.php"); s?>
   </body>
</html>