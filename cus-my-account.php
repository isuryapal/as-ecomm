<?php 
    Include_once("include/functions.php");
    $functions = New Functions();
    
    if(!$loggedInUserDetailsArr = $functions->sessionExists()){
        header("location: ".BASE_URL."/login.php");
        exit;
    }
    
    if($loggedInUserDetailsArr['user_type'] != "b2c"){
        header("location: ".BASE_URL."/login.php?failed&cusLogin");
        exit;   
    }

    if(isset($_POST['id']) && !empty($_POST['id'])){
        $functions->updateRegisteredUser($_POST, $loggedInUserDetailsArr['id']);
        header("location:cus-my-account.php?success");
        exit;
    }
    //print_r($loggedInUserDetailsArr);
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
				<li>My Account</li>
			</ul>
		</div>
    </div>
    <section class="orderreceived">
        <div class="inner-content bt">
            <div class="container">
                <div class="ac-detail-nav-box">
                    <ul class="ac-detail-nav">
                        <li class="active"><a href="cus-my-account.php"><i class="fa fa-user-o" aria-hidden="true"></i>  My Account</a></li>
                        <li><a href="cus-myorder.php"><i class="fa fa-bars" aria-hidden="true"></i>My Orders</a></li>
                        <li><a href="cus-mywishlist.php"><i class="fa fa-heart-o" aria-hidden="true"></i> Wishlist</a></li>
                        <li><a href="cus-myaddressbook.php"><i class="fa fa-map-marker" aria-hidden="true"></i> Address Book</a></li>
                        <div class="clearfix"></div>
                    </ul>
                </div>
                <div class="row">
                    <?php   
                        if(isset($_GET['success'])){ ?>
                            <div class="alert alert-success">
                                Profile Updated Successfully.
                            </div>
                    <?php 
                        } ?>
                    <div class="inner-content bt">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-4 col-lg-pull-4 col-lg-push-4 col-md-6 col-md-pull-3 col-md-push-3">
                                    <div class="login-box">
                                        <form class="login-form" id="userProfile" action="" method="POST">
                                            <input type="text" class="form-control" placeholder="Name" name="name" value="<?php echo $loggedInUserDetailsArr['first_name']; ?>"/>
                                            <input type="text" class="form-control" readonly placeholder="Email Id" name="email" value="<?php echo $loggedInUserDetailsArr['email']; ?>" />
                                            <input type="text"  class="form-control" placeholder="Contact Number" name="mobile" value="<?php echo $loggedInUserDetailsArr['mobile']; ?>" />

                                            <?php 
                                                /*
                                                <input type="text" name="company_name" class="form-control" placeholder="Contact Number" value="<?php echo $loggedInUserDetailsArr['company_name']; ?>" />
                                                <input type="text" name="gst_no" class="form-control" placeholder="Contact Number" value="<?php echo $loggedInUserDetailsArr['gst_no']; ?>" /> */
                                            ?>

                                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" />
                                            <input type="password" name="" id="" class="form-control" placeholder="Confirm Password" />
                                            <small class="leavebspace">(Leave  empty if dont't want to update)</small>
                                            <?php /* <input type="password" name="newrepassword" class="form-control" placeholder="Confirm Password" />*/ ?>
                                            <input type="hidden"  name="id" value="<?php echo $loggedInUserDetailsArr['id']; ?>" />
                                            <button type="submit" name="submit" class="btn red-btn">Save Changes</button>
                                        </form>
                             
                                    </div>
                                </div>
                            </div>
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
                $("#userProfile").validate({
                    ignore: ".ignore",
                    rules: {
                        name: {
                            required:true
                        },
                        email: {
                            required: true,
                            email:true,
                            remote:{
                                data : {id:"<?php echo $loggedInUserDetailsArr['id']; ?>"},
                                url:"<?php echo BASE_URL; ?>/ajaxCheckEmailExists.php",
                                type: "post",
                            }
                        },
                        mobile: {
                            required: true,
                            number:true,
                            minlength: 10,
                            maxlength: 10,
                            remote:{
                                data : {id:"<?php echo $loggedInUserDetailsArr['id']; ?>"},
                                url:"<?php echo BASE_URL; ?>/ajaxCheckMobileExists.php",
                                type: "post",
                            }
                        },
                        password: {
                            pwcheck: true,
                            minlength: 8,
                            maxlength: 12,
                        },
                        newrepassword: {
                            equalTo: "#password"
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
                        newrepassword: {
                            equalTo: "Repassword doesn't matched" ,
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
   </body>
</html>