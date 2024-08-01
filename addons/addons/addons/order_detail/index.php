<?php
require_once(ABSPATH . 'api/Sale.php');
include(ABSPATH . 'app/lang/' . $appSession->getConfig()->getProperty("lang_id") . '.php');
foreach ($langs as $key => $item) {
  $appSession->getLang()->setProperty($key, $item);
}
$msg = $appSession->getTier()->createMessage();

$sale_id = "";
if (isset($_REQUEST['id'])) {
  $sale_id = $_REQUEST['id'];
}
$user_id = $appSession->getUserInfo()->getId();
$company_currency_id = $appSession->getConfig()->getProperty("currency_id");

$sql = "SELECT d1.id, d1.order_no, d1.order_date, (SELECT COUNT(id) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS items, (SELECT SUM(quantity * unit_price) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS amount, (SELECT name FROM res_status_line WHERE rel_id=d1.id AND status =0 ORDER BY create_date DESC LIMIT 1) AS status_name, d1.status, d2.commercial_name FROM sale_local d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id = d2.id) WHERE d1.id='" . $sale_id . "'";

$msg->add("query", $sql);
$dt = $appSession->getTier()->getTable($msg);
if ($dt->getRowCount() > 0) {
  $order_no = $dt->getString(0, "order_no");
  $order_date = $dt->getString(0, "order_date");
  $status_name = $dt->getString(0, "status_name");
  $total = $appSession->getTool()->toDouble($dt->getString(0, "amount"));
  $sale = new Sale($appSession);
  $dt_product = $sale->productListSaleId($sale_id);
?>
  <div class="track-order-item bg-color-white">
    <div class="d-flex justify-content-between track-number-link align-items-center">
      <div>
        <h6 class="order-number">#<?php echo $order_no; ?> - <?php echo $status_name; ?></h6>
        <p class="date"><?php echo $order_date; ?></p>
        <p class="price"><?php echo $appSession->getCurrency()->format($company_currency_id, $total); ?></p>
      </div>
    </div>
    <div class="order-details">
      <div class="order-details-head">
        <h6><?php echo $appSession->getLang()->find("Order Details"); ?></h6>
      </div>
      <div class="order-details-container d-none d-md-block">
        <?php
        for ($j = 0; $j < $dt_product->getRowCount(); $j++) {
          $name = $dt_product->getString($j, "name_lg");
          if ($name == "") {
            $name = $dt_product->getString($j, "name");
          }
          $document_id = $dt_product->getString($j, "document_id");
          $currency_id = $dt_product->getString($j, "currency_id");
          $unit_name = $dt_product->getString($j, "unit_name");
          $quantity = $appSession->getTool()->toDouble($dt_product->getString($j, "quantity"));
          $unit_price = $appSession->getTool()->toDouble($dt_product->getString($j, "unit_price"));
          $amount = $quantity * $unit_price;

        ?>
          <div class="order-details-item d-sm-flex flex-wrap text-center text-sm-left align-items-center justify-content-between">
            <div class="thumb d-sm-flex flex-wrap align-items-center">
              <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&w=270&h=230" alt="<?php echo $name; ?>">
              <div class="product-content">
                <?php echo $name; ?>
              </div>
            </div>

            <div class="product-content pl-0">
              <div class="product-cart-info">
                <?php echo $quantity; ?> x <?php echo $appSession->getCurrency()->format($currency_id, $unit_price); ?> <?php echo $unit_name; ?>
              </div>
            </div>
            <div class="product-content pl-0">
              <div class="product-price">
                <span class="ml-4"><?php echo $appSession->getCurrency()->format($currency_id, $amount); ?></span>
              </div>
            </div>
          </div>
        <?php
        }
        ?>

      </div>
    </div>
    <div class="track-order-info">
      <ul class="to-list">

        <li class="inc-vat d-flex flex-wrap justify-content-between">
          <span class="t-title"><?php echo $appSession->getLang()->find("Total"); ?></span>
          <span class="desc"><?php echo $appSession->getCurrency()->format($company_currency_id, $total); ?></span>
        </li>
      </ul>
    </div>
    <div class="order-details">
      <div class="order-details-head">
        <h6><?php echo $appSession->getLang()->find("Payments"); ?></h6>
      </div>
      <div class="order-details-container d-none d-md-block">
        <table width="100%" class="table">
          <thead>
            <tr>
              <th valign="middle" align="left"><?php echo $appSession->getLang()->find("Payment Type"); ?></th>
              <th align="right" valign="middle" width="50"><?php echo $appSession->getLang()->find("Currency"); ?></th>
              <th align="right" valign="middle" width="100"><?php echo $appSession->getLang()->find("Amount"); ?></th>
            </tr>
          </thead>
          <?php
          $sql = "SELECT d1.id, d1.currency_id, d1.amount, d3.code AS currency_code, d2.name AS payment_name, d1.description";
          $sql = $sql . " FROM account_payment_line_local d1";
          $sql = $sql . " LEFT OUTER JOIN account_payment d2 ON(d1.payment_id = d2.id)";
          $sql = $sql . " LEFT OUTER JOIN res_currency d3 ON(d1.currency_id = d3.id)";
          $sql = $sql . " WHERE d1.line_id='" . $sale_id . "' AND d1.status =0";

          $msg->add("query", $sql);
          $paymentLines = $appSession->getTier()->getArray($msg);

          for ($i = 0; $i < count($paymentLines); $i++) {
            $payment_id = $paymentLines[$i][0];
            $currency_id = $paymentLines[$i][1];
            $amount = $appSession->getTool()->toDouble($paymentLines[$i][2]);
            $currency_code = $paymentLines[$i][3];

            $payment_name = $paymentLines[$i][4];
          ?>
            <tr>
              <td valign="middle"><?php echo $payment_name; ?></td>
              <td valign="middle" align="center"><?php echo $currency_code; ?></td>
              <td align="right" valign="middle"><?php echo $appSession->getCurrency()->format($currency_id, $amount); ?></td>

            </tr>
          <?php
          }
          ?>
        </table>

      </div>
    </div>

    <div class="order-details">
      <div class="order-details-head">
        <h6><?php echo $appSession->getLang()->find("Points"); ?></h6>
      </div>
      <div class="order-details-container d-none d-md-block">
        <table width="100%" class="table">
          <thead>
            <tr>
              <th valign="middle" align="left"><?php echo $appSession->getLang()->find("Name"); ?></th>
              <th align="right" valign="middle" width="50"><?php echo $appSession->getLang()->find("Type"); ?></th>
              <th align="right" valign="middle" width="100"><?php echo $appSession->getLang()->find("Point"); ?></th>
            </tr>
          </thead>
          <?php
          $sql = "SELECT d1.point, d2.name AS loyalty_name, d3.name AS category_name FROM loyalty_point d1 LEFT OUTER JOIN loyalty d2 ON(d1.loyalty_id = d2.id) LEFT OUTER JOIN loyalty_point_category d3 ON(d1.category_id = d3.id) WHERE d1.rel_id='" . $sale_id . "' AND d1.status =0 ORDER BY d1.create_date ASC";

          $msg->add("query", $sql);
          $points = $appSession->getTier()->getArray($msg);

          for ($i = 0; $i < count($points); $i++) {
            $point = $points[$i][0];
            $loyalty_name = $points[$i][1];
            $category_name = $points[$i][2];

          ?>
            <tr>
              <td valign="middle"><?php echo $loyalty_name; ?></td>
              <td valign="middle" align="center"><?php echo $category_name; ?></td>
              <td align="right" valign="middle"><?php echo $point; ?></td>

            </tr>
          <?php
          }
          ?>
        </table>

      </div>
    </div>

    <div class="order-details">
      <div class="order-details-head">
        <h6><?php echo $appSession->getLang()->find("Status"); ?></h6>
      </div>
      <div class="order-details-container d-none d-md-block">

        <?php
        $sql = "SELECT d1.create_date, d1.name, d1.description FROM res_status_line d1 WHERE d1.rel_id='" . $sale_id . "' ORDER BY d1.create_date ASC";

        $msg->add("query", $sql);
        $status = $appSession->getTier()->getArray($msg);

        for ($i = 0; $i < count($status); $i++) {
          $create_date = $status[$i][0];
          $name = $status[$i][1];
          $description = $status[$i][2];
          if ($i > 0) {
            echo "<hr>";
          }

        ?>
          <?php echo $name; ?> - <?php echo $create_date; ?><br>
          <?php
          echo $description;
          ?>
        <?php
        }
        ?>


      </div>
    </div>

  </div>
<?php
}
?>
