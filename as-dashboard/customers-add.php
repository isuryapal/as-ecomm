<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	if($_GET['cType']=="b2c"){
		$pageName = "Customer";
	}else if($_GET['cType']=="b2b"){
		$pageName = "Vendor";
	}
	$pageNameAdd = "Address";
	//if(isset($_GET['page']) && !empty($_GET['page'])){ $page = $_GET['page']; }else{ $page = 1; }
	if(isset($_GET['page']) && !empty($_GET['page'])){
		$parentPageURL = 'customers.php?page='.$_GET['page'];
	}else{
		$parentPageURL = 'customers.php?page=1';
	}
	$pageURL = 'customers-add.php';
	$deleteURL = 'address-delete.php';
	$tableName = 'customers_address';
	$addURL = 'add-address.php';
	 
	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	//print_r($loggedInUserDetailsArr);
	/*error_reporting(E_ALL);
	ini_set('display_errors', 1);*/

	$cityRS = $admin->getListOfCities();
	$stateRS = $admin->getListOfStates();

	//include_once 'csrf.class.php';
	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);

	if(isset($_POST['register'])){
		if($csrf->check_valid('post')) {
			$email = trim($admin->escape_string($admin->strip_all($_POST['email'])));
			$name = trim($admin->escape_string($admin->strip_all($_POST['first_name'])));
			$password = trim($admin->escape_string($admin->strip_all($_POST['password'])));
			$mobile = trim($admin->escape_string($admin->strip_all($_POST['mobile'])));
			if(empty($email)){
				header("location:".$pageURL."?registerfail&msg=Please enter a email");
				exit();
			}
			else {
				//add to database
				$result = $admin->addCustomerDetail($_POST);

				include "customer-registration-email-inc.php";
				include_once("include/Email.class.php");
				$emailObj = new Email();
				$emailObj->setAddress($email);
				$emailObj->setSubject("Welcome to ".SITE_NAME);
				$emailObj->setEmailBody($emailMsg);
				$emailObj->sendEmail();

				if(isset($_GET['page']) && !empty($_GET['id'])){
					header("location:".$pageURL."?registersuccess&page=".$_GET['id']."&cType=".$_POST['cType']);
				}else{
					header("location:".$pageURL."?registersuccess"."&cType=".$_POST['cType']);
				}
				exit;
			}
		}
	} 
	if(isset($_GET['edit'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$data = $admin->getUniqueCustomersById($id);
		$dataAddress = $admin->getCustomerAddressesByCustomerId($data['id']);
		$sql = "SELECT * FROM ".PREFIX.$tableName." where customer_id='".$id."' order by created DESC";
		$results = $admin->query($sql);
	}
	if(isset($_POST['update'])) {
		if($csrf->check_valid('post')) {
			//$allowed_ext = array('image/jpeg','image/jpg');
			$id = trim($admin->escape_string($admin->strip_all($_POST['id'])));
			$email = trim($admin->escape_string($admin->strip_all($_POST['email'])));
			if(empty($id) || empty($email)){
				header("location:".$pageURL."?updatefail&msg=Please enter a email&edit&id=".$id."&cType=".$_POST['cType']);
				exit();
			}
			else {
				//update to database
				$result = $admin->updateCustomerDetail($_POST);
				if(isset($_GET['page']) && !empty($_GET['id'])){
					header("location:".$pageURL."?updatesuccess&edit&id=".$id."&page=".$_GET['page']."&cType=".$_POST['cType']);
				}else{
					header("location:".$pageURL."?updatesuccess&edit&id=".$id."&cType=".$_POST['cType']);
				}
				
				exit;
			}
		}
	}	
	if(isset($_GET['page']) && !empty($_GET['page'])) {
		$pageNo = trim($admin->strip_all($_GET['page']));
	} else {
		$pageNo = 1;
	}
	$linkParam = "";


	$query = "SELECT COUNT(*) as num FROM ".PREFIX.$tableName;
	$total_pages = $admin->fetch($admin->query($query));
	$total_pages = $total_pages['num'];


	include_once "include/pagination.php";
	$pagination = new Pagination();
	$paginationArr = $pagination->generatePagination($pageURL, $pageNo, $total_pages, $linkParam);

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

	<script type="text/javascript">
		$(document).ready(function() {
			
			$("#form").validate({
				rules: {
					first_name: {
						required:true
					},
					last_name:{
						required:true
					},
					contact:{
						required:true,
						number:true,						
						minlength:10,
						maxlength:10
					},
					
					state:{
						required:true
					},
					email: {
						required: true,
						<?php 
							if(!isset($_GET['edit'])){
						?>
								remote:{
									url:"<?php echo BASE_URL; ?>/ajaxCheckEmailExists.php",
									type: "post",
								},
						<?php 	
							} ?>
						email: true,
					},
					mobile: {
						required: true,
						number:true,
						minlength: 10,
						maxlength: 10,
						<?php 
							if(!isset($_GET['edit'])){
						?>
								remote:{
									url:"<?php echo BASE_URL; ?>/ajaxCheckMobileExists.php",
									type: "post",
								},
					<?php 	} ?>
					},
					password:{
						//required:true
						minlength:8,
						pwcheck: true,
					},
					repassword:{
						//required:true,
						equalTo:'#password'
					},
					gst_no : {
						validateGST: true,
					},
				},
				messages: {
					first_name: {
						required:"Please enter your first name"
					},
					last_name:{
						required:"Please enter your lasr name"
					},
					mobile:{
						required:"Please enter the 10 digit contact",
						maxlength: "Please enter the 10 digit contact",
						maxlength: "Please enter the 10 digit contact",
						remote: "Contact already registred with us",
					},
					email: {	
						email:"Please enter valid email Id",
						remote:"Email Id already exists"
					},
					password:{
						required:"Please enter password"
					},
					repassword:{
						required:"Please re-enter password",
						equalTo:"Password not match"
					},
					
				}
			});
			jQuery.validator.addMethod("validateGST", function(value, element) {
				return this.optional(element) || /^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1}$/g.test(value);
			}, "Please enter valid GST Number");
			jQuery.validator.addMethod("greaterThan", 
			function(value, element, params) {
				if (!/Invalid|NaN/.test(new Date(value))) {
					return new Date(value) > new Date($(params).val());
				}
				return isNaN(value) && isNaN($(params).val()) 
					|| (Number(value) > Number($(params).val())); 
			},'Must be greater than {0}.');

			jQuery.validator.addMethod("pwcheck", function(value) {
				if(value!=''){
					return /^[A-Za-z0-9@!#]{8,}$/.test(value) // consists of only these
						&& /[a-zA-Z ]/.test(value) // has a lowercase letter
						&& /\d/.test(value) // has a digit
				} else {
					return true;
				}
			}," Password should be minimum 8 characters, alpha numeric & atleast 1 special character");
			
			var start_date = new Date();
			$('.datetimepicker1').datepicker({
				format: "dd-mm-yyyy",
				endDate: 'd', //or you can replace 'd' with 'today'
				autoclose: true
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
<?php if(!isset($_REQUEST['tmp'])){ ?>
		<a href="<?php echo $parentPageURL."&cType=".$_GET['cType']; ?>" class="label label-primary">Back to <?php echo $pageName; ?></a><br/><br/>
<?php }else{ ?>
		<a href="testimonials.php" class="label label-primary">Back to Testimonials</a><br/><br/>
<?php } ?>		
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
						<h6 class="panel-title"><i class="icon-library"></i> Customers Details</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<?php 
								$imageUrl = '';
								if(isset($data['profile_image']) && !empty($data['profile_image'])){
									$image_name = strtolower(pathinfo($data['profile_image'], PATHINFO_FILENAME));
									$image_ext = strtolower(pathinfo($data['profile_image'], PATHINFO_EXTENSION));
									$imageUrl = "/images/profileImg/".$image_name.'_large.'.$image_ext;
									$imageUrls = "../images/profileImg/".$image_name.'_large.'.$image_ext;
									if(file_exists($imageUrls)){
										$imageUrl = BASE_URL.'/'.$imageUrl;
									} else {
										$imageUrl = BASE_URL."/images/user-male-icon.png";
									}
								}
							?>
							
							<?php if(file_exists($imageUrl)){ ?>	
								<img src="<?php echo $imageUrl; ?>" class="img-thumbnail" alt="Cinque Terre" width="100" height="100"><br><br>
							<?php } ?>
							<div class="row"><?php if(!empty($data['customer_no'])){ ?>
								<div class="col-sm-3">
									<label>Unique Customer No.</label>
									<input type="text" class="form-control" disabled value="<?php if(isset($_GET['edit'])){ echo $data['customer_no']; }?>"/>
								</div>
							<?php } ?>
								<div class="col-sm-6">
									<label>Name<em>*</em></label>
									<input type="text" class="form-control" required="required" name="first_name" id="first_name" value="<?php if(isset($_GET['edit'])){ echo $data['first_name']; }?>"/>
									
								</div>
								<div class="col-sm-3">
									<label>Email Verified</label>
									<input disabled type="text" class="form-control" required="required" name="email_verified" id="email_verified" value="<?php if(isset($_GET['edit']) && $data['is_email_verified']==1){ echo "Verified"; }else{ echo "Not Verified"; } ?>"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<?php /*<div class="col-sm-3">
									<label>Mobile<em>*</em></label>
									<input type="text" class="form-control" required="required" name="contact"  value="<?php if(isset($_GET['edit'])){ echo $data['mobile1']; }?>"/>
								</div> */ ?>
								<div class="col-sm-3">
									<label>Email<em>*</em></label>
									<input type="text" class="form-control" required="required" name="email" id="email" value="<?php if(isset($_GET['edit'])){ echo $data['email']; }?>" <?php if(isset($_GET['edit'])) {  echo 'readonly'; } ?>/>
								</div>
								<div class="col-sm-3">
									<label>Mobile<em>*</em></label>
									<input type="text" class="form-control" required="required" name="mobile" id="mobile" value="<?php if(isset($_GET['edit'])){ echo $data['mobile']; }?>" <?php if(isset($_GET['edit'])) {  echo 'readonly'; } ?>/>
								</div>
								<div class="col-sm-3">
									<label>Active</label>
									<select class="form-control" name="active">
										<option value="1" <?php if(isset($_GET['edit']) and $data['active']=='1') { echo 'selected'; } ?>>Yes</option>
										<option value="0" <?php if(isset($_GET['edit']) and $data['active']=='0') { echo 'selected'; } ?>>No</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label>Password<em>*</em></label>
									<input type="password" class="form-control" <?php if(!isset($_GET['edit'])){ echo "required";} ?> name="password" id="password" value="<?php //if(isset($_GET['edit'])){ echo $data['password']; }?>"/>
									<?php if(isset($_GET['edit'])){	?><span class="help-block">(Leave blank if not applicable.)</span><?php }	?>
								</div>
								<?php 
									if(isset($_GET['cType']) && !empty($_GET['cType']) && $_GET['cType'] !='b2c'){
								?>
										<div class="col-sm-3">
											<label>Company Name<em>*</em></label>
											<input type="text" required class="form-control"  name="company_name" id="company_name" value="<?php if(isset($_GET['edit'])){ echo $data['company_name']; }?>"/>
											
										</div>
										<div class="col-sm-3">
											<label>GTS No.<em>*</em></label>
											<input type="text" required class="form-control"  name="gst_no" id="gst_no" value="<?php if(isset($_GET['edit'])){ echo $data['gst_no']; }?>"/>
										</div>
								<?php
									} 
									if(isset($_GET['edit'])){
									//print_r($data);
								?>
										<div class="col-sm-3">
										
										</div>
								<?php } 	
								?>
							</div>
						</div>
					</div>
					<div class="form-actions text-right">
					<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
					<input type="hidden" name="cType" value="<?php if(isset($_GET['cType']) && !empty($_GET['cType'])){ echo $_GET['cType']; } ?>" />
<?php
			if(isset($_GET['edit'])){ ?>
					<input type="hidden" class="form-control" name="id" id="" value="<?php echo $id ?>"/>
					<?php if(!isset($_REQUEST['tmp'])){ ?>
						<button type="submit" name="update" class="btn btn-warning"><i class="icon-pencil"></i>Update <?php echo $pageName; ?></button>
					<?php } ?>
<?php		} else { ?>
					<button type="submit" name="register" class="btn btn-danger"><i class="icon-signup"></i>Add <?php echo $pageName; ?></button>
<?php		} ?>
				</div>
				</div>
			</form>

<?php
	if(isset($_GET['deletesuccess'])){ ?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<i class="icon-checkmark"></i> <?php echo $pageNameAdd; ?> successfully deleted.
		</div><br/>
<?php	} ?>

<?php
	if(isset($_GET['deletefail'])){ ?>
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<i class="icon-close"></i> <strong><?php echo $pageNameAdd; ?> not deleted.</strong> Invalid Details.
		</div><br/>
<?php	} ?>
	
		<br/>
<?php if(isset($_GET['edit']) && $_GET['cType'] !='Vendor'){ ?>
			<?php if(!isset($_REQUEST['tmp'])){ ?>
				<a href="<?php echo $addURL."?page=".$pageNo."&cType=".$_GET['cType']."&cid=".$id; ?>" class="label label-primary">Add <?php echo $pageNameAdd; ?></a><br/><br/>	
			<?php } ?>
			<div class="panel panel-default">
				<div class="datatable-selectable">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Pincode</th>
								<th>City</th>
								<th>State</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
<?php
						$x = (10*$pageNo)-9;
						while($row = $admin->fetch($results)){ 
?>
							<tr>
								<td><?php echo $x++; ?></td>
								<td><?php echo $row['customer_fname'];?></td>
								<td><?php echo $row['pincode'];?></td>
								<td><?php echo $row['city']; ?></td>
								<td><?php echo $row['state']; ?></td>
								
								<td><?php if(!isset($_REQUEST['tmp'])){ ?>
									<a href="<?php echo $addURL; ?>?page=<?php echo $pageNo; ?>&edit&id=<?php echo $row['id']."&cType=".$_GET['cType']."&cid=".$id; ?>" name="edit" class="btn btn-warning btn-xs" title="Click to edit this row"><i class="icon-pencil"></i></a>
									<a class="btn btn-danger btn-xs" href="<?php echo $deleteURL; ?>?page=<?php echo $pageNo; ?>&id=<?php echo $row['id']."&cType=".$_GET['cType']."&cid=".$id; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Click to delete this row, this action cannot be undone."><i class="icon-remove3"></i></a>
								<?php } ?>
								</td>
							</tr>
<?php
						}
?>
						</tbody>
				  </table>
				</div>
			</div>
		
<?php 	}	?>
			
<?php 	include "include/footer.php"; ?>
    
		</div>
	</div>
<script>
	$(document).ready(function(){
		$('.fancybox').fancybox({
			height:300
		});
		
	});
	var BASE_URL = '<?php echo BASE_URL ?>';
	function getBillCity(state) {
		$.ajax({
			url:"ajaxGetCityByState.php",
			data:{state:state},
			type:"post",
			success: function(response){
				var response = JSON.parse(response);
				$("select[name='city']").html(response);
				// alert(response);
			},
			error: function(){
				alert("Something went wrong, please try again");
			},
		});
	}
</script>
</body>
</html>