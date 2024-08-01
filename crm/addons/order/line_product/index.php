<?php
require_once(ABSPATH . 'api/Product.php');

$product = new Product($appSession);
$dt_category = $product->categoryList();
validUser($appSession);
$msg = $appSession->getTier()->createMessage();
$sale_id = '';
if (isset($_REQUEST['sale_id'])) {
	$sale_id = $_REQUEST['sale_id'];
}
$category_id = '';
if (isset($_REQUEST['category_id'])) {
	$category_id = $_REQUEST['category_id'];
}
$search = '';
if (isset($_REQUEST['search'])) {
	$search = $_REQUEST['search'];
}
$customer_category_id = '';
if (isset($_REQUEST['customer_category_id'])) {
	$customer_category_id = $_REQUEST['customer_category_id'];
}
$customer_id = '';
if (isset($_REQUEST['customer_id'])) {
	$customer_id = $_REQUEST['customer_id'];
}



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
$ids = "";
$ids = findChild($dt_category, $category_id);
if ($ids != "") {
	$ids = $ids . "," . $category_id;
} else {
	$ids = $category_id;
}
$arr = $appSession->getTool()->split($ids, ',');
$ids = "";
for ($i = 0; $i < count($arr); $i++) {
	if ($ids != "") {
		$ids = $ids . " OR ";
	}
	$ids = $ids . " d1.category_id='" . $arr[$i] . "'";
}

$productList = null;
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
$sql = $sql . " WHERE d1.status =0 AND d1.publish = 1 AND d1.company_id='" . $appSession->getConfig()->getProperty("company_id") . "'";

if ($search != "") {
	$search = trim($search);
	$sql = $sql . " AND (" . $appSession->getTier()->buildSearch(["d1.name", "lg.description"], $search) . ")";
} else {
	$sql = $sql . " AND (" . $ids . ")";
}
$sql1 = $sql . " AND d5.type='PRODUCT'";
$sql1 = $sql1 . " ORDER BY d1.sequence ASC, d5.sequence ASC";
$msg->add("query", $sql1);
$productList = $appSession->getTier()->getTable($msg);
$product = new Product($appSession);
$productList = $product->countProduct($productList);
$sql = "SELECT d1.rel_id, d1.quantity";
$sql = $sql . " FROM sale_product_local d1";
$sql = $sql . " LEFT OUTER JOIN sale_local d2 ON(d1.sale_id = d2.id)";
$sql = $sql . " WHERE d1.status =0 AND d2.id='" . $sale_id . "'";

$msg = $appSession->getTier()->createMessage();
$msg->add("query", $sql);
$values = $appSession->getTier()->getArray($msg);


?>
<?php
for ($i = 0; $i < $productList->getRowCount(); $i++) {

	$product_id = $productList->getString($i, "id");
	$code = $productList->getString($i, "code");
	$document_id = $productList->getString($i, "document_id");
	$name = $productList->getString($i, "name");

	if ($name == "") {
		$name = $productList->getString($i, "name");
	}
	$unit_price = $productList->getFloat($i, "unit_price");
	$old_price = $productList->getFloat($i, "old_price");
	$unit_id = $productList->getString($i, "unit_id");
	$currency_id = $productList->getString($i, "currency_id");
	$unit_name = $productList->getString($i, "unit_name");
	$rel_id = $productList->getString($i, "price_id");
	if ($rel_id == "") {
		$rel_id = $product_id;
	}
	$attribute_category_name = $productList->getString($i, "attribute_category_name");
	$attribute_id = $productList->getString($i, "attribute_id");
	$attribute_name = $productList->getString($i, "attribute_name");
	$attribute_code = $productList->getString($i, "attribute_code");
	$unit_in_stock = $productList->getFloat($i, "unit_in_stock");
	$second_unit_id = $productList->getString($i, "second_unit_id");
	$factor = $productList->getString($i, "factor");
	$company_id = $productList->getString($i, "company_id");
	$commercial_name = $productList->getString($i, "commercial_name");
	$type_id = $productList->getString($i, "type_id");
	if ($commercial_name == "") {
		$commercial_name = $productList->getString($i, "company_name");
	}
	if ($factor == "" || $factor == "0") {
		$factor = "1";
	}
	$quantity = 0;
	for ($j = 0; $j < count($values); $j++) {
		if ($rel_id == $values[$j][0]) {
			$quantity = $appSession->getTool()->toDouble($values[$j][1]);
		}
	}

?>

	<div class="col col-6 col-sm-6 col-md-6 col-lg-4 col-xl-3">
		<div class="item animate__animated animate__zoomIn wow">
			<div class="item_img center_img">
				<?php if ($document_id != "") { ?>
					<img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&w=270&h=230" class="crop_img">
				<?php
				}
				?>
			</div>
			<div class="text_box">


				<h2><?php echo $code; ?>. <?php echo $name; ?></h2>
				<div class="row">
					<div class="col-6">
						<?php echo $appSession->getCurrency()->format($currency_id, $unit_price); ?>
					</div>
					<div class="col-6 text-center">
						<h3 class="d-flex">
							<a href="javascript:quantityChanged('<?php echo $product_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', '<?php echo $type_id; ?>', '<?php echo $rel_id; ?>', <?php echo ($quantity - 1); ?>,'<?php echo $currency_id; ?>',  <?php echo $unit_price; ?>)"><i class="zmdi zmdi-minus" style="background: var(--primary); color: var(--white); min-width: 20px; height: 20px; border-radius: 50%; text-align: center; line-height: 20px; font-size: 1rem; font-weight: 600;"></i></a>
							<a href="javascript:updateQuantity('<?php echo $product_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', '<?php echo $type_id; ?>', '<?php echo $rel_id; ?>', <?php echo ($quantity - 1); ?>,'<?php echo $currency_id; ?>',  <?php echo $unit_price; ?>)"><strong style="min-width: 33px;padding: 0 4px; font-weight: 600;font-size: 1rem;"><?php echo $quantity; ?></strong></a>
							<a href="javascript:quantityChanged('<?php echo $product_id; ?>', '<?php echo $unit_id; ?>', '<?php echo $attribute_id; ?>', '<?php echo $type_id; ?>', '<?php echo $rel_id; ?>', <?php echo ($quantity + 1); ?>,'<?php echo $currency_id; ?>',  <?php echo $unit_price; ?>)"><i class="zmdi zmdi-plus" style="background: var(--primary); color: var(--white); min-width: 20px; height: 20px; border-radius: 50%; text-align: center; line-height: 20px; font-size: 1rem; font-weight: 600;"></i></a>
						</h3>
					</div>
				</div>

			</div>
		</div>
	</div>
	</a>
<?php
}
?>