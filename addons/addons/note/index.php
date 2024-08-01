<?php
require_once(ABSPATH.'api/Product.php' );
require_once(ABSPATH.'api/Sale.php' );
$msg = $appSession->getTier()->createMessage();
$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}

if($ac == "add"){
	$content = "";
	if(isset($_REQUEST['content']))
	{
		$content = $_REQUEST['content'];
	}
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$builder = $appSession->getTier()->createBuilder("note");
	$id = $appSession->getTool()->getId();
	$builder->add("id", $id);
	
	$builder->add("create_uid", $appSession->getUserInfo()->getId());
	$builder->add("write_uid", $appSession->getUserInfo()->getId());
	$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
	$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
	$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
	$builder->add("status",0);
	$builder->add("receipt_no", $appSession->getTool()->findReceiptNo($appSession->getTier(),$appSession->getConfig()->getProperty("company_id"), "note" ));
	$builder->add("rel_id", $rel_id);
	$builder->add("content", $content);
	$sql = $appSession->getTier()->getInsert($builder);
	$msg->add("query", $sql);
	$appSession->getTier()->exec($msg);
	
	echo "OK:".$id;
	
}else if($ac == "view"){
	$rel_id = "";
	if(isset($_REQUEST['rel_id']))
	{
		$rel_id = $_REQUEST['rel_id'];
	}
	$sql = "SELECT d1.id, d1.receipt_no, d1.create_date, d1.parent_id, d1.content, d2.name AS account_name FROM note d1 LEFT OUTER JOIN res_user d2 ON(d1.create_uid = d2.id) WHERE d1.rel_id ='".$rel_id."'";
	$sql = $sql." ORDER BY d1.create_date ASC";
	$msg->add("query", $sql);
	$dt_note = $appSession->getTier()->getTable($msg);
	$values = [];
	for($i =0; $i<$dt_note->getRowCount(); $i++)
	{
		$values[count($values)] = ["id" => $dt_note->getString($i, "id"), "receipt_no" => $dt_note->getString($i, "receipt_no"), "create_date" => $dt_note->getString($i, "create_date"), "parent_id" => $dt_note->getString($i, "parent_id"), "content" => $dt_note->getString($i, "content"), "account_name" => $dt_note->getString($i, "account_name")];
	}
	echo json_encode($values);
	
}
		
?>