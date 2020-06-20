<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	
	$pageName = "Faq Master";
	$parenturl = "faq-master.php";
	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	If(isset($_GET['id']) && !empty($_GET['id'])){
		$admin->checkUserPermissions('faq_update',$loggedInUserDetailsArr);
	}else{
		$admin->checkUserPermissions('faq_add',$loggedInUserDetailsArr);
	}
	If(isset($_GET['id']) && !empty($_GET['id'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$data=$admin->fetch($admin->query("SELECT * FROM ".PREFIX."faq WHERE id='".$id."'"));
	}
	if(isset($_POST['SubmitFaq'])){
		$admin->SubmitFaq($_POST);
		header('location:faq-add.php?sucess');
	}
	if(isset($_POST['updateFaq'])){
		$admin->UpdateFaq($_POST);
		$id = $admin->escape_string($admin->strip_all($_POST['updateid']));
		header('location:faq-add.php?updatesucess&edit&id='.$id);
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
	<link rel="stylesheet" href="<?php echo BASE_URL ?>/assets/css/bootstrap-select.css">
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
					question:{
						required:true
					},
					answer:{
						required:true
					}
				},
				messages: {
					question: {
						extension: 'Please Enter Question'
					},
					answer: {
						extension: 'Please Enter Answer'
					},
					
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
					<?php echo 'Add '.$pageName; ?>
<?php			} ?>
			</div>
			<ul class="breadcrumb">
				<li><a href="index.php">Home</a></li>
				<li><a href="<?php echo $parenturl; ?>"><?php echo $pageName; ?></a></li>
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

		<a href="<?php echo $parenturl; ?>" class="label label-primary">Back to <?php echo $pageName; ?></a><br/><br/>
<?php
		if(isset($_GET['sucess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark3"></i> <?php echo $pageName; ?> successfully added.
			</div><br/>
<?php	} ?>
	


<?php
		if(isset($_GET['updatesucess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark3"></i> <?php echo $pageName; ?> successfully updated.
			</div><br/>
<?php	} ?>
		<form role="form" action="" method="post" id="form" enctype="multipart/form-data">
			<div class="col-md-12">
				<label>Questions<em>*</em></label>
				<textarea name="question" id="question" required><?php if(isset($_GET['id'])){ echo $data['question']; } ?></textarea>
				<br>
			</div>
			<div class="col-md-12">
				<label>Answer<em>*</em></label>
				<textarea name="answer" id="answer" required><?php if(isset($_GET['id'])){ echo $data['answer']; } ?></textarea>
				<br>
			</div>
			<div class="col-md-4">
				<label>Sequence</label>
				<input name="sequence" id="sequence" placeholder="Display Arrangement"class="form-control" value="<?php if(isset($_GET['id'])){ echo $data['sequence']; } ?>">
			</div>
			<div class="col-md-12">
				<?php if(isset($_GET['id']) && !empty($_GET['id'])){ ?>
					<input type="hidden" name="updateid" value="<?php if(isset($_GET['id'])){ echo $data['id']; } ?>" >
					<button name="updateFaq" class="btn btn-danger">Update FAQ</button><br><br>
				<?php }else{ ?>
					<button name="SubmitFaq" class="btn btn-danger">Update FAQ</button><br><br>
				<?php } ?>
			</div>	<br>
		</form>
<?php 	include "include/footer.php"; ?>
    
		</div>
	</div>
	<script src="<?php echo BASE_URL ?>/assets/js/bootstrap-select.js"></script>
	<script type="text/javascript" src="js/editor/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="js/editor/ckfinder/ckfinder.js"></script>
	<script type="text/javascript">
		var editor = CKEDITOR.replace( 'answer', {
			filebrowserBrowseUrl : 'js/editor/ckfinder/ckfinder.html',
			filebrowserImageBrowseUrl : 'js/editor/ckfinder/ckfinder.html?type=Images',
			filebrowserFlashBrowseUrl : 'js/editor/ckfinder/ckfinder.html?type=Flash',
			filebrowserUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
			filebrowserImageUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			filebrowserFlashUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
		});
		var editor = CKEDITOR.replace( 'question', {
			filebrowserBrowseUrl : 'js/editor/ckfinder/ckfinder.html',
			filebrowserImageBrowseUrl : 'js/editor/ckfinder/ckfinder.html?type=Images',
			filebrowserFlashBrowseUrl : 'js/editor/ckfinder/ckfinder.html?type=Flash',
			filebrowserUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
			filebrowserImageUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			filebrowserFlashUploadUrl : 'js/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
		});
		
		CKFinder.setupCKEditor( editor, '../' );
	</script>
</body>
</html>