<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	//include_once "include/getusercountrybyip.php";
	$admin = new AdminFunctions();
	$pageName = "Reviews";
	$parentPageURL = 'reviews.php';
	$pageURL = 'reviews-add.php';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	
	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);

	if(isset($_GET['edit'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$data = $admin->getUniqueReviewById($id);
	}
	if(isset($_POST['update'])) {
		if($csrf->check_valid('post')) {
			$allowed_ext = array('image/jpeg','image/jpg');
			$id = trim($admin->escape_string($admin->strip_all($_POST['id'])));
			$name = trim($admin->escape_string($admin->strip_all($_POST['name'])));
			if(empty($id) || empty($name)){
				if(isset($_GET['page']) && !empty($_GET['page'])){
					header("location:".$pageURL."?updatefail&msg=Please enter a name&edit&id=".$id."&page=".$_GET['page']);
				}else{
					header("location:".$pageURL."?updatefail&msg=Please enter a name&edit&id=".$id);
				}
				exit();
			}
			else if(!empty($_FILES['test_image']['name']) and !in_array($_FILES['test_image']['type'],$allowed_ext)) {
				if(isset($_GET['page']) && !empty($_GET['page'])){
					header("location:".$pageURL."?updatefail&msg=Please upload jpg image only&edit&id=".$id."&page=".$_GET['page']);
				}else{
					header("location:".$pageURL."?updatefail&msg=Please upload jpg image only&edit&id=".$id);
				}
				exit();
			}
			else {
				//update to database
				$result = $admin->updateReviews($_POST,$_FILES);
				if(isset($_GET['page']) && !empty($_GET['page'])){
					header("location:".$pageURL."?updatesuccess&edit&id=".$id."&page=".$_GET['page']);
				}else{
					header("location:".$pageURL."?updatesuccess&edit&id=".$id);
				}
			}
		}
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
	<link rel="shortcut icon" href="<?php echo BASE_URL ?>/favicon(1).png" type="image/x-icon">
	<link rel="icon" href="<?php echo BASE_URL ?>/favicon.ico" type="image/x-icon">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/nanoscroller.css" rel="stylesheet">
	<link href="css/emoji.css" rel="stylesheet">
	<link href="css/cover.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.1.10.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.1.10.2.min.js"></script>
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
				rules: {
					image_name: {
						extension: 'jpg|jpeg'
					},
				}
			});
		});
	</script>
