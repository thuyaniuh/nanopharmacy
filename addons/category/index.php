<?php
require_once(ABSPATH . 'api/Product.php');

function findChild($dt, $parent_id)
{
	$ids = "";
	for ($i = 0; $i < $dt->getRowCount(); $i++) {
		if ($dt->getString($i, "parent_id") == $parent_id) {
			if ($ids != "") {
				$ids = $ids . ",";
			}
			$ids = $ids . $dt->getString($i, "id");
			$s1 = findChild($dt, $dt->getString($i, "id"));
			if ($s1 != "") {
				$ids = $ids . "," . $s1;
			}
		}
	}
	return $ids;
}
$ids = findChild($dt_category, $selected_id);
if ($ids != "") {
	$ids = $ids . "," . $selected_id;
} else {
	$ids = $selected_id;
}
$arr = $appSession->getTool()->split($ids, ',');
$ids = "";
for ($i = 0; $i < count($arr); $i++) {
	if ($ids != "") {
		$ids = $ids . " OR ";
	}
	$ids = $ids . " d1.category_id='" . $arr[$i] . "'";
}
$p = 0;
if (isset($_REQUEST['p'])) {
	$p = $_REQUEST['p'];
}
$ps = 30;
if (isset($_REQUEST['ps'])) {
	$ps = $_REQUEST['ps'];
}
$search = "";
if (isset($_REQUEST['search'])) {
	$search = $_REQUEST['search'];
	$search = $appSession->getTool()->urldecode($search);
}

$sql = "SELECT d1.id, d5.unit_id, d5.attribute_id,	d5.type_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d5.code AS price_code, d5.unit_price, d5.old_price, d6.name AS unit_name, d5.currency_id";
$sql = $sql . ", d9.name AS attribute_name, d10.name attribute_category_name, d5.factor";
$sql = $sql . ", d5.company_id, d7.commercial_name, d7.name AS company_name, d5.id AS price_id, d8.document_id AS price_document_id, d5.description, d11.name AS type_name, 0.0 AS unit_in_stock, d12.name AS sticker";
$sql = $sql . " FROM product d1";
$sql = $sql . " LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status =0)";
$sql = $sql . " LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='" . $appSession->getConfig()->getProperty("lang_id") . "' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
$sql = $sql . " LEFT OUTER JOIN product_price d5 ON(d1.id = d5.product_id AND d5.status =0 AND d5.publish=1) LEFT OUTER JOIN product_unit d6 ON(d5.unit_id = d6.id)";
$sql = $sql . " LEFT OUTER JOIN res_company d7 ON(d5.company_id = d7.id)";
$sql = $sql . " LEFT OUTER JOIN poster d8 ON(d5.id = d8.rel_id AND d8.publish=1 AND d8.status =0)";
$sql = $sql . " LEFT OUTER JOIN attribute d9 ON(d5.attribute_id = d9.id)";
$sql = $sql . " LEFT OUTER JOIN attribute_category d10 ON(d9.category_id = d10.id)";
$sql = $sql . " LEFT OUTER JOIN product_type d11 ON(d5.type_id = d11.id)";
$sql = $sql . " LEFT OUTER JOIN res_meta d12 ON(d1.id = d12.rel_id AND d12.type='Sticker' AND d12.status =0)";
$sql = $sql . " WHERE d1.status =0 AND d1.publish = 1";
$sql = $sql . " AND (" . $ids . ")";
if ($search != "") {
	$sql = $sql . " AND (" . $appSession->getTier()->buildSearch(["d1.code", "d1.name", "lg.description"], $search) . ")";
}

$sql = $sql . " AND d5.type='PRODUCT'";



$arr = $appSession->getTier()->paging($sql, $p, $ps, " d1.name ASC");




$item_count = 0;
$sql = $arr[1];
$msg->add("query", $sql);
$values = $appSession->getTier()->getArray($msg);

if (count($values) > 0) {

	$item_count = $values[0][0];
}

