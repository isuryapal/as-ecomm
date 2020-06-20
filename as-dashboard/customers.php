<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	if(isset($_GET['page']) && !empty($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}
	
	$admin 		= new AdminFunctions();
	if($_GET['cType']=="b2c"){
		$pageName = "Customer";
	}else if($_GET['cType']=="b2b"){
		$pageName = "Vendor";
	}
	$pageURL 	= 'customers.php';
	$addURL 	= 'customers-add.php';
	$deleteURL 	= 'customer-delete.php';
	$datemgmt 	= 'datemgmt.php';
	$tableName 	= 'customers';
	$orderTypeWhereStr = '';
	$orderTypeWhereArr = array();

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

	if(isset($_GET['search']))
	{
		
		if(isset($_GET['fromDate']) && !empty($_GET['fromDate']) && isset($_GET['toDate']) && !empty($_GET['toDate'])){
		//	echo "hell"; exit;
			$fromDate = trim($admin->escape_string($admin->strip_all($_GET['fromDate'])));
			$toDate = trim($admin->escape_string($admin->strip_all($_GET['toDate'])));
			$fromDateTime = date('Y-m-d', strtotime($fromDate))." 00:00:00";
			$toDateTime = date('Y-m-d', strtotime($toDate))." 23:59:59";
			$orderTypeWhereArr[] = "(created between '".$fromDateTime."' and '".$toDateTime."')";
			$linkParam = "fromDate='".$fromDate."'&toDate='".$toDate."'&search=";
		}
		if(isset($_GET['search_name']) && !empty($_GET['search_name'])){
			$search_name = trim($admin->escape_string($admin->strip_all($_GET['search_name'])));
			$orderTypeWhereArr[] = " first_name like '%".$search_name."%' or email like '%".$search_name."%'";
			$linkParam .= '&search_name='.$search_name;
		}
	}
	if(count($orderTypeWhereArr)>0){
		$orderTypeWhereStr = implode(" and ", $orderTypeWhereArr);
		if(!empty($orderTypeWhereStr)){
			$orderTypeWhereStr = ' and '.$orderTypeWhereStr;
		}
	}
	$customerType='';
	if(isset($_GET['cType']) && $_GET['cType']=="b2c"){
		$customerType = " and user_type='b2c' ";
	}else{
		$customerType = " and user_type!='b2c' ";
	}

	$query = "SELECT COUNT(*) as num FROM ".PREFIX.$tableName." where id<>'' ".$customerType." ".$orderTypeWhereStr;
	
	$total_pages = $admin->fetch($admin->query($query));
	$total_pages = $total_pages['num'];

	

	include_once "include/pagination.php";
	$pagination = new Pagination();
	$paginationArr = $pagination->generatePagination($pageURL, $pageNo, $total_pages, $linkParam);

	// $sql = "SELECT * FROM ".PREFIX.$tableName." order by created DESC LIMIT ".$paginationArr['start'].", ".$paginationArr['limit']."";
	

	$sql = "SELECT * FROM ".PREFIX.$tableName." where id<>'' ".$customerType." ".$orderTypeWhereStr." order by created DESC LIMIT ".$paginationArr['start'].", ".$paginationArr['limit']."";
	//echo $sql; exit;
	$results = $admin->query($sql);
	
	//echo current_date; 
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
	<link href="css/fixedHeader.dataTables.min.css" rel="stylesheet">
	<link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<script src="js/bootstrap-datepicker.min.js"></script>
	<script src="js/Moment.js"></script>
<script>
	$(document).ready(function(){
		<?php if(!isset($_GET['toDate'])){ ?>
			$('#toDate').hide();
			$('#datetitle').hide();
		<?php } ?>	
			// var start_date = new Date();
			// var start_date = new Date();
		$('#fromDate').datepicker({
			format: "dd-mm-yyyy",
			//startDate: start_date,
			todayHighlight: true,
			endDate: '+0d',
			autoclose: true,
		}).on('changeDate', function (selected) {
			$('#toDate').show();
			$('#datetitle').show();
			var startDate = new Date(selected.date.valueOf());
			$("#toDate").datepicker('setStartDate', startDate);
		});
		$('#toDate').datepicker({
			format: "dd-mm-yyyy",
			//startDate: start_date,
			endDate: '+0d',
			todayHighlight: true,
			autoclose: true,
		}).on('changeDate', function (selected) {
			$('#fromDate').show();
			$('#datetitle').show();
			var endDate = new Date(selected.date.valueOf());
			$("#fromDate").datepicker('setEndDate', endDate);
		});
			
		
		$('[data-toggle="tooltip"]').tooltip();

		$('body').append('<div id="tableCopy" style="display:none"></div>');
		var tableCopy = $("#orderTable").clone();
		var headingRow = tableCopy.find('thead tr th');
		// console.log(headingRow[0]); // TEST
		headingRow[0].remove();
		headingRow[headingRow.length-1].remove();

		var rowArr = tableCopy.find('tbody tr');
		// console.log(rowArr); // TEST
		$.each(rowArr, function(index, oneRow){
			// console.log(rowArr[index]); // TEST
			// console.log(oneRow); // TEST
			// console.log($(oneRow).find('td')); // TEST
			var firstCell = $(oneRow).find('td');
			firstCell[0].remove();
			firstCell[firstCell.length-1].remove();
		})
		$("#tableCopy").html(tableCopy);

	});
</script>
	<script type="text/javascript" src="js/application.js"></script>
	<script type="text/javascript" src="js/export-excel.js"></script>
	<?php /* <script>
		$(document).ready(function(){
			$(".show-action-btn").on("click", function(){
				var actionBtn = $(this).closest('.btn-group').next('.action-btn');
				if(actionBtn.css("display") == "none"){
					actionBtn.css("display", "inline-block");
				} else {
					actionBtn.hide();
				}
			});
		});
	</script> */ ?>
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
			<form action="" method="GET">
				<div class="row">
					<div class="col-sm-3">
						<label>Search by name,email,contact</label>
						<input autocomplete="off" type="text" class="form-control valid_date"  id="search_name" name="search_name" value="<?php if(isset($search_name)){ 
									echo $search_name; } ?>"/>
					</div>
					<div class="col-sm-3">
						<label>From Date</label>
						<input autocomplete="off" type="text" class="form-control valid_date" required="required" id="fromDate" name="fromDate" value="<?php if(isset($fromDate)){ 
									echo $fromDate; } ?>"/>
					</div>
					<div class="col-sm-3">
						<label id="datetitle">To Date</label>
						<input autocomplete="off" type="text" class="form-control valid_date"  required="required" id="toDate" name="toDate" value="<?php if(isset($_GET['toDate'])){ echo $toDate; }?>"/>
						<input type="hidden" name="page" value="<?php if(isset($_GET['page'])){ echo $_GET['page']; }else{ echo "1"; } ?>" >
					</div>
					<div class="col-sm-3">
						<label>&nbsp;</label>
						<div class="clearfix"></div>
						<button type="submit" name="search" class="btn btn-danger">Search</button> 
						<a href="customers.php" class="btn btn-warning">Reset</a>
					</div>
				</div>
			</form><br>
			
			<a href="<?php echo $addURL."?cType=".$_GET['cType']; ?>" class="label label-primary">Add <?php echo $pageName; ?></a>
			
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

			<div class="panel panel-default">

				<div class="datatable-selectable-data">
					<table class="table" id="orderTable">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>E-mail verified</th>
								<th>Email</th>
								<th>Contact</th>
								<th>Registration Date</th>
								<th>User Verfied</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
