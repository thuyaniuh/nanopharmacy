<?php
class UserInfo
{

	private $config;
	function __construct($config) 
	{
		$this->config = $config;

	}
	function getId()
	{
		return $this->config->getProperty("user_id");
	}
	function setId($id)
	{
		$this->config->setProperty("user_id", $id);
	}
	function getName()
	{
		return $this->config->getProperty("user_name");
	}
	
	function getGroupId()
	{
		return $this->config->getProperty("user_group_id");
	}
	function getLang()
	{
		return $this->config->getProperty("lang_id");
	}
	function getCompanyId()
	{
		return $this->config->getProperty("company_id") ;
	}
}

?>