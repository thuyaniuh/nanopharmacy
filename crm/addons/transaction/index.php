<?php
validUser($appSession);
$msg = $appSession->getTier()->createMessage();

$page_id = "transaction";
$table_name = "sale";
$columns = ["code", "name", "phone", "email", "address", "category_id", "vat"];
$searchs = ["d2.name", "d1.receipt_no", "d3.name"];

$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
if($ac == "")
{
	$ac = "view";
}
if($ac == "view")
{
	
	$appSession->getLang()->load($appSession->getTier(), "HOME", $appSession->getConfig()->getProperty("lang_id"));
	$customer_id = "";
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}


?>
<div class="page_title">
	<div class="row align-items-center mx-0">
		<div class="col-4 col-md-4">
			<div class="title_inner d-flex">
				<h3 class="d-flex align-items-center"><?php echo $appSession->getLang()->find("Transaction");?></h3>
				
			</div>
		</div>
		<div class="col-8  col-md-8 ">
		<table>
			<tr>
				<td><div id="reportrange" style="cursor: pointer; width: 100%">
					<img src="<?php echo URL;?>assets/images/calendar.png" border="0" /></i>&nbsp;
					<span></span> <img src="<?php echo URL;?>assets/images/menu_down.png" border="0" />
				</div></td>
				<td><form class="search_box col-12 col-sm-12 col-md-12 col-lg-8 col-xl-7 p-0 px-lg-3 mt-3 mt-lg-0 pb-3 pb-md-0 ml-auto" onsubmit="return false">
				<div class="form-group d-flex">
					<div class="input-group-prepend">
						<div class="input-group-text"><i class="zmdi zmdi-search" onclick="doSearchTransaction(0)"></i></div>
					</div>
					<input type="text" id="editsearch" class="form-control" placeholder="Search" value="" onKeyDown="if(event.keyCode == 13){doSearchTransaction(0);}">
					
			</div></form></td>
			</tr>
		</table>
			
			
		</div>
	</div>
</div>
<!-- Right Sidebar Start -->
<div class="right_sidebar" id="pnOrders">
	
</div>
<!-- Tab Content End -->

