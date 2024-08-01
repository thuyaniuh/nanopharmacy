<?php
function httpPost($url, $data)
{
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
function filter_filename($name) {
   
    $name = str_replace(array_merge(
        array_map('chr', range(0, 31)),
        array('<', '>', ':', '"', '/', '\\', '|', '?', '*')
    ), '', $name);
    
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $name= mb_strcut(pathinfo($name, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($name)) . ($ext ? '.' . $ext : '');
    return $name;
}

function getMime($ext) {
	$ext = strtolower($ext);
	$mime_types = array(

		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv',

		// images
		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp',
		'ico' => 'image/vnd.microsoft.icon',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',

		// archives
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',

		// audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',

		// adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',

		// ms office
		'doc' => 'application/msword',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'ppt' => 'application/vnd.ms-powerpoint',

		// open office
		'odt' => 'application/vnd.oasis.opendocument.text',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	);


	if (array_key_exists($ext, $mime_types)) {
		return $mime_types[$ext];
	}
	else {
		return 'application/octet-stream';
	}
}
$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
if(isset($_REQUEST['id']))
{
	$ac = "document";
}
	
if($ac == "createFile")
{
	
	$document_id = "";
	if(isset($_REQUEST['document_id']))
	{
		$document_id = $_REQUEST['document_id'];
	}
	if($document_id == "")
	{
		$document_id = $appSession->getTool()->getId();
	}

	echo $document_id;
	
	
}else if($ac == "writeFile")
{
	$file_id = "";
	if(isset($_REQUEST['file_id']))
	{
		$file_id = $_REQUEST['file_id'];
	}
	
	$sData = "";
	if(isset($_REQUEST['sData']))
	{
		$sData = $_REQUEST['sData'];
	}
	
	$decocedData = base64_decode($sData);
	$path = ABSPATH."disk/".$file_id;
	$file = NULL;
	if(file_exists($path))
	{
		$file = fopen($path, "a");
	}else{
		$file = fopen($path, "w");
	}
	fwrite($file, $decocedData);
	fclose($file);
	echo "OK";
	
}else if($ac == "commitDocument")
{
	
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	if($company_id == "")
	{
		$company_id = COMPANY_ID;
	}
	
	
	$path = ABSPATH."disk";
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
	
	
	$file_id = "";
	if(isset($_REQUEST['file_id']))
	{
		$file_id = $_REQUEST['file_id'];
	}
	
	$name = "";
	if(isset($_REQUEST['file_name']))
	{
		$name = $_REQUEST['file_name'];
	}
	$extension = "";
	$arr = explode(".", $name);
	if(count($arr)>0)
	{
		$extension = $arr[count($arr) -1];
		$name= $arr[0];
	}
	$file_name = ABSPATH."disk/".$file_id;
	rename($file_name, $path."/".$file_id);
	$content_length = 0;
	$sql = "INSERT INTO document(";
    $sql = $sql."id, name, type, rel_id, status, create_uid, create_date, write_date";
    $sql = $sql.", path, ext, company_id, write_uid";
    $sql = $sql.") VALUES(";
    $sql = $sql."'".$file_id."', '".str_replace("'", "''", $name)."', 'file', '".$rel_id."', 0";
    $sql = $sql.", '', NOW(), NOW()";
    $sql = $sql.", '".$dir."', '".$extension."','".$company_id."', '')";
	$appSession->getTier()->exec($sql);
	
	echo "OK";
}
else if($ac == "updateName")
{
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	
	
	$sql = "UPDATE document SET document_name ='".str_replace("'", "''", $name)."', write_date=NOW() WHERE id ='".$id."'";
	$appSession->getTier()->exec($sql);
	echo "OK";
}else if($ac == "delDoc")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sql = "UPDATE document SET status =1, write_date=NOW() WHERE id ='".$id."'";
	$appSession->getTier()->exec($sql);
	echo "OK";
}else if($ac == "download")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sql = "SELECT d1.id, d1.document_name, d1.ext, d1.content_length, d1.lat, d1.lng FROM document d1 WHERE d1.id='".$id."'";
	$result = $appSession->getTier()->getArray($sql);
	$numrows = count($result);	
	
	if($numrows>0)
	{
		$row = $result[0];
		$document_name = $row[1];
		$file = ABSPATH."disk/".$id;
		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($document_name).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			exit;
		}
	}
}else if($ac == "document")
{
	$id = '';
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	
	$width = '';
	if(isset($_REQUEST['width']))
	{
		$width = $_REQUEST['width'];
	}
	if(isset($_REQUEST['w']))
	{
		$width = $_REQUEST['w'];
	}
	$height = '';
	if(isset($_REQUEST['height']))
	{
		$height = $_REQUEST['height'];
	}
	if(isset($_REQUEST['h']))
	{
		$height = $_REQUEST['h'];
	}
	$sql = "SELECT d1.name, d1.ext, d1.path FROM document d1 WHERE d1.id='".$id."'";
	
	$result = $appSession->getTier()->getArray($sql);
	$numrows = count($result);	
	$name = "";
	$path = "";
	$ext = "";
	if($numrows>0)
	{
		$row = $result[0];
		$name = $row[0];
		$path = $row[2];
		$ext = $row[1];
	}
		
	$local_file = ABSPATH."disk/".$path."/".$id;
	$download_rate = 20.5;
	if(file_exists($local_file) && is_file($local_file))
	{
		
		if($width != "" && $height != "")
		{
			$file_resize = $file."_".$width."_".$height;
			if (file_exists($file_resize))
			{
				$file = $file_resize;
			}else
			{
				$img = $appSession->getTool()->image_resize($file, $width, $height, $file_resize);
				$file = $file_resize;
			}
		}
		header('Content-Type: '.getMime($ext));
		header('Content-Disposition: attachment; filename="'.basename($file).'.'.$ext.'"');
		header('Content-Length: ' . filesize($file));
		flush();
		$file = fopen($local_file, "r");
		while(!feof($file))
		{
			// send the current file part to the browser
			print fread($file, round($download_rate * 1024));
			// flush the content to the browser
			flush();
			// sleep one second
			sleep(1);
		}
		fclose($file);
		exit;
	}else{
		die('Error: The file '.$local_file.' does not exist!');
	}
}else if($ac == "upload")
{
	$data = '';
	if(isset($_REQUEST['data']))
	{
		$data = $_REQUEST['data'];
	}
	$name = '';
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$rel_id = '';
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$company_id = '';
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	

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
	
	$file_id = $appSession->getTool()->getId();
	$file_name = $path."/".$file_id;
	$file = fopen($file_name, "wb");
    fwrite($file, base64_decode($data));
    fclose($file);
	$extension = "";
	$arr = explode(".", $name);
	if(count($arr)>0)
	{
		$extension = $arr[count($arr) -1];
		$name= $arr[0];
	}
	$content_length = 0;
	$sql = "INSERT INTO document(";
    $sql = $sql."id, name, type, rel_id, status, create_uid, create_date, write_date";
    $sql = $sql.", path, ext, company_id, write_uid";
    $sql = $sql.") VALUES(";
    $sql = $sql."'".$file_id."', '".str_replace("'", "''", $name)."', 'file', '".$rel_id."', 0";
    $sql = $sql.", '', NOW(), NOW()";
    $sql = $sql.", '".$dir.'/'.$file_id."', '".$extension."','".$company_id."', '')";
	$msg = $appSession->getTier()->createMessage();
	$msg->add("root_company_id", $appSession->getConfig()->getProperty("root_company_id"));
	$msg->add("company_id", $appSession->getUserInfo()->getCompanyId());
	$msg->add("user_id", $appSession->getUserInfo()->getId());
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo $file_id;
	
}else if($ac == "file")
{
	$name = '';
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$width = '';
	if(isset($_REQUEST['width']))
	{
		$width = $_REQUEST['width'];
	}
	if(isset($_REQUEST['w']))
	{
		$width = $_REQUEST['w'];
	}
	$height = '';
	if(isset($_REQUEST['height']))
	{
		$height = $_REQUEST['height'];
	}
	if(isset($_REQUEST['h']))
	{
		$height = $_REQUEST['h'];
	}
	
	$dir = DOC_PATH;
	$file = $dir."/".$name;
	
	if (file_exists($file))
	{
		
		$ext = "";
		$index= $appSession->getTool()->lastIndexOf($file, '.');
		if($index != -1)
		{
			$ext = $appSession->getTool()->substring($file, $index + 1);
			$file = $appSession->getTool()->substring($file, 0, $index );
		}
		
		if($width != "" && $height != "")
		{
			$file_resize = $file."_".$width."_".$height;
			if (file_exists($file_resize))
			{
				$file = $file_resize;
			}else
			{
				$img = $appSession->getTool()->image_resize($file, $width, $height, $file_resize);
				$file = $file_resize;
			}
		}
		$local_file = $file.'.'.$ext;
		$download_rate = 20.5;
		if(file_exists($local_file) && is_file($local_file))
		{
			header('Content-Type: '.getMime($ext));
			header('Content-Length: '.filesize($local_file));
			header('Content-Disposition: attachment; filename="'.basename($local_file).'"');

			flush();
			$file = fopen($local_file, "r");
			while(!feof($file))
			{
				print fread($file, round($download_rate * 1024));
				flush();
			}
			fclose($file);}
		else {
			die('Error: The file '.$local_file.' does not exist!');
		}

		exit;
	}
	
}
?>
