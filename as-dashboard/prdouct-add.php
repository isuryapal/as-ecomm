<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();

	$pageName = "Product";
	$parentPageURL = 'product-master.php';
	$pageURL = 'prdouct-add.php';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	
	//include_once 'csrf.class.php';
	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);

	if(isset($_POST['product_name']) && empty($_POST['id'])){
		//add to database
        $result = $admin->addProduct($_POST,$_FILES);
        header("location:".$pageURL."?registersuccess");
        exit;
		
	}
	if(isset($_GET['edit'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$data = $admin->getUniqueProductById($id);
	}
	if(isset($_POST['id']) && !empty($_POST['id'])) {
        //update to database
        $result = $admin->updateProduct($_POST,$_FILES);

        $productDetails = $admin->getUniqueProductById($_POST['id']);
        $vendorDetails = $admin->getVendorDetailsByvendorId($productDetails['vendor_id']);
        $name ='';
        $h4heading ='';
        $content ='';
        if(isset($vendorDetails['id']) && !empty($vendorDetails['id']) && $_POST['active']=="Yes"){
        	$h4heading   = "Product ".$productDetails['product_name']."/".$productDetails['product_code']." is live now.";
	        $content    .= "<p>Dear ".$vendorDetails['first_name'].",</p>";
	        $content    .= "<p>Following product is live now </p>";
	        
	        $content    .= "<p>Product Name : ".$productDetails['product_name']."</p>";
	        $content    .= "<p>Product Code : ".$productDetails['product_code']."</p>";
	        
	        include_once("../include/classes/Email.class.php");
	        include_once("../thank-you.php");

	        $emailObj = new Email();
	        $emailObj->setSubject(SITE_NAME." | Product ".$productDetails['product_name']."/".$productDetails['product_code']." is live now");
	        $emailObj->setAddress($vendorDetails['email']);
	        $emailObj->setEmailBody($emailMsg);
	        $emailObj->sendEmail();
        }
        header("location:".$pageURL."?updatesuccess&edit&id=".$id);
        exit();
	}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo TITLE ?></title>
	<link rel="shortcut icon" href="<?php echo BASE_URL; ?>/images/logo.png" type="image/png" />
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/londinium-theme.min.css" rel="stylesheet" type="text/css">
	<link href="css/styles.min.css" rel="stylesheet" type="text/css">
	<link href="css/icons.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/nanoscroller.css" rel="stylesheet">
	<link href="css/emoji.css" rel="stylesheet">
	<link href="css/cover.css" rel="stylesheet">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/plugins/charts/sparkline.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/uniform.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/select2.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/inputmask.js"></script>
	<script type="text/javascript" src="js/plugins/forms/autosize.js"></script>
	<script type="text/javascript" src="js/plugins/forms/inputlimit.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/listbox.js"></script>
	<script type="text/javascript" src="js/plugins/forms/multiselect.js"></script>
	<script type="text/javascript" src="js/plugins/forms/validate.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/tags.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/switch.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/uploader/plupload.full.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/uploader/plupload.queue.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/wysihtml5/wysihtml5.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/wysihtml5/toolbar.js"></script>
	<script type="text/javascript" src="js/plugins/interface/daterangepicker.js"></script>
	<script type="text/javascript" src="js/plugins/interface/fancybox.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/moment.js"></script>
	<script type="text/javascript" src="js/plugins/interface/jgrowl.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/datatables.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/colorpicker.js"></script>
	<script type="text/javascript" src="js/plugins/interface/fullcalendar.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/timepicker.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/collapsible.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/application.js"></script>
	<script type="text/javascript" src="js/additional-methods.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$("#form").validate({
                ignore:[],
				rules: {
					category:{
						required:true,
					},
					/*sub_category: {
						required: true,
					},*/
					product_name: {
                        required: true,
                    },
                    price: {
						number: true,
						//min: 1,
					},
					discount_price: {
						number: true,
						//min: 0,
						//lessthan: '#price',
					},
					b2b_price: {
						number: true,
					},
					b2b_discount_price: {
						number: true,
					},
                    product_code:{
						//required:true,
						remote: {
							<?php if(isset($_GET['id']) && !empty($_GET['id'])){ ?>
							data: {id:'<?php echo $_GET['id']; ?>' },
							<?php } ?>
							url: "ajaxCheckProductCode.php",
							type: "post",
						},
						
					},
                    botanical_name: {
                        required: true,
                    },
                    
					image: {
						extension: 'jpg|jpeg|png'
					},
					catalogue: {
						extension: 'pdf'
					},
          
				},
				messages:{
					product_code: {
						remote: "Product code alredy exists"
					}
				}
			});
			jQuery.validator.addMethod("letterswithspaceonly", function(value, element) {
			return this.optional(element) || /^[^-\s][a-zA-Z\s-]+$/i.test(value);
			}, "Please enter valid value");
			$.validator.addMethod("lessthan",
			function (value, element, param) {
			  var $min = $(param);
			  if (this.settings.onfocusout) {
				$min.off(".validate-greaterThan").on("blur.validate-greaterThan", function () {
				  $(element).valid();
				});
			  }
			  return parseInt(value) < parseInt($min.val());
			}, "Discount price must be less than product standard price");
		});
		function getCategoryFilters() {
			//console.log($(this).val());
			var category_id=[]; 
			$('select[name="category[]"] option:selected').each(function() {
			  category_id.push($(this).val());
			});
			//console.log("val1",val1);
			$.ajax({
				url:"ajaxGetCategoryFilters.php",
				data:{category_id:category_id},
				type:"GET",
				success: function(response){
					var response = JSON.parse(response);
					$("#product-filter-div").html(response.responseContent);
				},
				error: function(){
					alert("Unable to add to cart, please try again");
				},
				complete: function(response){
					
				}
			});
		}
	</script>
