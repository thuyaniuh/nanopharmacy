<?php
validUser($appSession);



$msg = $appSession->getTier()->createMessage();


$sale_id = '';
if(isset($_REQUEST['sale_id']))
{
	$sale_id = $_REQUEST['sale_id'];
}
$sql = "SELECT d1.order_no, d2.commercial_name AS company_name, d2.address AS company_address, d2.phone AS company_phone, d1.order_date, d3.name AS customer_name, d3.phone AS customer_phone, d3.address AS customer_address, d3.email AS customer_email, d3.contact_name AS customer_contact_name, d3.email AS customer_email, d4.name AS delivery_name, d4.tel AS delivery_phone, d4.address AS delivery_address, d4.description AS delivery_description, d5.name AS delivery_ward_name, d6.name AS delivery_dist_name, d7.name AS delivery_city_name, d2.currency_id, d1.receipt_date, d4.start_date FROM sale_local d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id= d2.id) LEFT OUTER JOIN customer d3 ON(d1.customer_id = d3.id) LEFT OUTER JOIN sale_shipping d4 ON(d1.id = d4.sale_id) LEFT OUTER JOIN res_address d5 ON(d4.address_id = d5.id) LEFT OUTER JOIN res_address d6 ON(d5.parent_id = d6.id) LEFT OUTER JOIN res_address d7 ON(d6.parent_id = d7.id) WHERE d1.id='".$sale_id."'";


$msg->add("query", $sql);
$dt_sale = $appSession->getTier()->getTable($msg);

