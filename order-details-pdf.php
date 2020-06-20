<?php
	if(isset($_GET['txnId']) && !empty($_GET['txnId'])){


		include_once 'include/functions.php';
    	$functions = new Functions();
		

		if(!$loggedInUserDetailsArr = $functions->sessionExists()){
			echo "Your session has expired, please login...";
			exit;
		}
		$txnId = $functions->escape_string($functions->strip_all($_GET['txnId']));
		if(isset($_GET['vendor_id']) && !empty($_GET['vendor_id'])){
			$userId = $_GET['vendor_id'];
			$purchaseDetails = $functions->getVendorProductOrderDetails($userId, $txnId);
		}else{
		
			$purchaseDetails = $functions->getProductOrderDetails($loggedInUserDetailsArr['id'], $txnId);
		}
		if($purchaseDetails){
			$order = $purchaseDetails['order'];
			if($order['payment_status']=="Payment Failed"){
				echo "No invoice exists with that invoice number";
				exit;
			}
			$orderDetails = $purchaseDetails['orderDetails'];
			$customerDetails = $functions->getUniqueRegisteredUserById($order['customer_id']);
		} else {
			// invoice with that txn_id for that customer was NOT found
			echo "No invoice exists with that invoice number";
			exit;
		}
	} else if(isset($_GET['adminTxnId']) && !empty($_GET['adminTxnId'])){
		include_once 'ds-dashboard/include/config.php';
		include_once 'ds-dashboard/include/admin-functions.php';
		$admin = new AdminFunctions();
		if(!$loggedInUserDetailsArr = $admin->sessionExists()){
			echo "Your session has expired, please login...";
			exit;
		}

		$txnId = $admin->escape_string($admin->strip_all($_GET['adminTxnId']));
		$purchaseDetails = $admin->getProductOrderDetails($txnId);
		if($purchaseDetails){
			$order = $purchaseDetails['order'];
			if($order['payment_status']!="Payment Complete"){
				echo "No invoice exists with that invoice number";
				exit;
			}
			$orderDetails = $purchaseDetails['orderDetails'];
			$customerDetails = $admin->getUniqueRegisteredUserById($order['customer_id']);
		} else {
			// invoice with that txn_id for that customer was NOT found
			echo "No invoice exists with that invoice number";
			exit;
		}
	} else {
		echo "No invoice exists with that invoice number";
		exit;
	}


	// include invoice
	include_once("include/cart/invoice-pdf.inc.php"); // $invoiceMsg
	// == TEST ==
		// echo $invoiceMsg; // TEST
		// exit;
	// == TEST ==

	// == GENERATE PDF FILE ==
	include "include/classes/pdf-to-html/tcpdf.php";

	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	// $pdf->SetCreator(PDF_CREATOR);
	$pdf->SetTitle('Invoice for '.$order['txn_id']);
	$pdf->SetAuthor(SITE_NAME);
	$pdf->SetSubject('Invoice No. '.$order['txn_id'].' for Order No. '.$order['txn_id'].' from '.SITE_NAME);
	// $pdf->SetKeywords('MAC, PDF, example, test, guide');

	// set default header data
	// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
	$pdf->setFooterData(array(0,64,0), array(0,64,128));
	
	// remove default header/footer
	$pdf->setPrintHeader(false);

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$pdf->setImageScale(1.9);

	// set some language-dependent strings (optional)
	/* if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	} */

	// ---------------------------------------------------------

	// set default font subsetting mode
	$pdf->setFontSubsetting(true);

	// Set font
	// dejavusans is a UTF-8 Unicode font, if you only need to
	// print standard ASCII chars, you can use core fonts like
	// helvetica or times to reduce file size.
	$pdf->SetFont('dejavusans', '', 14, '', true);

	// Add a page
	// This method has several options, check the source code documentation for more information.
	$pdf->AddPage();

	// set text shadow effect
	$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.1, 'depth_h'=>0.1, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

	// Set some content to print
	$html = $invoiceMsg;

	// Print text using writeHTMLCell()
	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

	// ---------------------------------------------------------

	// Close and output PDF document
	// This method has several options, check the source code documentation for more information.
	$pdf->Output($order['txn_id'].'.pdf', 'I');

	//============================================================+
	// END OF FILE
	//============================================================+
	// == GENERATE PDF FILE ==
?>
