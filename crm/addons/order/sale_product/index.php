<?php
validUser($appSession);
require_once(ABSPATH.'api/Sale.php' );

$msg = $appSession->getTier()->createMessage();

$sale_id = '';
if(isset($_REQUEST['sale_id']))
{
	$sale_id = $_REQUEST['sale_id'];
}
$msg = $appSession->getTier()->createMessage();

$sql = "SELECT customer_id FROM sale_local WHERE id='".$sale_id."'";

$msg->add("query", $sql);
$dt = $appSession->getTier()->getTable($msg);
$customer_id = "";
$status_id = "";
if($dt->getRowCount()>0)
{
	$customer_id = $dt->getString(0, "customer_id");
	
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
$sql = $sql." WHERE d1.status =0 AND d2.id='".$sale_id."' AND d3.company_id ='".$appSession->getConfig()->getProperty("company_id")."'";



$msg->add("query", $sql);
$dt = $appSession->getTier()->getTable($msg);


$sale = new Sale($appSession);
//$sale->checkSaleService($sale_id, $customer_id);

$sql = "SELECT id, name, forecolor, backcolor FROM res_status WHERE status =0 AND table_id='sale_local' ORDER BY sequence ASC";
$msg = $appSession->getTier()->createMessage();
$msg->add("query", $sql);
$status = $appSession->getTier()->getArray($msg);

$sql = "SELECT status_id FROM res_status_line WHERE status =0 AND rel_id= '".$sale_id."' ORDER BY create_date DESC LIMIT 1";
$msg->add("query", $sql);
$status_id = $appSession->getTier()->getValue($msg);


?>
<!-- Tab Items Start -->
<div id="item_list" class="item_list active">
	<div class="order_header">
		<div class="row align-items-center">
			<div class="col-4">
				<h2>Item</h2>
			</div>
			<div class="col-2 text-center">
				<h2>Price</h2>
			</div>
			<div class="col-3 text-center">
				<h2>Qnt.</h2>
			</div>
			<div class="col-3 text-right">
				<h2>Total($)</h2>
			</div>
		</div>
	</div>

	<!-- Food List Start -->
	<div >
	<ul>
		<?php
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
		
			$product_id = $dt->getString($i, "product_id");
			$unit_id = $dt->getString($i, "unit_id");
			$attribute_id = $dt->getString($i, "attribute_id");
			$type_id = $dt->getString($i, "type_id");
			$currency_id = $dt->getString($i, "currency_id");
			$rel_id = $dt->getString($i, "rel_id");
			
		?>
		<li>
			<div class="row">
				<div class="col-4">
					<h2><?php echo $product_code;?>. <?php echo $product_name;?></h2>
				</div>
				<div class="col-2 text-center">
					<a href="javascript:priceList('<?php echo $sale_id;?>', '<?php echo $sale_product_id;?>', '<?php echo $product_id;?>')"><?php echo $appSession->getCurrency()->format($currency_id, $unit_price); ?></a>
				</div>
				<div class="col-3 text-center">
					<h3 class="d-flex align-items-center">
						<a href="javascript:quantityChanged('<?php echo $product_id;?>', '<?php echo $unit_id;?>', '<?php echo $attribute_id;?>', '<?php echo $type_id;?>', '<?php echo $rel_id;?>', <?php echo ($quantity-1);?>,'<?php echo $currency_id;?>',  <?php echo $unit_price;?>)"><i class="zmdi zmdi-minus"></i></a>
						<a href="javascript:updateQuantity('<?php echo $product_id;?>', '<?php echo $unit_id;?>', '<?php echo $attribute_id;?>', '<?php echo $type_id;?>', '<?php echo $rel_id;?>', <?php echo ($quantity);?>,'<?php echo $currency_id;?>',  <?php echo $unit_price;?>)"><strong>&nbsp;<?php echo $quantity;?>&nbsp;</strong></a>
						<a href="javascript:quantityChanged('<?php echo $product_id;?>', '<?php echo $unit_id;?>', '<?php echo $attribute_id;?>', '<?php echo $type_id;?>', '<?php echo $rel_id;?>', <?php echo ($quantity + 1);?>,'<?php echo $currency_id;?>',  <?php echo $unit_price;?>)"><i class="zmdi zmdi-plus"></i></a>
					</h3>
				</div>
				<div class="col-3 text-right">
					<h4><?php echo $appSession->getCurrency()->format($currency_id, $amount);?></h4>
				</div>
			</div>
		</li>
		<?php
		}
		?>
		<li>
			<div class="row" style="height:40px">
			<h4><br><br></h4>
			</div>
		</li>
		
	</ul>
	</br></br>
	</br></br>
	
	</div>
	<!-- Food List End -->
	<?php
	$sql = "SELECT d1.rel_id, d1.category_id, d1.operator, SUM(d1.percent) AS percent, SUM(d1.value) AS value, d1.sequence";
	$sql = $sql." FROM account_service_line_local d1";
	$sql = $sql." LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id)";
	$sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 GROUP BY d1.rel_id, d1.category_id, d1.operator, d1.sequence ORDER BY d1.sequence ASC";
	$msg->add("query", $sql);
	
	
						
	$serviceList = $appSession->getTier()->getArray($msg);
	$subTotal = $total;
	$discount = 0;
	$service = 0;
	for($i =0; $i<count($serviceList); $i++)
	{
		$a = ($total * floatval($serviceList[$i][3])) + floatval($serviceList[$i][4]);
		if($serviceList[$i][2] == "+")
		{
			$service =  $service + $a;
			$total =  $total + $a;
		}else if($serviceList[$i][2] == "-")
		{
			$discount =  $discount -  $a;
			$total =  $total - $a;
		}else if($serviceList[$i][2] == "*")
		{
			$service =  $service *  $a;
			$total =  $total * $a;
		}else if($serviceList[$i][3] == "/")
		{
			$discount =  $discount /  $a;
			$total =  $total / $a;
		}
		
	}

	?>
	<div class="order_footer">
		<div class="amount_details">
			<h2 class="d-flex text-right">
				<span class="text">Sub total </span>
				<span class="mr-0 ml-auto"><?php echo $appSession->getCurrency()->format($currency_id, $subTotal);?></span>
			</h2>
			
		</div>
		<?php
		if($discount != 0)
		{
		?>
		<div class="amount_details">
			<h2 class="d-flex text-right">
				<span class="text">Discount </span>
				<span class="mr-0 ml-auto"><?php echo $appSession->getCurrency()->format($currency_id, $discount);?></span>
			</h2>
			
		</div>
		<?php
		}
		?>
		<?php
		if($service !=0)
		{
		?>
		<div class="amount_details">
			<h2 class="d-flex text-right">
				<span class="text">Service: </span>
				<span class="mr-0 ml-auto"><?php echo $appSession->getCurrency()->format($currency_id, $service);?></span>
			</h2>
			
		</div>
		<?php
		}
		?>
		<div class="amount_payble">
			<h2 class="d-flex text-right">
				<span class="text">Amount to Pay</span>
				<span class="mr-0 ml-auto"><?php echo $appSession->getCurrency()->format($currency_id, $subTotal + $discount + $service);?></span>
			</h2>
		</div>
		
		<div class="row">
			<div class="col-12">
				<?php
					for($i = 0; $i<count($status); $i++)
					{
						
					?>
					<td nowrap="nowrap" style="background-color:<?php echo $status[$i][3];?>; color:<?php echo $status[$i][2];?>"><input type="radio" <?php if($status_id  == $status[$i][0]){ echo " checked "; } ?> onchange="onStatus('<?php echo $sale_id;?>', '<?php echo $status[$i][0];?>')"; name="status[]" value="<?php echo $status[$i][0];?>"></td>
					<td nowrap="nowrap" style="background-color:<?php echo $status[$i][3];?>; color:<?php echo $status[$i][2];?>"><?php echo $status[$i][1];?>&nbsp;</td>
					<td>&nbsp;</td>
					<?php
					}
					?>
			</div>
		</div>
			<div class="row">
				<div class="col-4">
					<button type="button" class="btn"><a href="<?php echo URL;?>addons/order/invoice/?sale_id=<?php echo $sale_id;?>" target="_blank">Bill</a></button>
				</div>
				
				<div class="col-4">
					<button type="button" class="btn" onclick="createPayment('<?php echo $sale_id;?>')">Payment</button>
				</div>
				<div class="col-4">
				<button type="button" class="btn" ><a href="javascript:closeBill('<?php echo $sale_id;?>', function(message){ doneCloseBill(message); })">Close Sale</a></button>
				</div>
				
				
		</div>
	</div>
</div>
<!-- Tab Items End -->


		