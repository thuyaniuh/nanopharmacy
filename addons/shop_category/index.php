<?php

$msg = $appSession->getTier()->createMessage();

$page_id = "category";
$table_name = "product_category";
$columns = [ "name", "parent_id"];
$searchs = ["d1.name"];

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
	$appSession->getLang()->load($appSession->getTier(), "CATEGORY", $appSession->getConfig()->getProperty("lang_id"));
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



$sql = "SELECT d1.id, d1.name FROM ".$table_name." d1 WHERE d1.status =0 AND d1.company_id='".$appSession->getConfig()->getProperty("company_id")."'";
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
			<div class="title_inner d-flex">
				<h3 class="d-flex align-items-center"><?php echo $appSession->getLang()->find("Category");?></h3>
				<button type="button" class="btn"><a href="javascript:loadView('addons/<?php echo $page_id;?>/?ac=form&id=')"><?php echo $appSession->getLang()->find("New");?></a></button>
			</div>
		</div>
		<div class="col-12  col-md-4 ">
			<form class="search_box col-12 col-sm-12 col-md-12 col-lg-8 col-xl-7 p-0 px-lg-3 mt-3 mt-lg-0 pb-3 pb-md-0 ml-auto" onsubmit="return false">
			<div class="form-group d-flex">
					<div class="input-group-prepend">
						<div class="input-group-text"><i class="zmdi zmdi-search" onclick="doSearch(0)"></i></div>
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

			<!-- Order List Start -->
			<div class="order_list">
					<div class="table-responsive">
				  <table class="table table-bordered table-hover table-sm">
					<tr class="list_header">
					<td class="text-left" nowrap="nowrap" ><?php echo $appSession->getLang()->find("Name");?></td>
		
					
					<td class="text-right Action" style="width: 5%;"><?php echo $appSession->getLang()->find("Action");?></td>
					</tr>
			
				<tbody>
                           
					<?php
					for($i =0; $i<count($values); $i++)
					{
						$id = $values[$i][0];
						$name = $values[$i][1];
						
					?>
						
									 
					<tr class="animate__animated animate__fadeInUp wow">
						<td nowrap="nowrap" class="text-left" ><?php echo $name;?></td>
					
						<td  >
						<div class="btn_container d-flex mr-0 ml-auto">
							<button type="button" class="btn" style="background: #f8f9fd; color:#009946; border-radius: 4px; margin: 0 3px;min-width: 23px; height: 23px; font-size: 1.2rem;" onclick = "loadView('addons/<?php echo $page_id;?>/?ac=form&id=<?php echo $id;?>')">
								<i class="zmdi zmdi-edit"></i>
							</button>
							<button  type="button" style="background: #f8f9fd; color:#EF1010;  border-radius: 4px; margin: 0 3px;min-width: 23px; height: 23px; font-size: 1.2rem;" class="btn" onclick="delLine('<?php echo $id; ?>')">
								<i class="zmdi zmdi-delete"></i>
							</button>
							
							</div> 
							
						</td>
					</tr>
					<?php
					}
					?>
					

				</tbody>
				</table>
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
function addNew()
{
	var _url = '<?php echo URL;?>addons/category/?ac=form';
	loadPage('popupContent', _url, function(status, message)
	{
		
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
	loadView('addons/<?php echo $page_id;?>/?search=' + encodeURIComponent(search) + "&p=" + p);
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
	$appSession->getLang()->load($appSession->getTier(), "CATEGORY", $appSession->getConfig()->getProperty("lang_id"));
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
	$vat = "";
	$id_number = "";
	
	$sql = "SELECT d1.name, d1.parent_id FROM ".$table_name." d1 WHERE d1.id ='".$id."'";
	$msg->add("query", $sql);
	$commercial_name = "";
	$parent_id = "";
	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount()>0)
	{
	
		$name = $dt->getString(0, "name");
		$parent_id = $dt->getString(0, "parent_id");
		
	}
	$sql = "SELECT d1.id, d1.name FROM product_category d1 WHERE d1.status =0 AND d1.company_id='".$appSession->getConfig()->getProperty("company_id")."' AND d1.parent_id='' ORDER BY d1.name ASC";
	$msg->add("query", $sql);
	$categoryList = $appSession->getTier()->getArray($msg);
	
	
?>
<div class="page_title">
	<div class="row align-items-center mx-0">
		<div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0">
			<div class="title_inner d-flex">
				<button type="button" class="btn" onclick="saveLine()"><?php echo $appSession->getLang()->find("Save");?></button>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 p-0 d-flex">
			
		</div>
	</div>
</div>

<form>
	<div class="col-11 mx-auto form_container">
		<div class="form-group wow">
			<label><?php echo $appSession->getLang()->find("Name");?></label>
			<input type="text" id="editname" class="form-control" value="<?php echo $name;?>">
		</div>
	</div>
	<div class="col-11 mx-auto form_container">
		<div class="form-group wow">
			<label><?php echo $appSession->getLang()->find("Category");?></label>
			<select type="text" id="editparent_id" class="form-control">
			<option value=""></option>
			<?php
			for($i =0; $i<count($categoryList); $i++)
			{
				
			?>
			<option value="<?php echo $categoryList[$i][0];?>" <?php if($parent_id == $categoryList[$i][0]){ echo " selected "; }?>><?php echo $categoryList[$i][1];?></option>
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
		alert("Please eneter name");
		ctr.focus();
		return false;
	}
	name = ctr.value;
	ctr = document.getElementById('editparent_id');
	var parent_id = ctr.value;
	
	
	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=saveLine';
	_url = _url + '&id=<?php echo $id;?>';
	_url = _url + '&parent_id=' + parent_id;
	_url = _url + '&name=' + encodeURIComponent(name);

	
	loadPage('gotoTop', _url, function(status, message)
	{
		if(status== 0)
		{
			if(message.indexOf("OK") != -1)
			{
				loadModule('<?php echo $page_id;?>');
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
	$id_number = "";
	if(isset($_REQUEST['id_number']))
	{
		$id_number = $_REQUEST['id_number'];
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
		$sql = $sql.", type";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$id."'";
		$sql = $sql.", NOW()";
		$sql = $sql.", NOW()";
		$sql = $sql.", '".$appSession->getConfig()->getProperty("company_id")."'";
		$sql = $sql.", 0";
		$sql = $sql.", 'PRODUCT_CATEGORY'";
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
	
	echo "OK";
}
?>