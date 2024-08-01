<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'mailer/Exception.php';
require 'mailer/PHPMailer.php';
require 'mailer/SMTP.php';
require ABSPATH.'app/util/ws.php';
require ABSPATH.'app/util/Date.php';
require ABSPATH.'app/Message.php';

class Tool
{
	public function getId() {
		 $uuid = array(
		  'time_low'  => 0,
		  'time_mid'  => 0,
		  'time_hi'  => 0,
		  'clock_seq_hi' => 0,
		  'clock_seq_low' => 0,
		  'node'   => array()
		 );

		 $uuid['time_low'] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
		 $uuid['time_mid'] = mt_rand(0, 0xffff);
		 $uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
		 $uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
		 $uuid['clock_seq_low'] = mt_rand(0, 255);

		 for ($i = 0; $i < 6; $i++) {
		  $uuid['node'][$i] = mt_rand(0, 255);
		 }

		 $uuid = sprintf('%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
		  $uuid['time_low'],
		  $uuid['time_mid'],
		  $uuid['time_hi'],
		  $uuid['clock_seq_hi'],
		  $uuid['clock_seq_low'],
		  $uuid['node'][0],
		  $uuid['node'][1],
		  $uuid['node'][2],
		  $uuid['node'][3],
		  $uuid['node'][4],
		  $uuid['node'][5]
		 );

		 return $uuid;
	}
	public function validUrl($str) {
		  $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);             
		  $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);             
		  $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);             
		  $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);             
		  $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);             
		  $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);             
		  $str = preg_replace("/(đ)/", 'd', $str);             
		  $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);             
		  $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);             
		  $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);             
		  $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);             
		  $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);             
		  $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);             
		  $str = preg_replace("/(Đ)/", 'D', $str);        
		  $str = str_replace("/", "-", str_replace("&*#39;","",$str));		  
		  $str = str_replace(" ", "-", str_replace("&*#39;","",$str)); 
		  $str = str_replace("%", "", $str); 
		  $str = str_replace("(", "", $str); 
		  $str = str_replace(")", "", $str); 
				  
		  $str = strtolower($str);

		return $str;
	}
	const METHOD = 'aes-256-ctr';

	/**
	 * Encrypts (but does not authenticate) a message
	 * 
	 * @param string $message - plaintext message
	 * @param string $key - encryption key (raw binary expected)
	 * @param boolean $encode - set to TRUE to return a base64-encoded 
	 * @return string (raw binary)
	 */
	public function encrypt($message, $encode = false)
	{
		$nonceSize = openssl_cipher_iv_length($this::METHOD);
		$nonce = openssl_random_pseudo_bytes($nonceSize);
		$key = hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f');

		$ciphertext = openssl_encrypt(
			$message,
			$this::METHOD,
			$key,
			OPENSSL_RAW_DATA,
			$nonce
		);

		// Now let's pack the IV and the ciphertext together
		// Naively, we can just concatenate
		if ($encode) {
			return base64_encode($nonce.$ciphertext);
		}
		return $nonce.$ciphertext;
	}

	/**
	 * Decrypts (but does not verify) a message
	 * 
	 * @param string $message - ciphertext message
	 * @param string $key - encryption key (raw binary expected)
	 * @param boolean $encoded - are we expecting an encoded string?
	 * @return string
	 */
	public function decrypt($message, $encoded = false)
	{
		
		if ($encoded) {
			$message = base64_decode($message, true);
			if ($message === false) {
				return "";
			}
		}
		$key = hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f');
		$nonceSize = openssl_cipher_iv_length($this::METHOD);
		$nonce = mb_substr($message, 0, $nonceSize, '8bit');
		$ciphertext = mb_substr($message, $nonceSize, null, '8bit');

		$plaintext = openssl_decrypt(
			$ciphertext,
			$this::METHOD,
			$key,
			OPENSSL_RAW_DATA,
			$nonce
		);

		return $plaintext;
	}
	public function urlEncode($s)
	{
		return urlencode($s);
	}
	public function urlDecode($s)
	{
		return urldecode($s);
	}
	public function selectDistinct($values, $indexes)
	{
		$arr = array();
		$len = count($indexes);
		$m = 0;
		for($i = 0; $i<count($values); $i++)
		{
			$exist = false;
			for($n =0; $n<count($arr); $n++)
			{
				for($j =0; $j<$len; $j++)
				{
					if($arr[$n][$j] == $values[$i][$indexes[$j]])
					{
						$exist = true;					
					}else
					{
						$exist = false;	
						break;
					}
				}
				if($exist == true)
				{
					break;
				}
			}
			if($exist == false)
			{
				$item = array();
				for($j=0; $j<$len; $j++)
				{
					$item[$j]= $values[$i][$indexes[$j]];
					
				}
				
				$arr[$m] = $item;
				$m = $m + 1;
			}
		}
		return $arr;
		
	}
	public function rand_color() 
	{
		return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
	}
	public function padLeft($val, $num, $ch)
	{
		return str_pad($val, $num, $ch, STR_PAD_LEFT);
	}
	
	public function sendWS($host, $port, $message)
	{

		$ws = new ws(array
		(
			'host' => $host,
			'port' => $port,
			'path' => ''
		));
		$result = $ws->send($message);
		$ws->close();

		return $result;
		
	}
	public function send_sms($mobile, $message)
	{
		$url = "http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4/";
		$data = "<RQST><APIKEY>68C18EBCF56166BB3BFFB69F0CA634</APIKEY><SECRETKEY>96C3423CD136CF670CEDDE008FA393</SECRETKEY>";
		$data = $data."<CONTENT>".$message." la ma dat lai mat khau Baotrixemay cua ban</CONTENT>";
		$data = $data."<CUSTOMER><PHONE>".$mobile."</PHONE></CUSTOMER>";
		$data = $data."<BRANDNAME>Baotrixemay</BRANDNAME>";
		$data = $data."<SMSTYPE>2</SMSTYPE>";
		$data = $data."<CONTACTS></CONTACTS></RQST>";
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
		curl_setopt($ch,CURLOPT_TIMEOUT, 20);
		$response = curl_exec($ch);
		curl_close ($ch);
		return $response;
	}
	public function base64Decode($s)
	{
		return base64_decode($s);
	}
	public function getMime($ex)
	{
		$ex = strtolower($ex);
		
		if($ex == "bmp") return "image/bmp";
		if($ex == "fif") return "image/fif";
		if($ex == "gif") return "image/gif";
		if($ex == "jpe") return "image/jpeg";
		if($ex == "jpeg") return "image/jpeg";
		if($ex == "jpg") return "image/jpeg";
		if($ex == "png") return "image/png";
		if($ex == "css") return "text/css";
		if($ex == "js") return "text/javascript";
		if($ex == "htm") return "text/html";
		if($ex == "html") return "text/html";
		if($ex == "ico") return "image/x-icon";
		if($ex == "svg") return "image/svg+xml";
		if($ex == "ttf") return "application/x-font-ttf";
		if($ex == "otf") return "application/x-font-opentype";
		if($ex == "woff") return "application/font-woff";
		if($ex == "woff2") return "application/font-woff2";
		if($ex == "eot") return "application/vnd.ms-fontobject";
		if($ex == "xls") return "application/vnd.ms-excel";
		if($ex == "xlsx") return "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
		return "application/octet-stream";
		return "";
	}
	
	public function isdir($dir)
	{
		return is_dir($dir);
	}
	public function createDir($dir)
	{
		mkdir($dir,0777,TRUE);
	}
	public function existFile($f)
	{
		return is_file($f);
	}
	public function writeToFile($f, $s)
	{
		$file = fopen($f, $s);
		fclose($file);
	}

	public function split($s, $c)
	{
		return explode( $c, $s);
	}
	public function trim($s)
	{
		return trim($s);
	}
	public function substring($s, $start, $length =-1)
	{
		if($length == -1)
		{
			return substr($s, $start);
		}else{
			return substr($s, $start, $length);
		}
		
	}
	public function indexOf($s, $c)
	{
		if($s == "")
		{
			return -1;
		}
		$index = strpos ($s, $c);
		if($index !==false)
		{
			return $index;
		}
		return -1;
	}
	public function lastIndexOf($s, $c)
	{
		$index = strrpos ($s, $c);
		if($index !==false)
		{
			return $index;
		}
		return -1;
	}
	
	public function httpGet($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	public function httpPost($url, $data) {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
		curl_setopt($ch,CURLOPT_TIMEOUT, 20);
		$response = curl_exec($ch);
		curl_close ($ch);
		return $response;
	}
	public function getSslPage($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	public function paresLine($s)
	{
		return simplexml_load_string($s);
	}

	public function send_mail($from, $from_title, $to, $to_title, $subject, $body)
	{
		if($from == "")
		{
			$from = "no-reply@itada.com.vn";
		}
		
		$host = "mail.itada.com.vn";
		$username = "no-reply@itada.com.vn"; //no-reply@itada.com.vn
		$password = "cc]5&vY)9=H`[mY3";
		 
		$port = 465;

		try {
			$mail = new PHPMailer(true);
			
			//Server settings
			 
			//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
			$mail->isSMTP();                                            // Send using SMTP
			$mail->Host       =  $host;                    // Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = $username;                     // SMTP username
			$mail->Password   = $password;                               // SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port       = $port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

			//Recipients
			$mail->CharSet = 'UTF-8';
			$mail->setFrom($from, $from_title);
			$mail->addAddress($to, $to_title);     // Add a recipient
			
			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = $body;
		   

			$mail->send();
			return "OK";
		} catch (Exception $e) {
			return $mail->ErrorInfo;
		}
	}
	public function next_value($value)
	{
		if($value == "")
		{
			return "1";
		}
		$arr = str_split($value);
		$num = "";
		$a = "";
		for($i=count($arr)-1; $i>=0; $i--)
		{
			$c = ord($arr[$i]);
			
			if($c>47 && $c<=57)
			{
				$a = substr($value, 0, $i);
				$num = substr($value, $i);
				
				
			}else
			{
				break;
			}
		}
		if($num != "")
		{
			$num = (intval($num) + 1);
			return $a.$num;
		}else{
			return $value."1";
		}
		return "1";
	}
	function nextValue($value)
	{
		return $this->next_value($value);
	}
	function toHash($type, $s)
	{
		return hash($type, $s);
	}
	function lenght($s)
	{
		return strlen($s);
	}
	function len($s)
	{
		return strlen($s);
	}
	function addAtt(&$list, $group, $name, $value)
	{
		$hasItem = false;
		for($i=0; $i<count($list); $i++)
		{
			if($list[$i][0] == $group && $list[$i][1] == $name)
			{
				$list[$i][2]=$value;
				$hasItem = true;
				break;
			}
		}
		if($hasItem == false)
		{
			$list[count($list)] =[$group, $name, $value];
		}
	}
	function findAtt($list, $group, $name)
	{
		for($i=0; $i<count($list); $i++)
		{
			if($list[$i][0] == $group && $list[$i][1] == $name)
			{
				return $list[$i][2];
			}
		}
		return "";
	}
	function toDouble($value)
	{
		return floatval($value);
	}
	function toInt($value)
	{
		return intval($value);
	}
	function replace($s, $find, $with)
	{
		return str_replace($find, $with, $s);
	}
	function clean($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
	function findReceiptNo($mTier, $company_id, $receipt_no )
	{
		$msg = $mTier->createMessage();
		$msg->add("root_company_id", $company_id);
		$sql = "SELECT receipt_number FROM res_receipt_no WHERE company_id='".$company_id."' AND receipt_no='".$receipt_no."'";
		
		$msg->add("query", $sql);

		$receipt_number = $mTier->getValue($msg);
		if($receipt_number == "")
		{
			$receipt_number = "1";
			$id = $this->getId();
			$sql = "INSERT INTO res_receipt_no(";
			$sql = $sql."id";
			$sql = $sql.", create_date";
			$sql = $sql.", write_date";
			$sql = $sql.", company_id";
			$sql = $sql.", status";
			$sql = $sql.", receipt_no";
			$sql = $sql.", receipt_number";
			$sql = $sql.", receipt_date";
			$sql = $sql." )VALUES(";
			$sql = $sql."'".$id."'";
			$sql = $sql.", NOW()";
			$sql = $sql.", NOW()";
			$sql = $sql.", '".$company_id."'";
			$sql = $sql.", 0";
			$sql = $sql.", '".$receipt_no."'";
			$sql = $sql.", 1";
			$sql = $sql.", NOW()";
			$sql = $sql.")";
			$msg->add("query", $sql);
			
			$mTier->exec($msg);
		}else
		{
			
			$receipt_number = intval($receipt_number) + 1;
			
			$sql = "UPDATE res_receipt_no SET status =0, write_date=NOW(), receipt_date=NOW(), receipt_number=".$receipt_number." WHERE company_id='".$company_id."' AND receipt_no='".$receipt_no."'";
			$msg->add("query", $sql);
			
			$mTier->exec($msg);
			
		}
		return $receipt_number;
	}
	function respTable($dt) 
	{
		$s = "";
		for($i =0; $i <count($dt->getColumns()); $i++)
		{
			if($i>0)
			{
				$s = $s."\t";
			}
			$s = $s.$dt->getColumns()[$i]->getName($i);
		}
		for($r =0; $r<$dt->getRowCount(); $r++)
		{
			$s = $s."\n";
			for($i =0; $i <count($dt->getColumns()); $i++)
			{
				if($i>0)
				{
					$s = $s."\t";
				}
				$value = $dt->getStringAt($r, $i);
				$value = str_replace("\n", "", $value);
				$value = str_replace("\t", "", $value);
				$s = $s.$value;
			}
		}
		return $s;
		
	}
	
	public function image_resize($file, $max_width, $max_height, $save_file) 
	{
  
		list($width, $height, $image_type) = getimagesize($file);

		switch ($image_type)
		{
			case 1: $src = imagecreatefromgif($file); break;
			case 2: $src = imagecreatefromjpeg($file);  break;
			case 3: $src = imagecreatefrompng($file); break;
			default: return '';  break;
		}
		if($max_width == 0)
		{
			$p = $max_height/$height;
			$max_width = $width * $p;
		}
		if($max_height == 0)
		{
			$p = $max_width/$width;
			$max_height = $height * $p;
		}

		$x_ratio = $max_width / $width;
		$y_ratio = $max_height / $height;

		if( ($width <= $max_width) && ($height <= $max_height) ){
			$tn_width = $width;
			$tn_height = $height;
			}elseif (($x_ratio * $height) < $max_height){
				$tn_height = ceil($x_ratio * $height);
				$tn_width = $max_width;
			}else{
				$tn_width = ceil($y_ratio * $width);
				$tn_height = $max_height;
		}

		$tmp = imagecreatetruecolor($tn_width,$tn_height);

		imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

		/*
		 * imageXXX() only has two options, save as a file, or send to the browser.
		 * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
		 * So I start the output buffering, use imageXXX() to output the data stream to the browser,
		 * get the contents of the stream, and use clean to silently discard the buffered contents.
		 */
		ob_start();

		switch ($image_type)
		{
			case 1: imagegif($tmp, $save_file); break;
			case 2: imagejpeg($tmp, $save_file, 100);  break; // best quality
			case 3: imagepng($tmp, $save_file, 0); break; // no compression
			default: echo ''; break;
		}

	   

		ob_end_clean();
	}
	public function toDateTime($sDate)
	{
		if($sDate == "")
		{
			return new Date(0, 0, 0, 0, 0, 0, 0);
		}
		$y = 0;
		$m = 0;
		$d = 0;
		$hh = 0;
		$mm = 0;
		$ss = 0;
		$mi = 0;
		
		$index = $this->indexOf($sDate, ' ');
		if($index != -1)
		{
			$arr = $this->split($this->substring($sDate, 0, $index), '-');
			if(count($arr) == 3)
			{
				$y = $this->toInt($arr[0]);
				$m = $this->toInt($arr[1]);
				$d = $this->toInt($arr[2]);
			}
			$sDate = $this->substring($sDate, $index + 1);
		}
		$index = $this->indexOf($sDate, '.');
		if($index != -1)
		{
			$mi = $this->toInt($this->substring($sDate, $index + 1));
			$sDate = $this->substring($sDate, 0, $index);
		}
		$arr = $this->split($this->substring($sDate, 0, $index), ':');
		if(count($arr) == 3)
		{
			$hh = $this->toInt($arr[0]);
			$mm = $this->toInt($arr[1]);
			$ss = $this->toInt($arr[2]);
		}
		
		
		return new Date($y, $m, $d, $hh, $mm, $ss, $mi);
	}
	public function now()
	{
		return $this->toDateTime(date("Y-m-d h:i:s"));
	}
	
	public function listCompany($appSession, $id)
	{
		$s = [];
		$sql = "SELECT id, parent_id FROM res_company WHERE status =0 AND parent_id='".$id."'";
		$result = $appSession->getTier()->getArray($sql);
		for($i=0; $i<count($result); $i++)
		{
			
			$s[count($s)] = $result[$i][0] ;
			$s1 = $this->listCompany($appSession, $result[$i][0]);
			for($j =0; $j<count($s1); $j++)
			{
				$s[count($s)] = $s1[$j];
			}
		}
		return $s;
	}
	public function createMessage()
	{
		return new Message();
	}
	public function postMessage($appSession, $data)
	{
		
		$url = $appSession->getConfig()->getProperty("service_url");
		if($url == "")
		{
			return "SERVICE_NOT_SUPPORT";
		}
		return $this->httpPost($url, $data);
		
	}
	public function paddingLeft($s, $ch, $len)
	{
		if($ch == "")
		{
			$ch = "0";
		}
		while(true){
			if(strlen($s)<$len)
			{
				$s = $ch.$s;
			}else{
				break;
			}
		}
		return $s;
	}
	public function selectDistinctTable($dt, $columns)
	{
		$indexes = [];
		for($i =0; $i<count($columns); $i++)
		{
			$indexes[count($indexes)] = $dt->getColumnIndex($columns[$i]);
		}
		$dt_copy = new DataTable($dt->getName());
		$dt_copy->setColumns($dt->getColumns());
		
		$len = count($indexes);
		$m = 0;
		for($i = 0; $i<$dt->getRowCount(); $i++)
		{
			$exist = false;
			for($n =0; $n<$dt_copy->getRowCount(); $n++)
			{
				for($j =0; $j<$len; $j++)
				{
					if($dt_copy->getStringAt($n, $indexes[$j]) == $dt->getStringAt($i, $indexes[$j]))
					{
						$exist = true;					
					}else
					{
						$exist = false;	
						break;
					}
				}
				if($exist == true)
				{
					break;
				}
			}
			if($exist == false)
			{
				
				$dt_copy->addArray($dt->getDataRow($i));
			}
		}
		return $dt_copy;
		
	}
}

?>