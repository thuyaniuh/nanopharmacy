<?php
require_once('DataColumn.php' );
require_once('DataRow.php' );
require_once(ABSPATH.'app/util/Date.php' );
class DataTable
{
	private $name;
	private $columns = [];
	private $datas  = [];
	function __construct($name) {
		$this->name = $name;
	}
	function getName()
	{
		return $this->name;
	}
	function setName($name)
	{
		$this->name = $name;
	}
	function addColumn($name)
	{
		$column = new DataColumn($name);
		$column->setIndex(count($this->columns));
		$this->columns[count($this->columns)] = $column;
	}
	
	
	function newRow()
	{
		$row = new DataRow($this, count($this->datas));
	}
	function addArray($data)
	{
		$this->datas[count($this->datas)] = $data;
	}
	function getRow($index)
	{
		return new DataRow($this, $index);
	}
	function getRowCount()
	{
		return count($this->datas);
	}
	function getColumnCount()
	{
		return count($this->columns);
	}
	function getColumns()
	{
		return $this->columns;
	}
	function setColumns($columns)
	{
		$this->columns = $columns;
	}
	function getColumnIndex($name)
	{
		for($i =0; $i<count($this->columns); $i++)
		{
			if($this->columns[$i]->getName() == $name)
			{
				return $this->columns[$i]->getIndex();
			}
		}
		return -1;
	}
	function getColumnName($i)
	{
		return $this->columns[$i]->getName();
	}
	function getColumnType($i)
	{
		return $this->columns[$i]->getType();
	}
	function containColumn($name)
	{
		for($i =0; $i<count($this->columns); $i++)
		{
			if($this->columns[$i]->getName() == $name)
			{
				return true;
			}
		}
		return false;
	}
	function setValueAt($value, $rowIndex, $columnIndex)
	{
		$this->datas[$rowIndex][$columnIndex] = $value;
	}
	function setValue($value, $rowIndex, $columnName)
	{

		for($i =0; $i<count($this->columns); $i++)
		{
		
			if($this->columns[$i]->getName() == $columnName)
			{
				
				$this->setValueAt($value, $rowIndex, $i);
				break;
			}
			
		}
	}
	function getValueAt($rowIndex, $columnIndex)
	{
		if(count($this->datas)>=$rowIndex && count($this->datas[$rowIndex])>=$columnIndex)
		{
			return $this->datas[$rowIndex][$columnIndex];
		}
		return "";
	}
	function getValue($rowIndex, $columnName)
	{
		for($i =0; $i<count($this->columns); $i++)
		{
			if($this->columns[$i]->getName() == $columnName)
			{
				return $this->getValueAt($rowIndex, $i);
			}
		}
		return "";
	}
	
	function setStringAt($value, $rowIndex, $columnIndex)
	{
		$this->setValueAt($value, $rowIndex, $columnIndex);
	}
	function setString($value, $rowIndex, $columnName)
	{
		$this->setValue($value, $rowIndex, $columnName);
	}
	
	function getStringAt($rowIndex, $columnIndex)
	{
		return $this->getValueAt($rowIndex, $columnIndex);
	}
	function getString($rowIndex, $columnName)
	{
		return $this->getValue($rowIndex, $columnName);
	}
	function getIntAt($rowIndex, $columnIndex)
	{
		return $this->getValueAt($rowIndex, $columnIndex);
	}
	function getInt($rowIndex, $columName)
	{
		return $this->getValue($rowIndex, $columName);
	}
	function getFloatAt($rowIndex, $columnIndex)
	{
		return $this->getValueAt($rowIndex, $columnIndex);
	}
	function getFloat($rowIndex, $columName)
	{
		$value = $this->getValue($rowIndex, $columName);
		if($value == "")
		{
			$value = 0;
		}
		return floatval($value);
	}
	function getDateAt($rowIndex, $columnIndex)
	{
		$s = $this->getValueAt($rowIndex, $columnIndex);
		return new Date();
	}
	function getDate($rowIndex, $columName)
	{
		for($i =0; $i<count($columns); $i++)
		{
			if($columns[$i]->getName() == $columnName)
			{
				return $this->getDateAt($rowIndex, $i);
			}
		}
		return new Date();
	}
	function getData()
	{
		return $this->datas;
	}
	function getDataRow($i)
	{
		return $this->datas[$i];
	}
	function clear(){
		$this->datas = [];
	}
	
}

?>