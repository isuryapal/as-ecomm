<?php 
    include_once 'include/functions.php';
    $functions = new Functions();
    $loggedInUserDetailsArr = $functions->sessionExists();
    error_reporting(E_ALL);
    if(isset($_POST['email']) && !empty($_POST['email'])){
        $functions->contactUsRequest($_POST);
        $name='';
        $h4heading ='';
        $content='';
        $adminDetails = $functions->getAdminDetails();    
        
        include_once("include/classes/Email.class.php");

        $captcha;
        if(isset($_POST['g-recaptcha'])){
          $captcha=$_POST['g-recaptcha'];
        }else{
            header("location:contact.php?solveCaptcha");
            exit;
        }          
        $secretKey = "6Ld9t9cUAAAAABNDIYWC9dKawA5fbnulVXuiCu-M";
        $ip = $_SERVER['REMOTE_ADDR'];
        // post request to server
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
        $response = file_get_contents($url);
        $responseKeys = json_decode($response,true);
    
        $name ='Following User Details As Are Follow';

        $content    .= "<p>Name : ".$_POST['name']."</p>";
        $content    .= "<p>Email : ".$_POST['email'];
        $content    .= "<p>Contact : ".$_POST['mobile']."</p>";
        $content    .= "<p>Message : ".$_POST['message']."</p>";
             
        include_once("thank-you.php");
    
        $subject = SITE_NAME." | New Contact us Request";
        $emailObj = new Email();

        $emailObj->setAddress("suryapal@innovins.com");
        // $emailObj->setAddress($adminDetails['email']);
        $emailObj->setSubject($subject);
        $emailObj->setEmailBody($emailMsg);
        $emailObj->sendEmail();   
        
        header("location:contact.php?success");
        exit;
    }
?>
<!DOCTYPE>
<html>
   <head>
        <title>Arvind Sanitary</title>
        <?php include("include/header-link.php");?>
   </head>
   <body class="contact-page">
        <!--Top start menu head-->       
        <?php include("include/header.php");?>

	    <div class="only-breadcrumbs">
		    <div class="container">
			    <ul class="breadcrumbs">
				    <li><a href="index.php">Home</a></li>
				    <li>Contact Us</li>
			    </ul>
		    </div>
	    </div>
	    <div class="inner-content">
		   <div class="container">
                <section class="contact-sec">
                    <div class="container">
                        <div class="row">
                            <?php if(isset($_GET['success'])){ ?>
                                    <div class="alert alert-success" role="alert">
                                        Thank you for contacting us our team reach you shortly. 
                                    </div>
                            <?php } ?>
                            <div class="col-md-5">
                                <div class="contact-form">
                                    <form method="POST" id="contactUs">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Name <em>*</em></label>
                                                    <input type="text" name="name" value="<?php if(isset($loggedInUserDetailsArr['first_name'])){ echo $loggedInUserDetailsArr['first_name']; } ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email ID <em>*</em></label>
                                                    <input type="text" name="email" value="<?php if(isset($loggedInUserDetailsArr['email'])){ echo $loggedInUserDetailsArr['email']; } ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Contact Number <em>*</em></label>
                                                    <input type="text" name="mobile" value="<?php if(isset($loggedInUserDetailsArr['mobile'])){ echo $loggedInUserDetailsArr['mobile']; } ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Message <em>*</em></label>
                                                    <textarea class="form-control" rows="3" name="message"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-left">
                                                <div class="form-group form-group-inline">
                                                    <span class="msg-error error"></span>
                                                        <div class="g-recaptcha" name="g-recaptcha" data-sitekey="6Ld9t9cUAAAAALuHCdrmyPqPFD1dJrBSXNV7TAFR" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;">
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="submit" id="submitcontact" name="submit" value="Send" class="shop-now-btn dark-yellow-btn">
                                                 </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="contact-info">
					               <ul class="ct-page get-in-touch">
						                <li><i class="fa fa-map-marker" aria-hidden="true"></i>	
							               <p class="address"><?php echo nl2br($contactUsCMSDetails["address"]); ?></p>
						                </li>
						                <li><i class="fa fa-phone" aria-hidden="true"></i>
							                <a href="tel:<?php echo $contactUsCMSDetails["contact"]; ?>"><?php echo $contactUsCMSDetails["contact"]; ?></a>
                                        </li>
						                <li><i class="fa fa-envelope-o" aria-hidden="true"></i>
						                    <!-- <a href="mailto:info@arvindsanitary.com">info@arvindsanitary.com</a> -->
                                            <a href="mailto:<?php echo $contactUsCMSDetails["email"]; ?>"><?php echo $contactUsCMSDetails["email"]; ?></a>
                                        </li>
					                </ul>
                                </div>
				
                            </div>

                        </div>
                    </div>

                </section>

		    </div>
            <div class="map">
                        <iframe src="<?php echo $contactUsCMSDetails['link']; ?>" width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen=""></iframe> 
                    </div>
	    </div>
        <!--Main End Code Here-->
        <!--footer start menu head-->
        <?php include("include/footer.php");?> 
        <!--footer end menu head-->
        <?php include("include/footer-link.php");?>
        <script>
            $(document).ready(function(){
                $("#contactUs").validate({
                    ignore: ".ignore",
                    rules: {
                      name: {
                        required:true
                      },
                      email: {
                        required: true,
                        email:true,
                      },
                      mobile: {
                        required: true,
                        number:true,
                        minlength: 10,
                        maxlength: 10,
                      },
                      message: {
                        required: true,
                      },
                  
                    },
                    messages: {
                      name: {
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
                        required: 'please enter contact no',
                      },
                      message: {
                        required: 'please enter message',
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
                $("#submitcontact").submit(function(event) {
                    var recaptcha = $("#g-recaptcha-response").val();
                    if (recaptcha === "") {
                        event.preventDefault();
                        alert("Please check the recaptcha");
                    } else {
                        if($("#contactUs").valid()===true){
                            $("#submitcontact").attr("disabled", true);
                        }
                    }
                });
            });
        </script>
   </body>
</html>