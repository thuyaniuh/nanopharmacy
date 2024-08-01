<?php
require_once(ABSPATH."mbpay_php/config.php");  
require_once(ABSPATH.'api/Wallet.php' );
require_once(ABSPATH.'api/Sale.php' );

$inputData = array();
$returnData = array();
$startTime = date("YmdHis");
try {
	foreach ($_GET as $key => $value) {
		$inputData[$key] = $value;
	}
	$cache_file = ABSPATH."log/mbpay/cancel_".$startTime.".txt";
	$encodedString = json_encode($inputData);
	file_put_contents($cache_file, $encodedString);
	
} catch (Exception $e) {
    $returnData['RspCode'] = '99';
    $returnData['Message'] = 'Unknow error';
}
echo json_encode($returnData);
?>