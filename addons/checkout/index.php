<?php
require_once(ABSPATH . 'api/Sale.php');
$sale = new Sale($appSession);
$sale_id = $sale->findSaleId();
$sale->checkSaleService($sale_id, $appSession->getConfig()->getProperty("customer_id"));


$sql = "SELECT d1.order_no FROM sale_local d1 WHERE d1.id='" . $sale_id . "'";

$msg->add("query", $sql);
$saleLine = $appSession->getTier()->getArray($msg);
$receipt_no = "";
if (count($saleLine) > 0) {
  $receipt_no = $saleLine[0][0];
}

$productList = $sale->productList();

$sql = "SELECT d1.id, d1.name, d1.address FROM res_company d1 WHERE d1.status =0 AND d1.type='ONLINE'";
$msg->add("query", $sql);
$companies = $appSession->getTier()->getArray($msg);


$payment_method = [["fda0dc20-341e-4ac2-cfad-888dd77ee9d1", "Tiền mặt", "CASH", "FOSACHA.png"]];
if ($appSession->getConfig()->getProperty("customer_category_id") == "674ff761-e67d-4ee0-9d39-69538935fcc3" || $appSession->getConfig()->getProperty("customer_category_id") == "3ae2e203-7085-4888-9756-d1c10984b44f") {
  $payment_method[count($payment_method)] = ["7d2e384f-d100-42fc-812a-0e796c887ab9", "Ghi nợ", "DEBT", "FOSACHA.png"];
}
$payment_method[count($payment_method)] = ["ATM", "ATMCARD", "ATMCARD", "FOSACHA.png"];
$payment_method[count($payment_method)] = ["ATM", "Thanh toán BANKING", "QR", "VNPAYQR.png"];

$banks = [
  ["ABBANK", "Ngân hàng thương mại cổ phần An Bình (ABBANK)", "ABBANK.png"],
  ["ACB",  "Ngân hàng ACB", "ACB.png"],
  ["AGRIBANK", "Ngân hàng Nông nghiệp (Agribank)", "AGRIBANK.png"],
  ["BACABANK", "Ngân Hàng TMCP Bắc Á", "BACABANK.png"],
  ["BIDV", "Ngân hàng đầu tư và phát triển Việt Nam (BIDV)", "BIDV.png"],
  ["DONGABANK", "Ngân hàng Đông Á (DongABank)", "DONGABANK.png"],
  ["EXIMBANK", "Ngân hàng EximBank", "EXIMBANK.png"],
  ["HDBANK", "Ngan hàng HDBank", "HDBANK.png"],
  ["IVB", "Ngân hàng TNHH Indovina (IVB)", "IVB.png"],
  ["MBBANK", "Ngân hàng thương mại cổ phần Quân đội", "MBBANK.png"],
  ["MSBANK", "Ngân hàng Hàng Hải (MSBANK)", "MSBANK.png"],
  ["NAMABANK", "Ngân hàng Nam Á (NamABank)", "NAMABANK.png"],
  ["NCB", "Ngân hàng Quốc dân (NCB)", "NCB.png"],
  ["OCB", "Ngân hàng Phương Đông (OCB)", "OCB.png"],
  ["OJB", "Ngân hàng Đại Dương (OceanBank)", "OJB.png"],
  ["PVCOMBANK", "Ngân hàng TMCP Đại Chúng Việt Nam", "PVCOMBANK.png"],
  ["SACOMBANK", "Ngân hàng TMCP Sài Gòn Thương Tín (SacomBank)", "SACOMBANK.png"],
  ["SAIGONBANK", "Ngân hàng thương mại cổ phần Sài Gòn Công Thương", "SAIGONBANK.png"],
  ["SCB", "Ngân hàng TMCP Sài Gòn (SCB)", "SCB.png"],
  ["SHB", "Ngân hàng Thương mại cổ phần Sài Gòn - Hà Nội(SHB)", "SHB.png"],
  ["TECHCOMBANK", "Ngân hàng Kỹ thương Việt Nam (TechcomBank)", "TECHCOMBANK.png"],
  ["TPBANK", "Ngân hàng Tiên Phong (TPBank)", "TPBANK.png"],
  ["VPBANK", "Ngân hàng Việt Nam Thịnh vượng (VPBank)", "VPBANK.png"],
  ["SEABANK", "Ngân Hàng TMCP Đông Nam Á", "SEABANK.png"],
  ["VIB", "Ngân hàng Thương mại cổ phần Quốc tế Việt Nam (VIB)", "VIB.png"],
  ["VIETABANK", "Ngân hàng TMCP Việt Á", "VIETABANK.png"],
  ["VIETBANK", "Ngân hàng thương mại cổ phần Việt Nam Thương Tín", "VIETBANK.png"],
  ["VIETCOMBANK", "Ngân hàng Ngoại thương (Vietcombank)", "VIETCOMBANK.png"],
  ["VIETINBANK", "Ngân hàng Công thương (Vietinbank)", "VIETINBANK.png"],
  ["BIDC", "Ngân Hàng BIDC", "BIDC.PNG"],
  ["LAOVIETBANK", "NGÂN HÀNG LIÊN DOANH LÀO - VIỆT", "LAOVIETBANK.png"],
  ["WOORIBANK", "Ngân hàng TNHH MTV Woori Việt Nam", "WOORIBANK.png"],
  ["AMEX", "American Express", "AMEX.png"],
  ["VISA", "Thẻ quốc tế Visa", "VISA.png"],
  ["MASTERCARD", "Thẻ quốc tế MasterCard", "MASTERCARD.png"],
  ["JCB", "Thẻ quốc tế JCB", "JCB.png"],
  ["UPI", "UnionPay International", "UPI.png"],
  ["VNMART", "Ví điện tử VnMart", "VNMART.png"],
  ["VNPAYQR", "Cổng thanh toán VNPAYQR", "VNPAYQR.png"],
  ["1PAY", "Ví điện tử 1Pay", "1PAY.png"],
  ["FOXPAY", "Ví điện tử FOXPAY", "FOXPAY.png"],
  ["VIMASS", "Ví điện tử Vimass", "VIMASS.png"],
  ["VINID", "Ví điện tử VINID", "VINID.png"],
  ["VIVIET", "Ví điện tử Ví Việt", "VIVIET.png"],
  ["VNPTPAY", "Ví điện tử VNPTPAY", "VNPTPAY.png"],
  ["YOLO", "Ví điện tử YOLO", "YOLO.png"],
  ["VIETCAPITALBANK", "Ngân Hàng Bản Việt", "VIETCAPITALBANK.png"],
];

