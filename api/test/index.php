<?php
//require_once(ABSPATH.'api/Wallet.php' );
$phone = "0909525850";
$code = "3900";
$r = $appSession->getTool()->send_sms($phone, "Quy khach dang thuc hien thiet lap mat khau VfSCFood. Khong gui mat ma nay cho bat ky ai de tranh rui ro. MA OTP ".$code);
//$sale = new Sale($appSession);
//echo $sale->checkSaleService("55fe13f5-311b-4489-a1f9-9ba643f25702", "1df4cc71-7f28-4254-c9b1-77b4a06b3f65");
/*	$wallet = new Wallet($appSession);
	$wallet_id = "a8b78204-cd7c-4232-d856-95098faeb1f4";
	$customer_id = "bc58bc31-baf7-4bec-ad89-a16282beebf0"; 
	$holder_id = "45028a4d-381a-4cee-af9e-62c15caaae29";
	$amount = 300000 + (300000 * 0.05);
	$receipt_no = "";
	$description = "NAP TIEN VAO VI";
	
	$wallet->add($wallet_id, $customer_id, $holder_id, $amount, $receipt_no, $description);
	echo "Done";*/
?>