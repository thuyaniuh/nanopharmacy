<?php
validUser($appSession);
?>
<!doctype html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="<?php echo URL;?>favicon.ico" type="image/png"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo URL;?>assets/css/bootstrap.min.css">

    <!-- Animate CSS -->
    <link rel="stylesheet" href="<?php echo URL;?>assets/css/animate.css" />

    <!-- Custom CSS -->
    <link href="<?php echo URL;?>assets/css/style.css?v=3" type="text/css" rel="stylesheet">

    <!-- Responsive CSS -->
    <link href="<?php echo URL;?>assets/css/responsive.css" type="text/css" rel="stylesheet">

    <!-- Font CSS -->
    <link href="<?php echo URL;?>assets/css/gogle_sans_font.css" type="text/css" rel="stylesheet">
	<link href="<?php echo URL;?>assets/daterange/daterangepicker.css" rel="stylesheet" type="text/css" />
    <!--  For icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">

    <!-- Page Title -->
    <title><?php echo META_TITLE?></title>


</head>

<body id="page_orders_status">
    <!-- Header Start -->
    <header id="header" class="animate__animated animate__fadeInDown wow" data-wow-duration=".5s">
        <nav class="navbar align-items-center" style="flex-wrap: unset;">
            <div class="nav-item  ml-lg-2 ml-xl-0 logo" style="min-width: fit-content;">
                <a class="navbar-brand nav-link px-0" href="<?php echo URL;?>">
                    <img src="<?php echo URL;?>assets/images/logo.png?v=1" height="46" class="img-fluid">
                </a>
            </div>

            <div class="nav-inner ml-5 pl-4 w-100">
                <ul class="navbar-nav d-flex align-items-center w-100">
					<li class="nav-item show-only-large-devices">
                        <a class="nav-link" href="javascript:loadModule('order')"><i class="zmdi zmdi-hourglass-alt"></i><?php echo $appSession->getLang()->find("Orders");?></a>
                    </li>
					<li class="nav-item show-only-large-devices">
                        <a class="nav-link" href="javascript:loadModule('stockin')"><i class="zmdi zmdi-hourglass-alt"></i><?php echo $appSession->getLang()->find("Stock In");?></a>
                    </li>
					<li class="nav-item show-only-large-devices">
                        <a class="nav-link" href="javascript:loadModule('stockout')"><i class="zmdi zmdi-hourglass-alt"></i><?php echo $appSession->getLang()->find("Stock Out");?></a>
                    </li>
					
                
                    <li class="nav-item profile_img ml-auto" id="menu">
						<div class="dropdown">
                            <span>
                                <i class="zmdi zmdi-menu"></i>
                                <i class="zmdi zmdi-close"></i>
                            </span>
                            <a class="img_box center_img">
                                <?php echo $appSession->getConfig()->getProperty("user_name");?>
                            </a>
                        </div>
						
                     
                    </li>
                </ul>
            </div>
        </nav>

        <div class="right-side-menu">
            <div class="menu-inner">
                <ul class="align-items-center w-100">
                    <li class="show-medium-devices nav-item">
                        <a class="nav-link" href="javascript:loadModule('order')"><i class="zmdi zmdi-assignment"></i><?php echo $appSession->getLang()->find("Orders");?></a>
                    </li>
                    <li class="show-medium-devices">
                        <a class="nav-link" href="javascript:loadModule('stockin')"><i class="zmdi zmdi-collection-text"></i><?php echo $appSession->getLang()->find("Stock In");?></a>
                    </li>
					 <li class="show-medium-devices">
                        <a class="nav-link" href="javascript:loadModule('stockout')"><i class="zmdi zmdi-collection-text"></i><?php echo $appSession->getLang()->find("Stock Out");?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URL;?>account?ac=logout&continue=<?php echo urlencode(URL);?>"><i class="zmdi zmdi-open-in-new"></i>Thoát</a>
                    </li>
					
                </ul>
            </div>
        </div>
    </header>

    <div class="header_spacebar"></div>
    <!-- Header End -->

    <!-- Body Wrapper Start -->
    <div class="body_wrapper" id="contentView">
       
    </div>
	<div class="modal fade" id="frmdialog" role="dialog" style="padding-top: 50px">
		<div class="modal-dialog modal-lg">
		
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" id="frmdialogClose" class="close" data-dismiss="modal">&times;</button>
			
			</div>
			<div class="modal-body" id="popupContent">
		   
			</div>
			
		  </div>
		  
		</div>
	</div>
    <!-- Body Wrapper End -->

    <!-- Require Javascript Start -->
	<script src="<?php echo URL;?>assets/js/jquery.js"></script>
	<script src="<?php echo URL;?>assets/daterange/moment.min.js"></script>
	<script src="<?php echo URL;?>assets/daterange/daterangepicker.min.js"></script>
	
    <script src="<?php echo URL;?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo URL;?>assets/js/wow.min.js"></script>
	<script src="<?php echo URL;?>assets/js/controller.js"></script>
	<script src="<?php echo URL;?>assets/js/tool.js"></script>
    <script>
        $("#menu").on("click", function() {
            $("#header").toggleClass("active");
        });
    </script>
    <script>
        new WOW().init();
		function loadModule(page)
		{
			var _url = '<?php echo URL;?>addons/' + page + '/';
			loadPage('contentView', _url, function(status, message)
			{
				if(status== 0)
				{
					
				}
				
			}, false);
		}
		$( document ).ready(function() {
			loadModule('order');
		});
		function loadView(url)
		{
			var _url = '<?php echo URL;?>' + url;
			loadPage('contentView', _url, function(status, message)
			{
				if(status== 0)
				{
					
				}
				
			}, false);
		}
		
		
    </script>
    <!-- Require Javascript End -->
</body>

</html>