$sql = "SELECT d1.id, d1.currency_id, d1.amount, d3.code AS currency_code, d2.name AS payment_name, d1.payment_id";
$sql = $sql . " FROM account_payment_line_local d1";
$sql = $sql . " LEFT OUTER JOIN account_payment d2 ON(d1.payment_id = d2.id)";
$sql = $sql . " LEFT OUTER JOIN res_currency d3 ON(d1.currency_id = d3.id)";
$sql = $sql . " WHERE d1.line_id='" . $sale_id . "' AND d1.status =0";

$msg->add("query", $sql);
$paymentLines = $appSession->getTier()->getArray($msg);


$sql = "SELECT d1.id, d1.name";
$sql = $sql . " FROM res_address d1";
$sql = $sql . " WHERE d1.type='CITY' AND d1.status =0";
$sql = $sql . " ORDER BY d1.name ASC";

$msg->add("query", $sql);
$addresCity = $appSession->getTier()->getArray($msg);


$totalSale = $sale->totalSalePrice($sale_id);

$delivery_name = $appSession->getConfig()->getProperty("delivery_name");
$delivery_tel = $appSession->getConfig()->getProperty("delivery_tel");
$delivery_email = $appSession->getConfig()->getProperty("delivery_email");
$delivery_address = $appSession->getConfig()->getProperty("delivery_address");
$delivery_description = $appSession->getConfig()->getProperty("delivery_description");
$delivery_address_id =  $appSession->getConfig()->getProperty("delivery_address_id");


if ($appSession->getConfig()->getProperty("customer_id") != "") {
  $sql = "SELECT d1.name, d1.tel, d1.email, d1.address, d1.address_id FROM sale_shipping d1 WHERE d1.sale_id='" . $appSession->getConfig()->getProperty("customer_id") . "' ";
  $msg->add("query", $sql);
  $customers = $appSession->getTier()->getArray($msg);

  if (count($customers) > 0) {
    if ($delivery_name == "") {
      $delivery_name = $customers[0][0];
    }
    if ($delivery_tel == "") {
      $delivery_tel = $customers[0][1];
    }
    if ($delivery_email == "") {
      $delivery_email = $customers[0][2];
    }
    if ($delivery_address == "") {
      $delivery_address = $customers[0][3];
    }
    if ($delivery_address_id == "") {
      $delivery_address_id = $customers[0][4];
    }
  } else {

    $sql = "SELECT name, email, phone, address FROM customer WHERE id='" . $appSession->getConfig()->getProperty("customer_id") . "'";
    $msg->add("query", $sql);
    $customers = $appSession->getTier()->getArray($msg);
    if (count($customers) > 0) {
      if ($delivery_name == "") {
        $delivery_name = $customers[0][0];
      }
	  if ($delivery_email == "") {
        $delivery_email = $customers[0][1];
      }
      if ($delivery_tel == "") {
        $delivery_tel = $customers[0][2];
      }
      
      if ($delivery_address == "") {
        $delivery_address = $customers[0][3];
      }
    }
  }
}

