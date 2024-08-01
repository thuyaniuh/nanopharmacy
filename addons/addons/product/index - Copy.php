<?php
require_once(ABSPATH . 'api/Product.php');
$product = new Product($appSession);
if (strlen($selected_id) == 36) {
  $product->addProductSeen($selected_id);
}
$sql = "SELECT d1.id, d5.unit_id, d5.attribute_id,	d5.type_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d5.unit_price, d5.old_price, d6.name AS unit_name, d5.currency_id";
$sql = $sql . ", d9.name AS attribute_name, d10.name attribute_category_name, d5.factor";
$sql = $sql . ", d5.company_id, d7.commercial_name, d7.name AS company_name, d5.id AS price_id, d8.document_id AS price_document_id, d5.description, d11.name AS type_name, 0.0 AS unit_in_stock";
$sql = $sql . " FROM product d1";
$sql = $sql . " LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status =0)";
$sql = $sql . " LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='" . $appSession->getConfig()->getProperty("lang_id") . "' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
$sql = $sql . " LEFT OUTER JOIN product_price d5 ON(d1.id = d5.product_id AND d5.status =0) LEFT OUTER JOIN product_unit d6 ON(d5.unit_id = d6.id)";
$sql = $sql . " LEFT OUTER JOIN res_company d7 ON(d5.company_id = d7.id)";
$sql = $sql . " LEFT OUTER JOIN poster d8 ON(d5.id = d8.rel_id AND d8.publish=1 AND d8.status =0)";
$sql = $sql . " LEFT OUTER JOIN attribute d9 ON(d5.attribute_id = d9.id)";
$sql = $sql . " LEFT OUTER JOIN attribute_category d10 ON(d9.category_id = d10.id)";
$sql = $sql . " LEFT OUTER JOIN product_type d11 ON(d5.type_id = d11.id)";
$sql = $sql . " LEFT OUTER JOIN product_note d12 ON(d5.id = d12.product_id)";
$sql = $sql . " WHERE d5.id='" . $price_id . "'";

$msg->add("query", $sql);
$productList = $appSession->getTier()->getTable($msg);
$product = new Product($appSession);
$productList = $product->countProduct($productList);

$product_price_id = $price_id;

