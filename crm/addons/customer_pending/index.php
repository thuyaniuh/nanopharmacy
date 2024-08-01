<?php
require_once(ABSPATH . 'api/LineDepartment.php');


validUser($appSession);
$msg = $appSession->getTier()->createMessage();


$page_id = "customer_pending";
$table_name = "customer";
$columns = ["code", "name", "phone", "email", "address", "category_id", "vat"];
$searchs = ["d1.name", "d1.phone", "d1.email", "d1.address", "d1.vat", "d2.description"];

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
	
	$appSession->getLang()->load($appSession->getTier(), "CUSTOMER", $appSession->getConfig()->getProperty("lang_id"));
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




$sql = "SELECT d1.id, d1.code, d1.name, d1.phone, d1.email, d1.address, d1.vat";
$sql = $sql.", d3.code AS employee_code, d3.name AS employee_name";
$sql = $sql." FROM ".$table_name." d1 ";
$sql = $sql." LEFT OUTER JOIN hr_employee_rel d2 ON(d1.id = d2.rel_id AND d2.status =0)";
$sql = $sql." LEFT OUTER JOIN hr_employee d3 ON(d2.employee_id = d3.id)";
$sql= $sql." WHERE d1.status =0 AND d1.company_id='".$appSession->getConfig()->getProperty("company_id")."' AND d2.id IS NULL";


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
$dt = $appSession->getTier()->getTable($msg);


