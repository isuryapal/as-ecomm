<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$pageName = "Testimonials";
	$pageURL = 'testimonials.php';
	$addURL = 'testimonial-add.php';
	$deleteURL = 'testimonial-delete.php';
	$tableName = 'testimonials';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	// $loggedInUserDetailsArr = $admin->getLoggedInUserDetails();
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
	if(isset($_GET['search_testimonial']) && !empty($_GET['search_testimonial'])){
		$search_testimonial = $admin->escape_string($admin->strip_all($_GET['search_testimonial']));
		$linkParam='search&search_testimonial='.$search_testimonial;
	}

	include_once "include/pagination.php";
	$pagination = new Pagination();
	$paginationArr = $pagination->generatePagination($pageURL, $pageNo, $total_pages, $linkParam);

	// $sql = "SELECT * FROM ".PREFIX.$tableName." order by created DESC LIMIT ".$paginationArr['start'].", ".$paginationArr['limit']."";
	if(isset($_GET['search_testimonial']) && !empty($_GET['search_testimonial'])){
		$search_testimonial = $admin->escape_string($admin->strip_all($_GET['search_testimonial']));
		$sql = "SELECT * FROM ".PREFIX.$tableName." where name like '%".$search_testimonial."%' or place like '%".$search_testimonial."%' or testimonial like '%".$search_testimonial."%' order by created DESC LIMIT ".$paginationArr['start'].", ".$paginationArr['limit']."";
	}else{
		$sql = "SELECT * FROM ".PREFIX.$tableName." order by created DESC LIMIT ".$paginationArr['start'].", ".$paginationArr['limit']."";
	}
	$results = $admin->query($sql);
	
	if(isset($_GET['status']) && isset($_GET['status']) && !empty($_GET['active_id']) && !empty($_GET['active_id'])){
		$status = trim($admin->strip_all($_GET['status']));
		$active_id = trim($admin->strip_all($_GET['active_id']));
		
		if($status=="Yes"){
			$updatestatus = 'No';
		}elseif($status=="No"){
			$updatestatus = 'Yes';
		}
		
		$sql="UPDATE ".PREFIX.$tableName." SET `active`='".$updatestatus."'  WHERE id='".$active_id."'";
		$admin->query($sql);
		if(isset($_GET['page']) && !empty($_GET['page'])){
			header('Location:testimonials.php?updated&page='.$_GET['page']);
			exit;
		}else{
			header('Location:testimonials.php?updated');
			exit;
		}
	}
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
	<link href="css/fixedHeader.dataTables.min.css" rel="stylesheet">
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
<?php
	if(in_array('testimonials_create',$userPermissionsArray) or $loggedInUserDetailsArr['role']=='super') {
?>
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

			
			<form role="form" action="" method="GET">
				<div class="col-sm-5">
					<input type="text" class="form-control" name="search_testimonial" value="<?php if(isset($_GET['search_testimonial']) && !empty($_GET['search_testimonial'])){ echo $_GET['search_testimonial']; } ?>" Placeholder="" />
					<span class="help-block">Search by name,place and testimonial </span>
				</div>
				<div class="col-sm-1">
					<button type="submit" name="search" class="btn btn-danger">Search</button>
				</div>
			</form><br><br><br><br/>
			<div class="panel panel-default">

				<div class="datatable-selectable-data">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Active</th>
								<th>Place</th>
								<th>Testimonials</th>
								<th>Date of Submission</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
<?php
						$x = (10*$pageNo)-9;
						$link='#';
						$InvalidUrl='';
						$style='';
						while($row = $admin->fetch($results)){ 
						$submissionDate =  date('d-m-Y G:i:s', strtotime($row['created']));
						$custid = $row['customer_id']; 
						if(!empty($custid)){
							
							$sql="SELECT * FROM ".PREFIX."customers WHERE id='".$custid."'";
							//echo $sql;
							$cust = $admin->query($sql);
							if($admin->num_rows($cust) > 0){
								$userdetails = $admin->fetch($cust);
								$link = 'customers-add.php?edit&tmp&id='.$userdetails['id'];
								$InvalidUrl = "";
								$style='';
							}else{
								$link = '';
								$InvalidUrl = "InvalidUrl";
								$style='context-menu';
							}
							
						}
						
?>
							<tr>
								<td><?php echo $x++; ?></td>
								<?php if(empty($link)){ ?>
									<td><?php echo $row['name']; ?></td>
								<?php }else{ ?>
									<td><a href="<?php echo $link; ?>"  class="<?php echo $InvalidUrl; ?>"><?php echo $row['name']; ?></a></td>
								<?php } ?>
								<td><a href="testimonials.php?status=<?php echo $row['active'];?>&active_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to update status?');" title="Click to Change Product Status" ><?php echo $row['active']; ?></a></td>
								<td><?php echo $row['place']; ?></td>
								<?php /*<td><?php echo $row['position']; ?></td> */ ?>
								<td><?php echo $row['testimonial']; ?></td>
								<td><?php echo $submissionDate; ?></td>
								<td>
								<?php
								if(in_array('testimonials_update',$userPermissionsArray) or $loggedInUserDetailsArr['role']=='super') {
								?>
								<a href="<?php echo $addURL; ?>?page=<?php echo $_GET['page']; ?>&edit&id=<?php echo $row['id']; ?>" name="edit" class="" title="Click to edit this row"><i class="icon-pencil"></i></a><?php } ?>
								<?php
								if(in_array('testimonials_delete',$userPermissionsArray) or $loggedInUserDetailsArr['role']=='super') {
								?>
								<a class="" href="<?php echo $deleteURL; ?>?id=<?php echo $row['id']; ?>&page=<?php echo $_GET['page']; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Click to delete this row, this action cannot be undone."><i class="icon-remove3"></i></a><?php } ?>
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
						<?php echo $paginationArr['paginationHTML']; ?>
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
					},
				"bPaginate": false,
				"bFilter": false
			});
		});
	</script>
</body>
</html>