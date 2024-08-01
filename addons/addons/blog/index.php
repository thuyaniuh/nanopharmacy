<?php
$product = new Product($appSession);
$dt_product_group = $product->productGroup();
$sql = "SELECT id, name FROM post_category WHERE code='". $page_id . "'";
$msg->add("query", $sql);
$dt = $appSession->getTier()->getTable($msg);
if($dt->getRowCount()>0)
{
	$sql = "SELECT d1.code, d1.name, d1.create_date, p.document_id, d1.description FROM post d1 LEFT OUTER JOIN post_category d2 ON(d1.category_id = d2.id) LEFT OUTER JOIN poster p ON(d1.id = p.rel_id AND p.status =0 AND p.publish=1) WHERE d1.status =0 AND d1.publish =1 AND d2.type='POST' AND p.document_id IS NOT NULL ORDER BY d1.create_date DESC LIMIT 8";
	$msg->add("query", $sql);
	$dt_post_recent = $appSession->getTier()->getTable($msg);
	$code = $dt->getString(0, "code");
	$name = $dt->getString(0, "name");
	$category_id = $dt->getString(0, "id");
	
	$sql = "SELECT d1.code, d1.name, d1.create_date, p.document_id, d3.name AS account_name FROM post d1 LEFT OUTER JOIN post_category d2 ON(d1.category_id = d2.id) LEFT OUTER JOIN poster p ON(d1.id = p.rel_id AND p.status =0 AND p.publish=1) LEFT OUTER JOIN res_user d3 ON(d1.create_uid = d3.id) WHERE d1.status =0 AND d1.publish =1 AND d2.type='POST' AND p.document_id IS NOT NULL AND d1.category_id='".$category_id."' ORDER BY d1.create_date DESC";
	$msg->add("query", $sql);
	$dt_post = $appSession->getTier()->getTable($msg);
	
?>

<section class="breadscrumb-section pt-0">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-12">
				<div class="breadscrumb-contain">
					<h2><?php echo $name;?></h2>
					<nav>
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item">
								<a href="<?php echo URL;?>">
									<i class="fa-solid fa-house"></i>
								</a>
							</li>
							<li class="breadcrumb-item" aria-current="page">Blog</li>
							<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo URL;?>blog-detail/<?php echo  $appSession->getTool()->validUrl($name); ?>/<?php echo  $appSession->getTool()->validUrl($code); ?>"><?php echo $name;?></a></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Breadcrumb Section End -->

<!-- Blog Details Section Start -->
<section class="blog-section section-b-space">
	<div class="container-fluid-lg">
		<div class="row g-sm-4 g-3">
			<div class="col-xxl-3 col-xl-4 col-lg-5 d-lg-block d-none">
				<div class="left-sidebar-box">
					
					<div class="accordion left-accordion-box" id="accordionPanelsStayOpenExample">
						<div class="accordion-item">
							<h2 class="accordion-header" id="panelsStayOpen-headingOne">
								<button class="accordion-button" type="button" data-bs-toggle="collapse"
									data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
									aria-controls="panelsStayOpen-collapseOne">
									Recent Post
								</button>
							</h2>
							<div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
								aria-labelledby="panelsStayOpen-headingOne">
								<div class="accordion-body pt-0">
									<div class="recent-post-box">
										<?php
										for($i =0; $i<$dt_post_recent->getRowCount(); $i++)
										{
											$code = $dt_post_recent->getString($i, "code");
											$name = $dt_post_recent->getString($i, "name");
											$document_id = $dt_post_recent->getString($i, "document_id");
											$create_date = $dt_post_recent->getString($i, "create_date");
											$d = strtotime($create_date);
											$create_date = date('F j, Y', $d);
										?>
										<div class="recent-box">
											<a href="<?php echo URL;?>blog-detail/<?php echo  $appSession->getTool()->validUrl($name); ?>/<?php echo  $appSession->getTool()->validUrl($code); ?>" class="recent-image">
												<img src="<?php echo URL;?>document/?id=<?php echo $document_id;?>"
													class="img-fluid blur-up lazyload" alt="">
											</a>

											<div class="recent-detail">
												<a href="<?php echo URL;?>blog_detail/<?php echo  $appSession->getTool()->validUrl($name); ?>/<?php echo  $appSession->getTool()->validUrl($code); ?>">
													<h5 class="recent-name"><?php echo $name;?></h5>
												</a>
												<h6><?php echo $create_date;?> <i data-feather="thumbs-up"></i></h6>
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
						$sql = "SELECT d2.code, d2.name, COUNT(d2.id) AS c FROM post d1 LEFT OUTER JOIN post_category d2 ON(d1.category_id = d2.id) WHERE d1.status =0 AND d2.status =0 AND d1.publish =1 AND d2.type='POST' GROUP BY d2.code, d2.name";
						$msg->add("query", $sql);
						$dt_post_category = $appSession->getTier()->getTable($msg);
						?>
						<div class="accordion-item">
							<h2 class="accordion-header" id="panelsStayOpen-headingTwo">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
									aria-controls="panelsStayOpen-collapseTwo">
									Category
								</button>
							</h2>
							<div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse collapse show"
								aria-labelledby="panelsStayOpen-headingTwo">
								<div class="accordion-body p-0">
									<div class="category-list-box">
										<ul>
											<?php
											for($i =0; $i<$dt_post_category->getRowCount(); $i++)
											{
												$code = $dt_post_category->getString($i, "code");
												$name = $dt_post_category->getString($i, "name");
												$c = $dt_post_category->getFloat($i, "c");
												if($c>0)
												{
											?>
											<li>
												<a href="<?php echo URL;?>blog/<?php echo  $appSession->getTool()->validUrl($name); ?>/<?php echo  $appSession->getTool()->validUrl($code); ?>">
													<div class="category-name">
														<h5><?php echo $name;?></h5>
														<span><?php echo $c;?></span>
													</div>
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
						<div class="accordion-item">
							<h2 class="accordion-header" id="panelsStayOpen-headingFour">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false"
									aria-controls="panelsStayOpen-collapseFour">
									Trending Products
								</button>
							</h2>
							<div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse collapse show"
								aria-labelledby="panelsStayOpen-headingFour">
								<div class="accordion-body">
									<ul class="product-list product-list-2 border-0 p-0">
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
											
										<li>
											<div class="offer-product">
												<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $price_id;?>" class="offer-image">
													<img src="<?php echo URL;?>/document/?id=<?php echo $document_id;?>&h=95"
														class="blur-up lazyload" alt="">
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
						</div>
						<?php
							}
						}
					?>
					</div>
				</div>
			</div>

			<div class="col-xxl-9 col-xl-8 col-lg-7 ratio_50">
				<div class="row g-4">
					<?php
					for($i =0; $i<$dt_post->getRowCount(); $i++)
					{
						$code = $dt_post->getString($i, "code");
						$name = $dt_post->getString($i, "name");
						$description = $dt_post->getString($i, "description");
						$document_id = $dt_post->getString($i, "document_id");
						$create_date = $dt_post->getString($i, "create_date");
						$d = strtotime($create_date);
						$create_date = date('F j, Y', $d);
						$account_name = $dt_post->getString($i, "account_name");
					?>
                        <div class="col-12">
                            <div class="blog-box blog-list wow fadeInUp">
                                <div class="blog-image">
                                    <img src="<?php echo URL;?>document/?id=<?php echo $document_id;?>" class="blur-up lazyload" alt="<?php echo $name;?>">
                                </div>

                                <div class="blog-contain blog-contain-2">
                                    <div class="blog-label">
                                        <span class="time"><i data-feather="clock"></i> <span><?php echo $create_date;?></span></span>
                                        <span class="super"><i data-feather="user"></i> <span><?php echo $account_name;?></span></span>
                                    </div>
                                    <a href="<?php echo URL; ?>blog_detail/<?php echo  $appSession->getTool()->validUrl($name); ?>/<?php echo  $appSession->getTool()->validUrl($code); ?>">
                                        <h3><?php echo $name;?></h3>
                                    </a>
                                    <p><?php echo $description;?></p>
                                    <button onclick="location.href = '<?php echo URL; ?>blog_detail/<?php echo  $appSession->getTool()->validUrl($name); ?>/<?php echo  $appSession->getTool()->validUrl($code); ?>';" class="blog-button">Read
                                        More <i class="fa-solid fa-right-long"></i></button>
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
?>
