<?php
require_once(ABSPATH.'api/Product.php' );
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
if($ac == "product_group"){
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id != "")
	{
		$appSession->getConfig()->setProperty("lang_id", $lang_id);
	}
	$product = new Product($appSession);
	$dt = $product->productByGroup($name);
	echo respTable($dt);
}else if($ac == "product_group_app"){
	
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id != "")
	{
		$appSession->getConfig()->setProperty("lang_id", $lang_id);
	}
	
	$product = new Product($appSession);
	$dt = $product->productGroupApp();
	echo respTable($dt);
}else if($ac == "product_group_by_id")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id != "")
	{
		$appSession->getConfig()->setProperty("lang_id", $lang_id);
	}
	$product = new Product($appSession);
	$dt = $product->productByGroupId($id);
	echo respTable($dt);
}else if($ac == "add_product_to_sale"){
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$product_id = "";
	if(isset($_REQUEST['product_id']))
	{
		$product_id = $_REQUEST['product_id'];
	}
	$unit_id = "";
	if(isset($_REQUEST['unit_id']))
	{
		$unit_id = $_REQUEST['unit_id'];
	}
	$attribute_id = "";
	if(isset($_REQUEST['attribute_id']))
	{
		$attribute_id = $_REQUEST['attribute_id'];
	}
	$type_id = "";
	if(isset($_REQUEST['type_id']))
	{
		$type_id = $_REQUEST['type_id'];
	}
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	
	$currency_id = "";
	if(isset($_REQUEST['currency_id']))
	{
		$currency_id = $_REQUEST['currency_id'];
	}
	$quantity = "1";
	if(isset($_REQUEST['quantity']))
	{
		$quantity = $_REQUEST['quantity'];
	}
	$unit_price = "0";
	if(isset($_REQUEST['unit_price']))
	{
		$unit_price = $_REQUEST['unit_price'];
	}
	$second_unit_id = "";
	if(isset($_REQUEST['second_unit_id']))
	{
		$second_unit_id = $_REQUEST['second_unit_id'];
	}
	$factor = "1";
	if(isset($_REQUEST['factor']))
	{
		$factor = $_REQUEST['factor'];
	}
	$description = "";
	if(isset($_REQUEST['description']))
	{
		$description = $_REQUEST['description'];
	}
	$sale = new Sale($appSession);
	if($user_id == "")
	{
		$user_id = $appSession->getConfig()->getProperty("user_id");
	}
	$sale_product_id= $sale->addProductBySaleId($sale_id, $user_id, $product_id, $currency_id, $unit_id, $attribute_id, $type_id, $rel_id, $quantity, $unit_price, $second_unit_id, $factor, $description);
	$sql = "SELECT id, product_id, unit_id, currency_id, attribute_id, type_id, quantity, unit_price FROM product_modifier WHERE rel_id='".$product_id."' AND status =0";
	
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	for($i =0; $i<$dt->getRowCount(); $i++)
	{
		 $sale->addProductBySaleId($sale_id, $user_id, $dt->getString($i, "product_id"), $dt->getString($i, "currency_id"), $dt->getString($i, "unit_id"), $dt->getString($i, "attribute_id"), $dt->getString($i, "quantity"), $dt->getString($i, "unit_price"), "", $factor, "", $company_id, $dt->getString($i, "id"), $dt->getString($i, "type_id"), $sale_product_id);
	}
	echo $sale_product_id;
	
}else if($ac == "product_sale_count"){
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleIdBySessionId($session_id, $user_id);
	
	echo $sale->getSaleItemCount($sale_id);
	
}else if($ac == "category"){
	
	$msg = $appSession->getTier()->createMessage();
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id != "")
	{
		$appSession->getConfig()->setProperty("lang_id", $lang_id);
	}
	$sql = "SELECT d1.id, d1.parent_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id";
	$sql = $sql." FROM product_category d1";
	$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status=0)";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$lang_id."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0) LEFT OUTER JOIN product_category d3 ON(d1.parent_id = d3.id)";
	$sql = $sql." WHERE d1.status =0 AND d1.publish = 1 AND d3.parent_id='' AND d3.publish =1 AND d1.type='PRODUCT_CATEGORY'";
	$sql = $sql." AND d1.company_id='".$appSession->getConfig()->getProperty("company_id")."'";
	$sql = $sql." ORDER BY d3.sequence ASC, d1.sequence ASC, lg.description ASC, d1.name ASC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	
	echo respTable($dt);
}else if($ac == "product_category_id")
{
	$msg = $appSession->getTier()->createMessage();
	$category_id = "";
	if(isset($_REQUEST['id']))
	{
		$category_id = $_REQUEST['id'];
	}
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	
	if($lang_id != "")
	{
		$appSession->getConfig()->setProperty("lang_id", $lang_id);
	}
	
	$search = "";
	if(isset($_REQUEST['search']))
	{
		$search = $_REQUEST['search'];
	}
	
	
	$sql = "SELECT d1.id, d1.category_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d5.unit_price, d5.old_price, d5.unit_id, d6.name AS unit_name, d5.currency_id";
	$sql = $sql.", d7.name AS attribute_name, d8.name attribute_category_name, (SELECT SUM(product_count.quantity) FROM product_count LEFT OUTER JOIN res_company ON(product_count.company_id = res_company.id) WHERE product_count.status =0 AND product_count.rel_id= d5.id) AS unit_in_stock, d5.attribute_id, d7.quantity AS factor, d7.unit_id AS second_unit_id, d9.name AS second_unit_name"; 
	$sql = $sql." FROM product d1";
	$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1)";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
	$sql = $sql." LEFT OUTER JOIN product_price d5 ON(d1.id = d5.product_id AND d5.status =0 AND d5.publish = 1 AND d5.type='PRODUCT') LEFT OUTER JOIN product_unit d6 ON(d5.unit_id = d6.id)";
	$sql = $sql." LEFT OUTER JOIN attribute d7 ON(d5.attribute_id = d7.id)";
	$sql = $sql." LEFT OUTER JOIN attribute_category d8 ON(d7.category_id = d8.id)";
	$sql = $sql." LEFT OUTER JOIN attribute d9 ON(d7.unit_id = d9.id)";
	$sql = $sql." WHERE d1.status =0 AND d1.publish = 1 AND d1.category_id='".$category_id."'";
	
	//$sql = $sql." AND d1.company_id='".$appSession->getConfig()->getProperty("company_id")."'";
	if($search != "")
	{
		$search = $appSession->getTool()->replace($search, "'", "''");
		$sql = $sql." AND (d1.name ILIKE '%".$search."%' OR lg.description ILIKE '%".$search."%')";
	}
	$sql = $sql." ORDER BY d1.sequence ASC, d5.sequence ASC";
	
	
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	
	echo respTable($dt);
}else if($ac == "sale_product"){
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	
	if($lang_id != "")
	{
		$appSession->getConfig()->setProperty("lang_id", $lang_id);
	}
	
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleIdBySessionId($session_id, $user_id);
	
	$dt = $sale->productListSaleId($sale_id);
	echo respTable($dt);
}else if($ac == "res_company")
{
	$msg = $appSession->getTier()->createMessage();
	$sql = "SELECT d1.id, d1.name, d1.address";
	$sql = $sql." FROM res_company d1 WHERE d1.parent_id='". $appSession->getConfig()->getProperty("company_id")."'";
	$sql = $sql." AND d1.type='ONLINE'";
	$sql = $sql." ORDER BY d1.name ASC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	
	echo respTable($dt);
}else if($ac == "checkOut")
{
	$msg = $appSession->getTier()->createMessage();
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	if($user_id == "")
	{
		$user_id = $appSession->getUserInfo()->getId();
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	if($session_id == "")
	{
		$session_id = $appSession->getConfig()->getProperty("session_id");
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
	if(isset($_REQUEST['delivery_name']))
	{
		$name = $_REQUEST['delivery_name'];
	}
	
	$email = "";
	if(isset($_REQUEST['delivery_email']))
	{
		$email = $_REQUEST['delivery_email'];
	}
	
	$tel = "";
	if(isset($_REQUEST['delivery_tel']))
	{
		$tel = $_REQUEST['delivery_tel'];
	}
	$address = "";
	if(isset($_REQUEST['delivery_to']))
	{
		$address = $_REQUEST['delivery_to'];
	}
	$address = "";
	if(isset($_REQUEST['delivery_to']))
	{
		$address = $_REQUEST['delivery_to'];
	}
	
	$description = "";
	if(isset($_REQUEST['delivery_description']))
	{
		$description = $_REQUEST['delivery_description'];
	}
	$address_id = "";
	if(isset($_REQUEST['address_id']))
	{
		$address_id = $_REQUEST['address_id'];
	}
	
	$payment_id = "";
	if(isset($_REQUEST['payment_id']))
	{
		$payment_id = $_REQUEST['payment_id'];
	}
	$payment_amount = "";
	if(isset($_REQUEST['payment_amount']))
	{
		$payment_amount = $_REQUEST['payment_amount'];
	}
	$payment_amount = $appSession->getTool()->toDouble($payment_amount);
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleIdBySessionId($session_id, $user_id);
	$paid = $sale->totalSaleLocal($sale_id);
	if($payment_amount>$paid){
		$payment_amount = $paid;
	}
	$payment_points = "";
	if(isset($_REQUEST['payment_points']))
	{
		$payment_points = $_REQUEST['payment_points'];
	}
	$payment_description = "";
	if(isset($_REQUEST['payment_description']))
	{
		$payment_description = $_REQUEST['payment_description'];
	}
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	
		
	$message = "OK";
	if($payment_id != "")
	{
		$sql = "SELECT d2.name FROM account_payment d1 LEFT OUTER JOIN account_payment_category d2 ON(d1.category_id = d2.id) WHERE d1.id='".$payment_id."'";
		$msg->add("query", $sql);
		$payment_category_name = $appSession->getTier()->getValue($msg);
		
		if($payment_category_name == "WALLET")
		{
			
			$sql = "SELECT d1.currency_id, SUM(d1.amount * d1.factor) FROM wallet d1";
			$sql = $sql." WHERE d1.customer_id='".$customer_id."' AND d1.status=0 GROUP BY d1.currency_id";
		
			$msg->add("query", $sql);
			$values = $appSession->getTier()->getArray($msg);
			$balance = 0;
			$currency_id = "";
			if(count($values)>0){
				$currency_id = $values[0][0];
				$balance = $appSession->getTool()->toDouble($values[0][1]);
			}
			if($balance>=$payment_amount)
			{
				$category_id = "119d1566-cab9-4a65-cc94-75c42a0dfafd";
				if($currency_id == "")
				{
					$currency_id = $appSession->getConfig()->getProperty("currency_id");
				}
				
				$wallet_id = $appSession->getTool()->getId();
				$builder = $appSession->getTier()->createBuilder("wallet");
				$builder->add("id", $wallet_id);
				$builder->add("create_uid", $user_id);
				$builder->add("write_uid", $user_id);
				$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("factor", -1);
				$builder->add("customer_id", $customer_id);
				$builder->add("currency_id", $currency_id);
				$builder->add("category_id", $category_id);
				$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "wallet"));
				$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("rel_id", $sale_id);
				$builder->add("amount", $payment_amount);
				$builder->add("status", 0);
				$builder->add("description", $payment_description);
				$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
				
				$sql = $appSession->getTier()->getInsert($builder);
				$msg->add("query", $sql);
				$appSession->getTier()->exec($msg);
				
				
				$builder->clear();
				$builder->setName("account_payment_line_local");
				
				$builder->add("id", $appSession->getTool()->getId());
				$builder->add("create_uid", $user_id);
				$builder->add("write_uid", $user_id);
				$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("line_id", $sale_id);
				$builder->add("payment_id", $payment_id);
				$builder->add("currency_id", $currency_id);
				$builder->add("rel_id", $wallet_id);
				$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "account_payment_line_local"));
				$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("amount", $payment_amount);
				$builder->add("status", 0);
				$builder->add("description", $payment_description);
				$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
				
				$sql = $appSession->getTier()->getInsert($builder);
				$msg->add("query", $sql);
				$appSession->getTier()->exec($msg);
				
				
			}else{
				$message = "Payment is over";
			}
			
		}else if($payment_category_name == "LOYALTY")
		{
			$payment_amount = $appSession->getTool()->toDouble($payment_amount);
			$payment_points = $appSession->getTool()->toDouble($payment_points);
			$sql = "SELECT SUM(d1.point * d1.factor) FROM loyalty_point d1";
			$sql = $sql." WHERE d1.customer_id='".$customer_id."'";
		
			$msg->add("query", $sql);
			$balance = $appSession->getTier()->getValue($msg);
			$balance = $appSession->getTool()->toDouble($balance);
			if($balance>=$payment_points)
			{
				$category_id = "db3e3283-701b-4964-920c-20daceefd5ca";
				$currency_id = $appSession->getConfig()->getProperty("currency_id");
				$loyalty_point_id = $appSession->getTool()->getId();
				$builder = $appSession->getTier()->createBuilder("loyalty_point");
				$builder->add("id", $loyalty_point_id);
				$builder->add("create_uid", $user_id);
				$builder->add("write_uid", $user_id);
				$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("factor", -1);
				$builder->add("customer_id", $customer_id);
				$builder->add("category_id", $category_id);
				$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "loyalty_point"));
				$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("rel_id", $sale_id);
				$builder->add("point", $payment_points);
				$builder->add("status", 0);
				$builder->add("description", $payment_description);
				$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
				
				$sql = $appSession->getTier()->getInsert($builder);
				$msg->add("query", $sql);
				$appSession->getTier()->exec($msg);
				
				
				$builder->clear();
				$builder->setName("account_payment_line_local");
				
				$builder->add("id", $appSession->getTool()->getId());
				$builder->add("create_uid", $user_id);
				$builder->add("write_uid", $user_id);
				$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("line_id", $sale_id);
				$builder->add("payment_id", $payment_id);
				$builder->add("currency_id", $currency_id);
				$builder->add("rel_id", $loyalty_point_id);
				$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "account_payment_line_local"));
				$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("amount", $payment_amount);
				$builder->add("status", 0);
				$builder->add("description", $payment_description);
				$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
				
				$sql = $appSession->getTier()->getInsert($builder);
				$msg->add("query", $sql);
				$appSession->getTier()->exec($msg);
				
			}else{
				$message = "Point is over";
			}
		}else if($payment_category_name == "VOUCHER")
		{
			$vouchers = "";
			if(isset($_REQUEST['vouchers']))
			{
				$vouchers = $_REQUEST['vouchers'];
			}
			
			
			$arr = $appSession->getTool()->split($vouchers, ",");
			$vouchers = "";
			for($i =0; $i<count($arr); $i++)
			{
				if($vouchers != "")
				{
					$vouchers = $vouchers." OR ";
				}
				$vouchers = $vouchers." d2.code='".$arr[$i]."'";
			}
			
		
			if($vouchers != "")
			{
				$sql = "SELECT SUM(d1.quantity * d1.unit_price) FROM sale_product_local d1 WHERE d1.status=0 AND d1.sale_id='".$sale_id."'";
				$msg->add("query", $sql);
				
				$sale_amount = $appSession->getTool()->toDouble($appSession->getTier()->getValue($msg));
				
				$sql = "SELECT d2.id, d3.currency_id, d3.amount FROM voucher_line_rel d1 LEFT OUTER JOIN voucher_line d2 ON(d1.line_id = d2.id) LEFT OUTER JOIN voucher d3 ON(d2.voucher_id = d3.id) WHERE d1.status =0 AND d2.status =0 AND d1.line_id NOT IN(select voucher_line_id FROM voucher_log WHERE status=0) AND (".$vouchers.")";
				$sql = $sql." ORDER BY d1.create_date DESC";
				
				$msg->add("query", $sql);
				
				$values = $appSession->getTier()->getArray($msg);
				$paid = 0;
				for($i =0; $i<count($values); $i++)
				{
					$voucher_line_id = $values[$i][0];
					$currency_id = $values[$i][1];
					$amount = $appSession->getTool()->toDouble($values[$i][2]);
					$payment_amount = $sale_amount- $paid;
					$paid = $paid + $amount;
					if($payment_amount>$amount)
					{
						$payment_amount = $amount;
					}
					$log_id = $appSession->getTool()->getId();
					$builder = $appSession->getTier()->createBuilder("voucher_log");
					$builder->add("id", $log_id);
					$builder->add("create_uid", $user_id);
					$builder->add("write_uid", $user_id);
					$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("voucher_line_id", $voucher_line_id);
					$builder->add("rel_id", $sale_id);
					$builder->add("status", 0);
					$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
					
					$sql = $appSession->getTier()->getInsert($builder);
					
					$msg->add("query", $sql);
					$appSession->getTier()->exec($msg);
					
					
					$builder->clear();
					$builder->setName("account_payment_line_local");
					
					$builder->add("id", $appSession->getTool()->getId());
					$builder->add("create_uid", $user_id);
					$builder->add("write_uid", $user_id);
					$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("line_id", $sale_id);
					$builder->add("payment_id", $payment_id);
					$builder->add("currency_id", $currency_id);
					$builder->add("rel_id", $log_id);
					$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "account_payment_line_local"));
					$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("amount", $payment_amount);
					$builder->add("status", 0);
					$builder->add("description", $payment_description);
					$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
					
					$sql = $appSession->getTier()->getInsert($builder);
					$msg->add("query", $sql);
					$appSession->getTier()->exec($msg);
				}
				
			}
		}
	}
	if($message == "OK")
	{
		
		echo "OK:".$sale->checkOutBySaleId($sale_id, $name, $company_id, $tel, $email, $address_id,$address, $description, $customer_id);
	}else{
		echo $message;
	}
	
	
}else if($ac == "cancelOrder")
{
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$sale = new Sale($appSession);
	echo $sale->cancelBySaleId($sale_id);
}
else if($ac == "removeCard")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sale = new Sale($appSession);
	echo $sale->removeCard($id);
}else if($ac == "order_list")
{
	$msg = $appSession->getTier()->createMessage();
	
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	$sql = "SELECT d1.id, d1.order_no, d1.order_date, (SELECT COUNT(id) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS items, (SELECT SUM(quantity * ((d1.second_quantity/d1.factor) * unit_price)) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS amount, (SELECT name FROM res_status_line WHERE rel_id=d1.id AND status =0 ORDER BY create_date DESC LIMIT 1) AS status_name, d1.status, d2.commercial_name FROM sale_local d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id = d2.id) WHERE (d1.status=0 OR d1.status=3)";
	if($user_id != "")
	{
		$sql = $sql." AND (d1.session_id='".$session_id."' OR d1.create_uid='".$user_id."')";
	}else{
		$sql = $sql." AND d1.session_id='".$session_id."'";
	}
	$sql = $sql." ORDER BY d1.order_date DESC";

	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
		
}else if($ac == "sale_info"){
	$msg = $appSession->getTier()->createMessage();
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$sql = "SELECT d1.id, d1.order_no, d1.order_date, (SELECT name FROM res_status_line WHERE rel_id=d1.id AND status =0 ORDER BY create_date DESC LIMIT 1) AS status_name, d1.status, d2.commercial_name FROM sale_local d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id = d2.id) WHERE d1.id='".$sale_id."'";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);

}else if($ac == "sale_product_by_id"){
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	
	$sale = new Sale($appSession);

	$dt = $sale->productListSaleId($sale_id);
	echo respTable($dt);
}
else if($ac == "sale_product_update_quantity")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$quantity = "";
	if(isset($_REQUEST['quantity']))
	{
		$quantity = $_REQUEST['quantity'];
	}
	$sql = "UPDATE sale_product_local SET quantity = ".$quantity.", write_date=NOW() WHERE id='".$id."'";
	$msg = $appSession->getTier()->createMessage();
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo $id;
}else if($ac == "customer")
{
	$msg = $appSession->getTier()->createMessage();
	$id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$id = $_REQUEST['customer_id'];
	}
	
	$sql = "SELECT d1.id, d1.name, d1.phone, d1.email, d1.address";
	$sql =$sql." FROM customer d1 WHERE d1.id='".$id."'";
	$sql = $sql." ORDER BY d1.name ASC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	
	echo respTable($dt);
}else if($ac == "update_sale_session")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleIdBySessionId($session_id, "");
	
	$sql = "UPDATE sale_local SET create_uid = '".$user_id."', write_date=NOW() WHERE id='".$sale_id."'";
	$msg = $appSession->getTier()->createMessage();
	
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "update_sale_session_web")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	if($user_id == "")
	{
		$user_id = $appSession->getUserInfo()->getId();
	}
	
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	if($session_id == "")
	{
		$session_id = $appSession->getConfig()->getProperty("session_id");
		
		if($session_id == "")
		{
			$session_id = $appSession->getTool()->getId();
			$appSession->getConfig()->setProperty("session_id", $session_id);
			$appSession->getConfig()->save();
		}
	}
	
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleIdBySessionId($session_id, "");
	
	$sql = "UPDATE sale_local SET create_uid = '".$user_id."', write_date=NOW() WHERE id='".$sale_id."'";
	$msg = $appSession->getTier()->createMessage();

	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}
