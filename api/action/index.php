
<?php
function toHour($time)
{
	$arr = explode(":", $time);
	if(count($arr)>1)
	{
		return intval($arr[0]) + (intval($arr[1]) / 60);
	}
	return 0;
}
function calHour($from_time, $to_time, $ftime, $ttime, $padding_left, $padding_right, $factor)
{
	
	$a = toHour($from_time);
	$b = toHour($to_time);
	$c = toHour($ftime);
	$d = toHour($ttime);
	$f = 0;
	$t = 0;
	if($c<$a || ($c+$padding_left)<$a)
	{
		$f = $a;
	}else{
		$f = $c;
	}
	if($f>$b)
	{
		$f = $b;
	}
	if($d<$b || ($d + $padding_right)>$b){
		$t = $b;
	}else{
		$t = $d;
	}
	$result = ($t - $f) * $factor;
	
	
	return $result;
}
$ac = '';
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
$msg = $appSession->getTier()->createMessage();
if($ac == "ir_action_run")
{
	$action_id = '';
	if(isset($_REQUEST['action_id']))
	{
		$action_id = $_REQUEST['action_id'];
	}
	$rel_id = '';
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$id = '';
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	if($action_id == "5258df3f-3873-4961-89cc-3e5b513633f0")
	{
		if(URL_SERVICE == null || URL_SERVICE == "")
		{
			$id = '';
			if(isset($_REQUEST['rel_id']))
			{
				$id = $_REQUEST['rel_id'];
			}
			
			if(isset($_REQUEST['id']))
			{
				$id = $_REQUEST['id'];
			}
			$sql = "SELECT start_date, end_date, category_id, company_id FROM hr_timesheet WHERE id='".$id."'";
			$msg->add("query", $sql);

			$timesheet = $appSession->getTier()->getArray($msg);
			if(count($timesheet)>0)
			{
				
				$start_date = $timesheet[0][0];
				$end_date = $timesheet[0][1];
				$category_id = $timesheet[0][2];
				$company_id = $timesheet[0][3];
				$timesheet_id = $id;
				$sql = "SELECT id, code FROM hr_employee d1 WHERE company_id='".$company_id."'";
				$sql= $sql." AND code IN(";
				$sql = $sql."SELECT DISTINCT code FROM hr_timesheet_resource WHERE company_id='".$company_id."' AND status=0 AND create_date>='".$start_date."' AND create_date<='".$end_date."'";
				$sql = $sql.")";
				
				
				$msg->add("query", $sql);
				$dt_employee = $appSession->getTier()->getTable($msg);
				
				$sql = "SELECT DISTINCT d1.rel_id, d1.shift_id, d3.from_time, d3.to_time, d3.factor, d3.padding_left, d3.padding_right, d3.type, d5.code AS week_code FROM hr_shift_rel d1 LEFT OUTER JOIN hr_shift d2 ON(d1.shift_id = d2.id) LEFT OUTER JOIN hr_shift_time d3 ON(d2.id = d3.rel_id AND d3.status =0 AND (d3.type='TIME' OR d3.type='OT')) LEFT OUTER JOIN res_condition d4 ON(d2.id = d4.rel_id AND d4.status =0) LEFT OUTER JOIN res_condition_category d5 ON(d4.category_id = d5.id) WHERE d1.status = 0 AND d1.company_id='".$company_id."' AND d2.status =0 AND d3.status =0";
				
				
				
				$msg->add("query", $sql);
				$dt_shift = $appSession->getTier()->getTable($msg);
				
				$sql = "SELECT create_date, code FROM hr_timesheet_resource WHERE company_id='".$company_id."' AND status=0 AND create_date>='".$start_date."' AND create_date<='".$end_date."' ORDER BY code ASC, create_date ASC";
				$msg->add("query", $sql);
				$dt_resource = $appSession->getTier()->getTable($msg);
				
				
				
				$sql = "SELECT d2.start_date, d2.end_date, d1.employee_id, d3.factor  FROM hr_timesheet_ot_line d1 LEFT OUTER JOIN hr_timesheet_ot d2 ON(d1.ot_id = d2.id) LEFT OUTER JOIN hr_timesheet_ot_category d3 ON(d2.category_id = d3.id) WHERE d2.company_id='".$company_id."' AND d1.status=0 AND d2.receipt_date>='".$start_date."' AND d2.receipt_date<='".$end_date."'";
				$msg->add("query", $sql);
				
				
				
				$dt_ot = $appSession->getTier()->getTable($msg);
				
				$sql = "SELECT id, employee_id, start_date FROM hr_timesheet_time WHERE timesheet_id='".$id."'";
				$msg->add("query", $sql);
				$dt_timesheet_time = $appSession->getTier()->getTable($msg);
				
				$fd = date('Y-m-d', strtotime($start_date));
				$td = date('Y-m-d', strtotime($end_date));
					
				$sql_list = "";
				$sql_list_count = 0;
				
				
				
				for($i = 0; $i<$dt_employee->getRowCount(); $i++)
				{
					$employee_id = $dt_employee->getString($i, "id");
					
					$employee_code = $dt_employee->getString($i, "code");
					$shift_id = "";
					$dDate = $fd;
				 
					while(true)
					{
						$fdate = '';
						$tdate = "";
						
						
						
						$week_code =  strtoupper(date('D', strtotime($dDate)));
						
						
						for($j = 0; $j<$dt_resource->getRowCount(); $j++)
						{
							
							if($dt_resource->getString($j, "code") == $employee_code)
							{
								
								$log_date = date('Y-m-d', strtotime($dt_resource->getString($j, "create_date")));
								if($log_date == $dDate)
								{
								
									if($fdate == "")
									{
										$fdate = $dt_resource->getString($j, "create_date");
									}else{
										$tdate = $dt_resource->getString($j, "create_date");
									}
								}
							}
						}
						$ot_amount = 0;
						for($j = 0; $j<$dt_ot->getRowCount(); $j++)
						{
							
							if($dt_ot->getString($j, "employee_id") == $employee_id)
							{
								
								$log_date = date('Y-m-d', strtotime($dt_ot->getString($j, "start_date")));
								if($log_date == $dDate)
								{
									
									$factor = $dt_ot->getString($j, "factor");
									if($factor == "" || $factor == "0")
									{
										$factor = 1;
									}
									$datetime1 = strtotime($dt_ot->getString($j, "start_date"));
									$datetime2 = strtotime($dt_ot->getString($j, "end_date"));
									$secs = $datetime2 - $datetime1;// == return sec in difference
									$days = $secs / 86400;
								
									$ot_amount = $ot_amount + (($days * 24) * $factor);
									if($fdate == "")
									{
										$fdate = $dt_ot->getString($j, "start_date");
									}
									
								}
							}
						}
						
						if($fdate != "")
						{
							
							
							
							$check_id = "";
							
							for($j = 0; $j<$dt_timesheet_time->getRowCount(); $j++)
							{	
							
								
								if($dt_timesheet_time->getString($j, "employee_id") == $employee_id)
								{
									
									$log_date = date('Y-m-d', strtotime($dt_timesheet_time->getString($j, "start_date")));
									
									if($log_date == $dDate)
									{
										$check_id = $dt_timesheet_time->getString($j, "id");
										break;
									}
								}
								
							}
							
							
							$time_amount = 0;
							
						
							if($fdate != "" && $tdate != "")
							{
								
								$ftime = explode(" ", $fdate)[1];
								$ttime = explode(" ", $tdate)[1];
								
								
								for($j = 0; $j<$dt_shift->getRowCount(); $j++)
								{
								
									if($dt_shift->getString($j, "rel_id") == $employee_id && $dt_shift->getString($j, "week_code") == $week_code)
									{
										
										$shift_id = $dt_shift->getString($j, "shift_id");
										if($dt_shift->getString($j, "type") == "TIME")
										{
											$from_time = $dt_shift->getString($j, "from_time");
											$to_time = $dt_shift->getString($j, "to_time");
											$factor = $dt_shift->getString($j, "factor");
											if($factor == "" || $factor == "0" )
											{
												$factor = 1;
											}
											$padding_left = $dt_shift->getString($j, "padding_left");
											if($padding_left == "")
											{
												$padding_left = 0;
											}
											$padding_right = $dt_shift->getString($j, "padding_right");
											if($padding_right == "")
											{
												$padding_right = 0;
											}
											
											$time_amount =  $time_amount + calHour($from_time, $to_time, $ftime, $ttime, $padding_left, $padding_right, $factor);
											
										}
										if($dt_shift->getString($j, "type") == "OT")
										{
											$from_time = $dt_shift->getString($j, "from_time");
											$to_time = $dt_shift->getString($j, "to_time");
											$factor = $dt_shift->getString($j, "factor");
											if($factor == "" || $factor == "0" )
											{
												$factor = 1;
											}
											$padding_left = $dt_shift->getString($j, "padding_left");
											if($padding_left == "")
											{
												$padding_left = 0;
											}
											$padding_right = $dt_shift->getString($j, "padding_right");
											if($padding_right == "")
											{
												$padding_right = 0;
											}
											$ot_amount += calHour($from_time, $to_time, $ftime, $ttime, $padding_left, $padding_right, $factor);
										}
										
									}
									
								}
								
							}
							
							
						
							if($check_id != "")
							{
								$sql = "UPDATE hr_timesheet_time SET start_date='".$fdate."'";
								if($tdate != "")
								{
									$sql = $sql.", end_date='".$tdate."'";
								}else{
									$sql = $sql.", end_date=NULL";
								}
								$sql = $sql.", shift_id='".$shift_id."'";
								$sql = $sql.", time_amount=".$time_amount;
								$sql = $sql.", ot_amount=".$ot_amount;
								$sql = $sql." WHERE id='".$check_id."'";
								if($sql_list != "")
								{
									$sql_list = $sql_list.";";
								}
								$sql_list = $sql_list.$sql;
								$sql_list_count = $sql_list_count + 1;
								if($sql_list_count>=1000)
								{
									
									
									$msg->add("query", $sql_list);
									$r = $appSession->getTier()->exec($msg);
									$sql_list = "";
									$sql_list_count =0;
								}
								
								
							}else{
								$sql = "INSERT INTO hr_timesheet_time(";
								$sql = $sql."id";
								$sql = $sql.", create_date";
								$sql = $sql.", write_date";
								$sql = $sql.", company_id";
								$sql = $sql.", status";
								$sql = $sql.", start_date";
								$sql = $sql.", end_date";
								$sql = $sql.", employee_id";
								$sql = $sql.", shift_id";
								$sql = $sql.", timesheet_id";
								$sql = $sql.", time_amount";
								$sql = $sql.", ot_amount";
								$sql = $sql.")VALUES(";
								$sql = $sql."'".$appSession->getTool()->getId()."'";
								$sql = $sql.", NOW()";
								$sql = $sql.", NOW()";
								$sql = $sql.", '".$company_id."'";
								$sql = $sql.", 0";
								$sql = $sql.", '".$fdate."'";
								if($tdate != "")
								{
									$sql = $sql.",'".$tdate."'";
								}else{
									$sql = $sql.", NULL";
								}
								$sql = $sql.", '".$employee_id."'";
								$sql = $sql.", '".$shift_id."'";
								$sql = $sql.", '".$timesheet_id."'";
								$sql = $sql.", ".$time_amount;
								$sql = $sql.", ".$ot_amount;
								$sql = $sql.")";
								if($sql_list != "")
								{
									$sql_list = $sql_list.";";
								}
								$sql_list = $sql_list.$sql;
								$sql_list_count = $sql_list_count + 1;
								
								if($sql_list_count>=1000)
								{
									$msg->add("query", $sql_list);
									$r = $appSession->getTier()->exec($msg);
									$sql_list = "";
									$sql_list_count =0;
								}
								
							}
						}
						if($dDate>$td)
						{
							break;
						}
						$dDate = date('Y-m-d', strtotime("+1 days", strtotime($dDate)));
						
					
					}
					
				}
				if($sql_list != "")
				{
					$msg->add("query", $sql_list);
					$r = $appSession->getTier()->exec($msg);
				}
				
			}
			echo "OK";
			exit();
		}
	}
	if(SERVICE_URL != null && SERVICE_URL != "")
	{
		$description = '';
		if(isset($_REQUEST['description']))
		{
			$description = $_REQUEST['description'];
		}
	
		$db_id = $appSession->getConfig()->getProperty("db_id");
		$db_id = "dd4c0bfe-de03-41be-aaa4-8fe04d17dfa5";
		$params = "action_id=".$action_id."&rel_id=".$rel_id."&id=".$id."&api_id=".$db_id;
		$params = $params."&user_id=".$appSession->getConfig()->getProperty("user_id");
		$params = $params."&company_id=".$appSession->getConfig()->getProperty("company_id");
		if($description != "")
		{
			$params = $params.$description;
		}
		echo $appSession->getTool()->httpPost(URL_SERVICE."action", $params);
	}
	
	
}
if($ac == "hr_timesheet_time")
{
	$id = '';
	if(isset($_REQUEST['rel_id']))
	{
		$id = $_REQUEST['rel_id'];
	}
	
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sql = "SELECT start_date, end_date, category_id, company_id FROM hr_timesheet WHERE id='".$id."'";
	$msg->add("query", $sql);

	$timesheet = $appSession->getTier()->getArray($msg);
	if(count($timesheet)>0)
	{
		
		$start_date = $timesheet[0][0];
		$end_date = $timesheet[0][1];
		$category_id = $timesheet[0][2];
		$company_id = $timesheet[0][3];
		$timesheet_id = $id;
		$sql = "SELECT id, code FROM hr_employee d1 WHERE company_id='".$company_id."'";
		$sql= $sql." AND code IN(";
		$sql = $sql."SELECT DISTINCT code FROM hr_timesheet_resource WHERE company_id='".$company_id."' AND status=0 AND create_date>='".$start_date."' AND create_date<='".$end_date."'";
		$sql = $sql.")";
		
		
		$msg->add("query", $sql);
		$dt_employee = $appSession->getTier()->getTable($msg);
		
		$sql = "SELECT DISTINCT d1.rel_id, d1.shift_id, d3.from_time, d3.to_time, d3.factor, d3.padding_left, d3.padding_right, d3.type, d5.code AS week_code FROM hr_shift_rel d1 LEFT OUTER JOIN hr_shift d2 ON(d1.shift_id = d2.id) LEFT OUTER JOIN hr_shift_time d3 ON(d2.id = d3.rel_id AND d3.status =0 AND (d3.type='TIME' OR d3.type='OT')) LEFT OUTER JOIN res_condition d4 ON(d2.id = d4.rel_id AND d4.status =0) LEFT OUTER JOIN res_condition_category d5 ON(d4.category_id = d5.id) WHERE d1.status = 0 AND d1.company_id='".$company_id."' AND d2.status =0 AND d3.status =0";
		
		
		
		$msg->add("query", $sql);
		$dt_shift = $appSession->getTier()->getTable($msg);
		
		$sql = "SELECT create_date, code FROM hr_timesheet_resource WHERE company_id='".$company_id."' AND status=0 AND create_date>='".$start_date."' AND create_date<='".$end_date."' ORDER BY code ASC, create_date ASC";
		$msg->add("query", $sql);
		$dt_resource = $appSession->getTier()->getTable($msg);
		
		
		
		$sql = "SELECT d2.start_date, d2.end_date, d1.employee_id, d3.factor  FROM hr_timesheet_ot_line d1 LEFT OUTER JOIN hr_timesheet_ot d2 ON(d1.ot_id = d2.id) LEFT OUTER JOIN hr_timesheet_ot_category d3 ON(d2.category_id = d3.id) WHERE d2.company_id='".$company_id."' AND d1.status=0 AND d2.receipt_date>='".$start_date."' AND d2.receipt_date<='".$end_date."'";
		$msg->add("query", $sql);
		
		
		
		$dt_ot = $appSession->getTier()->getTable($msg);
		
		$sql = "SELECT id, employee_id, start_date FROM hr_timesheet_time WHERE timesheet_id='".$id."'";
		$msg->add("query", $sql);
		$dt_timesheet_time = $appSession->getTier()->getTable($msg);
		
		$fd = date('Y-m-d', strtotime($start_date));
		$td = date('Y-m-d', strtotime($end_date));
			
		$sql_list = "";
		$sql_list_count = 0;
		
		
		
		for($i = 0; $i<$dt_employee->getRowCount(); $i++)
		{
			$employee_id = $dt_employee->getString($i, "id");
			
			$employee_code = $dt_employee->getString($i, "code");
			$shift_id = "";
			$dDate = $fd;
		 
			while(true)
			{
				$fdate = '';
				$tdate = "";
				
				
				
				$week_code =  strtoupper(date('D', strtotime($dDate)));
				
				
				for($j = 0; $j<$dt_resource->getRowCount(); $j++)
				{
					
					if($dt_resource->getString($j, "code") == $employee_code)
					{
						
						$log_date = date('Y-m-d', strtotime($dt_resource->getString($j, "create_date")));
						if($log_date == $dDate)
						{
						
							if($fdate == "")
							{
								$fdate = $dt_resource->getString($j, "create_date");
							}else{
								$tdate = $dt_resource->getString($j, "create_date");
							}
						}
					}
				}
				$ot_amount = 0;
				for($j = 0; $j<$dt_ot->getRowCount(); $j++)
				{
					
					if($dt_ot->getString($j, "employee_id") == $employee_id)
					{
						
						$log_date = date('Y-m-d', strtotime($dt_ot->getString($j, "start_date")));
						if($log_date == $dDate)
						{
							
							$factor = $dt_ot->getString($j, "factor");
							if($factor == "" || $factor == "0")
							{
								$factor = 1;
							}
							$datetime1 = strtotime($dt_ot->getString($j, "start_date"));
							$datetime2 = strtotime($dt_ot->getString($j, "end_date"));
							$secs = $datetime2 - $datetime1;// == return sec in difference
							$days = $secs / 86400;
						
							$ot_amount = $ot_amount + (($days * 24) * $factor);
							if($fdate == "")
							{
								$fdate = $dt_ot->getString($j, "start_date");
							}
							
						}
					}
				}
				
				if($fdate != "")
				{
					
					
					
					$check_id = "";
					
					for($j = 0; $j<$dt_timesheet_time->getRowCount(); $j++)
					{	
					
						
						if($dt_timesheet_time->getString($j, "employee_id") == $employee_id)
						{
							
							$log_date = date('Y-m-d', strtotime($dt_timesheet_time->getString($j, "start_date")));
							
							if($log_date == $dDate)
							{
								$check_id = $dt_timesheet_time->getString($j, "id");
								break;
							}
						}
						
					}
					
					
					$time_amount = 0;
					
				
					if($fdate != "" && $tdate != "")
					{
						
						$ftime = explode(" ", $fdate)[1];
						$ttime = explode(" ", $tdate)[1];
						
						
						for($j = 0; $j<$dt_shift->getRowCount(); $j++)
						{
						
							if($dt_shift->getString($j, "rel_id") == $employee_id && $dt_shift->getString($j, "week_code") == $week_code)
							{
								
								$shift_id = $dt_shift->getString($j, "shift_id");
								if($dt_shift->getString($j, "type") == "TIME")
								{
									$from_time = $dt_shift->getString($j, "from_time");
									$to_time = $dt_shift->getString($j, "to_time");
									$factor = $dt_shift->getString($j, "factor");
									if($factor == "" || $factor == "0" )
									{
										$factor = 1;
									}
									$padding_left = $dt_shift->getString($j, "padding_left");
									if($padding_left == "")
									{
										$padding_left = 0;
									}
									$padding_right = $dt_shift->getString($j, "padding_right");
									if($padding_right == "")
									{
										$padding_right = 0;
									}
									
									$time_amount =  $time_amount + calHour($from_time, $to_time, $ftime, $ttime, $padding_left, $padding_right, $factor);
									
								}
								if($dt_shift->getString($j, "type") == "OT")
								{
									$from_time = $dt_shift->getString($j, "from_time");
									$to_time = $dt_shift->getString($j, "to_time");
									$factor = $dt_shift->getString($j, "factor");
									if($factor == "" || $factor == "0" )
									{
										$factor = 1;
									}
									$padding_left = $dt_shift->getString($j, "padding_left");
									if($padding_left == "")
									{
										$padding_left = 0;
									}
									$padding_right = $dt_shift->getString($j, "padding_right");
									if($padding_right == "")
									{
										$padding_right = 0;
									}
									$ot_amount += calHour($from_time, $to_time, $ftime, $ttime, $padding_left, $padding_right, $factor);
								}
								
							}
							
						}
						
					}
					
					
				
					if($check_id != "")
					{
						$sql = "UPDATE hr_timesheet_time SET start_date='".$fdate."'";
						if($tdate != "")
						{
							$sql = $sql.", end_date='".$tdate."'";
						}else{
							$sql = $sql.", end_date=NULL";
						}
						$sql = $sql.", shift_id='".$shift_id."'";
						$sql = $sql.", time_amount=".$time_amount;
						$sql = $sql.", ot_amount=".$ot_amount;
						$sql = $sql." WHERE id='".$check_id."'";
						if($sql_list != "")
						{
							$sql_list = $sql_list.";";
						}
						$sql_list = $sql_list.$sql;
						$sql_list_count = $sql_list_count + 1;
						if($sql_list_count>=1000)
						{
							
							
							$msg->add("query", $sql_list);
							$r = $appSession->getTier()->exec($msg);
							$sql_list = "";
							$sql_list_count =0;
						}
						
						
					}else{
						$sql = "INSERT INTO hr_timesheet_time(";
						$sql = $sql."id";
						$sql = $sql.", create_date";
						$sql = $sql.", write_date";
						$sql = $sql.", company_id";
						$sql = $sql.", status";
						$sql = $sql.", start_date";
						$sql = $sql.", end_date";
						$sql = $sql.", employee_id";
						$sql = $sql.", shift_id";
						$sql = $sql.", timesheet_id";
						$sql = $sql.", time_amount";
						$sql = $sql.", ot_amount";
						$sql = $sql.")VALUES(";
						$sql = $sql."'".$appSession->getTool()->getId()."'";
						$sql = $sql.", NOW()";
						$sql = $sql.", NOW()";
						$sql = $sql.", '".$company_id."'";
						$sql = $sql.", 0";
						$sql = $sql.", '".$fdate."'";
						if($tdate != "")
						{
							$sql = $sql.",'".$tdate."'";
						}else{
							$sql = $sql.", NULL";
						}
						$sql = $sql.", '".$employee_id."'";
						$sql = $sql.", '".$shift_id."'";
						$sql = $sql.", '".$timesheet_id."'";
						$sql = $sql.", ".$time_amount;
						$sql = $sql.", ".$ot_amount;
						$sql = $sql.")";
						if($sql_list != "")
						{
							$sql_list = $sql_list.";";
						}
						$sql_list = $sql_list.$sql;
						$sql_list_count = $sql_list_count + 1;
						
						if($sql_list_count>=1000)
						{
							$msg->add("query", $sql_list);
							$r = $appSession->getTier()->exec($msg);
							$sql_list = "";
							$sql_list_count =0;
						}
						
					}
				}
				if($dDate>$td)
				{
					break;
				}
				$dDate = date('Y-m-d', strtotime("+1 days", strtotime($dDate)));
				
			
			}
			
		}
		if($sql_list != "")
		{
			$msg->add("query", $sql_list);
			$r = $appSession->getTier()->exec($msg);
		}
		
	}
	echo "OK";
	
	
}else if($ac == "add_task")
{
	$name = '';
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	
	$description = '';
	if(isset($_REQUEST['description']))
	{
		$description = $_REQUEST['description'];
	}
	$sql = "INSERT INTO project_task(";
	$sql = $sql."id";
	$sql = $sql.", create_date";
	$sql = $sql.", write_date";
	$sql = $sql.", company_id";
	$sql = $sql.", status";
	$sql = $sql.", start_date";
	$sql = $sql.", end_date";
	$sql = $sql.", name";
	$sql = $sql.", description";
	$sql = $sql.")VALUES(";
	$sql = $sql."'".$appSession->getTool()->getId()."'";
	$sql = $sql.", NOW()";
	$sql = $sql.", NOW()";
	$sql = $sql.", 'ROOT'";
	$sql = $sql.", 0";
	$sql = $sql.", NOW()";
	$sql = $sql.", NOW()";
	$sql = $sql.", '".$appSession->getTool()->replace($name, "'", "''")."'";
	$sql = $sql.", '".$appSession->getTool()->replace($description, "'", "''")."'";
	$sql = $sql.")";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}

?>