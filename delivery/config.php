<?php
$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]". rtrim( dirname($_SERVER["SCRIPT_NAME"]), '/' )."/";
define( 'URL', $link);
$session_id = "";
if(isset($_COOKIE["vd_id"]))
{
	$session_id = $_COOKIE["vd_id"];
	if($session_id == "")
	{
		$uuid = array(
	  'time_low'  => 0,
	  'time_mid'  => 0,
	  'time_hi'  => 0,
	  'clock_seq_hi' => 0,
	  'clock_seq_low' => 0,
	  'node'   => array()
	 );

	 $uuid['time_low'] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
	 $uuid['time_mid'] = mt_rand(0, 0xffff);
	 $uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
	 $uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
	 $uuid['clock_seq_low'] = mt_rand(0, 255);

	 for ($i = 0; $i < 6; $i++) {
	  $uuid['node'][$i] = mt_rand(0, 255);
	 }

	 $uuid = sprintf('%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
	  $uuid['time_low'],
	  $uuid['time_mid'],
	  $uuid['time_hi'],
	  $uuid['clock_seq_hi'],
	  $uuid['clock_seq_low'],
	  $uuid['node'][0],
	  $uuid['node'][1],
	  $uuid['node'][2],
	  $uuid['node'][3],
	  $uuid['node'][4],
	  $uuid['node'][5]
	 );
	 $session_id = $uuid;
	setcookie("vd_id", $session_id, time() + (10 * 365 * 24 * 60 * 60), '/');
	}
}

if(isset($_REQUEST['_id']))
{
	$session_id = $_REQUEST['_id'];
}
if($session_id == "")
{
	
	$session_id = "1234";
	
}


define('ID', 'VFSC');
define('META_TITLE', 'Fosacha');
define('META_KEYWORD', 'vfscfood.com');
define('META_DESCRIPTION', 'vidu.vn');
define('DB_HOST', 'service.vidu.vn');
define('DB_PORT', '5432');
define('DB_NAME', 'tpcn_db');
define('DB_USER', 'postgres');
define('DB_PASSWORD', 'vidu@1234567!');
define('LANGUAGE', 'vi');
define( 'COMPANY_ID', 'ROOT' );
define('COMPANY_NAME', 'vidu.vn');
define('COMPANY_LAT', '10.861177053727173');
define('COMPANY_LNG', '106.74004260552694');
define('CONTACT_EMAIL', 'info@vidu.vn');
define('CONTACT_TEL', '(+84) 0909 525850');
define('CONTACT_ADDRESS', 'Tp. Hồ Chí Minh');
define( 'SERVICE_URL', 'http://service.vidu.vn:2020/');
define( 'WS_URL', 'ws://service.vidu.vn:2021/');
define('ODD_COLOR', "#fffde7");
define('EVEN_COLOR', "#fff");
define('thousands_point', ",");
define('decimal_point', ".");
define('date_format', "YYYY-MM-DD");
define('copyright', "All Rights Reserved by fosacha.vn");
define('addons', "addons");
define('db_type', "postgresql");
define('developer', "0");
define('HOME', "home");
define( 'DEFAULT_DOC_PATH', "D:\\xampp\\htdocs\\tpcn\\disk\\");
define('GOOGLE_API_KEY', 'AIzaSyA3mDc_R4Q5mX_AU9UMwsJbjljyTPvpqfM');
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

if (DEFAULT_DOC_PATH == "") {
    define("DOC_PATH", dirname(__FILE__) . "/disk/");
} else {
    define("DOC_PATH", DEFAULT_DOC_PATH);
}

require_once(ABSPATH . 'app/AppSession.php');
require_once(ABSPATH . 'app/data/DataTier.php');
require_once(ABSPATH . 'app/data/PostgreSQLTier.php');



$appSession = new AppSession($session_id);
if ($appSession->getConfig()->hasKey("thousands_sep") == false) {
    $appSession->getConfig()->setProperty("thousands_sep", thousands_point);
}

if ($appSession->getConfig()->hasKey("decimal_point") == false) {
    $appSession->getConfig()->setProperty("decimal_point", decimal_point);
}
if ($appSession->getConfig()->hasKey("date_format") == false) {
    $appSession->getConfig()->setProperty("date_format", date_format);
}
if ($appSession->getConfig()->getProperty("addons") == "") {
    $appSession->getConfig()->setProperty("addons", addons);
}
$appSession->getConfig()->setProperty("service_url", SERVICE_URL);
$appSession->getConfig()->setProperty("ws_url", WS_URL);
if (db_type == "postgresql") {
    $dataTier = new PostgreSQLTier(DB_NAME, DB_HOST, DB_PORT, DB_USER, DB_PASSWORD);
    $appSession->setTier($dataTier);
} else {
    $dataTier = new DataTier(SERVICE_URL, "", "");
    $appSession->setTier($dataTier);
}


require_once(ABSPATH . 'app/lang/Language.php');
$session_id = $appSession->getConfig()->getProperty("session_id");
if ($session_id == "") {
    $session_id = $appSession->getTool()->getId();
    $appSession->getConfig()->setProperty("session_id", $session_id);
    $appSession->getConfig()->save();
}
$lang_id = $appSession->getConfig()->getProperty("lang_id");
if ($lang_id == "") {
    $default_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $sql = "SELECT id FROM res_lang WHERE code iLIKE '%" . $default_lang . "%' AND status =0";
    $msg = $appSession->getTier()->createMessage();
    $msg->add("query", $sql);
    $arr = $appSession->getTier()->getArray($msg);
    if (count($arr) > 0) {
        $lang_id = $arr[0][0];
    } else {
        $lang_id = "vi";
    }
    $appSession->getConfig()->setProperty("lang_id", $lang_id);
    $appSession->getConfig()->save();
}
$routing = new Language();
$routing->load($appSession->getTier(), "web_routes", $lang_id);