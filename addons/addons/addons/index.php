<?php
require_once(ABSPATH . 'api/Product.php');
require_once(ABSPATH . 'api/Sale.php');

$page = "home";
$lang_id = "vi";
$page_id = '';
$items = [];
if ($uri != '/' && $uri != '') {
  $items = explode("/", $uri);
  if (count($items) >0) {
    $page = $items[0];
  }
  if (count($items) >1) {
	$page_id = $items[count($items) -1];
    
  }
}
if($page == "lang")
{
	 if (count($items) >1) {
      $lang_id = $items[1];
      $appSession->getConfig()->setProperty("lang_id", $lang_id);
      $appSession->getConfig()->save();
	  $page = $items[2];
	  $page_id = $items[count($items) -1];
    }
}

$search = "";
$arr = $appSession->getTool()->split($PARAMS, '&');

for ($i = 0; $i < count($arr); $i++) {
  $index = $appSession->getTool()->indexOf($arr[$i], '=');
  if ($index != -1) {

    $k = substr($arr[$i], 0, $index);
    $v = substr($arr[$i], $index + 1);

    if ($k == "search") {
      $search = urldecode($v);
    }
  }
}

$langList = ["vi", "en",  "kr", "jp"];

for ($i = 0; $i < count($langList); $i++) {
  if ($appSession->getConfig()->getProperty("lang_id") == $langList[$i]) {
    $lang_id = $langList[$i];
    break;
  }
}

include(ABSPATH . 'app/lang/' . $lang_id . '.php');
foreach ($langs as $key => $item) {
  $appSession->getLang()->setProperty($key, $item);
}


$msg = $appSession->getTier()->createMessage();




if ($search != "") {
  $page = "shop";
}

$selected_id = "";
$page_title = META_TITLE;
$page_keywords = META_KEYWORD;
$page_description = META_DESCRIPTION;
$document_id = "";
$document_ext = "";
$index = $appSession->getTool()->indexOf($uri, "/c-");
if ($index != -1) {
  $page = "category";
  $code = $appSession->getTool()->substring($uri, $index + 3);
  $code = $appSession->getTool()->replace($code, "#", "");
  $code = $appSession->getTool()->replace($code, "/", "");

  $sql = "SELECT d1.id, d1.name, d2.keywords, d2.description FROM product_category d1 LEFT OUTER JOIN meta d2 ON(d1.id = d2.rel_id AND d2.status=0 AND d2.publish=1) WHERE d1.code='" . $code . "' AND d1.status =0";

  $msg->add("query", $sql);
  $arr = $appSession->getTier()->getArray($msg);
  if (count($arr) > 0) {
    $selected_id = $arr[0][0];
    if ($arr[0][1] != "") {
      $page_title = META_TITLE . " - " . $arr[0][1];
    }
    if ($arr[0][2] != "") {
      $page_keywords = $arr[0][2];
    }
    if ($arr[0][3] != "") {
      $page_description = $arr[0][3];
    }
  }
}

$index = $appSession->getTool()->indexOf($uri, "/g-");
if ($index != -1) {
  $page = "group";
  $code = $appSession->getTool()->substring($uri, $index + 3);
  $code = $appSession->getTool()->replace($code, "#", "");
  $code = $appSession->getTool()->replace($code, "/", "");
  $sql = "SELECT d1.id, d1.name, d2.keywords, d2.description FROM product_group d1 LEFT OUTER JOIN meta d2 ON(d1.id = d2.rel_id AND d2.status=0 AND d2.publish=1) WHERE d1.code='" . $code . "'";

  $msg->add("query", $sql);
  $arr = $appSession->getTier()->getArray($msg);
  if (count($arr) > 0) {
    $selected_id = $arr[0][0];
    if ($arr[0][1] != "") {
      $page_title = META_TITLE . " - " . $arr[0][1];
    }
    if ($arr[0][2] != "") {
      $page_keywords = $arr[0][2];
    }
    if ($arr[0][3] != "") {
      $page_description = $arr[0][3];
    }
  }
}
$index = $appSession->getTool()->indexOf($uri, "/p-");

if ($index != -1) {
  $page = "product";
  $code = $appSession->getTool()->substring($uri, $index + 3);

  $sql = "SELECT d2.id, d2.name, d3.keywords, d3.description, d8.document_id, d9.document_id, doc1.ext, doc2.ext FROM product_price d1 LEFT OUTER JOIN product d2 ON(d1.product_id = d2.id) LEFT OUTER JOIN meta d3 ON(d2.id = d3.rel_id AND d3.status=0 AND d3.publish=1)  LEFT OUTER JOIN poster d8 ON(d1.id = d8.rel_id AND d8.publish=1 AND d8.status =0) LEFT OUTER JOIN poster d9 ON(d2.id = d9.rel_id AND d9.publish=1 AND d9.status =0) LEFT OUTER JOIN document doc1 ON(d8.document_id = doc1.id) LEFT OUTER JOIN document doc2 ON(d9.document_id = doc2.id) WHERE d2.code ='" . $code . "'";

  $msg->add("query", $sql);
  $arr = $appSession->getTier()->getArray($msg);
  if (count($arr) > 0) {
    $selected_id = $arr[0][0];
    if ($arr[0][1] != "") {
      $page_title = $arr[0][1] . " - " . META_TITLE;
    }
    if ($arr[0][2] != "") {
      $page_keywords = $arr[0][2];
    }
    if ($arr[0][3] != "") {
      $page_description = $arr[0][3];
    }
    $document_id = $arr[0][4];
    $document_ext = $arr[0][6];
    if ($document_id == "") {
      $document_id = $arr[0][5];
      $document_ext = $arr[0][7];
    }
  }
}

