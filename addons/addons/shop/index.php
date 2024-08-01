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
if(isset($_REQUEST['p']))
{
	$p = $_REQUEST['p'];
}
$ps = 30;
if(isset($_REQUEST['ps']))
{
	$ps = $_REQUEST['ps'];
}

$sql = "SELECT d1.id, d5.unit_id, d5.attribute_id,	d5.type_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d5.unit_price, d5.old_price, d6.name AS unit_name, d5.currency_id";
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
if($search == "")
{
	$sql = $sql." AND d1.company_id='".$company_company_id."'";
}

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

if(count($values)>0)
{
	
	$item_count = $values[0][0];
}

$page_count = (int)($item_count / $ps);
if ($item_count - ($page_count * $ps) > 0)
{
	$page_count = $page_count + 1;
}

$start = 0;
if($item_count>0)
{
	$start = ($p * $ps) + 1;
}
$end = $p + 1;
if((($p + 1) * $ps)<$item_count)
{
	$end = ($p + 1) * $ps;
}else
{
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

$sql = "SELECT d1.commercial_name, p.document_id, d1.description, d1.create_date FROM res_company d1 LEFT OUTER JOIN poster p ON(d1.id = p.rel_id AND p.status =0 AND p.publish =1) WHERE d1.id ='".$company_company_id."'";

$msg->add("query", $sql);
$dt_company = $appSession->getTier()->getTable($msg);
					
$company_name = "";
$company_document_id = "";
$company_slogan = "";
$company_join_date = "";
if($dt_company->getRowCount()>0)
{
	$company_name = $dt_company->getString(0, "commercial_name");
	$company_document_id = $dt_company->getString(0, "document_id");
	$company_slogan = $dt_company->getString(0, "description");
	$company_join_date = $dt_company->getString(0, "create_date");
	if($company_join_date != "")
	{
		$company_join_date = $appSession->getFormats()->getDATE()->formatDate($appSession->getTool()->toDateTime($company_join_date));
	}
	
}

$dt_product_group = $product->productGroupByCompany($company_company_id);
								
?>
 <section class="breadscrumb-section pt-0">
	<div class="container-fluid-lg">
		
		<div class="row">
			<div class="col-12">
				<div class="breadscrumb-contain">
					<h2><?php echo $category_name;?></h2>
					<nav>
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item">
								<a href="<?php echo URL;?>">
									<?php echo $appSession->getLang()->find("Home"); ?>
								</a>
							</li>
							 <?php 
							 if ($selected_id != "") 
							 {
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

<section>
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-12">
				<div class="slider-1 slider-animate product-wrapper no-arrow">
					<?php
						for ($i = 0; $i < $dt_product_group->getRowCount(); $i++) {
						  if ($dt_product_group->getString($i, "group_category_name") == "SHOP-BANNER") {
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
   
					<div>
						<div class="banner-contain-2 hover-effect">
							<img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>" class="bg-img rounded-3 blur-up lazyload" alt="<?php echo $name;?>">
							<div
								class="banner-detail p-center-right position-relative shop-banner ms-auto banner-small">
								<div>
									<?php
									echo $content;
									?>
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
<section>
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-md-12">
				<div class="vendor-detail-box">
					<div class="row">
						<div class="col-md-4">
							<div class="vendor-name vendor-bottom">
								<div class="vendor-logo">
									<?php
									if($company_document_id != "")
									{
									?>
									<img src="<?php echo URL;?>document/?id=<?php echo $company_document_id;?>&h=120" alt="<?php echo $company_name;?>">
									<?php
									}
									?>
									<div>
										<h3><?php echo $company_name;?></h3>
										<div class="product-rating vendor-rating">
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
											<span>4.3 of 5</span>
										</div>
									</div>
								</div>
								<p><?php echo $company_slogan;?></p>
							</div>


							<div class="vendor-share">
								<h5><?php echo $appSession->getLang()->find("Follow Us");?> :</h5>
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
							</div>
						</div>
						<div class="col-md-8">
							<table class="table" style="padding:40px" cellspacing="10">
							<tr>
								<td>Sản phẩm: </td>
								<td><?php echo $productList->getRowCount();?></td>
								<td>Người theo dõi: </td>
								<td>20</td>
							</tr>
							<tr>
								<td>Đang theo: </td>
								<td>4</td>
								<td>Đánh giá: </td>
								<td>4.3 of 5</td>
							</tr>
							<tr>
								<td>Tỷ lệ phản hồi chat: </td>
								<td>100%</td>
								<td>Tham gia: </td>
								<td><?php echo $company_join_date;?></td>
							</tr>
						</table>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
<!-- page-header-section end -->

<?php
$no = 0;
for ($n = 0; $n < $dt_product_group->getRowCount(); $n++) {
  if ($dt_product_group->getString($n, "group_category_name") == "SHOP-TOP") 
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
<section class="section-b-space shop-section">
  <div class="container-fluid-lg">
 <div class="title d-block">
		<h2><?php echo $name;?></h2>
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
<?php
  }
}
?>


<!-- Category Section Start -->
<section class="section-b-space shop-section">
  <div class="container-fluid-lg">
	<div class="title d-block">
		<h2><?php echo $appSession->getLang()->find("Product");?></h2>
		<span class="title-leaf">
			<svg class="icon-width">
				<use xlink:href="<?php echo URL;?>/assets/svg/leaf.svg#leaf"></use>
			</svg>
		</span>
		
  </div>
    <div class="row">
      <div class="col-custome-3">
        <div class="left-box wow fadeInUp">

          <!-- INFO: may be stylized more -->
          <div class="shop-left-sidebar" style=" margin-top: 14px;">
			<div class="back-button">
						<h3><i class="fa-solid fa-arrow-left"></i><?php echo $appSession->getLang()->find("Back");?></h3>
					</div>

					
					
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
              <div class="container-fluid">
                <div class="category-menu">
								<h3><?php echo $appSession->getLang()->find("Category");?></h3>
								<ul>
									<?php
									for($i =0; $i<$dt_category->getRowCount(); $i++)
									{
										if($dt_category->getString($i, "parent_id") != "")
										{
											continue;
										}
										$category_code = $dt_category->getString($i, "code");
										if($category_code == "G010" || $category_code == "G012" || $category_code == "G011" || $category_code == "G001"){
										
										$category_name = $dt_category->getString($i, "name_lg");

										if ($category_name == "") {
											$category_name = $dt_category->getString($i, "name");
										}
										$document_id = $dt_category->getString($i, "document_id");
									?>
									<li>
										<div class="category-list">
											<img src="<?php echo URL;?>document/?id=<?php echo $document_id;?>" class="blur-up lazyload" alt="">
											<h5>
												<a href="<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name); ?>/c-<?php echo $category_code; ?>"><?php echo $category_name;?></a>
											</h5>
										</div>
									</li>
									<?php
										}
									}
									?>
								</ul>
							</div>
              </div>
            </nav>
          </div>

        </div>
      </div>
      <div class="col-custome-9">
        <div class="show-button">
          <div class=" filter-button d-inline-block d-lg-none">
            <a><i class="fa-solid fa-filter"></i><?php echo $appSession->getLang()->find("Filter Menu");?></a>
          </div>
		  <div class="top-filter-menu">
                            <div class="category-dropdown">
                                <h5 class="text-content"><?php echo $appSession->getLang()->find("Sort By");?> :</h5>
                                <div class="dropdown">
                                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown">
                                        <span><?php echo $appSession->getLang()->find("Most Popular");?></span> <i class="fa-solid fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <a class="dropdown-item" id="pop" href="javascript:void(0)"><?php echo $appSession->getLang()->find("Popularity");?></a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" id="low" href="javascript:void(0)"><?php echo $appSession->getLang()->find("Low - High Price");?></a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" id="high" href="javascript:void(0)"><?php echo $appSession->getLang()->find("High - Low Price");?></a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" id="rating" href="javascript:void(0)"><?php echo $appSession->getLang()->find("Average Rating");?></a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" id="aToz" href="javascript:void(0)"><?php echo $appSession->getLang()->find("A - Z Order");?></a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" id="zToa" href="javascript:void(0)"><?php echo $appSession->getLang()->find("Z - A Order");?></a>
                                        </li>
                                       
                                    </ul>
                                </div>
                            </div>

                            <div class="grid-option d-none d-md-block">
                                <ul>
                                    <li class="three-grid">
                                        <a href="javascript:void(0)">
                                            <img src="<?php echo URL;?>/assets/svg/grid-3.svg" class="blur-up lazyload" alt="">
                                        </a>
                                    </li>
                                    <li class="grid-btn d-xxl-inline-block d-none active">
                                        <a href="javascript:void(0)">
                                            <img src="<?php echo URL;?>/assets/svg/grid-4.svg"
                                                class="blur-up lazyload d-lg-inline-block d-none" alt="">
                                            <img src="<?php echo URL;?>/assets/svg/grid.svg"
                                                class="blur-up lazyload img-fluid d-lg-none d-inline-block" alt="">
                                        </a>
                                    </li>
                                    <li class="list-btn">
                                        <a href="javascript:void(0)">
                                            <img src="<?php echo URL;?>/assets/svg/list.svg" class="blur-up lazyload" alt="">
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
					if($sticker != "")
					{
						?>
					<span   style="transform:rotate(-45deg); background-color:red;color:white;display:inline-block;padding-left:8px;padding-right:8px;text-align:center">
					<?php echo $sticker;?>
					</span> 
					<?php
					}
					?>
					<div class="product-header">
						<div class="product-image bg-white">
							<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $price_id;?>">
								<img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&h=147"
									class="img-fluid blur-up lazyload" alt="<?php echo $name;?>">
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
					</div>
					<div class="product-footer ">
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
					if($page_count == 0)
					{
						$page_count = 1;
					}
					for($i =0; $i<$page_count; $i++)
					{
					?>
					 <li class="page-item <?php if($i == $p){ ?> active<?php };?>"  >
						<a class="page-link" href="javascript:void(0)"><?php echo $i+1;?></a>
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
<!-- Category Section End -->
<?php
$no = 0;
for ($n = 0; $n < $dt_product_group->getRowCount(); $n++) {
  if ($dt_product_group->getString($n, "group_category_name") == "SHOP-PROMOTION") 
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
<section class="section-b-space shop-section">
  <div class="container-fluid-lg">
 <div class="title d-block">
		<h2><?php echo $name;?></h2>
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
<?php
  }
}
?>
