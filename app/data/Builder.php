<?php
class Builder
{
	private $name = "";
	private $datas = [];
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
	function clear()
	{
		$this->datas = [];
	}
	function add($name, $value, $type='')
	{
		for($i=0; $i<count($this->datas); $i++)
		{
			if($this->datas[$i][0] == $name)
			{
				$this->datas[$i][1] = $value;
				$this->datas[$i][2] = $type;
				return;
			}
		}
		$this->datas[count($this->datas)] =[$name, $value, $type];
	}
	function getData()
	{
		return $this->datas;
	}
	
}

?>