</head>
<body class="sidebar-wide">
	<?php include 'include/navbar.php' ?>

	<div class="page-container">

		<?php include 'include/sidebar.php' ?>

		<div class="page-content">

		<div class="breadcrumb-line">
			<div class="page-ttle hidden-xs" style="float:left;">
<?php
				if(isset($_GET['edit'])){ ?>
					<?php echo 'Edit '.$pageName; ?>
<?php			} else { ?>
					<?php echo 'Add New '.$pageName; ?>
<?php			} ?>
			</div>
			<ul class="breadcrumb">
				<li><a href="banner-master.php">Home</a></li>
				<li><a href="<?php echo $parentPageURL; ?>"><?php echo $pageName; ?></a></li>
				<li class="active">
<?php
				if(isset($_GET['edit'])){ ?>
					<?php echo 'Edit '.$pageName; ?>
<?php			} else { ?>
					<?php echo 'Add New '.$pageName; ?>
<?php			} ?>
				</li>
			</ul>
		</div>

		<a href="<?php echo $parentPageURL; ?>" class="label label-primary">Back to <?php echo $pageName; ?></a><br/><br/>
<?php
		if(isset($_GET['registersuccess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark3"></i> <?php echo $pageName; ?> successfully added.
			</div><br/>
<?php	} ?>
	
<?php
		if(isset($_GET['registerfail'])){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i> <strong><?php echo $pageName; ?> not added.</strong> <?php echo $admin->escape_string($admin->strip_all($_GET['msg'])); ?>.
			</div><br/>
<?php	} ?>

<?php
		if(isset($_GET['updatesuccess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark3"></i> <?php echo $pageName; ?> successfully updated.
			</div><br/>
<?php	} ?>
	
<?php
		if(isset($_GET['updatefail'])){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i> <strong><?php echo $pageName; ?> not updated.</strong> <?php echo $admin->escape_string($admin->strip_all($_GET['msg'])); ?>.
			</div><br/>
<?php	} ?>
			<form role="form" action="" method="post" id="form" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6 class="panel-title"><i class="icon-library"></i>Product Details</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
                            <div class="row">
								<div class="col-sm-4">
                                    <label>Product Name<span style="color:red;">*</span></label>                                
                                    <input type="text" name="product_name" id="product_name" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $data['product_name']; } ?>" required/>
                                </div>
                                <div class="col-sm-4">
                                    <label>Product Code<span style="color:red;">*</span></label>
                                   	<input type="text" name="product_code" id="product_code" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $data['product_code']; } ?>" required/>
								</div>
								<div class="col-sm-4">
                                    <label>HSN Code<span style="color:red;">*</span></label>
                                   	<input type="text" name="hsn_code" id="hsn_code" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $data['hsn_code']; } ?>" required/>
								</div>
                            </div>
                            <br>
                            <div class="row">
                            	<div class="col-sm-3">
                                    <label>Availability</label>
                                    <input type="text" name="availability" id="availability" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $data['availability']; } ?>"/>
                                </div>
                                <div class="col-sm-3">
                                    <label>B2C Price</label>
                                    <input type="text" name="price" id="price" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $data['price']; } ?>" />
                                </div>
                                <div class="col-sm-3">
                                    <label>B2C Discounted Price</label>
                                    <input type="text" name="discount_price" id="discount_price" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $data['discount_price']; } ?>"/>
                                </div>
                                <div class="col-sm-3">
                                    <label>B2B Price</label>
                                    <input type="text" name="b2b_price" id="b2b_price" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $data['b2b_price']; } ?>" />
                                </div>
                                <div class="col-sm-3">
                                    <label>B2B Discounted Price</label>
                                    <input type="text" name="b2b_discount_price" id="b2b_discount_price" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $data['b2b_discount_price']; } ?>"/>
                                </div>
                                <div class="col-sm-3">
                                    <label>B2B Minimum Quantity</label>
                                    <input type="text" name="b2b_min_qty" id="b2b_min_qty" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $data['b2b_min_qty']; } ?>"/>
                                </div>
                                <div class="col-sm-3">
                                    <label>GST Tax<span style="color:red;">*</span></label>
                                    <select class="form-control" name="tax" required>
                                    	<option value="">Please Select Tax</option>
                                    	<option <?php if(isset($_GET['edit']) && $data['tax']=="0"){ echo "selected"; } ?> value="0">0 %</option>
                                    	<option <?php if(isset($_GET['edit']) && $data['tax']=="5"){ echo "selected"; } ?> value="5">5 %</option>
                                    	<option <?php if(isset($_GET['edit']) && $data['tax']=="12"){ echo "selected"; } ?> value="12">12 %</option>
                                    	<option <?php if(isset($_GET['edit']) && $data['tax']=="18"){ echo "selected"; } ?> value="18">18 %</option>
                                    	<option <?php if(isset($_GET['edit']) && $data['tax']=="28"){ echo "selected"; } ?> value="28">28 %</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                               <div class="col-sm-3">
                                    <label>Main Image<span style="color:red;">*</span></label>
                                    <input type="file" class="form-control" name="main_image" id="main_image" accept="image/jpg,image/png,image/jpeg" id="" data-image-index="0" <?php if(isset($data['main_image']) && !empty($data['main_image'])){ }else{ echo "required"; } ?> value="<?php echo $data['image'] ?>" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>png jpg jpeg</strong>.<br>
										Images must be exactly <strong>1000x1000</strong> pixels.
									</span>
									<br>
									<?php if(isset($_GET['edit'])){
										$file_name = str_replace('', '-', strtolower( pathinfo($data['main_image'], PATHINFO_FILENAME)));
										$ext = pathinfo($data['main_image'], PATHINFO_EXTENSION);
										if(!empty($data['main_image'])){
									?>
											<img src="../images/products/<?php echo $file_name.'_crop.'.$ext ?>" width="100"  />
                                    <?php }else{ ?>
                                    		<img src="<?php echo BASE_URL.'/images/default.jpg'; ?>" width="100">
                                    <?php } 
                                    	}
                                    ?>
                                </div> 
                                <div class="col-sm-3">
									<label>Featured Product</label>
									<select class="form-control" name="feature_product">
										<option value="Yes" <?php if(isset($_GET['edit']) and $data['feature_product']=='Yes') { echo 'selected'; } ?>>Yes</option>
										<option value="No" <?php if(isset($_GET['edit']) and $data['feature_product']=='No') { echo 'selected'; } ?>>No</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label>Active</label>
									<select class="form-control" name="active">
										<option value="Yes" <?php if(isset($_GET['edit']) and $data['active']=='Yes') { echo 'selected'; } ?>>Yes</option>
										<option value="No" <?php if(isset($_GET['edit']) and $data['active']=='No') { echo 'selected'; } ?>>No</option>
									</select>
								</div>
                            </div>
                            <div class="row">
                              	<div class="col-sm-3">
                                    <label>Image</label>
                                    <input type="file" class="form-control" name="image_one" id="image_one" accept="image/jpg,image/png,image/jpeg" id="1" data-image-index="1" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>png jpg jpeg</strong>.<br>
										Images must be exactly <strong>1000x1000</strong> pixels.
									</span>
									<br>
									<?php 
										if(isset($_GET['edit'])){
											$file_name = str_replace('', '-', strtolower( pathinfo($data['image_one'], PATHINFO_FILENAME)));
											$ext = pathinfo($data['image_one'], PATHINFO_EXTENSION);
											if(!empty($data['image_one'])){
									?>
												<img src="../images/products/<?php echo $file_name.'_crop.'.$ext ?>" width="100"  />
                                    <?php 
                                			}
                                    	}
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Image</label>
                                    <input type="file" class="form-control" name="image_two" id="image_two" accept="image/jpg,image/png,image/jpeg" id="2" data-image-index="2"/>
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>png jpg jpeg</strong>.<br>
										Images must be exactly <strong>1000x1000</strong> pixels.
									</span>
									<br>
									<?php 
										if(isset($_GET['edit'])){
											$file_name = str_replace('', '-', strtolower( pathinfo($data['image_two'], PATHINFO_FILENAME)));
											$ext = pathinfo($data['image_two'], PATHINFO_EXTENSION);
											if(!empty($data['image_two'])){
									?>
												<img src="../images/products/<?php echo $file_name.'_crop.'.$ext ?>" width="100"  />
                                    <?php 	} 
                                    	}
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Image</label>
                                    <input type="file" class="form-control" name="image_three" id="image_three" accept="image/jpg,image/png,image/jpeg" id="3" data-image-index="3" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>png jpg jpeg</strong>.<br>
										Images must be exactly <strong>1000x1000</strong> pixels.
									</span>
									<br>
									<?php 
										if(isset($_GET['edit'])){
											$file_name = str_replace('', '-', strtolower( pathinfo($data['image_three'], PATHINFO_FILENAME)));
											$ext = pathinfo($data['image_three'], PATHINFO_EXTENSION);
											if(!empty($data['image_three'])){
									?>
												<img src="../images/products/<?php echo $file_name.'_crop.'.$ext ?>" width="100"  />
                                    <?php 	} 
                                    	}
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Image</label>
                                    <input type="file" class="form-control" name="image_four" id="image_four" accept="image/jpg,image/png,image/jpeg" id="4" data-image-index="4"  />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>png jpg jpeg</strong>.<br>
										Images must be exactly <strong>1000x1000</strong> pixels.
									</span>
									<br>
									<?php 
										if(isset($_GET['edit'])){
											$file_name = str_replace('', '-', strtolower( pathinfo($data['image_four'], PATHINFO_FILENAME)));
											$ext = pathinfo($data['image_four'], PATHINFO_EXTENSION);
											if(!empty($data['image_four'])){
									?>
												<img src="../images/products/<?php echo $file_name.'_crop.'.$ext ?>" width="100"  />
                                    <?php 
                                			}
                                    	}
                                    ?>
                                </div>                               
                            </div>
                            <br>
                            <div class="row">
                            	<div class="col-sm-3">
                                    <label>Video</label>
                                    <input type="file" class="form-control video-size" name="video_one" id="video_one" accept="video/mp4,video/mov,video/avi" id="5" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>mp4 mov avi</strong>.<br>
									</span>
									<br>
									<?php 
										if(isset($_GET['edit'])){
											$file_name = str_replace('', '-', strtolower( pathinfo($data['video_one'], PATHINFO_FILENAME)));
											$ext = pathinfo($data['video_one'], PATHINFO_EXTENSION);
											if(!empty($data['video_one'])){
									?>
												<!-- <img src="../images/products/<?php echo $file_name.'_crop.'.$ext ?>" width="100"  /> -->
												<video width="300" height="200" controls>
													<source src="../videos/products/<?php echo $file_name.".".$ext ?>" type="video/mp4">
												</video> 
                                    <?php 
                                			}
                                    	}
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Video</label>
                                    <input type="file" class="form-control video-size" name="video_two" id="video_two" accept="video/mp4,video/mov,video/avi" id="6" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>mp4 mov avi</strong>.<br>
									</span>
									<br>
									<?php 
										if(isset($_GET['edit'])){
											$file_name = str_replace('', '-', strtolower( pathinfo($data['video_two'], PATHINFO_FILENAME)));
											$ext = pathinfo($data['video_two'], PATHINFO_EXTENSION);
											if(!empty($data['video_two'])){
									?>
												<!-- <img src="../videos/products/<?php echo $file_name.'_crop.'.$ext ?>" width="100"  /> -->
												<video width="300" height="200" controls>
													<source src="../videos/products/<?php echo $file_name.".".$ext ?>" type="video/mp4">
												</video> 
                                    <?php 
                                			}
                                    	}
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Video</label>
                                    <input type="file" class="form-control video-size" name="video_three" id="video_three" accept="video/mp4,video/mov,video/avi" id="7" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>mp4 mov avi</strong>.<br>
									</span>
									<br>
									<?php 
										if(isset($_GET['edit'])){
											$file_name = str_replace('', '-', strtolower( pathinfo($data['video_three'], PATHINFO_FILENAME)));
											$ext = pathinfo($data['video_three'], PATHINFO_EXTENSION);
											if(!empty($data['video_three'])){
									?>
												<!-- <img src="../videos/products/<?php echo $file_name.'_crop.'.$ext ?>" width="100"  /> -->
												<video width="300" height="200" controls>
													<source src="../videos/products/<?php echo $file_name.".".$ext ?>" type="video/mp4">
												</video> 
                                    <?php 
                                			}
                                    	}
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Video</label>
                                    <input type="file" class="form-control video-size" name="video_four" id="video_four" accept="video/mp4,video/mov,video/avi" id="8" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>mp4 mov avi</strong>.<br>
									</span>
									<br>
									<?php 
										if(isset($_GET['edit'])){
											$file_name = str_replace('', '-', strtolower( pathinfo($data['video_four'], PATHINFO_FILENAME)));
											$ext = pathinfo($data['video_four'], PATHINFO_EXTENSION);
											if(!empty($data['video_four'])){
									?>
												<!-- <img src="../videos/products/<?php echo $file_name.'_crop.'.$ext ?>" width="100"  /> -->
												<video width="300" height="200" controls>
													<source src="../videos/products/<?php echo $file_name.".".$ext ?>" type="video/mp4">
												</video> 
                                    <?php 
                                			}
                                    	}
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Video</label>
                                    <input type="file" class="form-control video-size" name="video_five" id="video_five" accept="video/mp4,video/mov,video/avi" id="9" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>mp4 mov avi</strong>.<br>
									</span>
									<br>
									<?php 
										if(isset($_GET['edit'])){
											$file_name = str_replace('', '-', strtolower( pathinfo($data['video_five'], PATHINFO_FILENAME)));
											$ext = pathinfo($data['video_five'], PATHINFO_EXTENSION);
											if(!empty($data['video_five'])){
									?>
												<!-- <img src="../videos/products/<?php echo $file_name.'_crop.'.$ext ?>" width="100"  /> -->
												<video width="300" height="200" controls>
													<source src="../videos/products/<?php echo $file_name.".".$ext ?>" type="video/mp4">
												</video> 
                                    <?php 
                                			}
                                    	}
                                    ?>
                                </div>
                            </div>
                           	<div class="row">
                           		<div class="col-sm-4"><br>
                                    <label>Category <span style="color:red;">*<span></label>
                                    <select  required class="select-multiple" multiple name="category[]" id="category">
                                        
                                        <?php 
                                        	$query = $admin->query("select * from ".PREFIX."category_master where active='Yes' order by category_name ASC");
                                        	
                                            while($row = $admin->fetch($query)){
                                            	if(isset($_GET['edit'])){
                                            		$updatequry = $admin->query("SELECT * FROM ".PREFIX."product_category_mapping WHERE product_id='".$id."' and category_id='".$row['id']."'");
                                            		$catDetails = $admin->fetch($updatequry);
                                            		//print_r($catDetails);
                                            	}
                                            ?>
                                                <option value="<?php echo $row['id']; ?>" <?php if(isset($_GET['edit']) && $row['id']==$catDetails['category_id']){ echo "selected"; } ?> ><?php echo $row['category_name']; ?></option>
                                            <?php  }  ?>
                                    </select>
								</div>
								<div class="col-sm-4"><br>
                                    <label>Subcategory</label>
                                    <?php 
                                    	$catArr = array();
                                    	
                                    ?>
                                    <select class="select-multiple" multiple name="sub_cat[]" id="sub_category_id">
                                        
                                        <?php 
                                        	if(isset($_GET['edit'])){
                                        		$subsql= $admin->query("SELECT * FROM ".PREFIX."sub_category_master WHERE `active` ='1' order by `sub_category_name` ASC");

                                            	while($row = $admin->fetch($subsql)){
                                        			$query = $admin->query("SELECT * from ".PREFIX."product_subcategory_mapping WHERE `product_id`='".$id."' and subscategory_id='".$row['id']."'");
                                            		$subscbCat = $admin->fetch($query);

                                        ?>
                                                	<option value="<?php echo $row['id']; ?>" <?php if(isset($_GET['edit']) && $row['id']==$subscbCat['subscategory_id']){ echo "selected"; } ?> ><?php echo $row['sub_category_name']; ?></option>
                                       	<?php  
                                        		}  
                                        	}
                                       	?>
                                    </select>
								</div>
								<div class="col-sm-4"><br><!-- select-multiple -->
									<label>Sub SubCategory Type</label>
									<select class="select-multiple" name="Subsub_category[]" multiple id="Subsub_category">
										
										<?php
											if(isset($_GET['edit'])) {
												$subsub = $admin->query("SELECT * FROM ".PREFIX."subsubCategory where active='Yes' order by subcategory_name ASC");

												while($subCategoryDetail = $admin->fetch($subsub)){
													$subCategorySQL = $admin->getAllSubSubCategoriesbyProductID($id,$subCategoryDetail['id']);
													$subsubCdetail = $admin->fetch($subCategorySQL);

													
										?>
												<option value="<?php echo $subCategoryDetail['id']; ?>" <?php if(isset($_GET['edit']) and $subsubCdetail['subsubcategory_id']==$subCategoryDetail['id']) { echo 'selected'; } ?>><?php echo $subCategoryDetail['subcategory_name']; ?></option>
										<?php
											
												}
											}
										?>
									</select>
								</div>
								<div class="col-sm-4"><br><!-- select-multiple -->
									<label>Sub Sub SubCategory Type</label>
									<select class="select-multiple" name="Subsubsub_category[]" multiple id="Subsubsub_category">
										
										<?php
											if(isset($_GET['edit'])) {
												$subsubsub = $admin->query("SELECT * FROM ".PREFIX."subsubsubCategory where active='1' order by subsubsub_name ASC");

												while($subsubsubCategoryDetail = $admin->fetch($subsubsub)){
													$subsubsubCategorySQL = $admin->getAllSubSubSubCategoriesbyProductID($id,$subsubsubCategoryDetail['id']);
													$subsubsubCdetail = $admin->fetch($subsubsubCategorySQL);

													
										?>
												<option value="<?php echo $subsubsubCategoryDetail['id']; ?>" <?php if(isset($_GET['edit']) and $subsubsubCdetail['subsubsubcategory_id']==$subsubsubCategoryDetail['id']) { echo 'selected'; } ?>><?php echo $subsubsubCategoryDetail['subsubsub_name']; ?></option>
										<?php
											
												}
											}
										?>
									</select>
								</div>
								<div class="col-sm-4"><br><!-- select-multiple -->
									<label>Sub Sub Sub SubCategory Type</label>
									<select class="select-multiple" name="Subsubsubsub_category[]" multiple id="Subsubsubsub_category">
										
										<?php
											if(isset($_GET['edit'])) {
												$subsubsubsub = $admin->query("SELECT * FROM ".PREFIX."subsubsubsubCategory where active='1' order by subsubsubsub_name ASC");

												while($subsubsubsubCategoryDetail = $admin->fetch($subsubsubsub)){
													$subsubsubsubCategorySQL = $admin->getAllSubSubSubSubCategoriesbyProductID($id,$subsubsubsubCategoryDetail['id']);
													$subsubsubsubCdetail = $admin->fetch($subsubsubsubCategorySQL);

													
										?>
												<option value="<?php echo $subsubsubsubCategoryDetail['id']; ?>" <?php if(isset($_GET['edit']) and $subsubsubsubCdetail['subsubsubsubcategory_id']==$subsubsubsubCategoryDetail['id']) { echo 'selected'; } ?>><?php echo $subsubsubsubCategoryDetail['subsubsubsub_name']; ?></option>
										<?php
											
												}
											}
										?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4"><br>
                                    <label>Is Featured</label>
                                    <select class="form-control"  name="is_feature" id="is_feature">
                                        <option value="">Select Feature</option>
                                        <option value="Yes" <?php if(isset($_GET['edit']) and $data['is_feature']=='Yes') { echo 'selected'; } ?>>Yes</option>
										<option value="No" <?php if(isset($_GET['edit']) and $data['is_feature']=='No') { echo 'selected'; } ?>>No</option>
                                    </select>
								</div>
								<div class="col-sm-4"><br>
                                    <label>Best Seller</label>
                                    <select class="form-control"  name="best_seller" id="best_seller">
                                        <option value="">Select Feature</option>
                                        <option value="Yes" <?php if(isset($_GET['edit']) and $data['best_seller']=='Yes') { echo 'selected'; } ?>>Yes</option>
										<option value="No" <?php if(isset($_GET['edit']) and $data['best_seller']=='No') { echo 'selected'; } ?>>No</option>
                                    </select>
								</div>
                           	</div><br>
                            <div class="row">
                            	<div class="col-sm-12">
                                    <label>Short Description</label>
                                    <textarea col="5" rows="4"  class="form-control" name="short_description" id="short_description" ><?php if(isset($_GET['edit'])){ echo $data['short_description']; }  ?></textarea>
                                </div>
                                <div class="col-sm-12">
                                    <label>Description</label>
                                    <textarea col="5" rows="4"  class="form-control" name="description" id="description" ><?php if(isset($_GET['edit'])){ echo $data['description']; }  ?></textarea>
                                </div>
                                <div class="col-sm-12"><br>
									<label>Recommended Products</label>
									<select autocomplete="off" class="select-multiple" name="recommended_product[]" multiple placeholder="Select Recommended Products">
										<?php
											if(isset($_GET['edit'])) { 
												$productSQL = $admin->query("select * from ".PREFIX."product_master where id!='".$id."' and active ='Yes'");
											} else {
												$productSQL = $admin->getAllProducts();
											}

											if(isset($_GET['edit'])) {
												$existingRelatedColorArray = $admin->getProductRelatedProductsInArray($id);
											}
											while($productDetail = $admin->fetch($productSQL)) {
										?>
												<option value="<?php echo $productDetail['id'] ?>" <?php if(isset($_GET['edit']) and in_array($productDetail['id'],$existingRelatedColorArray)) { echo 'selected'; } ?>><?php echo $productDetail['product_name'] ?></option>
										<?php
											}
										?>
									</select>
								</div>
                            </div><br><br>
                            <div class="panel panel-default">
								<div class="panel-heading">
									<h6 class="panel-title"><i class="icon-library"></i> Product SEO Details</h6>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<label>Meta Title</label>
												<input type="text" class="form-control"  name="meta_title" id="1" value="<?php if(isset($_GET['edit'])){ echo $data['meta_title']; }?>"/>
											</div>
											<div class="col-sm-12"><br>
												<label>Meta Keyword</label>
												<input type="text" class="form-control"  name="meta_keyword" id="2" value="<?php if(isset($_GET['edit'])){ echo $data['meta_keyword']; }?>"/>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
												<div class="col-sm-12">
													<label>Meta Description</label>
													<textarea name="meta_description" rows="3" class="form-control"><?php if(isset($_GET['edit'])){ echo $data['meta_description']; }?></textarea>
													<span class="help-block"></span>
												</div>
											
										</div>
									</div>
								</div>
							</div>

						</div>	
						
						
					</div>
					<div class="panel panel-default" id="product-filter-div">
					<?php 
					if(isset($_GET['edit'])) { ?>
						<div class="panel-heading">
							<h6 class="panel-title"><i class="icon-library"></i> Product Filters</h6>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<div class="row">
									<?php
										$catArr = $admin->getProdcutCategoryByProductId($id);
										$cats = implode(",", $catArr);
										$attributeSQL = $admin->getAttributesByCategoryId($cats);
										while($attributes = $admin->fetch($attributeSQL)) {
											$attribute = $admin->getUniqueAttributeById($attributes['attribute_id']);
											if(!empty($attribute['id'])){
									?>
												<div class="col-sm-3">
													<label><?php echo $attribute['attribute_name']; ?></label>
													<select name="filter_value[]" id="filter_value" class="form-control">
														<option value="">Select <?php echo $attribute['attribute_name']; ?></option>
														<?php
															$attributeValueSql = $admin->getAttributeValues($attribute['id']);
															
															$productFilterValue = $admin->getProductFilterValueByFilterId($attribute['id'],$id);
															while($attributeValue = $admin->fetch($attributeValueSql)) {
														?>
																<option value="<?php echo $attributeValue['id'] ?>" <?php if(in_array($attributeValue['id'],$productFilterValue)){ echo 'selected'; } ?>><?php echo $attributeValue['feature'] ?></option>
														<?php
															}
														?>
													</select>
													<input type="hidden" name="filter_name[]" value="<?php echo $attribute['id'] ?>">
												</div>
									<?php 	}
										}
									?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
				</div>
				<div class="form-actions text-right">
				<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
<?php
			if(isset($_GET['edit'])){ ?>
					<input type="hidden" class="form-control" name="id" id="id" required="required" value="<?php echo $id ?>"/>
					<button type="submit" name="update" class="btn btn-warning"><i class="icon-pencil"></i>Update <?php echo $pageName; ?></button>
<?php		} else { ?>
					<button type="submit" name="register" class="btn btn-danger"><i class="icon-signup"></i>Add <?php echo $pageName; ?></button>
<?php		} ?>
				</div>
			</form>

<?php 	include "include/footer.php"; ?>
    
		</div>
	</div>
	
	<link href="css/crop-image/cropper.min.css" rel="stylesheet">
	<script src="js/crop-image/cropper.min.js"></script>
	<script src="js/crop-image/image-crop-app.js"></script>
	<script>
	$(document).ready(function() {
		$(".video-size").on("change", function(){
			if(this.files[0].size > 2000000){
		       alert("File is too big!");
		       this.value = "";
		    };
		});
		$("input[name='price']").change(function(){
			if(parseInt($("input[name='price']").val()) < parseInt($("input[name='discount_price']").val())){
				alert("Actual Price should be greater than Discount price");
				$("input[name='price']").val("0");
			}else{
				
			}
		});
		$("input[name='discount_price']").change(function(){
			if(parseInt($("input[name='discount_price']").val()) > parseInt($("input[name='price']").val())){
				alert("Discount price should be less than Actual Price");
				$("input[name='discount_price']").val("0");
			}else{

			}
		});
		$("input[name='b2b_price']").change(function(){
			if(parseInt($("input[name='b2b_price']").val()) < parseInt($("input[name='b2b_discount_price']").val())){
				alert("Discount price should be less than Actual Price");
				$("input[name='b2b_price']").val("0");
			}else{
				
			}
		});
		$("input[name='b2b_discount_price']").change(function(){
			if(parseInt($("input[name='b2b_discount_price']").val()) > parseInt($("input[name='b2b_price']").val())){
				alert("Discount price should be less than Actual Price");
				$("input[name='b2b_discount_price']").val("0");
			}else{

			}
		});
		$("input[name='b2b_min_qty']").change(function(){
			if(parseInt($("input[name='b2b_min_qty']").val()) > parseInt($("input[name='availability']").val())){
				alert("B2B Minimum quantity should be less than Availability");
				$("input[name='b2b_min_qty']").val("0");
			}else{

			}
		});
		$("input[name='availability']").change(function(){
			if(parseInt($("input[name='availability']").val()) < parseInt($("input[name='b2b_min_qty']").val())){
				alert("Availability should be greater than B2B Minimum quantity");
				$("input[name='availability']").val("0");
			}else{
				
			}
		});

		//subcategory();
		$('#sub_category_id').on("change",getSubSubCategory);
		$('#Subsub_category').on("change",getSubSubSubCategory);
		$('#Subsubsub_category').on("change",getSubSubSubSubCategory);
		$('input[name="main_image"],input[name="image_one"],input[name="image_two"],input[name="image_three"],input[name="image_four"]').change(function(){
			loadImagePreview(this, (1000 / 1000));
		});
		$("#category").on("change", function(){
	    	var category_id = $(this).val();

	    	$.ajax({
                url:"ajaxGetSubCategoryByCategoryId.php",
                data:{category_id:category_id},
                type:"POST",
                success: function(response){
                	$("#sub_category_id").select2('val', response.selectContent);
                	$("#Subsub_category").select2('val', response.selectContent);
                	$("#Subsubsub_category").select2('val', response.selectContent);
                	$("#Subsubsubsub_category").select2('val', response.selectContent);
                    $("#sub_category_id").html(response);
                    //$('#sub_category_id').on("change",getSubSubCategory);

                },
                error: function(){
                    alert("Unable to get content, please try again");
                },
                complete: function(response){
                    
                }
            });

			/*$.ajax({
				url:"ajaxGetCategoryAttributes.php",
				data:{category_id:category_id},
				type:"POST",
				success: function(response){
					var response = JSON.parse(response);
					$("#product-filter-div").html(response.responseContent);
				},
				error: function(){
					alert("Unable to add to cart, please try again");
				},
				complete: function(response){
					
				}
			});*/
	    });
	});
		function getSubSubCategory() {
			var Subsubcategory = $("#sub_category_id").val();
			//alert(Subsubcategory);
			$.ajax({
				url:"ajaxGetSubSubCategory.php",
				data:{Subsubcategory:Subsubcategory},
				type:"GET",
				success: function(response){
					var response = JSON.parse(response);
					$("#Subsub_category").select2('val', response.selectContent);
					$("#Subsub_category").html(response.selectContent);
				},
				error: function(){
					alert("Unable to add to cart, pleases try again");
				},
				complete: function(response){
					
				}
			}).then(function (response) {
			    // create the option and append to Select2
			    
				$("#Subsub_category").html(response.selectContent);
			    /*var option = new Option(data.full_name, data.id, true, true);
			    studentSelect.append(option).trigger('change');

			    // manually trigger the `select2:select` event
			    $("#Subsub_category").trigger({
			        type: 'select2:select',
			        params: {
			            data: response
			        }
			    });*/
			});;
		}
		function getSubSubSubCategory() {
			var Subsubsubcategory = $("#Subsub_category").val();
			//alert(Subsubcategory);
			$.ajax({
				url:"ajaxGetSubSubSubCategory.php",
				data:{Subsubsubcategory:Subsubsubcategory},
				type:"GET",
				success: function(response){
					var response = JSON.parse(response);
					$("#Subsubsub_category").select2('val', response.selectContent);
					$("#Subsubsub_category").html(response.selectContent);
				},
				error: function(){
					alert("Unable to add to cart, pleases try again");
				},
				complete: function(response){
					
				}
			}).then(function (response) {
			    // create the option and append to Select2
			    
				$("#Subsubsub_category").html(response.selectContent);
			    /*var option = new Option(data.full_name, data.id, true, true);
			    studentSelect.append(option).trigger('change');

			    // manually trigger the `select2:select` event
			    $("#Subsub_category").trigger({
			        type: 'select2:select',
			        params: {
			            data: response
			        }
			    });*/
			});;
		}
		function getSubSubSubSubCategory() {
			var Subsubsubsubcategory = $("#Subsubsub_category").val();
			//alert(Subsubcategory);
			$.ajax({
				url:"ajaxGetSubSubSubSubCategory.php",
				data:{Subsubsubsubcategory:Subsubsubsubcategory},
				type:"GET",
				success: function(response){
					var response = JSON.parse(response);
					$("#Subsubsubsub_category").select2('val', response.selectContent);
					$("#Subsubsubsub_category").html(response.selectContent);
				},
				error: function(){
					alert("Unable to add to cart, pleases try again");
				},
				complete: function(response){
					
				}
			}).then(function (response) {
			    // create the option and append to Select2
			    
				$("#Subsubsubsub_category").html(response.selectContent);
			    /*var option = new Option(data.full_name, data.id, true, true);
			    studentSelect.append(option).trigger('change');

			    // manually trigger the `select2:select` event
			    $("#Subsub_category").trigger({
			        type: 'select2:select',
			        params: {
			            data: response
			        }
			    });*/
			});;
		}
	</script>
	<script type="text/javascript" src="js/editor/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="js/editor/ckfinder/ckfinder.js"></script>
	<script>
	var editor = CKEDITOR.replace( 'description', {
			height: 300,
			filebrowserImageBrowseUrl : 'js/editor/ckfinder/ckfinder.html?type=Images',
			filebrowserImageUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			toolbarGroups: [
				
				{"name":"document","groups":["mode"]},
				{"name":"clipboard","groups":["undo"]},
				{"name":"basicstyles","groups":["basicstyles"]},
				{"name":"links","groups":["links"]},
				{"name":"paragraph","groups":["list"]},
				{"name":"insert","groups":["insert"]},
				{"name":"insert","groups":["insert"]},
				{"name":"styles","groups":["styles"]},
				{"name":"paragraph","groups":["align"]},
				{"name":"about","groups":["about"]},
				{"name":"colors","tems": [ 'TextColor', 'BGColor' ] },
			],
			removeButtons: 'Iframe,Flash,Strike,Smiley,Subscript,Superscript,Anchor,Specialchar'
		} );
	CKFinder.setupCKEditor( editor, '../' );

	function subcategory(){
		var category = $("#category").val();
		var id = $("#id").val();
		console.log(category);
		console.log(id);
		var element = document.getElementById("sub_category");
		element.innerHTML = '';
		$.ajax({
			type: "POST",
			url: "ajaxGetSubCategory.php",
			data: {category: category},               
			success: function(response) {
				console.log(response);  
				var data = JSON.parse(response);
				element.innerHTML += data.sub;
			}
		});
	}

	</script>
	
</body>
</html>