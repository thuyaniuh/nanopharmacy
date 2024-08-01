<?php
$product = new Product($appSession);
$dt_product_group = $product->productGroup();
$sql = "SELECT id, name, content, can_comment FROM post WHERE code='". $page_id . "'";
$msg->add("query", $sql);
$dt_post = $appSession->getTier()->getTable($msg);
if($dt_post->getRowCount()>0)
{
	$sql = "SELECT d1.code, d1.name, d1.create_date, p.document_id FROM post d1 LEFT OUTER JOIN post_category d2 ON(d1.category_id = d2.id) LEFT OUTER JOIN poster p ON(d1.id = p.rel_id AND p.status =0 AND p.publish=1) WHERE d1.status =0 AND d1.publish =1 AND d2.type='POST' AND p.document_id IS NOT NULL AND d1.id!= '".$dt_post->getString(0, "id")."' ORDER BY d1.create_date DESC LIMIT 8";
	$msg->add("query", $sql);
	$dt_post_recent = $appSession->getTier()->getTable($msg);
	$code = $dt_post->getString(0, "code");
	$name = $dt_post->getString(0, "name");
	$post_content = $dt_post->getString(0, "content");
	$post_can_comment = $dt_post->getString(0, "can_comment");
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
				

				<div class="blog-detail-contain">
					<?php echo $post_content;?>
				</div>
				<?php
				if($post_can_comment == "1")
				{
				?>
				<div class="comment-box overflow-hidden">
					<div class="leave-title">
						<h3>Comments</h3>
					</div>

					<div class="user-comment-box">
						<ul>
							<li>
								<div class="user-box border-color">
									<div class="reply-button">
										<i class="fa-solid fa-reply"></i>
										<span class="theme-color">Reply</span>
									</div>
									<div class="user-iamge">
										<img src="../assets/images/inner-page/user/1.jpg"
											class="img-fluid blur-up lazyload" alt="">
										<div class="user-name">
											<h6>30 Jan, 2022</h6>
											<h5 class="text-content">Glenn Greer</h5>
										</div>
									</div>

									<div class="user-contain">
										<p>"This proposal is a win-win situation which will cause a stellar paradigm
											shift, and produce a multi-fold increase in deliverables a better
											understanding"</p>
									</div>
								</div>
							</li>

							<li>
								<div class="user-box border-color">
									<div class="reply-button">
										<i class="fa-solid fa-reply"></i>
										<span class="theme-color">Reply</span>
									</div>
									<div class="user-iamge">
										<img src="../assets/images/inner-page/user/2.jpg"
											class="img-fluid blur-up lazyload" alt="">
										<div class="user-name">
											<h6>30 Jan, 2022</h6>
											<h5 class="text-content">Glenn Greer</h5>
										</div>
									</div>

									<div class="user-contain">
										<p>"Yeah, I think maybe you do. Right, gimme a Pepsi free. Of course, the
											Enchantment Under The Sea Dance they're supposed to go to this, that's
											where they kiss for the first time. You'll find out. Are you sure about
											this storm?"</p>
									</div>
								</div>
							</li>

							<li class="li-padding">
								<div class="user-box">
									<div class="reply-button">
										<i class="fa-solid fa-reply"></i>
										<span class="theme-color">Reply</span>
									</div>
									<div class="user-iamge">
										<img src="../assets/images/inner-page/user/3.jpg"
											class="img-fluid blur-up lazyload" alt="">
										<div class="user-name">
											<h6>30 Jan, 2022</h6>
											<h5 class="text-content">Glenn Greer</h5>
										</div>
									</div>

									<div class="user-contain">
										<p>"Cheese slices goat cottage cheese roquefort cream cheese pecorino cheesy
											feet when the cheese comes out everybody's happy"</p>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<?php
				if($appSession->getConfig()->getProperty("user_id") != "")
				{
				?>
				<div class="leave-box">
					<div class="leave-title mt-0">
						<h3>Leave Comment</h3>
					</div>

					<div class="leave-comment">
						
						<div class="row g-3">

							<div class="col-12">
								<div class="blog-input">
									<textarea class="form-control" id="exampleFormControlTextarea1" rows="4"
										placeholder="Comments"></textarea>
								</div>
							</div>
						</div>

	
						<button class="btn btn-animation ms-xxl-auto mt-xxl-0 mt-3 btn-md fw-bold">Post
							Comment</button>
					</div>
				</div>
				<?php
				}
				?>
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
