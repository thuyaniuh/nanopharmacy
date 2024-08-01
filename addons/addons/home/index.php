<?php
$product = new Product($appSession);


$dt_product_group = $product->productGroup();
$hasHome = false;


for ($i = 0; $i < $dt_product_group->getRowCount(); $i++) {
  if ($dt_product_group->getString($i, "group_category_name") == "BANNER") {
	   $id = $dt_product_group->getString($i, "id");
		$code = $dt_product_group->getString($i, "code");
		$name = $dt_product_group->getString($i, "name_lg");
		$document_id = $dt_product_group->getString($i, "document_id");
		if ($name == "") {
			$name = $dt_product_group->getString($i, "name");
		}
		$description = $dt_product_group->getString($i, "description");
		$content = $dt_product_group->getString($i, "content");
		$document_id = $dt_product_group->getString($i, "document_id");
   ?>
   <section class="home-section-2 home-section-bg pt-0 overflow-hidden">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="slider-animate">
                        <div>
                            <div class="home-contain rounded-0 p-0">
                                <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>"
                                    class="img-fluid bg-img blur-up lazyload" alt="">
                                <div class="home-detail home-big-space p-center-left home-overlay position-relative">
                                    <div class="container-fluid-lg">
                                        <div>
                                            <h6 class="ls-expanded theme-color text-uppercase"><?php echo $name;?>
                                            </h6>
                                           
                                            <h5 class="text-content"><?php echo $content;?>
                                            </h5>
                                            <button
                                                class="btn theme-bg-color btn-md text-white fw-bold mt-md-4 mt-2 mend-auto"
                                                onclick="location.href = '<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/g-<?php echo $code; ?>';"><?php echo $appSession->getLang()->find("Shop Now");?><i
                                                    class="fa-solid fa-arrow-right icon"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   <?php
   break;
  }
}
?>

