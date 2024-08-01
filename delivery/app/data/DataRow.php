<?php
class DataRow
{
	private $table;
	private $index = 0;
	function __construct($table, $index) {
		$this->table = $table;
		$this->index = $index;
	}
	function setValueAt($value, $columnIndex)
	{
		$this->table->setValueAt($value, $this->index, $columnIndex);
	}
	function setValue($value, $rowIndex, $columnName)
	{
		for($i =0; $i<count($columns); $i++)
		{
			if($columns[$i]->getName() == $columnName)
			{
				$this->table->setValueAt($value, $this->index, $i);
			}
			break;
		}
	}
	function getValueAt($columnIndex)
	{
		return $this->table->getValueAt($this->index, $columnIndex);
	}
	function getValue($columnName)
	{
		return $this->table->getValue($this->index, $columnName);
	}

	function getIntAt($columnIndex)
	{
		return $this->table->getIntAt($this->index, $columnIndex);
	}
	function getInt($columName)
	{
		return $this->table->getIntAt($this->index, $columnName);
	}
	function getFloatAt($rowIndex, $columnIndex)
	{
		return $this->table->getFloatAt($this->index, $columnIndex);
	}
	function getFloat($columName)
	{
		return $this->table->getFloat($this->index, $columName);
	}
	function getDateAt($columnIndex)
	{
		return $this->table->getDateAt($this->index, $columnIndex);
	}
	function getDate($columName)
	{
		return $this->table->getDate($this->index, $columName);
	}
	function getStringAt($columnIndex)
	{
		return $this->table->getValueAt($this->index, $columnIndex);
	}
	function getString($columnName)
	{
		
		return $this->table->getValue($this->index, $columnName);
	}
}

?>