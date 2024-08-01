<?php
require_once(ABSPATH.'api/User.php' );
$ac = '';
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
$ac = '';
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}

if($ac == "gettable")
{
	$sql = "";
	if(isset($_REQUEST['q']))
	{
		$sql = $_REQUEST['q'];
	}
	
	if($sql != "")
	{
		$msg = $appSession->getTier()->createMessage();
		$msg->add("query", $sql);
		$dt = $appSession->getTier()->getTable($msg);
		echo $appSession->getTool()->respTable($dt);
	}
	
}else if($ac == "exec")
{
	$sql = "";
	if(isset($_REQUEST['q']))
	{
		$sql = $_REQUEST['q'];
	}
	if($sql != "")
	{
		$msg = $appSession->getTier()->createMessage();
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		echo "1";
	}
	
}else if($ac == "getValue")
{
	$sql = "";
	if(isset($_REQUEST['q']))
	{
		$sql = $_REQUEST['q'];
	}
	if($sql != "")
	{
		$msg = $appSession->getTier()->createMessage();
		$msg->add("query", $sql);
		echo $appSession->getTier()->getValue($msg);
	}
	
}

?>