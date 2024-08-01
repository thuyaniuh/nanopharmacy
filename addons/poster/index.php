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
	<div id="pnPoster"></div>
	<script>
	function loadPoster()
	{
		var _url = '<?php echo URL;?>addons/poster/?ac=list_poster&rel_id=<?php echo $rel_id;?>';
		loadPage('pnPoster', _url, function(status, message)
		{
			if(status == 0)
			{
			}
			
		}, false);
	}
	function delPoster(id)
	{
		var result = confirm("Want to delete?");
		if (!result) {
			return;
		}

		var _url = '<?php echo URL;?>addons/poster/?ac=del&id=' + id ;					
		loadPage('pnPoster', _url, function(status, message)
		{
			if(status  == 0)
			{
				if(message == "OK")
				{
					loadPoster();
					
				}else
				{
					alert(message);
				}
			}
		}, true);
	}
	function publishChanged(theInput, id)
	{
		var publish = 0;
		if(theInput.checked == true)
		{
			publish = 1;
		}
		var _url = '<?php echo URL;?>addons/poster/?ac=publish&id=' + id ;	
		_url = _url + '&publish=' + publish;
		loadPage('pnPoster', _url, function(status, message)
		{
			if(status  == 0)
			{
				if(message == "OK")
				{
					//loadPoster();
					
				}else
				{
					alert(message);
				}
			}
		}, true);
	}
	loadPoster();
	</script>
	<?php
}else if($ac == "list_poster")
{
	$rel_id = '';
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	
	
	
	$sql = "SELECT d1.id, d1.document_id, d1.publish FROM poster d1 WHERE d1.rel_id='".$rel_id."' AND d1.status =0";
	$sql = $sql." ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);
	$arr = $appSession->getTier()->getArray($msg);
	$pageIndex = 0;
	?>
	<input id="files<?php echo $pageIndex;?>" type="file" multiple  style="display:none" onchange="readFile<?php echo $pageIndex;?>('', this);"><a href="javascript:document.getElementById('files<?php echo $pageIndex;?>').click();"> <img src="<?php echo URL;?>assets/images/publish.png"/></a> <span id="progress_upload<?php echo $pageIndex;?>"></span>
	<div class="responsive" >
		<table class="table">
		<thead>
			<tr>
				<th><?php $appSession->getLang()->find("Image");?></th>
				<th width="50" nowrap="nowrap"><?php $appSession->getLang()->find("Publish");?></th>
				<th width="30"></th>
			</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $i<count($arr); $i++)
		{
			$id = $arr[$i][0];
			$document_id = $arr[$i][1];
			$publish = $arr[$i][2];
			$checked = "";
			if($publish == "1")
			{
				$checked = " checked ";
			}
		?>
		<tr>
			<td><img style="max-height:150px" src="<?php echo URL;?>document/?id=<?php echo $document_id;?>"><td>
			<td width="40" align="center" valign="middle"><input type="checkbox" onclick="publishChanged(this, '<?php echo $id;?>');" class="checkbox" <?php echo $checked;?> /></td>
			<td width="30" align="center" valign="middle"><a href="javascript:delPoster('<?php echo $id;?>')"><img src="<?php echo URL;?>assets/images/remove.png"/> </a></td>
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
		var document_id = '';
		
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
														_url = '<?php echo URL;?>addons/poster/?ac=addPoster&rel_id=<?php echo $rel_id;?>&document_id=' + file_id ;
											
														loadPage('pnPoster', _url, function(status, message)
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
																		loadPoster();
																	}
																	
																}else
																{
																	alert(message);
																}
															}
														}, true);
														
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
}else if($ac == "addPoster")
{
	$rel_id = '';
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$document_id = '';
	if(isset($_REQUEST['document_id']))
	{
		$document_id = $_REQUEST['document_id'];
	}
	$sql = "SELECT id FROM poster WHERE rel_id='".$rel_id."'";
	$msg->add("query", $sql);
	$id = $appSession->getTier()->getValue($msg);
	if($id == ""){
		$id = $appSession->getTool()->getId();

		$builder = $appSession->getTier()->createBuilder("poster");
		$builder->add("id", $id);
		$builder->add("create_uid", $appSession->getUserInfo()->getId());
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_uid", $appSession->getUserInfo()->getId());
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("rel_id", $rel_id);
		$builder->add("document_id", $document_id);
		$builder->add("publish", $publish);
		$builder->add("status", 0);
		$builder->add("company_id", $appSession->getUserInfo()->getCompanyId());
		$sql = $appSession->getTier()->getInsert($builder);
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
	}else{
		$sql = "UPDATE poster SET publish =1, document_id='".$document_id."', write_date=now() where id='".$id."'";
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
	}
	
	echo "OK";
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
