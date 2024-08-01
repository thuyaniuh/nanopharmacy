<?php
class Message
{
	
	private $datas = [];
	function __construct() {
		
	}
	function clear()
	{
		$this->datas = [];
	}
	function add($name, $value)
	{
		for($i=0; $i<count($this->datas); $i++)
		{
			if($this->datas[$i][0] == $name)
			{
				$this->datas[$i][1] = $value;
				return;
			}
		}
		$this->datas[count($this->datas)] =[$name, $value];
	}
	function set($name, $value)
	{
		$this->add($name, $value);
	}
	function find($name)
	{
		for($i=0; $i<count($this->datas); $i++)
		{
			if($this->datas[$i][0] == $name)
			{
				return $this->datas[$i][1];
			}
		}
		return "";
	}
	function get($name)
	{
		return $this->find($name);
	}
	function getData()
	{
		return $this->datas;
	}
	function getMessage()
	{
		$message = "";
		for($i=0; $i<count($this->datas); $i++)
		{
			if($message != "")
			{
				$message = $message."&";
			}
			$message = $message.$this->datas[$i][0]."=".urlencode($this->datas[$i][1]);
		}
		return $message;
	}
	
}

?>