<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$pageName = "Order";
	$pageURL = 'order.php';
	$addURL = 'order-add.php';
	if(isset($_GET['page']) && !empty($_GET['page'])){
		$deleteURL = 'order-delete.php?page='.$_GET['page'];
	}else{
		$deleteURL = 'order-delete.php';
	}
	$tableName = 'order';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	
	if(isset($_GET['page']) && !empty($_GET['page'])) {
		$pageNo = trim($admin->strip_all($_GET['page']));
	}else{
		$pageNo=1;
	} 
	$linkParam = "";

	$urlParamArr = array();
	$urlParamStr = '';
	$orderTypeWhereArr = array();
	$orderTypeWhereStr = '';
	if(isset($_GET['type']) && !empty($_GET['type'])) {
		$orderType = $admin->escape_string(($admin->strip_all($_GET['type'])));
		$urlParamArr[] = 'type='.$orderType;
		$orderTypeWhereArr[] = " id in (select order_id from ".PREFIX."order_details)";
	}
	if(isset($_GET['customerId']) && !empty($_GET['customerId'])) {
		$customerId = $admin->escape_string(($admin->strip_all($_GET['customerId'])));
		$customerDetails = $admin->getUniqueCustomersById($customerId);
		$urlParamArr[] = 'customerId='.$customerId;
		$orderTypeWhereArr[] = " customer_id='".$customerId."'";
		$parentPageURL = 'customers.php';
	} else {
		$customerId = '';
	}
	$fromDate = '';
	$toDate = '';
	$search_name = '';
	if(isset($_GET['search']))
	{
		if(isset($_GET['fromDate']) && !empty($_GET['fromDate']) && isset($_GET['toDate']) && !empty($_GET['toDate'])){
		//	echo "hell"; exit;
			$fromDate = trim($admin->escape_string($admin->strip_all($_GET['fromDate'])));
			$toDate = trim($admin->escape_string($admin->strip_all($_GET['toDate'])));
			$fromDateTime = date('Y-m-d', strtotime($fromDate))." 00:00:00";
			$toDateTime = date('Y-m-d', strtotime($toDate))." 23:59:59";
			$orderTypeWhereArr[] = "(created between '".$fromDateTime."' and '".$toDateTime."')";
			$linkParam .= 'fromDate='.$fromDate.'&toDate='.$toDate.'&search=';
		}
		if(isset($_GET['search_name']) && !empty($_GET['search_name'])){
			$search_name = trim($admin->escape_string($admin->strip_all($_GET['search_name'])));
			$orderTypeWhereArr[] = " txn_id like '%".$search_name."%' or shipping_fname like '%".$search_name."%' or billing_fname like '%".$search_name."%'";
			$linkParam .= '&search_name='.$search_name;
		} 
	} else {
		$fromDate = '';
		$toDate = '';
		$search_name = '';
	}
	//echo $linkParam; exit;
	if(count($orderTypeWhereArr)>0){
		$orderTypeWhereStr = implode(" and ", $orderTypeWhereArr);
		if(!empty($orderTypeWhereStr)){
			$orderTypeWhereStr = ' and '.$orderTypeWhereStr;
		}
	}
	if(count($urlParamArr)>0){
		$urlParamStr = implode("&", $urlParamArr);
		$addURL .= '?'.$urlParamStr;
		$deleteURL .= '?'.$urlParamStr;
		$pageURL .= '?'.$urlParamStr;
	}

	$query = "SELECT COUNT(*) as num FROM ".PREFIX.$tableName." where id<>'0' ".$orderTypeWhereStr;
	//echo $query;
	$total_pages = $admin->fetch($admin->query($query));
	$total_pages = $total_pages['num'];


	include_once "include/pagination.php";
	$pagination = new Pagination();
	$paginationArr = $pagination->generatePagination($pageURL, $pageNo, $total_pages, $linkParam);

	// $sql = "SELECT * FROM ".PREFIX.$tableName." order by created DESC LIMIT ".$paginationArr['start'].", ".$paginationArr['limit']."";
	$sql = "SELECT * FROM ".PREFIX.$tableName." where id<>'0' ".$orderTypeWhereStr." order by id DESC LIMIT ".$paginationArr['start'].", ".$paginationArr['limit']."";
	//echo $sql;
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
	<link href="css/fixedHeader.dataTables.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
	<link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
	<script src="js/bootstrap-datepicker.min.js"></script>
	<script src="js/Moment.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#form").validate({
				rules: {
					image_name: {
						extension: 'jpg|jpeg'
					},
				}
			});
		});
	</script>
<script>
	$(document).ready(function(){
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
		<?php if(!isset($_GET['toDate'])){ ?>
			$('#toDate').hide();
			$('#datetitle').hide();
		<?php } ?>	
		var start_date = new Date();
		var start_date = new Date();
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
	});
</script>
	<script type="text/javascript" src="js/application.js"></script>
	<script type="text/javascript" src="js/export-excel.js"></script>

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
		<?php if(isset($_GET['customerId'])){  ?><a href="customers.php?cType=<?php echo "b2c"; ?>&customerId=<?php echo $customerId; ?>" class="label label-primary">Back to customers</a><?php } ?>
