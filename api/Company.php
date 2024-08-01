<?php
class Company
{

	public function create($appSession, $name, $commercial_name, $phone, $email , $address_id, $address, $contact_name, $contact_mobile, $contact_email, $user_name, $password, $lang_id)
	{
		$msg = $appSession->getTier()->createMessage();
		$message = "";
		
		$sql = "SELECT id, phone, email, user_name FROM res_user WHERE (user_name='".str_replace("'", "''", $user_name)."'";
		if($email != "")
		{
			$sql = $sql. " OR email = '".str_replace("'", "''", $email)."'";
		}
		if($phone != "")
		{
			$sql = $sql. " OR phone = '".str_replace("'", "''", $phone)."'";
		}
		$sql = $sql.")";
		$msg->add("query", $sql);
		
		$result = $appSession->getTier()->getTable($msg);
		$numrows = $result->getRowCount();
		
		if($numrows>0)
		{
			$row = $result->getRow(0);
			if($row->getString("user_name") == $user_name)
			{
				$message = "USER_AVAIBLE";
			}else if($row->getString("email") != "" && $row->getString("email") == $email)
			{
				$message = "EMAIL_AVAIBLE";
			}
			else if($row->getString("phone") != "" && $row->getString("phone") == $phone)
			{
				$message = "PHONE_AVAIBLE";
			}
		}else{
			$user_id = $appSession->getTool()->getId();
			$s = $appSession->getTool()->toHash("sha256", "[".$user_id."]".$password);
			$len = $appSession->getTool()->lenght($password);
			for($i = 0; $i<$len; $i++)
			{
				$s = $s.chr($i + 48);
			}
			$password = $appSession->getTool()->toHash("md5", $s);
			$company_id = $appSession->getTool()->getId();
			
			$parent_company_id = $appSession->getConfig()->getProperty("kiosk_company_id");
			
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
			$sql = $sql.", lang_id";
			$sql = $sql.", actived";
			$sql = $sql." )VALUES(";
			$sql = $sql."'".$user_id."'";
			$sql = $sql.", '".$company_id."'";
			$sql = $sql.", 2";
			$sql = $sql.", '".str_replace("'", "''", $name)."'";
			$sql = $sql.", '".str_replace("'", "''", $user_name)."'";
			$sql = $sql.", '".str_replace("'", "''", $password)."'";
			$sql = $sql.", '".str_replace("'", "''", $email)."'";
			$sql = $sql.", '".str_replace("'", "''", $phone)."'";
			$sql = $sql.", ','";
			$sql = $sql.", '.'";
			$sql = $sql.", 'YYYY-MM-DD'";
			$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
			$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.", '".$lang_id."'";
			$sql = $sql.", 1";
			$sql = $sql.")";
			$msg->add("query", $sql);
			$result = $appSession->getTier()->exec($msg);
		
			$code = $appSession->getTool()->findReceiptNo($appSession->getTier(), $company_id, "res_company.code");
			$code = $appSession->getTool()->paddingLeft($code, "0", 8);
			
			$sql = "INSERT INTO res_company(";
			$sql = $sql."id";
			$sql = $sql.", code";
			$sql = $sql.", company_id";
			$sql = $sql.", parent_id";
			$sql = $sql.", status";
			$sql = $sql.", name";
			$sql = $sql.", commercial_name";
			$sql = $sql.", phone";
			$sql = $sql.", email";
			$sql = $sql.", contact_name";
			$sql = $sql.", contact_mobile";
			$sql = $sql.", contact_email";
			$sql = $sql.", address_id";
			$sql = $sql.", address";
			$sql = $sql.", create_date";
			$sql = $sql.", write_date";
			$sql = $sql.", create_uid";
			$sql = $sql.", write_uid";
			$sql = $sql." )VALUES(";
			$sql = $sql."'".$company_id."'";
			$sql = $sql.", '".$code."'";
			$sql = $sql.", '".$company_id."'";
			$sql = $sql.", '".$parent_company_id."'";
			$sql = $sql.", 0";
			$sql = $sql.", '".str_replace("'", "''", $name)."'";
			$sql = $sql.", '".str_replace("'", "''", $commercial_name)."'";
			$sql = $sql.", '".str_replace("'", "''", $phone)."'";
			$sql = $sql.", '".str_replace("'", "''", $email)."'";
			$sql = $sql.", '".str_replace("'", "''", $contact_name)."'";
			$sql = $sql.", '".str_replace("'", "''", $contact_mobile)."'";
			$sql = $sql.", '".str_replace("'", "''", $contact_email)."'";
			$sql = $sql.", '".str_replace("'", "''", $address_id)."'";
			$sql = $sql.", '". $appSession->getTool()->replace($address, "'", "''")."'";
			$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
			$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.", '".$user_id."'";
			
			$sql = $sql.")";
			
			$msg->add("query", $sql);
			$result = $appSession->getTier()->exec($msg);
			
			$id = $appSession->getTool()->getId();
			$sql = "INSERT INTO res_user_company(";
			$sql = $sql."id";
			$sql = $sql.", company_id";
			$sql = $sql.", status";
			$sql = $sql.", user_id";
			$sql = $sql.", create_date";
			$sql = $sql.", write_date";
			$sql = $sql.", create_uid";
			$sql = $sql.", write_uid";
			$sql = $sql." )VALUES(";
			$sql = $sql."'".$user_id."'";
			$sql = $sql.", '".$company_id."'";
			$sql = $sql.", 0";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
			$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.")";
			$msg->add("query", $sql);
			$result = $appSession->getTier()->exec($msg);
			$message = $user_id;
		}
		return $message;
	}
	public function login($appSession, $user, $pass)
	{
		$message = "";
		$msg = $appSession->getTier()->createMessage();
		
		$sql = "SELECT d2.company_id, d2.id, d2.password, d2.user_name, d2.name, d2.date_format, d2.thousands_sep, d2.time_format, d2.decimal_point, d2.avatar, d2.lang_id, d3.code AS company_code, d3.name AS company_name, d3.commercial_name FROM res_user_company d1 LEFT OUTER JOIN res_user d2 ON(d1.user_id = d2.id) LEFT OUTER JOIN res_company d3 ON(d1.company_id = d3.id) WHERE d1.status =0 AND d2.status =0 AND (d2.user_name='".$user."' OR d2.email='".$user."' OR d2.phone='".$user."') AND d2.actived=1";
		$msg->add("query", $sql);
		$result = $appSession->getTier()->getTable($msg);
		$numrows = $result->getRowCount();
		
		if($numrows>0)
		{
			$row = $result->getRow(0);
			$user_id = $row->getString("id");
			$s = $appSession->getTool()->toHash("sha256", "[".$user_id."]".$pass);
			$len = strlen($pass);
			for($i = 0; $i<$len; $i++)
			{
				$s = $s.chr($i + 48);
			}
			$pass = $appSession->getTool()->toHash("md5", $s);
			
			
			if($pass == $row->getString("password"))
			{
				$message = $message."user_id=".$user_id.";";
				$message = $message."name=".$row->getString("name").";";
				$message = $message."user_name=".$row->getString("user_name").";";
				$message = $message."company_id=".$row->getString("company_id").";";
				$message = $message."date_format=".$row->getString("date_format").";";
				$message = $message."thousands_sep=".$row->getString("thousands_sep").";";
				$message = $message."time_format=".$row->getString("time_format").";";
				$message = $message."decimal_point=".$row->getString("decimal_point").";";
				$message = $message."avatar=".$row->getString("avatar").";";
				$message = $message."code=".$row->getString("customer_code").";";
				$message = $message."company_code=".$row->getString("company_code").";";
				$message = $message."company_name=".$row->getString("company_name").";";
				$message = $message."commercial_name=".$row->getString("commercial_name").";";
				
				$lang_id = $row->getString("lang_id");
				if($lang_id == "")
				{
					$lang_id = "vi";
				}
				$message = $message."lang_id=".$lang_id.";";
				
				
			}else{
				$message = 'INCORRECT';
			}
			
		}else{
			$message = 'INVALID';
		}
		return $message;
	}
	public function active($appSession, $user_id){
		$msg = $appSession->getTier()->createMessage();
		$sql = "UPDATE res_user SET status =0, write_date=NOW() WHERE id='".$user_id."'";
		$msg->add("query", $sql);
		$result = $appSession->getTier()->exec($msg);
		$sql = "UPDATE customer SET status =0, write_date=NOW() WHERE id='".$user_id."'";
		$msg->add("query", $sql);
		$result = $appSession->getTier()->exec($msg);
		return true;
	}
	
}

?>