<?php
class AppConfig
{
	private $atts = [];
	private $path = '';
	function load($path)
	{
		
		$this->path = $path;
		if(file_exists($path))
		{
			$file = fopen($path, 'r');
			if ($file)
			{
				while (($line = fgets($file)) !== false) 
				{
					
					$index = strpos($line, '=');
	
					if($index !== false)
					{
						$key = trim(substr($line, 0, $index));
						$value = trim(substr($line, $index + 1));
					
						$this->add($key, $value);
					}
				}
			}
			fclose($file);

		}
	}
	function save()
	{
		if($this->path != "")
		{
			
			$s = "";
			for($i=0; $i<count($this->atts); $i++)
			{
				if($s != "")
				{
					$s = $s."\n";
				}
				$s = $s.$this->atts[$i][0]."=".$this->atts[$i][1];
			}
			if(file_exists($this->path))
			{
				
			}
			$f=fopen($this->path,'w');
			fwrite($f, $s);
			fclose($f);
		}
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
				return $this->atts[$i][1] ;
			}
			
		}
		return "";
	}
	function clear()
	{
		$this->atts = [];
	}
	
}

?>