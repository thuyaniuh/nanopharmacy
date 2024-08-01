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
$msg = $appSession->getTier()->createMessage();
if($ac == "login")
{
	$user_name = "";
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	if(isset($_POST['user_name']))
	{
		$user_name = $_POST['user_name'];
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
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$user = new User();
	
	if($user_id != "")
	{
		$message = $user->loginById($appSession, $user_id);
		echo $message;
	}else{
		$message = $user->login($appSession, $user_name, $pass, $rel_user_id);
		echo $message;
	}
	
}else if($ac == "registerAccount")
{
	$name = '';
	$commercial_name = '';
	$email = '';
	$phone = '';
	$address_id = '';
	$address = '';
	$contact_name = '';
	$contact_email = '';
	$contact_mobile = '';
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
	if(isset($_REQUEST['commercial_name']))
	{
		$commercial_name = $_REQUEST['commercial_name'];
	}
	if(isset($_POST['commercial_name']))
	{
		$commercial_name = $_POST['commercial_name'];
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
	if(isset($_REQUEST['address_id']))
	{
		$address_id = $_REQUEST['address_id'];
	}
	if(isset($_POST['address_id']))
	{
		$address_id = $_POST['address_id'];
	}
	
	if(isset($_POST['address']))
	{
		$address = $_POST['address'];
	}
	if(isset($_REQUEST['address']))
	{
		$address = $_REQUEST['address'];
	}
	if(isset($_REQUEST['contact_name']))
	{
		$contact_name = $_REQUEST['contact_name'];
	}
	if(isset($_POST['contact_name']))
	{
		$contact_name = $_POST['contact_name'];
	}
	if(isset($_REQUEST['contact_email']))
	{
		$contact_email = $_REQUEST['contact_email'];
	}
	if(isset($_POST['contact_email']))
	{
		$contact_email = $_POST['contact_email'];
	}
	if(isset($_REQUEST['contact_mobile']))
	{
		$contact_mobile = $_REQUEST['contact_mobile'];
	}
	if(isset($_POST['contact_mobile']))
	{
		$contact_mobile = $_POST['contact_mobile'];
	}
	
	if(isset($_REQUEST['user']))
	{
		$user_name = $_REQUEST['user'];
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
	$message = $user->create($appSession, $name, $commercial_name, $email, $phone, $address_id, $address, $contact_name, $contact_mobile, $contact_email, $user_name, $password);
	if(strlen($message) == 36)
	{
		$code = rand(1000, 9999);
		$sql = "INSERT INTO res_user_verification(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", status";
		$sql = $sql.", create_uid";
		$sql = $sql.", code";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$appSession->getTool()->getId()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$message."'";
		$sql = $sql.", '".str_replace("'", "''", $code)."'";
		$sql = $sql.")";
		
		$msg->add("query", $sql);
		$result = $appSession->getTier()->exec($msg);
		if($phone != "")
		{
			$r = $appSession->getTool()->send_sms($phone, "Verify code:".$code);
		}
		if($email != "")
		{
			$r = $appSession->getTool()->send_mail(SUPPORT_EMAIL, "F&B", $email, "Register", "Verification Code", "Verification Code:".$code);
		}
	}
	
	echo $message;
	
}else if($ac == "change_forgot_password")
{
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	$new_password = "";
	if(isset($_REQUEST['new_password']))
	{
		$new_password = $_REQUEST['new_password'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	
	
	$s = $appSession->getTool()->toHash("sha256", "[".$user_id."]".$new_password);
	$len = $appSession->getTool()->lenght($new_password);
	for($i = 0; $i<$len; $i++)
	{
		$s = $s.chr($i + 48);
	}
	$new_password = $appSession->getTool()->toHash("md5", $s);
	$sql = "SELECT id FROM res_user WHERE id='".$user_id."'";
	$msg = $appSession->getTier()->createMessage();
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	if(count($values) > 0)
	{
		$sql = "UPDATE res_user SET write_date=".$appSession->getTier()->getDateString();
		$sql = $sql.", password='".$new_password."'";
		$sql = $sql." WHERE id='".$user_id."'";
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
		echo $user_id;
		
	}else{
		echo "INVALID_PASSWORD";
	}
}else if($ac == "active")
{
	$code = "";
	if(isset($_REQUEST['code']))
	{
		$code = $_REQUEST['code'];
	}
	$sql = "SELECT id, create_uid FROM res_user_verification WHERE code='".str_replace("'", "''", $code)."' AND status =0";
	$msg = $appSession->getTier()->createMessage();
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	if(count($values) > 0)
	{
		$sql = "UPDATE res_user SET status =0, write_date=".$appSession->getTier()->getDateString()." WHERE id='".$values[0][1]."'";
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
		
		$sql = "UPDATE res_user_verification SET status =1, write_date=".$appSession->getTier()->getDateString()." WHERE id='".$values[0][0]."'";
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);

		echo "OK";
	}else{
		echo "INVALID";
	}
}else if($ac == "forget_password")
{
	$phone = "";
	if(isset($_REQUEST['phone']))
	{
		$phone = $_REQUEST['phone'];
	}
	$email = "";
	if(isset($_REQUEST['email']))
	{
		$email = $_REQUEST['email'];
	}
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($phone == "" && $email == ""){
		exit();
	}
	
	$sql = "SELECT id FROM res_user WHERE status =0";
	if($phone != "")
	{
		$sql = $sql." AND phone='".str_replace("'", "''", $phone)."'";
	}
	if($email != "")
	{
		$sql = $sql." AND email='".str_replace("'", "''", $email)."'";
	}
	
	$msg = $appSession->getTier()->createMessage();
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	if(count($values) > 0)
	{
		$user_id = $values[0][0];
		$code = rand(1000, 9999);
		$sql = "INSERT INTO res_user_verification(";
		$sql = $sql."id";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", status";
		$sql = $sql.", create_uid";
		$sql = $sql.", code";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$appSession->getTool()->getId()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".$user_id."'";
		$sql = $sql.", '".str_replace("'", "''", $code)."'";
		$sql = $sql.")";
		$msg->add("query", $sql);
		$result = $appSession->getTier()->exec($msg);
		if($phone != "")
		{
			$r = $appSession->getTool()->send_sms($phone, "Mã OPT:".$code);
		}
		if($email != "")
		{
			$r = $appSession->getTool()->send_mail(SUPPORT_EMAIL, "F&B", $email, "Forgot password", "Verification Code", "Verification Code:".$code);
		}
		
		echo "OK:".$user_id;
	}else{
		echo "INVALID_PHONE";
	}
}else if($ac == "verify_user_code")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$code = "";
	if(isset($_REQUEST['code']))
	{
		$code = $_REQUEST['code'];
	}
	$sql = "SELECT id FROM res_user_verification WHERE (create_uid='".$user_id."' AND code='".str_replace("'", "''", $code)."') AND status =0";
	$msg = $appSession->getTier()->createMessage();
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	if(count($values) > 0)
	{
		$sql = "UPDATE res_user_verification SET status =1, write_date=".$appSession->getTier()->getDateString()." WHERE id='".$values[0][0]."'";
		$msg->add("query", $sql);
		$result = $appSession->getTier()->exec($msg);
		echo "OK";
	}else{
		echo "INVALID";
	}
}else if($ac == "user_list")
{
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$search = "";
	if(isset($_REQUEST['search']))
	{
		$search = $_REQUEST['search'];
	}
	
	
	$sql = "SELECT d1.id, d2.code, d2.name, d2.user_name, d2.avatar, d2.phone, d2.email, d1.group_id FROM res_user_company d1 LEFT OUTER JOIN res_user d2 ON(d1.user_id = d2.id) WHERE d1.status =0 AND d2.status =0 AND d1.company_id='".$company_id."'";
	if($search != "")
	{
		$search = $appSession->getTool()->replace($search, "'", "''");
		$sql = $sql." AND (d2.name ILIKE '%".$search."%' OR d2.user_name ILIKE '%".$search."%' OR d2.phone ILIKE '%".$search."%' OR d2.email ILIKE '%".$search."%')";
	}
	
	$sql = $sql." ORDER BY d2.name ASC";
	$msg = $appSession->getTier()->createMessage();
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
	
}else if($ac == "user_del")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sql = "SELECT user_id FROM res_user_company WHERE id='".$id."'";
	$msg = $appSession->getTier()->createMessage();
	$msg->add("query", $sql);
	$user_id = $appSession->getTier()->getValue($msg);
	if($user_id != "")
	{
		$sql = "UPDATE res_user SET status=1, write_date=NOW() WHERE id='".$user_id."'";
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($sql);
	}
	$sql = "UPDATE res_user_company SET status=1, write_date=NOW() WHERE id='".$id."'";
	$msg->add("query", $sql);
	$r = $appSession->getTier()->exec($sql);
	echo "OK";
}else if($ac == "user_item")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$sql = "SELECT d1.id, d2.code, d2.name, d2.user_name, d2.avatar, d2.phone, d2.email, d1.group_id FROM res_user_company d1 LEFT OUTER JOIN res_user d2 ON(d1.user_id = d2.id) WHERE d1.status =0 AND d2.status =0 AND d1.id='".$id."'";
	$msg = $appSession->getTier()->createMessage();
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
}else if($ac == "user_update")
{
	$company_id = "";
	if(isset($_REQUEST['company_id']))
	{
		$company_id = $_REQUEST['company_id'];
	}
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$employee_user_id = "";
	if(isset($_REQUEST['employee_user_id']))
	{
		$employee_user_id = $_REQUEST['employee_user_id'];
	}
	$employee_id = "";
	if(isset($_REQUEST['employee_id']))
	{
		$employee_id = $_REQUEST['employee_id'];
	}
	$company_user_id = "";
	if(isset($_REQUEST['company_user_id']))
	{
		$company_user_id = $_REQUEST['company_user_id'];
	}
	$code = "";
	if(isset($_REQUEST['code']))
	{
		$code = $_REQUEST['code'];
	}
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	
	$phone = "";
	if(isset($_REQUEST['phone']))
	{
		$phone = $_REQUEST['phone'];
	}
	$email = "";
	if(isset($_REQUEST['email']))
	{
		$email = $_REQUEST['email'];
	}
	
	$user_name = "";
	if(isset($_REQUEST['user_name']))
	{
		$user_name = $_REQUEST['user_name'];
	}
	$password = "";
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	$group_id = "";
	if(isset($_REQUEST['group_id']))
	{
		$group_id = $_REQUEST['group_id'];
	}
	
	$sql = "SELECT d2.user_name, d2.phone, d2.email  FROM res_user_company d1 LEFT OUTER JOIN res_user d2 ON(d1.user_id = d2.id) WHERE d1.id !='".$id."' AND (d2.user_name='".str_replace("'", "''", $user_name)."'";
	if($email != "")
	{
		$sql = $sql. " OR d2.email = '".str_replace("'", "''", $email)."'";
	}
	if($phone != "")
	{
		$sql = $sql. " OR d2.phone = '".str_replace("'", "''", $phone)."'";
	}
	$sql = $sql.") AND (d2.status =0 OR d2.status =2)";
	$msg = $appSession->getTier()->createMessage();
	$msg->add("query", $sql);
	$values = $appSession->getTier()->getArray($msg);
	if(count($values)>0)
	{
		if($values[0][0] == $user_name)
		{
			echo "USER_AVAIBLE";
		}else if($phone != "" && $values[0][1] == $phone)
		{
			echo "USER_PHONE";
		}else if($email != "" && $values[0][2] == $email)
		{
			echo "USER_EMAIL";
		}
		
		exit();
	}
	$sql = "SELECT user_id FROM res_user_company WHERE id='".$id."'";
	$msg->add("query", $sql);

	$user_user_id = $appSession->getTier()->getValue($msg);
	if($user_user_id == "")
	{
		$id = $appSession->getTool()->getId();
		$user_user_id =$appSession->getTool()->getId(); 
		$sql = "INSERT INTO res_user_company(";
		$sql = $sql."id";
		$sql = $sql.", user_id";
		$sql = $sql.", group_id";
		$sql = $sql.", company_id";
		$sql = $sql.", status";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", create_uid";
		$sql = $sql.", write_uid";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$id."'";
		$sql = $sql.", '".$user_user_id."'";
		$sql = $sql.", '".str_replace("'", "''", $group_id)."'";
		$sql = $sql.", '".$company_id."'";
		$sql = $sql.", 0";
		$sql = $sql.", ".$appSession->getTier()->getDateString();
		$sql = $sql.", ".$appSession->getTier()->getDateString();
		$sql = $sql.", '".$user_id."'";
		$sql = $sql.", '".$user_id."'";
		$sql = $sql.")";
		$msg->add("query", $sql);
		
		$r = $appSession->getTier()->exec($msg);
	}else{
		$sql = "UPDATE res_user_company SET status =0, write_date=".$appSession->getTier()->getDateString();
		$sql = $sql.", group_id='".str_replace("'", "''", $group_id)."'";
		$sql = $sql." WHERE id='".$id."'";
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
	}
	
	$sql = "SELECT id FROM res_user WHERE id='".$user_user_id."'";
	$msg->add("query", $sql);
	$check_user_id = $appSession->getTier()->getValue($msg);
	if($check_user_id == "")
	{
		
		$s = $appSession->getTool()->toHash("sha256", "[".$user_id."]".$password);
		$len = $appSession->getTool()->lenght($password);
		for($i = 0; $i<$len; $i++)
		{
			$s = $s.chr($i + 48);
		}
		$password = $appSession->getTool()->toHash("md5", $s);
			
		$sql = "INSERT INTO res_user(";
		$sql = $sql."id";
		$sql = $sql.", company_id";
		$sql = $sql.", status";
		$sql = $sql.", name";
		$sql = $sql.", user_name";
		$sql = $sql.", password";
		$sql = $sql.", email";
		$sql = $sql.", phone";
		$sql = $sql.", thousands_sep";
		$sql = $sql.", decimal_point";
		$sql = $sql.", date_format";
		$sql = $sql.", create_date";
		$sql = $sql.", write_date";
		$sql = $sql.", create_uid";
		$sql = $sql.", write_uid";
		$sql = $sql." )VALUES(";
		$sql = $sql."'".$user_user_id."'";
		$sql = $sql.", '".$company_id."'";
		$sql = $sql.", 0";
		$sql = $sql.", '".str_replace("'", "''", $name)."'";
		$sql = $sql.", '".str_replace("'", "''", $user_name)."'";
		$sql = $sql.", '".str_replace("'", "''", $password)."'";
		$sql = $sql.", '".str_replace("'", "''", $email)."'";
		$sql = $sql.", '".str_replace("'", "''", $phone)."'";
		$sql = $sql.", ','";
		$sql = $sql.", '.'";
		$sql = $sql.", 'YYYY-MM-DD'";
		$sql = $sql.", ".$appSession->getTier()->getDateString();
		$sql = $sql.", ".$appSession->getTier()->getDateString();
		$sql = $sql.", '".$company_user_id."'";
		$sql = $sql.", '".$company_user_id."'";
		$sql = $sql.")";
		
		$msg->add("query", $sql);
		$result = $appSession->getTier()->exec($msg);
		
	}else{
		if($password != "")
		{
			$s = $appSession->getTool()->toHash("sha256", "[".$user_user_id."]".$password);
			$len = $appSession->getTool()->lenght($password);
			for($i = 0; $i<$len; $i++)
			{
				$s = $s.chr($i + 48);
			}
			$password = $appSession->getTool()->toHash("md5", $s);
			$sql = "UPDATE res_user SET write_date=".$appSession->getTier()->getDateString();
			$sql = $sql.", password='".str_replace("'", "''", $password)."'";
			$sql = $sql.", user_name='".str_replace("'", "''", $user_name)."'";
			$sql = $sql." WHERE id='".$user_user_id."'";
			$msg->add("query", $sql);
			
			$r = $appSession->getTier()->exec($msg);
		}
	
	}
	echo $id;
}else if($ac == "remove_account")
{
	$user_id = "";
	if(isset($_REQUEST['user_id']))
	{
		$user_id = $_REQUEST['user_id'];
	}
	$sql = "SELECT d1.rel_id, d2.partner_id, d1.id FROM res_user_company d1 LEFT OUTER JOIN customer d2 ON(d1.rel_id = d2.id) WHERE d1.user_id='".$user_user_id."'";
	
	$msg->add("query", $sql);
	$arr = $appSession->getTier()->getArray($msg);
	if(count($arr)>0){
		if($arr[0][0] != ""){
			$sql = "UPDATE customer SET write_date=".$appSession->getTier()->getDateString();
			$sql = $sql.", status=1";
			$sql = $sql." WHERE id='".$arr[0][0]."'";
			$msg->add("query", $sql);
			$r = $appSession->getTier()->exec($msg);
		}
		if($arr[0][1] != ""){
			$sql = "UPDATE res_partner SET write_date=".$appSession->getTier()->getDateString();
			$sql = $sql.", status=1";
			$sql = $sql." WHERE id='".$arr[0][1]."'";
			$msg->add("query", $sql);
			$r = $appSession->getTier()->exec($msg);
		}
		if($arr[0][2] != ""){
			$sql = "UPDATE res_user_company SET write_date=".$appSession->getTier()->getDateString();
			$sql = $sql.", status=1";
			$sql = $sql." WHERE id='".$arr[0][2]."'";
			$msg->add("query", $sql);
			$r = $appSession->getTier()->exec($msg);
		}
		
	}
	
	
	$sql = "UPDATE res_user SET write_date=".$appSession->getTier()->getDateString();
	$sql = $sql.", status=1";
	
	$sql = $sql." WHERE id='".$user_id."'";
	$msg->add("query", $sql);
	$r = $appSession->getTier()->exec($msg);
	echo "OK";
}else if($ac == "CITY")
{
	
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id != "")
	{
		$appSession->getConfig()->setProperty("lang_id", $lang_id);
	}
	
	$sql = "SELECT d1.id, d1.name, lg.description AS name_lg";
	$sql = $sql." FROM res_address d1";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$lang_id."' AND lg.rel_id = d1.id AND lg.name='name' AND lg.status =0)";
	$sql = $sql." WHERE d1.type='CITY' AND d1.status =0";
	$sql = $sql." ORDER BY lg.description ASC, d1.name ASC";
	$msg->add("query", $sql);
	
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
	
}else if($ac == "ADDRESSPARENT")
{
	$lang_id = "";
	if(isset($_REQUEST['lang_id']))
	{
		$lang_id = $_REQUEST['lang_id'];
	}
	if($lang_id != "")
	{
		$appSession->getConfig()->setProperty("lang_id", $lang_id);
	}
	$parent_id = "";
	if(isset($_REQUEST['parent_id']))
	{
		$parent_id = $_REQUEST['parent_id'];
	}
	
	$sql = "SELECT d1.id, d1.name, lg.description AS name_lg";
	$sql = $sql." FROM res_address d1";
	$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$lang_id."' AND lg.rel_id = d1.id AND lg.name='name' AND lg.status =0)";
	$sql = $sql." WHERE d1.status =0 AND d1.parent_id='".$parent_id."'";
	$sql = $sql." ORDER BY lg.description ASC, d1.name ASC";
	$msg->add("query", $sql);
	$dt = $appSession->getTier()->getTable($msg);
	echo $appSession->getTool()->respTable($dt);
	
}

?>