else if($ac == "payment_method"){
	
	$msg = $appSession->getTier()->createMessage();
	$sql = "SELECT d1.id, d1.name, d2.name AS category_name FROM account_payment d1";
	$sql = $sql." LEFT OUTER JOIN account_payment_category d2 ON(d1.category_id = d2.id)";
	$sql = $sql." WHERE d1.status =0 AND d2.status =0";
	$sql = $sql." AND (d2.name='WALLET' OR d2.name='LOYALTY' OR d2.name='VOUCHER')";
	$sql = $sql." ORDER BY d1.sequence, d2.sequence ASC";

	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}else if($ac == "payment_type_amount")
{
	$msg = $appSession->getTier()->createMessage();
	
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	$category_name = "";
	if(isset($_REQUEST['category_name']))
	{
		$category_name = $_REQUEST['category_name'];
	}
	if($category_name == "WALLET")
	{
		$sql = "SELECT SUM(d1.amount * d1.factor) FROM wallet d1";
		$sql = $sql." WHERE d1.customer_id='".$customer_id."' AND d1.status=0";
		$msg->add("query", $sql);
		
		$amount = $appSession->getTier()->getValue($msg);
		echo $amount;
	}else if($category_name == "LOYALTY")
	{
		$sale = new Sale($appSession);
		$sale_id = $sale->findSaleIdBySessionId($session_id, $user_id);
		
		$sql = "SELECT SUM(d1.point * d1.factor) FROM loyalty_point d1";
		$sql = $sql." WHERE d1.customer_id='".$customer_id."' AND d1.status =0";
		$msg->add("query", $sql);
		$point = $appSession->getTool()->toDouble($appSession->getTier()->getValue($msg));
		$sql = "SELECT SUM(d1.quantity * d1.unit_price) FROM sale_product_local d1";
		$sql = $sql." WHERE d1.sale_id='".$sale_id."' AND d1.status =0";
		$msg->add("query", $sql);
		$amount = $appSession->getTool()->toDouble($appSession->getTier()->getValue($msg));
		
		$sql = "SELECT point, amount, currency_id FROM loyalty_exchange WHERE status =0 ORDER BY sequence ASC";
		$msg->add("query", $sql);
		$exchanges = $appSession->getTier()->getArray($msg);
		if(count($exchanges)>0)
		{
			$exchange_point = $appSession->getTool()->toDouble($exchanges[0][0]);
			$exchange_amount = $appSession->getTool()->toDouble($exchanges[0][1]);
			$rate = $appSession->getTool()->toInt($point/$exchange_point);
			for($i = 1; $i<$rate; $i++)
			{
				$a = $exchange_amount * $rate;
				if($a>$amount)
				{
					$rate = $i;
					break;
				}
			}
			echo ($rate * $exchange_point).":".($rate * $exchange_amount);
		}
	}else if($category_name == "VOUCHER")
	{
		$customer_id = "";
		if(isset($_REQUEST['customer_id']))
		{
			$customer_id = $_REQUEST['customer_id'];
		}
		$sql = "SELECT d3.name, d2.code, d3.exp_date, d3.amount, d3.currency_id, d4.code AS currency_code FROM voucher_line_rel d1 LEFT OUTER JOIN voucher_line d2 ON(d1.line_id = d2.id) LEFT OUTER JOIN voucher d3 ON(d2.voucher_id = d3.id) LEFT OUTER JOIN res_currency d4 ON(d3.currency_id = d4.id) WHERE d1.status =0 AND d2.status =0 AND d1.line_id NOT IN(select voucher_line_id FROM voucher_log WHERE status=0) AND d1.rel_id='".$customer_id."'";
		$sql = $sql." ORDER BY d1.create_date DESC";
		$msg->add("query", $sql);
		$dt = $appSession->getTier()->getTable($msg);
		respTable($dt);
	}
}else if($ac == "payment_type_amount_web")
{
	$msg = $appSession->getTier()->createMessage();
	
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	$category_name = "";
	if(isset($_REQUEST['category_name']))
	{
		$category_name = $_REQUEST['category_name'];
	}
	if($category_name == "WALLET")
	{
		$sql = "SELECT SUM(d1.amount * d1.factor) FROM wallet d1";
		$sql = $sql." WHERE d1.customer_id='".$customer_id."' AND d1.status=0";
		$msg->add("query", $sql);
		
		$amount = $appSession->getTier()->getValue($msg);
		echo $amount;
	}else if($category_name == "LOYALTY")
	{
		$sale = new Sale($appSession);
		$sale_id = $sale->findSaleIdBySessionId($session_id, $user_id);
		
		$sql = "SELECT SUM(d1.point * d1.factor) FROM loyalty_point d1";
		$sql = $sql." WHERE d1.customer_id='".$customer_id."' AND d1.status =0";
		$msg->add("query", $sql);
		$point = $appSession->getTool()->toDouble($appSession->getTier()->getValue($msg));
		$sql = "SELECT SUM(d1.quantity * d1.unit_price) FROM sale_product_local d1";
		$sql = $sql." WHERE d1.sale_id='".$sale_id."' AND d1.status =0";
		$msg->add("query", $sql);
		$amount = $appSession->getTool()->toDouble($appSession->getTier()->getValue($msg));
		
		$sql = "SELECT point, amount, currency_id FROM loyalty_exchange WHERE status =0 ORDER BY sequence ASC";
		$msg->add("query", $sql);
		$exchanges = $appSession->getTier()->getArray($msg);
		$points = "";
		$currency_id = $appSession->getConfig()->getProperty("currency_id");
		if(count($exchanges)>0)
		{
			$exchange_point = $appSession->getTool()->toDouble($exchanges[0][0]);
			$exchange_amount = $appSession->getTool()->toDouble($exchanges[0][1]);
			$rate = $appSession->getTool()->toInt($point/$exchange_point);
			for($i = 1; $i<$rate; $i++)
			{
				$a = $exchange_amount * $rate;
				if($points != "")
				{
					$points = $points.";";
				}
				$points = $points.($i * $exchange_point).":".$appSession->getCurrency()->format($currency_id, ($i * $exchange_amount)).":" .($i * $exchange_amount);
				if($a>$amount)
				{
					
					break;
				}
			}
			
		}
		echo $points;
	}else if($category_name == "VOUCHER")
	{
		$customer_id = "";
		if(isset($_REQUEST['customer_id']))
		{
			$customer_id = $_REQUEST['customer_id'];
		}
		$sql = "SELECT d3.name, d2.code, d3.exp_date, d3.amount, d3.currency_id FROM voucher_line_rel d1 LEFT OUTER JOIN voucher_line d2 ON(d1.line_id = d2.id) LEFT OUTER JOIN voucher d3 ON(d2.voucher_id = d3.id) LEFT OUTER JOIN res_currency d4 ON(d3.currency_id = d4.id) WHERE d1.status =0 AND d2.status =0 AND d1.line_id NOT IN(select voucher_line_id FROM voucher_log WHERE status=0) AND d1.rel_id='".$customer_id."' AND (d3.exp_date IS NULL OR d3.exp_date<NOW())";
		$sql = $sql." ORDER BY d1.create_date DESC";
		$msg->add("query", $sql);
		$dt = $appSession->getTier()->getTable($msg);
		$s = "";
		for($i=0; $i<$dt->getRowCount(); $i++)
		{
			if($s != "")
			{
				$s = $s."\n";
			}
			$currency_id = $dt->getString($i, "currency_id");
			$amount = $appSession->getTool()->toDouble($dt->getString($i, "amount"));
			$s = $s.$dt->getString($i, "name")."\t".$dt->getString($i, "code")."\t".$currency_id."\t".$appSession->getCurrency()->format($currency_id, $amount);
		}
		echo $s;
	}
}
else if($ac == "payment_line_local")
{
	$msg = $appSession->getTier()->createMessage();
	
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleIdBySessionId($session_id, $user_id);
	$sql = "SELECT d1.id, d1.currency_id, d1.amount, d3.code AS currency_code, d2.name AS payment_name, d1.description";
	$sql = $sql." FROM account_payment_line_local d1";
	$sql = $sql." LEFT OUTER JOIN account_payment d2 ON(d1.payment_id = d2.id)";
	$sql = $sql." LEFT OUTER JOIN res_currency d3 ON(d1.currency_id = d3.id)";
	$sql = $sql." WHERE d1.line_id='".$sale_id."' AND d1.status =0";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
	
}
else if($ac == "total_sale_amount")
{
	$msg = $appSession->getTier()->createMessage();
	
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleIdBySessionId($session_id, $user_id);

	
	echo $sale->totalSaleLocal($sale_id);
	
}else if($ac == "sale_payment"){
	
	$msg = $appSession->getTier()->createMessage();
	
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$sql = "SELECT d1.id, d1.currency_id, d1.amount, d3.code AS currency_code, d2.name AS payment_name";
	$sql = $sql." FROM account_payment_line_local d1";
	$sql = $sql." LEFT OUTER JOIN account_payment d2 ON(d1.payment_id = d2.id)";
	$sql = $sql." LEFT OUTER JOIN res_currency d3 ON(d1.currency_id = d3.id)";
	$sql = $sql." WHERE d1.line_id='".$sale_id."' AND d1.status =0";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}else if($ac == "sale_payment_local"){
	
	$msg = $appSession->getTier()->createMessage();
	
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$sql = "SELECT d1.id, d1.currency_id, d1.amount, d3.code AS currency_code, d2.name AS payment_name";
	$sql = $sql." FROM account_payment_line_local d1";
	$sql = $sql." LEFT OUTER JOIN account_payment d2 ON(d1.payment_id = d2.id)";
	$sql = $sql." LEFT OUTER JOIN res_currency d3 ON(d1.currency_id = d3.id)";
	$sql = $sql." WHERE d1.line_id='".$sale_id."' AND d1.status =0";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}
