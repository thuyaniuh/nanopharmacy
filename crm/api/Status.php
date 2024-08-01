<?php
require_once(ABSPATH.'api/WebService.php' );
class Status
{
	public $appSession;
	public $msg;
	public function __construct($appSession) {
		$this->appSession = $appSession;
		$this->msg = $appSession->getTier()->createMessage();
	}
	public function parseContent($dt, $s)
	{
		if($dt->getRowCount()>0)
		{
			for($i=0; $i<count($dt->getColumns()); $i++)
			{
				$s = $this->appSession->getTool()->replace($s, "{".$dt->getColumns()[$i]->getName()."}", $dt->getStringAt(0, $i));
			}
		}
		
		return $s;
	}
	public function sendMessage($data)
	{
		if($this->appSession->getConfig()->getProperty("service_url") != "")
		{
			$appSession->getTool()->httpPost($this->appSession->getConfig()->getProperty("service_url"), $data);
		}
	}
	public function doStatus($id, $table_id, $status_id, $company_id)
	{
		$line_id = "";
		$sql = "SELECT d1.id, d1.name, d1.query, d1.description FROM res_status d1 WHERE d1.status = 0 AND d1.table_id='".$table_id."'";
		if($status_id == "")
		{
			$sql = $sql." AND (d1.sequence=0 OR d1.sequence=1)";
		}else{
			$sql = $sql." AND d1.id='".$status_id."'";
		}
		$this->msg->add("query", $sql);
		$arr = $this->appSession->getTier()->getArray($this->msg);
		if(count($arr)>0)
		{
			$status_id = $arr[0][0];
			$status_name = $arr[0][1];
			$query = $arr[0][2];
			$status_description = $arr[0][3];
			$dt = NULL;
			if($query != ""){
				$sql = $this->appSession->getTool()->replace($query, "{id}", $id);
				$this->msg->add("query", $sql);
				$dt = $this->appSession->getTier()->getTable($this->msg);
				if($dt->getRowCount()>0)
				{
					$status_description = $this->parseContent($dt, $status_description);
				}
				
				
			}
			$sql = "UPDATE res_status_line SET status =2, write_date=NOW() WHERE rel_id='".$id."' AND status =0";
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
			if( $company_id == "")
			{
				 $company_id = "e741709c-6309-4704-a6dc-e339a8b4bf7f";
			}
			$builder = $this->appSession->getTier()->createBuilder("res_status_line");
			$line_id = $this->appSession->getTool()->getId();
			$builder->add("id", $line_id);
			$builder->add("create_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("write_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("name", $status_name);
			$builder->add("status_id", $status_id);
			$builder->add("description", $status_description);
			$builder->add("rel_id", $id);
			$builder->add("status", 0);
			$builder->add("company_id", $company_id);
			$sql = $this->appSession->getTier()->getInsert($builder);
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
			
			
					
			
			$sql = "SELECT d1.rel_id, d1.name, d1.type, d1.description FROM res_status_notification d1 WHERE d1.status_id='".$status_id."' AND d1.status=0";
			
			$this->msg->add("query", $sql);
			$notifications = $this->appSession->getTier()->getArray($this->msg);
			for($i =0; $i<count($notifications); $i++)
			{
				$rel_id = $notifications[$i][0];
				$name = $notifications[$i][1];
				$type = $notifications[$i][2];
				$description = $notifications[$i][3];
				if($dt != NULL){
					$rel_id = $this->parseContent($dt, $rel_id);
					$name = $this->parseContent($dt, $name);
					$description = $this->parseContent($dt, $description);
					$builder->setName("res_notification");
					$builder->clear();
					$builder->add("id", $this->appSession->getTool()->getId());
					$builder->add("create_uid", $this->appSession->getUserInfo()->getId());
					$builder->add("write_uid", $this->appSession->getUserInfo()->getId());
					$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
					$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
					$builder->add("name", $name);
					$builder->add("rel_id", $rel_id);
					$builder->add("notification_id", $id);
					$builder->add("description", $description);
					$builder->add("type", $type);
					$builder->add("seen", 0);
					$builder->add("status", 0);
					$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
					//$sql = $this->appSession->getTier()->getInsert($builder);
					//$this->msg->add("query", $sql);
					//$this->appSession->getTier()->exec($this->msg);
					
				}
			}
		}
		return $line_id;
	}
	
}

?>