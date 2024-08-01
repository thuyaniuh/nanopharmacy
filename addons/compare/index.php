<?php
require_once(ABSPATH . 'api/Product.php');
$msg = $appSession->getTier()->createMessage();
$searchs = ["d3.name"];
$user_id = $appSession->getConfig()->getProperty("user_id");
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
	if($ac == "del")
	{
		$id = $_REQUEST['id'];
		$sql = "UPDATE product_wishlist SET write_date=".$appSession->getTier()->getDateString().", write_uid='".$appSession->getConfig()->getProperty("user_id")."', status =1 WHERE id ='".$id."'";
		$msg->add("query", $sql);
		
		$r = $appSession->getTier()->exec($msg);
	}
	
}
if(isset($_REQUEST['price_id']))
{
	$price_id = $_REQUEST['price_id'];
	
	if($price_id != "")
	{
		$sql = "SELECT id FROM product_wishlist WHERE product_id ='".$price_id."' AND create_uid='".$user_id."' AND session_id='Compare'";
		$msg->add("query", $sql);
		$id = $appSession->getTier()->getValue($msg);
		if($id != "")
		{
			$sql = "UPDATE product_wishlist SET create_date=".$appSession->getTier()->getDateString().", create_uid='".$appSession->getConfig()->getProperty("user_id")."', status =0 WHERE id ='".$id."'";
			
		}else{
			$builder = $appSession->getTier()->createBuilder("product_wishlist");
			$id = $appSession->getTool()->getId();
			$builder->add("id", $id);
			$builder->add("create_uid", $appSession->getConfig()->getProperty("user_id"));
			$builder->add("write_uid", $appSession->getConfig()->getProperty("user_id"));
			$builder->add("create_date", $appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $appSession->getTier()->getDateString(), 'f');
			$builder->add("customer_id", $user_id);
			$builder->add("product_id", $price_id);
			$builder->add("session_id", "Compare");
			$builder->add("status",0);
			$builder->add("company_id", $appSession->getConfig()->getProperty("company_id"));
			$sql = $appSession->getTier()->getInsert($builder);
		}
		$msg->add("query", $sql);
		$r = $appSession->getTier()->exec($msg);
	}
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
$search = "";
if(isset($_REQUEST['search']))
{
	$search = $_REQUEST['search'];
}


$sql = "SELECT d1.id AS rel_price_id, d1.create_uid, d1.product_id AS price_id, d2.id, d3.code AS code, d3.name AS name, lg.description AS name_lg, p.document_id, d7.code AS category_code, d7.name AS category_name, d2.unit_price, d2.old_price, d2.unit_id, d2.currency_id, d9.name AS attribute_name, d10.name attribute_category_name,d11.name AS type_name, 0.0 AS unit_in_stock, lgc.description AS category_name_lg, d8.document_id AS price_document_id, d6.name AS unit_name";
$sql = $sql. " FROM product_wishlist d1";
$sql = $sql. " LEFT OUTER JOIN product_price d2 ON(d1.product_id = d2.id)";
$sql = $sql. " LEFT OUTER JOIN product d3 ON(d2.product_id = d3.id)";
$sql = $sql." LEFT OUTER JOIN poster p ON(d3.id = p.rel_id AND p.publish=1 AND p.status =0)";
$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d3.id AND lg.name='product_name' AND lg.status =0)";
$sql = $sql." LEFT OUTER JOIN product_unit d6 ON(d2.unit_id = d6.id)";
$sql = $sql." LEFT OUTER JOIN product_category d7 ON(d3.category_id = d7.id)";
$sql = $sql." LEFT OUTER JOIN poster d8 ON(d2.id = d8.rel_id AND d8.publish=1 AND d8.status =0)";
$sql = $sql." LEFT OUTER JOIN attribute d9 ON(d2.attribute_id = d9.id)";
$sql = $sql." LEFT OUTER JOIN attribute_category d10 ON(d9.category_id = d10.id)";
$sql = $sql." LEFT OUTER JOIN product_type d11 ON(d2.type_id = d11.id)";
$sql = $sql." LEFT OUTER JOIN res_lang_line lgc ON(lgc.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lgc.rel_id = d7.id AND lgc.name='product_category_name' AND lgc.status =0)";
$sql = $sql. " WHERE d1.status =0 AND d1.session_id='Compare' AND d1.status =0 AND d1.create_uid='".$user_id."'";

if($search != "")
{
	$sql = $sql." AND (".$appSession->getTier()->buildSearch($searchs, $search).")";
}

$arr = $appSession->getTier()->paging($sql, $p, $ps, "d1.create_date DESC");


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


