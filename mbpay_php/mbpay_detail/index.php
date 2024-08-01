<?php
require_once(ABSPATH."mbpay_php/config.php");  
require_once(ABSPATH.'api/Wallet.php' );
require_once(ABSPATH.'api/Sale.php' );

$order_reference ="000077202406211050071";
$pg_transaction_reference ="241731050427646";
$pay_date = "21062024";

if(isset($_GET['order_reference']))
{
	$order_reference = $_GET['order_reference'];
}
if(isset($_GET['pg_transaction_reference']))
{
	$pg_transaction_reference = $_GET['pg_transaction_reference'];
}
if(isset($_GET['pay_date']))
{
	$pay_date = $_GET['pay_date'];
}



$startTime = date("YmdHis");
try {
	$inputData = array();
	$inputData["order_reference"] = $order_reference;
	$inputData["pg_transaction_reference"] = $pg_transaction_reference;
	$inputData["pay_date"] = $pay_date;
	$inputData["merchant_id"] = $merchant_id;
	$inputData["access_code"] = $access_code;
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
	
	$ch = curl_init($url_detail);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


	$result = curl_exec($ch);

	curl_close($ch);

	$json = json_decode($result);
	$session_id = $startTime;
	
	$cache_file = ABSPATH."log/mbpay/detail_".$session_id.".txt";
	file_put_contents($cache_file, $result);
	echo $result;

} catch (Exception $e) {
    
}
?>