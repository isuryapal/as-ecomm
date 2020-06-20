<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	if(isset($_GET['page']) && !empty($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}
	if(isset($_GET['url'])){
		$parentPageURL = 'wholesaler-add.php';
		$pageURLadd = 'add-address.php?url&';
	}else{
		$parentPageURL = 'customers-add.php';
		$pageURLadd = 'add-address.php?';
	}
	$pageName = "Address";
	$pageURL = 'add-address.php?page='.$page;
	
	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	
	include_once 'include/classes/CSRF.class.php';
	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);

	$cityRS = $admin->getListOfCities();
	$stateRS = $admin->getListOfStates();

	if(isset($_GET['edit']) && !empty($_GET['id'])){
		$cust_id = $admin->escape_string($admin->strip_all($_GET['cid']));
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$dataAddress = $admin->getCustomerAddressesById($cust_id,$id);
	}
	if(isset($_GET['cid']) && !empty($_GET['cid'])){
		$cust_id = $admin->escape_string($admin->strip_all($_GET['cid']));
	}
	if(isset($_POST['addAddress'])){
		$errorArr = checkForError($admin);

		if(count($errorArr)>0){
			$errorStr = implode("|", $errorArr);
			header("location: ".$pageURLadd."cid=".$cust_id."&cType=".$_GET['cType']."&error=".$errorStr);
			exit;
		} else {
			// add to database
			$admin->addMoreCustomerAddress($_POST);
			header("location: ".$pageURLadd."cid=".$cust_id."&cType=".$_GET['cType']."&registersuccess&page=$page");
			exit;
		}
	} else if(isset($_POST['updateAddress'])){
		$errorArr = checkForError($admin);
		if(isset($_POST['updateAddress']) && !empty($_POST['updateAddress'])){
			$id = $admin->escape_string($admin->strip_all($_POST['updateAddress']));
		} else {
			$errorArr[] = "SELECTADDRESS";
		}

		if(count($errorArr)>0){
			$errorStr = implode("|", $errorArr);
			header("location: ".$pageURLadd."id=".$id."&cType=".$_GET['cType']."&cid=".$cust_id."&edit&error=".$errorStr."&page=$page");
			exit;
		} else {
			// update database
			$admin->updateCustomerAddress($_POST, $id);
			header("location: ".$pageURLadd."cid=".$cust_id."&cType=".$_GET['cType']."&id=".$id."&updatesuccess&edit"."&page=$page");
			exit;
		}
	}

	function checkForError($admin){
		$errorArr = array();
		
		if(isset($_POST['address1']) && !empty($_POST['address1'])){
			$address1 = $admin->escape_string($admin->strip_all($_POST['address1']));
		} else {
			$errorArr[] = "ENTERADDRESS1";
		}
		if(isset($_POST['address2']) && !empty($_POST['address2'])){
			$address2 = $admin->escape_string($admin->strip_all($_POST['address2']));
		}/* else {
			$errorArr[] = "ENTERADDRESS2";
		} */
		if(isset($_POST['state']) && !empty($_POST['state'])){
			$state = $admin->escape_string($admin->strip_all($_POST['state']));
		} else {
			$errorArr[] = "ENTERSTATE";
		}
		if(isset($_POST['city']) && !empty($_POST['city'])){
			$city = $admin->escape_string($admin->strip_all($_POST['city']));
		} else {
			$errorArr[] = "ENTERCITY";
		}
		if(isset($_POST['pincode']) && !empty($_POST['pincode'])){
			$pincode = $admin->escape_string($admin->strip_all($_POST['pincode']));
			if(!is_numeric($pincode) || (strlen($pincode)>6)){
				$errorArr[] = "ENTERVALIDPINCODE";
			}
		} else {
			$errorArr[] = "ENTERPINCODE";
		}
		if(isset($_POST['customer_fname']) && !empty($_POST['customer_fname'])){
			$customer_name = $admin->escape_string($admin->strip_all($_POST['customer_fname']));
		} else {
			$errorArr[] = "ENTERNAME";
		}
		/*if(isset($_POST['customer_contact']) && !empty($_POST['customer_contact'])){
			$customer_contact = $admin->escape_string($admin->strip_all($_POST['customer_contact']));
		} else {
			$errorArr[] = "ENTERCONTACT";
		}*/
		return $errorArr;
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

	<link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
	<script src="js/bootstrap-datepicker.min.js"></script>
	<script src="js/Moment.js"></script>
	<style>
		.em{
			color:red;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#form").validate({
				rules: {
					customer_fname: {
						required:true,
					},
					customer_lname:{
						required:true,
					},
					address1:{
						required:true,
					},
					customer_contact:{
						required:true,
						minlength:10,
						maxlength:10
					},
					state:{
						required:true
					},
					email: {
						required: true,
						email:true
					},
					city:{
						required:true
					},
					pincode:{
						required:true
					},
				},
				messages: {
					customer_fname: {
						required:"Please enter your first name"
					},
					customer_lname:{
						required:"Please enter your lasr name"
					},
					address1:{
						required:"Please enter your address"
					},
					customer_contact:{
						required:"Please enter the contact number between 10 to 15 digit",
						maxlength: "Please enter the contact number between 10 to 15 digit",
						minlength: "Please enter the contact number between 10 to 15 digit",
					},
					email: {	
						email:"Please enter valid email Id",
						remote:"Email Id already exists"
					},
					password:{
						required:"Please enter password"
					},
					state:{
						required:"Please select state"
					},
					city:{
						required:"Please select city"
					},
					pincode:{
						required:"Please enter pincode"
					},
					address1:{
						required:"Please enter address"
					},
					repassword:{
						required:"Please re-enter password",
						equalTo:"Password not match"
					}
				}
			});
			jQuery.validator.addMethod("greaterThan", 
			function(value, element, params) {
				if (!/Invalid|NaN/.test(new Date(value))) {
					return new Date(value) > new Date($(params).val());
				}
				return isNaN(value) && isNaN($(params).val()) 
					|| (Number(value) > Number($(params).val())); 
			},'Must be greater than {0}.');

			
			var start_date = new Date();
			$('.datetimepicker1').datepicker({
				format: "yyyy-mm-dd",
				//startDate: start_date,
				autoclose: true,
			});
			$("#coupon_type").change(validateValue);
			validateValue();
		});
		function validateValue() {
			var percentRules = {
				coupon_value: {
					required: true,
					number: true,
					min:1,
					max: 100
				},
				cashew:
				{
					required: true,
				},
			};
			var amountRules = {
				coupon_value: {
					required: true,
					number: true,
					min:1,
				},
				cashew:
				{
					required: true,
				},
			};
			var coupon_type = $("#coupon_type").val();
			if(coupon_type=='percent') {
				addRules(percentRules);
				removeRules(amountRules);
			}
			else if(coupon_type=='percent') {
				addRules(percentRules);
				removeRules(amountRules);
			}
		}
		function addRules(rulesObj){
			for (var item in rulesObj){
			   $('#'+item).rules('add',rulesObj[item]);
			}
		}

		function removeRules(rulesObj){
			for (var item in rulesObj){
			   $('#'+item).rules('remove');
			}
		}
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
<?php 	if(isset($_GET['cid'])){	?>		
		<a href="<?php echo $parentPageURL."?page=$page&edit&cType=".$_GET['cType']."&id=".$cust_id; ?>" class="label label-primary">Back to <?php echo $pageName; ?></a><br/><br/>
<?php	}	else	{	?>
		<a href="<?php echo $parentPageURL; ?>" class="label label-primary">Back to <?php echo $pageName; ?></a><br/><br/>
<?php	}	?>
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
						<h6 class="panel-title"><i class="icon-library"></i> Customers Address Details</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label>First Name<em>*</em></label>
									<input type="text" class="form-control" required="required" name="customer_fname"  value="<?php if(isset($_GET['edit'])){ echo $dataAddress['customer_fname']; }?>"/>
								</div>
								<!-- <div class="col-sm-3">
									<label>Last Name<em>*</em></label>
									<input type="text" class="form-control" required="required" name="customer_lname"  value="<?php //if(isset($_GET['edit'])){ //echo $dataAddress['customer_lname']; }?>"/>
								</div> -->
								<?php /*
								<div class="col-sm-3">
									<label>Contact<em>*</em></label>
									<input type="text" class="form-control" required="required" name="customer_contact"  value="<?php if(isset($_GET['edit'])){ echo $dataAddress['customer_contact']; }?>"/>
								</div>
								<div class="col-sm-3">
									<label>Email<em>*</em></label>
									<input type="text" class="form-control" required="required" name="email"  value="<?php if(isset($_GET['edit'])){ echo $dataAddress['email']; }?>"/>
								</div> */ ?>
								<div class="col-sm-3">
									<label>State<em>*</em></label>
									<select name="state" class="form-control" required="required" onchange="getBillCity(this.value)">
										<option selected value="">Select...</option>