<?php
						$x = (100*$pageNo)-99;
						while($row = $admin->fetch($results)){ 
							$orderPageURL = 'order.php?type=customer';
							$orderPageURL .= '&customerId='.$row['id'];
?>
							<tr>
								<td><?php echo $x++; ?></td>
								<td><?php echo $row['first_name'];?></td>
								<td><?php if($row['is_email_verified'] =='0'){echo "No"; }if($row['is_email_verified'] =='1'){echo "Yes";}?></td>
								<td><?php echo $row['email']; ?></td>
								<td><?php echo $row['mobile']; ?></td>
								<td><?php echo date('d-m-Y 	G:i:sa',strtotime($row['created'])); ?></td>
								<td>
<?php								if($row['user_verified']=="1"){ ?>
										<span class="label label-success">
											Verified
										</span>
<?php								}else{ ?>
										<span class="label label-danger">
											Not Verified
										</span>
<?php 								} ?>
								</td>
								<td>
<?php								if($row['active']=="1"){ ?>
										<span class="label label-success">
											Active
										</span>
<?php								}else{ ?>
										<span class="label label-danger">
											Not Active
										</span>
<?php 								} ?>
								</td>
								<td>
									<div class="btn-group">
										<a href="<?php echo $orderPageURL; ?>" class="" title="View customer orders">
											<i class="icon-list"></i>
										</a>
										<a href="<?php echo $addURL; ?>?page=<?php echo $page; ?>&edit&id=<?php echo $row['id'] ?>&cType=<?php echo $_GET['cType']; ?>" name="edit" class="" title="Click to edit this row"><i class="icon-pencil"></i></a>
										<a class="" href="<?php echo $deleteURL; ?>?page=<?php echo $page; ?>&id=<?php echo $row['id']; ?>&editedby=<?php echo $loggedInUserDetailsArr['id']; ?>&cType=<?php echo $_GET['cType']; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Click to delete this row, this action cannot be undone."><i class="icon-remove3"></i></a>
									</div>
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
	
	<script>
		$(document).ready(function() {
			$('.datatable-selectable-data table').dataTable({
				"order": [[ 0, 'asc' ]],
			
				"bPaginate": false,
				"bFilter": false
			});
		});
	</script>
	<script>
		$(document).ready(function() {
			$("#export").on("click",function(){
				var searchIDs = $('input:checked').map(function(){
				  return $(this).val();
				});
				window.open("export-customer.php?success&ids="+searchIDs.get());
			});
		});
	</script>
	<script>
	 $("#selectall").click(function () {
		 $('input:checkbox').not(this).prop('checked', this.checked);
	 });
	</script>
</body>
</html>