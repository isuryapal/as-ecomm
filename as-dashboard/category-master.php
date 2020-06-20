<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$pageName = "Categories";
	$pageURL = 'category-master.php';
	$addURL = 'category-add.php';
	$deleteURL = 'category-delete.php';
	$tableName = 'category_master';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	// $loggedInUserDetailsArr = $admin->getLoggedInUserDetails();

	
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

	// $sql = "SELECT * FROM ".PREFIX.$tableName." order by created DESC LIMIT ".$paginationArr['start'].", ".$paginationArr['limit']."";
	$sql = "SELECT * FROM ".PREFIX.$tableName." order by created DESC";
	$results = $admin->query($sql);
	
	if(isset($_GET['status']) && isset($_GET['status']) && !empty($_GET['active_id']) && !empty($_GET['active_id'])){
		$status = trim($admin->strip_all($_GET['status']));
		$active_id = trim($admin->strip_all($_GET['active_id']));
		
		if($status=="Yes"){
			$updatestatus = 'No';
		}elseif($status=="No"){
			$updatestatus = 'Yes';
		}
		
		$sql="UPDATE ".PREFIX."category_master SET `active`='".$updatestatus."'  WHERE id='".$active_id."'";
		$admin->query($sql);
		header('Location:category-master.php?updated');
	}
	// banner add and update
	// $bannerDetails = $admin->GetBannerDataByID("HAMPER");
	// if(isset($_POST['updateBanner'])){
	// 	$admin->updateBannerBYType($_POST,$_FILES);
	// 	header('Location:'.$pageURL.'?Bsuccess');
	// 	exit;
	// }
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
	<link rel="shortcut icon" href="<?php echo BASE_URL ?>/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo BASE_URL ?>/favicon.ico" type="image/x-icon">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<!--<link href="css/nanoscroller.css" rel="stylesheet">
	<link href="css/cover.css" rel="stylesheet">-->

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="css/fixedHeader.dataTables.min.css" rel="stylesheet">
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
	<script type="text/javascript">
		$(document).ready(function() {
			$("#formValid").validate({
				ignore: [],
				rules: {
					banner_image: {
					<?php if(isset($bannerDetails['banner_image'])){if(empty($bannerDetails['banner_image'])){	?>
						required:true,
						
					<?php }}else{ ?>
						required:true,
						
					<?php } ?>
					extension: 'jpg|jpeg|png'
					},
					
					mobile_image: {
					<?php if(isset($bannerDetails['mobile_image'])){if(empty($bannerDetails['mobile_image'])){	?>
						required:true,
						
					<?php }}else{ ?>
						required:true,
						
					<?php } ?>
					extension: 'jpg|jpeg|png'
					},
				}
			});
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
			
			<a href="<?php echo $addURL; ?>" class="label label-primary">Add <?php echo $pageName; ?></a><br/><br/>
			
	<?php
		if(isset($_GET['deletesuccess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark"></i> <?php echo $pageName; ?> successfully deleted.
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

			<!-- <form role="form" action="" method="post" id="formValid" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6 class="panel-title"><i class="icon-library"></i>Hamper Default Banner Image</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Image <em style="color:red;">*</em></label>
									<input type="file" class="form-control" <?php if(isset($bannerDetails['banner_image'])){if(empty($bannerDetails['banner_image'])){ echo "required"; } }else{ echo "required"; }?> name="banner_image" id="1" data-image-index="0" />
									<span class="help-text">
										Files must be less than <strong>3 MB</strong>.<br>
										Allowed file types: <strong>jpg|jpeg|png</strong>.<br>
										Images must be exactly <strong>1920 x 245</strong> pixels.
									</span>
									<?php if(isset($bannerDetails['banner_image'])) {
										$file_name = str_replace('', '-', strtolower( pathinfo($bannerDetails['banner_image'], PATHINFO_FILENAME)));
										$ext = pathinfo($bannerDetails['banner_image'], PATHINFO_EXTENSION);
									?>
										<img src="../images/category/<?php echo $file_name.'_crop.'.$ext ?>" width="200" />
									<?php
									} ?>
								</div>
								<div class="col-sm-6">
									<label>Mobile Image <em style="color:red;">*</em></label>
									<input type="file" class="form-control" <?php if(isset($bannerDetails['mobile_image'])){if(empty($bannerDetails['mobile_image'])){ echo "required"; } }else{ echo "required"; }?> name="mobile_image" id="2" data-image-index="1" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>jpg|jpeg|png</strong>.<br>
										Images must be exactly <strong>400 x 100</strong> pixels.
									</span>
									<?php if(isset($bannerDetails['mobile_image'])) {
										$file_name = str_replace('', '-', strtolower( pathinfo($bannerDetails['mobile_image'], PATHINFO_FILENAME)));
										$ext = pathinfo($bannerDetails['mobile_image'], PATHINFO_EXTENSION);
									?>
										<img src="../images/category/<?php echo $file_name.'_crop.'.$ext ?>" width="100" />
									<?php
									} ?>
								</div>
								<input type="hidden" name="banner_type" value="HAMPER">
								<div class="col-sm-10"><br>
									<button type="submit" name="updateBanner" class="btn btn-warning"><i class="icon-pencil"></i>Update Banner</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form> -->

			<br/>
			<div class="panel panel-default">

				<div class="datatable-selectable-data">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Active</th>
								<th>Sub Categories</th>
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
								<td><?php echo $row['category_name']; ?></td>
								<td><?php echo $row['active']; ?></a></td>
								<td><a href="sub-category-master.php?cat_id=<?php echo $row['id'] ?>" name="edit" class="">View Sub Categories</a></td>
								<td>
								
									<a href="<?php echo $addURL; ?>?edit&id=<?php echo $row['id'] ?>" name="edit" class="" title="Click to edit this row"><i class="icon-pencil"></i></a>
									<a class="" href="<?php echo $deleteURL; ?>?id=<?php echo $row['id']; ?>&editedby=<?php echo $loggedInUserDetailsArr['id']; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Click to delete this row, this action cannot be undone."><i class="icon-remove3"></i></a>
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
	<script type="text/javascript" src="js/plugins/interface/dataTables.fixedHeader.min.js"></script>
	<script>
		$(document).ready(function() {
			$('.datatable-selectable-data table').dataTable({
				"order": [[ 0, 'asc' ]],
				fixedHeader: {
						header: true,
						footer: true
					}
			});
			$('input[name="banner_image"]').change(function(){
				// loadImageInModal(this);
				loadImagePreview(this, (1920 / 245));
			});
			$('input[name="mobile_image"]').change(function(){
				// loadImageInModal(this);
				loadImagePreview(this, (400 / 100));
			});
		});
	</script>
</body>
</html>