<?php
require_once(ABSPATH . 'api/Product.php');

$page = "home";
$lang = "vi";
$page_id = '';

if ($uri != '/' && $uri != '') {
  $items = explode("/", $uri);
  if (count($items) == 1) {
    $page = $items[0];
  }
  if (count($items) > 0) {
    $page_id = $items[count($items) - 1];
  }
  if ($page_id == "lang") {

    if (count($items) > 2) {
    }
    $page_id = $items[0];
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
      $search = $v;
    }
  }
}

$langList = ["vi", "en", "ru", "cn", "jp", "kr"];
$lang_id = "vi";
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

$product = new Product($appSession);
$dt_category = $product->categoryList();

require_once(ABSPATH . 'api/Sale.php');
$sale = new Sale($appSession);
$productList = $sale->productList();

$sale_id = $sale->findSaleId();
?>

<ul class="cart-list" style="display: block;">
  <?php
  $total = 0;
  $company_currency_id = $appSession->getConfig()->getProperty("currency_id");

  for ($i = 0; $i < $productList->getRowCount(); $i++) {
    $id = $productList->getString($i, "id");
    $code = $productList->getString($i, "code");
    $document_id = $productList->getString($i, "document_id");
    $name = $productList->getString($i, "name_lg");
    $currency_id = $productList->getString($i, "currency_id");
    if ($name == "") {
      $name = $productList->getString($i, "name");
    }
    $old_price = $productList->getFloat(0, "old_price");
    $unit_price = $productList->getFloat($i, "unit_price");
    $quantity = $productList->getFloat($i, "quantity");
    $amount = $unit_price * $quantity;

    $attribute_category_name = $productList->getFloat($i, "attribute_category_name");
    $attribute_name = $productList->getFloat($i, "attribute_name");

    $unit_name = $productList->getFloat($i, "unit_name");
    $currency_code = $productList->getFloat($i, "currency_code");
    $total = $total + $amount;
  ?>
    <li class="product-box-contain" style="display: block;">
      <div class="drop-cart">
        <a href="javascript:void(0)" class="drop-image">
          <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&w=190&h=120" class="blur-up lazyload" alt="">
        </a>

        <div class="drop-contain">
          <a href="javascript:void(0)">
            <h5><?php echo $name; ?></h5>
          </a>
          <h6><span><?php echo $quantity; ?> x </span><?php echo $appSession->getCurrency()->format($currency_id, $unit_price); ?></h6>
        </div>
      </div>
    </li>
  <?php } ?>
</ul>
<div class="price-box">
  <?php
  $sql = "SELECT d1.percent, d1.value, d1.category_id, d1.operator, d2.name";
  $sql = $sql . " FROM account_service_line_local d1";
  $sql = $sql . " LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id)";
  $sql = $sql . " WHERE d1.rel_id='" . $sale_id . "' AND d1.status =0 ORDER BY d1.sequence ASC";
  $msg->add("query", $sql);

  $serviceList = $appSession->getTier()->getArray($msg);
  $amount = $total;

  for ($i = 0; $i < count($serviceList); $i++) {
    $a = ($total * floatval($serviceList[$i][0])) + floatval($serviceList[$i][1]);
    if ($serviceList[$i][3] == "+") {
      $amount =  $amount + $a;
      $total =  $total + $a;
    } else if ($serviceList[$i][3] == "-") {
      $amount =  $amount -  $a;
      $total =  $total - $a;
    } else if ($serviceList[$i][3] == "*") {
      $amount =  $amount *  $a;
      $total =  $total * $a;
    } else if ($serviceList[$i][3] == "/") {
      $amount =  $amount /  $a;
      $total =  $total / $a;
    }
    $service_name = $serviceList[$i][4];
  ?>
    <h5><?php echo $service_name; ?>: </h5>
    <h4 class="theme-color fw-bold"><?php echo $appSession->getCurrency()->format($appSession->getConfig()->getProperty("currency_id"), $a); ?></h4>
  <?php
  }
  ?>
</div>
<div class="price-box">
  <h5><?php echo $appSession->getLang()->find("Total"); ?>:</h5>
  <h4 class="theme-color fw-bold"><?php echo $appSession->getCurrency()->format($company_currency_id, $total); ?></h4>
</div>
