<?php
require_once(ABSPATH.'api/Product.php' );
require_once(ABSPATH.'api/Sale.php' );

$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
$msg = $appSession->getTier()->createMessage();
$limit_product_ids = "111111111111";
$limit_product_quantity = 3;
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
	echo $appSession->getTool()->respTable($dt);
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
	echo $appSession->getTool()->respTable($dt);
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
	$dt = $product->productByGroupById($appSession, $id, 10);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "add_product_to_sale"){
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
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
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$product_price_id = "";
	if(isset($_REQUEST['product_price_id']))
	{
		$product_price_id = $_REQUEST['product_price_id'];
	}
	
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
	$sale = new Sale($appSession);

	
	$sale_id = $sale->findSaleId();
	
	if($appSession->getTool()->indexOf($limit_product_ids, $product_id) != -1)
	{
		if($quantity>$limit_product_quantity){
			$quantity = $limit_product_quantity;
		}
		$sql = "SELECT id FROM sale_product WHERE product_id='".$product_id."' AND create_uid='".$user_id."' AND status =0 AND create_date>='2023-06-20 14:00:00'";
		$msg->add("query", $sql);
		$check_sale_product_id = $appSession->getTier()->getValue($msg);
		if($check_sale_product_id != "")
		{
			echo "OUT_OF_STOCK";
			exit();
		}else{
			$sql = "SELECT d1.id FROM sale_product_local d1 LEFT OUTER JOIN sale_local d2 ON(d1.sale_id =d2.id) WHERE d1.product_id='".$product_id."' AND d1.create_uid='".$user_id."' AND d1.status =0 AND (d2.status =0 OR d2.status = 2 OR d2.status =3) AND d1.create_date>='2023-06-18 15:41:00' AND d1.sale_id != '".$sale_id."'";
			$msg->add("query", $sql);
			$check_sale_product_id = $appSession->getTier()->getValue($msg);
			if($check_sale_product_id != "")
			{
				echo "OUT_OF_STOCK";
				exit();
			}
		}
	}
	
	if($rel_id != "")
	{
		$sql = "SELECT id, quantity FROM sale_product_local WHERE rel_id='".$rel_id."' AND sale_id='".$sale_id."' AND parent_id=''";
		
		$msg->add("query", $sql);
		$values = $appSession->getTier()->getArray($msg);
		if(count($values)>0)
		{
			$sale_product_id = $values[0][0];
			$sale_quantity = $values[0][1];
			if($sale_quantity == "")
			{
				$sale_quantity = "0";
			}
			$sale_quantity = $appSession->getTool()->toDouble($sale_quantity);
			
			/*if($quantity>$sale_quantity)
			{
				$sql = "SELECT SUM(product_count.quantity) FROM product_count LEFT OUTER JOIN res_company ON(product_count.company_id = res_company.id) WHERE product_count.status =0 AND product_count.rel_id= '".$rel_id."'";
				$msg->add("query", $sql);
				$unit_in_stock = $appSession->getTier()->getValue($msg);
				if($unit_in_stock == "")
				{
					$unit_in_stock = 0;
				}
				
				$sql = "SELECT SUM(sale_product_local.quantity) FROM sale_product_local  WHERE sale_product_local.status =0 AND sale_product_local.rel_id= '".$rel_id."'";
				$msg->add("query", $sql);
				$quantity_pending = $appSession->getTier()->getValue($msg);
				if($quantity_pending == "")
				{
					$quantity_pending = "0";
				}
				
				$quantity_pending = $appSession->getTool()->toDouble($quantity_pending);
				$unit_in_stock = $unit_in_stock - $quantity_pending;
				
				
				if($unit_in_stock<$sale_quantity)
				{
					echo "OUT_OF_STOCK";
					exit();
				}
			}*/
			$sql = "UPDATE sale_product_local SET quantity= ".$quantity;
			$sql = $sql.", unit_price=".$unit_price;
			if($quantity == "0"){
				$sql = $sql.", status =1";
			}else{
				$sql = $sql.", status =0";
			}
			$sql = $sql." WHERE id='".$sale_product_id."'";
			
			$msg->add("query", $sql);
			$r = $appSession->getTier()->exec($msg);
			$sql = "SELECT id, product_id, unit_id, currency_id, attribute_id, type_id, quantity, unit_price FROM product_modifier WHERE rel_id='".$product_id."' AND status =0";
	
			$msg->add("query", $sql);
			$dt = $appSession->getTier()->getTable($msg);
			for($i =0; $i<$dt->getRowCount(); $i++)
			{
				$qty = $quantity * $dt->getFloat($i, "quantity");
				$sql = "UPDATE sale_product_local SET quantity= ".$qty;
				if($quantity == "0"){
					$sql = $sql.", status =1";
				}else{
					$sql = $sql.", status =0";
				}
				$sql = $sql." WHERE rel_id='".$dt->getString($i, "id")."' AND sale_id='".$sale_id."' AND parent_id='".$sale_product_id."'";
				$msg->add("query", $sql);
				$appSession->getTier()->exec($msg);
			}
			
			$sql = "SELECT customer_id FROM sale_local WHERE id='".$sale_id."'";
			$msg->add("query", $sql);
			$customer_id = $appSession->getTier()->getValue($msg);
			
			$sale->checkSaleService($sale_id, $customer_id);
			echo $sale_product_id;
			exit();
		}
		
	}
	$sql = "SELECT SUM(product_count.quantity) FROM product_count LEFT OUTER JOIN res_company ON(product_count.company_id = res_company.id) WHERE product_count.status =0 AND product_count.rel_id= '".$rel_id."'";
	$msg->add("query", $sql);
	$unit_in_stock = $appSession->getTier()->getValue($msg);
	if($unit_in_stock == "")
	{
		$unit_in_stock = 0;
	}
	
	$sql = "SELECT SUM(sale_product_local.quantity) FROM sale_product_local  WHERE sale_product_local.status =0 AND sale_product_local.rel_id= '".$rel_id."'";
	$msg->add("query", $sql);
	$quantity_pending = $appSession->getTier()->getValue($msg);
	if($quantity_pending == "")
	{
		$quantity_pending = 0;
	}
	$quantity_pending = 20;
	$quantity_pending = $appSession->getTool()->toDouble($quantity_pending);
	$unit_in_stock = $unit_in_stock - $quantity_pending;
	
	
	
	/*if($unit_in_stock<$quantity)
	{
		echo "OUT_OF_STOCK";
		exit();
	}*/
	$sale_product_id = $sale->addProductBySaleId($sale_id, $user_id, $product_id, $currency_id, $unit_id, $attribute_id, $quantity, $unit_price, $second_unit_id, $factor, $description, $company_id, $rel_id, $type_id, "");
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
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	
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
	$sql = $sql." WHERE d1.status =0 AND d1.publish = 1 AND d1.type='PRODUCT_CATEGORY'";
	$sql = $sql." AND d1.company_id='".$appSession->getConfig()->getProperty("company_id")."'";
	$sql = $sql." ORDER BY d3.sequence ASC, d1.sequence ASC, lg.description ASC, d1.name ASC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "product_category_id")
{
	$msg = $appSession->getTier()->createMessage();
	$category_id = "";
	if(isset($_REQUEST['id']))
	{
		$category_id = $_REQUEST['id'];
	}
	$child_category_id = "";
	if(isset($_REQUEST['child_id']))
	{
		$child_category_id = $_REQUEST['child_id'];
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
	
	$productList = null;
	$sql = "SELECT d1.id, d5.unit_id, d5.attribute_id,	d5.type_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d5.unit_price, d5.old_price, d6.name AS unit_name, d5.currency_id";
	$sql = $sql.", d9.name AS attribute_name, d10.name attribute_category_name, d5.factor"; 
	$sql = $sql.", d5.company_id, d7.commercial_name, d7.name AS company_name, d5.id AS price_id, d8.document_id AS price_document_id, d5.description, d11.name AS type_name, 0.0 AS unit_in_stock, d15.name AS sticker";
	$sql = $sql." FROM product d1";
	$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status =0)";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
	$sql = $sql." LEFT OUTER JOIN product_price d5 ON(d1.id = d5.product_id AND d5.status =0 AND d5.publish=1) LEFT OUTER JOIN product_unit d6 ON(d5.unit_id = d6.id)";
	$sql = $sql." LEFT OUTER JOIN res_company d7 ON(d5.company_id = d7.id)";
	$sql = $sql." LEFT OUTER JOIN poster d8 ON(d5.id = d8.rel_id AND d8.publish=1 AND d8.status =0)";
	$sql = $sql." LEFT OUTER JOIN attribute d9 ON(d5.attribute_id = d9.id)";
	$sql = $sql." LEFT OUTER JOIN attribute_category d10 ON(d9.category_id = d10.id)";
	$sql = $sql." LEFT OUTER JOIN product_type d11 ON(d5.type_id = d11.id)";
	$sql = $sql." LEFT OUTER JOIN product_category d13 ON(d1.category_id = d13.id)";
	$sql = $sql." LEFT OUTER JOIN product_category d14 ON(d13.parent_id = d14.id)";
	$sql = $sql." LEFT OUTER JOIN res_meta d15 ON(d1.id = d15.rel_id AND d15.type='Sticker' AND d15.status =0)";
	$sql = $sql." WHERE d1.status =0 AND d1.publish = 1 AND d13.publish= 1 AND (d14.id IS NULL OR d14.publish =1)";
	if($child_category_id != "")
	{
		$sql = $sql." AND d1.category_id='".$child_category_id."'";
	}else{
		$sql = $sql." AND (d1.category_id='".$category_id."' OR d13.parent_id='".$category_id."')";
	}
	
	if($search != "")
	{
		$search = $appSession->getTool()->replace($search, "'", "''");
		$sql = $sql." AND (d1.name ILIKE '%".$search."%' OR lg.description ILIKE '%".$search."%')";
	}
	/*if($appSession->getConfig()->getProperty("customer_category_id") != "" && $appSession->getConfig()->getProperty("customer_category_id") != "ffe706f4-f7b5-400c-eb0f-f4b821389544")
	{
		if($appSession->getConfig()->getProperty("customer_id") != "")
		{
			$sql1 = $sql." AND d5.type='CUSTOMER' AND  d5.rel_id='".$appSession->getConfig()->getProperty("customer_id")."'";
			$sql1 = $sql1." ORDER BY d1.sequence ASC, d5.sequence ASC";
			$msg->add("query", $sql1);
			$productList = $appSession->getTier()->getTable($msg);

		}
		if($productList == null || $productList->getRowCount() ==0)
		{
			$sql1 = $sql." AND d5.type='CUSTOMER_CATEGORY'  AND d5.rel_id='".$appSession->getConfig()->getProperty("customer_category_id")."'";
			$sql1 = $sql1." ORDER BY d1.sequence ASC, d5.sequence ASC";
			$msg->add("query", $sql1);
			$productList = $appSession->getTier()->getTable($msg);
		}
	
	}else{
		$sql1 = $sql." AND d5.type='PRODUCT'";
		$sql1 = $sql1." ORDER BY d1.sequence ASC, d5.sequence ASC";
		$msg->add("query", $sql1);
		
		$productList = $appSession->getTier()->getTable($msg);
	}*/
	
	$sql1 = $sql." AND d5.type='PRODUCT'";
		$sql1 = $sql1." ORDER BY d1.sequence ASC, d5.sequence ASC";
		$msg->add("query", $sql1);
		
		$productList = $appSession->getTier()->getTable($msg);

	$product = new Product($appSession);
	$productList = $product->countProduct($productList);
	
	echo $appSession->getTool()->respTable($productList);
}else if($ac == "product_attribute_photo")
{
	$msg = $appSession->getTier()->createMessage();
	$attribue_id = "";
	if(isset($_REQUEST['attribue_id']))
	{
		$attribue_id = $_REQUEST['attribue_id'];
	}
	$product_id = "";
	if(isset($_REQUEST['product_id']))
	{
		$product_id = $_REQUEST['product_id'];
	}
	$sql = "SELECT d1.document_id, d3.name FROM document_rel d1";
    $sql = $sql." LEFT OUTER JOIN attribute_line d2 ON(d1.rel_id = d2.id) LEFT OUTER JOIN document d3 ON(d1.document_id = d3.id)";
    $sql = $sql."  WHERE d1.status =0 AND d2.status =0 AND d2.attribute_id='".$attribue_id."'";
    $sql = $sql." AND d2.rel_id='".$product_id."'";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}
else if($ac == "sale_info_by_session"){
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
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
	
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	
	$msg = $appSession->getTier()->createMessage();

	$sql = "SELECT d1.id, d1.order_no, d1.order_date, (SELECT name FROM res_status_line WHERE rel_id=d1.id AND status =0 ORDER BY create_date DESC LIMIT 1) AS status_name, d1.status, d2.commercial_name FROM sale_local d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id = d2.id) WHERE d1.id='".$sale_id."'";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}
else if($ac == "sale_product"){
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
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
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();

	$dt = $sale->productListSaleId($sale_id);
	echo $appSession->getTool()->respTable($dt);
}

else if($ac == "sale_product_quantity"){
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
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
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	$sql = "SELECT m.id, m.product_id, m.quantity, m.unit_price, m.unit_id, m.attribute_id, m.kiosk_id, m.type_id, m.rel_id, m.currency_id";
	$sql = $sql." FROM sale_product_local m";
	$sql = $sql." WHERE m.status =0 AND m.sale_id='".$sale_id."'";
	$msg->add("query", $sql);
	
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "sale_service_local")
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
	
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	$sql = "SELECT d2.rel_id FROM sale_local d1 LEFT OUTER JOIN res_user_company d2 ON(d1.create_uid = d2.user_id AND d2.status =0) WHERE d1.id ='".$sale_id."' AND d1.customer_id != d2.rel_id";
	$msg->add("query", $sql);
	$customer_id = $appSession->getTier()->getValue($msg);
	
	if($customer_id != "")
	{
		$sql = "UPDATE sale_local SET customer_id='".$customer_id."', write_date=NOW() WHERE id ='".$sale_id."'";
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
		$sale->checkSaleService($sale_id, $customer_id);
	}
	
	$sql = "SELECT SUM(d1.percent) AS percent, SUM(d1.value) AS value, d1.category_id, d1.operator, d1.sequence";
	$sql = $sql." FROM account_service_line_local d1";
	$sql = $sql." LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id)";
	$sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 GROUP BY d1.category_id, d1.operator, d1.sequence ORDER BY d1.sequence ASC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "sale_service_local_by_id")
{
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	

	$sql = "SELECT d1.percent, d1.value, d1.category_id, d1.operator, d2.name";
	$sql = $sql." FROM account_service_line_local d1";
	$sql = $sql." LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id)";
	$sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 ORDER BY d1.sequence ASC";
	$msg->add("query", $sql);
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}
else if($ac == "res_company")
{
	$msg = $appSession->getTier()->createMessage();
	$sql = "SELECT d1.id, d1.name, d1.address";
	$sql =$sql." FROM res_company d1 WHERE d1.status =0 AND d1.type='ONLINE'";
	$sql = $sql." ORDER BY d1.name ASC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	
	echo $appSession->getTool()->respTable($dt);
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
		$user_id = $appSession->getConfig()->getProperty("user_id");
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
	$session_id = "";
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
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
	$delivery_date = "";
	if(isset($_REQUEST['delivery_date']))
	{
		$delivery_date = $_REQUEST['delivery_date'];
	}
	$address = "";
	if(isset($_REQUEST['delivery_address']))
	{
		$address = $_REQUEST['delivery_address'];
	}
	if(isset($_REQUEST['delivery_address']))
	{
		$address = $_REQUEST['delivery_address'];
	}
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
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$payment_amount = "";
	if(isset($_REQUEST['payment_amount']))
	{
		$payment_amount = $_REQUEST['payment_amount'];
	}
	$payment_amount = $appSession->getTool()->toDouble($payment_amount);
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	$sale = new Sale($appSession);
	if($sale_id == "")
	{
		$sale_id = $sale->findSaleId();
	}
	
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
	
	if($customer_id == "")
	{
		$sql = "SELECT rel_id FROM res_user_company WHERE user_id ='".$user_id."'";
		$msg->add("query", $sql);
		$customer_id = $appSession->getTier()->getValue($msg);
		echo $customer_id;
		
	}
	
	$message = "OK";
	
	if($message == "OK")
	{
		
		echo "OK:".$sale->checkOutBySaleId($sale_id, $name, $company_id, $tel, $email, $address_id,$address, $description, $customer_id, $delivery_date);
	}else{
		echo $message;
	}
	
	
}else if($ac == "checkOuting")
{
	$msg = $appSession->getTier()->createMessage();
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	if($user_id == "")
	{
		$user_id = $appSession->getConfig()->getProperty("user_id");
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
	$session_id = "";
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
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
	$delivery_date = "";
	if(isset($_REQUEST['delivery_date']))
	{
		$delivery_date = $_REQUEST['delivery_date'];
	}
	$address = "";
	if(isset($_REQUEST['delivery_address']))
	{
		$address = $_REQUEST['delivery_address'];
	}
	if(isset($_REQUEST['delivery_address']))
	{
		$address = $_REQUEST['delivery_address'];
	}
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
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$payment_amount = "";
	if(isset($_REQUEST['payment_amount']))
	{
		$payment_amount = $_REQUEST['payment_amount'];
	}
	$payment_amount = $appSession->getTool()->toDouble($payment_amount);
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	$sale = new Sale($appSession);
	if($sale_id == "")
	{
		$sale_id = $sale->findSaleId();
	}
	
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
	
	if($message == "OK")
	{
		
		echo "OK:".$sale->checkOutingBySaleId($sale_id, $name, $company_id, $tel, $email, $address_id,$address, $description, $customer_id, $delivery_date);
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
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	$sale = new Sale($appSession);
	echo $sale->cancelBySaleId($sale_id);
}
else if($ac == "removeCard")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
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

	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	if($user_id != "")
	{
		$appSession->getConfig()->setProperty("user_id", $user_id);
	}
	
	$appSession->getConfig()->setProperty("session_id", $session_id);
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
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$create_date = date("Y-m-d", strtotime("-3 day"));
	$sql = "SELECT d1.id, d1.order_no, d1.order_date, (SELECT COUNT(id) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS items, (SELECT SUM(quantity * unit_price) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS amount, (SELECT name FROM res_status_line WHERE rel_id=d1.id AND status =0 ORDER BY create_date DESC LIMIT 1) AS status_name, d1.status, d2.commercial_name FROM res_rel d LEFT OUTER JOIN sale_local d1 ON(d.res_id = d1.id) LEFT OUTER JOIN res_company d2 ON(d1.company_id = d2.id) WHERE (d1.status=0 OR d1.status=3) AND d.write_date>='".$create_date." 00:00:00'";
	if($customer_id != "")
	{
		$sql = $sql." AND d.rel_id='".$customer_id."'";
	}else{
		$sql = $sql." AND 1=0";
	}
	$sql = $sql." ORDER BY d.write_date DESC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);

	$sql = "SELECT d1.id, d1.order_no, d1.order_date, (SELECT COUNT(id) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS items, (SELECT SUM(quantity * unit_price) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS amount, (SELECT name FROM res_status_line WHERE rel_id=d1.id AND status =0 ORDER BY create_date DESC LIMIT 1) AS status_name, d1.status, d2.commercial_name FROM sale_local d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id = d2.id) WHERE (d1.status=0 OR d1.status=3)";
	if($customer_id != "")
	{
		$sql = $sql." AND d1.customer_id='".$customer_id."'";
	}else{
		$sql = $sql." AND 1=0";
	}
	$sql = $sql." ORDER BY d1.order_date DESC";

	$msg->add("query", $sql);
	$dt1 = $appSession->getTier()->getTable($msg);
	for($i =0; $i<$dt1->getRowCount(); $i++)
	{
		$dt->addArray($dt1->getData()[$i]);
	}
	echo $appSession->getTool()->respTable($dt);
		
}else if($ac == "order_list_cancel")
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
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$sql = "SELECT d1.id, d1.order_no, d1.order_date, (SELECT COUNT(id) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS items, (SELECT SUM(quantity * unit_price) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS amount, (SELECT name FROM res_status_line WHERE rel_id=d1.id AND status =0 ORDER BY create_date DESC LIMIT 1) AS status_name, d1.status, d2.commercial_name FROM sale_local d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id = d2.id) WHERE d1.status =1";
	if($customer_id != "")
	{
		$sql = $sql." AND d1.customer_id='".$customer_id."'";
	}else{
		$sql = $sql." AND 1=0";
	}
	$sql = $sql." ORDER BY d1.order_date DESC";

	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
		
}else if($ac == "order_list_company")
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
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$sql = "SELECT d1.id, d1.order_no, d1.order_date, (SELECT COUNT(id) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS items, (SELECT SUM(quantity * ((second_quantity/factor) * unit_price)) FROM sale_product_local WHERE status =0 AND sale_id=d1.id AND kiosk_id='".$company_id."') AS amount, (SELECT name FROM res_status_line WHERE rel_id=d1.id AND status =0 ORDER BY create_date DESC LIMIT 1) AS status_name, d1.status, d2.commercial_name FROM sale_local d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id = d2.id) WHERE (d1.status=0 OR d1.status=3)";
	$sql = $sql." AND d1.id IN(SELECT sale_id FROM sale_product_local WHERE kiosk_id='".$company_id."')";
	$sql = $sql." ORDER BY d1.order_date DESC";

	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
		
}
else if($ac == "sale_info"){
	$msg = $appSession->getTier()->createMessage();
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$sql = "SELECT d1.id, d1.order_no, d1.order_date, (SELECT name FROM res_status_line WHERE rel_id=d1.id AND status =0 ORDER BY create_date DESC LIMIT 1) AS status_name, (SELECT id FROM res_status_line WHERE rel_id=d1.id AND status =0 ORDER BY create_date DESC LIMIT 1) AS status_id, d1.status, d2.commercial_name FROM sale_local d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id = d2.id) WHERE d1.id='".$sale_id."'";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);

}else if($ac == "sale_product_by_id"){
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}

	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$sale = new Sale($appSession);

	$dt = $sale->productListSaleId($sale_id);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "sale_product_kios_by_id"){
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}

	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$sale = new Sale($appSession);

	$dt = $sale->productListSaleCompanyId($sale_id, $company_id);
	echo $appSession->getTool()->respTable($dt);
}
else if($ac == "sale_product_update_quantity")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$quantity = "";
	if(isset($_REQUEST['quantity']))
	{
		$quantity = $_REQUEST['quantity'];
	}
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	if($limit_product_ids != "")
	{
		$sql = "SELECT d1.product_id FROM sale_product_local d1 WHERE d1.id='".$id."'";
		$msg->add("query", $sql);
		$product_id = $appSession->getTier()->getValue($msg);
		
		if($appSession->getTool()->indexOf($limit_product_ids, $product_id) != -1)
		{
			if($quantity>$limit_product_quantity){
				$quantity = $limit_product_quantity;
			}
		}

	}
	
	
	$sql = "UPDATE sale_product_local SET quantity = ".$quantity.", write_date=NOW() WHERE id='".$id."'";
	$msg = $appSession->getTier()->createMessage();
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	
	$sql = "SELECT product_id, sale_id FROM sale_product_local WHERE id='".$id."'";
	
	$msg->add("query", $sql);
	$arr = $appSession->getTier()->getArray($msg);
	if(count($arr)>0)
	{
		$product_id = $arr[0][0];
		$sale_id = $arr[0][1];
		$sql = "SELECT id, product_id, unit_id, currency_id, attribute_id, type_id, quantity, unit_price FROM product_modifier WHERE rel_id='".$product_id."' AND status =0";
	
		$msg->add("query", $sql);
		$dt = $appSession->getTier()->getTable($msg);
		for($i =0; $i<$dt->getRowCount(); $i++)
		{
			$qty = $quantity * $dt->getFloat($i, "quantity");
			$sql = "UPDATE sale_product_local SET quantity= ".$qty;
			if($quantity == "0"){
				$sql = $sql.", status =1";
			}else{
				$sql = $sql.", status =0";
			}
			$sql = $sql." WHERE rel_id='".$dt->getString($i, "id")."' AND sale_id='".$sale_id."' AND parent_id='".$id."'";
			$msg->add("query", $sql);
			$appSession->getTier()->exec($msg);
		}
	}
	
	//$sql = "SELECT customer_id FROM sale_local WHERE id='".$sale_id."'";
	//$msg->add("query", $sql);
	//$customer_id = $appSession->getTier()->getValue($msg);

	
	if($customer_id != "")
	{
		$session_id = "";
		if(isset($_REQUEST['session_id']))
		{
			$session_id = $_REQUEST['session_id'];
		}
		if(isset($_REQUEST['_id']))
		{
			$session_id = $_REQUEST['_id'];
		}
		$appSession->getConfig()->setProperty("session_id", $session_id);
		if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
		$sale = new Sale($appSession);
		$sale_id = $sale->findSaleId();
		$sql = "UPDATE sale_local SET customer_id = ".$customer_id.", write_date=NOW() WHERE id='".$sale_id."'";
		$msg = $appSession->getTier()->createMessage();
		$msg->add("query", $sql);
		$sale->checkSaleService($sale_id, $customer_id);
	}
	
	
	echo $id;
}else if($ac == "customer")
{
	$msg = $appSession->getTier()->createMessage();
	$id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$id = $_REQUEST['customer_id'];
	}
	
	$sql = "SELECT d1.id, d1.name, d1.phone, d1.email, d1.address, d1.address_id AS ward_address_id, d5.id AS dist_address_id,  d5.parent_id AS city_address_id";
	$sql =$sql." FROM customer d1";
	$sql = $sql."  LEFT OUTER JOIN res_address d4 ON(d1.address_id= d4.id) LEFT OUTER JOIN res_address d5 ON(d4.parent_id= d5.id)";
	$sql = $sql." WHERE d1.id='".$id."'";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "update_sale_session")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	
	
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}

	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	if($user_id != ""){
		$appSession->getConfig()->setProperty("user_id", $user_id);
	}
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	
	$sql = "UPDATE sale_local SET create_uid = '".$user_id."', customer_id='".$customer_id."', write_date=NOW() WHERE id='".$sale_id."' AND status =2";
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
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
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
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	
	$appSession->getConfig()->setProperty("session_id", $session_id);
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	
	$sql = "UPDATE sale_local SET create_uid = '".$user_id."', write_date=NOW() WHERE id='".$sale_id."'";
	$msg = $appSession->getTier()->createMessage();

	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}
