 <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Cart</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Cart</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Cart Section Start -->
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

<section class="cart-section section-b-space">
  <div class="container-fluid-lg">
    <div class="row g-sm-5 g-3">
      <div class="col-xxl-9">
        <div class="cart-table">
          <div class="table-responsive-xl">
            <table class="table">
              <tbody>
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

                  <tr class="product-box-contain">
                    <td class="product-detail">
                      <div class="product border-0">
                        <a href="javascript:void(0)" class="product-image">
                          <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>" class="img-fluid blur-up lazyload" alt="">
                        </a>
                        <div class="product-detail">
                          <ul>
                            <li class="name">
                              <a href="javascript:void(0)"><?php echo $name; ?></a>
                            </li>
                            <li class="text-content"><span class="text-title"><?php echo $appSession->getLang()->find("Quantity"); ?>:</span> <?php echo $quantity; ?></li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td class="price">
                      <h4 class="table-title text-content"><?php echo $appSession->getLang()->find("Price"); ?></h4>
                      <h5><?php echo $appSession->getCurrency()->format($currency_id, $unit_price); ?></h5>
                    </td>
                    <td class="quantity">
                      <h4 class="table-title text-content"><?php echo $appSession->getLang()->find("Quantity"); ?></h4>
                      <div class="quantity-price">
                        <div class="cart_qty">
                          <div class="input-group">
                            <button type="button" class="btn qty-left-minus" data-type="minus" data-field="" onClick="javascript:minus('<?php echo $id ?>')">
                              <i class="fa fa-minus ms-0" aria-hidden="true"></i>
                            </button>
                            <input disabled class="form-control input-number qty-input" type="text" name="quantity" value="<?php echo $quantity ?>" id="qty<?php echo $id ?>">
                            <button type="button" class="btn qty-right-plus" data-type="plus" data-field="" onClick="javascript:plus('<?php echo $id ?>')">
                              <i class="fa fa-plus ms-0" aria-hidden="true"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="subtotal">
                      <h4 class="table-title text-content"><?php echo $appSession->getLang()->find("Cart total"); ?></h4>
                      <h5><?php echo $appSession->getCurrency()->format($currency_id, $unit_price * $quantity); ?></h5>
                    </td>
                    <td class="save-remove">
                      <h4 class="table-title text-content">Action</h4>
                      <a class="remove close_button" href="javascript:removeCard('<?php echo $id; ?>'); window.location.reload();"><?php echo $appSession->getLang()->find("Remove"); ?></a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-xxl-3">
        <div class="summery-box p-sticky">
          <div class="summery-header">
            <h3><?php echo $appSession->getLang()->find("Cart total"); ?></h3>
          </div>

          <!-- TODO: probably: something if zero srvs -->
          <div class="summery-contain">
            <ul>
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
                <li>
                  <h4><?php echo $service_name; ?></h4>
                  <h4 class="price"><?php echo $appSession->getCurrency()->format($appSession->getConfig()->getProperty("currency_id"), $a); ?></h4>
                </li>
              <?php
              }
              ?>
            </ul>
          </div>
          <ul class="summery-total">
            <li class="list-total border-top-0">
              <h4><?php echo $appSession->getLang()->find("Total"); ?></h4>
              <h4 class="price theme-color"><?php echo $appSession->getCurrency()->format($company_currency_id, $total); ?></h4>
            </li>
          </ul>
          <div class="button-group cart-button">
            <ul>
              <li>
                <button onclick="location.href = '<?php echo URL; ?>checkout';" class="btn btn-animation proceed-btn fw-bold"><?php echo $appSession->getLang()->find("Check Out"); ?></button>
              </li>
              <li>
                <button onclick="location.href = '<?php echo URL; ?>';" class="btn btn-light shopping-button text-dark">
                  <i class="fa-solid fa-arrow-left-long"></i><?php echo $appSession->getLang()->find("Return To Shopping"); ?></button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Cart Section End -->

<script>
  function plus(id) {
    var ctr = document.getElementById('qty' + id);
    var count = parseFloat(ctr.value);

    javascript: updateCard(id, parseFloat(count + 1),
      function(status, message) {
        document.location.reload();
      })
  }

  function minus(id) {
    var ctr = document.getElementById('qty' + id);
    var count = parseFloat(ctr.value);
    if (count > 1) {

      javascript: updateCard(id, parseFloat(count - 1),
        function(status, message) {
          document.location.reload();
        })
    }
  }
</script>
