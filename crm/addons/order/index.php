<?php
validUser($appSession);
$search = "";
if (isset($_SESSION['order_search'])) {
	$search = $_SESSION['order_search'];
}
$fdate = "";
if (isset($_SESSION['order_fdate'])) {
	$fdate = $_SESSION['order_fdate'];
}
$tdate = "";
if (isset($_SESSION['order_tdate'])) {
	$tdate = $_SESSION['order_tdate'];
}
$status = "";
if (isset($_SESSION['order_status'])) {
	$status = $_SESSION['order_status'];
}


$sql = "SELECT id, name, forecolor, backcolor FROM res_status WHERE status =0 AND table_id='sale_local' ORDER BY sequence ASC";
$msg = $appSession->getTier()->createMessage();
$msg->add("query", $sql);

$status = $appSession->getTier()->getArray($msg);


?>


<div class="page_title">
	<div class="row align-items-center mx-0">
		<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 p-0">
			<div class="title_inner d-flex">

				<div id="reportrange" style="cursor: pointer; width: 100%">
					<img src="<?php echo URL; ?>assets/images/calendar.png" border="0" /></i>&nbsp;
					<span></span> <img src="<?php echo URL; ?>assets/images/menu_down.png" border="0" />
				</div>

			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 p-0 d-flex" style="vertical-align: top;">
			<form class="search_box col-12 col-sm-12 col-md-12 col-lg-8 col-xl-7 p-0 px-lg-3 mt-3 mt-lg-0 pb-3 pb-md-0 ml-auto" onsubmit="return false">
				<div class="form-group d-flex">
					<div class="input-group-prepend" style='background-color:orange'>
						<div class="input-group-text"><i class="zmdi zmdi-search"></i></div>
					</div>
					<input type="text" class="form-control" style="border:none" placeholder="Search" value="<?php echo $search; ?>" id="editsearch" onKeyDown="if(event.keyCode == 13){loadOrders();}">

				</div>
			</form>
		</div>
	</div>
	<br>
	<div class="row align-items-center mx-0">
		<div class="col-12">
			<table border="0" cellpadding="2" cellspacing="4">

				<tr>
					<?php
					for ($i = 0; $i < count($status); $i++) {

					?>
						<td nowrap="nowrap" style="background-color:<?php echo $status[$i][3]; ?>; color:<?php echo $status[$i][2]; ?>"><input onchange="loadOrders()" type="checkbox" name="status[]" value="<?php echo $status[$i][0]; ?>"></td>
						<td nowrap="nowrap" style="background-color:<?php echo $status[$i][3]; ?>; color:<?php echo $status[$i][2]; ?>"><?php echo $status[$i][1]; ?>&nbsp; </td>
						<td>&nbsp;</td>
					<?php
					}
					?>
					<td nowrap="nowrap"><input onchange="loadOrders()" type="checkbox" id="editcancel_order">Hủy đơn</td>

				</tr>
			</table>
		</div>
	</div>
</div>
<div id="pnOrders">
</div>

<script>
	var fdate = "<?php echo $fdate; ?>";
	var tdate = "<?php echo $tdate; ?>";
	$(document).ready(function() {
		var start = moment();
		var end = moment();

		function cb(start, end) {
			if (start.toString().indexOf("date") == -1) {
				$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
				fdate = start.format('YYYY-MM-D') + ' 00:00:00.000';
				tdate = end.format('YYYY-MM-D') + ' 23:59:59.999';
			} else {
				$('#reportrange span').html(null);
				fdate = "";
				tdate = "";
			}

			loadOrders();
		}

		$('#reportrange').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
				'All': [null, null],
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

	function loadOrders() {
		var search = document.getElementById('editsearch').value;

		var _url = '<?php echo URL; ?>addons/order/list/?search=' + encodeURIComponent(search);
		var cancel_order = 0;
		if (document.getElementById('editcancel_order').checked) {
			cancel_order = 1;
		}
		var x = document.getElementsByName('status[]');
		var status = '';
		for (var i = 0; i < x.length; i++) {
			if (x[i].checked) {
				if (status != '') {
					status = status + ",";
				}
				status = status + x[i].value;
			}

		}
		_url = _url + "&cancel_order=" + encodeURIComponent(cancel_order);
		_url = _url + "&status=" + encodeURIComponent(status);
		_url = _url + "&fdate=" + encodeURIComponent(fdate);
		_url = _url + "&tdate=" + encodeURIComponent(tdate);

		loadPage('pnOrders', _url, function(status, message) {
			if (status == 0) {

			}

		}, false);
	}

	function loadSale(id) {
		var _url = '<?php echo URL; ?>addons/order/line/?sale_id=' + id;
		loadPage('contentView', _url, function(status, message) {
			if (status == 0) {

			}

		}, false);
	}

	function delOrder(id) {
		var result = confirm("Want to delete");
		if (!result) {
			return;
		}
		var _url = '<?php echo URL; ?>addons/action/?ac=delSale';
		_url = _url + '&id=' + encodeURIComponent(id);

		loadPage('contentView', _url, function(status, message) {
			if (status == 0) {
				if (message == "OK") {

					loadOrders();
				} else {
					alert(message);
				}
			}

		}, true);
	}

	function deliveryInfo(sale_id, company_id) {

		var _url = '<?php echo URL; ?>addons/order/payment/?ac=deliveryInfo&sale_id=' + sale_id;
		_url = _url + "&company_id=" + company_id
		loadPage('popupContent', _url, function(status, message) {
			if (status == 0) {
				$('#frmdialog').modal('show');
			}

		}, false);
	}
	var sale_id = '';

	function priceList(id, sale_product_id, product_id) {
		sale_id = id;
		var _url = '<?php echo URL; ?>addons/order/price_list/?product_id=' + product_id;
		_url = _url + "&sale_product_id=" + sale_product_id;

		loadPage('popupContent', _url, function(status, message) {
			if (status == 0) {
				$('#frmdialog').modal('show');
			}

		}, false);
	}

	function updatePrice(sale_product_id, price_id, unit_id, attribute_id, type_id, unit_price) {
		var _url = '<?php echo URL; ?>addons/order/price_list/?ac=save&sale_product_id=' + sale_product_id;
		_url = _url + "&price_id=" + price_id;
		_url = _url + "&unit_id=" + unit_id;
		_url = _url + "&attribute_id=" + attribute_id;
		_url = _url + "&type_id=" + type_id;
		_url = _url + "&unit_price=" + encodeURIComponent(unit_price);
		loadPage('popupContent', _url, function(status, message) {
			if (message.indexOf("OK") != -1) {
				var ctr = document.getElementById('frmdialogClose');
				if (ctr != null) {
					ctr.click();
				} else {
					$('#frmdialog').modal('hide');
				}
				loadSale(sale_id)
			} else {
				alert(message);
			}


		}, true);
	}
	loadOrders();
</script>