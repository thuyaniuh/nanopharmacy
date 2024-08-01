<?php
$id = '';
if(isset($_REQUEST['id']))
{
	$id = $_REQUEST['id'];
}
$user_id = '';
if(isset($_REQUEST['user_id']))
{
	$user_id = $_REQUEST['user_id'];
}
$company_id = '';
if(isset($_REQUEST['company_id']))
{
	$company_id = $_REQUEST['company_id'];
}
$msg = $appSession->getTier()->createMessage();
$sql = "SELECT d1.id, d1.resource FROM res_resource d1 WHERE d1.rel_id='".$id."' AND d1.status =0";
$msg->add("query", $sql);
$arr = $appSession->getTier()->getArray($msg);
if(count($arr)>0)
{
	$resource_id = $arr[0][0];
	$resource = $arr[0][1];
	$path = ABSPATH."log/".$resource_id.".php";
	$myfile = fopen(ABSPATH."log/".$resource_id.".php", "w");
	fwrite($myfile, $resource);
	fclose($myfile);
	include($path);
	
}

?>