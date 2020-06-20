<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();

	$subsubsubcate_id = $admin->escape_string($admin->strip_all($_REQUEST['subsubsubcate_id']));
	$subsubcate_id = $admin->escape_string($admin->strip_all($_REQUEST['subsubcate_id']));
	$category_ids = $admin->escape_string($admin->strip_all($_REQUEST['category_id']));
	$category_id = $admin->escape_string($admin->strip_all($_REQUEST['subcate_id']));

	$pageName = "SubSub SubSubCategory Master";
	$parentPageURL = 'subSub-subSubCategory-master.php?subsubsubcate_id='.$subsubsubcate_id.'&subsubcate_id='.$subsubcate_id.'&subcate_id='.$category_id.'&category_id='.$category_ids;
	$pageURL = 'subSub-subSubCategory-add.php';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	if(isset($_GET['edit'])) {
		$admin->checkUserPermissions('category_update',$loggedInUserDetailsArr);
	} else {
		$admin->checkUserPermissions('category_add',$loggedInUserDetailsArr);
	}

	//include_once 'csrf.class.php';
	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);
	
	if(isset($_POST['register'])){
		if($csrf->check_valid('post')) {
			$allowed_ext = array('image/jpeg','image/jpg','image/png');
			
			$name = trim($admin->escape_string($admin->strip_all($_POST['name'])));
			
			if(empty($name)){
				header("location:".$pageURL."?registerfail&msg=Please enter a name&subsubsubcate_id=".$subsubsubcate_id."&subsubcate_id=".$subsubcate_id."&subcate_id=".$category_id."&category_id=".$category_ids);
				exit();
			}
			/* else if(!in_array($_FILES['image_name']['type'],$allowed_ext)) {
				header("location:".$pageURL."?registerfail&msg=Please upload jpg image only");
				exit();
			} */
			else {
				//add to database
				$result = $admin->addSubSubSubSubCategory($_POST,$_FILES);
				header("location:".$pageURL."?registersuccess&subsubsubcate_id=".$subsubsubcate_id."&subsubcate_id=".$subsubcate_id."&subcate_id=".$category_id."&category_id=".$category_ids);
			}
		}
	}
	if(isset($_GET['edit'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$data = $admin->getuniqueSubsubsubsubCategory($id);
		
	}
	if(isset($_POST['update'])) {
		if($csrf->check_valid('post')) {
			$allowed_ext = array('image/jpeg','image/jpg','image/png');
			$id = trim($admin->escape_string($admin->strip_all($_POST['id'])));
			$name = trim($admin->escape_string($admin->strip_all($_POST['name'])));
			if(empty($id) || empty($name)){
				header("location:".$pageURL."?updatefail&msg=Please enter a name&edit&id=".$id."&subsubsubcate_id=".$subsubsubcate_id."&subsubcate_id=".$subsubcate_id."&subcate_id=".$category_id."&category_id=".$category_ids);
				exit();
			}
			/* else if(!empty($_FILES['image_name']['name']) and !in_array($_FILES['image_name']['type'],$allowed_ext)) {
				header("location:".$pageURL."?updatefail&msg=Please upload jpg image only&edit&id=".$id);
				exit();
			} */
			else {
				//update to database
				$result = $admin->updateSubSubSubSubCategory($_POST,$_FILES);
				header("location:".$pageURL."?updatesuccess&edit&id=$id&subsubsubcate_id=".$subsubsubcate_id."&subsubcate_id=".$subsubcate_id."&subcate_id=".$category_id."&category_id=".$category_ids);
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
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/londinium-theme.min.css" rel="stylesheet" type="text/css">
	<link href="css/styles.min.css" rel="stylesheet" type="text/css">
	<link href="css/icons.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="<?php echo BASE_URL ?>/favicon.ico" type="image/x-icon">
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
	<script src="//cdn.ckeditor.com/4.5.5/full/ckeditor.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			
			$("#form").validate({
				rules: {
					banner_image: {
						extension: 'jpg|jpeg|png'
					},
					mobile_image: {
						extension: 'jpg|jpeg|png'
					},
					permalink:{
						Evalid:true
					}
				},
				messages: {
					banner_image: {
						extension: 'Upload image with jpg, jpeg or png extension'
					},
					mobile_image: {
						extension: 'Upload image with jpg, jpeg or png extension'
					}
				}
			});
			$.validator.addMethod("Evalid", function (value, element) {
         		if (this.optional(element)) {
         			return true;
         		}
				var reg = /^[a-zA-Z0-9-]+$/;
         		return reg.test(value);
			}, "Letters, numbers, and hyphen only please");	
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
						<h6 class="panel-title"><i class="icon-library"></i>Category Details</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label>SubSub SubCategory Name<em>*</em></label>
									<input  type="text" class="form-control" required="required" name="name" id="" value="<?php if(isset($_GET['edit'])){ echo $data['subsubsubsub_name']; }?>"/>
								</div>
								
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label>Active</label>
									<select class="form-control" required="required" name="active" id="" >
										<option value="1" <?php if(isset($_GET['edit']) && $data['active']=="1"){ echo 'selected="selected"'; }?> >Yes</option>
										<option value="0" <?php if(isset($_GET['edit']) && $data['active']=="0"){ echo 'selected="selected"'; }?> >No</option>
									</select>
									<span class="help-block"></span>
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
										<input type="text" class="form-control" name="meta_keywords" id=""  value="<?php if(isset($_GET['edit'])){ echo $data['meta_keyword']; }?>"/>
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
				<div class="form-actions text-right">
				<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
				<input type="hidden" name="subsubsubcate_id" value="<?php echo $subsubsubcate_id; ?>" />
				<input type="hidden" name="subcatIds" value="<?php if(isset($_GET['subsubsubcate_id'])){ echo $_GET['subsubsubcate_id']; }  ?>">
<?php
			if(isset($_GET['edit'])){ ?>
					<input type="hidden" class="form-control" name="id" id="" required="required" value="<?php echo $id ?>"/>
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
	<script type="text/javascript">
		$(document).ready(function() {
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
	});
	</script>
	
</body>
</html>