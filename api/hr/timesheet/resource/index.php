<?php
$content = file_get_contents("php://input");
if(isset($_REQUEST['data']))
{
	$content = $_REQUEST['data'];
	$content = urldecode($content);
}

if(isset($_POST['data']))
{
	$content = $_POST['data'];
	$content = urldecode($content);
}
$obj = json_decode($content);

$code = "";
$name = "";
$date = "";
$user_id = "";
if(isset($obj->{'personID'}))
{
	$code = $obj->{'personID'};
}

if($code == "" && isset($obj->{'aliasID'}))
{
	$code = $obj->{'aliasID'};
}
$deviceID = "";
if( isset($obj->{'deviceID'}))
{
	$deviceID = $obj->{'deviceID'};
}
if($code == "")
{
	$code = $deviceID;
}
if(isset($obj->{'personName'}))
{
	$name = $obj->{'personName'};
}
if($name== "" && isset($obj->{'personName'}))
{
	$name = $obj->{'personName'};
}
if(isset($obj->{'date'}))
{
	$date = $obj->{'date'};
}
if($date == "")
{
	$date = date("Y-m-d H:i:s");
}
$id = "";
if($code != "")
{
	$msg = $appSession->getTier()->createMessage();
	$sql = "SELECT id, company_id FROM hr_timesheet_device WHERE code='".$deviceID."'";
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	$device_id = "";
	$company_id = COMPANY_ID;
	
	if(count($values)>0)
	{
		$device_id = $values[0][0];
		$company_id = $values[0][1];
	}
	$id = $appSession->getTool()->getId();
	$sql = "INSERT INTO hr_timesheet_resource(";
	$sql = $sql."id";
	$sql = $sql.", create_date";
	$sql = $sql.", write_date";
	$sql = $sql.", status";
	$sql = $sql.", create_uid";
	$sql = $sql.", code";
	$sql = $sql.", name";
	$sql = $sql.", company_id";
	$sql = $sql.", device_id";
	$sql = $sql." )VALUES(";
	$sql = $sql."'".$id."'";
	$sql = $sql.", '".$date."'";
	$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
	$sql = $sql.", 0";
	$sql = $sql.", '".$user_id."'";
	$sql = $sql.", '".str_replace("'", "''", $code)."'";
	$sql = $sql.", '".str_replace("'", "''", $name)."'";
	$sql = $sql.", '".$company_id."'";
	$sql = $sql.", '".$device_id."'";
	$sql = $sql.")";
	
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	if(isset($obj->{'detected_image_url'}))
	{
		$detected_image_url = $obj->{'detected_image_url'};
	
		$path = DOC_PATH;
		$dir = "";
		$current_date =  date('Y-m-d');
		if($current_date != "")
		{
			$arr_date = explode("-", $current_date);
			if(count($arr_date)>2)
			{
				$dir = $arr_date[0]."/". + $arr_date[1]."/". + $arr_date[2];
			}
		}
		$path = $path."/".$dir;
		if(is_dir($path) == false)
		{
			if (!mkdir($path, 0777, true)) {
				die('Failed to create folders...');
			}
		}
		
		$path = $path."/".$id;
		
		$ch = curl_init($detected_image_url);
		$fp = fopen($path, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		$name = pathinfo($detected_image_url, PATHINFO_FILENAME); 
		$extension = pathinfo($detected_image_url, PATHINFO_EXTENSION);
		
		$sql = "INSERT INTO document(";
		$sql = $sql."id, name, type, rel_id, status, create_uid, create_date, write_date";
		$sql = $sql.", path, ext, company_id, write_uid";
		$sql = $sql.") VALUES(";
		$sql = $sql."'".$id."', '".str_replace("'", "''", $name)."', 'file', '".$id."', 0";
		$sql = $sql.", '', NOW(), NOW()";
		$sql = $sql.", '".$dir."', '".$extension."','".$company_id."', '')";
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		
	}
}
echo $id;

?>