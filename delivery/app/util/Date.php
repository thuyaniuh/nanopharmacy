<?php

class Date
{
	private $y = 0;
	private $m = 0;
	private $d = 0;
	private $hh = 0;
	private $mm = 0;
	private $ss = 0;
	private $ms=0;
	function __construct($y, $m, $d, $hh, $mm, $ss, $ms) 
	{
		$this->y = $y;
		$this->m = $m;
		$this->d = $d;
		$this->hh = $hh;
		$this->mm = $mm;
		$this->ss = $ss;
		$this->ms = $ms;
	}
	function toString()
	{
		return $this->y."-".$this->m."-".$this->d." ".$this->hh.":".$this->mm.":".$this->ss.".".$this->ms;
	}
	function getYears()
	{
		return $this->y;
	}
	function getMonths()
	{
		return $this->m;
	}
	function getDays()
	{
		return $this->d;
	}
	function getHours()
	{
		return $this->hh;
	}
	function getMinutes()
	{
		return $this->mm;
	}
	function getSeconds()
	{
		return $this->ss;
	}
	function getMilliseconds()
	{
		return $this->ms;
	}
	function getYY()
	{
		$s = "".$this->y;
		if(strlen($s)>1)
		{
			$s = substr($s, 2, 2);
		}
		return $s;
	}
	
}
?>