<?php 	if(isset($customerId) && !empty($customerId)){ ?>
			<div class="page-header">
				<div class="page-title">
					<h3><?php echo $customerDetails['first_name']; ?> 
						<small>Order Report</small>
					</h3>
				</div>
			</div>
<?php	}
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
			<form action="" method="GET" id="form">
				<div class="row">
					<div class="col-sm-3">
						<label>Search by orderNo,InvoiceNo</label>
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
						<input autocomplete="off" type="text" class="form-control valid_date"  required="required" name="toDate" id="toDate" value="<?php if(isset($_GET['toDate'])){ echo $toDate; }?>"/>
					</div>
					<div class="col-sm-3">
						<label>&nbsp;</label>
						<div class="clearfix"></div>
						<button type="submit" name="search" class="btn btn-danger">Search</button> 
						<a href="order.php" class="btn btn-warning">Reset</a>
						<input type="hidden" name="customerId" value="<?php echo $customerId ?>" />
					</div>
				</div>
			</form>
			<br><br>

			<!-- onclick="tableToExcel('tableCopy', 'Order Details')"  Previously Added For Export To Excel-->
			<br><a href="export-order.php?success&customerId=<?php echo $customerId ?>&fromDate=<?php echo $fromDate ?>&toDate=<?php echo $toDate ?>&search_name=<?php echo $search_name ?>" id="" class="label label-primary pull-right"><i class="fa fa-save"></i> Export to excel</a>
			<br/><br/>

			
			
			<div class="panel panel-default">
				<div class="datatable-selectable-data" id="example">
					<table class="table" id="orderTable">
						<thead>
							<tr>
								<th>#</th>
								<th width="10%">Customer Name</th>
								<th width="10%">Customer Contact</th>
								<th>Order No.</th>
								<th>Order Date</th>
								<th>Amount</th>
								<th>Order Status</th>
								<th>Payment Status</th>
								<th>Payment Mode</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
<?php
						$x = (100*$pageNo)-99;
						while($row = $admin->fetch($results)){
							$customerDetails = $admin->getUniqueCustomersById($row['customer_id']);
?>
							<tr>
								<td><?php echo $x++; ?></td>
								<td><?php echo $row['billing_fname']. ' '.$row['billing_lname']; ?></td>
								<td>
									<?php echo $row['shipping_email'];?><br>
									<?php echo $customerDetails['mobile'] ?>
								</td>
								<td>
									<?php echo $row['txn_id'];?><br>
									<?php
										$purchaseDetails = $admin->getProductOrderDetails($row['txn_id']);
										$orderDetails = $purchaseDetails['orderDetails'];
										foreach($orderDetails as $oneOrder){
											$productDetails = $admin->getUniqueProductById($oneOrder['product_id']);
											$quantity = $oneOrder['quantity'];
											echo $productDetails['product_name'].' - ('.$oneOrder['size'].') - ('.$quantity.')';
											echo '<br>';
										}
									?>
								</td>
								<td><?php echo date('d-m-Y G:i:s', strtotime($row['created'])); ?></td>
								<td><?php echo round($admin->getCustomerPurchaseAmount($row['txn_id'])); ?></td>
								<td>
<?php 					if($row['order_status']=="In Process"){ ?>
								<span class="label label-warning">
									<?php echo $row['order_status']; ?>
								</span>
<?php					} else if($row['order_status']=="Completed"){ ?>
								<span class="label label-success">
									<?php echo $row['order_status']; ?>
								</span>
<?php					} else if($row['order_status']=="Cancelled"){ ?>
								<span class="label label-danger">
									<?php echo $row['order_status']; ?>
								</span>
<?php 					} else if($row['order_status']=="Shipped"){ ?>
								<span class="label label-default">
									<?php echo $row['order_status']; ?>
								</span>
<?php 					} else {
								echo $row['order_status'];
						} ?>
								
								</td>
								<td>
<?php 					if($row['payment_status']=="Payment Pending"){ ?>
								<span class="label label-warning">
									<?php echo $row['payment_status']; ?>
								</span>
<?php					} else if($row['payment_status']=="Payment Complete"){ ?>
								<span class="label label-success">
									<?php echo $row['payment_status']; ?>
								</span>
<?php					} else if($row['payment_status']=="Payment Failed"){ ?>
								<span class="label label-danger">
									<?php echo $row['payment_status']; ?>
								</span>
<?php 					} else if($row['payment_status']=="Payment Refunded"){ ?>
								<span class="label label-default">
									<?php echo $row['payment_status']; ?>
								</span>
<?php 					} else {
								echo $row['payment_status'];
						} ?>
								</td>
								<td><?php echo $row['payment_mode'];?></td>
								<td align="right">
									<div class="btn-group">
										<a href="<?php echo $addURL; ?>?page=<?php echo $pageNo; ?>&edit&txnId=<?php echo $row['txn_id'] ?>" name="edit" class="" title="Click to edit this row"><i class="icon-pencil"></i></a>
<?php					if($row['payment_status']=="Payment Pending"){ ?>
									<a class="" href="<?php echo $deleteURL; ?>?txnId=<?php echo $row['txn_id']; ?>" onclick="return confirm('Are you sure you want to delete order no. <?php echo $row['txn_id'];?> ?\n\nThis action cannot be undone!');" title="Click to delete this row, this action cannot be undone."><i class="icon-remove3"></i></a>
<?php					} else { } ?>
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
	<script>
		$(document).ready(function() {
			$("#export").on("click",function(){
				//var fromDate = $("#fromDate").val();
				//var toDate = $("#toDate").val();
				/* var values = $("input[name='products[]']").map(function(){return $(this).val();}).get();
				console.log(values); */
				  var searchIDs = $('input:checked').map(function(){
				  return $(this).val();
				});
				//console.log(searchIDs.get());
				window.open("export-order.php?success&ids="+searchIDs.get());
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