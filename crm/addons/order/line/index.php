<?php
validUser($appSession);



$msg = $appSession->getTier()->createMessage();


$sale_id = '';
if(isset($_REQUEST['sale_id']))
{
	$sale_id = $_REQUEST['sale_id'];
}
$sql = "SELECT d1.customer_id, d2.category_id FROM sale_local d1 LEFT OUTER JOIN customer d2 ON(d1.customer_id = d2.id) WHERE  d1.id='".$sale_id."'";

$customer_category_id = "";
$customer_id = "";
$msg->add("query", $sql);
$arr = $appSession->getTier()->getArray($msg);
if(count($arr)>0)
{
	$customer_id = $arr[0][0];
	$customer_category_id = $arr[0][1];
}

$sql = "SELECT d1.id, d1.parent_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id";
$sql = $sql." FROM product_category d1";
$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status=0)";
$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_category_name' AND lg.status =0)";
$sql= $sql." WHERE d1.company_id = 'ROOT' AND parent_id= '' AND d1.status =0";

$sql = $sql." ORDER BY d1.sequence ASC, lg.description ASC, d1.name ASC";

$msg->add("query", $sql);
$dt_category = $appSession->getTier()->getTable($msg);
$category_id = "";
for($i =0; $i<$dt_category->getRowCount(); $i++)
{
  if($dt_category->getString($i, "parent_id") != "")
  {
	$category_id = $dt_category->getString($i, "id");
	break;
  }
}					  



?>
<link rel="stylesheet" href="<?php echo URL;?>assets/css/owl.carousel.min.css">
<link rel="stylesheet" href="<?php echo URL;?>assets/css/owl.theme.default.css">
<div id="page_home">

	<div class="order_section">
		<div class="order_item_container" id="pnSaleProduct">
			
		</div>
	</div>
	<!-- Order Section End -->

	<!-- Food Item Section Start -->
	<div class="item_section mt-4 mt-md-0">
		<div class="item_section_header">
			<div class="tab_btn_container">
				<div class="nav nav-tabs owl-carousel">
					 <?php
					  for($i =0; $i<$dt_category->getRowCount(); $i++)
					  {
						  
						  
								$id = $dt_category->getString($i, "id");
								$category_code = $dt_category->getString($i, "code");
								$category_name = $dt_category->getString($i, "name");
								if ($category_name == "") {
									$category_name = $dt_category->getString($i, "name");
								}
								$document_id = $dt_category->getString($i, "document_id");
					  ?>
							  
					<div onclick="loadProducts('<?php echo $id;?>')" id="tb<?php echo $id;?>" class="tab nav-item <?php if($id == $category_id){ echo " active ";} ?> animate__animated animate__zoomIn wow" data-wow-duration=".5s" role="presentation">
						
							<h5><?php echo $category_name;?></h5>
						
					</div>
					<?php
						  }
					  
					 ?>
				</div>
			</div>
			

			<form class="search_box animate__animated animate__zoomIn wow" onsubmit="return false;">
				<div class="form-group d-flex">
					<div class="input-group-prepend">
						<div class="input-group-text"><i class="zmdi zmdi-search"></i></div>
					</div>
					<input type="search" class="form-control" placeholder="Search Items" id="editsearch_product" onKeyDown="if(event.keyCode == 13){loadCategory();}">
					<button type="button" class="btn"><a href="javascript:loadCategory()">Search</a></button>
				</div>
			</form>
		</div>
		<div class="tab-content">
			<div class="row no-gutters" id="pnProducts">

			</div>
		</div>
	</div>
</div>


