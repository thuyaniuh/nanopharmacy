<?php


$module_caption = "";

$msg = $appSession->getTier()->createMessage();
$msg->add("root_company_id", $appSession->getConfig()->getProperty("root_company_id"));
$msg->add("company_id", $appSession->getUserInfo()->getCompanyId());
$msg->add("user_id", $appSession->getUserInfo()->getId());

$sql = "SELECT d1.module_model_id, d1.data, d4.name AS module_name, lg.description FROM ir_module_model_attribute d1 LEFT OUTER JOIN ir_module_model d2 ON(d1.module_model_id = d2.id) LEFT OUTER JOIN ir_module d4 ON(d2.module_id = d4.id) LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d4.id AND lg.name='module_name' AND lg.status =0) WHERE d1.status =0";
if($root_module_id != "")
{
	$sql = $sql. "AND (d2.module_id='".$module_id."' OR d2.module_id='".$root_module_id."')";
}else
{
	$sql = $sql. "AND d2.module_id='".$module_id."'";
}

$msg->add("query", $sql);

$result = $appSession->getTier()->getArray($msg);
$numrows = count($result);	
$values = [];
for($i =0; $i<$numrows; $i++)
{
	$row =$result[$i];
	$arr = [];
	$arr[0] = $row[0];
	$arr[1] = $row[1];
	$arr[2] = $row[2];
	
	$values[$i] = $arr;
	$module_caption = $row[2];
	if($row[3] != "")
	{
		$module_caption = $row[3];
	}
}



$sql = "SELECT d1.id, d1.parent_id, d2.name, d3.name AS rename, d1.group_id FROM ir_module_model d1 LEFT OUTER JOIN ir_model d2 ON(d1.model_id = d2.id) LEFT OUTER JOIN ir_model_attribute d3 ON(d1.rel_id = d3.id) WHERE d1.status =0";
if($root_module_id != "")
{
	$sql = $sql. "AND (d1.module_id='".$module_id."' OR d1.module_id='".$root_module_id."')";
}else
{
	$sql = $sql." AND d1.module_id='".$module_id."'";
}

$sql = $sql." ORDER BY d1.sequence ASC, d1.create_date ASC";


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
	$arr[4] = $row[4];
	$models[$i] = $arr;
	if($row[3] == "columns" && $row[4]== $module_model_id)
	{
		$columns_model_id = $row[0];
	}
	
				
}
$columns = [];
$table_name = "";
$data_id = "";
$action_ids = "";
$hasStatus = 0;
$view_type = "table";
$start_name = "start_date";
$end_name = "end_date";
$card_columns = 3;
$table_caption = "";
$table_parent_id = "";


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
		$format = "";
		$total = "";
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
					if($type == "status")
					{
						$hasStatus = 1;
					}
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
				}else if($models[$j][3] == 'format')
				{
					$format = $value;
				}else if($models[$j][3] == 'total')
				{
					$total = $value;
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
		$arr[7] = $format;
		$arr[8] = $total;
		$arr[9] = $name;
		$arr[10] = $models[$i][0];
		$columns[count($columns)] = $arr;
	}
	if(($models[$i][3] == "src" || $models[$i][2] == "data") && $models[$i][4] == $module_model_id)
	{
		$data_id = findValue($values, $models[$i][0]);
		
	}
	if(($models[$i][3] == "view_type") && $models[$i][4] == $module_model_id)
	{
		$view_type = findValue($values, $models[$i][0]);
		
	}
	if(($models[$i][3] == "start_date") && $models[$i][4] == $module_model_id)
	{
		$start_name = findValue($values, $models[$i][0]);
		
	}
	if(($models[$i][3] == "end_date") && $models[$i][4] == $module_model_id)
	{
		$end_name = findValue($values, $models[$i][0]);
		
	}if(($models[$i][3] == "card_columns") && $models[$i][4] == $module_model_id)
	{
		$sValue = findValue($values, $models[$i][0]);
		if($sValue != "")
		{
			$card_columns = $appSession->getTool()->toInt($sValue);
		}
		
	}
	if(($models[$i][3] == "table_caption") && $models[$i][4] == $module_model_id)
	{
		$table_caption = findValue($values, $models[$i][0]);
		
	}
	if(($models[$i][3] == "parent_id") && $models[$i][4] == $module_model_id)
	{
		$table_parent_id = findValue($values, $models[$i][0]);
		
	}
	
	if(($models[$i][3] == "action" || $models[$i][2] == "action") && $models[$i][4] == $module_model_id)
	{
		$ac_id = findValue($values, $models[$i][0]);
		if($ac_id != "")
		{
			if($action_ids != "")
			{
				$action_ids = " OR ";
			}
			$action_ids = $action_ids." d2.id='".$ac_id."'";
		}
		
	}
}
if($card_columns>12)
{
	$card_columns = 12;
}