<!-- Banner Section Start -->
<section class="banner-section banner-small ratio_65">
	<div class="container-fluid-lg">
		<div class="slider-4-banner no-arrow slick-height">
			<?php
			for ($ii = 0; $ii < $dt_product_group->getRowCount(); $ii++) {
				if ($dt_product_group->getString($ii, "group_category_name") == "BANNER-SLIDER") 
				{
					$code = $dt_product_group->getString($ii, "code");
					$name = $dt_product_group->getString($ii, "name_lg");
					$document_id = $dt_product_group->getString($ii, "document_id");
					if ($name == "") {
						$name = $dt_product_group->getString($ii, "name");
					}
					$content = $dt_product_group->getString($ii, "content");
			?>
			<div>
				<div class=" banner-contain-3 hover-effect">
					<a href="javascript:void(0)">
						<img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>" class="bg-img blur-up lazyload" alt="<?php echo $name;?>">
					</a>
					<div class="banner-detail p-center-left w-75 banner-p-sm mend-auto">
						<div>
							<?php echo $content;?>
							<button onclick="location.href = '<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/g-<?php echo $code; ?>';"
								class="btn shop-now-button mt-3 ps-0 mend-auto theme-color fw-bold"><?php echo $appSession->getLang()->find("Shop Now");?> <i
									class="fa-solid fa-chevron-right"></i></button>
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
</section>
<!-- Banner Section End -->

<!-- Category Section Start -->
<section class="category-section-3">
	<div class="container-fluid-lg">
		<div class="title">
			<h2><?php echo $appSession->getLang()->find("Shop By Categories");?></h2>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="category-slider-1 arrow-slider wow fadeInUp">
				
					<?php
					$ids = "";
					for($i =0; $i<$dt_category->getRowCount(); $i++)
					{
						if($dt_category->getString($i, "parent_id") != "")
						{
							continue;
						}
						$id = $dt_category->getString($i, "id");
						if($ids != "")
						{
							$ids = $ids ." OR ";
						}
						$ids = $ids." d2.parent_id ='".$id."'";
					}
					$sql = "SELECT d2.parent_id, COUNT(d1.id) AS c FROM product d1 LEFT OUTER JOIN product_category d2 ON(d1.category_id = d2.id) WHERE d1.status =0 ";
					if($ids != ""){
						$sql = $sql." AND (".$ids.")";
					}else{
						$sql = $sql." AND 1=0";
					}
					$sql = $sql." GROUP BY d2.parent_id";
					$msg->add("query", $sql);
					$dt_count = $appSession->getTier()->getTable($msg);
					
					for($i =0; $i<$dt_category->getRowCount(); $i++)
					{
						if($dt_category->getString($i, "parent_id") != "")
						{
							continue;
						}
						$category_code = $dt_category->getString($i, "code");
						$category_name = $dt_category->getString($i, "name_lg");

						if ($category_name == "") {
							$category_name = $dt_category->getString($i, "name");
						}
						$document_id = $dt_category->getString($i, "document_id");
						if($document_id == "")
						{
							continue;
						}
						$category_id = $dt_category->getString($i, "id");
						$count = 0;
						for($j =0; $j<$dt_count->getRowCount(); $j++)
						{
							if($dt_count->getString($j, "parent_id") == $category_id)
							{
								$count = $dt_count->getFloat($j, "c");
								break;
							}
						}
					?>
					<div>
						<div class="category-box-list" style="min-height: 215px;" > 
							<a href="shop-left-sidebar.html" class="category-name">
								<h4><?php echo $category_name;?></h4>
								<h6><?php echo $appSession->getFormats()->getDOUBLE()->format($count);?> <?php echo $appSession->getLang()->find("items");?></h6>
							</a>
							<div class="category-box-view">
								<a href="<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name); ?>/c-<?php echo $category_code; ?>">
									<img src="<?php echo URL;?>document/?id=<?php echo $document_id;?>"
										class="img-fluid blur-up lazyload" alt="<?php echo $category_name;?>">
								</a>
								<button onclick="location.href = '<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name); ?>/c-<?php echo $category_code; ?>';" class="btn shop-button">
									<span><?php echo $appSession->getLang()->find("Shop Now");?></span>
									<i class="fas fa-angle-right"></i>
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
</section>
<!-- Category Section End -->
<?php
for ($n = 0; $n < $dt_product_group->getRowCount(); $n++) {
  if ($dt_product_group->getString($n, "group_category_name") == "TOP_SALE_TODAY") {
	$group_id = $dt_product_group->getString($n, "id");
	$code = $dt_product_group->getString($n, "code");
	$name = $dt_product_group->getString($n, "name_lg");
	$document_id = $dt_product_group->getString($n, "document_id");
	if ($name == "") {
	  $name = $dt_product_group->getString($n, "name");
	}
	$description = $dt_product_group->getString($n, "description");
	$productList = $product->productByGroupById($appSession, $group_id, -1);
	?>
<section class="product-section-3">
	<div class="container-fluid-lg">
		<div class="title">
			<h2><?php echo $name;?></h2>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="slider-7_1 arrow-slider img-slider">
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
						$price_code = $productList->getString($i, "price_code");

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
						<div class="product-box-4 wow fadeInUp" data-wow-delay="0.35s">
							<div class="product-image product-image-2">
								<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $price_code;?>">
									<img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&h=105"
										class="img-fluid blur-up lazyload" alt="<?php echo $name;?>">
								</a>

								<ul class="option">
									<li data-bs-toggle="tooltip" data-bs-placement="top" title="Quick View">
										<a href="javascript:javascript:openPopup('<?php echo URL;?>addons/product_popup/?id=<?php echo $price_id;?>')">
											<i class="iconly-Show icli"></i>
										</a>
									</li>
									<li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
										<a href="<?php echo URL;?>wishlist/?price_id=<?php echo $price_id;?>" class="notifi-wishlist">
											<i class="iconly-Heart icli"></i>
										</a>
									</li>
									<li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
										<a href="<?php echo URL;?>compare/?price_id=<?php echo $price_id;?>">
											<i class="iconly-Swap icli"></i>
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
								<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $price_code;?>">
									<h5 class="name text-title"><?php echo $name;?></h5>
								</a>
								<h5 class="price theme-color"><?php echo $appSession->getCurrency()->format($currency_id, $unit_price);?><?php if($old_price>0){
															?><del><?php echo $appSession->getCurrency()->format($currency_id, $old_price);?></del><?php
														}
														?></h5>
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
								<div class="addtocart_btn">
									<button class="add-button addcart-button btn buy-button text-light" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })">
										<i class="fa-solid fa-plus"></i>
									</button>
									<div class="qty-box cart_qty <?php if($sale_quantity>0){ echo " open "; } ?>">
										<div class="input-group">
											<button type="button" class="btn qty-left-minus" data-type="minus"
												data-field="" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', -1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })">
												<i class="fa fa-minus" aria-hidden="true"></i>
											</button>
											<input class="form-control input-number qty-input" type="text"
												name="quantity" value="<?php echo $sale_quantity;?>">
											<button type="button" class="btn qty-right-plus" data-type="plus"
												data-field="" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })">
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
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>  
<?php
  }
  }
