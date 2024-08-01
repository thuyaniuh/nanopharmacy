<?php
require_once(ABSPATH . 'app/AppConfig.php');
require_once(ABSPATH . 'app/UserInfo.php');
require_once(ABSPATH . 'app/Tool.php');
require_once(ABSPATH . 'app/util/Formats.php');
require_once(ABSPATH . 'app/util/Currency.php');
require_once(ABSPATH . 'app/lang/Language.php');
require_once(ABSPATH . 'app/data/DataTier.php');


class AppSession
{
  private $formats;
  private $config;
  private $userInfo;
  private $currency;
  private $dataTier;
  private $tool;
  private $lang;

  function __construct($id)
  {
    $this->tool = new Tool();
    $this->config = new AppConfig();
    $this->config->load(ABSPATH . "session/" . $id);
    $this->userInfo = new UserInfo($this->config);
    $this->lang = new Language();
  }

  // INFO: uniqid()
  function getId()
  {
    return round(microtime(true) * 1000);
  }

  function getTool()
  {
    return $this->tool;
  }

  function getFormats()
  {
    if (empty($this->formats)) {
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
    if (empty($this->currency)) {
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
    if ($this->dataTier != NULL) {
      $this->dataTier->close();
    }
  }
}
