<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$pageName = "Refund Master";
	$pageURL = 'refund-request.php';
	$addURL = 'refund-request.php';
	$deleteURL = 'refund-request.php';
	$tableName = 'refund_request';

	
	
	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}
	$admin->checkUserPermissions('attribute_view',$loggedInUserDetailsArr);
	// $loggedInUserDetailsArr = $admin->getLoggedInUserDetails();

	if(isset($_GET['page']) && !empty($_GET['page'])) {
		$pageNo = trim($admin->strip_all($_GET['page']));
	} else {
		$pageNo = 1;
	}
	$linkParam = "";

	if(isset($_GET['OrderDetailId']) && !empty($_GET['OrderDetailId']) && isset($_GET['paymentStatus']) && !empty($_GET['paymentStatus'])){
		$OrderDetailId = trim($admin->strip_all($_GET['OrderDetailId']));
		$paymentStatus = trim($admin->strip_all($_GET['paymentStatus']));
		$price = trim($admin->strip_all($_GET['price']));
		
		$sql = "UPDATE ".PREFIX."order_details SET refund_status='".$paymentStatus."' WHERE `id`='".$OrderDetailId."'";
		//echo $sql; exit;
		$admin->query($sql);

		$orderDetailRS = $admin->query("select * from ".PREFIX."order_details where id='".$OrderDetailId."'");
		$orderDetailRow = $admin->fetch($orderDetailRS);
		
		$refundRS = $admin->query("select * from ".PREFIX."refund_request where order_detail_pal='".$OrderDetailId."'");
		$refundRow = $admin->fetch($refundRS);
		
		if($refundRow['refund_in']=='Wallet' and $paymentStatus=='Accepted') {
			// $admin->query("UPDATE ".PREFIX."customers SET wallet_balance = wallet_balance + '".$price."' WHERE `id`='".$orderDetailRow['customer_id']."'");
		}

		header("location:refund-request.php?statusSuccess");
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
	// $row = $admin->fetch($results);
	// echo"<pre>";print_r($row);die();
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

	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
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
		if(isset($_GET['deletesuccess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark"></i> <?php echo $pageName; ?> successfully deleted.
			</div>
			<br/>
	<?php	} ?>
	
	<?php
		if(isset($_GET['deletefail'])){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i><?php echo $pageName; ?> not deleted.</strong> Invalid Details.
			</div><br/>
	<?php	}
		if(isset($_GET['statusSuccess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark"></i>Cancel Request successfully Updated.
			</div><br>
	<?php	} 
		if(isset($_GET['statusFailed'])){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i>Something went wrong refund status not updated.
			</div><br/>
	<?php	} ?>
			<br><a href="export-cancel-order.php?success" id="" class="label label-primary pull-right"><i class="fa fa-save"></i> Export to excel</a>
			<br/><br/>
			<div class="panel panel-default">

				<div class="datatable-selectable-data">
					<table class="table">
						<thead>
							<tr>	
								<th>#</th>
								<th>OrderNo</th>
								<th>Image</th>
								<th>Product Name</th>
								<th>Price</th>
								<th>Cancel Reason</th>
								<th>Request Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
<?php
						$x = (10*$pageNo)-9;
						while($row = $admin->fetch($results)){
							$order = $admin->getOrderbyId($row['ordre_id']);
							$orderDetils = $admin->getOrderDetailsbyId($row['order_detail_pal']);
							$productDetails = $admin->getUniqueProductById($orderDetils['product_id']);
							$image_name = strtolower(pathinfo($productDetails['main_image'], PATHINFO_FILENAME));
							$image_ext = strtolower(pathinfo($productDetails['main_image'], PATHINFO_EXTENSION));
							if(!empty($image_name) && !empty($image_ext)){
								$imageUrl = BASE_URL."/images/products/".$image_name.'_crop.'.$image_ext;	
							}
							else{
								$imageUrl = BASE_URL."/images/products/no_img.jpg";
							}
							$unitPrice = $orderDetils['customer_price'];
							$unitDiscountedPrice = $orderDetils['customer_discount_price'];
							$quantity = $orderDetils['quantity'];
							if(!empty($unitDiscountedPrice)){
								$totalPrice = $quantity * $unitDiscountedPrice;
								$totalPriceMsg = 'Rs. '.$unitDiscountedPrice.' x '.$quantity.' unit';
								$displayPrice = $unitDiscountedPrice;
							} else {
								$totalPrice = $quantity * $unitPrice;
								$totalPriceMsg = 'Rs. '.$unitPrice.' x '.$quantity.' unit';
								$displayPrice = $unitPrice;
							}
							if($orderDetils['payment_discount']>0) {
								$paymentDiscountAmount = $totalPrice*($orderDetils['payment_discount']/100);
								$totalPrice = $totalPrice - $paymentDiscountAmount;
							}

?>
							<tr class="OrderDetails">
								<td><?php echo $x++; ?></td>
								<td><a href="<?php echo BASE_URL;?>/ds-dashboard/order-add.php?page=1&edit&txnId=<?php echo $order['txn_id'];?>"><?php echo $order['txn_id']; ?></a></td>
								<td><img src="<?php echo $imageUrl; ?>" style="width:100px;"></td>
								<td><?php echo $productDetails['product_name']; ?></td>
								<td><?php echo $totalPrice;?></td>
								<td><?php echo $row['refund_in'];?></td>
								<td><?php echo date('d /m /Y H:i:s',strtotime($row['created']));?></td>
								<td>
									
									<select class="form-control refundStatus" name="refundStatus" id="RefundStatusval">
										<!-- <option value="">Select Refund Status</option> -->
										<option value="Accepted" <?php if($orderDetils['refund_status']=="Requested"){ echo 'selected="selected"'; } ?>>Requested</option>
										<option value="Accepted" <?php if($orderDetils['refund_status']=="Accepted"){ echo 'selected="selected"'; } ?>>Accepted</option>
										<option value="Rejected" <?php if($orderDetils['refund_status']=="Rejected"){ echo 'selected="selected"'; } ?>>Rejected</option>
									</select>
									<input type="hidden" name="orderDetils" value="<?php echo $orderDetils['id']; ?>">
									<input type="hidden" name="price" value="<?php echo $displayPrice; ?>">
								</td>
								<?php /* 
								<td>
								<?php
									if(in_array('attribute_update',$userPermissionsArray) or $loggedInUserDetailsArr['role']=='super') {
								?>
									<a href="<?php echo $addURL; ?>?edit&id=<?php echo $row['id'] ?>" name="edit" class="" title="Click to edit this row"><i class="icon-pencil"></i></a>
								<?php }if(in_array('attribute_delete',$userPermissionsArray) or $loggedInUserDetailsArr['role']=='super'){ ?> 	
									<a class="" href="<?php echo $deleteURL; ?>?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Click to delete this row, this action cannot be undone."><i class="icon-remove3"></i></a>
								<?php } ?>
								</td> */?>
							</tr>
<?php
						}
?>
						</tbody>
				  </table>
				</div>
			</div>
			</form>

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
					}
			});

			$('input[name="select_all"]').change(function(){ //".checkbox" change 
				if(false == $(this).prop("checked")){ //if this item is unchecked
					$(".check").prop('checked', false); //change "select all" checked status to false
				}
				//check "select all" if all checkbox items are checked
				if ($('input[name="select_all"]:checked').length == $('input[name="select_all"]').length ){
					$(".check").prop('checked', true);
				}
			});
			$(".refundStatus").change(function() {
		        var paymentStatus = $('option:selected', this).text();
		        var id = $(this).closest('.OrderDetails').find("input[name='orderDetils']").val();
		        var price = $(this).closest('.OrderDetails').find("input[name='price']").val();
		       // alert(paymentStatus);
		        
		        var result = confirm("Are you sure you want to update refund status?");
				if(result){
				  	window.location = "refund-request.php?OrderDetailId="+id+"&paymentStatus="+paymentStatus+"&price="+price;
				}
		        
		    });
		});
	</script>
</body>
</html>