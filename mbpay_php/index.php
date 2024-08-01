<?php
require_once(ABSPATH."mbpay_php/config.php");  
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
$msg = $appSession->getTier()->createMessage();

$payment_method = "QR";
$startTime = date("YmdHis");
$order_reference = "V1QR567785451214".$startTime;
if(isset($_REQUEST['order_no']))
{
	$order_reference = $_REQUEST['order_no'].$startTime;
}

$order_info = "";
if(isset($_REQUEST['order_desc']))
{
	$order_info = $_REQUEST['order_desc'];
}


$order_id = "";
if(isset($_REQUEST['order_id']))
{
	$order_id = $_REQUEST['order_id'];
}
$payment_type = "";
if(isset($_REQUEST['payment_type']))
{
	$payment_type = $_REQUEST['payment_type'];
}

$amount = "";
if(isset($_REQUEST['amount']))
{
	$amount = $_REQUEST['amount'];
}

$redirect = "";
if(isset($_REQUEST['redirect']))
{
	$redirect = $_REQUEST['redirect'];
}

if(isset($_REQUEST['payment_method']))
{
	$payment_method = $_REQUEST['payment_method'];
}
$merchant_user_reference = "";
if(isset($_REQUEST['user']))
{
	$merchant_user_reference = $_REQUEST['user'];
}

$ip_address = $_SERVER['REMOTE_ADDR'];

//$order_info = "MA TT EC372855";
//$order_reference = "V1QR56778545121441";


$inputData = array(
	"amount" => $amount,
	"currency" => "VND",
	"access_code" => $access_code,
	"merchant_id" => $merchant_id,
	"order_info" => $order_info,
	"order_reference" => $order_reference,
	"device" => "",
	"return_url" => $return_url,
	"cancel_url" => $cancel_url,
	"ipn_url" => $ipn_url,
	"pay_type" => "pay",
	"merchant_user_reference" => $merchant_user_reference,
	"token_issuer_code" => "",
	"token" => "",
	"ip_address" => $ip_address,
	"payment_method" => $payment_method
);


ksort($inputData);
$i = 0;
$hashdata = "";
foreach ($inputData as $key => $value) {
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
if(isset($hashSecret)){
	$mac = strtoupper(md5($hashSecret.$hashdata));
	$inputData["mac_type"] = "MD5";
	$inputData["mac"] = $mac;
}

$payload = json_encode($inputData);


$ch = curl_init($url_create);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


$result = curl_exec($ch);

curl_close($ch);

$json = json_decode($result);
if($json->error_code == "00" && $json->message == "Success")
{
	$returnData = array('code' => '00'
    , 'message' => 'success'
    , 'data' => $json->payment_url);
	$session_id = $json->session_id;
	
	$cache_file = ABSPATH."log/mbpay/create_".$session_id.".txt";
	$encodedString = json_encode($inputData);
	file_put_contents($cache_file, $result);
	
	
	if($payment_type == "payment")
	{
		$user_id = $appSession->getConfig()->getProperty("user_id");
		$sql = "SELECT id FROM account_payment_line_local WHERE status =2 AND line_id='".$order_id."' AND amount='".$amount."' AND payment_id='e182748a-ba3c-4c24-afc2-853c9a6a0451'";
		$msg->add("query", $sql);
		$payment_id = $appSession->getTier()->getValue($msg);
		if($payment_id == "")
		{
			$builder = $appSession->getTier()->createBuilder("account_payment_line_local");
			$builder->add("id", $appSession->getTool()->getId());
			$builder->add("create_uid", $user_id);
			$builder->add("write_uid", $user_id);
			$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
			$builder->add("line_id", $order_id);
			$builder->add("payment_id", 'e182748a-ba3c-4c24-afc2-853c9a6a0451');
			$builder->add("currency_id", "23");
			$builder->add("rel_id", $session_id);
			$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "account_payment_line_local"));
			$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
			$builder->add("amount", $amount);
			$builder->add("status", 2);
			$builder->add("description", $vnp_TxnRef);
			$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
			
			$sql = $appSession->getTier()->getInsert($builder);
			$msg->add("query", $sql);
			$appSession->getTier()->exec($msg);
		}else{
			$sql = "UPDATE account_payment_line_local SET rel_id='".$session_id."' WHERE id='".$payment_id."'";
			$msg->add("query", $sql);
			$appSession->getTier()->exec($msg);
			
		}
	}
	
		
	if ($redirect == "1") {
		 header('Location: ' .$json->payment_url);
        die();
    } else {
        echo json_encode($returnData);
    }
	
}else{
	echo $result;
}

	

    
