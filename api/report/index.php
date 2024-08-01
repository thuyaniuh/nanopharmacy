<?php
//id, app_id, name, company_id, user_group_id, url
$reports = [
["01", "v1", "Chi tiết hóa đơn", "", "", "api/report/sale/checking_list_detail.php"],
["02", "v1", "Hóa đơn bán hàng", "", "", "api/report/sale/sale_by_bill.php"],
["03", "v1", "Thánh toán", "", "", "api/report/sale/sale_payment.php"],
["04", "v1", "Giảm giá", "", "", "api/report/sale/sale_discount.php"]
];

$ac = '';
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}

$app_id = '';
if(isset($_REQUEST['app_id']))
{
	$app_id = $_REQUEST['app_id'];
}
$lang_id = '';
if(isset($_REQUEST['lang_id']))
{
	$lang_id = $_REQUEST['lang_id'];
}
$company_id = '';
if(isset($_REQUEST['company_id']))
{
	$company_id = $_REQUEST['company_id'];
}
$user_id = '';
if(isset($_REQUEST['user_id']))
{
	$user_id = $_REQUEST['user_id'];
}
if($user_id == "")
{
	exit();
}
$user_group_id = '';
if(isset($_REQUEST['user_group_id']))
{
	$user_group_id = $_REQUEST['user_group_id'];
}
if($ac == "list")
{
	echo "id\tname";
	for($i =0; $i<count($reports); $i++)
	{
		$valid = false;
		if($reports[$i][1] == $app_id)
		{
			$valid = true;
		}
		if($valid == true){
			echo "\n";
			echo $reports[$i][0]."\t".$reports[$i][2];
		}
		
	}
}else if($ac== "view")
{
	$fdate = '';
	if(isset($_REQUEST['fdate']))
	{
		$fdate = $_REQUEST['fdate'];
	}
	$tdate = '';
	if(isset($_REQUEST['tdate']))
	{
		$tdate = $_REQUEST['tdate'];
	}
	$id = '';
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$path = "";
	for($i =0; $i<count($reports); $i++)
	{
		if($reports[$i][0]== $id){
			$path = $reports[$i][5];
			break;
		}
	}
	
	if($path != "")
	{
		include( ABSPATH.$path);
	}
}


?>