?>  

<section class="bank-section overflow-hidden">
	<div class="container-fluid-lg">
		<div class="title">
			<h2><?php echo $appSession->getLang()->find("Bank & Wallet Offers"); ?></h2>
		</div>
		<div class="slider-bank-3 arrow-slider slick-height">
			<?php
			for ($ii = 0; $ii < $dt_product_group->getRowCount(); $ii++) {
				if ($dt_product_group->getString($ii, "group_category_name") == "BANNER-SUB") 
				{
					$code = $dt_product_group->getString($ii, "code");
					$name = $dt_product_group->getString($ii, "name_lg");
					$document_id = $dt_product_group->getString($ii, "document_id");
					if ($name == "") {
						$name = $dt_product_group->getString($ii, "name");
					}
					$content = $dt_product_group->getString($ii, "content");
			?>
			<div>
				<div class="bank-offer">
					<div class="bank-header" style="min-height: 393px;">
					<div class="bank-left w-100" style="min-height:250px">
							<div class="bank-image">
								<img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&h=320" class="img-fluid" alt="">
							</div>
							<div class="bank-name">
								<?php echo $content;?>
							</div>
						</div>

						<div class="bank-right w-100" style="min-height:250px">
							<img src="../assets/images/grocery/bank/price/1.svg" class="img-fluid" alt="">
						</div>
					</div>

					<div class="bank-footer bank-footer-1">
						<h4>Code :
							<input id="clipboardexample" value="MULTICART" />
						</h4>
						<button type="button" class="bank-coupon btn" id="copyText" data-clipboard-action="copy"
							data-clipboard-target="#clipboardexample">Copy Code</button>
					</div>
				</div>
			</div>
			<?php
				}
			}
			?>
			
		</div>
	</div>
</section>

