<?php
require_once(ABSPATH.'app/AppConfig.php' );
require_once(ABSPATH.'app/UserInfo.php' );
require_once(ABSPATH.'app/Tool.php' );
require_once(ABSPATH.'app/util/Formats.php' );
require_once(ABSPATH.'app/util/Currency.php' );
require_once(ABSPATH.'app/lang/Language.php' );
require_once(ABSPATH.'app/data/DataTier.php' );
	

	class AppSession
	{
		private $formats = NULL;
		private $config = NULL;
		private $userInfo = NULL;
		private $currency = NULL;
		private $dataTier = NULL;
		private $tool = NULL;
		private $lang = NULL;
		private $id;
		function __construct($id) {
			$this->tool = new Tool();
			$this->config = new AppConfig();
			$this->config->load(ABSPATH."session/".$id);
			$this->userInfo = new UserInfo($this->config);
			$this->lang = new Language();
			$this->id = $id;
			
		}
		function getId()
		{
			return round(microtime(true)*1000);
		}
		function getTool()
		{
			return $this->tool;
		}
		function getFormats()
		{
			if($this->formats == NULL)
			{
				$this->formats = new Formats($this->config);
			}
			
			return $this->formats;
		}
		function getConfig()
		{
			return $this->config;
		}
		function getUserInfo()
		{
			return $this->userInfo;
		}
		function getCurrency()
		{
			if($this->currency == NULL)
			{
				$this->currency = new Currency($this->config);
				$this->currency->load($this);
			}
			return $this->currency;
		}
		
		function setTier($dataTier)
		{
			$this->dataTier = $dataTier;
		}
		function getTier()
		{
			
			return $this->dataTier;
		}
		
		
		function getLang()
		{
			return $this->lang;
		}
		function __destruct() 
		{
			if($this->dataTier != NULL)
			{
				$this->dataTier->close();
			}
		}
	}
?>