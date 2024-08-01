<?php
require_once(ABSPATH.'api/Customer.php' );
require_once(ABSPATH.'app/barcode/autoload.php' );
require_once(ABSPATH.'app/phpqrcode/qrlib.php' );


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
	$customer = new Customer();
	$message = $customer->create($appSession, $name, $phone, $email , $user_name, $password, $lang_id, $address);
	echo $message;
}else if($ac == "login"){
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
		$message = "OK";
	}
	echo $message;
}else if($ac == "logout")
{
	$appSession->getConfig()->setProperty("user_id", "");
	$appSession->getConfig()->save();
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
	$sql = "SELECT barcode FROM customer WHERE id='".$customer_id."'";
	
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
	$sql = "SELECT d2.id, d2.name, d2.sequence, SUM(d1.amount * d1.factor) AS amount FROM wallet d1 LEFT OUTER JOIN wallet_category d2 ON(d1.category_id = d2.id) WHERE d1.status =0 AND d1.customer_id='".$customer_id."'";
	$sql = $sql." GROUP BY d2.id, d2.name, d2.sequence";
	$sql = $sql." ORDER BY d2.sequence ASC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
	
}else if($ac == "loyalty"){
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
}
?>
