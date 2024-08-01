<?php
class FormatINT
{
	private $config;
	function __construct($config) 
	{
		$this->config = $config;
	}
	function parse($value)
	{
		if($value == '')
		{
			return 0;
		}
		return (int)$value;
	}
	function format($value)
	{
		return number_format($value, 0, $this->config->getProperty("thousands_sep"), $this->config->getProperty("decimal_point"));
	}
}

?>