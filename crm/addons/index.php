<?php
validUser($appSession);
require_once(ABSPATH . 'api/LineDepartment.php');

$msg = $appSession->getTier()->createMessage();


$mLineDepartment = new LineDepartment();
$values = $mLineDepartment->findByEmployee($appSession, $appSession->getConfig()->getProperty("employee_id"));

$sql = "SELECT DISTINCT d2.id, d2.name, '' AS name_lg, p.document_id, doc.path, doc.ext, '' AS m_id";
$sql = $sql." FROM mrp_workcenter_line_rel d1";
$sql = $sql." LEFT OUTER JOIN mrp_workcenter_line_flow d2 ON(d1.line_id = d2.id)";
$sql = $sql." LEFT OUTER JOIN mrp_workcenter_line d3 ON(d2.line_id= d3.id)";
$sql = $sql." LEFT OUTER JOIN poster p ON(p.rel_id = d3.id AND p.status =0 AND p.publish=1)";
$sql = $sql." LEFT OUTER JOIN document doc ON(p.document_id = doc.id)";
$sql = $sql." WHERE d1.status =0 AND d2.status =0";
$ids = "";

for($i=0; $i<count($values); $i++){
	if($ids != ""){
	  $ids = $ids ." OR ";
	}
	$ids = $ids ." d1.rel_id='".$values[$i][0]."'";
}
if($ids != ""){
	$sql = $sql." AND (".$ids.")";
}else{
	$sql = $sql." AND 1=0";
}
$sql = $sql." ORDER BY d2.name DESC";
$msg->add("query", $sql);
$dt = $appSession->getTier()->getTable($msg);
$sql = "SELECT  DISTINCT d2.id, d1.rel_id, d2.name, lg.description As name_lg, d2.rel_id AS root_module_id";
$sql = $sql.", p.document_id, doc.path, doc.ext, d1.sequence";
$sql = $sql." FROM ir_module_rel d1";
$sql = $sql." LEFT OUTER JOIN ir_module d2 ON(d1.module_id= d2.id)";
$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".
        $appSession->getConfig()->getProperty("lang_id") .
        "' AND lg.rel_id = d2.id AND lg.name='module_name' AND lg.status =0)";
$sql = $sql." LEFT OUTER JOIN poster p ON(d2.id = p.rel_id AND p.status =0 AND p.publish =1)";
$sql = $sql." LEFT OUTER JOIN document doc ON(p.document_id = doc.id)";
$sql = $sql." WHERE d1.status =0";
$ids = "";
for($i=0; $i<$dt->getRowCount(); $i++){
  if($ids != ""){
	$ids = $ids + " OR ";
  }
  $ids = $ids ." d1.rel_id ='" .$dt->getString($i, "id")."'";
}
if($ids != ""){
  $sql = $sql." AND (".$ids.")";
}else{
 $sql = $sql." AND 1=0";
}
$sql = $sql." ORDER BY d1.sequence ASC";
$msg->add("query", $sql);
$dt_module = $appSession->getTier()->getTable($msg);

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
                    <img src="<?php echo URL;?>assets/images/logo.png?v=2" height="46" class="img-fluid">
                </a>
            </div>

            <div class="nav-inner ml-5 pl-4 w-100">
                <ul class="navbar-nav d-flex align-items-center w-100">
					<?php
					for($i =0; $i<$dt_module->getRowCount(); $i++){
						if($i>4)
						{
							break;
						}
						$module_id = $dt_module->getString($i, "id");
						$name = $dt_module->getString($i, "name_lg");
						if($name == "")
						{
							$name = $dt_module->getString($i, "name");
						}
					?>
					<li class="nav-item show-only-large-devices">
                        <a class="nav-link" href="javascript:loadModule('<?php echo $module_id;?>')"><?php echo $name;?></a>
                    </li>
					<?php
					}
					?>
					
					
                
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
					<?php
					for($i =0; $i<$dt_module->getRowCount(); $i++){
						
						$module_id = $dt_module->getString($i, "id");
						$name = $dt_module->getString($i, "name_lg");
						if($name == "")
						{
							$name = $dt_module->getString($i, "name");
						}
					?>
                    <li class="show-medium-devices nav-item">
                        <a class="nav-link" href="javascript:loadModule('<?php echo $id;?>')"><?php echo $name;?></a>
                    </li>
					<?php
					}
					?>
                   
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URL;?>account?ac=logout&continue=<?php echo urlencode(URL);?>"><i class="zmdi zmdi-open-in-new"></i><?php echo $appSession->getLang()->find("Logout");?></a>
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
		var module_id = '<?php echo $appSession->getConfig()->getProperty("module_id");?>';
		if(module_id == "")
		{
			<?php
			if($dt_module->getRowCount()>0)
			{
				$module_id = $dt_module->getString(0, "id");
				?>
				module_id = '<?php echo $module_id;?>';
				<?php
			}
			?>
		}
        new WOW().init();
		var moduleList = new Array();
		moduleList.push(new Array("0e8273bf-8248-44e0-eb0b-51cd0e71d847", "order"));
		moduleList.push(new Array("2fecf749-28d7-4d7b-cc73-4f31c095ab44", "customer"));
		moduleList.push(new Array("customer_pending", "customer_pending"));
		function loadModule(page)
		{
			for(var i =0; i<moduleList.length; i++){
				if(moduleList[i][0] == page)
				{
					page = moduleList[i][1];
					var _url = '<?php echo URL;?>addons/' + page + '/';
					loadPage('contentView', _url, function(status, message)
					{
						if(status== 0)
						{
							
						}
						
					}, false);
					break;
				}
			}
			
			
		}
		$( document ).ready(function() {
			if(module_id != "")
			{
				loadModule(module_id);
			}
			
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
		function showPopup(_url)
		{
			
			document.getElementById('popupContent').innerHTML = '';
			loadPage('popupContent', _url, function(status, message)
			{
				if(status== 0)
				{
					$('#frmdialog').modal('show');
				}
				
			}, false);
		}
		function closePopup()
		{
			var ctr = document.getElementById('frmdialogClose');
			if(ctr != null)
			{
				ctr.click();
			}
		}
		
    </script>
    <!-- Require Javascript End -->
</body>

</html>