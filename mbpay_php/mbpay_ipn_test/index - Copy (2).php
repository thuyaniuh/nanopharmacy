<?php
require_once(ABSPATH."mbpay_php/config.php");  
require_once(ABSPATH.'api/Wallet.php' );
require_once(ABSPATH.'api/Sale.php' );
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
$msg = $appSession->getTier()->createMessage();

$mac = $_GET['mac'];
$mac_type = $_GET['mac_type'];

$inputData = array("error_code" => "00","pg_amount" => "105750.00","pg_card_holder_name" => "AUTOMATION TEST QDI","pg_card_number" => "08******071","pg_currency" => "VND","pg_issuer_code" => "970422","pg_issuer_response_code" => "00","pg_issuer_txn_reference" => "FT24169037420798","pg_merchant_id" => "103155","pg_order_info" => "HD000068","pg_order_reference" => "00006820240617160529","pg_payment_channel" => "qr","pg_payment_method" => "324","pg_paytime" => "20240617160621","pg_transaction_number" => "241691606216518","session_id" => "71aabd7d0b519c49eada28c82441f0ed","mac_type" => "SHA256","mac"  => "B69D7CB5B9B58C46918F9E645FEA98382ADE01D39401F1784D0BFA9404130F8C");
$returnData = array();
$startTime = date("YmdHis");
$session_id = $startTime;
try {
	
	

	$mac = $inputData['mac'];
	$mac_type = $inputData['mac_type'];
	$session_id = $inputData['session_id'];
	
	unset($inputData['mac']);
	unset($inputData['mac_type']);
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
	
	
	if($mac_type == "SHA256")
	{
		 $secureHash = strtoupper(hash('sha256', $hashSecret.$hashData));
	}else{
		$secureHash = strtoupper(md5($hashSecret.$hashData));
	}
	
	if($inputData["error_code"] == "00")
	{
		
		if ($secureHash == $mac)
		{
			$sql = "SELECT id, amount, create_uid, company_id, line_id FROM account_payment_line_local WHERE rel_id ='".$session_id."' AND status =2";
			$msg->add("query", $sql);
			
			$dt_payment = $appSession->getTier()->getTable($msg);
			if($dt_payment->getRowCount()>0)
			{
				$user_id = $dt_payment->getString(0, "create_uid");
				$company_id = $dt_payment->getString(0, "company_id");
				$order_id = $dt_payment->getString(0, "line_id");
				
				$payment_id = $dt_payment->getString(0, "id");
				$payment_amount = $dt_payment->getFloat(0, "amount");
				$res_amount = 0;
				if(array_key_exists("pg_amount", $inputData))
				{
					$res_amount = $appSession->getTool()->toDouble($inputData["pg_amount"]);
					if($payment_amount == $res_amount){
						$returnData['error_code'] = '00';
						$returnData['txn_amount'] = $res_amount;
						$returnData['desc'] = "DROH - MB PAYGATE - Hoan Tien";
						$returnData['access_code'] = $access_code;
						$returnData['merchant_id'] = $merchant_id;
						$returnData['transaction_reference_id'] = $inputData["pg_order_reference"];
						$returnData['trans_date'] = $inputData["pg_paytime"];
						
						$sql = "UPDATE account_payment_line_local SET status =0, rel_id='".$order_id."' WHERE id ='".$payment_id."'";
						$sql = $sql.";UPDATE sale_local SET status =3 WHERE id='".$order_id."'";
						
						$msg->add("query", $sql);
						$appSession->getTier()->exec($msg);
						
					}else if($res_amount<$payment_amount)
					{
						$returnData['error_code'] = '00';
						$returnData['txn_amount'] = $res_amount;
						$returnData['desc'] = "DROH - MB PAYGATE - Hoan Tien";
						$returnData['access_code'] = $access_code;
						$returnData['merchant_id'] = $merchant_id;
						$returnData['transaction_reference_id'] = $inputData["pg_order_reference"];
						$returnData['trans_date'] = $inputData["pg_paytime"];
						
						$sql = "UPDATE account_payment_line_local SET status =0, amount=".$res_amount." WHERE id ='".$payment_id."'";
						$msg->add("query", $sql);
						$appSession->getTier()->exec($msg);
						
						$builder = $appSession->getTier()->createBuilder("account_payment_line_local");
						$builder->add("id", $appSession->getTool()->getId());
						$builder->add("create_uid", $user_id);
						$builder->add("write_uid", $user_id);
						$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
						$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
						$builder->add("line_id", $order_id);
						$builder->add("parent_id", $payment_id);
						$builder->add("payment_id", 'e182748a-ba3c-4c24-afc2-853c9a6a0451');
						$builder->add("currency_id", "23");
						$builder->add("rel_id", $session_id);
						$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "account_payment_line_local"));
						$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
						$builder->add("amount", $payment_amount-$res_amount);
						$builder->add("status", 2);
						$builder->add("description", $vnp_TxnRef);
						$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
						
						$sql = $appSession->getTier()->getInsert($builder);
						$msg->add("query", $sql);
						$appSession->getTier()->exec($msg);
						
						
					}else if($res_amount>$payment_amount){
						$returnData['error_code'] = '08';
						$returnData['txn_amount'] = $res_amount;
						$returnData['desc'] = "DROH - MB PAYGATE - Hoan Tien";
						$returnData['access_code'] = $access_code;
						$returnData['merchant_id'] = $merchant_id;
						$returnData['transaction_reference_id'] = $inputData["pg_order_reference"];
						$returnData['trans_date'] = $inputData["pg_paytime"];
					}
					
					
				}else{
					$returnData['error_code'] = '91';
					$returnData['message'] = 'Thông tin giao dịch không hợp lệ';
					$cache_file = ABSPATH."log/mbpay/ipn_error_".$session_id.".txt";
					$encodedString = json_encode($inputData);
					file_put_contents($cache_file, $encodedString);
					
				}
				
			}else{
				$returnData['error_code'] = '91';
				$returnData['message'] = 'Không tìm thấy giao dịch';
				$cache_file = ABSPATH."log/mbpay/ipn_error_".$session_id.".txt";
				$encodedString = json_encode($inputData);
				file_put_contents($cache_file, $encodedString);
			}
			
		}else{
			$returnData['error_code'] = '91';
			$returnData['message'] = 'Invalid Checksum';
			$cache_file = ABSPATH."log/mbpay/ipn_error_".$session_id.".txt";
			$encodedString = json_encode($inputData);
			file_put_contents($cache_file, $encodedString);
		}
	}else{
		
		$returnData['error_code'] = '91';
		$returnData['message'] = 'Giao dịch không hợp lệ';
		$cache_file = ABSPATH."log/mbpay/ipn_error_".$session_id.".txt";
		$encodedString = json_encode($inputData);
		file_put_contents($cache_file, $encodedString);
	}
	
} catch (Exception $e) {
    $returnData['error_code'] = '99';
    $returnData['message'] = 'Unknow error';
	$cache_file = ABSPATH."log/mbpay/ipn_error_".$session_id.".txt";
	$encodedString = json_encode($inputData);
	file_put_contents($cache_file, $encodedString);
}
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
if(isset($hashSecret)){
	$mac = strtoupper(md5($hashSecret.$hashdata));
	$returnData["mac_type"] = "MD5";
	$returnData["mac"] = $mac;
}
echo json_encode($returnData);
?>