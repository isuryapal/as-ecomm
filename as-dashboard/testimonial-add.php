<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();

	$pageName = "Testimonial Master";
	$parentPageURL = 'testimonial-master.php';
	$pageURL = 'testimonial-add.php';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	
	if(isset($_GET['edit'])) {
		$admin->checkUserPermissions('testimonial_update',$loggedInUserDetailsArr);
	} else {
		$admin->checkUserPermissions('testimonial_create',$loggedInUserDetailsArr);
	}

	//include 'csrf.class.php';
	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);
	
	if(isset($_POST['register'])){
		if($csrf->check_valid('post')) {
			$allowed_ext = array('image/jpeg','image/jpg','image/png');
			if(!in_array($_FILES['image_name']['type'],$allowed_ext)) {
				header("location:".$pageURL."?registerfail&msg=Please upload image in correct format");
				exit();
			}
			else {
				//add to database
				$result = $admin->addTestimonial($_POST,$_FILES);
				header("location:".$pageURL."?registersuccess");
			}
		}
	}
	if(isset($_GET['edit'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$data = $admin->getUniqueTestimonialById($id);
	}
	if(isset($_POST['update'])) {
		if($csrf->check_valid('post')) {
			$allowed_ext = array('image/jpeg','image/jpg');
			$id 		= trim($admin->escape_string($admin->strip_all($_POST['id'])));
			if(empty($id)){
				header("location:".$pageURL."?updatefail&msg=Please enter a Text&edit&id=".$id);
				exit();
			} else {
				//update to database
				$result = $admin->updateTestimonial($_POST,$_FILES);
				header("location:".$pageURL."?updatesuccess&edit&id=".$id);
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
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/londinium-theme.min.css" rel="stylesheet" type="text/css">
	<link href="css/styles.min.css" rel="stylesheet" type="text/css">
	<link href="css/icons.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/nanoscroller.css" rel="stylesheet">
	<!-- <link href="css/emoji.css" rel="stylesheet"> -->
	<link href="css/cover.css" rel="stylesheet">

	<style>
		.red{color: #ff0000;}
	</style>

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
					image_name: {
						<?php if(!isset($_GET['edit'])){  ?>
								required:true,
						<?php } ?>	
							extension: 'jpg|jpeg|png'
					},
					user_name:{lettersonly:true, required:true},
					lname:{lettersonly:true, required:true},
					designation:{ required:true},
					active:{ required:true},
					message:{
                        required: function(){
                             CKEDITOR.instances.message.updateElement();
                        }
                    }
				},
				messages:{
					image_name : {extension: 'Upload image with jpg, jpeg or png extension'},
					message: {required: 'Please provide testimonial'},
					active : {required:"please select Activation Status of Testimonial"},
					user_name : {required:"please provide first name."},
					designation : {required:"please provide user designation."}
				}
			});
			$.validator.addMethod("lettersonly", function(value, element) {
	          return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);
	        });
		});
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
						if(isset($_GET['edit'])){echo 'Edit '.$pageName;} 
						else {echo 'Add New '.$pageName;}
					?>
				</div>
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a></li>
					<li><a href="<?php echo $parentPageURL; ?>"><?php echo $pageName; ?></a></li>
					<li class="active">
						<?php  
							if(isset($_GET['edit'])){echo 'Edit '.$pageName;} 
							else {echo 'Add New '.$pageName;}
						?>
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
							<i class="icon-close"></i> <strong><?php echo $pageName; ?> not added.</strong> <?php //echo $admin->escape_string($admin->strip_all($_GET['msg'])); ?>.
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
						<h6 class="panel-title"><i class="icon-library"></i> Testimonial Details</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label>User Image <span class="red">*</span></label>
									<input type="file" name="image_name" data-image-index="0" id=""/>
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>png jpg jpeg</strong>.<br>
										Images must be exactly <strong>134 x 134</strong> pixels.
									</span>
									<br/>
									<?php 
										if(isset($_GET['edit']) && $data['image']!=''){
											$file_name = str_replace('', '-', strtolower( pathinfo($data['image'], PATHINFO_FILENAME)));
											$ext = pathinfo($data['image'], PATHINFO_EXTENSION);
											echo '<img src="../images/testimonials/'.$file_name.'_crop.'.$ext.'" width="170" height="170">';
										} 
									?>
									
								</div>
								<div class="col-sm-4">
									<label>Name <span class="red">*</span></label>
									<input type="text" class="form-control" name="user_name" value="<?php if(isset($_GET['edit'])){ echo $data['name']; }?>"/>
								</div>
								<div class="col-sm-4">
									<label>Designation <span class="red">*</span></label>
									<input type="text" class="form-control" name="designation" value="<?php if(isset($_GET['edit'])){ echo $data['position']; }?>"/>
								</div>
								<div class="col-sm-3">
									<label>Active <span class="red">*</span></label>
									<select class="form-control" name="active">
										<option value="">Select Activation Status</option>
										<option value="Yes" <?php if(isset($_GET['edit']) && $data['active']=="Yes"){ echo 'selected="selected"'; }?> >Yes</option>
										<option value="No" <?php if(isset($_GET['edit']) && $data['active']=="No"){ echo 'selected="selected"'; }?> >No</option>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<label>Message <span class="red">*</span></label>
									<textarea rows="15" name="message" id="message" class="form-control"> <?php if(isset($_GET['edit'])){ echo $data['testimonial']; } ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions text-right">
				<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
				
				<?php if(isset($_GET['edit'])){ ?>
					<input type="hidden" class="form-control" name="id" id="" required="required" value="<?php echo $id ?>"/>
					<button type="submit" name="update" class="btn btn-warning"><i class="icon-pencil"></i>Update <?php echo $pageName; ?></button>
				<?php } else { ?>
					<button type="submit" name="register" class="btn btn-danger"><i class="icon-signup"></i>Add <?php echo $pageName; ?></button>
				<?php	} ?>

				</div>
			</form>

