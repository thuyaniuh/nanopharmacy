<?php
class WebService
{
	public $appSession;
	public $msg;
	public function __construct($appSession) {
		$this->appSession = $appSession;
		$this->msg = $appSession->getTier()->createMessage();
	}
	public function sendMessage($data)
	{
		if($this->appSession->getConfig()->getProperty("service_url") != "")
		{
			$this->appSession->getTool()->httpPost($this->appSession->getConfig()->getProperty("service_url"), $data);
			
		}
	}
}

?>