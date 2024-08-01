<?php
require_once(ABSPATH."mbpay_php/config.php");  
require_once(ABSPATH.'api/Wallet.php' );
require_once(ABSPATH.'api/Sale.php' );
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
$msg = $appSession->getTier()->createMessage();



$inputData = array();
$returnData = array();
$startTime = date("YmdHis");
$session_id = $startTime;

	
ksort($returnData);
$i = 0;
$hashdata = "";
foreach ($returnData as $key => $value) {
	if($value == "")
	{
		continue;
	}
	if($hashdata != "")
	{
		$hashdata = $hashdata."&";
	}
	$hashdata .= $key . "=" .$value;
	
}

$returnData['txn_amount'] = 1575000.00;
$returnData['desc'] = "DROH - MB PAYGATE - Hoan Tien";
$returnData['access_code'] = $access_code;
$returnData['merchant_id'] = $merchant_id;
$returnData['transaction_reference_id'] = "00007020240617164701";
$returnData['trans_date'] = "20240617164826";
						
if(isset($hashSecret)){
	$mac = strtoupper(md5($hashSecret.$hashdata));
	$returnData["mac_type"] = "MD5";
	$returnData["mac"] = $mac;
	echo json_encode($returnData);
}

?>