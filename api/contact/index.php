<?php

$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
$mail_to = "info@vifotec.com";

if($ac == "send")
{
	$name = "";
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];
	}
	$phone = "";
	if(isset($_REQUEST['phone']))
	{
		$phone = $_REQUEST['phone'];
	}
	$email = "";
	if(isset($_REQUEST['email']))
	{
		$email = $_REQUEST['email'];
	}
	$title = "";
	if(isset($_REQUEST['title']))
	{
		$title = $_REQUEST['title'];
	}
	$message = "";
	if(isset($_REQUEST['message']))
	{
		$message = $_REQUEST['message'];
	}
	$appSession->getTool()->send_mail($email, "CONTACT FOSACHA.VN", $mail_to, "", $title, "Name: ".$name."; ".$phone."; Email: ".$email."; ".$message);
	echo "OK";
}
?>