$column = "";
$name = "";
$p = 0;
$ps = 20;
$search = "";
$sort = "";
$rel_id = "";
$arr_params = explode("&", $params);
for($i =0; $i<count($arr_params); $i++)
{
	$arr = explode("=", $arr_params[$i]);
	if(count($arr)>1)
	{
		if($arr[0] == "search")
		{
			$search = $arr[1];
			
		}else if($arr[0] == "sort")
		{
			$sort = $arr[1];
		}
		else if($arr[0] == "p")
		{
			$p = $arr[1];
		}else if($arr[0] == "ps")
		{
			$ps = $arr[1];
		}else if($arr[0] == "rel_id")
		{
			$rel_id = $arr[1];
			
		}else if($arr[0] == "filters")
		{
			$filters = $arr[1];
			
		}
	}
}


	
	$sql = "SELECT d1.id, d1.name, d1.type, d1.icon FROM ir_module_line d1 WHERE d1.status =0 AND d1.module_id='".$module_id."' AND d1.type LIKE 'table%' ORDER BY d1.sequence ASC, d1.create_date ASC";
	$msg->add("query", $sql);
	$views = $appSession->getTier()->getArray($msg);

	$sql = "SELECT d1.id, d1.name, d1.rel_id, d1.icon, d2.icon, lg.description, d1.type, d2.type FROM ir_module d1 LEFT OUTER JOIN ir_module d2 ON(d1.rel_id = d2.id) LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='module_name' AND lg.status =0) WHERE d1.status =0 AND d1.publish=1 AND d1.grouped = 1 AND (d1.type='parent' OR d2.type='parent' OR d1.type='report' OR  d2.type='report')";
	if($root_module_id != "")
	{
		$sql = $sql." AND (d1.parent_id='".$module_id."' OR d1.parent_id='".$root_module_id."')";
	}else{
		$sql = $sql." AND d1.parent_id='".$module_id."'";
	}
	
	$sql = $sql." ORDER BY d1.sequence ASC";
	$msg->add("query", $sql);
	$result_grouped = $appSession->getTier()->getArray($msg);

	$appSession->getLang()->load($appSession->getTier(), $module_id, $appSession->getConfig()->getProperty("lang_id"));
	
	
	?>
	
	<?php
	function findLayout($layouts, $parent_id, $name)
	{
		
		for($i =0; $i<count($layouts); $i++)
		{
			if($layouts[$i][1]== $parent_id)
			{
				$_id = $layouts[$i][0];
				$type = $layouts[$i][2];
				$value = $layouts[$i][3];
				$data = $layouts[$i][4];
				if($data != "")
				{
					$pos = strpos($data, ',');
					if ($pos !== false)
					{
						$model_name = substr($data, 0, $pos);
						if($model_name == $name)
						{
							return [substr($data, $pos + 1), $_id];
						}
					}
				}
				$found_id = findLayout($layouts, $_id, $name);
				if(count($found_id )>0)
				{
					return $found_id;
				}
			}
		}
		return [];
	}
	
	if($view_type == "collapsible")
	{
		include( ABSPATH .'report/table_collapsible.php' );
	}else if($view_type == "card")
	{
		include( ABSPATH .'report/table_card.php' );
	}else if($view_type == "grantt")
	{
		include( ABSPATH .'report/table_grantt.php' );
	}else if($view_type == "calendar")
	{
		include( ABSPATH .'report/table_calendar.php' );
	}else{
		include( ABSPATH .'report/table_table.php' );
	}
	?>


	
</script>