else if($ac == "payment_method"){
	
	$msg = $appSession->getTier()->createMessage();
	$sql = "SELECT d1.id, d1.name, d2.name AS category_name, d1.description FROM account_payment d1";
	$sql = $sql." LEFT OUTER JOIN account_payment_category d2 ON(d1.category_id = d2.id)";
	$sql = $sql." WHERE d1.status =0 AND d2.status =0";
	$sql = $sql." AND (d1.id='9a9404e2-30d9-45c6-c3f6-43930e2d5a6c' OR d1.id='b6f7a3c7-7bb2-443c-a2da-c5e9a965c502' OR d1.id='a63f5b3d-1179-4c8d-afaa-f4797d345ebd' OR d1.id='fda0dc20-341e-4ac2-cfad-888dd77ee9d1' OR d1.id='7d2e384f-d100-42fc-812a-0e796c887ab9')";
	$sql = $sql." ORDER BY d1.sequence, d2.sequence ASC";

	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "payment_type_amount")
{
	$msg = $appSession->getTier()->createMessage();
	
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
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
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$category_name = "";
	if(isset($_REQUEST['category_name']))
	{
		$category_name = $_REQUEST['category_name'];
	}
	if($category_name == "WALLET")
	{
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
	
		$sql = "SELECT d1.holder_id, d3.code , SUM(d2.amount * d2.factor) AS amount FROM wallet_holder_rel d1";
		$sql = $sql." LEFT OUTER JOIN wallet d2 ON(d1.holder_id= d2.holder_id) LEFT OUTER JOIN wallet_holder d3 ON(d1.holder_id = d3.id)";
		$sql = $sql." WHERE d1.rel_id='".$customer_id."' AND d1.status=0 AND d2.status =0";
		$sql = $sql."GROUP BY d1.holder_id, d3.code";
		
		$msg->add("query", $sql);
		
		$dt = $appSession->getTier()->getTable($msg);
		for($i =0; $i<$dt->getRowCount(); $i++)
		{
			$amount = floatval($dt->getString($i, "amount"));
			if($amount>0)
			{
				$holder_id = $dt->getString($i, "holder_id");
				$sql = "SELECT d1.amount FROM wallet_setting d1 LEFT OUTER JOIN wallet_category_rel d2 ON(d1.id = d2.rel_id AND d2.status =0) LEFT OUTER JOIN wallet_category d3 ON(d2.category_id = d3.id) LEFT OUTER JOIN wallet_holder d5 ON(d3.id = d5.category_id) WHERE d5.id ='".$holder_id."'";
				$msg->add("query", $sql);
				$limit = $appSession->getTier()->getValue($msg);
				if($limit != "")
				{
					$amount = $amount - $appSession->getTool()->toDouble($limit);
				}
				if($amount>0){
				//$amount = $amount/2;
					echo ($amount).":".$dt->getString($i, "holder_id").":".$dt->getString($i, "code").";";
				}
			}
		}
		
	}else if($category_name == "LOYALTY")
	{
		
		$session_id = "";
		if(isset($_REQUEST['session_id']))
		{
			$session_id = $_REQUEST['session_id'];
		}
		if(isset($_REQUEST['_id']))
		{
			$session_id = $_REQUEST['_id'];
		}
		$appSession->getConfig()->setProperty("session_id", $session_id);
		$sale = new Sale($appSession);
		$sale_id = $sale->findSaleId();
		
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
		echo $appSession->getTool()->respTable($dt);
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
		$session_id = "";
		if(isset($_REQUEST['session_id']))
		{
			$session_id = $_REQUEST['session_id'];
		}
		if(isset($_REQUEST['_id']))
		{
			$session_id = $_REQUEST['_id'];
		}
		$appSession->getConfig()->setProperty("session_id", $session_id);
		$sale = new Sale($appSession);
		$sale_id = $sale->findSaleId();
		
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
	
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();

	$sql = "SELECT d1.id, d1.currency_id, d1.amount, d3.code AS currency_code, d2.name AS payment_name, d1.description, d1.payment_id";
	$sql = $sql." FROM account_payment_line_local d1";
	$sql = $sql." LEFT OUTER JOIN account_payment d2 ON(d1.payment_id = d2.id)";
	$sql = $sql." LEFT OUTER JOIN res_currency d3 ON(d1.currency_id = d3.id)";
	$sql = $sql." WHERE d1.line_id='".$sale_id."' AND d1.status =0";

	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
	
}
else if($ac == "total_sale_amount")
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
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	
	$amount= $sale->totalSaleLocal($sale_id);
	if(floor($amount)<=0)
	{
		$amount = 0;
	}
	echo $amount;
	
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
	echo $appSession->getTool()->respTable($dt);
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
	echo $appSession->getTool()->respTable($dt);
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
	echo $appSession->getTool()->respTable($dt);
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
	echo $appSession->getTool()->respTable($dt);
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
	$holder_id = "";
	if(isset($_REQUEST['holder_id']))
	{
		$holder_id = $_REQUEST['holder_id'];
	}
	
	$payment_id = "";
	if(isset($_REQUEST['payment_id']))
	{
		$payment_id = $_REQUEST['payment_id'];
	}
	$currency_id = "";
	if(isset($_REQUEST['currency_id']))
	{
		$currency_id = $_REQUEST['currency_id'];
	}
	if($currency_id == "")
	{
		$currency_id = "23";
	}
	$payment_amount = 0;
	if(isset($_REQUEST['payment_amount']))
	{
		$payment_amount = $_REQUEST['payment_amount'];
	}
	
	$payment_amount = $appSession->getTool()->replace($payment_amount, ",.", ".");
	$payment_amount = $appSession->getTool()->toDouble($payment_amount);
	
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
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();

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
			$sql = $sql." WHERE d1.status=0 AND d1.holder_id='".$holder_id."' GROUP BY d1.currency_id";
			
		
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
				$builder->add("holder_id", $holder_id);
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
				$sale = new Sale($appSession);
				$sale_amount = $sale->totalSalePrice($sale_id);
				
				$sql = "SELECT d2.id, d3.currency_id, d3.amount FROM voucher_line d2 LEFT OUTER JOIN voucher d3 ON(d2.voucher_id = d3.id) WHERE d2.status =0 AND d2.status =0 AND d2.id NOT IN(select voucher_line_id FROM voucher_log WHERE status=0) AND (".$vouchers.")";
				

				
				$msg->add("query", $sql);
				
				$values = $appSession->getTier()->getArray($msg);
				if(count($values) == 0){
					$message = "Mã quà tặng không hợp lệ.";
				}
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
		}else{
			
			if($payment_amount>0)
			{
				$sql = "SELECT d1.id";
				$sql = $sql." FROM account_payment_line_local d1";
				$sql = $sql." WHERE d1.payment_id='".$payment_id."' AND d1.line_id ='".$sale_id."'";
				
				$msg->add("query", $sql);
				$payment_line_id = $appSession->getTier()->getValue($msg);
				
				if($payment_line_id != "")
				{
					$sql = "UPDATE account_payment_line_local SET amount= ".$payment_amount.", write_date=now(), status =0 WHERE id='".$payment_line_id."'";
					$msg->add("query", $sql);
					
					$payment_line_id = $appSession->getTier()->exec($msg);
					
				}else{
					$builder = $appSession->getTier()->createBuilder("account_payment_line_local");
					$builder->setName("account_payment_line_local");
					
					$builder->add("id", $appSession->getTool()->getId());
					$builder->add("create_uid", $user_id);
					$builder->add("write_uid", $user_id);
					$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("line_id", $sale_id);
					$builder->add("payment_id", $payment_id);
					$builder->add("currency_id", $currency_id);
					$builder->add("rel_id", $sale_id);
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
	echo $message;
}else if($ac == "addPaymentLine")
{
	$msg = $appSession->getTier()->createMessage();
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
	
	$payment_id = "";
	if(isset($_REQUEST['payment_id']))
	{
		$payment_id = $_REQUEST['payment_id'];
	}
	$currency_id = "";
	if(isset($_REQUEST['currency_id']))
	{
		$currency_id = $_REQUEST['currency_id'];
	}
	if($currency_id == "")
	{
		$currency_id = "23";
	}
	
	$payment_amount = "";
	if(isset($_REQUEST['payment_amount']))
	{
		$payment_amount = $_REQUEST['payment_amount'];
	}
	$description = "";
	if(isset($_REQUEST['description']))
	{
		$description = $_REQUEST['description'];
	}
	$session_id = "";
	if(isset($_REQUEST['session_id']))
	{
		$session_id = $_REQUEST['session_id'];
	}
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	

	$builder = $appSession->getTier()->createBuilder("account_payment_line_local");
	$builder->setName("account_payment_line_local");		
	$builder->add("id", $appSession->getTool()->getId());
	$builder->add("create_uid", $user_id);
	$builder->add("write_uid", $user_id);
	$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
	$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
	$builder->add("line_id", $sale_id);
	$builder->add("payment_id", $payment_id);
	$builder->add("currency_id", $currency_id);
	$builder->add("line_id", $sale_id);
	$builder->add("rel_id", $sale_id);
	$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "account_payment_line_local"));
	$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
	$builder->add("amount", $payment_amount);
	$builder->add("status", 0);
	$builder->add("description", $description);
	$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
	
	$sql = $appSession->getTier()->getInsert($builder);
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "checkPayment")
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
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$appSession->getConfig()->setProperty("session_id", $session_id);
	if($user_id != ""){
			$appSession->getConfig()->setProperty("user_id", $user_id);
		}
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	
	$paid = $sale->totalSaleLocal($sale_id);
	
	if(abs($paid) <= 1)
	{
		echo "OK";
	}
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
	
}else if($ac == "product_list_category_id")
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
	
	
	$sql = "SELECT d1.id, d1.category_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d3.name AS unit_name, d1.unit_id"; 
	$sql = $sql." FROM product d1";
	$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1)";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0) LEFT OUTER JOIN product_unit d3 ON(d1.unit_id = d3.id)";
	$sql = $sql." WHERE d1.status =0 AND d1.publish = 1 AND d1.category_id='".$category_id."'";
	
	if($search != "")
	{
		$search = $appSession->getTool()->replace($search, "'", "''");
		$sql = $sql." AND (d1.code ILIKE '%".$search."%' OR d1.name ILIKE '%".$search."%' OR lg.description ILIKE '%".$search."%')";
	}
	$sql = $sql." ORDER BY d1.sequence ASC";
	
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	
	echo $appSession->getTool()->respTable($dt);
}
else if($ac == "product_list_category")
{
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

	$search = "";
	if(isset($_REQUEST['search']))
	{
		$search = $_REQUEST['search'];
	}
	
	
	$sql = "SELECT d1.id, d1.category_id, d1.code, d1.name, lg.description AS name_lg, d12.document_id, d5.unit_price, d5.old_price, d5.unit_id, d6.name AS unit_name, d5.currency_id";
	$sql = $sql.", d7.name AS attribute_name, d8.name attribute_category_name, (SELECT SUM(product_count.quantity) FROM product_count LEFT OUTER JOIN res_company ON(product_count.company_id = res_company.id) WHERE product_count.status =0 AND product_count.rel_id= d5.id) AS unit_in_stock, d5.attribute_id, d5.type_id, d7.quantity AS factor, d7.unit_id AS second_unit_id, d9.name AS second_unit_name"; 
	$sql = $sql.", d5.company_id, d10.commercial_name, d10.name AS company_name, d5.id AS price_id, d15.name AS sticker";
	$sql = $sql." FROM product d1";
	$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1)";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
	$sql = $sql." LEFT OUTER JOIN product_price d5 ON(d1.id = d5.product_id AND d5.status =0 AND d5.publish = 1 AND d5.type='PRODUCT') LEFT OUTER JOIN product_unit d6 ON(d5.unit_id = d6.id)";
	$sql = $sql." LEFT OUTER JOIN attribute d7 ON(d5.attribute_id = d7.id)";
	$sql = $sql." LEFT OUTER JOIN attribute_category d8 ON(d7.category_id = d8.id)";
	$sql = $sql." LEFT OUTER JOIN attribute d9 ON(d7.unit_id = d9.id)";
	$sql = $sql." LEFT OUTER JOIN res_company d10 ON(d5.company_id = d10.id)";
	$sql = $sql." LEFT OUTER JOIN poster d12 ON(d5.id = d12.rel_id AND d12.publish=1)";
	$sql = $sql." LEFT OUTER JOIN product_category d13 ON(d1.category_id = d13.id)";
	$sql = $sql." LEFT OUTER JOIN product_category d14 ON(d13.parent_id = d14.id)";
	$sql = $sql." LEFT OUTER JOIN res_meta d15 ON(d1.id = d15.rel_id AND d15.type='Sticker' AND d15.status =0)";
	$sql = $sql." WHERE d1.status =0 AND d1.publish = 1 AND d13.publish=1 AND (d14.id IS NULL OR d14.publish=1)";
	
	if($search != "")
	{
		$search = $appSession->getTool()->replace($search, "'", "''");
		$sql = $sql." AND (d1.code ILIKE '%".$search."%' OR d1.name ILIKE '%".$search."%' OR lg.description ILIKE '%".$search."%')";
	}
	$sql = $sql." ORDER BY d1.sequence ASC, d5.sequence ASC";
	$sql = $sql." LIMIT 100";
	
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	
	echo $appSession->getTool()->respTable($dt);

}else if($ac == "product_list_group_id")
{
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
	
	$search = "";
	if(isset($_REQUEST['search']))
	{
		$search = $_REQUEST['search'];
	}
	$group_id = "";
	if(isset($_REQUEST['group_id']))
	{
		$group_id = $_REQUEST['group_id'];
	}
	
	$sql = "SELECT d1.id, d1.category_id, d1.code, d1.name, lg.description AS name_lg, d12.document_id, d5.unit_price, d5.old_price, d5.unit_id, d6.name AS unit_name, d5.currency_id";
	$sql = $sql.", d7.name AS attribute_name, d8.name attribute_category_name, (SELECT SUM(product_count.quantity) FROM product_count LEFT OUTER JOIN res_company ON(product_count.company_id = res_company.id) WHERE product_count.status =0 AND product_count.rel_id= d5.id) AS unit_in_stock,  d5.attribute_id, d5.type_id, d7.quantity AS factor, d7.unit_id AS second_unit_id, d9.name AS second_unit_name"; 
	$sql = $sql.", d5.company_id, d10.commercial_name, d10.name AS company_name, d5.id AS price_id, d12.document_id AS price_document_id, d20.description, d14.name AS type_name, d15.unit_price AS unit_price_cust, d15.unit_id AS unit_id_cust, d15.currency_id AS currency_id_cust, d15.id AS price_id_cust, d16.unit_price AS unit_price_cat, d16.unit_id AS unit_id_cat, d16.currency_id AS currency_id_cat, d16.id AS price_id_cat, d21.name AS sticker";
	$sql = $sql." FROM product_group_product m LEFT OUTER JOIN product d1 ON(m.product_id= d1.id)";
	$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status =0)";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
	$sql = $sql." LEFT OUTER JOIN product_price d5 ON(d1.id = d5.product_id AND d5.status =0 AND d5.publish = 1 AND d5.type='PRODUCT') LEFT OUTER JOIN product_unit d6 ON(d5.unit_id = d6.id)";
	$sql = $sql." LEFT OUTER JOIN res_company d10 ON(d5.company_id = d10.id)";
	$sql = $sql." LEFT OUTER JOIN attribute_line d11 ON(d5.attribute_id = d11.id AND d11.rel_id=d1.id) ";
	
	$sql = $sql." LEFT OUTER JOIN poster d12 ON(d5.id = d12.rel_id AND d12.publish=1 AND d12.status =0)";
	$sql = $sql." LEFT OUTER JOIN attribute d7 ON(d11.attribute_id = d7.id)";
	$sql = $sql." LEFT OUTER JOIN attribute_category d8 ON(d7.category_id = d8.id)";
	$sql = $sql." LEFT OUTER JOIN attribute d9 ON(d7.unit_id = d9.id)";
	$sql = $sql." LEFT OUTER JOIN product_category d13 ON(d1.category_id = d13.id)";
	$sql = $sql." LEFT OUTER JOIN product_type d14 ON(d11.type_id = d14.id)";
	$sql = $sql." LEFT OUTER JOIN product_price d15 ON(d1.id = d15.product_id AND d15.type='CUSTOMER' AND d15.status =0 AND d15.rel_id='".$appSession->getConfig()->getProperty("customer_id")."' AND d15.publish=1 AND d15.attribute_id = d11.id AND d15.unit_id = d5.unit_id)";
