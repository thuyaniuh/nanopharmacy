<?php
validUser($appSession);

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
if($fdate == "")
{
	$fdate = date('Y-m-d 00:00:00');
}
if($tdate == "")
{
	$tdate = date('Y-m-d 23:59:59');
}
?>
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="<?php echo URL;?>favicon.ico" type="image/png"/>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="<?php echo URL;?>assets/css/bootstrap.min.css">

<!-- Animate CSS -->
<link rel="stylesheet" href="<?php echo URL;?>assets/css/animate.css" />

<!-- Custom CSS -->
<link href="<?php echo URL;?>assets/css/style.css?v=3" type="text/css" rel="stylesheet">

<!-- Responsive CSS -->
<link href="<?php echo URL;?>assets/css/responsive.css" type="text/css" rel="stylesheet">

<!-- Font CSS -->
<link href="<?php echo URL;?>assets/css/gogle_sans_font.css" type="text/css" rel="stylesheet">
<link href="<?php echo URL;?>assets/daterange/daterangepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo URL;?>assets/report/css.css" type="text/css" rel="stylesheet">
<script src="<?php echo URL;?>assets/js/jquery.js"></script>
<script src="<?php echo URL;?>assets/daterange/moment.min.js"></script>
<script src="<?php echo URL;?>assets/daterange/daterangepicker.min.js"></script>
<script src="<?php echo URL;?>assets/js/bootstrap.min.js"></script>


<?php
$msg = $appSession->getTier()->createMessage();
$sql = "SELECT d1.sale_id, d2.customer_id, d2.receipt_no, d2.receipt_date, d2.order_no, d2.order_date, d3.code, d3.name, d3.phone, d3.email,  d3.address AS address, d5.name AS ward_name, d6.name AS dist_name, d7.name AS city_name, d9.name AS delivery_name, d9.start_date, d9.tel AS delivery_phone, d9.address AS delivery_address, d10.name AS ward_delivery_name, d11.name AS dist_delivery_name, d12.name AS city_delivery_name, p.id, p.name AS company_name, SUM(d1.quantity * d1.unit_price) AS amount, SUM(d1.discount_amount) AS discount, SUM(d1.service_amount) AS service, SUM(d1.tax_amount) AS tax, d2.description, d9.description AS delivery_description FROM  sale_product_local d1 LEFT OUTER JOIN sale_local d2 ON(d1.sale_id = d2.id) LEFT OUTER JOIN customer d3 ON(d2.customer_id = d3.id) LEFT OUTER JOIN res_address d5 ON(d3.address_id = d5.id) LEFT OUTER JOIN res_address d6 ON(d5.parent_id = d6.id) LEFT OUTER JOIN res_address d7 ON(d6.parent_id = d7.id) LEFT OUTER JOIN  sale_shipping d9 ON(d2.id = d9.sale_id AND d9.status =0) LEFT OUTER JOIN res_address d10 ON(d9.address_id = d10.id) LEFT OUTER JOIN res_address d11 ON(d10.parent_id = d11.id) LEFT OUTER JOIN res_address d12 ON(d11.parent_id = d12.id) LEFT OUTER JOIN res_company p ON(d2.company_id = p.id)  WHERE d1.status =0 AND d2.status =0";

$sql = $sql." AND d9.start_date>='".$fdate."'";
$sql = $sql." AND d9.start_date<='".$tdate."'";

$sql = $sql." GROUP BY d1.sale_id, d2.order_no, d2.order_date, d2.receipt_no, d2.receipt_date, d3.code, d3.name, d3.phone, d3.email,  d3.address, d5.name, d6.name, d7.name , d9.name, d9.tel, d9.address, d10.name , d11.name , d12.name, d9.start_date, d2.description,  d9.description, p.id, p.name, d2.customer_id";
$sql = $sql." ORDER BY d9.start_date ASC";

$msg->add("query", $sql);
$dt = $appSession->getTier()->getTable($msg);


$msg->add("query", $sql);
$dt = $appSession->getTier()->getTable($msg);

