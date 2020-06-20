<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';

	$admin = new AdminFunctions();
	$pageName = "Sub Category";
	$parentPageURL = 'sub-category-master.php';
	$pageURL = 'sub-category-add.php';
	$tableName = 'sub_category_master';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}

	// if($loggedInUserDetailsArr['role']!='super') {
		// header("location: index.php");
		// exit();
	// }

	if(isset($_GET['cat_id']) && !empty($_GET['cat_id'])){
		$category_id = trim($admin->escape_string($admin->strip_all($_GET['cat_id'])));
	}else{
		header("location:category-master.php?INVALIDCAT");
		exit;
	}

	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);
	
	// Get List Of Pets Into The Database
	
	if(isset($_POST['sub_category_name']) && !empty($_POST['sub_category_name']) && empty($_POST['id'])){	
	
		$allowed_ext = array('image/jpeg','image/jpg','image/png');
		
		$category_id 		= trim($admin->escape_string($admin->strip_all($_POST['category_id'])));
		$sub_category_name 		= trim($admin->escape_string($admin->strip_all($_POST['sub_category_name'])));
		
		if(empty($category_id)){
			header("location:".$pageURL."?registerfail&msg=Please select Category");
			exit();
		} else if(empty($sub_category_name)){
			header("location:".$pageURL."?registerfail&msg=Please enter a Sub Category Name");
			exit();
		} else {
			//add to database
			$newCustomerDetails = $admin->addSubCategory($_POST, $_FILES);
			//print_r($_POST); exit; 
			header("location:".$pageURL."?registersuccess&cat_id=".$category_id);
			exit;
		}		
	}
	
	if(isset($_GET['edit'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$data = $admin->getUniqueSubCategoryById($id);
	}
	if(isset($_POST['id']) && !empty($_POST['id']))	{
	
		$allowed_ext = array('image/jpeg','image/jpg','image/png');
		
		$category_id 		= trim($admin->escape_string($admin->strip_all($_POST['category_id'])));
		$sub_category_name 		= trim($admin->escape_string($admin->strip_all($_POST['sub_category_name'])));
		
		if(empty($category_id)){
			header("location:".$pageURL."?updatefail&msg=Please select a Category&edit&id=".$id);
			exit();
		} else if(empty($sub_category_name)){
			header("location:".$pageURL."?updatefail&msg=Please enter a Sub Category Name&edit&id=".$id);
			exit();
		} else {
			$id	= $admin->escape_string($admin->strip_all($_POST['id']));
			$result 	= $admin->updateSubCategory($_POST, $_FILES);
			header("location:".$pageURL."?updatesuccess&edit&id=".$id."&cat_id=".$_POST['category_id']);
		}
	}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo TITLE ?> | Add Sub Category</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/londinium-theme.min.css" rel="stylesheet" type="text/css">
	<link href="css/styles.min.css" rel="stylesheet" type="text/css">
	<link href="css/icons.min.css" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/ico" href="images/favicon.png">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<style>
		.red{
			color: #ff0000;
		}
	</style>
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

		<a href="<?php echo $parentPageURL.'?cat_id='.$category_id; ?>" class="label label-primary">Back to <?php echo $pageName; ?></a><br/><br/>
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
			<form role="form" action="" method="POST" id="form" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6 class="panel-title"><i class="icon-library"></i> Sub Category Details</h6>
					</div>
					<div class="panel-body" id="scrolly">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label>Category Name <span class="red">*</span></label>
									<Select class="form-control" name="category_id">										
									<?php
										$categoriesList = $admin->getcateogrybyId($category_id);
										while($categories = $admin->fetch($categoriesList)){
									?>
										<option value="<?php echo $categories['id']; ?>" <?php if(isset($categories['id']) && !empty($data['category_id']) && $data['category_id'] == $categories['id']){ echo "selected"; } ?>><?php echo $categories['category_name']; ?></option>
									<?php
										}
									?>
									</Select>
								</div>
								<div class="col-sm-4">
									<label>Sub Category Name <span class="red">*</span></label>
									<input type="text" class="form-control" required="required" name="sub_category_name" value="<?php if(isset($_GET['edit'])){ echo $data['sub_category_name']; }?>"/>
								</div>								
								<div class="col-sm-4">
									<label>Active <span class="red">*</span></label>
									<select name="active" id="active" class="form-control">
										<option value="1" <?php if(isset($_GET['edit']) && $data['active']=='1'){ echo "selected"; } ?>>Yes</option>
										<option value="0" <?php if(isset($_GET['edit']) && $data['active']=='0'){ echo "selected"; } ?>>No</option>
									</select>
								</div>
							</div>
							</div>
						</div>
						
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h6 class="panel-title"><i class="icon-database2"></i> SEO Details</h6>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<label>Meta Title</label>
										<input type="text" class="form-control" name="meta_title" id=""  value="<?php if(isset($_GET['edit'])){ echo $data['meta_title']; }?>"/>
									</div>
									<div class="col-sm-6">
										<label>Meta Keywords</label>
										<input type="text" class="form-control" name="meta_keywords" id=""  value="<?php if(isset($_GET['edit'])){ echo $data['meta_keywords']; }?>"/>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<label>Meta Description</label>
										<textarea class="form-control" name="og_description" ><?php if(isset($_GET['edit'])){ echo $data['og_description'] ;} ?></textarea>
									</div>
								</div>
							</div>
						</div>
						
					</div>
							</div>
							</div>
						</div>
						
					</div>
				</div>
				
				<div class="form-actions text-right form-div">
					<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
					<input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
<?php
			if(isset($_GET['edit'])){ ?>
					<input type="hidden" class="form-control" name="id" id="id" required="required" value="<?php echo $id ?>"/>
					<button type="submit" name="update" class="btn btn-warning"><i class="icon-pencil"></i>Update <?php echo $pageName; ?></button>
<?php		} else { ?>
					<button type="submit" name="register" class="btn btn-danger"><i class="icon-signup"></i>Add <?php echo $pageName; ?></button>
<?php		} ?>
				</div>
			</form>	
		</div>
	</div>

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
	<script type="text/javascript" src="js/editor/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="js/editor/ckfinder/ckfinder.js"></script>
	<link href="css/crop-image/cropper.min.css" rel="stylesheet">
	<script src="js/crop-image/cropper.min.js"></script>
	<script src="js/crop-image/image-crop-app.js"></script>
	<script type="text/javascript">
		// var editor = CKEDITOR.replace( 'description', {
		// 	filebrowserImageBrowseUrl : 'js/editor/ckfinder/ckfinder.html?type=Images',
		// 	filebrowserImageUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		// 	toolbarGroups: [
		// 		{"name":"document","groups":["mode"]},
		// 		{"name":"clipboard","groups":["undo"]},
		// 		{"name":"basicstyles","groups":["basicstyles"]},
		// 		{"name":"links","groups":["links"]},
		// 		{"name":"paragraph","groups":["list"]},
		// 		{"name":"insert","groups":["insert"]},
		// 		{"name":"insert","groups":["insert"]},
		// 		{"name":"styles","groups":["styles"]},
		// 		{"name":"paragraph","groups":["align"]},
		// 		{"name":"about","groups":["about"]},
		// 		{"name":"colors","tems": [ 'TextColor', 'BGColor' ] },
		// 	],
		// 	removeButtons: 'Iframe,Flash,Smiley,Strike,Subscript,Superscript,Anchor,Specialchar'
		// });	
		$(document).ready(function() {

			// $(document).on("change",'input[name="profile_pic"]',function(){
			// 	// loadImageInModal(this);
			// 	loadImagePreview(this, (300 / 300));
			// });
			$('input[name="mobile_image"]').change(function(){
			// loadImageInModal(this);
			// loadImagePreview(this, (254 / 213));
			loadImagePreview(this, (400 / 100));
			});
			$('input[name="banner_image"]').change(function(){
				// loadImageInModal(this);
				loadImagePreview(this, (1366 / 200));
				// loadImagePreview(this, (500 / 500));
			});
			
			$("#form").validate({
				rules: {
					category_id: {
						required: true
					},
					banner_image: {
						extension: 'jpg|jpeg|png'
					},
					mobile_image: {
						extension: 'jpg|jpeg|png'
					},
					sub_category_name: {
						required: true
					}
				},
				messages: {
					category_id: {
						required:"Please select Category"
					},
					banner_image: {
						extension: 'Upload image with jpg, jpeg or png extension'
					},
					mobile_image: {
						extension: 'Upload image with jpg, jpeg or png extension'
					},
					sub_category_name: {
						required:"Please enter Sub Category Name"
					}
				}
			});
			jQuery.validator.addMethod("pwcheck", function(value) {
				if(value!=''){
         	   return /^[A-Za-z0-9@!#]{8,}$/.test(value) // consists of only these
         		   && /[a-zA-Z ]/.test(value) // has a lowercase letter
         		   && /\d/.test(value) // has a digit
				}else{
					return true;
				}
         	}," Password should be minimum 8 characters, alpha numeric & atleast 1 special character");
			jQuery.validator.addMethod("lettersonly", function(value, element) {
				return this.optional(element) || /^[a-zA-Z ]+$/i.test(value);
			}, "Only letters are allowed");
			jQuery.validator.addMethod("propersiteurlonly", function(value, element) {
				return this.optional(element) || /^http(s)?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g.test(value);
			}, "Please enter proper link");
			jQuery.validator.addMethod("mobilevalidity", function(value, element) {
				return this.optional(element) || /^[7-9]\d{9}/g.test(value);
			}, "Please enter valid mobile number");
			$.validator.addMethod("properemailonly", function(value, element) {
		        return this.optional(element) || /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i.test(value);
		    }, "Please enter valid email");

	        $("#form").submit(function(event) {
	            if($("#form").valid()===true) {
	                var loaderEle = $('<i class="fa fa-refresh fa-spin"></i>');
	                $(".form-div").append(loaderEle);
	            <?php if(isset($_GET['edit'])){ ?>
	                $("button[name='update']").attr("disabled", true);
	            <?php }else{ ?>	
	                $("button[name='register']").attr("disabled", true);
	            <?php } ?>	
	            }
	        });
		});
	
	</script>

</body>
</html>