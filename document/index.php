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
if($ac == "" && isset($_REQUEST['id']))
{
	$ac = "download";
}


if($ac == "")
{
	$ac == "view";
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
	
	$msg = $appSession->getTier()->createMessage();
	$msg->add("root_company_id", $appSession->getConfig()->getProperty("root_company_id"));
	$msg->add("company_id", $appSession->getUserInfo()->getCompanyId());
	$msg->add("user_id", $appSession->getUserInfo()->getId());
	
	$type = "file";
	if(isset($_REQUEST['type']))
	{
		$type = $_REQUEST['type'];
	}
	$category_id = "";
	if(isset($_REQUEST['category_id']))
	{
		$category_id = $_REQUEST['category_id'];
	}
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
	
	
	$file_id = "";
	if(isset($_REQUEST['file_id']))
	{
		$file_id = $_REQUEST['file_id'];
	}
	
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$extension = "";
	if(isset($_REQUEST['extension']))
	{
		$extension = $_REQUEST['extension'];
	}
	
	$arr = explode(".", $name);
	if(count($arr)>0)
	{
		$extension = $arr[count($arr) -1];
		$name= $arr[0];
	}
	$temp = ABSPATH."disk/".$file_id;
	rename($temp, $path."/".$file_id);
	$content_length = 0;
	$sql = "INSERT INTO document(";
    $sql = $sql."id, name, type, rel_id, status, create_uid, create_date, write_date";
    $sql = $sql.", path, ext, company_id, write_uid, category_id";
    $sql = $sql.") VALUES(";
    $sql = $sql."'".$file_id."', '".str_replace("'", "''", $name)."', '".$type."', '".$rel_id."', 0";
    $sql = $sql.", '', NOW(), NOW()";
    $sql = $sql.", '".$dir."', '".$extension."','".$company_id."', '', '".$category_id."')";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	
	echo "OK";
}
else if($ac == "updateName")
{
	$msg = $appSession->getTier()->createMessage();
	$msg->add("root_company_id", $appSession->getConfig()->getProperty("root_company_id"));
	$msg->add("company_id", $appSession->getUserInfo()->getCompanyId());
	$msg->add("user_id", $appSession->getUserInfo()->getId());
	
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
	
	$sql = "UPDATE document SET name ='".str_replace("'", "''", $name)."', write_date=NOW() WHERE id ='".$id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "delDoc")
{
	$msg = $appSession->getTier()->createMessage();
	$msg->add("root_company_id", $appSession->getConfig()->getProperty("root_company_id"));
	$msg->add("company_id", $appSession->getUserInfo()->getCompanyId());
	$msg->add("user_id", $appSession->getUserInfo()->getId());
	
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sql = "UPDATE document SET status =1, write_date=NOW() WHERE id ='".$id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "download")
{
	$msg = $appSession->getTier()->createMessage();
	$msg->add("root_company_id", $appSession->getConfig()->getProperty("root_company_id"));
	$msg->add("company_id", $appSession->getUserInfo()->getCompanyId());
	$msg->add("user_id", $appSession->getUserInfo()->getId());
	
	$cache_file = ABSPATH."log/doc.txt";
	$sContent = "";
	if(file_exists($cache_file))
	{
		$sContent =file_get_contents($cache_file);
	}
	
	
	
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
	
	
	$name = "";
	$path = "";
	$ext = "";
	
	
	if($path == "")
	{
		$sql = "SELECT d1.name, d1.ext, d1.path FROM document d1 WHERE d1.id='".$id."'";
		$msg->add("query", $sql);
		
		$result = $appSession->getTier()->getArray($msg);
		$numrows = count($result);	
		if($numrows>0)
		{
			$row = $result[0];
			$name = $row[0];
			$ext = $row[1];
			$path = $row[2];
			
		}
	}
	
		
	$local_file = DOC_PATH.$path."/".$id;
	
	$download_rate = 20.5;
	if(file_exists($local_file) && is_file($local_file))
	{
	
		if($width != "" || $height != "")
		{
			$file_resize = $local_file."_".$width."_".$height;
			if (file_exists($file_resize))
			{
				$local_file = $file_resize;
				
			}else
			{
				if(strtolower($ext) == ".jpg" || strtolower($ext) == ".png" || strtolower($ext) == ".bmp" || strtolower($ext) == "jpg" || strtolower($ext) == "png" || strtolower($ext) == "bmp")
				{
					
					$img = $appSession->getTool()->image_resize($local_file, $width, $height, $file_resize);
					$local_file = $file_resize;
					
				}
			}
		}
		
		
		$file = fopen($local_file, "rb");
		if ($file) {
			header('Content-Type: '.getMime($ext));
			header('Content-Disposition: attachment; filename="'.$name.'.'.$ext.'"');
			header('Content-Length: ' . filesize($local_file));
			flush();
			fpassthru($file);
			
		}
		
		exit;
	}else{
		$type = '';
		if(isset($_REQUEST['type']))
		{
			$type = $_REQUEST['type'];
		}
		if($type == "img" || $height != "" || $width != "")
		{
			$local_file = ABSPATH."assets/images/product.png";
			if(file_exists($local_file) && is_file($local_file))
			{
				
				$file = fopen($local_file, "r");
				if ($file) {
					header('Content-Type: '.getMime("png"));
					header('Content-Disposition: attachment; filename=product.png');
					header('Content-Length: ' . filesize($local_file));
					flush();
					fpassthru($file);
					/*while(!feof($file))
					{
						print fread($file, round($download_rate * 1024));
						
						flush();
						
					}
					fclose($file);*/
				}
				
				exit;
			}
		}else{
			die('Error: The file '.$local_file.' does not exist!');
		}
		
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
    $sql = $sql.", '".$dir."', '".$extension."','".$company_id."', '')";
	$msg = $appSession->getTier()->createMessage();
	$msg->add("root_company_id", $appSession->getConfig()->getProperty("root_company_id"));
	$msg->add("company_id", $appSession->getUserInfo()->getCompanyId());
	$msg->add("user_id", $appSession->getUserInfo()->getId());
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo $file_id;
	
}
else if($ac == "upload_view")
{
	$rel_id = '';
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}

	$cview = '';
	if(isset($_REQUEST['cview']))
	{
		$cview = $_REQUEST['cview'];
	}

	$pageIndex = '';
	if(isset($_REQUEST['id']))
	{
		$pageIndex = $_REQUEST['id'];
	}
	$document_id = '';
	if(isset($_REQUEST['document_id']))
	{
		$document_id = $_REQUEST['document_id'];
	}
	$can_edit = '';
	if(isset($_REQUEST['can_edit']))
	{
		$can_edit = $_REQUEST['can_edit'];
	}
	
?>
<?php if($can_edit == "1"){?>
<input id="files<?php echo $pageIndex;?>" type="file" multiple  style="display:none" onchange="readFile<?php echo $pageIndex;?>('', this);"><a href="javascript:document.getElementById('files<?php echo $pageIndex;?>').click();"> &nbsp;&nbsp; <i class="icon-paper-clip"></i></a> <span id="progress_upload<?php echo $pageIndex;?>"></span>
<?php } ?>
<div id="list<?php echo $pageIndex;?>"></div>


<script>


var reader<?php echo $pageIndex; ?>;
var files<?php echo $pageIndex; ?>;
var names<?php echo $pageIndex; ?> = new Array();
var file_index<?php echo $pageIndex; ?> = 0;

function viewDoc<?php echo $pageIndex; ?>()
{
	var _url = '<?php echo URL;?>document/?ac=list_doc_upload&rel_id=<?php echo $rel_id;?>&pageIndex=<?php echo $pageIndex; ?>&can_edit=<?php echo $can_edit;?>' ;
	loadPage('list<?php echo $pageIndex;?>', _url, function(status, message)
	{
		if(status== 0)
		{
			
		}
		
	}, false);
			
}
function updateName<?php echo $pageIndex; ?>(id, name)
{
	var name = prompt("Please enter your name", name);
	if (name != null && name != "") {
		var _url = '<?php echo URL;?>document/?ac=updateName&id=' + id;
		_url = _url + "&name=" + encodeURIComponent(name);
		
		loadPage('pnFileManager<?php echo $pageIndex;?>', _url, function(status, message)
		{
			if(status  == 0)
			{
				if(message == "OK")
				{
					viewDoc<?php echo $pageIndex;?>();
				}else
				{
					alert(message);
				}
			}
		}, true);
	}
}

function delDoc<?php echo $pageIndex; ?>(id)
{
	var result = confirm("<?php echo $appSession->getLang()->find('Are you sure to remove');?>");
	if (!result) {
		return;
	}	
	var _url = '<?php echo URL;?>document/?ac=delDoc&id=' + id;
	loadPage('pnFileManager<?php echo $pageIndex;?>', _url, function(status, message)
	{
		if(status  == 0)
		{
			if(message == "OK")
			{
				viewDoc<?php echo $pageIndex;?>();
			}else
			{
				alert(message);
			}
		}
	}, true);
}
function readFile<?php echo $pageIndex; ?>(parent_id, theFiles) 
{
	
	files<?php echo $pageIndex; ?> = theFiles.files;
	if (!files<?php echo $pageIndex; ?>.length) {
	  alert('Please select a file!');
	  return;
	}
	names<?php echo $pageIndex; ?> = new Array();
	for(var i =0; i<files<?php echo $pageIndex; ?>.length; i++)
	{
		var name = files<?php echo $pageIndex; ?>[i].name;
		var index = name.lastIndexOf(".");
		if(index != -1)
		{
			name = name.substr(0, index);
		}
		names<?php echo $pageIndex; ?>.push(name);
		
	}
	uploadDoc<?php echo $pageIndex; ?>();
	
	
}
function uploadDoc<?php echo $pageIndex; ?>()
{
	
	
	file_index<?php echo $pageIndex; ?> = 0;
	upByFile<?php echo $pageIndex; ?>();
}

function postDataPage<?php echo $pageIndex; ?>(_url, params, complete)
{
	  
	var xmlHttp = GetXmlHttpObject();
	if (xmlHttp == null) {
			alert("Browser does not support HTTP Request");
			return;
	}
	xmlHttp.open("POST", _url, true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
	xmlHttp.onreadystatechange = function() 
	{
		if ((xmlHttp.readyState == 4) || (xmlHttp.readyState == "complete")) 
		{
			var responseMsg =  xmlHttp.responseText;
			if (xmlHttp.status == 200) 
			{
				complete(0, responseMsg);	
			}
		}
	};
	
	xmlHttp.send(params);
	
}

function upByFile<?php echo $pageIndex; ?>()
{
	var file = files<?php echo $pageIndex; ?>[file_index<?php echo $pageIndex; ?>];
	file.name = "";
	var document_id = '<?php echo $document_id; ?>';
	if(file_index<?php echo $pageIndex; ?> >0)
	{
		document_id = "";
	}
	var _url = '<?php echo URL;?>document/?ac=createFile&document_id=' + document_id;

	loadPage('pnFileManager<?php echo $pageIndex; ?>', _url, function(status, message)
	{
		if(status  == 0)
		{
			if(message.length == 36)
			{
				var file_id = message;
				reader<?php echo $pageIndex; ?> = new FileReader();
				
				var byteRead = 1024 * 100;
				
				
				var start = 0;
				var stop = byteRead -1;
				var sizes = file.size;
				
				if(sizes<=byteRead)
				{
					stop = sizes - 1;
				}
				
				var baseUrl = '<?php echo URL;?>document/?ac=writeFile&file_id=' + file_id ;
				var proccess = document.getElementById('progress_upload<?php echo $pageIndex; ?>');
				reader<?php echo $pageIndex; ?>.onloadend = function(evt) {
				
				  if (evt.target.readyState == FileReader.DONE) { 
					
	
					var _url = baseUrl;
					if(start == 0)
					{
						_url = _url + '&n=1' 
					}
					
					var base64String = btoa(String.fromCharCode.apply(null, new Uint8Array(evt.target.result)));
				
					base64String = 'sData=' + encodeURIComponent(base64String);
					
					if(evt.target.result.byteLength>0)
					{
						postDataPage<?php echo $pageIndex; ?>(_url, base64String, function(status, message)
						{
							if(status  == 0)
							{
								if(message == "OK")
								{
									var p = (start/sizes) * 100;
									p = parseInt(p);
									proccess.innerHTML  = p + "%";
									stop = stop + 1;
									start = stop ;
									if((stop + 1)<sizes)
									{
						
										if((stop + byteRead)>=sizes)
										{
											byteRead = (sizes - stop);
										}
										stop = stop + byteRead;
										stop = stop -1;
										readBytes<?php echo $pageIndex; ?>(file, start, stop);
									}else
									{
										var extension = files<?php echo $pageIndex; ?>[file_index<?php echo $pageIndex; ?>].name;
										var index = extension.lastIndexOf(".");
										if(index != -1)
										{
											extension = extension.substr(index);
										}else{
											extension = "";
										}
										_url = '<?php echo URL;?>document/?ac=commitDocument&file_id=' + file_id ;
										_url = _url + '&name=' + encodeURIComponent(names<?php echo $pageIndex; ?>[file_index<?php echo $pageIndex; ?>] + extension);
										_url = _url + '&rel_id=<?php echo $rel_id; ?>';
										_url = _url + '&extension=' + extension;
										
										loadPage('pnFileManager<?php echo $pageIndex; ?>', _url, function(status, message)
										{
											if(status  == 0)
											{
												if(message == "OK")
												{
													file_index<?php echo $pageIndex; ?> += 1;
													if(files<?php echo $pageIndex; ?>.length>file_index<?php echo $pageIndex; ?>)
													{
														if(files<?php echo $pageIndex; ?>.length>1)
														{
															viewDoc<?php echo $pageIndex; ?>();
														}
														
														upByFile<?php echo $pageIndex; ?>();
													}
													else{
													
														proccess.setAttribute("class", "");
														proccess.innerHTML = "";
														<?php
														$func = "";
														if(isset($_REQUEST['func']))
														{
															$func = $_REQUEST['func'];
														}
														
														if($func != "")
														{
															echo $func."();";;
														}else{
														?>
														var ctr= document.getElementById('document_name');
														
														viewDoc<?php echo $pageIndex; ?>();
														<?php }?>
													}
													
												}else
												{
													alert(message);
												}
											}
										}, true);
										
									}
								}else
								{
									alert(message);
								}
							}
							
						});
					}
					
				  }
				};
				
				readBytes<?php echo $pageIndex; ?>(file, start, stop);
				
			}else
			{
				alert(message);
			}
		}
	}, true);
}
function readBytes<?php echo $pageIndex; ?>(file, start, stop)
{
 
	var blob = file.slice(start, stop + 1);
	reader<?php echo $pageIndex; ?>.readAsArrayBuffer(blob);
}
viewDoc<?php echo $pageIndex; ?>();
<?php
}else if($ac == "list_doc_upload")
{
	$msg = $appSession->getTier()->createMessage();
	$msg->add("root_company_id", $appSession->getConfig()->getProperty("root_company_id"));
	$msg->add("company_id", $appSession->getUserInfo()->getCompanyId());
	$msg->add("user_id", $appSession->getUserInfo()->getId());

	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$can_edit = "";
	if(isset($_REQUEST['can_edit']))
	{
		$can_edit = $_REQUEST['can_edit'];
	}
	$pageIndex = "";
	if(isset($_REQUEST['pageIndex']))
	{
		$pageIndex = $_REQUEST['pageIndex'];
	}
	$sql = "SELECT d1.id, d1.name, d1.ext, d1.path FROM document d1 WHERE d1.rel_id='".$rel_id."' AND d1.status =0 ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);
	$result = $appSession->getTier()->getArray($msg);
	$numrows = count($result);
	for($i =0; $i<$numrows; $i++)
	{
		$row = $result[$i];
		$file_id = $row[0];
		$name = $row[1];
		$extension = $row[2];
		$path = $row[3];
		if($i>0)
		{
			echo "<br>";
		}
		$path = DOC_PATH.$path."/".$file_id;
		
		if(file_exists($path) == 1)
		{
		?>
		
		<?php echo ($i+1);?>. <?php echo $name; ?> <?php if($can_edit == "1"){?> <a href="javascript:updateName<?php echo $pageIndex;?>('<?php echo $file_id; ?>', '<?php echo $name;?>');"><i class="icon-pencil"></i></a> &nbsp;&nbsp; <a href="<?php echo URL;?>document/?id=<?php echo $file_id; ?>" target="_blank"> <i class="icon-cloud-download"></i> </a> &nbsp;&nbsp; <a href="javascript:delDoc<?php echo $pageIndex;?>('<?php echo $file_id; ?>')"><i class="icon-close"></i></a><?php } ?>
		<?php
		}else{
		?>
		<?php echo ($i+1);?>. <?php echo $name; ?> &nbsp;&nbsp; <?php if($can_edit == "1"){?><a href="javascript:updateName<?php echo $pageIndex;?>('<?php echo $file_id; ?>', '<?php echo $name;?>');"><i class="icon-pencil"></i></a> &nbsp;&nbsp; <a href="<?php echo URL;?>document/?ac=download&id=<?php echo $file_id; ?>" target="_blank"><i class="icon-cloud-download"></i></a> &nbsp;&nbsp; <a href="javascript:delDoc<?php echo $pageIndex;?>('<?php echo $file_id; ?>')"><i class="icon-close"></i></a><?php } ?>
		
		
		<?php
		}
	}
	
}
?>
