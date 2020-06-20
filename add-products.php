<?php 
  Include_once("include/functions.php");
    $functions = New Functions();
    $pageURL ="add-products.php";
    include_once("include/classes/Email.class.php");

    if(!$loggedInUserDetailsArr = $functions->sessionExists()){
        header("location: ".BASE_URL."/login.php");
        exit;
    }
    
    if($loggedInUserDetailsArr['user_type'] == "Customer"){
        header("location: ".BASE_URL."/login.php?failed&VenLogin");
        exit;   
    }
    $h4heading ='';
    $name ='';
    if(isset($_POST['product_name']) && empty($_POST['id'])){
        //add to database
        $result = $functions->addProduct($_POST,$_FILES);
        $adminDetails =  $functions->getAdminDetails();

        $h4heading   = "New Product ".$_POST['product_name']."/".$_POST['product_code']." Added by vendor ".$loggedInUserDetailsArr['first_name'];
        
        $content    .= "<p>Dear Admin,</p>";
        $content    .= "<p>Following Product Added By Vendor ".$loggedInUserDetailsArr['first_name']."</p>";
        
        $content    .= "<p>Product Name : ".$_POST['product_name']."</p>";
        $content    .= "<p>Product Code : ".$_POST['product_code']."</p>";
        
        include("thank-you.php");

        $emailObj = new Email();
        $emailObj->setSubject(SITE_NAME." | New Product ".$_POST['product_name']."/".$_POST['product_code']." Added by vendor ".$loggedInUserDetailsArr['first_name']);
        $emailObj->setAddress($adminDetails['email']);
        $emailObj->setEmailBody($emailMsg);
        $emailObj->sendEmail();

        header("location:".$pageURL."?registersuccess");
        exit;
    }
    if(isset($_GET['edit'])){
        $id = $functions->escape_string($functions->strip_all($_GET['id']));
        $data = $functions->getUniqueProductById($id);
    }
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        //update to database
        $result = $functions->updateProduct($_POST,$_FILES);
        $adminDetails =  $functions->getAdminDetails();
        $h4heading   = "Product ".$_POST['product_name']."/".$_POST['product_code']." Updated by vendor ".$loggedInUserDetailsArr['first_name'];
        
        $content    .= "<p>Dear Admin,</p>";
        $content    .= "<p>Following Product Updated By Vendor ".$loggedInUserDetailsArr['first_name']."</p>";
        
        $content    .= "<p>Product Name : ".$_POST['product_name']."</p>";
        $content    .= "<p>Product Code : ".$_POST['product_code']."</p>";
        
        include("thank-you.php");

        $emailObj = new Email();
        $emailObj->setSubject(SITE_NAME." | New Product ".$_POST['product_name']."/".$_POST['product_code']." Updated by vendor ".$loggedInUserDetailsArr['first_name']);
        $emailObj->setAddress($adminDetails['email']);
        $emailObj->setEmailBody($emailMsg);
        $emailObj->sendEmail();
        header("location:".$pageURL."?updatesuccess&edit&id=".$id);
        exit();
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
				<li>Add Product</li>
			</ul>
		</div>
    </div>
    <section class="myproduct">
        <div class="inner-content bt">
            <div class="container">
                <div class="ac-detail-nav-box">
                    <ul class="ac-detail-nav">
                        <li> <a href="vendor-myaccount.php"><i class="fa fa-user-o" aria-hidden="true"></i>  My Account</a></li>
                        <li><a href="vendor-orderreceived.php"><i class="fa fa-bars" aria-hidden="true"></i> Order Recieved</a></li>
                        <li class="active"><a href="vendor-myproducts.php"><i class="fa fa-heart-o" aria-hidden="true"></i> My Products</a></li>
                        <div class="clearfix"></div>
    				</ul>
                </div>
        <section class="productadd">
            <div class="inner-content bt">
                 <div class="container">
                    <div class="row">
                    
                    <h1 class="page-heading">Add Products</h1>
                        <?php 
                            if(isset($_GET['registersuccess'])){ ?>
                                <div class="alert alert-success">
                                    ThankYou! Your product has been successfully uploaded. We will Quickly Review and notify you through SMS/Email when your product is Live
                                </div>
                    <?php   }if(isset($_GET['updatesuccess'])){ ?>
                                <div class="alert alert-success">
                                    ThankYou! Your product has been successfully uploaded. We will Quickly Review and notify you through Email when your product is Live
                                </div>
                    <?php   } ?>

                         <div class="col-lg-6 col-lg-pull-3 col-lg-push-3 col-md-10 col-md-pull-1 col-md-push-1">
                             <a href="<?php echo BASE_URL."/vendor-myproducts.php"; ?>" class="label label-primary">Back to product list</a><br/><br/>
                             <div class="login-box">
                                <form class="login-form" action="" method="POST" id="vendorAddProrductFrm" enctype="multipart/form-data">
                                    
                                    <div class="col-md-12 col-sm-12">
                                        <input type="name" class="form-control" name="product_name" id="product_name" placeholder="Name*" value="<?php if(isset($_GET['edit'])){ echo $data['product_name']; } ?>" />
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <input type="code" class="form-control" name="product_code" id="product_code" placeholder="Product Code*" value="<?php if(isset($_GET['edit'])){ echo $data['product_code']; } ?>" />
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <input type="HSN*" class="form-control" name="hsn_code" id="hsn_code" placeholder="HSN Code*" value="<?php if(isset($_GET['edit'])){ echo $data['hsn_code']; } ?>" />
                                    </div>


                                    <div class="col-md-6 match multipleselect">
                                       <div class="form-group">
                                          <select class="selectpicker selectpicker1" name="category[]" id="category" multiple data-live-search="false">
                                            <option value=""  disabled>Please Select Category</option>
                                             <?php 
                                                $query = $functions->query("select * from ".PREFIX."category_master where active='Yes' order by category_name ASC");
                                                while($row = $functions->fetch($query)){
                                                    if(isset($_GET['edit'])){
                                                        $updatequry = $functions->query("SELECT * FROM ".PREFIX."product_category_mapping WHERE product_id='".$id."' and category_id='".$row['id']."'");
                                                        $catDetails = $functions->fetch($updatequry);
                                                        //print_r($catDetails);
                                                    }
                                            ?>
                                                    <option value="<?php echo $row['id']; ?>" <?php if(isset($_GET['edit']) && $row['id']==$catDetails['category_id']){ echo "selected"; } ?> ><?php echo $row['category_name']; ?></option>
                                            <?php 
                                                } ?>  
                                          </select>
                                       </div>
                                    </div>

                                    <div class="col-md-6 match multipleselect">
                                        <select class="selectpicker selectpicker2" name="sub_cat[]" id="sub_category_id" multiple data-live-search="false">
                                            <option value=""  disabled>Please Select Sub Category</option>
                                            <?php 
                                            if(isset($_GET['edit'])){
                                                $query = $functions->query("SELECT * FROM ".PREFIX."sub_category_master WHERE `active` ='1' order by `sub_category_name` ASC");
                                                while($row = $functions->fetch($query)){ 
                                                    $subQuery = $functions->query("SELECT * from ".PREFIX."product_subcategory_mapping WHERE `product_id`='".$id."' and subscategory_id='".$row['id']."'");
                                                    $subscbCat = $functions->fetch($subQuery);
                                            ?>
                                                    <option value="<?php echo $row['id']; ?>" <?php if(isset($_GET['edit']) && $row['id']==$subscbCat['subscategory_id']){ echo "selected"; } ?> ><?php echo $row['sub_category_name']; ?></option>
                                            <?php  
                                                }  
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 match multipleselect">
                                        <select class="selectpicker selectpicker3" name="Subsub_category[]" id="Subsub_category" multiple data-live-search="false">
                                            <option value=""  disabled>Please Select Sub-Sub Category</option>
                                            <?php
                                            if(isset($_GET['edit'])) {
                                                $subsub = $functions->query("SELECT * FROM ".PREFIX."subsubCategory where active='Yes' order by subcategory_name ASC");

                                                while($subCategoryDetail = $functions->fetch($subsub)){
                                                    $subCategorySQL = $functions->getAllSubSubCategoriesbyProductID($id,$subCategoryDetail['id']);
                                                    $subsubCdetail = $functions->fetch($subCategorySQL);
                                                    
                                            ?>
                                                <option value="<?php echo $subCategoryDetail['id']; ?>" <?php if(isset($_GET['edit']) and $subsubCdetail['subsubcategory_id']==$subCategoryDetail['id']) { echo 'selected'; } ?>><?php echo $subCategoryDetail['subcategory_name']; ?></option>
                                            <?php
                                            
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                
                                    <div class="col-md-6 col-sm-12"><br>
                                        <select name="brand" id="brand" class="select">
                                            <option value="" data-display="Brand">Brand</option>
                                            <?php 
                                                $query = $functions->query("select * from ".PREFIX."brand_master");
                                                while($row = $functions->fetch($query)){ ?>
                                                    <option value="<?php echo $row['id']; ?>" <?php if(isset($_GET['edit']) && $row['id']== $data['brand_id']){ echo "selected"; } ?> ><?php echo $row['brand_name']; ?></option>
                                            <?php  
                                                }  ?>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                 
                                   <div class="col-md-12 col-sm-12">
                                   <h2 class="titleimg">Main Image</h2>
                                        <label class="cp-btn">
                                        <input type="file" name="main_image" id="main_image" accept="image/jpg,image/png,image/jpeg" id="" data-image-index="0" <?php if(isset($data['main_image']) && !empty($data['main_image'])){ }else{ echo "required"; } ?>>File Upload*</label>

                                        <p class="pinputfile">Max file size is 1MB, Minimum dimension: 1000 X 1000 And  <br>
                                            Suitable Files are .jpg & .png</p>
                                            <?php 
                                                if(isset($_GET['edit']) && !empty($data['main_image'])){
                                                $productBanner = $functions->getImageUrl('products',$data['main_image'],'crop','');
                                            ?>
                                                    <img src="<?php echo $productBanner; ?>" style="width: 100px;">
                                        <?php   } ?>
                                   </div>
                                   <div class="otheimg">
                                       <span class="htwo">Other Images</span>
                                       <p class="pother">Max file size is 1MB, Minimum dimension: 1000 X 1000 And  <br>
                                            Suitable Files are .jpg & .png</p>

                                   </div>
                                   <div class="imgfilechoose">
                                        <span class="htwo">Images 1</span>
                                        <div class="floatright">
                                            <label class="cp-btn martop">
                                            <input type="file" name="image_one" id="image_one" accept="image/jpg,image/png,image/jpeg" id="1" data-image-index="1">File Upload</label>
                                            <?php 
                                                if(isset($_GET['edit']) && !empty($data['image_one'])){
                                                $productBanner = $functions->getImageUrl('products',$data['image_one'],'crop','');
                                            ?>
                                                    <img src="<?php echo $productBanner; ?>" style="width: 100px;">
                                        <?php   } ?>
                                        </div>
                                   </div>
                                   <div class="imgfilechoose">
                                        <span class="htwo">Images 2</span>
                                        <div class="floatright">
                                            <label class="cp-btn martop">
                                            <input type="file" name="image_two" id="image_two" accept="image/jpg,image/png,image/jpeg" id="2" data-image-index="2">File Upload</label>
                                            <?php 
                                                if(isset($_GET['edit']) && !empty($data['image_two'])){
                                                $productBanner = $functions->getImageUrl('products',$data['image_two'],'crop','');
                                            ?>
                                                    <img src="<?php echo $productBanner; ?>" style="width: 100px;">
                                        <?php   } ?>
                                        </div>
                                   </div>
                                   <div class="imgfilechoose">
                                        <span class="htwo">Images 3</span>
                                        <div class="floatright">
                                            <label class="cp-btn martop">
                                            <input type="file" name="image_three" id="image_three" accept="image/jpg,image/png,image/jpeg" id="3" data-image-index="3">File Upload</label>
                                            <?php 
                                                if(isset($_GET['edit']) && !empty($data['image_three'])){
                                                $productBanner = $functions->getImageUrl('products',$data['image_three'],'crop','');
                                            ?>
                                                    <img src="<?php echo $productBanner; ?>" style="width: 100px;">
                                        <?php   } ?>
                                        </div>
                                   </div>
                                   <div class="imgfilechoose">
                                        <span class="htwo">Images 4</span>
                                        <div class="floatright">
                                            <label class="cp-btn martop">
                                            <input type="file" name="image_four" id="image_four" accept="image/jpg,image/png,image/jpeg" id="4" data-image-index="4">File Upload</label>
                                            <?php 
                                                if(isset($_GET['edit']) && !empty($data['image_four'])){
                                                $productBanner = $functions->getImageUrl('products',$data['image_four'],'crop','');
                                            ?>
                                                    <img src="<?php echo $productBanner; ?>" style="width: 100px;">
                                        <?php   } ?>
                                        </div>
                                   </div><br>

                                   <div class="col-md-6 col-sm-12">
                                        <input type="text" class="form-control" placeholder="Price*" name="price" id="price" required="" value="<?php if(isset($_GET['edit'])){ echo $data['price']; } ?>" />
                                   </div>
                                   <div class="col-md-6 col-sm-12">
                                        <input type="text" class="form-control" name="discount_price*" id="discount_price" value="<?php if(isset($_GET['edit'])){ echo $data['discount_price']; } ?>" placeholder="Discounted Price*" required="" />
                                   </div>
                                   <div class="col-md-6 col-sm-12">
                                        <input type="text" class="form-control" placeholder="Quantity*" name="availability" id="availability"  value="<?php if(isset($_GET['edit'])){ echo $data['availability']; } ?>" required="" />
                                   </div>
                                   <div class="col-md-6 col-sm-12 niceSelecterror" >
                                        <select name="tax" class='select'>
                                            <option data-display="Tax Applicable in percentage*">Tax Applicable in percentage</option>
                                            <option <?php if(isset($_GET['edit']) && $data['tax']=="0"){ echo "selected"; } ?> value="0">0 %</option>
                                            <option <?php if(isset($_GET['edit']) && $data['tax']=="5"){ echo "selected"; } ?> value="5">5 %</option>
                                            <option <?php if(isset($_GET['edit']) && $data['tax']=="12"){ echo "selected"; } ?> value="12">12 %</option>
                                            <option <?php if(isset($_GET['edit']) && $data['tax']=="18"){ echo "selected"; } ?> value="18">18 %</option>
                                            <option <?php if(isset($_GET['edit']) && $data['tax']=="28"){ echo "selected"; } ?> value="28">28 %</option>
                                        </select>
                                   </div>
                                   <div class="col-md-12 col-sm-12">
                                       <h3 class="droptit">Product Description</h3>
                                       <textarea name="description" id="description" cols="10" rows="2"><?php if(isset($_GET['edit'])){ echo $data['description']; }  ?></textarea>
                                   </div>
                                   <div class="clearfix"></div>
                                   <br>
                                   <br>
                                    <input type="hidden" name="vendor_id" value="<?php echo $loggedInUserDetailsArr["id"];?>"><br>
                                    <?php 
                                        if(isset($_GET['edit']) && isset($_GET['id']) && !empty($_GET['id'])){
                                    ?>
                                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                                            <input type="hidden" name="vendor_id" value="<?php echo $data['vendor_id']; ?>">
                                            <button class="btn submit-btnform" type="submit" name="updateProduct">Update</button> 
                                    <?php 
                                        }else{ ?>
                                            <button class="btn submit-btnform" type="submit" name="addProduct">SUBMIT</button>   
                                    <?php 
                                        } ?>            
                                </form>
                             
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </section>
	  
            </div>
        </div>
    </section>
	  
      
	  
         <!--Main End Code Here-->
      <!--footer start menu head-->
      <?php include("include/footer.php");?> 
      <!--footer end menu head-->
      <?php include("include/footer-link.php");?>
         <script>
               CKEDITOR.replace('description', {
                skin: 'moono',
                enterMode: CKEDITOR.ENTER_BR,
                shiftEnterMode:CKEDITOR.ENTER_P,
                toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
                            { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                            { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
                            { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                            { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                            { name: 'links', items: [ 'Link', 'Unlink' ] },
                            { name: 'insert', items: [ 'Image'] },
                            { name: 'spell', items: [ 'jQuerySpellChecker' ] },
                            { name: 'table', items: [ 'Table' ] }
                            ],
                });

         </script>
        <script>
            $(document).ready(function() {



                $('.select').niceSelect();


                $('.selectpicker1').selectpicker({noneSelectedText: 'Please Select Category'}); 
                $('.selectpicker2').selectpicker({noneSelectedText: 'Please Select Subcategory'}); 
                $('.selectpicker3').selectpicker({noneSelectedText: 'Please Select Sub-subcategory'}); 


                $("#vendorAddProrductFrm").validate({
                    ignore:[],
                    rules: {
                        product_name:{
                            required:true,
                        },
                        product_code: {
                            required: true,
                            remote: {
                                <?php if(isset($_GET['id']) && !empty($_GET['id'])){ ?>
                                data: {id:'<?php echo $_GET['id']; ?>' },
                                <?php } ?>
                                url: "ajaxCheckProductCode.php",
                                type: "post",
                            },
                        },
                        hsn_code: {
                            required: true,
                        },
                        category:{
                            required:true,
                        },
                        main_image: {
                            extension: 'jpg|jpeg|png'
                        },
                        price: {
                            required: true,
                            number: true,
                        },
                       
                        availability: {
                            required: true,
                            number: true,
                        },
                        tax: {
                            number: true,
                        },
              
                    },
                    messages:{
                        product_name: {
                            required: "Please enter product name",
                        },
                        product_code: {
                            required: "Please enter product code",
                            remote: "Product code alredy exists"
                        },
                        hsn_code: {
                            required: "Please enter HSN Code",
                        },
                        category: {
                            required: "Please select category",
                        },
                        main_image: {
                            required: "Please upload image",
                        },
                        price: {
                            required: "Please enter base price",
                        },
                        
                        availability: {
                            required: "Please enter total product  image",
                        },
                        tax: {
                           required: "Please select tax %",
                        },
                    }

                });
            });       
        </script>
        <link href="css/crop-image/cropper.min.css" rel="stylesheet">
        <script src="js/crop-image/cropper.min.js"></script>
        <script src="js/crop-image/image-crop-app.js"></script>
        <script>
            $(document).ready(function() {

                //subcategory();
                $('#sub_category_id').on("change",getSubSubCategory);
                $('input[type="file"]').change(function(){
                    loadImagePreview(this, (1000 / 1000));
                });
                $("#category").on("change", function(){
                    var category_id = $(this).val();

                    $.ajax({
                        url:"ajaxGetSubCategoryByCategoryId.php",
                        data:{category_id:category_id},
                        type:"POST",
                        success: function(response){
                            $('#sub_category_id').selectpicker('refresh'); 
                            $("#sub_category_id").html(response);
                            $('#sub_category_id').on("change",getSubSubCategory);
                            $('#sub_category_id').selectpicker('refresh'); 
                            //$('.selectpicker').selectpicker(); 
                           // $('select').niceSelect('update');
                        },
                        error: function(){
                            alert("Unable to get content, please try again");
                        },
                        complete: function(response){
                            
                        }
                    });

                    /*$.ajax({
                        url:"ajaxGetCategoryAttributes.php",
                        data:{category_id:category_id},
                        type:"POST",
                        success: function(response){
                            var response = JSON.parse(response);
                            $("#product-filter-div").html(response.responseContent);
                        },
                        error: function(){
                            alert("Unable to add to cart, please try again");
                        },
                        complete: function(response){
                            
                        }
                    });*/
                });
            });
            function getSubSubCategory() {
                var Subsubcategory = $("#sub_category_id").val();
                //alert(Subsubcategory);
                $.ajax({
                    url:"ajaxGetSubSubCategory.php",
                    data:{Subsubcategory:Subsubcategory},
                    type:"GET",
                    success: function(response){
                        var response = JSON.parse(response);
                        $("#Subsub_category").html(response.selectContent);
                        $('select').niceSelect('update');
                        $('#Subsub_category').selectpicker('refresh'); 
                    },
                    error: function(){
                        alert("Unable to add to cart, please try again");
                    },
                    complete: function(response){
                        
                    }
                });
            }
        </script>
   </body>
</html>