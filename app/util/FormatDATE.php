<?php
class FormatDATE
{
	private $config;
	private $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	private $months_full = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	function __construct($config) 
	{
		$this->config = $config;
	}
	function parse($value)
	{
		return $value;
	}
	function format($value)
	{
		return $value->toString();
	}
	function formatWith($value, $fm)
	{
		
		if($value->getMonths()>0)
		{
			$fm = str_replace("MMMM", $this->months_full[$value->getMonths()-1], $fm);
			$fm = str_replace("MMM", $this->months[$value->getMonths()-1], $fm);
		}
		
		
		$fm = str_replace("YYYY", $value->getYears(), $fm);
		$fm = str_replace("MM", $value->getMonths(), $fm);
		$fm = str_replace("DD", $value->getDays(), $fm);
		$fm = str_replace("hh", $value->getHours(), $fm);
		$fm = str_replace("mm", $value->getMinutes(), $fm);
		$fm = str_replace("ss", $value->getSeconds(), $fm);
		
		return $fm;
	}
	function formatDate($value)
	{
		
		$fm = $this->config->getProperty("date_format");
		if($fm == "")
		{
			$fm= "YYYY-MM-DD";
		}
		

		return $this->formatWith($value, $fm);
	}
	function formatDateTime($value)
	{
		$fm = $this->config->getProperty("date_format");
		if($fm == "")
		{
			$fm= "YYYY-MM-DD hh:mm:ss";
		}
		return $this->formatWith($value, $fm);
	}
	function formatTime($value)
	{
		$fm = $this->config->getProperty("time_format");
		if($fm == "")
		{
			$fm= "hh:mm:ss";
		}
		
		return $this->formatWith($value, $fm);
	}
	function formatShortDateTime($value)
	{
		$fm = $this->config->getProperty("date_format");
		if($fm == "")
		{
			$fm= "YYYY-MM-DD";
		}
		$fm = $fm." hh:mm";
		
		return $this->formatWith($value, $fm);
	}
}

?>