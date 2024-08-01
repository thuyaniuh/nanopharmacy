<?php
require_once(ABSPATH."mbpay_php/config.php");  
require_once(ABSPATH.'api/Wallet.php' );
require_once(ABSPATH.'api/Sale.php' );

$transaction_reference_id ="241731010482862";
$trans_date ="20240621093952";
$txn_amount = "400000";
$desc = "HOAN TIEN DON HD000075";
if(isset($_GET['transaction_reference_id']))
{
	$transaction_reference_id = $_GET['transaction_reference_id'];
}
if(isset($_GET['trans_date']))
{
	$trans_date = $_GET['trans_date'];
}
if(isset($_GET['txn_amount']))
{
	$txn_amount = $_GET['txn_amount'];
}
if(isset($_GET['transaction_reference_id']))
{
	$desc = $_GET['desc'];
}



$startTime = date("YmdHis");
try {
	$inputData = array();
	$inputData["transaction_reference_id"] = $transaction_reference_id;
	$inputData["trans_date"] = $trans_date;
	$inputData["txn_amount"] = $txn_amount;
	$inputData["desc"] = $desc;
	$inputData["access_code"] = $access_code;
	$inputData["merchant_id"] = $merchant_id;
	ksort($inputData);
	$i = 0;
	$hashData = "";
	foreach ($inputData as $key => $value) {
		if($value == "")
		{
			continue;
		}
		if($hashData != "")
		{
			$hashData = $hashData."&";
		}
		$hashData .= $key . "=" .$value;
	}
	$mac = strtoupper(md5($hashSecret.$hashData));
	$inputData["mac_type"] = "MD5";
	$inputData["mac"] = $mac;
	$payload = json_encode($inputData);
	echo $payload;
	
	$ch = curl_init($url_refund);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


	$result = curl_exec($ch);

	curl_close($ch);

	$json = json_decode($result);
	$session_id = $startTime;
	if($json->error_code == "00" && $json->message == "Success")
	{
		$session_id = $json->refund_reference_id;
	}
	$cache_file = ABSPATH."log/mbpay/refund_".$session_id.".txt";
	file_put_contents($cache_file, $result);
	echo $result;

} catch (Exception $e) {
    
}
?>