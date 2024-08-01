<?php

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
if($ac == "notification_list")
{
	$msg = $appSession->getTier()->createMessage();
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	if(isset($_POST['rel_id']))
	{
		$rel_id = $_POST['rel_id'];
	}
	$sql = "SELECT d1.id, d1.name, d1.description, d1.type, d1.notification_id, d1.create_date, d1.status FROM res_notification d1 WHERE d1.rel_id='".$rel_id."' AND d1.status =0 AND d1.seen = 0 AND d1.status != 1";
	$sql = $sql." ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);

	$sql = "UPDATE res_notification SET status =2 WHERE rel_id='".$rel_id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo respTable($dt);
	
}else if($ac == "notification_list_view")
{
	$msg = $appSession->getTier()->createMessage();
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	if(isset($_POST['rel_id']))
	{
		$rel_id = $_POST['rel_id'];
	}
	$fdate = "";
	if(isset($_REQUEST['fdate']))
	{
		$fdate = $_REQUEST['fdate'];
	}
	$tdate = "";
	if(isset($_REQUEST['tdate']))
	{
		$tdate = $_REQUEST['tdate'];
	}
	$search = "";
	if(isset($_REQUEST['search']))
	{
		$search = $_REQUEST['search'];
	}
	$sql = "SELECT d1.id, d1.name, d1.description, d1.type, d1.notification_id, d1.create_date FROM res_notification d1 WHERE (d1.status =0 OR d1.status=2)";
	$sql = $sql." AND  d1.rel_id='".$rel_id."'";
	if($fdate != "")
	{
		$sql = $sql." AND  d1.create_date>='".$fdate."'";
	}
	if($tdate != "")
	{
		$sql = $sql." AND  d1.create_date<='".$tdate."'";
	}
	if($tdate != "")
	{
		$sql = $sql." AND  d1.create_date<='".$tdate."'";
	}
	if($search != "")
	{
		$sql = $sql." AND (".$appSession->getTier()->buildSearch(["d1.name", "d1.description"], $search).")";
	}
	$sql = $sql." ORDER BY d1.create_date DESC";
	$sql = $sql. " LIMIT 50";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
	
}else if($ac == "notification_seen"){
	
	$msg = $appSession->getTier()->createMessage();
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sql = "UPDATE res_notification SET seen=1, write_date=".$appSession->getTier()->getDateString()." WHERE id='".$id."'";
	$msg->add("query", $sql);
	$result = $appSession->getTier()->exec($msg);
	echo "OK";
}
?>