$city_address_id = "";
$dist_address_id = "";
$ward_address_id = "";
if ($delivery_address_id != "") {
  while (true) {
    $sql = "SELECT parent_id, type FROM res_address WHERE id='" . $delivery_address_id . "'";
    $msg->add("query", $sql);

    $address = $appSession->getTier()->getArray($msg);
    if (count($address) == 0) {
      break;
    }
    if ($address[0][1] == "WARD") {
      $ward_address_id = $delivery_address_id;
    } else if ($address[0][1] == "DIST") {
      $dist_address_id = $delivery_address_id;
    } else if ($address[0][1] == "CITY") {
      $city_address_id = $delivery_address_id;
    }
    $delivery_address_id = $address[0][0];
  }
}
?>

 <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Checkout</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
</section>
<!-- page-header-section end -->

<!-- Checkout section Start -->
<section class="checkout-section-2 section-b-space">
  <div class="container-fluid-lg">
    <div class="row g-sm-4 g-3">
      <div class="col-lg-8">
        <div class="left-sidebar-checkout">
          <div class="checkout-detail-box">
            <ul>
              <li>
              
                <div class="checkout-box">
                  <div class="checkout-title">
					<table width="100%">
					<tr>
						<td> <h4><?php echo $appSession->getLang()->find("Delivery Info"); ?> #<?php echo $receipt_no; ?></h4></td>
						<td style="text-align:right"><?php if($appSession->getConfig()->getProperty("user_id") == ""){ ?> <a href="<?php URL;?>signin/?continue=checkout" class="btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold">Login</a><?php } ?></td>
					</tr>
					</table>
                  </div>
                  <div class="checkout-detail">
                    <div class="row">
                      <form action="#" class="custom-form">
                        <div class="custom-input">
                          <label for="editdelivery_name" class="form-label"><?php echo $appSession->getLang()->find("Name"); ?> (*)</label>
                          <input id="editdelivery_name" type="text" name="editdelivery_name" value="<?php echo $delivery_name; ?>" onblur="saveShipping()" class="form-control">
                        </div>
                      
                        <div class="custom-input">
                          <label for="editdelivery_tel" class="form-label"><?php echo $appSession->getLang()->find("Phone"); ?> (*)</label>
                          <input id="editdelivery_tel" type="text" name="editdelivery_tel" value="<?php echo $delivery_tel; ?>" onblur="saveShipping()" class="form-control">
                        </div>
                       
                        <div class="custom-textarea">
                          <label for="editdelivery_address" class="form-label"><?php echo $appSession->getLang()->find("Address"); ?>*</label>
                          <textarea class="form-control" id="editdelivery_address" name="editdelivery_address" style="width: 100%;" onblur="saveShipping()"><?php echo $delivery_address; ?></textarea>
                        </div>
                        <div class="custom-select">
                          <label for="editaddress_city" class="form-label"><?php echo $appSession->getLang()->find("City"); ?>*</label>
                          <select id="editaddress_city" class="form-control" onchange="cityChanged(this)" style="width: 100%;">
                            <option value=""><?php echo $appSession->getLang()->find("Select city/province"); ?></option>
                            <?php for ($i = 0; $i < count($addresCity); $i++) {                              ?>
                              <option value="<?php echo $addresCity[$i][0]; ?>"><?php echo $addresCity[$i][1]; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="custom-select">
                          <label for="editaddress_dist" class="form-label"><?php echo $appSession->getLang()->find("District"); ?> (*)</label>
                          <select id="editaddress_dist" class="form-control" onchange="distChanged(this)" style="width: 100%;"></select>
                        </div>
                        <div class="custom-input">
                          <label for="editaddress_ward" class="form-label"><?php echo $appSession->getLang()->find("Ward"); ?> (*)</label>
                          <select id="editaddress_ward" class="form-control" onchange="wardChanged(this);saveShipping()" style="width: 100%;"></select>
                        </div>
                        <div class="custom-textarea">
                          <label for="editdelivery_description" class="form-label"><?php echo $appSession->getLang()->find("Description"); ?></label>
                          <textarea class="form-control" id="editdelivery_description" name="editdelivery_description" style="width: 100%;" onblur="saveShipping()"><?php echo $delivery_description; ?></textarea>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </li>
              <?php if ($productList->getRowCount() > 0) 
			  { ?>
                <li>
                 
                  <div class="checkout-box">
                    <div class="checkout-title">
                      <h4><?php echo $appSession->getLang()->find("Payment"); ?></h4>
                    </div>
					<div class="checkout-detail">
						<div class="row g-4">
						<?php
						for ($i = 0; $i < count($paymentLines); $i++) {
						  $_id = $paymentLines[$i][0];
						  $currency_id = $paymentLines[$i][1];
						  $amount = $appSession->getTool()->toDouble($paymentLines[$i][2]);
						  $currency_code = $paymentLines[$i][3];
						  $totalSale = $totalSale - $amount;
						  $payment_name = $paymentLines[$i][4];
						  $payment_id = $paymentLines[$i][5];
						?>
							 <div class="col-xxl-6 col-lg-12 col-md-6">
								<div class="delivery-address-box">
									<div>
									
										<div class="label">
											<label onclick="removePayment('<?php echo $_id; ?>')"><?php echo $appSession->getLang()->find("Delete");?></label>
										</div>

										<ul class="delivery-address-detail">
											

											<li>
												<p class="text-content"><span
														class="text-title"><?php echo $payment_name; ?></p>
											</li>

											<li>
												<h6 class="text-content"><span
														class="text-title"><?php echo $appSession->getCurrency()->format($currency_id, $amount); ?></span></h6>
											</li>

											
										</ul>
									</div>
								</div>
							</div>
							<?php
							}
							?>
						</div>	
					</div>
					<br>
                    <div class="checkout-detail">
						<div class="accordion accordion-flush custom-accordion"
							id="accordionFlushExample">
							
							  <?php 
							  for ($i = 0; $i < count($payment_method); $i++) 
							  {
							  ?>
							<div class="accordion-item">
								<div class="accordion-header" id="flush-headingFour">
									<div class="accordion-button collapsed"
										data-bs-toggle="collapse"
										data-bs-target="#flush-payment_method<?php echo $i;?>">
										<div class="custom-form-check form-check mb-0">
											<label class="form-check-label" for="payment_method<?php echo $i;?>"><input
													class="form-check-input mt-0" type="radio"
													name="flexRadioDefault" id="payment_method<?php echo $i;?>" onclick="paymentMethod('<?php echo $payment_method[$i][0]; ?>', '<?php echo $payment_method[$i][2]; ?>')";><?php echo $payment_method[$i][2]; ?></label>
										</div>
									</div>
								</div>
								<div id="flush-payment_method<?php echo $i;?>"
									class="accordion-collapse collapse"
									data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
										<p class="cod-review" id="paymentInfo<?php echo $payment_method[$i][0]; ?>">
										</p>
									</div>
								</div>
							</div>
								<?php
								
								}
								?>

						</div>
						
                
                    </div>
                  </div>
                </li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="right-side-summery-box">
          <div class="summery-box-2">
            <div class="summery-header">
              <h3>Items</h3>
            </div>
            <ul class="summery-contain">
              <?php
              $total = 0;
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
                <li>
                  <div class="product-image">
                    <a onclick="javascript:void(0)">
                      <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&w=190&h=120" class="img-fluid blur-up lazyloaded checkout-image" alt="<?php echo $name; ?>">
                    </a>
                  </div>
                  <div class="container">
                    <div class="row">
                      <h4><a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>"><?php echo $name; ?></a> <span>X <?php echo $quantity; ?></span></h4>
                    </div>
                    <?php if (!empty($attribute_category_name)) { ?>
                      <div class="row">
                        <h4> <?php echo $attribute_category_name; ?>: <?php echo $attribute_name; ?> </h4>
                      </div>
                    <?php } ?>
                  </div>
                  <h4 class="price"><?php echo $appSession->getCurrency()->format($currency_id, $unit_price); ?></h4>
                </li>
              <?php } ?>
            </ul>
            <ul class="summery-total">
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
                <li class="list-total">
                  <h4><?php echo $service_name; ?>: </h4>
                  <h4 class="price"><?php echo $appSession->getCurrency()->format($appSession->getConfig()->getProperty("currency_id"), $a); ?></h4>
                </li>
              <?php
              }
              ?>
              <li class="list-total">
                <h4><?php echo $appSession->getLang()->find("Total"); ?></h4>
                <h4 class="price"><?php echo $appSession->getCurrency()->format($appSession->getConfig()->getProperty("currency_id"), $total); ?></h4>
              </li>
            </ul>
          </div>

          <!-- TODO: there are reset fields after payment (fix) -->
          <button onClick="checkOut()" class="btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold"><?php echo $appSession->getLang()->find("Check Out"); ?></button>

        </div>
      </div>
    </div>
  </div>