$uri = $appSession->getTool()->replace($uri, "/", "");
if ($uri == "home" || $uri == "") {
  $page = "home";
} else if ($uri == "category") {
  $page = "category";
} else if ($uri == "checkout") {
  $page = "checkout";
} else if ($uri == "contact") {
  $page = "contact";
} else if ($uri == "paid") {
  $page = "paid";
} else if ($uri == "account") {
  $page = "account";
} else if ($uri == "order") {
  $page = "order";
} else if ($uri == "loyalty") {
  $page = "loyalty";
} else if ($uri == "profile") {
  $page = "profile";
} else if ($uri == "login") {
  $page = "login";
} else if ($uri == "signup") {
  $page = "signup";
} else if ($uri == "signup_otp") {
  $page = "signup_otp";
} else if ($uri == "forgot") {
  $page = "forgot";
}else if ($uri == "signin_otp") {
  $page = "signin_otp";
}else if ($uri == "reset") {
  $page = "reset";
} else if ($uri == "blog") {
  $page = "blog";
} else if ($uri == "account") {
  $page = "account";
} else if ($uri == "delivery") {
  $page = "track-order";
} else if ($uri == "sale") {
  $page = "sale";
} else if ($uri == "signin") {
  $page = "signin";
} else if ($uri == "sale_qa") {
  $page = "sale_qa";
} else if ($uri == "sale_shipping") {
  $page = "sale_shipping";
} else if ($uri == "sale_policy") {
  $page = "sale_policy";
} else if ($uri == "sale_rule") {
  $page = "sale_rule";
} else if ($uri == "sale_refund") {
  $page = "sale_refund";
} else if ($uri == "sale_quality") {
  $page = "sale_quality";
} else if ($uri == "chinh_sach") {
  $page = "chinh_sach";
} else if ($uri == "privacy") {
  $page = "privacy";
} else if ($uri == "cart") {
  $page = "cart";
} else if ($uri == "index_cart") {
  $page = "index_cart";
}else if ($uri == "compare") {
  $page = "compare";
}
else if ($uri == "wishlist") {
  $page = "wishlist";
}else if ($uri == "seller_become") {
  $page = "seller_become";
}else if ($uri == "seller_grid") {
  $page = "seller_grid";
}else if ($uri == "seller_detail") {
  $page = "seller_detail";
}else if ($uri == "shop_become") {
  $page = "shop_become";
}else if ($uri == "shop_grid") {
  $page = "shop_grid";
  
}else if ($page == "group") {
  $page = "group";
  
}else if ($page == "blog") {
  $page = "blog";
  
}else if ($page == "blog_detail") {
  $page = "blog_detail";
  
}else if ($page == "shop") {
  $sql = "SELECT id FROM res_company WHERE code ='".$appSession->getTool()->replace($page_id, "'", "''")."' AND status =0";

  $msg->add("query", $sql);
  $values = $appSession->getTier()->getArray($msg);
  if(count($values)>0)
  {
	  $appSession->getConfig()->setProperty("company_company_id", $values[0][0]);
	  $appSession->getConfig()->save();
  }
  $page = "shop";
}else if($page == "become-success")
{
	$page = "become-success";
}else if($page == "product")
{
	//$page = "product";
}else if($page == "home")
{
	$page = "home";
}else if($page == "contact")
{
	$page = "contact";
}else if($page == "search")
{
	$page = "shop";
}else if($page == "about")
{
	$page = "about";
}else if ($page == "order-tracking") {
  $page = "order-tracking";
  
}else if ($page == "change_password") {
  $page = "change_password";
  
}else if ($page == "sale_payment") {
  $page = "sale_payment";
  
}else if ($page == "category") {
  $page = "category";
  
}else{

	$page = "404";
}

$sql = "SELECT d1.code, d1.name FROM post_category d1  WHERE d1.status =0 AND d1.type='POST' ORDER BY d1.sequence ASC";

$msg->add("query", $sql);
$post_category = $appSession->getTier()->getArray($msg);


$product = new Product($appSession);
$company_company_id = $appSession->getConfig()->getProperty("company_company_id");
$dt_category = $product->categoryListByCompany("ROOT");


$sale = new Sale($appSession);
$sale_id = $sale->findSaleId();
$saleProductList = $sale->productListQuantity($sale_id);

$sql = "SELECT d1.id, d1.code, d1.name, d1.commercial_name  FROM res_company d1 WHERE d1.status =0 AND d1.publish =1 ORDER BY d1.sequence ASC LIMIT 10";

$msg->add("query", $sql);
$dt_company = $appSession->getTier()->getTable($msg);

