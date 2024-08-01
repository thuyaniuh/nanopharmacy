<?php
validUser($appSession);
$msg = $appSession->getTier()->createMessage();

$page_id = "stock";
$table_name = "stock";
$columns = ["receipt_no", "receipt_date", "description"];
$searchs = ["d1.receipt_no", "d1.receipt_date", "d1.description"];

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
	
$search = "";
if(isset($_REQUEST['search']))
{
	$search = $_REQUEST['search'];
}else{
	if(isset($_SESSION[$page_id."_search"]))
	{
		$search = $_SESSION[$page_id."_search"];
	}
}
$_SESSION[$page_id."_search"] = $search;

$p = 0;
if(isset($_REQUEST['p']))
{
	$p = $_REQUEST['p'];
}else{
	if(isset($_SESSION[$page_id."_page"]))
	{
		$p = $_SESSION[$page_id."_page"];
	}
}
$_SESSION[$page_id."_page"] = $p;

$ps = 30;
if(isset($_REQUEST['ps']))
{
	$ps = $_REQUEST['ps'];
}else
{
	if(isset($_SESSION[$page_id."_pages"]))
	{
		$ps = $_SESSION[$page_id."_pages"];
	}
}
$_SESSION[$page_id."_pages"] = $ps;	


$sql = "SELECT d1.id, d1.receipt_no, d1.receipt_date, d1.description FROM ".$table_name." d1 WHERE d1.status =0 AND d1.type ='STOCKOUT' AND d1.company_id='".$appSession->getConfig()->getProperty("company_id")."'";
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
$values = $appSession->getTier()->getArray($msg);



?>
<div class="page_title">
	<div class="row align-items-center mx-0">
		<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 p-0">
			<div class="title_inner d-flex">
				<h1 class="d-flex align-items-center">Customer
					<!--                            <span class="ml-4">$987.50</span>-->
				</h1>
				
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 p-0 d-flex">
			<form class="search_box col-12 col-sm-12 col-md-12 col-lg-8 col-xl-7 p-0 px-lg-3 mt-3 mt-lg-0 pb-3 pb-md-0 ml-auto" onsubmit="return false">
				<div class="form-group d-flex">
					<div class="input-group-prepend">
						<div class="input-group-text"><i class="zmdi zmdi-search"></i></div>
					</div>
					<input type="text" id="editsearch" class="form-control" placeholder="Search" value="<?php echo $search;?>" onKeyDown="if(event.keyCode == 13){doSearch(0);}">
					
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Right Sidebar Start -->
<div class="right_sidebar">
	<!-- Tab Content Start -->
	<div class="tab-content" id="nav-tabContent">

		<!-- Food Items Tab customers Start -->
		<div>
			<!-- Order List Start -->
			<div class="order_list">
				<div class="list_header d-flex">
					<h2 class="text-left Name">Name</h2>
					<h2 class="text-left phone">Phone Number</h2>
					
					<h2 class="text-left created">Description</h2>
					<h2 class="text-right Action people">Action</h2>
				</div>
				
				<ul>
					<?php
					for($i =0; $i<count($values); $i++)
					{
						$id = $values[$i][0];
						$receipt_no = $values[$i][1];
						$receipt_date = $values[$i][2];
						$description = $values[$i][3];
						
					?>
					<li class="d-flex  animate__animated animate__fadeInUp wow">
						                           
						<h3 class="text-left Name"><strong><?php echo $receipt_no;?></strong></h3>
						<h3 class="text-left phone"><?php echo $receipt_date;?></h3>
						<h3 class="text-left email"><?php echo $description;?></h3>
						
						<div class="btn_container d-flex mr-0 ml-auto">
							<button type="button" class="btn" onclick="delLine('<?php echo $id; ?>')">
								<a ><i class="zmdi zmdi-delete"></i></a>
							</button>
							<button type="button" class="btn" onclick = "loadView('addons/<?php echo $page_id;?>/?ac=form&id=<?php echo $id;?>')">
								<i class="zmdi zmdi-edit"></i>
							</button>
						</div>
					</li>
					<?php
					}
					?>
					

				</ul>
			</div>
			<!-- Order List End -->

			<!-- Tab Footer start -->
			<div class="tab_footer">
				<div class="row no-gutter align-items-center">
					<div class="col-12 col-md-12 col-lg-4 pb-3">
						<h2>Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $item_count;?> item</h2>
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
			</div>
			<!-- Tab Footer End -->
		</div>
	</div>
	<!-- Tab Content End -->
