<?php
validUser($appSession);
require_once(ABSPATH.'api/Sale.php' );
require_once(ABSPATH.'api/Account.php' );

$msg = $appSession->getTier()->createMessage();
$ac = '';
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}


$sale_id = '';
if(isset($_REQUEST['sale_id']))
{
	$sale_id = $_REQUEST['sale_id'];
}

$msg = $appSession->getTier()->createMessage();




if($ac == "view")
{
	$sql = "SELECT d1.customer_id, d2.partner_id, d1.order_no, d1.order_date FROM sale_local d1 LEFT OUTER JOIN customer d2 ON(d1.customer_id = d2.id) WHERE d1.id='".$sale_id."'";

$msg->add("query", $sql);
$arr = $appSession->getTier()->getArray($msg);
$customer_id = "";
$status_id = "";
$partner_id = "";
$receipt_no = "";
$receipt_date = "";
$origin_no = "";
$origin_date = "";
if(count($arr)>0)
{
	$customer_id = $arr[0][0];
	$partner_id = $arr[0][1];
	if($partner_id == "")
	{
		$account = new Account($appSession);
		$partner_id = $account->customerToPartner($customer_id);
	}
	$origin_no = $arr[0][2];
	$receipt_date = $arr[0][3];
	$origin_date = $arr[0][3];
	
}


	$sql = "SELECT d2.id AS sale_id, d1.id AS sale_product_id ,d2.order_no, d2.order_date, d7.name AS delivery_name, d3.code AS product_code, d3.name AS product_name";
	$sql = $sql.", d4.name AS unit_name, d5.name AS attriubte_name, d6.name AS type_name";
	$sql = $sql.", d1.currency_id, d1.quantity, d1.unit_price, d1.product_id, d1.unit_id, d1.attribute_id, d1.type_id, d1.rel_id";
	$sql = $sql." FROM sale_product_local d1";
	$sql = $sql." LEFT OUTER JOIN sale_local d2 ON(d1.sale_id = d2.id)";
	$sql = $sql." LEFT OUTER JOIN product d3 ON(d1.product_id = d3.id)";
	$sql = $sql." LEFT OUTER JOIN product_unit d4 ON(d1.unit_id = d4.id)";
	$sql = $sql." LEFT OUTER JOIN attribute d5 ON(d1.attribute_id = d5.id)";
	$sql = $sql." LEFT OUTER JOIN product_type d6 ON(d1.type_id = d6.id)";
	$sql = $sql." LEFT OUTER JOIN sale_shipping d7 ON(d2.id = d7.sale_id)";
	$sql = $sql." WHERE d1.status =0 AND d2.id='".$sale_id."'";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	$total = 0;
	$currency_id = "23";
	for($i =0; $i<$dt->getRowCount(); $i++)
	{
		$sale_product_id = $dt->getString($i, "sale_product_id");
		$product_code = $dt->getString($i, "product_code");
		$product_name = $dt->getString($i, "product_name");
		$quantity = $dt->getFloat($i, "quantity");
		$unit_price = $dt->getFloat($i, "unit_price");
		$amount = $quantity * $unit_price;
		$total = $total + $amount;
	
	}
	
	$sql = "SELECT d1.percent, d1.value, d1.category_id, d1.operator, d2.name";
	$sql = $sql." FROM account_service_line_local d1";
	$sql = $sql." LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id)";
	$sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 ORDER BY d1.sequence ASC";
	$msg->add("query", $sql);
						
	$serviceList = $appSession->getTier()->getArray($msg);
	$subTotal = $total;
	$amount = $total;
	for($i =0; $i<count($serviceList); $i++)
	{
		$a = ($total * floatval($serviceList[$i][0])) + floatval($serviceList[$i][1]);
		if($serviceList[$i][3] == "+")
		{
			$amount =  $amount + $a;
			$total =  $total + $a;
		}else if($serviceList[$i][3] == "-")
		{
			$amount =  $amount -  $a;
			$total =  $total - $a;
		}else if($serviceList[$i][3] == "*")
		{
			$amount =  $amount *  $a;
			$total =  $total * $a;
		}else if($serviceList[$i][3] == "/")
		{
			$amount =  $amount /  $a;
			$total =  $total / $a;
		}
		
	}
	
	$sql = "SELECT d1.id, d1.name";
	$sql = $sql." FROM account_invoice_category d1";
	$sql = $sql." WHERE d1.company_id='".$appSession->getConfig()->getProperty("company_id")."' AND d1.status =0 ORDER BY d1.name ASC";
	$msg->add("query", $sql);			
	$categoryList = $appSession->getTier()->getArray($msg);
	
	$sql = "SELECT d1.id, d1.name";
	$sql = $sql." FROM account_invoice_status d1";
	$sql = $sql." WHERE d1.company_id='".$appSession->getConfig()->getProperty("company_id")."' AND d1.status =0 ORDER BY d1.name ASC";
	$msg->add("query", $sql);			
	$statusList = $appSession->getTier()->getArray($msg);
	
	
	$sql = "SELECT d1.id, d1.name";
	$sql = $sql." FROM account_payment_term d1";
	$sql = $sql." WHERE d1.company_id='".$appSession->getConfig()->getProperty("company_id")."' AND d1.status =0 ORDER BY d1.name ASC";
	$msg->add("query", $sql);			
	$paymentTermList = $appSession->getTier()->getArray($msg);
	
	
	$sql = "SELECT d1.id, d1.code";
	$sql = $sql." FROM res_currency d1";
	$sql = $sql." WHERE d1.status =0 ORDER BY d1.code ASC";
	$msg->add("query", $sql);			
	$currencyList = $appSession->getTier()->getArray($msg);
	
	$sql = "SELECT d1.id, d1.receipt_no, d1.receipt_date, d2.name AS category_name, d2.name AS status_name, d4.code AS currency_code, d1.currency_id, d1.amount, d1.description";
	$sql = $sql." FROM account_invoice d1";
	$sql = $sql." LEFT OUTER JOIN account_invoice_category d2 ON(d1.category_id = d2.id)";
	$sql = $sql." LEFT OUTER JOIN account_invoice_status d3 ON(d1.status_id = d3.id)";
	$sql = $sql." LEFT OUTER JOIN res_currency d4 ON(d1.currency_id = d4.id)";
	
	$sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);			
	$invoiceList = $appSession->getTier()->getTable($msg);
	
	
	?>
	<div class="row">
		<div class="col-12">
			<table class="table">
				<tr>
					<th>Số</th>
					<th>Ngày</th>
					<th>Loại</th>
					<th>Loại tiền</th>
					<th>Số tiền</th>
					<th>Nội dung</th>
					<th width="40"></th>
				</tr>
				<?php
				for($i =0; $i<$invoiceList->getRowCount(); $i++)
				{
					$id = $invoiceList->getString($i, "id");
					$receipt_no = $invoiceList->getString($i, "receipt_no");
					$receipt_date = $invoiceList->getString($i, "receipt_date");
					if($receipt_date != "")
					{
						$receipt_date = $appSession->getFormats()->getDATE()->formatDATE($appSession->getTool()->toDateTime($receipt_date));
					}
					$category_name = $invoiceList->getString($i, "category_name");
					$currency_code = $invoiceList->getString($i, "currency_code");
					$currency_id = $invoiceList->getString($i, "currency_id");
					$amount = $invoiceList->getFloat($i, "amount");
					$amount = $appSession->getCurrency()->format($currency_id, $amount);
					$description = $invoiceList->getString($i, "description");
				?>
				<tr>
					<td><?php echo $receipt_no;?></td>
					<td><?php echo $receipt_date;?></td>
					<td><?php echo $category_name;?></td>
					<td><?php echo $currency_code;?></td>
					<td><?php echo $amount;?></td>
					<td><?php echo $description;?></td>
					<td><a href="javascript:removeInvoice('<?php echo $id;?>');"><img src="<?php echo URL;?>assets/images/remove.png"/></a></td>
				</tr>
				<?php
				}
				?>
			</table>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-2">
			Loại: 
		</div>
		<div class="col-5">
			<select  id="editcategory_id" class="form-control" style="color:black">
				<?php
				for($i =0; $i<count($categoryList); $i++)
				{
				?>
				<option value="<?php echo $categoryList[$i][0];?>"><?php echo $categoryList[$i][1];?></option>
				<?php
				}
				?>
			</select>
		</div>
		<div class="col-2">
			Tình trạng: 
		</div>
		<div class="col-3">
			<select id="editstatus_id" class="form-control" style="color:black">
				<?php
				for($i =0; $i<count($statusList); $i++)
				{
				?>
				<option value="<?php echo $statusList[$i][0];?>"><?php echo $statusList[$i][1];?></option>
				<?php
				}
				?>
			</select>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-2">
			Điều khoản thanh toán: 
		</div>
		<div class="col-10">
			<select  id="editpayment_term_id" class="form-control" style="color:black">
				<?php
				for($i =0; $i<count($paymentTermList); $i++)
				{
				?>
				<option value="<?php echo $paymentTermList[$i][0];?>"><?php echo $paymentTermList[$i][1];?></option>
				<?php
				}
				?>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-2">
			Loại tiền: 
		</div>
		<div class="col-4">
			<select class="form-control" style="color:black" id="editcurency_id">
				<?php
				for($i =0; $i<count($currencyList); $i++)
				{
				?>
				<option value="<?php echo $currencyList[$i][0];?>"><?php echo $currencyList[$i][1];?></option>
				<?php
				}
				?>
			</select>
		</div>
		<div class="col-2">
			Số tiền: 
		</div>
		<div class="col-4">
			<input class="form-control" id="editamount" type="number" value="<?php echo $total;?>"/>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-2">
			Nội dung: 
		</div>
		<div class="col-10">
			<textarea class="form-control" id="editdescription"></textarea>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-2">
			 
		</div>
		<div class="col-4">
		<button type="button" class="btn rounded-pill" onclick="doAccountInvoice()">Thêm</button>
		</div>
		<div class="col-6">
			 
		</div>
	</div>
	
