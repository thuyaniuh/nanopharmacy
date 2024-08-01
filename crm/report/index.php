<?php
	$msg = $appSession->getTier()->createMessage();
	$msg->add("root_company_id", $appSession->getConfig()->getProperty("root_company_id"));
	$msg->add("company_id", $appSession->getUserInfo()->getCompanyId());
	$msg->add("user_id", $appSession->getUserInfo()->getId());
	
	$module_id = "";
	if(isset($_REQUEST['module_id']))
	{
		$module_id = $_REQUEST['module_id'];
	}
	$root_module_id = "";
	if(isset($_REQUEST['root_module_id']))
	{
		$root_module_id = $_REQUEST['root_module_id'];
	}
	
	$model_name = "";
	if(isset($_REQUEST['model_name']))
	{
		$model_name = $_REQUEST['model_name'];
	}
	if($model_name == "")
	{
		$model_name = "table";
	}
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$params = "";
	if(isset($_REQUEST['params']))
	{
		$params = $_REQUEST['params'];
	}
	$filters = "";
	if(isset($_REQUEST['filters']))
	{
		$filters = $_REQUEST['filters'];
	}
	$params = "filters=".$filters;
	$content_type = "";
	if(isset($_REQUEST['content_type']))
	{
		$content_type = $_REQUEST['content_type'];
	}
	$view_module_id = "";
	$sql = "SELECT d1.id, d1.name, d1.data, d2.name, lg.description FROM ir_module_line d1 LEFT OUTER JOIN ir_module d2 ON(d1.module_id = d2.id) LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d2.id AND lg.name='module_name' AND lg.status =0) WHERE d1.status =0";
	if($root_module_id != "")
	{
		$sql = $sql. "AND (d1.module_id='".$module_id."' OR d1.module_id='".$root_module_id."')";
	}else
	{
		$sql = $sql. "AND d1.module_id='".$module_id."'";
	}
	
    $sql = $sql." AND (d1.type='".$model_name."' OR d1.id='".$model_name."')";
	
	$sql = $sql." ORDER BY d1.sequence ASC, d1.create_date ASC LIMIT 1";
	
	$msg->add("query", $sql);
	$result_line = $appSession->getTier()->getArray($msg);
	$numrows_line = count($result_line);

	$data = "";
	$module_name = "";
	if($numrows_line>0)
	{
		$row = $result_line[0];
		$view_module_id = $row[0];
		$view_name = $row[1];
		$data = $row[2];
		$module_name = $row[4];
		if($module_name == "")
		{
			$module_name = $row[3];
		}
	}

	function findValue($values, $id)
	{
		for($i =0; $i<count($values); $i++)
		{
			if($values[$i][0] == $id)
			{
				return $values[$i][1];
			}
		}
		return "";
	}
	
	?>
	<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title><?php echo $module_name;?> - <?php echo META_TITLE;?></title>
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="<?php echo URL;?>favicon.ico" type="image/png"/>
		<link href="<?php echo URL;?>assets/report/css.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
	<?php
	if($content_type == "word")
	{
		header("Content-Type:   application/vnd.ms-word; charset=utf-8");
		header("Content-type:   application/x-msexcel; charset=utf-8");
		header("Content-Disposition: attachment; filename=".$model_name.".doc"); 
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
	}else if($content_type == "excel")
	{
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-type:   application/x-msexcel; charset=utf-8");
		header("Content-Disposition: attachment; filename=".$model_name.".xls"); 
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
	
	}else if($content_type == "pdf")
	{
		header("Content-Type:   application/pdf; charset=utf-8");
		header("Content-type:   application/x-msexcel; charset=utf-8");
		header("Content-Disposition: attachment; filename=".$model_name.".pdf"); 
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
	}
	
	function parseModel($appSession, $module_id, $model_name, $module_model_id, $id, $params, $layouts, $layout_id, $pageIndex, $root_module_id, $rel_id, $view_module_id)
	{
		
		if($model_name == "table")
		{
			include( ABSPATH .'report/table.php' );
		}if($model_name == "treetable")
		{
			include( ABSPATH .'report/treetable.php' );
		}
		else if($model_name == "form")
		{
			
			include( ABSPATH .'report/form.php' );
		}else if($model_name == "dropdown")
		{
			include( ABSPATH .'report/dropdown.php' );
		}else if($model_name == "list")
		{
			
			include( ABSPATH .'report/list.php' );
		}else if($model_name == "page")
		{
			include( ABSPATH .'report/page.php' );
			
		}else if($model_name == "multiple")
		{
			include( ABSPATH .'report/multiple.php' );
		}else if($model_name == "resource")
		{
			include( ABSPATH .'report/resource.php' );
		}else if($model_name == "convert")
		{
			include( ABSPATH .'report/convert.php' );
		}else if($model_name == "mail")
		{
			include( ABSPATH .'report/mail.php' );
		}
	}
	function parseLine($appSession, $layouts, $parent_id, $module_id, $id, $params, $root_module_id, $view_module_id)
	{
		for($i =0; $i<count($layouts); $i++)
		{
			if($layouts[$i][1]== $parent_id)
			{
				$_id = $layouts[$i][0];
				$type = $layouts[$i][2];
				$value = $layouts[$i][3];
				$data = $layouts[$i][4];
				if($type == "row")
				{
					
						parseLine($appSession,  $layouts, $_id, $module_id, $id, $params, $root_module_id, $view_module_id);
					
				}else if($type == "col")
				{
					
						if($data != "")
						{
							$pos = strpos($data, ',');
							if ($pos !== false)
							{
								$model_name = substr($data, 0, $pos);
								$module_model_id = substr($data, $pos + 1);
							
								parseModel($appSession, $module_id, $model_name, $module_model_id, $id, $params, $layouts, $layouts[$i][0], "", $root_module_id, "", $view_module_id);
							}
						}
						parseLine($appSession, $layouts, $_id, $module_id, $id, $params, $root_module_id, $view_module_id);
					
				}
				
				
			}
		}
	}
	$arr = explode(':', $data);
	$layouts = [];
	for($i=0; $i<count($arr); $i++)
	{
		$item = explode(';', $arr[$i]);
		$_id = "";
		$parent_id = "";
		$type = "";
		$value = "";
		$data = "";
		for($j=0; $j<count($item); $j++)
		{
			$arrItem = explode('=', $item[$j]);
			if(count($arrItem)>0)
			{
				if($arrItem[0] == "id")
				{
					$_id = $arrItem[1];
				}else if($arrItem[0] == "parent_id")
				{
					$parent_id = $arrItem[1];
				}else if($arrItem[0] == "type")
				{
					$type = $arrItem[1];
				}else if($arrItem[0] == "value")
				{
					$value = $arrItem[1];
				}else if($arrItem[0] == "data")
				{
					$data = $arrItem[1];
				}
			}
		}
		if($_id != "")
		{
			$layouts[count($layouts)] = [$_id, $parent_id, $type, $value, $data];
		}
	}
	echo "<h2>".$module_name."</h2>";
	$filters_list = $appSession->getTool()->split($filters, ',');
	for($i=0; $i<count($filters_list); $i++)
	{
	
		$filter_id = "";
		$parent_id = "";
		$column_name = "";
		$func = "";
		$op = "";
		$value = "";

		$logic = "";
		$type = "";
		$caption = "";
		$text = "";
		$lines = $appSession->getTool()->split( $filters_list[$i], ';');
		for($j=0; $j<count($lines); $j++)
		{
			$index = $appSession->getTool()->indexOf($lines[$j], ':');
			if($index != -1)
			{
				$n = substr($lines[$j], 0, $index);
				if($n == "o")
				{
					$op = substr($lines[$j] , $index + 1);
				}else if($n == "v")
				{
					$value = substr($lines[$j] , $index + 1);
				}else if($n == "l")
				{
					$logic = substr($lines[$j] , $index + 1);
				}else if($n == "c")
				{
					$caption = substr($lines[$j] , $index + 1);
				}else if($n == "x")
				{
					$text = substr($lines[$j] , $index + 1);
				}
			}
		}
		if($caption != "")
		{
			if($op == "equal")
			{
				$op = "=";
			}else if($op == "greater_or_equal")
			{
				$op = ">=";
			}else if($op == "not_equal")
			{
				$op = " != ";
			}else if($op == "less")
			{
				$op = " < ";
			}else if($op == "less_or_equal")
			{
				$op = " <= ";
			}else if($op == "greater")
			{
				$op = " > ";
			}
			if($text == "")
			{
				$text = $value;
			}
			
			if(strlen($text)==36 || $appSession->getTool()->indexOf($text, "~") != -1)
			{
				
				
				$ls = $appSession->getTool()->split($text, "~");
				$text = "";
				$ids = "";
			
				for($i =0; $i<count($ls); $i++)
				{
					if($ls[$i] == "")
					{
						continue;
					}
					if($ids != "")
					{
						$ids = $ids." OR ";
					}
					$ids = $ids." id='".$ls[$i]."'";
				}
				if($ids == "")
				{
					$ids = "1=0";
				}
				$sql = "SELECT user_name FROM res_user WHERE (".$ids.")";
				$msg->add("query", $sql);
				
				
				$users = $appSession->getTier()->getArray($msg);
				for($i=0; $i<count($users); $i++)
				{
					if($text != "")
					{
						$text = $text.", ";
					}
					$text = $text.$users[$i][0];
				}
				
				$ids = "";
				for($i =0; $i<count($ls); $i++)
				{
					if($ls[$i] == "")
					{
						continue;
					}
					if($ids != "")
					{
						$ids = $ids." OR ";
					}
					$ids = $ids." d1.id='".$ls[$i]."'";
				}
				if($ids == "")
				{
					$ids = "1=0";
				}
				$sql = "SELECT d2.category_name FROM module_category_structure d1 LEFT OUTER JOIN module_category d2 ON(d1.category_id = d2.id) WHERE (".$ids.")";
				$msg->add("query", $sql);
				$users = $appSession->getTier()->getArray($msg);
				for($i=0; $i<count($users); $i++)
				{
					if($text != "")
					{
						$text = $text.", ";
					}
					$text = $text.$users[$i][0];
				}
			}
			echo "".$caption." ".$op." ".$text."<br>";
		}
		
	}
	echo "<br>";
	parseLine($appSession, $layouts, "", $module_id, $id, $params, $root_module_id, $view_module_id);
	
	
?>
</body>
</html>