if($dt_sale->getRowCount()>0)
{
	$company_name = $dt_sale->getString(0, "company_name");
	$company_address = $dt_sale->getString(0, "company_address");
	$company_phone = $dt_sale->getString(0, "company_phone");
	$order_no = $dt_sale->getString(0, "order_no");
	$order_date = $dt_sale->getString(0, "order_date");
	if($order_date == "")
	{
		$order_date = $dt_sale->getString(0, "receipt_date");
	}
	$dd = "";
	$mm = "";
	$yy = "";
	if($order_date != "")
	{
		$dd =  date("d", strtotime($order_date));
		$mm =  date("m", strtotime($order_date));
		$yy =  date("Y", strtotime($order_date));
		
		$order_date = date("d/m/Y", strtotime($order_date));
		
		
	}
	$customer_contact_name = $dt_sale->getString(0, "customer_contact_name");
	$customer_name = $dt_sale->getString(0, "customer_name");
	$customer_phone = $dt_sale->getString(0, "customer_phone");
	$customer_address = $dt_sale->getString(0, "customer_address");
	$customer_contact_name = $dt_sale->getString(0, "customer_contact_name");
	$customer_email = $dt_sale->getString(0, "customer_email");
	$delivery_name = $dt_sale->getString(0, "delivery_name");
	$delivery_phone = $dt_sale->getString(0, "delivery_phone");
	$delivery_address = $dt_sale->getString(0, "delivery_address");
	$delivery_ward_name = $dt_sale->getString(0, "delivery_ward_name");
	$delivery_dist_name = $dt_sale->getString(0, "delivery_dist_name");
	$delivery_city_name = $dt_sale->getString(0, "delivery_city_name");
	$delivery_date = $dt_sale->getString(0, "start_date");
	if($delivery_date != "")
	{
		$delivery_date = date("d/m/Y H:i", strtotime($delivery_date));
	}
	
	if($delivery_ward_name != "")
	{
		if($delivery_address != "")
		{
			$delivery_address = $delivery_address.", ";
		}
		$delivery_address = $delivery_address.$delivery_ward_name;
	}
	if($delivery_dist_name != "")
	{
		if($delivery_address != "")
		{
			$delivery_address = $delivery_address.", ";
		}
		$delivery_address = $delivery_address.$delivery_dist_name;
	}
	if($delivery_city_name != "")
	{
		if($delivery_address != "")
		{
			$delivery_address = $delivery_address.", ";
		}
		$delivery_address = $delivery_address.$delivery_city_name;
	}
	$delivery_description = $dt_sale->getString(0, "delivery_description");
	$company_currency_id = $dt_sale->getString(0, "currency_id");

$sql = "SELECT d2.id AS sale_id, d1.id AS sale_product_id, d1.parent_id ,d2.order_no, d2.order_date, d7.name AS delivery_name, d3.code AS product_code, d3.name AS product_name";
$sql = $sql.", d4.name AS unit_name, d5.name AS attribute_name, d8.name AS type_name";
$sql = $sql.", d1.currency_id, d1.quantity, d1.unit_price, d1.product_id, d1.unit_id, d1.attribute_id, d1.type_id, d1.rel_id";
$sql = $sql." FROM sale_product_local d1";
$sql = $sql." LEFT OUTER JOIN sale_local d2 ON(d1.sale_id = d2.id)";
$sql = $sql." LEFT OUTER JOIN product d3 ON(d1.product_id = d3.id)";
$sql = $sql." LEFT OUTER JOIN product_price d6 ON(d1.rel_id = d6.id)";
$sql = $sql." LEFT OUTER JOIN product_unit d4 ON(d6.unit_id = d4.id)";


$sql = $sql." LEFT OUTER JOIN sale_shipping d7 ON(d2.id = d7.sale_id)";
$sql = $sql." LEFT OUTER JOIN product_type d8 ON(d6.type_id = d8.id)";
$sql = $sql." LEFT OUTER JOIN attribute d5 ON(d6.attribute_id = d5.id)";
$sql = $sql." WHERE d1.status =0 AND d2.id='".$sale_id."' AND d1.quantity>0";
$msg->add("query", $sql);

$dt_product = $appSession->getTier()->getTable($msg);		

$sql = "SELECT d1.rel_id, d1.category_id, d1.operator, SUM(d1.percent) AS percent, SUM(d1.value) AS value, d1.sequence";
 $sql = $sql." FROM account_service_line_local d1";
 $sql = $sql." LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id)";
 $sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 GROUP BY d1.rel_id, d1.category_id, d1.operator, d1.sequence ORDER BY d1.sequence ASC";
	
$msg->add("query", $sql);
					
$serviceList = $appSession->getTier()->getArray($msg);
$total = 0;
for($i =0; $i<$dt_product->getRowCount(); $i++)
{
	$quantity = $dt_product->getFloat($i, "quantity");
	$unit_price = $dt_product->getFloat($i, "unit_price");
	
	$total = $total + ($quantity * $unit_price);
}
$subTotal = $total;
$discount = 0;
$service = 0;
$tax = 0;
for($i =0; $i<count($serviceList); $i++)
{
	$a = ($total * floatval($serviceList[$i][3])) + floatval($serviceList[$i][4]);
	if($serviceList[$i][2] == "+")
	{
		
		$total =  $total + $a;
	}else if($serviceList[$i][2] == "-")
	{
		$total =  $total - $a;
	}else if($serviceList[$i][2] == "*")
	{
		$total =  $total * $a;
	}else if($serviceList[$i][3] == "/")
	{
		$total =  $total / $a;
	}
	if($serviceList[$i][1] == "DISCOUNT")
	{
		$discount += $a;
	}
	else if($serviceList[$i][1] == "SERVICE")
	{
		$service += $a;
	}
	else if($serviceList[$i][1] == "TAX")
	{
		$tax += $a;
	}
	
}
	
$total = $subTotal;


$sql = "SELECT d3.id, d1.currency_id, d3.name, SUM(d1.amount) AS amount";
$sql = $sql." FROM account_payment_line_local d1";
$sql = $sql." LEFT OUTER JOIN sale_local d2 ON(d1.line_id = d2.id)";
$sql = $sql." LEFT OUTER JOIN account_payment d3 ON(d1.payment_id = d3.id)";
$sql = $sql." WHERE d1.status =0 AND d2.id='".$sale_id."' GROUP BY d3.id, d1.currency_id, d3.name";
$msg->add("query", $sql);

$dt_payment = $appSession->getTier()->getTable($msg);

?>
<html>
<head>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" style="font-size:12px" >
<table width="100%" border="0">
<tr>
  <td align="center"><img width=71 height=72
  src="<?php echo URL;?>assets/images/vft.jpg"></td>
</tr>

<tr>
  <td align="center"><span lang=EN
  style='font-size:16.0pt;'>PHI&#7870;U GIAO H&Agrave;NG
       </span><br>
       <em>Ng&agrave;y <?php echo $dd;?> th&aacute;ng <?php echo $mm;?> n&#259;m <?php echo $yy;?><br>
       S&#7889; phi&#7871;u: <?php echo $order_no;?></em></td>
</tr>
<tr>
  <td align="left"> <b >KH&Aacute;CH H&Agrave;NG </b>
 </td>
</tr>
<tr>
  <td align="left"><?php echo $customer_contact_name;?></td>
</tr>
<tr>
  <td align="left"><?php echo $customer_name;?></td>
</tr>
<tr>
  <td><?php echo $customer_address;?></td>
</tr>
<tr>
  <td><?php echo $customer_phone;?></td>
</tr>
<tr>
  <td><?php echo $customer_email;?></td>
</tr>
<tr>
<td> <b>TH&Ocirc;NG GIAO H&Agrave;NG</b></td>
</tr>
<tr>
  <td><?php echo $delivery_name;?></td>
</tr>
<tr>
  <td><?php echo $delivery_date;?></td>
</tr>
<tr>
  <td><?php echo $delivery_address;?></td>
</tr>
<tr>
  <td><?php echo $delivery_phone;?></td>
</tr>
<tr>
  <td><?php echo $delivery_description;?></td>
</tr>

<tr>
  <td><table border=0 cellspacing=0 cellpadding=0 width="100%">
 

 <tr >
  <td  colspan=3 align="center" style="border:none;border-bottom:solid #999999 1.0pt;"  >
S&#7842;N PH&#7848;M </td>
	 <td width="30" align="center"  nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >&#272;VT</td>
     <td width="30" align="center" nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >&#272;G</td>
  <td width="30" align="center" nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >SL</td>
  <td width="30" align="center" nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >
 TT</td>
  </tr>
 <?php
 $subTotal = 0;
 for($i =0; $i<$dt_product->getRowCount(); $i++)
 {
 	$sale_product_id = $dt_product->getString($i, "sale_product_id");
	$parent_id = $dt_product->getString($i, "parent_id");
	if($parent_id != "")
	{
		continue;
	}
 	$product_code = $dt_product->getString($i, "product_code");
	$product_name = $dt_product->getString($i, "product_name");
	$currency_id = $dt_product->getString($i, "currency_id");
	$unit_name = $dt_product->getString($i, "unit_name");
	$attribute_name = $dt_product->getString($i, "attribute_name");
	$type_name = $dt_product->getString($i, "type_name");
	$quantity = $dt_product->getFloat($i, "quantity");
	$unit_price = $dt_product->getFloat($i, "unit_price");
	$amount = $quantity * $unit_price;
	if($amount!= 0)
	{
		$unit_price = ($amount - (($amount/$total) * $discount))/$quantity;
	}
	
	$amount = $quantity * $unit_price;
	$subTotal = $subTotal + $amount;
	
 ?>
 <tr >
  <td colspan=3 >
  <?php echo $product_name;?> (<?php echo $attribute_name;?> - <?php echo $type_name;?>)  </td>
  <td >
  <?php echo $unit_name;?>  </td>
  <td align="right" >
    <?php echo $appSession->getCurrency()->format($company_currency_id, $unit_price);?> </td>
  <td align="center"  >
 <?php echo $appSession->getFormats()->getDOUBLE()->format($quantity);?> </td>
  <td align="right"  >
   <?php echo $appSession->getCurrency()->format($company_currency_id, $amount);?>  </td>
  </tr>
  
	  <?php
	
	 for($ii =0; $ii<$dt_product->getRowCount(); $ii++)
	 {
		
		if($dt_product->getString($ii, "parent_id") != $sale_product_id)
		{
			continue;
		}
		$product_code = $dt_product->getString($ii, "product_code");
		$product_name = $dt_product->getString($ii, "product_name");
		$currency_id = $dt_product->getString($ii, "currency_id");
		$unit_name = $dt_product->getString($ii, "unit_name");
		$attribute_name = $dt_product->getString($ii, "attribute_name");
		$type_name = $dt_product->getString($ii, "type_name");
		$quantity = $dt_product->getFloat($ii, "quantity");
		$unit_price = $dt_product->getFloat($ii, "unit_price");
		$amount = $quantity * $unit_price;
		if($amount!= 0)
		{
			$unit_price = ($amount - (($amount/$total) * $discount))/$quantity;
		}
		
		$amount = $quantity * $unit_price;
		$subTotal = $subTotal + $amount;
		
	 ?>
	 <tr >
	  <td colspan=3 >
	  &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $product_name;?> (<?php echo $attribute_name;?> - <?php echo $type_name;?>)  </td>
	  <td >
	  <?php echo $unit_name;?>  </td>
	  <td align="right" >
		<?php echo $appSession->getCurrency()->format($company_currency_id, $unit_price);?> </td>
	  <td align="center"  >
	 <?php echo $appSession->getFormats()->getDOUBLE()->format($quantity);?> </td>
	  <td align="right"  >
	   <?php echo $appSession->getCurrency()->format($company_currency_id, $amount);?>  </td>
	  </tr>
	 
	 <?php
	 }
	 
	 ?>
 
 
 <?php
 }
 $total = $subTotal + $tax + $service;

 ?>

 <tr>
  <td colspan="6" style="border:none;border-top:solid #999999 1.0pt;"><strong><br>
  T&#7892;NG</strong></td>
  <td align="right"  style="border:none;border-top:solid #999999 1.0pt;">
    <strong><?php echo $appSession->getCurrency()->format($company_currency_id, $subTotal);?></strong> </td>
  </tr>
  <?php
  if($service != 0)
  {
?>
 <tr>
   <td colspan="6"><strong>PH&Iacute;</strong></td>
   <td align="right" >
    <strong><?php echo $appSession->getCurrency()->format($company_currency_id, $service);?></strong></td>
  </tr>
 <?php
  }
  ?>
   <?php
  if($tax != 0)
  {
?>
 <tr>
   <td colspan="6"><strong>THU&#7870;</strong></td>
   <td align="right" >
    <strong><?php echo $appSession->getCurrency()->format($company_currency_id, $tax);?></strong></td>
  </tr>
 <?php
  }
  ?>
 <tr>
   <td colspan="6" ><strong> T&#7892;NG C&#7896;NG</strong></td>
  <td align="right" >
    <strong><?php echo $appSession->getCurrency()->format($company_currency_id, $total);?></strong></td>
  </tr>
 <tr >
  <?php 
 for($j =0; $j<$dt_payment->getRowCount(); $j++)
 {
 	$payment_name= $dt_payment->getString($j, "name");
	$currency_id = $dt_payment->getString($j, "currency_id");
	$payment = $dt_payment->getFloat($j, "amount");
 ?>
  <tr>
   <td colspan="6" ><?php echo $payment_name;?></td>
  <td align="right" >
    <strong><?php echo $appSession->getCurrency()->format($company_currency_id, $payment);?></strong></td>
  </tr>
 <tr >
 <?php
 }
 ?>
  <td colspan="7">
 Vi&#7871;t b&#7857;ng ch&#7919;</strong>: <?php echo $appSession->getCurrency()->toword($company_currency_id, $total);?></i></td>
  </tr>
 
 
 
</table></td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
  <td><table width="100%" border="0">
    <tr>
      <td width="20%">&nbsp;</td>
      <td width="24%">&nbsp;</td>
      <td width="56%" align="center"><em>Ng&agrave;y <?php echo $dd;?> th&aacute;ng <?php echo $mm;?> n&#259;m <?php echo $yy;?></em></td>
    </tr>
    <tr>
      <td align="center" nowrap><strong>KCS</strong></td>
      <td align="center" nowrap><strong>Kh&aacute;ch h&agrave;ng </strong></td>
      <td align="center" nowrap><strong>Thu ng&acirc;n </strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><p>&nbsp;</p>
        <p>&nbsp;</p></td>
    </tr>
    <tr>
      <td>.</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table></td>
</tr>
</table>


</body>

</html>

<?php
}
?>
    