if($company_company_id == "")
{
	$company_company_id = COMPANY_ID;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo $page_description ?>">
  <meta name="keywords" content="<?php echo $page_keywords ?>">
  <meta name="author" content="Vang Nguyen Van">

 <?php
	if( $document_id != "")
	{
		if($document_ext == "svg")
		{
			$document_ext  = "svg+xml";
		}else if($document_ext == "png"){
			$document_ext  = "png";
		}else if($document_ext == "avif"){
			$document_ext  = "avif";
		}else{
			$document_ext  = "jpeg";
		}
	?>
	<meta property="og:image:type" content="image/<?php echo $document_ext;?>">
	<meta property="og:image" content="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&h=220">
	
	<?php
	}
	?>

  <title><?php echo $page_title; ?></title>
  <meta property="og:title" content="<?php echo $page_title; ?>">
  <meta property="og:description" content="<?php echo $page_title ?>">
  <link rel="icon" href="<?php echo URL; ?>assets/images/favicon.ico" />

  <!-- Google font -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


  <!-- bootstrap css -->
  <link id="rtl-link" rel="stylesheet" type="text/css" href="<?php echo URL; ?>assets/css/vendors/bootstrap.css">

  <!-- wow css -->
  <link rel="stylesheet" href="<?php echo URL; ?>assets/css/animate.min.css" />

  <!-- font-awesome css -->
  <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>assets/css/vendors/font-awesome.css">

  <!-- feather icon css -->
  <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>assets/css/vendors/feather-icon.css">

  <!-- slick css -->
  <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>assets/css/vendors/slick/slick.css">
  <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>assets/css/vendors/slick/slick-theme.css">

  <!-- Iconly css -->
  <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>assets/css/bulk-style.css">

  <!-- Template css -->
  <link id="color-link" rel="stylesheet" type="text/css" href="<?php echo URL; ?>assets/css/style.css">

  <script src="https://cdn.lordicon.com/lordicon.js"></script>
  <script src="<?php echo URL; ?>assets/js/jquery.min.js"></script>
	 <script type="text/javascript" src="<?php echo URL; ?>assets/js/controller.js"></script>
</head>

<body class="bg-effect">
<!--
  <div class="fullpage-loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
	-->
  <!-- Header Start -->
  <header class="pb-md-4 pb-0">
    <div class="header-top">
		<div class="container-fluid-lg">
			<div class="row">
				 <div class="col-xxl-3 d-xxl-block d-none">
                        <div class="top-left-header">
                            <i class="iconly-Location icli text-white"></i>
                            <span class="text-white">55 Phạm Văn Ngôn, Phường An Khánh, TP.Thủ Đức</span>
                        </div>
                </div>

				<div class="col-xxl-6 col-lg-9 d-lg-block d-none">
					<div class="header-offer">
						<div class="notification-slider">
							<div>
								<div class="timer-notification">
									<h6><?php echo $appSession->getLang()->find("Header timer notification");?>
									</h6>
								</div>
							</div>

							<div>
								<div class="timer-notification">
									<h6><?php echo $appSession->getLang()->find("Header timer notification 2");?>
									</h6>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<ul class="about-list right-nav-about">
						<li class="right-nav-list">
							<div class="dropdown theme-form-select">
								<button class="btn dropdown-toggle" type="button" id="select-language"
									data-bs-toggle="dropdown" aria-expanded="false">
									<img src="<?php echo URL;?>/assets/images/flags/<?php echo $lang_id;?>.png"
										class="img-fluid blur-up lazyload" alt="">
									<span><?php
											for($i =0; $i<count($langList); $i++)
											{
												if($langList[$i] == $lang_id){
													echo $appSession->getLang()->find($langList[$i]);
													break;
												}
											}
											?></span>
								</button>
								
								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="select-language">
									<?php
											for($i =0; $i<count($langList); $i++)
											{
												if($langList[$i] == $lang_id){
													continue;
												}
											
											?>
									<li>
										<a class="dropdown-item" href="<?php echo URL;?>lang/<?php echo $langList[$i];?>/<?php echo $page;?>/<?php echo $page_id;?>" >
											<img src="<?php echo URL;?>/assets/images/flags/<?php echo $langList[$i];?>.png"
												class="img-fluid blur-up lazyload" alt="">
											<span><?php echo $appSession->getLang()->find($langList[$i]);?></span>
										</a>
									</li>
									<?php
											}
											?>
									
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
    

    <div class="top-nav top-header sticky-header">
      <div class="container-fluid-lg">
        <div class="row">
          <div class="col-12">
            <div class="navbar-top">
              <button class="navbar-toggler d-xl-none d-inline navbar-menu-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#primaryMenu">
                <span class="navbar-toggler-icon">
                  <i class="fa-solid fa-bars"></i>
                </span>
              </button>
              <a href="<?php echo URL; ?>" class="web-logo nav-logo">
                <img src="<?php echo URL; ?>assets/images/logocty-npg.png" class="img-fluid blur-up lazyload" alt="">
              </a>
              <div class="middle-box">
          
                <div class="search-box">
                  <div class="input-group">
                    <input id="editsearch" type="text" class="form-control" placeholder="<?php echo $appSession->getLang()->find("Search for Products"); ?>" aria-label="Recipient's username" aria-describedby="button-addon2" value="<?php echo $search; ?>">
                    <button class="btn" type="button" id="button-addon2" onClick="doSearch('editsearch')">
                      <i data-feather="search"></i>
                    </button>
                  </div>
                </div>
              </div>
              <div class="rightside-box">
                <div class="search-full">
                  <div class="input-group">
                    <span class="input-group-text">
                      <i data-feather="search" class="font-light"></i>
                    </span>
                    <input id="editsearchSideMenu" type="text" class="form-control search-type" placeholder="<?php echo $appSession->getLang()->find("Search for Products"); ?>">
                    <span class="input-group-text close-search">
                      <i data-feather="x" class="font-light"></i>
                    </span>
                  </div>
                </div>
                <ul class="right-side-menu">
                  <li class="right-side">
                    <div class="delivery-login-box">
                      <div class="delivery-icon">
                        <div class="search-box">
                          <i data-feather="search"></i>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="right-side">
                    <a href="<?php echo URL; ?>contact" class="delivery-login-box">
                      <div class="delivery-icon">
                        <i data-feather="phone-call"></i> <?php echo CONTACT_TEL; ?>
                      </div>
                     
                    </a>
                  </li>
                  <li class="right-side">
                    <div class="onhover-dropdown header-badge">
                      <button type="button" class="btn p-0 position-relative header-wishlist">
                        <i id="cartMenuIcon" data-feather="shopping-cart"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge" id="itemCountCard">0
                          <span class="visually-hidden">unread messages</span>
                        </span>
                      </button>
                      <div class="onhover-div">

                        <div id="cartMenuList"> </div>

                        <div class="button-group">
                          <a href="<?php echo URL; ?>cart" class="btn btn-sm cart-button">
                            <?php echo $appSession->getLang()->find("Cart"); ?></a>
                          <a href="<?php echo URL; ?>checkout" class="btn btn-sm cart-button theme-bg-color text-white">
                            <?php echo $appSession->getLang()->find("Check Out"); ?>
                          </a>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="right-side onhover-dropdown">
                    <div class="delivery-login-box">
                      <div class=" delivery-icon">
                        <?php if ($appSession->getUserInfo()->getId() == "") {
                          echo '<i data-feather="user"></i>';
                        } else {
                          echo $appSession->getConfig()->getProperty("user_name");
                        } ?>
                      </div>
                    </div>
                    <div class="onhover-div onhover-div-login">
                      <ul class="user-box-name">

                     
                 

                        <?php if ($appSession->getUserInfo()->getId() == "") {                        ?>
                          <li class="product-box-contain">
                            <a href="<?php echo URL; ?>signin"><?php echo $appSession->getLang()->find("Sign In"); ?></a>
                          </li>
                          <li class="product-box-contain">
                            <a href="<?php echo URL; ?>signup"><?php echo $appSession->getLang()->find("Sign Up"); ?></a>
                          </li>
						 
                        <?php } else { ?>
                          <li class="product-box-contain"><a href="<?php echo URL; ?>account"><?php echo $appSession->getLang()->find("Profile"); ?></a></li>
                        <?php } ?>
                        <?php if ($appSession->getUserInfo()->getId() != "") { ?>
                          <li class="product-box-contain"><a href="javascript:doLogout()"><?php echo $appSession->getLang()->find("Sign Out"); ?></a></li>
                        <?php } ?>
                      </ul>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid-lg">
      <div class="row">
        <div class="col-12">
          <div class="header-nav">
            <div class="header-nav-middle">
              <div class="main-nav navbar navbar-expand-xl navbar-light navbar-sticky">
                <div class="offcanvas offcanvas-collapse order-xl-2" id="primaryMenu">
                  <div class="offcanvas-header navbar-shadow">
                    <h5><?php echo META_TITLE;?></h5>
                    <button class="btn-close lead" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                    <ul class="navbar-nav">
                      <li class="nav-item">
                        <a class="nav-link" href="<?php echo URL;?>"><?php echo $appSession->getLang()->find("Home"); ?></a>
                    
                      </li>
					  
						
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="javascript:void(0)"
								data-bs-toggle="dropdown"><?php echo $appSession->getLang()->find("Shop"); ?><i class="fas fa-tv-music"></i></a>
							<ul class="dropdown-menu">
								<?php 
								for($i =0; $i<$dt_company->getRowCount(); $i++)
								{
									$company_code = $dt_company->getString($i, "code");
									$company_name = $dt_company->getString($i, "commercial_name");
									$company_id = $dt_company->getString($i, "company_id");
									$company_name = ucwords(mb_strtolower($company_name));
								?>
								<li>
									<a class="dropdown-item" href="<?php echo URL;?>shop/<?php echo $appSession->getTool()->validUrl($company_name); ?>/<?php echo $company_code;?>"><?php echo $company_name;?></a>
								</li>
								<?php
								}
								?>
								<li>
									<a class="dropdown-item" href="<?php echo URL;?>shop_grid/"><?php echo $appSession->getLang()->find("More");?></a>
								</li>
								<li>
									<a class="dropdown-item" href="<?php echo URL;?>shop_become/"><?php echo $appSession->getLang()->find("Become a Shop");?></a>
								</li>
							
							</ul>
						</li>	
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="javascript:void(0)"
								data-bs-toggle="dropdown"><?php echo $appSession->getLang()->find("Product"); ?></a>
								<ul class="dropdown-menu">
									<?php
							for ($i = 0; $i < $dt_category->getRowCount(); $i++)
							{
								if($dt_category->getString($i, "parent_id") != "")
								{
									continue;
								}
								
								$parent_id1 = $dt_category->getString($i, "id");
								$category_code = $dt_category->getString($i, "code");
								$category_name = $dt_category->getString($i, "name_lg");

								if ($category_name == "") {
									$category_name = $dt_category->getString($i, "name");
								}
							   $hasItem = false;
							   for ($n = 0; $n < $dt_category->getRowCount(); $n++) 
								{
									$parent_id2 = $dt_category->getString($n, "parent_id");
									if ($parent_id2 == $parent_id1) {
										$hasItem = true;
										break;
									}
								}
							?>
								<?php 
								if($hasItem == true)
								{ 
									
								?>
									
									<li class="sub-dropdown-hover">
										<a href="javascript:void(0)" class="dropdown-item"><?php echo $category_name;?></a>
										<?php
										
									    for ($n = 0; $n < $dt_category->getRowCount(); $n++) 
										{
											$parent_id2 = $dt_category->getString($n, "parent_id");
											if ($parent_id2 != $parent_id1) {
												continue;
											}
											$category_code2 = $dt_category->getString($n, "code");
											$category_name2 = $dt_category->getString($n, "name_lg");

											if ($category_name2 == "") {
												$category_name2 = $dt_category->getString($n, "name");
											}
											$hasItem = false;
										    for ($n2 = 0; $n2 < $dt_category->getRowCount(); $n2++) 
											{
												$parent_id3 = $dt_category->getString($n2, "parent_id");
												if ($parent_id3 == $parent_id1) {
													$hasItem = true;
													break;
												}
											}
											?>
											<?php
											if($hasItem == true)
											{
											?>
											 <ul class="sub-menu">
												<?php
												 for ($n2 = 0; $n2 < $dt_category->getRowCount(); $n2++) 
												{
													$parent_id3 = $dt_category->getString($n2, "parent_id");
													if ($parent_id3 != $parent_id1) {
														continue;
													}
													$category_code3 = $dt_category->getString($n2, "code");
													$category_name3 = $dt_category->getString($n2, "name_lg");

													if ($category_name3 == "") {
														$category_name3 = $dt_category->getString($n2, "name");
													}
													
											?>
												<li>
													<a href="<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name3); ?>/c-<?php echo $category_code3; ?>"><?php echo $category_name3;?></a>
												</li>
												<?php
												}
												?>
											 </ul>
											 <?php
											}else{
												?>
												<li>
													<a href="<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name2); ?>/c-<?php echo $category_code2; ?>"><?php echo $category_name2;?></a>
												</li>
												<?php
											}?>
											<?php
										}
										?>
									</li>
								
								<?php
								}else{
									?>
									<li>
										<a class="dropdown-item" href="<?php echo URL;?><?php echo $appSession->getTool()->validUrl($category_name); ?>/c-<?php echo $category_code; ?>"><?php echo $category_name;?></a>
									</li>
									<?php
								}
								?>
							<?php
							}
							?>

								</ul>
						</li>
						
                       <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown"><?php echo $appSession->getLang()->find("Rules"); ?></a>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="<?php echo URL; ?>sale_payment">Chính sách thanh toán</a></li>
                          <li><a class="dropdown-item" href="<?php echo URL; ?>sale_shipping">Chính sách vận chuyển</a></li>
                          <li><a class="dropdown-item" href="<?php echo URL; ?>sale_refund">Chính sách kiểm hàng và hủy, đổi trả</a></li>
                          <li><a class="dropdown-item" href="<?php echo URL; ?>sale_policy">Chính sách bảo mật thông tin</a></li>
                         
                        </ul>
                      </li>
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown"><?php echo $appSession->getLang()->find("About Us"); ?></a>
                        <ul class="dropdown-menu">
                         
                          <li><a class="dropdown-item" href="<?php echo URL; ?>about"><?php echo $appSession->getLang()->find("Company profile"); ?></a></li>
                          <li><a class="dropdown-item" href="<?php echo URL; ?>contact"><?php echo $appSession->getLang()->find("Contact"); ?></a></li>
                         
                        </ul>
                      </li>
                      <?php if (count($post_category) > 0) { ?>
                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown"><?php echo $appSession->getLang()->find("News"); ?></a>
                          <ul class="dropdown-menu">
                            <?php for ($i = 0; $i < count($post_category); $i++) { ?>
                              <li><a class="dropdown-item" href="<?php echo URL; ?>blog/<?php echo $appSession->getTool()->validUrl($post_category[$i][1]); ?>/<?php echo $post_category[$i][0]; ?>"><?php echo $post_category[$i][1]; ?></a></li>
                            <?php } ?>
                          </ul>
                        </li>
                      <?php } ?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
			<div class="header-nav-right">
				<a href="<?php echo URL;?>shop_become/" class="btn" style="color:red; border-radius:5px">
					<span><?php echo $appSession->getLang()->find("Become a Shop");?></span>
				</a>
			</div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Header End -->

  <!-- mobile fix menu start -->
  <div class="mobile-menu d-md-none d-block mobile-cart">
    <ul>
      <li class="active">
        <a href="<?php echo URL; ?>">
          <i class="iconly-Home icli"></i>
          <span><?php echo $appSession->getLang()->find("Home"); ?></span>
        </a>
      </li>
      <li class="mobile-category">
        <a href="javascript:void(0)">
          <i class="iconly-Category icli js-link"></i>
          <span><?php echo $appSession->getLang()->find("Products"); ?></span>
        </a>
      </li>
      <li>
        <a href="<?php echo URL; ?>/cart">
          <i class="iconly-Buy icli fly-cate"></i>
          <span><?php echo $appSession->getLang()->find("Cart"); ?></span>
        </a>
      </li>
    </ul>
 </div>
  <!-- mobile fix menu end -->

  <!-- Home Section Start -->
  <?php
 
  include(ABSPATH . "addons/" . $page . '/index.php');
  ?>
	<br>
  <!-- Footer Section Start -->
  <footer class="section-t-space">
      <div class="container-fluid-lg">
      <div class="main-footer section-b-space section-t-space" style="border-top: 0px;">
        <div class="row g-md-4 g-3">
          <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="footer-logo">
				<div class="footer-title">
				  <h4>CÔNG TY CỔ PHẦN TẬP ĐOÀN DƯỢC NANO</h4>
				</div>
				<br>
              <div class="theme-logo">
                <a href="<?php echo URL;?>">
                  <img src="<?php echo URL; ?>assets/images/logo-png.png" class="blur-up lazyload" alt="">
                </a>
              </div>
              <div class="footer-logo-contain">
                <ul class="address">
					<li>
                    <i class="fa fa-address-card"></i>
                    <a href="<?php echo URL;?>">Giấy chứng nhận ĐKKD số: 0318120242 Do Sở KHĐT TPHCM cấp ngày 23/10/2023.</a>
                  </li>
                  <li>
                    <i class="fa-solid fa-location-dot"></i>
                    <a href="https://www.google.com/maps/place/55+%C4%90.+A,+B%C3%ACnh+Kh%C3%A1nh,+Th%E1%BB%A7+%C4%90%E1%BB%A9c,+H%E1%BB%93+Ch%C3%AD+Minh+700000,+Vietnam/@10.7863297,106.7352585,17z/data=!3m1!4b1!4m6!3m5!1s0x317525e28f4c9f73:0x90b4590abdaac65c!8m2!3d10.7863244!4d106.7378334!16s%2Fg%2F11y30xm8v6?entry=ttu" target="_blank">Số 55 Phạm Văn Ngôn, Khu tái định cư 1,89ha, Khu phố 7, P.An Khánh,TP.Thủ Đức,TP.Hồ Chí Minh</a>
                  </li>
                  <li>
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:info@pharmacygroup.vn">Email: info@pharmacygroup.vn</a>
                  </li>
				  <li>
                    <i class="fa fa-phone"></i>
                    <a href="tel:0785873618">Tổng đài CSKH: 0785 873 618</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-xl-4 col-sm-3">
            <div class="footer-title">
              <h4><?php echo $appSession->getLang()->find("Policy"); ?></h4>
            </div>
            <div class="footer-contain">
              <ul>
                <li>
                  <a href="<?php echo URL; ?>sale_payment" class="text-content"><?php echo $appSession->getLang()->find("Payment policy"); ?></a>
                </li>
                <li>
                  <a href="<?php echo URL; ?>sale_shipping" class="text-content"><?php echo $appSession->getLang()->find("Shipping policy"); ?></a>
                </li>
                <li>
                  <a href="<?php echo URL; ?>sale_refund" class="text-content"><?php echo $appSession->getLang()->find("Refund and return policy"); ?></a>
                </li>
                <li>
                  <a href="<?php echo URL; ?>sale_policy" class="text-content"><?php echo $appSession->getLang()->find("Privacy policy"); ?></a>
                </li>

              </ul>
            </div>
          </div>
          <div class="col-xl-2 col-sm-3">
            <div class="footer-title">
              <h4><?php echo $appSession->getLang()->find("Useful Links"); ?></h4>
            </div>
            <div class="footer-contain">
              <ul>
               
                <li>
                  <a href="<?php echo URL; ?>" class="text-content"><?php echo $appSession->getLang()->find("Home"); ?></a>
                </li>
                <li>
                  <a href="<?php echo URL; ?>shop" class="text-content"><?php echo $appSession->getLang()->find("Shop"); ?></a>
                </li>
               
				<li>
                  <a href="<?php echo URL; ?>blog/diet-food/4" class="text-content"><?php echo $appSession->getLang()->find("News"); ?></a>
                </li>
				<li>
                  <a href="<?php echo URL; ?>faq" class="text-content"><?php echo $appSession->getLang()->find("FAQ"); ?></a>
                </li>
				<li>
                  <a href="<?php echo URL; ?>contract" class="text-content"><?php echo $appSession->getLang()->find("Contact"); ?></a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
				<div class="footer-title">
					<h4><?php echo $appSession->getLang()->find("Categories"); ?></h4>
				</div>

				<div class="footer-contain">
					<ul>
						<li>
							<a href="<?php echo URL;?>duoc-my-pham/c-G010" class="text-content">Hồng Sâm Hàn Quốc</a>
						</li>
						<li>
							<a href="<?php echo URL;?>ho-tro-lam-dep/c-G012" class="text-content">Tinh dầu thiên nhiên</a>
						</li>
						<li>
							<a href="<?php echo URL;?>ho-tro-dieu-tri/c-G011" class="text-content">Thực phẩm bảo vệ sức khỏe</a>
						</li>
						<li>
							<a href="<?php echo URL;?>vitamin-&-khoang-chat/c-G001" class="text-content">Thực phẩm khác</a>
						</li>
						
					</ul>
				</div>
			</div>
			
        </div>
      </div>
      <div class="sub-footer section-small-space">
        <div class="reserve">
          <h6 class="text-content">Copyright 2024 © Công ty Cổ Phần Tập Đoàn Dược Nano</h6>
        </div>
        <div class="payment">
          <img src="assets/images/payment/visa.png" class="blur-up lazyload" alt="">
          <img src="assets/images/payment/master.png" class="blur-up lazyload" alt="">
          <img src="assets/images/payment/paypal.png" class="blur-up lazyload" alt="">
          <img src="assets/images/payment/discover.png" class="blur-up lazyload" alt="">
        </div>
        <div class="social-link">
          <h6 class="text-content"><?php echo $appSession->getLang()->find("Stay connected"); ?>:</h6>
          <ul>
            <li>
              <a href="https://www.facebook.com/sieuthitpcn" target="_blank">
                <i class="fa-brands fa-facebook-f"></i>
              </a>
            </li>
            <li>
              <a href="https://zalo.me/0785873618" target="_blank">
                <i class="fa-brands">Zalo</i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
  <!-- Footer Section End -->

  <!-- Search Modal Start -->
  <div class="modal location-modal fade theme-modal" id="locationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><?php echo $appSession->getLang()->find("Select category"); ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="location-list">
            <ul class="location-select custom-height" id="editsearch-category" value="">
              <li>
                <a href="javascript:void(0);">
                  <button class="btn bg-transparent btn-block w-100 h-100" onclick="setCategory('', '<?php echo $appSession->getLang()->find("All Categories"); ?>')" data-bs-dismiss="modal">
                    <h6><?php echo $appSession->getLang()->find("All Categories"); ?></h6>
                  </button>
                </a>
              </li>
              <?php
              for ($i = 0; $i < $dt_category->getRowCount(); $i++) {
                if ($dt_category->getString($i, "parent_id") == "") {
                  $category_code = $dt_category->getString($i, "code");
                  $category_name = $dt_category->getString($i, "name_lg");
                  if ($category_name == "") {
                    $category_name = $dt_category->getString($i, "name");
                  }
              ?>
                  <li>
                    <a href="javascript:void(0);">
                      <button class="btn bg-transparent btn-block w-100 h-100" onclick="setCategory('<?php echo $category_code; ?>', '<?php echo $appSession->getTool()->validUrl($category_name); ?>')" data-bs-dismiss="modal">
                        <h6><?php echo $category_name; ?></h6>
                      </button>
                    </a>
                  </li>
              <?php
                }
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Search Modal End -->

  <!-- Tap to top start -->
  <div class="theme-option">
    <div class="back-to-top">
      <a id="back-to-top" href="#">
        <i class="fas fa-chevron-up"></i>
      </a>
    </div>
  </div>
  <!-- Tap to top end -->

  <!-- Bg overlay Start -->
  <div class="bg-overlay"></div>
  <!-- Bg overlay End -->
<div class="modal fade theme-modal view-modal" id="pnFullDialog" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body" id="pnFullDialogContent">
                    
                </div>
            </div>
        </div>
    </div>
  
  <script src="<?php echo URL; ?>assets/js/jquery-ui.min.js"></script>

  <script src="<?php echo URL; ?>assets/js/bootstrap/bootstrap.bundle.min.js"></script>
  <script src="<?php echo URL; ?>assets/js/bootstrap/bootstrap-notify.min.js"></script>
  <script src="<?php echo URL; ?>assets/js/bootstrap/popper.min.js"></script>

  <script src="<?php echo URL; ?>assets/js/feather/feather.min.js"></script>
  <script src="<?php echo URL; ?>assets/js/feather/feather-icon.js"></script>

  <script src="<?php echo URL; ?>assets/js/lazysizes.min.js"></script>

  <script src="<?php echo URL; ?>assets/js/slick/slick.js"></script>
  <script src="<?php echo URL; ?>assets/js/slick/custom_slick.js"></script>

  <script src="<?php echo URL; ?>assets/js/auto-height.js"></script>

  <script src="<?php echo URL; ?>assets/js/timer1.js"></script>

  <script src="<?php echo URL; ?>assets/js/fly-cart.js"></script>

  <script src="<?php echo URL; ?>assets/js/wow.min.js"></script>
  <script src="<?php echo URL; ?>assets/js/custom-wow.js"></script>

  <script src="<?php echo URL; ?>assets/js/script.js"></script>

  <script src="<?php echo URL; ?>assets/js/filter-sidebar.js"></script>


 
  <script type="text/javascript" src="<?php echo URL; ?>assets/js/tool.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>assets/js/sha1.js"></script>

<script>
  var BASE_URL = '<?php echo URL; ?>';

  function doLogout() {
    loadPage('contentView', BASE_URL + "api/account/?ac=logout", function(status, message) {
      if (status == 0) {
        window.location.href = BASE_URL;
      } else {
        console.log(message);
      }
    }, true);
  }

  function loadCard() {
    var _url = BASE_URL + "api/sale_action/?ac=viewCardCount";

    loadPage('contentView', _url, function(status, message) {
      if (status === 0) {
        var index = message.indexOf(';');
        if (index !== -1) {
          var itemCount = message.substring(0, index);

          var icc = document.getElementById('itemCountCard');
          if (icc) {
            icc.innerHTML = itemCount;
          }
        }
      }
    }, true);
  }


  function loadCardContent() {
    var _url = BASE_URL + "addons/card/";
    loadPage('sitebar-cart', _url, function(status, message) {}, false);
  }

  function addProduct(product_id, currency_id, unit_id, attribute_id, quantity, unit_price, second_unit_id, factor, description, company_id, price_id, type_id, oncompleted) {
    if (factor == "" || factor == "0") {
      factor = 1;
    }
    var _url = BASE_URL + "api/sale_action/?ac=addProduct";
    _url = _url + "&product_id=" + product_id;
    _url = _url + "&currency_id=" + currency_id;
    _url = _url + "&unit_id=" + unit_id;
    _url = _url + "&attribute_id=" + attribute_id;
    _url = _url + "&quantity=" + quantity;
    _url = _url + "&unit_price=" + encodeURIComponent(unit_price);
    _url = _url + "&second_unit_id=" + second_unit_id;
    _url = _url + "&factor=" + factor;
    _url = _url + "&description=" + encodeURIComponent(description);
    _url = _url + "&company_id=" + company_id;
    _url = _url + "&rel_id=" + price_id;
    _url = _url + "&type_id=" + type_id;

    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        if (message.length == 36) {
          if (oncompleted != null) {
            oncompleted(status, message);
          }
          loadCardContent();
        } else {
          console.log(message);
        }
      }
    }, true);
  }

  function removeCard(id, oncompleted = null) {
    if (!confirm('<?php echo $appSession->getLang()->find("Are you sure to remove?"); ?>')) {
      return;
    }

    var url = BASE_URL + "api/sale_action/?ac=removeCard&id=" + id;

    loadPage('contentView', url, function(status, message) {
      if (status == 0) {
        if (message.length == 36) {
          if (oncompleted != null) {
            oncompleted(status, message);
          }
        } else {
          console.log(message);
        }
      }
    }, true);
  }

  function updateCard(id, quantity, oncompleted) {
    var _url = BASE_URL + "api/product/?ac=sale_product_update_quantity";
    _url = _url + "&id=" + id + "&quantity=" + quantity;

    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        if (message.length == 36) {
          if (oncompleted != null) {
            oncompleted(status, message);
          }
        } else {
          console.log(message);
        }
      }
    }, true);
  }

  // INFO: part of contact
  function sendEmailContact(name, phone, email, title, message, oncompleted) {
    var _url = BASE_URL + "api/contact/?ac=send";
    _url = _url + "&name=" + encodeURIComponent(name);
    _url = _url + "&phone=" + encodeURIComponent(phone);
    _url = _url + "&email=" + encodeURIComponent(email);
    _url = _url + "&title=" + encodeURIComponent(title);
    _url = _url + "&message=" + encodeURIComponent(message);

    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        if (message.indexOf("OK") != -1) {
          if (oncompleted != null) {
            oncompleted(status, message);
          }
        } else {
          console.log(message);
        }
      }
    }, true);
  }

  function openPopup(_url, oncompleted) {
	var ctr = document.getElementById('pnFullDialogContent');
	
    loadPage('pnFullDialogContent', _url, function(status, message) {
	
      if (status == 0) {
        $("#pnFullDialog").modal('show');
        if (oncompleted != null) {
          oncompleted(status, message);
        }
      }
    }, false);
  }
  function closePopup(){
	   document.getElementById('pnFullDialog').click();
  }

  function loadCart() {
    loadPage('cartMenuList', BASE_URL + "addons/index_cart/", function(status, message) {
      loadCard();
    }, false)
  }

  loadCart();

  out = document.getElementById("cartMenuIcon")

  out.addEventListener('mouseenter', () => {
    loadCart();
  })

  // INFO: search module index page
  var category

  function setCategory(code, name) {
    if (code !== "") {
      document.getElementById("editsearch-category").setAttribute("value", String(name + '/c-' + code));
    }

    document.getElementById("editsearch-category-name").innerHTML = name;
  }

  document.querySelector("#editsearch").addEventListener("keyup", event => {
    if (event.key !== "Enter") return;
    doSearch('editsearch');
    event.preventDefault();
  });

  document.querySelector("#editsearchSideMenu").addEventListener("keyup", event => {
    if (event.key !== "Enter") return;
    doSearch('editsearchSideMenu');
    event.preventDefault();
  });

  function doSearch(elementID) {
   
    var product = encodeURIComponent(document.getElementById(String(elementID)).value);

	window.location =  "<?php echo URL;?>category/?search=" +encodeURIComponent( product);
      return;
  }

  $(document).ready(function() {
    var subCategoryArr = document.getElementsByClassName('subCategory');

    for (let i = 0; i < subCategoryArr.length; i++) {
      var scl = subCategoryArr[i].getElementsByTagName('li').length;

      if (scl === 0) {
        document.getElementsByClassName('onhover-category-box')[i].style.display = 'none';
      }
    }
	
	$(".addcart-button").click(function () {
		 $(this).next().addClass("open");
		 $(".add-to-cart-box .qty-input").val('1');
	 });

	 $('.add-to-cart-box').on('click', function () {
		 var $qty = $(this).siblings(".qty-input");
		 var currentVal = parseFloat($qty.val());
		 if (!isNaN(currentVal)) {
			 $qty.val(currentVal + 1);
		 }
	 });

	 $('.qty-left-minus').on('click', function () {
		 var $qty = $(this).siblings(".qty-input");
		 var _val = $($qty).val();
		 if (_val == '1') {
			 var _removeCls = $(this).parents('.cart_qty');
			 $(_removeCls).removeClass("open");
		 }
		 var currentVal = parseFloat($qty.val());
		 if (!isNaN(currentVal) && currentVal > 0) {
			 $qty.val(currentVal - 1);
		 }
	 });

	 $('.qty-right-plus').click(function () {
		 if ($(this).prev().val() < 9) {
			 $(this).prev().val(+$(this).prev().val() + 1);
		 }
	 });
 
  });
  
  
</script>
</body>

</html>


