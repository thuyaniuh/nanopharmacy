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
if($ac == "view")
{
	
	$appSession->getLang()->load($appSession->getTier(), "CUSTOMER", $appSession->getConfig()->getProperty("lang_id"));
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
	$func = "";
	if(isset($_REQUEST['func']))
	{
		$func = $_REQUEST['func'];
	}
	
$mLineDepartment = new LineDepartment();
$employeeList = $mLineDepartment->findTreeEmployee($appSession, $appSession->getConfig()->getProperty("employee_id"));


$ids = "";
for($i =0; $i<count($employeeList); $i++)
{
	if($ids != "")
	{
		$ids = $ids." OR ";
	}
	$ids = $ids." d1.id='".$employeeList[$i][0]."'";
}

$sql = "SELECT d1.id, d1.code, d1.name";
$sql = $sql." FROM hr_employee d1 ";
$sql= $sql." WHERE d1.status =0 ";
if($ids != "")
{
	$sql = $sql." AND (".$ids.")";
}else{
	$sql = $sql." AND 1=0";
}

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
		<div class="col-12 col-md-8">
			<div class="title_inner d-flex">
				<h3 class="d-flex align-items-center"><?php echo $appSession->getLang()->find("Employee");?></h3>
				
			</div>
		</div>
		<div class="col-12  col-md-8 ">
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
					<td class="text-right Action" style="width: 5%;"><?php echo $appSession->getLang()->find("Action");?></td>
					<td class="text-left" nowrap="nowrap" ><?php echo $appSession->getLang()->find("Code");?></td>
					<td class="text-left " ><?php echo $appSession->getLang()->find("Name");?></td>
					
					<td class="text-left " ><?php echo $appSession->getLang()->find("Phone");?></td>
					<td class="text-left " ><?php echo $appSession->getLang()->find("Email");?></td>
					
					
					
					
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
						
						
					?>
						
									 
					<tr>
						<td>
						<a href="javascript:<?php echo $func;?>('<?php echo $id;?>')"><img src="<?php echo URL;?>assets/images/add.png"></a>
						</td>
						<td nowrap="nowrap" class="text-left" ><?php echo $code;?></td>
						<td nowrap="nowrap" class="text-left" ><?php echo $name;?></td>
						<td  nowrap="nowrap" class="text-left " ><?php echo $phone;?></td>
				
						<td nowrap="nowrap" class="text-left " ><?php echo $email;?></td>
						
						
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
function doSearch(p)
{
	
	var search = document.getElementById('editsearch').value;
	loadView('addons/<?php echo $page_id;?>/?search=' + encodeURIComponent(search) + "&p=" + p + "&func=<?php echo $func;?>");
}
</script>
<?php
}
?>