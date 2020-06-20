<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$pageName = "Contact Us Master";
	$pageURL = 'contact-us-master.php';
	
	//$addURL = 'our_team_master_add.php';
	$deleteURL = 'contact-us-master.php';
	$tableName = 'contact_us';
	//$parentPage = "event-master.php"; 

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}

	// $loggedInUserDetailsArr = $admin->getLoggedInUserDetails();
	//$admin->checkUserPermissions('slider_view',$loggedInUserDetailsArr);
	if(isset($_GET['page']) && !empty($_GET['page'])) {
		$pageNo = trim($admin->strip_all($_GET['page']));
	} else {
		$pageNo = 1;
	}
	$linkParam = "";
	
	if(isset($_GET['delid']) && !empty($_GET['delid'])){
		$delid = trim($admin->strip_all($_GET['delid']));
		

		$getsliderDetails = "select * from ".PREFIX.$tableName." WHERE id='".$delid."'";
		$result = $admin->query($getsliderDetails);
		if($admin->num_rows($result)>0){
			$sql="DELETE FROM ".PREFIX.$tableName ." WHERE id='".$delid."'";
			$admin->query($sql);
		}
		
		header('Location:'.$pageURL.'?deletesuccess');
		exit;
	}
	

	$query = "SELECT COUNT(*) as num FROM ".PREFIX.$tableName;
	$total_pages = $admin->fetch($admin->query($query));
	$total_pages = $total_pages['num'];


	include_once "include/pagination.php";
	$pagination = new Pagination();
	$paginationArr = $pagination->generatePagination($pageURL, $pageNo, $total_pages, $linkParam);

	// $sql = "SELECT * FROM ".PREFIX.$tableName." order by created DESC LIMIT ".$paginationArr['start'].", ".$paginationArr['limit']."";
	$sql = "SELECT * FROM ".PREFIX.$tableName." order by created DESC";
	$results = $admin->query($sql);


	$contactCms = "SELECT * FROM ".PREFIX."contact_us_cms order by id DESC";
	$contactUsCms = $admin->query($contactCms); 
	$contactUsCMMdetails = $admin->fetch($contactUsCms);
	if(isset($_POST['update'])){
		$email = trim($admin->escape_string($admin->strip_all($_POST['email'])));
		$contact = trim($admin->escape_string($admin->strip_all($_POST['contact'])));
		$address = trim($admin->escape_string($admin->strip_all($_POST['address'])));
		$link = trim($admin->escape_string($admin->strip_all($_POST['link'])));

		$facebook_url = trim($admin->escape_string($admin->strip_all($_POST['facebook_url'])));
		$youtube_url = trim($admin->escape_string($admin->strip_all($_POST['youtube_url'])));
		$twitter_url = trim($admin->escape_string($admin->strip_all($_POST['twitter_url'])));
		$pinteres_url = trim($admin->escape_string($admin->strip_all($_POST['pinteres_url'])));
		$linkedin_url = trim($admin->escape_string($admin->strip_all($_POST['linkedin_url'])));
		$instagram_url = trim($admin->escape_string($admin->strip_all($_POST['instagram_url'])));

		if(isset($_POST['id']) && !empty($_POST['id'])){
			// UPDATE
			$id = trim($admin->escape_string($admin->strip_all($_POST['id'])));
			

			$sql = "UPDATE ".PREFIX."contact_us_cms SET `email`='".$email."', `contact`='".$contact."', `address`='".$address."', `link`='".$link."', `facebook_url`='".$facebook_url."', `youtube_url`='".$youtube_url."', `twitter_url`='".$twitter_url."', `pinteres_url`='".$pinteres_url."', `linkedin_url`='".$linkedin_url."', `instagram_url`='".$instagram_url."' WHERE id='".$id."'";
		}else{
			// INSERT
			$sql = "INSERT INTO ".PREFIX."contact_us_cms(`email`, `contact`, `address`, `link`, `facebook_url`, `youtube_url`, `twitter_url`, `pinteres_url`, `linkedin_url`, `instagram_url`) VALUES ('".$email."', '".$contact."', '".$address."', '".$link."', '".$facebook_url."', '".$youtube_url."', '".$twitter_url."', '".$pinteres_url."', '".$linkedin_url."', '".$instagram_url."')";
		}
		//echo $sql; exit;
		$admin->query($sql);
		header("location:contact-us-master.php?cmsUpdateSuccess");
		exit;
	}

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
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	
	<!--<link href="css/nanoscroller.css" rel="stylesheet">
	<link href="css/cover.css" rel="stylesheet">-->

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
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
	<script type="text/javascript">
		$(document).ready(function() {
			
			$("#form").validate({
				rules: {
					/*image_name: {
						extension: 'jpg|jpeg|png'
					},*/
					email: {
						required:true,
						email:true,
					},
					contact:{
						required:true,
					},
					address:{
						required:true
					},
					link:{
						required:true,
						url:true
					},
					facebook_url:{
						url:true
					},
					youtube_url:{
						url:true
					},
					twitter_url:{
						url:true
					},
					pinteres_url:{
						url:true
					},
					linkedin_url:{
						url:true
					},
					instagram_url:{
						url:true
					}
				},
				messages: {
					/*image_name: {
						extension: 'Upload image with jpg, jpeg or png extension'
					}*/
				}
			});
			$.validator.addMethod("Evalid", function (value, element) {
         		if (this.optional(element)) {
         			return true;
         		}
				var reg = /^[a-zA-Z0-9-]+$/;
         		return reg.test(value);
			}, "Letters, numbers, and hyphen only please");	
		});
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
				<div class="page-ttle hidden-xs" style="float:left;"><?php echo $pageName; ?></div>
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a></li>
					<li class="active"><?php echo $pageName; ?></li>
				</ul>
			</div>
			<?php /*if(in_array('slider_add',$userPermissionsArray) or $loggedInUserDetailsArr['role']=='super') { ?>
				<a href="<?php echo $addURL; ?>" class="label label-primary">Add <?php echo $pageName; ?></a><br/><br/>
			<?php }*/ ?>
			<?php
				if(isset($_GET['cmsUpdateSuccess'])){ ?>
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<i class="icon-checkmark"></i> Contact Us Cms Master successfully updated.
					</div><br/>
			<?php	
				} ?>
			<form role="form" action="" method="post" id="form" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6 class="panel-title"><i class="icon-library"></i>Contact Us CMS Details</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Email<em style="color:red;">*</em></label>
									<input type="text" class="form-control"  name="email" id="email" value="<?php if(isset($contactUsCMMdetails['email'])){ echo $contactUsCMMdetails['email']; }?>"/><br>
									<label>Contact<em style="color:red;">*</em></label>
									<input type="text" class="form-control"  name="contact" id="contact" value="<?php if(isset($contactUsCMMdetails['contact'])){ echo $contactUsCMMdetails['contact']; }?>"/>
								</div>
								<div class="col-sm-6">
									<label>Address<em style="color:red;">*</em></label>
									<textarea name="address" rows="4" class="form-control"><?php if(isset($contactUsCMMdetails['address'])){ echo $contactUsCMMdetails['address']; }?></textarea><small>(for new line use enter)</small>
									<span class="help-block"></span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<label>Map URL<em style="color:red;">*</em></label>
									<input type="text" class="form-control"  name="link" id="1" value="<?php if(isset($contactUsCMMdetails['link'])){ echo $contactUsCMMdetails['link']; }?>"/>
									<span class="help-block"></span>
								</div>
								<?php /*
								<div class="col-sm-6">
									<label>Facebook URL</label>
									<input type="text" class="form-control"  name="facebook_url" id="facebook_url" value="<?php if(isset($contactUsCMMdetails['facebook_url'])){ echo $contactUsCMMdetails['facebook_url']; }?>"/>
									<span class="help-block"></span>
								</div>
								<div class="col-sm-6">
									<label>YouTube URL</label>
									<input type="text" class="form-control"  name="youtube_url" id="youtube_url" value="<?php if(isset($contactUsCMMdetails['youtube_url'])){ echo $contactUsCMMdetails['youtube_url']; }?>"/>
									<span class="help-block"></span>
								</div>
								<div class="col-sm-6">
									<label>Twitter URL</label>
									<input type="text" class="form-control"  name="twitter_url" id="twitter_url" value="<?php if(isset($contactUsCMMdetails['twitter_url'])){ echo $contactUsCMMdetails['twitter_url']; }?>"/>
									<span class="help-block"></span>
								</div>
								<div class="col-sm-6">
									<label>Pinterest URL</label>
									<input type="text" class="form-control"  name="pinteres_url" id="pinteres_url" value="<?php if(isset($contactUsCMMdetails['pinteres_url'])){ echo $contactUsCMMdetails['pinteres_url']; }?>"/>
									<span class="help-block"></span>
								</div>
								<div class="col-sm-6">
									<label>Linkedin URL</label>
									<input type="text" class="form-control"  name="linkedin_url" id="linkedin_url" value="<?php if(isset($contactUsCMMdetails['linkedin_url'])){ echo $contactUsCMMdetails['linkedin_url']; }?>"/>
									<span class="help-block"></span>
								</div>
								<div class="col-sm-6">
									<label>Instagram Link</label>
									<input type="text" class="form-control"  name="instagram_url" id="instagram_url" value="<?php if(isset($contactUsCMMdetails['instagram_url'])){ echo $contactUsCMMdetails['instagram_url']; }?>"/>
									<span class="help-block"></span>
								</div> */ ?>
							</div>
						</div>
						<input type="hidden" class="form-control" name="id" id="" required="required" value="<?php echo $contactUsCMMdetails['id'] ?>"/>
						<button type="submit" name="update" class="btn btn-warning"><i class="icon-pencil"></i>Update Contact us CMS Master </button>
					</div>
				</div>
			</form>
	
	<?php
		if(isset($_GET['deletesuccess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark"></i> <?php echo $pageName; ?> successfully deleted.
			</div><br/>
	<?php	} ?>
	<?php
		if(isset($_GET['videsuccess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark"></i> Video successfully updated.
			</div><br/>
	<?php	} ?>
	<?php
		if(isset($_GET['updated'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark"></i> <?php echo $pageName; ?> successfully updated.
			</div><br/>
	<?php	} ?>
	
	<?php
		if(isset($_GET['deletefail'])){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i> <strong><?php echo $pageName; ?> not deleted.</strong> Invalid Details.
			</div><br/>
	<?php	} ?>
			<a href="javascript:;" id="export" class="label label-primary pull-right">Export <?php echo $pageName; ?></a><br/><br/>
			<br/>
			<div class="panel panel-default" style="overflow: scroll;">
				<div class="datatable-selectable-data table">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Email</th>
								<th>Contact</th>
								<th>Feedback</th>
								<th>Contact Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
<?php
						$x = (10*$pageNo)-9;
						while($row = $admin->fetch($results)){ 
								//productDetails  = $admin->getUniqueProductById($row['product_id']);

?>
							<tr>
								<td><?php echo $x++; ?></td>
								<td><?php echo $row['name']; ?></td>
								<td><?php echo $row['email']; ?></td>
								<td><?php echo $row['mobile']; ?></td>
								<td><?php echo $row['feedback']; ?></td>
								<td><?php echo date('d-m-Y',strtotime($row['created']));; ?></td>
								<td>
									<!-- <a href="<?php echo $addURL; ?>?edit&id=<?php echo $row['id'] ?>" name="edit" class="btn btn-warning btn-xs" title="Click to edit this row"><i class="icon-pencil"></i></a> -->
									<a class="btn btn-danger btn-xs" href="<?php echo $deleteURL; ?>?delid=<?php echo $row['id']; ?> "onclick="return confirm('Are you sure you want to delete?');" title="Click to delete this row, this action cannot be undone."><i class="icon-remove3"></i></a>
								</td>
							</tr>
<?php
						}
?>
						</tbody>
				  </table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 clearfix">
					<nav class="pull-right">
						<?php //echo $paginationArr['paginationHTML']; ?>
					</nav>
				</div>
			</div>

<?php 	include "include/footer.php"; ?>

		</div>

	</div>
	<link href="css/jquery.dataTables.min.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script>
		$(document).ready(function() {
			$('.datatable-selectable-data table').dataTable({
				"order": [[ 0, 'asc' ]],
			});
			$("#export").on("click",function(){
				window.open("contact-export.php?success");
			});
		});
	</script>
</body>
</html>