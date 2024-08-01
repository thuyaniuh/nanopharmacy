<?php
class Customer
{

	public function create($appSession, $name, $phone, $email , $user_name, $password, $lang_id, $address)
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
				$message = $phone."PHONE_AVAIBLE";
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
			$company_id = $appSession->getConfig()->getProperty("company_id");
			
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
			$sql = $sql." )VALUES(";
			$sql = $sql."'".$user_id."'";
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
			$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
			$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.", '".$lang_id."'";
			$sql = $sql.")";
			$msg->add("query", $sql);
			$result = $appSession->getTier()->exec($msg);
			
			
			$customer_id = $user_id;
			
			$code = $appSession->getTool()->findReceiptNo($appSession->getTier(), $company_id, "customer.code");
			$code = $appSession->getTool()->paddingLeft($code, "0", 4);
			
			$barcode = $appSession->getTool()->findReceiptNo($appSession->getTier(), $company_id, "customer.barcode");
			$barcode = $appSession->getTool()->paddingLeft($barcode, "0", 8);
			
			$sql = "INSERT INTO customer(";
			$sql = $sql."id";
			$sql = $sql.", code";
			$sql = $sql.", barcode";
			$sql = $sql.", join_date";
			$sql = $sql.", company_id";
			$sql = $sql.", status";
			$sql = $sql.", name";
			$sql = $sql.", email";
			$sql = $sql.", phone";
			$sql = $sql.", create_date";
			$sql = $sql.", write_date";
			$sql = $sql.", create_uid";
			$sql = $sql.", write_uid";
			$sql = $sql.", address";
			$sql = $sql." )VALUES(";
			$sql = $sql."'".$customer_id."'";
			$sql = $sql.", '".$code."'";
			$sql = $sql.", '".$barcode."'";
			$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
			$sql = $sql.", '".$company_id."'";
			$sql = $sql.", 0";
			$sql = $sql.", '".str_replace("'", "''", $name)."'";
			$sql = $sql.", '".str_replace("'", "''", $email)."'";
			$sql = $sql.", '".str_replace("'", "''", $phone)."'";
			$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
			$sql = $sql.", '".$appSession->getTier()->getDateString()."'";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.", '". $appSession->getTool()->replace($address, "'", "''")."'";
			$sql = $sql.")";
			
			$msg->add("query", $sql);
			$result = $appSession->getTier()->exec($msg);
			
			
			$id = $appSession->getTool()->getId();
			$sql = "INSERT INTO customer_user(";
			$sql = $sql."id";
			$sql = $sql.", company_id";
			$sql = $sql.", status";
			$sql = $sql.", user_id";
			$sql = $sql.", customer_id";
			$sql = $sql.", create_date";
			$sql = $sql.", write_date";
			$sql = $sql.", create_uid";
			$sql = $sql.", write_uid";
			$sql = $sql." )VALUES(";
			$sql = $sql."'".$user_id."'";
			$sql = $sql.", '".$company_id."'";
			$sql = $sql.", 0";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.", '".$customer_id."'";
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
		
		$sql = "SELECT d1.customer_id, d2.id, d2.password, d2.user_name, d2.name, d2.company_id, d2.date_format, d2.thousands_sep, d2.time_format, d2.decimal_point, d2.avatar, d2.lang_id, d3.code AS customer_code, d3.barcode AS customer_barcode FROM customer_user d1 LEFT OUTER JOIN res_user d2 ON(d1.user_id = d2.id) LEFT OUTER JOIN customer d3 ON(d1.customer_id = d3.id) WHERE d1.status =0 AND d2.status =0 AND (d2.user_name='".$user."' OR d2.email='".$user."' OR d2.phone='".$user."')";
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
				
				$company_id = $row->getString("company_id");
				
				$message = $message."user_id=".$user_id.";";
				$message = $message."name=".$row->getString("name").";";
				$message = $message."user_name=".$row->getString("user_name").";";
				$message = $message."company_id=".$company_id.";";
				$message = $message."date_format=".$row->getString("date_format").";";
				$message = $message."thousands_sep=".$row->getString("thousands_sep").";";
				$message = $message."time_format=".$row->getString("time_format").";";
				$message = $message."decimal_point=".$row->getString("decimal_point").";";
				$message = $message."avatar=".$row->getString("avatar").";";
				$message = $message."customer_code=".$row->getString("customer_code").";";
				$message = $message."customer_barcode=".$row->getString("customer_barcode").";";
				
				$message = $message."customer_id=".$row->getString("customer_id").";";
				
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
	
}

?>