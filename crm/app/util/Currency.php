<?php
class Currency
{
	private $config;
	private $currentList =[];
	function __construct($config) 
	{
		$this->config = $config;
	}
	function load($appSession)
	{
		$sql = "SELECT d1.id, d1.code, (SELECT rate FROM res_currency_rate WHERE status =0 AND currency_id = d1.id ORDER BY receipt_date DESC limit 1) AS rate, d1.rounding, d1.decimal_places, d1.thousands_sep, d1.decimal_point, d1.symbol, d1.symbol_position FROM res_currency d1 WHERE d1.status =0";
		$msg = $appSession->getTier()->createMessage();
		$msg->add("query", $sql);
		$this->currencyList = $appSession->getTier()->getArray($msg);
	}
	function findCurrencyId($code)
	{
		for($i =0; $i<count($this->currencyList); $i++)
		{
			if($this->currencyList[$i][1] == $code)
			{
				return $this->currencyList[$i][0];
			}
		}
		return "";
	}
	function convert($from, $to, $amount)
	{
		$from_rate = 1;
		for($i =0; $i<count($this->currencyList); $i++)
		{
			if($this->currencyList[$i][0] == $from)
			{
				if($this->currencyList[$i][2] != "")
				{
					$from_rate = floatval($this->currencyList[$i][2]);
				}
				break;
				
			}
		}
		$to_rate = 1;
		for($i =0; $i<count($this->currencyList); $i++)
		{
			if($this->currencyList[$i][0] == $to)
			{	
				if($this->currencyList[$i][2] != "")
				{
					$to_rate = floatval($this->currencyList[$i][2]);
				}
				break;
				
			}
		}
		if($from_rate == 0)
		{
			$from_rate = 1;
		}
		if($to_rate == 0)
		{
			$to_rate = 1;
		}
		return ($from_rate/$to_rate) * $amount;
	}
	function parse($value)
	{
		return $value;
	}
	function format($currency_id, $value)
	{
		if(!is_numeric($value))
		{
			$value = 0;
		}
		$value = floatval($value);
		$sign = "";
		if($value<0)
		{
			$sign = "-";
			$value = $value * -1;
		}
		if($currency_id == "")
		{
			for($i =0; $i<count($this->currencyList); $i++)
			{
				if($this->currencyList[$i][2] == "1")
				{
					$currency_id = $this->currencyList[$i][0];
					break;
				}
			}
		}
		for($i =0; $i<count($this->currencyList); $i++)
		{
			if($this->currencyList[$i][0] == $currency_id || $this->currencyList[$i][1] == $currency_id)
			{
				$rounding = $this->currencyList[$i][3];
				$decimal_places = $this->currencyList[$i][4];
				$thousands_sep = $this->currencyList[$i][5];
				$decimal_point = $this->currencyList[$i][6];
				$symbol = $this->currencyList[$i][7];
				$symbol_position = $this->currencyList[$i][8];
				
				$s_value = number_format($value, $decimal_places, $decimal_point, $thousands_sep);
				if($symbol_position == "l")
				{
					$s_value = $symbol.$s_value;
				}else
				{
					$s_value = $s_value.$symbol;
				}
				$s_value = $sign.$s_value;
				return $s_value;
			}
		}
		
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
		
	
		return $value;
	}
	function round($amount)
	{
		return $value;
	}
	
	function numInWords($num)
	{
		$nwords = array(
			0                   => 'không',
			1                   => 'một',
			2                   => 'hai',
			3                   => 'ba',
			4                   => 'bốn',
			5                   => 'năm',
			6                   => 'sáu',
			7                   => 'bảy',
			8                   => 'tám',
			9                   => 'chín',
			10                  => 'mười',
			11                  => 'mười một',
			12                  => 'mười hai',
			13                  => 'mười ba',
			14                  => 'mười bốn',
			15                  => 'mười lăm',
			16                  => 'mười sáu',
			17                  => 'mười bảy',
			18                  => 'mười tám',
			19                  => 'mười chín',
			20                  => 'hai mươi',
			30                  => 'ba mươi',
			40                  => 'bốn mươi',
			50                  => 'năm mươi',
			60                  => 'sáu mươi',
			70                  => 'bảy mươi',
			80                  => 'tám mươi',
			90                  => 'chín mươi',
			100                 => 'trăm',
			1000                => 'nghìn',
			1000000             => 'triệu',
			1000000000          => 'tỷ',
			1000000000000       => 'nghìn tỷ',
			1000000000000000    => 'ngàn triệu triệu',
			1000000000000000000 => 'tỷ tỷ',
		);
		$separate = ' ';
		$negative = ' âm ';
		$rltTen   = ' linh ';
		$decimal  = ' phẩy ';
		if (!is_numeric($num)) {
			$w = '#';
		} else if ($num < 0) {
			$w = $negative . $this->numInWords(abs($num));
		} else {
			if (fmod($num, 1) != 0) {
				$numInstr    = strval($num);
				$numInstrArr = explode(".", $numInstr);
				$w           = $this->numInWords(intval($numInstrArr[0])) . $decimal . $this->numInWords(intval($numInstrArr[1]));
			} else {
				$w = '';
				if ($num < 21) // 0 to 20
				{
					$w .= $nwords[$num];
				} else if ($num < 100) {
					// 21 to 99
					$w .= $nwords[10 * floor($num / 10)];
					$r = fmod($num, 10);
					if ($r > 0) {
						$w .= $separate . $nwords[$r];
					}

				} else if ($num < 1000) {
					// 100 to 999
					$w .= $nwords[floor($num / 100)] . $separate . $nwords[100];
					$r = fmod($num, 100);
					if ($r > 0) {
						if ($r < 10) {
							$w .= $rltTen . $separate . $this->numInWords($r);
						} else {
							$w .= $separate . $this->numInWords($r);
						}
					}
				} else {
					$baseUnit     = pow(1000, floor(log($num, 1000)));
					$numBaseUnits = (int) ($num / $baseUnit);
					$r            = fmod($num, $baseUnit);
					if ($r == 0) {
						$w = $this->numInWords($numBaseUnits) . $separate . $nwords[$baseUnit];
					} else {
						if ($r < 100) {
							if ($r >= 10) {
								$w = $this->numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . ' không trăm ' . $this->numInWords($r);
							}
							else{
								$w = $this->numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . ' không trăm linh ' . $this->numInWords($r);
							}
						} else {
							$baseUnitInstr      = strval($baseUnit);
							$rInstr             = strval($r);
							$lenOfBaseUnitInstr = strlen($baseUnitInstr);
							$lenOfRInstr        = strlen($rInstr);
							if (($lenOfBaseUnitInstr - 1) != $lenOfRInstr) {
								$numberOfZero = $lenOfBaseUnitInstr - $lenOfRInstr - 1;
								if ($numberOfZero == 2) {
									$w = $this->numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . ' không trăm linh ' . $this->numInWords($r);
								} else if ($numberOfZero == 1) {
									$w = $this->numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . ' không trăm ' . $this->numInWords($r);
								} else {
									$w = $this->numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . $separate . $this->numInWords($r);
								}
							} else {
								$w = $this->numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . $separate . $this->numInWords($r);
							}
						}
					}
				}
			}
		}
		return $w;
	}
	function toword($currency_id, $num)
	{
		return $this->numInWords($num)." đồng";

	}
	
}

?>