<?php 
    Include_once("include/functions.php");
    $functions = New Functions();

    if(!$loggedInUserDetailsArr = $functions->sessionExists()){
        header("location: ".BASE_URL."/login.php");
        exit;
    }

    if(isset($_GET['q']) && !empty($_GET['q'])){
        $id = $functions->escape_string($functions->strip_all($_GET['q']));
        $addressDetails = $functions->getUniqueCustomerAddressById($id, $loggedInUserDetailsArr['id']);
    }
    $billing='';
    if(isset($_POST['Billing']) && !empty($_POST['Billing'])){
        $billing = $_POST['Billing'];
        $billing = "&$billing=$billing";
    }
    $shipping = '';
    if(isset($_POST['shipping']) && !empty($_POST['shipping'])){
        $shipping = $_POST['shipping'];
        $shipping = "&$shipping=$shipping";
    }
    if(isset($_GET['hideBack'])){
        $addAtt = 'hideBack';
    }else{
        $addAtt='';
    }
    if(isset($_POST['addAddress'])){
        if(isset($_POST['pincode']) && !empty($_POST['pincode'])){
            /*$deliveryApplicable =  $functions->isDeliveryAvilabelForthisPincode($_POST['pincode']);
            if($deliveryApplicable){*/
                $functions->addCustomerAddress($_POST, $loggedInUserDetailsArr['id']);
               // echo '<script type="text/javascript">window.parent.location.href="my-addressbook.php?success";parent.jQuery.fancybox.close();</script>';
                header("location: add-address.php?success&$addAtt$billing$shipping");
                exit;
           /* }else{
                
                header("location:add-address.php?NDELIVERY");
                exit;
            }  */
        }else{
            header("location:add-address.php?INVALIDPINCODE");
            exit;
        } 
        
    }  
    if(isset($_POST['id']) && !empty($_POST['id'])){

        if(isset($_POST['pincode']) && !empty($_POST['pincode'])){

            /*$deliveryApplicable =  $functions->isDeliveryAvilabelForthisPincode($_POST['pincode']);   
            if($deliveryApplicable){*/
                $functions->updateCustomerAddress($_POST, $loggedInUserDetailsArr['id']);
               // echo '<script type="text/javascript">window.parent.location.href="my-addressbook.php?success";parent.jQuery.fancybox.close();</script>';
                header("location: add-address.php?$addAtt$billing$shipping&q=".$id."&success");
                exit;
            /*}else{
                header("location:add-address.php?NDELIVERY");
                exit;
            }*/
        }else{
            header("location:add-address.php?INVALIDPINCODE");
            exit;
        }
    }
    $cityRS = $functions->getListOfCities();
    $stateRS = $functions->getListOfStates();
    $addrssType ='';
    if(isset($_GET['Billing'])){
        $addrssType = 'Billing';
    }
    if(isset($_GET['shipping'])){
        $addrssType = 'shipping';
    }