?>
<!-- Breadcrumb Section Start -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2 class="mb-2"><?php echo $appSession->getLang()->find("Compare");?></h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo URL;?>">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                
                                <li class="breadcrumb-item active"><?php echo $appSession->getLang()->find("Compare");?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Compare Section Start -->
    <section class="compare-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="compare-table table-responsive">
						<table class="table compare-table">
							<tbody>
								<tr>
									<td class="first-column"><?php echo $appSession->getLang()->find("Product");?></td>
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
										$category_code = $productList->getString($i, "category_code");
										$category_name = $productList->getString($i, "category_name_lg");
										if($category_name == "")
										{
											$category_name = $productList->getString($i, "category_name");
										}
										$description = $productList->getString($i, "description");
										$create_uid = $productList->getString($i, "create_uid");
										$rel_price_id = $productList->getString($i, "rel_price_id");
										$commercial_name = $productList->getString($i, "commercial_name");
									?>
									<td class="product-image-title">
										<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code;?>" class="image"><img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&h=147" alt="<?php echo $name;?>"></a><br>
										<a href="<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name); ?>/c-<?php echo $category_code; ?>" class="category"><?php echo $category_name;?></a><br>
										<a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code;?>" class="title"><?php echo $name;?></a>
									</td>
									<?php
									}
									?>
									
								</tr>
								<tr>
								<td class="first-column"><?php echo $appSession->getLang()->find("Description");?></td>
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
										$create_uid = $productList->getString($i, "create_uid");
										$rel_price_id = $productList->getString($i, "rel_price_id");
										$commercial_name = $productList->getString($i, "commercial_name");
									?>
									
									<td class="pro-desc">
										<p><?php echo $description;?></p>
									</td>
									<?php
									}
									?>
								</tr>
								<tr>
									<td class="first-column"><?php echo $appSession->getLang()->find("Price");?></td>
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
										$create_uid = $productList->getString($i, "create_uid");
										$rel_price_id = $productList->getString($i, "rel_price_id");
										$commercial_name = $productList->getString($i, "commercial_name");
									?>
									<td class="first-column"><?php echo $appSession->getCurrency()->format($currency_id, $unit_price);?></td>
									<?php
									}
									?>
								</tr>
								
								<tr>
									<td class="first-column"><?php echo $appSession->getLang()->find("Stock Status");?></td>
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
										$create_uid = $productList->getString($i, "create_uid");
										$rel_price_id = $productList->getString($i, "rel_price_id");
										$commercial_name = $productList->getString($i, "commercial_name");
									?>
									<td class="pro-stock"><?php if($unit_in_stock>0)
										{
										?><?php echo $appSession->getLang()->find("In Stock");?>
										<?php }else { ?> <?php echo $appSession->getLang()->find("Out of stock");?> <?php } ?></td>
									<?php
									}
									?>
								</tr>
								<tr>
									<td class="first-column"><?php echo $appSession->getLang()->find("Add To Cart");?></td>
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
										$create_uid = $productList->getString($i, "create_uid");
										$rel_price_id = $productList->getString($i, "rel_price_id");
										$commercial_name = $productList->getString($i, "commercial_name");
									?>
									<td class="pro-addtocart"><?php if($unit_in_stock>0)
										{
										?><button onclick="addProduct('<?php echo $product_id; ?>', '<?php echo $currency_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', 1, <?php echo $unit_price; ?>, '<?php echo $second_unit_id; ?>', <?php echo $factor; ?>, '', '<?php $company_id;?>', '<?php echo $price_id;?>','<?php echo $type_id;?>', function(status, message){ loadCard(); })"
                                            class="btn btn-animation btn-sm w-100"><?php echo $appSession->getLang()->find("Add To Cart");?></button>
											<?php } ?></td>
									
									<?php
									}
									?>
									
								</tr>
								<tr>
									<td class="first-column"><?php echo $appSession->getLang()->find("Delete");?></td>
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
										$create_uid = $productList->getString($i, "create_uid");
										$rel_price_id = $productList->getString($i, "rel_price_id");
										$commercial_name = $productList->getString($i, "commercial_name");
									?>
									<td ><a class="pro-remove" href="<?php echo URL;?>compare/?ac=del&id=<?php echo $rel_price_id;?>"><i class="fa fa-trash-o"></i></a></td>
									<?php
									}
									?>
									
								</tr>
								<tr>
									<td class="first-column"><?php echo $appSession->getLang()->find("Rating");?></td>
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
										$create_uid = $productList->getString($i, "create_uid");
										$rel_price_id = $productList->getString($i, "rel_price_id");
										$commercial_name = $productList->getString($i, "commercial_name");
									?>
									<td class="pro-ratting">
										<a href="#"><i class="ion-star"></i></a>
										<a href="#"><i class="ion-star"></i></a>
										<a href="#"><i class="ion-star"></i></a>
										<a href="#"><i class="ion-star"></i></a>
										<a href="#"><i class="ion-star"></i></a>
									</td>
									<?php
									}
									?>
									
								</tr>
							</tbody>
						</table>
					</div>
                </div>
            </div>
        </div>
    </section>