</div>
<script>
function delLine(id)
{
	var result = confirm("Want to delete");
	if (!result) {
		return;
	}
	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=delLine';
	_url = _url + '&id=' + encodeURIComponent(id);
	
	loadPage('contentView', _url, function(status, message)
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
function doSearch(p)
{
	
	var search = document.getElementById('editsearch').value;
	loadView('addons<?php echo $page_id;?>/?search=' + encodeURIComponent(search) + "&p=" + p);
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
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$code = "";
	$name = "";
	$phone = "";
	$email = "";
	$address = "";
	
	
	$sql = "SELECT d1.code, d1.name, d1.phone, d1.email, d1.address, d1.category_id FROM ".$table_name." d1 WHERE d1.id ='".$id."'";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount()>0)
	{
	
		$name = $dt->getString(0, "name");
		$phone = $dt->getString(0, "phone");
		$email = $dt->getString(0, "email");
		$address = $dt->getString(0, "address");
		$category_id = $dt->getString(0, "category_id");
	}
	$sql = "SELECT id, name FROM customer_category WHERE status =0 ORDER BY name ASC";
	$msg->add("query", $sql);
	$categories = $appSession->getTier()->getArray($msg);
	
?>
<div class="page_title">
	<div class="row align-items-center mx-0">
		<div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0">
			<div class="title_inner d-flex">
				<button type="button" class="btn" onclick="saveLine()">Save</button>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 p-0 d-flex">
			
		</div>
	</div>
</div>

<form class="pt-2 pt-sm-2 pt-md-4 ">
	<div class="col-11 mx-auto form_container">
		<div class="form-group wow">
			<label>Full Name</label>
			<input type="text" id="editname" class="form-control" value="<?php echo $name;?>">
		</div>
		<div class="form-group  wow" >
			<label>Phone Number</label>
			<input type="text" id="editphone" class="form-control" value="<?php echo $phone;?>">
		</div>
		<div class="form-group wow" >
			<label>Email Address</label>
			<input type="text" id="editemail" class="form-control" value="<?php echo $email;?>">
		</div>
		<div class="form-group wow" >
			<label>Address</label>
			<input type="text" id="editaddress" class="form-control" value="<?php echo $address;?>">
		</div>
		<div class="form-group wow" >
			<label>Category</label>
			<select  id="editcategory_id" style="color:black">
			<option	value=""></option>
			<?php
			for($i =0; $i<count($categories); $i++)
			{
				$select = "";
				if($categories[$i][0] == $category_id)
				{
					$select = " selected ";
				}
			?>
			<option <?php echo $select;?>value="<?php echo $categories[$i][0];?>"><?php echo $categories[$i][1];?></option>
			<?php
			}
			?>
			</select>
		</div>

	</div>
</form>
<script>
function saveLine()
{
	var ctr = document.getElementById('editname');
	if(ctr.value == '')
	{
		alert("Please enter name");
		ctr.focus();
		return false;
	}
	
	var name = ctr.value;
	
	
	ctr = document.getElementById('editphone');
	var phone = ctr.value;

	ctr = document.getElementById('editemail');
	var email = ctr.value;
	
	ctr = document.getElementById('editaddress');
	var address = ctr.value;
	ctr = document.getElementById('editcategory_id');
	var category_id = ctr.value;
	
	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=saveLine';
	_url = _url + '&id=<?php echo $id;?>';
	_url = _url + '&name=' + encodeURIComponent(name);
	_url = _url + '&phone=' + encodeURIComponent(phone);
	_url = _url + '&email=' + encodeURIComponent(email);
	_url = _url + '&address=' + encodeURIComponent(address);
	_url = _url + '&category_id=' + encodeURIComponent(category_id);
	
	loadPage('gotoTop', _url, function(status, message)
	{
		if(status== 0)
		{
			if(message.length == 36)
			{
				loadView('<?php echo $page_id;?>/?ac=view');
			}
			else{
				alert(message);
			}
		}
		
	}, true);

}
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
		$sql = $sql.", '".COMPANY_ID."'";
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
	echo $id;
}
?>