<section class="product-section product-section-3">
	<div class="container-fluid-lg">
		<div class="title">
			<h2><?php echo $appSession->getLang()->find("Top Selling Items"); ?></h2>
		</div>
		<div class="row g-sm-4 g-3">
			<div class="col-xxl-4 col-lg-5 order-lg-2">
				<?php
				$no = 0;
				for ($n = 0; $n < $dt_product_group->getRowCount(); $n++) {
				  if ($dt_product_group->getString($n, "group_category_name") == "HOME-CUPBOARD") 
				  {
					$group_id = $dt_product_group->getString($n, "id");
					$code = $dt_product_group->getString($n, "code");
					$name = $dt_product_group->getString($n, "name_lg");
					$document_id = $dt_product_group->getString($n, "document_id");
					if ($name == "") {
					  $name = $dt_product_group->getString($n, "name");
					}
					$description = $dt_product_group->getString($n, "description");
					$productList = $product->productByGroupById($appSession, $group_id, 30);
				?>	
				<div class="product-bg-image wow fadeInUp">
					<div class="product-title product-warning">
						<h2><?php echo $name;?></h2>
					</div>

					<div class="product-box-4 product-box-3 rounded-0">
						<div class="deal-box">
							<div class="circle-box">
								<div class="shape-circle">
									<img src="<?php echo URL;?>/assets/images/grocery/circle.svg" class="blur-up lazyload" alt="">
									<div class="shape-text">
										<h6>Hot <br> Deal</h6>
									</div>
								</div>
							</div>
						</div>
						<div class="top-selling-slider product-arrow">
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
								<div class="product-image">
									<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code;?>">
										<img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>"
											class="img-fluid product-image blur-up lazyload" alt="<?php echo $name;?>">
									</a>

									<ul class="option">
										<li data-bs-toggle="tooltip" data-bs-placement="top" title="Quick View">
											<a href="javascript:openPopup('<?php echo URL;?>addons/product_popup/?id=<?php echo $price_id;?>')">
												<i class="iconly-Show icli"></i>
											</a>
										</li>
										<li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
											<a href="<?php echo URL;?>wishlist/?price_id=<?php echo $price_id;?>" class="notifi-wishlist">
												<i class="iconly-Heart icli"></i>
											</a>
										</li>
										<li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
											<a href="<?php echo URL;?>compare/?price_id=<?php echo $price_id;?>">
												<i class="iconly-Swap icli"></i>
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

								<div class="product-detail text-center">
									<ul class="rating justify-content-center">
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
									<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code;?>">
										<h3 class="name w-100 mx-auto text-center"><?php echo $name;?></h3>
									</a>
									<h3 class="price theme-color d-flex justify-content-center">
										<?php echo $appSession->getCurrency()->format($currency_id, $unit_price);?></span>
													<?php if($old_price>0){
														?>
													<del><?php echo $appSession->getCurrency()->format($currency_id, $old_price);?></del>
													<?php
													}
													?>
									</h3>
									<div class="progress custom-progressbar">
										<div class="progress-bar" style="width: 79%" role="progressbar"
											aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<h5 class="text-content">Solid : <span class="text-dark">30 items</span>
										<span class="ms-auto text-content">Hurry up offer end in</span></h5>

									<div class="timer timer-2 ms-0 my-4" id="clockdiv-1" data-hours="1"
										data-minutes="2" data-seconds="3">
										<ul class="d-flex justify-content-center">
											<li>
												<div class="counter">
													<div class="days">
														<h6></h6>
													</div>
												</div>
											</li>
											<li>
												<div class="counter">
													<div class="hours">
														<h6></h6>
													</div>
												</div>
											</li>
											<li>
												<div class="counter">
													<div class="minutes">
														<h6></h6>
													</div>
												</div>
											</li>
											<li>
												<div class="counter">
													<div class="seconds">
														<h6></h6>
													</div>
												</div>
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
				</div>
				<?php
				  }
				}
				 ?>
			</div>

			<div class="col-xxl-8 col-lg-7 order-lg-1">
				<?php
				$no = 0;
				for ($n = 0; $n < $dt_product_group->getRowCount(); $n++) {
				  if ($dt_product_group->getString($n, "group_category_name") == "TRENDING-PRODUCT") 
				  {
					$group_id = $dt_product_group->getString($n, "id");
					$code = $dt_product_group->getString($n, "code");
					$name = $dt_product_group->getString($n, "name_lg");
					$document_id = $dt_product_group->getString($n, "document_id");
					if ($name == "") {
					  $name = $dt_product_group->getString($n, "name");
					}
					$content = $dt_product_group->getString($n, "content");
					$productList = $product->productByGroupById($appSession, $group_id, 30);
					
					
				?>	
				<div class="product-border border-row overflow-hidden">
                            <div class="product-box-slider no-arrow">
								<?php
								$i = 0;
								
								while($productList->getRowCount()>$i)
								{
								
								
								?>
								 <div>
								 <?php
								 for($r =0; $r<2; $r++)
								{
									?>
                                    <div class="row m-0">
                                        <div class="col-12 px-0">
										
										<div class="product-box">
											<?php
											if($i<$productList->getRowCount())
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
										
                                                <div class="product-image bg-white">
                                                    <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code;?>">
                                                        <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&h=147"
                                                            class="img-fluid blur-up lazyload" alt="">
                                                    </a>
                                                    <ul class="product-option">
                                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
															<a href="javascript:openPopup('<?php echo URL;?>addons/product_popup/?id=<?php echo $price_id;?>')">
																<i data-feather="eye"></i>
															</a>
														</li>

														<li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
															<a href="<?php echo URL;?>compare/?price_id=<?php echo $price_id;?>">
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
                                                    <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code;?>">
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
												<?php
												$i = $i + 1;
											}
										
										?>
                                            </div>
											
										</div>
									</div>
									<?php
								}
								?>
								</div>
								<?php
								
								}
								?>
                            </div>
                        </div>
				<?php
				  }
				}
				?>
					
			</div>
		</div>
	</div>
