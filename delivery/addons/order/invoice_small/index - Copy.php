<?php
validUser($appSession);



$msg = $appSession->getTier()->createMessage();


$sale_id = '';
if(isset($_REQUEST['sale_id']))
{
	$sale_id = $_REQUEST['sale_id'];
}
$sql = "SELECT d1.order_no, d2.commercial_name AS company_name, d2.address AS company_address, d2.phone AS company_phone, d1.order_date, d3.name AS customer_name, d3.phone AS customer_phone, d3.address AS customer_address, d3.email AS customer_email, d3.contact_name AS customer_contact_name, d3.email AS customer_email, d4.name AS delivery_name, d4.tel AS delivery_phone, d4.address AS delivery_address, d4.description AS delivery_description, d5.name AS delivery_ward_name, d6.name AS delivery_dist_name, d7.name AS delivery_city_name, d2.currency_id, d1.receipt_date FROM sale_local d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id= d2.id) LEFT OUTER JOIN customer d3 ON(d1.customer_id = d3.id) LEFT OUTER JOIN sale_shipping d4 ON(d1.id = d4.sale_id) LEFT OUTER JOIN res_address d5 ON(d4.address_id = d5.id) LEFT OUTER JOIN res_address d6 ON(d5.parent_id = d6.id) LEFT OUTER JOIN res_address d7 ON(d6.parent_id = d7.id) WHERE d1.id='".$sale_id."'";


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
		$mm =  date("M", strtotime($order_date));
		$yy =  date("y", strtotime($order_date));
		
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

$sql = "SELECT d2.id AS sale_id, d1.id AS sale_product_id ,d2.order_no, d2.order_date, d7.name AS delivery_name, d3.code AS product_code, d3.name AS product_name";
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
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
</head>

<body >
<table width="100%" border="0">
<tr>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
<tr>
  <td align="center"><img width=71 height=72
  src="<?php echo URL;?>assets/images/invoice.png" v:shapes="image2.png"></td>
</tr>
<tr>
  <td align="center"><?php echo $company_name."<br>";?><?php echo $company_address."<br>";?><?php echo $company_phone."<br>";?>V&igrave; m&#7897;t n&#7873;n n&ocirc;ng nghi&#7879;p c&oacute; tr&aacute;ch nhi&#7879;m</td>
</tr>
<tr>
  <td align="center"><br><span lang=EN
  style='font-size:18.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'>PHI&#7870;U GIAO H&Agrave;NG
       </span><br><?php echo $order_date;?><br><?php echo $order_no;?></td>
</tr>
<tr>
  <td align="left"> <p class=MsoNormal style='mso-pagination:none'><b style='mso-bidi-font-weight:
  normal'><span lang=EN style='font-size:9.0pt;line-height:115%;font-family:
  Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto;color:#1F3864'>KH&Aacute;CH H&Agrave;NG </span></b><span lang=EN style='font-size:10.0pt;line-height:115%;
  font-family:Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'>
  <o:p></o:p></span></p> </td>
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
<td> <p class=MsoNormal style='mso-pagination:none'><b style='mso-bidi-font-weight:
  normal'><span lang=EN style='font-size:9.0pt;line-height:115%;font-family:
  Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto;color:#1F3864'>TH&Ocirc;NG GIAO H&Agrave;NG </span></b><span lang=EN style='font-size:10.0pt;line-height:115%;
  font-family:Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'>
    <o:p></o:p></span></p></td>
</tr>
<tr>
  <td><?php echo $delivery_name;?></td>
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
	 <td width="30" align="center"  nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >
 &#272;&#416;N<br>
 V&#7882;</td>
     <td width="30" align="center" nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >&#272;&#416;N<br>
       GI&Aacute;</td>
  <td width="30" align="center" nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >S&#7888;<br>
    L&#431;&#7906;NG </td>
  <td width="30" align="center" nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >
 TH&Agrave;NH<br>
 TI&#7872;N</td>
  </tr>
 <?php
 $subTotal = 0;
 for($i =0; $i<$dt_product->getRowCount(); $i++)
 {
 	$product_code = $dt_product->getString($i, "product_code");
	$product_name = $dt_product->getString($i, "product_name");
	$currency_id = $dt_product->getString($i, "currency_id");
	$unit_name = $dt_product->getString($i, "unit_name");
	$attribute_name = $dt_product->getString($i, "attribute_name");
	$type_name = $dt_product->getString($i, "type_name");
	$quantity = $dt_product->getFloat($i, "quantity");
	$unit_price = $dt_product->getFloat($i, "unit_price");
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
 }
 $sql = "SELECT d1.percent, d1.value, d1.category_id, d1.operator, d2.name";
$sql = $sql." FROM account_service_line_local d1";
$sql = $sql." LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id)";
$sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 ORDER BY d1.sequence ASC";
$msg->add("query", $sql);
					
$serviceList = $appSession->getTier()->getArray($msg);
$amount = $subTotal;
for($i =0; $i<count($serviceList); $i++)
{
	$a = ($subTotal * floatval($serviceList[$i][0])) + floatval($serviceList[$i][1]);
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
$discountAmount = $subTotal - $amount;
$total = $subTotal - $discountAmount;
 ?>

 <tr>
  <td colspan="6"><strong><br>
  T&#7892;NG</strong></td>
  <td align="right">
    <strong><?php echo $appSession->getCurrency()->format($company_currency_id, $subTotal);?></strong> </td>
  </tr>
 <tr>
   <td colspan="6"><strong> GI&#7842;M TR&#7914;</strong></td>
  <td align="right">
    <strong><?php echo $appSession->getCurrency()->format($company_currency_id, $discountAmount);?></strong></td>
  </tr>
 
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
</table>


</body>

</html>

<?php
}
?>
    
