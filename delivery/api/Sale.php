<?php
require_once(ABSPATH.'api/Status.php' );
require_once(ABSPATH.'api/WebService.php' );
require_once(ABSPATH.'api/RowLine.php' );
require_once(ABSPATH.'api/Account.php' );
class Sale
{
	public $appSession;
	public $msg;
	public function __construct($appSession) {
		$this->appSession = $appSession;
		$this->msg = $appSession->getTier()->createMessage();
	}
	public function findSaleIdBySessionId($session_id, $user_id)
	{
		$sql = "SELECT d1.id FROM sale_local d1 WHERE d1.status = 2";
		if($user_id != ""){
			$sql = $sql." AND d1.create_uid='".$user_id."'";
		}else{
			$sql = $sql." AND d1.session_id='".$session_id."'";
		}
		$sql = $sql." ORDER BY d1.create_date DESC";
		$this->msg->add("query", $sql);
		
		$sale_id = $this->appSession->getTier()->getValue($this->msg);
		$builder = $this->appSession->getTier()->createBuilder("sale_local");
		if($sale_id == "")
		{
			$sale_id = $this->appSession->getTool()->getId();
			$builder->add("id", $sale_id);
			$builder->add("create_uid", $user_id);
			$builder->add("write_uid", $user_id);
			$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("receipt_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("session_id", $session_id);
			$builder->add("status", 2);
			$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
			$builder->add("customer_id", $this->appSession->getConfig()->getProperty("customer_id"));
			$sql = $this->appSession->getTier()->getInsert($builder);
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		return $sale_id;
	}
	
	public function findSaleId()
	{
		$session_id = $this->appSession->getConfig()->getProperty("session_id");
		
		if($session_id == "")
		{
			$session_id = $this->appSession->getTool()->getId();
			$this->appSession->getConfig()->setProperty("session_id", $session_id);
			$this->appSession->getConfig()->save();
		}
		if($this->appSession->getUserInfo()->getId() != "")
		{
			$session_id = "";
		}
		return $this->findSaleIdBySessionId($session_id, $this->appSession->getUserInfo()->getId());
	}
	public function addProductBySaleId($sale_id, $user_id, $product_id, $currency_id, $unit_id, $attribute_id, $type_id, $rel_id, $quantity, $unit_price, $second_unit_id, $factor, $description)
	{
		
		$sql = "SELECT id FROM sale_product_local WHERE status =0 AND product_id='".$product_id."' AND unit_id='".$unit_id."' AND attribute_id='".$attribute_id."' AND unit_price=".$unit_price." AND sale_id='".$sale_id."'";
		$this->msg->add("query", $sql);
		$sale_product_id = $this->appSession->getTier()->getValue($this->msg);
		if($sale_product_id == "")
		{
			if($quantity == 0 || $quantity== "0")
			{
				exit();
			}
			
			$builder = $this->appSession->getTier()->createBuilder("sale_product_local");
			$sale_product_id = $this->appSession->getTool()->getId();
			$builder->add("id", $sale_product_id);
			$builder->add("sale_id", $sale_id);
			$builder->add("create_uid", $user_id);
			$builder->add("write_uid", $user_id);
			$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("product_id", $product_id);
			$builder->add("unit_id", $unit_id);
			$builder->add("attribute_id", $attribute_id);
			$builder->add("currency_id", $currency_id);
			$builder->add("quantity", $quantity);
			$builder->add("unit_price", $unit_price);
			$builder->add("factor", $factor);
			$builder->add("second_quantity", $factor);
			$builder->add("second_unit_id", $second_unit_id);
			$builder->add("type_id", $type_id);
			$builder->add("rel_id", $rel_id);
			$builder->add("status", 0);
			$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
			$sql = $this->appSession->getTier()->getInsert($builder);
			
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}else{
			
			$sql = "UPDATE sale_product_local SET write_date=".$this->appSession->getTier()->getDateString();
			$sql = $sql.", quantity = ".$quantity;
			if($quantity == 0 || $quantity== "0")
			{
				$sql = $sql.", status = 1";
			}else{
				$sql = $sql.", status = 0";
			}
			$sql = $sql." WHERE id='".$sale_product_id."'";
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		
		return $sale_product_id;
	}
	
	public function addProduct($product_id, $currency_id, $unit_id, $attribute_id, $quantity, $unit_price, $second_unit_id, $factor, $description)
	{
		$sale_id = $this->findSaleId();
		
		return $this->addProductBySaleId($sale_id, $this->appSession->getUserInfo()->getId(), $product_id, $currency_id, $unit_id, $attribute_id, $quantity, $unit_price, $second_unit_id, $factor, $description);
	}
	public function getSaleItemCount($sale_id)
	{
		$sql = "SELECT SUM(d1.quantity) FROM sale_product_local d1 WHERE d1.status =0 AND d1.sale_id='".$sale_id."'";
		$this->msg->add("query", $sql);
		$arr = $this->appSession->getTier()->getArray($this->msg);
		if(count($arr)>0 && $arr[0][0] != "")
		{
			return $arr[0][0];
		}
		return "0";
	}
	public function getItemCount()
	{
		$sale_id = $this->findSaleId();
		return $this->getSaleItemCount($sale_id);
	}
	
	
	public function productListSaleId($sale_id)
	{
		
		$sql = "SELECT m.id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d3.name AS unit_name, d4.code AS currency_code, m.quantity, m.unit_price, m.unit_id, m.currency_id, d1.description, m.attribute_id, d5.name AS attribute_name, d6.name AS attribute_category_name";
		$sql =$sql.", m.factor, m.second_unit_id, d7.name AS second_unit_name";
		$sql = $sql." FROM sale_product_local m";
		$sql = $sql." LEFT OUTER JOIN product d1 ON(m.product_id = d1.id)";
		$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1)";
		$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$this->appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
		$sql = $sql." LEFT OUTER JOIN product_unit d3 ON(m.unit_id = d3.id)";
		$sql = $sql." LEFT OUTER JOIN res_currency d4 ON(m.currency_id = d4.id)";
		$sql = $sql." LEFT OUTER JOIN attribute d5 ON(m.attribute_id = d5.id)";
		$sql = $sql." LEFT OUTER JOIN attribute_category d6 ON(d5.category_id = d6.id)";
		$sql = $sql." LEFT OUTER JOIN product_unit d7 ON(m.second_unit_id = d7.id)";
		$sql = $sql." WHERE m.status =0 AND m.sale_id='".$sale_id."'";
		$sql = $sql." ORDER BY m.create_date ASC";
		
		$this->msg->add("query", $sql);
		
		$dt = $this->appSession->getTier()->getTable($this->msg);
		return $dt;
	}
	public function productList()
	{
		$sale_id = $this->findSaleId();
		
		return $this->productListSaleId($sale_id);
	}
	public function checkOutBySaleId($sale_id, $name, $company_id, $tel, $email, $address_id, $address, $description, $customer_id)
	{
		$sql = "SELECT id FROM sale_shipping WHERE sale_id='".$sale_id."'";
		$this->msg->add("query", $sql);
		$shipping_id = $this->appSession->getTier()->getValue($this->msg);
		if($shipping_id == "")
		{
			$builder = $this->appSession->getTier()->createBuilder("sale_shipping");
			$id = $this->appSession->getTool()->getId();
			$builder->add("id", $id);
			$builder->add("sale_id", $sale_id);
			$builder->add("create_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("write_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("company_id", $company_id);
			$builder->add("name", $name);
			$builder->add("tel", $tel);
			$builder->add("email", $email);
			$builder->add("address", $address);
			$builder->add("description", $description);
			$builder->add("address_id", $address_id);
			$builder->add("status", 0);
			$sql = $this->appSession->getTier()->getInsert($builder);

			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		
		
		$sql = "SELECT order_no FROM sale_local WHERE id='".$sale_id."'";
		$this->msg->add("query", $sql);
		$order_no = $this->appSession->getTier()->getValue($this->msg);
		if($order_no == ""){
			$order_no = $this->appSession->getTool()->findReceiptNo($this->appSession->getTier(), $this->appSession->getConfig()->getProperty("company_id"), "sale_local.order");
			$order_no = $this->appSession->getTool()->paddingLeft($order_no, '0', 6);
		}
		
		
		$sql = "UPDATE sale_local SET company_id='".$company_id."', status=3, order_date=".$this->appSession->getTier()->getDateString().", required_date =".$this->appSession->getTier()->getDateString().", order_no='".$order_no."', write_date=".$this->appSession->getTier()->getDateString().", customer_id='".$customer_id."', session_id='".$this->appSession->getConfig()->getProperty("session_id")."' WHERE id='".$sale_id."'";
		$this->msg->add("query", $sql);
		
		$this->appSession->getTier()->exec($this->msg);
		
		$sql = "UPDATE sale_product_local SET company_id='".$company_id."', write_date=".$this->appSession->getTier()->getDateString()." WHERE sale_id ='".$sale_id."'";
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
		
		$sql = "UPDATE account_payment_line_local SET company_id='".$company_id."', write_date=".$this->appSession->getTier()->getDateString()." WHERE line_id ='".$sale_id."'";
		
		$sql = "UPDATE account_service_line_local SET company_id='".$company_id."', write_date=".$this->appSession->getTier()->getDateString()." WHERE rel_id ='".$sale_id."'";
		
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
		
		$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=sale_product_local");
		$ws =new WebService($this->appSession);
		$ws->sendMessage($data);
		
		$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=sale_shipping");
		$ws =new WebService($this->appSession);
		$ws->sendMessage($data);
		
		$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=account_payment_line_local");
		$ws =new WebService($this->appSession);
		$ws->sendMessage($data);
		
		$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=wallet");
		$ws =new WebService($this->appSession);
		$ws->sendMessage($data);
		
		$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=voucher_log");
		$ws =new WebService($this->appSession);
		$ws->sendMessage($data);
		
		$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=loyalty_point");
		$ws =new WebService($this->appSession);
		$ws->sendMessage($data);
		
		
		
		$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=sale_local");
		$ws =new WebService($this->appSession);
		$ws->sendMessage($data);
		
		
					
					
		$status = new Status($this->appSession);
		$status->doStatus($sale_id, "sale_local", "", $company_id);
		return $id;
		
	}
	public function checkOut($name, $company_id, $tel, $email, $address_id, $address, $description)
	{
		$sale_id = $this->findSaleId();
		echo $this->checkOutBySaleId($sale_id, $name, $company_id, $tel, $email, $address_id, $address, $description, "");
	}
	public function removeCard($id){
		
		$sql = "UPDATE sale_product_local SET status=1, write_date=NOW() WHERE id ='".$id."'";
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
		echo $id;
	}
	public function cancelBySaleId($sale_id)
	{
		$sql = "SELECT status FROM sale_local WHERE id='".$sale_id."'";
		$this->msg->add("query", $sql);
		$status = $this->appSession->getTier()->getValue($this->msg);
		if($status == "3")
		{
			$sql = "SELECT rel_id FROM account_payment_line_local WHERE line_id ='".$sale_id."' AND status =0";
	
			$this->msg->add("query", $sql);
			$arr = $this->appSession->getTier()->getArray($this->msg);
			for($i=0; $i<count($arr); $i++)
			{
				$sql = "UPDATE wallet SET status=1, write_date=".$this->appSession->getTier()->getDateString()." WHERE id ='".$arr[$i][0]."' AND status=0";
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
				
				$sql = "UPDATE loyalty_point SET status=1, write_date=".$this->appSession->getTier()->getDateString()." WHERE id ='".$arr[$i][0]."' AND status=0";
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
				
				$sql = "UPDATE voucher_log SET status=1, write_date=".$this->appSession->getTier()->getDateString()." WHERE id ='".$arr[$i][0]."' AND status=0";
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
			}
			$sql = "UPDATE account_payment_line_local SET status=1, write_date=".$this->appSession->getTier()->getDateString()." WHERE line_id ='".$sale_id."' AND status=0";
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
			
			$sql = "UPDATE sale_local SET status=1, write_date=".$this->appSession->getTier()->getDateString()." WHERE id ='".$sale_id."' AND status=3";
	
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
			
			$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=wallet");
			
			$ws =new WebService($this->appSession);
			$ws->sendMessage($data);
			
			$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=loyalty_point");
			$ws->sendMessage($data);
			
			$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=voucher_log");
			$ws->sendMessage($data);
			
			
			$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=account_payment_line_local");
			$ws->sendMessage($data);
			
			$data = "action=28d9aaeb-9535-4939-9eb6-c88f103cd256&id=".$company_id."&message=".$this->appSession->getTool()->urlEncode("type=database.hq.updated&name=sale_local");
			$ws->sendMessage($data);
			echo "OK";
			
			
		}else{
			return "DENY";
		}
	}
	public function totalSalePrice($sale_id)
	{
		$msg = $this->appSession->getTier()->createMessage();
	
	
		$sql = "SELECT d1.product_id, (d1.quantity * d1.unit_price) AS amount";
		$sql = $sql." FROM sale_product_local d1";
		$sql = $sql." WHERE d1.sale_id='".$sale_id."' AND d1.status =0";
		$msg->add("query", $sql);
		$amount = 0;
		$products = $this->appSession->getTier()->getArray($msg);
		for($i =0; $i<count($products); $i++)
		{
			$amount = $amount + $this->appSession->getTool()->toDouble($products[$i][1]);
		}
		
		
		
		return $amount;
	}
	public function totalSaleLocal($sale_id)
	{
		$msg = $this->appSession->getTier()->createMessage();
	
	
		$sql = "SELECT d1.product_id, (d1.quantity * d1.unit_price) AS amount";
		$sql = $sql." FROM sale_product_local d1";
		$sql = $sql." WHERE d1.sale_id='".$sale_id."' AND d1.status =0";
		$msg->add("query", $sql);
		$amount = 0;
		$products = $this->appSession->getTier()->getArray($msg);
		for($i =0; $i<count($products); $i++)
		{
			$amount = $amount + $this->appSession->getTool()->toDouble($products[$i][1]);
		}
		
		$sql = "SELECT SUM(d1.amount) AS amount";
		$sql = $sql." FROM account_payment_line_local d1";
		$sql = $sql." WHERE d1.line_id='".$sale_id."' AND d1.status =0";
		$msg->add("query", $sql);
		
		$payment_amount = 0;
		$sValue = $this->appSession->getTier()->getValue($msg);
		if($sValue != ""){
			$payment_amount = $this->appSession->getTool()->toDouble($sValue);
		}
		
		return $amount - $payment_amount;
	}
	public function checkSaleService($sale_id, $customer_id)
	{
		if($customer_id == "")
		{
			//$sql = "UPDATE account_service_line_local SET status =1, write_date=NOW() WHERE rel_id='".$sale_id."'";
			//$this->msg->add("query", $sql);
			//$this->appSession->getTier()->exec($this->msg);	
			return;
		}
		
		$sql = "SELECT d1.category_id FROM customer d1 WHERE d1.id = '".$customer_id."'";
		$this->msg->add("query", $sql);
		$category_id = $this->appSession->getTier()->getValue($this->msg);
		
		$sql = "SELECT d1.service_id, d2.percent, d2.value, d2.category_id, d3.operator, d3.sequence FROM account_service_rel d1 LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id) LEFT OUTER JOIN account_service_category d3 ON(d2.category_id = d3.id) WHERE d1.status =0 AND d2.status =0";
		$sql = $sql." AND d1.rel_id='".$customer_id."'";
		
		$this->msg->add("query", $sql);
		$arr = $this->appSession->getTier()->getArray($this->msg);
		
		if(count($arr) == 0 && $category_id != "")
		{
			$sql = "SELECT d1.service_id, d2.percent, d2.value, d2.category_id, d3.operator, d3.sequence FROM account_service_rel d1 LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id) LEFT OUTER JOIN account_service_category d3 ON(d2.category_id = d3.id) WHERE d1.status =0 AND d2.status =0";
			$sql = $sql." AND d1.rel_id='".$category_id."'";
			$this->msg->add("query", $sql);
			
			$arr = $this->appSession->getTier()->getArray($this->msg);
			
		}
		
		$this->msg->add("query", $sql);
		$arr = $this->appSession->getTier()->getArray($this->msg);
		for($i=0; $i<count($arr); $i++)
		{
			$service_id = $arr[$i][0];
			$sql = "SELECT d1.id FROM account_service_line_local d1 WHERE d1.rel_id = '".$sale_id."' AND d1.service_id='".$service_id."'";
			$this->msg->add("query", $sql);
			$service_line_id = $this->appSession->getTier()->getValue($this->msg);
			if($service_line_id != "")
			{
				$sql = "UPDATE account_service_line_local SET status =0, write_date=NOW() WHERE id='".$service_line_id."'";
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
				continue;
			}
		
			$percent = $arr[$i][1];
			if($percent == "")
			{
				$percent = 0;
			}
			$value = $arr[$i][2];
			if($value == "")
			{
				$value = 0;
			}
			$category_id = $arr[$i][3];
			$operator = $arr[$i][4];
			$sequence = $arr[$i][5];
			$builder = $this->appSession->getTier()->createBuilder("account_service_line_local");
			$service_line_id = $this->appSession->getTool()->getId();
			$builder->add("id", $service_line_id);
			$builder->add("service_id", $arr[$i][0]);
			$builder->add("rel_id", $sale_id);
			$builder->add("percent", $percent);
			$builder->add("value", $value);
			$builder->add("category_id", $category_id);
			$builder->add("operator", $operator);
			$builder->add("sequence", $sequence);
			$builder->add("create_uid", $this->appSession->getConfig()->getProperty("user_id"));
			$builder->add("write_uid", $this->appSession->getConfig()->getProperty("user_id"));
			$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("receipt_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("status", 0);
			$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
			$sql = $this->appSession->getTier()->getInsert($builder);
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		
		
	}
	function updateService($sale_id)
	{
		$company_currency_id = $this->appSession->getConfig()->getProperty("currency_id");
		$total = 0;
		$sql = "SELECT d1.id, d1.product_id, d1.attribute_id ";
		$sql = $sql.", d1.second_quantity, d1.factor, d1.second_unit_id";
		$sql = $sql.", d1.attribute_id, d1.currency_id, d1.unit_id, d1.quantity, d1.unit_price";
		$sql = $sql.", (d1.quantity * d1.unit_price) AS amount";
		$sql = $sql." FROM sale_product d1";
		$sql = $sql." WHERE d1.status =0 AND d1.quantity>0";
		if ($sale_id != "")
		{
			$sql = $sql." AND d1.sale_id = '".$sale_id."'";
		}
		else
		{
			$sql = $sql." AND 1=0";
		}
		$sql = $sql." ORDER BY d1.create_date ASC";
		$this->msg->add("query", $sql);
		$dt_product = $this->appSession->getTier()->getTable($this->msg);
		
		$sql = "SELECT d1.rel_id, d1.service_id, d1.percent, d1.value, d1.operator, d1.category_id, d1.id";
		$sql = $sql. " FROM account_service_line d1";
		$sql = $sql. " LEFT OUTER JOIN sale_product d2 ON(d1.rel_id = d2.id AND d2.status =0)";
		$sql = $sql. " WHERE (d1.rel_id ='".$sale_id."' OR d2.sale_id ='".$sale_id."')";
		$sql = $sql. " AND d1.status =0";
		$sql = $sql. " ORDER BY d1.sequence ASC, d1.create_date ASC";
		$this->msg->add("query", $sql);
		
		$dt_service = $this->appSession->getTier()->getTable($this->msg);	
		for ($i = 0; $i < $dt_product->getRowCount(); $i++)
		{
			$currency_id = $dt_product->getString($i, "currency_id");
			$amount = $dt_product->getFloat($i, "amount");
			$amount = $this->appSession->getCurrency()->convert($currency_id, $company_currency_id, $amount);
			$total = $total + $amount;
		}
		$discount_id = "";
		$service_id = "";
		$tax_id = "";
		$item_discount = 0;
		$item_service = 0;
		$item_tax = 0;
		for ($i = 0; $i < $dt_product->getRowCount(); $i++)
		{
			$sale_product_id = $dt_product->getString($i, "id");
			$currency_id = $dt_product->getString($i, "currency_id");

			$amount = $dt_product->getFloat($i, "amount");
			$amount = $this->appSession->getCurrency()->convert($currency_id, $company_currency_id, $amount);


			$sv = 0;

			$item_discount = 0;
			$item_service = 0;
			$item_tax = 0;
			$discount_id = "";
			$service_id = "";
			$tax_id = "";
			for ($j = 0; $j < $dt_service->getRowCount(); $j++)
			{

				if ($dt_service->getString($j, "rel_id") == $sale_product_id)
				{
					$dValue = ($amount * $dt_service->getFloat($j, "percent")) + $dt_service->getFloat($j, "value");
					$sv += $dValue;
					if ($dt_service->getString($j, "operator") == "-")
					{
						$amount = $amount - $sv;
					}
					else
					{
						$amount = $amount + $sv;
					}
					if ($dt_service->getString($j, "category_id") == "DISCOUNT")
					{
						$item_discount += $sv;
						$discount_id = $dt_service->getString($j, "service_id");

					}
					else if ($dt_service->getString($j, "category_id") == "TAX")
					{
						$item_tax += $sv;
						$tax_id = $dt_service->getString(j, "service_id");
					}
					else if ($dt_service->getString(j, "category_id") == "SERVICE")
					{
						$item_service += $sv;
						$service_id = $dt_service->getString($j, "service_id");


					}

					$sql = "UPDATE account_service_line SET ";
					$sql = $sql." write_date=".$this->appSession->getTier()->getDateString();
					$sql = $sql.", amount =".$dValue;
					$sql = $sql." WHERE id='".$dt_service->getString($j, "id") + "'";
					$this->msg->add("query", $sql);
					$this->appSession->getTier()->exec($this->msg);
				}
			}
			$sql = "UPDATE sale_product SET ";
			$sql = $sql." write_date=".$this->appSession->getTier()->getDateString();
			$sql = $sql.", discount_id ='".$discount_id."'";
			$sql = $sql.", discount_amount =".$item_discount;
			$sql = $sql.", service_id='".$service_id."'";
			$sql = $sql.", service_amount =".$item_service;
			$sql = $sql.", tax_id='".$tax_id."'";
			$sql = $sql.", tax_amount =".$item_tax;
			$sql = $sql." WHERE id='".$sale_product_id ."'";
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		$discount_id = "";
		$service_id = "";
		$tax_id = "";
		for ($j = 0; $j < $dt_service->getRowCount(); $j++)
		{

			$item_discount = 0;
			$item_service = 0;
			$item_tax = 0;
			$sv = 0;
			$amount = $total;
			if ($dt_service->getString($j, "rel_id") == $sale_id)
			{
				$dValue = ($total * $dt_service->getFloat($j, "percent")) + $dt_service->getFloat($j, "value");
				$sv = $sv +  $dValue;
				if ($dt_service->getString($j, "operator") == "-")
				{
					$amount = $amount - $sv;
				}
				else
				{
					$amount = $amount + $sv;
				}
				if ($dt_service->getString($j, "category_id") == "DISCOUNT")
				{
					$item_discount = $item_discount + $sv;
					$discount_id = $dt_service->getString($j, "service_id");
				}
				else if ($dt_service->getString($j, "category_id") == "TAX")
				{
					$item_tax = $item_tax + $sv;
					$tax_id = $dt_service->getString($j, "service_id");
				}
				else if ($dt_service->getString($j, "category_id") == "SERVICE")
				{
					$item_service = $item_service + $sv;
					$service_id = $dt_service->getString($j, "service_id");
				}
				$sql = "UPDATE account_service_line SET ";
				$sql = $sql." write_date=".$this->appSession->getTier()->getDateString();
				$sql = $sql.", amount =".$dValue;
				$sql = $sql." WHERE id='".$dt_service->getString($j, "id")."'";
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
			}

		}
		for ($i = 0; $i < $dt_product->getRowCount(); $i++)
		{
			$sale_product_id = $dt_product->getString($i, "id");
			$currency_id = $dt_product->getString($i, "currency_id");

			$amount = $dt_product->getFloat($i, "amount");
			$amount = $this->appSession->getCurrency()->convert($currency_id, $company_currency_id, $amount);

			if ($discount_id != "")
			{
				$dValue = ($item_discount * $amount) / $total;
				$sql = "UPDATE sale_product SET ";
				$sql = $sql." write_date=".$this->appSession->getTier()->getDateString();
				$sql = $sql.", discount_id ='".$discount_id."'";
				$sql = $sql.", discount_amount =".$dValue;
				$sql = $sql." WHERE id='".$sale_product_id."'";
				
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
			}


			if ($service_id != "")
			{
				$dValue = ($item_service * $amount) / $total;
				$sql = "UPDATE sale_product SET ";
				$sql = $sql." write_date=".$this->appSession->getTier()->getDateString();
				$sql = $sql.", service_id ='".$service_id."'";
				$sql = $sql.", service_amount =".$dValue;
				$sql = $sql." WHERE id='".$sale_product_id."'";
				$this->msg->add("query", $sql);
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
			}
			if ($tax_id != "")
			{
				$dValue = ($item_tax * $amount) / $total;
				$sql = "UPDATE sale_product SET ";
				$sql = $sql." write_date=".$this->appSession->getTier()->getDateString();
				$sql = $sql.", tax_id ='".$tax_id."'";
				$sql = $sql.", tax_amount =".$dValue;
				$sql = $sql." WHERE id='".$sale_product_id."'";
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
			}
		}
					
		
	}
	function closeBill($sale_id)
	{
		$sql = "SELECT id FROM sale_local WHERE id='".$sale_id."' AND cashier_count = 0 AND receipt_no=''";
		$this->msg->add("query", $sql);
		$sale_temp_id =  $this->appSession->getTier()->getValue($this->msg);
		if($sale_temp_id != "")
		{
			$receipt_no = $this->appSession->getTool()->findReceiptNo($this->appSession->getTier(), $this->appSession->getConfig()->getProperty("company_id"), "sale");
			$sql = "UPDATE sale_local SET status =0, receipt_date=".$this->appSession->getTier()->getDateString();
			$sql = $sql.", receipt_no = 'HQ-".$receipt_no."'";
			$sql = $sql.", cashier_date = ".$this->appSession->getTier()->getDateString();
			$sql = $sql.", write_uid = '".$this->appSession->getUserInfo()->getId()."'";
			$sql = $sql.", cashier_id = '".$this->appSession->getUserInfo()->getId()."'";
			$sql = $sql." WHERE id='".$sale_id."'";
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		$sql = "SELECT id FROM sale_local WHERE id='".$sale_id."' AND status =0";
		$this->msg->add("query", $sql);
		$sale_temp_id =  $this->appSession->getTier()->getValue($this->msg);
		if($sale_temp_id == "")
		{
			echo "Bill is pending";
		}
		$sql = "UPDATE sale_local SET status =0, receipt_date=order_date, end_date=".$this->appSession->getTier()->getDateString();
		$sql = $sql.", write_date = ".$this->appSession->getTier()->getDateString();
		$sql = $sql.", cashier_date = ".$this->appSession->getTier()->getDateString();
		$sql = $sql.", write_uid = '".$this->appSession->getConfig()->getProperty("user_id")."'";
		$sql = $sql.", cashier_id = '".$this->appSession->getConfig()->getProperty("user_id")."'";
		$sql = $sql." WHERE id='".$sale_id."'";
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
		$saleCopy = new RowLine("sale", "id");
		$productCopy = new RowLine("sale_product", "sale_id");
		$paymentCopy = new RowLine("account_payment_line", "line_id");
		$serviceCopy = new RowLine("account_service_line", "rel_id");
		$printerLineine = new RowLine("product_printer_line", "rel_id");
		$noteLine = new RowLine("product_note_line", "rel_id");
		$saleCopy->addChild($productCopy);
		$saleCopy->addChild($paymentCopy);
		$saleCopy->addChild($serviceCopy);
		$productCopy->addChild($serviceCopy);
		$productCopy->addChild($printerLineine);
		$productCopy->addChild($noteLine);
		$saleCopy->copy($this->appSession, $sale_id, true);
		$sql = "SELECT d1.id, d1.customer_id, d1.order_no, d1.receipt_date, d2.category_id, d2.type_id FROM sale d1 LEFT OUTER JOIN customer d2 ON(d1.customer_id = d2.id) WHERE d1.id='".$sale_id."'";
		$this->msg->add("query", $sql);
		$values = $this->appSession->getTier()->getArray($this->msg);
		
		if(count($values)>0)
		{
			$customer_id = $values[0][1];
            $receipt_no = $values[0][2];
			$receipt_date = $values[0][3];
			$customer_category_id = $values[0][4];
			$customer_type_id = $values[0][5];
			
			$sql = "UPDATE sale_local SET status=11";
			$sql = $sql.", write_date = ".$this->appSession->getTier()->getDateString();
			$sql = $sql.", write_uid = '".$this->appSession->getUserInfo()->getId()."'";
			$sql = $sql." WHERE id='".$sale_id."'";
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
			$this->updateService( $sale_id);
			
			$amount = 0;
			$sql = "SELECT SUM((d1.quantity * d1.unit_price) - d1.discount_amount + d1.tax_amount) AS amount FROM sale_product d1";
			$sql = $sql." WHERE d1.sale_id='".$sale_id."'";
			$this->msg->add("query", $sql);
		
			$sValue = $this->appSession->getTier()->getValue($this->msg);
			if ($sValue != "")
			{
				$amount = $this->appSession->getTool()->toDouble($sValue);
			}
			$sql = "SELECT d6.percent, d3.id AS team_id, d7.partner_id, d2.category_id, d7.id ";
			$sql = $sql." FROM account_service_rel d1";
			$sql = $sql." LEFT OUTER JOIN account_invoice_category_rel d2 ON(d1.rel_id = d2.id)";
			$sql = $sql." LEFT OUTER JOIN crm_team d3 ON(d2.rel_id = d3.id AND d2.status =0)";
			$sql = $sql." LEFT OUTER JOIN customer_rel d4 ON(d3.id = d4.rel_id AND d4.status =0)";
			$sql = $sql." LEFT OUTER JOIN hr_employee_rel d5 ON(d3.id = d5.rel_id AND d5.status =0)";
			$sql = $sql." LEFT OUTER JOIN account_service d6 ON(d1.service_id = d6.id)";
			$sql = $sql." LEFT OUTER JOIN hr_employee d7 ON(d5.employee_id = d7.id)";
			$sql = $sql." WHERE d1.status =0 AND d4.customer_id='".$customer_id."'";
			$this->msg->add("query", $sql);
			$values = $this->appSession->getTier()->getArray($this->msg);
			$mAccount = new Account($this->appSession);
			for ($i = 0; $i <count($values); $i++)
			{

				$percent = $this->appSession->getTool()->toDouble($values[$i][0]);
				$team_id = $values[$i][1];
				$partner_id = $values[$i][2];
				$category_id = $values[$i][3];
				if ($partner_id == "")
				{
					$employee_id = $values[$i][4];
					$partner_id = $mAccount->employeeToPartner($this->appSession, $employee_id);

				}

				$mAccount->createInvoice($team_id, $sale_id, $partner_id, $category_id, "", "", $this->appSession->getConfig()->getProperty("currency_id"), $amount * $percent, "", "", $receipt_no, $receipt_date, "Order #".$receipt_no.", Amount: ".$amount);

			}


			$sql = "SELECT d6.percent, d4.partner_id, d2.category_id ";
			$sql = $sql." FROM account_service_rel d1";
			$sql = $sql." LEFT OUTER JOIN account_invoice_category_rel d2 ON(d1.rel_id = d2.id)";
			$sql = $sql." LEFT OUTER JOIN customer d3 ON(d2.rel_id = d3.id)";
			$sql = $sql." LEFT OUTER JOIN res_partner_rel d4 ON(d2.id = d4.rel_id AND d4.status =0)";
			$sql = $sql." LEFT OUTER JOIN account_service d6 ON(d1.service_id = d6.id)";
			$sql = $sql." WHERE d1.status =0 AND d3.id='".$customer_id."'";

			$this->msg->add("query", $sql);
			$values = $this->appSession->getTier()->getArray($this->msg);


			for ($i = 0; $i < count($values); $i++)
			{

				$percent = $this->appSession->getTool()->toDouble($values[$i][0]);
			   
				$partner_id = $values[$i][1];
				$category_id = $values[$i][2];
				if ($partner_id == "")
				{
					$partner_id = $mAccount->customerToPartner($this->appSession, $customer_id);
				}
				$mAccount->createInvoice($sale_id, $sale_id, $partner_id, $category_id, "", "", $this->appSession->getConfig()->getProperty("currency_id"), $amount * $percent, "", "", $receipt_no, $receipt_date, "Order #".$receipt_no.", Amount: ".$amount);
			}
			$sql = "SELECT parent_id FROM customer d1 LEFT OUTER JOIN res_partner d2 ON(d1.partner_id= d2.id) WHERE d1.id='".$customer_id."'";
			$this->msg->add("query", $sql);
			$partner_id = $this->appSession->getTier()->getValue($this->msg);
			if($partner_id != "")
			{
				$sql = "SELECT d1.category_id, d1.rel_id AS service_id, d2.percent, d2.value, d3.operator, d1.first_percent, d1.second_percent, d1.min_amount, d1.max_amount FROM account_invoice_category_rel d1 LEFT OUTER JOIN account_service d2 ON(d1.rel_id = d2.id) LEFT OUTER JOIN account_service_category d3 ON(d2.category_id = d3.id) LEFT OUTER JOIN account_service_setting_rel d4 ON(d1.id = d4.setting_id) WHERE d1.status =0 AND d2.status =0 AND d1.type='SALE_PARTNER' AND (d4.id IS NULL OR d4.rel_id='".$customer_type_id."' OR d4.rel_id='".$customer_category_id."')";
				$this->msg->add("query", $sql);
				$categoryList = $this->appSession->getTier()->getArray($this->msg);
				$paid_amount = $amount;
				for($i =0; $i<count($categoryList); $i++)
				{
					$category_id = $categoryList[$i][0];
					$service_id = $categoryList[$i][1];
					$percent = $this->appSession->getTool()->toDouble($categoryList[$i][2]);
					$first_percent = $this->appSession->getTool()->toDouble($categoryList[$i][5]);
					$second_percent = $this->appSession->getTool()->toDouble($categoryList[$i][6]);
					$min_amount = $this->appSession->getTool()->toDouble($categoryList[$i][7]);
					$max_amount = $this->appSession->getTool()->toDouble($categoryList[$i][8]);
					$level = 1;
					$list_ids = "";
					
					while(true)
					{
						
						$sql = "SELECT parent_id FROM res_partner WHERE id='".$partner_id."'";
						
						$this->msg->add("query", $sql);
						$next_parent_id = $this->appSession->getTier()->getValue($this->msg);
						
						$a = $paid_amount * $first_percent;
						$b = $paid_amount * $second_percent;
						$paid_amount = $b;
						if($next_parent_id == "")
						{
							$a = $a + $b;
						}
						$created = true;
						if($min_amount>0 && $a<$min_amount)
						{
							$created = false;
						}
						if($max_amount>0 && $a>$max_amount)
						{
							$a = $max_amount;
						}
						if($created)
						{
							$mAccount->createInvoice($sale_id, $sale_id, $partner_id, $category_id, "", "", $this->appSession->getConfig()->getProperty("currency_id"), $a * $percent, "", "", $receipt_no, $receipt_date, "Order #".$receipt_no.", Amount: ".$amount." Level: ".$level);
						}
						
						
						if($next_parent_id == "")
						{
							break;
						}
						if($this->appSession->getTool()->indexOf($list_ids, $next_parent_id) != -1)
						{
							break;
						}
						$list_ids = $list_ids.",". $next_parent_id;
						$partner_id = $next_parent_id;
						$level= $level + 1;
						
					}
					
				}
				
			}
			if($customer_id != "")
			{
				
				$sql = "SELECT d1.id, d1.point, d1.amount FROM loyalty_exchange d1 WHERE d1.status=0 AND d1.type='DEPOSIT'";
				
				$this->msg->add("query", $sql);
				$loyalty = $this->appSession->getTier()->getArray($this->msg);
				for($i =0; $i<count($loyalty); $i++)
				{
					$point = $this->appSession->getTool()->toDouble($loyalty[$i][1]);
					$loyalty_amount = $this->appSession->getTool()->toDouble($loyalty[$i][2]);
					if($loyalty_amount != "")
					{
						$ipoint = ($amount * $point)/$loyalty_amount;
						$ipoint = intval($ipoint);
						if($ipoint>0)
						{
							$builder = $this->appSession->getTier()->createBuilder("loyalty_point");
							$service_line_id = $this->appSession->getTool()->getId();
							$builder->add("id", $this->appSession->getTool()->getId());
							$builder->add("factor", 1);
							$builder->add("customer_id", $customer_id);
							$builder->add("rel_id", $sale_id);
							$builder->add("point", $ipoint);
							$builder->add("receipt_date", $this->appSession->getTier()->getDateString(), 'f');
							$builder->add("create_uid", $this->appSession->getConfig()->getProperty("user_id"));
							$builder->add("write_uid", $this->appSession->getConfig()->getProperty("user_id"));
							$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
							$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
							$builder->add("status", 0);
							$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
							$sql = $this->appSession->getTier()->getInsert($builder);
							$this->msg->add("query", $sql);
							
							$this->appSession->getTier()->exec($this->msg);
						}
					}
					
				}
				
			}
		}
		echo "OK";
	}
	
}

?>