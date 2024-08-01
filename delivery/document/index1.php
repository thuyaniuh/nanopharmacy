<?php

	$ac = "";
	if(isset($_REQUEST['ac']))
	{
		$ac = $_REQUEST['ac'];
	}
	if($ac == "")
	{
		$ac = "view";
	}

?>
<?php 
if($ac == "view")
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
	if(isset($_REQUEST['pageIndex']))
	{
		$pageIndex = $_REQUEST['pageIndex'];
	}
	$pageIndex = "";
	$can_edit = '';
	if(isset($_REQUEST['can_edit']))
	{
		$can_edit = $_REQUEST['can_edit'];
	}
	$img = '';
	if(isset($_REQUEST['img']))
	{
		$img = $_REQUEST['img'];
	}
	$frm = '';
	if(isset($_REQUEST['frm']))
	{
		$frm = $_REQUEST['frm'];
	}
	?>
	<?php if($can_edit == "1"){?>
	<div class="row">
		<div class="col-md-12">
		<input id="files<?php echo $pageIndex;?>" type="file" multiple  style="display:none" onchange="readFile<?php echo $pageIndex;?>('', this);"><a href="javascript:document.getElementById('files<?php echo $pageIndex;?>').click();"> &nbsp;&nbsp; <img src="<?php echo URL;?>assets/img/publish.png"> <?php echo $appSession->getLang()->find('Upload');?>&nbsp;&nbsp;</a> <span id="progress_upload<?php echo $pageIndex;?>"></span>
		</div>
	</div>
	<?php } ?>
	<hr>
	<div class="row">
		<div class="col-md-12">
			<div id ="list<?php echo $pageIndex;?>"></div>
		</div>
	</div>
	<script>
	var reader<?php echo $pageIndex; ?>;
	var files<?php echo $pageIndex; ?>;
	var names<?php echo $pageIndex; ?> = new Array();
	var file_index<?php echo $pageIndex; ?> = 0;

	function viewDoc<?php echo $pageIndex; ?>()
	{
		var _url = '<?php echo URL;?>addons/document/photo.php?ac=list_doc&rel_id=<?php echo $rel_id;?>&pageIndex=<?php echo $pageIndex; ?>&can_edit=<?php echo $can_edit;?>' ;
		loadPage('list<?php echo $pageIndex;?>', _url, function(status, message)
		{
			if(status== 0)
			{
				
			}
			
		}, false);
				
	}
	function selectFile<?php echo $pageIndex;?>(id)
	{
		var frm = document.getElementById('<?php echo $frm;?>');
		var img = '<?php echo $img;?>';
		if(frm != null)
		{
			for(var x =0; x<frm.elements.length; x++)
			{
				
				if(frm.elements[x].id == img)
				{
					frm.elements[x].value = id;
					document.getElementById('img<?php echo $img;?>').src ='<?php echo URL;?>document/action/?id=' + id + '&width=122&height=92';
					
					break;
				}
			}
		}
		closePopup();
		
	}
	

	function delDoc<?php echo $pageIndex; ?>(id)
	{
		var result = confirm("<?php echo $appSession->getLang()->find('Are you sure to clone');?>");
		if (!result) {
			return;
		}	
		var _url = '<?php echo URL;?>addons/document/action.php?ac=delDoc&id=' + id;
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
		var document_id = '';
		if(file_index<?php echo $pageIndex; ?> >0)
		{
			document_id = "";
		}
		var _url = '<?php echo URL;?>addons/document/action.php?ac=createFile';
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
					
					var baseUrl = '<?php echo URL;?>addons/document/action.php?ac=writeFile&file_id=' + file_id ;
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
											_url = '<?php echo URL;?>addons/document/action.php?ac=commitDocument&file_id=' + file_id ;
											_url = _url + '&file_name=' + encodeURIComponent(names<?php echo $pageIndex; ?>[file_index<?php echo $pageIndex; ?>] + extension);
											_url = _url + '&rel_id=<?php echo $rel_id;?>';
											
											
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
															selectFile<?php echo $pageIndex;?>(file_id);
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
}else if($ac == "list_doc")
{
	$rel_id = '';
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$pageIndex = '';
	if(isset($_REQUEST['pageIndex']))
	{
		$pageIndex = $_REQUEST['pageIndex'];
	}
	$sql = "SELECT d1.id, d1.name FROM document d1 WHERE d1.rel_id='".$rel_id."' AND d1.status =0 ORDER BY d1.create_date ASC";
		

	$result =$appSession->getTier()->getArray($sql);
	$numrows = count($result);
	
?>
<style type="text/css">

.column {
  float: left;
  width: 120;
  height:92;
 
}

.column img {
  border: 0;
  cursor: pointer;
}

.column img:hover {
  border: 1;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}



.one0 {
	transform: rotate(90deg);
}
.one1 {
	transform: rotate(180deg);
}
.one2 {
	transform: rotate(270deg);
}

</style>
<div class="row">
	<?php
			for($i =0; $i<$numrows; $i++)
			{
				$row = $result[$i];
				$file_id = $row[0];
				$name = $row[1];
				if($name == "")
				{
					$name = "Select";
				}
		

			?>
  <div class="column">
    <img src="<?php echo URL;?>addons/document/action.php?id=<?php echo $file_id;?>&width=122&height=92" alt="<?php echo $name;?>" name="<?php echo $file_id;?>" width="122" height="92" id="img<?php echo $i;?>" onclick="myFunction(<?php echo $i;?>);">
	<br><a href="javascript:selectFile('<?php echo $file_id;?>')"><?php echo $name;?></a>
  </div>
  <?php
	}
	?>

</div>
<hr>
<button type="button" onclick="rotateImage(0)">0</button> 
<button type="button" onclick="rotateImage(1)">90</button> 
<button type="button" onclick="rotateImage(2)">180</button> 
<button type="button" onclick="rotateImage(3)">270</button>
<button type="button" onclick="zoomin()"> Zoom-In</button> 
      <button type="button" onclick="zoomout()">  
        Zoom-Out 
    </button> 
<div class="container" style="text-align:center; valign:top;" >
	<table width="100%">
	<tr>
		<td width="50" align="left"><button type="button" onclick="backImg()"> Back </button> </td>
		<td align="center">
	<span id="imgtext"></span>
	</td>
	<td width="50" align="right"><button type="button" onclick="nextImg()"> Next </button> </td>
	</tr>
	</table>
	<div style="border-style: solid; order-color: #92a8d1;">
  <img id="expandedImg" onclick="rotateClick();">
  </div>
  
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<script>
var index = 0;
var img_index =0;
var image_count = <?php echo $numrows;?>;
function myFunction(i) {
	index = 0;
	img_index = i;
	imgs = document.getElementById('img' + i);
	
  var expandImg = document.getElementById("expandedImg");
  
  var imgText = document.getElementById("imgtext");
  var src = imgs.src;
  var index = src.indexOf('&width');
  if(index != -1)
  {
	  src = src.substring(0, index);
  }

  expandImg.src = src;
  // Use the value of the alt attribute of the clickable image as text inside the expanded image
  imgText.innerHTML = '<a href="javascript:selectFile<?php echo $pageIndex;?>(\''+ imgs.name + '\')">' + imgs.alt + '</a>';
  // Show the container element (hidden with CSS)
  expandImg.parentElement.style.display = "block";
}
<?php
if($numrows>0)
{
?>
myFunction(0);
<?php
}
?>
function backImg()
{
	img_index = img_index -1;
	if(img_index<0)
	{
		img_index = image_count -1;
	}
	myFunction(img_index);
}
function nextImg()
{
	img_index = img_index + 1;
	if(img_index>(image_count -1))
	{
		img_index = 0;
	}
	myFunction(img_index);
}

function rotateImage(index) {
	
	var img = document.getElementById('expandedImg');
	if(index == 0)
	{
		img.className = '';
	}else if(index == 1)
	{
		img.className = 'one0';
	}else if(index == 2)
	{
		img.className = 'one1';
	}else if(index == 3)
	{
		img.className = 'one2';
	}
	
}
 function zoomin() { 
	var GFG = document.getElementById("expandedImg"); 
	var currWidth = GFG.clientWidth; 
	GFG.style.width = (currWidth + 100) + "px"; 
} 
  
function zoomout() { 
	var GFG = document.getElementById("expandedImg"); 
	var currWidth = GFG.clientWidth; 
	GFG.style.width = (currWidth - 100) + "px"; 
} 
function rotateClick()
{
	index = index + 1;
	if(index>3)
	{
		index = 0;
	}
	rotateImage(index);
}
<?php
}
?>
