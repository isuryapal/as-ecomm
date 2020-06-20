<?php 
    include_once 'include/functions.php';
    $functions = new Functions();
    
    if($loggedInUserDetailsArr = $functions->sessionExists()){
      header("location: index.php");
      exit;
    }


    if(isset($_POST['mobile']) && !empty($_POST['mobile']) &&  !empty($_POST['email'])  && !empty($_POST['first_name'])) {
        $first_name = $functions->escape_string($functions->strip_all($_POST['first_name']));
        $email = $functions->escape_string($functions->strip_all($_POST['email']));
        $mobile = $functions->escape_string($functions->strip_all($_POST['mobile']));
        $user_type = $functions->escape_string($functions->strip_all($_POST['user_type']));
        if(!empty($email) && !empty($user_type) && $functions->isCustomerEmailUniqueType($email,$user_type)){
            $newCustomerDetails = $functions->addUser($_POST);
          //print_r($newCustomerDetails);
          //verify-email mail from Main page
            if(isset($newCustomerDetails['userId'])){ // registration success
                // == SEND EMAIL ==
                include_once("include/emailers/registration-email-verify.inc.php"); // $emailMsg
                //SMTP
                include_once("include/classes/Email.class.php");
            
                $to = $email;
                $subject = SITE_NAME." | New Customer Registration";
                //var_dump($subject); exit;
                $emailObj = new Email();
                //$emailObj->setAddress("hardik@innovins.com");
                $emailObj->setAddress($to);
                $emailObj->setSubject($subject);
                $emailObj->setEmailBody($emailMsg);
                $emailObj->sendEmail();
                //SMTP END
            }
                //verify-email mail::end
                //echo $emailMsg; exit;
            $encoded_email =  base64_encode($email);
            //$message = "Dear ".$first_name.", Thank you for registering with Team ".SITE_NAME;
            //$functions->sentSms($mobile,$message);
            header("location:".BASE_URL."/thankyou.php?registersuccess&thankyou=".$encoded_email);
            exit;
        } else {
            header("location:".BASE_URL."/register.php?registerfailed&alredyRegistredEamil");
            exit;
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
				    <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
			 	    <li>Register</li>
			    </ul>
		    </div>
        </div>
    
        <section class="regisform">
            <div class="container">
                <div class="row">
                    <div class="regiscontainer">
						<div class="formbox">
							<form action="" method="POST" id="registration-form">
								<h1>Create Account</h1>
							
								<div id="vender" class="" >
									<div class="venderform">
										<input type="text" name="first_name" class="form-control" placeholder="Name* " required="" />
										<input type="text" name="email" class="form-control" placeholder="Email ID* " required="" />
										<input type="text" name="mobile" class="form-control" placeholder="Mobile*" required="" />
										<input type="password" name="password" id="password" class="form-control" placeholder="Password*" required="" />
                                        <input type="password" name="repassword" class="form-control" placeholder="Confirm Password*" required="" />
                                        <ul class="nav nav-tabs">
                                          <span>Customer Type</span><br>
                                        <li><input type="radio" name='user_type' value='b2b' data-id="vender" checked/>&nbsp; Business Use</li>
                                        <li><input type="radio" name='user_type' value='b2c' data-id="customer" />&nbsp; Personal Use</li>
                                      </ul><br>
										<div class="vendorDetails">
											<input type="text" name="company_name" class="form-control company_name" placeholder="Company Name*" required="" />
											<input type="text" name="gst_no" class="form-control gst_no" placeholder="GST No*" required="" />  
										</div> 
                                        
										<button class="btn reg-btn" type="submit" name="register">REGISTER</button>
										<p class="already">Already registered ? <a class="green-text" href="login.php">Login Here</a></p>
									</div>
								</div>
							</form>
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
                // user_type_var = 'Vendor';
                $("input[type='radio'][name='user_type']").change(function() {
                    if (this.value == 'b2b') {
                        $(".company_name").attr("required", "true");
                        $(".gst_no").attr("required", "true");
                        $('.vendorDetails').show();
                    }else if (this.value == 'b2c'){
                        $(".company_name").attr("required",false);
                        $(".gst_no").attr("required",false);
                        $('.vendorDetails').hide();
                    }
                    // user_type_var = $('input[name=user_type]:checked').val();
                    // alert(user_type_var)
                });
                $("#registration-form").validate({
                    ignore: ".ignore",
                    rules: {
							first_name: {
								required:true
							},
							email: {
                                
								required: true,
								email:true,
                                emailChcCust:true,
								// remote:{
								// 	url:"<?php echo BASE_URL; ?>/ajaxCheckEmailExists.php",
								// 	type: "post",
        //                             data: {
        //                               user_type: function() {
        //                                 return $('input[name=user_type]:checked').val();
        //                               }
        //                             }
								// }
							},
							mobile: {
								required: true,
								number:true,
								minlength: 10,
								maxlength: 10,
								remote:{
								  url:"<?php echo BASE_URL; ?>/ajaxCheckMobileExists.php",
								  type: "post",
								}
							},
							password: {
								required: true,
								//pwcheck: true,
								minlength: 8,
								maxlength: 12,
							},
							repassword: {
								required: true,             
								equalTo: "#password"
							  },
							gst_no : {
								validateGST: true
							},
                        },
                        messages: {
                            first_name: {
                                required: "Please enter name"
                            },
                            mobile: {
                                required: "please enter contact number",
                                remote:'Sorry, this contact is already registered.'
                            },
                            email: {
                                required: 'please enter your email address',
                                remote:'Sorry, an account is already registered with that E-mail ID.'
                            },
                            password: {
                                required: 'please enter password',
                            },
                            repassword: {
                                required: 'please re enter password',
                            },
                            company_name: {
                                required: 'please enter company name',
                            },
                            gst_no: {
                                required: 'please enter gst no.',
                            },
                        }
                    });
					  jQuery.validator.addMethod("validateGST", function(value, element) {
						return this.optional(element) || /^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1}$/g.test(value);
					}, "Please enter valid GST Number");
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
                      jQuery.validator.addMethod("emailChcCust", function(value) {
                        // ajax started
                        ajaxRes = $.ajax({                            
                        url:"<?php echo BASE_URL; ?>/ajaxCheckEmailExists.php",
                        data:{user_type:$('input[name=user_type]:checked').val(),email:$('input[name=email]').val()},
                        type:"POST",
                        async:false,
                        success: function(response){
                             
                            }
                           
                        });

                        if (ajaxRes.responseText == "true") {
                             return true;   
                         }else{
                            return false;
                         }                         
                        // ajax stped
                        
                      }," Sorry, an account is already registered with that E-mail ID ");
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