<script>
	function doAccountInvoice()
	{
		var ctr = document.getElementById('editcategory_id');
		var category_id = ctr.value;
		
		ctr = document.getElementById('editstatus_id');
		var status_id = ctr.value;
		
		ctr = document.getElementById('editpayment_term_id');
		var payment_term_id = ctr.value;
		
		ctr = document.getElementById('editcurency_id');
		var currency_id = ctr.value;
		
		ctr = document.getElementById('editamount');
		var amount = ctr.value;
		ctr = document.getElementById('editdescription');
		var description = ctr.value;
		addAccountInvoice('<?php echo $sale_id;?>', '<?php echo $partner_id;?>', category_id, status_id, payment_term_id, currency_id, amount, '<?php echo $receipt_no;?>', '<?php echo $receipt_date;?>', '<?php echo $origin_no;?>', '<?php echo $origin_date;?>', description, function(status, message){
			var ctr = document.getElementById('frmdialogClose');
			if(ctr != null)
			{
				ctr.click();
			}
		});
		
	}
	function removeInvoice(id)
	{
		var result = confirm("Want to delete?");
		if (!result) {
			return;
		}
		var _url = '<?php echo URL;?>api/sale_action/?ac=removeInvoice&id=' + id;
	
		loadPage('pnProducts', _url, function(status, message)
		{
			if(status== 0)
			{
				var ctr = document.getElementById('frmdialogClose');
				if(ctr != null)
				{
					ctr.click();
				}
			}
			
		}, true);
		
	}
</script>
<?php
}

?>

<!-- Tab Items End -->


		