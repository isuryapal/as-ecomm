<?php
    include_once 'include/functions.php';
    $functions = new Functions();

	if($loggedInUserDetailsArr = $functions->sessionExists()){
		header("location: index.php");
		exit;
	}

	$errorArr = array();
	if(isset($_GET['success'])){
		
	} else if(isset($_GET['v']) && !empty($_GET['v'])) {
		$v = $functions->escape_string($functions->strip_all($_GET['v']));
		//echo $v; exit;
		if( (empty($v) || !preg_match("/^[A-z0-9]{1,}$/", $v)) ){
			$errorArr[] = "INVALIDURL";
		}
		if(count($errorArr)>0){
			$errorStr = implode("|", $errorArr);
			header("location: ".BASE_URL."/reset-customer-password.php?error=".$errorStr);
			exit;
		} else {
			if(isset($_POST['reseturl']) && isset($_POST['password']) && !empty($_POST['password'])  ){
				$passwordResetToken = $functions->escape_string($functions->strip_all($_POST['reseturl']));
				$newPassword = $functions->escape_string($functions->strip_all($_POST['password']));

				$updatedRows = $functions->resetCustomerPassword($passwordResetToken, $newPassword);
				if($updatedRows>0){ // new password was set
					header("location: ".BASE_URL."/reset-customer-password.php?success");
					exit;
				} else { // user already reset the password or user does not exists
					header("location: ".BASE_URL."/reset-customer-password.php?error=INVALIDURL");
					exit;
				}
			}
		}
	} else if(!isset($_GET['error']) || empty($_GET['error'])){
		header("location: ".BASE_URL."/reset-customer-password.php?error=INVALIDURL");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="format-detection" content="telephone=no" />    
	<title>Password Reset</title>
	<?php include("include/header-link.php");?>
	<!-- END: STYLESHEET -->
</head>
<body>
	<div class="wrapper">
		<!-- SET HEADER -->
		<?php include("include/header.php");?>
		<!-- END HEADER -->
		<!-- SET MAIN -->
		<section class="loginpage">
            <div class="inner-content bt">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4 col-sm-pull-4 col-sm-push-4">
                            <div class="login-box">
                            <h4 class="page-heading">Reset Password</h4>
                                <?php
									if(isset($_GET['error']) && !empty($_GET['error'])){
								?>
										<div class="alert alert-danger">
											<ul>
												<?php
													$errorArr = explode("|", $_GET['error']);
													foreach($errorArr as $oneError){
														switch($oneError){
															case "INVALIDURL":
																echo "<li><i class=\"fa fa-warning\"></i> This link is no longer active</li>";
																break;
															default:
																break;
														}
													}
												?>
											</ul>
										</div>

										<div class="row center">
											<div class="col-sm-4">
												<a id="login-pg" data-fancybox data-type="iframe" data-src="<?php echo BASE_URL; ?>/login.php?loginwithnewpwd" href="javascript:;" class="email_sbt_btn red_btn login-btn1">Login Now <i class="fa fa-chevron-right"></i></a>
											</div>
										</div>
										<br>
                            	<?php
									}
									if(isset($_GET['success'])){
								?>
										<div class="alert alert-success">
											<i class="fa fa-check"></i> You have successfully reset your <?php echo SITE_NAME; ?> account password. You can now <a href="<?php echo BASE_URL; ?>/login.php">login</a> to your <?php echo SITE_NAME; ?> account.
										</div>
										<a href="<?php echo BASE_URL; ?>/login.php" class="btn red-btn">Login Now <i class="fa fa-chevron-right"></i></a>
										<br>
										<br>
                                <?php
									} else if(isset($_GET['v']) && !empty($_GET['v'])) {
								?>
		                                <form id="register-form" method="post">
											<ul class="reset">
												<li>
													<input type="password" class="form-control" placeholder="Enter New Password" name="password" id="password" />
												</li>
												<li>
													<input type="password" class="form-control" placeholder="Re-enter New Password" name="cnfpassword" id="cnfpassword" />
												</li>
												<li>
													<input name="reseturl" type="hidden" id="reseturl" value="<?php echo $v; ?>">
													<button type="submit" class="btn red-btn" name="register">Submit</button>
												</li>
											</ul>
										</form>
							<?php 
									} ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </section>
		<!-- END MAIN -->

		<!-- SET FOOTER -->
		<?php include("include/footer.php");?>
		<!-- END FOOTER -->
	</div>
	<?php include("include/footer-link.php");?>
	<script>
		$(document).ready(function(){
			$("#register-form").validate({
                ignore: ".ignore",
				rules: {
					password:{
						required:true,
						// pwcheck:true
						minlength: 8,
						maxlength: 12,
					},
					cnfpassword:{
						required:true,
						equalTo: '#password'
					}
				},
				messages: {
					fname: {
						required: "First name is required"
					},
					lname: {
						required: "Last name is required"
					},
					email: {
						required: 'please enter your email address',
						remote:'Sorry, an account is already registered with that E-mail ID.'
					},
				}
			});

			jQuery.validator.addMethod("lettersonly", function(value, element) {
				return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);
			}, "Only letters are allowed");
			jQuery.validator.addMethod("pwcheck", function(value) {
				if(value!=''){
					return /^[A-Za-z0-9@!#]{8,}$/.test(value) // consists of only these
						&& /[a-zA-Z ]/.test(value) // has a lowercase letter
						&& /\d/.test(value) // has a digit
				} else {
					return true;
				}
			}," Password should be minimum 8 characters, alpha numeric & atleast 1 special character");
		});
	</script>
	<script src='<?php echo BASE_URL; ?>/js/ajax-update-cart.js'></script>
</body>
</html>