<?php										while($oneState = $admin->fetch($stateRS)){ ?>
										<option <?php if(isset($_GET['edit']) && $dataAddress['state']==ucfirst($oneState['name'])){ echo 'selected="selected"'; } ?> value="<?php echo ucfirst($oneState['name']); ?>"><?php echo ucfirst($oneState['name']); ?></option>
<?php	} ?>
									</select>
								</div>
								<div class="col-sm-3">
									<label>City<em>*</em></label>
									<select required="" class="form-control" name="city">
									<option selected value="">Select...</option>
<?php									while($oneCity = $admin->fetch($cityRS)){ ?>
											<option <?php if(isset($_GET['edit']) && $dataAddress['city']==ucfirst($oneCity['districtname'])){ echo 'selected="selected"'; } ?> value="<?php echo $oneCity['districtname']; ?>"><?php echo $oneCity['districtname']; ?></option>
<?php	} ?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label>Address Line 1<em>*</em></label>
									<input type="text" required name="address1" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $dataAddress['address1']; }?>" >
								</div>
								<div class="col-sm-4">
									<label>Address Line 2</label>
									<input type="text"  name="address2" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $dataAddress['address2']; }?>">
								</div>
								<div class="col-sm-2">
									<label>Pincode<em>*</em></label>
									<input type="text" class="form-control" minlength="6" maxlength="6" required="required" name="pincode"  value="<?php if(isset($_GET['edit'])){ echo $dataAddress['pincode']; }?>"/>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions text-right">
				<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
				
