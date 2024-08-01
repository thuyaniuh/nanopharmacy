<?php

$msg = $appSession->getTier()->createMessage();

$page_id = "product_list";
$table_name = "product";
$columns = [ "name", "category_id", "unit_id", "type", "publish"];
$searchs = ["d1.name", "d2.name"];

$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
if($ac == "")
{
	$ac = "view";
}
include(ABSPATH . 'app/lang/'.$appSession->getConfig()->getProperty("lang_id") . '.php');
	foreach ($langs as $key => $item) {
	  $appSession->getLang()->setProperty($key, $item);
	}
if($ac == "view")
{
	
	

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


$sql = "SELECT d1.id, d1.name, d2.name AS category_name, d3.name AS unit_name, d4.unit_price FROM ".$table_name." d1 LEFT OUTER JOIN product_category d2 ON(d1.category_id = d2.id)  LEFT OUTER JOIN product_unit d3 ON(d1.unit_id = d3.id) LEFT OUTER JOIN product_price d4 ON(d1.id = d4.product_id AND d4.type='PRODUCT') WHERE d1.status =0 AND d1.company_id='".$appSession->getConfig()->getProperty("company_id")."'";
if($search != "")
{
	$sql = $sql." AND (".$appSession->getTier()->buildSearch($searchs, $search).")";
}

$arr = $appSession->getTier()->paging($sql, $p, $ps, "d1.name ASC");


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
$values = $appSession->getTier()->getArray($msg);



?>
<div class="page_title">
	<div class="row align-items-center mx-0">
		<div class="col-12 col-md-8">
			<a href="javascript:editform('')"><img src="<?php echo URL;?>assets/images/add.png"/></a>
		</div>
		<div class="col-12  col-md-4 ">
			<form onsubmit="return false">
			<div class="form-group d-flex">

					<input type="text" id="editsearch" class="form-control" placeholder="Search" value="<?php echo $search;?>" onKeyDown="if(event.keyCode == 13){doSearch(0);}">
					
			</div>
			</form>
		</div>
	</div>
</div>

<div class="table-responsive">
  <table class="table">
	<tr>
	<td><?php echo $appSession->getLang()->find("Action");?></td>
	<td><?php echo $appSession->getLang()->find("Name");?></td>

	<td><?php echo $appSession->getLang()->find("Category");?></td>
	<td><?php echo $appSession->getLang()->find("Unit");?></td>
	<td><?php echo $appSession->getLang()->find("Price");?></td>
	
	</tr>

<tbody>
		   
	<?php
	for($i =0; $i<count($values); $i++)
	{
		$id = $values[$i][0];
		$name = $values[$i][1];
		$category_name = $values[$i][2];
		$unit_name = $values[$i][3];
		$unit_price = $appSession->getTool()->toDouble($values[$i][4]);
		
	?>
		
					 
	<tr>
		<td >
			<a href="javascript:editform('<?php echo $id;?>')"><img src="<?php echo URL;?>assets/images/edit.png"/></a>
			</td> 

		<td nowrap="nowrap" class="text-left" ><?php echo $name;?></td>
		<td nowrap="nowrap" class="text-left" ><?php echo $category_name;?></td>
		<td nowrap="nowrap" class="text-left" ><?php echo $unit_name;?></td>
		<td nowrap="nowrap" class="text-right" ><?php echo $appSession->getCurrency()->format("23", $unit_price);?></td>
		
		
	</tr>
	<?php
	}
	?>
	

</tbody>
</table>
</div>
<br>

<div class="row no-gutter align-items-center">
	<div class="col-12 col-md-12 col-lg-4 pb-3">
		<?php echo $start;?> - <?php echo $end;?> | <?php echo $item_count;?>
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
					<li class="page-item"><a class="page-link" href="javascript:doSearch('<?php echo $i;?>')"><?php echo ($i + 1);?></a></li>
					<?php
					}
					?>
				</ul>
			</nav>

		</div>
	</div>
</div>
<script>

function editform(id)
{
	var _url = '<?php echo URL;?>addons/product_list/?ac=form&id=' +id;
	
	loadPage('pnProduct', _url, function(status, message)
	{
		if(status== 0)
		{
			
		}
		
	}, false);
}
	
