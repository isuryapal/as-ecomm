<?php	
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$pageName = "Home Page CMS Master";
	$parentPageURL = 'homepage-cms.php';
	$pageURL = 'homepage-cms.php';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	//error_reporting(E_ALL);
	//include_once 'csrf.class.php';
	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);


	if(isset($_POST['register'])) {
		//if($csrf->check_valid('post')) {
			$allowed_ext = array('image/jpeg','image/jpg','image/png');
				
			$id = trim($admin->escape_string($admin->strip_all($_POST['id'])));
			$result = $admin->homepageCms($_POST,$_FILES);
			$datas = "location:".$pageURL."?updatesuccess&id=".$id;
			//echo $datas; exit;
			header("location:".$pageURL."?updatesuccess&id=".$id);
			exit;
		//}
	}
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	$data = $admin->gethomepageCms();
	//print_r($data);
	//print_r($data);
	//exit;
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
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
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
				ignore: [],
				rules: {
					image_name_one: {
					<?php if(isset($data['image_name_one'])){if(empty($data['image_name_one'])){	?>
						required:true,
					<?php }}else{ ?>
						required:true,
					<?php } ?>
					extension: 'jpg|jpeg|png',
					},
					image_name_two: {
					<?php if(isset($data['image_name_two'])){if(empty($data['image_name_two'])){	?>
						required:true,
						
					<?php }}else{ ?>
						required:true,
						
					<?php } ?>
					extension: 'jpg|jpeg|png',
					},
					image_name_three: {
					<?php if(isset($data['image_name_three'])){if(empty($data['image_name_three'])){	?>
						required:true,
					<?php }}else{ ?>
						required:true,
						
					<?php } ?>
					extension: 'jpg|jpeg|png',
					},
					banner_image: {
					<?php if(isset($data['banner_image'])){if(empty($data['banner_image'])){	?>
						required:true,
					<?php }}else{ ?>
						required:true,
					<?php } ?>
					extension: 'jpg|jpeg|png',
					},
					who_we_are:{
						required: function() {
							CKEDITOR.instances.who_we_are.updateElement();
						},
					},
					our_mission:{
						required: function() {
							CKEDITOR.instances.our_mission.updateElement();
						},
					},
					our_vision:{
						required: function() {
							CKEDITOR.instances.our_vision.updateElement();
						},
					},
					description:{
						required: function() {
							CKEDITOR.instances.description.updateElement();
						},
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
						<h6 class="panel-title"><i class="icon-library"></i>Home Page CMS Details</h6>
					</div>
					<div class="panel-body">
						
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Ad Image One<em style="color:red;">*</em></label>
									<input type="file" class="form-control" <?php if(isset($data['image_name_one'])){if(empty($data['image_name_one'])){ echo "required"; } }else{ echo "required"; }?> name="image_name_one" id="1" data-image-index="0" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>jpg|jpeg|png</strong>.<br>
										Images must be exactly <strong>297 x 431</strong> pixels.
									</span>
									<?php if(isset($data['image_name_one'])) {
										$file_name = str_replace('', '-', strtolower( pathinfo($data['image_name_one'], PATHINFO_FILENAME)));
										$ext = pathinfo($data['image_name_one'], PATHINFO_EXTENSION);
									?>
										<img src="../images/home_cms/<?php echo $file_name.'_crop.'.$ext ?>" width="200" />
									<?php
									} ?>
								</div>
								<div class="col-sm-6">
									<label>Ad Image Two<em style="color:red;">*</em></label>
									<input type="file" class="form-control" <?php if(isset($data['image_name_two'])){if(empty($data['image_name_two'])){ echo "required"; } }else{ echo "required"; }?> name="image_name_two" id="1" data-image-index="1" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>jpg|jpeg|png</strong>.<br>
										Images must be exactly <strong>294 x 169</strong> pixels.
									</span>
									<?php if(isset($data['image_name_two'])) {
										$file_name = str_replace('', '-', strtolower( pathinfo($data['image_name_two'], PATHINFO_FILENAME)));
										$ext = pathinfo($data['image_name_two'], PATHINFO_EXTENSION);
									?>
										<img src="../images/home_cms/<?php echo $file_name.'_crop.'.$ext ?>" width="200" />
									<?php
									} ?>
								</div>
								<div class="col-sm-6">
									<label>Ad Image Three<em style="color:red;">*</em></label>
									<input type="file" class="form-control" <?php if(isset($data['image_name_three'])){if(empty($data['image_name_three'])){ echo "required"; } }else{ echo "required"; }?> name="image_name_three" id="2" data-image-index="2" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>jpg|jpeg|png</strong>.<br>
										Images must be exactly <strong>613 x 166</strong> pixels.
									</span>
									<?php if(isset($data['image_name_three'])) {
										$file_name = str_replace('', '-', strtolower( pathinfo($data['image_name_three'], PATHINFO_FILENAME)));
										$ext = pathinfo($data['image_name_three'], PATHINFO_EXTENSION);
									?>
										<img src="../images/home_cms/<?php echo $file_name.'_crop.'.$ext ?>" width="200" />
									<?php
									} ?>
								</div>
								<div class="col-sm-6">
									<label>Ad url one</label>
									<input type="text" class="form-control" name="adUrlOne" value="<?php echo $data['adUrlOne']; ?>">
								</div>
								<div class="col-sm-6">
									<label>Ad url two</label>
									<input type="text" class="form-control" name="adUrlTwo" value="<?php echo $data['adUrlTwo']; ?>">
								</div>
								<div class="col-sm-6">
									<label>Ad url three</label>
									<input type="text" class="form-control" name="adUrlThree" value="<?php echo $data['adUrlThree']; ?>">
								</div>
							</div>
						</div>
					
					</div>
				</div>
				<div class="form-actions text-right">
				<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
				<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
<?php
			if(isset($_GET['edit'])){ ?>
					<input type="hidden" class="form-control" name="id" id="" required="required" value="<?php echo $id ?>"/>
					<button type="submit" name="update" class="btn btn-warning"><i class="icon-pencil"></i>Update <?php echo $pageName; ?></button>
<?php		} else { ?>
					<button type="submit" name="register" class="btn btn-danger"><i class="icon-signup"></i>Update <?php echo $pageName; ?></button>
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
			$('input[name="image_name_one"]').change(function(){
				// loadImageInModal(this);
				loadImagePreview(this, (297 / 431));
			});
			$('input[name="image_name_two"]').change(function(){
				// loadImageInModal(this);
				loadImagePreview(this, (294 / 169));
			});
			$('input[name="image_name_three"]').change(function(){
				// loadImageInModal(this);
				loadImagePreview(this, (613 / 166));
			});
		});
	</script>
	<script type="text/javascript" src="js/editor/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="js/editor/ckfinder/ckfinder.js"></script>
	<script>
	$(document).ready(function() {
		/*var editor = CKEDITOR.replace( 'description', {
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
			removeButtons: 'Iframe,Flash,Smiley,Strike,Subscript,Superscript,Anchor,Specialchar'
		});
		
		var editor = CKEDITOR.replace( 'our_mission', {
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
			removeButtons: 'Iframe,Flash,Smiley,Strike,Subscript,Superscript,Anchor,Specialchar'
		});
		var editor = CKEDITOR.replace( 'who_we_are', {
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
			removeButtons: 'Iframe,Flash,Smiley,Strike,Subscript,Superscript,Anchor,Specialchar'
		});
		var editor = CKEDITOR.replace( 'our_vision', {
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
			removeButtons: 'Iframe,Flash,Smiley,Strike,Subscript,Superscript,Anchor,Specialchar'
		});*/
	});
	</script>
</body>
</html>