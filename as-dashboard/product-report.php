<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$pageName = "Product Report";
	$pageURL = 'product-report.php';
	$tableName = 'order_details';

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

	$whereClause = '';
	$fromDateTime ='';
	$toDateTime ='';

	if(isset($_GET['report']) && !empty($_GET['report']) && $_GET['report']=="most_viewed"){
		if(isset($_GET['fromDate']) && !empty($_GET['fromDate'])){
			$fromDate = $admin->escape_string($admin->strip_all($_GET['fromDate']));
			$fromDateTime = date('Y-m-d', strtotime($fromDate))." 00:00:00";
		}

		if(isset($_GET['toDate']) && !empty($_GET['toDate'])){
			$toDate = $admin->escape_string($admin->strip_all($_GET['toDate']));
			$toDateTime = date('Y-m-d', strtotime($toDate))." 23:59:59";
		}
		if(!empty($fromDateTime) && !empty($toDateTime)){
			$whereClause .= "  and created between '$fromDateTime' and '$toDateTime'";
		}

		$viewSql = "SELECT * FROM ".PREFIX."product_views where id<>0 $whereClause order by views DESC";
		// echo $sql;
		$result = $admin->query($viewSql);
	}

	if(isset($_GET['report']) && !empty($_GET['report']) && $_GET['report']=="most_sold"){
		if(isset($_GET['fromDate']) && !empty($_GET['fromDate'])){
			$fromDate = $admin->escape_string($admin->strip_all($_GET['fromDate']));
			$fromDateTime = date('Y-m-d', strtotime($fromDate))." 00:00:00";
		}

		if(isset($_GET['toDate']) && !empty($_GET['toDate'])){
			$toDate = $admin->escape_string($admin->strip_all($_GET['toDate']));
			$toDateTime = date('Y-m-d', strtotime($toDate))." 23:59:59";
		}
		if(!empty($fromDateTime) && !empty($toDateTime)){
			$whereClause .= "  and created between '$fromDateTime' and '$toDateTime'";
		}

		$sql = "SELECT product_id FROM ".PREFIX.$tableName." where id<>0 $whereClause group by product_id desc";
		// echo $sql;
		$result = $admin->query($sql);
	}

	if(isset($_GET['report']) && !empty($_GET['report']) && $_GET['report']=="least_sold"){
		if(isset($_GET['fromDate']) && !empty($_GET['fromDate'])){
			$fromDate = $admin->escape_string($admin->strip_all($_GET['fromDate']));
			$fromDateTime = date('Y-m-d', strtotime($fromDate))." 00:00:00";
		}

		if(isset($_GET['toDate']) && !empty($_GET['toDate'])){
			$toDate = $admin->escape_string($admin->strip_all($_GET['toDate']));
			$toDateTime = date('Y-m-d', strtotime($toDate))." 23:59:59";
		}
		if(!empty($fromDateTime) && !empty($toDateTime)){
			$whereClause .= "  and created between '$fromDateTime' and '$toDateTime'";
		}

		$sql = "SELECT product_id FROM ".PREFIX.$tableName." where id<>0 $whereClause group by product_id asc";
		// echo $sql;
		$result = $admin->query($sql);
	}

	if(isset($_GET['report']) && !empty($_GET['report']) && $_GET['report']=="sales_report"){
		if(isset($_GET['fromDate']) && !empty($_GET['fromDate'])){
			$fromDate = $admin->escape_string($admin->strip_all($_GET['fromDate']));
			$fromDateTime = date('Y-m-d', strtotime($fromDate))." 00:00:00";
		}

		if(isset($_GET['toDate']) && !empty($_GET['toDate'])){
			$toDate = $admin->escape_string($admin->strip_all($_GET['toDate']));
			$toDateTime = date('Y-m-d', strtotime($toDate))." 23:59:59";
		}
		if(!empty($fromDateTime) && !empty($toDateTime)){
			$whereClause .= "  and created between '$fromDateTime' and '$toDateTime'";
		}

		$sql = "SELECT * FROM ".PREFIX."order where id<>0 $whereClause order by id DESC";
		// echo $sql;
		$result = $admin->query($sql);
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
	<script type="text/javascript" src="js/application.js"></script>
	<script type="text/javascript" src="js/export-excel.js"></script>

</head>
<body class="sidebar-wide">
	<?php include 'include/navbar.php' ?>

	<div class="page-container">

	<?php include 'include/sidebar.php' ?>

		<div class="page-content">

			<div class="breadcrumb-line">
				<div class="page-ttle hidden-xs" style="float:left;">
					<?php echo 'Edit '.$pageName; ?>
				</div>
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a></li>
					<li><a href="<?php echo $pageURL; ?>"><?php echo $pageName; ?></a></li>
					<li class="active">
						<?php echo 'Edit '.$pageName; ?>
					</li>
				</ul>
			</div>

			<form role="form" action="" method="get">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6 class="panel-title"><i class="icon-library"></i> Product And Sales Report</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<label class="col-form-label">Report</label>
									<select class="form-control" name="report">
										<option value="most_viewed" <?php if(isset($_GET['report'])&&$_GET['report']=="most_viewed"){ echo "selected"; } ?>>Most viewed Product</option>
										<option value="most_sold" <?php if(isset($_GET['report'])&&$_GET['report']=="most_sold"){ echo "selected"; } ?>>Most sold product</option>
										<option value="least_sold" <?php if(isset($_GET['report'])&&$_GET['report']=="least_viewed"){ echo "selected"; } ?>>Least sold product</option>
										<option value="sales_report" <?php if(isset($_GET['report'])&&$_GET['report']=="sales_report"){ echo "selected"; } ?>>Sales Report</option>
									</select>
								</div>
								<div class="col-sm-3">
									<label>From Date</label>
									<input autocomplete="off" type="text" class="form-control datepicker"  id="fromDate" name="fromDate" value="<?php if(isset($_GET['fromDate'])){ echo $_GET['fromDate']; } ?>"/>
								</div>
								<div class="col-sm-3">
									<label id="datetitle">To Date</label>
									<input autocomplete="off" type="text" class="form-control datepicker"  name="toDate" id="toDate" value="<?php if(isset($_GET['toDate'])){ echo $_GET['toDate']; }?>"/>
								</div>
								<div class="col-sm-6">
									<label>&nbsp;</label>
									<div class="clearfix"></div>
									<button type="submit" name="search" class="btn btn-danger">Search</button> 
									<a href="product-report.php" class="btn btn-warning">Reset</a>
										<a target="_blank" href="product-report-export.php?report=<?php if(isset($_GET['report'])){ echo $_GET['report']; } ?>&fromDate=<?php if(isset($_GET['fromDate'])){ echo $_GET['fromDate']; } ?>&toDate=<?php if(isset($_GET['toDate'])){ echo $_GET['toDate']; } ?>" class="btn btn-info">Export To Excel</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
	<?php if(isset($result) && $_GET['report']=="most_viewed" || $_GET['report']=="most_sold" || $_GET['report']=="least_sold"){ ?>

				<div class="panel panel-default">

					<div class="datatable-selectable-data">
						<table class="table" id="orderTable">
							<thead>
								<tr>
									<th>#</th>
									<th>Product Image URL</th>
									<th>Product Name</th>
									<th>Product Code</th>
									<th>Active</th>
									<th>Created</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(isset($_GET['report']) && $_GET['report']=="most_viewed"){
									$x = 1;
									if($admin->num_rows($result)>0){
										while($orderRow = $admin->fetch($result)){
											$prosql = "SELECT * FROM ".PREFIX."product_master WHERE id='".$orderRow['product_id']."'";
											$proRes = $admin->query($prosql);
											while($row = $admin->fetch($proRes)){
												$file_name = str_replace('', '-', strtolower( pathinfo($row['main_image'], PATHINFO_FILENAME)));
												$ext = pathinfo($row['main_image'], PATHINFO_EXTENSION);
												if(!empty($row['main_image'])){
													$url =  BASE_URL."/images/products/".$file_name.'_crop.'.$ext;
												}else{
													$url = BASE_URL.'/images/default.jpg';
												}
								?>
										<tr>
											<td><?php echo $x++; ?></td>
											<td><img style="height: 100px;" src="<?php echo $url; ?>"  /></td>
											<td><?php echo $row['product_name']; ?></td>
											<td><?php echo $row['product_code']; ?></td>
											<td><?php echo $row['active']; ?></td>
											<td><?php echo date('d-m-Y', strtotime($row['created'])); ?></td>
										</tr>
								<?php
											}
										}
									}
								}else{
								?>
								<?php
									$x = 1;
									while($orderRow = $admin->fetch($result)){
										// $countSql = "SELECT count(product_id) as count FROM ".PREFIX.$tableName." WHERE product_id='".$orderRow['product_id']."' $whereClause";
										// $countRes = $admin->query($countSql);
										// $countRow = $admin->fetch($countRes);
										// if($_GET['report']=="most_sold"){
										// 	$num = $countRow['count']>1;
										// }elseif($_GET['report']=="least_sold"){
										// 	$num = $countRow['count']<2;
										// }
										// if($num){
										$prosql = "SELECT * FROM ".PREFIX."product_master WHERE id='".$orderRow['product_id']."'";
										$proRes = $admin->query($prosql);
										while($row = $admin->fetch($proRes)){
											$file_name = str_replace('', '-', strtolower( pathinfo($row['main_image'], PATHINFO_FILENAME)));
											$ext = pathinfo($row['main_image'], PATHINFO_EXTENSION);
											if(!empty($row['main_image'])){
												$url =  BASE_URL."/images/products/".$file_name.'_crop.'.$ext;
											}else{
												$url = BASE_URL.'/images/default.jpg';
											}
								?>
										<tr>
											<td><?php echo $x++; ?></td>
											<td><img style="height: 100px;" src="<?php echo $url; ?>"  /></td>
											<td><?php echo $row['product_name']; ?></td>
											<td><?php echo $row['product_code']; ?></td>
											<td><?php echo $row['active']; ?></td>
											<td><?php echo date('d-m-Y', strtotime($row['created'])); ?></td>
										</tr>
								<?php
										}
											// }	
									}
								}
								?>
							</tbody>
					  </table>
					</div>
				</div>

	<?php }elseif(isset($result)){ ?>
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
								</tr>
							</thead>
							<tbody>
	<?php
							$x = 1;
							while($row = $admin->fetch($result)){
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
								</tr>
	<?php
							}
	?>
							</tbody>
					  </table>
					</div>
				</div>
	<?php } ?>

	<?php include "include/footer.php"; ?>
		
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