</section>
<?php
				for ($n = 0; $n < $dt_product_group->getRowCount(); $n++) {
				  if ($dt_product_group->getString($n, "group_category_name") == "HOME-MIDDLE-TEXT") {
					$group_id = $dt_product_group->getString($n, "id");
					$code = $dt_product_group->getString($n, "code");
					$name = $dt_product_group->getString($n, "name_lg");
					$document_id = $dt_product_group->getString($n, "document_id");
					if ($name == "") {
					  $name = $dt_product_group->getString($n, "name");
					}
					$content = $dt_product_group->getString($n, "content");
					
				?>	
 <section class="offer-section">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-12">
				<div class="section-t-space">
					<div class="banner-contain">
						<img src="<?php echo URL;?>document/?id=<?php echo $document_id;?>" class="bg-img blur-up lazyload" alt="">
						<div class="banner-details p-center p-4 text-white text-center">
							<div>
								<?php
								echo $content;
								?>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>			
<?php
  }
}
?>
<?php
	$no = 0;
	for ($n = 0; $n < $dt_product_group->getRowCount(); $n++) {
	  if ($dt_product_group->getString($n, "group_category_name") == "HOME-MIDDLE-TEXT") 
	  {
		$group_id = $dt_product_group->getString($n, "id");
		$code = $dt_product_group->getString($n, "code");
		$name = $dt_product_group->getString($n, "name_lg");
		$document_id = $dt_product_group->getString($n, "document_id");
		if ($name == "") {
		  $name = $dt_product_group->getString($n, "name");
		}
		$content = $dt_product_group->getString($n, "content");
		$productList = $product->productByGroupById($appSession, $group_id, 30);
		
	?>
<section class="product-section-4">
        <div class="container-fluid-lg">
            <div class="title">
                <h2><?php echo $name;?></h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="slider-7_1 arrow-slider img-slider">
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
                            <div class="product-box-4 wow fadeInUp" data-wow-delay="0.35s">
                                <div class="product-image product-image-2">
                                    <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code;?>">
                                        <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&h=105"
                                            class="img-fluid blur-up lazyload" alt="<?php echo $name;?>">
                                    </a>

                                    <ul class="option">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Quick View">
                                            <a href="javascript:openPopup('<?php echo URL;?>addons/product_popup/?id=<?php echo $price_id;?>')">
                                                <i class="iconly-Show icli"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                                            <a href="<?php echo URL;?>wishlist/?price_id=<?php echo $price_id;?>" class="notifi-wishlist">
                                                <i class="iconly-Heart icli"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
                                            <a href="<?php echo URL;?>compare/?price_id=<?php echo $price_id;?>">
                                                <i class="iconly-Swap icli"></i>
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
                                    <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code;?>">
                                        <h5 class="name text-title"><?php echo $name;?></h5>
                                    </a>
                                    <h5 class="price theme-color"><?php echo $appSession->getCurrency()->format($currency_id, $unit_price);?></span>
													<?php if($old_price>0){
														?>
													<del><?php echo $appSession->getCurrency()->format($currency_id, $old_price);?><?php } ?></h5>
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
								<div class="addtocart_btn">
									<button class="add-button addcart-button btn buy-button text-light" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })">
										<i class="fa-solid fa-plus"></i>
									</button>
									<div class="qty-box cart_qty <?php if($sale_quantity>0){ echo " open "; } ?>">
										<div class="input-group">
											<button type="button" class="btn qty-left-minus" data-type="minus"
												data-field="" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', -1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })">
												<i class="fa fa-minus" aria-hidden="true"></i>
											</button>
											<input class="form-control input-number qty-input" type="text"
												name="quantity" value="<?php echo $sale_quantity;?>">
											<button type="button" class="btn qty-right-plus" data-type="plus"
												data-field="" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })">
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
						<?php
							}
						?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
		}
	  }
	  ?>
	  
   <section class="banner-section">
	<div class="container-fluid-lg">
		<div class="row gy-lg-0 gy-3">
			<?php
			$no = 0;
			for ($n = 0; $n < $dt_product_group->getRowCount(); $n++) {
			  if ($dt_product_group->getString($n, "group_category_name") == "HOME-MIDDLE") 
			  {
				$group_id = $dt_product_group->getString($n, "id");
				$code = $dt_product_group->getString($n, "code");
				$name = $dt_product_group->getString($n, "name_lg");
				$document_id = $dt_product_group->getString($n, "document_id");
				if ($name == "") {
				  $name = $dt_product_group->getString($n, "name");
				}
				$content = $dt_product_group->getString($n, "content");
				$productList = $product->productByGroupById($appSession, $group_id, 30);
				
			?>
			<div class="col-lg-6">
			<div class="banner-contain-3 hover-effect"style="min-height: 100px;">
					
					<div>
						<img src="<?php echo URL;?>document/?id=<?php echo $document_id;?>&h=300" class="bg-img blur-up lazyload" alt="">
						<div
							class="banner-detail banner-detail-2 text-dark p-center-left w-75 banner-p-sm position-relative mend-auto"  style="min-height: 325px;">
							<div>
								<?php echo $content;?>
								<button class="btn btn-md theme-bg-color text-white mt-sm-3 mt-1 fw-bold mend-auto"
									onclick="location.href = '<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/g-<?php echo $code; ?>';"><?php echo $appSession->getLang()->find("Shop Now");?></button>
							</div>
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
</section>

<?php
	$no = 0;
	for ($n = 0; $n < $dt_product_group->getRowCount(); $n++) {
	  if ($dt_product_group->getString($n, "group_category_name") == "HOME") 
	  {
		$group_id = $dt_product_group->getString($n, "id");
		$code = $dt_product_group->getString($n, "code");
		$name = $dt_product_group->getString($n, "name_lg");
		$document_id = $dt_product_group->getString($n, "document_id");
		if ($name == "") {
		  $name = $dt_product_group->getString($n, "name");
		}
		$content = $dt_product_group->getString($n, "content");
		$productList = $product->productByGroupById($appSession, $group_id, 30);
		
	?>
<section class="product-section-4">
        <div class="container-fluid-lg">
            <div class="title">
                <h2><?php echo $name;?></h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="slider-7_1 arrow-slider img-slider">
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
                            <div class="product-box-4 wow fadeInUp" data-wow-delay="0.35s">
                                <div class="product-image product-image-2">
                                    <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code;?>">
                                        <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&h=105"
                                            class="img-fluid blur-up lazyload" alt="<?php echo $name;?>">
                                    </a>

                                    <ul class="option">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Quick View">
                                            <a href="javascript:openPopup('<?php echo URL;?>addons/product_popup/?id=<?php echo $price_id;?>')">
                                                <i class="iconly-Show icli"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                                            <a href="<?php echo URL;?>wishlist/?price_id=<?php echo $price_id;?>" class="notifi-wishlist">
                                                <i class="iconly-Heart icli"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
                                            <a href="<?php echo URL;?>compare/?price_id=<?php echo $price_id;?>">
                                                <i class="iconly-Swap icli"></i>
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
                                    <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code;?>">
                                        <h5 class="name text-title"><?php echo $name;?></h5>
                                    </a>
                                    <h5 class="price theme-color"><?php echo $appSession->getCurrency()->format($currency_id, $unit_price);?></span>
													<?php if($old_price>0){
														?>
													<del><?php echo $appSession->getCurrency()->format($currency_id, $old_price);?><?php } ?></h5>
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
								<div class="addtocart_btn">
									<button class="add-button addcart-button btn buy-button text-light" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })">
										<i class="fa-solid fa-plus"></i>
									</button>
									<div class="qty-box cart_qty <?php if($sale_quantity>0){ echo " open "; } ?>">
										<div class="input-group">
											<button type="button" class="btn qty-left-minus" data-type="minus"
												data-field="" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', -1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })">
												<i class="fa fa-minus" aria-hidden="true"></i>
											</button>
											<input class="form-control input-number qty-input" type="text"
												name="quantity" value="<?php echo $sale_quantity;?>">
											<button type="button" class="btn qty-right-plus" data-type="plus"
												data-field="" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })">
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
						<?php
							}
						?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
		}
	  }
	  ?>
	  
	  <section class="blog-section">
        <div class="container-fluid-lg">
            <div class="title title-4">
                <h2><?php echo $appSession->getLang()->find("Blog"); ?></h2>
            </div>

            <div class="slider-3-blog arrow-slider slick-height">
				<?php
				$sql = "SELECT d1.code, d1.name, d1.create_date, p.document_id, d2.name AS category_name FROM post d1 LEFT OUTER JOIN post_category d2 ON(d1.category_id = d2.id) LEFT OUTER JOIN poster p ON(d1.id = p.rel_id AND p.status =0 AND p.publish=1) WHERE d1.status =0 AND d1.publish =1 AND d2.type='POST' AND p.document_id IS NOT NULL ORDER BY d1.create_date DESC LIMIT 6";
				$msg->add("query", $sql);
				$dt_post = $appSession->getTier()->getTable($msg);
				for($i =0; $i<$dt_post->getRowCount(); $i++)
					{
						$code = $dt_post->getString($i, "code");
						$name = $dt_post->getString($i, "name");
						$category_name = $dt_post->getString($i, "category_name");
						$document_id = $dt_post->getString($i, "document_id");
						$create_date = $dt_post->getString($i, "create_date");
						$d = strtotime($create_date);
						$create_date = date('F j, Y', $d);
				?>
                <div>
                    <div class=" blog-box ratio_45">
                        <div class="blog-box-image">
                            <a href="<?php echo URL; ?>blog_detail/<?php echo  $appSession->getTool()->validUrl($name); ?>/<?php echo  $appSession->getTool()->validUrl($code); ?>">
                                <img src="<?php echo URL;?>document/?id=<?php echo $document_id;?>" class="blur-up lazyload bg-img" alt="">
                            </a>
                        </div>

                        <div class="blog-detail">
                            <label><?php echo $category_name;?></label>
                            <a href="blog-detail.html">
                                <h3><?php echo $name;?></h3>
                            </a>
                            <h5 class="text-content"><?php echo $create_date;?></h5>
                        </div>
                    </div>
                </div>
				<?php
					}
				?>
               

               


            </div>
        </div>
    </section>
	
	<section class="service-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-3 row-cols-xxl-5 row-cols-lg-3 row-cols-md-2">
                <div>
                    <div class="service-contain-2">
                        <svg class="icon-width">
                            <use xlink:href="<?php echo URL;?>/assets/svg/svg/service-icon-4.svg#shipping"></use>
                        </svg>
                        <div class="service-detail">
                            <h3>Free Shipping</h3>
                            <h6 class="text-content">Free Shipping world wide</h6>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="service-contain-2">
                        <svg class="icon-width">
                            <use xlink:href="<?php echo URL;?>/assets/svg/svg/service-icon-4.svg#service"></use>
                        </svg>
                        <div class="service-detail">
                            <h3>24 x 7 Service</h3>
                            <h6 class="text-content">Online Service For 24 x 7</h6>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="service-contain-2">
                        <svg class="icon-width">
                            <use xlink:href="<?php echo URL;?>/assets/svg/svg/service-icon-4.svg#pay"></use>
                        </svg>
                        <div class="service-detail">
                            <h3>Online Pay</h3>
                            <h6 class="text-content">Online Payment Avaible</h6>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="service-contain-2">
                        <svg class="icon-width">
                            <use xlink:href="<?php echo URL;?>/assets/svg/svg/service-icon-4.svg#offer"></use>
                        </svg>
                        <div class="service-detail">
                            <h3>Festival Offer</h3>
                            <h6 class="text-content">Super Sale Upto 50% off</h6>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="service-contain-2">
                        <svg class="icon-width">
                            <use xlink:href="<?php echo URL;?>/assets/svg/svg/service-icon-4.svg#return"></use>
                        </svg>
                        <div class="service-detail">
                            <h3>100% Original</h3>
                            <h6 class="text-content">100% Money Back</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>