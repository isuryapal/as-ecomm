<?php 
	include_once 'include/functions.php';
   	$functions = new Functions();
   	//echo BASE_URL;
   	if(!$loggedInUserDetailsArr = $functions->sessionExists()){
	    //print_r($loggedInUserDetailsArr); exit;
	    header("location: index.php");
	    exit;
	}
	//if(!$loggedInUserDetailsArr = $functions->sessionExists()){
		//header("location: ".BASE_URL."/login.php");
      	//exit;
	//}
	//print_r($_GET);
	//if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
		$product_id = trim($functions->escape_string($functions->strip_all($_GET['product_id'])));
		if(isset($_POST['rating'])){
			//if($csrf->check_valid('post')) {
				//$allowed_ext = array('image/jpeg','image/jpg');
			$name = trim($functions->escape_string($functions->strip_all($_POST['name'])));
			if(empty($name) && empty($product_id)){
				header("location:write-a-review.php?reviewfail");
				exit;
			}else{
				//add to database
				$result = $functions->addReviews($_POST,$loggedInUserDetailsArr,$_FILES,$product_id);
				header("location:write-a-review.php?reviewsuccess&product_id=".$_POST['product_id']);
				//echo '<script>parent.jQuery.fancybox.close();</script>';
				// $user->userLogin($_POST, "detail.php?reviewsuccess", "login.php?reviewfail");				
				// exit;
			}
			//}
		}
	//}
?>
<!DOCTYPE>
<html>
   <head>
      <title>Heres Your Present</title>
      <?php include("include/header-link.php");?>
   </head>
   <body class="review-page" id="review-box">
 		<div class="review-box-inner">
 			<?php	
				if(isset($_GET['reviewsuccess'])){
					echo "<div class=\"alert alert-success text-center\">Thank you for valuable review.</div>";
				}else if(isset($_GET['reviewfail'])) {
					echo "<div class=\"alert alert-danger text-center\">Sorry your review not submitted.</div>";
				} 
			?>
			<form id="review_form" action="" method="POST">
				<div class="form-group mr">
					<input type="text" name="name"  class="form-control" value="<?php echo $loggedInUserDetailsArr['first_name']; ?>"/>
				</div>
				<div class="form-group">
					<input type="text" name="email"  class="form-control" value="<?php echo $loggedInUserDetailsArr['email']; ?>"/>
				</div>
				<div class="form-group full">
					<textarea type="text" id="review" name="review" class="form-control" placeholder="Add Reason be"></textarea>
				</div>
				<div class="form-group full star-rating-area  ">
					<fieldset class="rating pull-left">
						<input type="radio" class="rating" id="star5" name="rating" value="5"><label for="star5"></label>
						<input type="radio" class="rating" id="star4half" name="rating" value="4.5"><label class="half" for="star4half"></label>
						<input type="radio" class="rating" id="star4" name="rating" value="4"><label for="star4"></label>
						<input type="radio" class="rating" id="star3half" name="rating" value="3.5"><label class="half" for="star3half"></label>
						<input type="radio" class="rating" id="star3" name="rating" value="3"><label for="star3"></label>
						<input type="radio" class="rating" id="star2half" name="rating" value="2.5"><label class="half" for="star2half"></label>
						<input type="radio" class="rating" id="star2" name="rating" value="2"><label for="star2"></label>
						<input type="radio" class="rating" id="star1half" name="rating" value="1.5"><label class="half" for="star1half"></label>
						<input type="radio" class="rating" id="star1" name="rating" value="1"><label for="star1"></label>
						<input type="radio" class="rating" id="starhalf" name="rating" value="0.5"><label class="half" for="starhalf"></label>
					</fieldset>
					<div class="clearfix"></div>
					<p style="color:red;" class="ratings"></p>
				</div>
				<div class="form-group">
					<input type="hidden" name='product_id' value="<?php echo $_GET['product_id']; ?>">
					<input id="sub_review" onClick="ValidateForm()" type="button" value="Submit"  class="btn default-btn"/>
				</div>
			</form>
		</div>
   	  
         <!--Main End Code Here-->
 
      <!--footer end menu head-->
      <?php include("include/footer-link.php");?>
      <script>
			$(document).ready(function() {
				$("form#review_form").validate({
					rules: {
						name: {
							required:true,
							//lettersOnly:true
						},
						email:{
							required:true,
							email:true
						},
						review: {
							required:true
						},
						/* test_image: {
							required:true,
							extension: 'jpg|jpeg|png'
						}, */
						rating: {
							required:true
						}
					},
					messages: {
						name: {
							required:"Please enter your first name"
						},
						email:{
							required:"Please enter valid email"
						},
						review: {
							required: "Please enter reviews"
						},
						rating:{
							required:"Please enter rating"
						}
					},
					errorPlacement: function(error, element) {
						error.appendTo( $(element[0].parentElement).append(error) );
					}
				});
			});
			function ValidateForm(){	
				ErrorText= "";
				if($('input[name=rating]:checked').length<=0){
					$(".ratings").text('Kindly give star rating');
					return false;
				}else{
					$(".ratings").text('');
				}
				if($('input[name=name]').val()==''){
					$(".name").text('Please enter name');
					return false;
					
				}else{
					$(".name").text('');
				}
				if($('input[name=email]').val()==''){
					$(".email").text('Please enter email');
					return false;
				}else{
					$(".email").text('');
				}
				if($('input[name=review]').val()==''){
					$(".reviewss").text('Please write review');
					return false;
					
				}else{
					$(".reviewss").text('');
					
				}
				if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input[name=email]').val())){
					
				}else{
					$(".email").text('Please enter valid email');
					return false;
				}
				if($('input[name=rating]:checked').length >0 && $('input[name=name]').val()!='' && $('input[name=email]').val()!='' && $('input[name=review]').val()!=''){
					$("form#review_form").submit();
				}
				
			}
		</script>
   </body>
</html>