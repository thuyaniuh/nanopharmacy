<?php
class DataColumn
{
	private $name = "";
	private $type = ""; //i: Integer, f: float, d:date, 
	private $index =0;
	private $table = null;
	function __construct($name) {
		$this->name = $name;
	}
	function getName()
	{
		return $this->name;
	}
	function setName($name)
	{
		$this->name = $name;
	}
	function setType($type)
	{
		$this->type = $type;
	}
	function getType()
	{
		return $this->type;
	}
	function setIndex($index)
	{
		$this->index = $index;
	}
	function getIndex()
	{
		return $this->index;
	}
	
	
}

?>