else if($ac == "sale_point"){
	
	$msg = $appSession->getTier()->createMessage();
	
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$sql = "SELECT d1.point, d2.name AS loyalty_name, d3.name AS category_name FROM loyalty_point d1 LEFT OUTER JOIN loyalty d2 ON(d1.loyalty_id = d2.id) LEFT OUTER JOIN loyalty_point_category d3 ON(d1.category_id = d3.id) WHERE d1.rel_id='".$sale_id."' AND d1.status =0 ORDER BY d1.create_date ASC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}else if($ac == "sale_status"){
	
	$msg = $appSession->getTier()->createMessage();
	
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$sql = "SELECT d1.create_date, d1.name, d1.description FROM res_status_line d1 WHERE d1.rel_id='".$sale_id."' ORDER BY d1.create_date ASC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}else if($ac == "addPayment"){
	$msg = $appSession->getTier()->createMessage();
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
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
	
	$payment_id = "";
	if(isset($_REQUEST['payment_id']))
	{
		$payment_id = $_REQUEST['payment_id'];
	}
	$payment_amount = "";
	if(isset($_REQUEST['payment_amount']))
	{
		$payment_amount = $_REQUEST['payment_amount'];
	}
	$payment_amount = $appSession->getTool()->toDouble($payment_amount);
	if(isset($_REQUEST['paid']))
	{
		$paid = $_REQUEST['paid'];
	}
	$payment_points = "";
	if(isset($_REQUEST['payment_points']))
	{
		$payment_points = $_REQUEST['payment_points'];
	}
	$payment_description = "";
	if(isset($_REQUEST['payment_description']))
	{
		$payment_description = $_REQUEST['payment_description'];
	}
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleIdBySessionId($session_id, $user_id);
	$paid = $sale->totalSaleLocal($sale_id);
	if($payment_amount>$paid){
		$payment_amount = $paid;
	}
	$message = "OK";
	if($payment_id != "")
	{
		$sql = "SELECT d2.name FROM account_payment d1 LEFT OUTER JOIN account_payment_category d2 ON(d1.category_id = d2.id) WHERE d1.id='".$payment_id."'";
		$msg->add("query", $sql);
		$payment_category_name = $appSession->getTier()->getValue($msg);
		
		if($payment_category_name == "WALLET")
		{
			
			$sql = "SELECT d1.currency_id, SUM(d1.amount * d1.factor) FROM wallet d1";
			$sql = $sql." WHERE d1.customer_id='".$customer_id."' AND d1.status=0 GROUP BY d1.currency_id";
		
			$msg->add("query", $sql);
			$values = $appSession->getTier()->getArray($msg);
			$balance = 0;
			$currency_id = "";
			if(count($values)>0){
				$currency_id = $values[0][0];
				$balance = $appSession->getTool()->toDouble($values[0][1]);
			}
			if($balance>=$payment_amount)
			{
				$category_id = "119d1566-cab9-4a65-cc94-75c42a0dfafd";
				if($currency_id == ""){
					$currency_id = $appSession->getConfig()->getProperty("currency_id");
				}
				
				$wallet_id = $appSession->getTool()->getId();
				$builder = $appSession->getTier()->createBuilder("wallet");
				$builder->add("id", $wallet_id);
				$builder->add("create_uid", $user_id);
				$builder->add("write_uid", $user_id);
				$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("factor", -1);
				$builder->add("customer_id", $customer_id);
				$builder->add("currency_id", $currency_id);
				$builder->add("category_id", $category_id);
				$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "wallet"));
				$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("rel_id", $sale_id);
				$builder->add("amount", $payment_amount);
				$builder->add("status", 0);
				$builder->add("description", $payment_description);
				$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
				
				$sql = $appSession->getTier()->getInsert($builder);
				$msg->add("query", $sql);
				$appSession->getTier()->exec($msg);
				
				
				$builder->clear();
				$builder->setName("account_payment_line_local");
				
				$builder->add("id", $appSession->getTool()->getId());
				$builder->add("create_uid", $user_id);
				$builder->add("write_uid", $user_id);
				$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("line_id", $sale_id);
				$builder->add("payment_id", $payment_id);
				$builder->add("currency_id", $currency_id);
				$builder->add("rel_id", $wallet_id);
				$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "account_payment_line_local"));
				$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("amount", $payment_amount);
				$builder->add("status", 0);
				$builder->add("description", $payment_description);
				$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
				
				$sql = $appSession->getTier()->getInsert($builder);
				$msg->add("query", $sql);
				$appSession->getTier()->exec($msg);
				
				
			}else{
				$message = "Payment is over";
			}
			
		}else if($payment_category_name == "LOYALTY")
		{
			
			$payment_points = $appSession->getTool()->toDouble($payment_points);
			$sql = "SELECT SUM(d1.point * d1.factor) FROM loyalty_point d1";
			$sql = $sql." WHERE d1.customer_id='".$customer_id."' AND d1.status=0";
		
			$msg->add("query", $sql);
			$balance = $appSession->getTier()->getValue($msg);
			$balance = $appSession->getTool()->toDouble($balance);
			if($balance>=$payment_points)
			{
				$category_id = "db3e3283-701b-4964-920c-20daceefd5ca";
				$currency_id = $appSession->getConfig()->getProperty("currency_id");
				$loyalty_point_id = $appSession->getTool()->getId();
				$builder = $appSession->getTier()->createBuilder("loyalty_point");
				$builder->add("id", $loyalty_point_id);
				$builder->add("create_uid", $user_id);
				$builder->add("write_uid", $user_id);
				$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("factor", -1);
				$builder->add("customer_id", $customer_id);
				$builder->add("category_id", $category_id);
				$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "loyalty_point"));
				$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("rel_id", $sale_id);
				$builder->add("point", $payment_points);
				$builder->add("status", 0);
				$builder->add("description", $payment_description);
				$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
				
				$sql = $appSession->getTier()->getInsert($builder);
				$msg->add("query", $sql);
				$appSession->getTier()->exec($msg);
				
				
				$builder->clear();
				$builder->setName("account_payment_line_local");
				
				$builder->add("id", $appSession->getTool()->getId());
				$builder->add("create_uid", $user_id);
				$builder->add("write_uid", $user_id);
				$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("line_id", $sale_id);
				$builder->add("payment_id", $payment_id);
				$builder->add("currency_id", $currency_id);
				$builder->add("rel_id", $loyalty_point_id);
				$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "account_payment_line_local"));
				$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
				$builder->add("amount", $payment_amount);
				$builder->add("status", 0);
				$builder->add("description", $payment_description);
				$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
				
				$sql = $appSession->getTier()->getInsert($builder);
				$msg->add("query", $sql);
				$appSession->getTier()->exec($msg);
				
			}else{
				$message = "Point is over";
			}
		}else if($payment_category_name == "VOUCHER")
		{
			$vouchers = "";
			if(isset($_REQUEST['vouchers']))
			{
				$vouchers = $_REQUEST['vouchers'];
			}
			
			
			$arr = $appSession->getTool()->split($vouchers, ",");
			$vouchers = "";
			for($i =0; $i<count($arr); $i++)
			{
				if($vouchers != "")
				{
					$vouchers = $vouchers." OR ";
				}
				$vouchers = $vouchers." d2.code='".$arr[$i]."'";
			}
			
		
			if($vouchers != "")
			{
				$sql = "SELECT SUM(d1.quantity * d1.unit_price) FROM sale_product_local d1 WHERE d1.status=0 AND d1.sale_id='".$sale_id."'";
				$msg->add("query", $sql);
				
				$sale_amount = $appSession->getTool()->toDouble($appSession->getTier()->getValue($msg));
				
				$sql = "SELECT d2.id, d3.currency_id, d3.amount FROM voucher_line d2 LEFT OUTER JOIN voucher d3 ON(d2.voucher_id = d3.id) WHERE d2.status =0 AND d2.status =0 AND d2.id NOT IN(select voucher_line_id FROM voucher_log WHERE status=0) AND (".$vouchers.")";
				

				
				$msg->add("query", $sql);
				
				$values = $appSession->getTier()->getArray($msg);
				$paid = 0;
				for($i =0; $i<count($values); $i++)
				{
					$voucher_line_id = $values[$i][0];
					$currency_id = $values[$i][1];
					$amount = $appSession->getTool()->toDouble($values[$i][2]);
					$payment_amount = $sale_amount- $paid;
					$paid = $paid + $amount;
					if($payment_amount>$amount)
					{
						$payment_amount = $amount;
					}
					$log_id = $appSession->getTool()->getId();
					$builder = $appSession->getTier()->createBuilder("voucher_log");
					$builder->add("id", $log_id);
					$builder->add("create_uid", $user_id);
					$builder->add("write_uid", $user_id);
					$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("voucher_line_id", $voucher_line_id);
					$builder->add("rel_id", $sale_id);
					$builder->add("status", 0);
					$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
					
					$sql = $appSession->getTier()->getInsert($builder);
					
					$msg->add("query", $sql);
					$appSession->getTier()->exec($msg);
					
					
					$builder->clear();
					$builder->setName("account_payment_line_local");
					
					$builder->add("id", $appSession->getTool()->getId());
					$builder->add("create_uid", $user_id);
					$builder->add("write_uid", $user_id);
					$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("line_id", $sale_id);
					$builder->add("payment_id", $payment_id);
					$builder->add("currency_id", $currency_id);
					$builder->add("rel_id", $log_id);
					$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "account_payment_line_local"));
					$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("amount", $payment_amount);
					$builder->add("status", 0);
					$builder->add("description", $payment_description);
					$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
					
					$sql = $appSession->getTier()->getInsert($builder);
					$msg->add("query", $sql);
					$appSession->getTier()->exec($msg);
				}
				
			}
		}
	}
	echo "OK";
}
else if($ac == "removePayment")
{
	$msg = $appSession->getTier()->createMessage();
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sql = "SELECT rel_id FROM account_payment_line_local WHERE id='".$id."'";
	$msg->add("query", $sql);
	$rel_id = $appSession->getTier()->getValue($msg);
	if($rel_id != ""){
		$sql = "UPDATE voucher_log SET status = 1, write_date=NOW() WHERE id='".$rel_id."'";
		
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
		$sql = "UPDATE loyalty_point SET status = 1, write_date=NOW() WHERE id='".$rel_id."'";
		
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
		$sql = "UPDATE wallet SET status = 1, write_date=NOW() WHERE id='".$rel_id."'";
	
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
	}
				
	$sql = "UPDATE account_payment_line_local SET status = 1, write_date=NOW() WHERE id='".$id."'";
	
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "saveDeliveryInfo")
{
	$delivery_name = "";
	if(isset($_REQUEST['delivery_name']))
	{
		$delivery_name = $_REQUEST['delivery_name'];
	}
	$delivery_tel = "";
	if(isset($_REQUEST['delivery_tel']))
	{
		$delivery_tel = $_REQUEST['delivery_tel'];
	}
	$delivery_email = "";
	if(isset($_REQUEST['delivery_email']))
	{
		$delivery_email = $_REQUEST['delivery_email'];
	}
	$delivery_address = "";
	if(isset($_REQUEST['delivery_address']))
	{
		$delivery_address = $_REQUEST['delivery_address'];
	}
	$delivery_description = "";
	if(isset($_REQUEST['delivery_description']))
	{
		$delivery_description = $_REQUEST['delivery_description'];
	}
	$address_id = "";
	if(isset($_REQUEST['address_id']))
	{
		$address_id = $_REQUEST['address_id'];
	}
	
	$appSession->getConfig()->setProperty("delivery_name", $delivery_name);
	$appSession->getConfig()->setProperty("delivery_tel", $delivery_tel);
	$appSession->getConfig()->setProperty("delivery_email", $delivery_email);
	$appSession->getConfig()->setProperty("delivery_address", $delivery_address);
	$appSession->getConfig()->setProperty("delivery_description", $delivery_description);
	$appSession->getConfig()->setProperty("delivery_address_id", $address_id);
	$appSession->getConfig()->save();
	echo "OK";
	
}else if($ac == "res_address")
{
	$msg = $appSession->getTier()->createMessage();
	$parent_id = "";
	if(isset($_REQUEST['parent_id']))
	{
		$parent_id = $_REQUEST['parent_id'];
	}
	if($parent_id != "")
	{
		$sql = "SELECT id, name FROM res_address WHERE parent_id='".$parent_id."' AND status =0 ORDER BY name ASC";
		$msg->add("query", $sql);
		$arr = $appSession->getTier()->getArray($msg);
		for($i=0; $i<count($arr); $i++)
		{
			if($i>0)
			{
				echo "\n";
			}
			echo $arr[$i][0]."=".$arr[$i][1];
		}
	}
	
}
?>