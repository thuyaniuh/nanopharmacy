<section class="section-b-space shop-section">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-custome-3">
				<div class="left-box wow fadeInUp">

					<!-- INFO: may be stylized more -->
					<div class="shop-left-sidebar" style=" margin-top: 14px;">
						<nav class="navbar navbar-expand-lg navbar-light bg-light">
							<div class="container-fluid">
								<div class="category-menu">
									<h3>Category</h3>
									<ul class="category-list custom-padding custom-height">
										<?php
										for ($i = 0; $i < $dt_category->getRowCount(); $i++) {
											if ($dt_category->getString($i, "parent_id") != "") {
												continue;
											}
											$category_code = $dt_category->getString($i, "code");
											$category_name = $dt_category->getString($i, "name_lg");

											if ($category_name == "") {
												$category_name = $dt_category->getString($i, "name");
											}
											$document_id = $dt_category->getString($i, "document_id");
										?>
											<li>
												<div class="form-check ps-0 m-0 category-list-box">
													<input class="checkbox_animated" type="checkbox" id="fruit">
													<label class="form-check-label" for="fruit">
														<span class="name"><a href="<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name); ?>/c-<?php echo $category_code; ?>"><?php echo $category_name; ?></a></span>
														<span class="number">(15)</span>
													</label>
												</div>
											</li>
										<?php
										}
										?>
									</ul>
								</div>
							</div>
						</nav>
					</div>

				</div>
			</div>

			<div class="col-md-9">
				<div class="show-button">
					<div class=" filter-button d-inline-block d-lg-none">
						<a><i class="fa-solid fa-filter"></i> Filter Menu</a>
					</div>
					<div class="top-filter-menu">
						<div class="category-dropdown">
							<h5 class="text-content">Sort By :</h5>
							<div class="dropdown">
								<button class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown">
									<span>Most Popular</span> <i class="fa-solid fa-angle-down"></i>
								</button>
								<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
									<li>
										<a class="dropdown-item" id="pop" href="javascript:void(0)">Popularity</a>
									</li>
									<li>
										<a class="dropdown-item" id="low" href="javascript:void(0)">Low - High
											Price</a>
									</li>
									<li>
										<a class="dropdown-item" id="high" href="javascript:void(0)">High - Low
											Price</a>
									</li>
									<li>
										<a class="dropdown-item" id="rating" href="javascript:void(0)">Average
											Rating</a>
									</li>
									<li>
										<a class="dropdown-item" id="aToz" href="javascript:void(0)">A - Z Order</a>
									</li>
									<li>
										<a class="dropdown-item" id="zToa" href="javascript:void(0)">Z - A Order</a>
									</li>
									<li>
										<a class="dropdown-item" id="off" href="javascript:void(0)">% Off - Hight To
											Low</a>
									</li>
								</ul>
							</div>
						</div>

						<div class="grid-option d-none d-md-block">
							<ul>
								<li class="three-grid">
									<a href="javascript:void(0)">
										<img src="<?php echo URL; ?>/assets/svg/grid-3.svg" class="blur-up lazyload" alt="">
									</a>
								</li>
								<li class="grid-btn d-xxl-inline-block d-none active">
									<a href="javascript:void(0)">
										<img src="<?php echo URL; ?>/assets/svg/grid-4.svg" class="blur-up lazyload d-lg-inline-block d-none" alt="">
										<img src="<?php echo URL; ?>/assets/svg/grid.svg" class="blur-up lazyload img-fluid d-lg-none d-inline-block" alt="">
									</a>
								</li>
								<li class="list-btn">
									<a href="javascript:void(0)">
										<img src="<?php echo URL; ?>/assets/svg/list.svg" class="blur-up lazyload" alt="">
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row g-sm-4 g-3 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2 product-list-section">
					<?php
					$wowDelay = -0.05;

					for ($i = 0; $i < $productList->getRowCount(); $i++) {
						$wowDelay += 0.05;

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
							<div class="product-box-3 h-100 wow fadeInUp bg-white">
								<?php
								if ($sticker != "") {
								?>
									<span style="transform:rotate(-45deg); background-color:red;color:white;display:inline-block;padding-left:8px;padding-right:8px;text-align:center">
										<?php echo $sticker; ?>
									</span>
								<?php
								}
								?>
								<div class="product-header">
									<div class="product-image bg-white">
										<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $price_code; ?>">
											<img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&h=147" class="img-fluid blur-up lazyload" alt="<?php echo $name; ?>">
										</a>

										<ul class="product-option">
											<li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
												<a href="javascript:openPopup('<?php echo URL; ?>addons/product_popup/?id=<?php echo $price_id; ?>')">
													<i data-feather="eye"></i>
												</a>
											</li>

											<li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
												<a href="<?php echo URL; ?>compare/?price_id=<?php echo $price_id; ?>">
													<i data-feather="refresh-cw"></i>
												</a>
											</li>

											<li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
												<a href="<?php echo URL; ?>wishlist/?price_id=<?php echo $price_id; ?>" class="notifi-wishlist">
													<i data-feather="heart"></i>
												</a>
											</li>
											<?php
											if ($appSession->getConfig()->getProperty("user_group_id") == "cfe1a96e-92e4-4f4b-ccba-93e03c915b1e") {
											?>
												<li data-bs-toggle="tooltip" data-bs-placement="top" title="seller">
													<a href="<?php echo URL; ?>seller_detail/?price_id=<?php echo $price_id; ?>" class="notifi-wishlist">
														<i data-feather="package"></i>
													</a>
												</li>
											<?php
											}
											?>
										</ul>
									</div>
								</div>
								<div class="product-footer ">
									<div class="product-detail">
										<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $price_code; ?>">
											<h6 class="name"><?php echo $name; ?>
											</h6>
										</a>

										<h5 class="sold text-content">
											<span class="theme-color price"><?php echo $appSession->getCurrency()->format($currency_id, $unit_price); ?></span>
											<?php if ($old_price > 0) {
											?>
												<del><?php echo $appSession->getCurrency()->format($currency_id, $old_price); ?></del>
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

											<h6 class="theme-color"><?php if ($unit_in_stock == 0) {
																		echo $appSession->getLang()->find("Out of stock");
																	} else {
																		echo $appSession->getLang()->find("In Stock");
																	}  ?></h6>

										</div>
										<?php
										$sale_quantity = 0;
										for ($k = 0; $k < $saleProductList->getRowCount(); $k++) {
											if ($saleProductList->getString($k, "rel_id") == $price_id) {
												$sale_quantity = $saleProductList->getFloat($k, "quantity");
											}
										}
										if ($unit_in_stock > 0 || $sale_quantity != 0) {

										?>
											<div class="add-to-cart-box">
												<button class="btn btn-add-cart addcart-button" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id; ?>', '<?php echo $price_id; ?>','<?php echo $type_id; ?>', function(status, message){ loadCard(); })"><?php echo $appSession->getLang()->find("Add"); ?>
													<span class="add-icon">
														<i class="fa-solid fa-plus"></i>
													</span>
												</button>
												<div class="cart_qty qty-box" <?php if ($sale_quantity > 0) {
																					echo " open ";
																				} ?>>
													<div class="input-group">
														<button type="button" class="qty-left-minus" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', -1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id; ?>', '<?php echo $price_id; ?>','<?php echo $type_id; ?>', function(status, message){ loadCard(); })" data-type="minus" data-field="">
															<i class="fa fa-minus" aria-hidden="true"></i>
														</button>
														<input class="form-control input-number qty-input" type="text" name="quantity" value="<?php echo $sale_quantity; ?>">
														<button type="button" class="qty-right-plus" onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id; ?>', '<?php echo $price_id; ?>','<?php echo $type_id; ?>', function(status, message){ loadCard(); })" data-type="plus" data-field="">
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
					<?php
					}
					?>
				</div>
				<nav class="custome-pagination">
					<ul class="pagination justify-content-center">
						<li class="page-item disabled">
							<a class="page-link" href="javascript:void(0)" tabindex="-1" aria-disabled="true">
								<i class="fa-solid fa-angles-left"></i>
							</a>
						</li>
						<?php
						if ($page_count == 0) {
							$page_count = 1;
						}
						for ($i = 0; $i < $page_count; $i++) {
						?>
							<li class="page-item <?php if ($i == $p) { ?> active<?php }; ?>">
								<a class="page-link" href="javascript:void(0)"><?php echo $i + 1; ?></a>
							</li>

						<?php
						}
						?>


						<li class="page-item">
							<a class="page-link" href="javascript:void(0)">
								<i class="fa-solid fa-angles-right"></i>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</section>