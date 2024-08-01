<?php
require_once(ABSPATH.'api/Status.php' );
require_once(ABSPATH.'api/WebService.php' );
class Wallet
{
	public $appSession;
	public $msg;
	public function __construct($appSession) {
		$this->appSession = $appSession;
		$this->msg = $appSession->getTier()->createMessage();
	}
	public function add($wallet_id, $customer_id, $holder_id, $amount, $receipt_no, $description)
	{
		$sql= "SELECT id FROM wallet_holder WHERE rel_id ='".$customer_id."' AND status =0";
		$this->msg->add("query", $sql);
		$dt = $this->appSession->getTier()->getTable($this->msg);
		
		if($holder_id  == "" && $dt->getRowCount() >0)
		{
			$holder_id = $dt->getString(0, "id");
		}
		$builder = $this->appSession->getTier()->createBuilder("wallet");
		$builder->add("id", $wallet_id);
		$builder->add("holder_id", $holder_id);
		$builder->add("create_uid", $this->appSession->getUserInfo()->getId());
		$builder->add("write_uid", $this->appSession->getUserInfo()->getId());
		$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
		$builder->add("receipt_date", $this->appSession->getTier()->getDateString(), 'f');
		$builder->add("category_id", "eaf4f9aa-42de-4d19-b957-b3d24580d39d");
		$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
		$builder->add("status", 0);
		$builder->add("customer_id", $customer_id);
		$builder->add("currency_id", "23");
		$builder->add("receipt_no", $receipt_no);
		$builder->add("receipt_date", $this->appSession->getTier()->getDateString(), 'f');
		$builder->add("description", $description);
		$builder->add("factor", 1);
		$builder->add("amount", $amount);
		$builder->add("payment_id", "fda0dc20-341e-4ac2-cfad-888dd77ee9d1");
		$sql = $this->appSession->getTier()->getInsert($builder);
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
	}
	
	
}

?>