<script>
var fdate = "";
var tdate = "";
$( document ).ready(function() {
	var start = moment().subtract(6, 'days');
    var end = moment();

    function cb(start, end) {
		if(start != null)
		{
			$('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
			fdate = start.format('YYYY-MM-D') + ' 00:00:00.000';
			tdate = end.format('YYYY-MM-D')+ ' 23:59:59.999';
		}else{
			$('#reportrange span').html(null);
		}
        
		doSearchTransaction(0);
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           '<?php echo $appSession->getLang()->find("Today");?>': [moment(), moment()],
           '<?php echo $appSession->getLang()->find("Yesterday");?>': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           '<?php echo $appSession->getLang()->find("Last 7 Days");?>': [moment().subtract(6, 'days'), moment()],
           '<?php echo $appSession->getLang()->find("Last 30 Days");?>': [moment().subtract(29, 'days'), moment()],
           '<?php echo $appSession->getLang()->find("This Month");?>': [moment().startOf('month'), moment().endOf('month')],
           '<?php echo $appSession->getLang()->find("Last Month");?>': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
		"locale": {
			"format": "DD/MM/YYYY",
			"separator": " - ",
			"applyLabel": "Đồng ý",
			"cancelLabel": "Đóng",
			"fromLabel": "Từ",
			"toLabel": "Đến",
			"customRangeLabel": "Chọn ngày",
			"daysOfWeek": [
				"Chủ nhật",
				"Thứ 2",
				"Thứ 3",
				"Thứ 4",
				"Thứ 5",
				"Thứ 6",
				"Thứ 7"
			],
			"monthNames": [
				"Tháng giêng",
				"Tháng hai",
				"Tháng 3",
				"Tháng tư",
				"Tháng năm",
				"Tháng sáu",
				"Tháng bảy",
				"Tháng tám",
				"Thang chín",
				"Tháng mười",
				"Tháng mười một",
				"Tháng mười hai"
			],
			"firstDay": 1
		}
    }, cb);

    cb(start, end);
});
var pp = 0;
function doSearchTransaction(p)
{
	pp = p;
	var search = document.getElementById('editsearch').value;
	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=orders&customer_id=<?php echo $customer_id;?>&search=' + encodeURIComponent(search) + "&p=" + p;
	_url = _url + "&fdate=" + encodeURIComponent(fdate);
	_url = _url + "&tdate=" + encodeURIComponent(tdate);
	loadPage('pnOrders', _url, function(status, message)
	{
		if(status== 0)
		{
			
		}
		
	}, false);
}
function showOrder(id)
{
	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=line&id=' + id;
	showPopup(_url);
}
function printContent(el){
var data=document.getElementById(el).innerHTML;
            var myWindow = window.open('', 'my div', 'height=400,width=600');
            myWindow.document.write('<html><head><title>my div</title>');
            /*optional stylesheet*/ //myWindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
            myWindow.document.write('</head><body >');
            myWindow.document.write(data);
            myWindow.document.write('</body></html>');
            myWindow.document.close(); // necessary for IE >= 10

            myWindow.onload=function(){ // necessary if the div contain images

                myWindow.focus(); // necessary for IE >= 10
                myWindow.print();
                myWindow.close();
            };
}
function removeRedInvoice(id)
{
	var result = confirm("Bạn muốn hủy hóa đơn?");
	if (!result) {
		return;
	}
	
	var _url = '<?php echo URL;?>addons/order/payment/?ac=removeRedInvoice&local=&sale_id=' + id;

	loadPage('pnCategory', _url, function(status, message)
	{
		if(status== 0)
		{
			message = message.replace(/^\s+|\s+$/gm,'')
			if(message.length == 36)
			{
				doSearchTransaction(0);
			}else{
				alert(message);
			}
			
		}
		
	}, true);
}
function saleCustomer(sale_id)
{
	
	var _url = '<?php echo URL;?>addons/transaction_customer/?ac=view&sale_id=' + sale_id;
	
	showPopup(_url);
}
function updateSaleCustomer(sale_id, customer_id, type)
{
	var _url = '<?php echo URL;?>addons/transaction/?ac=updateCustomer&sale_id=' + sale_id;
	_url = _url + '&customer_id=' + customer_id;

	
	var ctr = document.getElementById('frmdialogClose');
	if(ctr != null)
	{
		ctr.click();
	}
	loadPage('gotoTop', _url, function(status, message)
	{
		if(status== 0)
		{
			if(message.indexOf("OK") != -1)
			{
				
				doSearchTransaction(pp);
				
			}else{
				alert(message);
			}
		}
		
	}, true);
}