$sql = "SELECT d1.rel_id, d2.order_no FROM res_rel d1 LEFT OUTER JOIN sale_local d2 ON(d1.res_id = d2.id) LEFT OUTER JOIN sale_local d3 ON(d1.rel_id = d3.customer_id) LEFT OUTER JOIN sale_shipping d4 ON(d3.id = d4.sale_id AND d4.status =0) WHERE d1.status =0 ";
$sql = $sql." AND d4.start_date>='".$fdate."'";
$sql = $sql." AND d4.start_date<='".$tdate."'";

$msg->add("query", $sql);
$dt_rel = $appSession->getTier()->getTable($msg);



$sql = "SELECT d1.line_id, d1.payment_id, d3.name, d3.sequence,  SUM(d1.amount) AS amount FROM  account_payment_line d1 LEFT OUTER JOIN sale d2 ON(d1.line_id = d2.id) LEFT OUTER JOIN account_payment d3 ON(d1.payment_id = d3.id)  LEFT OUTER JOIN  sale_shipping d9 ON(d2.id = d9.sale_id AND d9.status =0) WHERE d1.status =0 AND d2.status =0";
$sql = $sql." AND d9.start_date>='".$fdate."'";
$sql = $sql." AND d9.start_date<='".$tdate."'";
$sql = $sql." GROUP BY d1.line_id, d1.payment_id, d3.name, d3.sequence";
$sql = $sql." ORDER BY d3.sequence ASC, d3.name ASC";

$msg->add("query", $sql);
$dt_payment = $appSession->getTier()->getTable($msg);
$dt_payment_group = $appSession->getTool()->selectDistinctTable($dt_payment, ["payment_id", "name"]);


?><body>
<div id="reportrange" style="cursor: pointer; width: 100%">
	<img src="<?php echo URL;?>assets/images/calendar.png" border="0" /></i>&nbsp;
	<span></span> <img src="<?php echo URL;?>assets/images/menu_down.png" border="0" />
	<?php echo $fdate;?> đến <?php echo $tdate;?>
