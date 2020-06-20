<?php 
  Include_once("include/functions.php");
  $functions = New Functions();

	if(isset($_GET['cusLogin'])){
	 
	}else{
	   if($loggedInUserDetailsArr = $functions->sessionExists()){
			//print_r($loggedInUserDetailsArr); exit;
			header("location: index.php");
			exit;
		}
	}

  if(isset($_GET['redirect']) and !empty($_GET['redirect'])) {
    $redirect = $functions->escape_string($functions->strip_all($_GET['redirect']));
  } else {
    $redirect = "";
  }

  if(isset($_POST['login_btn'])){
        $email = $functions->escape_string($functions->strip_all($_POST['email']));
        $password = $functions->escape_string($functions->strip_all($_POST['password']));
        $redirect_url = $functions->escape_string($functions->strip_all($_POST['redirect_url']));
        // $user_type = $functions->escape_string($functions->strip_all($_POST['user_type']));
        $successURL = 'index.php';
    if(!empty($redirect_url)) {
      $successURL = $redirect_url;
    }

        if(empty($email) || empty($password)){
			//failed url
			header("location:".BASE_URL."/login.php?failed");
			exit;
        } else {
			$functions->userLoginVC($_POST, $successURL, "login.php?failed");
			exit;
        }
  }

  if(isset($_POST['forgot_btn'])) {
    $email  = $functions->escape_string($functions->strip_all($_POST['email']));

    if(empty($email) || !preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/",$email)){
      header("Location: ".BASE_URL."/sign-in.php?fp&loginfail&forgot_email");
      exit;
    } else {
      $passwordResetResponse = $functions->setUserPasswordResetCode($email);
      if($passwordResetResponse['updateSuccess']>0) { // new password was updated in database
        include_once("include/emailers/forgot-pwd-email.inc.php");

        //SMTP
        include_once("include/classes/Email.class.php");

        $to = $email;
        $subject = " Password Reset | Welcome to ".SITE_NAME;

        $emailObj = new Email();
        //$emailObj->setAddress("hardik@innovins.com");
        $emailObj->setAddress($to);
        $emailObj->setSubject($subject);
        $emailObj->setEmailBody($emailMsg);
        $emailObj->sendEmail();
        //SMTP END

        header("location: ".BASE_URL."/login.php?fp&resetsuccess");
        exit;
      } else {
        // customer does not exists
        header("location: ".BASE_URL."/login.php?fp&user-does-not-exists");
        exit;
      }
    }
  }