</script>
<?php
}else if($ac == "orders")
{
	$appSession->getLang()->load($appSession->getTier(), "HOME", $appSession->getConfig()->getProperty("lang_id"));
	$search = "";
	if(isset($_REQUEST['search']))
	{
		$search = $_REQUEST['search'];
	}
	$p = 0;
	if(isset($_REQUEST['p']))
	{
		$p = $_REQUEST['p'];
	}


	$ps = 30;
	if(isset($_REQUEST['ps']))
	{
		$ps = $_REQUEST['ps'];
	}
	$fdate="";
	if(isset($_REQUEST['fdate']))
	{
		$fdate = $_REQUEST['fdate'];
	}
	$tdate="";;
	if(isset($_REQUEST['tdate']))
	{
		$tdate = $_REQUEST['tdate'];
	}
	$customer_id="";;
	if(isset($_REQUEST['customer_id']))
	{
		$customer_id = $_REQUEST['customer_id'];
	}
	
	$sql = "SELECT d1.id, d1.order_no, d1.receipt_date, d1.category_id, d3.code AS category_code, d3.name AS category_name, d2.name AS customer_name, d2.commercial_name, d4.name AS table_name, d5.name AS delivery_name, (SELECT SUM(quantity) from sale_product WHERE sale_id=d1.id AND status=0) AS items, (SELECT SUM((quantity * unit_price) + tax_amount+service_amount-discount_amount) from sale_product WHERE sale_id=d1.id AND status=0) AS amount, d1.status, d1.cashier_date FROM sale d1 LEFT OUTER JOIN customer d2 ON(d1.customer_id = d2.id) LEFT OUTER JOIN sale_category d3 ON(d1.category_id = d3.id) LEFT OUTER JOIN product_table d4 ON(d1.table_id = d4.id) LEFT OUTER JOIN sale_shipping d5 ON(d1.id = d5.sale_id) WHERE ";
	$sql = $sql."  (d1.status =0 OR d1.status=2) AND d1.customer_id ='".$customer_id."'";
	//$sql = $sql." AND d1.receipt_date>='".$fdate."'";
	//$sql = $sql." AND d1.receipt_date<='".$tdate."'";
	if($search != "")
	{
		$sql = $sql." AND (".$appSession->getTier()->buildSearch($searchs, $search).")";
	}

	$arr = $appSession->getTier()->paging($sql, $p, $ps, "d1.receipt_date DESC");


	$item_count = 0;
	$sql = $arr[1];
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);

	if(count($values)>0)
	{
		
		$item_count = $values[0][0];
	}

	$page_count = (int)($item_count / $ps);
	if ($item_count - ($page_count * $ps) > 0)
	{
		$page_count = $page_count + 1;
	}

	$start = 0;
	if($item_count>0)
	{
		$start = ($p * $ps) + 1;
	}
	$end = $p + 1;
	if((($p + 1) * $ps)<$item_count)
	{
		$end = ($p + 1) * $ps;
	}else
	{
		$end = $item_count;
	}

	$sql = $arr[0];

	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	
	
	
	$company_currency_id = $appSession->getConfig()->getProperty("currency_id");
	
	?>
	<!-- Tab Content Start -->
	<div class="tab-content" id="nav-tabContent">

		<!-- Food Items Tab customers Start -->
		<div>
			<!-- Order List Start -->
			<div class="order_list">
			<div class="table-responsive">
				 <table class="table table-bordered table-hover table-sm">
					<tr class="list_header">
					<td class="text-left" nowrap="nowrap">Số Bill</td>
					
					<td class="text-left " nowrap="nowrap">Ngày HĐ</td>

		
					<td class="text-left " nowrap="nowrap"><?php echo $appSession->getLang()->find("Customer");?></td>
					<td class="text-left " nowrap="nowrap"><?php echo $appSession->getLang()->find("Item");?></td>

					<td class="text-left " nowrap="nowrap"><?php echo $appSession->getLang()->find("Amount");?></td>
					<td class="text-right Action" style="width: 5%;"><?php echo $appSession->getLang()->find("Action");?></td>
				 </tr>
					<tbody>
                           
					<?php
					$hasRequest = false;
					for($i =0; $i<$dt->getRowCount(); $i++)
					{
						$id = $dt->getString($i, "id");
						$sale_status = $dt->getString($i, "status");
						$order_no = $dt->getString($i, "order_no");
						$receipt_date = $dt->getString($i, "receipt_date");
						$cashier_date = $dt->getString($i, "cashier_date");
						$category_name = $dt->getString($i, "category_name");
						$customer_name = $dt->getString($i, "commercial_name");
						if($customer_name == "")
						{
							$customer_name = $dt->getString($i, "customer_name");
						}
						$description = $dt->getString($i, "description");
						$items = $dt->getFloat($i, "items");
						$amount = $dt->getFloat($i, "amount");
						
					
						
						
					?>
						
									 
					<tr class="animate__animated animate__fadeInUp wow">
						<td class="text-left"><a href="javascript:printPreview('<?php echo $id;?>')"><?php echo $order_no;?></a></td>
			
						<td  class="text-left " nowrap="nowrap"><?php
	  if($receipt_date != "")
		{
			$d = $appSession->getTool()->toDateTime($receipt_date);
			$stime = $appSession->getTool()->paddingLeft($d->getHours(), '0', 2).":".$appSession->getTool()->paddingLeft($d->getMinutes(), '0', 2);
			
			$receipt_date = $appSession->getTool()->paddingLeft($d->getDays(), '0', 2)."/".$appSession->getTool()->paddingLeft($d->getMonths(), '0', 2)."/".$appSession->getTool()->paddingLeft($d->getYears(), '0', 2);
			echo $receipt_date;
		}
	  ?></td>
	  
						
						
						<td class="text-left " nowrap="nowrap" ><?php echo $customer_name;?>
						</td>
						<td class="text-left " nowrap="nowrap" ><?php echo $items;?></td>
			
						<td class="text-left " nowrap="nowrap"><?php echo $appSession->getCurrency()->format($company_currency_id, $amount);?></td>
						<td  class="btn_container  d-flex mr-0 ml-auto" nowrap="nowrap">
							<button title="In hóa đơn" type="button" class="btn" style="background: #f8f9fd; color:#009946; border-radius: 4px; margin: 0 3px;min-width: 23px; height: 23px; font-size: 1.2rem;" onclick = "printPreview('<?php echo $id;?>')">
								<i class="zmdi zmdi-print"></i>
							</button>
							
							
					
						</td>
					</tr>
					<?php
					}
					?>
					

				</tbody>
				</table>
				</div>
				</div>
				
			</div>
			<!-- Order List End -->

			<!-- Tab Footer start -->
			<div class="tab_footer">
				<div class="row no-gutter align-items-center">
					<div class="col-12 col-md-12 col-lg-4 pb-3">
						<h2><?php echo $start;?> - <?php echo $end;?> | <?php echo $item_count;?></h2>
					</div>
					<div class="col-12 col-md-12 col-lg-8 pb-3">
						<div class="row align-items-center">
							<nav class="navigation col-12" aria-label="Page navigation example">
								<ul class="pagination justify-content-end mb-0">
									<?php 
			
									if($page_count == 0)
									{
										$page_count = 1;
									}
									for($i =0; $i<$page_count; $i++)
									{
									?>
									<li class="page-item"><a class="page-link" href="javascript:doSearchTransaction('<?php echo $i;?>')"><?php echo ($i + 1);?></a></li>
									<?php
									}
									?>
								</ul>
							</nav>

						</div>
					</div>
				</div>
			</div>
			<!-- Tab Footer End -->
		</div>
		<script>
		function printPreview(sale_id)
		{
			var _url = '<?php echo URL;?>addons/order/invoice/?sale_id=' + sale_id;
			_url = _url + "&KHHDon=&local=";
			window.open(_url, "", "width=300");
			

			
		}
		
		</script>
	<?php
}
else if($ac == "line")
{
	$appSession->getLang()->load($appSession->getTier(), "SALE", $appSession->getConfig()->getProperty("lang_id"));
	
	$msg = $appSession->getTier()->createMessage();
	$sale_id = '';
	if(isset($_REQUEST['id']))
	{
		$sale_id = $_REQUEST['id'];
	}
	$sql = "SELECT d1.receipt_no, d2.commercial_name AS company_name, d2.address AS company_address, d2.phone AS company_phone, d1.order_date, d3.name AS customer_name, d3.phone AS customer_phone, d3.address AS customer_address, d3.email AS customer_email, d3.contact_name AS customer_contact_name, d3.email AS customer_email, d4.name AS delivery_name, d4.tel AS delivery_phone, d4.address AS delivery_address, d4.description AS delivery_description, d5.name AS delivery_ward_name, d6.name AS delivery_dist_name, d7.name AS delivery_city_name, d2.currency_id, d1.receipt_date, d8.document_id, d9.name AS table_name, d10.name AS user_name, d2.slogan, d3.vat AS customer_vat, d1.cashier_count FROM sale d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id= d2.id) LEFT OUTER JOIN customer d3 ON(d1.customer_id = d3.id) LEFT OUTER JOIN sale_shipping d4 ON(d1.id = d4.sale_id) LEFT OUTER JOIN res_address d5 ON(d4.address_id = d5.id) LEFT OUTER JOIN res_address d6 ON(d5.parent_id = d6.id) LEFT OUTER JOIN res_address d7 ON(d6.parent_id = d7.id) LEFT OUTER JOIN poster d8 ON(d2.id = d8.rel_id AND d8.publish=1) LEFT OUTER JOIN product_table d9 ON(d1.table_id = d9.id) LEFT OUTER JOIN res_user d10 ON(d1.create_uid = d10.id) WHERE d1.id='".$sale_id."'";


	$msg->add("query", $sql);
	$dt_sale = $appSession->getTier()->getTable($msg);

	if($dt_sale->getRowCount()>0)
	{
		$sql = "SELECT create_uid, response FROM third_party_response_line WHERE rel_id='".$sale_id."' AND status =0";
		$msg->add("query", $sql);
		
		$MCCQT = "";
		$SHDon = "";
		$NLap = "";
		$NMua = "";
		$MSTCNhanh = "";
		$MTCuu = "";
		$KHHDon = "";
		$responseList = $appSession->getTier()->getArray($msg);
		for($i=0; $i<count($responseList); $i++)
		{
			if($responseList[$i][0] == "MCCQT")
			{
				$MCCQT = $responseList[$i][1];
			}
			if($responseList[$i][0] == "TTChung.SHDon")
			{
				$SHDon = $responseList[$i][1];
			}
			if($responseList[$i][0] == "TTChung.NLap")
			{
				$NLap = $responseList[$i][1];
			}
			if($responseList[$i][0] == "TTChung.MSTCNhanh")
			{
				$MSTCNhanh = $responseList[$i][1];
			}
			if($responseList[$i][0] == "NMua")
			{
				$NMua = $responseList[$i][1];
			}
			if($responseList[$i][0] == "TTChung.MTCuu")
			{
				$MTCuu = $responseList[$i][1];
			}
			if($responseList[$i][0] == "TTChung.KHHDon")
			{
				$KHHDon = $responseList[$i][1];
			}
		}
		
		$company_name = $dt_sale->getString(0, "company_name");
		$company_address = $dt_sale->getString(0, "company_address");
		$company_phone = $dt_sale->getString(0, "company_phone");
		$receipt_no = $dt_sale->getString(0, "receipt_no");
		$receipt_date = $dt_sale->getString(0, "receipt_date");
		$cashier_count = $dt_sale->getString(0, "cashier_count");
		$dd = "";
		$mm = "";
		$yy = "";
		if($receipt_date != "")
		{
			$dd =  date("d", strtotime($receipt_date));
			$mm =  date("M", strtotime($receipt_date));
			$yy =  date("y", strtotime($receipt_date));
			
			$receipt_date = date("d/m/Y h:i", strtotime($receipt_date));
			
			
		}
		$customer_contact_name = $dt_sale->getString(0, "customer_contact_name");
		$customer_name = $dt_sale->getString(0, "customer_name");
		$customer_phone = $dt_sale->getString(0, "customer_phone");
		$customer_address = $dt_sale->getString(0, "customer_address");
		$customer_contact_name = $dt_sale->getString(0, "customer_contact_name");
		$customer_email = $dt_sale->getString(0, "customer_email");
		$customer_vat = $dt_sale->getString(0, "customer_vat");
		$delivery_name = $dt_sale->getString(0, "delivery_name");
		$delivery_phone = $dt_sale->getString(0, "delivery_phone");
		$delivery_address = $dt_sale->getString(0, "delivery_address");
		$delivery_ward_name = $dt_sale->getString(0, "delivery_ward_name");
		$delivery_dist_name = $dt_sale->getString(0, "delivery_dist_name");
		$delivery_city_name = $dt_sale->getString(0, "delivery_city_name");
		$table_name = $dt_sale->getString(0, "table_name");
		$user_name = $dt_sale->getString(0, "user_name");
		$document_id = $dt_sale->getString(0, "document_id");
		$slogan = $dt_sale->getString(0, "slogan");
		$delivery_description = $dt_sale->getString(0, "delivery_description");
		$company_currency_id = $dt_sale->getString(0, "currency_id");

	$sql = "SELECT d2.id AS sale_id, d1.id AS sale_product_id ,d2.order_no, d2.order_date, d7.name AS delivery_name, d3.code AS product_code, d3.name AS product_name";
	$sql = $sql.", d4.name AS unit_name, d5.name AS attribute_name, d8.name AS type_name";
	$sql = $sql.", d1.currency_id, d1.quantity, d1.unit_price, d1.product_id, d1.unit_id, d1.attribute_id, d1.type_id, d1.rel_id";
	$sql = $sql." FROM sale_product d1";
	$sql = $sql." LEFT OUTER JOIN sale d2 ON(d1.sale_id = d2.id)";
	$sql = $sql." LEFT OUTER JOIN product d3 ON(d1.product_id = d3.id)";
	$sql = $sql." LEFT OUTER JOIN product_price d6 ON(d1.rel_id = d6.id)";
	$sql = $sql." LEFT OUTER JOIN product_unit d4 ON(d6.unit_id = d4.id)";


	$sql = $sql." LEFT OUTER JOIN sale_shipping d7 ON(d2.id = d7.sale_id)";
	$sql = $sql." LEFT OUTER JOIN product_type d8 ON(d6.type_id = d8.id)";
	$sql = $sql." LEFT OUTER JOIN attribute d5 ON(d6.attribute_id = d5.id)";
	$sql = $sql." WHERE d1.status =0 AND d2.id='".$sale_id."' AND d1.quantity>0";
	$msg->add("query", $sql);

	$dt_product = $appSession->getTier()->getTable($msg);				  

	$sql = "SELECT d1.id, d1.currency_id, d1.amount, d1.description, d2.name AS payment_name FROM account_payment_line_local d1 LEFT OUTER JOIN account_payment d2 ON(d1.payment_id = d2.id) WHERE d1.rel_id ='".$sale_id."' AND d1.status =0";
	$msg->add("query", $sql);

	$dt_payment = $appSession->getTier()->getTable($msg);

	$sql = "SELECT d1.rel_id, d1.currency_id, d1.value, d1.description, d2.name, d2.percent, d3.id AS category_id , d3.name AS category_name FROM account_service_line_local d1 LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id) LEFT OUTER JOIN account_invoice_category d3 ON(d2.category_id = d3.id) LEFT OUTER JOIN sale_product_local d4 ON(d1.rel_id = d4.sale_id AND d4.status =0) WHERE d1.rel_id ='".$sale_id."' AND d1.status =0 ORDER BY d1.create_date ASC";

	$msg->add("query", $sql);
	$dt_service = $appSession->getTier()->getTable($msg);

	if($cashier_count == "")
	{
		$cashier_count = "0";
	}
	$cashier_count = $appSession->getTool()->toInt($cashier_count) + 1;
	$sql = "UPdATE sale_local SET cashier_count=".$cashier_count.", cashier_date=".$appSession->getTier()->getDateString().", cashier_id='".$appSession->getConfig()->getProperty("user_id")."' WHERE id='".$sale_id."'";
	$msg->add("query", $sql);
	$r = $appSession->getTier()->exec($msg);

	?>
	<div style="text-align:center; width:100%">
		<a href="javascript:printContent('pnBill')">Print</a>
		<div style="max-width:360px" id="pnBill">
		<table width="100%" border="0"  >

		<tr>
		  <td align="center"><?php if($document_id != ""){?> <img width=71 height=72
		  src="<?php echo URL;?>document/?ac=download&id=<?php echo $document_id;?>&h=72"><?php } ?></td>
		</tr>
		<tr>
		  <td align="center"><span style="font-size:16px"><?php echo $company_name."</span><br>";?><?php echo $company_address."<br>";?><?php echo $company_phone."<br>";?></td>
		</tr>
		<tr>
		  <td align="center"> </td>
		</tr>
		<tr>
		  <td align="center">
			  
			   <table width="100%" border="0" cellpadding="0" cellspacing="0">
				 <tr>
				   <td>Số: <b><?php echo $receipt_no;?></b></td>
				   <td align="right">Ngày: <b><?php echo $receipt_date;?></b></td>
				 </tr>
				  <tr>
				   <td>Nhân viên: <b><?php echo $user_name;?></b></td>
				   <td align="right"><?php if($table_name != ""){?>Bàn: <b><?php echo $table_name;?> <?php } ?></b></td>
				 </tr>
			   </table>     </td>
		</tr>
		<?php
		if($MCCQT != "")
		{
		?>

		<?php
		}
		?>
		<?php
		if($customer_contact_name != "")
		{
		?>
		<tr>
		  <td align="left"> <p class=MsoNormal style='mso-pagination:none'><b style='mso-bidi-font-weight:
		  normal'><span lang=EN style='font-size:9.0pt;line-height:115%;font-family:
		  Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto;color:#1F3864'>KHÁCH HÀNG </span></b><span lang=EN style='font-size:10.0pt;line-height:115%;
		  font-family:Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'>
		  </span></p> </td>
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
		<?php
		}
		?>
		<?php 
		if($delivery_name  != "")
		{
		?>
		<tr>
		<td> <p class=MsoNormal style='mso-pagination:none'><b style='mso-bidi-font-weight:
		  normal'><span lang=EN style='font-size:9.0pt;line-height:115%;font-family:
		  Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto;color:#1F3864'>THÔNG GIAO HÀNG </span></b><span lang=EN style='font-size:10.0pt;line-height:115%;
		  font-family:Roboto;mso-fareast-font-family:Roboto;mso-bidi-font-family:Roboto'>
			</span></p></td>
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
		<?php
		}
		?>
		<tr>
		  <td>
		  <table border=0 cellspacing=0 cellpadding=0 width="100%">
		 

		 <tr>
		  <td  colspan=3 align="center" style="border:none;border-bottom:solid #999999 1.0pt;"  >
		SẢN PHẨM </td>
			 <td width="30" align="center"  nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >
		 ĐƠN<br>
		 VỊ</td>
			 <td width="30" align="center" nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >ĐƠN<br>
			   GIÁ</td>
		  <td width="30" align="center" nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >SỐ<br>
			LƯỢNG </td>
		  <td width="30" align="center" nowrap="nowrap" style="border:none;border-bottom:solid #999999 1.0pt;" >
		 THÀNH<br>
		 TIỀN</td>
		  </tr>
		 <?php
		 $total = 0;
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
			$total = $total + $amount;
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
		 $sql = "SELECT d1.rel_id, d1.category_id, d1.operator, SUM(d1.percent) AS percent, SUM(d1.value) AS value, d1.sequence";
		 $sql = $sql." FROM account_service_line_local d1";
		 $sql = $sql." LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id)";
		 $sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 GROUP BY d1.rel_id, d1.category_id, d1.operator, d1.sequence ORDER BY d1.sequence ASC";
			
		$msg->add("query", $sql);
							
		$serviceList = $appSession->getTier()->getArray($msg);
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
			
		$total = $subTotal-$discount + $service + $tax;
		 ?>
		<tr>
		   <td colspan="7" ><hr></td>
		   </tr>
		 <tr>
		  <td colspan="6"><strong><br>
		  Tổng</strong></td>
		  <td align="right">
			<strong><?php echo $appSession->getCurrency()->format($company_currency_id, $subTotal);?></strong> </td>
		  </tr>
		  <?php
		  if($discount != 0)
		  {
		  ?>
		 <tr>
		   <td colspan="6"><strong>Giảm trừ</strong></td>
		  <td align="right">
			<strong><?php echo $appSession->getCurrency()->format($company_currency_id, $discount);?></strong></td>
		  </tr>
		 <?php 
		 }
		 ?>
		  <?php
		  if($service != 0)
		  {
		  ?>
		 <tr>
		   <td colspan="6"><strong>Dịch vu</strong></td>
		  <td align="right">
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
		   <td colspan="6"><strong>Thuế</strong></td>
		  <td align="right">
			<strong><?php echo $appSession->getCurrency()->format($company_currency_id, $tax);?></strong></td>
		  </tr>
		 <?php 
		 }
		 ?>
		 <tr>
		   <td colspan="6" ><strong>Tổng cộng</strong></td>
		  <td align="right" >
			<strong><?php echo $appSession->getCurrency()->format($company_currency_id, $total);?></strong></td>
		  </tr>
		  <tr>
		   <td colspan="7" ><hr></td>
		   </tr>
		 <tr>
		  <tr>
		   <td colspan="6" ><strong>Thanh toán</strong></td>
		  <td align="right" >    </td>
		  </tr>
		  <?php
		  for($i =0; $i<$dt_payment->getRowCount(); $i++)
		  {
			$payment_name = $dt_payment->getString($i, "payment_name");
			$currency_id = $dt_payment->getString($i, "currency_id");
			$amount = $dt_payment->getFloat($i, "amount");
		?>
		<tr>
		   <td colspan="6" ><strong><?php echo $payment_name;?></strong></td>
		  <td align="right" >
			<strong><?php echo $appSession->getCurrency()->format($currency_id, $amount);?></strong></td>
		  </tr>
		<?php
		  }
		  ?>
		 <tr >
		  <td colspan="7">
		 Viết bằng chữ</strong>: <?php echo $appSession->getCurrency()->toword($company_currency_id, $total);?></i></td>
		  </tr>
		</table></td>
		</tr>
		<?php
		if($KHHDon != "")
		{
		?>
		<tr>
		<td align="center"><strong>THÔNG TIN HÓA ĐƠN ĐIỆN TƯ </strong></td>
		</tr>
		<tr>
		<td align="left"><strong>Người mua</strong>: <b><?php echo $NMua;?></b></td>
		</tr>
		<tr>
		  <td align="left"><strong>Tên đơn vị</strong>: <?php echo $customer_name;?></td>
		</tr>
		<tr>
		  <td align="left"><strong>Mã số thuế</strong>: <?php echo $customer_vat;?></td>
		</tr>
		<tr>
		  <td align="left"><strong>Địa chỉ</strong>: <?php echo $customer_address;?></td>
		</tr>
		<tr>
		<td align="left"><strong>Ký hiệu</strong>: <?php echo $KHHDon;?></td>
		</tr>

		<tr>
		<td align="left"><strong>Số HĐ</strong>: <?php echo $SHDon;?></td>
		</tr>
		<tr>
		<td align="left"><strong>Ngày lập</strong>: <?php echo $NLap;?></td>
		</tr>
		<tr>
		<td align="left"><strong>Mã CQT</strong>: <?php echo $MCCQT;?></td>
		</tr>
		<tr>
		<td align="left"><strong>Mã tra cứu</strong>: <?php echo $MTCuu;?></td>
		</tr>
		<tr>
		  <td align="left"><strong>Tra cứu tại</strong>: https://tracuuhd.smartsign.com.vn/ </td>
		</tr>
		<tr>
		  <td align="left"><em><strong>Lưu ý</strong>: Đây là hóa đơn điện tử khởi tạo từ máy tính tiền theo Nghị định số 123/2020/NĐ-CB. Anh/chị có thể sử dụng thông tin trên để hoạch toán, kê khai thuế.</em></td>
		</tr>
		<?php
		}
		?>
		<?php
		if($slogan != "")
		{
		?>
		<tr>
		<td><hr><i><?php echo $slogan;?></i></td>
		</tr>
		<?php
		}
		?>
		</table>
		</div>
		</div>
	<?php
	}
	?>
	
<?php
}else if($ac == "updateCustomer")
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
	$sql = "UPDATE sale SET write_date=NOW(), customer_id='".$customer_id."'";
	$sql = $sql." WHERE id ='".$sale_id."'";
	$msg->add("query", $sql);
	$r = $appSession->getTier()->exec($msg);
	
	echo "OK";
}
	
?>