$page_count = (int)($item_count / $ps);
if ($item_count - ($page_count * $ps) > 0) {
	$page_count = $page_count + 1;
}

$start = 0;
if ($item_count > 0) {
	$start = ($p * $ps) + 1;
}
$end = $p + 1;
if ((($p + 1) * $ps) < $item_count) {
	$end = ($p + 1) * $ps;
} else {
	$end = $item_count;
}

$sql = $arr[0];

$msg->add("query", $sql);
$productList = $appSession->getTier()->getTable($msg);
$product = new Product($appSession);
$productList = $product->countProduct($productList);

$category_name = "";
$category_code = "";

for ($i = 0; $i < $dt_category->getRowCount(); $i++) {
	if ($dt_category->getString($i, "id") == $selected_id) {
		$category_code = $dt_category->getString($i, "code");
		$category_name = $dt_category->getString($i, "name_lg");
		if (empty($category_name)) {
			$category_name = $dt_category->getString($i, "name");
		}
	}
}

$min_price = 0;
$max_price = 0;

$sql = "SELECT min(d1.unit_price) FROM product_price d1 LEFT OUTER JOIN product d2 on(d1.product_id = d2.id) WHERE d1.status =0 AND d2.status = 0 AND d1.publish =1";
$msg->add("query", $sql);

$min_price = $appSession->getTier()->getValue($msg);

$sql = "SELECT max(d1.unit_price) FROM product_price d1 LEFT OUTER JOIN product d2 on(d1.product_id = d2.id) WHERE d1.status =0 AND d2.status = 0 AND d1.publish =1";
$msg->add("query", $sql);

$max_price = $appSession->getTier()->getValue($msg);


?>
<section class="breadscrumb-section pt-0">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-12">
				<div class="breadscrumb-contain">
					<h2><?php echo $category_name; ?></h2>
					<nav>
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item">
								<a href="<?php echo URL; ?>">
									<?php echo $appSession->getLang()->find("Home"); ?>
								</a>
							</li>
							<?php
							if ($selected_id != "") {
								$category_name = "";
								$category_code = "";

								for ($i = 0; $i < $dt_category->getRowCount(); $i++) {
									if ($dt_category->getString($i, "id") == $selected_id) {
										$category_code = $dt_category->getString($i, "code");
										$category_name = $dt_category->getString($i, "name_lg");
										if (empty($category_name)) {
											$category_name = $dt_category->getString($i, "name");
										}
									}
								}
							?>
								<li class="breadcrumb-item active"><a href="<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name); ?>/c-<?php echo $category_code; ?>"><?php echo $category_name; ?></a></li>

							<?php } ?>


						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Shop Section Start -->
