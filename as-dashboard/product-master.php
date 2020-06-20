<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	//error_reporting(0);
	
	$pageName = "Products";
	$pageURL = 'product-master.php';
	$addURL = 'prdouct-add.php';
	$deleteURL = 'product-master.php';
	$tableName = 'product_master';

	if(!$loggedInUserDetailsArr = $admin->sessionExists()){
		header("location: admin-login.php");
		exit();
	}

	if(isset($_GET['delid']) && !empty($_GET['delid'])){
		$delid = trim($admin->strip_all($_GET['delid']));

		$sql = "SELECT * FROM ".PREFIX."product_master WHERE id='".$delid."'";
		$result = $admin->query($sql);
		if($admin->num_rows($result)>0){
			$certi = $admin->fetch($result);

			$certimage = str_replace('', '-', strtolower( pathinfo($certi['main_image'], PATHINFO_FILENAME)));
			$certimage_ext = pathinfo($certi['main_image'], PATHINFO_EXTENSION);
			if(file_exists("../images/products/".$certimage.'_crop.'.$certimage_ext)){
				unlink("../images/products/".$certimage.'_crop.'.$certimage_ext);
				unlink("../images/products/".$certimage.'_large.'.$certimage_ext);
			}

			$certimage = str_replace('', '-', strtolower( pathinfo($certi['image_one'], PATHINFO_FILENAME)));
			$certimage_ext = pathinfo($certi['image_one'], PATHINFO_EXTENSION);
			if(file_exists("../images/products/".$certimage.'_crop.'.$certimage_ext)){
				unlink("../images/products/".$certimage.'_crop.'.$certimage_ext);
				unlink("../images/products/".$certimage.'_large.'.$certimage_ext);
			} 

			$certimage = str_replace('', '-', strtolower( pathinfo($certi['image_two'], PATHINFO_FILENAME)));
			$certimage_ext = pathinfo($certi['image_two'], PATHINFO_EXTENSION);
			if(file_exists("../images/products/".$certimage.'_crop.'.$certimage_ext)){
				unlink("../images/products/".$certimage.'_crop.'.$certimage_ext);
				unlink("../images/products/".$certimage.'_large.'.$certimage_ext);
			} 
			
			$certimage = str_replace('', '-', strtolower( pathinfo($certi['image_three'], PATHINFO_FILENAME)));
			$certimage_ext = pathinfo($certi['image_three'], PATHINFO_EXTENSION);
			if(file_exists("../images/products/".$certimage.'_crop.'.$certimage_ext)){
				unlink("../images/products/".$certimage.'_crop.'.$certimage_ext);
				unlink("../images/products/".$certimage.'_large.'.$certimage_ext);
			} 

			$certimage = str_replace('', '-', strtolower( pathinfo($certi['image_four'], PATHINFO_FILENAME)));
			$certimage_ext = pathinfo($certi['image_four'], PATHINFO_EXTENSION);
			if(file_exists("../images/products/".$certimage.'_crop.'.$certimage_ext)){
				unlink("../images/products/".$certimage.'_crop.'.$certimage_ext);
				unlink("../images/products/".$certimage.'_large.'.$certimage_ext);
			} 

			//$admin->deleteAllProductMappingbyProeductID($delid);


			$Upsql = "DELETE FROM ".PREFIX."product_master WHERE `id`='".$delid."'";
			$admin->query($Upsql);
			header('Location:'.$pageURL.'?deletesuccess');
			exit;	
		}

		
	}

	// $loggedInUserDetailsArr = $admin->getLoggedInUserDetails();
	//$admin->checkUserPermissions('product_view',$loggedInUserDetailsArr);
	
	if(isset($_GET['page']) && !empty($_GET['page'])) {
		$pageNo = trim($admin->strip_all($_GET['page']));
	}else{
		$pageNo=1;
	}
	$linkParam = "";
	if(isset($_GET['search']) && !empty($_GET['search_product'])){
		$search_product = trim($admin->escape_string($admin->strip_all($_GET['search_product'])));
		$searchP = " where product_name like '%".$search_product."%' or product_code like '%".$search_product."%' ";
	}else{
		$searchP='';
	}

	$query = "SELECT COUNT(*) as num FROM ".PREFIX.$tableName.$searchP;
	$total_pages = $admin->fetch($admin->query($query));
	$total_pages = $total_pages['num'];
	
	if(isset($_GET['search']) && !empty($_GET['search_product'])){
		$search_product = trim($admin->escape_string($admin->strip_all($_GET['search_product'])));
		$linkParam = 'search=&search_product='.$search_product;
	}

	include_once "include/pagination.php";
	$pagination = new Pagination();
	$paginationArr = $pagination->generatePagination($pageURL, $pageNo, $total_pages, $linkParam);
	$OrderBy = '';
	
	if(isset($_GET['EntryProductnameASC'])){
		$OrderBy = ' order by product_name ASC';
	}elseif(isset($_GET['EntryProductnameDESC'])){
		$OrderBy = ' order by product_name DESC';
	}elseif(isset($_GET['ProductSortASC'])){
		$OrderBy = ' order by product_code ASC';
	}elseif(isset($_GET['ProductSortDESC'])){
		$OrderBy = ' order by product_code DESC';
	}elseif(isset($_GET['EntryDateSortcreatedASC'])){
		$OrderBy = ' order by created ASC';
	}elseif(isset($_GET['EntryDateSortcreatedDESC'])){
		$OrderBy = ' order by created DESC';
	}else{
		$OrderBy = ' order by id DESC';
	}
	
	
	// $sql = "SELECT * FROM ".PREFIX.$tableName." order by created DESC LIMIT ".$paginationArr['start'].", ".$paginationArr['limit']."";
	if(isset($_GET['search']) && !empty($_GET['search_product'])){
		$search_product = trim($admin->escape_string($admin->strip_all($_GET['search_product'])));
		
		$sql = "SELECT * FROM ".PREFIX.$tableName." where product_name like '%".$search_product."%' or product_code like '%".$search_product."%' $OrderBy LIMIT ".$paginationArr['start'].", ".$paginationArr['limit']."";
	
	}else{
		$sql = "SELECT * FROM ".PREFIX.$tableName.$OrderBy;
	}
	
	
	//echo $sql; exit;
	$results = $admin->query($sql);

	$bannerDetails = $admin->GetBannerDataByID("CORPORATE");
	if(isset($_POST['updateBanner'])){
		$admin->updateBannerBYType($_POST,$_FILES);
		header('Location:'.$pageURL.'?Bsuccess');
		exit;
	}

	if(isset($_POST['csv_register'])) {
		include_once 'import-product-inc.php';
		exit();
	}
	
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
	<link href="css/fixedHeader.dataTables.min.css" rel="stylesheet">
	<!--<link href="css/nanoscroller.css" rel="stylesheet">
	<link href="css/cover.css" rel="stylesheet">-->
	<link href="css/cover.css" rel="stylesheet">
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
			
			
			$("#form2").validate({
				
				rules: {
					banner_image: {
					<?php if(isset($ProductbannerDetails['image_name'])){if(empty($ProductbannerDetails['image_name'])){	?>
						required:true,
						
					<?php }}else{ ?>
						required:true,
					<?php } ?>
					extension: 'jpg|jpeg|png'
					},
				}
			});
			$("#product-form").validate({
				ignore: [],
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
					<?php if(isset($bannerDetails['image_name'])){if(empty($bannerDetails['image_name'])){	?>
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
	
	<style>
	.totalsize{
		font-size: 15px;
	}
	</style>
	
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
			<?php /* 
			<form role="form" action="" method="post" id="formValid" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6 class="panel-title"><i class="icon-library"></i>Offer Banner Image</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Image <em style="color:red;">*</em></label>
									<input type="file" class="form-control" <?php if(isset($bannerDetails['image_name'])){if(empty($bannerDetails['image_name'])){ echo "required"; } }else{ echo "required"; }?> name="banner_image" id="1" data-image-index="0" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>jpg|jpeg|png</strong>.<br>
										Images must be exactly <strong>1366 x 174</strong> pixels.
									</span>
									
								</div>
								<div class="col-sm-4"><br>
									<?php if(isset($bannerDetails['image_name'])) {
										$file_name = str_replace('', '-', strtolower( pathinfo($bannerDetails['image_name'], PATHINFO_FILENAME)));
										$ext = pathinfo($bannerDetails['image_name'], PATHINFO_EXTENSION);
									?>
										<img src="../images/web_banner/<?php echo $file_name.'_crop.'.$ext ?>" width="200" />
									<?php
									} ?>
								</div>
								<input type="hidden" name="banner_type" value="CORPORATE">
								<div class="col-sm-10">
									<button type="submit" name="updateBanner" class="btn btn-warning"><i class="icon-pencil"></i>Update Banner</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>	
			
			<form role="form" action="" method="post" id="form2" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6 class="panel-title"><i class="icon-library"></i>Product Banner Image</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Image <em style="color:red;">*</em></label>
									<input type="file" class="form-control" <?php if(isset($ProductbannerDetails['image_name'])){if(empty($ProductbannerDetails['image_name'])){ echo "required"; } }else{ echo "required"; }?> name="banner_image" id="1" data-image-index="0" />
									<span class="help-text">
										Files must be less than <strong>2 MB</strong>.<br>
										Allowed file types: <strong>jpg|jpeg|png</strong>.<br>
										Images must be exactly <strong>1366 x 205</strong> pixels.
									</span>
									
								</div>
								<div class="col-sm-4"><br>
									<?php if(isset($ProductbannerDetails['image_name'])) {
										$file_name = str_replace('', '-', strtolower( pathinfo($ProductbannerDetails['image_name'], PATHINFO_FILENAME)));
										$ext = pathinfo($ProductbannerDetails['image_name'], PATHINFO_EXTENSION);
									?>
										<img src="../images/web_banner/<?php echo $file_name.'_crop.'.$ext ?>" width="200" />
									<?php
									} ?>
								</div>
								<input type="hidden" name="banner_type" value="P">
								<div class="col-sm-10">
									<button type="submit" name="updateBannerproduct" class="btn btn-warning"><i class="icon-pencil"></i>Update Banner</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form> */ ?>
			<div class="panel panel-default">

								<div class="panel-heading">

									<h6 class="panel-title"><i class="icon-database2"></i>Insert Products<em>*</em></h6>

								</div>

								<div class="panel-body">

									<form role="form" action="" method="post" id="user-form" enctype="multipart/form-data">

										<div class="col-sm-4">

											<input type="file" class="form-control" name="csv_upload"  required />

											<span class="help-block">Upload file with .csv extension</span>

											<a href="javascript:;" id="expUser" class="label label-primary ">Sample Export Inverter</a>

										</div>

										<div class="col-sm-2">

											<button type="submit" name="csv_register" class="btn btn-danger"><i class="icon-signup"></i>Upload CSV</button>

										</div>	

									</form>	

									

								</div>

							</div><br>
			<a href="<?php echo $addURL; ?>" class="label label-primary"  id="#" >Add <?php echo $pageName; ?></a><br/><br/>
		
			
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
	<?php
		if(isset($_GET['codealreadyexist'])){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i> <strong>Product Code Already Exist in csv.
			</div><br/>
	<?php	} ?>
	<?php
		if(isset($_GET['OnlyCsv'])){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i> <strong>Upload only csv file.
			</div><br/>
	<?php	} ?>
	<?php
		if(isset($_GET['StatusUpdateSucess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i> <strong>Status updated.</strong> successfully.
			</div><br/>
	<?php	} ?>
	<?php
		if(isset($_GET['success'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i> <strong>Csv Product successfully added.
			</div><br/>
	<?php	} ?>

			<br/>
			
			<div class="panel panel-default">
				<div class="datatable-selectable-data">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Product Image</th>
								<th>Product Name</th>
								<th>Product Code</th>
								<th>Active</th>
								<th>Created</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
<?php
						//$x = 1;
						$x = (100*$pageNo)-99;
						while($row = $admin->fetch($results)){
							// $vendorDetails = $admin->getVendorDetailsByvendorId($row['vendor_id']);
							
							// if(!empty($vendorDetails['id'])){
							// 	$vendor = $vendorDetails['first_name'];
							// }else{
							// 	$vendor = "OnlineDentalProduct";
							// }

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
								<td>
									<a href="<?php echo $addURL; ?>?edit&id=<?php echo $row['id'] ?>" name="edit" class="" title="Click to edit this row"><i class="icon-pencil"></i></a>
									
									
									<a class="" href="<?php echo $deleteURL; ?>?delid=<?php echo $row['id']; ?>&editedby=<?php echo $loggedInUserDetailsArr['id']; ?>&search_product=<?php if(isset($_GET['search_product'])){ echo $_GET['search_product']; } ?>" onclick="return confirm('Are you sure you want to delete?');" title="Click to delete this row, this action cannot be undone."><i class="icon-remove3"></i></a>
									
								</td>
							</tr>
<?php
						}
?>
						</tbody>
				  </table>
				</div>
			</div>
			<input type="hidden" name="search_text" id="search_text" value="<?php if(isset($_GET['search_product']) && !empty($_GET['search_product'])){ echo $_GET['search_product']; } ?>" >
			<div class="row">
				<div class="col-md-12 clearfix">
					<nav class="pull-right">
						<?php  //echo $paginationArr['paginationHTML']; ?>
					</nav>
				</div>
			</div>

<?php 	include "include/footer.php"; ?>

		</div>

	</div>

	<link href="css/jquery.dataTables.min.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/dataTables.fixedHeader.min.js"></script>
	<link href="css/crop-image/cropper.min.css" rel="stylesheet">
	<script src="js/crop-image/cropper.min.js"></script>
	<script src="js/crop-image/image-crop-app.js"></script>
	<script>
		$(document).ready(function() {
			$('.datatable-selectable-data table').dataTable({
				"order": [[ 0, 'asc' ]],
			});
			$('input[name="banner_image"]').change(function(){
				// loadImageInModal(this);
				loadImagePreview(this, (1366 / 174));
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
				window.open("export-products.php?success&ids="+searchIDs.get()+"&search_text=<?php if(isset($_GET['search_product']) && !empty($_GET['search_product'])){ echo $_GET['search_product']; } ?>");
			});
		});
		$("#expUser").on("click",function(){
			window.open("import-product-ex.php?success");
		});
	</script>
	<script>
	 $("#selectall").click(function () {
		 $('input:checkbox').not(this).prop('checked', this.checked);
	 });
	</script>
	<script>
	$(document).ready(function() {
		$("#EntryProductname").on("click",function(){
			<?php if(!isset($_GET['EntryProductnameASC'])){ ?>
				window.location="index.php?EntryProductnameASC&search&search_product=<?php if(isset($_GET['search_product']) && !empty($_GET['search_product'])){ echo $_GET['search_product']; } ?>";
			<?php }else{ ?>
				window.location="index.php?EntryProductnameDESC&search&search_product=<?php if(isset($_GET['search_product']) && !empty($_GET['search_product'])){ echo $_GET['search_product']; } ?>";
			<?php } ?>
		});
		$("#ProductSort").on("click",function(){
			<?php if(!isset($_GET['ProductSortASC'])){ ?>
				window.location="index.php?ProductSortASC&search&search_product=<?php if(isset($_GET['search_product']) && !empty($_GET['search_product'])){ echo $_GET['search_product']; } ?>";
			<?php }else{ ?>
				window.location="index.php?ProductSortDESC&search&search_product=<?php if(isset($_GET['search_product']) && !empty($_GET['search_product'])){ echo $_GET['search_product']; } ?>";
			<?php } ?>		
		});
		$("#ProductCat").on("click",function(){
			<?php if(!isset($_GET['ProductCatASC'])){ ?>
				window.location="index.php?ProductCatASC&search&search_product=<?php if(isset($_GET['search_product']) && !empty($_GET['search_product'])){ echo $_GET['search_product']; } ?>";
			<?php }else{ ?>
				window.location="index.php?ProductCatDESC&search&search_product=<?php if(isset($_GET['search_product']) && !empty($_GET['search_product'])){ echo $_GET['search_product']; } ?>";
			<?php } ?>		
		});
		$("#EntryDateSortcreated").on("click",function(){
			<?php if(!isset($_GET['EntryDateSortcreatedASC'])){ ?>
				window.location="index.php?EntryDateSortcreatedASC&search&search_product=<?php if(isset($_GET['search_product']) && !empty($_GET['search_product'])){ echo $_GET['search_product']; } ?>";
			<?php }else{ ?>
				window.location="index.php?EntryDateSortcreatedDESC&search&search_product=<?php if(isset($_GET['search_product']) && !empty($_GET['search_product'])){ echo $_GET['search_product']; } ?>";
			<?php } ?>		
		});
	});
</script>
</body>
</html>