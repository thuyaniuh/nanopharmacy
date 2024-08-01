<?php
require_once(ABSPATH.'api/RowLine.php' );

class RowLine
{
	public $childList = [];
	public $name = "";
	public $child_id = "";
	public $msg;
	public function __construct($name, $child_id) {
		$this->name = $name;
		$this->child_id = $child_id;
		
	}
	public function addChild($item)
	{
		$this->childList[count($this->childList)] = $item;
	}
	
	public function copy($appSession, $idValue, $deleted)
	{
		$this->msg = $appSession->getTier()->createMessage();
		$builder = $appSession->getTier()->createBuilder($this->name);
		$sql = "SELECT * FROM ".$this->name."_local WHERE ".$this->child_id."='".$idValue."' AND status != 1";
		
		$this->msg->add("query", $sql);
		$dt = $appSession->getTier()->getTable($this->msg);
		for ($i = 0; $i <$dt->getRowCount(); $i++)
		{
			$sql = "SELECT * FROM ".$this->name." WHERE id='".$dt->getString($i, "id")."'";
			
			$this->msg->add("query", $sql);
			$dt_copy = $appSession->getTier()->getTable($this->msg);
			
			
			if($dt_copy->getRowCount() == 0)
			{
				
				$builder->clear();
				for ($j = 0; $j < $dt_copy->getColumnCount(); $j++)
				{
					$column_name = $dt_copy->getColumnName($j);
					
					if ($dt->containColumn($column_name))
					{
						
						$builder->add($column_name, $dt->getString($i, $column_name), $dt_copy->getColumnType($j));
						if ($column_name == "write_date")
						{
							$builder->add($column_name, $appSession->getTier()->getDateString(), 'f');
						}
					}
				}
				$sql = $appSession->getTier()->getInsert($builder);
				$this->msg->add("query", $sql);
				
				$appSession->getTier()->exec($this->msg);
				if ($deleted)
				{
					$sql = "UPDATE ".$this->name."_local SET status=1 , write_date=".$appSession->getTier()->getDateString()." WHERE id='".$dt->getString($i, "id")."'";
					$this->msg->add("query", $sql);
					
					$appSession->getTier()->exec($this->msg);
				}
				for ($n = 0; $n < count($this->childList); $n++)
				{
					$this->childList[$n]->copy($appSession, $dt->getString($i, "id"), $deleted);
				}
			}else{
				
				for ($n = 0; $n < count($this->childList); $n++)
				{
					$this->childList[$n]->copy($appSession, $dt->getString($i, "id"), $deleted);
				}
					
				if ($deleted)
				{
					$sql = "UPDATE ".$this->name."_local SET status=1 , write_date=".$appSession->getTier()->getDateString()." WHERE id='".$dt->getString($i, "id")."'";
					$this->msg->add("query", $sql);
					$appSession->getTier()->exec($this->msg);
				}
			}
		}
	}
	public function delete($appSession, $idValue)
	{
		
	}
	
}

?>