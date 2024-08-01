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
	$sql = "SELECT d1.customer_id, d2.partner_id, d1.order_no, d1.order_date, d1.company_id FROM sale_local d1 LEFT OUTER JOIN customer d2 ON(d1.customer_id = d2.id) WHERE d1.id='".$sale_id."'";

$msg->add("query", $sql);
$arr = $appSession->getTier()->getArray($msg);
$customer_id = "";
$status_id = "";
$partner_id = "";
$receipt_no = "";
$receipt_date = "";
$origin_no = "";
$origin_date = "";
$company_id = "";
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
	$company_id = $arr[0][4];
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
	
	$sql = "SELECT SUM(d1.percent) AS percent, SUM(d1.value) AS value, d1.category_id, d1.operator, d1.sequence";
	$sql = $sql." FROM account_service_line_local d1";
	$sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 GROUP BY d1.category_id, d1.operator, d1.sequence ORDER BY d1.sequence ASC";
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
	$sql = $sql." FROM account_payment d1";
	$sql = $sql." WHERE d1.status =0 ORDER BY d1.name ASC";
	$msg->add("query", $sql);			
	$paymentList = $appSession->getTier()->getArray($msg);
	
	
	
	$sql = "SELECT d1.id, d1.code";
	$sql = $sql." FROM res_currency d1";
	$sql = $sql." WHERE d1.status =0 ORDER BY d1.code ASC";
	$msg->add("query", $sql);			
	$currencyList = $appSession->getTier()->getArray($msg);
	
	$sql = "SELECT d1.id, d1.receipt_no, d1.receipt_date, d2.name AS payment_name, d3.code AS currency_code, d1.currency_id, d1.amount, d1.description";
	$sql = $sql." FROM account_payment_line_local d1";
	$sql = $sql." LEFT OUTER JOIN account_payment d2 ON(d1.payment_id = d2.id)";
	$sql = $sql." LEFT OUTER JOIN res_currency d3 ON(d1.currency_id = d3.id)";
	$sql = $sql." WHERE (d1.rel_id='".$sale_id."' OR d1.line_id='".$sale_id."') AND d1.status =0 ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);

	$paymentLineList = $appSession->getTier()->getTable($msg);
	
	$sql = "SELECT d1.id, d1.name, d1.address";
	$sql =$sql." FROM res_company d1 WHERE d1.status =0 AND d1.type='ONLINE'";
	$sql = $sql." ORDER BY d1.name ASC";
	$msg->add("query", $sql);			
	$companyList = $appSession->getTier()->getTable($msg);
	
	$sql = "SELECT company_id FROM sale_local WHERE id='".$sale_id."'";
	
	$msg->add("query", $sql);			
	$sale_company_id = $appSession->getTier()->getValue($msg);

	
	?>
	<div class="row">
		<div class="col-2">
		Kho/Của hàng: 
		</div>
		<div class="col-8">
		<select id="editcompany_id" class="form-control">
			<option value=""></option>
			<?php
			for($i =0; $i<$companyList->getRowCount(); $i++)
			{
				$company_id = $companyList->getString($i, "id");
				$company_name = $companyList->getString($i, "name");
				$company_address = $companyList->getString($i, "address");
			?>
			<option value="<?php echo $company_id;?>" <?php if($sale_company_id == $company_id){ echo " selected ";}?>><?php echo $company_name;?> - <?php echo $company_address; ?></option>
			<?php
			}
			?>
		</select>
		</div>
		<div class="col-2">
			<button type="button" class="btn rounded-pill" onclick="saveCompany()">Lưu</button>
		</div>
	</div>
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
				for($i =0; $i<$paymentLineList->getRowCount(); $i++)
				{
					$id = $paymentLineList->getString($i, "id");
					$receipt_no = $paymentLineList->getString($i, "receipt_no");
					$receipt_date = $paymentLineList->getString($i, "receipt_date");
					if($receipt_date != "")
					{
						$receipt_date = $appSession->getFormats()->getDATE()->formatDATE($appSession->getTool()->toDateTime($receipt_date));
					}
					$payment_name = $paymentLineList->getString($i, "payment_name");
					$currency_code = $paymentLineList->getString($i, "currency_code");
					$currency_id = $paymentLineList->getString($i, "currency_id");
					$amount = $paymentLineList->getFloat($i, "amount");
					$total = $total - $amount;
					$amount = $appSession->getCurrency()->format($currency_id, $amount);
					$description = $paymentLineList->getString($i, "description");
				?>
				<tr>
					<td><?php echo $receipt_no;?></td>
					<td><?php echo $receipt_date;?></td>
					<td><?php echo $payment_name;?></td>
					<td><?php echo $currency_code;?></td>
					<td><?php echo $amount;?></td>
					<td><?php echo $description;?></td>
					<td><a href="javascript:removePayment('<?php echo $id;?>');"><img src="<?php echo URL;?>assets/images/remove.png"/></a></td>
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
		<div class="col-10">
			<select  id="editpayment_id" class="form-control" style="color:black">
				<?php
				for($i =0; $i<count($paymentList); $i++)
				{
				?>
				<option value="<?php echo $paymentList[$i][0];?>"><?php echo $paymentList[$i][1];?></option>
				<?php
				}
				?>
			</select>
		</div>
		
	</div>
	<br>
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
		<button type="button" class="btn rounded-pill" onclick="doPayment()">Thêm</button>
	
		</div>
		<div class="col-6">
			 
		</div>
	</div>
	
<script>
	function doPayment()
	{
		var ctr = document.getElementById('editpayment_id');
		var payment_id = ctr.value;
		
	
		
		ctr = document.getElementById('editcurency_id');
		var currency_id = ctr.value;
		
		ctr = document.getElementById('editamount');
		var amount = ctr.value;
		ctr = document.getElementById('editdescription');
		var description = ctr.value;
		ctr = document.getElementById('editcompany_id');
		
		addPayment('<?php echo $sale_id;?>', payment_id, '<?php echo $company_id;?>', currency_id, amount, description, function(status, message){
			var ctr = document.getElementById('frmdialogClose');
			if(ctr != null)
			{
				ctr.click();
			}
		});
		
	}
	function saveCompany()
	{
		
		var ctr = document.getElementById('editcompany_id');
		var company_id = ctr.value;
		if(company_id == "")
		{
			alert("Select location to delivery");
			ctr.focus();
			return;
		}
		updateSaleCompany('<?php echo $sale_id;?>', company_id, function(status, message){
			var ctr = document.getElementById('frmdialogClose');
			if(ctr != null)
			{
				ctr.click();
			}
		});
		
	}
	
	function removePayment(id)
	{
		var result = confirm("Want to delete?");
		if (!result) {
			return;
		}
		var _url = '<?php echo URL;?>api/sale_action/?ac=removePayment&id=' + id;
	
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
}else if($ac == "deliveryInfo")
{
	$sql = "SELECT d1.id, d1.name, d1.tel, d1.email, d1.address, d1.description, d1.start_date, d1.address_id FROM sale_shipping d1 WHERE d1.sale_id='".$sale_id."'";

	$msg->add("query", $sql);
	$arr = $appSession->getTier()->getArray($msg);
	$id = "";
	$name = "";
	$tel = "";
	$email = "";
	$delivery_address = "";
	$description = "";
	$start_date = "";
	$delivery_address_id = "";
	$company_id = '';
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	if(count($arr)>0)
	{
		$id = $arr[0][0];
		$name = $arr[0][1];
		
		$tel = $arr[0][2];
		$email = $arr[0][3];
		$delivery_address = $arr[0][4];
		$description = $arr[0][5];
		$start_date = $arr[0][6];
		
		$start_date = str_replace(" ", "T", $start_date);
		$delivery_address_id = $arr[0][7];
		
	}
	$city_address_id = "";
	$dist_address_id = "";
	$ward_address_id = "";
	if($delivery_address_id != "")
	{
		while(true)
		{
			$sql = "SELECT parent_id, type FROM res_address WHERE id='".$delivery_address_id."'";
			$msg->add("query", $sql);
			
			$address = $appSession->getTier()->getArray($msg);
			if(count($address) == 0)
			{
				break;
			}
			if($address[0][1]== "WARD")
			{
				$ward_address_id = $delivery_address_id;
			}else if($address[0][1]== "DIST")
			{
				$dist_address_id = $delivery_address_id;
			}else if($address[0][1]== "CITY")
			{
				$city_address_id = $delivery_address_id;
			}
			$delivery_address_id =$address[0][0];
		}
	}

	$sql = "SELECT d1.id, d1.name";
	$sql = $sql." FROM res_address d1";
	$sql = $sql." WHERE d1.type='CITY' AND d1.status =0";
	$sql = $sql." ORDER BY d1.name ASC";
		
	$msg->add("query", $sql);
	$addresCity = $appSession->getTier()->getArray($msg);

	?>
	<div class="row">
		<div class="col-2">
			Người nhận: 
		</div>
		<div class="col-10">
			<input class="form-control" id="editname" value="<?php echo $name;?>">
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-2">
			Điện thoại: 
		</div>
		<div class="col-4">
			<input class="form-control" id="edittel" value="<?php echo $tel;?>">
		</div>
		<div class="col-2">
			Thời gian: 
		</div>
		<div class="col-4">
			<input class="form-control" id="editstart_date" type="datetime-local" value="<?php echo $start_date;?>">
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-lg-6">
			<div class="input-item">
				<label><?php echo $appSession->getLang()->find("City");?>*</label>
				<select id="editaddress_city" class="form-control" onchange="cityChanged(this)"
				   style="width: 100%;">
				   <option value=""><?php echo $appSession->getLang()->find("Select city/province");?></option>
				   <?php
				   for($i =0; $i<count($addresCity); $i++)
				   {
				  ?>
				  <option value="<?php echo $addresCity[$i][0];?>"><?php echo $addresCity[$i][1];?></option>
				  <?php
				   }
				   ?>
				   </select>
				   
			</div>
		</div>
		<div class="col-lg-6">
			<div class="input-item">
				<label><?php echo $appSession->getLang()->find("District");?>*</label>
				<select id="editaddress_dist" class="form-control" onchange="distChanged(this)"
				   style="width: 100%;"></select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="input-item">
				<label><?php echo $appSession->getLang()->find("Ward");?>*</label>
				<select id="editaddress_ward" class="form-control" onchange="wardChanged(this)"
				   style="width: 100%;"></select>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-2">
			Địa chỉ giao: 
		</div>
		<div class="col-10">
			<textarea class="form-control" id="editaddress"><?php echo $delivery_address;?></textarea>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-2">
			Nội dung: 
		</div>
		<div class="col-10">
			<textarea class="form-control" id="editdescription"><?php echo $description;?></textarea>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-2">
			 
		</div>
		<div class="col-4">
		<button type="button" class="btn rounded-pill" onclick="doSaveShipping()">Lưu</button>
	
		</div>
		<div class="col-6">
			 
		</div>
	</div>
	<script>
	
	var city_address_id = '<?php echo $city_address_id;?>';
	var dist_address_id = '<?php echo $dist_address_id;?>';
	var ward_address_id = '<?php echo $ward_address_id;?>';


	function doSaveShipping()
	{
		var ctr = document.getElementById('editname');
		var name = ctr.value;
		
	
		var address_id = "";
		if(ward_address_id != "")
		{
			address_id = ward_address_id;
		}
		if(address_id == "" && dist_address_id != "")
		{
			address_id = dist_address_id;
		}
		if(address_id == "" && city_address_id != "")
		{
			address_id = city_address_id;
		}
	
		ctr = document.getElementById('edittel');
		var tel = ctr.value;
		
		ctr = document.getElementById('editstart_date');
		var start_date = ctr.value;
		start_date = start_date.replace("T", " ");
		start_date = start_date.replace("Z", "");
		ctr = document.getElementById('editdescription');
		var description = ctr.value;
		ctr = document.getElementById('editaddress');
		var address = ctr.value;
		var _url = '<?php echo URL;?>addons/order/payment/?ac=doSaveShipping&id=<?php echo $id;?>';
		_url = _url + "&name=" + encodeURIComponent(name);
		_url = _url + "&tel=" + encodeURIComponent(tel);
		_url = _url + "&address=" + encodeURIComponent(address);
		_url = _url + "&description=" + encodeURIComponent(description);
		_url = _url + "&start_date=" + encodeURIComponent(start_date);
		_url = _url + "&address_id=" + encodeURIComponent(address_id);
		_url = _url + "&sale_id=<?php echo $sale_id;?>";
		_url = _url + "&company_id=<?php echo $company_id;?>";
		
		loadPage('popupContent', _url, function(status, message)
		{
			if(status== 0 && message.indexOf("OK") != -1)
			{
				var ctr = document.getElementById('frmdialogClose');
				if(ctr != null)
				{
					ctr.click();
				}
				loadOrders();
			}
			
		}, true);
	
		
	}
	function cityChanged(theSelect)
	{
		city_address_id = theSelect.value;
		
		var params = "ac=res_address";
		params = params + "&parent_id=" + city_address_id;
		var _url = "<?php echo URL;?>addons/order/payment/?" + params;
		var cb = document.getElementById('editaddress_ward');
		if(cb != null)
		{
			while (cb.options.length > 0) 
			{                
				cb.remove(0);
			} 
		}
		var cb = document.getElementById('editaddress_dist');
		
		if(cb == null)
		{
			return;
		}
		while (cb.options.length > 0) 
		{                
				cb.remove(0);
		} 
		
		loadPage('contentView', _url, function(status, message) {
			if (status == 0) {
				var op = document.createElement( 'option' );
				op.value = "";
				op.text = '<?php echo $appSession->getLang()->find("Select district");?>';
				cb.options.add(op);
				var arr = message.split('\n');
				
				for(i =0; i<arr.length; i++)
				{
					
					var index = arr[i].indexOf('=');
					if(index != -1)
					{
						var id =  arr[i].substring(0, index);
						var name = arr[i].substring(index + 1);
						var op = document.createElement( 'option' );
						op.value = id;
						op.text = name;
						cb.options.add(op);
						if(dist_address_id == id)
						{
							cb.selectedIndex = i + 1;
						}
					}
				}
				if(dist_address_id != "")
				{
					distChanged(cb);
				}
			} else {
				console.log(message);
			}

		}, true); 
	}

	function distChanged(theSelect)
	{
		dist_address_id = theSelect.value;
		
		var params = "ac=res_address";
		params = params + "&parent_id=" + dist_address_id;
		var _url = "<?php echo URL;?>addons/order/payment/?" + params;
		var cb = document.getElementById('editaddress_ward');

		if(cb == null)
		{
			return;
		}
		while (cb.options.length > 0) 
		{                
				cb.remove(0);
		} 
		
		loadPage('contentView', _url, function(status, message) {
			if (status == 0) {
				var op = document.createElement( 'option' );
				op.value = "";
				op.text = '<?php echo $appSession->getLang()->find("Select ward");?>';
				cb.options.add(op);
				var arr = message.split('\n');
				
				for(i =0; i<arr.length; i++)
				{
					
					var index = arr[i].indexOf('=');
					if(index != -1)
					{
						var id =  arr[i].substring(0, index);
						var name = arr[i].substring(index + 1);
						var op = document.createElement( 'option' );
						op.value = id;
						op.text = name;
						cb.options.add(op);
						if(ward_address_id == id)
						{
							cb.selectedIndex = i + 1;
						}
					}
				}
				if(ward_address_id != "")
				{
					wardChanged(cb);
				}
			} else {
				console.log(message);
			}

		}, true); 
	}
	function wardChanged(theSelect)
	{
		ward_address_id = theSelect.value;
	}
	if(city_address_id != "")
	{
		var cb = document.getElementById('editaddress_city');
		for(var i=0; i<cb.options.length; i++)
		{
			if(cb.options[i].value == city_address_id)
			{
				cb.options[i];
				cb.selectedIndex = i;
				cityChanged(cb);
				break;
			}
		}
	}

	<?php
}else if($ac == "doSaveShipping")
{
	$id = '';
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sale_id = '';
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$company_id = '';
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	if($id == "")
	{
		$id = $appSession->getTool()->getId();
		$sql= "INSERT INTO sale_shipping(id, status, sale_id,company_id )VALUES('".$id."', 0, '".$sale_id."', '".$company_id."')";
		$msg->add("query", $sql);			
		$r = $appSession->getTier()->exec($msg);
	}
	$name = '';
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$tel = '';
	if(isset($_REQUEST['tel']))
	{
		$tel = $_REQUEST['tel'];
	}
	$start_date = '';
	if(isset($_REQUEST['start_date']))
	{
		$start_date = $_REQUEST['start_date'];
	}
	$address = '';
	if(isset($_REQUEST['address']))
	{
		$address = $_REQUEST['address'];
	}
	$description = '';
	if(isset($_REQUEST['description']))
	{
		$description = $_REQUEST['description'];
	}
	$address_id = '';
	if(isset($_REQUEST['address_id']))
	{
		$address_id = $_REQUEST['address_id'];
	}
	
	$sql = "UPDATE sale_shipping SET write_date=NOW(), name='".$appSession->getTool()->replace($name, "'", "''")."'";
	$sql = $sql.", tel='".$appSession->getTool()->replace($tel, "'", "''")."'";
	if($start_date != "")
	{
		$sql = $sql.", start_date='".$appSession->getTool()->replace($start_date, "'", "''")."'";
	}else{
		$sql = $sql.", start_date=NOW()";
	}
	
	$sql = $sql.", description='".$appSession->getTool()->replace($description, "'", "''")."'";
	$sql = $sql.", address='".$appSession->getTool()->replace($address, "'", "''")."'";
	$sql = $sql.", address_id='".$address_id."'";
	$sql = $sql." WHERE id ='".$id."'";
	$msg->add("query", $sql);	
	
	$r = $appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "res_address")
{
	$msg = $appSession->getTier()->createMessage();
	$parent_id = "";
	if(isset($_REQUEST['parent_id']))
	{
		$parent_id = $_REQUEST['parent_id'];
	}
	if($parent_id != "")
	{
		$sql = "SELECT id, name FROM res_address WHERE parent_id='".$parent_id."' AND status =0 ORDER BY name ASC";
		$msg->add("query", $sql);
		$arr = $appSession->getTier()->getArray($msg);
		for($i=0; $i<count($arr); $i++)
		{
			if($i>0)
			{
				echo "\n";
			}
			echo $arr[$i][0]."=".$arr[$i][1];
		}
	}
	
}

?>

<!-- Tab Items End -->


		