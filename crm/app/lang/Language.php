<?php
class Language
{
	private $atts = [];
	private $path = '';
	private $lang = "vi";
	function load($tier, $rel_id, $lang_id)
	{
		$sql = "SELECT name, description FROM res_lang_line WHERE rel_id='".$rel_id."' AND lang_id='".$lang_id."' AND status =0";
		
		$msg = $tier->createMessage();
		$msg->add("query", $sql);
		$arr = $tier->getArray($msg);
	
		for($i =0; $i<count($arr); $i++)
		{
			$this->add($arr[$i][0], $arr[$i][1]);
		}
	}
	function getLang()
	{
		return $this->lang;
	}
	function hasKey($key)
	{
		for($i=0; $i<count($this->atts); $i++)
		{
			if($this->atts[$i][0] == $key)
			{
				return true;
			}
		}
		return false;
	}
	function parse($s)
	{
		$arr = explode(';', $s);
		for($i =0; $i<count($arr); $i++)
		{
			$index = strpos($arr[$i]);
			if($index !== false)
			{
				$key = substr($arr[$i], 0, $index);
				$value = substr($arr[$i], $index + 1);
				$this->add($key, $value);
			}
		}
	}
	function setProperty($key, $value)
	{
		$this->add($key, $value);
	}
	function getProperty($key)
	{
		return $this->find($key);
	}
	function add($key, $value)
	{
		for($i=0; $i<count($this->atts); $i++)
		{
			if($this->atts[$i][0] == $key)
			{
				$this->atts[$i][1] = $value;
				return;
			}
			
		}
		$this->atts[count($this->atts)] =[$key, $value];
	}
	function find($key)
	{
		for($i=0; $i<count($this->atts); $i++)
		{
			if($this->atts[$i][0] == $key)
			{
				$value = $this->atts[$i][1];
				if($value == "")
				{
					return $key;
				}
				return $value ;
			}
			
		}
		return $key;
	}
	function findKey($key)
	{
		for($i=0; $i<count($this->atts); $i++)
		{
			if($this->atts[$i][1] == $key)
			{
				$value = $this->atts[$i][0];
				if($value == "")
				{
					return $key;
				}
				return $value ;
			}
			
		}
		return $key;
	}
	function clear()
	{
		$this->atts = [];
	}
	
}

?>