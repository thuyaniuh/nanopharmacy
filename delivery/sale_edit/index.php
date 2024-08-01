<?php
validUser($appSession);
$order_no = '';
if(isset($_REQUEST['order_no']))
{
	$order_no = $_REQUEST['order_no'];
}
$msg = $appSession->getTier()->createMessage();
if($order_no != "")
{
	$sql = "SELECT id FROM sale WHERE order_no='".$order_no."'";
	$msg->add("query", $sql);
	$sale_id = $appSession->getTier()->getValue($msg);
	if($sale_id != "")
	{
		$sql = "update sale_local set status =0, write_date=now() where id ='".$sale_id."'; update sale_product_local set status =0, write_date=now() where quantity>0 and sale_id ='".$sale_id." AND id IN(select id from sale_product WHERE status =0 AND sale_id ='".$sale_id."')'; update account_payment_line_local set status =0, write_date=now() where rel_id ='".$sale_id."'; update account_service_line_local set status =0, write_date=now() where rel_id ='".$sale_id."'; delete from sale  where id ='".$sale_id."'; delete from sale_product  where sale_id ='".$sale_id."'; delete from account_payment_line  where rel_id ='".$sale_id."'; delete from account_service_line where rel_id ='".$sale_id."';";
		$msg->add("query", $sql);
		$appSession->getTier()->exec($msg);
		echo $order_no." is restore.<br>";
	}
}
?>
Số đơn:
<input type="text" value="" id = "editorder_no">
<input type="button" value="Restore" onclick="restore()"/>
<script>
	function restore(){
		var ctr = document.getElementById('editorder_no');
		if(ctr.value == ''){
			alert('Please enter order no');
			ctr.focus();
			return;
		}
		document.location.href ='<?php echo URL;?>sale_edit/?order_no=' + ctr.value;
	}
</script>