<section class="section-b-space shop-section">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-md-3">
				<div class="left-box wow fadeInUp">
					<div class="shop-left-sidebar">
						<div class="back-button">
							<h3><i class="fa-solid fa-arrow-left"></i> Back</h3>
						</div>

						<div class="filter-category">
							<div class="filter-title">
								<h2>Filters</h2>
								<a href="javascript:void(0)">Clear All</a>
							</div>
							<ul>
								<li>
									<a href="javascript:void(0)">Vegetable</a>
								</li>
								<li>
									<a href="javascript:void(0)">Fruit</a>
								</li>
								<li>
									<a href="javascript:void(0)">Fresh</a>
								</li>
								<li>
									<a href="javascript:void(0)">Milk</a>
								</li>
								<li>
									<a href="javascript:void(0)">Meat</a>
								</li>
							</ul>
						</div>

						<div class="accordion custom-accordion" id="accordionExample">
							<div class="accordion-item">
								<h2 class="accordion-header" id="headingOne">
									<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
										<span>Categories</span>
									</button>
								</h2>
								<div id="collapseOne" class="accordion-collapse collapse show">
									<div class="accordion-body">
										<div class="form-floating theme-form-floating-2 search-box">
											<input type="search" class="form-control" id="search" placeholder="Search ..">
											<label for="search">Search</label>
										</div>

										<ul class="category-list custom-padding custom-height">
											<?php
											$ids = "";
											for ($i = 0; $i < $dt_category->getRowCount(); $i++) {
												if ($dt_category->getString($i, "parent_id") != "") {
													continue;
												}
												$id = $dt_category->getString($i, "id");
												if ($ids != "") {
													$ids = $ids . " OR ";
												}
												$ids = $ids . " d2.id ='" . $id . "'";
											}
											$sql = "SELECT d2.id AS parent_id, COUNT(d1.id) AS c FROM product d1 LEFT OUTER JOIN product_category d2 ON(d1.category_id = d2.id) WHERE d1.status =0 ";
											if ($ids != "") {
												$sql = $sql . " AND (" . $ids . ")";
											} else {
												$sql = $sql . " AND 1=0";
											}
											$sql = $sql . " GROUP BY d2.id";
											$msg->add("query", $sql);
											$dt_count = $appSession->getTier()->getTable($msg);

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
												if ($document_id == "") {
													continue;
												}
												$category_id = $dt_category->getString($i, "id");
												$count = 0;
												for ($j = 0; $j < $dt_count->getRowCount(); $j++) {
													if ($dt_count->getString($j, "parent_id") == $category_id) {
														$count = $dt_count->getFloat($j, "c");
														break;
													}
												}
											?>
												<li>
													<div class="form-check ps-0 m-0 category-list-box">
														<input class="checkbox_animated" type="checkbox" id="salami" name="category_id" value="<?php echo $category_id; ?>" onchange="loadProductList();">
														<label class="form-check-label" for="salami">
															<span class="name"><a href="<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name); ?>/c-<?php echo $category_code; ?>"><?php echo $category_name; ?></a></span>
															<span class="number"><?php echo $appSession->getFormats()->getDOUBLE()->format($count); ?></span>
														</label>
													</div>
												</li>
											<?php
											}
											?>
										</ul>
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="headingThree">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
										<span>Price</span>
									</button>
								</h2>
								<div id="collapseThree" class="accordion-collapse collapse show">
									<div class="accordion-body">
										<div class="range-slider">
											<input type="text" class="js-range-slider" id="range-price" value="" onchange="loadProductList()" data-min="<?php echo $min_price; ?>" data-max="<?php echo $max_price; ?>" data-from="200" data-to="500">
										</div>
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="headingSix">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
										<span>Rating</span>
									</button>
								</h2>
								<div id="collapseSix" class="accordion-collapse collapse show">
									<div class="accordion-body">
										<ul class="category-list custom-padding">
											<li>
												<div class="form-check ps-0 m-0 category-list-box">
													<input class="checkbox_animated" type="checkbox">
													<div class="form-check-label">
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
																<i data-feather="star" class="fill"></i>
															</li>
														</ul>
														<span class="text-content">(5 Star)</span>
													</div>
												</div>
											</li>

											<li>
												<div class="form-check ps-0 m-0 category-list-box">
													<input class="checkbox_animated" type="checkbox">
													<div class="form-check-label">
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
														<span class="text-content">(4 Star)</span>
													</div>
												</div>
											</li>

											<li>
												<div class="form-check ps-0 m-0 category-list-box">
													<input class="checkbox_animated" type="checkbox">
													<div class="form-check-label">
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
																<i data-feather="star"></i>
															</li>
															<li>
																<i data-feather="star"></i>
															</li>
														</ul>
														<span class="text-content">(3 Star)</span>
													</div>
												</div>
											</li>

											<li>
												<div class="form-check ps-0 m-0 category-list-box">
													<input class="checkbox_animated" type="checkbox">
													<div class="form-check-label">
														<ul class="rating">
															<li>
																<i data-feather="star" class="fill"></i>
															</li>
															<li>
																<i data-feather="star" class="fill"></i>
															</li>
															<li>
																<i data-feather="star"></i>
															</li>
															<li>
																<i data-feather="star"></i>
															</li>
															<li>
																<i data-feather="star"></i>
															</li>
														</ul>
														<span class="text-content">(2 Star)</span>
													</div>
												</div>
											</li>

											<li>
												<div class="form-check ps-0 m-0 category-list-box">
													<input class="checkbox_animated" type="checkbox">
													<div class="form-check-label">
														<ul class="rating">
															<li>
																<i data-feather="star" class="fill"></i>
															</li>
															<li>
																<i data-feather="star"></i>
															</li>
															<li>
																<i data-feather="star"></i>
															</li>
															<li>
																<i data-feather="star"></i>
															</li>
															<li>
																<i data-feather="star"></i>
															</li>
														</ul>
														<span class="text-content">(1 Star)</span>
													</div>
												</div>
											</li>
										</ul>
									</div>
								</div>
							</div>

							<div class="accordion-item">
								<h2 class="accordion-header" id="headingFour">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
										<span>Discount</span>
									</button>
								</h2>
								<div id="collapseFour" class="accordion-collapse collapse show">
									<div class="accordion-body">
										<ul class="category-list custom-padding">
											<li>
												<div class="form-check ps-0 m-0 category-list-box">
													<input class="checkbox_animated" type="checkbox" id="flexCheckDefault">
													<label class="form-check-label" for="flexCheckDefault">
														<span class="name">upto 5%</span>
														<span class="number">(06)</span>
													</label>
												</div>
											</li>

											<li>
												<div class="form-check ps-0 m-0 category-list-box">
													<input class="checkbox_animated" type="checkbox" id="flexCheckDefault1">
													<label class="form-check-label" for="flexCheckDefault1">
														<span class="name">5% - 10%</span>
														<span class="number">(08)</span>
													</label>
												</div>
											</li>

											<li>
												<div class="form-check ps-0 m-0 category-list-box">
													<input class="checkbox_animated" type="checkbox" id="flexCheckDefault2">
													<label class="form-check-label" for="flexCheckDefault2">
														<span class="name">10% - 15%</span>
														<span class="number">(10)</span>
													</label>
												</div>
											</li>

											<li>
												<div class="form-check ps-0 m-0 category-list-box">
													<input class="checkbox_animated" type="checkbox" id="flexCheckDefault3">
													<label class="form-check-label" for="flexCheckDefault3">
														<span class="name">15% - 25%</span>
														<span class="number">(14)</span>
													</label>
												</div>
											</li>

											<li>
												<div class="form-check ps-0 m-0 category-list-box">
													<input class="checkbox_animated" type="checkbox" id="flexCheckDefault4">
													<label class="form-check-label" for="flexCheckDefault4">
														<span class="name">More than 25%</span>
														<span class="number">(13)</span>
													</label>
												</div>
											</li>
										</ul>
									</div>
								</div>
							</div>

							<div class="accordion-item">
								<h2 class="accordion-header" id="headingFive">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
										<span>Pack Size</span>
									</button>
								</h2>
								<div id="collapseFive" class="accordion-collapse collapse show">
									<div class="accordion-body">
										<ul class="category-list custom-padding custom-height">

										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9" id="pnProduct">

			</div>
		</div>
	</div>
</section>
<!-- Shop Section End -->
<script>
	function loadProductList() {
		var category_ids = "";
		var elements = document.getElementsByName('category_id');
		for (var i = 0; i < elements.length; i++) {
			if (elements[i].checked == false) {
				continue;
			}
			if (category_ids != "") {
				category_ids = category_ids + ";";
			}
			category_ids = category_ids + elements[i].value;
		}
		var range_price = document.getElementById('range-price').value;

		var _url = "<?php echo URL; ?>addons/category_product/?category_ids=" + encodeURIComponent(category_ids);
		_url = _url + "&range_price=" + encodeURIComponent(range_price);

		loadPage('pnProduct', _url, function(status, message) {

		}, false)
	}
	loadProductList();
</script>