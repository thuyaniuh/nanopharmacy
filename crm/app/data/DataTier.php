<?php
require_once(ABSPATH.'app/data/DataTable.php' );
require_once(ABSPATH.'app/data/Builder.php' );
require_once(ABSPATH.'app/Message.php' );


class DataTier
{
	public $url =''; //mysql,postgresql,mssql, oracle
	public $user;
	public $password;
	public function __construct($url, $user, $password) {
		$this->url = $url;
		$this->user = $user;
		$this->password = $password;
	}
	public function getDateString()
	{
		return "NOW()";
	}
	public function paging($sql, $p, $ps, $sort)
	{
		$arr = [];
		$index = strpos($sql, "FROM", 5); 
		if($index == true)
		{
			$arr[1] = "SELECT COUNT(*) ".substr($sql, $index);
			
		}
		if($sort != "")
		{
			$sql = $sql." ORDER BY ".$sort;
		}
		$p = $p * $ps;
		$sql = $sql." OFFSET ".$p." LIMIT ".$ps;
		$arr[0] = $sql;
		
		return $arr;
	}
	public function limit($sql, $n)
	{
		return $sql." LIMIT ".$n;
	}
	function split($s, $c)
	{
		return explode( $c, $s);
	}
	function indexOf($s, $c)
	{
		return strpos ($s, $c);
	}
	public function condition_array($condition)
	{
		$arr = [];
		$conditions = explode(',', $condition);
		for($i =0; $i<count($conditions); $i++)
		{
			if($conditions[$i] != "")
			{
				$filter_id = "";
				$parent_id = "";
				$column_name = "";
				$func = "";
				$op = "";
				$value = "";
				$value_1 = "";
				$logic = "";
				$type = "";
				$caption = "";
				$lines = $this->split( $conditions[$i], ';');
				for($j=0; $j<count($lines); $j++)
				{
					$index = $this->indexOf($lines[$j], '=');
					if($index != -1)
					{
						$n = substr($lines[$j], 0, $index);
						if($n == "i")
						{
							$filter_id = substr($lines[$j] , $index + 1);
						}
						else if($n == "p")
						{
							$parent_id = substr($lines[$j] , $index + 1);
						}else if($n == "n")
						{
							$column_name = substr($lines[$j] , $index + 1);
						}else if($n == "f")
						{
							$func = substr($lines[$j] , $index + 1);
						}else if($n == "o")
						{
							$o = substr($lines[$j] , $index + 1);
						}else if($n == "v")
						{
							$value = substr($lines[$j] , $index + 1);
						}else if($n == "l")
						{
							$logic = substr($lines[$j] , $index + 1);
						}else if($n == "t")
						{
							$type = substr($lines[$j] , $index + 1);
						}else if($n == "c")
						{
							$caption = substr($lines[$j] , $index + 1);
						}
					}
				}
				if($filter_id != "")
				{
					$arr[count($arr)] =[$filter_id, $parent_id, $column_name, $func, $o, $value,  $logic, $type,  $caption];
				}
				
				
			}
		}
		return $arr;
	}
	public function buildCondition($conditions, $parent_id, $first)
	{
		$sr = "";
		$founds =[];
		for($i=0; $i<count($conditions); $i++)
		{
			if($conditions[$i][1] == $parent_id)
			{
				$founds[count($founds)] = $conditions[$i];
			}
		}
		
		for($i=0; $i<count($founds); $i++)
		{
			$s = "";
			//$filter_id, $parent_id, $column_name, $func, $o, $value,  $logic, $type, $caption
			
			$line = 0;
			$is_param = -1;
			if($founds[$i][5] != "")
			{
				$is_param = strpos('.', $founds[$i][5]);
			}
			if($is_param == false)
			{
				$is_param = -1;
			}
			
				
			if($first == 1)
			{
				if($founds[$i][6] == "")
				{
					$s = $s." AND  ";
				}else{
					$s = $s." ".$founds[$i][6]." ";
				}
			}
			$s1 = $this->buildCondition($conditions, $founds[$i][0], 1);
			if($s1 != "")
			{
				$s = $s."(";
				
			}
			
			$first = 1;
			$line = $line + 1;
			if($founds[$i][7] == "d")
			{
				$founds[$i][5] = str_replace("=", ":", $founds[$i][5]);
			}
				
			
			$s =$s.$founds[$i][2];
			
			if($founds[$i][4] == 'equal')
			{
				if($is_param ==-1)
				{
					if($founds[$i][7] == "s" || $founds[$i][7] == "d")
					{
						$s =$s." = '".$founds[$i][5]."'";
					}else{
						$s =$s." = ".$founds[$i][5];
					}
					
					
				}else{
					$s =$s." = '".$founds[$i][5]."'";
				}
				
			}else if($founds[$i][4] == 'not_equal')
			{
				if($is_param ==-1)
				{
					if($founds[$i][7] == "s" || $founds[$i][7] == "d")
					{
						$s =$s." != '".$founds[$i][5]."'";
					}else{
						$s =$s." != ".$founds[$i][5];
					}
					
					
				}else{
					$s =$s." != '".$founds[$i][5]."'";
				}
				
			}else if($founds[$i][4] == 'in')
			{
				if($founds[$i][7] == "s" || $founds[$i][7] == "d")
				{
					$items = explode("~", $founds[$i][5]);
					
					$s_in = "";
					for($n =0; $n<count($items); $n++)
					{
						if($n>0)
						{
							$s_in = $s_in.", ";
						}
						$s_in = $s_in."'".$items[$n]."'";
					}
					
					$s =$s." IN (".$s_in.")";
					
				}else{
					$s =$s." IN (".$founds[$i][5].")";
				}
				
			}else if($founds[$i][4] == 'not_in')
			{
				if($founds[$i][7] == "s" || $founds[$i][7] == "d")
				{
					$items = explode("~", $founds[$i][5]);
					
					$s_in = "";
					for($n =0; $n<count($items); $n++)
					{
						if($n>0)
						{
							$s_in = $s_in.", ";
						}
						$s_in = $s_in."'".$items[$n]."'";
					}
					
					$s =$s." NOT IN (".$s_in.")";
					
				}else{
					$s =$s." NOT IN (".$founds[$i][5].")";
				}
			}else if($founds[$i][4] == 'less')
			{
				if($founds[$i][7] == "s" || $founds[$i][7] == "d")
				{
					$s =$s." < '".$founds[$i][5]."'";
				}else{
					$s =$s." < ".$founds[$i][5]."";
				}
				
			}else if($founds[$i][4] == 'less_or_equal')
			{
				if($founds[$i][7] == "s" || $founds[$i][7] == "d")
				{
					$s =$s." <= '".$founds[$i][5]."'";
				}else{
					$s =$s." <= ".$founds[$i][5]."";
				}
				
			}else if($founds[$i][4] == 'greater')
			{
				if($founds[$i][7] == "s" || $founds[$i][7] == "d")
				{
					$s =$s." > '".$founds[$i][5]."'";
				}else{
					$s =$s." > ".$founds[$i][5]."";
				}
				
			}else if($founds[$i][4] == 'greater_or_equal')
			{
				if($founds[$i][7] == "s" || $founds[$i][7] == "d")
				{
					$s =$s." >= '".$founds[$i][5]."'";
				}else{
					$s =$s." >= ".$founds[$i][5]."";
				}
				
				
			}else if($founds[$i][4] == 'begins_with')
			{
				$s =$s." ILIKE '".$founds[$i][5]."%'";
			}else if($founds[$i][4] == 'not_begins_with')
			{
				$s =$s." NOT ILIKE '".$founds[$i][5]."%'";
			}else if($founds[$i][4] == 'contains')
			{
				$s =$s." ILIKE '%".$founds[$i][5]."%'";
			}else if($founds[$i][4] == 'not_contains')
			{
				$s =$s." NOT ILIKE '%".$founds[$i][5]."%'";
			}else if($founds[$i][4] == 'ends_with')
			{
				$s =$s." ILIKE '%".$founds[$i][5]."'";
			}else if($founds[$i][4] == 'not_ends_with')
			{
				$s =$s." NOT ILIKE '%".$founds[$i][5]."'";
			}else if($founds[$i][4] == 'is_empty')
			{
				$s =$s." = ''";
			}else if($founds[$i][4] == 'is_not_empty')
			{
				$s =$s." != ''";
			}else if($founds[$i][4] == 'is_null')
			{
				$s =$s." IS NULL";
			}else if($founds[$i][4] == 'is_not_null')
			{
				$s =$s." IS NOT NULL";
			}
			
			if($s1 != "")
			{
				
				$s = $s." ".$s1.")";
			}
			
			$sr = $sr.$s;
			
		}
		
		return $sr;
	}
	public function buildSQL($line, $parent_id, $params, $condition)
	{
		$sql = "";
		if($parent_id == "")
		{
			
			$columns = "";
			for($i=0; $i<count($line); $i++)
			{
				$table_index = 0;
				for($j=0; $j<$i; $j++)
				{
					if($line[$j][2] == $line[$i][2])
					{
						$table_index = $table_index + 1;
					}
				}
			
				$data = explode(';', $line[$i][3]);
				for($j=0; $j<count($data); $j++)
				{
					if($data[$j] != "")
					{
						if($columns != "")
						{
							$columns = $columns.", ";
						}
						$as = $line[$i][2];
						if($table_index>0)
						{
							$as = $line[$i][2]."".$table_index;
						}
					
						$columns = $columns."".$as.".".$data[$j]." AS ".$as."__".$data[$j];
					}
				}
				
			}
			
			$sql = "SELECT ".$columns;
		}
		for($i=0; $i<count($line); $i++)
		{
			
			if($line[$i][1] == $parent_id)
			{
				if($parent_id == "")
				{
					$sql = $sql." FROM ".$line[$i][2];
				}else
				{
					$table_index = 0;
					for($j=0; $j<$i; $j++)
					{
						if($line[$j][2] == $line[$i][2])
						{
							$table_index = $table_index + 1;
						}
					}
					$as = $line[$i][2];
					if($table_index>0)
					{
						$as = $line[$i][2]."".$table_index;
					}
					
					$where = $this->buildCondition($this->condition_array($line[$i][4]), 0, 0);
					for($j=0; $j<count($params); $j++)
					{
						$where = str_replace("{".$params[$j][0]."}", "'".$params[$j][2]."'", $where);
					}
			
					$sql = $sql." LEFT OUTER JOIN ".$line[$i][2]." ".$as." ON( ".$where.")";
				}
				$sql = $sql.$this->buildSQL($line, $line[$i][0], $params, $condition);
			}
		}
		if($parent_id == "" && count($condition)>0)
		{
			
			$where = $this->buildCondition($condition, 0, 0);
			for($j=0; $j<count($params); $j++)
			{
				$where = str_replace("{".$params[$j][0]."}", "'".$params[$j][2]."'", $where);
			}
			$sql = $sql." WHERE (".$where.")";
			
			
		}
		return $sql;
	}
	public function validColumn($name)
	{
		return $name;
	}
	public function buildSearch($arr, $search)
	{
		$s = "";
		for($i =0; $i<count($arr); $i++)
		{
			if($arr[$i] != "")
			{
				if($s != "")
				{
					$s = $s." OR ";
				}
				$s = $s.$arr[$i]." ILIKE '%".str_replace("'", "''", $search)."%'";
			}
			
		}
		return $s;
	}
	public function createBuilder($name)
	{
		return new Builder($name);
	}
	public function getBuilder($name)
	{
		return $this->createBuilder($name);
	}
	public function createMessage()
	{
		return new Message();
	}
	public function getMessage($name)
	{
		return $this->createMessage($name);
	}
	
