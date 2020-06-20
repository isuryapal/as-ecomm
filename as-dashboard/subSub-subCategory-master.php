<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	//error_reporting(E_ALL);
	$category_id = $admin->escape_string($admin->strip_all($_GET['subsubcate_id']));

	$pageName = "SubSub - Sub Categories";
	$pageURL = 'subSub-subCategory-master.php?subsubcate_id='.$_REQUEST['subsubcate_id'].'&subcate_id='.$_REQUEST['subcate_id'].'&category_id='.$_REQUEST['category_id'];
	$addURL = 'subSub-subCategory-add.php';
	$deleteURL = 'subsubsub-category-delete.php';
	$tableName = 'subsubsubCategory';
	
	
	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	$admin->checkUserPermissions('category_view',$loggedInUserDetailsArr);
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
	$sql = "SELECT * FROM ".PREFIX.$tableName." where subsubCate_id='$category_id' order by created DESC";
	$results = $admin->query($sql);
	
	if(isset($_GET['status']) && isset($_GET['status']) && !empty($_GET['active_id']) && !empty($_GET['active_id'])){
		$status = trim($admin->strip_all($_GET['status']));
		$active_id = trim($admin->strip_all($_GET['active_id']));
		
		if($status=="Yes"){
			$updatestatus = 'No';
		}elseif($status=="No"){
			$updatestatus = 'Yes';
		}
		//exit;
		$requestpage = 'subSub-subCategory-master.php?subsubcate_id='.$_GET['subsubcate_id'].'&subcate_id='.$_GET['subcate_id'].'&updated';
		$sql="UPDATE ".PREFIX.$tableName." SET `active`='".$updatestatus."'  WHERE id='".$active_id."'";
		$admin->query($sql);
		//echo $pageURL;
		header('Location:'.$requestpage.'&updated');
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
	<link rel="shortcut icon" href="<?php echo BASE_URL ?>/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo BASE_URL ?>/favicon.ico" type="image/x-icon">
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

			<a href="sub-subCategory-master.php?subcate_id=<?php echo $_REQUEST['subcate_id']; ?>&category_id=<?php echo $_REQUEST['category_id']; ?>" class="label label-primary">Back to SubSub Category Master</a>
			<a href="subSub-subCategory-add.php?subsubcate_id=<?php echo $_REQUEST['subsubcate_id']; ?>&subcate_id=<?php echo $_REQUEST['subcate_id']; ?>&category_id=<?php echo $_REQUEST['category_id']; ?>" class="label label-primary">Add <?php echo $pageName; ?></a><br/><br/>
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

			<br/>
			<div class="panel panel-default">

				<div class="datatable-selectable-data">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>SubSub-Category Name</th>
								<th>SubSub-SubSubCategory</th>
								<th>Active</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
<?php
						$x = (10*$pageNo)-9;
						while($row = $admin->fetch($results)){ 
						// if(!empty($row['gender']) && $row['gender'] == "Male"){
						// 	$gender = "( Prince )";
						// }elseif(!empty($row['gender']) && $row['gender'] == "Female")
						// { $gender = "( Angle )"; }else{ $gender = ""; }
							$subsubcateDetails = $admin->getuniqueSusuCategory($_REQUEST['subsubcate_id']);
?>		
							<tr>
								<td><?php echo $x++; ?></td>
								<td><?php echo $row['subsubsub_name']?></td>
								<td><?php echo $subsubcateDetails['subcategory_name']; ?></td>
								<td>
									<a href="subSub-subSubCategory-master.php?subsubsubcate_id=<?php echo $row['id']; ?>&subsubcate_id=<?php echo $_REQUEST['subsubcate_id']; ?>&subcate_id=<?php echo $_REQUEST['subcate_id']?>&category_id=<?php echo $_REQUEST['category_id']; ?>">view SubSub - SubSubCategory</a>
								</td>
								<td><?php if($row['active']=='1'){echo 'Yes';}else{echo 'No';} ?></a></td>
								<td>
									<a href="<?php echo $addURL; ?>?edit&id=<?php echo $row['id'] ?>&subsubcate_id=<?php echo $_REQUEST['subsubcate_id']; ?>&subcate_id=<?php echo $_REQUEST['subcate_id']; ?>&category_id=<?php echo $_REQUEST['category_id']; ?>" name="edit" class="" title="Click to edit this row"><i class="icon-pencil"></i></a>
									<a class="" href="<?php echo $deleteURL; ?>?id=<?php echo $row['id']; ?>&subsubcate_id=<?php echo $_REQUEST['subsubcate_id']; ?>&subcate_id=<?php echo $_REQUEST['subcate_id']; ?>&category_id=<?php echo $_REQUEST['category_id']; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Click to delete this row, this action cannot be undone."><i class="icon-remove3"></i></a>
								
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
		});
	</script>
</body>
</html>