$photos = [];
if ($productList->getRowCount() > 0) {
  $product_product_id = $productList->getString(0, "id");
  $product_id = $product_product_id;
  $code = $productList->getString(0, "code");
  $product_name = $productList->getString(0, "name_lg");

  if ($product_name == "") {
    $product_name = $productList->getString(0, "name");
  }
  $document_id = $productList->getString(0, "price_document_id");
  if ($document_id == "") {
    $document_id = $productList->getString(0, "document_id");
  }
  $product_unit_price = $productList->getFloat(0, "unit_price");
  $old_price = $productList->getFloat(0, "old_price");
  $product_unit_id = $productList->getString(0, "unit_id");
  $unit_name = $productList->getString(0, "unit_name");
  $product_currency_id = $productList->getString(0, "currency_id");
  $attribute_category_name = $productList->getString(0, "attribute_category_name");
  $product_attribute_id = $productList->getString(0, "attribute_id");
  $product_type_id = $productList->getString(0, "type_id");
  $attribute_name = $productList->getString(0, "attribute_name");
  $attribute_code = $productList->getString(0, "attribute_code");
  $unit_in_stock = $productList->getFloat(0, "unit_in_stock");
  $commercial_name = $productList->getString(0, "commercial_name");
  $description = $productList->getString(0, "description");
  $company_id = $productList->getString(0, "company_id");
  if (count($photos) == 0) {
    $photos[0] = [$document_id, $product_name];
  }

  $sql = "SELECT d1.document_id, d2.name FROM document_rel d1 LEFT OUTER JOIN document d2 ON(d1.document_id = d2.id) WHERE d1.status =0 AND d1.publish=1 AND (d1.rel_id='" . $price_id . "' OR d1.rel_id='" . $product_id . "')";
  $sql = $sql . " ORDER BY d1.create_date ASC";
  $msg->add("query", $sql);
  $values = $appSession->getTier()->getArray($msg);
  for ($i = 0; $i < count($values); $i++) {
    $photos[count($photos)] = [$values[$i][0], $values[$i][1]];
  }
?>
  <!-- page-header-section start -->
  <div class="page-header-section">
    <div class="container">
      <div class="row">
        <div class="col-12 d-flex justify-content-between justify-content-md-end">
          <ul class="breadcrumb">
            <li><a href="<?php echo URL; ?>"><?php echo $appSession->getLang()->find("Home"); ?> </a></li>
            <li><span>/</span></li>
            <li><?php echo $product_name ?></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- page-header-section end -->

  <!-- Product Left Sidebar Start -->
  <section class="product-section section-b-space">
    <div class="container-fluid-lg">
      <div class="row">
        <div class="col-xxl-9 col-xl-8 col-lg-7 wow fadeInUp">
          <div class="row g-4">
            <div class="col-xl-6 wow fadeInUp">
              <div class="product-left-box">
                <div class="row g-sm-4 g-2">
                  <div class="col-12">
                    <div class="product-main no-arrow">
                      <div>
                        <div class="slider-image">
                          <?php for ($i = 0; $i < count($photos); $i++) {
                            $document_id = $photos[$i][0];
                            $name = $photos[$i][1];
                          ?>
                            <div>
                              <div class="slider-image">
                                <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>" data-zoom-image="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>" id="img-<?php echo $i; ?>" data-zoom-image="../assets/images/product/category/1.jpg" class="
                                                        img-fluid image_zoom_cls-0 blur-up lazyload image_zoom_cls-<?php echo $i ?>" alt="">
                              </div>
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="left-slider-image left-slider no-arrow slick-top">
                      <?php for ($i = 0; $i < count($photos); $i++) {
                        $document_id = $photos[$i][0];
                        $name = $photos[$i][1];
                      ?>
                        <div>
                          <div class="sidebar-image">
                            <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>" class="img-fluid blur-up lazyload" alt="">
                          </div>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 wow fadeInUp">
              <div class="right-box-contain">
                <h2 class="name"><?php echo $product_name; ?></h2>
                <div class="price-rating">
                  <h3 class="theme-color price">
                    <?php echo $appSession->getCurrency()->format($product_currency_id, $product_unit_price); ?>/ <?php echo $unit_name ?>
                    <?php if ($old_price != 0) { ?>
                      <del> (<?php echo $old_price; ?>)</del>
                    <?php } ?>
                  </h3>
                </div>
                <div class="procuct-contain">
                  <p>
                    <?php echo $description; ?>
                  </p>
                </div>
                <div class="product-packege">
                  <div class="product-title">
                  </div>
                  <ul class="select-packege">
                    <li>
                      <?php if (!empty($attribute_category_name)) { ?>
                        <p class="quantity"><?php echo $attribute_category_name; ?> : <?php echo $attribute_name; ?></p>
                      <?php } ?>
                    </li>
                    <li>
                      <p class="quantity"><?php echo $appSession->getLang()->find("Stock"); ?> : <?php if ($unit_in_stock == "") {
                                                                                                    echo "0";
                                                                                                  } else {
                                                                                                    echo $unit_in_stock;
                                                                                                  }  ?></p>
                    </li>
                  </ul>
                  <div class="custom-textarea">
                    <textarea class="form-control" id="editdescription" placeholder="Ghi chú" rows="6"></textarea>
                  </div>
                </div>
                <div class="note-box product-packege">
                  <div class="cart_qty qty-box product-qty">
                    <div class="input-group">
                      <button type="button" class="qty-right-plus" data-type="plus" data-field="quantity">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                      </button>
                      <input id="qty" class="form-control input-number qty-input" type="text" name="quantity" value="1">
                      <button type="button" class="qty-left-minus" data-type="minus" data-field="quantity">
                        <i class="fa fa-minus" aria-hidden="true"></i>
                      </button>
                    </div>
                  </div>
                  <button <?php if ($unit_in_stock <= 0) {
                            echo 'disabled';
                          } ?> onclick="addingProduct('card')" class="btn btn-md bg-dark cart-button text-white w-100"><?php echo $appSession->getLang()->find("Add to card"); ?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Product Left Sidebar End -->

  <?php

  $sql = "SELECT d1.id, d1.name, d1.description, d1.content, d1.category_id, d2.name FROM res_rel d LEFT OUTER JOIN post d1 ON(d.res_id = d1.id) LEFT OUTER JOIN post_category d2 ON(d1.category_id = d2.id) WHERE d.rel_id='" . $product_id . "' AND d1.status =0 AND d1.publish = 1";
  $sql = $sql . " ORDER BY d2.sequence ASC";
  $msg->add("query", $sql);

  $posts = $appSession->getTier()->getArray($msg);
  $post_categories = $appSession->getTool()->selectDistinct($posts, [4, 5]);
  if (count($post_categories) > 0) {

  ?>
    <section class="description-review-area section-ptb">
      <div class="container">
        <div class="description-review-wrapper">
          <div class="description-review-topbar nav">
            <?php
            for ($i = 0; $i < count($post_categories); $i++) {
              $category_id = $post_categories[$i][0];
              $category_name = $post_categories[$i][1];
            ?>
              <a <?php if ($i == 0) { ?>class="active" <?php } ?> data-toggle="tab" href="#des<?php echo $i; ?>"><?php echo $category_name; ?></a>

            <?php
            }
            ?>
          </div>
          <div class="tab-content description-review-bottom">
            <?php
            for ($i = 0; $i < count($post_categories); $i++) {
              $category_id = $post_categories[$i][0];

            ?>
              <div id="des<?php echo $i; ?>" class="tab-pane <?php if ($i == 0) { ?> active<?php } ?>">
                <div class="product-description-wrapper">
                  <?php
                  for ($j = 0; $j < count($posts); $j++) {
                    if ($posts[$j][4] == $category_id) {
                      $post_name = $posts[$j][1];
                      $post_content = $posts[$j][3];
                  ?>
                      <h1><?php echo $post_name; ?></h1>
                      <p><?php echo $post_content; ?></p>
                  <?php
                    }
                  }
                  ?>
                </div>
              </div>
            <?php
            }
            ?>
          </div>
        </div>
      </div>
    </section>
  <?php
  }
  ?>


  <!-- trending product-section start -->
  <?php
  $productList = $product->productRel($appSession, $product_id);
  if ($productList->getRowCount() > 0) {
  ?>
    <section class="trending-product-section">
      <div class="container">
        <div class="section-heading">
          <h4 class="heading-title"><span class="heading-circle"></span> Sản phẩm gợi ý </h4>
        </div>

        <div class="section-wrapper">
          <!-- Add Arrows -->
          <div class="slider-btn-group">
            <div class="slider-btn-prev trending-slider-prev">
              <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 443.52 443.52" style="enable-background:new 0 0 443.52 443.52;" xml:space="preserve">
                <g>
                  <path d="M143.492,221.863L336.226,29.129c6.663-6.664,6.663-17.468,0-24.132c-6.665-6.662-17.468-6.662-24.132,0l-204.8,204.8
                        c-6.662,6.664-6.662,17.468,0,24.132l204.8,204.8c6.78,6.548,17.584,6.36,24.132-0.42c6.387-6.614,6.387-17.099,0-23.712
                        L143.492,221.863z" />
                </g>
              </svg>
            </div>
            <div class="slider-btn-next trending-slider-next">
              <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.002 512.002" style="enable-background:new 0 0 512.002 512.002;" xml:space="preserve">
                <g>
                  <path d="M388.425,241.951L151.609,5.79c-7.759-7.733-20.321-7.72-28.067,0.04c-7.74,7.759-7.72,20.328,0.04,28.067l222.72,222.105
                        L123.574,478.106c-7.759,7.74-7.779,20.301-0.04,28.061c3.883,3.89,8.97,5.835,14.057,5.835c5.074,0,10.141-1.932,14.017-5.795
                        l236.817-236.155c3.737-3.718,5.834-8.778,5.834-14.05S392.156,245.676,388.425,241.951z" />
                </g>
              </svg>
            </div>
          </div>
          <div class="mlr-20">
            <div class="trending-product-container">
              <div class="swiper-wrapper">

                <?php
                for ($i = 0; $i < $productList->getRowCount(); $i++) {

                  $product_id = $productList->getString($i, "id");
                  $code = $productList->getString($i, "code");
                  $document_id = $productList->getString($i, "price_document_id");
                  if ($document_id == "") {
                    $document_id = $productList->getString($i, "document_id");
                  }
                  $name = $productList->getString($i, "name_lg");

                  if ($name == "") {
                    $name = $productList->getString($i, "name");
                  }
                  $unit_price = $productList->getFloat($i, "unit_price");
                  $old_price = $productList->getFloat($i, "old_price");
                  $unit_id = $productList->getString($i, "unit_id");
                  $currency_id = $productList->getFloat($i, "currency_id");
                  $unit_name = $productList->getString($i, "unit_name");
                  $price_id = $productList->getString($i, "price_id");

                  $attribute_category_name = $productList->getString($i, "attribute_category_name");
                  $attribute_id = $productList->getString($i, "attribute_id");
                  $type_id = $productList->getString($i, "type_id");
                  $attribute_name = $productList->getString($i, "attribute_name");
                  $attribute_code = $productList->getString($i, "attribute_code");
                  $unit_in_stock = $productList->getFloat($i, "unit_in_stock");
                  $second_unit_id = $productList->getString($i, "second_unit_id");
                  $factor = $productList->getString($i, "factor");
                  $company_id = $productList->getString($i, "company_id");
                  $commercial_name = $productList->getString($i, "commercial_name");
                  if ($commercial_name == "") {
                    $commercial_name = $productList->getString($i, "company_name");
                  }
                  if ($factor == "" || $factor == "0") {
                    $factor = "1";
                  }
                  $product_type_name = $productList->getString($i, "type_name");
                  $sticker = $productList->getString($i, "sticker");

                ?>
                  <div class="swiper-slide">
                    <div class="product-item" style="height:300px">
                      <?php
                      if ($sticker != "") {
                      ?>
                        <span style="transform:rotate(-45deg); background-color:red;color:white;display:inline-block;padding-left:8px;padding-right:8px;text-align:center">
                          <?php echo $sticker; ?>
                        </span>
                      <?php
                      }
                      ?>
                      <div class="product-thumb">
                        <?php if ($product_type_name != "") { ?>
                          <div style="position:absolute;right: 4px; font-size:10px"><?php echo $product_type_name; ?></div>
                        <?php } ?>
                        <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $price_id; ?>"><img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&h=230" alt="<?php echo $name; ?>"></a>
                        <?php
                        if ($old_price != 0) {
                          $p = -100;
                          if ($unit_price != 0) {
                            $p = $old_price - $unit_price;
                            $p = ($p / $old_price) * 100;
                          }

                        ?>
                          <span class="batch sale"><?php echo intval($p); ?>%</span>
                        <?php
                        }
                        ?>



                        </a>
                      </div>
                      <div class="product-content">
                        <h6><a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $price_id; ?>""
                                        class=" product-title"><?php echo $name ?></a>
                        </h6>

                        <?php
                        if ($attribute_category_name != "") {
                        ?>
                          <p class="quantity"><?php echo $attribute_category_name; ?> : <?php echo $attribute_name; ?></p>
                        <?php
                        }
                        ?>

                        <p class="quantity"><?php echo $appSession->getLang()->find("Stock"); ?> : <?php if ($unit_in_stock == 0) {
                                                                                                      echo $appSession->getLang()->find("Out of stock");
                                                                                                    } else {
                                                                                                      echo $unit_in_stock;
                                                                                                    }  ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="price">
                            <?php echo $appSession->getCurrency()->format($currency_id, $appSession->getTool()->toDouble($unit_price)); ?>
                            / <?php echo $unit_name ?>
                          </div>
                          <?php if ($unit_in_stock > 0) {
                          ?>
                            <a href="javascript:addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id; ?>', '<?php echo $price_id; ?>','<?php echo $type_id; ?>', function(status, message){ loadCard(); })">
                              <span class="cart-btn"><i class="fas fa-shopping-cart"></i><?php echo $appSession->getLang()->find("Add"); ?></span>

                            </a>
                          <?php
                          }
                          ?>

                          </a>
                        </div>

                      </div>
                    </div>
                  </div>

                <?php
                }
                ?>


              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php } ?>
  <!-- trending product-section end -->

  <script>
    function addingProduct(type) {
      var ctr = document.getElementById('qty');
      if (ctr.value == '') {
        alert('Chọn số lượng');
        ctr.focus();
        return;
      }
      var quantity = parseFloat(ctr.value);
      if (quantity <= 0) {
        alert("Số lượng phải lớn hơn 0");
        ctr.focus();
        return;
      }
      ctr = document.getElementById('editdescription');
      var description = ctr.value;

      addProduct('<?php echo $product_product_id; ?>', '<?php echo $product_currency_id; ?>', '<?php echo $product_unit_id; ?>', '<?php echo $product_attribute_id; ?>',
        quantity, <?php echo $product_unit_price; ?>, '', 1, description, '<?php echo $company_id; ?>', '<?php echo $product_price_id; ?>', '<?php echo $product_type_id; ?>',
        function(status, message) {
          if (type == 'card') {
            document.location.href = '<?php echo URL; ?>'
          } else if (type == 'checkout') {
            document.location.href = '<?php echo URL; ?>checkout'
          }

        }
      );

    }
  </script>
<?php
}
?>
