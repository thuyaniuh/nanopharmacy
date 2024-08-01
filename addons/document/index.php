<?php

require_once(ABSPATH.'config.php' );

$ac = '';
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}

if($ac == "")
{
	$ac = "view";
}

$msg = $appSession->getTier()->createMessage();


if($ac == "view")
{
	$rel_id = "";
	$ac = '';
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}

	
	?>

	<script>
	function loadDocument()
	{
		var _url = '<?php echo URL;?>addons/document/?ac=list_poster&rel_id=<?php echo $rel_id;?>';
		loadPage('pnDocument', _url, function(status, message)
		{
			if(status == 0)
			{
			}
			
		}, false);
	}
	function delDocument(id)
	{
		var result = confirm("Want to delete?");
		if (!result) {
			return;
		}

		var _url = '<?php echo URL;?>document/?ac=delDoc&id=' + id ;					
		loadPage('pnDocument', _url, function(status, message)
		{
			if(status  == 0)
			{
				if(message == "OK")
				{
					loadDocument();
					
				}else
				{
					alert(message);
				}
			}
		}, true);
	}
	
	loadDocument();
	</script>
	<?php
}else if($ac == "list_poster")
{
	$rel_id = '';
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	
	
	
	$sql = "SELECT d1.id, d1.name, d2.name AS category_name,  d1.ext  FROM document d1 LEFT OUTER JOIN document_category d2 ON(d1.category_id = d2.id) WHERE d1.rel_id='".$rel_id."' AND d1.status =0 AND d1.type='PRODUCT'";
	$sql = $sql." ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);
	$arr = $appSession->getTier()->getArray($msg);
	$pageIndex = 1;

	
	$sql = "SELECT d1.id, d1.name FROM document_category d1 WHERE d1.rel_id='PRODUCT' AND d1.status =0";
	$sql = $sql." ORDER BY d1.name ASC";
	$msg->add("query", $sql);
	$dt_category = $appSession->getTier()->getTable($msg);
	
	?>
	<div class="row">
		<div class="col-md-6">
			<select class="form-control" id="editdoc_category_id<?php echo $pageIndex;?>">
				<?php
				for($i =0; $i<$dt_category->getRowCount(); $i++)
				{
				?>
				<option value="<?php echo $dt_category->getString($i, "id");?>"><?php echo $dt_category->getString($i, "name");?></option>
				<?php
				}
				?>
			</select>
		</div>
		<div class="col-md-4">
			<input id="files<?php echo $pageIndex;?>" type="file" multiple>
		</div>
		<div class="col-md-2">
			<span id="progress_upload<?php echo $pageIndex;?>"></span>	
			 <img src="<?php echo URL;?>assets/images/publish.png" onclick="readFile<?php echo $pageIndex; ?>('')"/></a>
		</div>
	</div>
	
	<div class="responsive" >
		<table class="table">
		<thead>
			<tr>
				<th><?php $appSession->getLang()->find("Document Name");?></th>
				<th width="50" nowrap="nowrap"><?php $appSession->getLang()->find("Category Name");?></th>
				<th width="30"></th>
				<th width="30"></th>
			</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $i<count($arr); $i++)
		{
			$id = $arr[$i][0];
			$name = $arr[$i][1];
			$category_name = $arr[$i][2];
			$ext = $arr[$i][3];
		
		?>
		<tr>
			<td><a href="<?php echo URL;?>document/?id=<?php echo $id;?>" target="_blank"><?php echo $name;?></a><td>
			<td width="140" align="center" valign="middle"><?php echo $category_name;?></td>
			<td width="30" align="center" valign="middle"><?php echo $ext;?></td>
			<td width="30" align="center" valign="middle"><a href="javascript:delDocument('<?php echo $id;?>')"><img src="<?php echo URL;?>assets/images/remove.png"/> </a></td>
		</tr>
		<?php
		}
		?>
		</tbody>
		</table>
	</div>
	<script>
	var reader<?php echo $pageIndex; ?>;
	var files<?php echo $pageIndex; ?>;
	var names<?php echo $pageIndex; ?> = new Array();
	var file_index<?php echo $pageIndex; ?> = 0;
	var category_id<?php echo $pageIndex; ?> = ''
	function readFile<?php echo $pageIndex; ?>(parent_id) 
	{
		category_id<?php echo $pageIndex; ?> = document.getElementById('editdoc_category_id<?php echo $pageIndex;?>').value;
		
		var theFiles = document.getElementById('files<?php echo $pageIndex;?>');
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
		var document_id = '';
		
		var _url = '<?php echo URL;?>document/?ac=createFile&type=PRODUCT&document_id=' + document_id;

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
											_url = '<?php echo URL;?>document/?ac=commitDocument&type=PRODUCT&category_id='+ category_id<?php echo $pageIndex; ?> +'&file_id=' + file_id ;
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
															upByFile<?php echo $pageIndex; ?>();
														}
														else{
															loadDocument();
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
	</script>

	<?php
}else if($ac == "del")
{
	$id = '';
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sql = "UPDATE poster SET status =1, write_date=".$appSession->getTier()->getDateString().", write_uid='".$appSession->getUserInfo()->getId()."'";
	$sql = $sql." WHERE id='".$id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "publish")
{
	$id = '';
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$publish = '';
	if(isset($_REQUEST['publish']))
	{
		$publish = $_REQUEST['publish'];
	}
	$sql = "UPDATE poster SET publish =".$publish.", write_date=".$appSession->getTier()->getDateString().", write_uid='".$appSession->getUserInfo()->getId()."'";
	$sql = $sql." WHERE id='".$id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}
?>
