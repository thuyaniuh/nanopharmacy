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
	$sql = "SELECT d1.id, d1.name, d1.description, d1.type, d1.notification_id, d1.create_date, d1.status FROM res_notification d1 WHERE d1.rel_id='".$rel_id."' AND d1.status =0 AND d1.seen = 0";
	$sql = $sql." ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);

	$sql = "UPDATE res_notification SET status =2 WHERE rel_id='".$rel_id."'";
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
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