?>
<!DOCTYPE>
<html>
   <head>
      <title>Arvind Sanitary</title>
      <?php include("include/header-link.php");?>
	  <style>
		em{
			color:red;
		}
	  </style>
   </head>
   <body class="add-address-page">
     
    <div class="change-address-div signup">
        <h3 class="title16">Add/Update Address</h3>
        <?php
            if(isset($_GET['success'])){ ?>
                <br>
                <div class="alert alert-success">
                    <p><i class="fa fa-check"></i> Address saved successfully</p>
                </div>
        <?php   
            } ?>
        <form class="form" id="addAddress" method="post" novalidate="novalidate">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Enter your Name<em>*</em></label>
                        <input type="text" class="form-control" placeholder="Enter your Name" name="customer_fname" value="<?php if(isset($addressDetails['customer_fname'])){ echo $addressDetails['customer_fname']; } ?>">
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Enter your Contact No.<em>*</em></label>
                        <input type="text" class="form-control" placeholder="Enter your Contact No." name="customer_contact" value="<?php if(isset($addressDetails['customer_contact'])){ echo $addressDetails['customer_contact']; } ?>">
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Email<em>*</em></label>
                        <input type="text" class="form-control" placeholder="Enter your Email Id." name="customer_email" value="<?php if(isset($addressDetails['customer_email'])){ echo $addressDetails['customer_email']; } ?>">
                    </div>
                </div>

                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>State<em>*</em></label>
                        <select name="state" class="form-control" required="required" onchange="getShippingCity(this.value)">
                            <option value="">Please Select State</option>
                            <?php
                                $stateRS = $functions->getListOfStates();
                                while($stateRow = $functions->fetch($stateRS)) {
                            ?>
                                    <option value="<?php echo $stateRow['name'] ?>" <?php if(isset($addressDetails) and ($stateRow['name']==$addressDetails['state'] || ucwords($stateRow['name'])==ucwords($addressDetails['state']))) { echo "selected"; } ?>><?php echo ucwords($stateRow['name']) ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>City<em>*</em></label>
                        <select required="" class="form-control" name="city">
                            <option value="">Please Select City</option>
                            <?php
                                if(isset($addressDetails)) {
                                    $state = $functions->escape_string($functions->strip_all($addressDetails['state']));
                                    $sql="select DISTINCT(districtname) from ".PREFIX."pincode where statename='".$state."' order by districtname";
                                   // echo $sql; 
                                    $cityResult = $functions->query($sql);
                                    $cityStr='<option value="">Please select city</option>';
                                    while($cityRow=$functions->fetch($cityResult)){
                            ?>
                                        <option value="<?php echo $cityRow['districtname'] ?>" <?php if($cityRow['districtname']==$addressDetails['city'] || ucwords($cityRow['districtname']) == ucwords($addressDetails['city'])) { echo "selected"; } ?>><?php echo $cityRow['districtname'] ?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Address Line 1<em>*</em></label>
                        <input type="text" class="form-control" required="" name="address1" value="<?php if(isset($addressDetails['address1'])){ echo $addressDetails['address1']; } ?>">
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Address Line 2</label>
                        <input type="text" class="form-control" name="address2" value="<?php if(isset($addressDetails['address2'])){ echo $addressDetails['address2']; } ?>">
                        <span class="bar"></span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Enter Pincode<em>*</em></label>
                        <input name="pincode" class="form-control" minlength="6" maxlength="6" required="required" maxlength="6" type="text" value="<?php if(isset($addressDetails['pincode'])){ echo $addressDetails['pincode']; } ?>">
                        <span class="bar"></span>
                    </div>
                </div>
            </div>
            <div class="pull-left">
                <?php if(isset($_GET['Billing']) && !empty($_GET['Billing'])){ ?>   
                    <input type="hidden" name="Billing" value="<?php echo $_GET['Billing']; ?>">
                <?php }if(isset($_GET['shipping']) && !empty($_GET['shipping'])){ ?>
                    <input type="hidden" name="shipping" value="<?php echo $_GET['shipping']; ?>">
                <?php } ?>  

                <?php if(isset($_GET['Billing']) || isset($_GET['shipping'])){ ?>
                    <div class="pull-left back-btn">
                        <a href="<?php echo BASE_URL."/checkout-summery-popup.php?".$addrssType."=".$addrssType; ?>" class="shop-now-btn black-btn text-center fancybox-button ">Back</a>
                    </div>
                <?php } ?>
            </div>
            <div class="pull-right">
                <?php 
                    if(isset($addressDetails)){  ?> 
                        <input type="hidden" name="id" value="<?php if(isset($addressDetails['id'])){ echo $addressDetails['id']; } ?>">
                        <input type="submit" class="savechanges shop-now-btn black-btn" name="updateAddress" value="Update Address">
                <?php 
                    }else{ ?>    
                        <input type="submit" class="savechanges shop-now-btn black-btn" name="addAddress" value="Add Address">
                <?php 
                    } ?>        
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
    <!--Main End Code Here-->
    <!--footer end menu head-->
    <?php include("include/footer-link.php");?>
    <script>
        $("#pop-here").fancybox({
            iframe : {
                css : {
                    width : '600px'
                }
            }
        });
    </script>
    <script>
        $(document).ready(function(){
            $("#addAddress").validate({
                ignore: ".ignore",
                rules: {
                    customer_fname: {
                        required : true,
                    }, 
                    state: {
                        required:true,
                    }, 
                    city: {
                        required:true,
                    },
                    address1: {
                        required:true
                    },
                    customer_email: {
                        required: true,
                        email:true,
                    },
                    customer_contact: {
                        required: true,
                        number:true,
                        minlength: 10,
                        maxlength: 10,
                    },
                    pincode: {
                        required: true,
                        number:true,
                        minlength: 6,
                        maxlength: 6,
                        /*remote:{
                            url:"<?php echo BASE_URL; ?>/ajaxPincodeValidForDelivery.php",
                            type: "post",
                        }*/
                    },
                    
                },
                messages: {
                    customer_fname: {
                        required: "Please enter name",
                    },
                    state: {
                        required: "Please Select state",
                    },
                    city: {
                        required: "Please Select city",
                    }, 
                    address1: {
                        required: "Please add Address",
                    },
                    customer_contact: {
                        required: "please enter contact number",
                        remote:'Sorry, this contact is already registered.'
                    },
                    pincode: {
                        required: "please enter pincode number",
                        minlength: "please enter valid pincode number",
                        maxlength: "please enter valid pincode number",
                        /*remote:'Sorry, currently we do not deliver on this pincode.'*/
                    },
                    customer_email: {
                        required: 'please enter email address',
                    },
                }
            });
        });
        function getShippingCity(state) {
            $.ajax({
                url:"<?php echo BASE_URL."/ajaxGetCityByState.php" ?>",
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
   </body>
</html>