$sql = $sql." LEFT OUTER JOIN product_price d16 ON(d1.id = d16.product_id AND d16.type='CUSTOMER_CATEGORY' AND d16.status =0 AND d16.rel_id='".$appSession->getConfig()->getProperty("customer_category_id")."' AND d16.publish=1 AND d16.attribute_id = d11.id AND d16.unit_id = d5.unit_id)";
$sql = $sql."  LEFT OUTER JOIN product_note d20 ON(d11.id= d15.product_id AND d15.status =0 AND d15.publish=1)";
$sql = $sql." LEFT OUTER JOIN res_meta d21 ON(d1.id = d21.rel_id AND d21.type='Sticker' AND d21.status =0)";
	$sql = $sql." WHERE m.status =0 AND d1.status =0 AND d1.publish = 1";
	$sql = $sql." AND m.group_id='".$group_id."'";
	
	
	
	
	
	if($search != "")
	{
		$search = $appSession->getTool()->replace($search, "'", "''");
		$sql = $sql." AND (d1.name ILIKE '%".$search."%' OR lg.description ILIKE '%".$search."%')";
	}
	$sql = $sql." ORDER BY m.sequence ASC, d1.sequence ASC, d5.sequence ASC";
	$sql = $sql." LIMIT 100";
	
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	
	echo $appSession->getTool()->respTable($dt);
	
}
else if($ac == "findCompanyPrice")
{
	$product_id = "";
	if(isset($_REQUEST['product_id']))
	{
		$product_id = $_REQUEST['product_id'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$sql = "SELECT d1.unit_price, d1.publish, d2.quantity AS unit_in_stock"; 
	$sql = $sql." FROM product_price d1";
	$sql = $sql." LEFT OUTER JOIN product_count d2 ON(d1.product_id = d2.product_id AND d1.company_id = d2.company_id AND d1.unit_id = d2.unit_id)";
	$sql = $sql." WHERE d1.status =0 AND d1.product_id='".$product_id."' AND d1.company_id='".$company_id."'";

	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "updateCompanyPrice")
{
	$product_id = "";
	if(isset($_REQUEST['product_id']))
	{
		$product_id = $_REQUEST['product_id'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$unit_price = "";
	if(isset($_REQUEST['unit_price']))
	{
		$unit_price = $_REQUEST['unit_price'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$unit_id = "";
	if(isset($_REQUEST['unit_id']))
	{
		$unit_id = $_REQUEST['unit_id'];
	}
	$publish = "";
	if(isset($_REQUEST['publish']))
	{
		$publish = $_REQUEST['publish'];
	}
	$unit_in_stock = "";
	if(isset($_REQUEST['unit_in_stock']))
	{
		$unit_in_stock = $_REQUEST['unit_in_stock'];
	}
	
	$sql = "SELECT d1.id"; 
	$sql = $sql." FROM product_price d1";
	$sql = $sql." WHERE d1.status =0 AND d1.product_id='".$product_id."' AND company_id='".$company_id."'";

	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	$id = "";
	if(count($values)>0)
	{
		$id = $values[0][0];
		$sql = "UPDATE product_price SET status = 0, write_date=NOW()";
		$sql = $sql.", publish =".$publish;
		$sql = $sql.", unit_price=".$unit_price;
		$sql = $sql.", unit_id='".$unit_id."'";
		$sql = $sql." WHERE id='".$id."'";
		
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}else{
		$id = $appSession->getTool()->getId();
		$builder = $appSession->getTier()->createBuilder("product_price");
		
		$builder->add("id", $id);
		$builder->add("create_uid", $user_id);
		$builder->add("write_uid", $user_id);
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("unit_id", $unit_id);
		$builder->add("currency_id", $appSession->getConfig()->getProperty("currency_id"));
		$builder->add("status", 0);
		$builder->add("company_id", $company_id);
		
		$builder->add("product_id", $product_id);
		$builder->add("unit_price", $unit_price);
		$builder->add("publish", $publish);
		$builder->add("type", "PRODUCT");
		$sql = $appSession->getTier()->getInsert($builder);
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);	
	}
	
	$sql = "SELECT d1.id"; 
	$sql = $sql." FROM product_count d1";
	$sql = $sql." WHERE d1.product_id='".$product_id."' AND company_id='".$company_id."' AND unit_id='".$unit_id."'";
	
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	
	if(count($values)>0)
	{
		$stock_id = $values[0][0];
		$sql = "UPDATE product_count SET status = 0, write_date=NOW()";
		$sql = $sql.", quantity=".$unit_in_stock;
		$sql = $sql.", rel_id='".$id."'";
		$sql = $sql." WHERE id='".$stock_id."'";
		$msg->add("query", $sql);
		
		$appSession->getTier()->exec($msg);
	}else{
		$stock_id = $appSession->getTool()->getId();
		$builder = $appSession->getTier()->createBuilder("product_count");
		
		$builder->add("id", $stock_id);
		$builder->add("create_uid", $user_id);
		$builder->add("write_uid", $user_id);
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("unit_id", $unit_id);
		$builder->add("status", 0);
		$builder->add("company_id", $company_id);
		$builder->add("product_id", $product_id);
		$builder->add("quantity", $unit_in_stock);
		$builder->add("rel_id", $id);
		$sql = $appSession->getTier()->getInsert($builder);
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);	
	}
	
	echo $id;
}else if($ac == "product_price_company"){
	
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$search = "";
	if(isset($_REQUEST['search']))
	{
		$search = $_REQUEST['search'];
	}
	$category_id = "";
	if(isset($_REQUEST['category_id']))
	{
		$category_id = $_REQUEST['category_id'];
	}
	$sql = "SELECT d2.id, d2.unit_id, d1.currency_id, d1.unit_price, d1.publish, d2.name, lg.description AS name_lg, d3.name AS unit_name, d4.document_id, d5.quantity AS unit_in_stock"; 
	$sql = $sql." FROM product_price d1";
	$sql = $sql." LEFT OUTER JOIN product d2 ON(d1.product_id = d2.id)";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0) LEFT OUTER JOIN product_unit d3 ON(d1.unit_id = d3.id)";
	$sql = $sql." LEFT OUTER JOIN poster d4 ON(d2.id = d4.rel_id AND d4.publish=1)";
	$sql = $sql." LEFT OUTER JOIN product_count d5 ON(d1.product_id = d5.product_id AND d1.company_id = d5.company_id AND d1.unit_id= d5.unit_id)";
	$sql = $sql." WHERE d1.status =0 AND d1.company_id='".$company_id."' AND d2.category_id='".$category_id."'";
	if($search != "")
	{
		$search = $appSession->getTool()->replace($search, "'", "''");
		$sql = $sql." AND (d1.code ILIKE '%".$search."%' OR d1.name ILIKE '%".$search."%' OR lg.description ILIKE '%".$search."%')";
	}
	$sql = $sql." ORDER BY lg.description ASC, d2.name ASC";
	$msg->add("query", $sql);
	
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "add_product_wishlist"){
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
	$product_id = "";
	if(isset($_REQUEST['product_id']))
	{
		$product_id = $_REQUEST['product_id'];
	}
	$sql = "SELECT id FROM product_wishlist WHERE customer_id='".$customer_id."' AND product_id='".$product_id."' AND company_id='".$company_id."'";
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	if(count($values)>0)
	{
		$sql = "UPDATE product_wishlist SET status =0 WHERE id='".$values[0][0]."'";
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}else{
		$builder = $appSession->getTier()->createBuilder("product_wishlist");
		
		$builder->add("id", $appSession->getTool()->getId());
		$builder->add("create_uid", $user_id);
		$builder->add("write_uid", $user_id);
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("status", 0);
		$builder->add("company_id", $company_id);
		$builder->add("product_id", $product_id);
		$builder->add("customer_id", $customer_id);
		$sql = $appSession->getTier()->getInsert($builder);
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);	
	}
	echo "OK";
}else if($ac == "wishlist")
{
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
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
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$search = "";
	if(isset($_REQUEST['search']))
	{
		$search = $_REQUEST['search'];
	}
	
	$sql = "SELECT d1.id, d1.category_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d5.unit_price, d5.old_price, d5.unit_id, d6.name AS unit_name, d5.currency_id, d7.name AS attribute_name, d7.code AS attribute_code, d8.name attribute_category_name, d5.id AS price_id, d7.quantity AS factor, d7.unit_id AS second_unit_id, d9.commercial_name, d5.company_id, d9.name AS company_name";
	$sql = $sql.", (SELECT SUM(product_count.quantity) FROM product_count WHERE product_count.status =0 AND product_count.rel_id = d5.id) AS unit_in_stock1, 20 AS unit_in_stock, d5.attribute_id"; 
	$sql = $sql." FROM product_wishlist m";
	$sql = $sql." LEFT OUTER JOIN product d1 ON(m.product_id = d1.id)";
	$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status=0)";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$lang_id."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
	$sql = $sql." LEFT OUTER JOIN product_price d5 ON(m.product_id = d5.product_id AND d5.status =0 AND d5.publish = 1 AND d5.type='PRODUCT' AND d5.company_id = m.company_id) LEFT OUTER JOIN product_unit d6 ON(d5.unit_id = d6.id)";
	$sql = $sql." LEFT OUTER JOIN attribute d7 ON(d5.attribute_id = d7.id)";
	$sql = $sql." LEFT OUTER JOIN attribute_category d8 ON(d7.category_id = d8.id)";
	$sql = $sql." LEFT OUTER JOIN res_company d9 ON(m.company_id = d9.id)";
	$sql = $sql." WHERE m.status =0 AND d1.status =0 AND d1.publish = 1 AND (m.customer_id='".$customer_id."' OR m.create_uid='".$user_id."')";
	
	
	if($search != "")
	{
		$search = $appSession->getTool()->replace($search, "'", "''");
		$sql = $sql." AND (d1.name ILIKE '%".$search."%' OR lg.description ILIKE '%".$search."%')";
	}
	$sql = $sql." ORDER BY lg.description ASC,  d1.name ASC";
	$sql = $sql." LIMIT 100";
	
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "remove_product_wishlist")
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
	$product_id = "";
	if(isset($_REQUEST['product_id']))
	{
		$product_id = $_REQUEST['product_id'];
	}
	$sql = "UPDATE product_wishlist SET status=1 WHERE customer_id='".$customer_id."' AND product_id='".$product_id."' AND company_id='".$company_id."'";
	$msg->add("query", $sql);
	$r = $appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "partner_bank")
{
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$sql = "SELECT id, code, name FROM account_bank WHERE status =0";
	$sql = $sql." ORDER BY name ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
	
}else if($ac == "partner_bank_line")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$sql = "SELECT d1.bank_id, d1.code, d1.name FROM account_bank_line d1 LEFT OUTER JOIN res_partner d2 ON(d1.rel_id = d2.id) LEFT OUTER JOIN customer d3 ON(d2.id = d3.partner_id) WHERE d3.id='".$customer_id."' AND d1.status =0 AND d2.status =0";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
	
}else if($ac == "partner_bank_line_update")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$bank_id = "";
	if(isset($_REQUEST['bank_id']))
	{
		$bank_id = $_REQUEST['bank_id'];
	}
	$code = "";
	if(isset($_REQUEST['code']))
	{
		$code = $_REQUEST['code'];
	}
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	
	
	
	$sql = "SELECT id FROM account_bank_line WHERE rel_id='".$customer_id."' AND status =0";
	$msg->add("query", $sql);
	$line_id = $appSession->getTier()->getValue($msg);
	if($line_id == "")
	{
		$line_id = $appSession->getTool()->getId();
		$sql = "INSERT INTO account_bank_line(";
		$sql = $sql."id";
		$sql = $sql.", create_uid";
		$sql = $sql.", write_uid";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", company_id";
		$sql = $sql.", status";
		$sql = $sql.", rel_id";
		$sql = $sql.", code";
		$sql = $sql.", name";
		$sql = $sql.", bank_id";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$line_id."'";
		$sql = $sql.", '".$user_id."'";
		$sql = $sql.", '".$user_id."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$company_id."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$customer_id."'";
		$sql = $sql.", '".$appSession->getTool()->replace($code, "'", "''")."'";
		$sql = $sql.", '".$appSession->getTool()->replace($name, "'", "''")."'";
		$sql = $sql.", '".$appSession->getTool()->replace($bank_id, "'", "''")."'";
		$sql = $sql.")";
		
		$msg->add("query", $sql);
		$result = $appSession->getTier()->exec($msg);
	}else{
		$sql = "UPDATE account_bank_line SET bank_id='".$bank_id."'";
		$sql = $sql.", write_uid='".$user_id."'";
		$sql = $sql.", write_date=".$appSession->getTier()->getDateString();
		$sql = $sql.", code='".$appSession->getTool()->replace($code, "'", "''")."'";
		$sql = $sql.", name='".$appSession->getTool()->replace($name, "'", "''")."'";
		
		$sql = $sql." WHERE id='".$line_id."'";
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
	}
	echo "OK";
	
}else if($ac == "partner_customer")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$search = "";
	if(isset($_REQUEST['search']))
	{
		$search = $_REQUEST['search'];
	}
	$sql = "SELECT d1.id, d1.code, d1.name, d1.address, d4.id AS customer_id FROM res_partner d1 LEFT OUTER JOIN res_partner d2 ON(d1.parent_id = d2.id) LEFT OUTER JOIN customer d3 ON(d2.id = d3.partner_id) LEFT OUTER JOIN customer d4 ON(d1.id = d4.partner_id AND d4.status=0) WHERE d3.id='".$customer_id."' AND d1.status =0 AND d2.status =0";
	if($search != "")
	{
		$sql = $sql. " AND (d1.code ILIKE '%".$appSession->getTool()->replace($search, "'", "''") ."%' OR d1.name ILIKE '%".$appSession->getTool()->replace($search, "'", "''") ."')";
	}
	$sql = $sql." ORDER BY d1.name ASC LIMIT 200";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "report_invoice_by_date")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$fdate = "";
	if(isset($_REQUEST['fdate']))
	{
		$fdate = $_REQUEST['fdate'];
	}
	$tdate = "";
	if(isset($_REQUEST['tdate']))
	{
		$tdate = $_REQUEST['tdate'];
	}
	$partner_id = "";
	if(isset($_REQUEST['partner_id']))
	{
		$partner_id = $_REQUEST['partner_id'];
	}
	$sql = "SELECT EXTRACT(YEAR FROM d1.receipt_date) AS yy , EXTRACT(MONTH FROM d1.receipt_date) AS mm, EXTRACT(DAY FROM d1.receipt_date) AS dd, SUM(d1.amount) AS amount FROM account_invoice d1 LEFT OUTER JOIN customer d2 ON(d1.partner_id = d2.partner_id) WHERE d1.status =0";
	
	$sql = $sql." AND d1.receipt_date>='".$fdate."'";
	$sql = $sql." AND d1.receipt_date<='".$tdate."'";
	if($partner_id != "")
	{
		$sql = $sql." AND d1.partner_id='".$partner_id."'";
	}else{
		$sql = $sql." AND d2.id='".$customer_id."'";
	}
	$sql = $sql." GROUP BY 1, 2, 3";

	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "report_invoice_line")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$fdate = "";
	if(isset($_REQUEST['fdate']))
	{
		$fdate = $_REQUEST['fdate'];
	}
	$tdate = "";
	if(isset($_REQUEST['tdate']))
	{
		$tdate = $_REQUEST['tdate'];
	}
	$partner_id = "";
	if(isset($_REQUEST['partner_id']))
	{
		$partner_id = $_REQUEST['partner_id'];
	}
	$sql = "SELECT d1.id, d1.receipt_no, d1.receipt_date, d1.amount, d1.description, d1.rel_id FROM account_invoice d1 LEFT OUTER JOIN customer d2 ON(d1.partner_id = d2.partner_id) WHERE d1.status =0";
	
	$sql = $sql." AND d1.receipt_date>='".$fdate."'";
	$sql = $sql." AND d1.receipt_date<='".$tdate."'";
	if($partner_id != "")
	{
		$sql = $sql." AND d1.partner_id='".$partner_id."'";
	}else{
		$sql = $sql." AND d2.id='".$customer_id."'";
	}
	$sql = $sql." ORDER BY d1.receipt_date ASC";

	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "report_loyalty_line_begin")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$fdate = "";
	if(isset($_REQUEST['fdate']))
	{
		$fdate = $_REQUEST['fdate'];
	}
	$tdate = "";
	if(isset($_REQUEST['tdate']))
	{
		$tdate = $_REQUEST['tdate'];
	}
	$partner_id = "";
	if(isset($_REQUEST['partner_id']))
	{
		$partner_id = $_REQUEST['partner_id'];
	}
	$sql = "SELECT SUM(d1.point * d1.factor) AS amount FROM loyalty_point d1 WHERE d1.status =0";
	
	$sql = $sql." AND d1.receipt_date<'".$fdate."'";

	$sql = $sql." AND d1.customer_id='".$customer_id."'";

	$msg->add("query", $sql);

	$dt1 = $appSession->getTier()->getTable($msg);
	$dt = new DataTable("");
	$dt->addColumn("amount");
	$dt->addColumn("receipt_date");
	if($dt1->getRowCount()>0)
	{
		$dt->addArray([$dt1->getString(0, "amount"), $fdate]);
	}
	$sql = "SELECT (d1.point * d1.factor) AS amount, d1.receipt_date FROM loyalty_point d1 WHERE d1.status =0";
	
	$sql = $sql." AND d1.receipt_date>='".$fdate."'";
	$sql = $sql." AND d1.receipt_date<='".$tdate."'";
	$sql = $sql." AND d1.customer_id='".$customer_id."'";

	$msg->add("query", $sql);
	$dt1 = $appSession->getTier()->getTable($msg);
	$datas = $dt1->getData();
	for($i=0; $i<count($datas); $i++)
	{
		$dt->addArray($datas[$i]);
	}
	
	echo $appSession->getTool()->respTable($dt);
}
else if($ac == "report_loyalty_line")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$fdate = "";
	if(isset($_REQUEST['fdate']))
	{
		$fdate = $_REQUEST['fdate'];
	}
	$tdate = "";
	if(isset($_REQUEST['tdate']))
	{
		$tdate = $_REQUEST['tdate'];
	}
	$partner_id = "";
	if(isset($_REQUEST['partner_id']))
	{
		$partner_id = $_REQUEST['partner_id'];
	}
	$sql = "SELECT d1.id, d1.receipt_no, d1.receipt_date, (d1.point * d1.factor) AS amount, d1.description, d1.rel_id FROM loyalty_point d1 WHERE d1.status =0";
	
	$sql = $sql." AND d1.receipt_date>='".$fdate."'";
	$sql = $sql." AND d1.receipt_date<='".$tdate."'";
	
	$sql = $sql." AND d1.id='".$customer_id."'";
	$sql = $sql." ORDER BY d1.receipt_date ASC";

	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}
