<?php
validUser($appSession);
$msg = $appSession->getTier()->createMessage();
$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
if($ac == "")
{
	$ac = "view";
}
if($ac == "delSale"){
	
	$msg = $appSession->getTier()->createMessage();
	$id = '';
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$items = explode(",", $id);
	for($i =0; $i<count($items); $i++)
	{
		$id = $items[$i];
		$sql = "UPDATE sale_local SET status =1";
		$sql = $sql.", write_date=NOW()";
		$sql = $sql." WHERE id='".$id."'";
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
		$sql = "UPDATE wallet SET status =1";
		$sql = $sql.", write_date=NOW()";
		$sql = $sql." WHERE rel_id='".$id."'";
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
		$sql = "SELECT amount,description, receipt_no FROM account_payment_line_local WHERE rel_id='".$id."' AND payment_id='e182748a-ba3c-4c24-afc2-853c9a6a0451'";
		
		$msg->add("query", $sql);
		$paymentList = $appSession->getTier()->getArray($msg);
		for($j =0; $j<count($paymentList); $j++)
		{
			$vnp_TmnCode = "FOSACHA1"; //Website ID in VNPAY System
			$vnp_HashSecret = "MTZOVWLBOADQNULTUBTMCXCNYDGEXOTT"; //Secret key
			$vnp_apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
			$vnp_TxnRef = $paymentList[$j][1];
			echo $vnp_TxnRef;
			$description = $paymentList[$j][2];
			$amount = ($paymentList[$j][0]) * 100;
				$ipaddr = $_SERVER['REMOTE_ADDR'];
				$inputData = array(
					"vnp_Version" => '2.1.0',
					"vnp_TransactionType" => '02',
					"vnp_Command" => "refund",
					"vnp_CreateBy" => 'info@vifotec.com',
					"vnp_TmnCode" => $vnp_TmnCode,
					"vnp_TxnRef" => $vnp_TxnRef,
					"vnp_Amount" => $amount,
					"vnp_OrderInfo" => $description,
					"vnp_TransDate" => date("YmdHis"),
					"vnp_CreateDate" => date('YmdHis'),
					"vnp_IpAddr" => $ipaddr
				);
				ksort($inputData);
				$query = "";
				$i = 0;
				$hashdata = "";
				foreach ($inputData as $key => $value) {
					if ($i == 1) {
						$hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
					} else {
						$hashdata .= urlencode($key) . "=" . urlencode($value);
						$i = 1;
					}
					$query .= urlencode($key) . "=" . urlencode($value) . '&';
				}

				$vnp_apiUrl = $vnp_apiUrl . "?" . $query;
				if (isset($vnp_HashSecret)) {
					$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
					$vnp_apiUrl .= 'vnp_SecureHash=' . $vnpSecureHash;
				}
				$ch = curl_init($vnp_apiUrl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				$data = curl_exec($ch);
				curl_close($ch);
				
		}
		$sql = "SELECT rel_id FROM account_payment_line_local WHERE (line_id='".$id."' OR rel_id ='".$id."')";
		
		$msg->add("query", $sql);
		$paymentList = $appSession->getTier()->getArray($msg);
		for($j =0; $j<count($paymentList); $j++)
		{
			$rel_id = $paymentList[$j][0];
			$sql = "UPDATE wallet SET status =1, write_date=NOW() WHERE id ='".$rel_id."'";
			$msg->add("query", $sql);
			$status = $appSession->getTier()->exec($msg);
		}
	}
	echo "OK";
}
?>