<?php
	require_once('config.php' );

	function validUser($appSession)
	{
		if($appSession->getUserInfo()->getId() == '')
		{
			
			header('Location: '.URL.'account?continue='.$appSession->getTool()->urlEncode(URL."addons"));
			exit;
		}
	}
	$uri = rtrim( dirname($_SERVER["SCRIPT_NAME"]), '/' );
	
	$uri = trim( str_replace( $uri, '', $_SERVER['REQUEST_URI'] ), '/' );
	$PARAMS = "";
	$uri = urldecode($uri );
	$index = strpos($uri, '?');
	
	if($index !== false)
	{
		$PARAMS = substr($uri, $index + 1);
		$uri = substr($uri, 0,$index);
	}
	
	if(strrpos($uri, ".php") !== false)
	{
		
		include(ABSPATH .$uri);
		exit();
	}
	
	$base = $uri;
	
	$index = $appSession->getTool()->lastIndexOf($base, '/');
	if($index != -1)
	{
		$base = $appSession->getTool()->substring($base, 0, $index)."/".$routing->findKey($appSession->getTool()->substring($base, $index +1 ), $lang_id);
		
	}
	
	
	$base = $routing->findKey($base, $lang_id);
	if(is_dir(ABSPATH.$base) == false)
	{
		$base = "";
	}
	if($base == "")
	{
		$base = addons;
	}
	if(file_exists(ABSPATH .$base.'/index.php') == true)
	{
		include( ABSPATH .$base.'/index.php' );
		exit();
	}
	include( ABSPATH .addons.'/index.php' );
	exit();
?>