</head>
<body class="sidebar-wide">
	<?php include 'include/navbar.php' ?>

	<div class="page-container">

		<?php include 'include/sidebar.php' ?>

		<div class="page-content">

		<!--
			<div class="page-header">
				<div class="page-title">
					<h3>Dashboard <small>Welcome Eugene. 12 hours since last visit</small></h3>
				</div>
				<div id="reportrange" class="range">
					<div class="visible-xs header-element-toggle"><a class="btn btn-primary btn-icon"><i class="icon-calendar"></i></a></div>
					<div class="date-range"></div>
					<span class="label label-danger">9</span>
				</div>
			</div>
		-->

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
				<li><a href="index.php">Home</a></li>
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
						<h6 class="panel-title"><i class="icon-library"></i> Review Details</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Product Name</label>
									<?php if(isset($_GET['edit'])){ 
												if($data['review_type']=="hamper"){
													$productName = $admin->getUniqueHamperById($data['product_id'])['hamper_name']; 	
												}else{
													$productName =  $admin->getUniqueProductById($data['product_id'])['product_name'];
												}
												
											}else{
												$productName ="";
											}
									?>
									<input type="text" class="form-control" required="required" disabled id="" value="<?php echo $productName; ?>"/>
								</div>
								<div class="col-sm-3">
									<label>Name<em>*</em></label>
									<input type="text" class="form-control" required="required" name="name" id="" value="<?php if(isset($_GET['edit'])){ echo $data['name']; } ?>"/>
								</div>
								<div class="col-sm-3">
									<label>Email<em>*</em></label>
									<input type="text" class="form-control" required="required" name="email" id="" value="<?php if(isset($_GET['edit'])){ echo $data['email']; }?>"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label>Rating</label>
									<select class="form-control" name="rating" id="active" placeholder="Select Active">
										<option value="">Select Rating</option>
										<option value="0.5" <?php if(isset($_GET['edit']) && isset($data['rating']) && !empty($data['rating'])){ if($data['rating'] == '0.5'){ echo 'selected="selected"'; } } ?>>0.5</option>
										<option value="1" <?php if(isset($_GET['edit']) && isset($data['rating']) && !empty($data['rating'])){ if($data['rating'] == '1'){ echo 'selected="selected"'; } } ?>>1</option>
										<option value="1.5" <?php if(isset($_GET['edit']) && isset($data['rating']) && !empty($data['rating'])){ if($data['rating'] == '1.5'){ echo 'selected="selected"'; } } ?>>1.5</option>
										<option value="2" <?php if(isset($_GET['edit']) && isset($data['rating']) && !empty($data['rating'])){ if($data['rating'] == '2'){ echo 'selected="selected"'; } } ?>>2</option>
										<option value="2.5" <?php if(isset($_GET['edit']) && isset($data['rating']) && !empty($data['rating'])){ if($data['rating'] == '2.5'){ echo 'selected="selected"'; } } ?>>2.5</option>
										<option value="3" <?php if(isset($_GET['edit']) && isset($data['rating']) && !empty($data['rating'])){ if($data['rating'] == '3'){ echo 'selected="selected"'; } } ?>>3</option>
										<option value="3.5" <?php if(isset($_GET['edit']) && isset($data['rating']) && !empty($data['rating'])){ if($data['rating'] == '3.5'){ echo 'selected="selected"'; } } ?>>3.5</option>
										<option value="4" <?php if(isset($_GET['edit']) && isset($data['rating']) && !empty($data['rating'])){ if($data['rating'] == '4'){ echo 'selected="selected"'; } } ?>>4</option>
										<option value="4.5" <?php if(isset($_GET['edit']) && isset($data['rating']) && !empty($data['rating'])){ if($data['rating'] == '4.5'){ echo 'selected="selected"'; } } ?>>4.5</option>
										<option value="5" <?php if(isset($_GET['edit']) && isset($data['rating']) && !empty($data['rating'])){ if($data['rating'] == '5'){ echo 'selected="selected"'; } } ?>>5</option>
									</select>
								</div>
								<?php /*<div class="col-sm-4">
									<label>Review Type</label>
									<select class="form-control" name="review_type" >
										<option value="Customer Review" <?php if(isset($_GET['edit']) && isset($data['review_type']) && !empty($data['review_type'])){ if($data['review_type'] == 'Customer Review'){ echo 'selected="selected"'; } } ?>>Customer Review</option>
										<option value="Corporate Review" <?php if(isset($_GET['edit']) && isset($data['review_type']) && !empty($data['review_type'])){ if($data['review_type'] == 'Corporate Review'){ echo 'selected="selected"'; } } ?>>Corporate Review</option>
									</select> 
									
								</div>*/ ?>
								<div class="col-sm-4">
									<input type="hidden" name="review_type" value="<?php echo $data['review_type']; ?>">
									<label>Active<em>*</em></label>
									<select class="form-control" name="active" id="active" placeholder="Select Active" required>
										<option value="">Select Active</option>
										<option value="Yes" <?php if(isset($_GET['edit']) && isset($data['active']) && !empty($data['active'])){ if($data['active'] == 'Yes'){ echo 'selected="selected"'; } } ?>>Yes</option>
										<option value="No" <?php if(isset($_GET['edit']) && isset($data['active']) && !empty($data['active'])){ if($data['active'] == 'No'){ echo 'selected="selected"'; } } ?>>No</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<?php /*<div class="col-sm-4">
									<label>Image</label>
									<?php 
									if(isset($_GET['edit'])){
									$file_name = str_replace('', '-', strtolower( pathinfo($data['image'], PATHINFO_FILENAME)));
									$ext = pathinfo($data['image'], PATHINFO_EXTENSION);?>
									<img src="<?php echo BASE_URL ?>/images/reviews/<?php echo $file_name.'_large.'.$ext ?>" style="width:250px;height:200px"><br><br><br>
									<input type="file" class="form-control"  name="test_image"  />
									<input type="hidden" name="hide_image" value="<?php echo $data['image']; ?>" />
									<?php } else { ?>
										<input type="file" class="form-control" required="required"  name="test_image"  />
									<?php } ?>
								</div> */?>
								<input type="hidden" class="form-control"  name="test_image"  />
								<div class="col-sm-8">
									<label>Review<em>*</em></label>
									<textarea  required="required" class="form-control" name="review" rows="3"><?php if(isset($_GET['edit'])) { echo $data['review']; } ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions text-right">
				<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
<?php
			if(isset($_GET['edit'])){ ?>
					<input type="hidden" class="form-control" name="id" id=""  value="<?php echo $id ?>"/>
					<input type="hidden" class="form-control" name="product_id"   value="<?php echo $data['product_id']; ?>"/>
					<button type="submit" name="update" class="btn btn-warning"><i class="icon-pencil"></i>Update <?php echo $pageName; ?></button>
<?php		} else { ?>
					<button type="submit" name="register" class="btn btn-danger"><i class="icon-signup"></i>Add <?php echo $pageName; ?></button>
<?php		} ?>
				</div>
			</form>

<?php 	include "include/footer.php"; ?>
    
		</div>
	</div>
	<script>
		$(document).ready(function(){
			$("#categories").on("change",function(){
				var category_id = $(this).val();
				
				$.ajax({
					url:"<?php echo BASE_URL ?>/ajaxGetCourses.php",
					data:{category_id:category_id},
					type:"POST",
					success: function(response){
						$("#courses").html(response);						
					},
					error: function(){
						//alert("Unable to add to wishlist, please try again");
						return false;
					},
					complete: function(response){
						
					}
				});
			});
		});
	</script>
</body>
</html>