	function getInsert($builder)
	{
		$sql= "INSERT INTO ".$builder->getName()." (";
		for($i=0; $i<count($builder->getData()); $i++)
		{
			if($i>0)
			{
				$sql = $sql.", ";
			}
			$sql = $sql.$this->validColumn($builder->getData()[$i][0]);
		}
		$sql = $sql.") VALUES (";
		for($i=0; $i<count($builder->getData()); $i++)
		{
			if($i>0)
			{
				$sql = $sql.", ";
			}
			if($builder->getData()[$i][2] == 'n'  )
			{
				if($builder->getData()[$i][1] == '')
				{
					$sql = $sql." NULL";
				}else{
					$sql = $sql.$builder->getData()[$i][1];
				}
				
			}else if($builder->getData()[$i][2] == 'd')
			{
				if($builder->getData()[$i][1] == '')
				{
					$sql = $sql." NULL";
				}else{
					$sql = $sql."'".$builder->getData()[$i][1]."'";
				}
			}else if($builder->getData()[$i][2] == 'i' ||$builder->getData()[$i][2] == 'r')
			{
				if($builder->getData()[$i][1] == '')
				{
					$sql = $sql." 0";
				}else{
					$sql = $sql."".$builder->getData()[$i][1];
				}
			}
			else if($builder->getData()[$i][2] == 'f')
			{
				$sql = $sql.$builder->getData()[$i][1];
			}
			else
			{
				
				$sql = $sql."'".str_replace("'", "''", $builder->getData()[$i][1]). "'";
				
			}
			
		}
		$sql = $sql.")";
		return $sql;
	}
	function getUpdate($builder)
	{
		$sql= "";
		$id = "";
		for($i=0; $i<count($builder->getData()); $i++)
		{
			if($builder->getData()[$i][0] == "id")
			{
				$id = $builder->getData()[$i][1];
				continue;
			}
			if($sql != "")
			{
				$sql = $sql.", ";
			}
			$sql = $sql.$this->validColumn($builder->getData()[$i][0])." =";
			if($builder->getData()[$i][2] == 'n')
			{
				if($builder->getData()[$i][1] == "")
				{
					$sql = $sql." NULL ";
				}else{
					$sql = $sql.$builder->getData()[$i][1];
				}
				
			}else if($builder->getData()[$i][2] == 'd')
			{
				if($builder->getData()[$i][1] == "")
				{
					$sql = $sql." NULL ";
				}else{
					$sql = $sql."'".$builder->getData()[$i][1]."'";
				}
			}else if($builder->getData()[$i][2] == 'i'|| $builder->getData()[$i][2] == 'f')
			{
				if($builder->getData()[$i][1] == "")
				{
					$sql = $sql." 0 ";
				}else{
					$sql = $sql."".$builder->getData()[$i][1];
				}
			}
			else if($builder->getData()[$i][2] == 'f')
			{
				$sql = $sql.$builder->getData()[$i][1];
			}
			else
			{
				$sql = $sql."'".str_replace("'", "''", $builder->getData()[$i][1]). "'";
			}
		}
		
		$sql = "UPDATE ".$builder->getName()." SET ".$sql;
		if($id == "")
		{
			$sql = $sql. " WHERE 1=0";
		}else
		{
			$sql = $sql." WHERE id='".$id."'";
		}
		return $sql;
	}
	public function httpPost($url, $data) {
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
		curl_setopt($ch,CURLOPT_TIMEOUT, 20);
		$response = curl_exec($ch);
		curl_close ($ch);
		return $response;
	}
	public function getData($msg)
	{
		return $this->httpPost($this->url, $msg->getMessage());
	}
	public function exec($msg)
	{
		$msg->add("action", "97871cc4-88db-4808-8649-965e90ac4c11");
		$response = $this->httpPost($this->url, $msg->getMessage());
		return "1";
	}
	public function update($builder)
	{
		return $this->exec($this->getUpdate($builder));
		
	}
	public function insert($builder)
	{
		return $this->exec($this->getInsert($builder));
	}
	
