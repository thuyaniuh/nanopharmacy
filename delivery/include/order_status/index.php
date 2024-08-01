<?php
$rel_id = "";
if(isset($_REQUEST['rel_id']))
{
	$rel_id = $_REQUEST['rel_id'];
}
$msg = $appSession->getTier()->createMessage();

$sql = "SELECT d1.name, d1.create_date, d1.description FROM res_status_line d1 WHERE d1.status=0 AND d1.rel_id='".$rel_id."'";
$sql = $sql." ORDER BY d1.create_date ASC";
$msg->add("query", $sql);
$lines = $appSession->getTier()->getArray($msg);
for($i=0; $i<count($lines); $i++)
{
	$name = $lines[$i][0];
	$create_date = $lines[$i][1];
	$description = $lines[$i][2];
	if($create_date != ""){
		$create_date = $appSession->getFormats()->getDATE()->formatShortDateTime($appSession->getTool()->toDateTime($create_date));
	}
	if($i>0)
	{
		echo "<hr>";
	}
?>
<b><?php echo $name;?><b> - <i><?php echo $create_date;?></i><br>
<?php echo $description;?>
<?php
}
?>
