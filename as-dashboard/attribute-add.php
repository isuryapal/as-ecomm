<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';

	$admin = new AdminFunctions();
	$pageName = "Attribute";
	$parentPageURL = 'attribute-master.php';
	$pageURL = 'attribute-add.php';
	$tableName = 'attribute_master';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}

	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);
	
	// Get List Of Pets Into The Database
	
	if(isset($_POST['register'])){	
	
		$allowed_ext = array('image/jpeg','image/jpg');
		
		$category_ids 		= $_POST['category_ids'];
		$attribute_name 	= trim($admin->escape_string($admin->strip_all($_POST['attribute_name'])));
		
		if(empty($category_ids) || count($category_ids) == 0){
			header("location:".$pageURL."?registerfail&msg=Please select atleast one Category");
			exit();
		} else if(empty($attribute_name)){
			header("location:".$pageURL."?registerfail&msg=Please enter a Attribute Name");
			exit();
		} else {
			//add to database
			$newCustomerDetails = $admin->addAttribute($_POST);

			/*if($newCustomerDetails){

                $firstAdminDetails = $admin->getFirstAdminDetails();

				include_once("../include/emailers/registration-welcome-email.inc.php"); // $emailMsg

                $emailObj = new Email();
                $emailObj->setSubject("Welcome to ".SITE_NAME);
                $emailObj->setAdminAddress($firstAdminDetails['email']);
                $emailObj->setEmailBody($emailMsg);
                
                $emailArr = array($email, 'noreply@pitchit.com');
                foreach ($emailArr as $key => $emailID) {
                    $emailObj->setAddress($emailID);
                    $emailObj->sendEmail();
                }
			}*/

			header("location:".$pageURL."?registersuccess");
		}		
	}
	
	if(isset($_GET['edit'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$data = $admin->getUniqueAttributeById($id);
	}
	if(isset($_POST['id']))	{
	
		$allowed_ext = array('image/jpeg','image/jpg');
		
		$category_ids 		= $_POST['category_ids'];
		$attribute_name 	= trim($admin->escape_string($admin->strip_all($_POST['attribute_name'])));
		
		if(empty($category_ids) || count($category_ids) == 0){
			header("location:".$pageURL."?updatefail&msg=Please select atleast one Category&edit&id=".$id);
			exit();
		} else if(empty($attribute_name)){
			header("location:".$pageURL."?updatefail&msg=Please enter a Attribute Name&edit&id=".$id);
			exit();
		} else {
			//add to database
			$id	= $admin->escape_string($admin->strip_all($_POST['id']));
			
			$result 	= $admin->updateAttribute($_POST);
			header("location:".$pageURL."?updatesuccess&edit&id=".$id);
		}
	}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo TITLE; ?> | Add Attribute</title>
	<link rel="shortcut icon" href="<?php echo BASE_URL; ?>/images/logo.png" type="image/png" />
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
			<form role="form" action="" method="POST" id="form" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6 class="panel-title"><i class="icon-library"></i> Attribute Details</h6>
					</div>
					<div class="panel-body" id="scrolly">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label>Category Name <span class="red">*</span></label>
									<Select class="select-multiple" name="category_ids[]" id="category_ids" multiple placeholder="Select Cateogry">
									<?php
										$category_ids_arr = array();
										if(isset($_GET['edit'])){
											$sqlQry = $admin->query("select * from ".PREFIX."category_attribute_list where attribute_id = '".$id."'");
											while($rowCats = $admin->fetch($sqlQry)){
												$category_ids_arr[] = $rowCats['category_id'];
											}
										}

										$categoriesList = $admin->getAllCategories();
										while($categories = $admin->fetch($categoriesList)){
									?>
										<option value="<?php echo $categories['id']; ?>" <?php if(isset($_GET['edit']) && count($category_ids_arr) > 0 && in_array($categories['id'],$category_ids_arr)) { echo "selected"; } ?>><?php echo $categories['category_name']; ?></option>
									<?php
										}
									?>
									</Select>
								</div>
								<div class="col-sm-4">
									<label>Attribute Name <span class="red">*</span></label>
									<input type="text" class="form-control" required="required" name="attribute_name" value="<?php if(isset($_GET['edit'])){ echo $data['attribute_name']; }?>"/>
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
							<h6 class="panel-title"><i class="icon-library"></i> Attribute Features</h6>
							<button type="button" class="btn btn-default pull-right" id="add-a-clone"><i class="icon-bubble-plus"></i> Add More</button>
					</div>
					<div class="panel-body" id="clone-house">
					<?php
						if(isset($_GET['edit'])){
							$featuresResult=$admin->getAllAttributeFeaturesByAttributeId($data['id']);
							$featureCounter=1;
							if($admin->num_rows($featuresResult)>0){
								while($featuresRow=$admin->fetch($featuresResult)){
					?>
									<div <?php if($featureCounter==1){ ?>id="clone-me" <?php } ?> class="clone-row">
										<div class="form-group" >
											<div class="row">
												<div class="col-sm-3">
													<label>Feature</label>
													<input type="text" class="form-control" name="features[]" value="<?php echo $featuresRow['feature'] ?>" id="" />
													<input type="hidden" class="form-control" name="attribute_feature_id[]" value="<?php echo $featuresRow['id'] ?>" id="" />
													<span class="help-block"></span>
												</div>
												<div class="remove-row-wrapper">
													<?php if($featureCounter!=1){ 
													?>
														<div class="col-sm-1">
															<label>Remove</label>
															<button type="button" class="btn btn-default form-control icon-close remove-row" ></button>
														</div>
												<?php } ?>
												</div>
											</div>
										</div>
										
									</div>
					<?php  $featureCounter++;     } 
							}else{ ?>
								<div id="clone-me" class="clone-row">
									<hr>
									<div class="form-group" >
										<div class="row">
											<div class="col-sm-3">
												<label>Feature</label>
												<input type="text" class="form-control" name="features[]" id="" />
												<input type="hidden" class="form-control" name="attribute_feature_id[]" value="" id="" />
												<span class="help-block"></span>
											</div>
											<div class="remove-row-wrapper"></div>
										</div>
									</div>
									
								</div>	
					<?php   }
						}else{
					?>
								<div id="clone-me" class="clone-row">
									<hr>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-3">
												<label>Feature</label>
												<input type="text" class="form-control" name="features[]" id="" />
												<input type="hidden" class="form-control" name="attribute_feature_id[]" value="" id="" />
												<span class="help-block"></span>
											</div>
											<div class="remove-row-wrapper"></div>
										</div>
									</div>									
								</div>
					<?php
						}
					?>
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

	<script type="text/javascript">
		$(document).ready(function() {

			$(document).on("change",'input[name="profile_pic"]',function(){
				// loadImageInModal(this);
				loadImagePreview(this, (300 / 300));
			});
			
			$("#form").validate({
				rules: {
					"category_ids[]": {
						required: true
					},
					attribute_name: {
						required: true
					}
				},
				messages: {
					category_id: {
						required:"Please select Category"
					},
					attribute_name: {
						required:"Please enter Attribute Name"
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
			
			$("#add-a-clone").on("click", function(){
				// part 1: get the target
				var target = $("#clone-me");
				// part 2: copy the target
				var newNode = target.clone(); // clone a node
				newNode.attr("id",""); // remove id from the cloned node
				newNode.find("input").val(""); // clear all fields
				newNode.find("textarea").val(""); // clear all fields
				newNode.find(".memimg").html(""); // clear all fields
				newNode.find(".showCharCnt").html("120");
				// part 3: add a remove button
				var closeBtnNode = $('<div class="col-sm-1"><label>Remove</label><button type="button" class="btn btn-default form-control icon-close remove-row" ></button></div>');
				newNode.find(".remove-row-wrapper").html(closeBtnNode);
				// part 4: append the copy
				$("#clone-house").append(newNode); // append the node to dom
				$(".remove-row").on("click", removeRow);
			});

			$(document).on("click", ".remove-row", removeRow);

			function removeRow(){
				$(this).closest(".clone-row").remove();
			}
		});
	
	</script>

</body>
</html>