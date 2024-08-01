<?php

require_once(ABSPATH.'api/User.php' );
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
function listCompany($appSession, $id)
{
	$s = [];
	$sql = "SELECT id, parent_id FROM res_company WHERE status =0 AND parent_id='".$id."'";
	$result = $appSession->getTier()->getArray($sql);
	for($i=0; $i<count($result); $i++)
	{
		
		$s[count($s)] = [$result[$i][0], $result[$i][1]] ;
		$s1 = listCompany($appSession, $result[$i][0]);
		for($j =0; $j<count($s1); $j++)
		{
			$s[count($s)] = $s1[$j];
		}
	}
	return $s;
}

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
if($ac == "location")
{
	$user = '';
	if(isset($_REQUEST['user']))
	{
		$user = $_REQUEST['user'];
	}
	$sql = "SELECT d1.id, d2.name, d5.name AS group_name, d6.name AS company_name FROM res_user_rel d1";
	$sql = $sql." LEFT OUTER JOIN res_company d2 ON(d1.rel_id = d2.id)";
	$sql = $sql." LEFT OUTER JOIN res_user d4 ON(d1.user_id = d4.id)";
	$sql = $sql." LEFT OUTER JOIN res_user_group d5 ON(d1.group_id = d5.id)";
	$sql = $sql." LEFT OUTER JOIN res_company d6 ON(d2.company_id = d6.id)";
	$sql = $sql." WHERE d1.status =0 AND (d4.user_name='".str_replace("'", "''", $user)."' OR d4.email='".str_replace("'", "''", $user)."')";
	
	$dt = $appSession->getTier()->getTable($sql);
	
	respTable($dt);
}else if($ac == "check")
{
	echo "WORK";
}
else if($ac == "login")
{
	$user_name = "";
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
	}
	if(isset($_POST['user']))
	{
		$user_name = $_POST['user'];
	}
	$pass = '';
	if(isset($_REQUEST['password']))
	{
		$pass = $_REQUEST['password'];
	}
	if(isset($_POST['password']))
	{
		$pass = $_POST['password'];
	}
	$rel_user_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$rel_user_id = $_REQUEST['company_id'];
	}
	if(isset($_POST['company_id']))
	{
		$rel_user_id = $_POST['company_id'];
	}
	$user = new User();
	$message = $user->login($appSession, $user_name, $pass, $rel_user_id);
	echo $message;
}else if($ac == "registerAccount")
{
	$name = '';
	$email = '';
	$phone = '';
	$user_name = '';
	$password = '';
	
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	if(isset($_POST['name']))
	{
		$user = $_POST['name'];
	}
	if(isset($_REQUEST['email']))
	{
		$email = $_REQUEST['email'];
	}
	if(isset($_POST['email']))
	{
		$email = $_POST['email'];
	}
	
	if(isset($_REQUEST['phone']))
	{
		$phone = $_REQUEST['phone'];
	}
	if(isset($_POST['phone']))
	{
		$phone = $_POST['phone'];
	}
	
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	if(isset($_POST['user_name']))
	{
		$user_name = $_POST['user_name'];
	}
	
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	if(isset($_POST['password']))
	{
		$password = $_POST['password'];
	}
	$user = new User();
	$message = $user->create($appSession, $name, $email, $phone, $user_name, $password);
	
	echo $message;
	
}
?>