<?php
validUser($appSession);



$msg = $appSession->getTier()->createMessage();


$ac = '';
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
	$product_id = '';
	if(isset($_REQUEST['product_id']))
	{
		$product_id = $_REQUEST['product_id'];
	}
	$sale_product_id = '';
	if(isset($_REQUEST['sale_product_id']))
	{
		$sale_product_id = $_REQUEST['sale_product_id'];
	}
	$sql = "SELECT d1.id, d1.unit_id, d1.type_id, d1.attribute_id, d1.unit_price, d2.name AS unit_name, d3.name AS type_name, d4.name AS attribute_name FROM product_price d1 LEFT OUTER JOIN product_unit d2 ON(d1.unit_id= d2.id) LEFT OUTER JOIN product_type d3 ON(d1.type_id = d3.id) LEFT OUTER JOIN attribute d4 ON(d1.attribute_id = d4.id) WHERE d1.product_id='".$product_id."' AND d1.type='PRODUCT' AND d1.status =0";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
?>


		<table class="table">
		<tr>
			<td width="30">
			</td>
			<td >
				Unit Name
			</td>
			<td >
				Price
			</td>
			<td >
				Attribute
			</td>
			<td>
				Type Name
			</td>
		</tr>
	

	<!-- Food List Start -->

		<?php
		for($i=0; $i<$dt->getRowCount(); $i++)
		{
			$price_id = $dt->getString($i, "id");
			$unit_id = $dt->getString($i, "unit_id");
			$type_id = $dt->getString($i, "type_id");
			$attribute_id = $dt->getString($i, "attribute_id");
			$unit_price = $dt->getFloat($i, "unit_price");
			$unit_name = $dt->getString($i, "unit_name");
			$attribute_name = $dt->getString($i, "attribute_name");
			$type_name = $dt->getString($i, "type_name");
		?>
		<tr>
				<td class="col-2">
				<button class="btn" onclick="updatePrice('<?php echo $sale_product_id;?>', '<?php echo $price_id;?>', '<?php echo $unit_id;?>','<?php echo $attribute_id;?>', '<?php echo $type_id;?>', <?php echo $unit_price;?>)">+</button>
				</td>
				<td class="col-2">
				<?php echo $unit_name;?>
				</td>
				<td class="col-2">
				<?php echo $unit_price;?>
				</td>
				<td class="col-2">
				<?php echo $attribute_name;?>
				</td>
				<td class="col-2">
				<?php echo $type_name;?>
				</td>

		</tr>
		<?php
		}
		?>
	
<?php
}else if($ac == "save")
{
	$sale_product_id = '';
	if(isset($_REQUEST['sale_product_id']))
	{
		$sale_product_id = $_REQUEST['sale_product_id'];
	}
	$price_id = '';
	if(isset($_REQUEST['price_id']))
	{
		$price_id = $_REQUEST['price_id'];
	}
	$unit_id = '';
	if(isset($_REQUEST['unit_id']))
	{
		$unit_id = $_REQUEST['unit_id'];
	}
	$attribute_id = '';
	if(isset($_REQUEST['attribute_id']))
	{
		$attribute_id = $_REQUEST['attribute_id'];
	}
	$type_id = '';
	if(isset($_REQUEST['type_id']))
	{
		$type_id = $_REQUEST['type_id'];
	}
	$unit_price = '';
	if(isset($_REQUEST['unit_price']))
	{
		$unit_price = $_REQUEST['unit_price'];
	}
	$sql = "UPDATE sale_product_local SET write_date=NOW(), rel_id='".$price_id."', unit_id='".$unit_id."', attribute_id='".$attribute_id."', type_id='".$type_id."', unit_price=".$unit_price." WHERE id='".$sale_product_id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	
	echo "OK";
	
}
?>

    