	public function getValue($msg)
	{
		
		$msg->add("action", "d35342bc-4c59-4166-f5cd-979b63da68b7");
		$response = $this->httpPost($this->url, $msg->getMessage());
		
		$arr = explode("\n", $response);
		$len = count($arr);
		if($len>2)
		{
			return explode("\t", $arr[2])[0];
		}
		return "";
	}
	public function getArray($msg)
	{
		
		$rows = [];
		$msg->add("action", "d35342bc-4c59-4166-f5cd-979b63da68b7");
		$response = $this->httpPost($this->url, $msg->getMessage());
		
		$arr = explode("\n", $response);
		$len = count($arr);
		for($i = 2; $i<$len; $i++)
		{
			$rows[count($rows)] = explode("\t", $arr[$i]);
		}
		return $rows;
		
	}
	public function getTable($msg)
	{
		$dt = new DataTable("");
		$msg->add("action", "d35342bc-4c59-4166-f5cd-979b63da68b7");
		$response = $this->httpPost($this->url, $msg->getMessage());
		
		$arr = explode("\n", $response);
		$len = count($arr);
		if($len>1)
		{
			$items = explode("\t", $arr[0]);
			for($j=0; $j<count($items); $j++)
			{
				$dt->addColumn($items[$j]);
			}
			$items =explode("\t", $arr[1]);
			for($j=0; $j<count($items); $j++)
			{
				$dt->getColumns()[count($dt->getColumns())-1]->setType($items[$j]);
			}
		}
		for($i = 2; $i<$len; $i++)
		{
			$dt->addArray(explode("\t", $arr[$i]));
		}
		return $dt;
	}
	public function getResource($msg)
	{
		
		$msg->add("action", "dde21454-bc06-494a-c87b-897b979fa867");
		return $this->httpPost($this->url, $msg->getMessage());
	}
	
	public function close()
	{
	}
}

?>