<?php 	include "include/footer.php"; ?>
    
		</div>
	</div>
<link href="css/crop-image/cropper.min.css" rel="stylesheet">
<script src="js/crop-image/cropper.min.js"></script>
<script src="js/crop-image/image-crop-app.js"></script>
<script type="text/javascript" src="js/editor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="js/editor/ckfinder/ckfinder.js"></script>
<script>

$(document).ready(function() {

	$('input[type="file"]').change(function(){loadImagePreview(this, (100 / 100));});

	 var message = CKEDITOR.replace( 'message', {
        filebrowserBrowseUrl : 'js/editor/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'js/editor/ckfinder/ckfinder.html?type=Images',
        filebrowserFlashBrowseUrl : 'js/editor/ckfinder/ckfinder.html?type=Flash',
        filebrowserUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
        filebrowserImageBrowseUrl : 'js/editor/ckfinder/ckfinder.html?type=Images',
        filebrowserImageUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        toolbar:'Standard',
        /*toolbarGroups: [
            
            {"name":"document","groups":["mode"]},
            {"name":"clipboard","groups":["undo"]},
            {"name":"basicstyles","groups":["basicstyles"]},
            {"name":"links","groups":["links"]},
            {"name":"paragraph","groups":["list"]},
            {"name":"insert","groups":["insert"]},
            {"name":"styles","groups":["styles"]},
            {"name":"paragraph","groups":["align"]},
            {"name":"about","groups":["about"]},
        ],*/
        removeButtons: 'Iframe,Flash,Smiley,Strike,Subscript,Superscript,Anchor,SpecialChar,Print,Templates,Preview,NewPage,Paste,PasteText,PasteFromWord,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Blockquote,Language,PageBreak,About,Save'
    });

	CKFinder.setupCKEditor( message, '../' );

});

</script>
</body>
</html>