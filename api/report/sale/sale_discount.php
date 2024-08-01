<?php

include( ABSPATH.'api/report/css.php');
?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Reports</title>
<meta name="viewport" content="width=device-width, initial-scale=1">		
<style>
html,body,h1,h2,h3,h4,h5,h6 {font-family: "Roboto", sans-serif;}
.sidebar {
  z-index: 3;
  width: 250px;
  top: 42px;
  bottom: 0;
  height: inherit;
}
</style>
</head><body>
<h1>Chi tiết hóa đơn</h1>
<?php
$msg = $appSession->getTier()->createMessage();
$sql = "SELECT d1.id, d1.receipt_no, d1.receipt_date, d1.create_date, d1.end_date, d2.name AS table_name, d1.table_number, d3.name AS customer_name FROM sale d1 LEFT OUTER JOIN product_table d2 ON(d1.table_id = d2.id) LEFT OUTER JOIN customer d3 ON(d1.customer_id = d3.id) WHERE d1.status =0 AND d1.company_id='".$company_id."' AND d1.receipt_date>='".$fdate."'";
$sql = $sql." AND d1.end_date<='".$tdate."'";

$msg->add("query", $sql);

$dt_sale = $appSession->getTier()->getTable($msg);
$sql = "SELECT d1.id, d1.sale_id, d3.name AS product_name, d4.name AS unit_name, d1.quantity, d1.unit_price, d1.discount_amount, d1.service_amount, d1.tax_amount, d1.description FROM sale_product d1 LEFT OUTER JOIN sale d2 ON(d1.sale_id = d2.id) LEFT OUTER JOIN product d3 ON(d1.product_id = d3.id) LEFT OUTER JOIN product_unit d4 ON(d1.unit_id = d4.id) WHERE d1.status =0 AND d2.status =0 AND d2.company_id='".$company_id."' AND d2.receipt_date>='".$fdate."'";
$sql = $sql." AND d2.receipt_date<='".$tdate."'";
$sql = $sql." ORDER BY d1.create_date ASC";
$msg->add("query", $sql);

$dt_product = $appSession->getTier()->getTable($msg);

$sql = "SELECT d1.id, d1.rel_id, d3.name, d1.amount, d1.description FROM account_payment_line d1 LEFT OUTER JOIN sale d2 ON(d1.rel_id = d2.id) LEFT OUTER JOIN account_payment d3 ON(d1.payment_id = d3.id)  WHERE d1.status =0 AND d2.status =0 AND d2.company_id='".$company_id."' AND d2.end_date>='".$fdate."'";
$sql = $sql." AND d2.end_date<='".$tdate."'";
$sql = $sql." ORDER BY d1.create_date ASC";
$msg->add("query", $sql);
$dt_payment = $appSession->getTier()->getTable($msg);



	
	
?>

