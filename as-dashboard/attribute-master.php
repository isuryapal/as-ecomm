<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	
	$pageName = "Attribute";
	$pageURL = 'attribute-master.php';
	$addURL = 'attribute-add.php';
	$deleteURL = 'attribute-delete.php';
	$tableName = 'category_attribute_list';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	
	// $loggedInUserDetailsArr = $admin->getLoggedInUserDetails();

	$sql = "SELECT * FROM ".PREFIX."attribute_master where is_deleted = '0'  order by attribute_name asc";
	$results = $admin->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo TITLE; ?> | Attribute Master</title>
	<link rel="shortcut icon" href="<?php echo BASE_URL; ?>/images/logo.png" type="image/png" />
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/londinium-theme.min.css?v1" rel="stylesheet" type="text/css">
	<link href="css/styles.min.css" rel="stylesheet" type="text/css">
	<link href="css/icons.min.css" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/ico" href="images/favicon.png">
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
				if(in_array('attribute_create',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') {
			?>
				<a href="<?php echo $addURL; ?>" class="btn btn-primary">Add <?php echo $pageName; ?></a>
			<?php 
				}
				/*if(in_array('attribute_excel',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') { 
			?>
				<a href="export-attribute-details-excel.php" target="_blank"><button type="button" class="btn btn-primary pull-right"><i class="fa fa-file-excel-o"></i>Export Excel</button></a>
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
					<h6 class="panel-title"><i class="icon-list"></i>List of Attribute</h6>
				</div>
				<div class="datatable-selectable-data">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Attribute Name</th>
								<th>Category Name</th>
								<th>Active</th>
								<?php if(in_array('attribute_update',$userPermissionsArray) or in_array('attribute_delete',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') { ?>
									<th>Action</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
<?php
						$x = 1;
						while($row = $admin->fetch($results)){
							$sql = "SELECT * FROM ".PREFIX.$tableName." as cal left join ".PREFIX."category_master as cm on cal.category_id = cm.id where cm.active = 'Yes' and cal.attribute_id = '".$row['id']."'";
							//echo $sql;
							$sqlQry = $admin->query($sql);
							$category_names = '';
							while($catRow = $admin->fetch($sqlQry)){
								//print_r($catRow);
								if(!empty($category_names)){
									$category_names .= ',';
								}
								$category_names .= $catRow['category_name'];

							}
?>
							<tr>
								<td><?php echo $x++;  ?></td>
								<td><?php echo $row['attribute_name']; ?></td>
								<td><?php echo $category_names; ?></td>
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
								<?php if(in_array('attribute_update',$userPermissionsArray) or in_array('attribute_delete',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') { ?>
								<td nowrap>
									<?php
										if(in_array('attribute_update',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') {
									?>
											<a class="btn btn-info btn-xs" href="<?php echo $addURL; ?>?edit&id=<?php echo $row['id'] ?>" name="edit" title="Click to edit this attribute"><i class="icon-pencil"></i></a>
									<?php
										}
										if(in_array('attribute_delete',$userPermissionsArray) or $loggedInUserDetailsArr['user_role']=='super') {
									?>
											<a class="btn btn-warning btn-xs" href="<?php echo $deleteURL; ?>?attribute_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this attribute. All data mapping also get deleted this action cannot be undone?');" title="Click to delete this attribute this action cannot be undone."><i class="icon-remove3"></i></a>
									<?php
										}
									?>
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
		});
	</script>
	
</body>
</html>