<?php
			if(isset($_GET['edit'])){ ?>
					<input type="hidden" class="form-control" name="id" id="" required="required" value="<?php echo $id ?>"/>
					<input type="hidden" class="form-control" name="customer_id" id="" required="required" value="<?php echo $cust_id ?>"/>
					<button type="submit" name="updateAddress" class="btn btn-warning" value="<?php  echo $dataAddress['id']; ?>"><i class="icon-pencil"></i>Update <?php echo $pageName; ?></button>
<?php		} else { ?>
					<button type="submit" name="addAddress" class="btn btn-danger"><i class="icon-signup"></i>Add <?php echo $pageName; ?></button>
					<input type="hidden" class="form-control" name="customer_id" id="" required="required" value="<?php echo $cust_id ?>"/>
<?php		} ?>
				</div>
			</form>
			
<?php 	include "include/footer.php"; ?>
    
		</div>
	</div>
</body>
<script>
	var BASE_URL = '<?php echo BASE_URL ?>';
	function getBillCity(state) {
		$.ajax({
			url:BASE_URL + "/ajaxGetCityByState.php",
			data:{state:state},
			type:"post",
			success: function(response){
				var response = JSON.parse(response);
				$("select[name='city']").html(response.cityStr);
			},
			error: function(){
				alert("Something went wrong, please try again");
			},
			complete: function(response){
				
			}
		});
	}
</script>
</html>