?>
<!DOCTYPE>
<html>
   <head>
	<title>Arvind Sanitary</title>
      <?php include("include/header-link.php");?>
   </head>
   <body class="home">
      <!--Top start menu head-->       
      <?php include("include/header.php");?>
      
      <div class="only-breadcrumbs">
		<div class="container">
			<ul class="breadcrumbs">
				<li><a href="index.php">Home</a></li>
				<li>Login</li>
			</ul>
		</div>
	</div>
	  
     <section class="loginpage">
          <div class="inner-content bt">
               <div class="container">
                    <div class="row">
                      <?php
                         if(isset($_GET['failed'])){
                                if(isset($_GET['account-not-active'])){
                                    $errMsg = "Your Account is Inactive. Please kindly contact Administrator or support.";
                                } else if(isset($_GET['email-not-verified'])){
                                    $errMsg = "Your Account is Inactive. Please kindly verify your email by clicking on the link that has been sent to your registered email account.";
                                } else if(isset($_GET['wrong-password'])) {
                                    $errMsg = "Invalid Login ID or password, please try again.";
                                }elseif(isset($_GET['cusLogin'])){
                                    $errMsg = "Customer login required.";  
                                }elseif(isset($_GET['venLogin'])){
                                    $errMsg = "Vendor login required.";  
                                }else {
                                    $errMsg = "Invalid Login ID or password, please try again.";
                                }
                      ?>
                          <br>
                          <div class="alert alert-danger alert-dismissible" role="alert">
                            <strong><?php echo $errMsg; ?></strong>
                          </div><br/>
                      <?php
                        }
                      ?>
                        <div class="col-lg-4 col-lg-pull-4 col-lg-push-4 col-md-6 col-md-pull-3 col-md-push-3 p0">
                            <div class="login-box">
                            <h1 class="page-heading">Login</h1>
                                   <form class="login-form" id="login-form" method="post">
                                     
                                        <input type="text" name="email" class="form-control" placeholder="Email ID" required="" autofocus="" />
                                        <input type="password" name="password" class="form-control" placeholder="Password" required="" />
                                        <!-- <ul class="nav nav-tabs">
                                        <span>Customer Type</span><br>
                                          <li><input type="radio" name='user_type' value='b2b' data-id="vender" checked/>&nbsp; B2B</li>
                                          <li><input type="radio" name='user_type' value='b2c' data-id="customer" />&nbsp; B2C</li>
                                        </ul><br> -->
                                        <input type="hidden" placeholder="" name="redirect_url" id="redirect_url" value="<?php echo $redirect; ?>" />
										                    <!-- <input type="hidden" name="user_type" value="b2c" data-id="customer"> -->

                                        <button class="btn red-btn loginbtn" name="login_btn" >Login</button>
                                         <p>Don’t have an account ?  <a href="register.php" class="green-text"> Register Here </a></p>
                                        <p class="fp-btn">Forgot Password ?</p>
                                   </form>
                                   <?php
                                        if(isset($_GET['resetsuccess'])){
                                   ?>
                                             <div class="alert alert-success hideOnback">
                                                  <p><em class="fa fa-check"></em> We have sent an email to your registered email address with the steps to reset your password, please follow the steps in the email to reset your account password.</p>
                                             </div>
                                   <?php
                                        } else {
                                             if(isset($_GET['user-does-not-exists'])){
                                   ?>
                                                  <div class="alert alert-danger hideOnback">
                                                       <p><em class="fa fa-warning"></em> This mail id is not registered with us.</p>
                                                  </div>
                                   <?php
                                             }
                                           }
                                   ?>
                                   <form class="fp-forms" style="display:none;" id="forgot-form" method="post">
                                        <input type="text" name="email" class="form-control" placeholder="Email ID" required="" />
                                        <button type="submit" name="forgot_btn" class="btn red-btn">Reset</button>
                                        <p class="back-btn">Back to Login</p>
                                   </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </section>
	  
         <!--Main End Code Here-->
      <!--footer start menu head-->
      <?php include("include/footer.php");?> 
      <!--footer end menu head-->
      <?php include("include/footer-link.php");?>
         <script>
			$(document).ready(function(){
				<?php 
					if(isset($_GET['user-does-not-exists']) || isset($_GET['resetsuccess'])){ ?>
						$(".fp-btn").trigger("click");
            $('.page-heading').text("Forgot Password");
				<?php 
					} ?>
				$(".fp-btn").click(function(){
          $('.page-heading').text("Forgot Password");
					$(".login-box").addClass("switch-form");

				});
				$(".back-btn").click(function(){
          $('.page-heading').text("Login");
				  $(".login-box").removeClass("switch-form");
          $(".hideOnback").hide();
				});
			  
			});
         </script>
         <script>
            $(document).ready(function(){

              <?php if(isset($_GET['fp'])) { ?>
                $('.for_pass').click();
              <?php } ?>

                $("#login-form").validate({
                    ignore: ".ignore",
                    rules: {
                        email: {
                            required: true,
                            email:true,
                        },
                        password: {
                            required: true,
                        },
                    },
                    messages: {
                        email: {
                            required: 'please enter your email address',
                        },
                        password: {
                            required: 'please enter your password',
                        },
                    }
                });
                $("#forgot-form").validate({
                    ignore: ".ignore",
                    rules: {
                        email: {
                            required: true,
                            email:true,
                        },  
                    },
                    messages: {
                        email: {
                            required: 'please enter your email address',
                        },
                    }
                });
                $("#news_letterSub").validate({
                    ignore: ".ignore",
                    rules: {
                        news_email: {
                            required: true,
                            email:true,
                        }
                    },
                    messages: {
                        news_email: {
                            required: 'Please enter your email address',
                        },
                    }
                });
            });
        </script>
   </body>
</html>