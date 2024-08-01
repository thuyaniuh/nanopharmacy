<?php
require_once(ABSPATH . 'api/LineDepartment.php');

validUser($appSession);
$msg = $appSession->getTier()->createMessage();

$page_id = "employee";
$table_name = "hr_employee";
$columns = ["code", "name", "phone", "email", "address"];
$searchs = ["d1.name", "d1.phone", "d1.email"];
$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
if($ac == "")
{
	$ac = "view";
}

	
$appSession->getLang()->load($appSession->getTier(), "CUSTOMER", $appSession->getConfig()->getProperty("lang_id"));
$search = "";
if(isset($_REQUEST['search']))
{
	$search = $_REQUEST['search'];
}

$rel_id = "";
if(isset($_REQUEST['rel_id']))
{
	$rel_id = $_REQUEST['rel_id'];
}
if($ac == "add")
{
	$description = "";
	if(isset($_REQUEST['description']))
	{
		$description = $_REQUEST['description'];
	}
	$status_id = "";
	if(isset($_REQUEST['status_id']))
	{
		$status_id = $_REQUEST['status_id'];
	}
	$category_id = "";
	if(isset($_REQUEST['category_id']))
	{
		$category_id = $_REQUEST['category_id'];
	}
	$user_id = $appSession->getConfig()->getProperty("user_id");
	$builder = $appSession->getTier()->createBuilder("crm_activity");
	$builder->add("id", $appSession->getTool()->getId());
	$builder->add("create_uid", $user_id);
	$builder->add("write_uid", $user_id);
	$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
	$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
	$builder->add("rel_id", $rel_id);
	$builder->add("category_id", $category_id);
	$builder->add("status_id", $status_id);
	
	$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), $appSession->getConfig()->getProperty("company_id"), "crm_activity"));
	$builder->add("start_date", $appSession->getTier()->getDateString(), 'f');
	$builder->add("end_date", $appSession->getTier()->getDateString(), 'f');
	$builder->add("description", $description);
	$builder->add("status", 0);
	$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
	$sql = $appSession->getTier()->getInsert($builder);
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);

}

	
$sql = "SELECT d1.id, d1.receipt_no, d1.description, d1.start_date, d1.end_date, d2.name AS status_name, d3.name AS category_name FROM crm_activity d1 LEFT OUTER JOIN crm_activity_status d2 ON(d1.status_id = d2.id) LEFT OUTER JOIN crm_activity_category d3 ON(d1.category_id = d3.id) WHERE d1.status =0 AND d1.rel_id ='".$rel_id."' ORDER BY d1.create_date DESC";

$msg->add("query", $sql);
$dt = $appSession->getTier()->getTable($msg);

$sql = "SELECT id, name FROM crm_activity_category WHERE status =0 AND (company_id='".$appSession->getConfig()->getProperty("company_id")."' OR company_id='".$appSession->getConfig()->getProperty("parent_company_id")."') ORDER BY name ASC";
$msg->add("query", $sql);
$categoryList = $appSession->getTier()->getArray($msg);


$sql = "SELECT id, name FROM crm_activity_status WHERE status =0 AND (company_id='".$appSession->getConfig()->getProperty("company_id")."' OR company_id='".$appSession->getConfig()->getProperty("parent_company_id")."') ORDER BY name ASC";
$msg->add("query", $sql);
$statusList = $appSession->getTier()->getArray($msg);

?>
<div class="page_title">
	<div class="row align-items-center mx-0">
		<div class="col-12 col-md-8">
			<div class="title_inner d-flex">
				<h3 class="d-flex align-items-center"><?php echo $appSession->getLang()->find("Activity");?></h3>
				<button type="button" class="btn"><a href="javascript:loadModule('customer')"><?php echo $appSession->getLang()->find("Back");?></a></button>
			</div>
		</div>
		<div class="col-12  col-md-8 ">
			
		</div>
	</div>