<div class="container" >
	<div id="contentView" style="margin:4px; ">
	<div class="row responsive" style="padding-top:4px">
	<table class="table-all bordered">
		<thead>
		  <tr>
			
			<th style="width:150px; text-align:center">Tên sản phầm</th>
			<th style="width:80px; text-align:center" >Đơn vị tính</th>
			<th style="width:80px; text-align:center" >Số lượng</th>
			<th style="width:80px; text-align:center" >Đơn giá</th>
			<th style="width:80px; text-align:center" >Thành tiền</th>
			<th style="width:80px; text-align:center" >Giảm giá</th>
			<th style="width:80px; text-align:center" >Phí</th>
			<th style="width:80px; text-align:center" >Thuế</th>
			<th style="width:80px; text-align:center" >Tổng</th>
			<th style="width:200px; text-align:center">Ghi chú</th>
		  </tr>
		</thead>
		
		<tbody>
			<?php
			for($n =0; $n<$dt_sale->getRowCount(); $n++)
			{
				$sale_id = $dt_sale->getString($n, "id");
				$receipt_no = $dt_sale->getString($n, "receipt_no");
				$receipt_date = $dt_sale->getString($n, "receipt_date");
				$create_date = $dt_sale->getString($n, "create_date");
				$end_date = $dt_sale->getString($n, "end_date");
				$table_name = $dt_sale->getString($n, "table_name");
				$table_number = $dt_sale->getString($n, "table_number");
				$customer_name = $dt_sale->getString($n, "customer_name");
			?>
			<tr>
				<td colspan="10">
				Số HĐ: <?php echo $receipt_no;?>; Ngày in: <?php echo $receipt_date;?> -> <?php echo $end_date;?>
				<?php
				if($table_name != "")
				{
					echo "; Số bàn: ".$table_name;
					if($table_number != "0")
					{
						echo ".".$table_number;
					}
				}
				?>
				<?php
				if($customer_name != "")
				{
					echo "; Khách hàng: ".$customer_name;
					
				}
				?>
				
				</td>
			</tr>
			<?php
			$subTotal = 0;
			for($i =0; $i<$dt_product->getRowCount(); $i++)
			{
				if($sale_id == $dt_product->getString($i, "sale_id"))
				{
					$product_name = $dt_product->getString($i, "product_name");
					$unit_name = $dt_product->getString($i, "unit_name");
					$quantity = $dt_product->getFloat($i, "quantity");
					$unit_price = $dt_product->getFloat($i, "unit_price");
					$discount_amount = $dt_product->getFloat($i, "discount_amount");
					$service_amount = $dt_product->getFloat($i, "service_amount");
					$tax_amount = $dt_product->getFloat($i, "tax_amount");
					$amount = $quantity * $unit_price;
					$amount = $amount - $discount_amount;
					$amount = $amount + $service_amount;
					$amount = $amount + $tax_amount;
					$subTotal = $subTotal + $amount;
			?>
			<tr>
			<td ><?php echo $product_name;?></td>
			<td ><?php echo $unit_name;?></td>
			<td style="width:80px; text-align:right" ><?php echo $appSession->getFormats()->getDOUBLE()->format($quantity);?></td>
			<td style="width:80px; text-align:right"><?php echo $appSession->getFormats()->getDOUBLE()->format($unit_price);?></td>
			<td style="width:80px; text-align:right" ><?php echo $appSession->getFormats()->getDOUBLE()->format($amount);?></td>
			<td style="width:80px; text-align:right"><?php echo $appSession->getFormats()->getDOUBLE()->format($discount_amount);?></td>
			<td style="width:80px; text-align:right" ><?php echo $appSession->getFormats()->getDOUBLE()->format($service_amount);?></td>
			<td style="width:80px; text-align:right"><?php echo $appSession->getFormats()->getDOUBLE()->format($tax_amount);?></td>
			<td style="width:80px; text-align:right"><?php echo $appSession->getFormats()->getDOUBLE()->format($amount);?></td>
			<td style="width:200px">Ghi chú</td>
		  </tr>
			<?php
				}
			}
			?>
			<tr>
				<td colspan="8"><b>Tổng</b>:</td>
				<td style="width:80px; text-align:right" ><b><?php echo $appSession->getFormats()->getDOUBLE()->format($subTotal);?></b></td>
				<td ></td>
			</tr>
			<tr>
				<td colspan="10"><b>Thanh toán</b>:</td>
			</tr>
			<?php
			for($i =0; $i<$dt_payment->getRowCount(); $i++)
			{
				if($sale_id == $dt_payment->getString($i, "rel_id"))
				{
					$name = $dt_payment->getString($i, "name");
					$amount = $dt_payment->getFloat($i, "amount");
			?>
			<tr>
				<td colspan="8" style="text-align:right"><?php echo $name;?></td>
				<td style="width:80px; text-align:right" ><b><?php echo $appSession->getFormats()->getDOUBLE()->format($amount);?></b></td>
				<td ></td>
			</tr>
			<?php
				}
			}
			?>
			<?php
			}
			?>
		</tbody>
	</table>
	</div>
</div>

 </body>
 </html>