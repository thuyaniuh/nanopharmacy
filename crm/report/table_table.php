<?php

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
	$sql = "SELECT name, type, data FROM parameter WHERE status =0";
    $sql = $sql." AND (rel_id='".$data_id."' OR rel_id='".$module_id."')";
	$sql = $sql." ORDER BY create_date ASC";
	
	$msg->add("query", $sql);
	$result_data_sql = $appSession->getTier()->getArray($msg);
	$numrows_data_sql = count($result_data_sql);
	$sql_params = [];
	for($m =0; $m<$numrows_data_sql; $m++)
	{
		$row1 = $result_data_sql[$m];		
		$arr = [];
		$arr[0] = $row1[0];
		$arr[1] = $row1[1];
		$arr[2] = $row1[2];
		$sql_params[$m] = $arr;
		
	}
	
	$sql = "SELECT d1.id, d1.parent_id, d1.name, d1.data, d1.relationship, d2.order_by FROM ir_data_line  d1 LEFT OUTER JOIN ir_data d2 ON(d1.data_id = d2.id) WHERE d1.data_id ='".$data_id."' AND d1.status =0";
				
	$lines = [];
	
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
		$arr[5] = $row1[5];
		$lines[$m] = $arr;
		
		if($table_name == "" && $row1[1] == "")
		{
			$table_name = $row1[2];
		}
		
	}
	
	if($filters != "")
	{
		if($appSession->getTool()->indexOf($filters, "assigned") != -1)
		{
			$arr = [];
			
			$conditions = explode(',', $filters);
			
			for($i =0; $i<count($conditions); $i++)
			{
				if($conditions[$i] != "" && $appSession->getTool()->indexOf($conditions[$i], "assigned") == -1)
				{
					
					if($condition != "")
					{
						$condition = $condition.",";
					}
					$condition = $condition.$conditions[$i];
				}
			}
			$condition = $appSession->getTool()->replace($condition, ":", "=");
			
			
		}else
		{
			if($condition != "")
			{
				$condition = $condition.",";
			}
			$condition = $condition.$appSession->getTool()->replace($filters, ":", "=");
		}
		
	}
	$conditions = $appSession->getTier()->condition_array($condition);
	
	$sql = $appSession->getTier()->buildSQL($lines, "", $sql_params, $conditions);
	
	if($condition == "")
	{
		$sql = $sql." WHERE ".$table_name.".status=0";
	}else{
		$sql = $sql." AND ".$table_name.".status=0";
	}
	
	$sql = str_replace("{rel_id}", "'".$rel_id."'", $sql);
	
	if($appSession->getTool()->indexOf($filters, "assigned") != -1)
	{
		$conditions = explode(',', $filters);
		$condition = "";
		$values = "";
		for($i =0; $i<count($conditions); $i++)
		{
			if($conditions[$i] != "" && $appSession->getTool()->indexOf($conditions[$i], "assigned") != -1)
			{
				
				$index = $appSession->getTool()->indexOf( $conditions[$i], "v:");
				if($index != -1)
				{
					$values = $appSession->getTool()->substring($conditions[$i], $index + 2);
				}
				
				$index = $appSession->getTool()->indexOf($values, ";");
				if($index != -1)
				{
					$values = $appSession->getTool()->substring($values, 0, $index);
				}
				
				$arr = $appSession->getTool()->split($values, "~");
				$values = "";
				for($j=0; $j<count($arr); $j++)
				{
					if($values != "")
					{
						$values = $values.",";
					}
					$values = $values."'".$arr[$j]."'";
				}
			}
		}
		
		$sql = str_replace("{user_id}", $values, $sql);
	}else{
		$sql = str_replace("{user_id}", "'".$appSession->getUserInfo()->getId()."'", $sql);
	}
	
	if($appSession->getTool()->indexOf($filters, "assigned") != -1)
	{
		$conditions = explode(',', $filters);
		$condition = "";
		for($i =0; $i<count($conditions); $i++)
		{
			if($conditions[$i] != "" && $appSession->getTool()->indexOf($conditions[$i], "assigned") != -1)
			{
				$values = "";
				$index = $appSession->getTool()->indexOf( $conditions[$i], "v:");
				if($index != -1)
				{
					$values = $appSession->getTool()->substring($conditions[$i], $index + 2);
				}
				
				$index = $appSession->getTool()->indexOf($values, ";");
				if($index != -1)
				{
					$values = $appSession->getTool()->substring($values, 0, $index);
				}
				
				$arr = $appSession->getTool()->split($values, "~");
				$values = "";
				for($j=0; $j<count($arr); $j++)
				{
					if($values != "")
					{
						$values = $values." or ";
					}
					$values = $values." resource_id='".$arr[$j]."'";
				}
				
				$sql = str_replace("{assigned}", "(select rel_id from res_assign where status =0 AND ((".$values.") OR resource_id IN(select d3.user_id from hr_department_employee d1 LEFT OUTER JOIN hr_employee d2 ON(d1.employee_id = d2.id) LEFT OUTER JOIN res_user_company d3 ON(d2.id = d3.employee_id) where  d1.status =0 AND d2.status =0 AND d3.status=0 AND (".$appSession->getTool()->replace($values, "resource_id", "d1.department_id").")) ) AND type='".$table_name."')", $sql);
			}
		}
	}else{
		$sql = str_replace("{assigned}", "(select rel_id from res_assign where status =0 AND (resource_id='".$appSession->getUserInfo()->getId()."' or resource_id='".$appSession->getUserInfo()->getCompanyId()."') AND type='".$table_name."')", $sql);
	}
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
	
	if($sort == ""&& count($lines)>0)
	{
		$sort = $lines[0][5];
	}
	$searchs = [];
	for($c =0; $c<count($columns); $c++)
	{
		if($columns[$c][1] != ""  && $columns[$c][2] == "")
		{
			$searchs[count($searchs)] = str_replace("__", ".", $columns[$c][1]);
		}
	}
	
	$search = trim($search);
	if(count($searchs)>0 && $search != "")
	{
		$sql = $sql." AND (".$appSession->getTier()->buildSearch($searchs, $search).")";
	}
	if($group_by != "")
	{
		$sql= $sql. " GROUP BY ".$group_by;
	}
	
	
	if($group_by != "")
	{
		$sql= $sql. " GROUP BY ".$group_by;
	}
	
	if($sort != "")
	{
		$sql = $sql. " ORDER BY ".$sort;
	}
	

	
	$msg->add("query", $sql);
	
	$result = $appSession->getTier()->getTable($msg);
	$numrows = $result->getRowCount();	
	
	$appSession->getLang()->load($appSession->getTier(), $module_id, $appSession->getConfig()->getProperty("lang_id"));
	?>	
	<table  border="1" cellspacing="0" cellpadding="2" width="100%" class="borderLine">
		
			<tr class="header">
				<?php
				for($c =0; $c<count($columns); $c++)
				{
					$catpion = $columns[$c][0];
					$name = $columns[$c][1];
					$width = $columns[$c][3];
					$type = $columns[$c][2];
					if($name != "")
					{
						
				?>
				<td style="text-align:center; <?php if($width != ""){?> width: <?php echo $width;?>px <?php }?>"><?php echo $appSession->getLang()->find($catpion);?></td>
				<?php
					}else
					{
						if($type == "status")
						{
						?>
						<td style="text-align:center;  width: 20px"><?php echo $appSession->getLang()->find("Status");?></td>
						<?php
						}
					}
				}
				?>
				<?php
				if($view_type == "document")
				{
				?>
				<td  style="width: 24px"></td>
				
				<?php
				}
				?>
			</tr>
	
		
			<?php
			
			$hasTotal = 0;
			for($j =0; $j<$numrows; $j++)
			{
				
				$row = $result->getRow($j);
				$id = $row->getString($table_name."__id");
			?>
			<tr>
				
				
				<?php
				
				for($c =0; $c<count($columns); $c++)
				{
					
					$catpion = $columns[$c][0];
					$name = $columns[$c][1];
					
					$type = $columns[$c][2];
					$width = $columns[$c][3];
					
					$align = $columns[$c][4];
					$forecolor = $columns[$c][5];
					$backcolor = $columns[$c][6];
					$format = $columns[$c][7];
					
					if($columns[$c][8] != "")
					{
						$hasTotal = 1;
					}
					$valign = "middle";
					
					if($name != "")
					{
						$name = $row->getString($name);
						if($type == "date" && $name != "")
						{
							
							
							
							if($format != "")
							{
								$name = $appSession->getFormats()->getDATE()->formatWith($appSession->getTool()->toDateTime($name), $format);
							}else{
								$name = $appSession->getFormats()->getDATE()->formatDate($appSession->getTool()->toDateTime($name));
							}
							
						}else if($type == "float" && $name != "")
						{
							$name = $appSession->getFormats()->getDOUBLE()->format($appSession->getTool()->toDouble($name));
							if($align == "")
							{
								$align = "right";
							}
						}
						else if($type == "int" && $name != "")
						{
							if($align == "")
							{
								$align = "right";
							}
							$name = $appSession->getFormats()->getINT()->format($appSession->getTool()->toInt($name));
						}else if($type == "currency")
						{
							if($name == "")
							{
								$name = 0;
							}
							if($align == "")
							{
								$align = "right";
							}
							$currency_id = $appSession->getConfig()->getProperty("currency_id");
							for($c1 =0; $c1<count($columns); $c1++)
							{
								$name1 = $columns[$c1][1];
								
								if($name1 == "currency_id")
								{
									$currency_id = $row->getString($name1);
									break;
								}
							}
							$name = $appSession->getCurrency()->format($currency_id, $appSession->getTool()->toDouble($name));
						}else if($type == "percent" && $name != "")
						{
							$name = $appSession->getFormats()->getDOUBLE()->format($appSession->getTool()->toDouble($name)) ."%";
							if($align == "")
							{
								$align = "right";
							}
						}else if($type == "assigned")
						{
							$sql = "SELECT d2.name, d3.name FROM res_assign d1 LEFT OUTER JOIN res_user d2 ON(d1.resource_id = d2.id) LEFT OUTER JOIN res_company d3 ON(d1.resource_id = d3.id) WHERE d1.rel_id='".$name."' AND d1.status =0 ORDER BY d1.create_date ASC";
							$name = "";
							$msg->add("query", $sql);
							$assignedList = $appSession->getTier()->getArray($msg);
							for($n =0; $n<count($assignedList); $n++)
							{
								$sname = $assignedList[$n][0];
								if($sname != "")
								{
									if($name != "")
									{
										$name = $name.", ";
									}
									$name = $name.$sname;
								}
								$sname = $assignedList[$n][1];
								if($sname != "")
								{
									if($name != "")
									{
										$name = $name.", ";
									}
									$name = $name.$sname;
								}
							}
						}
						
						if($type == "no")
						{
							
							$name = "".($j + 1);
						}
				
					
					
				?>
				<td  style="<?php if($align != ""){?> ;text-align: <?php echo $align;?> <?php } ?> <?php if($forecolor != ""){?> ;color: <?php echo $forecolor;?> <?php } ?><?php if($forecolor != ""){?> ;background-color: <?php echo $backcolor;?> <?php } ?>;max-width:600px; valign:<?php echo $valign;?>" >
					<?php echo $name;?>
				</td>
				<?php
					}
					else{
						if($type == "status")
						{
							$sql = "SELECT d2.name FROM ir_module_status_log d1 LEFT OUTER JOIN ir_module_status d2 ON(d1.status_id = d2.id) WHERE d1.status =0 AND d1.rel_id='".$id."'";
							$status_name = $appSession->getTier()->getValue($sql);
							
						?>
						<td  style="text-align:left">
							<?php echo $status_name;?>
						</td>
						<?php
						}
					}
				}
				?>
				<?php
				if($view_type == "document")
				{
				?>
				<td><a href="<?php echo URL;?>document/?id=<?php echo $id;?>" target="_blank"><i class="icon-cloud-download"></i></a></td>
				
				<?php
				}
				?>
				
			</tr>
			<?php
			}
			?>
			<?php
			if($hasTotal == 1)
			{
			?>
			<tr>
				<td colspan="1"><b>Total</b></td>
				<?php
				
				for($c =0; $c<count($columns); $c++)
				{
					
					
					$name = $columns[$c][1];
					$type = $columns[$c][2];
					$width = $columns[$c][3];
					
					$align = $columns[$c][4];
					$format = $columns[$c][7];
					$format_value = "";
					if($name != "")
					{
						$total = $columns[$c][8];
						$total_amount = 0;
						if($total != "")
						{
							for($j =0; $j<$numrows; $j++)
							{
								
								$row = $result->getRow($j);
								$total_amount = $total_amount + $row->getFloat($name);
							}
						}
						if($type == "float" || $type == "currency" || $type == "integer" || $type == "percent")
						{
							if($align == "")
							{
								$align = "right";
							}
						}
						if($total == "AVG")
						{
							if($numrows>0)
							{
								$total_amount = $total_amount/$numrows;
							}
						}
						if($type == "float" || $type == "integer")
						{
							$format_value = $appSession->getFormats()->getDOUBLE()->format($total_amount);
						}else if($type == "currency")
						{
							$format_value = $appSession->getCurrency()->format($appSession->getConfig()->getProperty("currency_id"), $total_amount);
						}else if($type == "percent")
						{
							$format_value = $appSession->getFormats()->getDOUBLE()->format($total_amount)."%";
						}
					?>
					<td style="<?php if($align != ""){?> ;text-align: <?php echo $align;?> <?php } ?> <?php if($forecolor != ""){?> ;color: <?php echo $forecolor;?> <?php } ?><?php if($forecolor != ""){?> ;background-color: <?php echo $backcolor;?> <?php } ?>"><?php if($total != ""){ echo $format_value; }?></td>
					<?php
					}
				}
				?>
				<?php
				if($view_type == "document")
				{
				?>
				<td></td>
				<?php
				}
				?>
			</tr>
			<?php
			}
			?>
		
	</table>
		
	
			
		