else if($ac == "sale_product_sale"){
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
	
	
	
	$sql = "SELECT m.id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d3.name AS unit_name, d4.code AS currency_code, m.quantity, m.unit_price, m.unit_id, m.currency_id, d1.description, m.attribute_id, d5.name AS attribute_name, d6.name AS attribute_category_name";
	$sql =$sql.", m.factor, m.second_unit_id, d7.name AS second_unit_name";
	$sql = $sql." FROM sale_product m";
	$sql = $sql." LEFT OUTER JOIN product d1 ON(m.product_id = d1.id)";
	$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1)";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
	$sql = $sql." LEFT OUTER JOIN product_unit d3 ON(m.unit_id = d3.id)";
	$sql = $sql." LEFT OUTER JOIN res_currency d4 ON(m.currency_id = d4.id)";
	$sql = $sql." LEFT OUTER JOIN attribute d5 ON(m.attribute_id = d5.id)";
	$sql = $sql." LEFT OUTER JOIN attribute_category d6 ON(d5.category_id = d6.id)";
	$sql = $sql." LEFT OUTER JOIN product_unit d7 ON(m.second_unit_id = d7.id)";
	$sql = $sql." WHERE m.status =0 AND m.sale_id='".$sale_id."'";
	$sql = $sql." ORDER BY m.create_date ASC";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);

	echo $appSession->getTool()->respTable($dt);
}else if($ac == "sale_service_sale")
{

	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$sql = "SELECT d1.percent, d1.value, d1.category_id, d1.operator, d2.name";
	$sql = $sql." FROM account_service_line d1";
	$sql = $sql." LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id)";
	$sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 ORDER BY d1.sequence ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "customer_type")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$sql = "SELECT type_id FROM customer WHERE id='".$customer_id."'";
	$msg->add("query", $sql);
	$customer_type_id = $appSession->getTier()->getValue($msg);
	
	$sql = "SELECT d1.id, d1.name, '".$customer_type_id."' AS type_id";
	$sql = $sql." FROM customer_type d1";
	$sql = $sql." WHERE d1.status =0 ORDER BY d1.sequence ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "update_customer_type")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$type_id = "";
	if(isset($_REQUEST['type_id']))
	{
		$type_id = $_REQUEST['type_id'];
	}
	$sql = "UPDATE customer SET type_id='".$type_id."', write_date=NOW()";
	$sql = $sql." WHERE id ='".$customer_id."'";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "wallet_category")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$sql = "SELECT category_id FROM wallet_holder WHERE rel_id='".$customer_id."' AND status =0";
	$msg->add("query", $sql);
	$customer_type_id = $appSession->getTier()->getValue($msg);
	
	$sql = "SELECT d1.id, d1.name, '".$customer_type_id."' AS category_id";
	$sql = $sql." FROM wallet_category d1";
	$sql = $sql." WHERE d1.status =0 ORDER BY d1.sequence ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "update_wallet_category_holder")
{
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$category_id = "";
	if(isset($_REQUEST['category_id']))
	{
		$category_id = $_REQUEST['category_id'];
	}
	$sql = "UPDATE wallet_holder SET category_id='".$category_id."', write_date=NOW()";
	$sql = $sql." WHERE rel_id ='".$customer_id."'";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "deliveryDateChanged")
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
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$delivery_date = "";
	if(isset($_REQUEST['delivery_date']))
	{
		$delivery_date = $_REQUEST['delivery_date'];
	}
	$message = "";
	if($delivery_date != "")
	{
		date_default_timezone_set("Asia/Ho_Chi_Minh");
		
		
		$sale = new Sale($appSession);
		$sale_id = $sale->findSaleId();
		$sql = "SELECT d1.id, d1.status, d1.service_id";
		$sql = $sql." FROM account_service_line_local d1";
		$sql = $sql." WHERE d1.description='DELIVERY_DATE' AND d1.rel_id='".$sale_id."'";
		$msg->add("query", $sql);
		$arr = $appSession->getTier()->getArray($msg);
		
		
		$service_id2 ='02078823-a49a-4f8e-b9b5-205846196cbc';
		$service_id3 ='a4686f17-62df-4398-9c33-5f3a3d4e980b';
		$d1 = strtotime($delivery_date);
		$d2 = strtotime(date("Y-m-d H:i:s"));
		$diff = $d1-$d2;
		$days = 0;
		if($diff>0)
		{
			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		}
		
		
		$service_id = "";
		if($days>=0)
		{
			$percent = 0;
			if($days<=1)
			{
				$service_id = $service_id3;
				$percent = 0.03;
			}else if($days<=2)
			{
				$service_id = $service_id2;
				$percent = 0.02;
				
			}
			if($service_id != "")
			{
				if(count($arr)>0)
				{
					if($arr[0][1] == "1" || $arr[0][2] != $service_id)
					{
						$sql = "UPDATE account_service_line_local SET percent=".$percent.", service_id='".$service_id."', write_date=NOW(), status =0 WHERE id='".$arr[0][0]."'";
						$msg->add("query", $sql);
						$appSession->getTier()->exec($msg);
						$message = "OK";
					
					}else{
						$arr =[];
					}
					
				}else{
					$builder = $appSession->getTier()->createBuilder("account_service_line_local");
					$service_line_id = $appSession->getTool()->getId();
					$builder->add("id", $appSession->getTool()->getId());
					$builder->add("service_id", $service_id);
					$builder->add("rel_id", $sale_id);
					$builder->add("percent", $percent);
					$builder->add("value", 0);
					$builder->add("category_id", "DISCOUNT");
					$builder->add("operator", "-");
					$builder->add("sequence", 1);
					$builder->add("description", "DELIVERY_DATE");
					$builder->add("create_uid", $appSession->getConfig()->getProperty("user_id"));
					$builder->add("write_uid", $appSession->getConfig()->getProperty("user_id"));
					$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("receipt_date", $appSession->getTier()->getDateString(), 'f');
					$builder->add("status", 0);
					$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
					$sql = $appSession->getTier()->getInsert($builder);
					$msg->add("query", $sql);
					$appSession->getTier()->exec($msg);
					$message = "OK";
				}
			}
		}
		if(count($arr)>0 && $message == "")
		{
			$sql = "UPDATE account_service_line_local SET status=1, write_date=NOW() WHERE id='".$arr[0][0]."'";
			$msg->add("query", $sql);
			$appSession->getTier()->exec($msg);
			$message = "OK";
		}
				
		
	}
	echo $message;
	
}else if($ac == "addressShippingChanged")
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
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
	}
	$address_id = "";
	if(isset($_REQUEST['address_id']))
	{
		$address_id = $_REQUEST['address_id'];
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
	
	$appSession->getConfig()->setProperty("session_id", $session_id);
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	$sql = "SELECT id FROM sale_shipping WHERE sale_id='".$sale_id."'";
	$msg->add("query", $sql);
	$shipping_id = $appSession->getTier()->getValue($msg);
	if($shipping_id == "")
	{
		$builder = $appSession->getTier()->createBuilder("sale_shipping");
		$id = $appSession->getTool()->getId();
		$builder->add("id", $id);
		$builder->add("sale_id", $sale_id);
		$builder->add("create_uid", $appSession->getUserInfo()->getId());
		$builder->add("write_uid", $appSession->getUserInfo()->getId());
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("company_id", $company_id);
		$builder->add("address_id", $address_id);
		$builder->add("status", 0);
		$sql = $appSession->getTier()->getInsert($builder);

		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}else{
		$builder = $appSession->getTier()->createBuilder("sale_shipping");
		$id = $shipping_id;
		$builder->add("id", $id);
		$builder->add("sale_id", $sale_id);
		$builder->add("create_uid", $appSession->getUserInfo()->getId());
		$builder->add("write_uid", $appSession->getUserInfo()->getId());
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("company_id", $company_id);
		$builder->add("address_id", $address_id);
		$builder->add("status", 0);
		$sql = $appSession->getTier()->getUpdate($builder);

		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}
	$sql = "SELECT customer_id FROM sale_local WHERE id='".$sale_id."'";
	$msg->add("query", $sql);
	$customer_id = $appSession->getTier()->getValue($msg);
	$sale->checkSaleService($sale_id, $customer_id);
	echo "OK";
	
}else if($ac == "saveShipping")
{
	$msg = $appSession->getTier()->createMessage();
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	if($user_id == "")
	{
		$user_id = $appSession->getConfig()->getProperty("user_id");
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
	$session_id = "";
	if(isset($_REQUEST['_id']))
	{
		$session_id = $_REQUEST['_id'];
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
	$delivery_date = "";
	if(isset($_REQUEST['delivery_date']))
	{
		$delivery_date = $_REQUEST['delivery_date'];
	}
	$address = "";
	if(isset($_REQUEST['delivery_address']))
	{
		$address = $_REQUEST['delivery_address'];
	}
	if(isset($_REQUEST['delivery_address']))
	{
		$address = $_REQUEST['delivery_address'];
	}
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
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$sql = "SELECT id FROM sale_shipping WHERE sale_id='".$sale_id."'";
	$msg->add("query", $sql);
	$shipping_id = $appSession->getTier()->getValue($msg);
	if($shipping_id == "")
	{
		$builder = $appSession->getTier()->createBuilder("sale_shipping");
		$id = $appSession->getTool()->getId();
		$builder->add("id", $id);
		$builder->add("sale_id", $sale_id);
		$builder->add("create_uid", $appSession->getUserInfo()->getId());
		$builder->add("write_uid", $appSession->getUserInfo()->getId());
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("company_id", $company_id);
		$builder->add("name", $name);
		$builder->add("tel", $tel);
		$builder->add("email", $email);
		$builder->add("address", $address);
		$builder->add("description", $description);
		if($delivery_date != "")
		{
			$builder->add("start_date", $delivery_date);
		}
		
		$builder->add("address_id", $address_id);
		$builder->add("status", 0);
		$sql = $appSession->getTier()->getInsert($builder);

		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}else{
		$builder = $appSession->getTier()->createBuilder("sale_shipping");
		$id = $shipping_id;
		$builder->add("id", $id);
		$builder->add("sale_id", $sale_id);
		$builder->add("create_uid", $appSession->getUserInfo()->getId());
		$builder->add("write_uid", $appSession->getUserInfo()->getId());
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("company_id", $company_id);
		$builder->add("name", $name);
		$builder->add("tel", $tel);
		$builder->add("email", $email);
		$builder->add("address", $address);
		$builder->add("description", $description);
		if($delivery_date != "")
		{
			$builder->add("start_date", $delivery_date);
		}
		
		$builder->add("address_id", $address_id);
		$builder->add("status", 0);
		$sql = $appSession->getTier()->getUpdate($builder);

		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}
	if($customer_id != "")
	{
		$sql = "SELECT id FROM sale_shipping WHERE sale_id='".$customer_id."'";
		$msg->add("query", $sql);
		$shipping_id = $appSession->getTier()->getValue($msg);
		if($shipping_id == "")
		{
			$builder = $appSession->getTier()->createBuilder("sale_shipping");
			$id = $appSession->getTool()->getId();
			$builder->add("id", $id);
			$builder->add("sale_id", $customer_id);
			$builder->add("create_uid", $appSession->getUserInfo()->getId());
			$builder->add("write_uid", $appSession->getUserInfo()->getId());
			$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
			$builder->add("company_id", $company_id);
			$builder->add("name", $name);
			$builder->add("tel", $tel);
			$builder->add("email", $email);
			$builder->add("address", $address);
			$builder->add("description", $description);
			if($delivery_date != "")
			{
				$builder->add("start_date", $delivery_date);
			}
			$builder->add("address_id", $address_id);
			$builder->add("status", 0);
			$sql = $appSession->getTier()->getInsert($builder);

			$msg->add("query", $sql);
			$appSession->getTier()->exec($msg);
		}else{
			$builder = $appSession->getTier()->createBuilder("sale_shipping");
			$id = $shipping_id;
			$builder->add("id", $id);
			$builder->add("sale_id", $customer_id);
			$builder->add("create_uid", $appSession->getUserInfo()->getId());
			$builder->add("write_uid", $appSession->getUserInfo()->getId());
			$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
			$builder->add("company_id", $company_id);
			$builder->add("name", $name);
			$builder->add("tel", $tel);
			$builder->add("email", $email);
			$builder->add("address", $address);
			$builder->add("description", $description);
			if($delivery_date != "")
			{
				$builder->add("start_date", $delivery_date);
			}
			$builder->add("address_id", $address_id);
			$builder->add("status", 0);
			$sql = $appSession->getTier()->getUpdate($builder);

			$msg->add("query", $sql);
			$appSession->getTier()->exec($msg);
		}
	}
		
	
}else if($ac == "add_order_rel")
{
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	$sql = "SELECT d1.id FROM res_rel d1 WHERE d1.rel_id='".$customer_id."' AND res_id='".$sale_id."'";
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount()>0)
	{
		$id = $dt->getString(0, "id");
		$sql = "UPDATE res_rel SET status=0, write_date=NOW() WHERE id='".$id."'";
		$msg->add("query", $sql);
		$dt = $appSession->getTier()->exec($msg);
	}else{
		$sql = "INSERT INTO res_rel(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", status";
		$sql = $sql.", res_id";
		$sql = $sql.", rel_id";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$appSession->getTool()->getId()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$sale_id."'";
		$sql = $sql.", '".$customer_id."'";
		$sql = $sql.")";
		
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}
	echo "OK";
	
}
?>