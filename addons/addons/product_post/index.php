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
	<a href="javascript:formPost('')"><img src="<?php echo URL;?>assets/images/add.png"/></a>
	<div id="pnProductPost"></div>
	
	<script>
	function loadPost()
	{
		var _url = '<?php echo URL;?>addons/product_post/?ac=list_poster&rel_id=<?php echo $rel_id;?>';
		loadPage('pnProductPost', _url, function(status, message)
		{
			if(status == 0)
			{
			}
			
		}, false);
	}
	function formPost(id)
	{
		var _url = '<?php echo URL;?>addons/product_post/?ac=form&id=' + id;
		openPopup(_url, function(status, message){
			
		});
	}
	function delPost(id)
	{
		var result = confirm("Want to delete?");
		if (!result) {
			return;
		}

		var _url = '<?php echo URL;?>addons/product_post/?ac=del&id=' + id ;					
		loadPage('pnPoster', _url, function(status, message)
		{
			if(status  == 0)
			{
				if(message == "OK")
				{
					loadPost();
					
				}else
				{
					alert(message);
				}
			}
		}, true);
	}
	function savePost(id)
	{
		var ctr = document.getElementById('editpost_name');
		if(ctr.value == '')
		{
			alert('<?pp echo $appSession->getLang()->find("Please enter name");?>');
			ctr.focus();
			return;
		}
		var name = ctr.value;
		var ctr = document.getElementById('editpost_category_id');
		var category_id = ctr.value;
		
		var ctr = document.getElementById('editpost_content');
		if(ctr.value == '')
		{
			alert('<?pp echo $appSession->getLang()->find("Please enter content");?>');
			ctr.focus();
			return;
		}
		var content = ctr.value;
		var _url = '<?php echo URL;?>addons/product_post/?ac=savePost&rel_id=<?php echo$rel_id;?>&id=' + id ;
		_url = _url + '&name=' + encodeURIComponent(name);
		_url = _url + '&content=' + encodeURIComponent(content);
		_url = _url + '&category_id=' + category_id;
		loadPage('pnPoster', _url, function(status, message)
		{
			if(status  == 0)
			{
				if(message == "OK")
				{
					closePopup();
					loadPost();
					
				}else
				{
					alert(message);
				}
			}
		}, true);
		
	}
	
	loadPost();
	</script>
	<?php
}else if($ac == "list_poster")
{
	$rel_id = '';
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	
	
	
	$sql = "SELECT d1.id, d1.name, d2.name AS category_name FROM post d1 LEFT OUTER JOIN post_category d2 ON(d1.category_id = d2.id) WHERE d1.rel_id='".$rel_id."' AND d1.status =0";
	$sql = $sql." ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);
	$arr = $appSession->getTier()->getArray($msg);
	$pageIndex = 0;
	?>
	
	<div class="responsive" >
		<table class="table">
		<thead>
			<tr>
				<th>Tên</th>
				<th width="150" nowrap="nowrap">Nhóm</th>
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
			
		?>
		<tr>
			<td><?php echo $name;?><td>
			<td width="140" align="center" valign="middle"><?php echo $category_name;?></td>
			<td width="30" align="center" valign="middle"><a href="javascript:formPost('<?php echo $id;?>')"><img src="<?php echo URL;?>assets/images/edit.png"/> </a> </td>
			<td width="30" align="center" valign="middle"><a href="javascript:delPost('<?php echo $id;?>')"><img src="<?php echo URL;?>assets/images/remove.png"/> </a></td>
		</tr>
		<?php
		}
		?>
		</tbody>
		</table>
	</div>


	<?php
}else if($ac == "savePost")
{
	$id = '';
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	
	$rel_id = '';
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$name = '';
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$content = '';
	if(isset($_REQUEST['content']))
	{
		$content = $_REQUEST['content'];
	}
	$category_id = '';
	if(isset($_REQUEST['category_id']))
	{
		$category_id = $_REQUEST['category_id'];
	}
	$sql = "SELECT id FROM post WHERE id='".$id."'";
	$msg->add("query", $sql);
	$id = $appSession->getTier()->getValue($msg);
	if($id == ""){
		$id = $appSession->getTool()->getId();

		$builder = $appSession->getTier()->createBuilder("post");
		$builder->add("id", $id);
		$builder->add("create_uid", $appSession->getUserInfo()->getId());
		$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("write_uid", $appSession->getUserInfo()->getId());
		$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
		$builder->add("rel_id", $rel_id);
		$builder->add("category_id", $category_id);
		$builder->add("name", $name);
		$builder->add("content", $content);
		$builder->add("status", 0);
		$builder->add("company_id", $appSession->getUserInfo()->getCompanyId());
		$sql = $appSession->getTier()->getInsert($builder);
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
	}else{
		$sql = "UPDATE post SET name ='".$appSession->getTool()->replace($name, "'", "''")."', content='".$appSession->getTool()->replace($content, "'", "''")."', category_id='".$category_id."', write_date=now() where id='".$id."'";
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
	$sql = "UPDATE post SET status =1, write_date=".$appSession->getTier()->getDateString().", write_uid='".$appSession->getUserInfo()->getId()."'";
	$sql = $sql." WHERE id='".$id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "form")
{
	$id = '';
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	if($id == "")
	{
		$id = $appSession->getTool()->getId();
	}
	$name = "";
	$category_id = "";
	$content = "";
	$sql = "SELECT category_id, name, content FROM post WHERE id='".$id."'";
	$msg->add("query", $sql);
	
	$values = $appSession->getTier()->getArray($msg);
	if(count($values)>0)
	{
		$category_id = $values[0][0];
		$name = $values[0][1];
		$content = $values[0][2];
		
	}
	$sql = "SELECT d1.id, d1.name FROM post_category d1 WHERE d1.status =0 AND d1.type='PRODUCT'";
	$sql = $sql." ORDER BY d1.name ASC";
	$msg->add("query", $sql);
	$dt_category = $appSession->getTier()->getTable($msg);
?>
<div class="row">
	<div class="col-md-12">
		Tiều đề: 
	</div>
	
</div>
<div class="row">
	<div class="col-md-12">
		<input class="form-control" id="editpost_name" value="<?php echo $name;?>">
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		Nhóm: 
	</div>
	
</div>
<div class="row">
	<div class="col-md-12">
		<select class="form-control" id="editpost_category_id">
			<?php
				for($i =0; $i<$dt_category->getRowCount(); $i++)
				{
				?>
				<option value="<?php echo $dt_category->getString($i, "id");?>" <?php if($category_id == $dt_category->getString($i, "id")){ echo " selected "; }?>><?php echo $dt_category->getString($i, "name");?></option>
				<?php
				}
				?>
		</select>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		Nội dung: 
	</div>
	
</div>
<div class="row">
	<div class="col-md-12">
		<textarea class="form-control" id="editpost_content"><?php echo $content;?></textarea>
	</div>
	
</div>
<hr>
<div class="row">
	<div class="col-md-12">
		<button class="btn btn-primary" type="button" onclick="savePost('<?php echo $id;?>')">Lưu nội dung</button>
	</div>
</div>

<?php
}
?>
