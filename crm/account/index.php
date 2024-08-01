<?php
$continue = "";
if(isset($_REQUEST['continue']))
{
	$continue = $_REQUEST['continue'];
}
$ac = '';
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
if(isset($_POST['ac']))
{
	$ac = $_POST['ac'];
}

if($ac == "")
{
	$ac = "view";
}
$message = "";
$user = '';
$login_count = 0;
$check_count = 3;
 if($ac == "captcha")
{
	$x = 4;
	$captcha_code = substr(str_shuffle("0123456789"), 0, $x);
	$_SESSION["captcha_code"] = $captcha_code;
	$layer = imagecreatetruecolor(80, 37);
	$captcha_bg = imagecolorallocate($layer, 247, 174, 71);
	imagefill($layer, 0, 0, $captcha_bg);
	$captcha_text_color = imagecolorallocate($layer, 0, 0, 0);
	imagestring($layer, 5, 20, 10, $captcha_code, $captcha_text_color);
	header("Content-type: image/jpeg");
	imagejpeg($layer);

}else if($ac == "lang")
{
	if(isset($_REQUEST["id"]))
	{
		if($_REQUEST["id"] != "")
		{
			$appSession->getConfig()->setProperty("lang_id", $_REQUEST["id"]);
			$appSession->getConfig()->save();
		}
		
	}
	$ac = "view";
}
else if($ac == "login")
{
	$msg = $appSession->getTier()->createMessage();
	$ac = "view";
	$valid = 1;
	if(isset($_SESSION["login_count"]))
	{
		$login_count = $_SESSION["login_count"];
		if($login_count > $check_count)
		{
			$captcha_code = "";
			if(isset($_SESSION["captcha_code"]))
			{
				$captcha_code = $_SESSION["captcha_code"];
			}
			$captcha = '';
			if(isset($_REQUEST['captcha']))
			{
				$captcha = $_REQUEST['captcha'];
			}
			if(isset($_POST['captcha']))
			{
				$captcha = $_POST['captcha'];
			}
			if($captcha != $captcha_code)
			{
				$message = "Security code is invalid";
				$valid = 0;
			}
			
			
		}
		
	}
	$login_count = $login_count + 1;
	$_SESSION["login_count"] = $login_count;
	
	if(isset($_REQUEST['user_name']))
	{
		$user = $_REQUEST['user_name'];
	}
	if(isset($_POST['user_name']))
	{
		$user = $_POST['user_name'];
	}
	$password = '';
	if(isset($_REQUEST['password']))
	{
		$password = $_REQUEST['password'];
	}
	if(isset($_POST['password']))
	{
		$password = $_POST['password'];
	}
	if($valid == 1)
	{
		$sql = "SELECT d1.id, d1.password, d1.name, d.company_id, d2.parent_id AS parent_company_id, d1.date_format, d1.thousands_sep, d1.time_format, d1.decimal_point, d1.avatar, d1.lang_id, d2.currency_id, d.group_id, d.employee_id FROM res_user_company d LEFT OUTER JOIN res_user d1 ON(d.user_id = d1.id) LEFT OUTER JOIN res_company d2 ON(d.company_id = d2.id) WHERE d.status =0 AND (d1.user_name='".$user."' OR d1.email='".$user."') AND d1.status =0";
		
		$msg->add("query", $sql);
		$dt = $appSession->getTier()->getTable($msg);
		
		if($dt->getRowCount()>0)
		{
			
			$row = $dt->getRow(0);
			$user_id = $row->getString("id");
			$s = $appSession->getTool()->toHash("sha256", "[".$user_id."]".$password);
			$len = $appSession->getTool()->lenght($password);
			for($i = 0; $i<$len; $i++)
			{
				$s = $s.chr($i + 48);
			}
			$password = $appSession->getTool()->toHash("md5", $s);
			
			if($password == $row->getString("password"))
			{
				if($appSession->getConfig()->getProperty("lang_id") != "")
				{
					$sql = "UPDATE res_user SET lang_id='".$appSession->getConfig()->getProperty("lang_id")."' WHERE id='".$user_id."'";
					$msg->add("query", $sql);
					$appSession->getTier()->exec($msg);
				}else{
					$appSession->getConfig()->setProperty("lang_id", $row->getString("lang_id"));
				}
				$appSession->getConfig()->setProperty("user_id", $user_id);
				$appSession->getConfig()->setProperty("user_name", $row->getString("name"));
				
				$appSession->getConfig()->setProperty("date_format", $row->getString("date_format"));
				$appSession->getConfig()->setProperty("thousands_sep", $row->getString("thousands_sep"));
				$appSession->getConfig()->setProperty("time_format", $row->getString("time_format"));
				$appSession->getConfig()->setProperty("decimal_point", $row->getString("decimal_point"));
				$appSession->getConfig()->setProperty("avatar", $row->getString("avatar"));
				$appSession->getConfig()->setProperty("employee_id", $row->getString("employee_id"));
				$appSession->getConfig()->setProperty("currency_id", $row->getString("currency_id"));
				$appSession->getConfig()->setProperty("parent_company_id", $row->getString("parent_company_id"));
				$appSession->getConfig()->setProperty("user_group_id", $row->getString("group_id"));
				
				$company_id = $row->getString("company_id");
				$appSession->getConfig()->setProperty("company_id", $company_id);
				$msg = $appSession->getTier()->createMessage();
				$msg->add("company_id", $company_id);
				$msg->add("user_id", $user_id);
			
				$appSession->getConfig()->save();
				$_SESSION["login_count"] = 0;
				if($continue == "")
				{
					$continue = URL;
				}
				header('Location: '.$continue);
				exit;
			}else{
				
				$message = 'Password is invalid';
			}
			
		}else{
			
			$message = 'Account name is invalid';
		}
	}
	
}else if($ac == "logout")
{
	$appSession->getConfig()->setProperty("user_id", "");
	$appSession->getConfig()->setProperty("user_name", "");
	$appSession->getConfig()->setProperty("company_id", "");
	$appSession->getConfig()->save();
	
	header('Location: '.$continue);
	exit;
	
	
}
if($ac == "view")
{
	$appSession->getLang()->load($appSession->getTier(), "account", $appSession->getConfig()->getProperty("lang_id"));
	
?>

<!doctype html>
<html lang="en">
<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="<?php echo URL;?>favicon.ico" type="image/png"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo URL;?>assets/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link href="<?php echo URL;?>assets/css/style.css" type="text/css" rel="stylesheet">

    <!-- Responsive CSS -->
    <link href="<?php echo URL;?>assets/css/responsive.css" type="text/css" rel="stylesheet">

    <!-- Font CSS -->
    <link href="<?php echo URL;?>assets/css/gogle_sans_font.css" type="text/css" rel="stylesheet">

    <!--  For icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
	
    <!-- Page Title -->
    <title><?php echo $appSession->getLang()->find("Login");?></title>
</head>

<body id="page_sign_in">
    <div class="container-fluid px-0 px-md-3 px-lg-4">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-5 col-lg-5">
                <div class="logo_box mx-auto text-center">
                    <img src="<?php echo URL;?>assets/images/logo.png" class="img-fluid">
                </div>
                <div class="banner_img">
                    <img src="<?php echo URL;?>assets/images/img_signin.png" class="img-fluid">
                </div>
				
            </div>

            <div class="col-12 col-sm-12 col-md-7 col-lg-7">
                <form class="col-12 col-lg-10 mx-auto" id="frmLoginInfo" name="frmLoginInfo">
                    <div class="form-inner w-100" >
                        <div class="col-12 col-md-12 col-lg-9 col-xl-9  m-auto px-4">
                            <h2><?php echo $appSession->getLang()->find("Login to your Account");?></h2>
							<span style="color:red"><b><?php echo $appSession->getLang()->find($message)?></b></span>
							
                            <div class="form-group">
                                <label><?php echo $appSession->getLang()->find("User Name");?></label>
                                <input type="text" id="edituser_name" class="form-control" placeholder="" autocomplete="off" onKeyDown="if(event.keyCode == 13){login();}">
                            </div>
                            <div class="form-group">
                                <label><?php echo $appSession->getLang()->find("Password");?></label>
                                <input type="password" class="form-control" placeholder="" autocomplete="off" onKeyDown="if(event.keyCode == 13){login();}" id="editpassword">
                            </div>
							<?php
								if($login_count > $check_count)
								{
							?>
							<div class="row">
								<div class="col m8">
									<label class="label"><?php echo $appSession->getLang()->find("Security Code")?>: </label>
									<input class="input"  type="text" id="editcaptcha" value="" autocomplete="off" onKeyDown="if(event.keyCode == 13){login();}" />
								</div>
								<div class="col m4">
									<label></label>
									<br>
									<img width="100%" height="36px" src="<?php echo URL;?>account/?ac=captcha" onclick="this.src = this.src">
								</div>
							</div>
							<?php
								}
							?>


                            <button type="button" class="btn rounded-pill" onclick="login()"><?php echo $appSession->getLang()->find("Login");?></button>
							<input type="hidden" id="ac" name="ac" value="login"/>
							<input type="hidden" id="user_name" name="user_name"/>
							<input type="hidden" id="password" name="password"/>
					
							<input type="hidden" id="captcha" name="captcha"/>
							<input type="hidden" id="continue" name="continue" value="<?php echo $continue;?>"/>

				

            </div>
        </div>
		
    </div>

    <!-- Require Javascript Start -->
	<script src="<?php echo URL;?>assets/js/jquery.js"></script>
    <script src="<?php echo URL;?>assets/js/bootstrap.min.js"></script>
	
	<script src="<?php echo URL;?>assets/js/controller.js"></script>
	<script src="<?php echo URL;?>assets/js/tool.js"></script>
	<script src="<?php echo URL;?>assets/js/sha1.js"></script>
    <!-- Require Javascript End -->
	<script>

	function login()
	{
		var ctr = document.frmLoginInfo.edituser_name;
		if(ctr.value == '')
		{
			alert("Please enter user");
			ctr.focus();
			return;
		}
		var user_name = ctr.value;
		ctr = document.frmLoginInfo.editpassword;
		if(ctr.value == '')
		{
			alert("Please enter password");
			ctr.focus();
			return;
		}
		var password = ctr.value;
		
		<?php
		if($login_count > $check_count)
		{
		?>
		var ctr = document.frmLoginInfo.editcaptcha;
		if(ctr.value == '')
		{
			alert("Please enter captcha code");
			ctr.focus();
			return;
		}
		document.frmLoginInfo.captcha.value = ctr.value;
		<?php
		}
		?>
		password = Sha1.hash(password);
		
		document.frmLoginInfo.user_name.value = user_name;
		document.frmLoginInfo.password.value = password;
		document.frmLoginInfo.submit();
	}

	
	$( document ).ready(function() {
	  document.getElementById('edituser_name').focus();
	});
	</script>

</body>

</html>
<?php
}
?>