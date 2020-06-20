<?php
// Get Fedex Pricing With the Adress Changes

$query ="SELECT * FROM as_order WHERE is_deleted='0' and (`payment_status`='Payment Complete' or (`payment_status`='Payment Pending' and payment_mode='COD')) order by id ASC";
$result = $functions->query($query);
?>