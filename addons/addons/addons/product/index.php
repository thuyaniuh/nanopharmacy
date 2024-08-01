<?php
require_once(ABSPATH . 'api/Product.php');
$product = new Product($appSession);
if (strlen($selected_id) == 36) {
  $product->addProductSeen($selected_id);
}
$sql = "SELECT d1.id, d5.unit_id, d5.attribute_id,	d5.type_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d5.unit_price, d5.old_price, d6.name AS unit_name, d5.currency_id";
$sql = $sql . ", d9.name AS attribute_name, d10.name attribute_category_name, d5.factor";
$sql = $sql . ", d1.company_id, d7.commercial_name, d7.name AS company_name, d5.id AS price_id, d8.document_id AS price_document_id, d5.description, d11.name AS type_name, 0.0 AS unit_in_stock";
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
$sql = $sql . " WHERE d5.code='" . $code . "'";
$msg->add("query", $sql);

$productList = $appSession->getTier()->getTable($msg);
$product = new Product($appSession);
$productList = $product->countProduct($productList);

$product_price_id = "";
 $product_id = "";
$photos = [];
if ($productList->getRowCount() > 0) {
	$product_price_id = $productList->getString(0, "price_id");
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

  $sql = "SELECT d1.document_id, d2.name FROM document_rel d1 LEFT OUTER JOIN document d2 ON(d1.document_id = d2.id) WHERE d1.status =0 AND d1.publish=1 AND (d1.rel_id='" . $product_price_id . "' OR d1.rel_id='" . $product_id . "')";
  $sql = $sql . " ORDER BY d1.create_date ASC";
  $msg->add("query", $sql);
  $values = $appSession->getTier()->getArray($msg);
  for ($i = 0; $i < count($values); $i++) {
    $photos[count($photos)] = [$values[$i][0], $values[$i][1]];
  }
  
  $dt_product_group = $product->productGroup();
  
  $sql = "SELECT d1.commercial_name, p.document_id, d1.description, d1.address, d1.phone, d1.code FROM res_company d1 LEFT OUTER JOIN poster p ON(d1.id = p.rel_id AND p.status =0 AND p.publish =1) WHERE d1.id ='".$company_id."'";

	$msg->add("query", $sql);
	
	$dt_company = $appSession->getTier()->getTable($msg);
									
	$company_name = "";
	$company_document_id = "";
	$company_slogan = "";
	$company_address = "";
	$company_phone = "";
	$company_code = "";
	if($dt_company->getRowCount()>0)
	{
		$company_code = $dt_company->getString(0, "company_code");
		$company_name = $dt_company->getString(0, "commercial_name");
		$company_document_id = $dt_company->getString(0, "document_id");
		$company_slogan = $dt_company->getString(0, "description");
		$company_address = $dt_company->getString(0, "address");
		$company_phone = $dt_company->getString(0, "phone");
		
	}

?>

<section class="breadscrumb-section pt-0">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-12">
				<div class="breadscrumb-contain">
					<h2><?php echo $appSession->getLang()->find("Product"); ?></h2>
					<nav>
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item">
								<a href="index.html">
									<i class="fa-solid fa-house"></i>
								</a>
							</li>

							<li class="breadcrumb-item active"><?php echo $product_name ?></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</section>


 <section class="product-section">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-9 col-xl-8 col-lg-7 wow fadeInUp">
                    <div class="row g-4">
                        <div class="col-xl-6 wow fadeInUp">
                            <div class="product-left-box">
                                <div class="row g-2">
                                    <div class="col-xxl-10 col-lg-12 col-md-10 order-xxl-2 order-lg-1 order-md-2">
                                        <div class="product-main-2 no-arrow">
											 <?php for ($i = 0; $i < count($photos); $i++) {
												$document_id = $photos[$i][0];
												$name = $photos[$i][1];
											  ?>
                                            <div>
                                                <div class="slider-image">
                                                    <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>" id="img-1"
                                                        data-zoom-image="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>"
                                                        class="img-fluid image_zoom_cls-0 blur-up lazyload" alt="<?php echo $name;?>">
                                                </div>
                                            </div>
											 <?php } ?>
                                            
                                        </div>
                                    </div>

                                    <div class="col-xxl-2 col-lg-12 col-md-2 order-xxl-1 order-lg-2 order-md-1">
                                        <div class="left-slider-image-2 left-slider no-arrow slick-top">
											 <?php for ($i = 0; $i < count($photos); $i++) {
												$document_id = $photos[$i][0];
												$name = $photos[$i][1];
											  ?>
                                            <div>
                                                <div class="sidebar-image">
                                                    <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>"
                                                        class="img-fluid blur-up lazyload" alt="<?php echo $name;?>">
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

                        <div class="col-xl-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="right-box-contain">
                                <h6 class="offer-top">30% Off</h6>
                                <h2 class="name"><?php echo $product_name; ?></h2>
                                <div class="price-rating">
                                    <h3 class="theme-color price"><?php echo $appSession->getCurrency()->format($product_currency_id, $product_unit_price); ?>/ <?php echo $unit_name ?>
                    <?php if ($old_price != 0) { ?>
                      <del> (<?php echo $old_price; ?>)</del>
                    <?php } ?> <span
                                            class="offer theme-color">(8% off)</span></h3>
                                    <div class="product-rating custom-rate">
                                        <ul class="rating">
                                            <li>
                                                <i data-feather="star" class="fill"></i>
                                            </li>
                                            <li>
                                                <i data-feather="star" class="fill"></i>
                                            </li>
                                            <li>
                                                <i data-feather="star" class="fill"></i>
                                            </li>
                                            <li>
                                                <i data-feather="star" class="fill"></i>
                                            </li>
                                            <li>
                                                <i data-feather="star"></i>
                                            </li>
                                        </ul>
                                        <span class="review">23 Customer Review</span>
                                    </div>
                                </div>

                                <div class="procuct-contain">
                                    <p><?php echo $description;?>
                                    </p>
                                </div>

                              

                                <div class="time deal-timer product-deal-timer mx-md-0 mx-auto" id="clockdiv-1"
                                    data-hours="1" data-minutes="2" data-seconds="3">
                                    <div class="product-title">
                                        <h4><?php echo $appSession->getLang()->find("Hurry up");?>!</h4>
                                    </div>
                                    <ul>
                                        <li>
                                            <div class="counter d-block">
                                                <div class="days d-block">
                                                    <h5></h5>
                                                </div>
                                                <h6><?php echo $appSession->getLang()->find("Days");?></h6>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="counter d-block">
                                                <div class="hours d-block">
                                                    <h5></h5>
                                                </div>
                                                <h6><?php echo $appSession->getLang()->find("Hours");?></h6>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="counter d-block">
                                                <div class="minutes d-block">
                                                    <h5></h5>
                                                </div>
                                                <h6><?php echo $appSession->getLang()->find("Min");?></h6>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="counter d-block">
                                                <div class="seconds d-block">
                                                    <h5></h5>
                                                </div>
                                                <h6><?php echo $appSession->getLang()->find("Sec");?></h6>
                                            </div>
                                        </li>
                                    </ul>
                                </div>



                                <div class="note-box product-packege">
                                    <div class="cart_qty qty-box product-qty">
                                        <div class="input-group">
                                           <button type="button" class="qty-left-minus" data-type="minus"
                                                data-field="">
                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                            </button>
                                            <input class="form-control input-number qty-input" type="text"
                                                name="quantity" value="0" id="quantity">
												 <button type="button" class="qty-right-plus" data-type="plus" data-field="">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </button>
                                            
                                        </div>
                                    </div>

                                    <button onclick="addingProduct('card');"
                                        class="btn btn-md bg-dark cart-button text-white w-100"><?php echo $appSession->getLang()->find("Add To Cart");?></button>
                                </div>

                                <div class="buy-box">
                                    <a href="<?php echo URL;?>wishlist/?price_id=<?php echo $price_id;?>" class="notifi-wishlist">
                                        <i data-feather="heart"></i>
                                        <span><?php echo $appSession->getLang()->find("Add To Wishlist");?></span>
                                    </a>

                                    <a href="<?php echo URL;?>compare/?id=<?php echo $price_id;?>">
                                        <i data-feather="shuffle"></i>
                                        <span><?php echo $appSession->getLang()->find("Add To Compare");?></span>
                                    </a>
                                </div>

                               
                               
                            </div>
                        </div>
						<?php

						$sql = "SELECT d1.id, d1.name, d1.description, d1.content, d1.category_id, d2.name FROM res_rel d LEFT OUTER JOIN post d1 ON(d.res_id = d1.id) LEFT OUTER JOIN post_category d2 ON(d1.category_id = d2.id) WHERE d.status =0 AND d1.status =0 AND d.rel_id='" . $product_product_id . "' AND d1.status =0 AND d1.publish = 1";
						$sql = $sql . " ORDER BY d2.sequence ASC";
						$msg->add("query", $sql);
						
			

						$posts = $appSession->getTier()->getArray($msg);
						$post_categories = $appSession->getTool()->selectDistinct($posts, [4, 5]);
						

						?>
                        <div class="col-12">
                            <div class="product-section-box">
                                <ul class="nav nav-tabs custom-nav" id="myTab" role="tablist">
									 <?php
									for ($i = 0; $i < count($post_categories); $i++) {
										$category_id = $post_categories[$i][0];
										$category_name = $post_categories[$i][1];
									?>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link <?php if($i ==0){ ?> active <?php } ?>" id="description-tab" data-bs-toggle="tab"
                                            data-bs-target="#tb<?php echo $i;?>" type="button" role="tab"
                                            aria-controls="tb<?php echo $i;?>" aria-selected="true"><?php echo $category_name;?></button>
                                    </li>
									<?php
									}
									?>
									<li class="nav-item" role="presentation">
                                        <button class="nav-link <?php if(count($post_categories) ==0){ ?> active <?php } ?>" id="description-tab" data-bs-toggle="tab"
                                            data-bs-target="#tbreview" type="button" role="tab"
                                            aria-controls="tbreview" aria-selected="true"><?php echo $appSession->getLang()->find("Reviews");?><span id="reviewCount"></span></button>
                                    </li>
                                   
                                </ul>

                                <div class="tab-content custom-tab" id="myTabContent">
									 <?php
									for ($i = 0; $i < count($post_categories); $i++) {
										$category_id = $post_categories[$i][0];
										

									?>
                                    <div class="tab-pane fade show <?php if($i ==0){ ?> active <?php } ?>" id="tb<?php echo $i;?>" role="tabpanel"
                                        aria-labelledby="description-tab">
                                        <div class="product-description">
                                            <?php
											  for ($j = 0; $j < count($posts); $j++) {
												if ($posts[$j][4] == $category_id) {
												  $post_name = $posts[$j][1];
												  $post_content = $posts[$j][3];
											  ?>
												  <b><?php echo $post_name; ?></b>
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
									<div class="tab-pane fade show <?php if(count($post_categories) ==0){ ?> active <?php } ?>" id="tbreview" role="tabpanel"
                                        aria-labelledby="description-tab">
                                        <div class="product-description" >
											<div class="row">
												<div class="col-12" id="reviewContent">
												</div>
											</div>
											<?php
											if($appSession->getConfig()->getProperty("user_id") != "")
											{
											?>
											<div class="row">
											   <div class="col-12">
													<div class="form-floating theme-form-floating">
														<textarea class="form-control"
															placeholder="Leave a comment here" id="review_comment"
															style="height: 150px"></textarea>
														<label for="review_comment">Write Your Comment</label>
													</div>
													<br>
													<button class="btn theme-bg-color text-white" onclick="sendReview()">Send</button>
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
						
                    </div>
                </div>

                <div class="col-xxl-3 col-xl-4 col-lg-5 d-none d-lg-block wow fadeInUp">
                    <div class="right-sidebar-box">
                     <div class="vendor-box">
					 <a href="<?php echo URL;?>shop/<?php echo $appSession->getTool()->validUrl($company_name);?>/<?php echo $company_code;?>">
                            <div class="verndor-contain">
                                <div class="vendor-image">
                                   <?php
								if($company_document_id != "")
								{
								?>
								<img src="<?php echo URL;?>document/?id=<?php echo $company_document_id;?>&h=120" alt="<?php echo $company_name;?>">
								<?php
								}
								?>
                                </div>

                                <div class="vendor-name">
                                    <h5 class="fw-500"><?php echo $company_name;?></h5>

                                    <div class="product-rating mt-1">
                                        <ul class="rating">
                                            <li>
                                                <i data-feather="star" class="fill"></i>
                                            </li>
                                            <li>
                                                <i data-feather="star" class="fill"></i>
                                            </li>
                                            <li>
                                                <i data-feather="star" class="fill"></i>
                                            </li>
                                            <li>
                                                <i data-feather="star" class="fill"></i>
                                            </li>
                                            <li>
                                                <i data-feather="star"></i>
                                            </li>
                                        </ul>
                                        <span>(36 Reviews)</span>
										
                                    </div>
									
                                </div>
								
								
                            </div>
							</a>
							

                            <p class="vendor-detail"><?php echo $company_slogan;?></p>

							
                            <div class="vendor-list">
                                <ul>
									
									<li>
                                        <div class="address-contact">
                                           
                                            <h5><?php echo $appSession->getLang()->find("Follow Us");?>: <span class="text-content"><div class="vendor-list">
								
												<ul>
													<li>
														<a href="javascript:void(0)">
															<i class="fa-brands fa-facebook-f"></i>
														</a>
													</li>
													<li>
														<a href="javascript:void(0)">
															<i class="fa-brands fa-google-plus-g"></i>
														</a>
													</li>
													<li>
														<a href="javascript:void(0)">
															<i class="fa-brands fa-twitter"></i>
														</a>
													</li>
													<li>
														<a href="javascript:void(0)">
															<i class="fa-brands fa-instagram"></i>
														</a>
													</li>
												</ul>
							</div></span></h5>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="address-contact">
                                            <i data-feather="map-pin"></i>
                                            <h5><?php echo $appSession->getLang()->find("Address");?>: <span class="text-content"><?php echo $company_address;?></span></h5>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="address-contact">
                                            <i data-feather="headphones"></i>
                                            <h5><?php echo $appSession->getLang()->find("Contact Us");?>: <span class="text-content"><?php echo $company_phone;?></span></h5>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>  
						<?php
					for ($ii = 0; $ii < $dt_product_group->getRowCount(); $ii++) {
						if ($dt_product_group->getString($ii, "group_category_name") == "TRENDING-PRODUCT") 
						{
							$group_id = $dt_product_group->getString($ii, "id");
							$code = $dt_product_group->getString($ii, "code");
							$name = $dt_product_group->getString($ii, "name_lg");
							$document_id = $dt_product_group->getString($ii, "document_id");
							if ($name == "") {
								$name = $dt_product_group->getString($ii, "name");
							}
							$productList = $product->productByGroupById($appSession, $group_id, 6);
							
					?>
                        <!-- Trending Product -->
                        <div class="pt-25">
                            <div class="category-menu">
                                <h3><?php echo $appSession->getLang()->find("Trending Products");?></h3>

                                <ul class="product-list product-right-sidebar border-0 p-0">
									<?php
								for($i =0; $i<$productList->getRowCount(); $i++)
								{
									
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
									$description = $productList->getString($i, "description");
								?>
                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="<?php echo URL;?>/document/?id=<?php echo $document_id;?>&h=95"
                                                    class="img-fluid blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $price_id;?>">
                                                        <h6 class="name"><?php echo $name;?></h6>
                                                    </a>
                                                    <span><?php echo $unit_name;?></span>
                                                    <h6 class="price theme-color"><?php echo $appSession->getCurrency()->format($currency_id, $unit_price);?></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <?php
								}
								?>
                                </ul>
                            </div>
                        </div>
						<?php
						}
					}
					?>

						<?php
					for ($ii = 0; $ii < $dt_product_group->getRowCount(); $ii++) {
						if ($dt_product_group->getString($ii, "group_category_name") == "HOME-LEFT") 
						{
							$code = $dt_product_group->getString($ii, "code");
							$name = $dt_product_group->getString($ii, "name_lg");
							$document_id = $dt_product_group->getString($ii, "document_id");
							if ($name == "") {
								$name = $dt_product_group->getString($ii, "name");
							}
							$content = $dt_product_group->getString($ii, "content");
					?>
                        <!-- Banner Section -->
                        <div class="ratio_156 pt-25">
                            <div class="home-contain">
                                <img src="<?php echo URL;?>document/?id=<?php echo $document_id;?>" class="bg-img blur-up lazyload"
                                    alt="">
                                <div class="home-detail p-top-left home-p-medium">
                                    <div>
                                        <?php echo $content;?>
                                        <button onclick="location.href = '<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/g-<?php echo $code; ?>';"
                                            class="btn btn-animation btn-md fw-bold mend-auto">Shop Now <i
                                                class="fa-solid fa-arrow-right icon"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php
						}
					}
					?>
                    </div>
                </div>
            </div>
        </div>
    </section>

 


  <!-- trending product-section start -->
  <?php
  $productList = $product->productRel($appSession, $product_id);
  if ($productList->getRowCount() > 0) {
  ?>
    <section class="section-b-space shop-section">
  <div class="container-fluid-lg">
 <div class="title d-block">
		<h2><?php echo $appSession->getLang()->find("Product Links");?></h2>
		<span class="title-leaf">
			<svg class="icon-width">
				<use xlink:href="<?php echo URL;?>/assets/svg/leaf.svg#leaf"></use>
			</svg>
		</span>
		<p><?php echo $description;?></p>
  </div>
  <div class="product-border overflow-hidden wow fadeInUp">
		<div class="product-box-slider no-arrow">
			<?php
			for($i =0; $i<$productList->getRowCount(); $i++)
			{
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
				$description = $productList->getString($i, "description");
			?>
			<div>
				<div class="row m-0">
					<div class="col-12 px-0">
						<div class="product-box">
							<div class="product-image">
								<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $price_id;?>">
									<img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&w=145"
										class="img-fluid blur-up lazyload" alt="">
								</a>
								<ul class="product-option">
									<li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
										<a href="javascript:openPopup('<?php echo URL;?>addons/product_popup/?id=<?php echo $price_id;?>')">
											<i data-feather="eye"></i>
										</a>
									</li>

									<li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
										<a href="<?php echo URL;?>compare/?id=<?php echo $price_id;?>">
											<i data-feather="refresh-cw"></i>
										</a>
									</li>

									<li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
										<a href="<?php echo URL;?>wishlist/?price_id=<?php echo $price_id;?>" class="notifi-wishlist">
											<i data-feather="heart"></i>
										</a>
									</li>
									<?php
									if($appSession->getConfig()->getProperty("user_group_id") == "cfe1a96e-92e4-4f4b-ccba-93e03c915b1e")
									{
									?>
									<li data-bs-toggle="tooltip" data-bs-placement="top" title="seller">
										<a href="<?php echo URL;?>seller_detail/?price_id=<?php echo $price_id;?>" class="notifi-wishlist">
											<i data-feather="package"></i>
										</a>
									</li>
									<?php
									}
									?>
								</ul>
							</div>
							<div class="product-detail">
								<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $price_id;?>">
									<h6 class="name"><?php echo $name;?>
									</h6>
								</a>

								<h5 class="sold text-content">
									<span class="theme-color price"><?php echo $appSession->getCurrency()->format($currency_id, $unit_price);?></span>
									<?php if($old_price>0){
										?>
									<del><?php echo $appSession->getCurrency()->format($currency_id, $old_price);?></del>
									<?php
									}
									?>
								</h5>

								<div class="product-rating mt-sm-2 mt-1">
									<ul class="rating">
										<li>
											<i data-feather="star" class="fill"></i>
										</li>
										<li>
											<i data-feather="star" class="fill"></i>
										</li>
										<li>
											<i data-feather="star" class="fill"></i>
										</li>
										<li>
											<i data-feather="star" class="fill"></i>
										</li>
										<li>
											<i data-feather="star"></i>
										</li>
									</ul>

									<h6 class="theme-color"><?php if ( $unit_in_stock == 0) {
				echo $appSession->getLang()->find("Out of stock");
			} else {
				echo $appSession->getLang()->find("In Stock");
			}  ?></h6>
									
								</div>
							<?php 
							$sale_quantity = 0;
							for($k =0; $k<$saleProductList->getRowCount(); $k++)
							{
								if($saleProductList->getString($k, "rel_id") == $price_id)
								{
									$sale_quantity = $saleProductList->getFloat($k, "quantity");
								}
							}
							if($unit_in_stock>0 || $sale_quantity != 0)
							{
																						
							?>
								<div class="add-to-cart-box">
									<button class="btn btn-add-cart addcart-button" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })"><?php echo $appSession->getLang()->find("Add");?>
										<span class="add-icon">
											<i class="fa-solid fa-plus"></i>
										</span>
									</button>
									<div class="cart_qty qty-box" <?php if($sale_quantity>0){ echo " open "; } ?>>
										<div class="input-group">
											<button type="button" class="qty-left-minus" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', -1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })"
												data-type="minus" data-field="">
												<i class="fa fa-minus" aria-hidden="true"></i>
											</button>
											<input class="form-control input-number qty-input"
												type="text" name="quantity" value="<?php echo $sale_quantity;?>">
											<button type="button" class="qty-right-plus" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })"
												data-type="plus" data-field="">
												<i class="fa fa-plus" aria-hidden="true"></i>
											</button>
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
			<?php
			}
			?>

		  
		</div>
	</div>
	</div>
</div>
  <?php } ?>
  <!-- trending product-section end -->

  <script>
    function addingProduct(type) {
      var ctr = document.getElementById('quantity');
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
     
      var description = "";

      addProduct('<?php echo $product_product_id; ?>', '<?php echo $product_currency_id; ?>', '<?php echo $product_unit_id; ?>', '<?php echo $product_attribute_id; ?>',
        quantity, <?php echo $product_unit_price; ?>, '', 1, description, '<?php echo $company_id; ?>', '<?php echo $product_price_id; ?>', '<?php echo $product_type_id; ?>',
        function(status, message) {
          if (type == 'card') {
            document.location.href = '<?php echo URL; ?>checkout'
          } else if (type == 'checkout') {
            document.location.href = '<?php echo URL; ?>checkout'
          }

        }
      );

    }
	function loadReview(){
		var _url = "<?php echo URL;?>addons/note/?ac=view&rel_id=<?php echo $product_id;?>";
		
		loadPage('reviewContent', _url, function(status, message) {
		  if (status == 0) {
			var json = JSON.parse(message);
			document.getElementById('reviewCount').innerHTML = "(" + json.length + ")";
			var s = '<div class="review-people\"><ul class="review-list">';
			for (var i = 0; i < json.length; i++)
			{
				var create_date = Date.parse(json[i].create_date);
				var formattedDate = new Intl.DateTimeFormat('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(create_date);
				
				s = s + '<li><div class="comment_text"><div class="people-comment">';
					s = s + '<p><strong>' + json[i].account_name + ' </strong>- ' + formattedDate + '</p>';
					s = s + '<span>' + json[i].content+ '</span>';
				s = s + '</div></li>';
					
				
			}
			s = s + '</ul></div>';
			document.getElementById('reviewContent').innerHTML = s;
		  }
		}, true);
	}
	function sendReview(){
		var ctr = document.getElementById('review_comment');
		if(ctr.value == '')
		{
			alert('<?php echo $appSession->getLang()->find("Please enter your message");?>');
			ctr.focus();
			return;
		}
		var content = ctr.value;
		var _url = "<?php echo URL;?>addons/note/?ac=add&rel_id=<?php echo $product_id;?>";
		_url = _url + "&content=" + encodeURIComponent(content);
		
		loadPage('reviewContent', _url, function(status, message) {
		  if (status == 0) {
			if(message.indexOf("OK") != -1)
			{
				ctr.value = "";
				loadReview();
			}else{
				alert(message);
			}
			
		  }
		}, true);
	}
	loadReview();
  </script>
<?php
}
?>
