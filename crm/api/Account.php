<?php

class Account
{
	public $appSession;
	public $msg;
	public function __construct($appSession) {
		$this->appSession = $appSession;
		$this->msg = $appSession->getTier()->createMessage();
	}
	public function employeeToPartner($employee_id)
	{
		$partner_id = "";
		$sql = "SELECT d1.partner_id, d1.code, d1.first_name, d1.middle_name, d1.last_name, d1.address FROM hr_employee d1 WHERE d1.id='".$employee_id."'";
		
		$this->msg->add("query", $sql);
		
		$arr = $this->appSession->getTier()->getArray($this->msg);
		if(count($arr)>0)
		{
			$partner_id = $arr[0][0];
			$builder = $this->appSession->getTier()->createBuilder("res_partner");
			if($partner_id == "")
			{
				$code = $arr[0][1];
				$name =  $arr[0][2]." ". $arr[0][2]. " ". $arr[0][4];
				$address =  $arr[0][5];
				$partner_id = $this->appSession->getTool()->getId();
				$builder->add("id", $partner_id);
				$builder->add("create_uid", $user_id);
				$builder->add("write_uid", $user_id);
				$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
				$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
				$builder->add("status", 0);
				$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
				$builder->add("code", $code);
				$builder->add("name", $name);
				$builder->add("address", $address);
				$sql = $this->appSession->getTier()->getInsert($builder);
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
				$sql = "UPDATE hr_employee SET partner_id='".$partner_id."', write_date=".$this->appSession->getTier()->getDateString()." WHERE id='".$employee_id."'";
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
			}
			
		}
		
		return $partner_id;
	}
	public function customerToPartner($customer_id)
	{
		$partner_id = "";
		$sql = "SELECT d1.partner_id, d1.code, d1.name, d1.phone, d1.vat, d1.address FROM customer d1 WHERE d1.id='".$customer_id."'";
		
		$this->msg->add("query", $sql);
		
		$arr = $this->appSession->getTier()->getArray($this->msg);
		if(count($arr)>0)
		{
			$partner_id = $arr[0][0];
			$builder = $this->appSession->getTier()->createBuilder("res_partner");
			if($partner_id == "")
			{
				$code = $arr[0][1];
				$name =  $arr[0][2];
				$phone = $arr[0][2];
				$vat = $arr[0][4];
				$address =  $arr[0][5];
				$partner_id = $this->appSession->getTool()->getId();
				$builder->add("id", $partner_id);
				$builder->add("create_uid", $this->appSession->getConfig()->getProperty("user_id"));
				$builder->add("write_uid", $this->appSession->getConfig()->getProperty("user_id"));
				$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
				$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
				$builder->add("status", 0);
				$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
				$builder->add("code", $code);
				$builder->add("name", $name);
				$builder->add("phone", $phone);
				$builder->add("vat", $vat);
				$builder->add("address", $address);
				$sql = $this->appSession->getTier()->getInsert($builder);
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
				$sql = "UPDATE customer SET partner_id='".$partner_id."', write_date=".$this->appSession->getTier()->getDateString()." WHERE id='".$customer_id."'";
				$this->msg->add("query", $sql);
				$this->appSession->getTier()->exec($this->msg);
			}
			
		}
		
		return $partner_id;
	}
	public function createInvoice($rel_id, $type, $partner_id, $category_id, $status_id, $payment_term_id, $currency_id, $amount, $receipt_no, $receipt_date, $origin_no, $origin_date, $description)
	{
		$sql = "";
		if($receipt_no == "")
		{
			$category_code = "";
			$sql = "SELECT code FROM account_invoice_category WHERE id='".$category_id."'";
			$this->msg->add("query", $sql);
			$category_code = $this->appSession->getTier()->getValue($this->msg);
				
		

			 $receipt_type = date("y");
			 $m = date("m");
		   
			 $receipt_no = $this->appSession->getTool()->findReceiptNo($this->appSession->getTier(), $this->appSession->getUserInfo()->getCompanyId(), "account_invoice.".$receipt_type.".".$category_id);
			$receipt_no = $this->appSession->getTool()->padLeft($receipt_no, 4, "0");
			$receipt_no = $category_code."-".$receipt_type.".".$receipt_no;
		}
		$invoice_id = $this->appSession->getTool()->getId();
		$builder = $this->appSession->getTier()->createBuilder("account_invoice");
		$builder->add("id", $invoice_id);
		$builder->add("create_uid", $this->appSession->getUserInfo()->getId());
		$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
		$builder->add("write_uid", $this->appSession->getUserInfo()->getId());
		$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
		$builder->add("company_id", $this->appSession->getUserInfo()->getCompanyId());

		$builder->add("status", 0);
		$builder->add("partner_id", $partner_id);
		$builder->add("category_id", $category_id);
		$builder->add("status_id", $status_id);
		$builder->add("currency_id", $currency_id);
		$builder->add("payment_term_id", $payment_term_id);
		$builder->add("amount",$amount);
		$builder->add("receipt_no",$receipt_no);
		if($receipt_date == "")
		{
			$builder->add("receipt_date", $this->appSession->getTier()->getDateString(), 'f');
		}else{
			$builder->add("receipt_date", $receipt_date);
		}
		
		$builder->add("origin_no", $origin_no);
		if($origin_date != "")
		{
			$builder->add("origin_date", $origin_date);
		}
		
		$builder->add("description", $description);
		$builder->add("rel_id", $rel_id);
		$builder->add("type", $type);
		
		$sql = $this->appSession->getTier()->getInsert($builder);
		
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
		return $invoice_id;
		
	}
	public function addPaymentLocal($rel_id, $payment_id, $currency_id, $amount, $description)
	{
		$sql = "SELECT id FROM account_payment_line_local WHERE id='".$payment_id."'";
		$this->msg->add("query", $sql);
		$payment_line_id = $this->appSession->getTier()->getValue($this->msg);
		if($payment_line_id == "")
		{
			$receipt_no = $this->appSession->getTool()->findReceiptNo($this->appSession->getTier(), $this->appSession->getUserInfo()->getCompanyId(), "account_payment_line");
			$receipt_no = $this->appSession->getTool()->padLeft($receipt_no, 4, "0");
				
			$payment_line_id = $this->appSession->getTool()->getId();
			$builder = $this->appSession->getTier()->createBuilder("account_payment_line_local");
			$builder->add("id", $payment_line_id);
			$builder->add("create_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("write_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("company_id", $this->appSession->getUserInfo()->getCompanyId());
			
			$builder->add("status", 0);
			$builder->add("receipt_no", $receipt_no);
			$builder->add("payment_id", $payment_id);
			$builder->add("currency_id", $currency_id);
			$builder->add("amount",$amount);
			$builder->add("receipt_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("description", $description);
			$builder->add("rel_id", $rel_id);
			$builder->add("line_id", $rel_id);
			$sql = $this->appSession->getTier()->getInsert($builder);
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}else{
			$sql = "UPDATE account_payment_line_local SET amount=".$amount.", write_date=NOW(), status =0 WHERE id='".$payment_line_id."'";
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		
		
		return $payment_line_id;
	}
}

?>