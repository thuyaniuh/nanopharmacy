<?php
class LineDepartment
{

	public function findByEmployee($appSession, $employee_id)
	{
		$msg = $appSession->getTier()->createMessage();
		$sql = "SELECT d2.id, d2.name ";
		$sql = $sql." FROM hr_employee_rel d1";
		$sql = $sql." LEFT OUTER JOIN hr_line_department d2 ON(d1.rel_id = d2.id)";
		$sql = $sql." WHERE d1.status =0 AND d2.status =0 AND d1.employee_id='".$employee_id."'";
		$msg->add("query", $sql);
		$dt = $appSession->getTier()->getTable($msg);
		$values =[];
		for($i =0; $i<$dt->getRowCount(); $i++)
		{
			if($dt->getString($i, "id") != "")
			{
				$values[count($values)]=[$dt->getString($i, "id"), $dt->getString($i, "name")];
			}
			
		}
		return $values;
	}
	public function fillChildDepartment($dt, $parent_id)
	{
		$values =[];
		 for ($i = 0; $i < $dt->getRowCount(); $i++)
		 {
			 if ($dt->getString($i, "parent_id") == $parent_id)
			 {
				 $id = $dt->getString($i, "id");
				 $values[count($values)] = [ $id, $dt->getString($i, "name")];
				 $this->fillChildDepartment( $dt, $id);
			 }
		 }
		 return $values;
	}
	public function findTreeEmployee($appSession, $employee_id)
	{
		$msg = $appSession->getTier()->createMessage();
		$sql = "SELECT d2.id,  d2.name ";
		$sql = $sql." FROM hr_employee_rel d1";
		$sql = $sql." LEFT OUTER JOIN hr_line_department d2 ON(d1.rel_id = d2.id)";
		$sql = $sql." WHERE d1.status =0 AND d2.status =0 AND d1.employee_id='".$employee_id."'";
		$values = [];
		$msg->add("query", $sql);
		$dt = $appSession->getTier()->getTable($msg);
		
		for ($i = 0; $i < $dt->getRowCount(); $i++)
		{
			$values[count($values)] = [$dt->getString($i, "id"), $dt->getString($i, "name")];
		}
		$departmentList = [];
		if (count($values) > 0)
		{
			$sql = "SELECT d1.id, d1.parent_id, d1.name ";
			$sql = $sql." FROM hr_line_department d1";
			$sql = $sql." WHERE d1.status =0 AND (d1.company_id='".$appSession->getConfig()->getProperty("company_id")."' OR d1.company_id='".$appSession->getConfig()->getProperty("parent_company_id")."')";
			
			$msg->add("query", $sql);
			$dt = $appSession->getTier()->getTable($msg);
			
			for ($i = 0; $i < count($values); $i++)
			{
				$v = $this->fillChildDepartment($dt, $values[$i][0]);
				for($j =0; $j<count($v); $j++)
				{
					$departmentList[count($departmentList)] = $v[$j];
				}
			}
		}
		$ids = "d1.employee_id ='".$employee_id."'";
		for($i =0; $i<count($departmentList); $i++)
		{
			if($ids != ""){
				$ids = $ids." OR ";
			}
			$ids = $ids." d2.id='".$departmentList[$i][0]."'";
		}
		$sql = "SELECT d3.id, d3.code, d3.name ";
		$sql = $sql." FROM hr_employee_rel d1";
		$sql = $sql." LEFT OUTER JOIN hr_line_department d2 ON(d1.rel_id = d2.id)";
		$sql = $sql." LEFT OUTER JOIN hr_employee d3 ON(d1.employee_id = d3.id)";
		$sql = $sql." WHERE d1.status =0 AND d2.status =0";
		if($ids != "")
		{
			$sql = $sql." AND (".$ids.")";
		}else{
			$sql = $sql. " AND 1=0";
		}
		$sql." ORDER BY d3.code ASC ";
		$msg->add("query", $sql);
		
		$dt = $appSession->getTier()->getTable($msg);
		$values =[];
		for($i =0; $i<$dt->getRowCount(); $i++)
		{
			if($dt->getString($i, "id") != "")
			{
				$values[count($values)]=[$dt->getString($i, "id"), $dt->getString($i, "code"), $dt->getString($i, "name")];
			}
			
		}
		
		return $values;
	}
}

?>