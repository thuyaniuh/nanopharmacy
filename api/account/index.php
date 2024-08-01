<?php
require_once(ABSPATH.'api/Customer.php' );
require_once(ABSPATH.'api/Company.php' );
require_once(ABSPATH.'app/barcode/autoload.php' );
require_once(ABSPATH.'app/phpqrcode/qrlib.php' );
require_once(ABSPATH.'api/Sale.php' );

function respTable($dt) 
{

	for($i =0; $i <count($dt->getColumns()); $i++)
	{
		if($i>0)
		{
			echo "\t";
		}
		echo $dt->getColumns()[$i]->getName($i);
	}
	for($r =0; $r<$dt->getRowCount(); $r++)
	{
		echo "\n";
		for($i =0; $i <count($dt->getColumns()); $i++)
		{
			if($i>0)
			{
				echo "\t";
			}
			echo $dt->getStringAt($r, $i);
		}
	}
	
}

$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
$msg = $appSession->getTier()->createMessage();

if($ac == "register_account"){
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$phone = "";
	if(isset($_REQUEST['phone']))
	{
		$phone = $_REQUEST['phone'];
	}
	$email = "";
	if(isset($_REQUEST['email']))
	{
		$email = $_REQUEST['email'];
	}
	$address = "";
	if(isset($_REQUEST['address']))
	{
		$address = $_REQUEST['address'];
	}
	$address_id = "";
	if(isset($_REQUEST['address_id']))
	{
		$address_id = $_REQUEST['address_id'];
	}
	$user_name = "";
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
	}
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id == "")
	{
		$lang_id = "vi";
	}
	$sale_code = "";
	if(isset($_REQUEST['sale_code']))
	{
		$sale_code = $_REQUEST['sale_code'];
	}
	$source = "";
	if(isset($_REQUEST['source']))
	{
		$source = $_REQUEST['source'];
	}
	$gender = "";
	if(isset($_REQUEST['gender']))
	{
		$gender = $_REQUEST['gender'];
	}
	$birthday = "";
	if(isset($_REQUEST['birthday']))
	{
		$birthday = $_REQUEST['birthday'];
	}
	
	$parent_partner_id = "";
	if($sale_code != "")
	{
		$msg = $appSession->getTier()->createMessage();
		$sql = "SELECT id FROM res_partner WHERE code='".$appSession->getTool()->replace($sale_code, "'", "''")."' AND status =0";
		
		$msg->add("query", $sql);
		
		$parent_partner_id = $appSession->getTier()->getValue($msg);
		if($parent_partner_id == "")
		{
			$sql = "SELECT d2.partner_id FROM res_user d1 LEFT OUTER JOIN res_user_company d2 ON(d1.id = d2.user_id) LEFT OUTER JOIN customer d3 ON(d2.rel_id = d3.id) WHERE d1.status =0 AND d2.status =0 AND d1.phone='".$sale_code."'";
			$parent_partner_id = $appSession->getTier()->getValue($msg);
		}
		if($parent_partner_id == "")
		{
			echo "INVALID_SALE_CODE";
			exit();
		}
	}else{
		$parent_partner_id = "";
	}
	$customer = new Customer();
	$user_id = $customer->createWithPartner($appSession, $name, $phone, $email , $user_name, $password, $lang_id, $address, $address_id, $parent_partner_id);
	
	$company_id = $appSession->getConfig()->getProperty("company_id");
	if(strlen($user_id) == 36)
	{
		$sql = "UPDATE customer SET gender='".$gender."'";
		if($birthday != "")
		{
			$sql = $sql.", birthday = '".$birthday."'";
		}
		$sql = $sql." WHERE id='".$user_id."'";
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		$customer->createHolder($appSession, $user_id, $company_id, $user_id);
		$id = $appSession->getTool()->getId();
		$code = rand(1000, 9999);
		$sql = "INSERT INTO res_user_verification(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", status";
		$sql = $sql.", create_uid";
		$sql = $sql.", code";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$id."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$user_id."'";
		$sql = $sql.", '".str_replace("'", "''", $code)."'";
		$sql = $sql.")";
		$msg->add("query", $sql);
		$result = $appSession->getTier()->exec($msg);
		
		if($phone != "")
		{
			$r = $appSession->getTool()->send_sms($phone, "Quy khach dang thuc hien thiet lap mat khau VfSCFood. Khong gui mat ma nay cho bat ky ai de tranh rui ro. MA OTP ".$code);
		}else{
			if($email != "")
			{
				$r = $appSession->getTool()->send_mail(SUPPORT_EMAIL, "Fosacha", $email, "Đăng ký", "Kích hoạt tài khoản", "Mã OPT:".$code);
			}
		}
		echo "OK:".$appSession->getTool()->encrypt($id, true);
	
	}else{
		echo $user_id;
	}
	
}else if($ac == "register_account_company")
{
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$commercial_name = "";
	if(isset($_REQUEST['commercial_name']))
	{
		$commercial_name = $_REQUEST['commercial_name'];
	}
	$phone = "";
	if(isset($_REQUEST['phone']))
	{
		$phone = $_REQUEST['phone'];
	}
	$email = "";
	if(isset($_REQUEST['email']))
	{
		$email = $_REQUEST['email'];
	}
	$address_id = "";
	if(isset($_REQUEST['address_id']))
	{
		$address_id = $_REQUEST['address_id'];
	}
	$address = "";
	if(isset($_REQUEST['address']))
	{
		$address = $_REQUEST['address'];
	}
	$contact_name = "";
	if(isset($_REQUEST['contact_name']))
	{
		$contact_name = $_REQUEST['contact_name'];
	}
	$contact_mobile = "";
	if(isset($_REQUEST['contact_mobile']))
	{
		$contact_mobile = $_REQUEST['contact_mobile'];
	}
	$contact_email = "";
	if(isset($_REQUEST['contact_email']))
	{
		$contact_email = $_REQUEST['contact_email'];
	}
	$user_name = "";
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
	}
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id == "")
	{
		$lang_id = "vi";
	}
	$company = new Company();
	$user_id = $company->create($appSession, $name, $commercial_name, $phone, $email , $address_id, $address, $contact_name, $contact_mobile, $contact_email, $user_name, $password, $lang_id);
	$code = rand(1000, 9999);
	$sql = "INSERT INTO res_user_verification(";
	$sql = $sql."id";
	$sql = $sql.", create_date";
	$sql = $sql.", write_date";
	$sql = $sql.", status";
	$sql = $sql.", create_uid";
	$sql = $sql.", code";
	$sql = $sql." )VALUES(";
	$sql = $sql."'".$appSession->getTool()->getId()."'";
	$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
	$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
	$sql = $sql.", 0";
	$sql = $sql.", '".$user_id."'";
	$sql = $sql.", '".str_replace("'", "''", $code)."'";
	$sql = $sql.")";
	$msg->add("query", $sql);
	$result = $appSession->getTier()->exec($msg);
	
	$r = $appSession->getTool()->send_mail(SUPPORT_EMAIL, "Fosacha", $email, "Đăng ký", "Kích hoạt tài khoản", "Mã OPT:".$code);
	echo $user_id;
}else if($ac == "active")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
		$id = $appSession->getTool()->decrypt($id, true);
	}

	
		
	$code = "";
	if(isset($_REQUEST['code']))
	{
		$code = $_REQUEST['code'];
	}
	$sql = "SELECT id, create_uid FROM res_user_verification WHERE code='".str_replace("'", "''", $code)."' AND status =0 AND id='".$id."'";
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	if(count($values) > 0)
	{
		$sql = "UPDATE res_user_verification SET status =1, write_date=".$appSession->getTier()->getDateString()." WHERE id='".$values[0][0]."'";
		$msg->add("query", $sql);
		$result = $appSession->getTier()->exec($msg);
		$user_id = $values[0][1];
		$company = new Company();
		if($company->active($appSession, $user_id) == true)
		{
			echo "OK";
		}
	}else{
		echo "INVALID";
	}
}
else if($ac == "login"){
	$user_name = "";
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
	}
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	
	$customer = new Customer();
	$message = $customer->login($appSession, $user_name, $password);
	if($appSession->getTool()->indexOf($message, "user_id") !=-1)
	{
		$arr = $appSession->getTool()->split($message, ";");
		for($i=0; $i<count($arr); $i++)
		{
			$index = $appSession->getTool()->indexOf($arr[$i], '=');
			if($index != -1)
			{
				$appSession->getConfig()->setProperty($appSession->getTool()->substring($arr[$i], 0, $index), $appSession->getTool()->substring($arr[$i],$index + 1));
			}
		}
		$appSession->getConfig()->save();
		$sale = new Sale($appSession);
		$sale_id = $sale->findSaleId();
		
		$sale->checkSaleService($sale_id, $appSession->getConfig()->getProperty("customer_id"));
	}
	echo $message;
}else if($ac == "login_web")
{
	$user_name = "";
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
	}
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	
	$customer = new Customer();
	$message = $customer->login($appSession, $user_name, $password);
	if($appSession->getTool()->indexOf($message, "user_id") !=-1)
	{
		$arr = $appSession->getTool()->split($message, ";");
		for($i=0; $i<count($arr); $i++)
		{
			$index = $appSession->getTool()->indexOf($arr[$i], '=');
			if($index != -1)
			{
				$appSession->getConfig()->setProperty($appSession->getTool()->substring($arr[$i], 0, $index), $appSession->getTool()->substring($arr[$i],$index + 1));
			}
		}
		$appSession->getConfig()->save();
		$sale = new Sale($appSession);
		$sale_id = $sale->findSaleId();
		$sale->checkSaleService($sale_id, $appSession->getConfig()->getProperty("customer_id"));
		
		$message = "OK";
	}
	echo $message;
}else if($ac == "login_company"){
	$user_name = "";
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
	}
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	
	$company = new Company();
	$message = $company->login($appSession, $user_name, $password);
	echo $message;
}
else if($ac == "logout")
{
	$appSession->getConfig()->setProperty("user_id", "");
	$appSession->getConfig()->setProperty("customer_id", "");
	$appSession->getConfig()->setProperty("customer_category_id", "");
	$appSession->getConfig()->save();
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	$message = $sale->checkSaleService($sale_id, "");
	echo $message;
}
else if($ac == "barcode"){
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$type = "";
	if(isset($_REQUEST['type']))
	{
		$type = $_REQUEST['type'];
	}

	$msg = $appSession->getTier()->createMessage();
	$sql = "SELECT code FROM customer WHERE id='".$customer_id."'";
	
	$msg->add("query", $sql);
	
	$bc = $appSession->getTier()->getValue($msg);
	

	if($bc != "")
	{
		if($type == "qrcode"){
			$file_name = ABSPATH.'disk/'.$customer_id.'qr.png';
			QRcode::png($bc, $file_name, 'L', 4, 2);
		
			header('Content-Type: image/png');
			header('Content-Disposition: attachment; filename=barcode.png');
			header('Content-Length: ' . filesize($file_name));
			flush();
			$download_rate = 20.5;
			$file = fopen($file_name, "r");
			while(!feof($file))
			{
				print fread($file, round($download_rate * 1024));
				
				flush();
				
			}
			fclose($file);
			exit;
			
		}else{
			$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
			$img = $generator->getBarcode($bc, $generator::TYPE_CODE_128, 3, 80);
			$file_name = ABSPATH.'disk/'.$customer_id.'.png';
			
			file_put_contents($file_name, $img);
			header('Content-Type: image/png');
			header('Content-Disposition: attachment; filename=barcode.png');
			header('Content-Length: ' . filesize($file_name));
			flush();
			$download_rate = 20.5;
			$file = fopen($file_name, "r");
			while(!feof($file))
			{
				print fread($file, round($download_rate * 1024));
				
				flush();
				
			}
			fclose($file);
			exit;
			
		}
		
	}
}else if($ac == "wallet"){
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$msg = $appSession->getTier()->createMessage();
	$sql= "SELECT id FROM wallet_holder WHERE rel_id ='".$customer_id."' AND status =0";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount() == 0)
	{
		$code = $appSession->getTool()->findReceiptNo($appSession->getTier(), $company_id, "wallet_holder");
		$code = "VFS".$appSession->getTool()->paddingLeft($code, "0", 6);
			
		$msg->add("query", $sql);
		$dt = $appSession->getTier()->getTable($msg);
		$holder_id = $appSession->getTool()->getId();
		$sql = "INSERT INTO wallet_holder(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", status";
		$sql = $sql.", rel_id";
		$sql = $sql.", code";
		$sql = $sql.", category_id";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$holder_id."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$customer_id."'";
		$sql = $sql.", '".$appSession->getTool()->paddingLeft($code, '0', 6)."'";
		$sql = $sql.", '2056b3bb-97d8-4c3d-ad3a-4a61ce80b143'";
		$sql = $sql.")";
		
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
		$sql = "INSERT INTO wallet_holder_rel(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", status";
		$sql = $sql.", holder_id";
		$sql = $sql.", rel_id";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$appSession->getTool()->getId()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$holder_id."'";
		$sql = $sql.", '".$customer_id."'";
		$sql = $sql.")";
		
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
		$sql = "UPDATE wallet SET holder_id ='".$holder_id."' WHERE customer_id='".$customer_id."' AND (customer_id='' OR customer_id IS NULL)";
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
	}
	
	$sql = "SELECT d2.id, d2.name, d2.sequence, d1.holder_id, SUM(d1.amount * d1.factor) AS amount FROM wallet d1 LEFT OUTER JOIN wallet_category d2 ON(d1.category_id = d2.id) WHERE d1.status =0";
	if($customer_id != "")
	{
		$sql = $sql." AND d1.customer_id='".$customer_id."'";
	}else{
		$sql = $sql." AND 1=0";
	}
	$sql = $sql." GROUP BY d2.id, d2.name, d2.sequence, d1.holder_id";
	$sql = $sql." ORDER BY d2.sequence ASC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
	
}else if($ac == "wallet1"){
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$company_id = "e741709c-6309-4704-a6dc-e339a8b4bf7f";
	$msg = $appSession->getTier()->createMessage();
	$sql= "SELECT id FROM wallet_holder WHERE rel_id ='".$customer_id."' AND status =0";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount() == 0)
	{
		$code = $appSession->getTool()->findReceiptNo($appSession->getTier(), $company_id, "wallet_holder");
		$code = "VFS".$appSession->getTool()->paddingLeft($code, "0", 6);
			
		$msg->add("query", $sql);
		$dt = $appSession->getTier()->getTable($msg);
		$holder_id = $appSession->getTool()->getId();
		$sql = "INSERT INTO wallet_holder(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", status";
		$sql = $sql.", rel_id";
		$sql = $sql.", code";
		$sql = $sql.", category_id";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$holder_id."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$customer_id."'";
		$sql = $sql.", '".$appSession->getTool()->paddingLeft($code, '0', 6)."'";
		$sql = $sql.", '2056b3bb-97d8-4c3d-ad3a-4a61ce80b143'";
		$sql = $sql.")";
		
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
		$sql = "INSERT INTO wallet_holder_rel(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", status";
		$sql = $sql.", holder_id";
		$sql = $sql.", rel_id";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$appSession->getTool()->getId()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$holder_id."'";
		$sql = $sql.", '".$customer_id."'";
		$sql = $sql.")";
		
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
		$sql = "UPDATE wallet SET holder_id ='".$holder_id."' WHERE customer_id='".$customer_id."' AND (customer_id='' OR customer_id IS NULL)";
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
	}
	
	$sql = "SELECT d2.id, d2.code, d1.holder_id, d2.rel_id AS customer_id, SUM(d1.amount * d1.factor) AS amount FROM wallet d1 LEFT OUTER JOIN wallet_holder d2 ON(d1.holder_id = d2.id) LEFT OUTER JOIN wallet_holder_rel d3 ON(d2.id =d3.holder_id AND d3.status=0) WHERE d1.status =0";
	if($customer_id != "")
	{
		$sql = $sql." AND d3.rel_id='".$customer_id."'";
	}else{
		$sql = $sql." AND 1=0";
	}
	$sql = $sql." GROUP BY d2.id, d2.code, d1.holder_id, d3.rel_id";
	$sql = $sql." ORDER BY d2.code ASC";
	
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount()==0){
		$sql = "SELECT d2.id, d2.code, d2.id AS holder_id, d2.rel_id AS customer_id, 0 AS amount FROM wallet_holder d2 WHERE d2.status =0";
		$sql = $sql." AND d2.rel_id='".$customer_id."'";
		
		$sql = $sql." ORDER BY d2.code ASC";
		$msg->add("query", $sql);
		
		$dt = $appSession->getTier()->getTable($msg);
	}
	echo respTable($dt);
	
}else if($ac == "wallet_holder")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$msg = $appSession->getTier()->createMessage();
	$sql = "SELECT d1.id, d1.code FROM wallet_holder d1 WHERE d1.status =0 AND d1.rel_id='".$customer_id."'";
	$sql = $sql." ORDER BY d1.code ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}else if($ac == "wallet_holder_rel")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$msg = $appSession->getTier()->createMessage();
	$sql = "SELECT d1.id,  d1.rel_id, d1.holder_id, d3.code, d3.name AS name, d3.phone FROM wallet_holder_rel d1 LEFT OUTER JOIN wallet_holder d2 ON(d1.holder_id = d2.id) LEFT OUTER JOIN customer d3 ON(d1.rel_id = d3.id) WHERE d1.status =0 AND d2.status =0 AND d2.rel_id='".$customer_id."'";
	$sql = $sql." ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}else if($ac == "remove_holder")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$msg = $appSession->getTier()->createMessage();
	$sql = "UPDATE wallet_holder_rel SET status=1, write_date=NOW() WHERE holder_id='".$id."'";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->exec($msg);
	
	$sql = "UPDATE wallet_holder SET status=1, write_date=NOW() WHERE id='".$id."'";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->exec($msg);
	
	echo "OK";
}else if($ac == "add_holder_rel")
{
	$holder_id = "";
	if(isset($_REQUEST['holder_id']))
	{
		$holder_id = $_REQUEST['holder_id'];
	}
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$sql = "SELECT d1.id FROM wallet_holder_rel d1 WHERE d1.rel_id='".$customer_id."' AND holder_id='".$holder_id."'";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount()>0)
	{
		$id = $dt->getString(0, "id");
		$sql = "UPDATE wallet_holder_rel SET status=0, write_date=NOW() WHERE id='".$id."'";
		$msg->add("query", $sql);
		$dt = $appSession->getTier()->exec($msg);
	}else{
		$sql = "INSERT INTO wallet_holder_rel(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", status";
		$sql = $sql.", holder_id";
		$sql = $sql.", rel_id";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$appSession->getTool()->getId()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$holder_id."'";
		$sql = $sql.", '".$customer_id."'";
		$sql = $sql.")";
		
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}
	echo "OK";
	
}else if($ac == "remove_holder_rel")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$msg = $appSession->getTier()->createMessage();
	$sql = "UPDATE wallet_holder_rel SET status=1, write_date=NOW() WHERE id='".$id."'";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->exec($msg);
	
	
	echo "OK";
}
else if($ac == "loyalty"){
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$msg = $appSession->getTier()->createMessage();
	$sql = "SELECT d2.id, d2.name, d2.sequence, SUM(d1.point * d1.factor) AS point FROM loyalty_point d1 LEFT OUTER JOIN loyalty_point_category d2 ON(d1.category_id = d2.id) WHERE d1.status =0 AND d1.customer_id='".$customer_id."'";
	$sql = $sql." GROUP BY d2.id, d2.name, d2.sequence ORDER BY d2.sequence ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}else if($ac == "voucher"){
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$msg = $appSession->getTier()->createMessage();
	$sql = "SELECT d3.name, d2.code, d3.exp_date, d3.amount, d3.currency_id, d4.code AS currency_code FROM voucher_line_rel d1 LEFT OUTER JOIN voucher_line d2 ON(d1.line_id = d2.id) LEFT OUTER JOIN voucher d3 ON(d2.voucher_id = d3.id) LEFT OUTER JOIN res_currency d4 ON(d3.currency_id = d4.id) WHERE d1.status =0 AND d2.status =0 AND d1.line_id NOT IN(select voucher_line_id FROM voucher_log WHERE status=0) AND d1.rel_id='".$customer_id."'";
	$sql = $sql." ORDER BY d1.create_date DESC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}else if($ac == "update_account_company")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$commercial_name = "";
	if(isset($_REQUEST['commercial_name']))
	{
		$commercial_name = $_REQUEST['commercial_name'];
	}
	$phone = "";
	if(isset($_REQUEST['phone']))
	{
		$phone = $_REQUEST['phone'];
	}
	$email = "";
	if(isset($_REQUEST['email']))
	{
		$email = $_REQUEST['email'];
	}
	$address_id = "";
	if(isset($_REQUEST['address_id']))
	{
		$address_id = $_REQUEST['address_id'];
	}
	$address = "";
	if(isset($_REQUEST['address']))
	{
		$address = $_REQUEST['address'];
	}
	$contact_name = "";
	if(isset($_REQUEST['contact_name']))
	{
		$contact_name = $_REQUEST['contact_name'];
	}
	$contact_mobile = "";
	if(isset($_REQUEST['contact_mobile']))
	{
		$contact_mobile = $_REQUEST['contact_mobile'];
	}
	$contact_email = "";
	if(isset($_REQUEST['contact_email']))
	{
		$contact_email = $_REQUEST['contact_email'];
	}
	$user_name = "";
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
	}
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id == "")
	{
		$lang_id = "vi";
	}
	$date_format = "";
	if(isset($_REQUEST['date_format']))
	{
		$date_format = $_REQUEST['date_format'];
	}
	$thousands_sep = "";
	if(isset($_REQUEST['thousands_sep']))
	{
		$thousands_sep = $_REQUEST['thousands_sep'];
	}
	if($thousands_sep == "")
	{
		$thousands_sep = ",";
	}
	$decimal_point = "";
	if(isset($_REQUEST['decimal_point']))
	{
		$decimal_point = $_REQUEST['decimal_point'];
	}
	if($decimal_point == "")
	{
		$decimal_point = ".";
	}
	if($thousands_sep == $decimal_point)
	{
		$thousands_sep = ",";
		$decimal_point = ".";
	}
	
	$sql = "UPDATE res_company SET write_date=".$appSession->getTier()->getDateString();
	$sql = $sql.", name='".$appSession->getTool()->replace($name, "'", "''")."'";
	$sql = $sql.", commercial_name='".$appSession->getTool()->replace($commercial_name, "'", "''")."'";
	$sql = $sql.", phone='".$appSession->getTool()->replace($phone, "'", "''")."'";
	$sql = $sql.", email='".$appSession->getTool()->replace($email, "'", "''")."'";
	$sql = $sql.", address='".$appSession->getTool()->replace($address, "'", "''")."'";
	$sql = $sql.", address_id='".$appSession->getTool()->replace( $address_id, "'", "''")."'";
	$sql = $sql.", contact_name='".$appSession->getTool()->replace( $contact_name, "'", "''")."'";
	$sql = $sql.", contact_mobile='".$appSession->getTool()->replace($contact_mobile, "'", "''")."'";
	$sql = $sql.", contact_email='".$appSession->getTool()->replace($contact_email, "'", "''")."'";
	$sql = $sql." WHERE id='".$company_id."'";
	
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	$sql = "UPDATE res_user SET write_date=".$appSession->getTier()->getDateString();
	$sql = $sql.", lang_id='".$lang_id."'";
	$sql = $sql.", thousands_sep='".$appSession->getTool()->replace($thousands_sep, "'", "''")."'";
	$sql = $sql.", decimal_point='".$appSession->getTool()->replace($decimal_point, "'", "''")."'";
	$sql = $sql." WHERE id='".$user_id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo $user_id;
}else if($ac == "update_account_company_company")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$commercial_name = "";
	if(isset($_REQUEST['commercial_name']))
	{
		$commercial_name = $_REQUEST['commercial_name'];
	}
	$phone = "";
	if(isset($_REQUEST['phone']))
	{
		$phone = $_REQUEST['phone'];
	}
	$email = "";
	if(isset($_REQUEST['email']))
	{
		$email = $_REQUEST['email'];
	}
	$address_id = "";
	if(isset($_REQUEST['address_id']))
	{
		$address_id = $_REQUEST['address_id'];
	}
	$address = "";
	if(isset($_REQUEST['address']))
	{
		$address = $_REQUEST['address'];
	}
	$contact_name = "";
	if(isset($_REQUEST['contact_name']))
	{
		$contact_name = $_REQUEST['contact_name'];
	}
	$contact_mobile = "";
	if(isset($_REQUEST['contact_mobile']))
	{
		$contact_mobile = $_REQUEST['contact_mobile'];
	}
	$contact_email = "";
	if(isset($_REQUEST['contact_email']))
	{
		$contact_email = $_REQUEST['contact_email'];
	}
	$user_name = "";
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
	}
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id == "")
	{
		$lang_id = "vi";
	}
	$date_format = "";
	if(isset($_REQUEST['date_format']))
	{
		$date_format = $_REQUEST['date_format'];
	}
	$thousands_sep = "";
	if(isset($_REQUEST['thousands_sep']))
	{
		$thousands_sep = $_REQUEST['thousands_sep'];
	}
	if($thousands_sep == "")
	{
		$thousands_sep = ",";
	}
	$decimal_point = "";
	if(isset($_REQUEST['decimal_point']))
	{
		$decimal_point = $_REQUEST['decimal_point'];
	}
	if($decimal_point == "")
	{
		$decimal_point = ".";
	}
	if($thousands_sep == $decimal_point)
	{
		$thousands_sep = ",";
		$decimal_point = ".";
	}
	
	$sql = "UPDATE res_company SET write_date=".$appSession->getTier()->getDateString();
	$sql = $sql.", name='".$appSession->getTool()->replace($name, "'", "''")."'";
	$sql = $sql.", commercial_name='".$appSession->getTool()->replace($commercial_name, "'", "''")."'";
	$sql = $sql.", phone='".$appSession->getTool()->replace($phone, "'", "''")."'";
	$sql = $sql.", email='".$appSession->getTool()->replace($email, "'", "''")."'";
	$sql = $sql.", address='".$appSession->getTool()->replace($address, "'", "''")."'";
	$sql = $sql.", address_id='".$appSession->getTool()->replace( $address_id, "'", "''")."'";
	$sql = $sql.", contact_name='".$appSession->getTool()->replace( $contact_name, "'", "''")."'";
	$sql = $sql.", contact_mobile='".$appSession->getTool()->replace($contact_mobile, "'", "''")."'";
	$sql = $sql.", contact_email='".$appSession->getTool()->replace($contact_email, "'", "''")."'";
	$sql = $sql." WHERE id='".$company_id."'";
	
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo $user_id;
}
else if($ac == "company_item")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$sql = "SELECT d1.company_id, d2.id, d2.name, d2.date_format, d2.thousands_sep, d2.time_format, d2.decimal_point, d2.avatar, d2.lang_id, d3.code AS company_code, d3.name AS company_name, d3.commercial_name , d3.phone, d3.email, d3.address_id AS ward_address_id, d3.address, d3.contact_name, d3.contact_mobile, d3.contact_email, d5.id AS dist_address_id,  d5.parent_id AS city_address_id FROM res_user_company d1 LEFT OUTER JOIN res_user d2 ON(d1.user_id = d2.id) LEFT OUTER JOIN res_company d3 ON(d1.company_id = d3.id) LEFT OUTER JOIN res_address d4 ON(d3.address_id= d4.id) LEFT OUTER JOIN res_address d5 ON(d4.parent_id= d5.id)  WHERE d1.status =0 AND d2.status =0 AND d2.id='".$user_id."' AND d3.id='".$company_id."'";
	$msg->add("query", $sql);
	
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}else if($ac == "company_item_company")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$sql = "SELECT d3.code AS company_code, d3.name AS company_name, d3.commercial_name , d3.phone, d3.email, d3.address_id AS ward_address_id, d3.address, d3.contact_name, d3.contact_mobile, d3.contact_email, d5.id AS dist_address_id,  d5.parent_id AS city_address_id FROM res_company d3 LEFT OUTER JOIN res_address d4 ON(d3.address_id= d4.id) LEFT OUTER JOIN res_address d5 ON(d4.parent_id= d5.id)  WHERE d3.id='".$company_id."'";
	$msg->add("query", $sql);
	
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}
else if($ac == "customer_item")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$sql = "SELECT d1.company_id, d2.id, d2.name, d2.date_format, d2.thousands_sep, d2.time_format, d2.decimal_point, d2.avatar, d2.lang_id, d3.code AS company_code, d3.name AS company_name, d3.commercial_name , d3.phone, d3.email, d3.address_id AS ward_address_id, d3.address, d3.contact_name, d3.contact_mobile, d3.contact_email, d5.id AS dist_address_id,  d5.parent_id AS city_address_id FROM customer_user d1 LEFT OUTER JOIN res_user d2 ON(d1.user_id = d2.id) LEFT OUTER JOIN customer d3 ON(d1.customer_id = d3.id) LEFT OUTER JOIN res_address d4 ON(d3.address_id= d4.id) LEFT OUTER JOIN res_address d5 ON(d4.parent_id= d5.id)  WHERE d1.status =0 AND d2.status =0 AND d2.id='".$user_id."'";
	$msg->add("query", $sql);
	
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}
else if($ac == "update_account_customer")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$commercial_name = "";
	if(isset($_REQUEST['commercial_name']))
	{
		$commercial_name = $_REQUEST['commercial_name'];
	}
	$phone = "";
	if(isset($_REQUEST['phone']))
	{
		$phone = $_REQUEST['phone'];
	}
	$email = "";
	if(isset($_REQUEST['email']))
	{
		$email = $_REQUEST['email'];
	}
	$address_id = "";
	if(isset($_REQUEST['address_id']))
	{
		$address_id = $_REQUEST['address_id'];
	}
	$address = "";
	if(isset($_REQUEST['address']))
	{
		$address = $_REQUEST['address'];
	}
	$contact_name = "";
	if(isset($_REQUEST['contact_name']))
	{
		$contact_name = $_REQUEST['contact_name'];
	}
	$contact_mobile = "";
	if(isset($_REQUEST['contact_mobile']))
	{
		$contact_mobile = $_REQUEST['contact_mobile'];
	}
	$contact_email = "";
	if(isset($_REQUEST['contact_email']))
	{
		$contact_email = $_REQUEST['contact_email'];
	}
	$user_name = "";
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
	}
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id == "")
	{
		$lang_id = "vi";
	}
	$date_format = "";
	if(isset($_REQUEST['date_format']))
	{
		$date_format = $_REQUEST['date_format'];
	}
	$thousands_sep = "";
	if(isset($_REQUEST['thousands_sep']))
	{
		$thousands_sep = $_REQUEST['thousands_sep'];
	}
	if($thousands_sep == "")
	{
		$thousands_sep = ",";
	}
	$decimal_point = "";
	if(isset($_REQUEST['decimal_point']))
	{
		$decimal_point = $_REQUEST['decimal_point'];
	}
	if($decimal_point == "")
	{
		$decimal_point = ".";
	}
	if($thousands_sep == $decimal_point)
	{
		$thousands_sep = ",";
		$decimal_point = ".";
	}
	
	$sql = "UPDATE customer SET write_date=".$appSession->getTier()->getDateString();
	$sql = $sql.", name='".$appSession->getTool()->replace($name, "'", "''")."'";
	$sql = $sql.", commercial_name='".$appSession->getTool()->replace($commercial_name, "'", "''")."'";
	$sql = $sql.", phone='".$appSession->getTool()->replace($phone, "'", "''")."'";
	$sql = $sql.", email='".$appSession->getTool()->replace($email, "'", "''")."'";
	$sql = $sql.", address='".$appSession->getTool()->replace($address, "'", "''")."'";
	$sql = $sql.", address_id='".$appSession->getTool()->replace( $address_id, "'", "''")."'";
	$sql = $sql.", contact_name='".$appSession->getTool()->replace( $contact_name, "'", "''")."'";
	$sql = $sql.", contact_mobile='".$appSession->getTool()->replace($contact_mobile, "'", "''")."'";
	$sql = $sql.", contact_email='".$appSession->getTool()->replace($contact_email, "'", "''")."'";
	$sql = $sql." WHERE id='".$customer_id."'";
	
	
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	$sql = "UPDATE res_user SET write_date=".$appSession->getTier()->getDateString();
	$sql = $sql.", lang_id='".$lang_id."'";
	$sql = $sql.", thousands_sep='".$appSession->getTool()->replace($thousands_sep, "'", "''")."'";
	$sql = $sql.", decimal_point='".$appSession->getTool()->replace($decimal_point, "'", "''")."'";
	$sql = $sql." WHERE id='".$user_id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo $user_id;
}
else if($ac == "change_password")
{
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	$new_password = "";
	if(isset($_REQUEST['new_password']))
	{
		$new_password = $_REQUEST['new_password'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$s = $appSession->getTool()->toHash("sha256", "[".$user_id."]".$password);
	$len = $appSession->getTool()->lenght($password);
	for($i = 0; $i<$len; $i++)
	{
		$s = $s.chr($i + 48);
	}
	$password = $appSession->getTool()->toHash("md5", $s);
	
	$s = $appSession->getTool()->toHash("sha256", "[".$user_id."]".$new_password);
	$len = $appSession->getTool()->lenght($new_password);
	for($i = 0; $i<$len; $i++)
	{
		$s = $s.chr($i + 48);
	}
	$new_password = $appSession->getTool()->toHash("md5", $s);
			
	$sql = "SELECT id FROM res_user WHERE password='".$password."' AND id='".$user_id."'";
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	if(count($values) > 0)
	{
		$sql = "UPDATE res_user SET write_date=".$appSession->getTier()->getDateString();
		$sql = $sql.", password='".$new_password."'";
		$sql = $sql." WHERE id='".$user_id."'";
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
		echo "OK:".$user_id;
		
	}else{
		echo "INVALID_PASSWORD";
	}
}else if($ac == "forget_password")
{
	
	$user_name = "";
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
	}
	
	if( $user_name == "")
	{
		echo "INVALID";
		exit();
	}
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	
	$sql = "SELECT id, email, phone FROM res_user WHERE status =0 ";
	
	$sql = $sql." AND (user_name='".str_replace("'", "''", $user_name)."' OR phone='".str_replace("'", "''", $user_name)."' OR email='".str_replace("'", "''", $user_name)."')";
	$msg->add("query", $sql);
	
	$values = $appSession->getTier()->getArray($msg);
	if(count($values) > 0)
	{
		$user_id = $values[0][0];
		$email = $values[0][1];
		$phone = $values[0][2];
		
		$id = $appSession->getTool()->getId();
		$code = rand(1000, 9999);
		$sql = "INSERT INTO res_user_verification(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", status";
		$sql = $sql.", create_uid";
		$sql = $sql.", code";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$id."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$user_id."'";
		$sql = $sql.", '".str_replace("'", "''", $code)."'";
		$sql = $sql.")";
		$msg->add("query", $sql);
		$result = $appSession->getTier()->exec($msg);
		if($phone != "")
		{
			$r = $appSession->getTool()->send_sms($phone, "Quy khach dang thuc hien thiet lap mat khau VfSCFood. Khong gui mat ma nay cho bat ky ai de tranh rui ro. MA OTP ".$code);
		}
		if($email != "")
		{
			$r = $appSession->getTool()->send_mail(SUPPORT_EMAIL, "Fosacha", $email, "Quên mật khẩu", "Quên mật khẩu", "Mã OPT:".$code);
		}
		
		echo "OK:".$appSession->getTool()->encrypt($id, true);
	
	}else{
		echo "INVALID";
	}
}else if($ac == "verify_user_code")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
		$id = $appSession->getTool()->decrypt($id, true);
	}
	$code = "";
	if(isset($_REQUEST['code']))
	{
		$code = $_REQUEST['code'];
	}
	$sql = "SELECT id, create_uid FROM res_user_verification WHERE (id='".$id."' AND code='".str_replace("'", "''", $code)."') AND status =0";
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	if(count($values) > 0)
	{
		$sql = "UPDATE res_user_verification SET status =1, write_date=".$appSession->getTier()->getDateString()." WHERE id='".$values[0][0]."'";
		$msg->add("query", $sql);
		$result = $appSession->getTier()->exec($msg);
		echo "OK:".$appSession->getTool()->encrypt($values[0][1], true);;
	}else{
		echo "INVALID";
	}
}else if($ac == "change_forgot_password")
{
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
		$id = $appSession->getTool()->decrypt($id, true);
	}
	$sql = "SELECT create_uid FROM res_user_verification WHERE id='".$id."'";
	$msg->add("query", $sql);
	$user_id = $appSession->getTier()->getValue($msg);
	if($user_id == "")
	{
		echo "INVALID";
		exit();
	}
		
	$s = $appSession->getTool()->toHash("sha256", "[".$user_id."]".$password);
	$len = $appSession->getTool()->lenght($password);
	for($i = 0; $i<$len; $i++)
	{
		$s = $s.chr($i + 48);
	}
	$password = $appSession->getTool()->toHash("md5", $s);
			
	$sql = "SELECT id FROM res_user WHERE id='".$user_id."'";
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	if(count($values) > 0)
	{
		$sql = "UPDATE res_user SET write_date=".$appSession->getTier()->getDateString();
		$sql = $sql.", password='".$password."'";
		$sql = $sql." WHERE id='".$user_id."'; DELETE FROM  res_user_verification WHERE id ='".$id."'";
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
		echo "OK:".$user_id;
		
	}else{
		echo "INVALID_PASSWORD";
	}
}else if($ac == "register_kiosk_company")
{
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$commercial_name = "";
	if(isset($_REQUEST['commercial_name']))
	{
		$commercial_name = $_REQUEST['commercial_name'];
	}
	$phone = "";
	if(isset($_REQUEST['phone']))
	{
		$phone = $_REQUEST['phone'];
	}
	$email = "";
	if(isset($_REQUEST['email']))
	{
		$email = $_REQUEST['email'];
	}
	$address_id = "";
	if(isset($_REQUEST['address_id']))
	{
		$address_id = $_REQUEST['address_id'];
	}
	$address = "";
	if(isset($_REQUEST['address']))
	{
		$address = $_REQUEST['address'];
	}
	$contact_name = "";
	if(isset($_REQUEST['contact_name']))
	{
		$contact_name = $_REQUEST['contact_name'];
	}
	$contact_mobile = "";
	if(isset($_REQUEST['contact_mobile']))
	{
		$contact_mobile = $_REQUEST['contact_mobile'];
	}
	$contact_email = "";
	if(isset($_REQUEST['contact_email']))
	{
		$contact_email = $_REQUEST['contact_email'];
	}
	$user_name = "";
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
	}
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id == "")
	{
		$lang_id = "vi";
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	
	
	
	$parent_company_id = $appSession->getConfig()->getProperty("kiosk_company_id");
	$company_id = $appSession->getTool()->getId();
	$code = $appSession->getTool()->findReceiptNo($appSession->getTier(), $company_id, "res_company.code");
	$code = $appSession->getTool()->paddingLeft($code, "0", 8);
			
	$sql = "INSERT INTO res_company(";
	$sql = $sql."id";
	$sql = $sql.", code";
	$sql = $sql.", company_id";
	$sql = $sql.", parent_id";
	$sql = $sql.", status";
	$sql = $sql.", name";
	$sql = $sql.", commercial_name";
	$sql = $sql.", phone";
	$sql = $sql.", email";
	$sql = $sql.", contact_name";
	$sql = $sql.", contact_mobile";
	$sql = $sql.", contact_email";
	$sql = $sql.", address_id";
	$sql = $sql.", address";
	$sql = $sql.", create_date";
	$sql = $sql.", write_date";
	$sql = $sql.", create_uid";
	$sql = $sql.", write_uid";
	$sql = $sql." )VALUES(";
	$sql = $sql."'".$company_id."'";
	$sql = $sql.", '".$code."'";
	$sql = $sql.", '".$company_id."'";
	$sql = $sql.", '".$parent_company_id."'";
	$sql = $sql.", 0";
	$sql = $sql.", '".str_replace("'", "''", $name)."'";
	$sql = $sql.", '".str_replace("'", "''", $commercial_name)."'";
	$sql = $sql.", '".str_replace("'", "''", $phone)."'";
	$sql = $sql.", '".str_replace("'", "''", $email)."'";
	$sql = $sql.", '".str_replace("'", "''", $contact_name)."'";
	$sql = $sql.", '".str_replace("'", "''", $contact_mobile)."'";
	$sql = $sql.", '".str_replace("'", "''", $contact_email)."'";
	$sql = $sql.", '".str_replace("'", "''", $address_id)."'";
	$sql = $sql.", '". $appSession->getTool()->replace($address, "'", "''")."'";
	$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
	$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
	$sql = $sql.", '".$user_id."'";
	$sql = $sql.", '".$user_id."'";
	$sql = $sql.")";
	$msg->add("query", $sql);
	$r = $appSession->getTier()->exec($msg);
	echo $company_id;
		
}else if($ac == "employee")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$sql = "SELECT d1.rel_id FROM res_user_company d1 LEFT OUTER JOIN hr_employee d2 ON(d1.rel_id = d2.id) WHERE d2.id IS NOT NULL AND d1.user_id='".$user_id."'";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}else if($ac == "wallet_add")
{
	$holder_id = "";
	if(isset($_REQUEST['holder_id']))
	{
		$holder_id = $_REQUEST['holder_id'];
	}

	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$amount = "";
	if(isset($_REQUEST['amount']))
	{
		$amount = $_REQUEST['amount'];
	}
	$description = "";
	if(isset($_REQUEST['description']))
	{
		$description = $_REQUEST['description'];
	}
	if($customer_id != "")
	{
		$receipt_no = $appSession->getTool()->findReceiptNo($appSession->getTier(), "", "wallet");
		$wallet_id = $appSession->getTool()->getId();
		$builder = $appSession->getTier()->createBuilder("wallet");
		$builder->add("id", $wallet_id);
		$builder->add("holder_id", $holder_id);
		$builder->add("create_uid", $appSession->getUserInfo()->getId());
		$builder->add("write_uid", $appSession->getUserInfo()->getId());
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("category_id", "eaf4f9aa-42de-4d19-b957-b3d24580d39d");
		$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
		$builder->add("status", -1);
		$builder->add("customer_id", $customer_id);
		$builder->add("currency_id", "23");
		$builder->add("receipt_no", $receipt_no);
		$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("factor", 1);
		$builder->add("amount", $amount);
		$builder->add("description", $description);
		$builder->add("payment_id", "fda0dc20-341e-4ac2-cfad-888dd77ee9d1");
		$sql = $appSession->getTier()->getInsert($builder);
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		echo $wallet_id;
	}
	
	
}else if($ac == "check_user")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$sql = "SELECT d1.user_id FROM res_user_company d1 LEFT OUTER JOIN res_user d2 ON(d1.user_id = d2.id) WHERE d1.status =0 AND d1.user_id='".$user_id."'";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount()>0){
		echo $dt->getString(0, "user_id");
	}else{
		echo "ERROR";
	}
}else if($ac == "add_wallet_by_voucher_code")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$holder_id = "";
	if(isset($_REQUEST['holder_id']))
	{
		$holder_id = $_REQUEST['holder_id'];
	}
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$code = "";
	if(isset($_REQUEST['code']))
	{
		$code = $_REQUEST['code'];
	}
	$sql = "SELECT d1.id, d2.amount, d2.currency_id FROM voucher_line d1 LEFT OUTER JOIN voucher d2 ON(d1.voucher_id = d2.id) LEFT OUTER JOIN voucher_log d3 ON(d1.id = d3.voucher_line_id) WHERE d1.status =0 AND d2.status =0 AND d2.exp_date>NOW() AND d1.code='".str_ireplace("'", "''", $code)."' AND (d3.id IS NULL OR d3.status !=0) AND d2.publish=1";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount()>0){
		$voucher_line_id = $dt->getString(0, "id");
		$amount = $dt->getString(0, "amount");
		$currency_id = $dt->getString(0, "currency_id");
		$receipt_no = $appSession->getTool()->findReceiptNo($appSession->getTier(), "", "wallet");
		$wallet_id = $appSession->getTool()->getId();
		$builder = $appSession->getTier()->createBuilder("wallet");
		$builder->add("id", $wallet_id);
		$builder->add("holder_id", $holder_id);
		$builder->add("create_uid", $appSession->getUserInfo()->getId());
		$builder->add("write_uid", $appSession->getUserInfo()->getId());
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("category_id", "eaf4f9aa-42de-4d19-b957-b3d24580d39d");
		$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
		$builder->add("status", 0);
		$builder->add("customer_id", $customer_id);
		$builder->add("currency_id", $currency_id);
		$builder->add("receipt_no", $receipt_no);
		$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("description", "Thêm từ mã :".$code);
		$builder->add("factor", 1);
		$builder->add("amount", $amount);
		$builder->add("payment_id", "fda0dc20-341e-4ac2-cfad-888dd77ee9d1");
		$sql = $appSession->getTier()->getInsert($builder);
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
		$builder = $appSession->getTier()->createBuilder("voucher_log");
		$builder->add("id", $appSession->getTool()->getId());
		$builder->add("rel_id", $wallet_id);
		$builder->add("voucher_line_id", $voucher_line_id);
		$builder->add("create_uid", $appSession->getUserInfo()->getId());
		$builder->add("write_uid", $appSession->getUserInfo()->getId());
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
		$builder->add("status", 0);
		$sql = $appSession->getTier()->getInsert($builder);
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		echo "OK";
	}else{
		echo "Mã không hợp lệ";
	}
	
}else if($ac == "resend_otp")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
		$id = $appSession->getTool()->decrypt($id, true);
	}

	$sql = "SELECT d1.id, d2.phone, d2.email FROM res_user_verification d1 LEFT OUTER JOIN res_user d2 ON(d1.create_uid = d2.id) WHERE d1.id ='".$id."' AND d1.status =0";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount()>0)
	{
		$phone = $dt->getString(0, "phone");
		$email = $dt->getString(0, "email");
		$code = rand(1000, 9999);
		$sql = "UPDATE res_user_verification SET code ='".$code."' WHERE id ='".$dt->getString(0, "id")."'";
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
	
		if($phone != "")
		{
			$r = $appSession->getTool()->send_sms($phone, "Quy khach dang thuc hien thiet lap mat khau VfSCFood. Khong gui mat ma nay cho bat ky ai de tranh rui ro. MA OTP ".$code);
		}else{
			if($email != "")
			{
				$r = $appSession->getTool()->send_mail(SUPPORT_EMAIL, "Fosacha", $email, "Đăng ký", "Kích hoạt tài khoản", "Mã OPT:".$code);
			}
		}
		echo "OK";
	}else {
		echo "INVALID";
	}
}
?>