</div>
<form>
	<div class="col-12 mx-auto form_container">
		<div class ="row">
			<div class="col md-6">
				<div class="form-group wow">
					<label><?php echo $appSession->getLang()->find("Category");?></label>
					<select type="text" id="editcategory_id" class="form-control">
					
				<?php
				for($i =0; $i<count($categoryList); $i++)
				{
					
				?>
				<option value="<?php echo $categoryList[$i][0];?>"><?php echo $categoryList[$i][1];?></option>
				<?php
				}
				?>
					</select>
				</div>
			</div>
			<div class="col md-6">
				<div class="form-group wow">
					<label><?php echo $appSession->getLang()->find("Status");?></label>
					<select type="text" id="editstatus_id" class="form-control">
						<?php
						for($i =0; $i<count($statusList); $i++)
						{
						?>
						<option value="<?php echo $statusList[$i][0];?>"><?php echo $statusList[$i][1];?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<div class ="row">
			<div class="col md-12">
				<div class="form-group wow">
				<textarea id="editdescription" class="form-control"></textarea>
				</div>
			</div>
		</div>
		<div class ="row">
			<div class="col md-12">
				<div class="form-group wow">
				<button type="button" style="width:120" class="btn"><a href="javascript:addActivity()"><?php echo $appSession->getLang()->find("Add");?></a></button>
				</div>
			</div>
		</div>
	</div>
</form>
<hr>
<div class="col-12 mx-auto form_container">
	<div class ="row">
		<div class="col md-12">

		<div class="table-responsive">
			  <table class="table table-bordered table-hover table-sm">
				<tr class="list_header">
			
				<td class="text-left" nowrap="nowrap" ><?php echo $appSession->getLang()->find("Receipt No");?></td>
				<td class="text-left " ><?php echo $appSession->getLang()->find("Date");?></td>
				
				<td class="text-left " ><?php echo $appSession->getLang()->find("Status");?></td>
				<td class="text-left " ><?php echo $appSession->getLang()->find("Category");?></td>
				<td class="text-left " ><?php echo $appSession->getLang()->find("Description");?></td>
				
				
				
				</tr>
		
			<tbody>
					   
				<?php
				for($i =0; $i<$dt->getRowCount(); $i++)
				{
					$id = $dt->getString($i, "id");
					$receipt_no = $dt->getString($i, "receipt_no");
					$start_date = $dt->getString($i, "start_date");
					$category_name = $dt->getString($i, "category_name");
					$status_name = $dt->getString($i, "status_name");
					$description = $dt->getString($i, "description");
					if($start_date != "")
					{
						$start_date = $appSession->getFormats()->getDATE()->formatDate($appSession->getTool()->toDateTime($start_date));
					}
					
					
				?>
					
								 
				<tr>
					
					<td nowrap="nowrap" class="text-left" ><?php echo $receipt_no;?></td>
					<td nowrap="nowrap" class="text-left" ><?php echo $start_date;?></td>
					<td nowrap="nowrap" class="text-left" ><?php echo $category_name;?></td>
					<td  nowrap="nowrap" class="text-left " ><?php echo $status_name;?></td>
			
					<td nowrap="nowrap" class="text-left " ><?php echo $description;?></td>
					
					
				</tr>
				<?php
				}
				?>
				

			</tbody>
			</table>
			</div>
		</div>
	</div>
</div>
				
<script>
	function addActivity()
	{
		var ctr = document.getElementById('editdescription');
		if(ctr.value == '')
		{
			alert('<?php echo $appSession->getLang()->find("Please enter description");?>');
			return;
			ctr.focus();
		}
		var description = ctr.value;
		var category_id = document.getElementById('editcategory_id').value;
		var status_id = document.getElementById('editstatus_id').value;
		var _url = '<?php echo URL;?>addons/activities/?ac=add&rel_id=<?php echo $rel_id;?>';
		_url = _url + '&description=' + encodeURIComponent(description);
		_url = _url + '&status_id=' + encodeURIComponent(status_id);
		_url = _url + '&category_id=' + encodeURIComponent(category_id);
		
		loadPage('contentView', _url, function(status, message)
		{
			if(status== 0)
			{
				
			}
			
		}, false);
	}
</script>
		

