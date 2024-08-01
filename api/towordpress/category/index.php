<?php
$msg = $appSession->getTier()->createMessage();
$sql = "SELECT d1.id, d2.url, d1.name FROM product_category d1 LEFT OUTER JOIN url_shorter d2 ON(d1.id = d2.rel_id) WHERE d1.status =0";
$msg->add("query", $sql);
$dt = $appSession->getTier()->getTable($msg);
$s = "id\tthird_party_id\tname";
$s = $s."s\ts\ts";
for($i =0; $i<$dt->getRowCount(); $i++)
{
	$id = $dt->getString($i, "id");
	$third_party_id = $dt->getString($i, "url");
	$name = $dt->getString($i, "name");
	
}
?>