<script src="<?php echo URL;?>assets/js/owl.carousel.min.js"></script>
<script>
	$('.owl-carousel').owlCarousel({
		loop: false,
		margin: 20,
		nav: false,
		dot: false,
		responsive: {
			0: {
				items: 2
			},
			600: {
				items: 4
			},
			1200: {
				items: 8
			}
		}
	});
	var category_id = ''
	function loadProducts(id)
	{
		if(category_id != '')
		{
			var ctr = document.getElementById('tb' + category_id);
			if(ctr != null)
			{
				ctr.classList.remove("active");
			}
		}
		category_id = id;
		var ctr = document.getElementById('tb' + category_id);
		if(ctr != null)
		{
			ctr.classList.add("active");
		}
			
		var search= document.getElementById('editsearch_product').value;
		var _url = '<?php echo URL;?>addons/order/line_product/?search=' + encodeURIComponent(search);
		_url = _url + "&sale_id=<?php echo $sale_id;?>&category_id=" + id;
		_url = _url + "&customer_category_id=<?php echo $customer_category_id;?>";
		_url = _url + "&customer_id=<?php echo $customer_id;?>";
		loadPage('pnProducts', _url, function(status, message)
		{
			if(status== 0)
			{
				
			}
			
		}, false);
	}
	<?php 
	if($category_id != "")
	{
	?>
	loadProducts('<?php echo $category_id;?>');
	<?php
	}
	?>
	function loadCategory()
	{
		loadProducts(category_id);
	}
	function statusChanged()
	{
		var ctr = document.getElementById('editstatus_id');
		if(ctr.value == '')
		{
			alert("Select status");
			ctr.focus();
			return;
		}
		var status_id = ctr.value;
		
		var _url = '<?php echo URL;?>api/sale_action/?ac=sale_status&status_id=' + status_id;
		_url = _url + "&sale_id=<?php echo $sale_id;?>";
		_url = _url + "&company_id=e741709c-6309-4704-a6dc-e339a8b4bf7f";
	
		loadPage('pnProducts', _url, function(status, message)
		{
			if(status== 0)
			{
				if(message == "OK")
				{
					loadModule('order');
				}
			}
			
		}, true);
	}
	function loadSaleProducts()
	{
		var _url = '<?php echo URL;?>addons/order/sale_product/?sale_id=<?php echo $sale_id;?>';
		loadPage('pnSaleProduct', _url, function(status, message)
		{
			if(status== 0)
			{
				
			}
			
		}, false);
	}
	loadSaleProducts();
	
	function quantityChanged(product_id, unit_id, attribute_id, type_id, rel_id, quantity, currency_id, unit_price)
	{
		var _url = '<?php echo URL;?>api/product/?ac=add_product_to_sale';
		_url = _url + "&product_id=" + product_id;
		_url = _url + "&unit_id=" + unit_id;
		_url = _url + "&attribute_id=" + attribute_id;
		_url = _url + "&type_id=" + type_id;
		_url = _url + "&rel_id=" + rel_id;
		_url = _url + "&quantity=" + quantity;
		_url = _url + "&currency_id=" + currency_id;
		_url = _url + "&unit_price=" + unit_price;
		_url = _url + "&sale_id=<?php echo $sale_id;?>";
		
	
		loadPage('pnProducts', _url, function(status, message)
		{
			if(status== 0)
			{
				loadSaleProducts();
				loadProducts(category_id);
			}
			
		}, true);
	}
	function updateQuantity(product_id, unit_id, attribute_id, type_id, rel_id, quantity, currency_id, unit_price){
		var qty = prompt("Please enter your quantity", quantity);

		if (qty == null || qty == null) {
			return;
		} 
		if(numeric(qty) == false)
		{
			return;
		}
		
		quantityChanged(product_id, unit_id, attribute_id, type_id, rel_id, qty, currency_id, unit_price)
	}
	function closeBill(sale_id, oncompleted)
	{
		var result = confirm("Are you sure to close?");
		if (!result) {
			return;
		}
		var _url = '<?php echo URL;?>api/sale_action/?ac=close_bill&sale_id=' + sale_id;
		_url = _url + "&company_id=<?php echo $appSession->getConfig()->getProperty("company_id");?>";
		
		loadPage('pnProducts', _url, function(status, message)
		{
			if(status== 0)
			{
				if(oncompleted != null)
				{
					oncompleted(message);
				}
			}
			
		}, true);
	}
	function doneCloseBill(message)
	{
		if(message == "OK")
		{
			loadModule('order');
		}else
		{
			alert(message);
		}
	}
	function onStatus(sale_id, status_id, oncompleted)
	{
		var _url = '<?php echo URL;?>api/sale_action/?ac=sale_status&sale_id=' + sale_id;
		_url = _url + "&status_id=" + status_id;
		
		loadPage('pnProducts', _url, function(status, message)
		{
			if(status== 0)
			{
				if(oncompleted != null)
				{
					oncompleted(message);
				}
			}
			
		}, true);
	}
	function createAccountInvoice(sale_id)
	{
		var _url = '<?php echo URL;?>addons/order/account_invoice/?ac=view&sale_id=' + sale_id;
		loadPage('popupContent', _url, function(status, message)
		{
			if(status== 0)
			{
				$('#frmdialog').modal('show');
			}
			
		}, false);
	}
	function addAccountInvoice(rel_id, partner_id, category_id, status_id, payment_term_id, currency_id, amount, receipt_no, receipt_date, origin_no, origin_date, description, oncompleted)
	{
		var _url = '<?php echo URL;?>api/sale_action/?ac=createAccountInvoice&rel_id=' + rel_id;
		_url = _url + "&partner_id=" + partner_id;
		_url = _url + "&status_id=" + status_id;
		_url = _url + "&currency_id=" + currency_id;
		_url = _url + "&category_id=" + category_id;
		_url = _url + "&payment_term_id=" + encodeURIComponent(payment_term_id);
		_url = _url + "&amount=" + encodeURIComponent(amount);
		_url = _url + "&description=" + encodeURIComponent(description);
		_url = _url + "&receipt_no=" + encodeURIComponent(receipt_no);
		_url = _url + "&receipt_date=" + encodeURIComponent(receipt_date);
		_url = _url + "&origin_no=" + encodeURIComponent(origin_no);
		_url = _url + "&origin_date=" + encodeURIComponent(origin_date);
		
		
		loadPage('pnProducts', _url, function(status, message)
		{
			if(status== 0)
			{
				if(oncompleted != null)
				{
					oncompleted(message);
				}
			}
			
		}, true);
	}
	function createPayment(sale_id)
	{
		var _url = '<?php echo URL;?>addons/order/payment/?ac=view&sale_id=' + sale_id;
		loadPage('popupContent', _url, function(status, message)
		{
			if(status== 0)
			{
				$('#frmdialog').modal('show');
			}
			
		}, false);
	}
	function addPayment(rel_id, payment_id, company_id, currency_id, amount, description, oncompleted)
	{
		var _url = '<?php echo URL;?>api/sale_action/?ac=addPayment&rel_id=' + rel_id;
		_url = _url + "&rel_id=" + rel_id;
		_url = _url + "&payment_id=" + payment_id;
		_url = _url + "&company_id=" + company_id;
		_url = _url + "&currency_id=" + currency_id;
		_url = _url + "&amount=" + encodeURIComponent(amount);
		_url = _url + "&description=" + encodeURIComponent(description);
		
		loadPage('pnProducts', _url, function(status, message)
		{
			if(status== 0)
			{
				if(oncompleted != null)
				{
					oncompleted(message);
				}
			}
			
		}, true);
	}
	function updateSaleCompany(sale_id, company_id, oncompleted)
	{
		var _url = '<?php echo URL;?>api/sale_action/?ac=updateSaleCompany&sale_id=' + sale_id;
		_url = _url + "&company_id=" + company_id;
		
		loadPage('pnProducts', _url, function(status, message)
		{
			if(status== 0)
			{
				if(oncompleted != null)
				{
					oncompleted(message);
				}
			}
			
		}, true);
	}
</script>
    
