<?php
require_once(ABSPATH . 'api/Sale.php');
require_once(ABSPATH . 'api/Product.php');
require_once(ABSPATH . 'api/Status.php');
require_once(ABSPATH . 'api/Account.php');
$ac = "";
if (isset($_REQUEST['ac'])) {
    $ac = $_REQUEST['ac'];
}
$msg = $appSession->getTier()->createMessage();
if ($ac == "addProduct") {
    $product_id = "";
    if (isset($_REQUEST['product_id'])) {
        $product_id = $_REQUEST['product_id'];
    }
    $unit_id = "";
    if (isset($_REQUEST['unit_id'])) {
        $unit_id = $_REQUEST['unit_id'];
    }
    $attribute_id = "";
    if (isset($_REQUEST['attribute_id'])) {
        $attribute_id = $_REQUEST['attribute_id'];
    }
    $currency_id = "";
    if (isset($_REQUEST['currency_id'])) {
        $currency_id = $_REQUEST['currency_id'];
    }
    $quantity = "";
    if (isset($_REQUEST['quantity'])) {
        $quantity = $_REQUEST['quantity'];
    }
    $unit_price = "";
    if (isset($_REQUEST['unit_price'])) {
        $unit_price = $_REQUEST['unit_price'];
    }
    $second_unit_id = "";
    if (isset($_REQUEST['second_unit_id'])) {
        $second_unit_id = $_REQUEST['second_unit_id'];
    }
    $factor = "1";
    if (isset($_REQUEST['factor'])) {
        $factor = $_REQUEST['factor'];
    }
    $description = "";
    if (isset($_REQUEST['description'])) {
        $description = $_REQUEST['description'];
    }
    $sale = new Sale($appSession);
    $sale_detail_id = $sale->addProduct($product_id, $currency_id, $unit_id, $attribute_id, $quantity, $unit_price, $second_unit_id, $factor, $description);
    echo $sale_detail_id;
} else if ($ac == "viewCardCount") {
    $sale = new Sale($appSession);
    $sale_id = $sale->findSaleId();
    $currency_id = $appSession->getConfig()->getProperty("currency_id");
    echo $sale->getItemCount() . ";" . $appSession->getCurrency()->format($currency_id, $sale->totalSalePrice($sale_id));
} else if ($ac == "checkOut") {
    $delivery_name = "";
    if (isset($_REQUEST['delivery_name'])) {
        $delivery_name = $_REQUEST['delivery_name'];
    }
    $company_id = "";
    if (isset($_REQUEST['company_id'])) {
        $company_id = $_REQUEST['company_id'];
    }
    $delivery_tel = "";
    if (isset($_REQUEST['delivery_tel'])) {
        $delivery_tel = $_REQUEST['delivery_tel'];
    }
    $delivery_email = "";
    if (isset($_REQUEST['delivery_email'])) {
        $delivery_email = $_REQUEST['delivery_email'];
    }
    $delivery_address = "";
    if (isset($_REQUEST['delivery_address'])) {
        $delivery_address = $_REQUEST['delivery_address'];
    }
    $delivery_description = "";
    if (isset($_REQUEST['delivery_description'])) {
        $delivery_description = $_REQUEST['delivery_description'];
    }
    $address_id = "";
    if (isset($_REQUEST['address_id'])) {
        $address_id = $_REQUEST['address_id'];
    }

    $sale = new Sale($appSession);
    echo $sale->checkOut($delivery_name, $company_id, $delivery_tel, $delivery_email, $address_id, $delivery_address, $delivery_description);
} else if ($ac == "addToWishList") {
    $product_id = "";
    if (isset($_REQUEST['product_id'])) {
        $product_id = $_REQUEST['product_id'];
    }
    $product = new Product($appSession);
    echo $product->addProductWishList($product_id);
} else if ($ac == "viewWishListCount") {
    $product = new Product($appSession);
    echo $product->countProductWishList();
} else if ($ac == "removeToWishList") {
    $product_id = "";
    if (isset($_REQUEST['product_id'])) {
        $product_id = $_REQUEST['product_id'];
    }
    $product = new Product($appSession);
    echo $product->removeWishList($product_id);
} else if ($ac == "removeCard") {
    $id = "";
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
    }
    $sale = new Sale($appSession);
    echo $sale->removeCard($id);
} else if ($ac == "updateCard") {
    $id = "";
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
    }
    $quantity = "1";
    if (isset($_REQUEST['quantity'])) {
        $quantity = $_REQUEST['quantity'];
    }
    $sale = new Sale($appSession);
    echo $sale->removeCard($id);
}else if($ac == "sale_status"){
	$status_id = "";
	if(isset($_REQUEST['status_id']))
	{
		$status_id = $_REQUEST['status_id'];
	}
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
	
	if($status_id != "")
	{
		$status = new Status($appSession);
		$status->doStatus($sale_id, "sale_local", $status_id, $company_id);
	}
	echo "OK";
}else if($ac == "close_bill")
{
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	
	$sale = new Sale($appSession);
	echo $sale->closeBill($sale_id);

}else if($ac== "createAccountInvoice")
{
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$type = "";
	if(isset($_REQUEST['type']))
	{
		$type = $_REQUEST['type'];
	}
	$type = "";
	if(isset($_REQUEST['type']))
	{
		$type = $_REQUEST['type'];
	}
	$partner_id = "";
	if(isset($_REQUEST['partner_id']))
	{
		$partner_id = $_REQUEST['partner_id'];
	}
	$category_id = "";
	if(isset($_REQUEST['category_id']))
	{
		$category_id = $_REQUEST['category_id'];
	}
	$status_id = "";
	if(isset($_REQUEST['status_id']))
	{
		$status_id = $_REQUEST['status_id'];
	}
	$currency_id = "";
	if(isset($_REQUEST['currency_id']))
	{
		$currency_id = $_REQUEST['currency_id'];
	}
	$amount = "";
	if(isset($_REQUEST['amount']))
	{
		$amount = $_REQUEST['amount'];
	}
	$payment_term_id = "";
	if(isset($_REQUEST['payment_term_id']))
	{
		$payment_term_id = $_REQUEST['payment_term_id'];
	}
	$receipt_no = "";
	if(isset($_REQUEST['receipt_no']))
	{
		$receipt_no = $_REQUEST['receipt_no'];
	}
	$receipt_date = "";
	if(isset($_REQUEST['receipt_date']))
	{
		$receipt_date = $_REQUEST['receipt_date'];
	}
	$origin_no = "";
	if(isset($_REQUEST['origin_no']))
	{
		$origin_no = $_REQUEST['origin_no'];
	}
	$origin_date = "";
	if(isset($_REQUEST['origin_date']))
	{
		$origin_date = $_REQUEST['origin_date'];
	}
	$description = "";
	if(isset($_REQUEST['description']))
	{
		$description = $_REQUEST['description'];
	}
	$account= new Account($appSession);
	echo $account->createInvoice($rel_id,$type, $partner_id, $category_id, $status_id, $payment_term_id, $currency_id, $amount, $receipt_no, $receipt_date, $origin_no, $origin_date, $description );
}else if($ac == "removeInvoice")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	
	$sql = "UPDATE account_invoice SET status =1, write_date=NOW() WHERE id='".$id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";

}else if($ac== "addPayment")
{
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
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
	$account= new Account($appSession);
	
	
	echo $account->addPaymentLocal($rel_id,$payment_id, $currency_id, $amount, $description );
	
}else if($ac == "updateSaleCompany")
{
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
	$sql = "UPDATE sale_local SET company_id ='".$company_id."', write_date=NOW() WHERE id='".$sale_id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}
else if($ac == "removePayment")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	
	$sql = "UPDATE account_payment_line_local SET status =1, write_date=NOW() WHERE id='".$id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}