function delLine(id)
{
	var result = confirm("Want to delete");
	if (!result) {
		return;
	}
	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=delLine';
	_url = _url + '&id=' + encodeURIComponent(id);
	
	loadPage('pnProduct', _url, function(status, message)
	{
		if(status== 0)
		{
			if(message == "OK")
			{
				
				doSearch(<?php echo $p;?>);
			}
			else{
				alert(message);
			}
		}
		
	}, true);
}
var last_p =0;
function doSearch(p)
{
	last_p = 0;
	var search = document.getElementById('editsearch').value;
	loadPage('pnProduct', '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=view&search=' + encodeURIComponent(search) + "&p=" + p, function(status, message)
	{
		if(status== 0)
		{
			
		}
		
	}, false);
}
function delRows()
{
	var count = 0;
	var checkboxes = document.getElementsByName("[]");
	var ids = "";
	for (var i= 0; i<checkboxes.length; i++) {
		if(checkboxes[i].checked && checkboxes[i].value != "")
		{
			if(ids != "")
			{
				ids += ",";
			}
			ids += checkboxes[i].id;
			count += 1;
		}
	}
	if(ids == "")
	{
		alert("Please check to delete");
		return;
	}
	var result = confirm(count + ". Want to delete?");
	if (!result) {
		return;
	}
	delLine(ids);
}
</script>
<?php
}else if($ac == "form")
{
	
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	if($id == "")
	{
		$id = $appSession->getTool()->getId();
	}
	$name = "";
	$category_id = "";
	$unit_id = "";
	$unit_price = 0;
	
	
	$sql = "SELECT d1.name, d1.category_id, d1.unit_id, d2.unit_price FROM ".$table_name." d1 LEFT OUTER JOIN product_price d2 ON(d1.id = d2.product_id AND d2.status =0 AND d2.type='PRODUCT') WHERE d1.id ='".$id."'";
	$msg->add("query", $sql);

	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount()>0)
	{
	
		$name = $dt->getString(0, "name");
		$category_id = $dt->getString(0, "category_id");
		$unit_id = $dt->getString(0, "unit_id");
		$unit_price = $dt->getFloat(0, "unit_price");
	}

	
	$sql = "SELECT d1.id, d1.name FROM product_category d1 WHERE d1.status =0 AND (d1.company_id='".$appSession->getConfig()->getProperty("company_id")."' OR d1.company_id='ROOT') ORDER BY d1.name ASC";
	$msg->add("query", $sql);
	$dt_category = $appSession->getTier()->getTable($msg);
	
	$sql = "SELECT d1.id, d1.name FROM product_unit d1 WHERE d1.status =0 AND (d1.company_id='".$appSession->getConfig()->getProperty("company_id")."' OR d1.company_id='ROOT') ORDER BY d1.name ASC";
	$msg->add("query", $sql);
	$dt_unit = $appSession->getTier()->getTable($msg);
	
?>

