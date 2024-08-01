<?php
require_once(ABSPATH.'api/Sale.php' );
require_once(ABSPATH.'api/Product.php' );
$msg = $appSession->getTier()->createMessage();
$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
if($ac == "addProduct")
{
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
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
	$currency_id = "";
	if(isset($_REQUEST['currency_id']))
	{
		$currency_id = $_REQUEST['currency_id'];
	}
	$quantity = "";
	if(isset($_REQUEST['quantity']))
	{
		$quantity = $_REQUEST['quantity'];
	}
	if($quantity == "")
	{
		exit();
	}
	$unit_price = "";
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
	$type_id = "";
	if(isset($_REQUEST['type_id']))
	{
		$type_id = $_REQUEST['type_id'];
	}
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	$sale_product_id = $sale->addProduct($product_id, $currency_id, $unit_id, $attribute_id, $quantity, $unit_price, $second_unit_id, $factor, $description, $company_id, $rel_id, $type_id, "");
	$sql = "SELECT id, product_id, unit_id, currency_id, attribute_id, type_id, quantity, unit_price FROM product_modifier WHERE rel_id='".$product_id."' AND status =0";
	$user_id = $appSession->getConfig()->getProperty("user_id");
	
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	for($i =0; $i<$dt->getRowCount(); $i++)
	{
		 $sale->addProductBySaleId($sale_id, $user_id, $dt->getString($i, "product_id"), $dt->getString($i, "currency_id"), $dt->getString($i, "unit_id"), $dt->getString($i, "attribute_id"), $dt->getString($i, "quantity"), $dt->getString($i, "unit_price"), "", $factor, "", $company_id, $dt->getString($i, "id"), $dt->getString($i, "type_id"), $sale_product_id);
	}
	
	echo $sale_product_id;
}else if($ac == "viewCardCount")
{
	$sale = new Sale($appSession);
	$sale_id = $sale->findSaleId();
	$currency_id = $appSession->getConfig()->getProperty("currency_id");
	echo $sale->getItemCount().";".$appSession->getCurrency()->format($currency_id, $sale->totalSalePrice($sale_id));
}
else if($ac == "checkOut")
{
	$delivery_name = "";
	if(isset($_REQUEST['delivery_name']))
	{
		$delivery_name = $_REQUEST['delivery_name'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
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
	$delivery_date = "";
	if(isset($_REQUEST['delivery_date']))
	{
		$delivery_date = $_REQUEST['delivery_date'];
	}
	
	$sale = new Sale($appSession);
	echo $sale->checkOut($delivery_name, $company_id, $delivery_tel, $delivery_email, $address_id, $delivery_address, $delivery_description, $delivery_date);
}else if($ac == "addToWishList")
{
	$product = new Product($appSession);
	echo $product->addProductWishList($product_id);
}else if($ac == "addToWishList")
{
	$product_id = "";
	if(isset($_REQUEST['product_id']))
	{
		$product_id = $_REQUEST['product_id'];
	}
	$product = new Product($appSession);
	echo $product->addProductWishList($product_id);
}else if($ac == "viewWishListCount")
{
	$product = new Product($appSession);
	echo $product->countProductWishList();
}else if($ac == "removeCard")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sale = new Sale($appSession);
	echo $sale->removeCard($id);
}
else if($ac == "updateCard")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$quantity = "1";
	if(isset($_REQUEST['quantity']))
	{
		$quantity = $_REQUEST['quantity'];
	}
	$sale = new Sale($appSession);
	echo $sale->removeCard($id);
}
?>