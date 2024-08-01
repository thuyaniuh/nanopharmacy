<?php
class FormatDOUBLE
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
		return (double)$value;
	}
	function format($value)
	{
		$decimal_point = $this->config->getProperty("decimal_point");
		$thousands_sep = $this->config->getProperty("thousands_sep");
		if($decimal_point == "")
		{
			$decimal_point = ".";
		}
		if($thousands_sep == "")
		{
			$thousands_sep = ",";
		}
		if($thousands_sep == $decimal_point)
		{
			$decimal_point = ".";
			$thousands_sep = ",";
		}
		$value = number_format($value, 2, $decimal_point, $thousands_sep);
		$value = str_replace($decimal_point."00", "", $value);
		return $value;
	}
	function formatWith($value, $pattern)
	{
		$decimal_point = $this->config->getProperty("decimal_point");
		$thousands_sep = $this->config->getProperty("thousands_sep");
		if($decimal_point == "")
		{
			$decimal_point = ".";
		}
		if($thousands_sep == "")
		{
			$thousands_sep = ",";
		}
		if($thousands_sep == $decimal_point)
		{
			$decimal_point = ".";
			$thousands_sep = ",";
		}
		$value = number_format($value, 2, $decimal_point, $thousands_sep);
		$value = str_replace($decimal_point."00", "", $value);
		return $value;
	}
}

?>