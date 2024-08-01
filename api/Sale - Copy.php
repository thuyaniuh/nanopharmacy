<?php
require_once(ABSPATH.'api/Status.php' );
require_once(ABSPATH.'api/WebService.php' );
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
			//$sql = $sql." AND d1.session_id='".$session_id."'";
		}
		$sql = $sql." AND d1.session_id='".$session_id."'";
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
			$order_no = $this->appSession->getTool()->findReceiptNo($this->appSession->getTier(), $this->appSession->getConfig()->getProperty("company_id"), "sale_local.order");
			$order_no = $this->appSession->getTool()->paddingLeft($order_no, '0', 6);
			
			$builder->add("order_no", $order_no);
			$sql = $this->appSession->getTier()->getInsert($builder);
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
			$this->checkSaleService($sale_id, $this->appSession->getConfig()->getProperty("customer_id"));
		}
		return $sale_id;
	}
	public function checkSaleService($sale_id, $customer_id)
	{
		$sql = "SELECT d2.category_id, d2.type_id, d1.category_id AS sale_category_id, d3.address_id, d5.id AS dist_address_id, d6.id AS city_address_id, d1.customer_id FROM sale_local d1 LEFT OUTER JOIN customer d2 ON(d1.customer_id = d2.id) LEFT OUTER JOIN sale_shipping d3 ON(d1.id = d3.sale_id AND d3.status=0 ) LEFT OUTER JOIN res_address d4 ON(d3.address_id = d4.id) LEFT OUTER JOIN res_address d5 ON(d4.parent_id = d5.id) LEFT OUTER JOIN res_address d6 ON(d5.parent_id = d6.id) WHERE d1.id = '".$sale_id."'";
	
		
		
		$this->msg->add("query", $sql);
		$arr = $this->appSession->getTier()->getArray($this->msg);
		$customer_category_id = "";
		$customer_type_id = "";
		$sale_category_id = "";
		$address_id = "";
		$dist_address_id = "";
		$city_address_id = "";
		if(count($arr)>0)
		{
			$customer_category_id = $arr[0][0];
			$customer_type_id = $arr[0][1];
			$sale_category_id = $arr[0][2];
			$address_id = $arr[0][3];
			$dist_address_id = $arr[0][4];
			$city_address_id = $arr[0][5];
			if($arr[0][6] == "")
			{
				$sql = "UPDATE sale_local SET customer_id ='".$customer_id."', write_date=NOW() WHERE id='".$sale_id."'";
				$this->msg->add("query", $sql);
				
				$this->appSession->getTier()->exec($this->msg);
			}else{
				$customer_id = $arr[0][6];
			}
			
		}
		
		$sql = "UPDATE account_service_line_local SET status =1, write_date=NOW() WHERE rel_id='".$sale_id."'";
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
		
		if($customer_id == "")
		{
			
			$sql = "UPDATE sale_local SET customer_id ='', write_date=NOW() WHERE id='".$sale_id."'";
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
			
			return;
		}
		
		
		
		
		
		
		$sql = "SELECT d1.service_id, d2.percent, d2.value, d2.category_id, d3.operator, d3.sequence FROM account_service_setting d1 LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id) LEFT OUTER JOIN account_service_category d3 ON(d2.category_id = d3.id) LEFT OUTER JOIN account_service_setting_rel d4 ON(d1.id = d4.setting_id) WHERE d1.status =0 AND d2.status =0 AND d4.status =0 AND d1.type='SALE'";
		$rel_ids = "";
		if($customer_id != "")
		{
			if($rel_ids != "")
			{
				$rel_ids = $rel_ids." OR ";
			}
			$rel_ids = $rel_ids." d4.rel_id='".$customer_id."'";
		}
		if($customer_category_id != "")
		{
			if($rel_ids != "")
			{
				$rel_ids = $rel_ids." OR ";
			}
			$rel_ids = $rel_ids." d4.rel_id='".$customer_category_id."'";
		}
		if($customer_type_id != "")
		{
			if($rel_ids != "")
			{
				$rel_ids = $rel_ids." OR ";
			}
			$rel_ids = $rel_ids." d4.rel_id='".$customer_type_id."'";
		}
		if($sale_category_id != "")
		{
			if($rel_ids != "")
			{
				$rel_ids = $rel_ids." OR ";
			}
			$rel_ids = $rel_ids." d4.rel_id='".$sale_category_id."'";
		}
		if($address_id != "")
		{
			if($rel_ids != "")
			{
				$rel_ids = $rel_ids." OR ";
			}
			$rel_ids = $rel_ids." d4.rel_id='".$address_id."'";
		}
		if($dist_address_id != "")
		{
			if($rel_ids != "")
			{
				$rel_ids = $rel_ids." OR ";
			}
			$rel_ids = $rel_ids." d4.rel_id='".$dist_address_id."'";
		}
		if($city_address_id != "")
		{
			if($rel_ids != "")
			{
				$rel_ids = $rel_ids." OR ";
			}
			$rel_ids = $rel_ids." d4.rel_id='".$city_address_id."'";
		}
		
		if($rel_ids != "")
		{
			$sql = $sql." AND (".$rel_ids.")";
		}else
		{
			$sql = $sql." AND 1=0";
		}
	
		$this->msg->add("query", $sql);
		$arr = $this->appSession->getTier()->getArray($this->msg);
		
		
		
	
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
	
	public function findSaleId()
	{
		$session_id = $this->appSession->getConfig()->getProperty("session_id");
		
		if($session_id == "")
		{
			$session_id = $this->appSession->getTool()->getId();
			$this->appSession->getConfig()->setProperty("session_id", $session_id);
			$this->appSession->getConfig()->save();
		}
		
		return $this->findSaleIdBySessionId($session_id, $this->appSession->getConfig()->getProperty("user_id"));
	}
	public function addProductBySaleId($sale_id, $user_id, $product_id, $currency_id, $unit_id, $attribute_id, $quantity, $unit_price, $second_unit_id, $factor, $description, $company_id, $rel_id, $type_id, $parent_id)
	{
		
		$sql = "SELECT id FROM sale_product_local WHERE status =0 AND product_id='".$product_id."' AND rel_id='".$rel_id."' AND sale_id='".$sale_id."'";
		$this->msg->add("query", $sql);
		$sale_product_id = $this->appSession->getTier()->getValue($this->msg);
		if($sale_product_id == "")
		{
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
			$builder->add("second_quantity", 0);
			$builder->add("second_unit_id", $second_unit_id);
			$builder->add("kiosk_id", $company_id);
			$builder->add("type_id", $type_id);
			$builder->add("rel_id", $rel_id);
			$builder->add("parent_id", $parent_id);
			$builder->add("status", 0);
			$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
			$sql = $this->appSession->getTier()->getInsert($builder);
			
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
			
		}else{
			$sql = "UPDATE sale_product_local SET write_date=".$this->appSession->getTier()->getDateString();
			$sql = $sql.", quantity = quantity + ".$quantity;
			$sql = $sql." WHERE id='".$sale_product_id."'";
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		$sql = "SELECT customer_id FROM sale_local WHERE id='".$sale_id."'";
		$this->msg->add("query", $sql);
		$customer_id = $this->appSession->getTier()->getValue($this->msg);
		$this->checkSaleService($sale_id, $customer_id);
		return $sale_product_id;
	}
	
	public function addProduct($product_id, $currency_id, $unit_id, $attribute_id, $quantity, $unit_price, $second_unit_id, $factor, $description, $company_id, $rel_id, $type_id)
	{
		$sale_id = $this->findSaleId();
		
		return $this->addProductBySaleId($sale_id, $this->appSession->getUserInfo()->getId(), $product_id, $currency_id, $unit_id, $attribute_id, $quantity, $unit_price, $second_unit_id, $factor, $description, $company_id, $rel_id, $type_id, "");
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
		
		$sql = "SELECT m.id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d3.name AS unit_name, d4.code AS currency_code, m.product_id, m.quantity, m.unit_price, m.unit_id, m.currency_id, d1.description, m.attribute_id, d5.name AS attribute_name, d6.name AS attribute_category_name, m.rel_id, m.type_id, d9.name AS type_name";
		$sql =$sql.", 0.0 AS unit_in_stock, m.factor, m.second_unit_id, d7.name AS second_unit_name, d12.document_id AS price_document_id, d1.publish AS product_publish, d8.publish AS price_publish, d8.unit_price AS product_price";
		$sql = $sql." FROM sale_product_local m";
		$sql = $sql." LEFT OUTER JOIN product d1 ON(m.product_id = d1.id)";
		$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status =0)";
		$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$this->appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
		$sql = $sql." LEFT OUTER JOIN product_unit d3 ON(m.unit_id = d3.id)";
		$sql = $sql." LEFT OUTER JOIN res_currency d4 ON(m.currency_id = d4.id)";
		$sql = $sql." LEFT OUTER JOIN attribute d5 ON(m.attribute_id = d5.id)";
		$sql = $sql." LEFT OUTER JOIN attribute_category d6 ON(d5.category_id = d6.id)";
		$sql = $sql." LEFT OUTER JOIN product_unit d7 ON(m.second_unit_id = d7.id)";
		$sql = $sql." LEFT OUTER JOIN product_price d8 ON(m.rel_id = d8.id) ";
		$sql = $sql." LEFT OUTER JOIN poster d12 ON(d8.id = d12.rel_id AND d12.publish=1 AND d12.status =0)";
		$sql = $sql." LEFT OUTER JOIN product_type d9 ON(m.type_id = d9.id)";
		$sql = $sql." WHERE m.status =0 AND m.quantity>0 AND m.sale_id='".$sale_id."'";
		$sql = $sql." ORDER BY m.create_date ASC";
		$this->msg->add("query", $sql);
		
		
		
		
		
		$dt = $this->appSession->getTier()->getTable($this->msg);
		$hasChanged = false;
		for($i =0; $i<$dt->getRowCount(); $i++)
		{
			if($dt->getString($i, "product_publish") != "1" || $dt->getString($i, "price_publish") != "1")
			{
				$hasChanged = true;
				$sql1 = "UPDATE sale_product_local SET status =1, write_date=NOW() WHERE id='".$dt->getString($i, "id")."' AND  parent_id=''";
				$this->msg->add("query", $sql1);
				$this->appSession->getTier()->exec($this->msg);
			}
			if($dt->getString($i, "unit_price") != $dt->getString($i, "product_price"))
			{
				$sql1 = "UPDATE sale_product_local SET unit_price =".$dt->getString($i, "product_price").", write_date=NOW() WHERE id='".$dt->getString($i, "id")."' AND  parent_id=''";
				$this->msg->add("query", $sql1);
				$hasChanged = true;
			}
		}
		if($hasChanged == true)
		{
			$this->msg->add("query", $sql);
			$dt = $this->appSession->getTier()->getTable($this->msg);
		}
		return $dt;
	}
	public function productListSaleCompanyId($sale_id, $kiosk_id)
	{
		
		$sql = "SELECT m.id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d3.name AS unit_name, d4.code AS currency_code, m.quantity, m.unit_price, m.unit_id, m.currency_id, d1.description, m.attribute_id, d5.name AS attribute_name, d6.name AS attribute_category_name";
		$sql =$sql.", (SELECT SUM(product_count.quantity) FROM product_count LEFT OUTER JOIN res_company ON(product_count.company_id = res_company.id) WHERE product_count.status =0 AND product_count.product_id= d1.id AND (product_count.company_id='".$this->appSession->getConfig()->getProperty("company_id")."' OR res_company.parent_id='".$this->appSession->getConfig()->getProperty("company_id")."') AND m.attribute_id = product_count.attribute_id AND product_count.unit_id = m.unit_id) AS unit_in_stock, m.factor, m.second_unit_id, d7.name AS second_unit_name";
		$sql = $sql." FROM sale_product_local m";
		$sql = $sql." LEFT OUTER JOIN product d1 ON(m.product_id = d1.id)";
		$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1)";
		$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$this->appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
		$sql = $sql." LEFT OUTER JOIN product_unit d3 ON(m.unit_id = d3.id)";
		$sql = $sql." LEFT OUTER JOIN res_currency d4 ON(m.currency_id = d4.id)";
		$sql = $sql." LEFT OUTER JOIN attribute d5 ON(m.attribute_id = d5.id)";
		$sql = $sql." LEFT OUTER JOIN attribute_category d6 ON(d5.category_id = d6.id)";
		$sql = $sql." LEFT OUTER JOIN product_unit d7 ON(m.second_unit_id = d7.id)";
		$sql = $sql." WHERE m.status =0 AND m.sale_id='".$sale_id."' AND m.kiosk_id='".$kiosk_id."'";
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
	public function checkOutBySaleId($sale_id, $name, $company_id, $tel, $email, $address_id, $address, $description, $customer_id, $delivery_date)
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
			if($delivery_date != "" && strlen($delivery_date)<20)
			{
				$index =  $this->appSession->getTool()->indexOf($delivery_date, " ");
				if($index != -1)
				{
					$delivery_date = $this->appSession->getTool()->substring($delivery_date, 0, $index);
				}
				$index =  $this->appSession->getTool()->indexOf($delivery_date, ":");
				if($index != -1)
				{
					$delivery_date = $this->appSession->getTool()->substring($delivery_date, 0, $index);
				}
				$builder->add("start_date", $delivery_date);
			}else{
				$builder->add("start_date", $this->appSession->getTier()->getDateString(), 'f');
			}
			
			$builder->add("address_id", $address_id);
			$builder->add("status", 0);
			$sql = $this->appSession->getTier()->getInsert($builder);

			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}else{
			$builder = $this->appSession->getTier()->createBuilder("sale_shipping");
			$id = $shipping_id;
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
			if($delivery_date != "" && strlen($delivery_date)<20)
			{
				$builder->add("start_date", $delivery_date);
			}else{
				$builder->add("start_date", $this->appSession->getTier()->getDateString(), 'f');
			}
			
			$builder->add("address_id", $address_id);
			$builder->add("status", 0);
			$sql = $this->appSession->getTier()->getUpdate($builder);

			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		
		$sql = "SELECT id FROM sale_shipping WHERE sale_id='".$customer_id."'";
		$this->msg->add("query", $sql);
		$shipping_id = $this->appSession->getTier()->getValue($this->msg);
		if($shipping_id == "")
		{
			$builder = $this->appSession->getTier()->createBuilder("sale_shipping");
			$id = $this->appSession->getTool()->getId();
			$builder->add("id", $id);
			$builder->add("sale_id", $customer_id);
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
		}else{
			$builder = $this->appSession->getTier()->createBuilder("sale_shipping");
			$id = $shipping_id;
			$builder->add("id", $id);
			$builder->add("sale_id", $customer_id);
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
			$sql = $this->appSession->getTier()->getUpdate($builder);

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
		
		
		$sql = "UPDATE sale_local SET company_id='".$company_id."', status=3, order_date=".$this->appSession->getTier()->getDateString().", required_date =".$this->appSession->getTier()->getDateString().", order_no='".$order_no."', write_date=".$this->appSession->getTier()->getDateString().", customer_id='".$customer_id."' WHERE id='".$sale_id."'";
		$this->msg->add("query", $sql);
		
		$this->appSession->getTier()->exec($this->msg);
		
		$sql = "UPDATE sale_product_local SET company_id='".$company_id."', write_date=".$this->appSession->getTier()->getDateString()." WHERE sale_id ='".$sale_id."'";
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
		
		$sql = "UPDATE account_payment_line_local SET company_id='".$company_id."', write_date=".$this->appSession->getTier()->getDateString()." WHERE line_id ='".$sale_id."'";
		
		$sql = "UPDATE account_service_line_local SET company_id='".$company_id."', write_date=".$this->appSession->getTier()->getDateString()." WHERE rel_id ='".$sale_id."'";
		
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
		

		
				
		$status = new Status($this->appSession);
		$status->doStatus($sale_id, "sale_local", "", $company_id);
		return $sale_id;
		
	}
	public function checkOutingBySaleId($sale_id, $name, $company_id, $tel, $email, $address_id, $address, $description, $customer_id, $delivery_date)
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
			if($delivery_date != "" && strlen($delivery_date)<20)
			{
				$builder->add("start_date", $delivery_date);
			}else{
				$builder->add("start_date", $this->appSession->getTier()->getDateString(), 'f');
			}
			
			$builder->add("address_id", $address_id);
			$builder->add("status", 0);
			$sql = $this->appSession->getTier()->getInsert($builder);

			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}else{
			$builder = $this->appSession->getTier()->createBuilder("sale_shipping");
			$id = $shipping_id;
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
			if($delivery_date != "" && strlen($delivery_date)<20)
			{
				$builder->add("start_date", $delivery_date);
			}else{
				$builder->add("start_date", $this->appSession->getTier()->getDateString(), 'f');
			}
			
			$builder->add("address_id", $address_id);
			$builder->add("status", 0);
			$sql = $this->appSession->getTier()->getUpdate($builder);

			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		
		$sql = "SELECT id FROM sale_shipping WHERE sale_id='".$customer_id."'";
		$this->msg->add("query", $sql);
		$shipping_id = $this->appSession->getTier()->getValue($this->msg);
		if($shipping_id == "")
		{
			$builder = $this->appSession->getTier()->createBuilder("sale_shipping");
			$id = $this->appSession->getTool()->getId();
			$builder->add("id", $id);
			$builder->add("sale_id", $customer_id);
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
		}else{
			$builder = $this->appSession->getTier()->createBuilder("sale_shipping");
			$id = $shipping_id;
			$builder->add("id", $id);
			$builder->add("sale_id", $customer_id);
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
			$sql = $this->appSession->getTier()->getUpdate($builder);

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
		
		
		$sql = "UPDATE sale_local SET company_id='".$company_id."', status=2, order_date=".$this->appSession->getTier()->getDateString().", required_date =".$this->appSession->getTier()->getDateString().", order_no='".$order_no."', write_date=".$this->appSession->getTier()->getDateString().", customer_id='".$customer_id."' WHERE id='".$sale_id."'";
		$this->msg->add("query", $sql);
		
		$this->appSession->getTier()->exec($this->msg);
		
		$sql = "UPDATE sale_product_local SET company_id='".$company_id."', write_date=".$this->appSession->getTier()->getDateString()." WHERE sale_id ='".$sale_id."'";
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
		
		$sql = "UPDATE account_payment_line_local SET company_id='".$company_id."', write_date=".$this->appSession->getTier()->getDateString()." WHERE line_id ='".$sale_id."'";
		
		
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
		
		return $sale_id;
		
	}
	public function checkOut($name, $company_id, $tel, $email, $address_id, $address, $description, $delivery_date)
	{
		$sale_id = $this->findSaleId();
		echo $this->checkOutBySaleId($sale_id, $name, $company_id, $tel, $email, $address_id, $address, $description, "", "", $delivery_date);
	}
	public function removeCard($id){
		
		$sql = "UPDATE sale_product_local SET status=1, write_date=NOW() WHERE id ='".$id."' OR parent_id='".$id."'";
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
			
			$sql = "UPDATE sale_local SET status=1, write_date=".$this->appSession->getTier()->getDateString()." WHERE id ='".$sale_id."'";
	
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
			
			$sql = "UPDATE wallet SET status=1, write_date=".$this->appSession->getTier()->getDateString()." WHERE rel_id ='".$sale_id."'";
	
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
			
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
		$sql = "SELECT SUM(d1.percent) AS percent, SUM(d1.value) AS value, d1.category_id, d1.operator, d1.sequence";
		$sql = $sql." FROM account_service_line_local d1";
		$sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 GROUP BY d1.category_id, d1.operator, d1.sequence ORDER BY d1.sequence ASC";
		$msg->add("query", $sql);
		
		$serviceList = $this->appSession->getTier()->getArray($msg);
		
		for($i =0; $i<count($serviceList); $i++)
		{
			$a = ($amount * floatval($serviceList[$i][0])) + floatval($serviceList[$i][1]);
			if($serviceList[$i][3] == "+")
			{
				$amount =  $amount + $a;
			}else if($serviceList[$i][3] == "-")
			{
				$amount =  $amount -  $a;
			}else if($serviceList[$i][3] == "*")
			{
				$amount =  $amount *  $a;
			}else if($serviceList[$i][3] == "/")
			{
				$amount =  $amount /  $a;
			}
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
		
		$sql = "SELECT SUM(d1.percent) AS percent, SUM(d1.value) AS value, d1.category_id, d1.operator, d1.sequence";
		$sql = $sql." FROM account_service_line_local d1";
		$sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 GROUP BY d1.category_id, d1.operator, d1.sequence ORDER BY d1.sequence ASC";
		$msg->add("query", $sql);
		
		
		
		$serviceList = $this->appSession->getTier()->getArray($msg);
		
		for($i =0; $i<count($serviceList); $i++)
		{
			$a = ($amount * floatval($serviceList[$i][0])) + floatval($serviceList[$i][1]);
			if($serviceList[$i][3] == "+")
			{
				$amount =  $amount + $a;
			}else if($serviceList[$i][3] == "-")
			{
				$amount =  $amount -  $a;
			}else if($serviceList[$i][3] == "*")
			{
				$amount =  $amount *  $a;
			}else if($serviceList[$i][3] == "/")
			{
				$amount =  $amount /  $a;
			}
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
	
}

?>