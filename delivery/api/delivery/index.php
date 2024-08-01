<?php
require_once(ABSPATH.'api/Status.php' );
function respTable($dt) 
{

	for($i =0; $i <count($dt->getColumns()); $i++)
	{
		if($i>0)
		{
			echo "\t";
		}
		echo $dt->getColumns()[$i]->getName($i);
	}
	for($r =0; $r<$dt->getRowCount(); $r++)
	{
		echo "\n";
		for($i =0; $i <count($dt->getColumns()); $i++)
		{
			if($i>0)
			{
				echo "\t";
			}
			echo $dt->getStringAt($r, $i);
		}
	}
	
}
$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
if($ac == "delivery_list")
{
	$msg = $appSession->getTier()->createMessage();
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	if(isset($_POST['user_id']))
	{
		$user_id = $_POST['user_id'];
	}
	$fdate = "";
	if(isset($_REQUEST['fdate']))
	{
		$fdate = $_REQUEST['fdate'];
	}
	if(isset($_POST['fdate']))
	{
		$fdate = $_POST['fdate'];
	}
	$tdate = "";
	if(isset($_REQUEST['tdate']))
	{
		$tdate = $_REQUEST['tdate'];
	}
	if(isset($_POST['tdate']))
	{
		$tdate = $_POST['tdate'];
	}
	
	$sql = "SELECT d1.id, d1.name, d1.tel, d1.email, d1.address, d1.description, d1.sale_id, d2.order_no, d2.order_date FROM sale_shipping d1 LEFT OUTER JOIN sale_local d2 ON(d1.sale_id = d2.id) WHERE d1.user_id='".$user_id."' AND (d1.proccess = 0 OR d1.proccess = 1 OR d1.proccess IS NULL)";
	$sql = $sql." AND d2.receipt_date>='".$fdate."'";
	$sql = $sql." AND d2.receipt_date<='".$tdate."'";
	$sql = $sql." ORDER BY d2.receipt_date ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);

	echo respTable($dt);
	
}else if($ac == "sale_order_product"){
	$msg = $appSession->getTier()->createMessage();
	
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	
	$sql = "SELECT m.id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d3.name AS unit_name, d4.code AS currency_code, m.quantity, m.unit_price";
	$sql = $sql." FROM sale_product_local m";
	$sql = $sql." LEFT OUTER JOIN product d1 ON(m.product_id = d1.id)";
	$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1)";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$lang_id."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
	$sql = $sql." LEFT OUTER JOIN product_unit d3 ON(m.unit_id = d3.id)";
	$sql = $sql." LEFT OUTER JOIN res_currency d4 ON(m.currency_id = d4.id)";
	$sql = $sql." WHERE m.status =0 AND m.sale_id='".$sale_id."'";
	$sql = $sql." ORDER BY m.create_date ASC";
	
	$msg->add("query", $sql);
	
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
	
}else if($ac == "sale_shipping")
{
	$msg = $appSession->getTier()->createMessage();
	
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	
	$sql = "SELECT d1.id, d1.name, d1.tel, d1.email, d1.address, d1.description, d1.proccess, d2.order_no, d2.order_date";
	$sql = $sql." FROM sale_shipping d1";
	$sql = $sql." LEFT OUTER JOIN sale_local d2 ON(d1.sale_id = d2.id)";
	$sql = $sql." WHERE d1.sale_id='".$sale_id."'";
	
	$msg->add("query", $sql);
	
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
}
else if($ac == "delivery_proccess"){
	$msg = $appSession->getTier()->createMessage();
	
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$status_id = "";
	if(isset($_REQUEST['status_id']))
	{
		$status_id = $_REQUEST['status_id'];
	}
	$sale_id = "";
	if(isset($_REQUEST['sale_id']))
	{
		$sale_id = $_REQUEST['sale_id'];
	}
	$proccess = "";
	if(isset($_REQUEST['proccess']))
	{
		$proccess = $_REQUEST['proccess'];
	}
	$sql = "UPDATE sale_shipping SET proccess =".$proccess."";
	$sql = $sql.", write_date=".$appSession->getTier()->getDateString();
	if($proccess == "1")
	{
		$sql = $sql.", start_date=".$appSession->getTier()->getDateString();
	}
	if($proccess == "2")
	{
		$sql = $sql.", end_date=".$appSession->getTier()->getDateString();
	}
	$sql = $sql." WHERE id='".$id."'";
	$msg->add("query", $sql);
	$result = $appSession->getTier()->exec($msg);
	if($status_id != "")
	{
		$status = new Status($appSession);
		$status->doStatus($sale_id, "sale_local", $statu_id);
	}
	echo "OK";
}else if($ac == "status_list")
{
	$msg = $appSession->getTier()->createMessage();
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$type = "";
	if(isset($_REQUEST['type']))
	{
		$type = $_REQUEST['type'];
	}
	$sql = "SELECT d1.id, d1.name FROM res_status d1";
	$sql = $sql." WHERE d1.status = 0 AND d1.table_id ='".$type."'";
	$sql = $sql." AND d1.id NOT IN(select status_id FROM res_status_line where status =0 AND rel_id='".$rel_id."')";
	$sql = $sql." ORDER BY d1.sequence ASC";
	$msg->add("query", $sql);
	
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
	
}
?>