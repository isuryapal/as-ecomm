<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();

	$pageName = "Testimonial Master";
	$pageURL = 'testimonial-master.php';
	$addURL = 'testimonial-add.php';
	$deleteURL = 'testimonial-delete.php';
	$tableName = 'testimonials';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	$loggedInUserDetailsArr = $admin->getLoggedInUserDetails();
	$admin->checkUserPermissions('testimonials_view',$loggedInUserDetailsArr);

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo TITLE ?></title>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/londinium-theme.min.css" rel="stylesheet" type="text/css">
	<link href="css/styles.min.css" rel="stylesheet" type="text/css">
	<link href="css/icons.min.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<!--<link href="css/nanoscroller.css" rel="stylesheet">
	<link href="css/cover.css" rel="stylesheet">-->

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
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
			
			<?php if(in_array('testimonial_create',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') { ?>
				<a href="<?php echo $addURL; ?>" class="label label-primary">Add <?php echo $pageName; ?></a><br/><br/>
			<?php } ?>

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
					<h6 class="panel-title"><i class="icon-library"></i>testimonials List</h6>
				</div>
				<div class="datatable-selectable">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Profile Image</th>
								<th>Name</th>
								<th>Designation</th>
								<th>Message</th>
								<th>Active Status</th>

							<?php if(in_array('testimonial_update',$userPermissionsArray) or in_array('testimonial_delete',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') { ?>
									<th>Action</th>
							<?php } ?>

							</tr>
						</thead>
						<tbody>
						<?php
						$x = (10*$pageNo)-9;
						while($row = $admin->fetch($results)){ 
							$imgDir = '../images/testimonials/';
							$file_name = str_replace('', '-', strtolower( pathinfo($row['image'], PATHINFO_FILENAME)));
							$ext = pathinfo($row['image'], PATHINFO_EXTENSION);
							?>
							<tr>
								<td><?php echo $x++; ?></td>
								<td>
									<?php 
										if(file_exists($imgDir.$file_name.'_crop.'.$ext)){
											echo "<img src='".$imgDir.$file_name."_crop.".$ext."' width='100' height='100'>";
										} else {
											echo "<img src='../images/default-user.jpg' width='100' height='100' >";
										}
									?>
										
								</td>
								<td><?php echo $row['name']; ?></td>
								<td><?php echo $row['position']; ?></td>
								<td><?php echo trim($admin->strip_all($row['testimonial'])); ?></td>
								<td>
									<?php
										if(in_array('testimonial_update',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super'){
											if($row['active']=='Yes'){ 
												echo '<span class="label label-success">Yes</span>';
											}else{ 
												echo '<span class="label label-danger">No</span>'; 
											}
										} 
									?>
								</td>

								<?php if(in_array('testimonial_update',$userPermissionsArray) or in_array('testimonial_delete',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') { ?>
										<td>

											<?php if(in_array('testimonial_update',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') { ?>
													<a class="label label-warning"  href="<?php echo $addURL; ?>?edit&id=<?php echo $row['id'] ?>" name="edit" title="Click to edit this row"><i class="icon-pencil"></i></a>
											<?php } ?>

											<?php if(in_array('testimonial_delete',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') { ?>

													<a class="label label-danger" href="<?php echo $deleteURL; ?>?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Click to delete this row, this action cannot be undone."><i class="icon-remove3"></i></a>

											<?php } ?>
										</td>
								<?php } ?>
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

</body>
</html>