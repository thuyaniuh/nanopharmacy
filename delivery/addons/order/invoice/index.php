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
	$order_date = $dt_sale->getString(0, "start_date");
	if($order_date == "")
	{
		$order_date = $dt_sale->getString(0, "order_date");
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
	$delivery_date = $dt_sale->getString(0, "order_date");
	if($delivery_date != "")
	{
		$delivery_date = date("d/m/Y", strtotime($delivery_date));
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

$sql = "SELECT d2.id AS sale_id, d1.id AS sale_product_id , d1.parent_id,d2.order_no, d2.order_date, d7.name AS delivery_name, d3.code AS product_code, d3.name AS product_name";
$sql = $sql.", d4.name AS unit_name, d5.name AS attribute_name, d8.name AS type_name";
$sql = $sql.", d1.currency_id, d1.unit_price, d1.product_id, d1.unit_id, d1.attribute_id, d1.type_id, d1.rel_id, SUM(d1.quantity) AS quantity";
$sql = $sql." FROM sale_product_local d1";
$sql = $sql." LEFT OUTER JOIN sale_local d2 ON(d1.sale_id = d2.id)";
$sql = $sql." LEFT OUTER JOIN product d3 ON(d1.product_id = d3.id)";
$sql = $sql." LEFT OUTER JOIN product_price d6 ON(d1.rel_id = d6.id)";
$sql = $sql." LEFT OUTER JOIN product_unit d4 ON(d6.unit_id = d4.id)";


$sql = $sql." LEFT OUTER JOIN sale_shipping d7 ON(d2.id = d7.sale_id)";
$sql = $sql." LEFT OUTER JOIN product_type d8 ON(d6.type_id = d8.id)";
$sql = $sql." LEFT OUTER JOIN attribute d5 ON(d6.attribute_id = d5.id)";
$sql = $sql." WHERE d1.status =0 AND d2.id='".$sale_id."' AND d1.quantity>0 AND d3.company_id='".$appSession->getConfig()->getProperty("company_id")."'";
$sql = $sql." GROUP BY  d2.id, d1.id, d1.parent_id,d2.order_no, d2.order_date, d7.name, d3.code, d3.name, d4.name, d5.name, d8.name,d1.currency_id, d1.unit_price, d1.product_id, d1.unit_id, d1.attribute_id, d1.type_id, d1.rel_id";
$msg->add("query", $sql);
$dt_product = $appSession->getTier()->getTable($msg);				  


$sql = "SELECT d3.id, d1.currency_id, d3.name, SUM(d1.amount) AS amount";
$sql = $sql." FROM account_payment_line_local d1";
$sql = $sql." LEFT OUTER JOIN sale_local d2 ON(d1.line_id = d2.id)";
$sql = $sql." LEFT OUTER JOIN account_payment d3 ON(d1.payment_id = d3.id)";
$sql = $sql." WHERE d1.status =0 AND d2.id='".$sale_id."' GROUP BY d3.id, d1.currency_id, d3.name";
$msg->add("query", $sql);

$dt_payment = $appSession->getTier()->getTable($msg);

$sql = "SELECT d1.rel_id, d1.category_id, d1.operator, SUM(d1.percent) AS percent, SUM(d1.value) AS value, d1.sequence";
 $sql = $sql." FROM account_service_line_local d1";
 $sql = $sql." LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id)";
 $sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 GROUP BY d1.rel_id, d1.category_id, d1.operator, d1.sequence ORDER BY d1.sequence ASC";
	
$msg->add("query", $sql);
					
$serviceList = $appSession->getTier()->getArray($msg);


$sql = "SELECT DISTINCT d3.name";
$sql = $sql." FROM stock d1";
$sql = $sql." LEFT OUTER JOIN sale_local d2 ON(d1.rel_id = d2.id)";
$sql = $sql." LEFT OUTER JOIN res_company d3 ON(d1.company_id = d3.id)";
$sql = $sql." WHERE d1.status =0 AND d2.id='".$sale_id."'";
$msg->add("query", $sql);

$stock = $appSession->getTier()->getTable($msg);

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

$stock_name = "";
for($i =0; $i<$stock->getRowCount(); $i++)
{
	if($stock_name != "")
	{
		$stock_name = $stock_name.", ";
	}
	$stock_name = $stock_name.$stock->getString($i, "name");
}

$sql = "SELECT d1.name, d1.address, d1.phone, d1.vat, p.document_id FROM res_company d1 LEFT OUTER JOIN poster p ON(d1.id = p.rel_id AND p.status =0 AND p.publish = 1) WHERE d1.id ='".$appSession->getConfig()->getProperty("company_id")."'";
$msg->add("query", $sql);
$dt_company = $appSession->getTier()->getTable($msg);

$company_document_id = "";
if($dt_company->getRowCount()>0)
{
	$company_name = $dt_company->getString(0, "name");
	$company_address = $dt_company->getString(0, "address");
	$company_document_id = $dt_company->getString(0, "document_id");
}

?>
<html>

<head>
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
<meta http-equiv=Content-Type content="text/html; charset=windows-1252"><style type="text/css">
<!--
.style1 {
	font-family: Roboto;
	font-size: 9.0pt;
	color: #333F4F;
}
.style3 {
	font-size: 8.0pt;
	font-weight: bold;
	color: #333F4F;
}
-->
</style></head>

<body >
<table class=a border=1 cellspacing=0 cellpadding=0 width="100%" style='margin-left:
 2.0pt;border-collapse:collapse;mso-table-layout-alt:fixed;border:none;
 mso-yfti-tbllook:1536;mso-padding-alt:5.0pt 5.0pt 5.0pt 5.0pt;mso-border-insideh:
 cell-none;mso-border-insidev:cell-none'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:14.0pt'>
  <td width=243 style='width:51.75pt;border:none;background:#FF5722;padding:
  0in 2.0pt 0in 2.0pt;height:14.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=246 style='width:128.25pt;border:none;background:#FF5722;
  padding:0in 2.0pt 0in 2.0pt;height:14.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=53 style='width:30.25pt;border:none;background:#FF5722;padding:
  0in 2.0pt 0in 2.0pt;height:14.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=182 style='width:62.25pt;border:none;background:#FF5722;padding:
  0in 2.0pt 0in 2.0pt;height:14.0pt'>&nbsp;</td>
  <td width=104 style='width:62.25pt;border:none;background:#FF5722;padding:
  0in 2.0pt 0in 2.0pt;height:14.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=147 style='width:89.25pt;border:none;background:#FF5722;padding:
  0in 2.0pt 0in 2.0pt;height:14.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=147 style='width:73.55pt;border:none;background:#FF5722;padding:
  0in 2.0pt 0in 2.0pt;height:14.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=150 style='width:16.0pt;border:none;background:#FF5722;padding:0in 2.0pt 0in 2.0pt;
  height:14.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
 </tr>
 <tr style='mso-yfti-irow:1;height:21.0pt'>
  <td width=243 rowspan=4 style='width:51.75pt;border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:21.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>
  <p class=MsoNormal style='mso-pagination:none'><span style='font-size:10.0pt;
  line-height:115%;font-family:Roboto;mso-fareast-font-family:Roboto;
  mso-bidi-font-family:Roboto;mso-ansi-language:EN-US;mso-no-proof:yes'><img width=71 height=72
  src="<?php echo URL;?>document/?id=<?php echo $company_document_id;?>" v:shapes="image2.png"></span><span
  lang=EN style='font-size:10.0pt;line-height:115%;font-family:Roboto;
  mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'><o:p></o:p></span></p>  </td>
  <td colspan="3" style='width:128.25pt;border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:21.0pt'></td>
  <td width=104 style='width:62.25pt;border:none;background:#F3F3F3;padding:
  0in 2.0pt 0in 2.0pt;height:21.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=147 style='width:89.25pt;border:none;background:#F3F3F3;padding:
  0in 2.0pt 0in 2.0pt;height:21.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=147 style='width:73.55pt;border:none;background:#F3F3F3;padding:
  0in 2.0pt 0in 2.0pt;height:21.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=150 style='width:16.0pt;border:none;background:#F3F3F3;padding:0in 2.0pt 0in 2.0pt;
  height:21.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
 </tr>
 <tr style='mso-yfti-irow:2;height:15.0pt'>
  <td colspan="4" valign=top style='width:128.25pt;border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-family:Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:

  Roboto;color:#333F4F'><?php echo $company_name;?></span></p>  </td>
  <td width=147 valign=bottom style='width:89.25pt;border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td colspan="2" style='border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>
    <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:18.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'>PHI&#7870;U GIAO H&Agrave;NG
       </span></p>    </td>
  </tr>
 <tr style='mso-yfti-irow:3;height:.25in'>
  <td colspan="4" valign=top style='width:128.25pt;border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:8.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto;color:#333F4F'><?php echo $company_address;?></span><span
  lang=EN style='font-size:10.0pt;line-height:115%;font-family:Roboto;
  mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'><o:p></o:p></span></p>  </td>
  <td width=147 valign=bottom style='width:89.25pt;border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td style='width:73.55pt;border:none;border-bottom:solid #BFBFBF 1.0pt;
  mso-border-bottom-alt:solid #BFBFBF .75pt;background:#F3F3F3;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'><p class=MsoNormal align=center style='text-align:center;mso-pagination:none'><b
  style='mso-bidi-font-weight:normal'><span lang=EN style='font-size:9.0pt;
  line-height:115%;font-family:Roboto;mso-fareast-font-family:Roboto;
  mso-bidi-font-family:Roboto;color:#1F3864'><?php echo $order_date;?></span></b><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p></o:p></span></p> </td>
  <td style='border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>&nbsp;</td>
 </tr>
 <tr style='mso-yfti-irow:4;height:.25in'>
  <td height="24" colspan="4" valign=top style='width:128.25pt;border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:8.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto;color:#333F4F'><?php echo $company_phone;?></span></p>  </td>
  <td width=147 valign=bottom style='width:89.25pt;border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td style='width:73.55pt;border:none;border-bottom:solid #BFBFBF 1.0pt;
  mso-border-bottom-alt:solid #BFBFBF .75pt;background:#F3F3F3;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'><p class=MsoNormal align=center style='text-align:center;mso-pagination:none'><b
  style='mso-bidi-font-weight:normal'><span lang=EN style='font-size:9.0pt;
  line-height:115%;font-family:Roboto;mso-fareast-font-family:Roboto;
  mso-bidi-font-family:Roboto;color:#1F3864'><?php echo $order_no;?></span></b><span
  lang=EN style='font-size:10.0pt;line-height:115%;font-family:Roboto;
  mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'><o:p></o:p></span></p></td>
  <td style='border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>&nbsp;</td>
 </tr>
 <tr style='mso-yfti-irow:5;height:.25in'>
  <td width=243 height="31" style='width:51.75pt;border:none;background:#F3F3F3;padding:
  0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td colspan="4" style='border:none;background:#F3F3F3;
  height:.25in'>
 <p class=MsoNormal  style='mso-pagination:none'>&nbsp;</p>  </td>
  <td width=147 style='width:89.25pt;border:none;background:#F3F3F3;padding:
  0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=147 style='width:73.55pt;border:none;background:#F3F3F3;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal align=center style='text-align:center;mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p></o:p></span></p>  </td>
  <td width=150 style='width:16.0pt;border:none;background:#F3F3F3;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
 </tr>
 
 <tr style='mso-yfti-irow:8;height:.25in'>
  <td width=243 style='width:51.75pt;border:none;background:#F3F3F3;padding:
  0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=246 style='width:128.25pt;border:none;background:#F3F3F3;
  padding:0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=53 style='width:30.25pt;border:none;background:#F3F3F3;padding:
  0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=182 style='width:62.25pt;border:none;background:#F3F3F3;padding:
  0in 2.0pt 0in 2.0pt;height:.25in'>&nbsp;</td>
  <td width=104 style='width:62.25pt;border:none;background:#F3F3F3;padding:
  0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=147 style='width:89.25pt;border:none;background:#F3F3F3;padding:
  0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=147 style='width:73.55pt;border:none;background:#F3F3F3;padding:
  0in 2.0pt 0in 2.0pt;height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=150 style='width:16.0pt;border:none;background:#F3F3F3;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
 </tr>
 <tr style='mso-yfti-irow:9;height:.25in'>
  <td width=243 style='width:51.75pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=246 style='width:128.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=53 style='width:30.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=182 style='width:62.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>&nbsp;</td>
  <td width=104 style='width:62.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td colspan=2 style='width:162.8pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=150 style='width:16.0pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
 </tr>
 
 
 <tr style='mso-yfti-irow:16;height:.25in'>
  <td colspan=8 style='border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
 <table  cellspacing=0 cellpadding=0 width="100%" style='border:none;'>
 
 
 
 
 <tr style='mso-yfti-irow:11;height:15.0pt'>
  <td  style='width:2.5in;border:none;border-bottom:solid #BFBFBF 1.0pt;
  mso-border-bottom-alt:solid #BFBFBF .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><b style='mso-bidi-font-weight:
  normal'><span lang=EN style='font-size:9.0pt;line-height:115%;font-family:
  Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto;color:#1F3864'>KH&Aacute;CH H&Agrave;NG </span></b><span lang=EN style='font-size:10.0pt;line-height:115%;
  font-family:Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'>
  <o:p></o:p></span></p>  </td>
  <td width=369 style='width:30.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width="330" style='border:none;border-bottom:solid #BFBFBF 1.0pt;
  mso-border-bottom-alt:solid #BFBFBF .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'>
    <p class=MsoNormal style='mso-pagination:none'><b style='mso-bidi-font-weight:
  normal'><span lang=EN style='font-size:9.0pt;line-height:115%;font-family:
  Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto;color:#1F3864'>TH&Ocirc;NG GIAO H&Agrave;NG </span></b><span lang=EN style='font-size:10.0pt;line-height:115%;
  font-family:Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'>
    <o:p></o:p></span></p></td>
  </tr>
 <tr style='mso-yfti-irow:12;height:.25in'>
  <td  style='width:2.5in;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><?php echo $customer_contact_name;?></span></p>  </td>
  <td  style='border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td style='width:225.05pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><?php echo $delivery_name;?></span></p>  </td>
  </tr>
 <tr style='mso-yfti-irow:13;height:.25in'>
  <td style='width:2.5in;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><?php echo $customer_name;?></span></p>  </td>
  <td width=369 style='width:30.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td style='width:225.05pt;border:none;
  mso-border-right-alt:solid black .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><?php echo $delivery_date;?>&nbsp;</p>  </td>
  </tr>
 <tr style='mso-yfti-irow:14;height:.25in'>
  <td height="30"  style='width:2.5in;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><?php echo $customer_address;?></span></p>  </td>
  <td width=369 style='width:30.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td style='width:225.05pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span class="MsoNormal" style="mso-pagination:none"><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><?php echo $delivery_address;?></span></span></p>  </td>
  </tr>
 <tr style='mso-yfti-irow:15;height:.25in'>
  <td style='width:2.5in;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><?php echo $customer_phone;?></span></p>  </td>
  <td  style='border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td style='width:225.05pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span class="MsoNormal" style="mso-pagination:none"><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><?php echo $delivery_phone;?></span></span></p>  </td>
  </tr>
 <tr style='mso-yfti-irow:16;height:.25in'>
  <td  style='width:2.5in;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><?php echo $customer_email;?></span></p>  </td>
  <td width=369 style='width:30.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td style='border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
    <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span><span class="MsoNormal" style="mso-pagination:none"><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><?php echo $delivery_description;?></span></span></p></td>
  </tr>
</table></td>
  </tr>
 <tr style='mso-yfti-irow:17;height:.25in'>
  <td width=243 style='width:51.75pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=246 style='width:128.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=53 style='width:30.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td colspan=4 style='width:225.05pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=150 style='width:16.0pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
 </tr>
 
 <tr style='mso-yfti-irow:19;height:.25in'>
  <td colspan=3 nowrap style='border:none;border-bottom:solid #B7B7B7 1.0pt; border-top:solid #B7B7B7 1.0pt;border-left:solid #B7B7B7 1.0pt;
 ;padding:0in 2.0pt 0in 2.0pt;height:.25in'>
  <p align=center class=MsoNormal style='text-align:center;mso-pagination:none'><strong><span  style="font-family: Roboto">S&#7842;N PH&#7848;M </span></strong></p>  </td>
	 <td width=182 nowrap style='width:150;border:none;border-bottom:solid #B7B7B7 1.0pt; border-top:solid #B7B7B7 1.0pt; padding:0in 2.0pt 0in 2.0pt;
  height:.25in'><p align=center class=MsoNormal style='text-align:center;mso-pagination:none'><strong><span  style="font-family: Roboto"> NH&Atilde;N H&Agrave;NG</span><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'>
     <o:p></o:p>
    </span></strong></p> </td>
	 <td width=104 nowrap style='width:62.25pt;border:none;border-bottom:solid #B7B7B7 1.0pt;border-top:solid #B7B7B7 1.0pt; padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
  <p align=center class=MsoNormal style='text-align:center;mso-pagination:none'><strong><span  style="font-family: Roboto"> &#272;&#416;N V&#7882;</span><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'>

  </span></strong></p>  </td>
  <td width=147 nowrap style='width:120;border:none;border-bottom:solid #B7B7B7 1.0pt;border-top:solid #B7B7B7 1.0pt;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>  <p align=center class=MsoNormal style='text-align:center;mso-pagination:none'><strong><span  style="font-family: Roboto">&#272;&#416;N GI&Aacute;</span></strong></p>   </td>
  <td width=147 nowrap style='width:120;border:none;border-bottom:solid #B7B7B7 1.0pt;border-top:solid #B7B7B7 1.0pt;
  mso-border-top-alt:solid #B7B7B7 1.0pt;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'> <p align=center class=MsoNormal style='text-align:center;mso-pagination:none'><strong><span  style="font-family: Roboto">S&#7888; L&#431;&#7906;NG</span></strong></p>   </td>
  <td width=150 nowrap style='width:120;border-left:none;
  border-bottom:solid #B7B7B7 1.0pt;border-top:solid #B7B7B7 1.0pt;border-right:solid #B7B7B7 1.0pt;
  mso-border-top-alt:solid #B7B7B7 1.0pt;padding:0in 2.0pt 0in 2.0pt;
  height:.25in'>
   <p align=center class=MsoNormal style='text-align:center;mso-pagination:none'><strong><span  style="font-family: Roboto">TH&Agrave;NH TI&#7872;N</span></strong></p>  </td>
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
	if($amount>0){
		$unit_price = ($amount - (($amount/$total) * $discount))/$quantity;
	}
	
	$amount = $quantity * $unit_price;
	$subTotal = $subTotal + $amount;
 ?>
 <tr style='mso-yfti-irow:20;height:.25in'>
  <td colspan=3 style='width:210.25pt;border:none;border-right:solid #999999 1.0pt;border-left:solid #999999 1.0pt;
  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:.25in; border-bottom:solid #999999 1.0pt;'>
  <?php echo $product_name;?>  </td>
  <td  align="left" style='border:none;border-right:solid #999999 1.0pt;
  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:.25in ; text-align:left; vertical-align:middle; border-bottom:solid #999999 1.0pt'><?php echo $attribute_name;?> - <?php echo $type_name;?> </td>
  <td style='border:none;border-right:solid #999999 1.0pt;
  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:.25in ; text-align:left; vertical-align:middle; border-bottom:solid #999999 1.0pt;'>
  <?php echo $unit_name;?>  </td>
  <td  style='border:none;border-right:solid #999999 1.0pt;
  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:.25in ; text-align:right; vertical-align:middle; border-bottom:solid #999999 1.0pt;'>
    <?php echo $appSession->getCurrency()->format($company_currency_id, $unit_price);?> </td>
  <td  style='border:none;border-right:solid #999999 1.0pt;
  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:.25in ; text-align:center; vertical-align:middle; border-bottom:solid #999999 1.0pt;'>
 <?php echo $appSession->getFormats()->getDOUBLE()->format($quantity);?> </td>
  <td  style='border:none;border-right:solid #999999 1.0pt;
  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:.25in; text-align:right; vertical-align:middle; border-bottom:solid #999999 1.0pt;'>
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
		if($amount>0){
			$unit_price = ($amount - (($amount/$total) * $discount))/$quantity;
		}
		
		$amount = $quantity * $unit_price;
		$subTotal = $subTotal + $amount;
	 ?>
	 <tr style='mso-yfti-irow:20;height:.25in'>
	  <td colspan=3 style='width:210.25pt;border:none;border-right:solid #999999 1.0pt;border-left:solid #999999 1.0pt;
	  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
	  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
	  height:.25in; border-bottom:solid #999999 1.0pt;'>
	  &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $product_name;?>  </td>
	  <td  align="left" style='border:none;border-right:solid #999999 1.0pt;
	  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
	  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
	  height:.25in ; text-align:left; vertical-align:middle; border-bottom:solid #999999 1.0pt'><?php echo $attribute_name;?> - <?php echo $type_name;?> </td>
	  <td style='border:none;border-right:solid #999999 1.0pt;
	  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
	  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
	  height:.25in ; text-align:left; vertical-align:middle; border-bottom:solid #999999 1.0pt;'>
	  <?php echo $unit_name;?>  </td>
	  <td  style='border:none;border-right:solid #999999 1.0pt;
	  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
	  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
	  height:.25in ; text-align:right; vertical-align:middle; border-bottom:solid #999999 1.0pt;'>
		<?php echo $appSession->getCurrency()->format($company_currency_id, $unit_price);?> </td>
	  <td  style='border:none;border-right:solid #999999 1.0pt;
	  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
	  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
	  height:.25in ; text-align:center; vertical-align:middle; border-bottom:solid #999999 1.0pt;'>
	 <?php echo $appSession->getFormats()->getDOUBLE()->format($quantity);?> </td>
	  <td  style='border:none;border-right:solid #999999 1.0pt;
	  mso-border-top-alt:solid #B7B7B7 1.0pt;mso-border-top-alt:solid #B7B7B7 1.0pt;
	  mso-border-right-alt:solid #999999 .75pt;padding:0in 2.0pt 0in 2.0pt;
	  height:.25in; text-align:right; vertical-align:middle; border-bottom:solid #999999 1.0pt;'>
	   <?php echo $appSession->getCurrency()->format($company_currency_id, $amount);?>  </td>
  </tr>
	 
	 <?php
	 }
	
	 ?>
 <?php
 }
  $total = $subTotal + $service + $tax;

 ?>

 <tr style='mso-yfti-irow:29;height:19.0pt'>
  <td colspan="6" rowspan="<?php echo 3 + $dt_payment->getRowCount();?>" valign="top" style='width:51.75pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:19.0pt;'><br>
    <em>Ghi ch&uacute;: </em>    </td>
  <td width=147 style='width:89.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:19.0pt'>
  <p class=MsoNormal align=right style='text-align:right;mso-pagination:none'><span style="font-family: Roboto"><b
  style='mso-bidi-font-weight:normal'><span lang=EN style='font-size:8.0pt; line-height:115%;mso-fareast-font-family:Roboto; mso-bidi-font-family:Roboto;color:#333F4F'>T&#7892;NG</span></b></span></p>  </td>
  <td width=150 style='width:73.55pt;border:none;border-bottom:solid #BFBFBF 1.0pt;
  mso-border-bottom-alt:solid #BFBFBF .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:19.0pt; text-align:right; vertical-align:middle'>
  <?php echo $appSession->getCurrency()->format($company_currency_id, $subTotal);?>  </td>
  </tr>
 <tr style='mso-yfti-irow:30;height:19.0pt'>
  <td width=243 style='width:89.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:19.0pt'>
  <p class=MsoNormal align=right style='text-align:right;mso-pagination:none'>PH&Iacute;<span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'>
    <o:p></o:p></span></p>  </td>
  <td width=246 style='width:73.55pt;border:none;border-bottom:solid #BFBFBF 1.0pt;
  mso-border-bottom-alt:solid #BFBFBF .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:19.0pt'>
  <p class=MsoNormal align=right style='text-align:right;mso-pagination:none'><span
  lang=EN style='font-size:9.0pt;line-height:115%;font-family:Roboto;
  mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'><?php echo $appSession->getCurrency()->format($company_currency_id, $service);?> </span><span
  lang=EN style='font-size:10.0pt;line-height:115%;font-family:Roboto;
  mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'><o:p></o:p></span></p>  </td>
  <td width=53 style='width:16.0pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:19.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
 </tr>
 
 <tr style='mso-yfti-irow:33;height:33.0pt'>
  <td width=243 style='width:89.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:33.0pt'>
  <p class=MsoNormal align=right style='text-align:right;mso-pagination:none'><span style="font-family: Roboto"><b
  style='mso-bidi-font-weight:normal'><span lang=EN style='font-size:12.0pt; line-height:115%;mso-fareast-font-family:Roboto; mso-bidi-font-family:Roboto;color:#333F4F'>T&#7892;NG C&#7896;NG </span></b></span></p>  </td>
  <td width=246 style='width:73.55pt;border:none;border-bottom:solid black 1.0pt;
  mso-border-bottom-alt:solid black .75pt;background:#D9EAD3;padding:0in 2.0pt 0in 2.0pt;
  height:33.0pt'>
  <p class=MsoNormal align=right style='text-align:right;mso-pagination:none'><b
  style='mso-bidi-font-weight:normal'><span lang=EN style='font-size:14.0pt;
  line-height:115%;font-family:Roboto;mso-fareast-font-family:Roboto;
  mso-bidi-font-family:Roboto'><?php echo $appSession->getCurrency()->format($company_currency_id, $total);?></span></b><span lang=EN style='font-size:
  10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:Roboto;
  mso-bidi-font-family:Roboto'><o:p></o:p></span></p>  </td>
  <td width=53 style='width:16.0pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:33.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
 </tr>
 <?php 
 for($j =0; $j<$dt_payment->getRowCount(); $j++)
 {
 	$payment_name= $dt_payment->getString($j, "name");
	$currency_id = $dt_payment->getString($j, "currency_id");
	$payment = $dt_payment->getFloat($j, "amount");
 ?>
<tr style='mso-yfti-irow:30;height:19.0pt'>
  <td width=243 style='width:89.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:19.0pt'>
  <p class=MsoNormal align=right style='text-align:right;mso-pagination:none'><span class="style3" style="font-family: Roboto"><?php echo $payment_name;?></span><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'>
    <o:p></o:p></span></p>  </td>
  <td width=246 style='width:73.55pt;border:none;border-bottom:solid #BFBFBF 1.0pt;
  mso-border-bottom-alt:solid #BFBFBF .75pt;padding:0in 2.0pt 0in 2.0pt;
  height:19.0pt'>
  <p class=MsoNormal align=right style='text-align:right;mso-pagination:none'><span
  lang=EN style='font-size:9.0pt;line-height:115%;font-family:Roboto;
  mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'><?php echo $appSession->getCurrency()->format($company_currency_id, $payment);?> </span><span
  lang=EN style='font-size:10.0pt;line-height:115%;font-family:Roboto;
  mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'><o:p></o:p></span></p>  </td>
  <td width=53 style='width:16.0pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:19.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
 </tr>
 <?php
 }
 ?>
 <tr style='mso-yfti-irow:34;height:9.0pt'>
  <td valign="top" nowrap="nowrap" style='border:none;padding:0in 2.0pt 0in 2.0pt;'></soan></td>
  <td colspan=7 align="right" valign="top" style='width:383.55pt;border:none;
  height:9.0pt;padding:2pt 2.0pt 0in 2.0pt;'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:11.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><i><strong>Vi&#7871;t b&#7857;ng ch&#7919;</strong>: <?php echo $appSession->getCurrency()->toword($company_currency_id, $total);?></i></span></p> </td>
  </tr>
   
 
 <tr style='mso-yfti-irow:36;height:15.0pt'>
   <td colspan="8" align="center" valign=top class="style1" style='border:none;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'><table width="100%" border="0">
     <tr>
       <td align="center" valign="middle">&nbsp;</td>
       <td align="center" valign="middle">&nbsp;</td>
       <td align="center" valign="middle">&nbsp;</td>
       <td align="center" valign="middle">&nbsp;</td>
       <td align="center" valign="middle" nowrap><strong><em>Ng&agrave;y <?php echo $dd;?> th&aacute;ng <?php echo $mm;?> n&#259;m <?php echo $yy;?> </em></strong></td>
     </tr>
     <tr>
       <td width="18%" align="center" valign="middle"><strong>KCS</strong></td>
       <td width="18%" align="center" valign="middle"><strong>Th&#7911; kho
         <?php if($stock_name != ""){?>
         (<?php echo $stock_name;?>)
         <?php } ?>
       </strong></td>
       <td width="26%" align="center" valign="middle"><strong>B&ecirc;n giao h&agrave;ng </strong></td>
       <td width="25%" align="center" valign="middle"><strong>B&ecirc;n nh&#7853;n h&agrave;ng </strong></td>
       <td width="31%" align="center" valign="middle"><strong>Th&#7911; qu&#7929; </strong></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
   </table></td>
  </tr>
 <tr style='mso-yfti-irow:36;height:15.0pt'>
  <td width=243 valign=bottom style='width:51.75pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=246 valign=bottom style='width:128.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=53 valign=bottom style='width:30.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=182 valign=bottom style='width:62.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'>&nbsp;</td>
  <td width=104 valign=bottom style='width:62.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=147 valign=bottom style='width:89.25pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=147 valign=bottom style='width:73.55pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=150 valign=bottom style='width:16.0pt;border:none;padding:0in 2.0pt 0in 2.0pt;
  height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
 </tr>
 <tr style='mso-yfti-irow:37;mso-yfti-lastrow:yes;height:15.0pt'>
  <td width=243 valign=bottom style='width:51.75pt;border:none;background:#FF5722;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=246 valign=bottom style='width:128.25pt;border:none;background:
  #FF5722;padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=53 valign=bottom style='width:30.25pt;border:none;background:#FF5722;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=182 valign=bottom style='width:62.25pt;border:none;background:#FF5722;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>&nbsp;</td>
  <td width=104 valign=bottom style='width:62.25pt;border:none;background:#FF5722;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=147 valign=bottom style='width:89.25pt;border:none;background:#FF5722;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=147 valign=bottom style='width:73.55pt;border:none;background:#FF5722;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
  <td width=150 valign=bottom style='width:16.0pt;border:none;background:#FF5722;
  padding:0in 2.0pt 0in 2.0pt;height:15.0pt'>
  <p class=MsoNormal style='mso-pagination:none'><span lang=EN
  style='font-size:10.0pt;line-height:115%;font-family:Roboto;mso-fareast-font-family:
  Roboto;mso-bidi-font-family:Roboto'><o:p>&nbsp;</o:p></span></p>  </td>
 </tr>
</table>

</body>

</html>

<?php
}
?>
    
