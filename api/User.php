<?php
class User
{
	function listCompany($appSession, $id)
	{
		$s = [];
		$sql = "SELECT id, parent_id FROM res_company WHERE status =0 AND parent_id='".$id."'";
		$msg = $appSession->getTier()->createMessage();
		$msg->add("query", $sql);
		$result = $appSession->getTier()->getArray($msg);
		for($i=0; $i<count($result); $i++)
		{
			
			$s[count($s)] = [$result[$i][0], $result[$i][1]] ;
			$s1 = $this->listCompany($appSession, $result[$i][0]);
			for($j =0; $j<count($s1); $j++)
			{
				$s[count($s)] = $s1[$j];
			}
		}
		return $s;
	}
	function findChildCompany($companyList, $parent_id)
	{
		$s = "";
		for($j =0; $j<count($companyList); $j++)
		{
			if($companyList[$j][1] == $parent_id)
			{
				if($s != "")
				{
					$s = $s.",";
				}
				$s = $s.$companyList[$j][0];
				$s1 = $this->findChildCompany($companyList, $companyList[$j][0]);
				if($s1 != "")
				{
					if($s != "")
					{
						$s = $s.",";
					}
					$s = $s.$s1;
				}
			}
			
		}
		return $s;
	}
	public function create($appSession, $name, $commercial_name, $email, $phone, $address_id, $address, $contact_name, $contact_mobile, $contact_email, $user_name, $password)
	{
		$message = "";
		
		$sql = "SELECT id, phone, email, user_name, status FROM res_user WHERE (user_name='".str_replace("'", "''", $user_name)."'";
		if($email != "")
		{
			$sql = $sql. " OR email = '".str_replace("'", "''", $email)."'";
		}
		if($phone != "")
		{
			$sql = $sql. " OR phone = '".str_replace("'", "''", $phone)."'";
		}
		$sql = $sql.") AND (status =0 OR status =2)";
		
		$msg = $appSession->getTier()->createMessage();
		$msg->add("query", $sql);
		$result = $appSession->getTier()->getTable($msg);
		$numrows = $result->getRowCount();
		
		if($numrows>0)
		{
			$row = $result->getRow(0);
			if($row->getString("status") == "2")
			{
				$message = $row->getString("id");
				$sql = "UPDATE INTO res_user SET email='".str_replace("'", "''", $email)."'";
				$sql = $sql.", phone='".str_replace("'", "''", $phone)."'";
				$sql = $sql.", user_name='".str_replace("'", "''", $user_name)."'";
				$sql = $sql.", name='".str_replace("'", "''", $name)."'";
				$sql = $sql." WHERE id='".$message."'";
				$msg->add("query", $sql);
				$result = $appSession->getTier()->exec($msg);
				
		
			}
			else if($row->getString("user_name") == $user_name)
			{
				$message = "User is avaible";
			}else if($email != "" && $row->getString("email") == $email)
			{
				$message = "Email is avaible";
			}
			else if($phone != "" && $row->getString("phone") == $phone)
			{
				$message = "Phone is avaible";
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
			$sql = $sql.")";
			$msg->add("query", $sql);
			$result = $appSession->getTier()->exec($msg);
			
			
			$sql = "INSERT INTO res_company(";
			$sql = $sql."id";
			$sql = $sql.", company_id";
			$sql = $sql.", status";
			$sql = $sql.", name";
			$sql = $sql.", commercial_name";
			$sql = $sql.", email";
			$sql = $sql.", phone";
			$sql = $sql.", address_id";
			$sql = $sql.", address";
			$sql = $sql.", contact_name";
			$sql = $sql.", contact_mobile";
			$sql = $sql.", contact_email";
			$sql = $sql.", parent_id";
			$sql = $sql.", create_date";
			$sql = $sql.", write_date";
			$sql = $sql.", create_uid";
			$sql = $sql.", write_uid";
			$sql = $sql." )VALUES(";
			$sql = $sql."'".$company_id."'";
			$sql = $sql.", '".$company_id."'";
			$sql = $sql.", 0";
			$sql = $sql.", '".str_replace("'", "''", $name)."'";
			$sql = $sql.", '".str_replace("'", "''", $commercial_name)."'";
			$sql = $sql.", '".str_replace("'", "''", $email)."'";
			$sql = $sql.", '".str_replace("'", "''", $phone)."'";
			$sql = $sql.", '".str_replace("'", "''", $address_id)."'";
			$sql = $sql.", '".str_replace("'", "''", $address)."'";
			$sql = $sql.", '".str_replace("'", "''", $contact_name)."'";
			$sql = $sql.", '".str_replace("'", "''", $contact_mobile)."'";
			$sql = $sql.", '".str_replace("'", "''", $contact_email)."'";
			$sql = $sql.", 'bf65de00-9204-40b6-f389-d5606548ae3a'";
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
			$sql = $sql.", group_id";
			$sql = $sql.", create_date";
			$sql = $sql.", write_date";
			$sql = $sql.", create_uid";
			$sql = $sql.", write_uid";
			$sql = $sql." )VALUES(";
			$sql = $sql."'".$user_id."'";
			$sql = $sql.", '".$company_id."'";
			$sql = $sql.", 0";
			$sql = $sql.", '".$user_id."'";
			$sql = $sql.", 'ADMIN'";
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
	public function login($appSession, $user, $pass, $rel_user_id)
	{
		$message = "";
		$msg = $appSession->getTier()->createMessage();
		
		$sql = "SELECT d1.id, d1.password, d1.user_name, d1.name, d1.company_id, d1.date_format, d1.thousands_sep, d1.time_format, d1.decimal_point, d1.avatar, d1.lang_id, d2.group_id AS user_group_id, d4.name AS user_group_name, d5.parent_id AS parent_company_id  FROM res_user d1 LEFT OUTER JOIN res_user_company d2 ON(d1.id = d2.user_id AND d2.status =0) LEFT OUTER JOIN res_user_group d4 ON(d2.group_id = d4.id) LEFT OUTER JOIN res_company d5 ON(d2.company_id = d5.id) WHERE (d1.user_name='".$user."' OR d1.email='".$user."' OR d1.phone='".$user."') AND d1.status =0 AND d2.status=0";
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
				$group_id = "";
				$root_company_id = $company_id;
				
				
				$p_company_ids = $company_id;
				$pid = $company_id;
				while(true)
				{
					$sql = "SELECT parent_id FROM res_company WHERE status =0 AND id='".$pid."'";
					$msg->add("query", $sql);
					$result1 = $appSession->getTier()->getArray($msg);
					$numrows1 = count($result1);	
					if($numrows1>0)
					{
						$row1 = $result1[0];
						$pid = $row1[0];
						if($pid == "" || $pid == "ROOT")
						{
							break;
						}
						$root_company_id = $pid;
						$p_company_ids = $p_company_ids.",".$pid;
					}else
					{
						break;
					}
				}
				
				if($root_company_id == "")
				{
					$root_company_id = $company_id;
				}
				
				
				$companyList = $this->listCompany($appSession, $root_company_id);
				$companyList[count($companyList)]= [$root_company_id, ""];
				
				$company_ids = "";
				for($j =0; $j<count($companyList); $j++)
				{
					if($company_ids != "")
					{
						$company_ids = $company_ids.",";
					}
					$company_ids = $company_ids.$companyList[$j][0];
				}
				
				$c_company_ids = $this->findChildCompany($companyList, $company_id);
				
				$message = $message."user_id=".$user_id.";";
				$message = $message."user_name=".$row->getString("user_name").";";
				$message = $message."account_name=".$row->getString("name").";";
				$message = $message."root_company_id=".$root_company_id.";";
				$message = $message."company_id=".$company_id.";";
				$message = $message."company_ids=".$company_ids.";";
				$message = $message."parent_company=".$p_company_ids.";";
				$message = $message."parent_company_id=".$row->getString("parent_company_id").";";
				$message = $message."child_company=".$c_company_ids.";";
				
				$message = $message."user_group_id=".$row->getString("user_group_id").";";
				$message = $message."user_group_name=".$row->getString("user_group_name").";";
				$message = $message."date_format=".$row->getString("date_format").";";
				
				$message = $message."thousands_sep=".$row->getString("thousands_sep").";";
				$message = $message."time_format=".$row->getString("time_format").";";
				
				$message = $message."decimal_point=".$row->getString("decimal_point").";";
	
				
			}else{
				$message = $message.'INCORRECT';
			}
			
		}else{
			$message = $message.'INVALID';
		}
		return $message;
	}
	public function loginById($appSession, $user_id)
	{
		$message = "";
		$msg = $appSession->getTier()->createMessage();
		
		$sql = "SELECT d1.id, d1.password, d1.user_name, d1.name, d1.company_id, d1.date_format, d1.thousands_sep, d1.time_format, d1.decimal_point, d1.avatar, d1.lang_id, d2.group_id AS user_group_id, d4.name AS user_group_name, d5.parent_id AS parent_company_id  FROM res_user d1 LEFT OUTER JOIN res_user_company d2 ON(d1.id = d2.user_id AND d2.status =0) LEFT OUTER JOIN res_user_group d4 ON(d2.group_id = d4.id) LEFT OUTER JOIN res_company d5 ON(d2.company_id = d5.id) WHERE d1.id='".$user_id."' AND d1.status =0 AND d2.status=0";
		$msg->add("query", $sql);
		
		$result = $appSession->getTier()->getTable($msg);
		$numrows = $result->getRowCount();
		
		if($numrows>0)
		{
			$row = $result->getRow(0);
			$user_id = $row->getString("id");
			$company_id = $row->getString("company_id");
			$group_id = "";
			$root_company_id = $company_id;
			
			
			$p_company_ids = $company_id;
			$pid = $company_id;
			while(true)
			{
				$sql = "SELECT parent_id FROM res_company WHERE status =0 AND id='".$pid."'";
				$msg->add("query", $sql);
				$result1 = $appSession->getTier()->getArray($msg);
				$numrows1 = count($result1);	
				if($numrows1>0)
				{
					$row1 = $result1[0];
					$pid = $row1[0];
					if($pid == "" || $pid == "ROOT")
					{
						break;
					}
					$root_company_id = $pid;
					$p_company_ids = $p_company_ids.",".$pid;
				}else
				{
					break;
				}
			}
			
			if($root_company_id == "")
			{
				$root_company_id = $company_id;
			}
			
			
			$companyList = $this->listCompany($appSession, $root_company_id);
			$companyList[count($companyList)]= [$root_company_id, ""];
			
			$company_ids = "";
			for($j =0; $j<count($companyList); $j++)
			{
				if($company_ids != "")
				{
					$company_ids = $company_ids.",";
				}
				$company_ids = $company_ids.$companyList[$j][0];
			}
			
			$c_company_ids = $this->findChildCompany($companyList, $company_id);
			
			$message = $message."user_id=".$user_id.";";
			$message = $message."user_code=".$row->getString("user_name").";";
			$message = $message."user_name=".$row->getString("name").";";
			$message = $message."root_company_id=".$root_company_id.";";
			$message = $message."company_id=".$company_id.";";
			$message = $message."company_ids=".$company_ids.";";
			$message = $message."parent_company=".$p_company_ids.";";
			$message = $message."parent_company_id=".$row->getString("parent_company_id").";";
			$message = $message."child_company=".$c_company_ids.";";
			
			$message = $message."user_group_id=".$row->getString("user_group_id").";";
			$message = $message."user_group_name=".$row->getString("user_group_name").";";
			$message = $message."date_format=".$row->getString("date_format").";";
			
			$message = $message."thousands_sep=".$row->getString("thousands_sep").";";
			$message = $message."time_format=".$row->getString("time_format").";";
			
			$message = $message."decimal_point=".$row->getString("decimal_point").";";
		
			$message = $message."avatar=".$row->getString("avatar").";";
			
			
		}else{
			$message = $message.'INVALID';
		}
		return $message;
	}
	
}

?>