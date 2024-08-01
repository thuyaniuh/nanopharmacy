<?php
require_once(ABSPATH . 'api/Product.php');
include( ABSPATH .'app/lang/'.$appSession->getConfig()->getProperty("lang_id").'.php');
foreach($langs as $key => $item)
{
	$appSession->getLang()->setProperty($key, $item);				
}
$msg = $appSession->getTier()->createMessage();

$product = new Product($appSession);

$price_id = "";
if(isset($_REQUEST['id']))
{
	$price_id = $_REQUEST['id'];
}

$sql = "SELECT d1.id, d1.category_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d3.unit_price, d3.old_price, d4.name AS unit_name, d5.code AS currency_code, d3.unit_id, d3.currency_id, d3.attribute_id, d7.name AS attribute_name, d8.name attribute_category_name, (SELECT SUM(product_count.quantity) FROM product_count WHERE product_count.status =0 AND product_count.rel_id = d3.id) AS unit_in_stock, d7.quantity AS factor, d7.unit_id AS second_unit_id";
$sql = $sql . " FROM product d1";
$sql = $sql . " LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1)";
$sql = $sql . " LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='" . $appSession->getConfig()->getProperty("lang_id") . "' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0) LEFT OUTER JOIN product_price d3 ON(d1.id = d3.product_id AND d3.publish=1) LEFT OUTER JOIN product_unit d4 ON(d3.unit_id = d4.id) LEFT OUTER JOIN res_currency d5 ON(d3.currency_id = d5.id)";
$sql = $sql." LEFT OUTER JOIN attribute d7 ON(d3.attribute_id = d7.id)";
$sql = $sql." LEFT OUTER JOIN attribute_category d8 ON(d7.category_id = d8.id)";
$sql = $sql . " WHERE (d3.id='" . $price_id . "' OR d1.id='".$price_id."')";



$msg->add("query", $sql);
$productList = $appSession->getTier()->getTable($msg);
$product_id = "";
if($productList->getRowCount()>0)
{
	$product_id = $productList->getString(0, "id");
}

$sql = "SELECT d1.document_id, d2.name FROM document_rel d1 LEFT OUTER JOIN document d2 ON(d1.document_id = d2.id) WHERE d1.status =0 AND d1.publish=1 AND d1.rel_id='" . $product_id . "'";
$sql = $sql . " ORDER BY d1.create_date ASC";

$msg->add("query", $sql);
$photos = $appSession->getTier()->getArray($msg);

if ($productList->getRowCount() > 0) {
    $product_product_id = $productList->getString(0, "id");
    $product_code = $productList->getString(0, "code");
    $product_product_name = $productList->getString(0, "name_lg");

    if ($product_product_name == "") {
        $product_product_name = $productList->getString(0, "name");
    }
    $product_unit_price = $productList->getFloat(0, "unit_price");
	if($product_unit_price == "")
	{
		$product_unit_price = 0;
	}
    $product_old_price = $productList->getFloat(0, "old_price");
    $product_unit_id = $productList->getFloat(0, "unit_id");
    $product_unit_name = $productList->getString(0, "unit_name");
    $product_currency_id = $productList->getFloat(0, "currency_id");
	$product_attribute_category_name = $productList->getString(0, "attribute_category_name");
	$product_attribute_id = $productList->getString(0, "attribute_id");
	$product_attribute_name = $productList->getString(0, "attribute_name");
	$product_attribute_code = $productList->getString(0, "attribute_code");
	$product_unit_in_stock = $productList->getFloat(0, "unit_in_stock");	
	$product_document_id = $productList->getString(0, "document_id");
	$product_second_unit_id = $productList->getString(0, "second_unit_id");
	$product_factor = $productList->getString(0, "factor");	
	$product_company_id = $productList->getString(0, "company_id");
	$description = $productList->getString(0, "description");
	$currency_id = $productList->getString(0, "currency_id");
	 $product_type_name = $productList->getString(0, "type_name");
	if($product_factor == "" || $product_factor == "0")
	{
		$product_factor = "1";
	}
							

?>
<div class="row g-sm-4 g-2">
	<div class="col-lg-6">
		<div class="slider-image">
			<img src="<?php echo URL; ?>document/?id=<?php echo $product_document_id; ?>" class="img-fluid blur-up lazyload"
				alt="">
		</div>
	</div>

	<div class="col-lg-6">
		<div class="right-sidebar-modal">
			<h4 class="title-name"><?php echo  $product_product_name;?></h4>
			<h4 class="price"><?php echo $appSession->getCurrency()->format($currency_id, $product_unit_price);?></h4>
			<div class="product-rating">
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
				<span class="ms-2">8 Reviews</span>
				<span class="ms-2 text-danger">6 sold in last 16 hours</span>
			</div>

			<div class="product-detail">
				<h4>Product Details :</h4>
				<p><?php echo $description;?></p>
			</div>

			<ul class="brand-list">
				<li>
					<div class="brand-box">
						<h5><?php echo $product_attribute_category_name;?>:</h5>
						<h6><?php echo $product_attribute_name;?></h6>
					</div>
				</li>

				<li>
					<div class="brand-box">
						<h5>Product Code:</h5>
						<h6><?php echo $product_code;?></h6>
					</div>
				</li>

				<li>
					<div class="brand-box">
						<h5>Product Type:</h5>
						<h6><?php echo $product_type_name;?></h6>
					</div>
				</li>
			</ul>

		

			<div class="modal-button">
				<?php if($product_unit_in_stock>0)
				{
				?>
				<button onclick="addingProduct('card')"
					class="btn btn-md add-cart-button icon"><?php echo $appSession->getLang()->find("Add to card");?></button>
					<?php
					}
					?>
				<button onclick="location.href = '<?php echo URL;?>product/?id=';"
					class="btn theme-bg-color view-button icon text-white fw-bold btn-md">
					View More Details</button>
			</div>
		</div>
	</div>
</div>
<?php
}
?>