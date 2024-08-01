<?php
require_once('DataTier.php' );
set_time_limit(2000); // 
class PostgreSQLTier extends DataTier
{
	private $db = NULL;
	private $host = "localhost";
	private $port = "5432";
	private $connection ="";
	function __construct($name, $host, $port, $user, $password) {
		$this->type = 'postgresql';
		$this->name = $name;
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->password = $password;
		$this->connection = "host=".$host." port=".$port." dbname=".$name." user=".$user." password=".$password;
		
	}
	public function getHost()
	{
		return $this->host;
	}
	public function getPort()
	{
		return $this->port;
	}
	public function getUser()
	{
		return $this->user;
	}
	public function getPassword()
	{
		return $this->password;
	}
	function open()
	{
		if($this->db == NULL)
		{
			$this->db = pg_Connect($this->connection);
		}
		
	}
	function close()
	{
		if($this->db != NULL)
		{
			pg_close($this->db);
		}
		
	}
	
	public function validColumn($name)
	{
		return '"'.$name.'"';
	}
	function exec($msg)
	{
		$sql = $msg->get("query");
		$this->open();
		$result = pg_exec($this->db, $sql);
		$error = pg_result_error($result);
		
		if($error != "")
		{
			return -1;
		}
		//$this->close();
		return 1;
	}
	
	function getValue($msg)
	{
		$sql = $msg->get("query");
		$this->open();
		$result = pg_exec($this->db, $sql);
		
		$numrows = pg_numrows($result);	
		$value = "";
		if($numrows>0)
		{
			$row = pg_fetch_array($result, 0);
			$value = $row[0];
		}
		//$this->close();
		return $value;
	}
	function getArray($msg)
	{
		$sql = $msg->get("query");
		$this->open();
		$data = [];
		$result = pg_exec($this->db, $sql);
		$numrows = pg_numrows($result);	
		
		$fields = pg_num_fields($result);
		for($i=0; $i<$numrows; $i++)
		{
			$row = pg_fetch_array($result, $i);
			$arr = [];
			for($j =0; $j<$fields; $j++)
			{
				$arr[count($arr)] = $row[$j];
			}
			$data[count($data)] = $arr;
		}

		return $data;
	}
	function getTable($msg)
	{
		$sql = $msg->get("query");
		$this->open();
		$dt = new DataTable("");
		$result = pg_exec($this->db, $sql);
		$numrows = pg_numrows($result);	
		$fields = pg_num_fields($result);
		for($n =0; $n<$fields; $n++)
		{
			$fieldname = pg_field_name($result, $n);
			$fieldtype = pg_field_type($result, $n);
			if($fieldtype == 'int2' || $fieldtype == 'int4' || $fieldtype== 'int8')
			{
				$fieldtype = 'i';
			}else if($fieldtype == 'float8')
			{
				$fieldtype = 'r';
			}else if($fieldtype == 'timestamp')
			{
				$fieldtype = 'd';
			}
			else{
				$fieldtype = '';
			}
			$dt->addColumn($fieldname);
			$dt->getColumns()[count($dt->getColumns())-1]->setType($fieldtype);
		}
		for($i=0; $i<$numrows; $i++)
		{
			$row = pg_fetch_array($result, $i);
			
			for($j =0; $j<$fields; $j++)
			{
				$dt->setValueAt($row[$j], $i, $j);

			}
		}
		return $dt;
	}
	
}

?>