?>
<div class="page_title">
	<div class="row align-items-center mx-0">
		<div class="col-12 col-md-6">
			<div class="title_inner d-flex">
				<h3 class="d-flex align-items-center"><?php echo $appSession->getLang()->find("Customer");?></h3>
				<button type="button" class="btn"><a href="javascript:loadModule('customer')"><?php echo $appSession->getLang()->find("Back");?></a></button>
				
			</div>
		</div>
		<div class="col-12  col-md-6 ">
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
					<td class="text-left" nowrap="nowrap" ><?php echo $appSession->getLang()->find("Code");?></td>
					<td class="text-left " ><?php echo $appSession->getLang()->find("Name");?></td>
					
					<td class="text-left " ><?php echo $appSession->getLang()->find("Phone");?></td>
					<td class="text-left " ><?php echo $appSession->getLang()->find("Email");?></td>
					<td class="text-left " ><?php echo $appSession->getLang()->find("Vat");?></td>
					<td class="text-left " ><?php echo $appSession->getLang()->find("Address");?></td>
					
					
					<td class="text-right Action" style="width: 5%;"><?php echo $appSession->getLang()->find("Action");?></td>
					</tr>
			
				<tbody>
                           
					<?php
					for($i =0; $i<$dt->getRowCount(); $i++)
					{
						$id = $dt->getString($i, "id");
						$code = $dt->getString($i, "code");
						$name = $dt->getString($i, "name");
						$phone = $dt->getString($i, "phone");
						$email = $dt->getString($i, "email");
						$address = $dt->getString($i, "address");
						$vat = $dt->getString($i, "vat");
						
					?>
						
									 
					<tr class="animate__animated animate__fadeInUp wow">
						<td nowrap="nowrap" class="text-left" ><?php echo $code;?></td>
						<td nowrap="nowrap" class="text-left" ><?php echo $name;?></td>
						<td  nowrap="nowrap" class="text-left " ><?php echo $phone;?></td>
				
						<td nowrap="nowrap" class="text-left " ><?php echo $email;?></td>
						<td nowrap="nowrap" class="text-left " ><?php echo $vat;?></td>
						<td nowrap="nowrap" class="text-left " ><?php echo $address;?></td>
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
	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=form';
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
	$appSession->getLang()->load($appSession->getTier(), "CUSTOMER", $appSession->getConfig()->getProperty("lang_id"));
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	if($id == "")
	{
		$id= $appSession->getTool()->getId();
	}
	$code = "";
	$name = "";
	$phone = "";
	$email = "";
	$address = "";
	$vat = "";
	$id_number = "";
	$category_id = "";
	$sql = "SELECT d1.code, d1.name, d1.phone, d1.vat, d1.email, d1.address, d1.category_id, d2.description AS id_number, d1.commercial_name FROM ".$table_name." d1 LEFT OUTER JOIN res_meta d2 ON(d1.id = d2.rel_id AND d2.type='IDNUMBER') WHERE d1.id ='".$id."'";
	
	$msg->add("query", $sql);
	$commercial_name = "";
	$dt = $appSession->getTier()->getTable($msg);
	if($dt->getRowCount()>0)
	{
		$code = $dt->getString(0, "code");
		$name = $dt->getString(0, "name");
		$phone = $dt->getString(0, "phone");
		$email = $dt->getString(0, "email");
		$address = $dt->getString(0, "address");
		$category_id = $dt->getString(0, "category_id");
		$vat = $dt->getString(0, "vat");
		$id_number = $dt->getString(0, "id_number");
		$commercial_name = $dt->getString(0, "commercial_name");
	}
	$sql = "SELECT id, name FROM customer_category WHERE status =0 AND (company_id='".$appSession->getConfig()->getProperty("company_id")."' OR company_id='".$appSession->getConfig()->getProperty("parent_company_id")."') ORDER BY name ASC";
	$msg->add("query", $sql);
	$categories = $appSession->getTier()->getArray($msg);
	
?>
<div class="page_title">
	<div class="row align-items-center mx-0">
		<div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0">
			<div class="title_inner d-flex">
				<button type="button" class="btn" onclick="saveLine()"><?php echo $appSession->getLang()->find("Save");?></button>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 p-0 d-flex">
			<button type="button" class="btn btn-outline-primary" onclick="loadModule('customer_pending')"><?php echo $appSession->getLang()->find("Back");?></button>
		</div>
	</div>
</div>

<form>
	<div class="col-11 mx-auto form_container">
		<div class="form-group wow">
			<label><?php echo $appSession->getLang()->find("Code");?></label>
			<input type="text" id="editcode" class="form-control" value="<?php echo $code;?>">
		</div>
		<div class="form-group wow">
			<label><?php echo $appSession->getLang()->find("Name");?></label>
			<input type="text" id="editname" class="form-control" value="<?php echo $name;?>">
		</div>
		
		<div class="form-group  wow" >
			<label><?php echo $appSession->getLang()->find("Phone");?></label>
			<input type="text" id="editphone" class="form-control" value="<?php echo $phone;?>">
		</div>
		<div class="form-group wow" >
			<label><?php echo $appSession->getLang()->find("Email");?></label>
			<input type="text" id="editemail" class="form-control" value="<?php echo $email;?>">
		</div>
		<div class="form-group wow" >
			<label><?php echo $appSession->getLang()->find("Tax Code");?></label>
			<input type="text" id="editvat" class="form-control" value="<?php echo $vat;?>">
		</div>
		
		
		<div class="form-group wow" >
			<label><?php echo $appSession->getLang()->find("Address");?></label>
			<input type="text" id="editaddress" class="form-control" value="<?php echo $address;?>">
		</div>
		<div class="form-group wow" >
			<label><?php echo $appSession->getLang()->find("Category");?></label>
			<select  id="editcategory_id" class="form-control">
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
		<div class="form-group wow" >
			<label><?php echo $appSession->getLang()->find("Saleman");?></label>
			<a href="javascript:selEmployee()"><img src="<?php echo URL;?>assets/images/add.png"/></a>
			<div id="pnSaleman"></div>
		</div>

	</div>
</form>
<script>
function saveLine()
{
	var ctr = document.getElementById('editcode');
	
	var code = ctr.value;
	var name = document.getElementById('editname').value;
	if(name == '')
	{
		alert('<?php echo $appSession->getLang()->find("Please enter name");?>');
		return;
	}
	
	ctr = document.getElementById('editphone');
	if(ctr.value != '')
	{
		if(ctr.value.length<7){
			alert('<?php echo $appSession->getLang()->find("Invalid phone number");?>');
			ctr.focus();
			return;
		}
	}
	var phone = ctr.value;

	ctr = document.getElementById('editemail');
	if(ctr.value != "" && validate_email(ctr.value) == false)
	{
		alert('<?php echo $appSession->getLang()->find("Invalid email");?>');
		ctr.focus();
		return;

	}
	var email = ctr.value;
	
	ctr = document.getElementById('editaddress');
	
	var address = ctr.value;
	ctr = document.getElementById('editcategory_id');
	var category_id = ctr.value;
	ctr = document.getElementById('editvat');
	var vat = ctr.value;

	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=saveLine';
	_url = _url + '&id=<?php echo $id;?>';
	_url = _url + '&name=' + encodeURIComponent(name);
	_url = _url + '&phone=' + encodeURIComponent(phone);
	_url = _url + '&email=' + encodeURIComponent(email);
	_url = _url + '&address=' + encodeURIComponent(address);
	_url = _url + '&vat=' + encodeURIComponent(vat);
	_url = _url + '&code=' + encodeURIComponent(code);
	_url = _url + '&category_id=' + encodeURIComponent(category_id);
	console.log(_url);
	
	loadPage('gotoTop', _url, function(status, message)
	{
		if(status== 0)
		{
			if(message.length == 36)
			{
				loadModule('<?php echo $page_id;?>');
			}
			else{
				alert(message);
			}
		}
		
	}, true);

}
function loadSaleman()
{
	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=saleman&rel_id=<?php echo $id;?>';
	loadPage('pnSaleman', _url, function(status, message)
	{
		if(status== 0)
		{
			
		}
		
	}, false);
}
loadSaleman();
function removeSaleman(id)
{
	var result = confirm("Want to delete?");
	if (!result) {
		return;
	}
	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=delSaleman&id=<?php echo $id;?>';
	loadPage('pnSaleman', _url, function(status, message)
	{
		if(status== 0)
		{
			loadSaleman();
		}
		
	}, true);
}
function selectSaleman(id)
{
	closePopup();
	var _url = '<?php echo URL;?>addons/<?php echo $page_id;?>/?ac=addSaleman&rel_id=<?php echo $id;?>&employee_id=' + id;
	console.log(_url);
	loadPage('pnSaleman', _url, function(status, message)
	{
		if(status== 0)
		{
			loadSaleman();
		}
		
	}, true);
}
function selEmployee()
{
	var _url = '<?php echo URL;?>addons/employee/?func=selectSaleman';
	showPopup(_url);
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
	$code = "";
	if(isset($_REQUEST['code']))
	{
		$code = $_REQUEST['code'];
	}
	if($code == "")
	{
		$code = $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "customer");
		$code = $appSession->getTool()->paddingLeft($code, '0', 5);
	}
	if($id == "")
	{
		$id = $appSession->getTool()->getId();
	}
	$phone = "";
	if(isset($_REQUEST['phone']))
	{
		$phone = $_REQUEST['phone'];
	}
	
	if($phone != "")
	{
		$sql = "SELECT id FROM customer WHERE phone='".$appSession->getTool()->replace($phone, "'", "''")."' AND status =0 AND company_id='".$appSession->getConfig()->getProperty("company_id")."' AND id !='".$id."'";
		
		$msg->add("query", $sql);
		$value = $appSession->getTier()->getValue($msg);
		if($value != "")
		{
			echo "Phone is avaible";
			exit();
		}
		
	}
	$email = "";
	if(isset($_REQUEST['email']))
	{
		$email = $_REQUEST['email'];
	}
	
	if($email != "")
	{
		$sql = "SELECT id FROM customer WHERE email='".$appSession->getTool()->replace($email, "'", "''")."' AND status =0 AND company_id='".$appSession->getConfig()->getProperty("company_id")."' AND id !='".$id."'";
		$msg->add("query", $sql);
		$value = $appSession->getTier()->getValue($msg);
		if($value != "")
		{
			echo "Email is avaible";
			exit();
		}
		
	}
	$vat = "";
	if(isset($_REQUEST['vat']))
	{
		$vat = $_REQUEST['vat'];
	}
	if($vat != "")
	{
		$sql = "SELECT id FROM customer WHERE vat='".$appSession->getTool()->replace($vat, "'", "''")."' AND status =0 AND company_id='".$appSession->getConfig()->getProperty("company_id")."' AND id !='".$id."'";
		$msg->add("query", $sql);
		$value = $appSession->getTier()->getValue($msg);
		if($value != "")
		{
			echo "Vat is avaible";
			exit();
		}
		
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
		if($name == "code")
		{
			$value = $code;
		}else{
			if(isset($_REQUEST[$name]))
			{
				$value = $_REQUEST[$name];
			}
		}
		
		$sql = $sql.", ".$name."='".str_replace("'", "''", $value)."'";
	}
	$sql = $sql." WHERE id ='".$id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	
	echo $id;
}else if($ac == "saleman")
{
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$sql = "SELECT d1.id, d2.code, d2.name";
	$sql = $sql." FROM hr_employee_rel d1";
	$sql = $sql." LEFT OUTER JOIN hr_employee d2 ON(d1.employee_id = d2.id)";
	$sql = $sql." WHERE d1.status=0 AND d1.rel_id='".$rel_id."'";
	$sql = $sql." ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);
	
	$dt = $appSession->getTier()->getTable($msg);
	
	for($i =0; $i<$dt->getRowCount(); $i++)
	{
		$id = $dt->getString($i, "id");
		$code = $dt->getString($i, "code");
		$name = $dt->getString($i, "name");
	?>
	<?php echo $code;?>. <?php echo $name;?> <a href="javascript:removeSaleman('<?php echo $id;?>')"><img src="<?php echo URL;?>assets/images/remove.png"/></a>
	<?php
	}
}else if($ac == "delSaleman")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$items = explode(",", $id);
	for($i =0; $i<count($items); $i++)
	{
		$id = $items[$i];
		$sql = "UPDATE hr_employee_rel SET status =1";
		$sql = $sql.", write_date=NOW()";
		$sql = $sql." WHERE id='".$id."'";
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
	}
	echo "OK";
}else if($ac == "addSaleman")
{
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$employee_id = "";
	if(isset($_REQUEST['employee_id']))
	{
		$employee_id = $_REQUEST['employee_id'];
	}
	$sql = "SELECT id FROM hr_employee_rel WHERE status =0 AND employee_id='".$employee_id."' AND rel_id='".$rel_id."'";
	
	$msg->add("query", $sql);
	$value = $appSession->getTier()->getValue($msg);
	if($value == "")
	{
		$sql = "INSERT INTO hr_employee_rel(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", company_id";
		$sql = $sql.", status";
		$sql = $sql.", rel_id";
		$sql = $sql.", employee_id";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$appSession->getTool()->getId()."'";
		$sql = $sql.", NOW()";
		$sql = $sql.", NOW()";
		$sql = $sql.", '".$appSession->getConfig()->getProperty("company_id")."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$rel_id."'";
		$sql = $sql.", '".$employee_id."'";
		$sql = $sql.")";
		$msg->add("query", $sql);
		
		$appSession->getTier()->exec($msg);
	}
	echo "OK";
}
?>