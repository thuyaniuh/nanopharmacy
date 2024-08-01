<?php

$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}

if($ac == "")
{
	$ac = "view";
}
$msg = $appSession->getTier()->createMessage();
$msg->add("root_company_id", $appSession->getConfig()->getProperty("root_company_id"));
$msg->add("company_id", $appSession->getUserInfo()->getCompanyId());
$msg->add("user_id", $appSession->getUserInfo()->getId());

if($ac == "view")
{

$sql = "SELECT d1.module_model_id, d1.data FROM ir_module_model_attribute d1 LEFT OUTER JOIN ir_module_model d2 ON(d1.module_model_id = d2.id) WHERE d2.module_id='".$module_id."' AND d2.group_id='".$module_model_id."'";

$msg->add("query", $sql);
$result = $appSession->getTier()->getArray($msg);
$numrows = count($result);	
$values = [];
for($i =0; $i<$numrows; $i++)
{
	$row = $result[$i];
	$arr = [];
	$arr[0] = $row[0];
	$arr[1] = $row[1];
	$values[$i] = $arr;
}


$sql = "SELECT d1.id, d1.parent_id, d2.name, d3.name AS rename FROM ir_module_model d1 LEFT OUTER JOIN ir_model d2 ON(d1.model_id = d2.id) LEFT OUTER JOIN ir_model_attribute d3 ON(d1.rel_id = d3.id) WHERE d1.status =0 AND d1.module_id='".$module_id."' AND d1.group_id='".$module_model_id."' ORDER BY d1.create_date ASC";

$msg->add("query", $sql);
$result = $appSession->getTier()->getArray($msg);
$numrows = count($result);	
$models = [];

$columns_model_id = "";
for($i =0; $i<$numrows; $i++)
{
	$row = $result[$i];
	$arr = [];
	$arr[0] = $row[0];
	$arr[1] = $row[1];
	$arr[2] = $row[2];
	$arr[3] = $row[3];
	$models[$i] = $arr;
	if($row[3] == "columns")
	{
		$columns_model_id = $row[0];
	}
				
}

$data_id = "";
$columns = [];
for($i =0; $i<count($models); $i++)
{
	if($models[$i][1] == $columns_model_id)
	{
		$caption = "";
		$name = "";
		$type = "";
		$align = "";
		$forecolor = "";
		$backcolor = "";
		$width = "";
		
		for($j =0; $j<count($models); $j++)
		{
			if($models[$j][1] == $models[$i][0])
			{
				$value = findValue($values, $models[$j][0]);
				if($models[$j][3] == 'caption')
				{
					$caption = $value;
				}
				else if($models[$j][3] == 'name')
				{
					$name = $value;
				}else if($models[$j][3] == 'type')
				{
					$type = $value;
				}else if($models[$j][3] == 'align')
				{
					$align = $value;
				}else if($models[$j][3] == 'forecolor')
				{
					$forecolor = $value;
				}else if($models[$j][3] == 'backcolor')
				{
					$backcolor = $value;
				}else if($models[$j][3] == 'width')
				{
					$width = $value;
				}
			}
		}
		$arr = [];
		$arr[0] = $caption;
		$arr[1] = $name;
		$arr[2] = $type;
		$arr[3] = $width;
		$arr[4] = $align;
		$arr[5] = $forecolor;
		$arr[6] = $backcolor;
		$columns[count($columns)] = $arr;
	}
	else if($models[$i][3] == "src" || $models[$i][2] == "data")
	{
		$data_id = findValue($values, $models[$i][0]);
		
	}
}
$column = "";
$name = "";
$action = "";
if($data_id == "")
{
	echo "Data is not support";
}else
{
	$rel_id = "";
	if(isset($_SESSION[$module_id."rel_id"]))
	{
		$rel_id = $_SESSION[$module_id."rel_id"];
		
	}


	$condition = "";
	$group_by = "";
	$order_by = "";
	$sql = "SELECT condition, group_by, order_by FROM ir_data WHERE id ='".$data_id."' AND status =0";
	$msg->add("query", $sql);
	$result_data_sql = $appSession->getTier()->getArray($msg);
	$numrows_data_sql = count($result_data_sql);	
	if($numrows_data_sql>0)
	{
		$row1 = $result_data_sql[0];
		$condition = $row1[0];
		$group_by = $row1[1];
		$order_by = $row1[2];
	}
	$sql = "SELECT name, type, data FROM parameter WHERE status =0 AND rel_id='".$data_id."' ORDER BY create_date ASC";
	$msg->add("query", $sql);
	$result_data_sql = $appSession->getTier()->getArray($msg);
	$numrows_data_sql = count($result_data_sql);
	$sql_params = [];
	for($m =0; $m<$numrows_data_sql; $m++)
	{
		$row1 = $result_data_sql[0];		
		$arr = [];
		$arr[0] = $row1[0];
		$arr[1] = $row1[1];
		$arr[2] = $row1[2];
		$sql_params[$m] = $arr;
	}
	
	
	$sql = "SELECT id, parent_id, name, data, relationship FROM ir_data_line WHERE data_id ='".$data_id."' AND status =0";
					
	$lines = [];
	$table_name = "";
	$msg->add("query", $sql);
	$result_data_sql = $appSession->getTier()->getArray($msg);
	$numrows_data_sql = count($result_data_sql);	
	for($m =0; $m<$numrows_data_sql; $m++)
	{
		$row1 = $result_data_sql[$m];
		$arr = [];
		$arr[0] = $row1[0];
		$arr[1] = $row1[1];
		$arr[2] = $row1[2];
		$arr[3] = $row1[3];
		$arr[4] = $row1[4];
		$lines[$m] = $arr;
		if($table_name == "" && $row1[1] == "")
		{
			$table_name = $row1[2];
		}
	}
	$conditions = $appSession->getTier()->condition_array($condition);
	$sql = $appSession->getTier()->buildSQL($lines, "", $sql_params, $conditions);
	
	
	$sql = str_replace("{rel_id}", "'".$rel_id."'", $sql);
	$sql = str_replace("{user_id}", "'".$appSession->getUserInfo()->getId()."'", $sql);
	$sql = str_replace("{company}", "'".$appSession->getUserInfo()->getCompanyId()."'", $sql);
	$sql = str_replace("{root_company}", "'".$appSession->getConfig()->getProperty("root_company_id")."'", $sql);
	$arr = explode(',', $appSession->getConfig()->getProperty("child_company"));
	$c_company_id = "";
	for($n =0; $n<count($arr); $n++)
	{
		if($arr[$n] != "")
		{
			if($c_company_id != "")
			{
				$c_company_id = $c_company_id.", ";
			}
			$c_company_id = $c_company_id."'".$arr[$n]."'";
		}
		
	}
	$sql = str_replace("{child_company}", $c_company_id, $sql);
	
	$arr = explode(',', $appSession->getConfig()->getProperty("parent_company"));
	$c_company_id = "";
	for($n =0; $n<count($arr); $n++)
	{
		if($arr[$n] != "")
		{
			if($c_company_id != "")
			{
				$c_company_id = $c_company_id.", ";
			}
			$c_company_id = $c_company_id."'".$arr[$n]."'";
		}
		
	}
	$sql = str_replace("{parent_company}", $c_company_id, $sql);
	
	$arr = explode(',', $appSession->getConfig()->getProperty("company_ids"));
	$c_company_id = "";
	for($n =0; $n<count($arr); $n++)
	{
		if($arr[$n] != "")
		{
			if($c_company_id != "")
			{
				$c_company_id = $c_company_id.", ";
			}
			$c_company_id = $c_company_id."'".$arr[$n]."'";
		}
		
	}
	$sql = str_replace("{companies}", $c_company_id, $sql);
	
	
	$sql = str_replace("{rel_id}", "'".$rel_id."'", $sql);
	$sql = str_replace("{user_id}", "'".$appSession->getUserInfo()->getId()."'", $sql);
	
	$value = "";
	
	if($id == "")
	{
		if($condition == "")
		{
			$sql = $sql." WHERE 1=0";
		}else{
			$sql = $sql." AND 1=0";
		}
		
	}else
	{
		$sql = $sql." WHERE ".$table_name.".id ='".$id."'";
	}
	
	$msg->add("query", $sql);
	$result = $appSession->getTier()->getTable($msg);
	$numrows = $result->getRowCount();	
	if($numrows>0)
	{
		$row = $result->getRow(0);
		$value = "";
		for($c =0; $c<count($columns); $c++)
		{
			$name = $columns[$c][1];
			if($name != "")
			{
				$name = $row->getString($name);
				if($value != '')
				{
					$value = $value."/";
				}
				$value = $value.$name;
			}
		}
	}


	$arr_params = explode("&", $params);
	for($i =0; $i<count($arr_params); $i++)
	{
		$arr = explode("=", $arr_params[$i]);
		if(count($arr)>1)
		{
			if($arr[0] == "name")
			{
				$name = $arr[1];
			}else if($arr[0] == "action")
			{
				$action = $arr[1];
			}
		}
	}
	?>
	<input type="hidden" id="<?php echo $name;?>" value="<?php echo $id;?>">
	<div class="input-group">
		<input class="input" id="<?php echo $name;?>_value" readonly value="<?php echo $value;?>">
		<div class="input-group-append">
			<a class="btn btn-default" href="javascript:openPopup('<?php echo URL;?>admin/module/list_search/?ac=search&module_id=<?php echo $module_id;?>&module_model_id=<?php echo $module_model_id;?>&pid=<?php echo $name;?>')">...</a>
		</div>
	</div>
		
	<?php
	}
}

?>