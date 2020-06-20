<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	
	$pageName = "Sub Category";
	$pageURL = 'sub-category-master.php';
	$addURL = 'sub-category-add.php';
	$deleteURL = 'sub-category-delete.php';
	$tableName = 'sub_category_master';


	if(isset($_GET['cat_id']) && !empty($_GET['cat_id'])){
		$cat_id = trim($admin->escape_string($admin->strip_all($_GET['cat_id'])));
		$caTdetails = $admin->getUniqueCategoryById($cat_id);
		if(!empty($caTdetails['id'])){
			$cat_id = trim($admin->escape_string($admin->strip_all($caTdetails['id'])));
		}else{
			header("location:category-master.php?INVALIDCAT");
			exit;	
		}
	}else{
		header("location:category-master.php?INVALIDCAT");
		exit;
	}

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	$admin->checkUserPermissions('sub_category_view',$loggedInUserDetailsArr);
	// $loggedInUserDetailsArr = $admin->getLoggedInUserDetails();

	$sql = "SELECT * FROM ".PREFIX.$tableName." where category_id = '".$cat_id."' order by created desc";
	
	$results = $admin->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo TITLE ?> | Sub Category Master</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/londinium-theme.min.css?v1" rel="stylesheet" type="text/css">
	<link href="css/styles.min.css" rel="stylesheet" type="text/css">
	<link href="css/icons.min.css" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/ico" href="images/favicon.png">

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
			$("#product-form").validate({
				rules: {
					csv_upload: {
						required: true,
						extension: 'csv',
					},
				},
				messages: {
					csv_upload: {
						extension: 'Please upload csv file',
					},
				}
			});
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

			<div class="breadcrumb-line">
				<div class="page-ttle hidden-xs" style="float:left;"><?php echo $pageName; ?></div>
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a></li>
					<li class="active"><?php echo $pageName; ?></li>
				</ul>
			</div>
			<?php
				if(in_array('sub_category_create',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') {
				if(isset($_GET['cat_id']) && !empty($_GET['cat_id'])){
			?>
					<a href="<?php echo "category-master.php"; ?>" class="btn btn-primary">Back To Category Master</a>
			<?php 
				} ?>		
					<a href="<?php echo $addURL."?cat_id=".$cat_id; ?>" class="btn btn-primary">Add <?php echo $pageName; ?></a>
			<?php 
				}
				/*if(in_array('sub_category_excel',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') { 
			?>
				<a href="export-sub-category-details-excel.php" target="_blank"><button type="button" class="btn btn-primary pull-right"><i class="fa fa-file-excel-o"></i>Export Excel</button></a>
			<?php 	} */ ?>
			<br/><br/>
			
	<?php
		if(isset($_GET['deletesuccess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark"></i> <?php echo $pageName; ?> successfully deleted.
			</div><br/>
	<?php	} ?>
	
	<?php
		if(isset($_GET['deletefail'])){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i> <strong><?php echo $pageName; ?> not deleted.</strong> Invalid Details.
			</div><br/>
	<?php	} ?>

			<br/>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h6 class="panel-title"><i class="icon-list"></i>List of Sub Categories</h6>
				</div>
				<div class="datatable-selectable-data">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Category Name</th>
								<th>Sub Category Name</th>
								<th>Sub SubCategory</th>
								<th>Action</th>
								<th>Active</th>
							</tr>
						</thead>
						<tbody>
<?php
						$x = 1;
						while($row = $admin->fetch($results)){
							$categoryDetails = $admin->getUniqueCategoryById($row['category_id']);	?>
							<tr>
								<td><?php echo $x++; ?></td>
								<td><?php echo $categoryDetails['category_name']; ?></td>
								<td><?php echo $row['sub_category_name']; ?></td>
								<td>
									<a href="sub-subCategory-master.php?subcate_id=<?php echo $row['id']; ?>&category_id=<?php echo $_GET['cat_id']; ?>">view SubSub Category</a>
								</td>
								<td>
									<?php
										if($row['active'] == '0') {
											$active_msg = 'Are you sure you want to make active?';
										} else if($row['active'] == '1') {
											$active_msg = 'Are you sure you want to make inactive?';
										}
									 
										echo $admin->getActiveLabel($row['active']);
									?>
								</td>
								
								<td>
									<a class="btn btn-info btn-xs" href="<?php echo $addURL; ?>?edit&id=<?php echo $row['id'] ?>&cat_id=<?php echo $cat_id; ?>" name="edit" title="Click to edit this sub category"><i class="icon-pencil"></i></a>
									<a class="btn btn-warning btn-xs" href="<?php echo $deleteURL; ?>?sub_category_id=<?php echo $row['id']; ?>&cat_id=<?php echo $cat_id; ?>" onclick="return confirm('Are you sure you want to delete this sub category?');" title="Click to delete this sub category, this action cannot be undone."><i class="icon-remove3"></i></a>
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
						<?php // echo $paginationArr['paginationHTML']; ?>
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
				//var fromDate = $("#fromDate").val();
				//var toDate = $("#toDate").val();
				
				window.open("export-products.php?success");
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