<div class="faq-accordion">
	<div class="accordion" id="accordionExample">
		<div class="accordion-item">
			<h2 class="accordion-header" id="headingOne">
				<button class="accordion-button" type="button" data-bs-toggle="collapse"
					data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					1. Thông tin sản phẩm 
				</button>
			</h2>
			<div id="collapseOne" class="accordion-collapse collapse show"
				aria-labelledby="headingOne" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<form>
						<div class="col-11 mx-auto form_container">
							<div class="form-group wow">
								<label><?php echo $appSession->getLang()->find("Name");?></label>
								<input type="text" id="editname" class="form-control" value="<?php echo $name;?>">
							</div>
							
						</div>
						<div class="col-11 mx-auto form_container">
							<div class="row">
								<div class="col-4">
									<div class="form-group wow">
										<label><?php echo $appSession->getLang()->find("Category");?></label>
										<select type="text" id="editcategory_id" class="form-control">
											<option value=""></option>
											<?php
											for($i =0; $i<$dt_category->getRowCount(); $i++)
											{
											?>
											<option value="<?php echo $dt_category->getString($i, "id");?>" <?php if($category_id == $dt_category->getString($i, "id")){ echo " selected"; }?>><?php echo $dt_category->getString($i, "name");?></option>
											<?php
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-4">
									<div class="form-group wow">
										<label><?php echo $appSession->getLang()->find("Unit");?></label>
										<select type="text" id="editunit_id" class="form-control">
											<option value=""></option>
											<?php
											for($i =0; $i<$dt_unit->getRowCount(); $i++)
											{
											?>
											<option value="<?php echo $dt_unit->getString($i, "id");?>" <?php if($unit_id == $dt_unit->getString($i, "id")){ echo " selected"; }?>><?php echo $dt_unit->getString($i, "name");?></option>
											<?php
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-4">
									<div class="form-group wow">
										<label><?php echo $appSession->getLang()->find("Price");?></label>
										<input type="number" id="editunit_price" class="form-control" value="<?php echo $unit_price;?>">
									</div>
								</div>
							</div>
							
						</div>
						<div class="col-11 mx-auto form_container">
							<div class="form-group wow">
								<div id="pnPoster"></div>
							</div>
						</div>
						<button type="button" class="btn btn-primary" onclick="saveLine()"><?php echo $appSession->getLang()->find("Save");?></button>
					</form>
				</div>
			</div>
		</div>

		<div class="accordion-item">
			<h2 class="accordion-header" id="headingTwo">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
					data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
					2. Mô tả sản phẩm
				</button>
			</h2>
			<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
				data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<div id="pnDescription"></div>
				</div>
			</div>
		</div>

		<div class="accordion-item">
			<h2 class="accordion-header" id="headingFour">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
					data-bs-target="#collapseFour" aria-expanded="true"
					aria-controls="collapseFour">
					3. Tài liệu yêu cầu
				</button>
			</h2>
			<div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
				data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<p><div id="pnDocument"></div></p>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
function saveLine()
{
	var ctr = document.getElementById('editname');
	if(ctr.value == '')
	{
		alert("Nhập tên");
		ctr.focus();
		return false;
	}
	
	var name = ctr.value;
	
	var ctr = document.getElementById('editcategory_id');
	if(ctr.value == '')
	{
		alert("Chọn danh mục");
		ctr.focus();
		return false;
	}
	
	var category_id = ctr.value;
	var ctr = document.getElementById('editunit_id');
	var unit_id = ctr.value;
	var ctr = document.getElementById('editunit_price');
	if(numeric(ctr.value) == false)
	{
		alert("Đơn giá không hợp lệ");
		ctr.focus();
		return false;
	}
	var unit_price = parseFloat(ctr.value);
	if(unit_price<0)
	{
		alert("Đơn giá không được nhỏ hơn 0");
		ctr.focus();
	}
	
	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=saveLine';
	_url = _url + '&id=<?php echo $id;?>&type=PRODUCT&publish=1';
	_url = _url + '&name=' + encodeURIComponent(name);
	_url = _url + '&category_id=' + encodeURIComponent(category_id);
	_url = _url + '&unit_id=' + encodeURIComponent(unit_id);
	_url = _url + '&unit_price=' + encodeURIComponent(unit_price);
	
	loadPage('gotoTop', _url, function(status, message)
	{
		if(status== 0)
		{
			if(message.indexOf("OK") != -1)
			{
				doSearch(last_p);
			}
			else{
				alert(message);
			}
		}
		
	}, true);

}
var _url = '<?php echo URL;?>addons/poster/?rel_id=<?php echo $id;?>';
loadPage('pnPoster', _url, function(status, message)
{
	if(status== 0)
	{
		var _url = '<?php echo URL;?>addons/document/?rel_id=<?php echo $id;?>';
		loadPage('pnDocument', _url, function(status, message)
		{
			if(status== 0)
			{
				var _url = '<?php echo URL;?>addons/product_post/?rel_id=<?php echo $id;?>';
				loadPage('pnDescription', _url, function(status, message)
				{
					if(status== 0)
					{
						
					}
					
				}, false);
			}
			
		}, false);
	}
	
}, false);


</script>
<?php
}else if($ac == "delLine")
{
	$msg = $appSession->getTier()->createMessage();
	$id = '';
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$items = explode(",", $id);
	for($i =0; $i<count($items); $i++)
	{
		$id = $items[$i];
		$sql = "UPDATE ".$table_name." SET status =1";
		$sql = $sql.", write_date=NOW()";
		$sql = $sql." WHERE id='".$id."'";
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}
	echo "OK";
}else if($ac == "saveLine")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$unit_price = 0;
	if(isset($_REQUEST['unit_price']))
	{
		$unit_price = $_REQUEST['unit_price'];
	}
	$unit_price = round($unit_price, 0);
	$unit_id = "";
	if(isset($_REQUEST['unit_id']))
	{
		$unit_id = $_REQUEST['unit_id'];
	}
	if($id == "")
	{
		$id = $appSession->getTool()->getId();
	}
	$sql = "SELECT id FROM ".$table_name." WHERE id='".$id."'";
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	if(count($values) == 0)
	{
		$sql = "INSERT INTO ".$table_name."(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", company_id";
		$sql = $sql.", status";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$id."'";
		$sql = $sql.", NOW()";
		$sql = $sql.", NOW()";
		$sql = $sql.", '".$appSession->getConfig()->getProperty("company_id")."'";
		$sql = $sql.", 0";
		$sql = $sql.")";
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}
	$sql = "UPDATE ".$table_name." SET write_date=NOW()";
	for($i = 0; $i<count($columns); $i++)
	{
		$name = $columns[$i];
		$value = "";
		if(isset($_REQUEST[$name]))
		{
			$value = $_REQUEST[$name];
		}
		$sql = $sql.", ".$name."='".str_replace("'", "''", $value)."'";
	}
	$sql = $sql." WHERE id ='".$id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	
	$sql = "SELECT id FROM product_price WHERE product_id='".$id."' AND type='PRODUCT' AND status =0";
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	$price_id = "";
	if(count($values) == 0)
	{
		$price_id = $appSession->getTool()->getId();
		$sql = "INSERT INTO product_price(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", company_id";
		$sql = $sql.", product_id";
		$sql = $sql.", currency_id";
		$sql = $sql.", type";
		$sql = $sql.", status";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$price_id."'";
		$sql = $sql.", NOW()";
		$sql = $sql.", NOW()";
		$sql = $sql.", '".$appSession->getConfig()->getProperty("company_id")."'";
		$sql = $sql.", '".$id."'";
		$sql = $sql.", '23'";
		$sql = $sql.", 'PRODUCT'";
		$sql = $sql.", 0";
		$sql = $sql.")";
		echo $sql;
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}else{
		$price_id = $values[0][0];
	}
	$sql = "UPDATE product_price SET write_date=NOW(), unit_price=".$unit_price.", unit_id='".$unit_id."' WHERE id='".$price_id."'";
	$msg->add("query", $sql);
	 $appSession->getTier()->exec($msg);
	echo "OK";
}
?>