</div>
<br>
<table width="100%" border="0" class="borderLine">

  <tr class="header">
    <td rowspan="2" align="center" nowrap style="width:20px"><strong>Stt</strong></td>
    <td rowspan="2" align="center" nowrap style="width:80px"><strong>Số CT </strong></td>
    <td rowspan="2" align="center" nowrap style="width:60px"><strong>Ngày CT </strong></td>
    <td rowspan="2" align="center" nowrap style="width:60px"><strong>Số đặt </strong></td>
    <td rowspan="2" align="center" nowrap style="width:60px"><strong>Ngày đặt </strong></td>
    <td rowspan="2" align="center" nowrap style="width:60px"><strong>Thành tiền </strong></td>
    <td rowspan="2" align="center" nowrap style="width:60px"><strong>Giảm giá </strong></td>
    <td rowspan="2" align="center" nowrap style="width:60px"><strong>Phí</strong></td>
    <td rowspan="2" align="center" nowrap style="width:60px"><strong>Thuế</strong></td>
    <td rowspan="2" align="center" nowrap style="width:60px"><strong>Tổng</strong></td>
	<td colspan="<?php echo $dt_payment_group->getRowCount();?>" align="center" nowrap style="width:60px"><strong>Thanh toán</strong></td>
    <td rowspan="2" align="center" nowrap style="width:60px"><strong>Đơn hàng kèm </strong></td>
    <td colspan="8" align="center" nowrap style="width:60px"><strong>Thông tin giao hàng </strong></td>
	<td rowspan="2" align="center" nowrap  style="width:90px"><strong>Cửa hàng</strong></td>
    <td   rowspan="2" align="center" nowrap style="width:60px"><strong>Ghi chú </strong></td>
  </tr>
  <tr class="header">
	<?php
	for($j=0; $j<$dt_payment_group->getRowCount(); $j++)
	{
	?>
	<td   align="center" nowrap style="width:60px"><strong><?php echo $dt_payment_group->getString($j, "name");?></strong></td>
	<?php
	}
	?>
    <td align="center" nowrap  style="width:200px"><strong>Tên</strong></td>
    <td align="center" nowrap  style="width:90px"><strong>Ngày giao </strong></td>
    <td align="center" nowrap  style="width:90px"><strong>Điện thoại </strong></td>
	<td align="center" nowrap  style="width:90px"><strong>Địa chỉ </strong></td>
	<td align="center" nowrap  style="width:90px"><strong>Phường</strong></td>
    <td align="center" nowrap  style="width:90px"><strong>Quận</strong></td>
    <td align="center" nowrap  style="width:90px"><strong>Tỉnh/thành</strong></td>
    
	 <td align="center" nowrap  style="width:90px"><strong>Ghi chú </strong></td>
  </tr>
	<?php
	$no = 0;
	$totalAmount = 0;
	$totalDiscount = 0;
	$totalService = 0;
	$totalTax = 0;

	
	for($i =0; $i<$dt->getRowCount(); $i++)
	{
		$no = $no + 1;
		$sale_id = $dt->getString($i, "sale_id");
		$customer_id = $dt->getString($i, "customer_id");
		$receipt_no = $dt->getString($i, "receipt_no");
		$receipt_date = $dt->getString($i, "receipt_date");
		$receipt_date = $appSession->getFormats()->getDATE()->formatDate($appSession->getTool()->toDateTime($receipt_date));
		
		$order_no = $dt->getString($i, "order_no");
		$order_date = $dt->getString($i, "order_date");
		$order_date = $appSession->getFormats()->getDATE()->formatDate($appSession->getTool()->toDateTime($order_date));
		
		$amount = $dt->getFloat($i, "amount");
		$discount = $dt->getFloat($i, "discount");
		$service = $dt->getFloat($i, "service");
		$tax = $dt->getFloat($i, "tax");
		$total = $amount - $discount + $service + $tax;
		
		$code = $dt->getString($i, "code");
		$name = $dt->getString($i, "name");
		$phone = $dt->getString($i, "phone");
		$email = $dt->getString($i, "email");
		$address = $dt->getString($i, "address");
		$ward_name = $dt->getString($i, "ward_name");
		$dist_name = $dt->getString($i, "dist_name");
		$city_name = $dt->getString($i, "city_name");
		$delivery_name = $dt->getString($i, "delivery_name");
		$delivery_phone = $dt->getString($i, "delivery_phone");
		$delivery_address = $dt->getString($i, "delivery_address");
		$delivery_ward_name = $dt->getString($i, "ward_delivery_name");
		$delivery_dist_name = $dt->getString($i, "dist_delivery_name");
		$delivery_city_name = $dt->getString($i, "city_delivery_name");
		$delivery_date = $dt->getString($i, "start_date");
		$company_name = $dt->getString($i, "company_name");
		if($delivery_date != "")
		{
			$delivery_date = $appSession->getFormats()->getDATE()->formatDate($appSession->getTool()->toDateTime($delivery_date));
		}
		$description = $dt->getString($i, "description");
		$delivery_description = $dt->getString($i, "delivery_description");
		$totalAmount = $totalAmount + $amount;
		$totalDiscount = $totalDiscount + $discount;
		$totalService = $totalService + $service;
		$totalTax = $totalTax + $tax;
		$order_rel = "";
		for($j =0; $j<$dt_rel->getRowCount(); $j++)
		{
			if($dt_rel->getString($j, "rel_id") == $customer_id)
			{
				if($order_rel != "")
				{
					$order_rel = $order_rel."; ";
				}
				$order_rel = $order_rel.$dt_rel->getString($j, "order_no");
			}
		}
		
	?>
	<tr>
		<td style="vertical-align:middle"><?php echo $no;?>.</td>
		<td style="vertical-align:middle "><?php echo $receipt_no;?></td>
		<td style="vertical-align:middle"><?php echo $receipt_date;?></td>
		<td style="vertical-align:middle"><?php echo $order_no;?></td>
		<td style="vertical-align:middle"><?php echo $order_date;?></td>
		<td align="right" style="vertical-align:middle"><?php echo $appSession->getFormats()->getINT()->format($amount);?></td>
		<td align="right" style="vertical-align:middle"><?php echo $appSession->getFormats()->getINT()->format($discount);?></td>
		<td align="right" style="vertical-align:middle"><?php echo $appSession->getFormats()->getINT()->format($service);?></td>
		<td align="right" style="vertical-align:middle"><?php echo $appSession->getFormats()->getINT()->format($tax);?></td>
		<td align="right" style="vertical-align:middle"><?php echo $appSession->getFormats()->getINT()->format($total);?></td>
		<?php
		
		for($j=0; $j<$dt_payment_group->getRowCount(); $j++)
		{
			$payment = 0;
			$payment_id = $dt_payment_group->getString($j, "payment_id");
			
			for($n =0; $n<$dt_payment->getRowCount(); $n++)
			{
				if($dt_payment->getString($n, "payment_id") == $payment_id && $dt_payment->getString($n, "line_id") == $sale_id)
				{
					$payment = $payment + $dt_payment->getFloat($n, "amount");
				}
			}
		?>
		<td align="right"><?php echo $appSession->getFormats()->getINT()->format($payment);?></td>
		<?php
		}
		?>
	
		<td style="vertical-align:middle"><?php echo $order_rel;?></td>
		<td style="text-align: left;"><?php echo $delivery_name;?></td>
		<td style="text-align: left;"><?php echo $delivery_date;?></td>
		<td style="text-align: left;"><?php echo $delivery_phone;?></td>
		<td style="text-align: left;"><?php echo $delivery_address;?></td>
		<td style="text-align: left;"><?php echo $delivery_ward_name;?></td>
	    <td style="text-align: left;"><?php echo $delivery_dist_name;?></td>
	    <td style="text-align: left;"><?php echo $delivery_city_name;?></td>
	    <td style="text-align: left;"><?php echo $delivery_description;?></td>
	    <td style="text-align: left;"><?php echo $company_name;?></td>
		<td style="text-align: left;"><?php echo $description;?></td>
	</tr>
	<?php
	}
	$total = $totalAmount - $totalDiscount + $totalService + $totalTax;
	?>
	<tr>
		<td colspan="5" style="vertical-align:middle"><strong>Tổng cộng: </strong></td>
		<td align="right" style="vertical-align:middle"><strong><?php echo $appSession->getFormats()->getINT()->format($totalAmount);?></strong></td>
		<td align="right" style="vertical-align:middle"><strong><?php echo $appSession->getFormats()->getINT()->format($totalDiscount);?></strong></td>
		<td align="right" style="vertical-align:middle"><strong><?php echo $appSession->getFormats()->getINT()->format($totalService);?></strong></td>
		<td align="right" style="vertical-align:middle"><strong><?php echo $appSession->getFormats()->getINT()->format($totalTax);?></strong></td>
		<td align="right" style="vertical-align:middle"><strong><?php echo $appSession->getFormats()->getINT()->format($total);?></strong></td>
		<?php
		
		for($j=0; $j<$dt_payment_group->getRowCount(); $j++)
		{
			$payment = 0;
			$payment_id = $dt_payment_group->getString($j, "payment_id");
			
			for($n =0; $n<$dt_payment->getRowCount(); $n++)
			{
				if($dt_payment->getString($n, "payment_id") == $payment_id)
				{
					$payment = $payment + $dt_payment->getFloat($n, "amount");
				}
			}
		?>
		<td align="right"><b><?php echo $appSession->getFormats()->getINT()->format($payment);?></b></td>
		<?php
		}
		?>
		<td colspan="11" style="vertical-align:middle"> </td>
	  </tr>
</table>	
<script>
var fdate = "<?php echo $fdate;?>";
var tdate = "<?php echo $tdate;?>";
$( document ).ready(function() {
	var start = moment();
    var end = moment();

    function cb(start, end) {
		if(start != null)
		{
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			fdate = start.format('YYYY-MM-D') + ' 00:00:00';
			tdate = end.format('YYYY-MM-D')+ ' 23:59:59';
		}else{
			$('#reportrange span').html(null);
		}
        
		loadOrders();
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
		   ' ': [null, null],
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    //cb(start, end);
});
function loadOrders()
{
	document.location.href='<?php echo URL;?>addons/order/package_list/?fdate=' + fdate + '&tdate=' + tdate;
}
</script>
</body>
</html>

    
