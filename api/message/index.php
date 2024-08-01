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
if($ac == "list")
{
	$msg = $appSession->getTier()->createMessage();
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
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
	$sql = "SELECT d1.id, d1.type, d2.name, d1.message, d1.create_date FROM chat_message d1 LEFT OUTER JOIN res_user d2 ON(d1.create_uid = d2.id) WHERE d1.status =0";
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
		$sql = $sql." AND (".$appSession->getTier()->buildSearch(["d2.name", "d1.message"], $search).")";
	}
	$sql = $sql." ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo respTable($dt);
	
}else if($ac == "add")
{
	$msg = $appSession->getTier()->createMessage();
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$type = "";
	if(isset($_REQUEST['type']))
	{
		$type = $_REQUEST['type'];
	}
	$message = "";
	if(isset($_REQUEST['message']))
	{
		$message = $_REQUEST['message'];
	}
	$id = $appSession->getTool()->getId();
	$builder = $appSession->getTier()->createBuilder("chat_message");
	$builder->add("id", $id);
	$builder->add("create_uid", $user_id);
	$builder->add("write_uid", $user_id);
	$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
	$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
	$builder->add("status", 0);
	$builder->add("rel_id", $rel_id);
	$builder->add("type", $type);
	$builder->add("message", $message);
	$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(), "", "chat_message"));
	$builder->add("company_id", $company_id);
	
	$sql = $appSession->getTier()->getInsert($builder);
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	echo $id;
	
}
?>