</section>
<!-- Checkout section End -->

<script type="text/javascript">
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

  var payment_id = '';
  var city_address_id = '<?php echo $city_address_id; ?>';
  var dist_address_id = '<?php echo $dist_address_id; ?>';
  var ward_address_id = '<?php echo $ward_address_id; ?>';

  function checkOut() {
    var address_id = "";
    if (ward_address_id != "") {
      address_id = ward_address_id;
    }
    if (address_id == "" && dist_address_id != "") {
      address_id = dist_address_id;
    }
    if (address_id == "" && city_address_id != "") {
      address_id = city_address_id;
    }
    var ctr = document.getElementById("editdelivery_name");
    if (ctr.value == "") {
      alert('<?php echo $appSession->getLang()->find("Enter your name"); ?>');
      ctr.focus();
      return;
    }
    var delivery_name = ctr.value;

   
    var company_id = '<?php echo $company_company_id;?>';

    var ctr = document.getElementById("editdelivery_tel");
    if (ctr.value == "") {
      alert('<?php echo $appSession->getLang()->find("Enter your phone"); ?>');
      ctr.focus();
      return;
    }
    var delivery_tel = ctr.value;

   
    var ctr = document.getElementById("editdelivery_address");
    if (ctr.value == "") {
      alert('<?php echo $appSession->getLang()->find("Enter your address"); ?>');
      ctr.focus();
      return;
    }
    var delivery_address = ctr.value;
    if (<?php echo $totalSale; ?> != 0) {
      alert('<?php echo $appSession->getLang()->find("Please check payment"); ?>');
      ctr.focus();
      return;
    }

    var ctr = document.getElementById("editdelivery_description");
    var delivery_description = ctr.value;
    var _url = "<?php echo URL; ?>api/product/?ac=checkOut";
    _url = _url + "&delivery_name=" + encodeURIComponent(delivery_name);
    _url = _url + "&company_id=" + company_id;
    _url = _url + "&delivery_tel=" + encodeURIComponent(delivery_tel);
    _url = _url + "&delivery_email=" + encodeURIComponent(delivery_email);
    _url = _url + "&delivery_to=" + encodeURIComponent(delivery_address);
    _url = _url + "&delivery_description=" + encodeURIComponent(delivery_description);
    _url = _url + "&address_id=" + address_id;
    _url = _url + "&session_id=<?php echo $appSession->getConfig()->getProperty("session_id"); ?>";
    _url = _url + "&sale_id=<?php echo $sale_id; ?>";
    _url = _url + "&user_id=<?php echo $appSession->getConfig()->getProperty("user_id"); ?>";
	
    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        if (message.indexOf("OK") != -1) {
          _url = "<?php echo URL; ?>api/product/?ac=saveDeliveryInfo";
          _url = _url + "&delivery_name=" + encodeURIComponent(delivery_name);
          _url = _url + "&delivery_tel=" + encodeURIComponent(delivery_tel);
          _url = _url + "&delivery_email=" + encodeURIComponent(delivery_email);
          _url = _url + "&delivery_address=" + encodeURIComponent(delivery_address);
          _url = _url + "&delivery_description=" + encodeURIComponent(delivery_description);
          _url = _url + "&address_id=" + address_id;
          _url = _url + "&customer_id=<?php echo $appSession->getConfig()->getProperty("customer_id"); ?>";
          _url = _url + "&session_id=<?php echo $appSession->getConfig()->getProperty("session_id"); ?>";
          loadPage('contentView', _url, function(status, message) {
            if (status == 0) {
              document.location.href = '<?php echo URL; ?>paid';

            }

          });

        } else {
          console.log(message);
        }
      }
    }, true);
  }

  function loginPayment() {
    var _url = "<?php echo URL; ?>addons/login/";
    openPopup(_url, function(status, message) {
      var ctr = document.getElementById('edituser');
      if (ctr != null) {
        ctr.focus();
      }
    });

  }

  function doLogin() {
    var ctr = document.getElementById('edituser');
    if (ctr.value == '') {
      alert('<?php echo $appSession->getLang()->find("Please enter your username"); ?>');
      ctr.focus();
      return;
    }

    var user = ctr.value;

    var ctr = document.getElementById('editpassword');
    if (ctr.value == '') {
      alert('<?php echo $appSession->getLang()->find("Please enter your username"); ?>');
      ctr.focus();
      return;
    }

    var password = ctr.value;
    password = Sha1.hash(password);

    doLoginAccount(user, password,
      function(status, message) {

        if (message == "OK") {
          var _url = "<?php echo URL; ?>api/product/?ac=update_sale_session_web";

          loadPage('contentView', _url, function(status, message) {
            if (status == 0) {

              if (message == "OK") {
                document.location.reload();
              }

            } else {
              console.log(message);
            }

          }, true);

        } else if (message == "INCORRECT") {
          alert('<?php echo $appSession->getLang()->find("Invalid password"); ?>');
        } else if (message == "INVALID") {
          alert('<?php echo $appSession->getLang()->find("Invalid user name"); ?>');
        }

      }
    );
  }
  var category_name = '';
  var payment_term = '';

  function paymentMethod(id, name) {

	payment_id = id;
	category_name = name;
    if (category_name == "QR") {
      paymentBank('QR');
      return;
    } 
	if (category_name == "ATMCARD") {
      paymentBank('ATMCARD');
      return;
    }

    var params = "ac=payment_type_amount";
    params = params + "&lang_id=<?php echo $appSession->getConfig()->getProperty("lang_id"); ?>";
    params = params + "&session_id=<?php echo $appSession->getConfig()->getProperty("session_id"); ?>";
    params = params + "&user_id=<?php echo $appSession->getConfig()->getProperty("user_id"); ?>";
    params = params + "&customer_id=<?php echo $appSession->getConfig()->getProperty("customer_id"); ?>";
    params = params + "&category_name=" + category_name;
    var _url = "<?php echo URL; ?>api/product/?" + params;
   
    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {

        payment_term = message;
        buildPayment();

      } else {
        console.log(message);
      }

    }, true);
  }
  var total = <?php echo $totalSale; ?>;
  var balance = 0;
  var payment_points = "";
  var vouchers = "";
  var payment_amount = 0;
  var payment_description = "";

  function buildPayment() {

    var s = '';
    if (category_name == "WALLET") {
      var items = payment_term.split(';');
      var holders = new Array();

      for (var j = 0; j < items.length; j++) {


        var item = items[j].split(":");

        if (item.length == 3) {
          holders.push(item);
        }
      }
      var s = "";
      for (var j = 0; j < holders.length; j++) {
        if (j > 0) {
          s = s + "<hr>";
        }
        var wallet_amount = parseFloat(holders[j][0]);
        var h_id = holders[j][1];
        var holder_code = holders[j][2];
        if (wallet_amount > total) {
          wallet_amount = total;
        }
        s = s + holder_code + ": <b>" + wallet_amount.toLocaleString('en-US') + "</b>";
        s = s + '<input type="button" class ="btn btn-success" onclick="addWallet(\'' + h_id + '\', ' + wallet_amount + ')" value="<?php echo $appSession->getLang()->find("Add Payment"); ?>">';

      }

    } else if (category_name == "LOYALTY") {

      var arr = payment_term.split(';');
      var j = 0;
      for (var i = 0; i < arr.length; i++) {

        var items = arr[i].split(':');

        if (items.length == 3) {

          var point = items[0];
          var amount = items[1];
          var paid = items[2];
          s = s + '<span style="padding:4px"><input type="button" style="min-width:150px; marign:4px" class="btn btn-secondary" onclick="paymentPoint(' + point + ', ' + paid + ')" value="' + amount + ' - ' + point + '"></span>';
          j += 1;
          if (j == 3) {
            s = s + "<br><br>";
            j = 0;
          }

        }
      }
    } else if (category_name == "VOUCHER") {
      var arr = payment_term.split('\n');

      for (var i = 0; i < arr.length; i++) {

        var items = arr[i].split('\t');

        if (items.length == 4) {

          var name = items[0];
          var code = items[1];
          var currency_id = items[2];
          var amount = items[3];
          s = s + '<span style="padding:4px"><input type="button" style="width:100%; marign:4px" class="btn btn-secondary" onclick="addPaymentVoucherCode(\'' + code + '\')" value="' + amount + ' - ' + code + '/' + name + '"></span>';
          s = s + "</br></br>";

        }
      }
      s += '<div class="custom-input">';
      s += '<label for="editvoucher_code" class="form-label"><?php echo $appSession->getLang()->find("Voucher Code"); ?></label>';
      s += '<input class="form-control" type="text" id="editvoucher_code">';
      s += '</div>';
      s += '<div class="custom-textarea" >';
      s += '<label for="editpayment_description" class="form-label"><?php echo $appSession->getLang()->find("Description"); ?></label>';
      s += '<textarea class="form-control" id="editpayment_description"></textarea>';
      s += '</div>';
      s += '<button class ="btn btn-animation fw-bold" onclick="addPaymentVoucher()"><?php echo $appSession->getLang()->find("Add Payment"); ?></button>'
    } else {
      var paid = total;

      s += '<div class="custom-input">';
      s += '<label for="editpayment_amount" class="form-label"><?php echo $appSession->getLang()->find("Payment Amount"); ?></label>';
      s += '<input type="text" id="editpayment_amount" class="form-control" value="' + paid + '">';
      s += '</div>';
      s += '<div class="custom-textarea">';
      s += '<label for="editpayment_description" class="form-label"><?php echo $appSession->getLang()->find("Description"); ?></label>';
      s += '<textarea class="form-control" id="editpayment_description"></textarea>';
      s += '</div>';
      s += '<br>';
      s += '<button class="btn btn-animation fw-bold" onclick="addPayment()"><?php echo $appSession->getLang()->find("Add Payment"); ?></button>';
    }
    var ctr = document.getElementById("paymentInfo" + payment_id);
    if (ctr != null) {
      ctr.innerHTML = s;
    }

  }

  function paymentBank(bank_code) {

   
    var postData = "order_id=<?php echo $sale_id;?>&order_no=<?php echo $receipt_no;?>&order_desc=HD<?php echo $receipt_no;?>";
    postData = postData + "&payment_type=payment";
    postData = postData + "&amount=<?php echo $total; ?>";
    postData = postData + "&language=vn";
    postData = postData + "&payment_method=" + bank_code;
    var _url = "<?php echo URL; ?>mbpay_php/?" + postData;
	console.log(_url);
    loadPage('contentView', _url, function(status, message) {
      if (status == 0 && message.indexOf("code") != -1) {

        var obj = JSON.parse(message);
        if (obj.code == "00" && obj.message == "success") {

          window.location.href = obj.data;
        } else {
          console.log(message);
        }
      } else {
        console.log(message);
      }

    }, true);


  }
  var holder_id = "";

  function addWallet(id, amount) {
    holder_id = id;
    payment_amount = amount;
    doAddPayment();

  }

  function addPayment() {
    var ctr = document.getElementById('editpayment_amount');
    if (ctr.value == '') {
      alert('<?php echo $appSession->getLang()->find("Please enter your payment amount"); ?>');
      ctr.focus();
      return;
    }
    var amount = parseFloat(ctr.value);
    if (category_name == 'WALLET' && amount > balance) {
      alert('<?php echo $appSession->getLang()->find("Paymenent amount is less than or equal"); ?> ' + balance);
      ctr.focus();
      return;
    }
    if (amount > total) {
      amount = total;
    }
    if (amount <= 0) {

      alert('<?php echo $appSession->getLang()->find("Invalid payment amount"); ?>');
      return;
    }
    var ctr = document.getElementById('editpayment_description');
    payment_description = ctr.value;

    payment_amount = amount;
    doAddPayment();
  }

  function paymentPoint(point, amount) {
    payment_amount = amount;
    payment_points = point;
    doAddPayment();
  }

  function addPaymentVoucherCode(code) {
    vouchers = code;
    doAddPayment();
  }

  function addPaymentVoucher() {
    var ctr = document.getElementById('editvoucher_code');
    if (ctr.value == '') {
      alert('<?php echo $appSession->getLang()->find("Please enter your voucher code"); ?>');
      ctr.focus();
      return;
    }
    vouchers = ctr.value;
    var ctr = document.getElementById('editpayment_description');
    payment_description = ctr.value;
    doAddPayment();
  }

  var running = false;

  function doAddPayment() {

    if (running == true) {
      return;
    }
    running = true;
    var params = "ac=addPayment";
    params = params + "&lang_id=<?php echo $appSession->getConfig()->getProperty("lang_id"); ?>";
    params = params + "&company_id=<?php echo $appSession->getConfig()->getProperty("company_id"); ?>";
    params = params + "&session_id=<?php echo $appSession->getConfig()->getProperty("session_id"); ?>";
    params = params + "&user_id=<?php echo $appSession->getConfig()->getProperty("user_id"); ?>";
    params = params + "&customer_id=<?php echo $appSession->getConfig()->getProperty("customer_id"); ?>";

    params = params + "&payment_id=" + payment_id;
    params = params + "&holder_id=" + holder_id;
    params = params + "&payment_amount=" + encodeURIComponent(payment_amount);
    params = params + "&payment_points=" + encodeURIComponent(payment_points);
    params = params + "&payment_description=" + encodeURIComponent(payment_description);
    params = params + "&vouchers=" + encodeURIComponent(vouchers);
    var _url = "<?php echo URL; ?>api/product/?" + params;

    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        running = false;
        if (message.indexOf("OK") != -1) {

          document.location.reload();
        }
      } else {
        console.log(message);
      }

    }, true);
  }

  function removePayment(id) {
    var result = confirm("Want to remove payment?");
    if (!result) {
      return;
    }
    var params = "ac=removePayment";
    params = params + "&lang_id=<?php echo $appSession->getConfig()->getProperty("lang_id"); ?>";
    params = params + "&company_id=<?php echo $appSession->getConfig()->getProperty("company_id"); ?>";
    params = params + "&session_id=<?php echo $appSession->getConfig()->getProperty("session_id"); ?>";
    params = params + "&user_id=<?php echo $appSession->getConfig()->getProperty("user_id"); ?>";
    params = params + "&customer_id=<?php echo $appSession->getConfig()->getProperty("customer_id"); ?>";
    params = params + "&id=" + id;
    var _url = "<?php echo URL; ?>api/product/?" + params;

    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        if (message == "OK") {
          document.location.reload();
        }
      } else {
        console.log(message);
      }

    }, true);

  }

  function cityChanged(theSelect) {
    city_address_id = theSelect.value;

    var params = "ac=res_address";
    params = params + "&parent_id=" + city_address_id;
    var _url = "<?php echo URL; ?>api/product/?" + params;
    var cb = document.getElementById('editaddress_ward');
    if (cb != null) {
      while (cb.options.length > 0) {
        cb.remove(0);
      }
    }
    var cb = document.getElementById('editaddress_dist');

    if (cb == null) {
      return;
    }
    while (cb.options.length > 0) {
      cb.remove(0);
    }

    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        var op = document.createElement('option');
        op.value = "";
        op.text = '<?php echo $appSession->getLang()->find("Select district"); ?>';
        cb.options.add(op);
        var arr = message.split('\n');

        for (i = 0; i < arr.length; i++) {

          var index = arr[i].indexOf('=');
          if (index != -1) {
            var id = arr[i].substring(0, index);
            var name = arr[i].substring(index + 1);
            var op = document.createElement('option');
            op.value = id;
            op.text = name;
            cb.options.add(op);
            if (dist_address_id == id) {
              cb.selectedIndex = i + 1;
            }
          }
        }
        if (dist_address_id != "") {
          distChanged(cb);
        }
      } else {
        console.log(message);
      }

    }, true);
  }

  function distChanged(theSelect) {
    dist_address_id = theSelect.value;

    var params = "ac=res_address";
    params = params + "&parent_id=" + dist_address_id;
    var _url = "<?php echo URL; ?>api/product/?" + params;
    var cb = document.getElementById('editaddress_ward');

    if (cb == null) {
      return;
    }
    while (cb.options.length > 0) {
      cb.remove(0);
    }

    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        var op = document.createElement('option');
        op.value = "";
        op.text = '<?php echo $appSession->getLang()->find("Select ward"); ?>';
        cb.options.add(op);
        var arr = message.split('\n');

        for (i = 0; i < arr.length; i++) {

          var index = arr[i].indexOf('=');
          if (index != -1) {
            var id = arr[i].substring(0, index);
            var name = arr[i].substring(index + 1);
            var op = document.createElement('option');
            op.value = id;
            op.text = name;
            cb.options.add(op);
            if (ward_address_id == id) {
              cb.selectedIndex = i + 1;
            }
          }
        }
        if (ward_address_id != "") {
          wardChanged(cb);
        }
      } else {
        console.log(message);
      }

    }, true);
  }

  function wardChanged(theSelect) {
    ward_address_id = theSelect.value;
  }
  if (city_address_id != "") {
    var cb = document.getElementById('editaddress_city');
    for (var i = 0; i < cb.options.length; i++) {
      if (cb.options[i].value == city_address_id) {
        cb.options[i];
        cb.selectedIndex = i;
        cityChanged(cb);
        break;
      }
    }
  }

  function saveShipping() {
    var address_id = "";
    if (ward_address_id != "") {
      address_id = ward_address_id;
    }
    if (address_id == "" && dist_address_id != "") {
      address_id = dist_address_id;
    }
    if (address_id == "" && city_address_id != "") {
      address_id = city_address_id;
    }
    var ctr = document.getElementById("editdelivery_name");
    var delivery_name = ctr.value;

  
    var company_id = '<?php echo $appSession->getConfig()->getProperty("company_id");?>';

    var ctr = document.getElementById("editdelivery_tel");

    var delivery_tel = ctr.value;

    var ctr = document.getElementById("editdelivery_email");
    var delivery_email = ctr.value;


    var ctr = document.getElementById("editdelivery_address");

    var delivery_address = ctr.value;
    var ctr = document.getElementById("editdelivery_description");
    var delivery_description = ctr.value;
    var _url = "<?php echo URL; ?>api/product/?ac=saveShipping";
    _url = _url + "&delivery_name=" + encodeURIComponent(delivery_name);
    _url = _url + "&company_id=" + company_id;
    _url = _url + "&delivery_tel=" + encodeURIComponent(delivery_tel);
    _url = _url + "&delivery_email=" + encodeURIComponent(delivery_email);
    _url = _url + "&delivery_to=" + encodeURIComponent(delivery_address);
    _url = _url + "&delivery_description=" + encodeURIComponent(delivery_description);
    _url = _url + "&address_id=" + address_id;
    _url = _url + "&customer_id=<?php echo $appSession->getConfig()->getProperty("customer_id"); ?>";
    _url = _url + "&sale_id=<?php echo $sale_id; ?>";

    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {

      } else {

      }

    }, true);
  }
</script>
